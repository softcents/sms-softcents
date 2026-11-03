<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware('permission:reports-read|dashboard-read')->only(['index', 'liveData']);
        $this->middleware('permission:settings-update|sms-settings-update')->only(['retryFailed', 'flushFailed', 'purgeQueue']);
    }

    /**
     * Display live queue monitoring NOC dashboard.
     */
    public function index(Request $request): View
    {
        $filterUserId = $request->input('filter_user_id') ? (int) $request->input('filter_user_id') : null;
        $broker = $this->service->getBrokerOverview();
        $workers = $this->service->getWorkerPoolStatus();
        $queues = $this->service->getQueues();
        $metrics = $this->service->getScopedLiveMetrics(auth()->user(), $filterUserId);
        $failedJobs = $this->service->getFailedJobs(15);
        $downlineUsers = $this->service->getFilterableDownline(auth()->user());
        $resellerSummary = $this->service->getResellerHierarchySummary();
        $providers = $this->service->getProviderTrafficSummary(auth()->user(), $filterUserId);
        $routes = $this->service->getRouteTrafficSummary(auth()->user(), $filterUserId);

        return view('admin.queue-monitor.index', compact(
            'broker',
            'workers',
            'queues',
            'metrics',
            'failedJobs',
            'downlineUsers',
            'filterUserId',
            'resellerSummary',
            'providers',
            'routes'
        ));
    }

    /**
     * AJAX endpoint for real-time polling updates.
     */
    public function liveData(Request $request): JsonResponse
    {
        $filterUserId = $request->input('filter_user_id') ? (int) $request->input('filter_user_id') : null;
        $broker = $this->service->getBrokerOverview();
        $workers = $this->service->getWorkerPoolStatus();
        $queues = $this->service->getQueues();
        $metrics = $this->service->getScopedLiveMetrics(auth()->user(), $filterUserId);
        $failedJobs = $this->service->getFailedJobs(15);
        $resellerSummary = $this->service->getResellerHierarchySummary();
        $providers = $this->service->getProviderTrafficSummary(auth()->user(), $filterUserId);
        $routes = $this->service->getRouteTrafficSummary(auth()->user(), $filterUserId);

        return response()->json([
            'success'   => true,
            'timestamp' => now()->format('H:i:s'),
            'broker'    => $broker,
            'workers'   => $workers,
            'queues'    => $queues,
            'metrics'   => $metrics,
            'failed'    => $failedJobs,
            'resellers' => $resellerSummary,
            'providers' => $providers,
            'routes'    => $routes,
        ]);
    }

    /**
     * Retry a specific failed job or all failed jobs.
     */
    public function retryFailed(Request $request, $id = null): JsonResponse
    {
        if ($id === 'all' || $request->input('id') === 'all') {
            $success = $this->service->retryAllFailedJobs();
            return response()->json([
                'success' => $success,
                'message' => $success ? __('All failed jobs have been re-queued successfully.') : __('Failed to retry all jobs.'),
            ]);
        }

        $jobId = $id ?: $request->input('id');
        $success = $this->service->retryFailedJob($jobId);

        return response()->json([
            'success' => $success,
            'message' => $success ? __("Job #{$jobId} re-queued successfully.") : __("Failed to retry Job #{$jobId}."),
        ]);
    }

    /**
     * Flush all failed jobs.
     */
    public function flushFailed(): JsonResponse
    {
        $success = $this->service->flushFailedJobs();
        return response()->json([
            'success' => $success,
            'message' => $success ? __('Failed jobs table cleared.') : __('Failed to clear failed jobs.'),
        ]);
    }

    /**
     * Purge all queued messages from a specific RabbitMQ queue.
     */
    public function purgeQueue(Request $request, string $queue): JsonResponse
    {
        $allowed = ['sms', 'sms_dispatch_queue', 'test_queue'];
        if (!in_array($queue, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid queue selected.'),
            ], 400);
        }

        $success = $this->service->purgeQueue($queue);
        return response()->json([
            'success' => $success,
            'message' => $success ? __("Queue '{$queue}' has been purged successfully.") : __("Failed to purge queue '{$queue}'."),
        ]);
    }
}
