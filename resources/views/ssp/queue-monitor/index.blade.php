@extends('layouts.ssp.master')

@section('title', __('Live SMS Monitoring (Sub-Reseller)'))

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
                        <h4 class="m-0 fw-bold text-dark">{{ __('Live SMS Monitoring') }}</h4>
                        <span class="badge badge-soft-primary ms-1" style="font-size: 11px;">SUB-RESELLER SCOPE</span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3 mt-1" style="font-size: 13px; color: #64748b;">
                        <span><i class="fas fa-sitemap me-1"></i> Account: <strong>{{ auth()->user()->name }}</strong> ({{ '@' . (auth()->user()->username ?? auth()->user()->name) }})</span>
                        <span>•</span>
                        <span><i class="fas fa-microchip me-1"></i> Worker Engine: <strong class="text-success" id="workers-top-count">{{ $workers['active_workers'] }} Active</strong></span>
                        <span>•</span>
                        <span><i class="fas fa-tachometer-alt me-1 text-info"></i> Speed: <strong id="val-tps" class="text-info">{{ $metrics['current_tps'] }}</strong> TPS</span>
                        <span>•</span>
                        <span><i class="fas fa-check-circle me-1 text-success"></i> Delivery Rate: <strong id="val-delivery-rate" class="text-success">{{ $metrics['delivery_rate'] }}%</strong></span>
                        <span>•</span>
                        <span><i class="far fa-clock me-1"></i> Last Synced: <strong id="last-synced-time">{{ now()->format('H:i:s') }}</strong></span>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Customer Drill-Down Selector -->
                    <div style="min-width: 220px;">
                        <select class="form-select form-select-sm" id="select-filter-user" style="font-size: 12px; font-weight: 600;">
                            <option value="">{{ __('🌐 All Registered Customers') }}</option>
                            @foreach($downlineUsers ?? [] as $u)
                                <option value="{{ $u['id'] }}" {{ ($filterUserId ?? null) == $u['id'] ? 'selected' : '' }}>
                                    {{ $u['name'] }} (@{{ $u['username'] ?? $u['name'] }})
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
                        <span>{{ __('Refresh') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Hierarchical Drill-Down Navigation: Sub-reseller -> Customer -> SMS Queue Details -->
        <div class="panel-box mb-4 py-2 px-3" id="drilldown-nav-container" style="background: #f8fafc; border-left: 4px solid #4f46e5;">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                        <i class="fas fa-sitemap text-primary me-1"></i> {{ __('Hierarchy Drill-Down') }}:
                    </span>
                    <div class="d-flex flex-wrap align-items-center gap-1" id="drilldown-breadcrumbs-list">
                        <!-- Root Level: Sub-Reseller -->
                        <button type="button" class="btn btn-sm {{ empty($breadcrumbs) ? 'btn-primary shadow-sm' : 'btn-light border' }} py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="" style="font-size: 12px; font-weight: 600;">
                            <i class="fas fa-users {{ empty($breadcrumbs) ? 'text-white' : 'text-primary' }}"></i>
                            <span>{{ __('Sub-reseller') }}: {{ auth()->user()->name }}</span>
                            <span class="badge {{ empty($breadcrumbs) ? 'bg-light text-dark' : 'badge-soft-primary' }}" style="font-size: 10px;">{{ empty($breadcrumbs) ? __('All Customers') : __('Root') }}</span>
                        </button>
                        @foreach($breadcrumbs ?? [] as $idx => $bc)
                            @if($bc['id'] != auth()->id())
                                <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                                <button type="button" class="btn btn-sm {{ $loop->last && empty($customerQueue) ? 'btn-primary shadow-sm' : 'btn-light border' }} py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="{{ $bc['id'] }}" style="font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-user text-success"></i>
                                    <span>{{ $bc['name'] }}</span>
                                    <span class="badge {{ $loop->last && empty($customerQueue) ? 'bg-light text-dark' : 'badge-soft-info' }}" style="font-size: 10px;">{{ $bc['role_label'] }}</span>
                                </button>
                            @endif
                        @endforeach
                        @if(!empty($customerQueue))
                            <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                            <a href="#panel-customer-queue" class="btn btn-sm btn-primary shadow-sm py-1 px-2 d-flex align-items-center gap-1" style="font-size: 12px; font-weight: 600; text-decoration: none;">
                                <i class="fas fa-layer-group"></i>
                                <span>{{ __('SMS Queue Details') }}</span>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted d-none d-md-inline-block" style="font-size: 11px;">
                        <span class="badge badge-soft-info">{{ __('Sub-reseller') }}</span> <i class="fas fa-arrow-right text-muted mx-1" style="font-size: 9px;"></i>
                        <span class="badge badge-soft-success">{{ __('Customer') }}</span> <i class="fas fa-arrow-right text-muted mx-1" style="font-size: 9px;"></i>
                        <span class="badge badge-soft-primary">{{ __('SMS Queue Details') }}</span>
                    </span>
                    <div id="btn-reset-drilldown-wrapper" class="{{ empty($filterUserId) ? 'd-none' : '' }}">
                        <button type="button" class="btn btn-xs btn-outline-danger py-1 px-2" id="btn-reset-drilldown" style="font-size: 11px;">
                            <i class="fas fa-times me-1"></i> {{ __('Reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 8 KPI Cards Row -->
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
                            <div class="kpi-val text-warning" id="val-processing-count">{{ number_format($metrics['processing_count'] ?? 0) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                            <i class="fas fa-cog fa-spin"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>{{ __('In Worker RAM / Dispatching') }}</span>
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
                            <div class="kpi-label">{{ __('Failed / Rejected') }}</div>
                            <div class="kpi-val text-danger" id="val-today-failed">{{ number_format($metrics['today_failed'] ?? 0) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #fee2e2; color: #dc2626;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Failed: <strong id="val-sub-today-failed">{{ number_format($metrics['today_failed'] ?? 0) }}</strong> • Rejected: <strong id="val-sub-today-rejected">{{ number_format($metrics['today_rejected'] ?? 0) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 7. TPS & Velocity -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Throughput / TPS') }}</div>
                            <div class="kpi-val text-info" id="val-kpi-tps">{{ $metrics['current_tps'] }} <span style="font-size: 13px; font-weight: 500;">TPS</span></div>
                        </div>
                        <div class="kpi-icon" style="background: #cffafe; color: #0891b2;">
                            <i class="fas fa-bolt"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>1m: <strong id="val-kpi-1m">{{ number_format($metrics['last_1_min_volume']) }}</strong> • 5m: <strong id="val-kpi-5m">{{ number_format($metrics['last_5_min_volume']) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 8. Backlog & Engine Status -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Engine Workers') }}</div>
                            <div class="kpi-val text-dark" id="val-kpi-workers">{{ $workers['active_workers'] }} <span style="font-size: 12px; font-weight: 600;" class="badge badge-soft-success" id="val-kpi-worker-badge">{{ ucfirst($workers['status']) }}</span></div>
                        </div>
                        <div class="kpi-icon" style="background: #f1f5f9; color: #334155;">
                            <i class="fas fa-microchip"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Drain: <strong id="val-backlog-drain">{{ $metrics['drain_eta'] ?? 'Optimal Flow' }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Own Customers' SMS Queue Statistics Matrix -->
        <div class="panel-box mb-4" id="panel-customer-matrix">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #dcfce7; color: #15803d;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __("Own Customers' SMS Queue Statistics") }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Real-time client queue status, processing velocity, and dispatch progress across your registered customer base') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-success ms-1"><span id="customers-matrix-count">{{ count($customerSummary['list'] ?? []) }}</span> {{ __('Customers Monitored') }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="max-width: 250px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-sm border-start-0" id="input-search-customers" placeholder="{{ __('Search customer...') }}" style="font-size: 12px;">
                    </div>
                </div>
            </div>

            <!-- Aggregated Mini Counters for Own Customers -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('ACTIVE CLIENTS') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="cust-agg-total">{{ $customerSummary['aggregates']['total_customers'] ?? 0 }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Registered Accounts') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('ACTIVE QUEUE TOTAL') }}</span>
                            <span class="fs-5 fw-bold text-primary" id="cust-agg-active-queue">{{ number_format(($customerSummary['aggregates']['pending_queue'] ?? 0) + ($customerSummary['aggregates']['processing_queue'] ?? 0)) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('RAM + DB Pipeline') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PENDING IN QUEUE') }}</span>
                            <span class="fs-5 fw-bold {{ ($customerSummary['aggregates']['pending_queue'] ?? 0) > 0 ? 'text-warning' : 'text-muted' }}" id="cust-agg-pending">{{ number_format($customerSummary['aggregates']['pending_queue'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Awaiting Workers') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PROCESSING (RAM)') }}</span>
                            <span class="fs-5 fw-bold {{ ($customerSummary['aggregates']['processing_queue'] ?? 0) > 0 ? 'text-info' : 'text-muted' }}" id="cust-agg-processing">{{ number_format($customerSummary['aggregates']['processing_queue'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Active Dispatching') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('TODAY DISPATCHED') }}</span>
                            <span class="fs-5 fw-bold text-success" id="cust-agg-today">{{ number_format($customerSummary['aggregates']['today_total'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;"><span class="text-success fw-bold">{{ __('Delivered') }}: <span id="cust-agg-delivered">{{ number_format($customerSummary['aggregates']['today_delivered'] ?? 0) }}</span></span></small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DELIVERY RATE') }}</span>
                            <span class="fs-5 fw-bold text-success" id="cust-agg-rate">{{ $customerSummary['aggregates']['delivery_rate'] ?? 100 }}%</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Success Ratio') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-customers-matrix">
                    <thead>
                        <tr>
                            <th>{{ __('Customer Account & Phone') }}</th>
                            <th class="text-center">{{ __('Pending Queue') }}</th>
                            <th class="text-center">{{ __('Processing (RAM)') }}</th>
                            <th class="text-center">{{ __('Today Total SMS') }}</th>
                            <th class="text-center">{{ __('Today Delivered') }}</th>
                            <th class="text-center">{{ __('Today Failed') }}</th>
                            <th class="text-center">{{ __('Delivery Rate') }}</th>
                            <th class="text-center" style="min-width: 130px;">{{ __('Traffic Share') }}</th>
                            <th class="text-end">{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-customers-matrix">
                        @forelse($customerSummary['list'] ?? [] as $cust)
                        <tr data-cust-id="{{ $cust['id'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                    <i class="fas fa-user text-success me-1"></i>
                                    {{ $cust['name'] }}
                                </div>
                                <small class="text-muted">@<!-- -->{{ $cust['username'] }} @if(!empty($cust['phone']) && $cust['phone'] !== 'N/A') • {{ $cust['phone'] }} @endif</small>
                            </td>
                            <td class="text-center fw-bold {{ $cust['pending_queue'] > 0 ? 'text-warning' : 'text-muted' }}">
                                {{ number_format($cust['pending_queue']) }}
                            </td>
                            <td class="text-center fw-bold {{ $cust['processing_queue'] > 0 ? 'text-info' : 'text-muted' }}">
                                {{ number_format($cust['processing_queue']) }}
                            </td>
                            <td class="text-center fw-bold text-dark">{{ number_format($cust['today_total']) }}</td>
                            <td class="text-center fw-bold text-success">{{ number_format($cust['today_delivered']) }}</td>
                            <td class="text-center fw-bold {{ $cust['today_failed'] > 0 ? 'text-danger' : 'text-muted' }}">{{ number_format($cust['today_failed']) }}</td>
                            <td class="text-center">
                                <span class="badge badge-soft-success">{{ $cust['delivery_rate'] }}%</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="fw-bold">{{ $cust['traffic_share'] }}%</span>
                                    <small class="text-muted">{{ number_format($cust['lifetime_total']) }}</small>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $cust['traffic_share'] }}%;" aria-valuenow="{{ $cust['traffic_share'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-end fw-bold text-dark">৳{{ number_format($cust['balance'], 2) }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-success py-1 px-2 btn-open-customer-queue" data-id="{{ $cust['id'] }}" title="{{ __('Open live queue messages for this customer') }}" style="font-size: 11px;">
                                    <i class="fas fa-layer-group me-1"></i> {{ __('View Queue') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-primary py-1 px-2 btn-filter-reseller" data-id="{{ $cust['id'] }}" title="{{ __('Drill-down entire dashboard') }}" style="font-size: 11px;">
                                    <i class="fas fa-crosshairs"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="{{ $cust['id'] }}" title="{{ __('Inspect Queue Details') }}" style="font-size: 11px;">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">{{ __('No customers registered under your Sub-Reseller account.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dedicated Customer SMS Queue Details & Direct Message Actions -->
        <div class="panel-box mb-4 {{ empty($customerQueue) ? 'd-none' : '' }}" id="panel-customer-queue">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-user-tag" style="font-size: 13px;"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            <span id="cust-queue-title-prefix">{{ __('Customer SMS Queue Details') }}</span>:
                            <span class="text-primary" id="cust-queue-name">{{ $customerQueue['customer']['name'] ?? '' }}</span>
                        </h5>
                        <div class="text-muted d-flex align-items-center flex-wrap gap-1" style="font-size: 11px;" id="cust-queue-hierarchy-path">
                            <span class="fw-bold text-dark">{{ __('Sub-reseller') }}:</span> {{ auth()->user()->name }} <i class="fas fa-angle-right mx-1 text-muted"></i>
                            <span class="fw-bold text-dark">{{ __('Customer') }}:</span> <strong class="text-dark">{{ $customerQueue['customer']['name'] ?? 'Customer' }}</strong> <i class="fas fa-angle-right mx-1 text-muted"></i>
                            <span class="badge bg-primary text-white"><i class="fas fa-layer-group me-1"></i>{{ __('SMS Queue Details') }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-soft-info" style="font-size: 11px;">
                        <i class="fas fa-wallet me-1"></i> {{ __('Balance') }}: ৳<strong id="cust-queue-balance">{{ number_format($customerQueue['customer']['balance'] ?? 0, 2) }}</strong>
                    </span>
                    <!-- Queue Status Filter Tabs -->
                    <div class="btn-group btn-group-sm" role="group" aria-label="Customer Queue Status Filter">
                        <button type="button" class="btn btn-outline-primary cust-status-tab-btn active" data-status="all">{{ __('All') }}</button>
                        <button type="button" class="btn btn-outline-warning cust-status-tab-btn" data-status="pending">{{ __('Pending') }}</button>
                        <button type="button" class="btn btn-outline-info cust-status-tab-btn" data-status="processing">{{ __('Processing') }}</button>
                        <button type="button" class="btn btn-outline-success cust-status-tab-btn" data-status="delivered">{{ __('Delivered') }}</button>
                    </div>
                </div>
            </div>

            <!-- Mini Summary Counters for Target Customer Queue -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-md-2 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PENDING IN QUEUE') }}</span>
                            <span class="fs-5 fw-bold text-warning" id="cust-kpi-pending">{{ number_format($customerQueue['summary']['pending_queue'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PROCESSING (RAM)') }}</span>
                            <span class="fs-5 fw-bold text-info" id="cust-kpi-processing">{{ number_format($customerQueue['summary']['processing_queue'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DELIVERED TODAY') }}</span>
                            <span class="fs-5 fw-bold text-success" id="cust-kpi-delivered">{{ number_format($customerQueue['summary']['today_delivered'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('FAILED TODAY') }}</span>
                            <span class="fs-5 fw-bold text-danger" id="cust-kpi-failed">{{ number_format($customerQueue['summary']['today_failed'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DELIVERY RATE') }}</span>
                            <span class="fs-5 fw-bold text-success" id="cust-kpi-rate">{{ $customerQueue['summary']['delivery_rate'] ?? 100 }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Message Dispatches Table -->
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-customer-queue">
                    <thead>
                        <tr>
                            <th>{{ __('Queued Time') }}</th>
                            <th>{{ __('Recipient') }}</th>
                            <th>{{ __('Sender ID') }}</th>
                            <th>{{ __('Operator') }}</th>
                            <th>{{ __('Gateway Provider') }}</th>
                            <th class="text-center">{{ __('Total SMS') }}</th>
                            <th class="text-center">{{ __('Charge') }}</th>
                            <th>{{ __('Status & Response') }}</th>
                            <th class="text-center">{{ __('Latency') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-customer-queue">
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
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>
                                @elseif($msg['can_retry'])
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btn-retry-sms" data-id="{{ $msg['id'] }}" title="{{ __('Re-queue failed SMS') }}" style="font-size: 11px;">
                                    <i class="fas fa-redo-alt me-1"></i> Retry
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-light border py-0 px-2 btn-view-sms" data-id="{{ $msg['id'] }}" data-content="{{ rawurlencode($msg['content']) }}" title="{{ __('View message content') }}" style="font-size: 11px;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">{{ __('No queued or dispatched SMS for this customer.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Middle Section: Operator Distribution & Top Customers -->
        <div class="row g-4 mb-4">
            <!-- Operator Breakdown Donut Chart -->
            <div class="col-lg-6 col-12">
                <div class="panel-box h-100">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-chart-pie text-success"></i>
                            {{ __('Client Operator Breakdown (24h)') }}
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

            <!-- Top Customers -->
            <div class="col-lg-6 col-12">
                <div class="panel-box h-100">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-users-cog text-warning"></i>
                            {{ __('Top Customers (24h)') }}
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
                        <div class="text-center py-4 text-muted">{{ __('No customer traffic recorded in last 24 hours.') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Live SMS Stream -->
        <div class="panel-box mb-4">
            <div class="panel-header">
                <h5 class="panel-title">
                    <i class="fas fa-stream text-primary"></i>
                    {{ __('Live Client SMS Dispatch Stream') }}
                </h5>
                <span class="badge badge-soft-success"><i class="fas fa-satellite-dish me-1"></i> {{ __('Live Stream') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-stream">
                    <thead>
                        <tr>
                            <th>{{ __('Time') }}</th>
                            <th>{{ __('Customer Username') }}</th>
                            <th>{{ __('Recipient') }}</th>
                            <th>{{ __('Sender ID') }}</th>
                            <th>{{ __('Operator') }}</th>
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
                            <td colspan="7" class="text-center py-4 text-muted">{{ __('No dispatched traffic detected yet.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    <i class="fas fa-crosshairs me-1"></i> {{ __('Drill-Down Scope') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View SMS Content Modal -->
<div class="modal fade" id="modal-view-sms" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3 bg-light border-bottom">
                <h6 class="modal-title fw-bold mb-0"><i class="fas fa-sms text-primary me-2"></i> SMS Content #<span id="view-sms-id"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="p-3 bg-light rounded border text-dark" id="view-sms-content" style="white-space: pre-wrap; word-break: break-word; font-family: monospace; font-size: 13px;"></div>
            </div>
            <div class="modal-footer py-2 bg-light border-top">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
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
    let currentCustQueueStatusFilter = 'all';
    let currentCustomerMatrixData = @json($customerSummary ?? []);
    let currentSearchTerm = '';

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

        const filterUserId = $('#select-filter-user').val();
        $.ajax({
            url: "{{ route('ssp.queue-monitor.data') }}",
            type: "GET",
            data: {
                filter_user_id: filterUserId,
                status_filter: currentCustQueueStatusFilter
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
                $('#workers-top-count').text(activeW + ' Active');
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

                // Render Customer-Wise Queue Matrix & Mini Counters
                renderCustomersMatrixTable(res.customers);

                // Update Hierarchical Breadcrumbs
                updateBreadcrumbs(res.breadcrumbs, !!res.customer_queue);

                // Dedicated Customer SMS Queue Details
                renderCustomerQueueDetails(res.customer_queue);

                // Top Customers
                renderTopSenders(res.metrics.top_senders);

                // Live Stream
                renderStreamTable(res.metrics.live_stream);
            },
            error: function(err) {
                console.warn('SSP Queue monitor poll failed:', err);
                $('#live-indicator').addClass('warning');
            },
            complete: function() {
                isFetching = false;
                $('#refresh-spinner').removeClass('fa-spin');
            }
        });
    }

    // 3. Render Customer-Wise Queue Matrix & Mini Counters
    function renderCustomersMatrixTable(customerSummary) {
        if (!customerSummary) return;
        currentCustomerMatrixData = customerSummary;
        const list = customerSummary.list || [];
        const agg = customerSummary.aggregates || {};

        let filtered = list.slice();
        if (currentSearchTerm.trim() !== '') {
            const term = currentSearchTerm.toLowerCase();
            filtered = filtered.filter(function(c) {
                const name = (c.name || '').toLowerCase();
                const username = (c.username || '').toLowerCase();
                const phone = (c.phone || '').toLowerCase();
                return name.includes(term) || username.includes(term) || phone.includes(term);
            });
        }

        $('#customers-matrix-count').text(filtered.length);
        $('#cust-agg-total').text(agg.total_customers || 0);
        $('#cust-agg-active-queue').text(Number((agg.pending_queue || 0) + (agg.processing_queue || 0)).toLocaleString());
        $('#cust-agg-pending').text(Number(agg.pending_queue || 0).toLocaleString());
        $('#cust-agg-processing').text(Number(agg.processing_queue || 0).toLocaleString());
        $('#cust-agg-today').text(Number(agg.today_total || 0).toLocaleString());
        $('#cust-agg-delivered').text(Number(agg.today_delivered || 0).toLocaleString());
        $('#cust-agg-rate').text((agg.delivery_rate || 100) + '%');

        if (!filtered.length) {
            $('#tbody-customers-matrix').html('<tr><td colspan="10" class="text-center py-4 text-muted">No customers match this search.</td></tr>');
            return;
        }

        let html = '';
        filtered.forEach(function(cust) {
            const pendingClass = cust.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = cust.processing_queue > 0 ? 'text-info' : 'text-muted';
            const failClass = cust.today_failed > 0 ? 'text-danger' : 'text-muted';
            const phoneStr = (cust.phone && cust.phone !== 'N/A') ? ` • ${cust.phone}` : '';

            html += `
                <tr data-cust-id="${cust.id}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-user text-success me-1"></i>
                            ${cust.name}
                        </div>
                        <small class="text-muted">@${cust.username}${phoneStr}</small>
                    </td>
                    <td class="text-center fw-bold ${pendingClass}">${Number(cust.pending_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold ${procClass}">${Number(cust.processing_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold text-dark">${Number(cust.today_total).toLocaleString()}</td>
                    <td class="text-center fw-bold text-success">${Number(cust.today_delivered).toLocaleString()}</td>
                    <td class="text-center fw-bold ${failClass}">${Number(cust.today_failed).toLocaleString()}</td>
                    <td class="text-center">
                        <span class="badge badge-soft-success">${cust.delivery_rate}%</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                            <span class="fw-bold">${cust.traffic_share}%</span>
                            <small class="text-muted">${Number(cust.lifetime_total).toLocaleString()}</small>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: ${cust.traffic_share}%;" aria-valuenow="${cust.traffic_share}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark">৳${Number(cust.balance).toFixed(2)}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-success py-1 px-2 btn-open-customer-queue" data-id="${cust.id}" title="Open live queue messages for this customer" style="font-size: 11px;">
                            <i class="fas fa-layer-group me-1"></i> View Queue
                        </button>
                        <button type="button" class="btn btn-sm btn-primary py-1 px-2 btn-filter-reseller" data-id="${cust.id}" title="Drill-down entire dashboard" style="font-size: 11px;">
                            <i class="fas fa-crosshairs"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="${cust.id}" title="Inspect Queue Details" style="font-size: 11px;">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody-customers-matrix').html(html);
    }

    // 4. Render Dedicated Customer SMS Queue Details
    function renderCustomerQueueDetails(cq) {
        if (!cq) {
            $('#panel-customer-queue').addClass('d-none');
            return;
        }

        $('#panel-customer-queue').removeClass('d-none');
        const c = cq.customer || {};
        const s = cq.summary || {};

        $('#cust-queue-name').text(c.name ? c.name + ' (@' + c.username + ')' : '');
        $('#cust-queue-balance').text(Number(c.balance || 0).toFixed(2));

        $('#cust-queue-hierarchy-path').html(`
            <span class="fw-bold text-dark">{{ __('Sub-reseller') }}:</span> {{ auth()->user()->name }} <i class="fas fa-angle-right mx-1 text-muted"></i>
            <span class="fw-bold text-dark">{{ __('Customer') }}:</span> <strong class="text-dark">${c.name || 'Customer'}</strong> <i class="fas fa-angle-right mx-1 text-muted"></i>
            <span class="badge bg-primary text-white"><i class="fas fa-layer-group me-1"></i>{{ __('SMS Queue Details') }}</span>
        `);

        $('#cust-kpi-pending').text(Number(s.pending_queue || 0).toLocaleString());
        $('#cust-kpi-processing').text(Number(s.processing_queue || 0).toLocaleString());
        $('#cust-kpi-delivered').text(Number(s.today_delivered || 0).toLocaleString());
        $('#cust-kpi-failed').text(Number(s.today_failed || 0).toLocaleString());
        $('#cust-kpi-rate').text((s.delivery_rate || 100) + '%');

        const msgs = cq.messages || [];
        if (!msgs.length) {
            $('#tbody-customer-queue').html('<tr><td colspan="10" class="text-center py-4 text-muted">No queued or dispatched SMS for this customer.</td></tr>');
            return;
        }

        let html = '';
        msgs.forEach(function(m) {
            let actionBtns = '';
            if (m.can_cancel) {
                actionBtns += `<button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-cancel-sms" data-id="${m.id}" title="Cancel pending SMS" style="font-size: 11px;"><i class="fas fa-times me-1"></i> Cancel</button> `;
            } else if (m.can_retry) {
                actionBtns += `<button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btn-retry-sms" data-id="${m.id}" title="Re-queue failed SMS" style="font-size: 11px;"><i class="fas fa-redo-alt me-1"></i> Retry</button> `;
            }
            actionBtns += `<button type="button" class="btn btn-sm btn-light border py-0 px-2 btn-view-sms" data-id="${m.id}" data-content="${encodeURIComponent(m.content)}" title="View message content" style="font-size: 11px;"><i class="fas fa-eye"></i></button>`;

            html += `
                <tr data-sms-id="${m.id}">
                    <td class="text-muted" style="font-size: 11px;">
                        <i class="far fa-clock me-1"></i> ${m.queued_at}
                    </td>
                    <td><strong class="text-dark">${m.recipient}</strong></td>
                    <td><span class="badge badge-soft-primary px-2 py-1">${m.sender}</span></td>
                    <td><span class="badge badge-soft-secondary">${m.operator}</span></td>
                    <td class="text-muted" style="font-size: 12px;">${m.gateway}</td>
                    <td class="text-center fw-semibold">${m.total_parts} Part(s)</td>
                    <td class="text-center fw-bold text-dark">৳${Number(m.charge).toFixed(2)}</td>
                    <td>
                        <span class="badge ${m.status_badge}">${m.status}</span>
                        <small class="text-muted d-block mt-1" style="font-size: 10px;">${m.response_code}</small>
                    </td>
                    <td class="text-center text-muted" style="font-size: 11px;">
                        <i class="fas fa-stopwatch me-1 text-info"></i> ${m.latency}
                    </td>
                    <td class="text-end text-nowrap">
                        ${actionBtns}
                    </td>
                </tr>
            `;
        });
        $('#tbody-customer-queue').html(html);
    }

    // 5. Breadcrumbs Engine: Sub-reseller -> Customer -> SMS Queue Details
    function updateBreadcrumbs(breadcrumbs, hasCustomerQueue) {
        const rootActive = !breadcrumbs || !breadcrumbs.length;
        const rootBtnClass = rootActive ? 'btn-primary shadow-sm' : 'btn-light border';
        const rootIconColor = rootActive ? 'text-white' : 'text-primary';
        const rootBadgeClass = rootActive ? 'bg-light text-dark' : 'badge-soft-primary';
        const rootBadgeText = rootActive ? "{{ __('All Customers') }}" : "{{ __('Root') }}";

        let html = `
            <button type="button" class="btn btn-sm ${rootBtnClass} py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="" style="font-size: 12px; font-weight: 600;">
                <i class="fas fa-users ${rootIconColor}"></i>
                <span>{{ __('Sub-reseller') }}: {{ auth()->user()->name }}</span>
                <span class="badge ${rootBadgeClass}" style="font-size: 10px;">${rootBadgeText}</span>
            </button>
        `;

        if (breadcrumbs && breadcrumbs.length) {
            breadcrumbs.forEach(function(bc, idx) {
                if (bc.id == {{ auth()->id() }}) return; // skip self
                const isLast = idx === (breadcrumbs.length - 1);
                const isLeafActive = isLast && !hasCustomerQueue;
                const btnClass = isLeafActive ? 'btn-primary shadow-sm' : 'btn-light border';
                const badgeClass = isLeafActive ? 'bg-light text-dark' : 'badge-soft-info';

                html += `
                    <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                    <button type="button" class="btn btn-sm ${btnClass} py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="${bc.id}" style="font-size: 12px; font-weight: 600;">
                        <i class="fas fa-user text-success"></i>
                        <span>${bc.name}</span>
                        <span class="badge ${badgeClass}" style="font-size: 10px;">${bc.role_label}</span>
                    </button>
                `;
            });
        }

        if (hasCustomerQueue) {
            html += `
                <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                <a href="#panel-customer-queue" class="btn btn-sm btn-primary shadow-sm py-1 px-2 d-flex align-items-center gap-1" style="font-size: 12px; font-weight: 600; text-decoration: none;">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ __('SMS Queue Details') }}</span>
                </a>
            `;
        }

        $('#drilldown-breadcrumbs-list').html(html);

        if (!rootActive) {
            $('#btn-reset-drilldown-wrapper').removeClass('d-none');
        } else {
            $('#btn-reset-drilldown-wrapper').addClass('d-none');
        }
    }

    // 6. Top Senders & Live Stream
    function renderTopSenders(senders) {
        if (!senders) return;
        if (!senders.length) {
            $('#top-senders-list').html('<div class="text-center py-4 text-muted">No customer traffic in last 24 hours.</div>');
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
                    <td class="text-muted" style="font-size: 12px;">${sms.sender}</td>
                    <td><span class="badge badge-soft-primary">${sms.operator}</span></td>
                    <td>${statusBadge}</td>
                    <td class="text-muted" style="font-size: 12px;">${sms.response_code || ''}</td>
                </tr>
            `;
        });
        $('#tbody-stream').html(html);
    }

    // Customer Matrix Search Filter
    $('#input-search-customers').on('input', function() {
        currentSearchTerm = $(this).val();
        renderCustomersMatrixTable(currentCustomerMatrixData);
    });

    // Customer Queue Status Tabs
    $(document).on('click', '.cust-status-tab-btn', function() {
        $('.cust-status-tab-btn').removeClass('active');
        $(this).addClass('active');
        currentCustQueueStatusFilter = $(this).data('status');
        fetchLiveData();
    });

    // Open Live Customer Queue Details
    $(document).on('click', '.btn-open-customer-queue', function() {
        const custId = $(this).data('id');
        $('#select-filter-user').val(custId);
        fetchLiveData();
        setTimeout(function() {
            if ($('#panel-customer-queue').length) {
                $('html, body').animate({
                    scrollTop: $('#panel-customer-queue').offset().top - 80
                }, 'fast');
            }
        }, 300);
    });

    // Drill-Down into Customer
    $(document).on('click', '.btn-filter-reseller', function() {
        const id = $(this).data('id');
        $('#select-filter-user').val(id).trigger('change');
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    // Click Breadcrumb Trail Link
    $(document).on('click', '.btn-nav-breadcrumb', function() {
        const id = $(this).data('id');
        $('#select-filter-user').val(id).trigger('change');
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    // Reset Drill-down back to Global
    $(document).on('click', '#btn-reset-drilldown', function() {
        $('#select-filter-user').val('').trigger('change');
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    // Account Queue Inspector Modal
    $(document).on('click', '.btn-inspect-user', function() {
        const id = $(this).data('id');
        $('#btn-modal-apply-scope').data('id', id);
        const modal = new bootstrap.Modal(document.getElementById('modal-drilldown-inspector'));
        modal.show();

        $('#modal-inspect-body').html(`
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">Loading live queue analytics for customer #${id}...</p>
            </div>
        `);

        $.ajax({
            url: "{{ url('ssp/queue-monitor/inspect') }}/" + id,
            type: "GET",
            dataType: "json",
            success: function(res) {
                if (!res.success || !res.data) {
                    $('#modal-inspect-body').html('<div class="alert alert-danger">Failed to load customer details.</div>');
                    return;
                }

                const d = res.data;
                $('#modal-inspect-name').text(d.name + ' (@' + d.username + ')');
                $('#modal-inspect-role').text(d.role_label + ' • Balance: ৳' + Number(d.balance).toFixed(2));

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
                                <small class="text-muted d-block" style="font-size: 11px;">TODAY DISPATCHED</small>
                                <span class="fs-4 fw-bold text-success">${Number(d.today_total).toLocaleString()}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 border rounded bg-light text-center">
                                <small class="text-muted d-block" style="font-size: 11px;">SUCCESS RATE</small>
                                <span class="fs-4 fw-bold text-success">${d.delivery_rate}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-bold mb-2" style="font-size: 13px;"><i class="fas fa-id-card text-primary me-1"></i> Assigned Sender IDs</h6>
                        <div>${sendersHtml}</div>
                    </div>

                    <div>
                        <h6 class="fw-bold mb-2" style="font-size: 13px;"><i class="fas fa-history text-secondary me-1"></i> Recent Queue Dispatches</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-modern align-middle" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Recipient</th>
                                        <th>Sender</th>
                                        <th>Operator</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>${recentSmsHtml}</tbody>
                            </table>
                        </div>
                    </div>
                `);
            },
            error: function() {
                $('#modal-inspect-body').html('<div class="alert alert-danger">Error loading customer queue analytics.</div>');
            }
        });
    });

    $('#btn-modal-apply-scope').on('click', function() {
        const id = $(this).data('id');
        bootstrap.Modal.getInstance(document.getElementById('modal-drilldown-inspector')).hide();
        $('#select-filter-user').val(id).trigger('change');
    });

    // Cancel SMS
    $(document).on('click', '.btn-cancel-sms', function() {
        const btn = $(this);
        const id = btn.data('id');
        if (!confirm('Are you sure you want to cancel pending message #' + id + '?')) return;

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        $.ajax({
            url: "{{ url('ssp/queue-monitor/cancel-sms') }}/" + id,
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    fetchLiveData();
                } else {
                    toastr.error(res.message || 'Failed to cancel SMS.');
                }
            },
            error: function() {
                toastr.error('Failed to cancel SMS.');
            },
            complete: function() { btn.prop('disabled', false); }
        });
    });

    // Retry SMS
    $(document).on('click', '.btn-retry-sms', function() {
        const btn = $(this);
        const id = btn.data('id');

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        $.ajax({
            url: "{{ url('ssp/queue-monitor/retry-sms') }}/" + id,
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    fetchLiveData();
                } else {
                    toastr.error(res.message || 'Failed to retry SMS.');
                }
            },
            error: function() {
                toastr.error('Failed to retry SMS.');
            },
            complete: function() { btn.prop('disabled', false); }
        });
    });

    // View message text modal
    $(document).on('click', '.btn-view-sms', function() {
        const id = $(this).data('id');
        const content = decodeURIComponent($(this).data('content'));
        $('#view-sms-id').text(id);
        $('#view-sms-content').text(content);
        const modal = new bootstrap.Modal(document.getElementById('modal-view-sms'));
        modal.show();
    });

    // Polling interval timer
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

    $('#select-filter-user').on('change', function() {
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
