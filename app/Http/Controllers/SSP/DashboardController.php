<?php

namespace App\Http\Controllers\SSP;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sms;
use App\Models\Recharge;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function getDownlineUserIds($ssp)
    {
        $customerIds = User::where('parent_id', $ssp->id)->where('role', 'user')->pluck('id')->toArray();
        $customerIds[] = $ssp->id;
        return array_unique($customerIds);
    }

    public function index()
    {
        $ssp = auth()->user();
        $allIds = $this->getDownlineUserIds($ssp);

        $totalCustomers = User::where('parent_id', $ssp->id)->where('role', 'user')->count();
        $totalSmsSent = Sms::whereIn('user_id', $allIds)->count();
        $todaySmsSent = Sms::whereIn('user_id', $allIds)->whereDate('created_at', today())->count();
        $pendingSms = Sms::whereIn('user_id', $allIds)->where('status', 'pending')->count();
        $successSms = Sms::whereIn('user_id', $allIds)->where('status', 'success')->count();
        $failedSms = Sms::whereIn('user_id', $allIds)->where('status', 'canceled')->count();

        $rechargeAmount = Recharge::where('reseller_id', $ssp->id)->where('status', 'approved')->sum('amount');

        $recentSms = Sms::whereIn('user_id', $allIds)->with('user:id,name,username')->latest()->take(10)->get();

        return view('ssp.dashboard', compact(
            'ssp', 'totalCustomers', 'totalSmsSent', 'todaySmsSent',
            'pendingSms', 'successSms', 'failedSms', 'rechargeAmount',
            'recentSms'
        ));
    }

    public function getDashboardData()
    {
        $ssp = auth()->user();
        $allIds = $this->getDownlineUserIds($ssp);

        $data['customers'] = User::where('parent_id', $ssp->id)->where('role', 'user')->count();
        $data['recharge_amount'] = currency_format(Recharge::where('reseller_id', $ssp->id)->where('status', 'approved')->sum('amount'));
        $data['total_sms'] = Sms::whereIn('user_id', $allIds)->count();
        $data['pending_sms'] = Sms::whereIn('user_id', $allIds)->where('status', 'pending')->count();
        $data['success_sms'] = Sms::whereIn('user_id', $allIds)->where('status', 'success')->count();
        $data['canceled_sms'] = Sms::whereIn('user_id', $allIds)->where('status', 'canceled')->count();

        return response()->json($data);
    }

    public function yearlyStatistics()
    {
        $ssp = auth()->user();
        $allIds = $this->getDownlineUserIds($ssp);

        $isPgSql = \DB::connection()->getDriverName() === 'pgsql';
        $monthExpr = $isPgSql ? "TRIM(TO_CHAR(created_at, 'Month'))" : "MONTHNAME(created_at)";

        $data['sms'] = Sms::whereIn('user_id', $allIds)
                            ->whereYear('created_at', request('year') ?? date('Y'))
                            ->selectRaw("{$monthExpr} as month, COUNT(*) as total_items")
                            ->groupBy('month')
                            ->get();

        return response()->json($data);
    }

    public function smsOverview()
    {
        $ssp = auth()->user();
        $allIds = $this->getDownlineUserIds($ssp);

        $data['success_sms'] = Sms::whereIn('user_id', $allIds)->where('status', 'success')->whereYear('created_at', request('year') ?? date('Y'))->count();
        $data['canceled_sms'] = Sms::whereIn('user_id', $allIds)->where('status', 'canceled')->whereYear('created_at', request('year') ?? date('Y'))->count();

        return response()->json($data);
    }
}
