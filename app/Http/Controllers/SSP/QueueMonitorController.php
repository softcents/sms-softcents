<?php

namespace App\Http\Controllers\SSP;

use App\Http\Controllers\Controller;
use App\Services\QueueMonitorService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class QueueMonitorController extends Controller
{
    protected QueueMonitorService $service;

    public function __construct(QueueMonitorService $service)
    {
        $this->service = $service;
    }

    /**
     * Display live queue monitoring dashboard for Sub-Reseller (SSP).
     */
    public function index(): View
    {
        $user = auth()->user();
        $workers = $this->service->getWorkerPoolStatus();
        $metrics = $this->service->getScopedLiveMetrics($user);

        return view('ssp.queue-monitor.index', compact('workers', 'metrics'));
    }

    /**
     * Real-time polling endpoint for Sub-Reseller (SSP).
     */
    public function liveData(): JsonResponse
    {
        $user = auth()->user();
        $workers = $this->service->getWorkerPoolStatus();
        $metrics = $this->service->getScopedLiveMetrics($user);

        return response()->json([
            'success'   => true,
            'timestamp' => now()->format('H:i:s'),
            'workers'   => $workers,
            'metrics'   => $metrics,
        ]);
    }
}
