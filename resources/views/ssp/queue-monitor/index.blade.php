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
                        <span><i class="fas fa-microchip me-1"></i> Worker Engine: <strong class="text-success">{{ $workers['active_workers'] }} Active</strong></span>
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
                    <div style="min-width: 200px;">
                        <select class="form-select form-select-sm" id="select-filter-user" style="font-size: 12px; font-weight: 600;">
                            <option value="">{{ __('🌐 All Client Traffic') }}</option>
                            @foreach($downlineUsers ?? [] as $u)
                                <option value="{{ $u['id'] }}" {{ ($filterUserId ?? null) == $u['id'] ? 'selected' : '' }}>
                                    {{ $u['username'] ?? $u['name'] }}
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

        <!-- 5 KPI Cards Row -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Queued SMS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Total Queued SMS') }}</div>
                            <div class="kpi-val text-primary" id="val-queue-backlog">{{ number_format($metrics['pending_count']) }}</div>
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
        <div class="panel-box">
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
@endsection

@push('js')
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    let pollingInterval = 2000;
    let pollTimer = null;
    let isFetching = false;

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
            data: { filter_user_id: filterUserId },
            dataType: "json",
            timeout: 5000,
            success: function(res) {
                if (!res.success) return;

                $('#last-synced-time').text(res.timestamp);

                // KPI
                $('#val-queue-backlog').text(res.metrics.pending_count.toLocaleString());
                $('#val-pending-count').text(res.metrics.pending_count.toLocaleString());
                $('#val-processing-count').text((res.metrics.processing_count || 0).toLocaleString());
                $('#val-submitted-count').text((res.metrics.today_submitted || 0).toLocaleString());
                $('#val-today-submitted').text((res.metrics.today_submitted || 0).toLocaleString());
                $('#val-total-submitted').text((res.metrics.submitted_count || 0).toLocaleString());
                $('#val-delivered-count').text((res.metrics.today_delivered || 0).toLocaleString());
                $('#val-today-delivered').text((res.metrics.today_delivered || 0).toLocaleString());
                $('#val-total-delivered').text((res.metrics.delivered_count || 0).toLocaleString());
                $('#val-tps').text(res.metrics.current_tps);
                $('#val-1m').text(res.metrics.last_1_min_volume.toLocaleString());
                $('#val-5m').text(res.metrics.last_5_min_volume.toLocaleString());
                $('#val-today-total').text(res.metrics.today_total.toLocaleString());
                $('#val-today-success').text(res.metrics.today_success.toLocaleString());
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
                $('#val-delivery-rate').text(res.metrics.delivery_rate + '%');

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

                // Top Senders
                renderTopSenders(res.metrics.top_senders);

                // Live Stream
                renderStreamTable(res.metrics.live_stream);
            },
            complete: function() {
                isFetching = false;
                $('#refresh-spinner').removeClass('fa-spin');
            }
        });
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

    function renderTopSenders(senders) {
        if (!senders) return;
        if (!senders.length) {
            $('#top-senders-list').html('<div class="text-center py-4 text-muted">No customer traffic recorded in last 24 hours.</div>');
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
        $('#select-filter-user').on('change', function() {
        fetchLiveData();
    });

    startPolling();
    });

    $('#btn-refresh-now').on('click', function() {
        fetchLiveData();
    });

    $('#select-filter-user').on('change', function() {
        fetchLiveData();
    });

    startPolling();
});
</script>
@endpush
