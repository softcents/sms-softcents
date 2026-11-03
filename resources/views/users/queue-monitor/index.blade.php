@extends('layouts.users.master')

@section('title', __('Live SMS Monitoring'))

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
                        <span class="badge badge-soft-success ms-1" style="font-size: 11px;">REAL-TIME</span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3 mt-1" style="font-size: 13px; color: #64748b;">
                        <span><i class="fas fa-user-circle me-1"></i> Account: <strong>{{ auth()->user()->name }}</strong> ({{ '@' . (auth()->user()->username ?? auth()->user()->name) }})</span>
                        <span>•</span>
                        <span><i class="fas fa-broadcast-tower me-1"></i> Engine: <strong class="text-success">Active & Dispatching</strong></span>
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
                            <div class="kpi-val text-warning" id="val-processing-count">{{ number_format($metrics['processing_count'] ?? 0) }}</div>
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

            <!-- 4. Real-Time TPS -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Dispatch Throughput') }}</div>
                            <div class="kpi-val text-info"><span id="val-tps">{{ $metrics['current_tps'] }}</span> <small style="font-size: 13px; font-weight: 600;">TPS</small></div>
                        </div>
                        <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>1m: <strong id="val-1m">{{ number_format($metrics['last_1_min_volume']) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 4. Today's Dispatched Volume -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Today Total Sent') }}</div>
                            <div class="kpi-val text-dark" id="val-today-total">{{ number_format($metrics['today_total']) }}</div>
                        </div>
                        <div class="kpi-icon" style="background: #f1f5f9; color: #334155;">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Delivered: <strong id="val-today-success" class="text-success">{{ number_format($metrics['today_success']) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 5. Delivery Rate -->
            <div class="col-xl col-md-4 col-sm-6 col-12">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-label">{{ __('Delivery Success Rate') }}</div>
                            <div class="kpi-val text-success"><span id="val-delivery-rate">{{ $metrics['delivery_rate'] }}%</span></div>
                        </div>
                        <div class="kpi-icon" style="background: #dcfce7; color: #16a34a;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="kpi-sub">
                        <span>Failed: <span id="val-today-failed" class="text-danger">{{ number_format($metrics['today_failed']) }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Section: Operator Distribution & Quick Actions -->
        <div class="row g-4 mb-4">
            <!-- Operator Breakdown Donut Chart -->
            <div class="col-lg-6 col-12">
                <div class="panel-box h-100">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-chart-pie text-success"></i>
                            {{ __('Recipient Operator Breakdown (24h)') }}
                        </h5>
                    </div>
                    <div class="p-3 d-flex flex-column align-items-center justify-content-center" style="min-height: 220px;">
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

            <!-- Live Campaign Queue & Progress Tracker -->
            <div class="col-lg-6 col-12">
                <div class="panel-box h-100">
                    <div class="panel-header">
                        <h5 class="panel-title">
                            <i class="fas fa-tasks text-primary"></i>
                            {{ __('Campaign Dispatch & Queue Tracker') }}
                        </h5>
                        <span class="badge badge-soft-info" id="active-campaigns-badge">{{ count($metrics['active_campaigns'] ?? []) }} {{ __('Recent') }}</span>
                    </div>
                    <div class="p-3" id="active-campaigns-container">
                        @forelse($metrics['active_campaigns'] ?? [] as $camp)
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fw-bold text-dark fs-6">{{ $camp['name'] }}</div>
                                <span class="badge {{ $camp['pending'] > 0 ? 'badge-soft-warning' : 'badge-soft-success' }}">
                                    {{ $camp['pending'] > 0 ? __('In-Queue: ') . number_format($camp['pending']) : __('Completed') }}
                                </span>
                            </div>

                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $camp['progress_percent'] }}%;" aria-valuenow="{{ $camp['progress_percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                <span>Sent: <strong class="text-success">{{ number_format($camp['success']) }}</strong> / {{ number_format($camp['total']) }} ({{ $camp['progress_percent'] }}%)</span>
                                <span>ETA: <strong class="text-primary">{{ $camp['eta'] }}</strong></span>
                            </div>
                        </div>
                        @empty
                        <div class="p-4 d-flex flex-column justify-content-center text-center h-100">
                            <i class="fas fa-check-circle text-success fs-1 mb-2"></i>
                            <h6 class="fw-bold text-dark">{{ __('No Active Campaigns in Queue') }}</h6>
                            <p class="text-muted mb-3" style="font-size: 13px;">
                                {{ __('All your scheduled campaigns have finished processing. Start a new campaign or send quick messages at any time.') }}
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('users.quick-sms.index') }}" class="btn btn-outline-primary btn-sm px-3 py-1">
                                    <i class="fas fa-paper-plane me-1"></i> {{ __('Send Quick SMS') }}
                                </a>
                                <a href="{{ route('users.bulk-sms.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-1">
                                    <i class="fas fa-mail-bulk me-1"></i> {{ __('Launch Bulk Campaign') }}
                                </a>
                            </div>
                        </div>
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
                    {{ __('Live Outgoing SMS Stream') }}
                </h5>
                <span class="badge badge-soft-success"><i class="fas fa-satellite-dish me-1"></i> {{ __('Live Stream') }}</span>
            </div>
            <div class="table-responsive">
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

        $.ajax({
            url: "{{ route('users.queue-monitor.data') }}",
            type: "GET",
            dataType: "json",
            timeout: 5000,
            success: function(res) {
                if (!res.success) return;

                $('#last-synced-time').text(res.timestamp);

                // KPI
                $('#val-queue-backlog').text(res.metrics.pending_count.toLocaleString());
                $('#val-pending-count').text(res.metrics.pending_count.toLocaleString());
                $('#val-processing-count').text((res.metrics.processing_count || 0).toLocaleString());
                $('#val-tps').text(res.metrics.current_tps);
                $('#val-1m').text(res.metrics.last_1_min_volume.toLocaleString());
                $('#val-5m').text(res.metrics.last_5_min_volume.toLocaleString());
                $('#val-today-total').text(res.metrics.today_total.toLocaleString());
                $('#val-today-success').text(res.metrics.today_success.toLocaleString());
                $('#val-today-failed').text(res.metrics.today_failed.toLocaleString());
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

                // Active Campaigns
                renderActiveCampaigns(res.metrics.active_campaigns);

                // Live Stream
                renderStreamTable(res.metrics.live_stream);
            },
            complete: function() {
                isFetching = false;
                $('#refresh-spinner').removeClass('fa-spin');
            }
        });
    }

    function renderActiveCampaigns(campaigns) {
        if (!campaigns || !campaigns.length) {
            $('#active-campaigns-badge').text('0 Recent');
            $('#active-campaigns-container').html(`
                <div class="p-4 d-flex flex-column justify-content-center text-center h-100">
                    <i class="fas fa-check-circle text-success fs-1 mb-2"></i>
                    <h6 class="fw-bold text-dark">No Active Campaigns in Queue</h6>
                    <p class="text-muted mb-3" style="font-size: 13px;">
                        All your scheduled campaigns have finished processing. Start a new campaign or send quick messages at any time.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('users.quick-sms.index') }}" class="btn btn-outline-primary btn-sm px-3 py-1">
                            <i class="fas fa-paper-plane me-1"></i> Send Quick SMS
                        </a>
                        <a href="{{ route('users.bulk-sms.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-1">
                            <i class="fas fa-mail-bulk me-1"></i> Launch Bulk Campaign
                        </a>
                    </div>
                </div>
            `);
            return;
        }

        $('#active-campaigns-badge').text(campaigns.length + ' Recent');
        let html = '';
        campaigns.forEach(function(c) {
            const badgeClass = c.pending > 0 ? 'badge-soft-warning' : 'badge-soft-success';
            const badgeText = c.pending > 0 ? 'In-Queue: ' + Number(c.pending).toLocaleString() : 'Completed';

            html += `
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="fw-bold text-dark fs-6">${c.name}</div>
                        <span class="badge ${badgeClass}">${badgeText}</span>
                    </div>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: ${c.progress_percent}%;" aria-valuenow="${c.progress_percent}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                        <span>Sent: <strong class="text-success">${Number(c.success).toLocaleString()}</strong> / ${Number(c.total).toLocaleString()} (${c.progress_percent}%)</span>
                        <span>ETA: <strong class="text-primary">${c.eta}</strong></span>
                    </div>
                </div>
            `;
        });
        $('#active-campaigns-container').html(html);
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

    startPolling();
});
</script>
@endpush
