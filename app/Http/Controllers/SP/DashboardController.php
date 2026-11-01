<?php

namespace App\Http\Controllers\SP;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sms;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sp = auth()->user();
        
        $totalSsps = User::where('parent_id', $sp->id)->where('role', 'ssp')->count();
        $totalCustomers = User::where('parent_id', $sp->id)->where('role', 'user')->count();
        
        // Downline user IDs (both direct customers and customers under SSPs)
        $sspIds = User::where('parent_id', $sp->id)->where('role', 'ssp')->pluck('id')->toArray();
        $allDownlineIds = User::where(function ($q) use ($sp, $sspIds) {
            $q->where('parent_id', $sp->id)
              ->orWhereIn('parent_id', $sspIds);
        })->pluck('id')->toArray();
        $allDownlineIds[] = $sp->id;

        $totalSmsSent = Sms::whereIn('user_id', $allDownlineIds)->count();
        $todaySmsSent = Sms::whereIn('user_id', $allDownlineIds)->whereDate('created_at', today())->count();

        $recentSms = Sms::whereIn('user_id', $allDownlineIds)->with('user:id,name,username')->latest()->take(10)->get();

        return view('sp.dashboard', compact('sp', 'totalSsps', 'totalCustomers', 'totalSmsSent', 'todaySmsSent', 'recentSms'));
    }
}
