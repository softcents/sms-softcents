<?php

namespace App\Http\Controllers\SP;

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
     * Display live queue monitoring dashboard for Reseller (SP).
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
        $resellerSummary = $this->service->getResellerHierarchySummary($user);
        $subResellers = $this->service->getSubResellerQueueSummary($user, $filterUserId);
        $providers = $this->service->getProviderTrafficSummary($user, $filterUserId);
        $routes = $this->service->getRouteTrafficSummary($user, $filterUserId);
        $campaigns = $this->service->getCampaignTrafficSummary($user, $filterUserId);
        $breadcrumbs = $filterUserId ? $this->service->getUserHierarchyBreadcrumbs($filterUserId) : [];
        $customerQueue = $filterUserId ? $this->service->getCustomerSmsQueueDetails($filterUserId) : null;

        return view('sp.queue-monitor.index', compact(
            'workers',
            'metrics',
            'downlineUsers',
            'filterUserId',
            'resellerSummary',
            'subResellers',
            'providers',
            'routes',
            'campaigns',
            'breadcrumbs',
            'customerQueue'
        ));
    }

    /**
     * Real-time polling endpoint for Reseller (SP).
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
        $resellerSummary = $this->service->getResellerHierarchySummary($user);
        $subResellers = $this->service->getSubResellerQueueSummary($user, $filterUserId);
        $providers = $this->service->getProviderTrafficSummary($user, $filterUserId);
        $routes = $this->service->getRouteTrafficSummary($user, $filterUserId);
        $campaigns = $this->service->getCampaignTrafficSummary($user, $filterUserId);
        $breadcrumbs = $filterUserId ? $this->service->getUserHierarchyBreadcrumbs($filterUserId) : [];
        $customerQueue = $filterUserId ? $this->service->getCustomerSmsQueueDetails($filterUserId, $statusFilter) : null;

        return response()->json([
            'success'        => true,
            'timestamp'      => now()->format('H:i:s'),
            'workers'        => $workers,
            'metrics'        => $metrics,
            'resellers'      => $resellerSummary,
            'sub_resellers'  => $subResellers,
            'providers'      => $providers,
            'routes'         => $routes,
            'campaigns'      => $campaigns,
            'breadcrumbs'    => $breadcrumbs,
            'customer_queue' => $customerQueue,
        ]);
    }

    /**
     * Inspect rich drill-down metrics for a specific user in Reseller downline.
     */
    public function inspectUser(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        if (!$this->service->validateAccountAccess($user, $id)) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized downline account access.'),
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
        $sms = Sms::find($id);
        if (!$sms || !$this->service->validateAccountAccess(auth()->user(), $sms->user_id)) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized access or SMS not found.'),
            ], 403);
        }

        if ($sms->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => __('SMS is no longer pending in queue.'),
            ], 400);
        }

        $sms->status = 'cancelled';
        $sms->notes = 'Cancelled from queue by Reseller (' . (auth()->user()->username ?? auth()->user()->name) . ')';
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
        $sms = Sms::with('smsgateway')->find($id);
        if (!$sms || !$this->service->validateAccountAccess(auth()->user(), $sms->user_id)) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized access or SMS not found.'),
            ], 403);
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
