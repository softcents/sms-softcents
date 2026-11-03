<?php

namespace App\Http\Controllers\User;

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
     * Display live SMS dispatch and queue tracker for End Users.
     */
    public function index(): View
    {
        $user = auth()->user();
        $metrics = $this->service->getScopedLiveMetrics($user);

        return view('users.queue-monitor.index', compact('metrics'));
    }

    /**
     * Real-time polling endpoint for End Users.
     */
    public function liveData(): JsonResponse
    {
        $user = auth()->user();
        $metrics = $this->service->getScopedLiveMetrics($user);

        return response()->json([
            'success'   => true,
            'timestamp' => now()->format('H:i:s'),
            'metrics'   => $metrics,
        ]);
    }
}
