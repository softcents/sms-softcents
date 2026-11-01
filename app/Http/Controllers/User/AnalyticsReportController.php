<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Senderid;
use App\Services\ReportAnalyticsService;
use Illuminate\Http\Request;

class AnalyticsReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Scope strictly to current customer
        $baseQuery = ReportAnalyticsService::buildFilteredQuery($request, [$userId]);

        // KPIs, Operator breakdown, and Daily trend
        $kpis = ReportAnalyticsService::getSummaryKpis($baseQuery);
        $operators = ReportAnalyticsService::getOperatorBreakdown($baseQuery);
        $trend = ReportAnalyticsService::getDailyTrend($baseQuery, 14);

        // Detailed paginated list
        $smsList = (clone $baseQuery)
            ->with(['senderid:id,sender,type'])
            ->latest('sms.created_at')
            ->paginate(25)
            ->withQueryString();

        $senderids = Senderid::where('user_id', $userId)
            ->orWhere('is_default', 1)
            ->distinct()
            ->get(['id', 'sender']);

        return view('users.reports.analytics', compact(
            'smsList',
            'kpis',
            'operators',
            'trend',
            'senderids'
        ));
    }

    public function export(Request $request)
    {
        $userId = auth()->id();
        $query = ReportAnalyticsService::buildFilteredQuery($request, [$userId]);
        $filename = 'my_sms_report_' . date('Ymd_His') . '.csv';

        return ReportAnalyticsService::streamCsvExport($query, $filename);
    }
}
