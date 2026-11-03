@extends('layouts.ssp.master')

@section('title', __('Advanced Traffic & Delivery Reports'))

@push('css')
<style>
    /* Reset custom.css 100vh card issue for this page */
    .erp-advanced-report-section .card,
    .erp-advanced-report-section .cards,
    .erp-advanced-report-section .couter-box {
        min-height: auto !important;
        height: auto;
    }

    .erp-advanced-report-section {
        margin-top: 20px;
        margin-bottom: 40px;
        font-family: "Manrope", sans-serif;
    }

    /* Page Header */
    .erp-advanced-report-section .page-header-wrap {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 22px;
        box-shadow: 0px 4px 20px rgba(195, 208, 228, 0.25);
        margin-bottom: 20px;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    /* Quick Range Bar */
    .erp-advanced-report-section .quick-preset-bar {
        background: #ffffff;
        border-radius: 12px;
        padding: 12px 18px;
        box-shadow: 0px 2px 14px rgba(195, 208, 228, 0.2);
        margin-bottom: 20px;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .erp-advanced-report-section .quick-preset-scroll {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
        padding-bottom: 4px;
    }

    .erp-advanced-report-section .quick-preset-scroll::-webkit-scrollbar {
        height: 4px;
    }

    .erp-advanced-report-section .quick-preset-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .erp-advanced-report-section .preset-pill {
        border-radius: 20px;
        padding: 5px 14px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    /* Filter Form */
    .erp-advanced-report-section .filter-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: 0px 2px 14px rgba(195, 208, 228, 0.2);
        margin-bottom: 24px;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .erp-advanced-report-section .form-label-custom {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }

    /* KPI Summary Cards */
    .erp-advanced-report-section .kpi-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0px 4px 20px rgba(195, 208, 228, 0.25);
        border: 1px solid rgba(0, 0, 0, 0.04);
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .erp-advanced-report-section .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0px 6px 24px rgba(195, 208, 228, 0.35);
    }

    .erp-advanced-report-section .kpi-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .erp-advanced-report-section .kpi-card-title {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        line-height: 1.2;
    }

    .erp-advanced-report-section .kpi-icon-pill {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .erp-advanced-report-section .kpi-value {
        font-size: clamp(1.15rem, 2.2vw, 1.45rem);
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 6px;
        word-break: break-word;
    }

    .erp-advanced-report-section .kpi-subtext {
        font-size: 11.5px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Section Cards (Charts & Tables) */
    .erp-advanced-report-section .section-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0px 4px 20px rgba(195, 208, 228, 0.25);
        border: 1px solid rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .erp-advanced-report-section .section-header {
        padding: 16px 20px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .erp-advanced-report-section .section-header h5,
    .erp-advanced-report-section .section-header h6 {
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        font-size: 16px;
    }

    .erp-advanced-report-section .section-body {
        padding: 20px;
    }

    /* Responsive Tables */
    .erp-advanced-report-section .responsive-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        margin-top: 0;
        min-height: auto;
    }

    .erp-advanced-report-section .table {
        margin-bottom: 0;
    }

    .erp-advanced-report-section .table th {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        padding: 12px 14px;
        vertical-align: middle;
    }

    .erp-advanced-report-section .table td {
        font-size: 13px;
        padding: 12px 14px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 767.98px) {
        .erp-advanced-report-section {
            margin-top: 10px;
            margin-bottom: 25px;
        }

        .erp-advanced-report-section .page-header-wrap {
            padding: 14px 16px;
        }

        .erp-advanced-report-section .filter-card {
            padding: 14px 16px;
        }

        .erp-advanced-report-section .kpi-card {
            padding: 12px 14px;
        }

        .erp-advanced-report-section .kpi-icon-pill {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }

        .erp-advanced-report-section .kpi-value {
            font-size: 1.15rem;
        }

        .erp-advanced-report-section .section-header {
            padding: 12px 16px;
        }

        .erp-advanced-report-section .section-body {
            padding: 14px;
        }
    }
</style>
@endpush

@section('main_content')
<div class="erp-table-section erp-advanced-report-section">
    <div class="container-fluid">

        <!-- 1. Page Title & Header Actions -->
        <div class="page-header-wrap d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="fw-bold mb-1" style="color: #101828;">
                    <i class="fas fa-chart-line text-primary me-2"></i>{{ __('Customer Traffic Analytics & SMS Reports') }}
                </h4>
                <p class="text-muted small mb-0">
                    {{ __('Real-time customer SMS volume, operator delivery SLA, and traffic reports.') }}
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('ssp.reports.export', request()->all()) }}" class="btn btn-sm btn-outline-success shadow-sm" style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-file-excel me-1"></i> {{ __('Export to CSV') }}
                </a>
                <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm" onclick="window.print()" style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-print me-1"></i> {{ __('Print') }}
                </button>
            </div>
        </div>

        <!-- 2. Date Range Presets -->
        <div class="quick-preset-bar">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="quick-preset-scroll w-100 w-lg-auto">
                    <span class="fw-bold text-secondary small text-nowrap me-1">
                        <i class="far fa-calendar-alt me-1"></i>{{ __('Quick Range:') }}
                    </span>
                    @php
                        $currentPreset = request('date_preset', 'this_month');
                    @endphp
                    <a href="{{ route('ssp.reports.index', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'today'])) }}"
                       class="preset-pill {{ $currentPreset == 'today' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('Today') }}
                    </a>
                    <a href="{{ route('ssp.reports.index', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'yesterday'])) }}"
                       class="preset-pill {{ $currentPreset == 'yesterday' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('Yesterday') }}
                    </a>
                    <a href="{{ route('ssp.reports.index', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'last_7_days'])) }}"
                       class="preset-pill {{ $currentPreset == 'last_7_days' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('Last 7 Days') }}
                    </a>
                    <a href="{{ route('ssp.reports.index', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'this_month'])) }}"
                       class="preset-pill {{ $currentPreset == 'this_month' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('This Month') }}
                    </a>
                    <a href="{{ route('ssp.reports.index', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'last_month'])) }}"
                       class="preset-pill {{ $currentPreset == 'last_month' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('Last Month') }}
                    </a>
                    <a href="{{ route('ssp.reports.index', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'all_time'])) }}"
                       class="preset-pill {{ $currentPreset == 'all_time' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('All Time') }}
                    </a>
                </div>
                <div class="small text-muted d-none d-lg-block">
                    <i class="fas fa-filter text-primary me-1"></i> {{ __('Showing filtered traffic analytics') }}
                </div>
            </div>
        </div>

        <!-- 3. 5 KPI Summary Cards -->
        <div class="row g-2 g-md-3 mb-4">
            <!-- 1. Total SMS Sent -->
            <div class="col-6 col-md-4 col-xl">
                <div class="kpi-card" style="border-left: 4px solid #2563eb !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Total SMS Sent') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                        </div>
                        <div class="kpi-value text-primary">{{ number_format($kpis['total_sms']) }}</div>
                    </div>
                    <div class="kpi-subtext">
                        <i class="fas fa-users me-1"></i> {{ number_format($kpis['unique_numbers']) }} {{ __('recipients') }}
                    </div>
                </div>
            </div>

            <!-- 2. Delivered SLA -->
            <div class="col-6 col-md-4 col-xl">
                <div class="kpi-card" style="border-left: 4px solid #10b981 !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Delivered SLA') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline gap-2 mb-1">
                            <div class="kpi-value text-success mb-0">{{ $kpis['delivery_rate'] }}%</div>
                            <span class="small text-muted d-none d-sm-inline">({{ number_format($kpis['delivered']) }})</span>
                        </div>
                    </div>
                    <div>
                        <div class="progress mb-1" style="height: 6px; border-radius: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $kpis['delivery_rate'] }}%"></div>
                        </div>
                        <div class="kpi-subtext">
                            <i class="fas fa-check me-1"></i> {{ number_format($kpis['delivered']) }} {{ __('delivered') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Failed / Undelivered -->
            <div class="col-6 col-md-4 col-xl">
                <div class="kpi-card" style="border-left: 4px solid #ef4444 !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Failed SMS') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                        <div class="kpi-value text-danger">{{ number_format($kpis['failed']) }}</div>
                    </div>
                    <div class="kpi-subtext">
                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-0 fw-bold">{{ __('Fail Rate:') }} {{ $kpis['failure_rate'] }}%</span>
                    </div>
                </div>
            </div>

            <!-- 4. Pending Queue -->
            <div class="col-6 col-md-4 col-xl">
                <div class="kpi-card" style="border-left: 4px solid #f59e0b !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Pending Queue') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="kpi-value text-warning">{{ number_format($kpis['pending']) }}</div>
                    </div>
                    <div class="kpi-subtext">
                        <i class="fas fa-sync me-1"></i> {{ __('In queue') }}
                    </div>
                </div>
            </div>

            <!-- 5. Total Cost -->
            <div class="col-12 col-md-4 col-xl">
                <div class="kpi-card" style="border-left: 4px solid #8b5cf6 !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Total Cost') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                        <div class="kpi-value" style="color: #8b5cf6;">৳{{ number_format($kpis['total_cost'], 2) }}</div>
                    </div>
                    <div class="kpi-subtext">
                        <i class="fas fa-wallet me-1"></i> {{ __('Total customer billing') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Charts Row -->
        <div class="row g-3 mb-4">
            <!-- Left: Traffic Trend Chart -->
            <div class="col-12 col-lg-8">
                <div class="section-card h-100 mb-0">
                    <div class="section-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-area text-primary me-2"></i>{{ __('SMS Traffic Volume Trend') }}
                        </h6>
                        <span class="badge bg-light text-secondary border">{{ __('Daily Volume') }}</span>
                    </div>
                    <div class="section-body p-3">
                        <div style="height: 290px; position: relative;">
                            <canvas id="sspTrafficTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Operator Distribution Chart -->
            <div class="col-12 col-lg-4">
                <div class="section-card h-100 mb-0">
                    <div class="section-header">
                        <h6 class="mb-0">
                            <i class="fas fa-pie-chart text-primary me-2"></i>{{ __('Mobile Operator Distribution') }}
                        </h6>
                        <span class="badge bg-light text-secondary border">{{ __('By Telco') }}</span>
                    </div>
                    <div class="section-body p-3 d-flex flex-column justify-content-center">
                        <div style="height: 190px; position: relative;" class="mb-3">
                            <canvas id="sspOperatorDonutChart"></canvas>
                        </div>
                        <div class="d-flex flex-wrap justify-content-center gap-2 small">
                            @foreach($operators as $op)
                                <span class="badge px-2 py-1" style="background-color: {{ $op['color'] }}; color: #fff;">
                                    {{ $op['name'] }}: {{ $op['percentage'] }}%
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Mobile Operator Telco Performance Table -->
        <div class="section-card mb-4">
            <div class="section-header">
                <div>
                    <h5 class="mb-1"><i class="fas fa-broadcast-tower text-primary me-2"></i>{{ __('Telco Operator SLA & Traffic Performance') }}</h5>
                    <p class="text-muted small mb-0">{{ __('Operator-wise message traffic, delivered vs failed counts, SLA success rate, and billing.') }}</p>
                </div>
                <span class="badge bg-light text-secondary border d-md-none">
                    <i class="fas fa-arrows-alt-h me-1"></i>{{ __('Scroll table') }}
                </span>
            </div>
            <div class="responsive-table">
                <table class="table table-hover table-striped align-middle text-nowrap" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th>{{ __('Mobile Operator') }}</th>
                            <th class="text-center">{{ __('Total Messages') }}</th>
                            <th class="text-center">{{ __('Delivered') }}</th>
                            <th class="text-center">{{ __('Failed') }}</th>
                            <th style="min-width: 160px;">{{ __('Delivery SLA (%)') }}</th>
                            <th class="text-center">{{ __('Traffic Share') }}</th>
                            <th class="text-end">{{ __('Total Cost (BDT)') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($operators as $op)
                            <tr>
                                <td>
                                    <i class="{{ $op['icon'] }} me-2"></i>
                                    <span class="fw-bold">{{ $op['name'] }}</span>
                                </td>
                                <td class="text-center"><span class="fw-bold">{{ number_format($op['total']) }}</span></td>
                                <td class="text-center"><span class="badge bg-success">{{ number_format($op['delivered']) }}</span></td>
                                <td class="text-center">
                                    @if($op['failed'] > 0)
                                        <span class="badge bg-danger">{{ number_format($op['failed']) }}</span>
                                    @else
                                        <span class="badge bg-light text-muted">0</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px; border-radius: 4px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $op['success_rate'] }}%"></div>
                                        </div>
                                        <span class="small fw-bold">{{ $op['success_rate'] }}%</span>
                                    </div>
                                </td>
                                <td class="text-center"><div class="small fw-bold">{{ $op['percentage'] }}%</div></td>
                                <td class="text-end fw-bold text-primary">৳{{ number_format($op['cost'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">{{ __('No operator traffic recorded.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 6. Filter Panel -->
        <div class="filter-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="color: #101828;">
                    <i class="fas fa-filter text-primary me-2"></i>{{ __('Filter Customer Traffic Records') }}
                </h6>
                <a href="{{ route('ssp.reports.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px;">
                    <i class="fas fa-redo me-1"></i> {{ __('Reset') }}
                </a>
            </div>
            <form action="{{ route('ssp.reports.index') }}" method="GET">
                <input type="hidden" name="date_preset" value="{{ request('date_preset', 'this_month') }}">
                <div class="row g-2 g-md-3">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label-custom">{{ __('Keyword / Number Search') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="{{ __('Mobile number or message...') }}">
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label-custom">{{ __('Customer') }}</label>
                        <select name="user_id" class="form-select form-select-sm">
                            <option value="">{{ __('-- All Customers --') }}</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ request('user_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ '@' . ($c->username ?? $c->id) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label-custom">{{ __('Operator') }}</label>
                        <select name="operator" class="form-select form-select-sm">
                            <option value="">{{ __('-- All Operators --') }}</option>
                            <option value="gp" {{ request('operator') == 'gp' ? 'selected' : '' }}>Grameenphone</option>
                            <option value="bl" {{ request('operator') == 'bl' ? 'selected' : '' }}>Banglalink</option>
                            <option value="robi" {{ request('operator') == 'robi' ? 'selected' : '' }}>Robi / Airtel</option>
                            <option value="teletalk" {{ request('operator') == 'teletalk' ? 'selected' : '' }}>Teletalk</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label-custom">{{ __('Delivery Status') }}</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">{{ __('-- All Statuses --') }}</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label-custom">{{ __('Sender ID') }}</label>
                        <select name="senderid_id" class="form-select form-select-sm">
                            <option value="">{{ __('-- All Sender IDs --') }}</option>
                            @foreach($senderids as $s)
                                <option value="{{ $s->id }}" {{ request('senderid_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->sender }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label-custom">{{ __('From Date') }}</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label-custom">{{ __('To Date') }}</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-sm btn-primary w-100" style="border-radius: 6px; padding: 7px 12px; font-weight: 600;">
                            <i class="fas fa-search me-1"></i> {{ __('Apply Filters') }}
                        </button>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-end">
                        <a href="{{ route('ssp.reports.export', request()->all()) }}" class="btn btn-sm btn-success w-100" style="border-radius: 6px; padding: 7px 12px; font-weight: 600;">
                            <i class="fas fa-download me-1"></i> {{ __('Export CSV') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- 7. Detailed SMS Traffic Log Table -->
        <div class="section-card mb-4">
            <div class="section-header">
                <div>
                    <h5 class="mb-1"><i class="fas fa-list text-primary me-2"></i>{{ __('SMS Traffic Log') }}</h5>
                    <p class="text-muted small mb-0">{{ __('Individual message delivery status, timestamp, recipients, and charges.') }}</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-secondary border d-md-none">
                        <i class="fas fa-arrows-alt-h me-1"></i>{{ __('Scroll table') }}
                    </span>
                    <span class="badge bg-light text-dark border">{{ __('Total records: :count', ['count' => number_format($smsList->total())]) }}</span>
                </div>
            </div>
            <div class="responsive-table">
                <table class="table table-hover table-striped text-nowrap align-middle" style="min-width: 1400px;">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>{{ __('Date & Time') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Sender ID') }}</th>
                            <th>{{ __('Recipient') }}</th>
                            <th>{{ __('Operator') }}</th>
                            <th class="text-center">{{ __('Count') }}</th>
                            <th>{{ __('Cost') }}</th>
                            <th>{{ __('Channel') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Server TxnId') }}</th>
                            <th>{{ __('Gateway Response Code') }}</th>
                            <th>{{ __('Infozillion Response') }}</th>
                            <th>{{ __('Gateway TxnId') }}</th>
                            <th>{{ __('Message Preview') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($smsList as $sms)
                            @php
                                $op = $sms->operator ?: \App\Services\ReportAnalyticsService::detectOperator($sms->number ?? '');
                                $serverTxnId = 'TXN-' . str_pad($sms->id, 8, '0', STR_PAD_LEFT);
                                $gwTxnId = $sms->mno_txnid ?: ($sms->message_id ?: ($sms->gateway_txnid ?: ('GW-' . str_pad($sms->id, 8, '0', STR_PAD_LEFT))));
                                $gwRespCode = $sms->gateway_response_code ?: ($sms->status == 'success' ? '200 (Success)' : ($sms->status == 'pending' ? '102 (Queued)' : '400 (Failed)'));
                                $infoz = $sms->infozillion_response ?: ($sms->status == 'success' ? '9000 (Accepted by Central Platform)' : (($sms->status == 'pending') ? 'Processing' : 'Failed'));
                            @endphp
                            <tr>
                                <td class="font-monospace small text-muted">{{ $sms->id }}</td>
                                <td class="small text-muted">{{ $sms->created_at->format('d M Y, h:i:s A') }}</td>
                                <td>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $sms->user->name ?? 'Customer' }}</div>
                                            <div class="small text-primary font-monospace">@<span>{{ $sms->user->username ?? 'N/A' }}</span></div>
                                        </div>
                                        @if($sms->user_id && $sms->user_id !== auth()->id())
                                            <a href="{{ route('ssp.customers.login-as', $sms->user_id) }}" class="btn btn-xs btn-outline-primary ms-2" style="border-radius: 6px; padding: 2px 8px;" title="{{ __('Login As Customer') }}">
                                                <i class="fas fa-sign-in-alt"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $sms->senderid->sender ?? 'N/A' }}</span>
                                </td>
                                <td><span class="fw-bold font-monospace">{{ $sms->number }}</span></td>
                                <td>
                                    {!! operator_badge($op) !!}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $sms->total_sms ?? 1 }}</span>
                                </td>
                                <td class="fw-bold text-success">৳{{ number_format($sms->charge ?? 0, 2) }}</td>
                                <td>
                                    <span class="fw-semibold">{{ optional($sms->smsgateway)->name ?? 'Direct Gateway' }}</span>
                                </td>
                                <td>
                                    @if(in_array($sms->status, ['success', 'delivered']))
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>{{ __('Delivered') }}</span>
                                    @elseif(in_array($sms->status, ['failed', 'undelivered']))
                                        <span class="badge bg-danger"><i class="fas fa-times me-1"></i>{{ __('Failed') }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>{{ ucfirst($sms->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-monospace small text-muted">{{ $serverTxnId }}</span>
                                </td>
                                <td>
                                    @if(str_contains($gwRespCode, 'Success') || str_contains($gwRespCode, '200') || str_contains($gwRespCode, '1000'))
                                        <span class="badge bg-success">{{ $gwRespCode }}</span>
                                    @elseif(str_contains($gwRespCode, 'Queued') || str_contains($gwRespCode, '102') || str_contains($gwRespCode, 'Processing'))
                                        <span class="badge bg-warning text-dark">{{ $gwRespCode }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $gwRespCode }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small {{ (str_contains($infoz, 'Accepted') || str_contains($infoz, '9000') || str_contains(strtolower($infoz), 'success')) ? 'text-success fw-semibold' : 'text-danger' }}" title="{{ $infoz }}">
                                        {{ Str::limit($infoz, 30) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-monospace small text-dark fw-semibold">{{ $gwTxnId }}</span>
                                </td>
                                <td style="max-width: 260px;" class="text-truncate" title="{{ $sms->contents }}">
                                    {{ $sms->contents }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0 fw-bold">{{ __('No SMS records match your filter criteria.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($smsList->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    {{ $smsList->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script>
    // 1. Daily Traffic Trend Chart
    const trendEl = document.getElementById('sspTrafficTrendChart');
    if (trendEl) {
        const trendCtx = trendEl.getContext('2d');
        const trendLabels = @json($trend['labels']);
        const trendDelivered = @json($trend['delivered']);
        const trendFailed = @json($trend['failed']);

        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: trendLabels,
                datasets: [
                    {
                        label: 'Delivered',
                        data: trendDelivered,
                        backgroundColor: 'rgba(16, 185, 129, 0.75)',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Failed',
                        data: trendFailed,
                        backgroundColor: 'rgba(239, 68, 68, 0.75)',
                        borderColor: '#ef4444',
                        borderWidth: 1,
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 },
                            maxRotation: 45,
                            minRotation: 0
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 14,
                            padding: 10,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += Number(context.parsed.y).toLocaleString() + ' SMS';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Mobile Operator Share Donut Chart
    const opEl = document.getElementById('sspOperatorDonutChart');
    if (opEl) {
        const opCtx = opEl.getContext('2d');
        const opLabels = @json(array_column($operators, 'name'));
        const opTotals = @json(array_column($operators, 'total'));
        const opColors = @json(array_column($operators, 'color'));

        new Chart(opCtx, {
            type: 'doughnut',
            data: {
                labels: opLabels,
                datasets: [{
                    data: opTotals,
                    backgroundColor: opColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
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
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const value = context.parsed;
                                return label + Number(value).toLocaleString() + ' SMS';
                            }
                        }
                    }
                },
                cutout: '68%'
            }
        });
    }
</script>
@endpush
