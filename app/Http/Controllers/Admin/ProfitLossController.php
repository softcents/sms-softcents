<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Senderid;
use App\Models\User;
use App\Services\ProfitLossService;
use Illuminate\Http\Request;

class ProfitLossController extends Controller
{
    public function index(Request $request)
    {
        // System-wide query (superadmin sees all)
        $baseQuery = ProfitLossService::buildFinancialQuery($request, null);

        $metrics = ProfitLossService::getSummaryMetrics($baseQuery, 'admin');
        $userBreakdown = ProfitLossService::getUserProfitBreakdown($baseQuery, 'admin', null, 25);
        $operatorBreakdown = ProfitLossService::getOperatorProfitBreakdown($baseQuery, 'admin');
        $gatewayBreakdown = ProfitLossService::getGatewayProfitBreakdown($baseQuery);
        $financialTrend = ProfitLossService::getDailyFinancialTrend($baseQuery, 'admin', null, 15);

        // Filter dropdown options
        $users = User::select('id', 'name', 'username', 'role')
            ->whereIn('role', ['sp', 'ssp', 'user'])
            ->orderBy('name')
            ->get();

        $senderids = Senderid::distinct()->get(['id', 'sender']);

        return view('admin.reports.profit-loss', compact(
            'metrics',
            'userBreakdown',
            'operatorBreakdown',
            'gatewayBreakdown',
            'financialTrend',
            'users',
            'senderids'
        ));
    }

    public function export(Request $request)
    {
        $baseQuery = ProfitLossService::buildFinancialQuery($request, null);
        $filename = 'admin_profit_loss_' . date('Y_m_d_His') . '.csv';
        return ProfitLossService::streamProfitLossCsv($baseQuery, 'admin', null, $filename);
    }
}
