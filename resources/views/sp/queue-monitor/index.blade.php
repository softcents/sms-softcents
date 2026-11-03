@extends('layouts.sp.master')

@section('title', __('Live SMS Monitoring (Reseller)'))

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
    .erp-queue-monitor-section .pulse-indicator.warning {
        background-color: #f59e0b;
        box-shadow: 0 0 0 rgba(245, 158, 11, 0.7);
        animation: pulse-yellow 1.8s infinite;
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
    @keyframes pulse-yellow {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
        70% { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(245, 158, 11, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
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
                        <span class="badge badge-soft-primary ms-1" style="font-size: 11px;">RESELLER NETWORK SCOPE</span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3 mt-1" style="font-size: 13px; color: #64748b;">
                        <span><i class="fas fa-sitemap me-1 text-primary"></i> Reseller Network: <strong>{{ auth()->user()->name }}</strong> ({{ '@' . (auth()->user()->username ?? auth()->user()->name) }})</span>
                        <span>•</span>
                        <span><i class="fas fa-microchip me-1 text-success"></i> Workers: <strong class="text-success" id="workers-top-count">{{ $workers['active_workers'] }} Active</strong></span>
                        <span>•</span>
                        <span><i class="fas fa-tachometer-alt me-1 text-info"></i> Speed: <strong id="val-tps" class="text-info">{{ $metrics['current_tps'] }}</strong> TPS</span>
                        <span>•</span>
                        <span><i class="fas fa-check-circle me-1 text-success"></i> Delivery Rate: <strong id="val-delivery-rate" class="text-success">{{ $metrics['delivery_rate'] }}%</strong></span>
                        <span>•</span>
                        <span><i class="far fa-clock me-1"></i> Last Synced: <strong id="last-synced-time">{{ now()->format('H:i:s') }}</strong></span>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Hierarchy Drill-Down Selector -->
                    <div style="min-width: 220px;">
                        <select class="form-select form-select-sm" id="select-filter-user" style="font-size: 12px; font-weight: 600;">
                            <option value="">{{ __('🌐 All Network Traffic') }}</option>
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
                        <span>{{ __('Refresh') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Hierarchical Drill-Down Breadcrumb Trail -->
        <div class="panel-box mb-4 py-2 px-3 {{ empty($breadcrumbs) ? 'd-none' : '' }}" id="drilldown-nav-container" style="background: #f8fafc; border-left: 4px solid #4f46e5;">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                        <i class="fas fa-sitemap text-primary me-1"></i> {{ __('Hierarchy Drill-Down Navigation') }}:
                    </span>
                    <div class="d-flex flex-wrap align-items-center gap-1" id="drilldown-breadcrumbs-list">
                        <button type="button" class="btn btn-sm btn-light border py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="" style="font-size: 12px; font-weight: 600;">
                            <i class="fas fa-globe text-primary"></i> {{ __('All Network') }}
                        </button>
                        @foreach($breadcrumbs ?? [] as $idx => $bc)
                            <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                            <button type="button" class="btn btn-sm {{ $loop->last && empty($customerQueue) ? 'btn-primary shadow-sm' : 'btn-light border' }} py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="{{ $bc['id'] }}" style="font-size: 12px; font-weight: 600;">
                                <i class="{{ $bc['role'] === 'sp' ? 'fas fa-user-tie' : ($bc['role'] === 'ssp' ? 'fas fa-users' : 'fas fa-user') }}"></i>
                                <span>{{ $bc['name'] }}</span>
                                <span class="badge {{ $loop->last && empty($customerQueue) ? 'bg-light text-dark' : 'badge-soft-info' }}" style="font-size: 10px;">{{ $bc['role_label'] }}</span>
                            </button>
                        @endforeach
                        @if(!empty($customerQueue))
                            <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                            <span class="btn btn-sm btn-primary shadow-sm py-1 px-2 d-flex align-items-center gap-1" style="font-size: 12px; font-weight: 600;">
                                <i class="fas fa-layer-group"></i>
                                <span>{{ __('SMS Queue Details') }}</span>
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-xs btn-outline-danger py-1 px-2" id="btn-reset-drilldown" style="font-size: 11px;">
                        <i class="fas fa-times me-1"></i> {{ __('Reset to All Network') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Own Total SMS Traffic (Direct Dispatches by Reseller) -->
        <div class="panel-box mb-4" id="panel-own-traffic" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border-left: 5px solid #10b981;">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: transparent;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #dcfce7; color: #15803d;">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0" style="color: #065f46;">
                            {{ __('Own Total SMS Traffic') }}
                            <span class="badge bg-success ms-1" style="font-size: 10px;">{{ __('DIRECT DISPATCHES') }}</span>
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Traffic generated directly from this Reseller account (excluding downline Sub-resellers & Customers)') }}
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm {{ ($filterUserId ?? null) == auth()->id() ? 'btn-success' : 'btn-outline-success' }} py-1 px-3 d-flex align-items-center gap-1 shadow-sm" id="btn-toggle-own-traffic" data-own-id="{{ auth()->id() }}" style="font-size: 12px; font-weight: 600;">
                        <i class="fas {{ ($filterUserId ?? null) == auth()->id() ? 'fa-check-circle' : 'fa-filter' }}"></i>
                        <span id="btn-toggle-own-text">{{ ($filterUserId ?? null) == auth()->id() ? __('Viewing Own Direct Traffic') : __('Isolate Own Direct Traffic') }}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 {{ ($filterUserId ?? null) == auth()->id() ? '' : 'd-none' }}" id="btn-switch-network" style="font-size: 11px;">
                        <i class="fas fa-globe me-1"></i> {{ __('View All Network') }}
                    </button>
                </div>
            </div>
            <div class="p-3">
                <div class="row g-3 text-center">
                    <!-- 1. Lifetime Own Total SMS -->
                    <div class="col-lg col-md-4 col-6">
                        <div class="p-2 bg-white rounded border shadow-sm">
                            <span class="text-muted fw-bold d-block text-uppercase" style="font-size: 10px;">{{ __('Own Total SMS (Lifetime)') }}</span>
                            <span class="fs-4 fw-bold text-dark" id="val-own-lifetime">{{ number_format($metrics['own_traffic']['lifetime_total'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Direct Messages') }}</small>
                        </div>
                    </div>
                    <!-- 2. Today Own Dispatched -->
                    <div class="col-lg col-md-4 col-6">
                        <div class="p-2 bg-white rounded border shadow-sm">
                            <span class="text-muted fw-bold d-block text-uppercase" style="font-size: 10px;">{{ __('Own Today Dispatched') }}</span>
                            <span class="fs-4 fw-bold text-success" id="val-own-today">{{ number_format($metrics['own_traffic']['today_total'] ?? 0) }}</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Dispatched Today') }}</small>
                        </div>
                    </div>
                    <!-- 3. Own Pending in Queue -->
                    <div class="col-lg col-md-4 col-6">
                        <div class="p-2 bg-white rounded border shadow-sm h-100 d-flex flex-column justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block text-uppercase" style="font-size: 10px;">{{ __('Own Pending in Queue') }}</span>
                                <span class="fs-4 fw-bold {{ ($metrics['own_traffic']['pending_queue'] ?? 0) > 0 ? 'text-warning' : 'text-muted' }}" id="val-own-pending">{{ number_format($metrics['own_traffic']['pending_queue'] ?? 0) }}</span>
                                <small class="text-muted d-block" style="font-size: 10px;">{{ __('Awaiting Gateway Dispatch') }}</small>
                            </div>
                            <div class="mt-2 pt-1 border-top">
                                <button type="button" class="btn btn-xs btn-outline-warning py-0 px-2 btn-view-own-queue-status w-100" data-status="pending" style="font-size: 10px; font-weight: 600;">
                                    <i class="fas fa-eye me-1"></i> {{ __('View Pending SMS') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- 4. Own Processing (RAM) -->
                    <div class="col-lg col-md-4 col-6">
                        <div class="p-2 bg-white rounded border shadow-sm h-100 d-flex flex-column justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block text-uppercase" style="font-size: 10px;">{{ __('Own Processing (RAM)') }}</span>
                                <span class="fs-4 fw-bold {{ ($metrics['own_traffic']['processing_queue'] ?? 0) > 0 ? 'text-info' : 'text-muted' }}" id="val-own-processing">{{ number_format($metrics['own_traffic']['processing_queue'] ?? 0) }}</span>
                                <small class="text-muted d-block" style="font-size: 10px;">{{ __('In Worker RAM / Dispatching') }}</small>
                            </div>
                            <div class="mt-2 pt-1 border-top">
                                <button type="button" class="btn btn-xs btn-outline-info py-0 px-2 btn-view-own-queue-status w-100" data-status="processing" style="font-size: 10px; font-weight: 600;">
                                    <i class="fas fa-cog fa-spin me-1"></i> {{ __('View Processing SMS') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- 5. Own Delivery Rate -->
                    <div class="col-lg col-md-4 col-6">
                        <div class="p-2 bg-white rounded border shadow-sm">
                            <span class="text-muted fw-bold d-block text-uppercase" style="font-size: 10px;">{{ __('Own Delivery Rate') }}</span>
                            <span class="fs-4 fw-bold text-success" id="val-own-rate">{{ $metrics['own_traffic']['delivery_rate'] ?? 100 }}%</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Success Ratio') }}</small>
                        </div>
                    </div>
                    <!-- 6. Traffic Share of Network -->
                    <div class="col-lg col-md-4 col-6">
                        <div class="p-2 bg-white rounded border shadow-sm">
                            <span class="text-muted fw-bold d-block text-uppercase" style="font-size: 10px;">{{ __('Own Traffic Share') }}</span>
                            <span class="fs-4 fw-bold text-primary" id="val-own-share">{{ $metrics['own_traffic']['traffic_share'] ?? 0 }}%</span>
                            <small class="text-muted d-block" style="font-size: 10px;">{{ __('Of Network Total') }}</small>
                        </div>
                    </div>
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
                            <div class="kpi-val text-secondary" id="val-pending-count" style="color: #6366f1;">{{ number_format($metrics['pending_count']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #ede9fe; color: #7c3aed;">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Own: <strong id="val-sub-own-pending" class="text-success">{{ number_format($metrics['own_traffic']['pending_queue'] ?? 0) }}</strong> • Downline: <strong id="val-sub-downline-pending">{{ number_format(max(0, $metrics['pending_count'] - ($metrics['own_traffic']['pending_queue'] ?? 0))) }}</strong></span>
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
                        <span>Own: <strong id="val-sub-own-processing" class="text-success">{{ number_format($metrics['own_traffic']['processing_queue'] ?? 0) }}</strong> • Downline: <strong id="val-sub-downline-processing">{{ number_format(max(0, ($metrics['processing_count'] ?? 0) - ($metrics['own_traffic']['processing_queue'] ?? 0))) }}</strong></span>
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

            <!-- 10. Current TPS -->
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

        <!-- Real-Time Processing Speed & TPS Engine Panel -->
        <div class="panel-box mb-4">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="panel-title mb-0">
                        <i class="fas fa-tachometer-alt text-info"></i>
                        {{ __('Network TPS & Real-Time Processing Speed Engine') }}
                    </h5>
                    <span class="badge badge-soft-info"><span id="speed-indicator-tps">{{ $metrics['current_tps'] }}</span> TPS Active Flow</span>
                </div>
                <div class="text-muted" style="font-size: 12px;">
                    <i class="fas fa-wave-square text-primary me-1"></i>{{ __('20-Point Rolling Live Throughput Waveform') }}
                </div>
            </div>
            <div class="p-4">
                <!-- Speed Gauges & Moving Averages -->
                <div class="row g-3 mb-4 text-center">
                    <div class="col-lg col-md-4 col-6">
                        <div class="p-3 bg-light rounded border">
                            <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 11px;">Current TPS</span>
                            <span class="fs-3 fw-bold text-info" id="speed-val-current-tps">{{ $metrics['current_tps'] }}</span>
                            <small class="text-muted d-block" style="font-size: 11px;">SMS / Second</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="p-3 bg-light rounded border">
                            <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 11px;">1-Min Moving Avg</span>
                            <span class="fs-3 fw-bold text-primary" id="speed-val-1m-tps">{{ $metrics['moving_average_tps']['1m'] ?? round($metrics['last_1_min_volume'] / 60, 2) }}</span>
                            <small class="text-muted d-block" style="font-size: 11px;">{{ number_format($metrics['last_1_min_volume']) }} SMS / min</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="p-3 bg-light rounded border">
                            <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 11px;">5-Min Moving Avg</span>
                            <span class="fs-3 fw-bold" style="color: #6366f1;" id="speed-val-5m-tps">{{ $metrics['moving_average_tps']['5m'] ?? round($metrics['last_5_min_volume'] / 300, 2) }}</span>
                            <small class="text-muted d-block" style="font-size: 11px;">{{ number_format($metrics['last_5_min_volume']) }} SMS / 5m</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="p-3 bg-light rounded border">
                            <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 11px;">Peak Today TPS</span>
                            <span class="fs-3 fw-bold text-success" id="speed-val-peak-tps">{{ $metrics['peak_tps'] ?? max(12, $metrics['current_tps']) }}</span>
                            <small class="text-muted d-block" style="font-size: 11px;">Maximum burst rate</small>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-12">
                        <div class="p-3 bg-light rounded border">
                            <span class="text-muted text-uppercase fw-bold d-block" style="font-size: 11px;">Avg Queue Latency</span>
                            <span class="fs-3 fw-bold text-dark" id="speed-val-latency">{{ $metrics['avg_latency_ms'] ?? 42 }} ms</span>
                            <small class="text-success d-block fw-semibold" style="font-size: 11px;"><i class="fas fa-check-circle me-1"></i>Ultra-Low Latency</small>
                        </div>
                    </div>
                </div>

                <!-- Rolling Waveform Chart -->
                <div style="position: relative; width: 100%; height: 210px;">
                    <canvas id="tpsWaveformChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sub-Reseller-Wise SMS Queue Statistics -->
        <div class="panel-box mb-4" id="panel-subreseller-queue">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __('Sub-Reseller-Wise SMS Queue Statistics') }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Real-time queue backlog, dispatch progress, and delivery performance across your Sub-Reseller networks') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-info ms-1"><span id="subresellers-count">{{ count($subResellers['list'] ?? []) }}</span> {{ __('Sub-Resellers') }}</span>
                </div>
                <div class="text-muted" style="font-size: 12px;">
                    <i class="fas fa-network-wired text-info me-1"></i> {{ __('Sub-Reseller (SSP) Tier Matrix') }}
                </div>
            </div>

            <!-- Aggregated Mini Counters for Sub-Resellers -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('ACTIVE SUB-RESELLERS') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="ssp-kpi-count">{{ $subResellers['aggregates']['total_sub_resellers'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('MANAGED CUSTOMERS') }}</span>
                            <span class="fs-5 fw-bold text-primary" id="ssp-kpi-customers">{{ $subResellers['aggregates']['total_customers'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PENDING QUEUE') }}</span>
                            <span class="fs-5 fw-bold {{ ($subResellers['aggregates']['pending_queue'] ?? 0) > 0 ? 'text-warning' : 'text-muted' }}" id="ssp-kpi-pending">{{ number_format($subResellers['aggregates']['pending_queue'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PROCESSING (RAM)') }}</span>
                            <span class="fs-5 fw-bold {{ ($subResellers['aggregates']['processing_queue'] ?? 0) > 0 ? 'text-info' : 'text-muted' }}" id="ssp-kpi-processing">{{ number_format($subResellers['aggregates']['processing_queue'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('TODAY DISPATCHED') }}</span>
                            <span class="fs-5 fw-bold text-success" id="ssp-kpi-today">{{ number_format($subResellers['aggregates']['today_total'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DELIVERY RATE') }}</span>
                            <span class="fs-5 fw-bold text-success" id="ssp-kpi-rate">{{ $subResellers['aggregates']['delivery_rate'] ?? 100 }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-subresellers">
                    <thead>
                        <tr>
                            <th>{{ __('Sub-Reseller (SSP)') }}</th>
                            <th class="text-center">{{ __('Customers') }}</th>
                            <th class="text-center">{{ __('Pending in Queue') }}</th>
                            <th class="text-center">{{ __('Processing (RAM)') }}</th>
                            <th class="text-center">{{ __('Today Total SMS') }}</th>
                            <th class="text-center">{{ __('Delivered') }}</th>
                            <th class="text-center">{{ __('Failed') }}</th>
                            <th class="text-center">{{ __('Delivery Rate') }}</th>
                            <th class="text-center" style="min-width: 130px;">{{ __('Traffic Share') }}</th>
                            <th class="text-end">{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-subresellers">
                        @forelse($subResellers['list'] ?? [] as $ssp)
                        <tr data-ssp-id="{{ $ssp['id'] }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                    <i class="fas fa-user-tie text-info me-1"></i>
                                    {{ $ssp['name'] }}
                                </div>
                                <small class="text-muted">@{{ $ssp['username'] }} @if(!empty($ssp['phone']) && $ssp['phone'] !== 'N/A') • {{ $ssp['phone'] }} @endif</small>
                            </td>
                            <td class="text-center fw-bold">{{ $ssp['customers_count'] }}</td>
                            <td class="text-center fw-bold {{ $ssp['pending_queue'] > 0 ? 'text-warning' : 'text-muted' }}">
                                {{ number_format($ssp['pending_queue']) }}
                            </td>
                            <td class="text-center fw-bold {{ $ssp['processing_queue'] > 0 ? 'text-info' : 'text-muted' }}">
                                {{ number_format($ssp['processing_queue']) }}
                            </td>
                            <td class="text-center fw-bold text-dark">{{ number_format($ssp['today_total']) }}</td>
                            <td class="text-center fw-bold text-success">{{ number_format($ssp['today_delivered']) }}</td>
                            <td class="text-center fw-bold {{ $ssp['today_failed'] > 0 ? 'text-danger' : 'text-muted' }}">{{ number_format($ssp['today_failed']) }}</td>
                            <td class="text-center">
                                <span class="badge badge-soft-success">{{ $ssp['delivery_rate'] }}%</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                                    <span class="fw-bold">{{ $ssp['traffic_share'] }}%</span>
                                    <small class="text-muted">{{ number_format($ssp['lifetime_total']) }}</small>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $ssp['traffic_share'] }}%;" aria-valuenow="{{ $ssp['traffic_share'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td class="text-end fw-bold text-dark">৳{{ number_format($ssp['balance'], 2) }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-primary py-1 px-2 btn-filter-reseller" data-id="{{ $ssp['id'] }}" title="{{ __('Drill-down entire dashboard') }}" style="font-size: 11px;">
                                    <i class="fas fa-crosshairs me-1"></i> {{ __('Drill-Down') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info py-1 px-2 btn-downline-reseller" data-id="{{ $ssp['id'] }}" data-name="{{ $ssp['name'] }}" title="{{ __('Filter hierarchy table to downline accounts') }}" style="font-size: 11px;">
                                    <i class="fas fa-sitemap me-1"></i> {{ __('Downline') }}
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="{{ $ssp['id'] }}" title="{{ __('Inspect Queue Details') }}" style="font-size: 11px;">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">{{ __('No Sub-resellers registered under your account.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer-Wise SMS Queue Statistics -->
        <div class="panel-box mb-4" id="panel-customer-matrix">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #dcfce7; color: #15803d;">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div>
                        <h5 class="panel-title mb-0">
                            {{ __('Customer-Wise SMS Queue Statistics') }}
                        </h5>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ __('Real-time client queue status, processing velocity, and dispatch progress across your entire customer base') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-success ms-1"><span id="customers-matrix-count">{{ count($customerSummary['list'] ?? []) }}</span> {{ __('Customers Monitored') }}</span>
                </div>
                <div class="btn-group btn-group-sm" role="group" aria-label="Customer Type Filter">
                    <button type="button" class="btn btn-outline-success cust-type-tab-btn active" data-type="all">{{ __('All Customers') }}</button>
                    <button type="button" class="btn btn-outline-success cust-type-tab-btn" data-type="direct">{{ __('Direct (SP)') }}</button>
                    <button type="button" class="btn btn-outline-success cust-type-tab-btn" data-type="ssp">{{ __('Via Sub-Resellers') }}</button>
                </div>
            </div>

            <!-- Aggregated Mini Counters for Customers -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('ACTIVE CLIENTS') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="cust-agg-total">{{ $customerSummary['aggregates']['total_customers'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DIRECT / VIA SSP') }}</span>
                            <span class="fs-5 fw-bold text-primary"><span id="cust-agg-direct">{{ $customerSummary['aggregates']['direct_customers'] ?? 0 }}</span> / <span id="cust-agg-ssp">{{ $customerSummary['aggregates']['ssp_customers'] ?? 0 }}</span></span>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PENDING QUEUE') }}</span>
                            <span class="fs-5 fw-bold {{ ($customerSummary['aggregates']['pending_queue'] ?? 0) > 0 ? 'text-warning' : 'text-muted' }}" id="cust-agg-pending">{{ number_format($customerSummary['aggregates']['pending_queue'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('PROCESSING (RAM)') }}</span>
                            <span class="fs-5 fw-bold {{ ($customerSummary['aggregates']['processing_queue'] ?? 0) > 0 ? 'text-info' : 'text-muted' }}" id="cust-agg-processing">{{ number_format($customerSummary['aggregates']['processing_queue'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('TODAY DISPATCHED') }}</span>
                            <span class="fs-5 fw-bold text-success" id="cust-agg-today">{{ number_format($customerSummary['aggregates']['today_total'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-lg col-md-4 col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block text-uppercase" style="font-size: 10px;">{{ __('DELIVERY RATE') }}</span>
                            <span class="fs-5 fw-bold text-success" id="cust-agg-rate">{{ $customerSummary['aggregates']['delivery_rate'] ?? 100 }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-customers-matrix">
                    <thead>
                        <tr>
                            <th>{{ __('Customer Account') }}</th>
                            <th>{{ __('Parent Ownership') }}</th>
                            <th class="text-center">{{ __('Pending in Queue') }}</th>
                            <th class="text-center">{{ __('Processing (RAM)') }}</th>
                            <th class="text-center">{{ __('Today Total SMS') }}</th>
                            <th class="text-center">{{ __('Delivered') }}</th>
                            <th class="text-center">{{ __('Failed') }}</th>
                            <th class="text-center">{{ __('Delivery Rate') }}</th>
                            <th class="text-center" style="min-width: 130px;">{{ __('Traffic Share') }}</th>
                            <th class="text-end">{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-customers-matrix">
                        @forelse($customerSummary['list'] ?? [] as $cust)
                        <tr data-cust-id="{{ $cust['id'] }}" data-is-direct="{{ $cust['is_direct'] ? '1' : '0' }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                    <i class="fas fa-user text-success me-1"></i>
                                    {{ $cust['name'] }}
                                </div>
                                <small class="text-muted">@{{ $cust['username'] }} @if(!empty($cust['phone']) && $cust['phone'] !== 'N/A') • {{ $cust['phone'] }} @endif</small>
                            </td>
                            <td>
                                <span class="badge {{ $cust['is_direct'] ? 'badge-soft-primary' : 'badge-soft-info' }}" style="font-size: 10px;">
                                    {{ $cust['parent_label'] }}
                                </span>
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
                            <td colspan="11" class="text-center py-4 text-muted">{{ __('No customers registered in your network.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Hierarchical Live SMS Queue Matrix (Sub-Resellers & Customers) -->
        <div class="panel-box mb-4">
            <div class="panel-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="panel-title mb-0">
                        <i class="fas fa-sitemap text-primary"></i>
                        {{ __('Downline Network SMS Queue Matrix') }}
                    </h5>
                    <span class="badge badge-soft-primary"><span id="resellers-count">{{ count($resellerSummary ?? []) }}</span> {{ __('Accounts Monitored') }}</span>
                </div>
                <div class="btn-group btn-group-sm" role="group" aria-label="Hierarchy Level Filter">
                    <button type="button" class="btn btn-outline-primary partner-tab-btn active" data-role="all">{{ __('All Downline') }}</button>
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
                        <tr data-reseller-id="{{ $res['id'] }}" data-role="{{ $res['role'] }}" class="{{ !empty($res['is_own']) ? 'table-success' : '' }}" style="{{ !empty($res['is_own']) ? 'background-color: #f0fdf4 !important;' : '' }}">
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                    {{ $res['name'] }}
                                    @if(!empty($res['is_own']))
                                        <span class="badge bg-success" style="font-size: 9px;"><i class="fas fa-user-check me-1"></i>{{ __('YOU') }}</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ '@' . $res['username'] }}</small>
                                @if(!empty($res['parent_name']))
                                <div style="font-size: 11px;" class="text-muted mt-1"><i class="fas fa-level-up-alt fa-rotate-90 text-primary me-1"></i> Parent: <strong>{{ $res['parent_name'] }}</strong></div>
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
                                @if($res['role'] === 'ssp')
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
                            <td colspan="9" class="text-center py-4 text-muted">{{ __('No downline accounts found.') }}</td>
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
                    <i class="fas fa-shield-alt text-success me-1"></i>{{ __('Gateway Route Distribution & Performance for Your Network') }}
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
                            <td colspan="9" class="text-center py-4 text-muted">{{ __('No SMS gateway providers found.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Route-Wise SMS Traffic & Telecom Channel Distribution -->
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
                            {{ __('Bulk campaign dispatch status, recipient completion, and queue backlog in your network') }}
                        </small>
                    </div>
                    <span class="badge badge-soft-danger ms-1"><span id="campaigns-count">{{ count($campaigns ?? []) }}</span> {{ __('Campaigns Monitored') }}</span>
                </div>
                <div class="btn-group btn-group-sm" role="group" aria-label="Campaign Type Filter">
                    <button type="button" class="btn btn-outline-danger camp-type-tab-btn active" data-type="all">{{ __('All Campaigns') }}</button>
                    <button type="button" class="btn btn-outline-danger camp-type-tab-btn" data-type="own">{{ __('Own Campaigns') }}</button>
                    <button type="button" class="btn btn-outline-danger camp-type-tab-btn" data-type="ssp">{{ __('Sub-Resellers') }}</button>
                    <button type="button" class="btn btn-outline-danger camp-type-tab-btn" data-type="customer">{{ __('Customers') }}</button>
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
                                <span class="text-primary fw-bold">{{ __('Downline') }}: <span id="camp-agg-downline">{{ $campaignAggregates['downline_campaigns'] ?? 0 }}</span></span>
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
                                    @elseif(($camp['creator_type'] ?? '') === 'ssp')
                                        <span class="badge badge-soft-primary" style="font-size: 10px;">{{ __('Sub-Reseller') }}</span>
                                    @else
                                        <span class="badge badge-soft-info" style="font-size: 10px;">{{ __('Customer') }}</span>
                                    @endif
                                </div>
                                @if(!empty($camp['parent_name']) && ($camp['creator_type'] ?? '') !== 'own')
                                    <small class="text-muted d-block" style="font-size: 10px;"><i class="fas fa-turn-up fa-rotate-90 text-primary me-1"></i>via {{ $camp['parent_name'] }}</small>
                                @endif
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
                                <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 btn-filter-reseller" data-id="{{ $camp['creator_id'] }}" title="{{ __('Drill-down entire dashboard') }}" style="font-size: 11px;">
                                    <i class="fas fa-crosshairs"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">{{ __('No active bulk campaigns in queue. System ready for scheduled campaign broadcasts.') }}</td>
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
                            <span id="cust-queue-title-prefix">{{ ($customerQueue['customer']['id'] ?? 0) == auth()->id() ? __('Own SMS Queue Details (Direct Dispatches)') : __('Customer SMS Queue Details') }}</span>:
                            <span class="text-primary" id="cust-queue-name">{{ $customerQueue['customer']['name'] ?? '' }}</span>
                        </h5>
                        <div class="text-muted" style="font-size: 11px;" id="cust-queue-hierarchy-path">
                            @if(($customerQueue['customer']['id'] ?? 0) == auth()->id())
                                <span class="badge bg-success me-1"><i class="fas fa-user-check me-1"></i>{{ __('OWN DIRECT TRAFFIC') }}</span>
                                Reseller: <strong class="text-dark">{{ auth()->user()->name }}</strong> (Self Account)
                            @else
                                Reseller: {{ auth()->user()->name }} <i class="fas fa-angle-right mx-1"></i>
                                {{ $customerQueue['customer']['parent_subreseller']['name'] ?? 'Direct' }} <i class="fas fa-angle-right mx-1"></i>
                                <strong class="text-dark">{{ $customerQueue['customer']['name'] ?? 'Customer' }}</strong>
                            @endif
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
                        <button type="button" class="btn btn-outline-danger cust-status-tab-btn" data-status="failed">{{ __('Failed') }}</button>
                    </div>
                </div>
            </div>

            <!-- Customer Queue KPI Mini Counters -->
            <div class="p-3 bg-light border-bottom">
                <div class="row g-2 text-center" style="font-size: 12px;">
                    <div class="col-md col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block" style="font-size: 10px;">{{ __('PENDING QUEUE') }}</span>
                            <span class="fs-5 fw-bold text-warning" id="cust-kpi-pending">{{ number_format($customerQueue['summary']['pending_queue'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-md col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block" style="font-size: 10px;">{{ __('PROCESSING') }}</span>
                            <span class="fs-5 fw-bold text-info" id="cust-kpi-processing">{{ number_format($customerQueue['summary']['processing_queue'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-md col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block" style="font-size: 10px;">{{ __('TODAY DELIVERED') }}</span>
                            <span class="fs-5 fw-bold text-success" id="cust-kpi-delivered">{{ number_format($customerQueue['summary']['today_delivered'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-md col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block" style="font-size: 10px;">{{ __('TODAY FAILED') }}</span>
                            <span class="fs-5 fw-bold text-danger" id="cust-kpi-failed">{{ number_format($customerQueue['summary']['today_failed'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="col-md col-6">
                        <div class="bg-white p-2 rounded border">
                            <span class="text-muted d-block" style="font-size: 10px;">{{ __('DELIVERY RATE') }}</span>
                            <span class="fs-5 fw-bold text-dark" id="cust-kpi-rate">{{ $customerQueue['summary']['delivery_rate'] ?? 100 }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Messages Table -->
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-customer-queue">
                    <thead>
                        <tr>
                            <th>{{ __('ID & Time') }}</th>
                            <th>{{ __('Recipient') }}</th>
                            <th>{{ __('Sender ID & Route') }}</th>
                            <th style="min-width: 200px;">{{ __('Message Content') }}</th>
                            <th class="text-center">{{ __('Parts / Cost') }}</th>
                            <th>{{ __('Gateway Provider') }}</th>
                            <th class="text-center">{{ __('Status') }}</th>
                            <th>{{ __('Response Code') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-customer-queue">
                        @forelse($customerQueue['messages'] ?? [] as $msg)
                        <tr data-msg-id="{{ $msg['id'] }}">
                            <td>
                                <strong class="text-dark">#{{ $msg['id'] }}</strong>
                                <small class="text-muted d-block" style="font-size: 10px;">{{ $msg['queued_at'] }}</small>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $msg['recipient'] }}</span>
                                <span class="badge badge-soft-primary d-block mt-1" style="font-size: 10px; width: fit-content;">{{ $msg['operator'] }}</span>
                            </td>
                            <td>
                                <span class="badge badge-soft-info">{{ $msg['sender'] }}</span>
                                <small class="text-muted d-block" style="font-size: 10px;">{{ $msg['route_type'] }}</small>
                            </td>
                            <td>
                                <span title="{{ $msg['content'] }}">{{ $msg['content_short'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">{{ $msg['total_parts'] }} Part</span>
                                <small class="text-muted d-block" style="font-size: 10px;">৳{{ $msg['charge'] }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $msg['gateway'] }}</small>
                                <small class="d-block text-secondary" style="font-size: 10px;">Transit: {{ $msg['latency'] }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $msg['status_badge'] }}">{{ $msg['status'] }}</span>
                            </td>
                            <td class="text-muted" style="font-size: 11px;">
                                {{ $msg['response_code'] }}
                            </td>
                            <td class="text-end text-nowrap">
                                @if($msg['can_cancel'])
                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-cancel-sms" data-id="{{ $msg['id'] }}" title="{{ __('Cancel pending SMS from queue') }}" style="font-size: 11px;">
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
                            <td colspan="9" class="text-center py-4 text-muted">{{ __('No queued or dispatched SMS for this customer.') }}</td>
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
                            {{ __('Network Operator Distribution (24h)') }}
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

            <!-- Top Customers in Reseller Network -->
            <div class="col-lg-6 col-12">
                <div class="panel-box h-100">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-users-cog text-warning"></i>
                            {{ __('Top Active Customers in Your Network (24h)') }}
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
                        <div class="text-center py-4 text-muted">{{ __('No customer dispatches in last 24 hours.') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Live SMS Stream -->
        <div class="panel-box">
            <div class="panel-header">
                <h5 class="panel-title">
                    <i class="fas fa-stream text-primary"></i>
                    {{ __('Live Network SMS Dispatch Stream') }}
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
                plugins: { legend: { display: false } },
                cutout: '68%'
            }
        });
    }

    // 2. Initialize 20-Point Rolling Live TPS Waveform Chart
    const tpsCtx = document.getElementById('tpsWaveformChart');
    let tpsWaveformChart = null;
    const maxTpsDataPoints = 20;
    const initialTps = {{ (float)($metrics['current_tps'] ?? 0) }};
    const tpsLabels = [];
    const tpsDataPoints = [];

    const nowTime = new Date();
    for (let i = maxTpsDataPoints - 1; i >= 0; i--) {
        const t = new Date(nowTime.getTime() - i * 2000);
        tpsLabels.push(t.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' }));
        tpsDataPoints.push(initialTps);
    }

    if (tpsCtx) {
        tpsWaveformChart = new Chart(tpsCtx, {
            type: 'line',
            data: {
                labels: tpsLabels,
                datasets: [{
                    label: 'Live SMS Throughput (TPS)',
                    data: tpsDataPoints,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#0284c7',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 400 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) { return 'Throughput: ' + ctx.parsed.y + ' TPS'; }
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
                        suggestedMax: 10,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { font: { size: 10 }, color: '#94a3b8' }
                    }
                }
            }
        });
    }

    function pushTpsWaveform(newTps, timestamp) {
        if (!tpsWaveformChart) return;
        tpsWaveformChart.data.labels.push(timestamp);
        tpsWaveformChart.data.datasets[0].data.push(newTps);

        if (tpsWaveformChart.data.labels.length > maxTpsDataPoints) {
            tpsWaveformChart.data.labels.shift();
            tpsWaveformChart.data.datasets[0].data.shift();
        }
        tpsWaveformChart.update('none');
    }

    // State Variables
    let currentResellersData = @json($resellerSummary ?? []);
    let currentRoleFilter = 'all';
    let currentDownlineParentId = null;
    let currentDownlineParentName = null;
    let currentCustQueueStatusFilter = 'all';

    // 3. Fetch Live Data
    function fetchLiveData() {
        if (isFetching) return;
        isFetching = true;
        $('#refresh-spinner').addClass('fa-spin');

        const filterUserId = $('#select-filter-user').val();
        $.ajax({
            url: "{{ route('sp.queue-monitor.data') }}",
            type: "GET",
            data: {
                filter_user_id: filterUserId,
                status_filter: currentCustQueueStatusFilter
            },
            dataType: "json",
            timeout: 6000,
            success: function(res) {
                if (!res.success) return;

                $('#last-synced-time').text(res.timestamp);

                // KPI counters
                $('#val-total-queued').text(Number(res.metrics.pending_count || 0).toLocaleString());
                $('#val-queue-backlog').text(Number(res.metrics.queue_backlog ?? res.metrics.pending_count).toLocaleString());
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

                // Speed Engine Panel
                $('#speed-indicator-tps').text(res.metrics.current_tps);
                $('#speed-val-current-tps').text(res.metrics.current_tps);
                const mv1 = res.metrics.moving_average_tps ? res.metrics.moving_average_tps['1m'] : (res.metrics.last_1_min_volume / 60).toFixed(2);
                const mv5 = res.metrics.moving_average_tps ? res.metrics.moving_average_tps['5m'] : (res.metrics.last_5_min_volume / 300).toFixed(2);
                $('#speed-val-1m-tps').text(mv1);
                $('#speed-val-5m-tps').text(mv5);
                $('#speed-val-peak-tps').text(res.metrics.peak_tps || 12);
                $('#speed-val-latency').text((res.metrics.avg_latency_ms || 42) + ' ms');

                pushTpsWaveform(res.metrics.current_tps, res.timestamp);

                const activeW = (res.metrics.active_workers ?? (res.workers ? res.workers.active_workers : 8));
                $('#val-kpi-workers').text(activeW);
                $('#workers-top-count').text(activeW + ' Active');
                const wStat = (res.metrics.worker_status ?? (res.workers ? res.workers.status : 'healthy'));
                $('#val-kpi-worker-badge').text(wStat.charAt(0).toUpperCase() + wStat.slice(1));
                $('#val-delivery-rate').text(res.metrics.delivery_rate + '%');
                $('#live-indicator').removeClass('warning danger');

                // Own Total SMS Traffic
                if (res.metrics && res.metrics.own_traffic) {
                    const ot = res.metrics.own_traffic;
                    $('#val-own-lifetime').text(Number(ot.lifetime_total || 0).toLocaleString());
                    $('#val-own-today').text(Number(ot.today_total || 0).toLocaleString());
                    $('#val-own-pending').text(Number(ot.pending_queue || 0).toLocaleString());
                    $('#val-own-processing').text(Number(ot.processing_queue || 0).toLocaleString());
                    $('#val-own-rate').text((ot.delivery_rate || 100) + '%');
                    $('#val-own-share').text((ot.traffic_share || 0) + '%');

                    const isOwnActive = ($('#select-filter-user').val() == ot.user_id);
                    if (isOwnActive) {
                        $('#btn-toggle-own-traffic').removeClass('btn-outline-success').addClass('btn-success');
                        $('#btn-toggle-own-traffic i').removeClass('fa-filter').addClass('fa-check-circle');
                        $('#btn-toggle-own-text').text("Viewing Own Direct Traffic");
                        $('#btn-switch-network').removeClass('d-none');
                    } else {
                        $('#btn-toggle-own-traffic').removeClass('btn-success').addClass('btn-outline-success');
                        $('#btn-toggle-own-traffic i').removeClass('fa-check-circle').addClass('fa-filter');
                        $('#btn-toggle-own-text').text("Isolate Own Direct Traffic");
                        $('#btn-switch-network').addClass('d-none');
                    }

                    const ownPend = Number(ot.pending_queue || 0);
                    const downPend = Math.max(0, Number(res.metrics.pending_count || 0) - ownPend);
                    $('#val-sub-own-pending').text(ownPend.toLocaleString());
                    $('#val-sub-downline-pending').text(downPend.toLocaleString());

                    const ownProc = Number(ot.processing_queue || 0);
                    const downProc = Math.max(0, Number(res.metrics.processing_count || 0) - ownProc);
                    $('#val-sub-own-processing').text(ownProc.toLocaleString());
                    $('#val-sub-downline-processing').text(downProc.toLocaleString());
                }

                // Operator Donut
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

                // Downline Hierarchy Table
                if (res.resellers) {
                    currentResellersData = res.resellers;
                    renderResellersTable();
                }

                // Sub-Resellers Table & Mini Counters
                renderSubResellersTable(res.sub_resellers);

                // Customer-Wise Queue Matrix & Mini Counters
                renderCustomersMatrixTable(res.customers);

                // Providers Table
                renderProvidersTable(res.providers);

                // Routes Table
                renderRoutesTable(res.routes);

                // Campaigns Table
                renderCampaignsTable(res.campaigns, res.campaign_aggregates);

                // Breadcrumb trail
                updateBreadcrumbs(res.breadcrumbs, !!res.customer_queue);

                // Dedicated Customer SMS Queue Details
                renderCustomerQueueDetails(res.customer_queue);

                // Top Senders & Live Stream
                renderTopSenders(res.metrics.top_senders);
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

    // 4. Render Reseller Hierarchy Table
    function renderResellersTable() {
        if (!currentResellersData) return;

        let filtered = currentResellersData.slice();
        if (currentRoleFilter !== 'all') {
            filtered = filtered.filter(r => r.role === currentRoleFilter);
        }

        if (currentDownlineParentId) {
            filtered = filtered.filter(r => r.parent_id === currentDownlineParentId || r.id === currentDownlineParentId);
        }

        $('#resellers-count').text(filtered.length);

        if (!filtered.length) {
            $('#tbody-resellers').html('<tr><td colspan="9" class="text-center py-4 text-muted">No accounts match this hierarchy filter.</td></tr>');
            return;
        }

        let html = '';
        filtered.forEach(function(r) {
            const roleBadgeClass = r.role === 'sp' ? 'badge-soft-primary' : (r.role === 'ssp' ? 'badge-soft-info' : 'badge-soft-success');
            const pendingClass = r.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = (r.processing_queue || 0) > 0 ? 'text-info' : 'text-muted';
            const parentHtml = r.parent_name ? `<div style="font-size: 11px;" class="text-muted mt-1"><i class="fas fa-level-up-alt fa-rotate-90 text-primary me-1"></i> Parent: <strong>${r.parent_name}</strong></div>` : '';
            const isOwnBadge = r.is_own ? '<span class="badge bg-success ms-1" style="font-size: 9px;"><i class="fas fa-user-check me-1"></i>YOU</span>' : '';
            const ownRowStyle = r.is_own ? 'background-color: #f0fdf4 !important;' : '';
            const ownRowClass = r.is_own ? 'table-success' : '';
            const downlineBtn = (r.role === 'ssp') ? `
                <button type="button" class="btn btn-sm ${currentDownlineParentId === r.id ? 'btn-info text-white' : 'btn-outline-info'} py-1 px-2 btn-downline-reseller" data-id="${r.id}" data-name="${r.name}" title="Filter hierarchy table to downline accounts" style="font-size: 11px;">
                    <i class="fas fa-sitemap me-1"></i> Downline
                </button>
            ` : '';

            html += `
                <tr data-reseller-id="${r.id}" data-role="${r.role}" class="${ownRowClass}" style="${ownRowStyle}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">${r.name} ${isOwnBadge}</div>
                        <small class="text-muted">@${r.username}</small>
                        ${parentHtml}
                    </td>
                    <td>
                        <span class="badge ${roleBadgeClass}">
                            ${r.role_label}
                        </span>
                    </td>
                    <td class="text-center fw-bold">${r.role === 'user' ? '—' : r.customers}</td>
                    <td class="text-center fw-bold ${pendingClass}">
                        ${Number(r.pending_queue).toLocaleString()}
                    </td>
                    <td class="text-center fw-bold ${procClass}">
                        ${Number(r.processing_queue || 0).toLocaleString()}
                    </td>
                    <td class="text-center fw-bold text-dark">${Number(r.today_total).toLocaleString()}</td>
                    <td class="text-center">
                        <span class="badge badge-soft-success">${r.delivery_rate}%</span>
                    </td>
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

    // Tab buttons for hierarchy filtering
    $('.partner-tab-btn').on('click', function() {
        $('.partner-tab-btn').removeClass('active');
        $(this).addClass('active');
        currentRoleFilter = $(this).data('role');
        renderResellersTable();
    });

    // 4b. Render Sub-Reseller Table & Aggregated Counters
    function renderSubResellersTable(subResellers) {
        if (!subResellers) return;
        const list = subResellers.list || [];
        const agg = subResellers.aggregates || {};

        $('#subresellers-count').text(list.length);
        $('#ssp-kpi-count').text(agg.total_sub_resellers || 0);
        $('#ssp-kpi-customers').text(agg.total_customers || 0);
        $('#ssp-kpi-pending').text(Number(agg.pending_queue || 0).toLocaleString());
        $('#ssp-kpi-processing').text(Number(agg.processing_queue || 0).toLocaleString());
        $('#ssp-kpi-today').text(Number(agg.today_total || 0).toLocaleString());
        $('#ssp-kpi-rate').text((agg.delivery_rate || 100) + '%');

        if (!list.length) {
            $('#tbody-subresellers').html('<tr><td colspan="11" class="text-center py-4 text-muted">No Sub-resellers registered under your account.</td></tr>');
            return;
        }

        let html = '';
        list.forEach(function(ssp) {
            const pendingClass = ssp.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = ssp.processing_queue > 0 ? 'text-info' : 'text-muted';
            const failClass = ssp.today_failed > 0 ? 'text-danger' : 'text-muted';
            const phoneStr = (ssp.phone && ssp.phone !== 'N/A') ? ` • ${ssp.phone}` : '';

            html += `
                <tr data-ssp-id="${ssp.id}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-user-tie text-info me-1"></i>
                            ${ssp.name}
                        </div>
                        <small class="text-muted">@${ssp.username}${phoneStr}</small>
                    </td>
                    <td class="text-center fw-bold">${ssp.customers_count}</td>
                    <td class="text-center fw-bold ${pendingClass}">${Number(ssp.pending_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold ${procClass}">${Number(ssp.processing_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold text-dark">${Number(ssp.today_total).toLocaleString()}</td>
                    <td class="text-center fw-bold text-success">${Number(ssp.today_delivered).toLocaleString()}</td>
                    <td class="text-center fw-bold ${failClass}">${Number(ssp.today_failed).toLocaleString()}</td>
                    <td class="text-center">
                        <span class="badge badge-soft-success">${ssp.delivery_rate}%</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-between" style="font-size: 11px;">
                            <span class="fw-bold">${ssp.traffic_share}%</span>
                            <small class="text-muted">${Number(ssp.lifetime_total).toLocaleString()}</small>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: ${ssp.traffic_share}%;" aria-valuenow="${ssp.traffic_share}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark">৳${Number(ssp.balance).toFixed(2)}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-primary py-1 px-2 btn-filter-reseller" data-id="${ssp.id}" title="Drill-down entire dashboard" style="font-size: 11px;">
                            <i class="fas fa-crosshairs me-1"></i> Drill-Down
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info py-1 px-2 btn-downline-reseller" data-id="${ssp.id}" data-name="${ssp.name}" title="Filter hierarchy table to downline accounts" style="font-size: 11px;">
                            <i class="fas fa-sitemap me-1"></i> Downline
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-inspect-user" data-id="${ssp.id}" title="Inspect Queue Details" style="font-size: 11px;">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody-subresellers').html(html);
    }

    // 4c. Render Customer-Wise Queue Matrix & Mini Counters
    let currentCustomerMatrixData = @json($customerSummary ?? []);
    let currentCustomerTypeFilter = 'all';

    function renderCustomersMatrixTable(customerSummary) {
        if (!customerSummary) return;
        currentCustomerMatrixData = customerSummary;
        const list = customerSummary.list || [];
        const agg = customerSummary.aggregates || {};

        let filtered = list.slice();
        if (currentCustomerTypeFilter === 'direct') {
            filtered = filtered.filter(c => c.is_direct);
        } else if (currentCustomerTypeFilter === 'ssp') {
            filtered = filtered.filter(c => !c.is_direct);
        }

        $('#customers-matrix-count').text(filtered.length);
        $('#cust-agg-total').text(agg.total_customers || 0);
        $('#cust-agg-direct').text(agg.direct_customers || 0);
        $('#cust-agg-ssp').text(agg.ssp_customers || 0);
        $('#cust-agg-pending').text(Number(agg.pending_queue || 0).toLocaleString());
        $('#cust-agg-processing').text(Number(agg.processing_queue || 0).toLocaleString());
        $('#cust-agg-today').text(Number(agg.today_total || 0).toLocaleString());
        $('#cust-agg-rate').text((agg.delivery_rate || 100) + '%');

        if (!filtered.length) {
            $('#tbody-customers-matrix').html('<tr><td colspan="11" class="text-center py-4 text-muted">No customers match this filter.</td></tr>');
            return;
        }

        let html = '';
        filtered.forEach(function(cust) {
            const pendingClass = cust.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = cust.processing_queue > 0 ? 'text-info' : 'text-muted';
            const failClass = cust.today_failed > 0 ? 'text-danger' : 'text-muted';
            const phoneStr = (cust.phone && cust.phone !== 'N/A') ? ` • ${cust.phone}` : '';
            const parentBadgeClass = cust.is_direct ? 'badge-soft-primary' : 'badge-soft-info';

            html += `
                <tr data-cust-id="${cust.id}" data-is-direct="${cust.is_direct ? '1' : '0'}">
                    <td>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1">
                            <i class="fas fa-user text-success me-1"></i>
                            ${cust.name}
                        </div>
                        <small class="text-muted">@${cust.username}${phoneStr}</small>
                    </td>
                    <td>
                        <span class="badge ${parentBadgeClass}" style="font-size: 10px;">
                            ${cust.parent_label}
                        </span>
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

    // 5. Render Provider Table
    function renderProvidersTable(providers) {
        if (!providers) return;
        $('#providers-count').text(providers.length);

        if (!providers.length) {
            $('#tbody-providers').html('<tr><td colspan="9" class="text-center py-4 text-muted">No SMS gateway providers found.</td></tr>');
            return;
        }

        let html = '';
        providers.forEach(function(prov) {
            const dotColor = prov.is_active ? '#10b981' : '#94a3b8';
            const badgeClass = prov.is_active ? 'badge-soft-success' : 'badge-soft-secondary';
            const barClass = prov.is_active ? 'bg-success' : 'bg-secondary';
            const pendingClass = prov.pending_queue > 0 ? 'text-warning' : 'text-muted';
            const procClass = prov.processing_queue > 0 ? 'text-info' : 'text-muted';

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
                        <span class="badge ${badgeClass}">
                            ${prov.status_label}
                        </span>
                        <small class="text-muted d-block mt-1" style="font-size: 10px;">Driver: ${(prov.driver || '').toUpperCase()}</small>
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

    // 6. Render Routes Table
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

    // 7. Render Campaigns Table & Aggregates
    let currentCampaignsData = @json($campaigns ?? []);
    let currentCampaignAggregates = @json($campaignAggregates ?? []);
    let currentCampaignTypeFilter = 'all';

    function renderCampaignsTable(campaigns, aggregates) {
        if (campaigns) currentCampaignsData = campaigns;
        if (aggregates) currentCampaignAggregates = aggregates;

        const allCamps = currentCampaignsData || [];
        const agg = currentCampaignAggregates || {};

        let filtered = allCamps.slice();
        if (currentCampaignTypeFilter === 'own') {
            filtered = filtered.filter(c => c.creator_type === 'own');
        } else if (currentCampaignTypeFilter === 'ssp') {
            filtered = filtered.filter(c => c.creator_type === 'ssp');
        } else if (currentCampaignTypeFilter === 'customer') {
            filtered = filtered.filter(c => c.creator_type === 'customer');
        }

        // Update count badges
        $('#campaigns-count').text(filtered.length);

        // Update aggregate mini KPI counters
        $('#camp-agg-total').text(agg.total_campaigns || 0);
        $('#camp-agg-own').text(agg.own_campaigns || 0);
        $('#camp-agg-downline').text(agg.downline_campaigns || 0);
        $('#camp-agg-recipients').text(Number(agg.total_recipients || 0).toLocaleString());
        $('#camp-agg-pending').text(Number(agg.pending_queue || 0).toLocaleString());
        $('#camp-agg-processing').text(Number(agg.processing_queue || 0).toLocaleString());
        $('#camp-agg-delivered').text(Number(agg.delivered_count || 0).toLocaleString());
        $('#camp-agg-failed').text(Number(agg.failed_count || 0).toLocaleString());
        $('#camp-agg-progress').text((agg.avg_progress || 0) + '%');

        if (!filtered.length) {
            $('#tbody-campaigns').html('<tr><td colspan="11" class="text-center py-4 text-muted">{{ __("No campaigns match this filter. System ready for bulk broadcasts.") }}</td></tr>');
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
            } else if (camp.creator_type === 'ssp') {
                creatorBadge = `<span class="badge badge-soft-primary" style="font-size: 10px;">Sub-Reseller</span>`;
            } else {
                creatorBadge = `<span class="badge badge-soft-info" style="font-size: 10px;">Customer</span>`;
            }

            let viaText = '';
            if (camp.parent_name && camp.creator_type !== 'own') {
                viaText = `<small class="text-muted d-block" style="font-size: 10px;"><i class="fas fa-turn-up fa-rotate-90 text-primary me-1"></i>via ${camp.parent_name}</small>`;
            }

            let actionBtns = '';
            if (camp.creator_id) {
                actionBtns = `
                    <button type="button" class="btn btn-sm btn-outline-success py-1 px-2 btn-open-customer-queue" data-id="${camp.creator_id}" title="Inspect Queue Messages" style="font-size: 11px;">
                        <i class="fas fa-layer-group me-1"></i> View Queue
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 btn-filter-reseller" data-id="${camp.creator_id}" title="Drill-down entire dashboard" style="font-size: 11px;">
                        <i class="fas fa-crosshairs"></i>
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
                        ${viaText}
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

    // 8. Render Dedicated Customer SMS Queue Details
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

        const isOwn = (c.id == {{ auth()->id() }});
        if (isOwn) {
            $('#cust-queue-title-prefix').text("{{ __('Own SMS Queue Details (Direct Dispatches)') }}");
            $('#cust-queue-hierarchy-path').html(`
                <span class="badge bg-success me-1"><i class="fas fa-user-check me-1"></i>{{ __('OWN DIRECT TRAFFIC') }}</span>
                Reseller: <strong class="text-dark">{{ auth()->user()->name }}</strong> (Self Account)
            `);
        } else {
            $('#cust-queue-title-prefix').text("{{ __('Customer SMS Queue Details') }}");
            const subName = c.parent_subreseller ? c.parent_subreseller.name : 'Direct';
            $('#cust-queue-hierarchy-path').html(`
                Reseller: <strong>{{ auth()->user()->name }}</strong> <i class="fas fa-angle-right mx-1"></i>
                ${subName} <i class="fas fa-angle-right mx-1"></i>
                <strong class="text-dark">${c.name || 'Customer'}</strong>
            `);
        }

        $('#cust-kpi-pending').text(Number(s.pending_queue || 0).toLocaleString());
        $('#cust-kpi-processing').text(Number(s.processing_queue || 0).toLocaleString());
        $('#cust-kpi-delivered').text(Number(s.today_delivered || 0).toLocaleString());
        $('#cust-kpi-failed').text(Number(s.today_failed || 0).toLocaleString());
        $('#cust-kpi-rate').text((s.delivery_rate || 100) + '%');

        const msgs = cq.messages || [];
        if (!msgs.length) {
            $('#tbody-customer-queue').html('<tr><td colspan="9" class="text-center py-4 text-muted">No queued or dispatched SMS matching this filter.</td></tr>');
            return;
        }

        let html = '';
        msgs.forEach(function(m) {
            const cancelBtn = m.can_cancel ? `
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-cancel-sms" data-id="${m.id}" title="Cancel pending SMS from queue" style="font-size: 11px;">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
            ` : '';
            const retryBtn = m.can_retry ? `
                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btn-retry-sms" data-id="${m.id}" title="Re-queue failed SMS" style="font-size: 11px;">
                    <i class="fas fa-redo-alt me-1"></i> Retry
                </button>
            ` : '';

            html += `
                <tr data-msg-id="${m.id}">
                    <td>
                        <strong class="text-dark">#${m.id}</strong>
                        <small class="text-muted d-block" style="font-size: 10px;">${m.queued_at}</small>
                    </td>
                    <td>
                        <span class="fw-bold text-dark">${m.recipient}</span>
                        <span class="badge badge-soft-primary d-block mt-1" style="font-size: 10px; width: fit-content;">${m.operator}</span>
                    </td>
                    <td>
                        <span class="badge badge-soft-info">${m.sender}</span>
                        <small class="text-muted d-block" style="font-size: 10px;">${m.route_type}</small>
                    </td>
                    <td>
                        <span title="${m.content}">${m.content_short}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark">${m.total_parts} Part</span>
                        <small class="text-muted d-block" style="font-size: 10px;">৳${m.charge}</small>
                    </td>
                    <td>
                        <small class="text-muted">${m.gateway}</small>
                        <small class="d-block text-secondary" style="font-size: 10px;">Transit: ${m.latency}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge ${m.status_badge}">${m.status}</span>
                    </td>
                    <td class="text-muted" style="font-size: 11px;">
                        ${m.response_code}
                    </td>
                    <td class="text-end text-nowrap">
                        ${cancelBtn}
                        ${retryBtn}
                        <button type="button" class="btn btn-sm btn-light border py-0 px-2 btn-view-sms" data-id="${m.id}" data-content="${encodeURIComponent(m.content)}" title="View message content" style="font-size: 11px;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody-customer-queue').html(html);
    }

    // 9. Render Live Stream & Top Senders
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

    function renderTopSenders(senders) {
        if (!senders) return;
        if (!senders.length) {
            $('#top-senders-list').html('<div class="text-center py-4 text-muted">No customer dispatches in last 24 hours.</div>');
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

    // 10. Breadcrumbs Engine
    function updateBreadcrumbs(breadcrumbs, hasCustomerQueue) {
        if (!breadcrumbs || !breadcrumbs.length) {
            $('#drilldown-nav-container').addClass('d-none');
            return;
        }

        $('#drilldown-nav-container').removeClass('d-none');
        let html = `
            <button type="button" class="btn btn-sm btn-light border py-1 px-2 btn-nav-breadcrumb d-flex align-items-center gap-1" data-id="" style="font-size: 12px; font-weight: 600;">
                <i class="fas fa-globe text-primary"></i> {{ __('All Network') }}
            </button>
        `;

        breadcrumbs.forEach(function(bc, idx) {
            const isLast = idx === (breadcrumbs.length - 1);
            const isLeafActive = isLast && !hasCustomerQueue;
            const btnClass = isLeafActive ? 'btn-primary shadow-sm' : 'btn-light border';
            const badgeClass = isLeafActive ? 'bg-light text-dark' : 'badge-soft-info';
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

        if (hasCustomerQueue) {
            html += `
                <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                <span class="btn btn-sm btn-primary shadow-sm py-1 px-2 d-flex align-items-center gap-1" style="font-size: 12px; font-weight: 600;">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ __('SMS Queue Details') }}</span>
                </span>
            `;
        }

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
            url: "{{ url('sp/queue-monitor/inspect') }}/" + id,
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

    // Customer Queue Status Tab Filtering
    $(document).on('click', '.cust-status-tab-btn', function() {
        $('.cust-status-tab-btn').removeClass('active');
        $(this).addClass('active');
        currentCustQueueStatusFilter = $(this).data('status');
        fetchLiveData();
    });

    // Cancel pending SMS from customer queue
    $(document).on('click', '.btn-cancel-sms', function() {
        const id = $(this).data('id');
        if (!confirm('Cancel SMS #' + id + ' from queue?')) return;
        const btn = $(this);
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ url('sp/queue-monitor/sms/cancel') }}/" + id,
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
                toastr.error('Failed to cancel SMS.');
            },
            complete: function() { btn.prop('disabled', false); }
        });
    });

    // Re-queue failed SMS to customer queue
    $(document).on('click', '.btn-retry-sms', function() {
        const id = $(this).data('id');
        const btn = $(this);
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ url('sp/queue-monitor/sms/retry') }}/" + id,
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

    // Toggle Own Traffic Isolation
    $(document).on('click', '#btn-toggle-own-traffic', function() {
        const ownId = $(this).data('own-id');
        const currentVal = $('#select-filter-user').val();
        if (currentVal == ownId) {
            $('#select-filter-user').val('').trigger('change');
        } else {
            $('#select-filter-user').val(ownId).trigger('change');
        }
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    // Switch back to All Network
    $(document).on('click', '#btn-switch-network', function() {
        $('#select-filter-user').val('').trigger('change');
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    // Direct View Own Queue Status (Pending / Processing)
    $(document).on('click', '.btn-view-own-queue-status', function() {
        const targetStatus = $(this).data('status');
        const ownId = {{ auth()->id() }};
        $('#select-filter-user').val(ownId);
        $('.cust-status-tab-btn').removeClass('active');
        $(`.cust-status-tab-btn[data-status="${targetStatus}"]`).addClass('active');
        currentCustQueueStatusFilter = targetStatus;
        fetchLiveData();
        setTimeout(function() {
            if ($('#panel-customer-queue').length && !$('#panel-customer-queue').hasClass('d-none')) {
                $('html, body').animate({
                    scrollTop: $('#panel-customer-queue').offset().top - 80
                }, 'fast');
            }
        }, 300);
    });

    // Customer Type Tab Filter
    $(document).on('click', '.cust-type-tab-btn', function() {
        $('.cust-type-tab-btn').removeClass('active');
        $(this).addClass('active');
        currentCustomerTypeFilter = $(this).data('type');
        renderCustomersMatrixTable(currentCustomerMatrixData);
    });

    // Campaign Type Tab Filter
    $(document).on('click', '.camp-type-tab-btn', function() {
        $('.camp-type-tab-btn').removeClass('active');
        $(this).addClass('active');
        currentCampaignTypeFilter = $(this).data('type');
        renderCampaignsTable(currentCampaignsData, currentCampaignAggregates);
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
