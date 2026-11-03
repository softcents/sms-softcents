@extends('layouts.admin.master')

@section('title', __('Live SMS Monitoring (NOC)'))

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

    /* Page Header */
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

    .erp-queue-monitor-section .pulse-indicator.warning {
        background-color: #f59e0b;
        box-shadow: 0 0 0 rgba(245, 158, 11, 0.7);
        animation: pulse-amber 1.8s infinite;
    }

    .erp-queue-monitor-section .pulse-indicator.danger {
        background-color: #ef4444;
        box-shadow: 0 0 0 rgba(239, 68, 68, 0.7);
        animation: pulse-red 1.8s infinite;
    }

    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    @keyframes pulse-amber {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
        70% { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(245, 158, 11, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }

    /* KPI Cards */
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

    /* Panel Wrapper */
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

    /* Table styling */
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
                        <h4 class="m-0 fw-bold text-dark">{{ __('Live SMS Monitoring') }}</h4>
                        <span class="badge badge-soft-primary ms-1" style="font-size: 11px;">TELECOM NOC</span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3 mt-1" style="font-size: 13px; color: #64748b;">
                        <span><i class="fas fa-server me-1"></i> Broker: <strong id="broker-status" class="text-success">{{ ucfirst($broker['status']) }}</strong> (v<span id="broker-version">{{ $broker['version'] }}</span>)</span>
                        <span>•</span>
                        <span><i class="fas fa-microchip me-1"></i> Workers: <strong id="workers-count" class="text-success">{{ $workers['active_workers'] }}/{{ $workers['expected_workers'] }} Healthy</strong></span>
                        <span>•</span>
                        <span><i class="fas fa-tachometer-alt me-1 text-info"></i> Speed: <strong id="val-tps" class="text-info">{{ $metrics['current_tps'] }}</strong> TPS</span>
                        <span>•</span>
                        <span><i class="fas fa-check-circle me-1 text-success"></i> Delivery Rate: <strong id="val-delivery-rate" class="text-success">{{ $metrics['delivery_rate'] }}%</strong></span>
                        <span>•</span>
                        <span><i class="far fa-clock me-1"></i> Last Synced: <strong id="last-synced-time">{{ now()->format('H:i:s') }}</strong></span>
                    </div>
                </div>

                <!-- Controls: Filter, Refresh Interval & Manual Trigger -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Global Hierarchy Drill-Down Selector -->
                    <div style="min-width: 220px;">
                        <select class="form-select form-select-sm" id="select-filter-user" style="font-size: 12px; font-weight: 600;">
                            <option value="">{{ __('🌐 Global Platform Traffic') }}</option>
                            @foreach($downlineUsers ?? [] as $u)
                                <option value="{{ $u['id'] }}" {{ ($filterUserId ?? null) == $u['id'] ? 'selected' : '' }}>
                                    [{{ strtoupper($u['role']) }}] {{ $u['username'] ?? $u['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="btn-group btn-group-sm" role="group" aria-label="Refresh Interval">
                        <button type="button" class="btn btn-outline-secondary interval-btn active" data-interval="2000">2s</button>
                        <button type="button" class="btn btn-outline-secondary interval-btn" data-interval="5000">5s</button>
                        <button type="button" class="btn btn-outline-secondary interval-btn" data-interval="10000">10s</button>
                        <button type="button" class="btn btn-outline-secondary interval-btn" data-interval="0">{{ __('Pause') }}</button>
                    </div>

                    <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-1 shadow-sm" id="btn-refresh-now">
                        <i class="fas fa-sync-alt" id="refresh-spinner"></i>
                        <span>{{ __('Refresh Now') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Hierarchical Drill-Down Navigation & Breadcrumb Trail -->
        <div class="card border-0 shadow-sm mb-3 {{ empty($breadcrumbs) ? 'd-none' : '' }}" id="drilldown-nav-container" style="background: #f8fafc; border-left: 4px solid #4f46e5 !important; border-radius: 8px;">
            <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap" id="drilldown-breadcrumbs-list">
                    <button type="button" class="btn btn-sm btn-light border py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="" style="font-size: 12px; font-weight: 600;">
                        <i class="fas fa-globe text-primary"></i> {{ __('Global Platform') }}
                    </button>
                    @foreach($breadcrumbs ?? [] as $bc)
                        <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                        <button type="button" class="btn btn-sm {{ $loop->last ? 'btn-primary shadow-sm' : 'btn-light border' }} py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="{{ $bc['id'] }}" style="font-size: 12px; font-weight: 600;">
                            <i class="{{ $bc['role'] === 'sp' ? 'fas fa-user-tie' : ($bc['role'] === 'ssp' ? 'fas fa-users' : 'fas fa-user') }}"></i>
                            <span>{{ $bc['name'] }}</span>
                            <span class="badge {{ $loop->last ? 'bg-light text-dark' : 'badge-soft-info' }}" style="font-size: 10px;">{{ $bc['role_label'] }}</span>
                        </button>
                    @endforeach
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-soft-warning"><i class="fas fa-filter me-1"></i>{{ __('Hierarchical Scope Active') }}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" id="btn-reset-drilldown" style="font-size: 11px;">
                        <i class="fas fa-times me-1"></i> {{ __('Exit Drill-Down') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Global SMS Queue Statistics Heading -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 px-1">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #e0e7ff; color: #4338ca;">
                    <i class="fas fa-globe-americas" style="font-size: 14px;"></i>
                </div>
                <h5 class="m-0 fw-bold text-dark" id="label-kpi-heading">{{ __('Global SMS Queue Statistics') }}</h5>
                <span class="badge badge-soft-primary" id="badge-traffic-scope">{{ __('ALL PLATFORM TRAFFIC') }}</span>
            </div>
            <div class="text-muted" style="font-size: 12px;" id="sub-kpi-heading">
                <i class="fas fa-info-circle me-1"></i>{{ __('Cross-tenant aggregated live queue performance across all gateways & workers') }}
            </div>
        </div>

        <!-- 12 KPI Cards Row -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Queued SMS (RabbitMQ Ready) -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Total Queued SMS') }}</div>
                            <div class="kpi-val text-primary" id="val-rmq-ready">{{ number_format($broker['ready_messages']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #e0e7ff; color: #4f46e5;">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>{{ __('RabbitMQ Broker Queue') }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Pending SMS (Database Queued) -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Pending SMS') }}</div>
                            <div class="kpi-val text-secondary" id="val-pending-count" style="color: #6366f1;">{{ number_format($metrics['pending_count']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #ede9fe; color: #7c3aed;">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>{{ __('Awaiting Gateway Delivery') }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. Processing SMS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Processing SMS') }}</div>
                            <div class="kpi-val text-warning" id="val-unack">{{ number_format($broker['unack_messages']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                            <i class="fas fa-running"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>{{ __('Active in Worker RAM') }}</span>
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
                        <span class="badge bg-success" id="val-kpi-worker-badge" style="font-size: 11px;">{{ ucfirst($metrics['worker_status'] ?? ($workers['status'] ?? 'Healthy')) }}</span> <span class="text-muted ms-1">{{ __('Process Sockets') }}</span>
                    </div>
                </div>
            </div>

            <!-- 12. Queue Backlog -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Queue Backlog') }}</div>
                            <div class="kpi-val" id="val-queue-backlog" style="color: #6366f1;">{{ number_format($metrics['queue_backlog'] ?? (($broker['ready_messages'] ?? 0) + $metrics['pending_count'])) }}</div>
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

        <!-- Global Throughput (TPS) & Processing Speed Engine -->
        <div class="panel-box mb-4">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="panel-title mb-0">
                        <i class="fas fa-tachometer-alt text-info"></i>
                        {{ __('Global Throughput (TPS) & Processing Speed Engine') }}
                    </h5>
                    <span class="badge badge-soft-info" id="live-tps-engine-badge">
                        <i class="fas fa-bolt text-warning me-1"></i><span id="val-engine-tps">{{ $metrics['current_tps'] }}</span> TPS (Current)
                    </span>
                    <span class="badge badge-soft-warning">
                        <i class="fas fa-fire me-1"></i>{{ __('Peak Today') }}: <strong id="val-engine-peak">{{ $metrics['peak_tps'] ?? 0 }}</strong> TPS
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-soft-success">
                        <i class="fas fa-microchip me-1"></i> {{ __('Capacity') }}: <strong id="val-engine-capacity">{{ $metrics['concurrency_capacity'] ?? 200 }}</strong> TPS (<span id="val-engine-load-text">{{ $metrics['engine_load_percent'] ?? 0 }}%</span> {{ __('Load') }})
                    </span>
                    <span class="text-muted" style="font-size: 12px;">
                        <i class="fas fa-clock text-primary me-1"></i>{{ __('Real-Time Engine Telemetry') }}
                    </span>
                </div>
            </div>

            <div class="p-4">
                <!-- 4 Real-time Speed & Latency Metrics Tiles -->
                <div class="row g-3 mb-4">
                    <!-- Current TPS -->
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <div class="text-muted d-flex align-items-center justify-content-between" style="font-size: 12px;">
                                <span>{{ __('Instantaneous Speed') }}</span>
                                <span class="badge badge-soft-info">{{ __('1m window') }}</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-3 fw-bold text-info" id="val-speed-current">{{ $metrics['current_tps'] }}</span>
                                <span class="fw-semibold text-muted">TPS</span>
                            </div>
                            <div class="text-muted mt-1" style="font-size: 11px;">
                                <i class="fas fa-exchange-alt me-1 text-info"></i>{{ __('Dispatch Velocity') }}: <strong id="val-speed-velocity" class="text-dark">{{ round($metrics['current_tps'] * 60) }}</strong> SMS/min
                            </div>
                        </div>
                    </div>

                    <!-- 5m & 1h Moving Averages -->
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <div class="text-muted d-flex align-items-center justify-content-between" style="font-size: 12px;">
                                <span>{{ __('Moving Averages') }}</span>
                                <span class="badge badge-soft-primary">{{ __('Smoothing') }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 10px;">5-MIN AVG</small>
                                    <span class="fs-4 fw-bold text-dark" id="val-speed-5m">{{ $metrics['tps_5m'] ?? 0 }}</span> <small class="text-muted">TPS</small>
                                </div>
                                <div class="border-start ps-3">
                                    <small class="text-muted d-block" style="font-size: 10px;">1-HOUR AVG</small>
                                    <span class="fs-4 fw-bold text-dark" id="val-speed-1h">{{ $metrics['tps_1h'] ?? 0 }}</span> <small class="text-muted">TPS</small>
                                </div>
                            </div>
                            <div class="text-muted mt-1" style="font-size: 11px;">
                                <i class="fas fa-history me-1 text-primary"></i>1h Volume: <strong id="val-speed-1h-vol" class="text-dark">{{ number_format($metrics['last_1_hour_volume'] ?? 0) }}</strong> SMS
                            </div>
                        </div>
                    </div>

                    <!-- End-to-End Processing Latency -->
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <div class="text-muted d-flex align-items-center justify-content-between" style="font-size: 12px;">
                                <span>{{ __('Processing Latency') }}</span>
                                <span class="badge badge-soft-success">{{ __('End-to-End') }}</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-3 fw-bold text-success" id="val-speed-latency">{{ $metrics['avg_latency_formatted'] ?? '< 500 ms' }}</span>
                            </div>
                            <div class="text-muted mt-1" style="font-size: 11px;">
                                <i class="fas fa-stopwatch me-1 text-success"></i>{{ __('Queue Intake → Worker → Gateway Handoff') }}
                            </div>
                        </div>
                    </div>

                    <!-- Concurrency & Engine Load -->
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="p-3 rounded-3 border bg-light h-100">
                            <div class="text-muted d-flex align-items-center justify-content-between" style="font-size: 12px;">
                                <span>{{ __('Concurrency & Engine Load') }}</span>
                                <span class="badge badge-soft-secondary" id="val-speed-load-badge">{{ $metrics['engine_load_percent'] ?? 0 }}% {{ __('Load') }}</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="fs-3 fw-bold text-dark" id="val-speed-capacity">{{ $metrics['concurrency_capacity'] ?? 200 }}</span>
                                <span class="fw-semibold text-muted">TPS Max</span>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-info" id="bar-speed-load" role="progressbar" style="width: {{ $metrics['engine_load_percent'] ?? 0 }}%;" aria-valuenow="{{ $metrics['engine_load_percent'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- High-Resolution Live Waveform Timeline Graph -->
                <div class="p-3 border rounded-3 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold text-dark" style="font-size: 13px;">
                            <i class="fas fa-wave-square text-info me-1"></i>
                            {{ __('Real-Time Throughput Waveform (Last 20 Poll Cycles)') }}
                        </div>
                        <div class="text-muted" style="font-size: 11px;">
                            <span class="badge badge-soft-info me-1">● Dispatched TPS</span>
                            <span id="live-chart-cycle-time" class="font-monospace">Interval: 2s</span>
                        </div>
                    </div>
                    <div style="position: relative; width: 100%; height: 160px;">
                        <canvas id="tpsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Section: Active Queues & Operator Breakdown -->
        <div class="row g-4 mb-4">
            <!-- Active Queues Table -->
            <div class="col-lg-8 col-12">
                <div class="panel-box h-100">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-network-wired text-primary"></i>
                            {{ __('Active Message Queues & Pipelines') }}
                        </h5>
                        <span class="badge badge-soft-info"><span id="queues-count">{{ count($queues) }}</span> {{ __('Queues Detected') }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle" id="table-queues">
                            <thead>
                                <tr>
                                    <th>{{ __('Queue Name') }}</th>
                                    <th>{{ __('State') }}</th>
                                    <th class="text-center">{{ __('Ready') }}</th>
                                    <th class="text-center">{{ __('In-Flight') }}</th>
                                    <th class="text-center">{{ __('Total') }}</th>
                                    <th class="text-center">{{ __('Consumers') }}</th>
                                    <th>{{ __('Memory') }}</th>
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-queues">
                                @forelse($queues as $q)
                                <tr data-queue="{{ $q['name'] }}">
                                    <td>
                                        <div class="fw-bold text-dark">
                                            <i class="fas fa-inbox text-muted me-1"></i> {{ $q['name'] }}
                                        </div>
                                        <small class="text-muted">vhost: {{ $q['vhost'] }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $q['state'] === 'running' ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                            {{ ucfirst($q['state']) }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-primary">{{ number_format($q['ready']) }}</td>
                                    <td class="text-center fw-bold text-warning">{{ number_format($q['unack']) }}</td>
                                    <td class="text-center fw-bold text-dark">{{ number_format($q['messages']) }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-soft-info">{{ $q['consumers'] }} Workers</span>
                                    </td>
                                    <td class="text-muted">{{ $q['memory_human'] }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-purge-queue" data-queue="{{ $q['name'] }}" title="{{ __('Purge all queued messages') }}">
                                            <i class="fas fa-trash-alt me-1"></i> {{ __('Purge') }}
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">{{ __('No queues found on RabbitMQ broker.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Operator Distribution (24h) -->
            <div class="col-lg-4 col-12">
                <div class="panel-box h-100">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-chart-pie text-success"></i>
                            {{ __('Operator Distribution (24h)') }}
                        </h5>
                        <span class="badge badge-soft-success"><i class="fas fa-broadcast-tower me-1"></i> {{ __('Telecom MNOs') }}</span>
                    </div>
                    <div class="p-4 d-flex flex-column align-items-center justify-content-center">
                        <div style="position: relative; width: 100%; max-width: 220px; height: 180px;">
                            <canvas id="operatorChart"></canvas>
                        </div>
                        <div class="d-flex flex-wrap justify-content-center gap-3 mt-4 w-100" style="font-size: 12px;" id="operator-legend">
                            <span class="d-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 2px; background: #0ea5e9;"></span> GP: <strong id="leg-gp">{{ $metrics['operators']['GP'] }}</strong></span>
                            <span class="d-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 2px; background: #f97316;"></span> BL: <strong id="leg-bl">{{ $metrics['operators']['BL'] }}</strong></span>
                            <span class="d-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 2px; background: #e11d48;"></span> Robi: <strong id="leg-robi">{{ $metrics['operators']['Robi'] }}</strong></span>
                            <span class="d-flex align-items-center gap-1"><span style="width: 10px; height: 10px; border-radius: 2px; background: #16a34a;"></span> TT: <strong id="leg-tt">{{ $metrics['operators']['Teletalk'] }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Queue Worker Health & Daemon Pool Diagnostics -->
        <div class="panel-box mb-4">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="panel-title mb-0">
                        <i class="fas fa-heartbeat text-success"></i>
                        {{ __('Queue Worker Health & Daemon Pool Diagnostics') }}
                    </h5>
                    <span class="badge {{ $workers['status_badge'] ?? 'badge-soft-success' }}" id="worker-pool-badge">
                        <i class="fas fa-check-circle me-1"></i> <span id="worker-active-count">{{ $workers['active_workers'] ?? 8 }}</span> / {{ $workers['expected_workers'] ?? 8 }} {{ __('Active Daemons') }}
                    </span>
                    <span class="badge badge-soft-info">
                        <i class="fas fa-memory me-1"></i> <span id="worker-total-mem">{{ $workers['total_memory_mb'] ?? 710 }}</span> MB / {{ $workers['max_memory_mb'] ?? 4096 }} MB RSS
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-warning" id="btn-restart-workers" title="{{ __('Broadcast queue:restart to recycle active workers gracefully') }}">
                        <i class="fas fa-sync-alt me-1"></i> {{ __('Restart Worker Pool') }}
                    </button>
                    <span class="badge badge-soft-secondary" style="font-size: 11px;">
                        <i class="fas fa-server me-1"></i> {{ $workers['driver'] ?? 'RabbitMQ AMQP' }}
                    </span>
                </div>
            </div>

            <!-- Worker Diagnostics Table -->
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-workers">
                    <thead>
                        <tr>
                            <th>{{ __('Worker Daemon & PID') }}</th>
                            <th>{{ __('Queue Binding') }}</th>
                            <th class="text-center">{{ __('Socket & Port') }}</th>
                            <th class="text-center">{{ __('Process State') }}</th>
                            <th class="text-center" style="min-width: 130px;">{{ __('Memory RSS (Limit 512MB)') }}</th>
                            <th class="text-center">{{ __('Uptime') }}</th>
                            <th class="text-center">{{ __('Reductions / sec') }}</th>
                            <th class="text-center">{{ __('Lifetime Packets') }}</th>
                            <th class="text-end">{{ __('Worker Health') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-workers">
                        @forelse($workers['workers'] ?? [] as $w)
                        <tr data-worker-pid="{{ $w['pid'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background: rgba(16, 185, 129, 0.12); color: #10b981; font-size: 12px;">
                                        <i class="fas fa-microchip"></i>
                                    </div>
                                    <span>{{ $w['name'] }}</span>
                                </div>
                                <small class="text-muted ms-4">PID: <strong class="text-dark">#{{ $w['pid'] }}</strong> • CLI Worker</small>
                            </td>
                            <td>
                                <span class="badge badge-soft-primary px-2 py-1"><i class="fas fa-layer-group me-1"></i>{{ $w['queue'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-secondary font-monospace" style="font-size: 11px;">:{{ $w['peer_port'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-success">
                                    <i class="fas fa-play-circle me-1"></i>{{ $w['state'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="fw-bold">{{ $w['memory_mb'] }} MB</span>
                                    <small class="text-muted">{{ $w['memory_percent'] }}%</small>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar {{ $w['memory_percent'] > 80 ? 'bg-danger' : ($w['memory_percent'] > 50 ? 'bg-warning' : 'bg-success') }}" role="progressbar" style="width: {{ $w['memory_percent'] }}%;" aria-valuenow="{{ $w['memory_percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-center text-muted" style="font-size: 12px;">
                                <i class="far fa-clock me-1"></i>{{ $w['uptime'] }}
                            </td>
                            <td class="text-center fw-semibold text-dark">
                                {{ number_format($w['reductions_rate'], 1) }} ops/s
                            </td>
                            <td class="text-center text-muted" style="font-size: 12px;">
                                {{ number_format($w['total_packets']) }}
                            </td>
                            <td class="text-end">
                                <span class="badge {{ $w['status_badge'] }}">
                                    <i class="fas fa-shield-alt me-1"></i>{{ $w['status'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">{{ __('No active worker daemons found. Ensure queue worker processes are running.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Hierarchical Live SMS Queue Matrix (Resellers, Sub-Resellers & Customers) -->
        <div class="panel-box mb-4">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="panel-title mb-0">
                        <i class="fas fa-sitemap text-primary"></i>
                        {{ __('Hierarchical Live SMS Queue Matrix') }}
                    </h5>
                    <span class="badge badge-soft-primary"><span id="resellers-count">{{ count($resellerSummary ?? []) }}</span> {{ __('Accounts Monitored') }}</span>
                </div>
                <div class="btn-group btn-group-sm" role="group" aria-label="Hierarchy Level Filter">
                    <button type="button" class="btn btn-outline-primary partner-tab-btn active" data-role="all">{{ __('All Accounts') }}</button>
                    <button type="button" class="btn btn-outline-primary partner-tab-btn" data-role="sp">{{ __('Resellers (SP)') }}</button>
                    <button type="button" class="btn btn-outline-primary partner-tab-btn" data-role="ssp">{{ __('Sub-Resellers (SSP)') }}</button>
                    <button type="button" class="btn btn-outline-primary partner-tab-btn" data-role="user">{{ __('Customers') }}</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-resellers">
                    <thead>
                        <tr>
                            <th>{{ __('Account Name & Username') }}</th>
                            <th>{{ __('Hierarchy Level') }}</th>
                            <th class="text-center">{{ __('Active Customers') }}</th>
                            <th class="text-center">{{ __('Pending in Queue') }}</th>
                            <th class="text-center">{{ __('Processing') }}</th>
                            <th class="text-center">{{ __('Today Total SMS') }}</th>
                            <th class="text-center">{{ __('Delivery Rate') }}</th>
                            <th class="text-end">{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-resellers">
                        @forelse($resellerSummary ?? [] as $res)
                        <tr data-reseller-id="{{ $res['id'] }}" data-role="{{ $res['role'] }}">
                            <td>
                                <div class="fw-bold text-dark">{{ $res['name'] }}</div>
                                <small class="text-muted">{{ '@' . $res['username'] }}</small>
                                @if(!empty($res['parent_name']))
                                <div style="font-size: 11px;" class="text-muted mt-1"><i class="fas fa-level-up-alt fa-rotate-90 text-primary me-1"></i> Reseller: <strong>{{ $res['parent_name'] }}</strong></div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $res['role'] === 'sp' ? 'badge-soft-primary' : ($res['role'] === 'ssp' ? 'badge-soft-info' : 'badge-soft-success') }}">
                                    {{ $res['role_label'] }}
                                </span>
                            </td>
                            <td class="text-center fw-bold">{{ $res['role'] === 'user' ? '—' : $res['customers'] }}</td>
                            <td class="text-center fw-bold {{ $res['pending_queue'] > 0 ? 'text-warning' : 'text-muted' }}">
                                {{ number_format($res['pending_queue']) }}
                            </td>
                            <td class="text-center fw-bold {{ ($res['processing_queue'] ?? 0) > 0 ? 'text-info' : 'text-muted' }}">
                                {{ number_format($res['processing_queue'] ?? 0) }}
                            </td>
                            <td class="text-center fw-bold text-dark">{{ number_format($res['today_total']) }}</td>
                            <td class="text-center">
                                <span class="badge badge-soft-success">{{ $res['delivery_rate'] }}%</span>
                            </td>
                            <td class="text-end fw-bold text-dark">৳{{ number_format($res['balance'], 2) }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-primary py-1 px-2 btn-filter-reseller" data-id="{{ $res['id'] }}" title="{{ __('Drill-down entire dashboard') }}" style="font-size: 11px;">
                                    <i class="fas fa-crosshairs me-1"></i> {{ __('Drill-Down') }}
                                </button>
                                @if($res['role'] !== 'user')
                                <button type="button" class="btn btn-sm btn-outline-info py-1 px-2 btn-downline-reseller" data-id="{{ $res['id'] }}" data-name="{{ $res['name'] }}" title="{{ __('Filter hierarchy table to downline accounts') }}" style="font-size: 11px;">
                                    <i class="fas fa-sitemap me-1"></i> {{ __('Downline') }}
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="{{ $res['id'] }}" title="{{ __('Inspect Queue Details') }}" style="font-size: 11px;">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">{{ __('No accounts registered yet.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Provider-Wise SMS Gateway Traffic & Route Health -->
        <div class="panel-box mb-4">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="panel-title mb-0">
                        <i class="fas fa-network-wired text-success"></i>
                        {{ __('Provider-Wise SMS Gateway Traffic') }}
                    </h5>
                    <span class="badge badge-soft-success"><span id="providers-count">{{ count($providers ?? []) }}</span> {{ __('Gateway Providers') }}</span>
                </div>
                <div class="text-muted" style="font-size: 12px;">
                    <i class="fas fa-shield-alt text-success me-1"></i>{{ __('Multi-Channel Gateway Route Distribution & Performance') }}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-providers">
                    <thead>
                        <tr>
                            <th>{{ __('Gateway Provider') }}</th>
                            <th>{{ __('Status & Driver') }}</th>
                            <th class="text-center">{{ __('Priority') }}</th>
                            <th class="text-center">{{ __('Pending in Queue') }}</th>
                            <th class="text-center">{{ __('Processing') }}</th>
                            <th class="text-center">{{ __('Today Dispatched') }}</th>
                            <th class="text-center">{{ __('Delivery Rate') }}</th>
                            <th class="text-center" style="min-width: 140px;">{{ __('Traffic Share') }}</th>
                            <th class="text-end">{{ __('Lifetime Volume') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-providers">
                        @forelse($providers ?? [] as $prov)
                        <tr data-provider-id="{{ $prov['id'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                    <span class="rounded-circle d-inline-block" style="width: 8px; height: 8px; background: {{ $prov['is_active'] ? '#10b981' : '#94a3b8' }};"></span>
                                    {{ $prov['name'] }}
                                </div>
                                <small class="text-muted ms-3">ID: #{{ $prov['id'] }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $prov['is_active'] ? 'badge-soft-success' : 'badge-soft-secondary' }}">
                                    {{ $prov['status_label'] }}
                                </span>
                                <small class="text-muted d-block mt-1" style="font-size: 10px;">Driver: {{ strtoupper($prov['driver']) }}</small>
                            </td>
                            <td class="text-center fw-semibold">{{ $prov['priority'] }}</td>
                            <td class="text-center fw-bold {{ $prov['pending_queue'] > 0 ? 'text-warning' : 'text-muted' }}">
                                {{ number_format($prov['pending_queue']) }}
                            </td>
                            <td class="text-center fw-bold {{ $prov['processing_queue'] > 0 ? 'text-info' : 'text-muted' }}">
                                {{ number_format($prov['processing_queue']) }}
                            </td>
                            <td class="text-center fw-bold text-dark">{{ number_format($prov['today_total']) }}</td>
                            <td class="text-center">
                                <span class="badge badge-soft-success">{{ $prov['delivery_rate'] }}%</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="fw-bold">{{ $prov['traffic_share'] }}%</span>
                                    <small class="text-muted">{{ number_format($prov['lifetime_total']) }}</small>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar {{ $prov['is_active'] ? 'bg-success' : 'bg-secondary' }}" role="progressbar" style="width: {{ $prov['traffic_share'] }}%;" aria-valuenow="{{ $prov['traffic_share'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-end fw-bold text-dark">{{ number_format($prov['lifetime_total']) }} SMS</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">{{ __('No SMS gateway providers configured.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Route-Wise SMS Traffic & Channel Distribution Matrix -->
        <div class="panel-box mb-4">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="panel-title mb-0">
                        <i class="fas fa-route text-info"></i>
                        {{ __('Route-Wise SMS Traffic & Channel Distribution') }}
                    </h5>
                    <span class="badge badge-soft-info"><span id="routes-count">{{ count($routes ?? []) }}</span> {{ __('Active Telecom Routes') }}</span>
                </div>
                <div class="text-muted" style="font-size: 12px;">
                    <i class="fas fa-check-double text-info me-1"></i>{{ __('Masking, Non-Masking, Transactional OTP & Bulk Queue Routing') }}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-routes">
                    <thead>
                        <tr>
                            <th>{{ __('SMS Route & Type') }}</th>
                            <th>{{ __('Route Classification') }}</th>
                            <th class="text-center">{{ __('Priority Level') }}</th>
                            <th class="text-center">{{ __('Pending in Queue') }}</th>
                            <th class="text-center">{{ __('Processing') }}</th>
                            <th class="text-center">{{ __('Today Dispatched') }}</th>
                            <th class="text-center">{{ __('Delivery Rate') }}</th>
                            <th class="text-center" style="min-width: 140px;">{{ __('Traffic Share') }}</th>
                            <th class="text-end">{{ __('Lifetime Volume') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-routes">
                        @forelse($routes ?? [] as $rt)
                        <tr data-route-id="{{ $rt['id'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background: {{ $rt['color'] }}20; color: {{ $rt['color'] }}; font-size: 12px;">
                                        <i class="{{ $rt['icon'] }}"></i>
                                    </div>
                                    {{ $rt['name'] }}
                                </div>
                                <small class="text-muted ms-4">{{ $rt['type_label'] }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $rt['badge'] }}">
                                    {{ $rt['status'] }}
                                </span>
                            </td>
                            <td class="text-center fw-semibold">{{ $rt['priority'] }}</td>
                            <td class="text-center fw-bold {{ $rt['pending_queue'] > 0 ? 'text-warning' : 'text-muted' }}">
                                {{ number_format($rt['pending_queue']) }}
                            </td>
                            <td class="text-center fw-bold {{ $rt['processing_queue'] > 0 ? 'text-info' : 'text-muted' }}">
                                {{ number_format($rt['processing_queue']) }}
                            </td>
                            <td class="text-center fw-bold text-dark">{{ number_format($rt['today_total']) }}</td>
                            <td class="text-center">
                                <span class="badge badge-soft-success">{{ $rt['delivery_rate'] }}%</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="fw-bold">{{ $rt['traffic_share'] }}%</span>
                                    <small class="text-muted">{{ number_format($rt['lifetime_total']) }}</small>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $rt['traffic_share'] }}%; background-color: {{ $rt['color'] }};" aria-valuenow="{{ $rt['traffic_share'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-end fw-bold text-dark">{{ number_format($rt['lifetime_total']) }} SMS</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">{{ __('No SMS routes configured.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Campaign-Wise SMS Traffic & Queue Progress Matrix -->
        <div class="panel-box mb-4">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="panel-title mb-0">
                        <i class="fas fa-bullhorn text-danger"></i>
                        {{ __('Campaign-Wise SMS Traffic & Queue Progress') }}
                    </h5>
                    <span class="badge badge-soft-danger"><span id="campaigns-count">{{ count($campaigns ?? []) }}</span> {{ __('Active / Recent Campaigns') }}</span>
                </div>
                <div class="text-muted" style="font-size: 12px;">
                    <i class="fas fa-tasks text-danger me-1"></i>{{ __('Real-time bulk campaign dispatch status, recipient completion, and queue backlog') }}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-campaigns">
                    <thead>
                        <tr>
                            <th>{{ __('Campaign & ID') }}</th>
                            <th>{{ __('Initiator / User') }}</th>
                            <th class="text-center">{{ __('Sender ID') }}</th>
                            <th class="text-center">{{ __('Recipients') }}</th>
                            <th class="text-center">{{ __('Pending') }}</th>
                            <th class="text-center">{{ __('Processing') }}</th>
                            <th class="text-center">{{ __('Delivered') }}</th>
                            <th class="text-center">{{ __('Failed') }}</th>
                            <th class="text-center" style="min-width: 140px;">{{ __('Progress') }}</th>
                            <th class="text-end">{{ __('Status / Time') }}</th>
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
                            <td>
                                <div class="fw-bold text-dark">{{ $camp['creator_name'] }}</div>
                                <small class="badge badge-soft-info" style="font-size: 10px;">{{ ucfirst($camp['creator_role']) }}</small>
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">{{ __('No active bulk campaigns in queue. System ready for scheduled campaign broadcasts.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom Section: Live Dispatched Stream & Failed Jobs Manager -->
        <div class="row g-4">
            <!-- Live SMS Stream -->
            <div class="col-lg-8 col-12">
                <div class="panel-box">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-stream text-primary"></i>
                            {{ __('Live SMS Dispatch Stream') }}
                        </h5>
                        <span class="badge badge-soft-success"><i class="fas fa-satellite-dish me-1"></i> {{ __('Live Stream') }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-modern align-middle" id="table-stream">
                            <thead>
                                <tr>
                                    <th>{{ __('Time') }}</th>
                                    <th>{{ __('User / Sender') }}</th>
                                    <th>{{ __('Recipient') }}</th>
                                    <th>{{ __('Operator') }}</th>
                                    <th>{{ __('Gateway') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Response Code') }}</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-stream">
                                @forelse($metrics['live_stream'] as $sms)
                                <tr data-id="{{ $sms['id'] }}">
                                    <td class="text-muted" style="font-size: 12px;">{{ $sms['time'] }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $sms['username'] }}</div>
                                        <small class="badge badge-soft-info" style="font-size: 10px;">{{ $sms['user_role'] }}</small>
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $sms['recipient'] }}</td>
                                    <td>
                                        <span class="badge badge-soft-primary">{{ $sms['operator'] }}</span>
                                    </td>
                                    <td class="text-muted" style="font-size: 12px;">{{ $sms['gateway'] }}</td>
                                    <td>
                                        @if($sms['status'] === 'success')
                                            <span class="badge badge-soft-success">{{ __('Success') }}</span>
                                        @elseif($sms['status'] === 'pending')
                                            <span class="badge badge-soft-warning">{{ __('Queued') }}</span>
                                        @else
                                            <span class="badge badge-soft-danger">{{ ucfirst($sms['status']) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="font-size: 12px;">
                                        {{ $sms['response_code'] }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">{{ __('No dispatched traffic detected yet.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Failed Jobs Manager & Top Senders -->
            <div class="col-lg-4 col-12">
                <!-- Top Traffic Senders (24h) -->
                <div class="panel-box mb-4">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-users-cog text-warning"></i>
                            {{ __('Top Active Senders (24h)') }}
                        </h5>
                    </div>
                    <div class="p-3" id="top-senders-list">
                        @forelse($metrics['top_senders'] as $sender)
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div>
                                <div class="fw-bold text-dark">{{ $sender['username'] }}</div>
                                <small class="text-muted">{{ $sender['name'] }} • <span class="badge badge-soft-info" style="font-size: 9px;">{{ $sender['role'] }}</span></small>
                            </div>
                            <div class="text-end">
                                <span class="badge badge-soft-primary fw-bold" style="font-size: 12px;">{{ number_format($sender['total_sms']) }} SMS</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-3 text-muted" style="font-size: 13px;">{{ __('No senders in last 24 hours.') }}</div>
                        @endforelse
                    </div>
                </div>

                <!-- Failed Jobs Queue Card -->
                <div class="panel-box">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                            {{ __('Failed Jobs') }}
                            <span class="badge badge-soft-danger ms-1" id="failed-jobs-badge">{{ count($failedJobs) }}</span>
                        </h5>
                        @if(count($failedJobs) > 0)
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-success py-1 px-2" id="btn-retry-all-failed" title="{{ __('Retry all failed queue jobs') }}" style="font-size: 11px;">
                                <i class="fas fa-redo-alt me-1"></i> {{ __('Retry All') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" id="btn-flush-failed" title="{{ __('Clear failed jobs table') }}" style="font-size: 11px;">
                                <i class="fas fa-trash me-1"></i> {{ __('Flush') }}
                            </button>
                        </div>
                        @endif
                    </div>
                    <div class="p-3" id="failed-jobs-list" style="max-height: 280px; overflow-y: auto;">
                        @forelse($failedJobs as $job)
                        <div class="border rounded p-2 mb-2 bg-light failed-job-item" data-id="{{ $job['id'] }}">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <strong class="text-dark" style="font-size: 12px;">#{{ $job['id'] }} • {{ $job['job_name'] }}</strong>
                                <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 btn-retry-single-job" data-id="{{ $job['id'] }}" style="font-size: 11px;">
                                    <i class="fas fa-redo-alt"></i> {{ __('Retry') }}
                                </button>
                            </div>
                            @if($job['recipient'])
                            <div style="font-size: 11px;" class="text-muted mb-1"><i class="fas fa-phone-alt me-1"></i> To: {{ $job['recipient'] }}</div>
                            @endif
                            <div style="font-size: 11px; color: #dc2626; word-break: break-word;">
                                {{ $job['exception'] }}
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 10px;">{{ $job['failed_at'] }}</small>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted" id="no-failed-jobs-msg">
                            <i class="fas fa-check-circle text-success fs-4 mb-2 d-block"></i>
                            {{ __('No failed queue jobs. All systems nominal.') }}
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Drill-Down Account Queue Inspector Modal -->
<div class="modal fade" id="modal-drilldown-inspector" tabindex="-1" aria-labelledby="modalDrilldownTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light py-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #e0e7ff; color: #4338ca;">
                        <i class="fas fa-search-plus"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" id="modal-inspect-name">Account Queue Inspector</h6>
                        <small class="text-muted" id="modal-inspect-role">Loading details...</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modal-inspect-body">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                    <p class="mt-2 text-muted">Loading live queue analytics...</p>
                </div>
            </div>
            <div class="modal-footer bg-light py-2 border-top d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary" id="btn-modal-apply-scope">
                    <i class="fas fa-crosshairs me-1"></i> {{ __('Drill-Down Dashboard Scope') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    let pollingInterval = 2000;
    let pollTimer = null;
    let isFetching = false;

    // CSRF Setup for AJAX calls
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 1. Initialize Operator Donut Chart
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
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + Number(context.raw).toLocaleString() + ' SMS';
                            }
                        }
                    }
                },
                cutout: '68%'
            }
        });
    }

    // TPS Line Chart
    const tpsCtx = document.getElementById('tpsChart');
    let tpsChart = null;
    const maxTpsPoints = 20;
    let tpsLabels = Array.from({length: maxTpsPoints}, (_, i) => -((maxTpsPoints - 1 - i) * 2) + 's');
    let tpsData = Array(maxTpsPoints).fill(0);
    tpsData[maxTpsPoints - 1] = {{ (float)($metrics['current_tps'] ?? 0) }};

    if (tpsCtx) {
        tpsChart = new Chart(tpsCtx, {
            type: 'line',
            data: {
                labels: tpsLabels,
                datasets: [{
                    label: 'Dispatched TPS',
                    data: tpsData,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#0284c7'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ctx.raw + ' TPS (Dispatched)';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.7)' },
                        ticks: {
                            font: { size: 10 },
                            color: '#64748b',
                            callback: function(v) { return v + ' TPS'; }
                        }
                    }
                }
            }
        });
    }

    // 2. Main Live Update Function
    function fetchLiveData() {
        if (isFetching) return;
        isFetching = true;
        $('#refresh-spinner').addClass('fa-spin');

        const filterUserId = $('#select-filter-user').val();
        $.ajax({
            url: "{{ route('admin.queue-monitor.data') }}",
            type: "GET",
            data: { filter_user_id: filterUserId },
            dataType: "json",
            timeout: 5000,
            success: function(res) {
                if (!res.success) return;

                // Sync Clock
                $('#last-synced-time').text(res.timestamp);

                // Update Broker Status
                if (res.broker.status === 'online') {
                    $('#broker-status').text('Online').removeClass('text-danger text-warning').addClass('text-success');
                    $('#live-indicator').removeClass('danger warning');
                } else {
                    $('#broker-status').text('Offline').removeClass('text-success text-warning').addClass('text-danger');
                    $('#live-indicator').removeClass('warning').addClass('danger');
                }
                $('#broker-version').text(res.broker.version);
                $('#val-cluster-name').text(res.broker.cluster_name);

                // Workers
                const wStatus = res.workers.status;
                const wText = res.workers.active_workers + '/' + res.workers.expected_workers + (wStatus === 'healthy' ? ' Healthy' : ' Degraded');
                $('#workers-count').text(wText);
                $('#val-workers-status').text(res.workers.active_workers + '/' + res.workers.expected_workers);

                // KPI values
                const isFiltered = !!$('#select-filter-user').val();
                const readyMsg = isFiltered ? res.metrics.pending_count : (res.broker.ready_messages || 0);
                const unackMsg = isFiltered ? (res.metrics.processing_count || 0) : (res.broker.unack_messages || 0);
                const totalBacklog = res.metrics.queue_backlog ?? (readyMsg + (res.metrics.pending_count || 0));

                $('#val-queue-backlog').text(totalBacklog.toLocaleString());
                $('#val-backlog-drain').text(res.metrics.drain_eta || 'Optimal Flow');
                $('#val-rmq-ready').text(readyMsg.toLocaleString());
                $('#val-pending-count').text(res.metrics.pending_count.toLocaleString());
                $('#val-unack').text(unackMsg.toLocaleString());
                $('#val-submitted-count').text((res.metrics.today_submitted || 0).toLocaleString());
                $('#val-today-submitted').text((res.metrics.today_submitted || 0).toLocaleString());
                $('#val-total-submitted').text((res.metrics.submitted_count || 0).toLocaleString());
                $('#val-delivered-count').text((res.metrics.today_delivered || 0).toLocaleString());
                $('#val-today-delivered').text((res.metrics.today_delivered || 0).toLocaleString());
                $('#val-total-delivered').text((res.metrics.delivered_count || 0).toLocaleString());
                $('#val-tps').text(res.metrics.current_tps);
                $('#val-1m').text(res.metrics.last_1_min_volume.toLocaleString());
                $('#val-5m').text(res.metrics.last_5_min_volume.toLocaleString());
                $('#val-delivery-rate').text(res.metrics.delivery_rate + '%');
                $('#val-today-total').text(res.metrics.today_total.toLocaleString());
                $('#val-today-failed').text((res.metrics.today_failed || 0).toLocaleString());
                $('#val-sub-today-failed').text((res.metrics.today_failed || 0).toLocaleString());
                $('#val-total-failed').text((res.metrics.failed_count || 0).toLocaleString());
                $('#val-today-rejected').text((res.metrics.today_rejected || 0).toLocaleString());
                $('#val-sub-today-rejected').text((res.metrics.today_rejected || 0).toLocaleString());
                $('#val-total-rejected').text((res.metrics.rejected_count || 0).toLocaleString());
                $('#val-today-expired').text((res.metrics.today_expired || 0).toLocaleString());
                $('#val-sub-today-expired').text((res.metrics.today_expired || 0).toLocaleString());
                $('#val-total-expired').text((res.metrics.expired_count || 0).toLocaleString());
                $('#val-retry-queue').text((res.metrics.retry_queue_count || 0).toLocaleString());
                $('#val-kpi-tps').text(res.metrics.current_tps);
                $('#val-kpi-1m').text(res.metrics.last_1_min_volume.toLocaleString());
                $('#val-kpi-5m').text(res.metrics.last_5_min_volume.toLocaleString());
                const activeW = (res.metrics.active_workers ?? (res.workers ? res.workers.active_workers : 8));
                $('#val-kpi-workers').text(activeW);
                const wStat = (res.metrics.worker_status ?? (res.workers ? res.workers.status : 'healthy'));
                $('#val-kpi-worker-badge').text(wStat.charAt(0).toUpperCase() + wStat.slice(1));

                // Update Queues Table
                renderQueuesTable(res.queues);

                // Update Workers Health Table
                renderWorkersTable(res.workers);

                // Update Resellers Hierarchy Matrix Table
                renderResellersTable(res.resellers);

                // Update Providers Table
                renderProvidersTable(res.providers);

                // Update Routes Table
                renderRoutesTable(res.routes);

                // Update Campaigns Table
                renderCampaignsTable(res.campaigns);

                // Update Global TPS & Processing Speed Engine
                $('#val-engine-tps').text(res.metrics.current_tps);
                if (res.metrics.peak_tps !== undefined) {
                    $('#val-engine-peak').text(res.metrics.peak_tps);
                }
                if (res.metrics.concurrency_capacity !== undefined) {
                    $('#val-engine-capacity').text(res.metrics.concurrency_capacity);
                }
                if (res.metrics.engine_load_percent !== undefined) {
                    $('#val-engine-load-text').text(res.metrics.engine_load_percent + '%');
                    $('#val-speed-load-badge').text(res.metrics.engine_load_percent + '% Load');
                    $('#bar-speed-load').css('width', res.metrics.engine_load_percent + '%').attr('aria-valuenow', res.metrics.engine_load_percent);
                }
                $('#val-speed-current').text(res.metrics.current_tps);
                $('#val-speed-velocity').text(Math.round(res.metrics.current_tps * 60));
                if (res.metrics.tps_5m !== undefined) $('#val-speed-5m').text(res.metrics.tps_5m);
                if (res.metrics.tps_1h !== undefined) $('#val-speed-1h').text(res.metrics.tps_1h);
                if (res.metrics.last_1_hour_volume !== undefined) $('#val-speed-1h-vol').text(Number(res.metrics.last_1_hour_volume).toLocaleString());
                if (res.metrics.avg_latency_formatted !== undefined) $('#val-speed-latency').text(res.metrics.avg_latency_formatted);

                // Update TPS Line Chart
                if (tpsChart) {
                    tpsLabels.shift();
                    tpsLabels.push(res.timestamp);
                    tpsData.shift();
                    tpsData.push(res.metrics.current_tps);
                    tpsChart.update('none');
                }

                // Update Operator Chart
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

                // Update Live Stream Feed
                renderStreamTable(res.metrics.live_stream);

                // Update Top Senders
                renderTopSenders(res.metrics.top_senders);

                // Update Failed Jobs
                renderFailedJobs(res.failed);

                // Update Hierarchical Breadcrumbs
                updateBreadcrumbs(res.breadcrumbs);
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

    function renderQueuesTable(queues) {
        if (!queues || !queues.length) return;
        $('#queues-count').text(queues.length);

        let html = '';
        queues.forEach(function(q) {
            const stateBadge = q.state === 'running' ? 'badge-soft-success' : 'badge-soft-warning';
            html += `
                <tr data-queue="${q.name}">
                    <td>
                        <div class="fw-bold text-dark"><i class="fas fa-inbox text-muted me-1"></i> ${q.name}</div>
                        <small class="text-muted">vhost: ${q.vhost}</small>
                    </td>
                    <td><span class="badge ${stateBadge}">${q.state.charAt(0).toUpperCase() + q.state.slice(1)}</span></td>
                    <td class="text-center fw-bold text-primary">${Number(q.ready).toLocaleString()}</td>
                    <td class="text-center fw-bold text-warning">${Number(q.unack).toLocaleString()}</td>
                    <td class="text-center fw-bold text-dark">${Number(q.messages).toLocaleString()}</td>
                    <td class="text-center"><span class="badge badge-soft-info">${q.consumers} Workers</span></td>
                    <td class="text-muted">${q.memory_human}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-purge-queue" data-queue="${q.name}">
                            <i class="fas fa-trash-alt me-1"></i> Purge
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody-queues').html(html);
    }

    function renderWorkersTable(workers) {
        if (!workers) return;
        $('#worker-active-count').text(workers.active_workers);
        if (workers.total_memory_mb !== undefined) {
            $('#worker-total-mem').text(workers.total_memory_mb);
        }
        if (workers.status_badge) {
            $('#worker-pool-badge').removeClass('badge-soft-success badge-soft-warning badge-soft-danger').addClass(workers.status_badge);
        }

        const list = workers.workers || [];
        if (!list.length) {
            $('#tbody-workers').html('<tr><td colspan="9" class="text-center py-4 text-muted">No active worker daemons found. Ensure queue worker processes are running.</td></tr>');
            return;
        }

        let html = '';
        list.forEach(function(w) {
            const memBg = w.memory_percent > 80 ? 'bg-danger' : (w.memory_percent > 50 ? 'bg-warning' : 'bg-success');
            html += `
                <tr data-worker-pid="${w.pid}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background: rgba(16, 185, 129, 0.12); color: #10b981; font-size: 12px;">
                                <i class="fas fa-microchip"></i>
                            </div>
                            <span>${w.name}</span>
                        </div>
                        <small class="text-muted ms-4">PID: <strong class="text-dark">#${w.pid}</strong> • CLI Worker</small>
                    </td>
                    <td>
                        <span class="badge badge-soft-primary px-2 py-1"><i class="fas fa-layer-group me-1"></i>${w.queue}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-soft-secondary font-monospace" style="font-size: 11px;">:${w.peer_port}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-soft-success">
                            <i class="fas fa-play-circle me-1"></i>${w.state}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                            <span class="fw-bold">${w.memory_mb} MB</span>
                            <small class="text-muted">${w.memory_percent}%</small>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar ${memBg}" role="progressbar" style="width: ${w.memory_percent}%;" aria-valuenow="${w.memory_percent}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-center text-muted" style="font-size: 12px;">
                        <i class="far fa-clock me-1"></i>${w.uptime}
                    </td>
                    <td class="text-center fw-semibold text-dark">
                        ${Number(w.reductions_rate).toFixed(1)} ops/s
                    </td>
                    <td class="text-center text-muted" style="font-size: 12px;">
                        ${Number(w.total_packets).toLocaleString()}
                    </td>
                    <td class="text-end">
                        <span class="badge ${w.status_badge}">
                            <i class="fas fa-shield-alt me-1"></i>${w.status}
                        </span>
                    </td>
                </tr>
            `;
        });
        $('#tbody-workers').html(html);
    }

    let cachedResellers = @json($resellerSummary ?? []);
    let currentPartnerRoleFilter = 'all';
    let currentDownlineParentId = null;
    let currentDownlineParentName = '';

    function renderResellersTable(resellers) {
        if (resellers) cachedResellers = resellers;
        const list = cachedResellers || [];

        let filtered = list;
        if (currentDownlineParentId !== null) {
            filtered = list.filter(r => r.id === currentDownlineParentId || r.parent_id === currentDownlineParentId || (r.downline_ids && r.downline_ids.includes(currentDownlineParentId)));
        } else if (currentPartnerRoleFilter !== 'all') {
            filtered = list.filter(r => r.role === currentPartnerRoleFilter);
        }

        $('#resellers-count').text(filtered.length);

        if (!filtered.length) {
            const roleLabel = currentDownlineParentId ? 'downline accounts' : (currentPartnerRoleFilter === 'ssp' ? 'sub-resellers' : (currentPartnerRoleFilter === 'sp' ? 'resellers' : (currentPartnerRoleFilter === 'user' ? 'customers' : 'accounts')));
            $('#tbody-resellers').html(`<tr><td colspan="9" class="text-center py-4 text-muted">No ${roleLabel} registered yet.</td></tr>`);
            return;
        }

        let html = '';
        filtered.forEach(function(r) {
            const roleBadge = r.role === 'sp' ? 'badge-soft-primary' : (r.role === 'ssp' ? 'badge-soft-info' : 'badge-soft-success');
            const pendingClass = r.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = (r.processing_queue || 0) > 0 ? 'text-info' : 'text-muted';
            const parentInfo = r.parent_name ? `<div style="font-size: 11px;" class="text-muted mt-1"><i class="fas fa-level-up-alt fa-rotate-90 text-primary me-1"></i> Reseller: <strong>${r.parent_name}</strong></div>` : '';
            const customerCell = r.role === 'user' ? '—' : r.customers;
            const downlineBtn = r.role !== 'user' ? `
                <button type="button" class="btn btn-sm btn-outline-info py-1 px-2 btn-downline-reseller" data-id="${r.id}" data-name="${r.name}" title="Filter hierarchy table to downline accounts" style="font-size: 11px;">
                    <i class="fas fa-sitemap me-1"></i> Downline
                </button>
            ` : '';

            html += `
                <tr data-reseller-id="${r.id}" data-role="${r.role}">
                    <td>
                        <div class="fw-bold text-dark">${r.name}</div>
                        <small class="text-muted">@${r.username}</small>
                        ${parentInfo}
                    </td>
                    <td><span class="badge ${roleBadge}">${r.role_label}</span></td>
                    <td class="text-center fw-bold">${customerCell}</td>
                    <td class="text-center fw-bold ${pendingClass}">${Number(r.pending_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold ${procClass}">${Number(r.processing_queue || 0).toLocaleString()}</td>
                    <td class="text-center fw-bold text-dark">${Number(r.today_total).toLocaleString()}</td>
                    <td class="text-center"><span class="badge badge-soft-success">${r.delivery_rate}%</span></td>
                    <td class="text-end fw-bold text-dark">৳${Number(r.balance).toFixed(2)}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-primary py-1 px-2 btn-filter-reseller" data-id="${r.id}" title="Drill-down entire dashboard" style="font-size: 11px;">
                            <i class="fas fa-crosshairs me-1"></i> Drill-Down
                        </button>
                        ${downlineBtn}
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="${r.id}" title="Inspect Queue Details" style="font-size: 11px;">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody-resellers').html(html);
    }

    $(document).on('click', '.partner-tab-btn', function() {
        $('.partner-tab-btn').removeClass('active');
        $(this).addClass('active');
        currentPartnerRoleFilter = $(this).data('role');
        renderResellersTable();
    });

    function renderProvidersTable(providers) {
        if (!providers) return;
        $('#providers-count').text(providers.length);

        if (!providers.length) {
            $('#tbody-providers').html('<tr><td colspan="9" class="text-center py-4 text-muted">No SMS gateway providers configured.</td></tr>');
            return;
        }

        let html = '';
        providers.forEach(function(prov) {
            const statusBadge = prov.is_active ? 'badge-soft-success' : 'badge-soft-secondary';
            const dotColor = prov.is_active ? '#10b981' : '#94a3b8';
            const pendingClass = prov.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = prov.processing_queue > 0 ? 'text-info' : 'text-muted';
            const barClass = prov.is_active ? 'bg-success' : 'bg-secondary';

            html += `
                <tr data-provider-id="${prov.id}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                            <span class="rounded-circle d-inline-block" style="width: 8px; height: 8px; background: ${dotColor};"></span>
                            ${prov.name}
                        </div>
                        <small class="text-muted ms-3">ID: #${prov.id}</small>
                    </td>
                    <td>
                        <span class="badge ${statusBadge}">${prov.status_label}</span>
                        <small class="text-muted d-block mt-1" style="font-size: 10px;">Driver: ${prov.driver.toUpperCase()}</small>
                    </td>
                    <td class="text-center fw-semibold">${prov.priority}</td>
                    <td class="text-center fw-bold ${pendingClass}">${Number(prov.pending_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold ${procClass}">${Number(prov.processing_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold text-dark">${Number(prov.today_total).toLocaleString()}</td>
                    <td class="text-center">
                        <span class="badge badge-soft-success">${prov.delivery_rate}%</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                            <span class="fw-bold">${prov.traffic_share}%</span>
                            <small class="text-muted">${Number(prov.lifetime_total).toLocaleString()}</small>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar ${barClass}" role="progressbar" style="width: ${prov.traffic_share}%;" aria-valuenow="${prov.traffic_share}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark">${Number(prov.lifetime_total).toLocaleString()} SMS</td>
                </tr>
            `;
        });
        $('#tbody-providers').html(html);
    }

    function renderRoutesTable(routes) {
        if (!routes) return;
        $('#routes-count').text(routes.length);

        if (!routes.length) {
            $('#tbody-routes').html('<tr><td colspan="9" class="text-center py-4 text-muted">No SMS routes configured.</td></tr>');
            return;
        }

        let html = '';
        routes.forEach(function(rt) {
            const pendingClass = rt.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = rt.processing_queue > 0 ? 'text-info' : 'text-muted';

            html += `
                <tr data-route-id="${rt.id}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; background: ${rt.color}20; color: ${rt.color}; font-size: 12px;">
                                <i class="${rt.icon}"></i>
                            </div>
                            ${rt.name}
                        </div>
                        <small class="text-muted ms-4">${rt.type_label}</small>
                    </td>
                    <td>
                        <span class="badge ${rt.badge}">
                            ${rt.status}
                        </span>
                    </td>
                    <td class="text-center fw-semibold">${rt.priority}</td>
                    <td class="text-center fw-bold ${pendingClass}">${Number(rt.pending_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold ${procClass}">${Number(rt.processing_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold text-dark">${Number(rt.today_total).toLocaleString()}</td>
                    <td class="text-center">
                        <span class="badge badge-soft-success">${rt.delivery_rate}%</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                            <span class="fw-bold">${rt.traffic_share}%</span>
                            <small class="text-muted">${Number(rt.lifetime_total).toLocaleString()}</small>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: ${rt.traffic_share}%; background-color: ${rt.color};" aria-valuenow="${rt.traffic_share}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark">${Number(rt.lifetime_total).toLocaleString()} SMS</td>
                </tr>
            `;
        });
        $('#tbody-routes').html(html);
    }

    function renderCampaignsTable(campaigns) {
        if (!campaigns) return;
        $('#campaigns-count').text(campaigns.length);

        if (!campaigns.length) {
            $('#tbody-campaigns').html('<tr><td colspan="10" class="text-center py-4 text-muted">No active bulk campaigns in queue. System ready for scheduled campaign broadcasts.</td></tr>');
            return;
        }

        let html = '';
        campaigns.forEach(function(camp) {
            const pendingClass = camp.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = camp.processing_queue > 0 ? 'text-info' : 'text-muted';
            const delivClass = camp.delivered_count > 0 ? 'text-success' : 'text-muted';
            const failClass = camp.failed_count > 0 ? 'text-danger' : 'text-muted';
            const progressBg = camp.progress_percent >= 100 ? 'bg-success' : 'bg-primary';
            const chargeText = camp.charges ? ` • ৳${camp.charges}` : '';

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
                    <td>
                        <div class="fw-bold text-dark">${camp.creator_name}</div>
                        <small class="badge badge-soft-info" style="font-size: 10px;">${camp.creator_role ? camp.creator_role.charAt(0).toUpperCase() + camp.creator_role.slice(1) : 'User'}</small>
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
                </tr>
            `;
        });
        $('#tbody-campaigns').html(html);
    }

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
                    <td>
                        <div class="fw-bold text-dark">${sms.username}</div>
                        <small class="badge badge-soft-info" style="font-size: 10px;">${sms.user_role}</small>
                    </td>
                    <td class="fw-semibold text-dark">${sms.recipient}</td>
                    <td><span class="badge badge-soft-primary">${sms.operator}</span></td>
                    <td class="text-muted" style="font-size: 12px;">${sms.gateway}</td>
                    <td>${statusBadge}</td>
                    <td class="text-muted" style="font-size: 12px;">${sms.response_code || ''}</td>
                </tr>
            `;
        });
        $('#tbody-stream').html(html);
    }

    function renderTopSenders(senders) {
        if (!senders) return;
        if (!senders.length) {
            $('#top-senders-list').html('<div class="text-center py-3 text-muted" style="font-size: 13px;">No senders in last 24 hours.</div>');
            return;
        }

        let html = '';
        senders.forEach(function(s) {
            html += `
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div>
                        <div class="fw-bold text-dark">${s.username}</div>
                        <small class="text-muted">${s.name} • <span class="badge badge-soft-info" style="font-size: 9px;">${s.role}</span></small>
                    </div>
                    <div class="text-end">
                        <span class="badge badge-soft-primary fw-bold" style="font-size: 12px;">${Number(s.total_sms).toLocaleString()} SMS</span>
                    </div>
                </div>
            `;
        });
        $('#top-senders-list').html(html);
    }

    function renderFailedJobs(failed) {
        const count = failed ? failed.length : 0;
        $('#failed-jobs-badge').text(count);

        if (!failed || !failed.length) {
            $('#failed-jobs-list').html(`
                <div class="text-center py-4 text-muted" id="no-failed-jobs-msg">
                    <i class="fas fa-check-circle text-success fs-4 mb-2 d-block"></i>
                    No failed queue jobs. All systems nominal.
                </div>
            `);
            return;
        }

        let html = '';
        failed.forEach(function(job) {
            html += `
                <div class="border rounded p-2 mb-2 bg-light failed-job-item" data-id="${job.id}">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <strong class="text-dark" style="font-size: 12px;">#${job.id} • ${job.job_name}</strong>
                        <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 btn-retry-single-job" data-id="${job.id}" style="font-size: 11px;">
                            <i class="fas fa-redo-alt"></i> Retry
                        </button>
                    </div>
                    ${job.recipient ? `<div style="font-size: 11px;" class="text-muted mb-1"><i class="fas fa-phone-alt me-1"></i> To: ${job.recipient}</div>` : ''}
                    <div style="font-size: 11px; color: #dc2626; word-break: break-word;">${job.exception}</div>
                    <small class="text-muted d-block mt-1" style="font-size: 10px;">${job.failed_at}</small>
                </div>
            `;
        });
        $('#failed-jobs-list').html(html);
    }

    // 3. Interval Timer Controller
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

    // Actions: Restart Worker Pool
    $(document).on('click', '#btn-restart-workers', function() {
        if (!confirm("Are you sure you want to broadcast a restart signal to all SMS queue workers? Active workers will gracefully recycle after completing current dispatches.")) {
            return;
        }
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Restarting...');

        $.ajax({
            url: "{{ route('admin.queue-monitor.restart-workers') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    fetchLiveData();
                } else {
                    toastr.error(res.message);
                }
            },
            error: function() {
                toastr.error("Failed to broadcast worker restart signal.");
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i> Restart Worker Pool');
            }
        });
    });

    // 4. Actions: Purge Queue
    $(document).on('click', '.btn-purge-queue', function() {
        const queueName = $(this).data('queue');
        if (!confirm(`Are you sure you want to PURGE all unconsumed messages from queue "${queueName}"?`)) {
            return;
        }

        $.ajax({
            url: "{{ url('admin/queue-monitor/purge') }}/" + queueName,
            type: "POST",
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    fetchLiveData();
                } else {
                    toastr.error(res.message);
                }
            },
            error: function() {
                toastr.error("Failed to purge queue.");
            }
        });
    });

    // 5. Actions: Retry Single Failed Job
    $(document).on('click', '.btn-retry-single-job', function() {
        const jobId = $(this).data('id');
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: "{{ url('admin/queue-monitor/retry-failed') }}/" + jobId,
            type: "POST",
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    fetchLiveData();
                } else {
                    toastr.error(res.message);
                    btn.prop('disabled', false).html('<i class="fas fa-redo-alt"></i> Retry');
                }
            },
            error: function() {
                toastr.error("Failed to retry job.");
                btn.prop('disabled', false).html('<i class="fas fa-redo-alt"></i> Retry');
            }
        });
    });

    // 6. Actions: Retry All Failed Jobs
    $('#btn-retry-all-failed').on('click', function() {
        if (!confirm("Are you sure you want to retry all failed jobs?")) return;
        const btn = $(this);
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('admin.queue-monitor.retry-failed', ['id' => 'all']) }}",
            type: "POST",
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    fetchLiveData();
                } else {
                    toastr.error(res.message);
                }
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    });

    // 7. Actions: Flush All Failed Jobs
    $('#btn-flush-failed').on('click', function() {
        if (!confirm("Are you sure you want to permanently delete all failed jobs?")) return;
        const btn = $(this);
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('admin.queue-monitor.flush-failed') }}",
            type: "POST",
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    fetchLiveData();
                } else {
                    toastr.error(res.message);
                }
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    });

    // 8. Hierarchical Drill-Down Navigation Engine
    function updateBreadcrumbs(breadcrumbs) {
        if (!breadcrumbs || !breadcrumbs.length) {
            $('#drilldown-nav-container').addClass('d-none');
            return;
        }

        $('#drilldown-nav-container').removeClass('d-none');
        let html = `
            <button type="button" class="btn btn-sm btn-light border py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="" style="font-size: 12px; font-weight: 600;">
                <i class="fas fa-globe text-primary"></i> Global Platform
            </button>
        `;

        breadcrumbs.forEach(function(bc, idx) {
            const isLast = idx === (breadcrumbs.length - 1);
            const btnClass = isLast ? 'btn-primary shadow-sm' : 'btn-light border';
            const badgeClass = isLast ? 'bg-light text-dark' : 'badge-soft-info';
            const icon = bc.role === 'sp' ? 'fas fa-user-tie' : (bc.role === 'ssp' ? 'fas fa-users' : 'fas fa-user');

            html += `
                <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                <button type="button" class="btn btn-sm ${btnClass} py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="${bc.id}" style="font-size: 12px; font-weight: 600;">
                    <i class="${icon}"></i>
                    <span>${bc.name}</span>
                    <span class="badge ${badgeClass}" style="font-size: 10px;">${bc.role_label}</span>
                </button>
            `;
        });

        $('#drilldown-breadcrumbs-list').html(html);
    }

    // Click Breadcrumb Trail Link
    $(document).on('click', '.btn-nav-breadcrumb', function() {
        const id = $(this).data('id');
        $('#select-filter-user').val(id).trigger('change');
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    // Reset Drill-down back to Global
    $(document).on('click', '#btn-reset-drilldown', function() {
        $('#select-filter-user').val('').trigger('change');
        currentDownlineParentId = null;
        renderResellersTable();
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    // Drill-Down into account
    $(document).on('click', '.btn-filter-reseller', function() {
        const id = $(this).data('id');
        $('#select-filter-user').val(id).trigger('change');
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    // Filter Hierarchy Table to Reseller Downline
    $(document).on('click', '.btn-downline-reseller', function() {
        const id = parseInt($(this).data('id'));
        const name = $(this).data('name');

        if (currentDownlineParentId === id) {
            currentDownlineParentId = null;
            $(this).removeClass('btn-info text-white').addClass('btn-outline-info');
        } else {
            currentDownlineParentId = id;
            currentDownlineParentName = name;
            $('.btn-downline-reseller').removeClass('btn-info text-white').addClass('btn-outline-info');
            $(this).removeClass('btn-outline-info').addClass('btn-info text-white');
        }

        renderResellersTable();
    });

    // Inspect Account Queue Details Modal
    let activeInspectedUserId = null;
    $(document).on('click', '.btn-inspect-user', function() {
        const id = $(this).data('id');
        activeInspectedUserId = id;
        const modal = new bootstrap.Modal(document.getElementById('modal-drilldown-inspector'));
        modal.show();

        $('#modal-inspect-body').html(`
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">Loading live queue analytics for account #${id}...</p>
            </div>
        `);

        $.ajax({
            url: "{{ url('admin/queue-monitor/inspect') }}/" + id,
            type: "GET",
            dataType: "json",
            success: function(res) {
                if (!res.success || !res.data) {
                    $('#modal-inspect-body').html('<div class="alert alert-danger">Failed to load account details.</div>');
                    return;
                }

                const d = res.data;
                $('#modal-inspect-name').text(d.name + ' (@' + d.username + ')');
                $('#modal-inspect-role').text(d.role_label + ' • Parent: ' + d.parent_name);

                let recentSmsHtml = '';
                if (d.recent_sms && d.recent_sms.length) {
                    d.recent_sms.forEach(function(s) {
                        const sBadge = s.status === 'success' ? 'badge-soft-success' : (s.status === 'pending' ? 'badge-soft-warning' : 'badge-soft-danger');
                        recentSmsHtml += `
                            <tr>
                                <td class="text-muted" style="font-size: 11px;">${s.time}</td>
                                <td class="fw-bold">${s.recipient}</td>
                                <td><span class="badge badge-soft-primary">${s.sender}</span></td>
                                <td>${s.operator}</td>
                                <td><span class="badge ${sBadge}">${s.status}</span></td>
                            </tr>
                        `;
                    });
                } else {
                    recentSmsHtml = '<tr><td colspan="5" class="text-center py-3 text-muted">No recent SMS transactions.</td></tr>';
                }

                let sendersHtml = '';
                if (d.sender_ids && d.sender_ids.length) {
                    d.sender_ids.forEach(function(s) {
                        sendersHtml += `<span class="badge badge-soft-primary me-1">${s}</span>`;
                    });
                } else {
                    sendersHtml = '<span class="text-muted" style="font-size: 11px;">No dedicated Sender IDs assigned.</span>';
                }

                $('#modal-inspect-body').html(`
                    <div class="row g-3 mb-3">
                        <div class="col-md-3 col-6">
                            <div class="p-3 border rounded bg-light text-center">
                                <small class="text-muted d-block" style="font-size: 11px;">PENDING QUEUE</small>
                                <span class="fs-4 fw-bold ${d.pending_queue > 0 ? 'text-warning' : 'text-dark'}">${Number(d.pending_queue).toLocaleString()}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 border rounded bg-light text-center">
                                <small class="text-muted d-block" style="font-size: 11px;">PROCESSING</small>
                                <span class="fs-4 fw-bold ${d.processing_queue > 0 ? 'text-info' : 'text-dark'}">${Number(d.processing_queue).toLocaleString()}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 border rounded bg-light text-center">
                                <small class="text-muted d-block" style="font-size: 11px;">TODAY TOTAL</small>
                                <span class="fs-4 fw-bold text-dark">${Number(d.today_total).toLocaleString()}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 border rounded bg-light text-center">
                                <small class="text-muted d-block" style="font-size: 11px;">DELIVERY RATE</small>
                                <span class="fs-4 fw-bold text-success">${d.delivery_rate}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="card border rounded p-3 mb-3 bg-white">
                        <div class="row g-2" style="font-size: 12px;">
                            <div class="col-md-6"><strong>Account Balance:</strong> <span class="text-dark fw-bold">৳${Number(d.balance).toFixed(2)}</span></div>
                            <div class="col-md-6"><strong>Lifetime Dispatches:</strong> ${Number(d.lifetime_total).toLocaleString()} SMS</div>
                            <div class="col-md-6"><strong>Parent Hierarchy:</strong> ${d.parent_name}</div>
                            <div class="col-md-6"><strong>Email:</strong> ${d.email || 'N/A'}</div>
                        </div>
                        <div class="mt-2 pt-2 border-top">
                            <strong style="font-size: 11px;" class="d-block mb-1">Active Sender IDs:</strong>
                            ${sendersHtml}
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2" style="font-size: 12px;"><i class="fas fa-history me-1 text-primary"></i> Recent Dispatches:</h6>
                    <div class="table-responsive border rounded">
                        <table class="table table-sm table-modern align-middle mb-0" style="font-size: 11px;">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Recipient</th>
                                    <th>Sender</th>
                                    <th>Operator</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${recentSmsHtml}
                            </tbody>
                        </table>
                    </div>
                `);
            },
            error: function() {
                $('#modal-inspect-body').html('<div class="alert alert-danger">Error loading queue details.</div>');
            }
        });
    });

    // Apply Scope from Modal
    $('#btn-modal-apply-scope').on('click', function() {
        if (activeInspectedUserId) {
            bootstrap.Modal.getInstance(document.getElementById('modal-drilldown-inspector')).hide();
            $('#select-filter-user').val(activeInspectedUserId).trigger('change');
            $('html, body').animate({ scrollTop: 0 }, 'fast');
        }
    });

    // Dropdown Scope Change Handler with URL State Sync
    $('#select-filter-user').on('change', function() {
        const selectedVal = $(this).val();
        const selectedText = $(this).find('option:selected').text().trim();

        // Update URL query state for deep-linking
        const url = new URL(window.location.href);
        if (selectedVal) {
            url.searchParams.set('filter_user_id', selectedVal);
        } else {
            url.searchParams.delete('filter_user_id');
        }
        window.history.pushState({ filter_user_id: selectedVal }, '', url.toString());

        if (selectedVal) {
            $('#label-kpi-heading').text("{{ __('Scoped SMS Queue Statistics') }}");
            $('#badge-traffic-scope').removeClass('badge-soft-primary').addClass('badge-soft-warning').text(selectedText);
            $('#sub-kpi-heading').html('<i class="fas fa-filter me-1"></i>Filtered live traffic view for ' + selectedText);
        } else {
            $('#label-kpi-heading').text("{{ __('Global SMS Queue Statistics') }}");
            $('#badge-traffic-scope').removeClass('badge-soft-warning').addClass('badge-soft-primary').text("{{ __('ALL PLATFORM TRAFFIC') }}");
            $('#sub-kpi-heading').html('<i class="fas fa-info-circle me-1"></i>Cross-tenant aggregated live queue performance across all gateways & workers');
        }
        fetchLiveData();
    });

    // Browser back/forward navigation support
    window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const uid = urlParams.get('filter_user_id') || '';
        if ($('#select-filter-user').val() !== uid) {
            $('#select-filter-user').val(uid).trigger('change');
        }
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
