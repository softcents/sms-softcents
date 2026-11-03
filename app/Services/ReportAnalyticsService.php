<?php

namespace App\Services;

use App\Models\Sms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportAnalyticsService
{
    /**
     * Build base query with all security scopes and request filters applied.
     */
    public static function buildFilteredQuery(Request $request, ?array $allowedUserIds = null)
    {
        $query = Sms::query();

        // 1. Role Scope (Super Admin vs Reseller SP vs SSP vs Customer)
        if ($allowedUserIds !== null) {
            $query->whereIn('sms.user_id', $allowedUserIds);
        }

        // 2. Specific User Filter
        if ($request->filled('user_id')) {
            $query->where('sms.user_id', $request->user_id);
        }

        // 3. Date Range Filter
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
                    // No date constraint
                    break;
            }
        }

        // 4. Status Filter
        if ($request->filled('status')) {
            $status = strtolower($request->status);
            if ($status === 'delivered' || $status === 'success') {
                $query->whereIn('sms.status', ['success', 'delivered']);
            } elseif ($status === 'failed' || $status === 'undelivered') {
                $query->whereIn('sms.status', ['failed', 'undelivered']);
            } elseif ($status === 'pending') {
                $query->where('sms.status', 'pending');
            } else {
                $query->where('sms.status', $status);
            }
        }

        // 5. Operator Filter
        if ($request->filled('operator')) {
            $op = strtolower($request->operator);
            if ($op === 'gp' || $op === 'grameenphone') {
                $query->whereRaw("sms.number ~ '^(88|)01[37]'");
            } elseif ($op === 'bl' || $op === 'banglalink') {
                $query->whereRaw("sms.number ~ '^(88|)01[49]'");
            } elseif ($op === 'robi' || $op === 'airtel') {
                $query->whereRaw("sms.number ~ '^(88|)01[68]'");
            } elseif ($op === 'teletalk') {
                $query->whereRaw("sms.number ~ '^(88|)015'");
            } elseif ($op === 'other') {
                $query->whereRaw("sms.number !~ '^(88|)01[3-9]'");
            }
        }

        // 6. Sender ID Filter
        if ($request->filled('senderid_id')) {
            $query->where('sms.senderid_id', $request->senderid_id);
        }

        // 7. Route / Masking Filter
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

        // 8. Keyword / Number Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($sub) use ($search) {
                $sub->where('sms.number', 'like', "%{$search}%")
                    ->orWhere('sms.contents', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                          ->orWhere('username', 'like', "%{$search}%");
                    })
                    ->orWhereHas('senderid', function ($s) use ($search) {
                        $s->where('sender', 'like', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    /**
     * Compute summary KPIs for dashboard cards.
     */
    public static function getSummaryKpis($baseQuery)
    {
        $raw = (clone $baseQuery)->selectRaw("
            COUNT(*) as total_sms,
            COALESCE(SUM(charge), 0) as total_cost,
            SUM(CASE WHEN status IN ('success', 'delivered') THEN 1 ELSE 0 END) as delivered,
            SUM(CASE WHEN status IN ('failed', 'undelivered') THEN 1 ELSE 0 END) as failed,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            COUNT(DISTINCT number) as unique_numbers
        ")->first();

        $totalSms = (int) ($raw->total_sms ?? 0);
        $delivered = (int) ($raw->delivered ?? 0);
        $failed = (int) ($raw->failed ?? 0);
        $pending = (int) ($raw->pending ?? 0);
        $totalCost = (float) ($raw->total_cost ?? 0);
        $uniqueNumbers = (int) ($raw->unique_numbers ?? 0);

        $deliveryRate = $totalSms > 0 ? round(($delivered / $totalSms) * 100, 1) : 0;
        $failureRate = $totalSms > 0 ? round(($failed / $totalSms) * 100, 1) : 0;

        return [
            'total_sms' => $totalSms,
            'total_cost' => $totalCost,
            'delivered' => $delivered,
            'failed' => $failed,
            'pending' => $pending,
            'delivery_rate' => $deliveryRate,
            'failure_rate' => $failureRate,
            'unique_numbers' => $uniqueNumbers,
        ];
    }

    /**
     * Compute Telco Operator breakdown (Grameenphone, Banglalink, Robi, Teletalk, Other).
     */
    public static function getOperatorBreakdown($baseQuery)
    {
        $operatorData = (clone $baseQuery)->selectRaw("
            CASE 
                WHEN sms.number ~ '^(88|)01[37]' THEN 'Grameenphone'
                WHEN sms.number ~ '^(88|)01[49]' THEN 'Banglalink'
                WHEN sms.number ~ '^(88|)01[68]' THEN 'Robi / Airtel'
                WHEN sms.number ~ '^(88|)015' THEN 'Teletalk'
                ELSE 'Other / Intl'
            END as operator,
            COUNT(*) as total_sms,
            COALESCE(SUM(charge), 0) as total_cost,
            SUM(CASE WHEN status IN ('success', 'delivered') THEN 1 ELSE 0 END) as delivered,
            SUM(CASE WHEN status IN ('failed', 'undelivered') THEN 1 ELSE 0 END) as failed
        ")->groupByRaw('1')->orderByRaw("COUNT(*) DESC")->get();

        $colorMap = [
            'Grameenphone' => '#00a3e0',
            'Banglalink' => '#f7931e',
            'Robi / Airtel' => '#e4002b',
            'Teletalk' => '#008855',
            'Other / Intl' => '#6c757d',
        ];

        $iconMap = [
            'Grameenphone' => 'fas fa-signal text-info',
            'Banglalink' => 'fas fa-bolt text-warning',
            'Robi / Airtel' => 'fas fa-broadcast-tower text-danger',
            'Teletalk' => 'fas fa-phone text-success',
            'Other / Intl' => 'fas fa-globe text-secondary',
        ];

        $results = [];
        $totalAll = $operatorData->sum('total_sms') ?: 1;

        foreach ($operatorData as $row) {
            $total = (int) $row->total_sms;
            $deliv = (int) $row->delivered;
            $fail = (int) $row->failed;
            $opName = $row->operator;

            $results[] = [
                'name' => $opName,
                'total' => $total,
                'delivered' => $deliv,
                'failed' => $fail,
                'cost' => (float) $row->total_cost,
                'percentage' => round(($total / $totalAll) * 100, 1),
                'success_rate' => $total > 0 ? round(($deliv / $total) * 100, 1) : 0,
                'color' => $colorMap[$opName] ?? '#6c757d',
                'icon' => $iconMap[$opName] ?? 'fas fa-mobile-alt',
            ];
        }

        return $results;
    }

    /**
     * Compute daily traffic volume for Chart.js.
     */
    public static function getDailyTrend($baseQuery, $days = 15)
    {
        $trend = (clone $baseQuery)
            ->selectRaw("
                TO_CHAR(sms.created_at, 'YYYY-MM-DD') as date_str,
                COUNT(*) as total_sms,
                SUM(CASE WHEN status IN ('success', 'delivered') THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status IN ('failed', 'undelivered') THEN 1 ELSE 0 END) as failed
            ")
            ->groupByRaw('1')
            ->orderBy('date_str', 'ASC')
            ->limit($days)
            ->get();

        $labels = [];
        $totalData = [];
        $deliveredData = [];
        $failedData = [];

        foreach ($trend as $t) {
            $labels[] = Carbon::parse($t->date_str)->format('d M');
            $totalData[] = (int) $t->total_sms;
            $deliveredData[] = (int) $t->delivered;
            $failedData[] = (int) $t->failed;
        }

        return [
            'labels' => $labels,
            'total' => $totalData,
            'delivered' => $deliveredData,
            'failed' => $failedData,
        ];
    }

    /**
     * Resolve operator name from mobile number.
     */
    public static function detectOperator(string $number): string
    {
        if (preg_match('/^(88)?01[37]/', $number)) {
            return 'GP';
        } elseif (preg_match('/^(88)?01[49]/', $number)) {
            return 'BL';
        } elseif (preg_match('/^(88)?01[68]/', $number)) {
            return 'Robi';
        } elseif (preg_match('/^(88)?015/', $number)) {
            return 'Teletalk';
        }
        return 'Other';
    }

    /**
     * Stream CSV download of filtered SMS records.
     */
    public static function streamCsvExport($query, string $filename = 'sms_traffic_report.csv'): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID',
                'Date & Time',
                'Username',
                'Customer Name',
                'Sender ID',
                'Operator',
                'Recipient Number',
                'Total SMS',
                'Charge (BDT)',
                'Channel',
                'Status',
                'Server TxnId',
                'Gateway Response Code',
                'Infozillion Response',
                'Gateway TxnId',
                'Message Content'
            ]);

            $query->with(['user:id,name,username', 'senderid:id,sender', 'smsgateway:id,name'])
                ->chunk(1000, function ($batch) use ($handle) {
                    foreach ($batch as $sms) {
                        $serverTxnId = 'TXN-' . str_pad($sms->id, 8, '0', STR_PAD_LEFT);
                        $gwTxnId = $sms->mno_txnid ?: ($sms->message_id ?: ($sms->gateway_txnid ?: ('GW-' . str_pad($sms->id, 8, '0', STR_PAD_LEFT))));
                        $gwRespCode = $sms->gateway_response_code ?: ($sms->status == 'success' ? '200 (Success)' : ($sms->status == 'pending' ? '102 (Queued)' : '400 (Failed)'));
                        $infoz = $sms->infozillion_response ?: ($sms->status == 'success' ? '9000 (Accepted by Central Platform)' : (($sms->status == 'pending') ? 'Processing' : 'Failed'));

                        fputcsv($handle, [
                            $sms->id,
                            $sms->created_at->format('Y-m-d H:i:s'),
                            $sms->user->username ?? 'N/A',
                            $sms->user->name ?? 'N/A',
                            $sms->senderid->sender ?? 'N/A',
                            self::detectOperator($sms->number ?? ''),
                            $sms->number,
                            $sms->total_sms ?? 1,
                            $sms->charge ?? 0,
                            optional($sms->smsgateway)->name ?? 'Direct Gateway',
                            $sms->status,
                            $serverTxnId,
                            $gwRespCode,
                            $infoz,
                            $gwTxnId,
                            $sms->contents,
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
