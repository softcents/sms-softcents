<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Senderid;
use App\Models\User;
use App\Services\ReportAnalyticsService;
use Illuminate\Http\Request;

class AdvancedReportController extends Controller
{
    public function index(Request $request)
    {
        // System-wide query (null allowedUserIds means all)
        $baseQuery = ReportAnalyticsService::buildFilteredQuery($request, null);

        // Compute KPIs, Operator breakdown, and Daily trend
        $kpis = ReportAnalyticsService::getSummaryKpis($baseQuery);
        $operators = ReportAnalyticsService::getOperatorBreakdown($baseQuery);
        $trend = ReportAnalyticsService::getDailyTrend($baseQuery, 14);

        // Detailed paginated list
        $smsList = (clone $baseQuery)
            ->with(['user:id,name,username,role', 'senderid:id,sender,type'])
            ->latest('sms.created_at')
            ->paginate(25)
            ->withQueryString();

        // Resellers and Users for filter dropdown
        $users = User::select('id', 'name', 'username', 'role')
            ->whereIn('role', ['sp', 'ssp', 'user'])
            ->orderBy('name')
            ->get();

        // Distinct Sender IDs for filter dropdown
        $senderids = Senderid::distinct()->get(['id', 'sender']);

        return view('admin.reports.advanced', compact(
            'smsList',
            'kpis',
            'operators',
            'trend',
            'users',
            'senderids'
        ));
    }

    public function export(Request $request)
    {
        $query = ReportAnalyticsService::buildFilteredQuery($request, null);
        $filename = 'admin_system_traffic_report_' . date('Ymd_His') . '.csv';

        return ReportAnalyticsService::streamCsvExport($query, $filename);
    }
}
