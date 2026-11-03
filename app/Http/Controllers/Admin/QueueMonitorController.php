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
        $campaigns = $this->service->getCampaignTrafficSummary(auth()->user(), $filterUserId);
        $breadcrumbs = $filterUserId ? $this->service->getUserHierarchyBreadcrumbs($filterUserId) : [];
        $customerQueue = $filterUserId ? $this->service->getCustomerSmsQueueDetails($filterUserId) : null;

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
            'routes',
            'campaigns',
            'breadcrumbs',
            'customerQueue'
        ));
    }

    /**
     * AJAX endpoint for real-time polling updates.
     */
    public function liveData(Request $request): JsonResponse
    {
        $filterUserId = $request->input('filter_user_id') ? (int) $request->input('filter_user_id') : null;
        $statusFilter = $request->input('status_filter') ?: null;
        $broker = $this->service->getBrokerOverview();
        $workers = $this->service->getWorkerPoolStatus();
        $queues = $this->service->getQueues();
        $metrics = $this->service->getScopedLiveMetrics(auth()->user(), $filterUserId);
        $failedJobs = $this->service->getFailedJobs(15);
        $resellerSummary = $this->service->getResellerHierarchySummary();
        $providers = $this->service->getProviderTrafficSummary(auth()->user(), $filterUserId);
        $routes = $this->service->getRouteTrafficSummary(auth()->user(), $filterUserId);
        $campaigns = $this->service->getCampaignTrafficSummary(auth()->user(), $filterUserId);
        $breadcrumbs = $filterUserId ? $this->service->getUserHierarchyBreadcrumbs($filterUserId) : [];
        $customerQueue = $filterUserId ? $this->service->getCustomerSmsQueueDetails($filterUserId, $statusFilter) : null;

        return response()->json([
            'success'        => true,
            'timestamp'      => now()->format('H:i:s'),
            'broker'         => $broker,
            'workers'        => $workers,
            'queues'         => $queues,
            'metrics'        => $metrics,
            'failed'         => $failedJobs,
            'resellers'      => $resellerSummary,
            'providers'      => $providers,
            'routes'         => $routes,
            'campaigns'      => $campaigns,
            'breadcrumbs'    => $breadcrumbs,
            'customer_queue' => $customerQueue,
        ]);
    }

    /**
     * Inspect rich drill-down metrics for a specific user.
     */
    public function inspectUser(Request $request, int $id): JsonResponse
    {
        $details = $this->service->getUserDrilldownDetails($id);
        if (!$details) {
            return response()->json([
                'success' => false,
                'message' => __('User not found or unauthorized.'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $details,
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

    /**
     * Broadcast graceful restart signal to all active SMS queue workers.
     */
    public function restartWorkers(): JsonResponse
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('queue:restart');
            return response()->json([
                'success' => true,
                'message' => __('Worker pool restart signal broadcasted successfully. Active workers will gracefully recycle.'),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to broadcast worker restart signal: ') . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get detailed customer SMS queue messages and metrics.
     */
    public function customerQueueDetails(Request $request, int $id): JsonResponse
    {
        $statusFilter = $request->input('status');
        $details = $this->service->getCustomerSmsQueueDetails($id, $statusFilter);
        if (!$details) {
            return response()->json([
                'success' => false,
                'message' => __('Customer account not found.'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $details,
        ]);
    }

    /**
     * Cancel a pending SMS message from the queue.
     */
    public function cancelSms(Request $request, int $id): JsonResponse
    {
        $sms = \App\Models\Sms::find($id);
        if (!$sms || $sms->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => __('SMS not found or is no longer pending in queue.'),
            ], 400);
        }

        $sms->status = 'cancelled';
        $sms->notes = 'Cancelled from queue by Super Admin';
        $sms->save();

        return response()->json([
            'success' => true,
            'message' => __("SMS #{$id} has been cancelled from the queue."),
        ]);
    }

    /**
     * Re-queue a failed SMS message into the active dispatch pipeline.
     */
    public function retrySms(Request $request, int $id): JsonResponse
    {
        $sms = \App\Models\Sms::with('smsgateway')->find($id);
        if (!$sms) {
            return response()->json([
                'success' => false,
                'message' => __('SMS not found.'),
            ], 404);
        }

        $sms->status = 'pending';
        $sms->gateway_response_code = '102 (Re-queued)';
        $sms->save();

        $gw = $sms->smsgateway;
        if ($gw) {
            $config = [
                'driver'   => $gw->driver,
                'senderid' => $sms->senderid_id,
                'apiurl'   => $gw->api_url,
                'apikey'   => $gw->api_key,
            ];
            dispatch(new \App\Jobs\SendSmsJob($sms->number, $sms->contents, $config, $sms->id))->onQueue('sms');
        }

        return response()->json([
            'success' => true,
            'message' => __("SMS #{$id} has been re-queued to the active dispatch pipeline."),
        ]);
    }
}
