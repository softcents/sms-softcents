<?php

namespace App\Http\Controllers\User;

use App\Models\Sms;
use App\Models\Group;
use App\Models\Contact;
use App\Models\Recharge;
use App\Models\Template;
use App\Models\PlanSubscribe;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todayStr = now()->format('Y-m-d');
        $messages = Sms::with('senderid:id,sender')
            ->where('user_id', auth()->id())
            ->whereDate('created_at', $todayStr)
            ->latest()
            ->limit(15)
            ->get();

        return view("users.dashboard.index", compact('messages'));
    }

    public function getDashboardData(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $filter = $request->get('filter', 'today'); // default is today
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $smsQuery = Sms::where('user_id', $userId);
        $rechargeQuery = Recharge::where('user_id', $userId)->whereStatus('approved');

        $isPgSql = \DB::connection()->getDriverName() === 'pgsql';
        $hourExpr = $isPgSql ? "TO_CHAR(created_at, 'HH24')" : "DATE_FORMAT(created_at, '%H')";
        $dateExpr = $isPgSql ? "TO_CHAR(created_at, 'YYYY-MM-DD')" : "DATE(created_at)";
        $monthExpr = $isPgSql ? "TRIM(TO_CHAR(created_at, 'Month'))" : "MONTHNAME(created_at)";

        $todayStr = now()->format('Y-m-d');
        $chartMode = 'hourly';

        if ($filter === 'today') {
            $smsQuery->whereDate('created_at', $todayStr);
            $rechargeQuery->whereDate('created_at', $todayStr);
            $dateLabel = __('Today') . ' (' . now()->format('d M Y') . ')';
            $chartMode = 'hourly';
        } elseif ($filter === 'yesterday') {
            $yesterdayStr = now()->subDay()->format('Y-m-d');
            $smsQuery->whereDate('created_at', $yesterdayStr);
            $rechargeQuery->whereDate('created_at', $yesterdayStr);
            $dateLabel = __('Yesterday') . ' (' . now()->subDay()->format('d M Y') . ')';
            $chartMode = 'hourly';
        } elseif ($filter === 'last_7_days') {
            $from = now()->subDays(6)->startOfDay();
            $smsQuery->where('created_at', '>=', $from);
            $rechargeQuery->where('created_at', '>=', $from);
            $dateLabel = __('Last 7 Days');
            $chartMode = '7_days';
        } elseif ($filter === 'this_month') {
            $from = now()->startOfMonth();
            $smsQuery->where('created_at', '>=', $from);
            $rechargeQuery->where('created_at', '>=', $from);
            $dateLabel = __('This Month') . ' (' . now()->format('F Y') . ')';
            $chartMode = 'this_month';
        } elseif ($filter === 'last_month') {
            $from = now()->subMonth()->startOfMonth();
            $to = now()->subMonth()->endOfMonth();
            $smsQuery->whereBetween('created_at', [$from, $to]);
            $rechargeQuery->whereBetween('created_at', [$from, $to]);
            $dateLabel = __('Last Month') . ' (' . now()->subMonth()->format('F Y') . ')';
            $chartMode = 'last_month';
        } elseif ($filter === 'this_year') {
            $from = now()->startOfYear();
            $smsQuery->where('created_at', '>=', $from);
            $rechargeQuery->where('created_at', '>=', $from);
            $dateLabel = __('This Year') . ' (' . now()->format('Y') . ')';
            $chartMode = 'monthly';
        } elseif ($filter === 'custom' && $startDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = $endDate ? Carbon::parse($endDate)->endOfDay() : $start->copy()->endOfDay();
            $smsQuery->whereBetween('created_at', [$start, $end]);
            $rechargeQuery->whereBetween('created_at', [$start, $end]);
            $dateLabel = $start->format('d M Y') . ($endDate && $endDate != $startDate ? ' - ' . Carbon::parse($endDate)->format('d M Y') : '');

            $diffDays = $start->diffInDays($end);
            if ($diffDays <= 1) {
                $chartMode = 'hourly';
            } elseif ($diffDays <= 31) {
                $chartMode = 'custom_daily';
            } else {
                $chartMode = 'monthly';
            }
        } elseif ($filter === 'all_time') {
            $dateLabel = __('All Time');
            $chartMode = 'monthly';
        } else {
            $smsQuery->whereDate('created_at', $todayStr);
            $rechargeQuery->whereDate('created_at', $todayStr);
            $dateLabel = __('Today') . ' (' . now()->format('d M Y') . ')';
            $chartMode = 'hourly';
        }

        // Top Counters
        $data['filter_label'] = $dateLabel;
        $data['subscription_subscribe'] = '৳' . number_format((float)($user->non_masking_rate ?? 0.39), 2) . ' / ৳' . number_format((float)($user->masking_rate ?? 0.75), 2);
        $data['recharge_amount'] = currency_format($rechargeQuery->sum('amount'));
        $data['total_sms'] = (clone $smsQuery)->count();
        $data['pending_sms'] = (clone $smsQuery)->where('status', 'pending')->count();
        $data['success_sms'] = (clone $smsQuery)->where('status', 'success')->count();
        $data['canceled_sms'] = (clone $smsQuery)->where('status', 'canceled')->count();
        $data['groups'] = Group::where('user_id', $userId)->count();
        $data['contacts'] = Contact::where('user_id', $userId)->count();
        $data['templates'] = Template::where('user_id', $userId)->count();

        // Chart 1: Line History Statistics Chart
        $chartLabels = [];
        $chartData = [];
        $chartTitle = __('SMS Statistics');

        if ($chartMode === 'hourly') {
            $hourlyCounts = (clone $smsQuery)
                ->selectRaw("{$hourExpr} as hr, COUNT(*) as cnt")
                ->groupBy('hr')
                ->pluck('cnt', 'hr');

            for ($h = 0; $h <= 23; $h++) {
                $key = sprintf('%02d', $h);
                $chartLabels[] = sprintf('%02d:00', $h);
                $chartData[] = (int)($hourlyCounts[$key] ?? 0);
            }
            $chartTitle = __('Hourly SMS Statistics') . ': ' . array_sum($chartData);
        } elseif ($chartMode === '7_days') {
            $dailyCounts = (clone $smsQuery)
                ->selectRaw("{$dateExpr} as dt, COUNT(*) as cnt")
                ->groupBy('dt')
                ->pluck('cnt', 'dt');

            for ($i = 6; $i >= 0; $i--) {
                $cDate = now()->subDays($i);
                $dt = $cDate->format('Y-m-d');
                $chartLabels[] = $cDate->format('d M');
                $chartData[] = (int)($dailyCounts[$dt] ?? 0);
            }
            $chartTitle = __('7 Days SMS Statistics') . ': ' . array_sum($chartData);
        } elseif ($chartMode === 'this_month') {
            $dailyCounts = (clone $smsQuery)
                ->selectRaw("{$dateExpr} as dt, COUNT(*) as cnt")
                ->groupBy('dt')
                ->pluck('cnt', 'dt');

            $daysCount = now()->daysInMonth;
            for ($d = 1; $d <= $daysCount; $d++) {
                $cDate = now()->startOfMonth()->addDays($d - 1);
                $dt = $cDate->format('Y-m-d');
                $chartLabels[] = $cDate->format('d M');
                $chartData[] = (int)($dailyCounts[$dt] ?? 0);
            }
            $chartTitle = __('This Month SMS Statistics') . ': ' . array_sum($chartData);
        } elseif ($chartMode === 'last_month') {
            $dailyCounts = (clone $smsQuery)
                ->selectRaw("{$dateExpr} as dt, COUNT(*) as cnt")
                ->groupBy('dt')
                ->pluck('cnt', 'dt');

            $lastMonth = now()->subMonth();
            $daysCount = $lastMonth->daysInMonth;
            for ($d = 1; $d <= $daysCount; $d++) {
                $cDate = $lastMonth->copy()->startOfMonth()->addDays($d - 1);
                $dt = $cDate->format('Y-m-d');
                $chartLabels[] = $cDate->format('d M');
                $chartData[] = (int)($dailyCounts[$dt] ?? 0);
            }
            $chartTitle = __('Last Month SMS Statistics') . ': ' . array_sum($chartData);
        } elseif ($chartMode === 'custom_daily' && isset($start) && isset($end)) {
            $dailyCounts = (clone $smsQuery)
                ->selectRaw("{$dateExpr} as dt, COUNT(*) as cnt")
                ->groupBy('dt')
                ->pluck('cnt', 'dt');

            $curr = $start->copy();
            while ($curr->lte($end)) {
                $dt = $curr->format('Y-m-d');
                $chartLabels[] = $curr->format('d M');
                $chartData[] = (int)($dailyCounts[$dt] ?? 0);
                $curr->addDay();
            }
            $chartTitle = __('Period SMS Statistics') . ': ' . array_sum($chartData);
        } else {
            // monthly
            $monthCounts = (clone $smsQuery)
                ->selectRaw("{$monthExpr} as m, COUNT(*) as cnt")
                ->groupBy('m')
                ->pluck('cnt', 'm');

            $fullMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            $shortMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $chartLabels = $shortMonths;
            foreach ($fullMonths as $fm) {
                $chartData[] = (int)($monthCounts[$fm] ?? 0);
            }
            $chartTitle = ($filter === 'all_time' ? __('All Time SMS Statistics') : __('Yearly SMS Statistics')) . ': ' . array_sum($chartData);
        }

        $data['chart_labels'] = $chartLabels;
        $data['chart_data'] = $chartData;
        $data['chart_title'] = $chartTitle;

        // Chart 2: SMS Overview (Doughnut)
        $data['sms_overview'] = [
            'delivered' => $data['success_sms'],
            'failed' => $data['canceled_sms'],
            'pending' => $data['pending_sms'],
            'total' => $data['total_sms'],
        ];

        // Recent Messages Table (Filtered by Date Range)
        $messages = (clone $smsQuery)->with('senderid:id,sender')->latest()->limit(15)->get();
        $data['messages_html'] = view('users.dashboard.message-datas', compact('messages'))->render();

        return response()->json($data);
    }

    public function yearlyStatistics()
    {
        $isPgSql = \DB::connection()->getDriverName() === 'pgsql';
        $monthExpr = $isPgSql ? "TRIM(TO_CHAR(created_at, 'Month'))" : "MONTHNAME(created_at)";

        $data['sms'] = Sms::where('user_id', auth()->id())->whereYear('created_at', request('year') ?? date('Y'))
                            ->selectRaw("{$monthExpr} as month, COUNT(*) as total_items")
                            ->groupBy('month')
                            ->get();

        return response()->json($data);
    }

    public function smsOverview()
    {
        $data['success_sms'] = Sms::where('user_id', auth()->id())->where('status', 'success')->whereYear('created_at', request('year') ?? date('Y'))->count();
        $data['canceled_sms'] = Sms::where('user_id', auth()->id())->where('status', 'canceled')->whereYear('created_at', request('year') ?? date('Y'))->count();

        return response()->json($data);
    }
}
