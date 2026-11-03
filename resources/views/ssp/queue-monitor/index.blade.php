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
                    <button type="button" class="btn btn-sm btn-outline-warning" id="btn-toggle-matrix-pending" data-filter="all" style="font-size: 11px;">
                        <i class="fas fa-hourglass-half me-1"></i> {{ __('Pending Queue Only') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" id="btn-toggle-matrix-proc" data-filter="all" style="font-size: 11px;">
                        <i class="fas fa-cog me-1"></i> {{ __('Processing Only') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-toggle-matrix-subm" data-filter="all" style="font-size: 11px;">
                        <i class="fas fa-paper-plane me-1"></i> {{ __('Submitted Today') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" id="btn-toggle-matrix-deliv" data-filter="all" style="font-size: 11px;">
                        <i class="fas fa-check-double me-1"></i> {{ __('Delivered Today') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="btn-toggle-matrix-fail" data-filter="all" style="font-size: 11px;">
                        <i class="fas fa-times-circle me-1"></i> {{ __('Failed Today') }}
                    </button>
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

        <!-- Customer-Wise Pending SMS Queue Section -->
        <div class="panel-box mb-4" id="panel-customer-pending">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #fef3c7; color: #d97706;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __('Customer-Wise Pending SMS Queue') }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Real-time client backlog tracking, wait time latency, operator routing, and immediate queue control per customer') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-warning ms-1">
                        <span id="pending-clients-count">{{ $customerPendingSummary['aggregates']['customers_with_pending'] ?? 0 }}</span> {{ __('Clients with Backlog') }}
                    </span>
                    <span class="badge badge-soft-primary">
                        <span id="pending-total-badge">{{ number_format($customerPendingSummary['aggregates']['total_pending'] ?? 0) }}</span> {{ __('Total Pending') }}
                    </span>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <!-- Operator Filter Tabs -->
                    <div class="btn-group btn-group-sm" role="group" id="btn-group-pending-ops">
                        <button type="button" class="btn btn-outline-secondary active btn-pending-op-filter" data-op="all">{{ __('All Ops') }}</button>
                        <button type="button" class="btn btn-outline-secondary btn-pending-op-filter" data-op="GP">GP</button>
                        <button type="button" class="btn btn-outline-secondary btn-pending-op-filter" data-op="BL">BL</button>
                        <button type="button" class="btn btn-outline-secondary btn-pending-op-filter" data-op="Robi">Robi</button>
                        <button type="button" class="btn btn-outline-secondary btn-pending-op-filter" data-op="Teletalk">TT</button>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 220px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-sm border-start-0" id="input-search-pending" placeholder="{{ __('Search pending client...') }}" style="font-size: 12px;">
                    </div>
                </div>
            </div>

            <!-- Aggregated Mini Counters for Pending Queue -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('TOTAL PENDING SMS') }}</span>
                            <span class="fs-5 fw-bold text-warning" id="kpi-pending-total">{{ number_format($customerPendingSummary['aggregates']['total_pending'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Awaiting Dispatch') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('CLIENTS WITH BACKLOG') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="kpi-pending-clients">{{ $customerPendingSummary['aggregates']['customers_with_pending'] ?? 0 }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;"><span id="kpi-pending-of-total">of {{ $customerPendingSummary['aggregates']['total_customers'] ?? 0 }} Clients</span></small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('MAX WAIT LATENCY') }}</span>
                            <span class="fs-5 fw-bold text-danger" id="kpi-pending-max-wait">{{ $customerPendingSummary['aggregates']['max_wait_human'] ?? 'Queue Clear' }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Oldest Message Age') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PROCESSING IN RAM') }}</span>
                            <span class="fs-5 fw-bold text-info" id="kpi-pending-proc">{{ number_format($customerPendingSummary['aggregates']['total_processing'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Active Dispatching') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">GP PENDING</span>
                            <span class="fs-5 fw-bold text-primary" id="kpi-pending-gp">{{ number_format($customerPendingSummary['aggregates']['operator_pending']['GP'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Grameenphone</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">BL PENDING</span>
                            <span class="fs-5 fw-bold" style="color: #f97316;" id="kpi-pending-bl">{{ number_format($customerPendingSummary['aggregates']['operator_pending']['BL'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Banglalink</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">ROBI PENDING</span>
                            <span class="fs-5 fw-bold text-danger" id="kpi-pending-robi">{{ number_format($customerPendingSummary['aggregates']['operator_pending']['Robi'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Robi / Airtel</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">TT PENDING</span>
                            <span class="fs-5 fw-bold text-success" id="kpi-pending-tt">{{ number_format($customerPendingSummary['aggregates']['operator_pending']['Teletalk'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Teletalk</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Pending Table -->
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-customer-pending">
                    <thead>
                        <tr>
                            <th>{{ __('Customer / Client') }}</th>
                            <th class="text-center">{{ __('Pending Queue') }}</th>
                            <th class="text-center">{{ __('Oldest Wait Latency') }}</th>
                            <th class="text-center">{{ __('Operator Breakdown') }}</th>
                            <th class="text-center">{{ __('Source (Camp / API)') }}</th>
                            <th class="text-center">{{ __('Queue Health') }}</th>
                            <th class="text-end">{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-customer-pending">
                        @forelse($customerPendingSummary['list'] as $cp)
                        <tr data-cust-id="{{ $cp['id'] }}" data-pending-count="{{ $cp['pending_count'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                    <i class="fas fa-user {{ $cp['pending_count'] > 0 ? 'text-warning' : 'text-muted' }} me-1"></i>
                                    {{ $cp['name'] }}
                                </div>
                                <small class="text-muted">
                                    {{ '@' . $cp['username'] }}
                                    @if(!empty($cp['phone']) && $cp['phone'] !== 'N/A')
                                        • {{ $cp['phone'] }}
                                    @endif
                                </small>
                            </td>
                            <td class="text-center">
                                @if($cp['pending_count'] > 0)
                                    <span class="badge bg-warning text-dark fs-6 px-2 py-1 fw-bold">
                                        <i class="fas fa-hourglass-half me-1"></i>{{ number_format($cp['pending_count']) }}
                                    </span>
                                @else
                                    <span class="text-muted fw-bold">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($cp['pending_count'] > 0)
                                    <span class="badge {{ $cp['urgency'] === 'critical' ? 'bg-danger text-white' : ($cp['urgency'] === 'warning' ? 'bg-warning text-dark' : 'bg-light text-dark border') }} px-2 py-1">
                                        <i class="fas fa-clock me-1"></i>{{ $cp['wait_time_human'] }}
                                    </span>
                                @else
                                    <span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check-circle me-1"></i>{{ __('Queue Clear') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1" style="font-size: 10px;">
                                    <span class="badge {{ ($cp['operator_breakdown']['GP'] ?? 0) > 0 ? 'badge-soft-primary' : 'badge-soft-secondary text-muted' }}" title="Grameenphone">GP: {{ $cp['operator_breakdown']['GP'] ?? 0 }}</span>
                                    <span class="badge {{ ($cp['operator_breakdown']['BL'] ?? 0) > 0 ? 'badge-soft-warning' : 'badge-soft-secondary text-muted' }}" title="Banglalink">BL: {{ $cp['operator_breakdown']['BL'] ?? 0 }}</span>
                                    <span class="badge {{ ($cp['operator_breakdown']['Robi'] ?? 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted' }}" title="Robi/Airtel">Robi: {{ $cp['operator_breakdown']['Robi'] ?? 0 }}</span>
                                    <span class="badge {{ ($cp['operator_breakdown']['Teletalk'] ?? 0) > 0 ? 'badge-soft-success' : 'badge-soft-secondary text-muted' }}" title="Teletalk">TT: {{ $cp['operator_breakdown']['Teletalk'] ?? 0 }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-info" style="font-size: 10px;" title="{{ __('Campaign Messages') }}">
                                    <i class="fas fa-bullhorn me-1"></i>{{ $cp['campaign_pending'] ?? 0 }}
                                </span>
                                <span class="badge badge-soft-secondary ms-1" style="font-size: 10px;" title="{{ __('Direct / API Single Messages') }}">
                                    <i class="fas fa-bolt me-1"></i>{{ $cp['direct_pending'] ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($cp['urgency'] === 'critical')
                                    <span class="badge bg-danger text-white py-1 px-2"><i class="fas fa-exclamation-triangle me-1"></i>{{ __('High Backlog') }}</span>
                                @elseif($cp['urgency'] === 'warning')
                                    <span class="badge bg-warning text-dark py-1 px-2"><i class="fas fa-exclamation-circle me-1"></i>{{ __('Moderate') }}</span>
                                @else
                                    <span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check me-1"></i>{{ __('Normal Flow') }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-dark">৳{{ number_format($cp['balance'], 2) }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-warning py-1 px-2 btn-view-customer-pending" data-id="{{ $cp['id'] }}" title="{{ __('Open live pending messages for this customer') }}" style="font-size: 11px; font-weight: 600;">
                                    <i class="fas fa-layer-group me-1"></i> {{ __('View Pending') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="{{ $cp['id'] }}" title="{{ __('Inspect Customer Queue Details') }}" style="font-size: 11px;">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                @if($cp['pending_count'] > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 btn-cancel-customer-pending" data-id="{{ $cp['id'] }}" data-name="{{ $cp['name'] }}" data-count="{{ $cp['pending_count'] }}" title="{{ __('Cancel all pending SMS for this customer') }}" style="font-size: 11px;">
                                    <i class="fas fa-ban"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-check-circle text-success fs-3 d-block mb-2"></i>
                                {{ __('All customer queues are optimal. No pending backlog found.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer-Wise Processing SMS (RAM & Dispatch) Section -->
        <div class="panel-box mb-4" id="panel-customer-processing">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-cog fa-spin"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __('Customer-Wise Processing SMS (RAM & Dispatch)') }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Active in-flight message dispatching, worker concurrency, operator routing, and real-time RAM pipeline per customer') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-info ms-1">
                        <span id="proc-clients-count">{{ $customerProcessingSummary['aggregates']['customers_transmitting'] ?? 0 }}</span> {{ __('Clients Transmitting') }}
                    </span>
                    <span class="badge badge-soft-primary">
                        <span id="proc-total-badge">{{ number_format($customerProcessingSummary['aggregates']['total_processing'] ?? 0) }}</span> {{ __('Processing In RAM') }}
                    </span>
                    <span class="badge badge-soft-success">
                        <i class="fas fa-microchip me-1"></i><span id="proc-workers-badge">{{ $customerProcessingSummary['aggregates']['active_workers'] ?? 8 }}</span> {{ __('Workers Online') }}
                    </span>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <!-- Operator Filter Tabs -->
                    <div class="btn-group btn-group-sm" role="group" id="btn-group-proc-ops">
                        <button type="button" class="btn btn-outline-secondary active btn-proc-op-filter" data-op="all">{{ __('All Ops') }}</button>
                        <button type="button" class="btn btn-outline-secondary btn-proc-op-filter" data-op="GP">GP</button>
                        <button type="button" class="btn btn-outline-secondary btn-proc-op-filter" data-op="BL">BL</button>
                        <button type="button" class="btn btn-outline-secondary btn-proc-op-filter" data-op="Robi">Robi</button>
                        <button type="button" class="btn btn-outline-secondary btn-proc-op-filter" data-op="Teletalk">TT</button>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 220px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-sm border-start-0" id="input-search-processing" placeholder="{{ __('Search transmitting client...') }}" style="font-size: 12px;">
                    </div>
                </div>
            </div>

            <!-- Aggregated Mini Counters for Processing Queue -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('TOTAL PROCESSING (RAM)') }}</span>
                            <span class="fs-5 fw-bold text-info" id="kpi-proc-total">{{ number_format($customerProcessingSummary['aggregates']['total_processing'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('In Worker Memory') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('TRANSMITTING CLIENTS') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="kpi-proc-clients">{{ $customerProcessingSummary['aggregates']['customers_transmitting'] ?? 0 }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;"><span id="kpi-proc-of-total">of {{ $customerProcessingSummary['aggregates']['total_customers'] ?? 0 }} Clients</span></small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('MAX IN-FLIGHT TIME') }}</span>
                            <span class="fs-5 fw-bold text-primary" id="kpi-proc-max-flight">{{ $customerProcessingSummary['aggregates']['max_flight_human'] ?? 'Idle / Ready' }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Dispatch Latency') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('AWAITING QUEUE') }}</span>
                            <span class="fs-5 fw-bold text-warning" id="kpi-proc-pending">{{ number_format($customerProcessingSummary['aggregates']['total_pending'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Pending in Line') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">GP IN-FLIGHT</span>
                            <span class="fs-5 fw-bold text-primary" id="kpi-proc-gp">{{ number_format($customerProcessingSummary['aggregates']['operator_processing']['GP'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Grameenphone</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">BL IN-FLIGHT</span>
                            <span class="fs-5 fw-bold" style="color: #f97316;" id="kpi-proc-bl">{{ number_format($customerProcessingSummary['aggregates']['operator_processing']['BL'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Banglalink</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">ROBI IN-FLIGHT</span>
                            <span class="fs-5 fw-bold text-danger" id="kpi-proc-robi">{{ number_format($customerProcessingSummary['aggregates']['operator_processing']['Robi'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Robi / Airtel</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">TT IN-FLIGHT</span>
                            <span class="fs-5 fw-bold text-success" id="kpi-proc-tt">{{ number_format($customerProcessingSummary['aggregates']['operator_processing']['Teletalk'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Teletalk</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Processing Table -->
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-customer-processing">
                    <thead>
                        <tr>
                            <th>{{ __('Customer / Client') }}</th>
                            <th class="text-center">{{ __('In-Flight (RAM)') }}</th>
                            <th class="text-center">{{ __('In-Flight Duration') }}</th>
                            <th class="text-center">{{ __('Operator Routing') }}</th>
                            <th class="text-center">{{ __('Source (Camp / API)') }}</th>
                            <th class="text-center">{{ __('Concurrency') }}</th>
                            <th class="text-center">{{ __('Pipeline State') }}</th>
                            <th class="text-end">{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-customer-processing">
                        @forelse($customerProcessingSummary['list'] as $cp)
                        <tr data-cust-id="{{ $cp['id'] }}" data-proc-count="{{ $cp['processing_count'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                    <i class="fas fa-user {{ $cp['processing_count'] > 0 ? 'text-info' : 'text-muted' }} me-1"></i>
                                    {{ $cp['name'] }}
                                </div>
                                <small class="text-muted">
                                    {{ '@' . $cp['username'] }}
                                    @if(!empty($cp['phone']) && $cp['phone'] !== 'N/A')
                                        • {{ $cp['phone'] }}
                                    @endif
                                </small>
                            </td>
                            <td class="text-center">
                                @if($cp['processing_count'] > 0)
                                    <span class="badge bg-info text-white fs-6 px-2 py-1 fw-bold">
                                        <i class="fas fa-cog fa-spin me-1"></i>{{ number_format($cp['processing_count']) }}
                                    </span>
                                @else
                                    <span class="text-muted fw-bold">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($cp['processing_count'] > 0)
                                    <span class="badge {{ $cp['state'] === 'congested' ? 'bg-danger text-white' : ($cp['state'] === 'high_load' ? 'bg-warning text-dark' : 'bg-light text-dark border') }} px-2 py-1">
                                        <i class="fas fa-stopwatch me-1"></i>{{ $cp['flight_human'] }}
                                    </span>
                                @else
                                    <span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check-circle me-1"></i>{{ __('Idle / Ready') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1" style="font-size: 10px;">
                                    <span class="badge {{ ($cp['operator_breakdown']['GP'] ?? 0) > 0 ? 'badge-soft-primary' : 'badge-soft-secondary text-muted' }}" title="Grameenphone">GP: {{ $cp['operator_breakdown']['GP'] ?? 0 }}</span>
                                    <span class="badge {{ ($cp['operator_breakdown']['BL'] ?? 0) > 0 ? 'badge-soft-warning' : 'badge-soft-secondary text-muted' }}" title="Banglalink">BL: {{ $cp['operator_breakdown']['BL'] ?? 0 }}</span>
                                    <span class="badge {{ ($cp['operator_breakdown']['Robi'] ?? 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted' }}" title="Robi/Airtel">Robi: {{ $cp['operator_breakdown']['Robi'] ?? 0 }}</span>
                                    <span class="badge {{ ($cp['operator_breakdown']['Teletalk'] ?? 0) > 0 ? 'badge-soft-success' : 'badge-soft-secondary text-muted' }}" title="Teletalk">TT: {{ $cp['operator_breakdown']['Teletalk'] ?? 0 }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-info" style="font-size: 10px;" title="{{ __('Campaign Messages') }}">
                                    <i class="fas fa-bullhorn me-1"></i>{{ $cp['campaign_processing'] ?? 0 }}
                                </span>
                                <span class="badge badge-soft-secondary ms-1" style="font-size: 10px;" title="{{ __('Direct / API Single Messages') }}">
                                    <i class="fas fa-bolt me-1"></i>{{ $cp['direct_processing'] ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($cp['processing_count'] > 0)
                                    <span class="badge badge-soft-primary" style="font-size: 11px;">
                                        <i class="fas fa-tasks me-1"></i>{{ $cp['concurrency'] }} {{ __('Threads') }}
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size: 11px;">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($cp['state'] === 'congested')
                                    <span class="badge bg-danger text-white py-1 px-2"><i class="fas fa-fire me-1"></i>{{ __('Congested') }}</span>
                                @elseif($cp['state'] === 'high_load')
                                    <span class="badge bg-warning text-dark py-1 px-2"><i class="fas fa-tachometer-alt me-1"></i>{{ __('High Load') }}</span>
                                @else
                                    <span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check me-1"></i>{{ __('Smooth Flow') }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-dark">৳{{ number_format($cp['balance'], 2) }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-info text-white py-1 px-2 btn-view-customer-processing" data-id="{{ $cp['id'] }}" title="{{ __('Open live processing messages for this customer') }}" style="font-size: 11px; font-weight: 600;">
                                    <i class="fas fa-cog fa-spin me-1"></i> {{ __('View Processing') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="{{ $cp['id'] }}" title="{{ __('Inspect Customer Queue Details') }}" style="font-size: 11px;">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-check-circle text-info fs-3 d-block mb-2"></i>
                                {{ __('No customer SMS currently in-flight or processing in RAM.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer-Wise Submitted SMS (Gateway Dispatch & Velocity) Section -->
        <div class="panel-box mb-4" id="panel-customer-submitted">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __('Customer-Wise Submitted SMS (Gateway Dispatch & Velocity)') }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Real-time gateway submissions, delivery/acceptance conversion, operator routing, and today vs lifetime volume per customer') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-primary ms-1">
                        <span id="subm-today-badge">{{ number_format($customerSubmittedSummary['aggregates']['today_submitted'] ?? 0) }}</span> {{ __('Today Submitted') }}
                    </span>
                    <span class="badge badge-soft-success">
                        <span id="subm-total-badge">{{ number_format($customerSubmittedSummary['aggregates']['total_submitted'] ?? 0) }}</span> {{ __('Lifetime Submitted') }}
                    </span>
                    <span class="badge badge-soft-info">
                        <span id="subm-clients-badge">{{ $customerSubmittedSummary['aggregates']['active_clients_today'] ?? 0 }}</span> {{ __('Active Clients Today') }}
                    </span>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <!-- Operator Filter Tabs -->
                    <div class="btn-group btn-group-sm" role="group" id="btn-group-subm-ops">
                        <button type="button" class="btn btn-outline-secondary active btn-subm-op-filter" data-op="all">{{ __('All Ops') }}</button>
                        <button type="button" class="btn btn-outline-secondary btn-subm-op-filter" data-op="GP">GP</button>
                        <button type="button" class="btn btn-outline-secondary btn-subm-op-filter" data-op="BL">BL</button>
                        <button type="button" class="btn btn-outline-secondary btn-subm-op-filter" data-op="Robi">Robi</button>
                        <button type="button" class="btn btn-outline-secondary btn-subm-op-filter" data-op="Teletalk">TT</button>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 220px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-sm border-start-0" id="input-search-submitted" placeholder="{{ __('Search submitted client...') }}" style="font-size: 12px;">
                    </div>
                </div>
            </div>

            <!-- Aggregated Mini Counters for Submitted Queue -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('TODAY SUBMITTED') }}</span>
                            <span class="fs-5 fw-bold text-primary" id="kpi-subm-today">{{ number_format($customerSubmittedSummary['aggregates']['today_submitted'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Dispatched Today') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('LIFETIME SUBMITTED') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="kpi-subm-total">{{ number_format($customerSubmittedSummary['aggregates']['total_submitted'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Historical Volume') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('ACTIVE CLIENTS') }}</span>
                            <span class="fs-5 fw-bold text-success" id="kpi-subm-clients">{{ $customerSubmittedSummary['aggregates']['active_clients_today'] ?? 0 }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;"><span id="kpi-subm-of-total">of {{ $customerSubmittedSummary['aggregates']['total_customers'] ?? 0 }} Clients</span></small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DELIVERY RATIO') }}</span>
                            <span class="fs-5 fw-bold text-success" id="kpi-subm-rate">{{ $customerSubmittedSummary['aggregates']['acceptance_rate'] ?? 100 }}%</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Success / Delivered') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">GP SUBMITTED</span>
                            <span class="fs-5 fw-bold text-primary" id="kpi-subm-gp">{{ number_format($customerSubmittedSummary['aggregates']['operator_submitted']['GP'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Grameenphone</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">BL SUBMITTED</span>
                            <span class="fs-5 fw-bold" style="color: #f97316;" id="kpi-subm-bl">{{ number_format($customerSubmittedSummary['aggregates']['operator_submitted']['BL'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Banglalink</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">ROBI SUBMITTED</span>
                            <span class="fs-5 fw-bold text-danger" id="kpi-subm-robi">{{ number_format($customerSubmittedSummary['aggregates']['operator_submitted']['Robi'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Robi / Airtel</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">TT SUBMITTED</span>
                            <span class="fs-5 fw-bold text-success" id="kpi-subm-tt">{{ number_format($customerSubmittedSummary['aggregates']['operator_submitted']['Teletalk'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Teletalk</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Submitted Table -->
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-customer-submitted">
                    <thead>
                        <tr>
                            <th>{{ __('Customer / Client') }}</th>
                            <th class="text-center">{{ __('Today Submitted') }}</th>
                            <th class="text-center">{{ __('Lifetime Dispatched') }}</th>
                            <th class="text-center">{{ __('Operator Routing') }}</th>
                            <th class="text-center">{{ __('Source (Camp / API)') }}</th>
                            <th class="text-center">{{ __('Delivery Ratio') }}</th>
                            <th class="text-center">{{ __('Dispatch Velocity') }}</th>
                            <th class="text-end">{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-customer-submitted">
                        @forelse($customerSubmittedSummary['list'] as $cs)
                        <tr data-cust-id="{{ $cs['id'] }}" data-today-submitted="{{ $cs['today_submitted'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                    <i class="fas fa-user {{ $cs['today_submitted'] > 0 ? 'text-primary' : 'text-muted' }} me-1"></i>
                                    {{ $cs['name'] }}
                                </div>
                                <small class="text-muted">
                                    {{ '@' . $cs['username'] }}
                                    @if(!empty($cs['phone']) && $cs['phone'] !== 'N/A')
                                        • {{ $cs['phone'] }}
                                    @endif
                                </small>
                            </td>
                            <td class="text-center">
                                @if($cs['today_submitted'] > 0)
                                    <span class="badge bg-primary text-white fs-6 px-2 py-1 fw-bold">
                                        <i class="fas fa-paper-plane me-1"></i>{{ number_format($cs['today_submitted']) }}
                                    </span>
                                @else
                                    <span class="text-muted fw-bold">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="fw-bold text-dark">{{ number_format($cs['lifetime_submitted']) }}</span>
                                    <small class="text-muted">{{ $cs['traffic_share'] }}%</small>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $cs['traffic_share'] }}%;" aria-valuenow="{{ $cs['traffic_share'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1" style="font-size: 10px;">
                                    <span class="badge {{ ($cs['operator_breakdown']['GP'] ?? 0) > 0 ? 'badge-soft-primary' : 'badge-soft-secondary text-muted' }}" title="Grameenphone">GP: {{ $cs['operator_breakdown']['GP'] ?? 0 }}</span>
                                    <span class="badge {{ ($cs['operator_breakdown']['BL'] ?? 0) > 0 ? 'badge-soft-warning' : 'badge-soft-secondary text-muted' }}" title="Banglalink">BL: {{ $cs['operator_breakdown']['BL'] ?? 0 }}</span>
                                    <span class="badge {{ ($cs['operator_breakdown']['Robi'] ?? 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted' }}" title="Robi/Airtel">Robi: {{ $cs['operator_breakdown']['Robi'] ?? 0 }}</span>
                                    <span class="badge {{ ($cs['operator_breakdown']['Teletalk'] ?? 0) > 0 ? 'badge-soft-success' : 'badge-soft-secondary text-muted' }}" title="Teletalk">TT: {{ $cs['operator_breakdown']['Teletalk'] ?? 0 }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-info" style="font-size: 10px;" title="{{ __('Campaign Messages') }}">
                                    <i class="fas fa-bullhorn me-1"></i>{{ $cs['campaign_submitted'] ?? 0 }}
                                </span>
                                <span class="badge badge-soft-secondary ms-1" style="font-size: 10px;" title="{{ __('Direct / API Single Messages') }}">
                                    <i class="fas fa-bolt me-1"></i>{{ $cs['direct_submitted'] ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-success py-1 px-2 fw-bold" style="font-size: 11px;">
                                    <i class="fas fa-check-double me-1"></i>{{ $cs['acceptance_rate'] }}%
                                </span>
                            </td>
                            <td class="text-center">
                                @if($cs['velocity'] === 'high_volume')
                                    <span class="badge bg-success text-white py-1 px-2"><i class="fas fa-rocket me-1"></i>{{ __('High Volume') }}</span>
                                @elseif($cs['velocity'] === 'active')
                                    <span class="badge bg-primary text-white py-1 px-2"><i class="fas fa-paper-plane me-1"></i>{{ __('Active Sending') }}</span>
                                @else
                                    <span class="badge badge-soft-secondary py-1 px-2">{{ __('Idle Today') }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-dark">৳{{ number_format($cs['balance'], 2) }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-primary py-1 px-2 btn-view-customer-submitted" data-id="{{ $cs['id'] }}" title="{{ __('Open live submitted messages for this customer') }}" style="font-size: 11px; font-weight: 600;">
                                    <i class="fas fa-paper-plane me-1"></i> {{ __('View Dispatched') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="{{ $cs['id'] }}" title="{{ __('Inspect Customer Queue Details') }}" style="font-size: 11px;">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-paper-plane text-muted fs-3 d-block mb-2"></i>
                                {{ __('No customer submitted SMS found.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer-Wise Delivered SMS (DLR Success & Completion) Section -->
        <div class="panel-box mb-4" id="panel-customer-delivered">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #dcfce7; color: #16a34a;">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __('Customer-Wise Delivered SMS (DLR Success & Completion)') }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Confirmed handset delivery (DLR), success completion rate %, operator delivery split, and today vs lifetime volume per customer') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-success ms-1">
                        <span id="deliv-today-badge">{{ number_format($customerDeliveredSummary['aggregates']['today_delivered'] ?? 0) }}</span> {{ __('Today Delivered') }}
                    </span>
                    <span class="badge badge-soft-primary">
                        <span id="deliv-total-badge">{{ number_format($customerDeliveredSummary['aggregates']['total_delivered'] ?? 0) }}</span> {{ __('Lifetime Delivered') }}
                    </span>
                    <span class="badge badge-soft-info">
                        <span id="deliv-rate-badge">{{ $customerDeliveredSummary['aggregates']['delivery_rate'] ?? 100 }}%</span> {{ __('Overall Delivery Rate') }}
                    </span>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <!-- Operator Filter Tabs -->
                    <div class="btn-group btn-group-sm" role="group" id="btn-group-deliv-ops">
                        <button type="button" class="btn btn-outline-secondary active btn-deliv-op-filter" data-op="all">{{ __('All Ops') }}</button>
                        <button type="button" class="btn btn-outline-secondary btn-deliv-op-filter" data-op="GP">GP</button>
                        <button type="button" class="btn btn-outline-secondary btn-deliv-op-filter" data-op="BL">BL</button>
                        <button type="button" class="btn btn-outline-secondary btn-deliv-op-filter" data-op="Robi">Robi</button>
                        <button type="button" class="btn btn-outline-secondary btn-deliv-op-filter" data-op="Teletalk">TT</button>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 220px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-sm border-start-0" id="input-search-delivered" placeholder="{{ __('Search delivered client...') }}" style="font-size: 12px;">
                    </div>
                </div>
            </div>

            <!-- Aggregated Mini Counters for Delivered Queue -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('TODAY DELIVERED') }}</span>
                            <span class="fs-5 fw-bold text-success" id="kpi-deliv-today">{{ number_format($customerDeliveredSummary['aggregates']['today_delivered'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Confirmed DLR') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('LIFETIME DELIVERED') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="kpi-deliv-total">{{ number_format($customerDeliveredSummary['aggregates']['total_delivered'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('All-Time Success') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('ACTIVE CLIENTS') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="kpi-deliv-clients">{{ $customerDeliveredSummary['aggregates']['active_clients_today'] ?? 0 }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;"><span id="kpi-deliv-of-total">of {{ $customerDeliveredSummary['aggregates']['total_customers'] ?? 0 }} Clients</span></small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DELIVERY RATIO') }}</span>
                            <span class="fs-5 fw-bold text-success" id="kpi-deliv-rate">{{ $customerDeliveredSummary['aggregates']['delivery_rate'] ?? 100 }}%</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Success Percentage') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">GP DELIVERED</span>
                            <span class="fs-5 fw-bold text-primary" id="kpi-deliv-gp">{{ number_format($customerDeliveredSummary['aggregates']['operator_delivered']['GP'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Grameenphone</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">BL DELIVERED</span>
                            <span class="fs-5 fw-bold" style="color: #f97316;" id="kpi-deliv-bl">{{ number_format($customerDeliveredSummary['aggregates']['operator_delivered']['BL'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Banglalink</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">ROBI DELIVERED</span>
                            <span class="fs-5 fw-bold text-danger" id="kpi-deliv-robi">{{ number_format($customerDeliveredSummary['aggregates']['operator_delivered']['Robi'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Robi / Airtel</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">TT DELIVERED</span>
                            <span class="fs-5 fw-bold text-success" id="kpi-deliv-tt">{{ number_format($customerDeliveredSummary['aggregates']['operator_delivered']['Teletalk'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Teletalk</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Delivered Table -->
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-customer-delivered">
                    <thead>
                        <tr>
                            <th>{{ __('Customer / Client') }}</th>
                            <th class="text-center">{{ __('Today Delivered') }}</th>
                            <th class="text-center">{{ __('Lifetime Delivered') }}</th>
                            <th class="text-center">{{ __('Operator Routing') }}</th>
                            <th class="text-center">{{ __('Source (Camp / API)') }}</th>
                            <th class="text-center">{{ __('Delivery Rate %') }}</th>
                            <th class="text-center">{{ __('Delivery Health') }}</th>
                            <th class="text-end">{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-customer-delivered">
                        @forelse($customerDeliveredSummary['list'] as $cd)
                        <tr data-cust-id="{{ $cd['id'] }}" data-today-delivered="{{ $cd['today_delivered'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                    <i class="fas fa-user {{ $cd['today_delivered'] > 0 ? 'text-success' : 'text-muted' }} me-1"></i>
                                    {{ $cd['name'] }}
                                </div>
                                <small class="text-muted">
                                    {{ '@' . $cd['username'] }}
                                    @if(!empty($cd['phone']) && $cd['phone'] !== 'N/A')
                                        • {{ $cd['phone'] }}
                                    @endif
                                </small>
                            </td>
                            <td class="text-center">
                                @if($cd['today_delivered'] > 0)
                                    <span class="badge bg-success text-white fs-6 px-2 py-1 fw-bold">
                                        <i class="fas fa-check-double me-1"></i>{{ number_format($cd['today_delivered']) }}
                                    </span>
                                @else
                                    <span class="text-muted fw-bold">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="fw-bold text-dark">{{ number_format($cd['lifetime_delivered']) }}</span>
                                    <small class="text-muted">{{ $cd['traffic_share'] }}%</small>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $cd['traffic_share'] }}%;" aria-valuenow="{{ $cd['traffic_share'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1" style="font-size: 10px;">
                                    <span class="badge {{ ($cd['operator_breakdown']['GP'] ?? 0) > 0 ? 'badge-soft-primary' : 'badge-soft-secondary text-muted' }}" title="Grameenphone">GP: {{ $cd['operator_breakdown']['GP'] ?? 0 }}</span>
                                    <span class="badge {{ ($cd['operator_breakdown']['BL'] ?? 0) > 0 ? 'badge-soft-warning' : 'badge-soft-secondary text-muted' }}" title="Banglalink">BL: {{ $cd['operator_breakdown']['BL'] ?? 0 }}</span>
                                    <span class="badge {{ ($cd['operator_breakdown']['Robi'] ?? 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted' }}" title="Robi/Airtel">Robi: {{ $cd['operator_breakdown']['Robi'] ?? 0 }}</span>
                                    <span class="badge {{ ($cd['operator_breakdown']['Teletalk'] ?? 0) > 0 ? 'badge-soft-success' : 'badge-soft-secondary text-muted' }}" title="Teletalk">TT: {{ $cd['operator_breakdown']['Teletalk'] ?? 0 }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-info" style="font-size: 10px;" title="{{ __('Campaign Messages') }}">
                                    <i class="fas fa-bullhorn me-1"></i>{{ $cd['campaign_delivered'] ?? 0 }}
                                </span>
                                <span class="badge badge-soft-secondary ms-1" style="font-size: 10px;" title="{{ __('Direct / API Single Messages') }}">
                                    <i class="fas fa-bolt me-1"></i>{{ $cd['direct_delivered'] ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-success py-1 px-2 fw-bold" style="font-size: 11px;">
                                    <i class="fas fa-check-double me-1"></i>{{ $cd['delivery_rate'] }}%
                                </span>
                            </td>
                            <td class="text-center">
                                @if($cd['health'] === 'low_ratio')
                                    <span class="badge bg-warning text-dark py-1 px-2"><i class="fas fa-exclamation-triangle me-1"></i>{{ __('Low DLR Ratio') }}</span>
                                @elseif($cd['health'] === 'satisfactory')
                                    <span class="badge bg-info text-white py-1 px-2"><i class="fas fa-check me-1"></i>{{ __('Satisfactory') }}</span>
                                @else
                                    <span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check-double me-1"></i>{{ __('Optimal Flow') }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-dark">৳{{ number_format($cd['balance'], 2) }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-success py-1 px-2 btn-view-customer-delivered" data-id="{{ $cd['id'] }}" title="{{ __('Open live delivered messages for this customer') }}" style="font-size: 11px; font-weight: 600;">
                                    <i class="fas fa-check-double me-1"></i> {{ __('View Delivered') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="{{ $cd['id'] }}" title="{{ __('Inspect Customer Queue Details') }}" style="font-size: 11px;">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-check-circle text-muted fs-3 d-block mb-2"></i>
                                {{ __('No customer delivered SMS found.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer-Wise Failed SMS & Error Root-Cause Tracking Section -->
        <div class="panel-box mb-4" id="panel-customer-failed">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #fee2e2; color: #dc2626;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __('Customer-Wise Failed SMS & Error Root-Cause Tracking') }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Real-time undelivered/failed message tracking, operator error patterns, failure rate %, and instant retry controls') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-danger ms-1">
                        <span id="fail-today-badge">{{ number_format($customerFailedSummary['aggregates']['today_failed'] ?? 0) }}</span> {{ __('Today Failed') }}
                    </span>
                    <span class="badge badge-soft-dark">
                        <span id="fail-total-badge">{{ number_format($customerFailedSummary['aggregates']['total_failed'] ?? 0) }}</span> {{ __('Lifetime Failed') }}
                    </span>
                    <span class="badge badge-soft-warning">
                        <span id="fail-clients-badge">{{ $customerFailedSummary['aggregates']['clients_with_failures'] ?? 0 }}</span> {{ __('Clients with Failures') }}
                    </span>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <!-- Operator Filter Tabs -->
                    <div class="btn-group btn-group-sm" role="group" id="btn-group-fail-ops">
                        <button type="button" class="btn btn-outline-secondary active btn-fail-op-filter" data-op="all">{{ __('All Ops') }}</button>
                        <button type="button" class="btn btn-outline-secondary btn-fail-op-filter" data-op="GP">GP</button>
                        <button type="button" class="btn btn-outline-secondary btn-fail-op-filter" data-op="BL">BL</button>
                        <button type="button" class="btn btn-outline-secondary btn-fail-op-filter" data-op="Robi">Robi</button>
                        <button type="button" class="btn btn-outline-secondary btn-fail-op-filter" data-op="Teletalk">TT</button>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 220px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-sm border-start-0" id="input-search-failed" placeholder="{{ __('Search failed client...') }}" style="font-size: 12px;">
                    </div>
                </div>
            </div>

            <!-- Aggregated Mini Counters for Failed Queue -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('TODAY FAILED') }}</span>
                            <span class="fs-5 fw-bold text-danger" id="kpi-fail-today">{{ number_format($customerFailedSummary['aggregates']['today_failed'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Undelivered Today') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('LIFETIME FAILED') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="kpi-fail-total">{{ number_format($customerFailedSummary['aggregates']['total_failed'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('All-Time Failures') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('AFFECTED CLIENTS') }}</span>
                            <span class="fs-5 fw-bold text-warning" id="kpi-fail-clients">{{ $customerFailedSummary['aggregates']['clients_with_failures'] ?? 0 }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;"><span id="kpi-fail-of-total">of {{ $customerFailedSummary['aggregates']['total_customers'] ?? 0 }} Clients</span></small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('FAILURE RATIO') }}</span>
                            <span class="fs-5 fw-bold text-danger" id="kpi-fail-rate">{{ $customerFailedSummary['aggregates']['failure_rate'] ?? 0 }}%</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Error Ratio') }}</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">GP FAILED</span>
                            <span class="fs-5 fw-bold text-danger" id="kpi-fail-gp">{{ number_format($customerFailedSummary['aggregates']['operator_failed']['GP'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Grameenphone</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">BL FAILED</span>
                            <span class="fs-5 fw-bold" style="color: #f97316;" id="kpi-fail-bl">{{ number_format($customerFailedSummary['aggregates']['operator_failed']['BL'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Banglalink</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">ROBI FAILED</span>
                            <span class="fs-5 fw-bold text-danger" id="kpi-fail-robi">{{ number_format($customerFailedSummary['aggregates']['operator_failed']['Robi'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Robi / Airtel</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-3 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">TT FAILED</span>
                            <span class="fs-5 fw-bold text-success" id="kpi-fail-tt">{{ number_format($customerFailedSummary['aggregates']['operator_failed']['Teletalk'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">Teletalk</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Failed Table -->
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-customer-failed">
                    <thead>
                        <tr>
                            <th>{{ __('Customer / Client') }}</th>
                            <th class="text-center">{{ __('Today Failed') }}</th>
                            <th class="text-center">{{ __('Lifetime Failed') }}</th>
                            <th class="text-center">{{ __('Operator Routing') }}</th>
                            <th class="text-center">{{ __('Source (Camp / API)') }}</th>
                            <th class="text-center">{{ __('Primary Response / Error') }}</th>
                            <th class="text-center">{{ __('Failure Severity') }}</th>
                            <th class="text-end">{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-customer-failed">
                        @forelse($customerFailedSummary['list'] as $cf)
                        <tr data-cust-id="{{ $cf['id'] }}" data-today-failed="{{ $cf['today_failed'] }}" data-total-failed="{{ $cf['lifetime_failed'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                    <i class="fas fa-user {{ $cf['today_failed'] > 0 ? 'text-danger' : 'text-muted' }} me-1"></i>
                                    {{ $cf['name'] }}
                                </div>
                                <small class="text-muted">
                                    {{ '@' . $cf['username'] }}
                                    @if(!empty($cf['phone']) && $cf['phone'] !== 'N/A')
                                        • {{ $cf['phone'] }}
                                    @endif
                                </small>
                            </td>
                            <td class="text-center">
                                @if($cf['today_failed'] > 0)
                                    <span class="badge bg-danger text-white fs-6 px-2 py-1 fw-bold">
                                        <i class="fas fa-times-circle me-1"></i>{{ number_format($cf['today_failed']) }}
                                    </span>
                                @else
                                    <span class="text-muted fw-bold">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="fw-bold text-dark">{{ number_format($cf['lifetime_failed']) }}</span>
                                    <small class="text-muted">{{ $cf['traffic_share'] }}%</small>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $cf['traffic_share'] }}%;" aria-valuenow="{{ $cf['traffic_share'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1" style="font-size: 10px;">
                                    <span class="badge {{ ($cf['operator_breakdown']['GP'] ?? 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted' }}" title="Grameenphone">GP: {{ $cf['operator_breakdown']['GP'] ?? 0 }}</span>
                                    <span class="badge {{ ($cf['operator_breakdown']['BL'] ?? 0) > 0 ? 'badge-soft-warning' : 'badge-soft-secondary text-muted' }}" title="Banglalink">BL: {{ $cf['operator_breakdown']['BL'] ?? 0 }}</span>
                                    <span class="badge {{ ($cf['operator_breakdown']['Robi'] ?? 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted' }}" title="Robi/Airtel">Robi: {{ $cf['operator_breakdown']['Robi'] ?? 0 }}</span>
                                    <span class="badge {{ ($cf['operator_breakdown']['Teletalk'] ?? 0) > 0 ? 'badge-soft-success' : 'badge-soft-secondary text-muted' }}" title="Teletalk">TT: {{ $cf['operator_breakdown']['Teletalk'] ?? 0 }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-info" style="font-size: 10px;" title="{{ __('Campaign Messages') }}">
                                    <i class="fas fa-bullhorn me-1"></i>{{ $cf['campaign_failed'] ?? 0 }}
                                </span>
                                <span class="badge badge-soft-secondary ms-1" style="font-size: 10px;" title="{{ __('Direct / API Single Messages') }}">
                                    <i class="fas fa-bolt me-1"></i>{{ $cf['direct_failed'] ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <small class="text-muted d-block text-truncate" style="max-width: 140px; font-size: 11px;" title="{{ $cf['primary_error'] }}">
                                    {{ $cf['primary_error'] }}
                                </small>
                            </td>
                            <td class="text-center">
                                @if($cf['severity'] === 'critical')
                                    <span class="badge bg-danger text-white py-1 px-2"><i class="fas fa-exclamation-triangle me-1"></i>{{ __('High Error Ratio') }}</span>
                                @elseif($cf['severity'] === 'moderate')
                                    <span class="badge bg-warning text-dark py-1 px-2"><i class="fas fa-exclamation-circle me-1"></i>{{ __('Moderate Failures') }}</span>
                                @else
                                    <span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check me-1"></i>{{ __('Normal Flow') }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-dark">৳{{ number_format($cf['balance'], 2) }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-danger py-1 px-2 btn-view-customer-failed" data-id="{{ $cf['id'] }}" title="{{ __('Open live failed messages for this customer') }}" style="font-size: 11px; font-weight: 600;">
                                    <i class="fas fa-times-circle me-1"></i> {{ __('View Failed') }}
                                </button>
                                @if($cf['lifetime_failed'] > 0)
                                <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2 btn-retry-customer-failed" data-id="{{ $cf['id'] }}" data-name="{{ $cf['name'] }}" data-count="{{ $cf['lifetime_failed'] }}" title="{{ __('Retry / Re-queue all failed messages for this customer') }}" style="font-size: 11px;">
                                    <i class="fas fa-redo me-1"></i> {{ __('Retry All') }}
                                </button>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="{{ $cf['id'] }}" title="{{ __('Inspect Customer Queue Details') }}" style="font-size: 11px;">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-check-circle text-success fs-3 d-block mb-2"></i>
                                {{ __('Zero failures reported across customer queues. All transmissions healthy!') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Campaign-Wise SMS Statistics & Queue Progress Matrix -->
        <div class="panel-box mb-4" id="panel-campaigns">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(220, 53, 69, 0.12); color: #dc3545;">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __('Campaign-Wise SMS Statistics & Queue Progress') }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Bulk marketing & notification campaigns, recipient completion, and queue backlog in your network') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-danger ms-1"><span id="campaigns-count">{{ count($campaigns ?? []) }}</span> {{ __('Campaigns Monitored') }}</span>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Campaign Type Filter">
                        <button type="button" class="btn btn-outline-danger camp-type-tab-btn active" data-type="all">{{ __('All Campaigns') }}</button>
                        <button type="button" class="btn btn-outline-danger camp-type-tab-btn" data-type="own">{{ __('Own Campaigns') }}</button>
                        <button type="button" class="btn btn-outline-danger camp-type-tab-btn" data-type="customer">{{ __('Customers') }}</button>
                    </div>
                    <div class="input-group input-group-sm" style="max-width: 220px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-sm border-start-0" id="input-search-campaigns" placeholder="{{ __('Search campaign...') }}" style="font-size: 12px;">
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
                            <small class="text-muted d-block" style="font-size: 10px;">
                                <span class="text-success fw-bold">{{ __('Own') }}: <span id="camp-agg-own">{{ $campaignAggregates['own_campaigns'] ?? 0 }}</span></span> • 
                                <span class="text-primary fw-bold">{{ __('Customers') }}: <span id="camp-agg-customer">{{ $campaignAggregates['customer_campaigns'] ?? 0 }}</span></span>
                            </small>
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
                            <th>{{ __('Initiator / Account') }}</th>
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
                        <tr data-campaign-id="{{ $camp['id'] }}" data-creator-type="{{ $camp['creator_type'] ?? 'customer' }}">
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
                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                    {{ $camp['creator_name'] }}
                                    @if(($camp['creator_type'] ?? '') === 'own')
                                        <span class="badge bg-success" style="font-size: 9px;"><i class="fas fa-user-check me-1"></i>{{ __('YOU (Direct)') }}</span>
                                    @else
                                        <span class="badge badge-soft-info" style="font-size: 10px;">{{ __('Customer') }}</span>
                                    @endif
                                </div>
                                <small class="text-muted d-block" style="font-size: 11px;">@<span>{{ $camp['creator_username'] }}</span></small>
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
                                @if(!empty($camp['creator_id']))
                                <button type="button" class="btn btn-sm btn-outline-success py-1 px-2 btn-open-customer-queue" data-id="{{ $camp['creator_id'] }}" title="{{ __('Inspect Queue Messages') }}" style="font-size: 11px;">
                                    <i class="fas fa-layer-group me-1"></i> {{ __('View Queue') }}
                                </button>
                                @endif
                                @if($camp['pending_queue'] > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 btn-cancel-campaign-pending" data-id="{{ $camp['id'] }}" data-name="{{ $camp['name'] }}" data-count="{{ $camp['pending_queue'] }}" title="{{ __('Cancel pending SMS in this campaign') }}" style="font-size: 11px;">
                                    <i class="fas fa-ban me-1"></i> {{ __('Cancel') }}
                                </button>
                                @endif
                                @if(!empty($camp['creator_id']))
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="{{ $camp['creator_id'] }}" title="{{ __('Inspect Account Details') }}" style="font-size: 11px;">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">
                                <i class="fas fa-bullhorn text-muted fs-3 d-block mb-2"></i>
                                {{ __('No campaigns match this filter. System ready for bulk broadcasts.') }}
                            </td>
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
    let currentCustomerPendingData = @json($customerPendingSummary ?? []);
    let currentSearchTerm = '';
    let currentPendingOpFilter = 'all';
    let currentPendingSearchTerm = '';
    let matrixPendingOnly = false;
    let currentCustomerProcessingData = @json($customerProcessingSummary ?? []);
    let currentProcOpFilter = 'all';
    let currentProcSearchTerm = '';
    let matrixProcOnly = false;
    let currentCustomerSubmittedData = @json($customerSubmittedSummary ?? []);
    let currentSubmOpFilter = 'all';
    let currentSubmSearchTerm = '';
    let matrixSubmOnly = false;
    let currentCustomerDeliveredData = @json($customerDeliveredSummary ?? []);
    let currentDelivOpFilter = 'all';
    let currentDelivSearchTerm = '';
    let matrixDelivOnly = false;
    let currentCustomerFailedData = @json($customerFailedSummary ?? []);
    let currentFailOpFilter = 'all';
    let currentFailSearchTerm = '';
    let matrixFailOnly = false;
    let currentCampaignsData = @json($campaigns ?? []);
    let currentCampaignAggregates = @json($campaignAggregates ?? []);
    let currentCampaignTypeFilter = 'all';
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

                // Render Customer-Wise Pending SMS Queue
                renderCustomerPendingTable(res.customer_pending);

                // Render Customer-Wise Processing SMS Queue
                renderCustomerProcessingTable(res.customer_processing);

                // Render Customer-Wise Submitted SMS Queue
                renderCustomerSubmittedTable(res.customer_submitted);

                // Render Customer-Wise Delivered SMS Queue
                renderCustomerDeliveredTable(res.customer_delivered);

                // Render Customer-Wise Failed SMS Queue
                renderCustomerFailedTable(res.customer_failed);

                // Render Campaign-Wise SMS Statistics & Queue Progress
                renderCampaignsTable(res.campaigns, res.campaign_aggregates);

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
        if (matrixPendingOnly) {
            filtered = filtered.filter(function(c) {
                return (c.pending_queue || 0) > 0;
            });
        }
        if (matrixProcOnly) {
            filtered = filtered.filter(function(c) {
                return (c.processing_queue || 0) > 0;
            });
        }
        if (matrixSubmOnly) {
            filtered = filtered.filter(function(c) {
                return (c.today_total || 0) > 0;
            });
        }
        if (matrixDelivOnly) {
            filtered = filtered.filter(function(c) {
                return (c.today_delivered || 0) > 0;
            });
        }
        if (matrixFailOnly) {
            filtered = filtered.filter(function(c) {
                return (c.today_failed || 0) > 0;
            });
        }
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
            $('#tbody-customers-matrix').html('<tr><td colspan="10" class="text-center py-4 text-muted">No customers match this filter/search.</td></tr>');
            return;
        }

        let html = '';
        filtered.forEach(function(cust) {
            const pendingClass = cust.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = cust.processing_queue > 0 ? 'text-info' : 'text-muted';
            const failClass = cust.today_failed > 0 ? 'text-danger' : 'text-muted';
            const phoneStr = (cust.phone && cust.phone !== 'N/A') ? ` • ${cust.phone}` : '';

            const pendingDisplay = cust.pending_queue > 0 ? `
                <a href="javascript:void(0);" class="btn-view-customer-pending text-warning text-decoration-underline fw-bold" data-id="${cust.id}" title="Click to view pending queue">
                    <i class="fas fa-hourglass-half me-1"></i>${Number(cust.pending_queue).toLocaleString()}
                </a>
            ` : `<span class="text-muted fw-bold">0</span>`;

            const procDisplay = cust.processing_queue > 0 ? `
                <a href="javascript:void(0);" class="btn-view-customer-processing text-info text-decoration-underline fw-bold" data-id="${cust.id}" title="Click to view processing queue">
                    <i class="fas fa-cog fa-spin me-1"></i>${Number(cust.processing_queue).toLocaleString()}
                </a>
            ` : `<span class="text-muted fw-bold">0</span>`;

            const submDisplay = cust.today_total > 0 ? `
                <a href="javascript:void(0);" class="btn-view-customer-submitted text-primary text-decoration-underline fw-bold" data-id="${cust.id}" title="Click to view submitted messages">
                    <i class="fas fa-paper-plane me-1"></i>${Number(cust.today_total).toLocaleString()}
                </a>
            ` : `<span class="text-muted fw-bold">0</span>`;

            const delivDisplay = cust.today_delivered > 0 ? `
                <a href="javascript:void(0);" class="btn-view-customer-delivered text-success text-decoration-underline fw-bold" data-id="${cust.id}" title="Click to view delivered messages">
                    <i class="fas fa-check-double me-1"></i>${Number(cust.today_delivered).toLocaleString()}
                </a>
            ` : `<span class="text-muted fw-bold">0</span>`;

            const failDisplay = cust.today_failed > 0 ? `
                <a href="javascript:void(0);" class="btn-view-customer-failed text-danger text-decoration-underline fw-bold" data-id="${cust.id}" title="Click to view failed messages">
                    <i class="fas fa-times-circle me-1"></i>${Number(cust.today_failed).toLocaleString()}
                </a>
            ` : `<span class="text-muted fw-bold">0</span>`;

            html += `
                <tr data-cust-id="${cust.id}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-user text-success me-1"></i>
                            ${cust.name}
                        </div>
                        <small class="text-muted">@${cust.username}${phoneStr}</small>
                    </td>
                    <td class="text-center">${pendingDisplay}</td>
                    <td class="text-center">${procDisplay}</td>
                    <td class="text-center">${submDisplay}</td>
                    <td class="text-center">${delivDisplay}</td>
                    <td class="text-center">${failDisplay}</td>
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

    // 3b. Render Customer-Wise Pending SMS Queue Table
    function renderCustomerPendingTable(data) {
        if (!data) return;
        currentCustomerPendingData = data;
        const list = data.list || [];
        const agg = data.aggregates || {};

        let filtered = list.slice();

        // Filter by Operator tab if selected
        if (currentPendingOpFilter !== 'all') {
            filtered = filtered.filter(function(cp) {
                const ops = cp.operator_breakdown || {};
                return (ops[currentPendingOpFilter] || 0) > 0;
            });
        }

        // Filter by Search Input
        if (currentPendingSearchTerm.trim() !== '') {
            const term = currentPendingSearchTerm.toLowerCase();
            filtered = filtered.filter(function(cp) {
                const name = (cp.name || '').toLowerCase();
                const username = (cp.username || '').toLowerCase();
                const phone = (cp.phone || '').toLowerCase();
                return name.includes(term) || username.includes(term) || phone.includes(term);
            });
        }

        // Update Header Badges & Aggregates
        $('#pending-clients-count').text(agg.customers_with_pending || 0);
        $('#pending-total-badge').text(Number(agg.total_pending || 0).toLocaleString());
        $('#kpi-pending-total').text(Number(agg.total_pending || 0).toLocaleString());
        $('#kpi-pending-clients').text(agg.customers_with_pending || 0);
        $('#kpi-pending-of-total').text('of ' + (agg.total_customers || 0) + ' Clients');
        $('#kpi-pending-max-wait').text(agg.max_wait_human || 'Queue Clear');
        $('#kpi-pending-proc').text(Number(agg.total_processing || 0).toLocaleString());

        const ops = agg.operator_pending || {};
        $('#kpi-pending-gp').text(Number(ops.GP || 0).toLocaleString());
        $('#kpi-pending-bl').text(Number(ops.BL || 0).toLocaleString());
        $('#kpi-pending-robi').text(Number(ops.Robi || 0).toLocaleString());
        $('#kpi-pending-tt').text(Number(ops.Teletalk || 0).toLocaleString());

        if (!filtered.length) {
            $('#tbody-customer-pending').html(`
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="fas fa-check-circle text-success fs-3 d-block mb-2"></i>
                        No pending SMS backlog found matching your criteria.
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        filtered.forEach(function(cp) {
            const phoneStr = (cp.phone && cp.phone !== 'N/A') ? ` • ${cp.phone}` : '';
            const cOps = cp.operator_breakdown || {};

            let latencyBadge = '';
            if (cp.pending_count > 0) {
                let badgeClass = 'bg-light text-dark border';
                if (cp.urgency === 'critical') badgeClass = 'bg-danger text-white';
                else if (cp.urgency === 'warning') badgeClass = 'bg-warning text-dark';
                latencyBadge = `<span class="badge ${badgeClass} px-2 py-1"><i class="fas fa-clock me-1"></i>${cp.wait_time_human}</span>`;
            } else {
                latencyBadge = `<span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check-circle me-1"></i>Queue Clear</span>`;
            }

            let urgencyBadge = '';
            if (cp.urgency === 'critical') {
                urgencyBadge = `<span class="badge bg-danger text-white py-1 px-2"><i class="fas fa-exclamation-triangle me-1"></i>High Backlog</span>`;
            } else if (cp.urgency === 'warning') {
                urgencyBadge = `<span class="badge bg-warning text-dark py-1 px-2"><i class="fas fa-exclamation-circle me-1"></i>Moderate</span>`;
            } else {
                urgencyBadge = `<span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check me-1"></i>Normal Flow</span>`;
            }

            const pendingHtml = cp.pending_count > 0
                ? `<span class="badge bg-warning text-dark fs-6 px-2 py-1 fw-bold"><i class="fas fa-hourglass-half me-1"></i>${Number(cp.pending_count).toLocaleString()}</span>`
                : `<span class="text-muted fw-bold">0</span>`;

            const cancelBtn = cp.pending_count > 0 ? `
                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 btn-cancel-customer-pending" data-id="${cp.id}" data-name="${cp.name}" data-count="${cp.pending_count}" title="Cancel all pending SMS for this customer" style="font-size: 11px;">
                    <i class="fas fa-ban"></i>
                </button>
            ` : '';

            html += `
                <tr data-cust-id="${cp.id}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-user ${cp.pending_count > 0 ? 'text-warning' : 'text-muted'} me-1"></i>
                            ${cp.name}
                        </div>
                        <small class="text-muted">@${cp.username}${phoneStr}</small>
                    </td>
                    <td class="text-center">${pendingHtml}</td>
                    <td class="text-center">${latencyBadge}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1" style="font-size: 10px;">
                            <span class="badge ${(cOps.GP || 0) > 0 ? 'badge-soft-primary' : 'badge-soft-secondary text-muted'}" title="Grameenphone">GP: ${cOps.GP || 0}</span>
                            <span class="badge ${(cOps.BL || 0) > 0 ? 'badge-soft-warning' : 'badge-soft-secondary text-muted'}" title="Banglalink">BL: ${cOps.BL || 0}</span>
                            <span class="badge ${(cOps.Robi || 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted'}" title="Robi/Airtel">Robi: ${cOps.Robi || 0}</span>
                            <span class="badge ${(cOps.Teletalk || 0) > 0 ? 'badge-soft-success' : 'badge-soft-secondary text-muted'}" title="Teletalk">TT: ${cOps.Teletalk || 0}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-soft-info" style="font-size: 10px;" title="Campaign Messages">
                            <i class="fas fa-bullhorn me-1"></i>${cp.campaign_pending || 0}
                        </span>
                        <span class="badge badge-soft-secondary ms-1" style="font-size: 10px;" title="Direct / API Single Messages">
                            <i class="fas fa-bolt me-1"></i>${cp.direct_pending || 0}
                        </span>
                    </td>
                    <td class="text-center">${urgencyBadge}</td>
                    <td class="text-end fw-bold text-dark">৳${Number(cp.balance).toFixed(2)}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-warning py-1 px-2 btn-view-customer-pending" data-id="${cp.id}" title="Open live pending messages for this customer" style="font-size: 11px; font-weight: 600;">
                            <i class="fas fa-layer-group me-1"></i> View Pending
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="${cp.id}" title="Inspect Customer Queue Details" style="font-size: 11px;">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        ${cancelBtn}
                    </td>
                </tr>
            `;
        });
        $('#tbody-customer-pending').html(html);
    }

    // 3c. Render Customer-Wise Processing SMS Queue Table
    function renderCustomerProcessingTable(data) {
        if (!data) return;
        currentCustomerProcessingData = data;
        const list = data.list || [];
        const agg = data.aggregates || {};

        let filtered = list.slice();

        // Filter by Operator tab if selected
        if (currentProcOpFilter !== 'all') {
            filtered = filtered.filter(function(cp) {
                const ops = cp.operator_breakdown || {};
                return (ops[currentProcOpFilter] || 0) > 0;
            });
        }

        // Filter by Search Input
        if (currentProcSearchTerm.trim() !== '') {
            const term = currentProcSearchTerm.toLowerCase();
            filtered = filtered.filter(function(cp) {
                const name = (cp.name || '').toLowerCase();
                const username = (cp.username || '').toLowerCase();
                const phone = (cp.phone || '').toLowerCase();
                return name.includes(term) || username.includes(term) || phone.includes(term);
            });
        }

        // Update Header Badges & Aggregates
        $('#proc-clients-count').text(agg.customers_transmitting || 0);
        $('#proc-total-badge').text(Number(agg.total_processing || 0).toLocaleString());
        $('#proc-workers-badge').text(agg.active_workers || 8);
        $('#kpi-proc-total').text(Number(agg.total_processing || 0).toLocaleString());
        $('#kpi-proc-clients').text(agg.customers_transmitting || 0);
        $('#kpi-proc-of-total').text('of ' + (agg.total_customers || 0) + ' Clients');
        $('#kpi-proc-max-flight').text(agg.max_flight_human || 'Idle / Ready');
        $('#kpi-proc-pending').text(Number(agg.total_pending || 0).toLocaleString());

        const ops = agg.operator_processing || {};
        $('#kpi-proc-gp').text(Number(ops.GP || 0).toLocaleString());
        $('#kpi-proc-bl').text(Number(ops.BL || 0).toLocaleString());
        $('#kpi-proc-robi').text(Number(ops.Robi || 0).toLocaleString());
        $('#kpi-proc-tt').text(Number(ops.Teletalk || 0).toLocaleString());

        if (!filtered.length) {
            $('#tbody-customer-processing').html(`
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="fas fa-check-circle text-info fs-3 d-block mb-2"></i>
                        No customer SMS currently in-flight or processing in RAM matching your criteria.
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        filtered.forEach(function(cp) {
            const phoneStr = (cp.phone && cp.phone !== 'N/A') ? ` • ${cp.phone}` : '';
            const cOps = cp.operator_breakdown || {};

            let latencyBadge = '';
            if (cp.processing_count > 0) {
                let badgeClass = 'bg-light text-dark border';
                if (cp.state === 'congested') badgeClass = 'bg-danger text-white';
                else if (cp.state === 'high_load') badgeClass = 'bg-warning text-dark';
                latencyBadge = `<span class="badge ${badgeClass} px-2 py-1"><i class="fas fa-stopwatch me-1"></i>${cp.flight_human}</span>`;
            } else {
                latencyBadge = `<span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check-circle me-1"></i>Idle / Ready</span>`;
            }

            let stateBadge = '';
            if (cp.state === 'congested') {
                stateBadge = `<span class="badge bg-danger text-white py-1 px-2"><i class="fas fa-fire me-1"></i>Congested</span>`;
            } else if (cp.state === 'high_load') {
                stateBadge = `<span class="badge bg-warning text-dark py-1 px-2"><i class="fas fa-tachometer-alt me-1"></i>High Load</span>`;
            } else {
                stateBadge = `<span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check me-1"></i>Smooth Flow</span>`;
            }

            const procHtml = cp.processing_count > 0
                ? `<span class="badge bg-info text-white fs-6 px-2 py-1 fw-bold"><i class="fas fa-cog fa-spin me-1"></i>${Number(cp.processing_count).toLocaleString()}</span>`
                : `<span class="text-muted fw-bold">0</span>`;

            const concurrencyHtml = cp.processing_count > 0
                ? `<span class="badge badge-soft-primary" style="font-size: 11px;"><i class="fas fa-tasks me-1"></i>${cp.concurrency} Threads</span>`
                : `<span class="text-muted" style="font-size: 11px;">—</span>`;

            html += `
                <tr data-cust-id="${cp.id}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-user ${cp.processing_count > 0 ? 'text-info' : 'text-muted'} me-1"></i>
                            ${cp.name}
                        </div>
                        <small class="text-muted">@${cp.username}${phoneStr}</small>
                    </td>
                    <td class="text-center">${procHtml}</td>
                    <td class="text-center">${latencyBadge}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1" style="font-size: 10px;">
                            <span class="badge ${(cOps.GP || 0) > 0 ? 'badge-soft-primary' : 'badge-soft-secondary text-muted'}" title="Grameenphone">GP: ${cOps.GP || 0}</span>
                            <span class="badge ${(cOps.BL || 0) > 0 ? 'badge-soft-warning' : 'badge-soft-secondary text-muted'}" title="Banglalink">BL: ${cOps.BL || 0}</span>
                            <span class="badge ${(cOps.Robi || 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted'}" title="Robi/Airtel">Robi: ${cOps.Robi || 0}</span>
                            <span class="badge ${(cOps.Teletalk || 0) > 0 ? 'badge-soft-success' : 'badge-soft-secondary text-muted'}" title="Teletalk">TT: ${cOps.Teletalk || 0}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-soft-info" style="font-size: 10px;" title="Campaign Messages">
                            <i class="fas fa-bullhorn me-1"></i>${cp.campaign_processing || 0}
                        </span>
                        <span class="badge badge-soft-secondary ms-1" style="font-size: 10px;" title="Direct / API Single Messages">
                            <i class="fas fa-bolt me-1"></i>${cp.direct_processing || 0}
                        </span>
                    </td>
                    <td class="text-center">${concurrencyHtml}</td>
                    <td class="text-center">${stateBadge}</td>
                    <td class="text-end fw-bold text-dark">৳${Number(cp.balance).toFixed(2)}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-info text-white py-1 px-2 btn-view-customer-processing" data-id="${cp.id}" title="Open live processing messages for this customer" style="font-size: 11px; font-weight: 600;">
                            <i class="fas fa-cog fa-spin me-1"></i> View Processing
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="${cp.id}" title="Inspect Customer Queue Details" style="font-size: 11px;">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody-customer-processing').html(html);
    }

    // 3d. Render Customer-Wise Submitted SMS Table
    function renderCustomerSubmittedTable(data) {
        if (!data) return;
        currentCustomerSubmittedData = data;
        const list = data.list || [];
        const agg = data.aggregates || {};

        let filtered = list.slice();

        // Filter by Operator tab if selected
        if (currentSubmOpFilter !== 'all') {
            filtered = filtered.filter(function(cs) {
                const ops = cs.operator_breakdown || {};
                return (ops[currentSubmOpFilter] || 0) > 0;
            });
        }

        // Filter by Search Input
        if (currentSubmSearchTerm.trim() !== '') {
            const term = currentSubmSearchTerm.toLowerCase();
            filtered = filtered.filter(function(cs) {
                const name = (cs.name || '').toLowerCase();
                const username = (cs.username || '').toLowerCase();
                const phone = (cs.phone || '').toLowerCase();
                return name.includes(term) || username.includes(term) || phone.includes(term);
            });
        }

        // Update Header Badges & Aggregates
        $('#subm-today-badge').text(Number(agg.today_submitted || 0).toLocaleString());
        $('#subm-total-badge').text(Number(agg.total_submitted || 0).toLocaleString());
        $('#subm-clients-badge').text(agg.active_clients_today || 0);
        $('#kpi-subm-today').text(Number(agg.today_submitted || 0).toLocaleString());
        $('#kpi-subm-total').text(Number(agg.total_submitted || 0).toLocaleString());
        $('#kpi-subm-clients').text(agg.active_clients_today || 0);
        $('#kpi-subm-of-total').text('of ' + (agg.total_customers || 0) + ' Clients');
        $('#kpi-subm-rate').text((agg.acceptance_rate || 100) + '%');

        const ops = agg.operator_submitted || {};
        $('#kpi-subm-gp').text(Number(ops.GP || 0).toLocaleString());
        $('#kpi-subm-bl').text(Number(ops.BL || 0).toLocaleString());
        $('#kpi-subm-robi').text(Number(ops.Robi || 0).toLocaleString());
        $('#kpi-subm-tt').text(Number(ops.Teletalk || 0).toLocaleString());

        if (!filtered.length) {
            $('#tbody-customer-submitted').html(`
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="fas fa-paper-plane text-muted fs-3 d-block mb-2"></i>
                        No customer submitted SMS found matching your criteria.
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        filtered.forEach(function(cs) {
            const phoneStr = (cs.phone && cs.phone !== 'N/A') ? ` • ${cs.phone}` : '';
            const cOps = cs.operator_breakdown || {};

            let velocityBadge = '';
            if (cs.velocity === 'high_volume') {
                velocityBadge = `<span class="badge bg-success text-white py-1 px-2"><i class="fas fa-rocket me-1"></i>High Volume</span>`;
            } else if (cs.velocity === 'active') {
                velocityBadge = `<span class="badge bg-primary text-white py-1 px-2"><i class="fas fa-paper-plane me-1"></i>Active Sending</span>`;
            } else {
                velocityBadge = `<span class="badge badge-soft-secondary py-1 px-2">Idle Today</span>`;
            }

            const todayHtml = cs.today_submitted > 0
                ? `<span class="badge bg-primary text-white fs-6 px-2 py-1 fw-bold"><i class="fas fa-paper-plane me-1"></i>${Number(cs.today_submitted).toLocaleString()}</span>`
                : `<span class="text-muted fw-bold">0</span>`;

            html += `
                <tr data-cust-id="${cs.id}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-user ${cs.today_submitted > 0 ? 'text-primary' : 'text-muted'} me-1"></i>
                            ${cs.name}
                        </div>
                        <small class="text-muted">@${cs.username}${phoneStr}</small>
                    </td>
                    <td class="text-center">${todayHtml}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                            <span class="fw-bold text-dark">${Number(cs.lifetime_submitted).toLocaleString()}</span>
                            <small class="text-muted">${cs.traffic_share}%</small>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: ${cs.traffic_share}%;" aria-valuenow="${cs.traffic_share}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1" style="font-size: 10px;">
                            <span class="badge ${(cOps.GP || 0) > 0 ? 'badge-soft-primary' : 'badge-soft-secondary text-muted'}" title="Grameenphone">GP: ${cOps.GP || 0}</span>
                            <span class="badge ${(cOps.BL || 0) > 0 ? 'badge-soft-warning' : 'badge-soft-secondary text-muted'}" title="Banglalink">BL: ${cOps.BL || 0}</span>
                            <span class="badge ${(cOps.Robi || 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted'}" title="Robi/Airtel">Robi: ${cOps.Robi || 0}</span>
                            <span class="badge ${(cOps.Teletalk || 0) > 0 ? 'badge-soft-success' : 'badge-soft-secondary text-muted'}" title="Teletalk">TT: ${cOps.Teletalk || 0}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-soft-info" style="font-size: 10px;" title="Campaign Messages">
                            <i class="fas fa-bullhorn me-1"></i>${cs.campaign_submitted || 0}
                        </span>
                        <span class="badge badge-soft-secondary ms-1" style="font-size: 10px;" title="Direct / API Single Messages">
                            <i class="fas fa-bolt me-1"></i>${cs.direct_submitted || 0}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-soft-success py-1 px-2 fw-bold" style="font-size: 11px;">
                            <i class="fas fa-check-double me-1"></i>${cs.acceptance_rate}%
                        </span>
                    </td>
                    <td class="text-center">${velocityBadge}</td>
                    <td class="text-end fw-bold text-dark">৳${Number(cs.balance).toFixed(2)}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-primary py-1 px-2 btn-view-customer-submitted" data-id="${cs.id}" title="Open live submitted messages for this customer" style="font-size: 11px; font-weight: 600;">
                            <i class="fas fa-paper-plane me-1"></i> View Dispatched
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="${cs.id}" title="Inspect Customer Queue Details" style="font-size: 11px;">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody-customer-submitted').html(html);
    }

    // 3e. Render Customer-Wise Delivered SMS Table
    function renderCustomerDeliveredTable(data) {
        if (!data) return;
        currentCustomerDeliveredData = data;
        const list = data.list || [];
        const agg = data.aggregates || {};

        let filtered = list.slice();

        // Filter by Operator tab if selected
        if (currentDelivOpFilter !== 'all') {
            filtered = filtered.filter(function(cd) {
                const ops = cd.operator_breakdown || {};
                return (ops[currentDelivOpFilter] || 0) > 0;
            });
        }

        // Filter by Search Input
        if (currentDelivSearchTerm.trim() !== '') {
            const term = currentDelivSearchTerm.toLowerCase();
            filtered = filtered.filter(function(cd) {
                const name = (cd.name || '').toLowerCase();
                const username = (cd.username || '').toLowerCase();
                const phone = (cd.phone || '').toLowerCase();
                return name.includes(term) || username.includes(term) || phone.includes(term);
            });
        }

        // Update Header Badges & Aggregates
        $('#deliv-today-badge').text(Number(agg.today_delivered || 0).toLocaleString());
        $('#deliv-total-badge').text(Number(agg.total_delivered || 0).toLocaleString());
        $('#deliv-rate-badge').text((agg.delivery_rate || 100) + '%');
        $('#kpi-deliv-today').text(Number(agg.today_delivered || 0).toLocaleString());
        $('#kpi-deliv-total').text(Number(agg.total_delivered || 0).toLocaleString());
        $('#kpi-deliv-clients').text(agg.active_clients_today || 0);
        $('#kpi-deliv-of-total').text('of ' + (agg.total_customers || 0) + ' Clients');
        $('#kpi-deliv-rate').text((agg.delivery_rate || 100) + '%');

        const ops = agg.operator_delivered || {};
        $('#kpi-deliv-gp').text(Number(ops.GP || 0).toLocaleString());
        $('#kpi-deliv-bl').text(Number(ops.BL || 0).toLocaleString());
        $('#kpi-deliv-robi').text(Number(ops.Robi || 0).toLocaleString());
        $('#kpi-deliv-tt').text(Number(ops.Teletalk || 0).toLocaleString());

        if (!filtered.length) {
            $('#tbody-customer-delivered').html(`
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="fas fa-check-circle text-muted fs-3 d-block mb-2"></i>
                        No customer delivered SMS found matching your criteria.
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        filtered.forEach(function(cd) {
            const phoneStr = (cd.phone && cd.phone !== 'N/A') ? ` • ${cd.phone}` : '';
            const cOps = cd.operator_breakdown || {};

            let healthBadge = '';
            if (cd.health === 'low_ratio') {
                healthBadge = `<span class="badge bg-warning text-dark py-1 px-2"><i class="fas fa-exclamation-triangle me-1"></i>Low DLR Ratio</span>`;
            } else if (cd.health === 'satisfactory') {
                healthBadge = `<span class="badge bg-info text-white py-1 px-2"><i class="fas fa-check me-1"></i>Satisfactory</span>`;
            } else {
                healthBadge = `<span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check-double me-1"></i>Optimal Flow</span>`;
            }

            const todayHtml = cd.today_delivered > 0
                ? `<span class="badge bg-success text-white fs-6 px-2 py-1 fw-bold"><i class="fas fa-check-double me-1"></i>${Number(cd.today_delivered).toLocaleString()}</span>`
                : `<span class="text-muted fw-bold">0</span>`;

            html += `
                <tr data-cust-id="${cd.id}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-user ${cd.today_delivered > 0 ? 'text-success' : 'text-muted'} me-1"></i>
                            ${cd.name}
                        </div>
                        <small class="text-muted">@${cd.username}${phoneStr}</small>
                    </td>
                    <td class="text-center">${todayHtml}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                            <span class="fw-bold text-dark">${Number(cd.lifetime_delivered).toLocaleString()}</span>
                            <small class="text-muted">${cd.traffic_share}%</small>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: ${cd.traffic_share}%;" aria-valuenow="${cd.traffic_share}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1" style="font-size: 10px;">
                            <span class="badge ${(cOps.GP || 0) > 0 ? 'badge-soft-primary' : 'badge-soft-secondary text-muted'}" title="Grameenphone">GP: ${cOps.GP || 0}</span>
                            <span class="badge ${(cOps.BL || 0) > 0 ? 'badge-soft-warning' : 'badge-soft-secondary text-muted'}" title="Banglalink">BL: ${cOps.BL || 0}</span>
                            <span class="badge ${(cOps.Robi || 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted'}" title="Robi/Airtel">Robi: ${cOps.Robi || 0}</span>
                            <span class="badge ${(cOps.Teletalk || 0) > 0 ? 'badge-soft-success' : 'badge-soft-secondary text-muted'}" title="Teletalk">TT: ${cOps.Teletalk || 0}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-soft-info" style="font-size: 10px;" title="Campaign Messages">
                            <i class="fas fa-bullhorn me-1"></i>${cd.campaign_delivered || 0}
                        </span>
                        <span class="badge badge-soft-secondary ms-1" style="font-size: 10px;" title="Direct / API Single Messages">
                            <i class="fas fa-bolt me-1"></i>${cd.direct_delivered || 0}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-soft-success py-1 px-2 fw-bold" style="font-size: 11px;">
                            <i class="fas fa-check-double me-1"></i>${cd.delivery_rate}%
                        </span>
                    </td>
                    <td class="text-center">${healthBadge}</td>
                    <td class="text-end fw-bold text-dark">৳${Number(cd.balance).toFixed(2)}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-success py-1 px-2 btn-view-customer-delivered" data-id="${cd.id}" title="Open live delivered messages for this customer" style="font-size: 11px; font-weight: 600;">
                            <i class="fas fa-check-double me-1"></i> View Delivered
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="${cd.id}" title="Inspect Customer Queue Details" style="font-size: 11px;">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody-customer-delivered').html(html);
    }

    // 3f. Render Customer-Wise Failed SMS Table
    function renderCustomerFailedTable(data) {
        if (!data) return;
        currentCustomerFailedData = data;
        const list = data.list || [];
        const agg = data.aggregates || {};

        let filtered = list.slice();

        // Filter by Operator tab if selected
        if (currentFailOpFilter !== 'all') {
            filtered = filtered.filter(function(cf) {
                const ops = cf.operator_breakdown || {};
                return (ops[currentFailOpFilter] || 0) > 0;
            });
        }

        // Filter by Search Input
        if (currentFailSearchTerm.trim() !== '') {
            const term = currentFailSearchTerm.toLowerCase();
            filtered = filtered.filter(function(cf) {
                const name = (cf.name || '').toLowerCase();
                const username = (cf.username || '').toLowerCase();
                const phone = (cf.phone || '').toLowerCase();
                return name.includes(term) || username.includes(term) || phone.includes(term);
            });
        }

        // Update Header Badges & Aggregates
        $('#fail-today-badge').text(Number(agg.today_failed || 0).toLocaleString());
        $('#fail-total-badge').text(Number(agg.total_failed || 0).toLocaleString());
        $('#fail-clients-badge').text(agg.clients_with_failures || 0);
        $('#kpi-fail-today').text(Number(agg.today_failed || 0).toLocaleString());
        $('#kpi-fail-total').text(Number(agg.total_failed || 0).toLocaleString());
        $('#kpi-fail-clients').text(agg.clients_with_failures || 0);
        $('#kpi-fail-of-total').text('of ' + (agg.total_customers || 0) + ' Clients');
        $('#kpi-fail-rate').text((agg.failure_rate || 0) + '%');

        const ops = agg.operator_failed || {};
        $('#kpi-fail-gp').text(Number(ops.GP || 0).toLocaleString());
        $('#kpi-fail-bl').text(Number(ops.BL || 0).toLocaleString());
        $('#kpi-fail-robi').text(Number(ops.Robi || 0).toLocaleString());
        $('#kpi-fail-tt').text(Number(ops.Teletalk || 0).toLocaleString());

        if (!filtered.length) {
            $('#tbody-customer-failed').html(`
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="fas fa-check-circle text-success fs-3 d-block mb-2"></i>
                        Zero failures reported across customer queues. All transmissions healthy!
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        filtered.forEach(function(cf) {
            const phoneStr = (cf.phone && cf.phone !== 'N/A') ? ` • ${cf.phone}` : '';
            const cOps = cf.operator_breakdown || {};

            let severityBadge = '';
            if (cf.severity === 'critical') {
                severityBadge = `<span class="badge bg-danger text-white py-1 px-2"><i class="fas fa-exclamation-triangle me-1"></i>High Error Ratio</span>`;
            } else if (cf.severity === 'moderate') {
                severityBadge = `<span class="badge bg-warning text-dark py-1 px-2"><i class="fas fa-exclamation-circle me-1"></i>Moderate Failures</span>`;
            } else {
                severityBadge = `<span class="badge badge-soft-success py-1 px-2"><i class="fas fa-check me-1"></i>Normal Flow</span>`;
            }

            const todayHtml = cf.today_failed > 0
                ? `<span class="badge bg-danger text-white fs-6 px-2 py-1 fw-bold"><i class="fas fa-times-circle me-1"></i>${Number(cf.today_failed).toLocaleString()}</span>`
                : `<span class="text-muted fw-bold">0</span>`;

            const retryBtn = cf.lifetime_failed > 0
                ? `<button type="button" class="btn btn-sm btn-outline-warning py-1 px-2 btn-retry-customer-failed" data-id="${cf.id}" data-name="${cf.name}" data-count="${cf.lifetime_failed}" title="Retry / Re-queue all failed messages for this customer" style="font-size: 11px;">
                        <i class="fas fa-redo me-1"></i> Retry All
                   </button>`
                : '';

            html += `
                <tr data-cust-id="${cf.id}" data-today-failed="${cf.today_failed}" data-total-failed="${cf.lifetime_failed}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-user ${cf.today_failed > 0 ? 'text-danger' : 'text-muted'} me-1"></i>
                            ${cf.name}
                        </div>
                        <small class="text-muted">@${cf.username}${phoneStr}</small>
                    </td>
                    <td class="text-center">${todayHtml}</td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                            <span class="fw-bold text-dark">${Number(cf.lifetime_failed).toLocaleString()}</span>
                            <small class="text-muted">${cf.traffic_share}%</small>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: ${cf.traffic_share}%;" aria-valuenow="${cf.traffic_share}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1" style="font-size: 10px;">
                            <span class="badge ${(cOps.GP || 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted'}" title="Grameenphone">GP: ${cOps.GP || 0}</span>
                            <span class="badge ${(cOps.BL || 0) > 0 ? 'badge-soft-warning' : 'badge-soft-secondary text-muted'}" title="Banglalink">BL: ${cOps.BL || 0}</span>
                            <span class="badge ${(cOps.Robi || 0) > 0 ? 'badge-soft-danger' : 'badge-soft-secondary text-muted'}" title="Robi/Airtel">Robi: ${cOps.Robi || 0}</span>
                            <span class="badge ${(cOps.Teletalk || 0) > 0 ? 'badge-soft-success' : 'badge-soft-secondary text-muted'}" title="Teletalk">TT: ${cOps.Teletalk || 0}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-soft-info" style="font-size: 10px;" title="Campaign Messages">
                            <i class="fas fa-bullhorn me-1"></i>${cf.campaign_failed || 0}
                        </span>
                        <span class="badge badge-soft-secondary ms-1" style="font-size: 10px;" title="Direct / API Single Messages">
                            <i class="fas fa-bolt me-1"></i>${cf.direct_failed || 0}
                        </span>
                    </td>
                    <td class="text-center">
                        <small class="text-muted d-block text-truncate" style="max-width: 140px; font-size: 11px;" title="${cf.primary_error}">
                            ${cf.primary_error}
                        </small>
                    </td>
                    <td class="text-center">${severityBadge}</td>
                    <td class="text-end fw-bold text-dark">৳${Number(cf.balance).toFixed(2)}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-danger py-1 px-2 btn-view-customer-failed" data-id="${cf.id}" title="Open live failed messages for this customer" style="font-size: 11px; font-weight: 600;">
                            <i class="fas fa-times-circle me-1"></i> View Failed
                        </button>
                        ${retryBtn}
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="${cf.id}" title="Inspect Customer Queue Details" style="font-size: 11px;">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody-customer-failed').html(html);
    }

    // 3g. Render Campaign-Wise SMS Table & Aggregates
    function renderCampaignsTable(campaigns, aggregates) {
        if (campaigns) currentCampaignsData = campaigns;
        if (aggregates) currentCampaignAggregates = aggregates;

        const allCamps = currentCampaignsData || [];
        const agg = currentCampaignAggregates || {};

        let filtered = allCamps.slice();

        // 1. Filter by Type (All / Own / Customer)
        if (currentCampaignTypeFilter === 'own') {
            filtered = filtered.filter(c => c.creator_type === 'own');
        } else if (currentCampaignTypeFilter === 'customer') {
            filtered = filtered.filter(c => c.creator_type === 'customer');
        }

        // 2. Filter by Search input
        if (currentCampaignSearchTerm.trim() !== '') {
            const term = currentCampaignSearchTerm.toLowerCase();
            filtered = filtered.filter(function(c) {
                const name = (c.name || '').toLowerCase();
                const sender = (c.sender || '').toLowerCase();
                const cName = (c.creator_name || '').toLowerCase();
                const cUser = (c.creator_username || '').toLowerCase();
                const cId = String(c.id || '');
                return name.includes(term) || sender.includes(term) || cName.includes(term) || cUser.includes(term) || cId.includes(term);
            });
        }

        // Update count badges
        $('#campaigns-count').text(filtered.length);

        // Update aggregate mini KPI counters
        $('#camp-agg-total').text(agg.total_campaigns || 0);
        $('#camp-agg-own').text(agg.own_campaigns || 0);
        $('#camp-agg-customer').text(agg.customer_campaigns || (agg.downline_campaigns || 0));
        $('#camp-agg-recipients').text(Number(agg.total_recipients || 0).toLocaleString());
        $('#camp-agg-pending').text(Number(agg.pending_queue || 0).toLocaleString());
        $('#camp-agg-processing').text(Number(agg.processing_queue || 0).toLocaleString());
        $('#camp-agg-delivered').text(Number(agg.delivered_count || 0).toLocaleString());
        $('#camp-agg-failed').text(Number(agg.failed_count || 0).toLocaleString());
        $('#camp-agg-progress').text((agg.avg_progress || 0) + '%');

        if (!filtered.length) {
            $('#tbody-campaigns').html(`
                <tr>
                    <td colspan="11" class="text-center py-4 text-muted">
                        <i class="fas fa-bullhorn text-muted fs-3 d-block mb-2"></i>
                        No campaigns match this filter. System ready for bulk broadcasts.
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

            let creatorBadge = '';
            if (camp.creator_type === 'own') {
                creatorBadge = `<span class="badge bg-success" style="font-size: 9px;"><i class="fas fa-user-check me-1"></i>YOU (Direct)</span>`;
            } else {
                creatorBadge = `<span class="badge badge-soft-info" style="font-size: 10px;">Customer</span>`;
            }

            let actionBtns = '';
            if (camp.creator_id) {
                actionBtns += `
                    <button type="button" class="btn btn-sm btn-outline-success py-1 px-2 btn-open-customer-queue" data-id="${camp.creator_id}" title="Inspect Queue Messages" style="font-size: 11px;">
                        <i class="fas fa-layer-group me-1"></i> View Queue
                    </button>
                `;
            }
            if (camp.pending_queue > 0) {
                actionBtns += `
                    <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 btn-cancel-campaign-pending" data-id="${camp.id}" data-name="${camp.name}" data-count="${camp.pending_queue}" title="Cancel pending SMS in this campaign" style="font-size: 11px;">
                        <i class="fas fa-ban me-1"></i> Cancel
                    </button>
                `;
            }
            if (camp.creator_id) {
                actionBtns += `
                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="${camp.creator_id}" title="Inspect Account Details" style="font-size: 11px;">
                        <i class="fas fa-search-plus"></i>
                    </button>
                `;
            }

            html += `
                <tr data-campaign-id="${camp.id}" data-creator-type="${camp.creator_type || 'customer'}">
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
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                            ${camp.creator_name}
                            ${creatorBadge}
                        </div>
                        <small class="text-muted d-block" style="font-size: 11px;">@<span>${camp.creator_username}</span></small>
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
                        ${actionBtns}
                    </td>
                </tr>
            `;
        });
        $('#tbody-campaigns').html(html);
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

    // Customer Matrix Toggle Pending Only Filter
    $('#btn-toggle-matrix-pending').on('click', function() {
        matrixPendingOnly = !matrixPendingOnly;
        if (matrixPendingOnly) {
            matrixProcOnly = false;
            matrixSubmOnly = false;
            matrixDelivOnly = false;
            matrixFailOnly = false;
            $('#btn-toggle-matrix-proc').removeClass('btn-info text-white active').addClass('btn-outline-info').html('<i class="fas fa-cog me-1"></i> Processing Only');
            $('#btn-toggle-matrix-subm').removeClass('btn-primary text-white active').addClass('btn-outline-primary').html('<i class="fas fa-paper-plane me-1"></i> Submitted Today');
            $('#btn-toggle-matrix-deliv').removeClass('btn-success text-white active').addClass('btn-outline-success').html('<i class="fas fa-check-double me-1"></i> Delivered Today');
            $('#btn-toggle-matrix-fail').removeClass('btn-danger text-white active').addClass('btn-outline-danger').html('<i class="fas fa-times-circle me-1"></i> Failed Today');
            $(this).removeClass('btn-outline-warning').addClass('btn-warning text-dark active');
            $(this).html('<i class="fas fa-check me-1"></i> Showing Pending Only');
        } else {
            $(this).removeClass('btn-warning text-dark active').addClass('btn-outline-warning');
            $(this).html('<i class="fas fa-hourglass-half me-1"></i> Pending Queue Only');
        }
        renderCustomersMatrixTable(currentCustomerMatrixData);
    });

    // Customer Matrix Toggle Processing Only Filter
    $('#btn-toggle-matrix-proc').on('click', function() {
        matrixProcOnly = !matrixProcOnly;
        if (matrixProcOnly) {
            matrixPendingOnly = false;
            matrixSubmOnly = false;
            matrixDelivOnly = false;
            matrixFailOnly = false;
            $('#btn-toggle-matrix-pending').removeClass('btn-warning text-dark active').addClass('btn-outline-warning').html('<i class="fas fa-hourglass-half me-1"></i> Pending Queue Only');
            $('#btn-toggle-matrix-subm').removeClass('btn-primary text-white active').addClass('btn-outline-primary').html('<i class="fas fa-paper-plane me-1"></i> Submitted Today');
            $('#btn-toggle-matrix-deliv').removeClass('btn-success text-white active').addClass('btn-outline-success').html('<i class="fas fa-check-double me-1"></i> Delivered Today');
            $('#btn-toggle-matrix-fail').removeClass('btn-danger text-white active').addClass('btn-outline-danger').html('<i class="fas fa-times-circle me-1"></i> Failed Today');
            $(this).removeClass('btn-outline-info').addClass('btn-info text-white active');
            $(this).html('<i class="fas fa-check me-1"></i> Showing Processing Only');
        } else {
            $(this).removeClass('btn-info text-white active').addClass('btn-outline-info');
            $(this).html('<i class="fas fa-cog me-1"></i> Processing Only');
        }
        renderCustomersMatrixTable(currentCustomerMatrixData);
    });

    // Customer Matrix Toggle Submitted Today Filter
    $('#btn-toggle-matrix-subm').on('click', function() {
        matrixSubmOnly = !matrixSubmOnly;
        if (matrixSubmOnly) {
            matrixPendingOnly = false;
            matrixProcOnly = false;
            matrixDelivOnly = false;
            matrixFailOnly = false;
            $('#btn-toggle-matrix-pending').removeClass('btn-warning text-dark active').addClass('btn-outline-warning').html('<i class="fas fa-hourglass-half me-1"></i> Pending Queue Only');
            $('#btn-toggle-matrix-proc').removeClass('btn-info text-white active').addClass('btn-outline-info').html('<i class="fas fa-cog me-1"></i> Processing Only');
            $('#btn-toggle-matrix-deliv').removeClass('btn-success text-white active').addClass('btn-outline-success').html('<i class="fas fa-check-double me-1"></i> Delivered Today');
            $('#btn-toggle-matrix-fail').removeClass('btn-danger text-white active').addClass('btn-outline-danger').html('<i class="fas fa-times-circle me-1"></i> Failed Today');
            $(this).removeClass('btn-outline-primary').addClass('btn-primary text-white active');
            $(this).html('<i class="fas fa-check me-1"></i> Showing Submitted Today');
        } else {
            $(this).removeClass('btn-primary text-white active').addClass('btn-outline-primary');
            $(this).html('<i class="fas fa-paper-plane me-1"></i> Submitted Today');
        }
        renderCustomersMatrixTable(currentCustomerMatrixData);
    });

    // Customer Matrix Toggle Delivered Today Filter
    $('#btn-toggle-matrix-deliv').on('click', function() {
        matrixDelivOnly = !matrixDelivOnly;
        if (matrixDelivOnly) {
            matrixPendingOnly = false;
            matrixProcOnly = false;
            matrixSubmOnly = false;
            matrixFailOnly = false;
            $('#btn-toggle-matrix-pending').removeClass('btn-warning text-dark active').addClass('btn-outline-warning').html('<i class="fas fa-hourglass-half me-1"></i> Pending Queue Only');
            $('#btn-toggle-matrix-proc').removeClass('btn-info text-white active').addClass('btn-outline-info').html('<i class="fas fa-cog me-1"></i> Processing Only');
            $('#btn-toggle-matrix-subm').removeClass('btn-primary text-white active').addClass('btn-outline-primary').html('<i class="fas fa-paper-plane me-1"></i> Submitted Today');
            $('#btn-toggle-matrix-fail').removeClass('btn-danger text-white active').addClass('btn-outline-danger').html('<i class="fas fa-times-circle me-1"></i> Failed Today');
            $(this).removeClass('btn-outline-success').addClass('btn-success text-white active');
            $(this).html('<i class="fas fa-check me-1"></i> Showing Delivered Today');
        } else {
            $(this).removeClass('btn-success text-white active').addClass('btn-outline-success');
            $(this).html('<i class="fas fa-check-double me-1"></i> Delivered Today');
        }
        renderCustomersMatrixTable(currentCustomerMatrixData);
    });

    // Customer Matrix Toggle Failed Today Filter
    $('#btn-toggle-matrix-fail').on('click', function() {
        matrixFailOnly = !matrixFailOnly;
        if (matrixFailOnly) {
            matrixPendingOnly = false;
            matrixProcOnly = false;
            matrixSubmOnly = false;
            matrixDelivOnly = false;
            $('#btn-toggle-matrix-pending').removeClass('btn-warning text-dark active').addClass('btn-outline-warning').html('<i class="fas fa-hourglass-half me-1"></i> Pending Queue Only');
            $('#btn-toggle-matrix-proc').removeClass('btn-info text-white active').addClass('btn-outline-info').html('<i class="fas fa-cog me-1"></i> Processing Only');
            $('#btn-toggle-matrix-subm').removeClass('btn-primary text-white active').addClass('btn-outline-primary').html('<i class="fas fa-paper-plane me-1"></i> Submitted Today');
            $('#btn-toggle-matrix-deliv').removeClass('btn-success text-white active').addClass('btn-outline-success').html('<i class="fas fa-check-double me-1"></i> Delivered Today');
            $(this).removeClass('btn-outline-danger').addClass('btn-danger text-white active');
            $(this).html('<i class="fas fa-check me-1"></i> Showing Failed Today');
        } else {
            $(this).removeClass('btn-danger text-white active').addClass('btn-outline-danger');
            $(this).html('<i class="fas fa-times-circle me-1"></i> Failed Today');
        }
        renderCustomersMatrixTable(currentCustomerMatrixData);
    });

    // Customer Failed Search Filter
    $('#input-search-failed').on('input', function() {
        currentFailSearchTerm = $(this).val();
        renderCustomerFailedTable(currentCustomerFailedData);
    });

    // Customer Failed Operator Tabs
    $('.btn-fail-op-filter').on('click', function() {
        $('.btn-fail-op-filter').removeClass('active');
        $(this).addClass('active');
        currentFailOpFilter = $(this).data('op');
        renderCustomerFailedTable(currentCustomerFailedData);
    });

    // View Failed Queue for specific customer
    $(document).on('click', '.btn-view-customer-failed', function() {
        const custId = $(this).data('id');
        $('#select-filter-user').val(custId);
        currentCustQueueStatusFilter = 'failed';
        $('.cust-status-tab-btn').removeClass('active');
        $('.cust-status-tab-btn[data-status="failed"]').addClass('active');
        fetchLiveData();
        setTimeout(function() {
            if ($('#panel-customer-queue').length) {
                $('html, body').animate({
                    scrollTop: $('#panel-customer-queue').offset().top - 80
                }, 'fast');
            }
        }, 300);
    });

    // Bulk Retry Failed SMS for specific customer
    $(document).on('click', '.btn-retry-customer-failed', function() {
        const custId = $(this).data('id');
        const custName = $(this).data('name') || 'this customer';
        const failCount = $(this).data('count') || 0;

        Swal.fire({
            title: 'Retry All Failed SMS?',
            html: `Are you sure you want to re-queue <strong>${Number(failCount).toLocaleString()}</strong> failed messages for <strong>${custName}</strong>?<br><small class="text-muted">Messages will be placed back into the pending queue with fresh delivery timestamps.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-redo me-1"></i> Yes, Re-Queue SMS',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Re-queueing Messages...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                $.ajax({
                    url: "{{ url('/ssp/queue-monitor/retry-customer-failed') }}/" + custId,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'SMS Re-Queued!',
                                text: res.message || 'Failed messages returned to pending queue successfully.',
                                timer: 2500,
                                showConfirmButton: false
                            });
                            fetchLiveData();
                        } else {
                            Swal.fire('Error', res.message || 'Failed to retry messages.', 'error');
                        }
                    },
                    error: function(xhr) {
                        const errMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Server error occurred while re-queuing messages.';
                        Swal.fire('Failed', errMsg, 'error');
                    }
                });
            }
        });
    });

    // Customer Delivered Search Filter
    $('#input-search-delivered').on('input', function() {
        currentDelivSearchTerm = $(this).val();
        renderCustomerDeliveredTable(currentCustomerDeliveredData);
    });

    // Customer Delivered Operator Tabs
    $('.btn-deliv-op-filter').on('click', function() {
        $('.btn-deliv-op-filter').removeClass('active');
        $(this).addClass('active');
        currentDelivOpFilter = $(this).data('op');
        renderCustomerDeliveredTable(currentCustomerDeliveredData);
    });

    // View Delivered Queue for specific customer
    $(document).on('click', '.btn-view-customer-delivered', function() {
        const custId = $(this).data('id');
        $('#select-filter-user').val(custId);
        currentCustQueueStatusFilter = 'delivered';
        $('.cust-status-tab-btn').removeClass('active');
        $('.cust-status-tab-btn[data-status="delivered"]').addClass('active');
        fetchLiveData();
        setTimeout(function() {
            if ($('#panel-customer-queue').length) {
                $('html, body').animate({
                    scrollTop: $('#panel-customer-queue').offset().top - 80
                }, 'fast');
            }
        }, 300);
    });

    // Customer Submitted Search Filter
    $('#input-search-submitted').on('input', function() {
        currentSubmSearchTerm = $(this).val();
        renderCustomerSubmittedTable(currentCustomerSubmittedData);
    });

    // Customer Submitted Operator Tabs
    $('.btn-subm-op-filter').on('click', function() {
        $('.btn-subm-op-filter').removeClass('active');
        $(this).addClass('active');
        currentSubmOpFilter = $(this).data('op');
        renderCustomerSubmittedTable(currentCustomerSubmittedData);
    });

    // View Submitted Queue for specific customer
    $(document).on('click', '.btn-view-customer-submitted', function() {
        const custId = $(this).data('id');
        $('#select-filter-user').val(custId);
        currentCustQueueStatusFilter = 'all';
        $('.cust-status-tab-btn').removeClass('active');
        $('.cust-status-tab-btn[data-status="all"]').addClass('active');
        fetchLiveData();
        setTimeout(function() {
            if ($('#panel-customer-queue').length) {
                $('html, body').animate({
                    scrollTop: $('#panel-customer-queue').offset().top - 80
                }, 'fast');
            }
        }, 300);
    });

    // Customer Processing Search Filter
    $('#input-search-processing').on('input', function() {
        currentProcSearchTerm = $(this).val();
        renderCustomerProcessingTable(currentCustomerProcessingData);
    });

    // Customer Processing Operator Tabs
    $('.btn-proc-op-filter').on('click', function() {
        $('.btn-proc-op-filter').removeClass('active');
        $(this).addClass('active');
        currentProcOpFilter = $(this).data('op');
        renderCustomerProcessingTable(currentCustomerProcessingData);
    });

    // View Processing Queue for specific customer
    $(document).on('click', '.btn-view-customer-processing', function() {
        const custId = $(this).data('id');
        $('#select-filter-user').val(custId);
        currentCustQueueStatusFilter = 'processing';
        $('.cust-status-tab-btn').removeClass('active');
        $('.cust-status-tab-btn[data-status="processing"]').addClass('active');
        fetchLiveData();
        setTimeout(function() {
            if ($('#panel-customer-queue').length) {
                $('html, body').animate({
                    scrollTop: $('#panel-customer-queue').offset().top - 80
                }, 'fast');
            }
        }, 300);
    });

    // Customer Pending Search Filter
    $('#input-search-pending').on('input', function() {
        currentPendingSearchTerm = $(this).val();
        renderCustomerPendingTable(currentCustomerPendingData);
    });

    // Customer Pending Operator Tabs
    $('.btn-pending-op-filter').on('click', function() {
        $('.btn-pending-op-filter').removeClass('active');
        $(this).addClass('active');
        currentPendingOpFilter = $(this).data('op');
        renderCustomerPendingTable(currentCustomerPendingData);
    });

    // View Pending Queue for specific customer
    $(document).on('click', '.btn-view-customer-pending', function() {
        const custId = $(this).data('id');
        $('#select-filter-user').val(custId);
        currentCustQueueStatusFilter = 'pending';
        $('.cust-status-tab-btn').removeClass('active');
        $('.cust-status-tab-btn[data-status="pending"]').addClass('active');
        fetchLiveData();
        setTimeout(function() {
            if ($('#panel-customer-queue').length) {
                $('html, body').animate({
                    scrollTop: $('#panel-customer-queue').offset().top - 80
                }, 'fast');
            }
        }, 300);
    });

    // Cancel all pending SMS for a specific customer
    $(document).on('click', '.btn-cancel-customer-pending', function() {
        const id = $(this).data('id');
        const name = $(this).data('name') || ('#' + id);
        const count = $(this).data('count') || 'all';
        if (!confirm('Are you sure you want to cancel ' + count + ' pending SMS for customer ' + name + '?')) return;

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        $.ajax({
            url: "{{ url('ssp/queue-monitor/cancel-customer-pending') }}/" + id,
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            dataType: "json",
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    fetchLiveData();
                } else {
                    toastr.error(res.message || 'Failed to cancel customer pending SMS.');
                }
            },
            error: function() {
                toastr.error('Failed to cancel customer pending SMS.');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-ban"></i>');
            }
        });
    });

    // Customer Queue Status Tabs
    $(document).on('click', '.cust-status-tab-btn', function() {
        $('.cust-status-tab-btn').removeClass('active');
        $(this).addClass('active');
        currentCustQueueStatusFilter = $(this).data('status');
        fetchLiveData();
    });

    // Campaign Type Filter Tabs
    $('.camp-type-tab-btn').on('click', function() {
        $('.camp-type-tab-btn').removeClass('active');
        $(this).addClass('active');
        currentCampaignTypeFilter = $(this).data('type');
        renderCampaignsTable(currentCampaignsData, currentCampaignAggregates);
    });

    // Campaign Search Filter
    $('#input-search-campaigns').on('input', function() {
        currentCampaignSearchTerm = $(this).val();
        renderCampaignsTable(currentCampaignsData, currentCampaignAggregates);
    });

    // Cancel all pending SMS in a campaign
    $(document).on('click', '.btn-cancel-campaign-pending', function() {
        const campId = $(this).data('id');
        const campName = $(this).data('name') || ('#' + campId);
        const count = $(this).data('count') || 'all';

        Swal.fire({
            title: 'Cancel Campaign Pending SMS?',
            html: `Are you sure you want to cancel <strong>${Number(count).toLocaleString()}</strong> pending SMS in campaign <strong>${campName}</strong>?<br><small class="text-muted">Unprocessed messages will be removed from the worker dispatch queue.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-ban me-1"></i> Yes, Cancel Pending',
            cancelButtonText: 'Abort'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Cancelling Campaign SMS...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                $.ajax({
                    url: "{{ url('/ssp/queue-monitor/cancel-campaign') }}/" + campId,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cancelled!',
                                text: res.message || 'Pending campaign SMS cancelled successfully.',
                                timer: 2500,
                                showConfirmButton: false
                            });
                            fetchLiveData();
                        } else {
                            Swal.fire('Error', res.message || 'Failed to cancel campaign SMS.', 'error');
                        }
                    },
                    error: function(xhr) {
                        const errMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Server error occurred while cancelling campaign SMS.';
                        Swal.fire('Failed', errMsg, 'error');
                    }
                });
            }
        });
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
