<?php

namespace App\Http\Controllers\SP;

use App\Http\Controllers\Controller;
use App\Models\Senderid;
use App\Models\User;
use App\Services\ReportAnalyticsService;
use Illuminate\Http\Request;

class ReportController extends Controller
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

        // Build filtered query
        $baseQuery = ReportAnalyticsService::buildFilteredQuery($request, $allDownlineIds);

        // Compute KPIs, Operator breakdown, and Daily trend
        $kpis = ReportAnalyticsService::getSummaryKpis($baseQuery);
        $operators = ReportAnalyticsService::getOperatorBreakdown($baseQuery);
        $trend = ReportAnalyticsService::getDailyTrend($baseQuery, 14);

        // Detailed paginated list
        $smsList = (clone $baseQuery)
            ->with(['user:id,name,username,role,balance', 'senderid:id,sender,type', 'smsgateway:id,name'])
            ->latest('sms.created_at')
            ->paginate(25)
            ->withQueryString();

        // Downline users for filter dropdown
        $downlineUsers = User::whereIn('id', $allDownlineIds)
            ->select('id', 'name', 'username', 'role')
            ->orderBy('name')
            ->get();

        // Sender IDs for filter dropdown
        $senderids = Senderid::whereIn('user_id', $allDownlineIds)
            ->orWhere('is_default', 1)
            ->distinct()
            ->get(['id', 'sender']);

        return view('sp.reports.index', compact(
            'smsList',
            'kpis',
            'operators',
            'trend',
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

        $query = ReportAnalyticsService::buildFilteredQuery($request, $allDownlineIds);
        $filename = 'sp_traffic_report_' . date('Ymd_His') . '.csv';

        return ReportAnalyticsService::streamCsvExport($query, $filename);
    }
}
