<?php

namespace App\Http\Controllers\SSP;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sms;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $ssp = auth()->user();

        $totalCustomers = User::where('parent_id', $ssp->id)->where('role', 'user')->count();
        $customerIds = User::where('parent_id', $ssp->id)->where('role', 'user')->pluck('id')->toArray();
        $customerIds[] = $ssp->id;

        $totalSmsSent = Sms::whereIn('user_id', $customerIds)->count();
        $todaySmsSent = Sms::whereIn('user_id', $customerIds)->whereDate('created_at', today())->count();

        $recentSms = Sms::whereIn('user_id', $customerIds)->with('user:id,name,username')->latest()->take(10)->get();

        return view('ssp.dashboard', compact('ssp', 'totalCustomers', 'totalSmsSent', 'todaySmsSent', 'recentSms'));
    }
}
