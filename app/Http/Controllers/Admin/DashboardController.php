<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\PlanSubscribe;
use App\Models\Recharge;
use App\Models\Sms;
use App\Models\Smsgateway;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:dashboard-read')->only('index');
    }

    public function index()
    {
        $users = User::whereRole('user')->latest()->take(5)->get();
        return view('admin.dashboard.index', compact('users'));
    }

    public function getDashboardData()
    {
        $data['customers'] = User::where('role', 'user')->count();
        $data['plans'] = Plan::count();
        $data['subcribers'] = PlanSubscribe::whereStatus('approved')->count();
        $data['api_gateways'] = Smsgateway::count();
        $data['andriod_gateways'] = Smsgateway::count();
        $data['recharge_amount'] = currency_format(Recharge::whereStatus('approved')->sum('amount'));

        $data['total_sms'] = Sms::count();
        $data['pending_sms'] = Sms::where('status', 'pending')->count();
        $data['success_sms'] = Sms::where('status', 'success')->count();
        $data['canceled_sms'] = Sms::where('status', 'canceled')->count();

        return response()->json($data);
    }

   public function yearlyStatistics()
   {
       $data['sms'] = Sms::whereYear('created_at', request('year') ?? date('Y'))
                           ->selectRaw('MONTHNAME(created_at) as month, COUNT(*) as total_items')
                           ->groupBy('month')
                           ->get();

       return response()->json($data);
   }

    public function smsOverview()
    {
        $data['success_sms'] = Sms::where('status', 'success')->whereYear('created_at', request('year') ?? date('Y'))->count();
        $data['canceled_sms'] = Sms::where('status', 'canceled')->whereYear('created_at', request('year') ?? date('Y'))->count();

        return response()->json($data);
    }
}
