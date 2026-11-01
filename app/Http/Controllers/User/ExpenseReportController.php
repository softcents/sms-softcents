<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Senderid;
use App\Services\ProfitLossService;
use App\Services\ReportAnalyticsService;
use Illuminate\Http\Request;

class ExpenseReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Scope strictly to current customer
        $baseQuery = ProfitLossService::buildFinancialQuery($request, [$user->id]);

        $summary = ProfitLossService::getCustomerExpenseSummary($user, $request);
        $operatorBreakdown = ProfitLossService::getOperatorProfitBreakdown($baseQuery, 'user', $user);
        $dailyTrend = ReportAnalyticsService::getDailyTrend($baseQuery, 15);

        // Paginated SMS expenses
        $expenses = (clone $baseQuery)
            ->with(['senderid:id,sender,type'])
            ->latest('sms.created_at')
            ->paginate(25)
            ->withQueryString();

        $senderids = Senderid::where('user_id', $user->id)
            ->orWhere('is_default', 1)
            ->distinct()
            ->get(['id', 'sender']);

        return view('users.reports.expenses', compact(
            'summary',
            'operatorBreakdown',
            'dailyTrend',
            'expenses',
            'senderids'
        ));
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $baseQuery = ProfitLossService::buildFinancialQuery($request, [$user->id]);
        $filename = 'my_sms_expenses_' . date('Y_m_d_His') . '.csv';

        return ProfitLossService::streamCustomerExpenseCsv($baseQuery, $filename);
    }
}
