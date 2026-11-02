<?php

namespace App\Http\Controllers\SP;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sms;
use App\Models\Recharge;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function getDownlineUserIds($sp)
    {
        $sspIds = User::where('parent_id', $sp->id)->where('role', 'ssp')->pluck('id')->toArray();
        $allDownlineIds = User::where(function ($q) use ($sp, $sspIds) {
            $q->where('parent_id', $sp->id)
              ->orWhereIn('parent_id', $sspIds);
        })->pluck('id')->toArray();
        $allDownlineIds[] = $sp->id;

        return array_unique($allDownlineIds);
    }

    public function index()
    {
        $sp = auth()->user();
        
        $totalSsps = User::where('parent_id', $sp->id)->where('role', 'ssp')->count();
        $totalCustomers = User::where('parent_id', $sp->id)->where('role', 'user')->count();
        
        $allDownlineIds = $this->getDownlineUserIds($sp);

        $totalSmsSent = Sms::whereIn('user_id', $allDownlineIds)->count();
        $todaySmsSent = Sms::whereIn('user_id', $allDownlineIds)->whereDate('created_at', today())->count();
        $pendingSms = Sms::whereIn('user_id', $allDownlineIds)->where('status', 'pending')->count();
        $successSms = Sms::whereIn('user_id', $allDownlineIds)->where('status', 'success')->count();
        $failedSms = Sms::whereIn('user_id', $allDownlineIds)->where('status', 'canceled')->count();

        $rechargeAmount = Recharge::where('reseller_id', $sp->id)->where('status', 'approved')->sum('amount');
        $totalDownlineUsers = count(array_diff($allDownlineIds, [$sp->id]));

        $recentSms = Sms::whereIn('user_id', $allDownlineIds)->with('user:id,name,username')->latest()->take(10)->get();

        return view('sp.dashboard', compact(
            'sp', 'totalSsps', 'totalCustomers', 'totalDownlineUsers', 'totalSmsSent',
            'todaySmsSent', 'pendingSms', 'successSms', 'failedSms', 'rechargeAmount',
            'recentSms'
        ));
    }

    public function getDashboardData()
    {
        $sp = auth()->user();
        $allDownlineIds = $this->getDownlineUserIds($sp);

        $data['customers'] = User::where('parent_id', $sp->id)->where('role', 'user')->count();
        $data['ssps'] = User::where('parent_id', $sp->id)->where('role', 'ssp')->count();
        $data['recharge_amount'] = currency_format(Recharge::where('reseller_id', $sp->id)->where('status', 'approved')->sum('amount'));
        $data['total_sms'] = Sms::whereIn('user_id', $allDownlineIds)->count();
        $data['pending_sms'] = Sms::whereIn('user_id', $allDownlineIds)->where('status', 'pending')->count();
        $data['success_sms'] = Sms::whereIn('user_id', $allDownlineIds)->where('status', 'success')->count();
        $data['canceled_sms'] = Sms::whereIn('user_id', $allDownlineIds)->where('status', 'canceled')->count();

        return response()->json($data);
    }

    public function yearlyStatistics()
    {
        $sp = auth()->user();
        $allDownlineIds = $this->getDownlineUserIds($sp);

        $isPgSql = \DB::connection()->getDriverName() === 'pgsql';
        $monthExpr = $isPgSql ? "TRIM(TO_CHAR(created_at, 'Month'))" : "MONTHNAME(created_at)";

        $data['sms'] = Sms::whereIn('user_id', $allDownlineIds)
                            ->whereYear('created_at', request('year') ?? date('Y'))
                            ->selectRaw("{$monthExpr} as month, COUNT(*) as total_items")
                            ->groupBy('month')
                            ->get();

        return response()->json($data);
    }

    public function smsOverview()
    {
        $sp = auth()->user();
        $allDownlineIds = $this->getDownlineUserIds($sp);

        $data['success_sms'] = Sms::whereIn('user_id', $allDownlineIds)->where('status', 'success')->whereYear('created_at', request('year') ?? date('Y'))->count();
        $data['canceled_sms'] = Sms::whereIn('user_id', $allDownlineIds)->where('status', 'canceled')->whereYear('created_at', request('year') ?? date('Y'))->count();

        return response()->json($data);
    }
}
