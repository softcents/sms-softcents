@extends('layouts.users.master')

@section('title', __('My Live SMS Queue Monitoring'))

@push('css')
<style>
    .erp-queue-monitor-section .card,
    .erp-queue-monitor-section .cards,
    .erp-queue-monitor-section .couter-box {
        min-height: auto !important;
        height: auto;
    }

    .erp-queue-monitor-section {
        margin-top: 20px;
        margin-bottom: 40px;
        font-family: "Manrope", sans-serif;
    }

    .erp-queue-monitor-section .page-header-wrap {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 22px;
        box-shadow: 0px 4px 20px rgba(195, 208, 228, 0.25);
        margin-bottom: 20px;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .erp-queue-monitor-section .pulse-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-green 1.8s infinite;
        vertical-align: middle;
        margin-right: 6px;
    }

    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .erp-queue-monitor-section .kpi-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: 0px 4px 18px rgba(195, 208, 228, 0.2);
        border: 1px solid rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }
    .erp-queue-monitor-section .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0px 8px 25px rgba(195, 208, 228, 0.35);
    }
    .erp-queue-monitor-section .kpi-card .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .erp-queue-monitor-section .kpi-card .kpi-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .erp-queue-monitor-section .kpi-card .kpi-val {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .erp-queue-monitor-section .kpi-card .kpi-sub {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 6px;
    }

    .erp-queue-monitor-section .panel-box {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0px 4px 20px rgba(195, 208, 228, 0.2);
        border: 1px solid rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .erp-queue-monitor-section .panel-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .erp-queue-monitor-section .panel-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .erp-queue-monitor-section .table-modern {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .erp-queue-monitor-section .table-modern thead th {
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border: none;
        white-space: nowrap;
    }
    .erp-queue-monitor-section .table-modern tbody td {
        padding: 12px 16px;
        font-size: 13px;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .erp-queue-monitor-section .table-modern tbody tr:last-child td {
        border-bottom: none;
    }
    .erp-queue-monitor-section .table-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    .badge-soft-success { background: #dcfce7; color: #15803d; border-radius: 6px; padding: 4px 8px; font-weight: 600; font-size: 11px; }
    .badge-soft-warning { background: #fef3c7; color: #b45309; border-radius: 6px; padding: 4px 8px; font-weight: 600; font-size: 11px; }
    .badge-soft-danger  { background: #fee2e2; color: #b91c1c; border-radius: 6px; padding: 4px 8px; font-weight: 600; font-size: 11px; }
    .badge-soft-info    { background: #e0f2fe; color: #0369a1; border-radius: 6px; padding: 4px 8px; font-weight: 600; font-size: 11px; }
    .badge-soft-primary { background: #e0e7ff; color: #4338ca; border-radius: 6px; padding: 4px 8px; font-weight: 600; font-size: 11px; }
    .badge-soft-secondary { background: #f1f5f9; color: #475569; border-radius: 6px; padding: 4px 8px; font-weight: 600; font-size: 11px; }

    .stream-row-new {
        animation: highlight-row 1.5s ease-out;
    }
    @keyframes highlight-row {
        0% { background-color: #ecfdf5; }
        100% { background-color: transparent; }
    }
</style>
@endpush

@section('main_content')
<div class="erp-queue-monitor-section">
    <div class="container-fluid">

        <!-- Top Header & Live Status Bar -->
        <div class="page-header-wrap">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="pulse-indicator" id="live-indicator"></span>
                        <h4 class="m-0 fw-bold text-dark">{{ __('My Live SMS Queue Monitoring') }}</h4>
                        <span class="badge badge-soft-success ms-1" style="font-size: 11px;">REAL-TIME</span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3 mt-1" style="font-size: 13px; color: #64748b;">
                        <span><i class="fas fa-user-circle me-1"></i> Account: <strong>{{ auth()->user()->name }}</strong> ({{ '@' . (auth()->user()->username ?? auth()->user()->name) }})</span>
                        <span>•</span>
                        <span><i class="fas fa-wallet text-success me-1"></i> Balance: <strong class="text-success">৳{{ number_format(auth()->user()->balance ?? 0, 2) }}</strong></span>
                        <span>•</span>
                        <span><i class="fas fa-broadcast-tower me-1"></i> Engine: <strong class="text-success">Active & Dispatching</strong></span>
                        <span>•</span>
                        <span><i class="fas fa-tachometer-alt me-1 text-info"></i> Speed: <strong id="val-tps" class="text-info">{{ $metrics['current_tps'] }}</strong> TPS</span>
                        <span>•</span>
                        <span><i class="fas fa-check-circle me-1 text-success"></i> Delivery Rate: <strong id="val-delivery-rate" class="text-success">{{ $metrics['delivery_rate'] }}%</strong></span>
                        <span>•</span>
                        <span><i class="far fa-clock me-1"></i> Last Synced: <strong id="last-synced-time">{{ now()->format('H:i:s') }}</strong></span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Refresh Interval">
                        <button type="button" class="btn btn-outline-secondary interval-btn active" data-interval="2000">2s</button>
                        <button type="button" class="btn btn-outline-secondary interval-btn" data-interval="5000">5s</button>
                        <button type="button" class="btn btn-outline-secondary interval-btn" data-interval="10000">10s</button>
                        <button type="button" class="btn btn-outline-secondary interval-btn" data-interval="0">{{ __('Pause') }}</button>
                    </div>

                    <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-1 shadow-sm" id="btn-refresh-now">
                        <i class="fas fa-sync-alt" id="refresh-spinner"></i>
                        <span>{{ __('Refresh') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 12 KPI Cards Row -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Queued SMS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Total Queued SMS') }}</div>
                            <div class="kpi-val text-primary" id="val-total-queued">{{ number_format($metrics['pending_count']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #e0e7ff; color: #4f46e5;">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>{{ __('In Queue Pipeline') }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Pending SMS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Pending SMS') }}</div>
                            <div class="kpi-val text-warning" id="val-pending-count">{{ number_format($metrics['pending_count']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #ede9fe; color: #7c3aed;">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>{{ __('Waiting for Gateway Delivery') }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. Processing SMS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Processing SMS') }}</div>
                            <div class="kpi-val text-info" id="val-processing-count">{{ number_format($metrics['processing_count'] ?? 0) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                            <i class="fas fa-cog fa-spin"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>{{ __('Currently In-Flight / Dispatching') }}</span>
                    </div>
                </div>
            </div>

            <!-- 4. Submitted SMS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Submitted SMS') }}</div>
                            <div class="kpi-val" id="val-submitted-count" style="color: #0284c7;">{{ number_format($metrics['today_submitted']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Today: <strong id="val-today-submitted">{{ number_format($metrics['today_submitted']) }}</strong> • All: <strong id="val-total-submitted">{{ number_format($metrics['submitted_count']) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 5. Delivered SMS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Delivered SMS') }}</div>
                            <div class="kpi-val text-success" id="val-delivered-count">{{ number_format($metrics['today_delivered']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #dcfce7; color: #16a34a;">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Today: <strong id="val-today-delivered">{{ number_format($metrics['today_delivered']) }}</strong> • All: <strong id="val-total-delivered">{{ number_format($metrics['delivered_count']) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 6. Failed SMS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Failed SMS') }}</div>
                            <div class="kpi-val text-danger" id="val-today-failed">{{ number_format($metrics['today_failed']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #fee2e2; color: #dc2626;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Today: <strong id="val-sub-today-failed">{{ number_format($metrics['today_failed']) }}</strong> • All: <strong id="val-total-failed">{{ number_format($metrics['failed_count']) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 7. Rejected SMS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Rejected SMS') }}</div>
                            <div class="kpi-val" id="val-today-rejected" style="color: #e11d48;">{{ number_format($metrics['today_rejected'] ?? 0) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #ffe4e6; color: #e11d48;">
                            <i class="fas fa-ban"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Today: <strong id="val-sub-today-rejected">{{ number_format($metrics['today_rejected'] ?? 0) }}</strong> • All: <strong id="val-total-rejected">{{ number_format($metrics['rejected_count'] ?? 0) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 8. Expired SMS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Expired SMS') }}</div>
                            <div class="kpi-val" id="val-today-expired" style="color: #64748b;">{{ number_format($metrics['today_expired'] ?? 0) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #f1f5f9; color: #64748b;">
                            <i class="fas fa-hourglass-end"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Today: <strong id="val-sub-today-expired">{{ number_format($metrics['today_expired'] ?? 0) }}</strong> • All: <strong id="val-total-expired">{{ number_format($metrics['expired_count'] ?? 0) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 9. Retry Queue -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Retry Queue') }}</div>
                            <div class="kpi-val" id="val-retry-queue" style="color: #ea580c;">{{ number_format($metrics['retry_queue_count'] ?? 0) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #ffedd5; color: #ea580c;">
                            <i class="fas fa-redo-alt"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>{{ __('Awaiting Re-dispatch') }}</span>
                    </div>
                </div>
            </div>

            <!-- 10. Current TPS (Transactions Per Second) -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Current TPS') }}</div>
                            <div class="kpi-val text-info"><span id="val-kpi-tps">{{ $metrics['current_tps'] }}</span> <small style="font-size: 13px; font-weight: 600;">TPS</small></div>
                        </div>
                        <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>1m: <strong id="val-kpi-1m">{{ number_format($metrics['last_1_min_volume']) }}</strong> • 5m: <strong id="val-kpi-5m">{{ number_format($metrics['last_5_min_volume']) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 11. Active Queue Workers -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Active Queue Workers') }}</div>
                            <div class="kpi-val" style="color: #059669;"><span id="val-kpi-workers">{{ $metrics['active_workers'] ?? ($workers['active_workers'] ?? 8) }}</span> <small style="font-size: 13px; font-weight: 600; color: #64748b;">/ {{ $metrics['expected_workers'] ?? ($workers['expected_workers'] ?? 8) }}</small></div>
                        </div>
                        <div class="kpi-icon" style="background: #d1fae5; color: #059669;">
                            <i class="fas fa-cogs"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span class="badge bg-success" id="val-kpi-worker-badge" style="font-size: 11px;">{{ ucfirst($metrics['worker_status'] ?? ($workers['status'] ?? 'Healthy')) }}</span> <span class="text-muted ms-1">{{ __('Processing Engine') }}</span>
                    </div>
                </div>
            </div>

            <!-- 12. Queue Backlog -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Queue Backlog') }}</div>
                            <div class="kpi-val" id="val-queue-backlog" style="color: #6366f1;">{{ number_format($metrics['queue_backlog'] ?? $metrics['pending_count']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #ede9fe; color: #6366f1;">
                            <i class="fas fa-stream"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Drain: <strong id="val-backlog-drain">{{ $metrics['drain_eta'] ?? 'Optimal Flow' }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dedicated Outgoing SMS Queue & Message Actions Panel -->
        <div class="panel-box mb-4" id="panel-my-queue">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #e0e7ff; color: #4338ca;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __('My Outgoing SMS Queue & Message Dispatches') }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Live inspection of your queued messages, wait latency, status breakdown, and instant cancel / retry actions') }}
                        </small>
                    </div>
                </div>

                <div class="d-flex align-items-center flex-wrap gap-2">
                    <!-- Queue Status Filter Tabs -->
                    <div class="btn-group btn-group-sm" role="group" aria-label="Queue Status Filter">
                        <button type="button" class="btn btn-outline-primary cust-status-tab-btn active" data-status="all">{{ __('All Messages') }}</button>
                        <button type="button" class="btn btn-outline-warning cust-status-tab-btn" data-status="pending">{{ __('Pending Queue') }}</button>
                        <button type="button" class="btn btn-outline-info cust-status-tab-btn" data-status="processing">{{ __('Processing') }}</button>
                        <button type="button" class="btn btn-outline-success cust-status-tab-btn" data-status="delivered">{{ __('Delivered') }}</button>
                        <button type="button" class="btn btn-outline-danger cust-status-tab-btn" data-status="failed">{{ __('Failed') }}</button>
                    </div>

                    <!-- Bulk Actions -->
                    <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" id="btn-cancel-all-pending" title="{{ __('Cancel all pending messages') }}" style="font-size: 11px; font-weight: 600;">
                        <i class="fas fa-ban me-1"></i> {{ __('Cancel All Pending') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2" id="btn-retry-all-failed" title="{{ __('Retry all failed messages') }}" style="font-size: 11px; font-weight: 600;">
                        <i class="fas fa-redo me-1"></i> {{ __('Retry All Failed') }}
                    </button>
                </div>
            </div>

            <!-- Mini Summary Counters for Customer Queue -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-md col-sm-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PENDING IN QUEUE') }}</span>
                            <span class="fs-5 fw-bold text-warning" id="cust-kpi-pending">{{ number_format($customerQueue['summary']['pending_queue'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Awaiting Dispatch') }}</small>
                        </div>
                    </div>
                    <div class="col-md col-sm-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PROCESSING (RAM)') }}</span>
                            <span class="fs-5 fw-bold text-info" id="cust-kpi-processing">{{ number_format($customerQueue['summary']['processing_queue'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('In-Flight Concurrency') }}</small>
                        </div>
                    </div>
                    <div class="col-md col-sm-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DELIVERED TODAY') }}</span>
                            <span class="fs-5 fw-bold text-success" id="cust-kpi-delivered">{{ number_format($customerQueue['summary']['today_delivered'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Handset Confirmed') }}</small>
                        </div>
                    </div>
                    <div class="col-md col-sm-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('FAILED TODAY') }}</span>
                            <span class="fs-5 fw-bold text-danger" id="cust-kpi-failed">{{ number_format($customerQueue['summary']['today_failed'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Errors / Undelivered') }}</small>
                        </div>
                    </div>
                    <div class="col-md col-sm-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DELIVERY RATE') }}</span>
                            <span class="fs-5 fw-bold text-success" id="cust-kpi-rate">{{ $customerQueue['summary']['delivery_rate'] ?? 100 }}%</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Success Ratio') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Message Dispatches Table -->
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-my-queue">
                    <thead>
                        <tr>
                            <th>{{ __('Queued Time') }}</th>
                            <th>{{ __('Recipient') }}</th>
                            <th>{{ __('Sender ID') }}</th>
                            <th>{{ __('Operator') }}</th>
                            <th>{{ __('Route / Gateway') }}</th>
                            <th class="text-center">{{ __('Total Parts') }}</th>
                            <th class="text-center">{{ __('Charge') }}</th>
                            <th>{{ __('Status & Response') }}</th>
                            <th class="text-center">{{ __('Latency') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-my-queue">
                        @forelse($customerQueue['messages'] ?? [] as $msg)
                        <tr data-sms-id="{{ $msg['id'] }}">
                            <td class="text-muted" style="font-size: 11px;">
                                <i class="far fa-clock me-1"></i> {{ $msg['queued_at'] }}
                            </td>
                            <td>
                                <strong class="text-dark">{{ $msg['recipient'] }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-soft-primary px-2 py-1">{{ $msg['sender'] }}</span>
                            </td>
                            <td>
                                <span class="badge badge-soft-secondary">{{ $msg['operator'] }}</span>
                            </td>
                            <td class="text-muted" style="font-size: 12px;">{{ $msg['gateway'] }}</td>
                            <td class="text-center fw-semibold">{{ $msg['total_parts'] }} Part(s)</td>
                            <td class="text-center fw-bold text-dark">৳{{ number_format($msg['charge'], 2) }}</td>
                            <td>
                                <span class="badge {{ $msg['status_badge'] }}">
                                    {{ $msg['status'] }}
                                </span>
                                <small class="text-muted d-block mt-1" style="font-size: 10px;">{{ $msg['response_code'] }}</small>
                            </td>
                            <td class="text-center text-muted" style="font-size: 11px;">
                                <i class="fas fa-stopwatch me-1 text-info"></i> {{ $msg['latency'] }}
                            </td>
                            <td class="text-end text-nowrap">
                                @if($msg['can_cancel'])
                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-cancel-sms" data-id="{{ $msg['id'] }}" title="{{ __('Cancel this pending SMS') }}" style="font-size: 11px;">
                                    <i class="fas fa-times me-1"></i> {{ __('Cancel') }}
                                </button>
                                @elseif($msg['can_retry'])
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btn-retry-sms" data-id="{{ $msg['id'] }}" title="{{ __('Re-queue failed SMS') }}" style="font-size: 11px;">
                                    <i class="fas fa-redo-alt me-1"></i> {{ __('Retry') }}
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-light border py-0 px-2 btn-view-sms" data-id="{{ $msg['id'] }}" data-content="{{ rawurlencode($msg['content']) }}" data-recipient="{{ $msg['recipient'] }}" data-sender="{{ $msg['sender'] }}" data-time="{{ $msg['queued_at'] }}" data-status="{{ $msg['status'] }}" data-charge="{{ number_format($msg['charge'], 2) }}" title="{{ __('View message content') }}" style="font-size: 11px;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="fas fa-check-circle text-success fs-3 d-block mb-2"></i>
                                {{ __('No queued or dispatched SMS found for this status.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Campaign-Wise SMS Statistics & Queue Progress Panel -->
        <div class="panel-box mb-4" id="panel-campaigns">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(220, 53, 69, 0.12); color: #dc3545;">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __('My Bulk Broadcast Campaigns & Dispatch Tracker') }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Real-time recipient completion velocity, worker dispatch progress, and queue backlog for your campaigns') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-danger ms-1"><span id="campaigns-count">{{ count($campaigns ?? []) }}</span> {{ __('Campaigns Monitored') }}</span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="max-width: 240px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-sm border-start-0" id="input-search-campaigns" placeholder="{{ __('Search my campaigns...') }}" style="font-size: 12px;">
                    </div>
                </div>
            </div>

            <!-- Aggregated Mini Counters for Campaigns -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('ACTIVE CAMPAIGNS') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="camp-agg-total">{{ $campaignAggregates['total_campaigns'] ?? 0 }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Broadcast Batches') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('TOTAL RECIPIENTS') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="camp-agg-recipients">{{ number_format($campaignAggregates['total_recipients'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Target Mobile Contacts') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PENDING IN QUEUE') }}</span>
                            <span class="fs-5 fw-bold {{ ($campaignAggregates['pending_queue'] ?? 0) > 0 ? 'text-warning' : 'text-muted' }}" id="camp-agg-pending">{{ number_format($campaignAggregates['pending_queue'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Awaiting Worker RAM') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PROCESSING (RAM)') }}</span>
                            <span class="fs-5 fw-bold {{ ($campaignAggregates['processing_queue'] ?? 0) > 0 ? 'text-info' : 'text-muted' }}" id="camp-agg-processing">{{ number_format($campaignAggregates['processing_queue'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Active Dispatches') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DELIVERED SMS') }}</span>
                            <span class="fs-5 fw-bold text-success" id="camp-agg-delivered">{{ number_format($campaignAggregates['delivered_count'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;"><span class="text-danger fw-bold">{{ __('Failed') }}: <span id="camp-agg-failed">{{ number_format($campaignAggregates['failed_count'] ?? 0) }}</span></span></small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('AVG PROGRESS') }}</span>
                            <span class="fs-5 fw-bold text-primary" id="camp-agg-progress">{{ $campaignAggregates['avg_progress'] ?? 0 }}%</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Completion Velocity') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-campaigns">
                    <thead>
                        <tr>
                            <th>{{ __('Campaign & ID') }}</th>
                            <th class="text-center">{{ __('Sender ID') }}</th>
                            <th class="text-center">{{ __('Recipients') }}</th>
                            <th class="text-center">{{ __('Pending') }}</th>
                            <th class="text-center">{{ __('Processing') }}</th>
                            <th class="text-center">{{ __('Delivered') }}</th>
                            <th class="text-center">{{ __('Failed') }}</th>
                            <th class="text-center" style="min-width: 140px;">{{ __('Progress') }}</th>
                            <th class="text-end">{{ __('Status / Time') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-campaigns">
                        @forelse($campaigns ?? [] as $camp)
                        <tr data-campaign-id="{{ $camp['id'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background: rgba(220, 53, 69, 0.12); color: #dc3545; font-size: 12px;">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                    <span>{{ $camp['name'] }}</span>
                                </div>
                                <small class="text-muted ms-4">ID: #{{ $camp['id'] }} @if(!empty($camp['charges'])) • ৳{{ $camp['charges'] }} @endif</small>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-primary px-2 py-1">{{ $camp['sender'] }}</span>
                            </td>
                            <td class="text-center fw-bold text-dark">{{ number_format($camp['total_recipients']) }}</td>
                            <td class="text-center fw-bold {{ $camp['pending_queue'] > 0 ? 'text-warning' : 'text-muted' }}">
                                {{ number_format($camp['pending_queue']) }}
                            </td>
                            <td class="text-center fw-bold {{ $camp['processing_queue'] > 0 ? 'text-info' : 'text-muted' }}">
                                {{ number_format($camp['processing_queue']) }}
                            </td>
                            <td class="text-center fw-bold {{ $camp['delivered_count'] > 0 ? 'text-success' : 'text-muted' }}">
                                {{ number_format($camp['delivered_count']) }}
                            </td>
                            <td class="text-center fw-bold {{ $camp['failed_count'] > 0 ? 'text-danger' : 'text-muted' }}">
                                {{ number_format($camp['failed_count']) }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="fw-bold">{{ $camp['progress_percent'] }}%</span>
                                    <small class="text-muted">{{ $camp['delivery_rate'] }}% rate</small>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar {{ $camp['progress_percent'] >= 100 ? 'bg-success' : 'bg-primary' }}" role="progressbar" style="width: {{ $camp['progress_percent'] }}%;" aria-valuenow="{{ $camp['progress_percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-end">
                                <span class="badge {{ $camp['status_badge'] }}">
                                    {{ $camp['status'] }}
                                </span>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">{{ $camp['created_at'] }}</small>
                            </td>
                            <td class="text-end text-nowrap">
                                @if($camp['pending_queue'] > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 btn-cancel-campaign-pending" data-id="{{ $camp['id'] }}" data-name="{{ $camp['name'] }}" data-count="{{ $camp['pending_queue'] }}" title="{{ __('Cancel pending SMS in this campaign') }}" style="font-size: 11px;">
                                    <i class="fas fa-ban me-1"></i> {{ __('Cancel Pending') }}
                                </button>
                                @else
                                <span class="text-muted" style="font-size: 11px;"><i class="fas fa-check text-success me-1"></i> Completed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="fas fa-bullhorn text-muted fs-3 d-block mb-2"></i>
                                {{ __('No broadcast campaigns in queue. System ready for new campaigns.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Middle Section: Operator Distribution & Live Stream -->
        <div class="row g-4 mb-4">
            <!-- Operator Breakdown Donut Chart -->
            <div class="col-lg-5 col-12">
                <div class="panel-box h-100">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-chart-pie text-success"></i>
                            {{ __('My Recipient Operator Breakdown (24h)') }}
                        </h5>
                    </div>
                    <div class="p-3 d-flex flex-column align-items-center justify-content-center" style="min-height: 240px;">
                        <div style="position: relative; width: 100%; max-width: 220px; height: 180px;">
                            <canvas id="operatorChart"></canvas>
                        </div>
                        <div class="d-flex flex-wrap justify-content-center gap-3 mt-3 w-100" style="font-size: 12px;" id="operator-legend">
                            <span class="d-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 2px; background: #0ea5e9;"></span> GP: <strong id="leg-gp">{{ $metrics['operators']['GP'] }}</strong></span>
                            <span class="d-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 2px; background: #f97316;"></span> BL: <strong id="leg-bl">{{ $metrics['operators']['BL'] }}</strong></span>
                            <span class="d-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 2px; background: #e11d48;"></span> Robi: <strong id="leg-robi">{{ $metrics['operators']['Robi'] }}</strong></span>
                            <span class="d-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 2px; background: #16a34a;"></span> TT: <strong id="leg-tt">{{ $metrics['operators']['Teletalk'] }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Outgoing SMS Stream -->
            <div class="col-lg-7 col-12">
                <div class="panel-box h-100">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-stream text-primary"></i>
                            {{ __('My Live Outgoing SMS Stream') }}
                        </h5>
                        <span class="badge badge-soft-success"><i class="fas fa-satellite-dish me-1"></i> {{ __('Live Stream') }}</span>
                    </div>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-modern align-middle" id="table-stream">
                            <thead>
                                <tr>
                                    <th>{{ __('Time') }}</th>
                                    <th>{{ __('Recipient') }}</th>
                                    <th>{{ __('Sender ID') }}</th>
                                    <th>{{ __('Operator') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Gateway Response') }}</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-stream">
                                @forelse($metrics['live_stream'] as $sms)
                                <tr data-id="{{ $sms['id'] }}">
                                    <td class="text-muted" style="font-size: 12px;">{{ $sms['time'] }}</td>
                                    <td class="fw-semibold text-dark">{{ $sms['recipient'] }}</td>
                                    <td class="text-muted" style="font-size: 12px;">{{ $sms['sender'] }}</td>
                                    <td><span class="badge badge-soft-primary">{{ $sms['operator'] }}</span></td>
                                    <td>
                                        @if($sms['status'] === 'success')
                                            <span class="badge badge-soft-success">{{ __('Success') }}</span>
                                        @elseif($sms['status'] === 'pending')
                                            <span class="badge badge-soft-warning">{{ __('Queued') }}</span>
                                        @else
                                            <span class="badge badge-soft-danger">{{ ucfirst($sms['status']) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="font-size: 12px;">{{ $sms['response_code'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">{{ __('No dispatched traffic detected yet.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal: View Message Content -->
<div class="modal fade" id="modal-view-sms" tabindex="-1" aria-labelledby="modal-view-sms-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-view-sms-label">
                    <i class="fas fa-envelope-open-text text-primary me-2"></i> {{ __('SMS Message Details') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 d-flex flex-wrap gap-2 justify-content-between bg-light p-2 rounded" style="font-size: 12px;">
                    <div><strong>Recipient:</strong> <span id="modal-sms-recipient"></span></div>
                    <div><strong>Sender:</strong> <span id="modal-sms-sender"></span></div>
                    <div><strong>Time:</strong> <span id="modal-sms-time"></span></div>
                    <div><strong>Status:</strong> <span id="modal-sms-status"></span></div>
                    <div><strong>Charge:</strong> ৳<span id="modal-sms-charge"></span></div>
                </div>
                <label class="form-label fw-bold text-dark">{{ __('SMS Text Content') }}</label>
                <div class="p-3 border rounded bg-light" id="modal-sms-content" style="white-space: pre-wrap; word-break: break-word; font-size: 13px; font-family: monospace;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    let pollingInterval = 2000;
    let pollTimer = null;
    let isFetching = false;
    let currentQueueStatusFilter = 'all';
    let currentCustomerQueueData = @json($customerQueue ?? []);
    let currentCampaignsData = @json($campaigns ?? []);
    let currentCampaignAggregates = @json($campaignAggregates ?? []);
    let currentCampaignSearchTerm = '';

    // 1. Operator Chart
    const opCtx = document.getElementById('operatorChart');
    let operatorChart = null;
    if (opCtx) {
        operatorChart = new Chart(opCtx, {
            type: 'doughnut',
            data: {
                labels: ['GP', 'BL', 'Robi', 'Teletalk', 'Other'],
                datasets: [{
                    data: [
                        {{ (int)($metrics['operators']['GP'] ?? 0) }},
                        {{ (int)($metrics['operators']['BL'] ?? 0) }},
                        {{ (int)($metrics['operators']['Robi'] ?? 0) }},
                        {{ (int)($metrics['operators']['Teletalk'] ?? 0) }},
                        {{ (int)($metrics['operators']['Other'] ?? 0) }}
                    ],
                    backgroundColor: ['#0ea5e9', '#f97316', '#e11d48', '#16a34a', '#94a3b8'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '68%'
            }
        });
    }

    // 2. Fetch Live Data
    function fetchLiveData() {
        if (isFetching) return;
        isFetching = true;
        $('#refresh-spinner').addClass('fa-spin');

        $.ajax({
            url: "{{ route('users.queue-monitor.data') }}",
            type: "GET",
            data: {
                status_filter: currentQueueStatusFilter
            },
            dataType: "json",
            timeout: 5000,
            success: function(res) {
                if (!res.success) return;

                $('#last-synced-time').text(res.timestamp);

                // KPI counters
                $('#val-total-queued').text(Number(res.metrics.pending_count || 0).toLocaleString());
                $('#val-queue-backlog').text(Number(res.metrics.queue_backlog ?? res.metrics.pending_count ?? 0).toLocaleString());
                $('#val-backlog-drain').text(res.metrics.drain_eta || 'Optimal Flow');
                $('#val-pending-count').text(Number(res.metrics.pending_count || 0).toLocaleString());
                $('#val-processing-count').text(Number(res.metrics.processing_count || 0).toLocaleString());
                $('#val-submitted-count').text(Number(res.metrics.today_submitted || 0).toLocaleString());
                $('#val-today-submitted').text(Number(res.metrics.today_submitted || 0).toLocaleString());
                $('#val-total-submitted').text(Number(res.metrics.submitted_count || 0).toLocaleString());
                $('#val-delivered-count').text(Number(res.metrics.today_delivered || 0).toLocaleString());
                $('#val-today-delivered').text(Number(res.metrics.today_delivered || 0).toLocaleString());
                $('#val-total-delivered').text(Number(res.metrics.delivered_count || 0).toLocaleString());
                $('#val-tps').text(res.metrics.current_tps);
                $('#val-1m').text(Number(res.metrics.last_1_min_volume || 0).toLocaleString());
                $('#val-5m').text(Number(res.metrics.last_5_min_volume || 0).toLocaleString());
                $('#val-today-total').text(Number(res.metrics.today_total || 0).toLocaleString());
                $('#val-today-success').text(Number(res.metrics.today_success || 0).toLocaleString());
                $('#val-today-failed').text(Number(res.metrics.today_failed || 0).toLocaleString());
                $('#val-sub-today-failed').text(Number(res.metrics.today_failed || 0).toLocaleString());
                $('#val-total-failed').text(Number(res.metrics.failed_count || 0).toLocaleString());
                $('#val-today-rejected').text(Number(res.metrics.today_rejected || 0).toLocaleString());
                $('#val-sub-today-rejected').text(Number(res.metrics.today_rejected || 0).toLocaleString());
                $('#val-total-rejected').text(Number(res.metrics.rejected_count || 0).toLocaleString());
                $('#val-today-expired').text(Number(res.metrics.today_expired || 0).toLocaleString());
                $('#val-sub-today-expired').text(Number(res.metrics.today_expired || 0).toLocaleString());
                $('#val-total-expired').text(Number(res.metrics.expired_count || 0).toLocaleString());
                $('#val-retry-queue').text(Number(res.metrics.retry_queue_count || 0).toLocaleString());
                $('#val-kpi-tps').text(res.metrics.current_tps);
                $('#val-kpi-1m').text(Number(res.metrics.last_1_min_volume || 0).toLocaleString());
                $('#val-kpi-5m').text(Number(res.metrics.last_5_min_volume || 0).toLocaleString());
                const activeW = (res.metrics.active_workers ?? (res.workers ? res.workers.active_workers : 8));
                $('#val-kpi-workers').text(activeW);
                const wStat = (res.metrics.worker_status ?? (res.workers ? res.workers.status : 'healthy'));
                $('#val-kpi-worker-badge').text(wStat.charAt(0).toUpperCase() + wStat.slice(1));
                $('#val-delivery-rate').text(res.metrics.delivery_rate + '%');
                $('#live-indicator').removeClass('warning danger');

                // Operator Chart
                if (operatorChart && res.metrics.operators) {
                    const ops = res.metrics.operators;
                    operatorChart.data.datasets[0].data = [
                        ops.GP || 0,
                        ops.BL || 0,
                        ops.Robi || 0,
                        ops.Teletalk || 0,
                        ops.Other || 0
                    ];
                    operatorChart.update('none');
                    $('#leg-gp').text(ops.GP || 0);
                    $('#leg-bl').text(ops.BL || 0);
                    $('#leg-robi').text(ops.Robi || 0);
                    $('#leg-tt').text(ops.Teletalk || 0);
                }

                // Render Customer Own Queue Table
                renderCustomerQueueTable(res.customer_queue);

                // Render Customer Own Campaigns
                renderCampaignsTable(res.campaigns, res.campaign_aggregates);

                // Live Stream
                renderStreamTable(res.metrics.live_stream);
            },
            error: function(err) {
                console.warn('Queue monitor poll failed:', err);
                $('#live-indicator').addClass('warning');
            },
            complete: function() {
                isFetching = false;
                $('#refresh-spinner').removeClass('fa-spin');
            }
        });
    }

    // 3. Render Customer Queue Table & Mini Counters
    function renderCustomerQueueTable(cq) {
        if (!cq) return;
        currentCustomerQueueData = cq;
        const s = cq.summary || {};
        const msgs = cq.messages || [];

        // Mini KPI counters
        $('#cust-kpi-pending').text(Number(s.pending_queue || 0).toLocaleString());
        $('#cust-kpi-processing').text(Number(s.processing_queue || 0).toLocaleString());
        $('#cust-kpi-delivered').text(Number(s.today_delivered || 0).toLocaleString());
        $('#cust-kpi-failed').text(Number(s.today_failed || 0).toLocaleString());
        $('#cust-kpi-rate').text((s.delivery_rate || 100) + '%');

        if (!msgs.length) {
            $('#tbody-my-queue').html(`
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">
                        <i class="fas fa-check-circle text-success fs-3 d-block mb-2"></i>
                        No queued or dispatched SMS found for this status.
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        msgs.forEach(function(msg) {
            let actionHtml = '';
            if (msg.can_cancel) {
                actionHtml += `
                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-cancel-sms" data-id="${msg.id}" title="Cancel this pending SMS" style="font-size: 11px;">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                `;
            } else if (msg.can_retry) {
                actionHtml += `
                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btn-retry-sms" data-id="${msg.id}" title="Re-queue failed SMS" style="font-size: 11px;">
                        <i class="fas fa-redo-alt me-1"></i> Retry
                    </button>
                `;
            }
            actionHtml += `
                <button type="button" class="btn btn-sm btn-light border py-0 px-2 btn-view-sms" data-id="${msg.id}" data-content="${encodeURIComponent(msg.content)}" data-recipient="${msg.recipient}" data-sender="${msg.sender}" data-time="${msg.queued_at}" data-status="${msg.status}" data-charge="${Number(msg.charge).toFixed(2)}" title="View message content" style="font-size: 11px;">
                    <i class="fas fa-eye"></i>
                </button>
            `;

            html += `
                <tr data-sms-id="${msg.id}">
                    <td class="text-muted" style="font-size: 11px;">
                        <i class="far fa-clock me-1"></i> ${msg.queued_at}
                    </td>
                    <td>
                        <strong class="text-dark">${msg.recipient}</strong>
                    </td>
                    <td>
                        <span class="badge badge-soft-primary px-2 py-1">${msg.sender}</span>
                    </td>
                    <td>
                        <span class="badge badge-soft-secondary">${msg.operator}</span>
                    </td>
                    <td class="text-muted" style="font-size: 12px;">${msg.gateway}</td>
                    <td class="text-center fw-semibold">${msg.total_parts} Part(s)</td>
                    <td class="text-center fw-bold text-dark">৳${Number(msg.charge).toFixed(2)}</td>
                    <td>
                        <span class="badge ${msg.status_badge}">
                            ${msg.status}
                        </span>
                        <small class="text-muted d-block mt-1" style="font-size: 10px;">${msg.response_code}</small>
                    </td>
                    <td class="text-center text-muted" style="font-size: 11px;">
                        <i class="fas fa-stopwatch me-1 text-info"></i> ${msg.latency}
                    </td>
                    <td class="text-end text-nowrap">
                        ${actionHtml}
                    </td>
                </tr>
            `;
        });
        $('#tbody-my-queue').html(html);
    }

    // 4. Render Campaigns Table & Aggregates
    function renderCampaignsTable(campaigns, aggregates) {
        if (campaigns) currentCampaignsData = campaigns;
        if (aggregates) currentCampaignAggregates = aggregates;

        const allCamps = currentCampaignsData || [];
        const agg = currentCampaignAggregates || {};

        let filtered = allCamps.slice();

        if (currentCampaignSearchTerm.trim() !== '') {
            const term = currentCampaignSearchTerm.toLowerCase();
            filtered = filtered.filter(function(c) {
                const name = (c.name || '').toLowerCase();
                const sender = (c.sender || '').toLowerCase();
                const cId = String(c.id || '');
                return name.includes(term) || sender.includes(term) || cId.includes(term);
            });
        }

        $('#campaigns-count').text(filtered.length);
        $('#camp-agg-total').text(agg.total_campaigns || 0);
        $('#camp-agg-recipients').text(Number(agg.total_recipients || 0).toLocaleString());
        $('#camp-agg-pending').text(Number(agg.pending_queue || 0).toLocaleString());
        $('#camp-agg-processing').text(Number(agg.processing_queue || 0).toLocaleString());
        $('#camp-agg-delivered').text(Number(agg.delivered_count || 0).toLocaleString());
        $('#camp-agg-failed').text(Number(agg.failed_count || 0).toLocaleString());
        $('#camp-agg-progress').text((agg.avg_progress || 0) + '%');

        if (!filtered.length) {
            $('#tbody-campaigns').html(`
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">
                        <i class="fas fa-bullhorn text-muted fs-3 d-block mb-2"></i>
                        No broadcast campaigns in queue. System ready for new campaigns.
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        filtered.forEach(function(camp) {
            const pendingClass = camp.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = camp.processing_queue > 0 ? 'text-info' : 'text-muted';
            const delivClass = camp.delivered_count > 0 ? 'text-success' : 'text-muted';
            const failClass = camp.failed_count > 0 ? 'text-danger' : 'text-muted';
            const progressBg = camp.progress_percent >= 100 ? 'bg-success' : 'bg-primary';
            const chargeText = camp.charges ? ` • ৳${camp.charges}` : '';

            let actionBtn = '';
            if (camp.pending_queue > 0) {
                actionBtn = `
                    <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 btn-cancel-campaign-pending" data-id="${camp.id}" data-name="${camp.name}" data-count="${camp.pending_queue}" title="Cancel pending SMS in this campaign" style="font-size: 11px;">
                        <i class="fas fa-ban me-1"></i> Cancel Pending
                    </button>
                `;
            } else {
                actionBtn = `<span class="text-muted" style="font-size: 11px;"><i class="fas fa-check text-success me-1"></i> Completed</span>`;
            }

            html += `
                <tr data-campaign-id="${camp.id}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background: rgba(220, 53, 69, 0.12); color: #dc3545; font-size: 12px;">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <span>${camp.name}</span>
                        </div>
                        <small class="text-muted ms-4">ID: #${camp.id}${chargeText}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-soft-primary px-2 py-1">${camp.sender}</span>
                    </td>
                    <td class="text-center fw-bold text-dark">${Number(camp.total_recipients).toLocaleString()}</td>
                    <td class="text-center fw-bold ${pendingClass}">${Number(camp.pending_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold ${procClass}">${Number(camp.processing_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold ${delivClass}">${Number(camp.delivered_count).toLocaleString()}</td>
                    <td class="text-center fw-bold ${failClass}">${Number(camp.failed_count).toLocaleString()}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                            <span class="fw-bold">${camp.progress_percent}%</span>
                            <small class="text-muted">${camp.delivery_rate}% rate</small>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar ${progressBg}" role="progressbar" style="width: ${camp.progress_percent}%;" aria-valuenow="${camp.progress_percent}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-end">
                        <span class="badge ${camp.status_badge}">
                            ${camp.status}
                        </span>
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">${camp.created_at}</small>
                    </td>
                    <td class="text-end text-nowrap">
                        ${actionBtn}
                    </td>
                </tr>
            `;
        });
        $('#tbody-campaigns').html(html);
    }

    // 5. Render Live Stream Table
    function renderStreamTable(stream) {
        if (!stream || !stream.length) return;
        const currentFirstId = $('#tbody-stream tr:first').data('id');
        let html = '';

        stream.forEach(function(sms) {
            const isNew = currentFirstId && sms.id > currentFirstId ? 'stream-row-new' : '';
            let statusBadge = '<span class="badge badge-soft-danger">' + sms.status + '</span>';
            if (sms.status === 'success') {
                statusBadge = '<span class="badge badge-soft-success">Success</span>';
            } else if (sms.status === 'pending') {
                statusBadge = '<span class="badge badge-soft-warning">Queued</span>';
            }

            html += `
                <tr data-id="${sms.id}" class="${isNew}">
                    <td class="text-muted" style="font-size: 12px;">${sms.time}</td>
                    <td class="fw-semibold text-dark">${sms.recipient}</td>
                    <td class="text-muted" style="font-size: 12px;">${sms.sender}</td>
                    <td><span class="badge badge-soft-primary">${sms.operator}</span></td>
                    <td>${statusBadge}</td>
                    <td class="text-muted" style="font-size: 12px;">${sms.response_code || ''}</td>
                </tr>
            `;
        });
        $('#tbody-stream').html(html);
    }

    // Queue Status Tab Filter
    $(document).on('click', '.cust-status-tab-btn', function() {
        $('.cust-status-tab-btn').removeClass('active');
        $(this).addClass('active');
        currentQueueStatusFilter = $(this).data('status');
        fetchLiveData();
    });

    // Campaign Search Filter
    $('#input-search-campaigns').on('input', function() {
        currentCampaignSearchTerm = $(this).val();
        renderCampaignsTable(currentCampaignsData, currentCampaignAggregates);
    });

    // Cancel Single Pending SMS
    $(document).on('click', '.btn-cancel-sms', function() {
        const smsId = $(this).data('id');
        Swal.fire({
            title: 'Cancel this SMS?',
            text: 'Are you sure you want to cancel queued SMS #' + smsId + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('user/queue-monitor/cancel-sms') }}/" + smsId,
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({ icon: 'success', title: 'Cancelled', text: res.message, timer: 2000, showConfirmButton: false });
                            fetchLiveData();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to cancel SMS.', 'error');
                    }
                });
            }
        });
    });

    // Retry Single Failed SMS
    $(document).on('click', '.btn-retry-sms', function() {
        const smsId = $(this).data('id');
        $.ajax({
            url: "{{ url('user/queue-monitor/retry-sms') }}/" + smsId,
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Re-queued', text: res.message, timer: 2000, showConfirmButton: false });
                    fetchLiveData();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to retry SMS.', 'error');
            }
        });
    });

    // Bulk Cancel All Pending SMS
    $('#btn-cancel-all-pending').on('click', function() {
        Swal.fire({
            title: 'Cancel All Pending SMS?',
            text: 'Are you sure you want to cancel all your pending SMS currently in the dispatch queue?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-ban me-1"></i> Yes, Cancel All'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('users.queue-monitor.cancel-pending') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({ icon: 'success', title: 'Cancelled!', text: res.message, timer: 2500, showConfirmButton: false });
                            fetchLiveData();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to cancel pending messages.', 'error');
                    }
                });
            }
        });
    });

    // Bulk Retry All Failed SMS
    $('#btn-retry-all-failed').on('click', function() {
        Swal.fire({
            title: 'Retry All Failed SMS?',
            text: 'Are you sure you want to re-queue all your failed messages back into the transmission queue?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-redo me-1"></i> Yes, Retry All'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('users.queue-monitor.retry-failed') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({ icon: 'success', title: 'Re-Queued!', text: res.message, timer: 2500, showConfirmButton: false });
                            fetchLiveData();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to retry failed messages.', 'error');
                    }
                });
            }
        });
    });

    // Cancel Campaign Pending SMS
    $(document).on('click', '.btn-cancel-campaign-pending', function() {
        const campId = $(this).data('id');
        const campName = $(this).data('name') || ('#' + campId);
        const count = $(this).data('count') || 'all';

        Swal.fire({
            title: 'Cancel Campaign Pending SMS?',
            html: `Are you sure you want to cancel <strong>${Number(count).toLocaleString()}</strong> pending SMS in campaign <strong>${campName}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-ban me-1"></i> Yes, Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('user/queue-monitor/cancel-campaign') }}/" + campId,
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({ icon: 'success', title: 'Cancelled', text: res.message, timer: 2000, showConfirmButton: false });
                            fetchLiveData();
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to cancel campaign SMS.', 'error');
                    }
                });
            }
        });
    });

    // View SMS Content Modal
    $(document).on('click', '.btn-view-sms', function() {
        const content = decodeURIComponent($(this).data('content') || '');
        const recipient = $(this).data('recipient') || 'N/A';
        const sender = $(this).data('sender') || 'N/A';
        const time = $(this).data('time') || 'N/A';
        const status = $(this).data('status') || 'N/A';
        const charge = $(this).data('charge') || '0.00';

        $('#modal-sms-recipient').text(recipient);
        $('#modal-sms-sender').text(sender);
        $('#modal-sms-time').text(time);
        $('#modal-sms-status').text(status);
        $('#modal-sms-charge').text(charge);
        $('#modal-sms-content').text(content || '(Empty content)');

        const modal = new bootstrap.Modal(document.getElementById('modal-view-sms'));
        modal.show();
    });

    // Polling Intervals
    function startPolling() {
        if (pollTimer) clearInterval(pollTimer);
        if (pollingInterval > 0) {
            pollTimer = setInterval(fetchLiveData, pollingInterval);
        }
    }

    $('.interval-btn').on('click', function() {
        $('.interval-btn').removeClass('active');
        $(this).addClass('active');
        pollingInterval = parseInt($(this).data('interval'), 10);
        startPolling();
    });

    $('#btn-refresh-now').on('click', function() {
        fetchLiveData();
    });

    // Start Poller & initial sync
    fetchLiveData();
    startPolling();

    // Auto-sync immediately when tab regains focus
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && pollingInterval > 0) {
            fetchLiveData();
        }
    });
});
</script>
@endpush
