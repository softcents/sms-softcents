<?php

namespace App\Http\Controllers\SP;

use App\Http\Controllers\Controller;
use App\Models\Senderid;
use App\Models\User;
use App\Services\ProfitLossService;
use Illuminate\Http\Request;

class ProfitLossController extends Controller
{
    public function index(Request $request)
    {
        $sp = auth()->user();

        // Get all downline user IDs (direct customers + SSPs + SSP customers)
        $sspIds = User::where('parent_id', $sp->id)->where('role', 'ssp')->pluck('id')->toArray();
        $allDownlineIds = User::where(function ($q) use ($sp, $sspIds) {
            $q->where('parent_id', $sp->id)
              ->orWhereIn('parent_id', $sspIds);
        })->pluck('id')->toArray();
        $allDownlineIds[] = $sp->id;

        // Build filtered query restricted to downline
        $baseQuery = ProfitLossService::buildFinancialQuery($request, $allDownlineIds);

        $metrics = ProfitLossService::getSummaryMetrics($baseQuery, 'sp', $sp, $allDownlineIds);
        $userBreakdown = ProfitLossService::getUserProfitBreakdown($baseQuery, 'sp', $sp, 25);
        $operatorBreakdown = ProfitLossService::getOperatorProfitBreakdown($baseQuery, 'sp', $sp);
        $financialTrend = ProfitLossService::getDailyFinancialTrend($baseQuery, 'sp', $sp, 15);

        // Filter dropdown options
        $downlineUsers = User::whereIn('id', $allDownlineIds)
            ->select('id', 'name', 'username', 'role')
            ->orderBy('name')
            ->get();

        $senderids = Senderid::whereIn('user_id', $allDownlineIds)
            ->distinct()
            ->get(['id', 'sender']);

        return view('sp.reports.profit-loss', compact(
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
        $sp = auth()->user();

        $sspIds = User::where('parent_id', $sp->id)->where('role', 'ssp')->pluck('id')->toArray();
        $allDownlineIds = User::where(function ($q) use ($sp, $sspIds) {
            $q->where('parent_id', $sp->id)
              ->orWhereIn('parent_id', $sspIds);
        })->pluck('id')->toArray();
        $allDownlineIds[] = $sp->id;

        $baseQuery = ProfitLossService::buildFinancialQuery($request, $allDownlineIds);
        $filename = 'sp_profit_loss_' . date('Y_m_d_His') . '.csv';

        return ProfitLossService::streamProfitLossCsv($baseQuery, 'sp', $sp, $filename);
    }
}
