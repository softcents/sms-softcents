<?php

namespace App\Services;

use App\Models\Recharge;
use App\Models\Sms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfitLossService
{
    /**
     * Default wholesale cost rates.
     */
    const DEFAULT_GATEWAY_NON_MASKING = 0.24;
    const DEFAULT_GATEWAY_MASKING = 0.45;
    const DEFAULT_SP_NON_MASKING = 0.28;
    const DEFAULT_SP_MASKING = 0.55;
    const DEFAULT_SSP_NON_MASKING = 0.32;
    const DEFAULT_SSP_MASKING = 0.62;

    /**
     * Build filtered base query.
     */
    public static function buildFinancialQuery(Request $request, ?array $allowedUserIds = null)
    {
        $query = Sms::query();

        if ($allowedUserIds !== null) {
            $query->whereIn('sms.user_id', $allowedUserIds);
        }

        if ($request->filled('user_id')) {
            $query->where('sms.user_id', $request->user_id);
        }

        // Date range filtering
        $datePreset = $request->get('date_preset', 'this_month');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        if ($fromDate && $toDate) {
            $query->whereBetween('sms.created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay(),
            ]);
        } elseif ($datePreset) {
            switch ($datePreset) {
                case 'today':
                    $query->where('sms.created_at', '>=', Carbon::today()->startOfDay());
                    break;
                case 'yesterday':
                    $query->whereBetween('sms.created_at', [
                        Carbon::yesterday()->startOfDay(),
                        Carbon::yesterday()->endOfDay(),
                    ]);
                    break;
                case 'last_7_days':
                    $query->where('sms.created_at', '>=', Carbon::now()->subDays(7)->startOfDay());
                    break;
                case 'this_month':
                    $query->where('sms.created_at', '>=', Carbon::now()->startOfMonth());
                    break;
                case 'last_month':
                    $query->whereBetween('sms.created_at', [
                        Carbon::now()->subMonth()->startOfMonth(),
                        Carbon::now()->subMonth()->endOfMonth(),
                    ]);
                    break;
                case 'all_time':
                    break;
            }
        }

        // Operator filtering
        if ($request->filled('operator')) {
            $op = strtolower($request->operator);
            if ($op === 'gp') {
                $query->whereRaw("sms.number ~ '^(88|)01[37]'");
            } elseif ($op === 'bl') {
                $query->whereRaw("sms.number ~ '^(88|)01[49]'");
            } elseif ($op === 'robi') {
                $query->whereRaw("sms.number ~ '^(88|)01[68]'");
            } elseif ($op === 'teletalk') {
                $query->whereRaw("sms.number ~ '^(88|)015'");
            }
        }

        // Masking / Type filtering
        if ($request->filled('type')) {
            $type = strtolower($request->type);
            $query->whereHas('senderid', function ($q) use ($type) {
                if ($type === 'masking') {
                    $q->where('type', 'like', '%masking%')->where('type', 'not like', '%non%');
                } elseif ($type === 'non-masking') {
                    $q->where('type', 'like', '%non-masking%');
                }
            });
        }

        return $query;
    }

    /**
     * Get SQL expression for buying cost based on role.
     */
    public static function getCostSqlExpression(string $role, ?User $currentUser = null): string
    {
        if ($role === 'superadmin' || $role === 'admin') {
            $gwCostNonMasking = self::DEFAULT_GATEWAY_NON_MASKING;
            $gwCostMasking = self::DEFAULT_GATEWAY_MASKING;
            return "CASE 
                WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN COALESCE(gw.masking_rate, {$gwCostMasking}) * COALESCE(sms.total_sms, 1)
                ELSE COALESCE(gw.non_masking_rate, {$gwCostNonMasking}) * COALESCE(sms.total_sms, 1)
            END";
        } elseif ($role === 'sp' && $currentUser) {
            $spNonMasking = (float) ($currentUser->non_masking_rate ?: self::DEFAULT_SP_NON_MASKING);
            $spMasking = (float) ($currentUser->masking_rate ?: self::DEFAULT_SP_MASKING);
            return "CASE 
                WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN {$spMasking} * COALESCE(sms.total_sms, 1)
                ELSE {$spNonMasking} * COALESCE(sms.total_sms, 1)
            END";
        } elseif ($role === 'ssp' && $currentUser) {
            $sspNonMasking = (float) ($currentUser->non_masking_rate ?: self::DEFAULT_SSP_NON_MASKING);
            $sspMasking = (float) ($currentUser->masking_rate ?: self::DEFAULT_SSP_MASKING);
            return "CASE 
                WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN {$sspMasking} * COALESCE(sms.total_sms, 1)
                ELSE {$sspNonMasking} * COALESCE(sms.total_sms, 1)
            END";
        }

        return "0";
    }

    /**
     * Compute comprehensive Profit & Loss summary metrics.
     */
    public static function getSummaryMetrics($baseQuery, string $role, ?User $currentUser = null, ?array $allowedUserIds = null): array
    {
        $costSql = self::getCostSqlExpression($role, $currentUser);

        $data = (clone $baseQuery)
            ->leftJoin('senderids as s', 'sms.senderid_id', '=', 's.id')
            ->leftJoin('smsgateways as gw', 'sms.smsgateway_id', '=', 'gw.id')
            ->selectRaw("
                COUNT(*) as total_sms_records,
                COALESCE(SUM(sms.total_sms), COUNT(*)) as total_sms_parts,
                COALESCE(SUM(sms.charge), 0) as total_revenue,
                COALESCE(SUM({$costSql}), 0) as total_cost,
                
                SUM(CASE WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN 1 ELSE 0 END) as masking_count,
                SUM(CASE WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN sms.charge ELSE 0 END) as masking_revenue,
                SUM(CASE WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN {$costSql} ELSE 0 END) as masking_cost,

                SUM(CASE WHEN s.type NOT LIKE '%Masking%' OR s.type LIKE '%Non%' THEN 1 ELSE 0 END) as non_masking_count,
                SUM(CASE WHEN s.type NOT LIKE '%Masking%' OR s.type LIKE '%Non%' THEN sms.charge ELSE 0 END) as non_masking_revenue,
                SUM(CASE WHEN s.type NOT LIKE '%Masking%' OR s.type LIKE '%Non%' THEN {$costSql} ELSE 0 END) as non_masking_cost,

                COUNT(DISTINCT sms.user_id) as active_senders_count
            ")
            ->first();

        $totalSms = (int) ($data->total_sms_parts ?? 0);
        $totalRevenue = (float) ($data->total_revenue ?? 0);
        $totalCost = (float) ($data->total_cost ?? 0);
        $netProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 2) : 0;

        $maskingRevenue = (float) ($data->masking_revenue ?? 0);
        $maskingCost = (float) ($data->masking_cost ?? 0);
        $maskingProfit = $maskingRevenue - $maskingCost;

        $nonMaskingRevenue = (float) ($data->non_masking_revenue ?? 0);
        $nonMaskingCost = (float) ($data->non_masking_cost ?? 0);
        $nonMaskingProfit = $nonMaskingRevenue - $nonMaskingCost;

        // Recharges in period
        $rechargeQuery = Recharge::where('status', 'approved');
        if ($allowedUserIds !== null) {
            $rechargeQuery->whereIn('user_id', $allowedUserIds);
        }
        $totalRecharges = (float) $rechargeQuery->sum('amount');

        // Current wallet balance of users
        $usersQuery = User::query();
        if ($allowedUserIds !== null) {
            $usersQuery->whereIn('id', $allowedUserIds);
        } else {
            $usersQuery->whereNotIn('role', ['admin', 'superadmin']);
        }
        $walletLiability = (float) $usersQuery->sum('balance');

        return [
            'total_sms' => $totalSms,
            'total_records' => (int) ($data->total_sms_records ?? 0),
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'net_profit' => $netProfit,
            'profit_margin' => $profitMargin,
            'masking_count' => (int) ($data->masking_count ?? 0),
            'masking_revenue' => $maskingRevenue,
            'masking_cost' => $maskingCost,
            'masking_profit' => $maskingProfit,
            'non_masking_count' => (int) ($data->non_masking_count ?? 0),
            'non_masking_revenue' => $nonMaskingRevenue,
            'non_masking_cost' => $nonMaskingCost,
            'non_masking_profit' => $nonMaskingProfit,
            'active_senders_count' => (int) ($data->active_senders_count ?? 0),
            'total_recharges' => $totalRecharges,
            'wallet_liability' => $walletLiability,
            'avg_revenue_per_sms' => $totalSms > 0 ? round($totalRevenue / $totalSms, 4) : 0,
            'avg_profit_per_sms' => $totalSms > 0 ? round($netProfit / $totalSms, 4) : 0,
        ];
    }

    /**
     * Compute User / Downline-wise Profit Breakdown table.
     */
    public static function getUserProfitBreakdown($baseQuery, string $role, ?User $currentUser = null, int $limit = 20): array
    {
        $costSql = self::getCostSqlExpression($role, $currentUser);

        $results = (clone $baseQuery)
            ->leftJoin('senderids as s', 'sms.senderid_id', '=', 's.id')
            ->leftJoin('smsgateways as gw', 'sms.smsgateway_id', '=', 'gw.id')
            ->join('users as u', 'sms.user_id', '=', 'u.id')
            ->selectRaw("
                u.id as user_id,
                u.name as user_name,
                u.username,
                u.role,
                u.balance,
                COUNT(*) as sms_count,
                COALESCE(SUM(sms.charge), 0) as revenue,
                COALESCE(SUM({$costSql}), 0) as cost,
                SUM(CASE WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN 1 ELSE 0 END) as masking_count,
                SUM(CASE WHEN s.type NOT LIKE '%Masking%' OR s.type LIKE '%Non%' THEN 1 ELSE 0 END) as non_masking_count
            ")
            ->groupBy('u.id', 'u.name', 'u.username', 'u.role', 'u.balance')
            ->orderByRaw("(COALESCE(SUM(sms.charge), 0) - COALESCE(SUM({$costSql}), 0)) DESC")
            ->limit($limit)
            ->get();

        $list = [];
        foreach ($results as $r) {
            $rev = (float) $r->revenue;
            $c = (float) $r->cost;
            $profit = $rev - $c;
            $margin = $rev > 0 ? round(($profit / $rev) * 100, 1) : 0;

            $list[] = [
                'user_id' => $r->user_id,
                'name' => $r->user_name,
                'username' => $r->username,
                'role' => $r->role,
                'balance' => (float) $r->balance,
                'sms_count' => (int) $r->sms_count,
                'revenue' => $rev,
                'cost' => $c,
                'profit' => $profit,
                'margin' => $margin,
                'masking_count' => (int) $r->masking_count,
                'non_masking_count' => (int) $r->non_masking_count,
            ];
        }

        return $list;
    }

    /**
     * Compute Mobile Operator Profit & Loss breakdown.
     */
    public static function getOperatorProfitBreakdown($baseQuery, string $role, ?User $currentUser = null): array
    {
        $costSql = self::getCostSqlExpression($role, $currentUser);

        $results = (clone $baseQuery)
            ->leftJoin('senderids as s', 'sms.senderid_id', '=', 's.id')
            ->leftJoin('smsgateways as gw', 'sms.smsgateway_id', '=', 'gw.id')
            ->selectRaw("
                CASE 
                    WHEN sms.number ~ '^(88|)01[37]' THEN 'Grameenphone'
                    WHEN sms.number ~ '^(88|)01[49]' THEN 'Banglalink'
                    WHEN sms.number ~ '^(88|)01[68]' THEN 'Robi / Airtel'
                    WHEN sms.number ~ '^(88|)015' THEN 'Teletalk'
                    ELSE 'Other / Intl'
                END as operator,
                COUNT(*) as total_sms,
                COALESCE(SUM(sms.charge), 0) as revenue,
                COALESCE(SUM({$costSql}), 0) as cost
            ")
            ->groupByRaw('1')
            ->orderByRaw("COUNT(*) DESC")
            ->get();

        $colorMap = [
            'Grameenphone' => '#00a3e0',
            'Banglalink' => '#f7931e',
            'Robi / Airtel' => '#e4002b',
            'Teletalk' => '#008855',
            'Other / Intl' => '#6c757d',
        ];

        $list = [];
        $totalAll = $results->sum('total_sms') ?: 1;

        foreach ($results as $row) {
            $rev = (float) $row->revenue;
            $c = (float) $row->cost;
            $p = $rev - $c;
            $m = $rev > 0 ? round(($p / $rev) * 100, 1) : 0;
            $op = $row->operator;

            $list[] = [
                'name' => $op,
                'total_sms' => (int) $row->total_sms,
                'revenue' => $rev,
                'cost' => $c,
                'profit' => $p,
                'margin' => $m,
                'share' => round(((int) $row->total_sms / $totalAll) * 100, 1),
                'color' => $colorMap[$op] ?? '#6c757d',
            ];
        }

        return $list;
    }

    /**
     * Compute daily financial trend for Chart.js.
     */
    public static function getDailyFinancialTrend($baseQuery, string $role, ?User $currentUser = null, int $days = 15): array
    {
        $costSql = self::getCostSqlExpression($role, $currentUser);

        $results = (clone $baseQuery)
            ->leftJoin('senderids as s', 'sms.senderid_id', '=', 's.id')
            ->leftJoin('smsgateways as gw', 'sms.smsgateway_id', '=', 'gw.id')
            ->selectRaw("
                TO_CHAR(sms.created_at, 'YYYY-MM-DD') as date_str,
                COUNT(*) as total_sms,
                COALESCE(SUM(sms.charge), 0) as revenue,
                COALESCE(SUM({$costSql}), 0) as cost
            ")
            ->groupByRaw('1')
            ->orderBy('date_str', 'ASC')
            ->limit($days)
            ->get();

        $labels = [];
        $revenues = [];
        $costs = [];
        $profits = [];

        foreach ($results as $r) {
            $labels[] = Carbon::parse($r->date_str)->format('d M');
            $rev = round((float) $r->revenue, 2);
            $c = round((float) $r->cost, 2);
            $revenues[] = $rev;
            $costs[] = $c;
            $profits[] = round($rev - $c, 2);
        }

        return [
            'labels' => $labels,
            'revenues' => $revenues,
            'costs' => $costs,
            'profits' => $profits,
        ];
    }

    /**
     * Customer Expense & Usage Summary.
     */
    public static function getCustomerExpenseSummary(User $customer, Request $request): array
    {
        $baseQuery = self::buildFinancialQuery($request, [$customer->id]);

        $raw = (clone $baseQuery)
            ->leftJoin('senderids as s', 'sms.senderid_id', '=', 's.id')
            ->selectRaw("
                COUNT(*) as total_sms,
                COALESCE(SUM(sms.charge), 0) as total_spent,
                SUM(CASE WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN 1 ELSE 0 END) as masking_count,
                SUM(CASE WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN sms.charge ELSE 0 END) as masking_spent,
                SUM(CASE WHEN s.type NOT LIKE '%Masking%' OR s.type LIKE '%Non%' THEN 1 ELSE 0 END) as non_masking_count,
                SUM(CASE WHEN s.type NOT LIKE '%Masking%' OR s.type LIKE '%Non%' THEN sms.charge ELSE 0 END) as non_masking_spent,
                COUNT(DISTINCT sms.number) as unique_numbers
            ")
            ->first();

        $totalSms = (int) ($raw->total_sms ?? 0);
        $totalSpent = (float) ($raw->total_spent ?? 0);

        $totalRecharge = (float) Recharge::where('user_id', $customer->id)->where('status', 'approved')->sum('amount');

        return [
            'total_sms' => $totalSms,
            'total_spent' => $totalSpent,
            'masking_count' => (int) ($raw->masking_count ?? 0),
            'masking_spent' => (float) ($raw->masking_spent ?? 0),
            'non_masking_count' => (int) ($raw->non_masking_count ?? 0),
            'non_masking_spent' => (float) ($raw->non_masking_spent ?? 0),
            'unique_numbers' => (int) ($raw->unique_numbers ?? 0),
            'current_balance' => (float) $customer->balance,
            'total_recharge' => $totalRecharge,
            'avg_cost_per_sms' => $totalSms > 0 ? round($totalSpent / $totalSms, 4) : 0,
        ];
    }

    /**
     * Stream CSV Export of Profit & Loss report.
     */
    public static function streamProfitLossCsv($baseQuery, string $role, ?User $currentUser = null, string $filename = 'profit_loss_report.csv'): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($baseQuery, $role, $currentUser) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'SMS ID',
                'Date & Time',
                'User (@username)',
                'SMS Gateway',
                'Sender ID',
                'Type',
                'Recipient',
                'Operator',
                'Selling Price (BDT)',
                'Gateway Buying Cost (BDT)',
                'Net Profit (BDT)',
                'Margin (%)'
            ]);

            $costSql = self::getCostSqlExpression($role, $currentUser);

            $baseQuery->leftJoin('senderids as s', 'sms.senderid_id', '=', 's.id')
                ->leftJoin('smsgateways as gw', 'sms.smsgateway_id', '=', 'gw.id')
                ->join('users as u', 'sms.user_id', '=', 'u.id')
                ->selectRaw("
                    sms.id,
                    sms.created_at,
                    u.username,
                    u.name as user_name,
                    gw.name as gateway_name,
                    s.sender as sender_name,
                    s.type as sender_type,
                    sms.number,
                    sms.charge as revenue,
                    {$costSql} as cost
                ")
                ->chunk(1000, function ($batch) use ($handle) {
                    foreach ($batch as $row) {
                        $rev = (float) $row->revenue;
                        $c = (float) $row->cost;
                        $profit = $rev - $c;
                        $margin = $rev > 0 ? round(($profit / $rev) * 100, 1) : 0;

                        fputcsv($handle, [
                            $row->id,
                            $row->created_at->format('Y-m-d H:i:s'),
                            $row->username ?? $row->user_name,
                            $row->gateway_name ?? 'Gateway',
                            $row->sender_name ?? 'N/A',
                            $row->sender_type ?? 'Non-Masking',
                            $row->number,
                            ReportAnalyticsService::detectOperator($row->number ?? ''),
                            number_format($rev, 4, '.', ''),
                            number_format($c, 4, '.', ''),
                            number_format($profit, 4, '.', ''),
                            $margin . '%',
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Compute SMS Gateway-wise Profit & Loss breakdown.
     */
    public static function getGatewayProfitBreakdown($baseQuery): array
    {
        $defNM = self::DEFAULT_GATEWAY_NON_MASKING;
        $defM = self::DEFAULT_GATEWAY_MASKING;
        $costSql = "CASE 
            WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN COALESCE(gw.masking_rate, {$defM}) * COALESCE(sms.total_sms, 1)
            ELSE COALESCE(gw.non_masking_rate, {$defNM}) * COALESCE(sms.total_sms, 1)
        END";

        $results = (clone $baseQuery)
            ->leftJoin('senderids as s', 'sms.senderid_id', '=', 's.id')
            ->leftJoin('smsgateways as gw', 'sms.smsgateway_id', '=', 'gw.id')
            ->selectRaw("
                COALESCE(gw.id, 0) as gateway_id,
                COALESCE(gw.name, 'Unassigned Gateway') as gateway_name,
                gw.driver,
                COALESCE(gw.non_masking_rate, {$defNM}) as non_masking_rate,
                COALESCE(gw.masking_rate, {$defM}) as masking_rate,
                COUNT(*) as total_records,
                COALESCE(SUM(sms.total_sms), COUNT(*)) as total_sms,
                COALESCE(SUM(sms.charge), 0) as revenue,
                COALESCE(SUM({$costSql}), 0) as cost,
                SUM(CASE WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN 1 ELSE 0 END) as masking_count,
                SUM(CASE WHEN s.type NOT LIKE '%Masking%' OR s.type LIKE '%Non%' THEN 1 ELSE 0 END) as non_masking_count
            ")
            ->groupBy('gw.id', 'gw.name', 'gw.driver', 'gw.non_masking_rate', 'gw.masking_rate')
            ->orderByRaw("COUNT(*) DESC")
            ->get();

        $list = [];
        foreach ($results as $row) {
            $rev = (float) $row->revenue;
            $cost = (float) $row->cost;
            $profit = $rev - $cost;
            $margin = $rev > 0 ? round(($profit / $rev) * 100, 1) : 0;

            $list[] = [
                'id' => $row->gateway_id,
                'name' => $row->gateway_name,
                'driver' => $row->driver,
                'non_masking_rate' => (float) $row->non_masking_rate,
                'masking_rate' => (float) $row->masking_rate,
                'total_sms' => (int) $row->total_sms,
                'masking_count' => (int) $row->masking_count,
                'non_masking_count' => (int) $row->non_masking_count,
                'revenue' => $rev,
                'cost' => $cost,
                'profit' => $profit,
                'margin' => $margin,
            ];
        }

        return $list;
    }

    /**
     * Stream CSV Export of Customer Expense report.
     */
    public static function streamCustomerExpenseCsv($baseQuery, string $filename = 'sms_expense_report.csv'): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($baseQuery) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'SMS ID',
                'Date & Time',
                'Sender ID',
                'Type',
                'Recipient',
                'Operator',
                'SMS Parts',
                'Cost / Charge (BDT)',
                'Status'
            ]);

            $baseQuery->leftJoin('senderids as s', 'sms.senderid_id', '=', 's.id')
                ->select([
                    'sms.id',
                    'sms.created_at',
                    's.sender as sender_name',
                    's.type as sender_type',
                    'sms.number',
                    'sms.total_sms',
                    'sms.charge',
                    'sms.status',
                ])
                ->chunk(1000, function ($batch) use ($handle) {
                    foreach ($batch as $row) {
                        fputcsv($handle, [
                            $row->id,
                            $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : 'N/A',
                            $row->sender_name ?? 'N/A',
                            $row->sender_type ?? 'Non-Masking',
                            $row->number,
                            ReportAnalyticsService::detectOperator($row->number ?? ''),
                            $row->total_sms ?? 1,
                            number_format((float) $row->charge, 4, '.', ''),
                            $row->status ?? 'success',
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
