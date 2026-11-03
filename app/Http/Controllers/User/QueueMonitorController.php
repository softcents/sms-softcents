<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CampaignSms;
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
     * Display live SMS dispatch and queue tracker for End Users (Customers).
     * STRICTLY scoped to their own account traffic only.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        if ($request->has('filter_user_id') && (int) $request->input('filter_user_id') !== $user->id) {
            abort(403, 'Unauthorized access: You can only monitor your own queue.');
        }

        $workers = $this->service->getWorkerPoolStatus();
        $metrics = $this->service->getScopedLiveMetrics($user);
        $customerQueue = $this->service->getCustomerSmsQueueDetails($user->id, null, 25, $user);
        $campaigns = $this->service->getCampaignTrafficSummary($user);
        $campaignAggregates = $this->service->getCampaignAggregates($campaigns);

        return view('users.queue-monitor.index', compact(
            'user',
            'workers',
            'metrics',
            'customerQueue',
            'campaigns',
            'campaignAggregates'
        ));
    }

    /**
     * Real-time polling endpoint for End Users (Customers).
     * STRICTLY scoped to their own account traffic only.
     */
    public function liveData(Request $request): JsonResponse
    {
        $user = auth()->user();
        if ($request->has('filter_user_id') && (int) $request->input('filter_user_id') !== $user->id) {
            abort(403, 'Unauthorized access: You can only monitor your own queue.');
        }

        $statusFilter = $request->input('status_filter') ?: null;
        $workers = $this->service->getWorkerPoolStatus();
        $metrics = $this->service->getScopedLiveMetrics($user);
        $customerQueue = $this->service->getCustomerSmsQueueDetails($user->id, $statusFilter, 25, $user);
        $campaigns = $this->service->getCampaignTrafficSummary($user);
        $campaignAggregates = $this->service->getCampaignAggregates($campaigns);

        return response()->json([
            'success'             => true,
            'timestamp'           => now()->format('H:i:s'),
            'workers'             => $workers,
            'metrics'             => $metrics,
            'customer_queue'      => $customerQueue,
            'campaigns'           => $campaigns,
            'campaign_aggregates' => $campaignAggregates,
        ]);
    }

    /**
     * Cancel a single pending SMS owned by this customer.
     */
    public function cancelSms(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        $sms = Sms::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$sms) {
            return response()->json([
                'success' => false,
                'message' => __('SMS message not found or not eligible for cancellation.'),
            ], 404);
        }

        $sms->update([
            'status'     => 'cancelled',
            'notes'      => 'Cancelled from queue monitor by customer at ' . now()->toDateTimeString(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('SMS message #:id cancelled successfully.', ['id' => $id]),
        ]);
    }

    /**
     * Cancel all pending SMS messages owned by this customer.
     */
    public function cancelAllPendingSms(Request $request): JsonResponse
    {
        $user = auth()->user();
        $cancelledCount = Sms::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update([
                'status'     => 'cancelled',
                'notes'      => 'Bulk cancelled from queue monitor by customer at ' . now()->toDateTimeString(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success'         => true,
            'message'         => __(':count pending SMS messages cancelled successfully.', ['count' => $cancelledCount]),
            'cancelled_count' => $cancelledCount,
        ]);
    }

    /**
     * Retry a single failed/rejected/undelivered SMS owned by this customer.
     */
    public function retrySms(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        $sms = Sms::where('id', $id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['failed', 'rejected', 'undelivered'])
            ->first();

        if (!$sms) {
            return response()->json([
                'success' => false,
                'message' => __('SMS message not found or not eligible for retry.'),
            ], 404);
        }

        $sms->update([
            'status'     => 'pending',
            'notes'      => 'Re-queued from queue monitor by customer at ' . now()->toDateTimeString(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('SMS message #:id re-queued successfully for transmission.', ['id' => $id]),
        ]);
    }

    /**
     * Retry all failed/rejected/undelivered SMS messages owned by this customer.
     */
    public function retryAllFailedSms(Request $request): JsonResponse
    {
        $user = auth()->user();
        $retriedCount = Sms::where('user_id', $user->id)
            ->whereIn('status', ['failed', 'rejected', 'undelivered'])
            ->update([
                'status'     => 'pending',
                'notes'      => 'Bulk re-queued from queue monitor by customer at ' . now()->toDateTimeString(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success'       => true,
            'message'       => __(':count failed SMS messages re-queued for transmission.', ['count' => $retriedCount]),
            'retried_count' => $retriedCount,
        ]);
    }

    /**
     * Cancel pending SMS for a specific campaign owned by this customer.
     */
    public function cancelCampaignPending(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();

        $campaignSms = CampaignSms::where(function($q) use ($id) {
            $q->where('campaign_id', $id)->orWhere('id', $id);
        })->where('user_id', $user->id)->first();

        $count = Sms::where('campaign_id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->update([
                'status'     => 'cancelled',
                'notes'      => 'Campaign cancelled from queue monitor by customer at ' . now()->toDateTimeString(),
                'updated_at' => now(),
            ]);

        if ($campaignSms && in_array($campaignSms->status, ['pending', 'queued', 'processing'])) {
            $campaignSms->update([
                'status'     => 'cancelled',
                'notes'      => 'Cancelled by customer',
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success'         => true,
            'message'         => __(':count pending SMS in campaign have been cancelled.', ['count' => $count]),
            'cancelled_count' => $count,
        ]);
    }
}
