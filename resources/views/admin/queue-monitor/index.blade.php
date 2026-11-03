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

        <!-- 5 KPI Cards Row -->
        <div class="row g-3 mb-4">
            <!-- 1. Queue Backlog -->
            <div class="col-xl col-md-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Queue Backlog') }}</div>
                            <div class="kpi-val text-primary" id="val-queue-backlog">{{ number_format($broker['ready_messages'] + $metrics['pending_count']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #e0e7ff; color: #4f46e5;">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>RabbitMQ Ready: <strong id="val-rmq-ready">{{ number_format($broker['ready_messages']) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 2. In-Flight Processing -->
            <div class="col-xl col-md-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('In-Flight / Processing') }}</div>
                            <div class="kpi-val text-warning" id="val-unack">{{ number_format($broker['unack_messages']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                            <i class="fas fa-running"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>{{ __('Currently in worker RAM') }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. Real-Time TPS -->
            <div class="col-xl col-md-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Live Throughput') }}</div>
                            <div class="kpi-val text-info"><span id="val-tps">{{ $metrics['current_tps'] }}</span> <small style="font-size: 14px; font-weight: 600;">TPS</small></div>
                        </div>
                        <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>1m: <strong id="val-1m">{{ number_format($metrics['last_1_min_volume']) }}</strong> • 5m: <strong id="val-5m">{{ number_format($metrics['last_5_min_volume']) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 4. Today's Dispatched & Rate -->
            <div class="col-xl col-md-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Today Delivery') }}</div>
                            <div class="kpi-val text-success"><span id="val-delivery-rate">{{ $metrics['delivery_rate'] }}%</span></div>
                        </div>
                        <div class="kpi-icon" style="background: #dcfce7; color: #16a34a;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Total: <strong id="val-today-total">{{ number_format($metrics['today_total']) }}</strong> (Fail: <span id="val-today-failed" class="text-danger">{{ number_format($metrics['today_failed']) }}</span>)</span>
                    </div>
                </div>
            </div>

            <!-- 5. Active Workers Pool -->
            <div class="col-xl col-md-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Worker Pool') }}</div>
                            <div class="kpi-val" id="val-workers-status" style="color: #059669;">{{ $workers['active_workers'] }}/{{ $workers['expected_workers'] }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #d1fae5; color: #059669;">
                            <i class="fas fa-cogs"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Cluster: <strong id="val-cluster-name">{{ $broker['cluster_name'] }}</strong></span>
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

            <!-- Operator Breakdown & Live TPS Timeline -->
            <div class="col-lg-4 col-12">
                <div class="panel-box h-100">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-chart-line text-info"></i>
                            {{ __('Live Throughput (TPS)') }}
                        </h5>
                        <span class="badge badge-soft-info" id="live-tps-badge">{{ $metrics['current_tps'] }} TPS</span>
                    </div>
                    <div class="p-3">
                        <div style="position: relative; width: 100%; height: 110px;">
                            <canvas id="tpsChart"></canvas>
                        </div>
                    </div>

                    <div class="panel-header border-top" style="background: #f8fafc;">
                        <h5 class="panel-title" style="font-size: 13px;">
                            <i class="fas fa-chart-pie text-success"></i>
                            {{ __('Operator Distribution (24h)') }}
                        </h5>
                    </div>
                    <div class="p-3 d-flex flex-column align-items-center justify-content-center">
                        <div style="position: relative; width: 100%; max-width: 170px; height: 130px;">
                            <canvas id="operatorChart"></canvas>
                        </div>
                        <div class="d-flex flex-wrap justify-content-center gap-2 mt-2 w-100" style="font-size: 11px;" id="operator-legend">
                            <span class="d-flex align-items-center gap-1"><span style="width: 8px; height: 8px; border-radius: 2px; background: #0ea5e9;"></span> GP: <strong id="leg-gp">{{ $metrics['operators']['GP'] }}</strong></span>
                            <span class="d-flex align-items-center gap-1"><span style="width: 8px; height: 8px; border-radius: 2px; background: #f97316;"></span> BL: <strong id="leg-bl">{{ $metrics['operators']['BL'] }}</strong></span>
                            <span class="d-flex align-items-center gap-1"><span style="width: 8px; height: 8px; border-radius: 2px; background: #e11d48;"></span> Robi: <strong id="leg-robi">{{ $metrics['operators']['Robi'] }}</strong></span>
                            <span class="d-flex align-items-center gap-1"><span style="width: 8px; height: 8px; border-radius: 2px; background: #16a34a;"></span> TT: <strong id="leg-tt">{{ $metrics['operators']['Teletalk'] }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resellers & Sub-Resellers Hierarchy Queue Matrix -->
        <div class="panel-box mb-4">
            <div class="panel-header">
                <h5 class="panel-title">
                    <i class="fas fa-sitemap text-primary"></i>
                    {{ __('Reseller & Sub-Reseller Network Queue Matrix') }}
                </h5>
                <span class="badge badge-soft-primary"><span id="resellers-count">{{ count($resellerSummary ?? []) }}</span> {{ __('Partners Monitored') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-modern align-middle" id="table-resellers">
                    <thead>
                        <tr>
                            <th>{{ __('Partner Name & Username') }}</th>
                            <th>{{ __('Hierarchy Level') }}</th>
                            <th class="text-center">{{ __('Active Customers') }}</th>
                            <th class="text-center">{{ __('Pending in Queue') }}</th>
                            <th class="text-center">{{ __('Today Total SMS') }}</th>
                            <th class="text-center">{{ __('Delivery Rate') }}</th>
                            <th class="text-end">{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-resellers">
                        @forelse($resellerSummary ?? [] as $res)
                        <tr data-reseller-id="{{ $res['id'] }}">
                            <td>
                                <div class="fw-bold text-dark">{{ $res['name'] }}</div>
                                <small class="text-muted">{{ '@' . $res['username'] }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $res['role'] === 'sp' ? 'badge-soft-primary' : 'badge-soft-info' }}">
                                    {{ $res['role_label'] }}
                                </span>
                            </td>
                            <td class="text-center fw-bold">{{ $res['customers'] }}</td>
                            <td class="text-center fw-bold {{ $res['pending_queue'] > 0 ? 'text-warning' : 'text-muted' }}">
                                {{ number_format($res['pending_queue']) }}
                            </td>
                            <td class="text-center fw-bold text-dark">{{ number_format($res['today_total']) }}</td>
                            <td class="text-center">
                                <span class="badge badge-soft-success">{{ $res['delivery_rate'] }}%</span>
                            </td>
                            <td class="text-end fw-bold text-dark">৳{{ number_format($res['balance'], 2) }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 btn-filter-reseller" data-id="{{ $res['id'] }}" style="font-size: 11px;">
                                    <i class="fas fa-filter me-1"></i> {{ __('Filter Traffic') }}
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">{{ __('No resellers or sub-resellers registered yet.') }}</td>
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
    let tpsLabels = ['-14s', '-12s', '-10s', '-8s', '-6s', '-4s', '-2s', 'Now'];
    let tpsData = [0, 0, 0, 0, 0, 0, 0, {{ (float)($metrics['current_tps'] ?? 0) }}];

    if (tpsCtx) {
        tpsChart = new Chart(tpsCtx, {
            type: 'line',
            data: {
                labels: tpsLabels,
                datasets: [{
                    label: 'TPS',
                    data: tpsData,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 2,
                    pointHoverRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: {
                        beginAtZero: true,
                        ticks: { font: { size: 9 }, maxTicksLimit: 4 }
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
                const totalBacklog = (res.broker.ready_messages || 0) + (res.metrics.pending_count || 0);
                $('#val-queue-backlog').text(totalBacklog.toLocaleString());
                $('#val-rmq-ready').text(res.broker.ready_messages.toLocaleString());
                $('#val-unack').text(res.broker.unack_messages.toLocaleString());
                $('#val-tps').text(res.metrics.current_tps);
                $('#val-1m').text(res.metrics.last_1_min_volume.toLocaleString());
                $('#val-5m').text(res.metrics.last_5_min_volume.toLocaleString());
                $('#val-delivery-rate').text(res.metrics.delivery_rate + '%');
                $('#val-today-total').text(res.metrics.today_total.toLocaleString());
                $('#val-today-failed').text(res.metrics.today_failed.toLocaleString());

                // Update Queues Table
                renderQueuesTable(res.queues);

                // Update Resellers Hierarchy Matrix Table
                renderResellersTable(res.resellers);

                // Update TPS Line Chart
                if (tpsChart) {
                    $('#live-tps-badge').text(res.metrics.current_tps + ' TPS');
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

    function renderResellersTable(resellers) {
        if (!resellers) return;
        $('#resellers-count').text(resellers.length);

        if (!resellers.length) {
            $('#tbody-resellers').html('<tr><td colspan="8" class="text-center py-4 text-muted">No resellers or sub-resellers registered yet.</td></tr>');
            return;
        }

        let html = '';
        resellers.forEach(function(r) {
            const roleBadge = r.role === 'sp' ? 'badge-soft-primary' : 'badge-soft-info';
            const pendingClass = r.pending_queue > 0 ? 'text-warning' : 'text-muted';

            html += `
                <tr data-reseller-id="${r.id}">
                    <td>
                        <div class="fw-bold text-dark">${r.name}</div>
                        <small class="text-muted">@${r.username}</small>
                    </td>
                    <td><span class="badge ${roleBadge}">${r.role_label}</span></td>
                    <td class="text-center fw-bold">${r.customers}</td>
                    <td class="text-center fw-bold ${pendingClass}">${Number(r.pending_queue).toLocaleString()}</td>
                    <td class="text-center fw-bold text-dark">${Number(r.today_total).toLocaleString()}</td>
                    <td class="text-center"><span class="badge badge-soft-success">${r.delivery_rate}%</span></td>
                    <td class="text-end fw-bold text-dark">৳${Number(r.balance).toFixed(2)}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 btn-filter-reseller" data-id="${r.id}" style="font-size: 11px;">
                            <i class="fas fa-filter me-1"></i> Filter Traffic
                        </button>
                    </td>
                </tr>
            `;
        });
        $('#tbody-resellers').html(html);
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

    $(document).on('click', '.btn-filter-reseller', function() {
        const id = $(this).data('id');
        $('#select-filter-user').val(id).trigger('change');
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    $('#select-filter-user').on('change', function() {
        fetchLiveData();
    });

    // Start Poller
    startPolling();
});
</script>
@endpush
