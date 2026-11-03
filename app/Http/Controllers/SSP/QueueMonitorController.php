<?php

namespace App\Http\Controllers\SSP;

use App\Http\Controllers\Controller;
use App\Models\Sms;
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
     * Display live queue monitoring dashboard for Sub-Reseller (SSP).
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $filterUserId = $request->input('filter_user_id') ? (int) $request->input('filter_user_id') : null;
        if ($filterUserId && !$this->service->validateAccountAccess($user, $filterUserId)) {
            $filterUserId = null;
        }

        $workers = $this->service->getWorkerPoolStatus();
        $metrics = $this->service->getScopedLiveMetrics($user, $filterUserId);
        $downlineUsers = $this->service->getFilterableDownline($user);
        $customerSummary = $this->service->getCustomerQueueSummary($user, $filterUserId);
        $customerPendingSummary = $this->service->getCustomerPendingSmsSummary($user, $filterUserId);
        $customerProcessingSummary = $this->service->getCustomerProcessingSmsSummary($user, $filterUserId);
        $breadcrumbs = $filterUserId ? $this->service->getUserHierarchyBreadcrumbs($filterUserId, $user) : [];
        $customerQueue = $filterUserId ? $this->service->getCustomerSmsQueueDetails($filterUserId, null, 25, $user) : null;

        return view('ssp.queue-monitor.index', compact(
            'workers',
            'metrics',
            'downlineUsers',
            'filterUserId',
            'customerSummary',
            'customerPendingSummary',
            'customerProcessingSummary',
            'breadcrumbs',
            'customerQueue'
        ));
    }

    /**
     * Real-time polling endpoint for Sub-Reseller (SSP).
     */
    public function liveData(Request $request): JsonResponse
    {
        $user = auth()->user();
        $filterUserId = $request->input('filter_user_id') ? (int) $request->input('filter_user_id') : null;
        if ($filterUserId && !$this->service->validateAccountAccess($user, $filterUserId)) {
            $filterUserId = null;
        }

        $statusFilter = $request->input('status_filter') ?: null;
        $workers = $this->service->getWorkerPoolStatus();
        $metrics = $this->service->getScopedLiveMetrics($user, $filterUserId);
        $customerSummary = $this->service->getCustomerQueueSummary($user, $filterUserId);
        $customerPendingSummary = $this->service->getCustomerPendingSmsSummary($user, $filterUserId);
        $customerProcessingSummary = $this->service->getCustomerProcessingSmsSummary($user, $filterUserId);
        $breadcrumbs = $filterUserId ? $this->service->getUserHierarchyBreadcrumbs($filterUserId, $user) : [];
        $customerQueue = $filterUserId ? $this->service->getCustomerSmsQueueDetails($filterUserId, $statusFilter, 25, $user) : null;

        return response()->json([
            'success'             => true,
            'timestamp'           => now()->format('H:i:s'),
            'workers'             => $workers,
            'metrics'             => $metrics,
            'customers'           => $customerSummary,
            'customer_pending'    => $customerPendingSummary,
            'customer_processing' => $customerProcessingSummary,
            'breadcrumbs'         => $breadcrumbs,
            'customer_queue'      => $customerQueue,
        ]);
    }

    /**
     * Inspect rich drill-down metrics for a specific customer in Sub-Reseller downline.
     */
    public function inspectUser(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$this->service->validateAccountAccess($user, $id)) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized customer account access.'),
            ], 403);
        }

        $details = $this->service->getUserDrilldownDetails($id);
        if (!$details) {
            return response()->json([
                'success' => false,
                'message' => __('User not found.'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $details,
        ]);
    }

    /**
     * Get detailed customer SMS queue messages and metrics.
     */
    public function customerQueueDetails(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$this->service->validateAccountAccess($user, $id)) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized customer account access.'),
            ], 403);
        }

        $statusFilter = $request->input('status');
        $details = $this->service->getCustomerSmsQueueDetails($id, $statusFilter, 25, $user);
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
        $sms = Sms::find($id);
        if (!$sms || !$this->service->validateAccountAccess(auth()->user(), $sms->user_id)) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized or message not found.'),
            ], 403);
        }

        if ($sms->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => __('Only pending messages can be cancelled.'),
            ], 422);
        }

        $sms->status = 'cancelled';
        $sms->notes = 'Cancelled from queue monitor by Sub-Reseller at ' . now()->toDateTimeString();
        $sms->save();

        return response()->json([
            'success' => true,
            'message' => __('SMS message #' . $id . ' cancelled successfully.'),
        ]);
    }

    /**
     * Retry a failed SMS message.
     */
    public function retrySms(Request $request, int $id): JsonResponse
    {
        $sms = Sms::find($id);
        if (!$sms || !$this->service->validateAccountAccess(auth()->user(), $sms->user_id)) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized or message not found.'),
            ], 403);
        }

        if (!in_array($sms->status, ['failed', 'rejected'])) {
            return response()->json([
                'success' => false,
                'message' => __('Only failed or rejected messages can be retried.'),
            ], 422);
        }

        $sms->status = 'pending';
        $sms->updated_at = now();
        $sms->save();

        return response()->json([
            'success' => true,
            'message' => __('SMS message #' . $id . ' re-queued for transmission.'),
        ]);
    }

    /**
     * Cancel all pending SMS messages for a specific customer in Sub-Reseller downline.
     */
    public function cancelCustomerPendingSms(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$this->service->validateAccountAccess($user, $id)) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized customer account access.'),
            ], 403);
        }

        $cancelledCount = Sms::where('user_id', $id)
            ->where('status', 'pending')
            ->update([
                'status' => 'cancelled',
                'notes'  => 'Bulk cancelled from queue monitor by Sub-Reseller at ' . now()->toDateTimeString(),
            ]);

        return response()->json([
            'success'         => true,
            'message'         => __(':count pending SMS messages cancelled successfully.', ['count' => $cancelledCount]),
            'cancelled_count' => $cancelledCount,
        ]);
    }
}
