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
        $messages = Sms::with('senderid:id,sender')->where('user_id', auth()->id())->latest()->limit(5)->get();
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

        $todayStr = now()->format('Y-m-d');
        if ($filter === 'today') {
            $smsQuery->whereDate('created_at', $todayStr);
            $rechargeQuery->whereDate('created_at', $todayStr);
            $dateLabel = __('Today') . ' (' . now()->format('d M Y') . ')';
        } elseif ($filter === 'yesterday') {
            $yesterdayStr = now()->subDay()->format('Y-m-d');
            $smsQuery->whereDate('created_at', $yesterdayStr);
            $rechargeQuery->whereDate('created_at', $yesterdayStr);
            $dateLabel = __('Yesterday') . ' (' . now()->subDay()->format('d M Y') . ')';
        } elseif ($filter === 'last_7_days') {
            $from = now()->subDays(6)->startOfDay();
            $smsQuery->where('created_at', '>=', $from);
            $rechargeQuery->where('created_at', '>=', $from);
            $dateLabel = __('Last 7 Days');
        } elseif ($filter === 'this_month') {
            $from = now()->startOfMonth();
            $smsQuery->where('created_at', '>=', $from);
            $rechargeQuery->where('created_at', '>=', $from);
            $dateLabel = __('This Month') . ' (' . now()->format('F Y') . ')';
        } elseif ($filter === 'last_month') {
            $from = now()->subMonth()->startOfMonth();
            $to = now()->subMonth()->endOfMonth();
            $smsQuery->whereBetween('created_at', [$from, $to]);
            $rechargeQuery->whereBetween('created_at', [$from, $to]);
            $dateLabel = __('Last Month') . ' (' . now()->subMonth()->format('F Y') . ')';
        } elseif ($filter === 'this_year') {
            $from = now()->startOfYear();
            $smsQuery->where('created_at', '>=', $from);
            $rechargeQuery->where('created_at', '>=', $from);
            $dateLabel = __('This Year') . ' (' . now()->format('Y') . ')';
        } elseif ($filter === 'custom' && $startDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = $endDate ? Carbon::parse($endDate)->endOfDay() : $start->copy()->endOfDay();
            $smsQuery->whereBetween('created_at', [$start, $end]);
            $rechargeQuery->whereBetween('created_at', [$start, $end]);
            $dateLabel = $start->format('d M Y') . ($endDate && $endDate != $startDate ? ' - ' . Carbon::parse($endDate)->format('d M Y') : '');
        } elseif ($filter === 'all_time') {
            $dateLabel = __('All Time');
        } else {
            $smsQuery->whereDate('created_at', $todayStr);
            $rechargeQuery->whereDate('created_at', $todayStr);
            $dateLabel = __('Today') . ' (' . now()->format('d M Y') . ')';
        }

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
