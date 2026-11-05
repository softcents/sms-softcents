<?php

namespace App\Http\Controllers\SSP;

use App\Http\Controllers\Controller;
use App\Models\Senderid;
use App\Models\User;
use App\Services\ProfitLossService;
use Illuminate\Http\Request;

class ProfitLossController extends Controller
{
    public function index(Request $request)
    {
        $ssp = auth()->user();

        // Get downline customer IDs
        $downlineIds = User::where('parent_id', $ssp->id)->pluck('id')->toArray();
        $downlineIds[] = $ssp->id;

        // Build filtered query restricted to downline customers
        $baseQuery = ProfitLossService::buildFinancialQuery($request, $downlineIds);

        $metrics = ProfitLossService::getSummaryMetrics($baseQuery, 'ssp', $ssp, $downlineIds);
        $userBreakdown = ProfitLossService::getUserProfitBreakdown($baseQuery, 'ssp', $ssp, 25);
        $operatorBreakdown = ProfitLossService::getOperatorProfitBreakdown($baseQuery, 'ssp', $ssp);
        $financialTrend = ProfitLossService::getDailyFinancialTrend($baseQuery, 'ssp', $ssp, 15);

        // Filter dropdown options
        $downlineUsers = User::whereIn('id', $downlineIds)
            ->select('id', 'name', 'username', 'role')
            ->orderBy('name')
            ->get();

        $senderids = Senderid::whereIn('user_id', $downlineIds)
            ->distinct()
            ->get(['id', 'sender']);

        return view('ssp.reports.profit-loss', compact(
            'metrics',
            'userBreakdown',
            'operatorBreakdown',
            'financialTrend',
            'downlineUsers',
            'senderids'
        ));
    }

    public function export(Request $request)
    {
        $ssp = auth()->user();

        $downlineIds = User::where('parent_id', $ssp->id)->pluck('id')->toArray();
        $downlineIds[] = $ssp->id;

        $baseQuery = ProfitLossService::buildFinancialQuery($request, $downlineIds);
        $filename = 'ssp_profit_loss_' . date('Y_m_d_His') . '.csv';

        return ProfitLossService::streamProfitLossCsv($baseQuery, 'ssp', $ssp, $filename);
    }
}
