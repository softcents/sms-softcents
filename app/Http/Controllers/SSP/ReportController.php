<?php

namespace App\Http\Controllers\SSP;

use App\Http\Controllers\Controller;
use App\Models\Senderid;
use App\Models\User;
use App\Services\ReportAnalyticsService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $ssp = auth()->user();

        // Get direct customers of SSP
        $customerIds = User::where('parent_id', $ssp->id)->pluck('id')->toArray();
        $customerIds[] = $ssp->id;

        // Build filtered query
        $baseQuery = ReportAnalyticsService::buildFilteredQuery($request, $customerIds);

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

        // Customers for filter dropdown
        $customers = User::whereIn('id', $customerIds)
            ->select('id', 'name', 'username')
            ->orderBy('name')
            ->get();

        // Sender IDs for filter dropdown
        $senderids = Senderid::whereIn('user_id', $customerIds)
            ->orWhere('is_default', 1)
            ->distinct()
            ->get(['id', 'sender']);

        return view('ssp.reports.index', compact(
            'smsList',
            'kpis',
            'operators',
            'trend',
            'customers',
            'senderids'
        ));
    }

    public function export(Request $request)
    {
        $ssp = auth()->user();
        $customerIds = User::where('parent_id', $ssp->id)->pluck('id')->toArray();
        $customerIds[] = $ssp->id;

        $query = ReportAnalyticsService::buildFilteredQuery($request, $customerIds);
        $filename = 'ssp_traffic_report_' . date('Ymd_His') . '.csv';

        return ReportAnalyticsService::streamCsvExport($query, $filename);
    }
}
