<?php

namespace App\Http\Controllers\User;

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
     * Display live SMS dispatch and queue tracker for End Users.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        if ($request->has('filter_user_id') && (int) $request->input('filter_user_id') !== $user->id) {
            abort(403, 'Unauthorized access: You can only monitor your own queue.');
        }

        $metrics = $this->service->getScopedLiveMetrics($user);

        return view('users.queue-monitor.index', compact('metrics'));
    }

    /**
     * Real-time polling endpoint for End Users.
     */
    public function liveData(Request $request): JsonResponse
    {
        $user = auth()->user();
        if ($request->has('filter_user_id') && (int) $request->input('filter_user_id') !== $user->id) {
            abort(403, 'Unauthorized access: You can only monitor your own queue.');
        }

        $metrics = $this->service->getScopedLiveMetrics($user);

        return response()->json([
            'success'   => true,
            'timestamp' => now()->format('H:i:s'),
            'metrics'   => $metrics,
        ]);
    }
}
