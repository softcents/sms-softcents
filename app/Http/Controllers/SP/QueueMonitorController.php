<?php

namespace App\Http\Controllers\SP;

use App\Http\Controllers\Controller;
use App\Services\QueueMonitorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueueMonitorController extends Controller
{
    protected QueueMonitorService $service;

    public function __construct(QueueMonitorService $service)
    {
        $this->service = $service;
    }

    /**
     * Display live queue monitoring dashboard for Reseller (SP).
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $filterUserId = $request->input('filter_user_id') ? (int) $request->input('filter_user_id') : null;
        $workers = $this->service->getWorkerPoolStatus();
        $metrics = $this->service->getScopedLiveMetrics($user, $filterUserId);
        $downlineUsers = $this->service->getFilterableDownline($user);

        return view('sp.queue-monitor.index', compact('workers', 'metrics', 'downlineUsers', 'filterUserId'));
    }

    /**
     * Real-time polling endpoint for Reseller (SP).
     */
    public function liveData(Request $request): JsonResponse
    {
        $user = auth()->user();
        $filterUserId = $request->input('filter_user_id') ? (int) $request->input('filter_user_id') : null;
        $workers = $this->service->getWorkerPoolStatus();
        $metrics = $this->service->getScopedLiveMetrics($user, $filterUserId);

        return response()->json([
            'success'   => true,
            'timestamp' => now()->format('H:i:s'),
            'workers'   => $workers,
            'metrics'   => $metrics,
        ]);
    }
}
