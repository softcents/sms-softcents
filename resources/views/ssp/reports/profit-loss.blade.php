@extends('layouts.ssp.master')

@section('title', __('Sub-Reseller (SSP) Profit & Loss Summary'))

@push('css')
<style>
    /* Reset custom.css 100vh card issue for this page */
    .erp-profit-loss-section .card,
    .erp-profit-loss-section .cards,
    .erp-profit-loss-section .couter-box {
        min-height: auto !important;
        height: auto;
    }

    .erp-profit-loss-section {
        margin-top: 20px;
        margin-bottom: 40px;
        font-family: "Manrope", sans-serif;
    }

    /* Page Header */
    .erp-profit-loss-section .page-header-wrap {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 22px;
        box-shadow: 0px 4px 20px rgba(195, 208, 228, 0.25);
        margin-bottom: 20px;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    /* Quick Range Bar */
    .erp-profit-loss-section .quick-preset-bar {
        background: #ffffff;
        border-radius: 12px;
        padding: 12px 18px;
        box-shadow: 0px 2px 14px rgba(195, 208, 228, 0.2);
        margin-bottom: 20px;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .erp-profit-loss-section .quick-preset-scroll {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
        padding-bottom: 4px;
    }

    .erp-profit-loss-section .quick-preset-scroll::-webkit-scrollbar {
        height: 4px;
    }

    .erp-profit-loss-section .quick-preset-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .erp-profit-loss-section .preset-pill {
        border-radius: 20px;
        padding: 5px 14px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    /* Filter Form */
    .erp-profit-loss-section .filter-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: 0px 2px 14px rgba(195, 208, 228, 0.2);
        margin-bottom: 24px;
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    .erp-profit-loss-section .form-label-custom {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }

    /* KPI Summary Cards */
    .erp-profit-loss-section .kpi-card {
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

    .erp-profit-loss-section .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0px 6px 24px rgba(195, 208, 228, 0.35);
    }

    .erp-profit-loss-section .kpi-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .erp-profit-loss-section .kpi-card-title {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        line-height: 1.2;
    }

    .erp-profit-loss-section .kpi-icon-pill {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .erp-profit-loss-section .kpi-value {
        font-size: clamp(1.1rem, 2.2vw, 1.4rem);
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 6px;
        word-break: break-word;
    }

    .erp-profit-loss-section .kpi-subtext {
        font-size: 11.5px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Section Cards (Charts & Tables) */
    .erp-profit-loss-section .section-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0px 4px 20px rgba(195, 208, 228, 0.25);
        border: 1px solid rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .erp-profit-loss-section .section-header {
        padding: 16px 20px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .erp-profit-loss-section .section-header h5,
    .erp-profit-loss-section .section-header h6 {
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        font-size: 16px;
    }

    .erp-profit-loss-section .section-body {
        padding: 20px;
    }

    /* Responsive Tables */
    .erp-profit-loss-section .responsive-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        margin-top: 0;
        min-height: auto;
    }

    .erp-profit-loss-section .table {
        margin-bottom: 0;
    }

    .erp-profit-loss-section .table th {
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

    .erp-profit-loss-section .table td {
        font-size: 13px;
        padding: 12px 14px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 767.98px) {
        .erp-profit-loss-section {
            margin-top: 10px;
            margin-bottom: 25px;
        }

        .erp-profit-loss-section .page-header-wrap {
            padding: 14px 16px;
        }

        .erp-profit-loss-section .filter-card {
            padding: 14px 16px;
        }

        .erp-profit-loss-section .kpi-card {
            padding: 12px 14px;
        }

        .erp-profit-loss-section .kpi-icon-pill {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }

        .erp-profit-loss-section .kpi-value {
            font-size: 1.15rem;
        }

        .erp-profit-loss-section .section-header {
            padding: 12px 16px;
        }

        .erp-profit-loss-section .section-body {
            padding: 14px;
        }
    }
</style>
@endpush

@section('main_content')
<div class="erp-table-section erp-profit-loss-section">
    <div class="container-fluid">

        <!-- 1. Page Title & Action Buttons -->
        <div class="page-header-wrap d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="fw-bold mb-1" style="color: #101828;">
                    <i class="fas fa-hand-holding-usd text-success me-2"></i>{{ __('Sub-Reseller Profit & Loss Summary') }}
                </h4>
                <p class="text-muted small mb-0">
                    {{ __('Customer retail revenue, parent reseller wholesale cost, your net profit, and profit margins.') }}
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('ssp.reports.profit-loss.export', request()->all()) }}" class="btn btn-sm btn-outline-success shadow-sm" style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-file-excel me-1"></i> {{ __('Export CSV') }}
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
                    <a href="{{ route('ssp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'today'])) }}"
                       class="preset-pill {{ $currentPreset == 'today' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('Today') }}
                    </a>
                    <a href="{{ route('ssp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'yesterday'])) }}"
                       class="preset-pill {{ $currentPreset == 'yesterday' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('Yesterday') }}
                    </a>
                    <a href="{{ route('ssp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'last_7_days'])) }}"
                       class="preset-pill {{ $currentPreset == 'last_7_days' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('Last 7 Days') }}
                    </a>
                    <a href="{{ route('ssp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'this_month'])) }}"
                       class="preset-pill {{ $currentPreset == 'this_month' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('This Month') }}
                    </a>
                    <a href="{{ route('ssp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'last_month'])) }}"
                       class="preset-pill {{ $currentPreset == 'last_month' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('Last Month') }}
                    </a>
                    <a href="{{ route('ssp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'all_time'])) }}"
                       class="preset-pill {{ $currentPreset == 'all_time' ? 'bg-primary text-white' : 'bg-light text-secondary border' }}">
                        {{ __('All Time') }}
                    </a>
                </div>
                <div class="small text-muted d-none d-lg-block">
                    <i class="fas fa-tag text-success me-1"></i>
                    {{ __('Your Buying Rate: ৳' . (auth()->user()->non_masking_rate ?: '0.32') . ' Non-Masking | ৳' . (auth()->user()->masking_rate ?: '0.62') . ' Masking') }}
                </div>
            </div>
        </div>

        <!-- 3. Responsive Filter Bar -->
        <div class="filter-card">
            <form method="GET" action="{{ route('ssp.reports.profit-loss') }}" class="row g-2 g-md-3 align-items-end">
                <input type="hidden" name="date_preset" value="{{ request('date_preset', 'this_month') }}">

                <div class="col-12 col-sm-6 col-lg-2">
                    <label class="form-label-custom">{{ __('From Date') }}</label>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                </div>

                <div class="col-12 col-sm-6 col-lg-2">
                    <label class="form-label-custom">{{ __('To Date') }}</label>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label-custom">{{ __('Customer Account') }}</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">{{ __('All Direct Customers') }}</option>
                        @foreach($downlineUsers as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ '@' . ($u->username ?? $u->id) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-lg-2">
                    <label class="form-label-custom">{{ __('Operator') }}</label>
                    <select name="operator" class="form-select form-select-sm">
                        <option value="">{{ __('All Operators') }}</option>
                        <option value="gp" {{ request('operator') == 'gp' ? 'selected' : '' }}>Grameenphone</option>
                        <option value="bl" {{ request('operator') == 'bl' ? 'selected' : '' }}>Banglalink</option>
                        <option value="robi" {{ request('operator') == 'robi' ? 'selected' : '' }}>Robi / Airtel</option>
                        <option value="teletalk" {{ request('operator') == 'teletalk' ? 'selected' : '' }}>Teletalk</option>
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-lg-2">
                    <label class="form-label-custom">{{ __('Route Type') }}</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">{{ __('All Route Types') }}</option>
                        <option value="masking" {{ request('type') == 'masking' ? 'selected' : '' }}>Masking</option>
                        <option value="non-masking" {{ request('type') == 'non-masking' ? 'selected' : '' }}>Non-Masking</option>
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-lg-1">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1" title="{{ __('Filter') }}" style="border-radius: 6px; font-weight: 600;">
                            <i class="fas fa-filter me-1 d-sm-none"></i><i class="fas fa-filter d-none d-sm-inline"></i><span class="d-sm-none">{{ __('Filter') }}</span>
                        </button>
                        <a href="{{ route('ssp.reports.profit-loss') }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Reset') }}" style="border-radius: 6px;">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- 4. 6 KPI Metric Cards -->
        <div class="row g-2 g-md-3 mb-4">
            <!-- 1. Customer Sales -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card" style="border-left: 4px solid #2563eb !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Customer Sales') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
                                <i class="fas fa-receipt"></i>
                            </div>
                        </div>
                        <div class="kpi-value text-primary">৳{{ number_format($metrics['total_revenue'], 2) }}</div>
                    </div>
                    <div class="kpi-subtext">
                        <i class="fas fa-paper-plane me-1"></i> {{ number_format($metrics['total_sms']) }} SMS
                    </div>
                </div>
            </div>

            <!-- 2. Cost to SP Reseller -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card" style="border-left: 4px solid #ef4444 !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Cost to Reseller') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="kpi-value text-danger">৳{{ number_format($metrics['total_cost'], 2) }}</div>
                    </div>
                    <div class="kpi-subtext">
                        <i class="fas fa-tag me-1"></i> {{ __('Wholesale Cost') }}
                    </div>
                </div>
            </div>

            <!-- 3. Net SSP Profit -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card" style="border-left: 4px solid #10b981 !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Net Profit') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                        <div class="kpi-value {{ $metrics['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            ৳{{ number_format($metrics['net_profit'], 2) }}
                        </div>
                    </div>
                    <div class="kpi-subtext">
                        <span class="badge {{ $metrics['net_profit'] >= 0 ? 'bg-success' : 'bg-danger' }} text-white px-2 py-1">
                            {{ $metrics['net_profit'] >= 0 ? __('Profitable') : __('Loss') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 4. Profit Margin -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card" style="border-left: 4px solid #8b5cf6 !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Profit Margin') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                                <i class="fas fa-percentage"></i>
                            </div>
                        </div>
                        <div class="kpi-value" style="color: #8b5cf6;">{{ $metrics['profit_margin'] }}%</div>
                    </div>
                    <div class="kpi-subtext">
                        <span>৳{{ $metrics['avg_profit_per_sms'] }}/SMS {{ __('profit') }}</span>
                    </div>
                </div>
            </div>

            <!-- 5. Total Recharges -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card" style="border-left: 4px solid #f59e0b !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Recharges') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div class="kpi-value text-warning">৳{{ number_format($metrics['total_recharges'], 2) }}</div>
                    </div>
                    <div class="kpi-subtext">
                        <i class="fas fa-check-circle me-1"></i> {{ __('Approved Deposits') }}
                    </div>
                </div>
            </div>

            <!-- 6. Customer Liability -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="kpi-card" style="border-left: 4px solid #64748b !important;">
                    <div>
                        <div class="kpi-card-header">
                            <span class="kpi-card-title">{{ __('Liability') }}</span>
                            <div class="kpi-icon-pill" style="background: rgba(100, 116, 139, 0.1); color: #64748b;">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                        </div>
                        <div class="kpi-value text-secondary">৳{{ number_format($metrics['wallet_liability'], 2) }}</div>
                    </div>
                    <div class="kpi-subtext">
                        <i class="fas fa-users me-1"></i> {{ __('Customer Balance') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Route Breakdown: Non-Masking vs Masking Financials -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-6">
                <div class="section-card h-100 mb-0">
                    <div class="section-header">
                        <h6 class="mb-0">
                            <i class="fas fa-comment-alt text-primary me-2"></i>{{ __('Non-Masking Route Financials') }}
                        </h6>
                        <span class="badge bg-primary px-3 py-1">{{ number_format($metrics['non_masking_count']) }} SMS</span>
                    </div>
                    <div class="section-body p-3">
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <div class="p-2 rounded bg-light">
                                    <div class="text-muted small mb-1">{{ __('Sales') }}</div>
                                    <div class="fw-bold text-primary" style="font-size: clamp(0.95rem, 2vw, 1.15rem);">
                                        ৳{{ number_format($metrics['non_masking_revenue'], 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded bg-light">
                                    <div class="text-muted small mb-1">{{ __('Cost') }}</div>
                                    <div class="fw-bold text-danger" style="font-size: clamp(0.95rem, 2vw, 1.15rem);">
                                        ৳{{ number_format($metrics['non_masking_cost'], 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded bg-light">
                                    <div class="text-muted small mb-1">{{ __('Net Profit') }}</div>
                                    <div class="fw-bold text-success" style="font-size: clamp(0.95rem, 2vw, 1.15rem);">
                                        ৳{{ number_format($metrics['non_masking_profit'], 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="section-card h-100 mb-0">
                    <div class="section-header">
                        <h6 class="mb-0">
                            <i class="fas fa-envelope text-info me-2"></i>{{ __('Masking Route Financials') }}
                        </h6>
                        <span class="badge bg-info text-dark px-3 py-1">{{ number_format($metrics['masking_count']) }} SMS</span>
                    </div>
                    <div class="section-body p-3">
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <div class="p-2 rounded bg-light">
                                    <div class="text-muted small mb-1">{{ __('Sales') }}</div>
                                    <div class="fw-bold text-info" style="font-size: clamp(0.95rem, 2vw, 1.15rem);">
                                        ৳{{ number_format($metrics['masking_revenue'], 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded bg-light">
                                    <div class="text-muted small mb-1">{{ __('Cost') }}</div>
                                    <div class="fw-bold text-danger" style="font-size: clamp(0.95rem, 2vw, 1.15rem);">
                                        ৳{{ number_format($metrics['masking_cost'], 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded bg-light">
                                    <div class="text-muted small mb-1">{{ __('Net Profit') }}</div>
                                    <div class="fw-bold text-success" style="font-size: clamp(0.95rem, 2vw, 1.15rem);">
                                        ৳{{ number_format($metrics['masking_profit'], 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Charts Row -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-8">
                <div class="section-card h-100 mb-0">
                    <div class="section-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar text-primary me-2"></i>{{ __('Daily Financial Flow (Sales vs Buying Cost vs Sub-Reseller Profit)') }}
                        </h6>
                        <span class="badge bg-light text-secondary border">{{ __('Last 15 Active Days') }}</span>
                    </div>
                    <div class="section-body p-3">
                        <div style="height: 290px; position: relative;">
                            <canvas id="sspFinancialTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="section-card h-100 mb-0">
                    <div class="section-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie text-success me-2"></i>{{ __('Operator Profit Breakdown') }}
                        </h6>
                    </div>
                    <div class="section-body p-3">
                        <div style="height: 190px; position: relative;" class="mb-3">
                            <canvas id="sspOperatorProfitChart"></canvas>
                        </div>
                        <div class="small">
                            @foreach($operatorBreakdown as $op)
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                <div class="d-flex align-items-center">
                                    <span class="badge rounded-circle p-1 me-2" style="background-color: {{ $op['color'] }}; width: 10px; height: 10px;"></span>
                                    <span class="fw-semibold">{{ $op['name'] }}</span>
                                </div>
                                <div class="fw-bold text-success">
                                    ৳{{ number_format($op['profit'], 2) }}
                                    <span class="text-muted fw-normal">({{ $op['margin'] }}%)</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. Customer Profit Breakdown Table -->
        <div class="section-card mb-4">
            <div class="section-header">
                <div>
                    <h5 class="mb-1"><i class="fas fa-users text-primary me-2"></i>{{ __('Customer Accounts by Profit Contribution') }}</h5>
                    <p class="text-muted small mb-0">{{ __('Direct customer revenue, parent reseller cost, and profit margins.') }}</p>
                </div>
                <span class="badge bg-light text-secondary border d-md-none">
                    <i class="fas fa-arrows-alt-h me-1"></i>{{ __('Scroll table') }}
                </span>
            </div>
            <div class="responsive-table">
                <table class="table table-hover table-striped align-middle text-nowrap" style="min-width: 950px;">
                    <thead>
                        <tr>
                            <th>{{ __('Customer') }}</th>
                            <th class="text-center">{{ __('SMS Sent') }}</th>
                            <th class="text-center">{{ __('Masking / Non') }}</th>
                            <th>{{ __('Retail Revenue') }}</th>
                            <th>{{ __('Wholesale Cost') }}</th>
                            <th>{{ __('Your Net Profit') }}</th>
                            <th class="text-center">{{ __('Margin %') }}</th>
                            <th>{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userBreakdown as $item)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $item['name'] }}</div>
                                <div class="small text-primary font-monospace">@<span>{{ $item['username'] ?? $item['user_id'] }}</span></div>
                            </td>
                            <td class="text-center"><span class="fw-bold">{{ number_format($item['sms_count']) }}</span></td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $item['masking_count'] }} M</span>
                                <span class="badge bg-light text-dark border">{{ $item['non_masking_count'] }} NM</span>
                            </td>
                            <td><span class="text-primary fw-bold">৳{{ number_format($item['revenue'], 2) }}</span></td>
                            <td><span class="text-danger fw-bold">৳{{ number_format($item['cost'], 2) }}</span></td>
                            <td>
                                <span class="fw-bold {{ $item['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    ৳{{ number_format($item['profit'], 2) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $item['margin'] >= 25 ? 'bg-success' : ($item['margin'] > 0 ? 'bg-primary' : 'bg-danger') }}">
                                    {{ $item['margin'] }}%
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">৳{{ number_format($item['balance'], 2) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('ssp.customers.login-as', $item['user_id']) }}" class="btn btn-xs btn-outline-primary" style="border-radius: 6px; padding: 4px 10px;">
                                    <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As') }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle me-1"></i> {{ __('No traffic or financial records found for customers.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 8. Mobile Operator Financial Breakdown Table -->
        <div class="section-card mb-4">
            <div class="section-header">
                <div>
                    <h5 class="mb-1"><i class="fas fa-broadcast-tower text-primary me-2"></i>{{ __('Mobile Operator Financial Breakdown') }}</h5>
                    <p class="text-muted small mb-0">{{ __('Operator-wise retail sales, buying cost, and net profit margins.') }}</p>
                </div>
                <span class="badge bg-light text-secondary border d-md-none">
                    <i class="fas fa-arrows-alt-h me-1"></i>{{ __('Scroll table') }}
                </span>
            </div>
            <div class="responsive-table">
                <table class="table table-hover table-striped align-middle text-nowrap" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th>{{ __('Operator') }}</th>
                            <th class="text-center">{{ __('SMS Volume') }}</th>
                            <th style="min-width: 180px;">{{ __('Traffic Share') }}</th>
                            <th>{{ __('Retail Revenue') }}</th>
                            <th>{{ __('Buying Cost') }}</th>
                            <th>{{ __('Net Profit') }}</th>
                            <th class="text-center">{{ __('Profit Margin') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($operatorBreakdown as $op)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge rounded-circle p-2 me-2" style="background-color: {{ $op['color'] }}; width: 12px; height: 12px;"></span>
                                    <span class="fw-bold">{{ $op['name'] }}</span>
                                </div>
                            </td>
                            <td class="text-center"><span class="fw-bold">{{ number_format($op['total_sms']) }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px; border-radius: 4px;">
                                        <div class="progress-bar" style="width: {{ $op['share'] }}%; background-color: {{ $op['color'] }};"></div>
                                    </div>
                                    <span class="small text-muted fw-bold">{{ $op['share'] }}%</span>
                                </div>
                            </td>
                            <td><span class="text-primary fw-bold">৳{{ number_format($op['revenue'], 2) }}</span></td>
                            <td><span class="text-danger fw-bold">৳{{ number_format($op['cost'], 2) }}</span></td>
                            <td><span class="text-success fw-bold">৳{{ number_format($op['profit'], 2) }}</span></td>
                            <td class="text-center">
                                <span class="badge {{ $op['margin'] >= 25 ? 'bg-success' : 'bg-primary' }}">{{ $op['margin'] }}%</span>
                            </td>
                        </tr>
                        @endforeach
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
    const trendEl = document.getElementById('sspFinancialTrendChart');
    if (trendEl) {
        const trendCtx = trendEl.getContext('2d');
        const labels = @json($financialTrend['labels']);
        const revenues = @json($financialTrend['revenues']);
        const costs = @json($financialTrend['costs']);
        const profits = @json($financialTrend['profits']);

        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Retail Sales (৳)',
                        data: revenues,
                        backgroundColor: 'rgba(37, 99, 235, 0.75)',
                        borderColor: '#2563eb',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Parent Reseller Cost (৳)',
                        data: costs,
                        backgroundColor: 'rgba(239, 68, 68, 0.75)',
                        borderColor: '#ef4444',
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: 'Sub-Reseller Net Profit (৳)',
                        data: profits,
                        type: 'line',
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: false,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 },
                            maxRotation: 45,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
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
                                    label += '৳' + Number(context.parsed.y).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    const opProfitEl = document.getElementById('sspOperatorProfitChart');
    if (opProfitEl) {
        const opProfitCtx = opProfitEl.getContext('2d');
        const opLabels = @json(array_column($operatorBreakdown, 'name'));
        const opProfits = @json(array_column($operatorBreakdown, 'profit'));
        const opColors = @json(array_column($operatorBreakdown, 'color'));

        new Chart(opProfitCtx, {
            type: 'doughnut',
            data: {
                labels: opLabels,
                datasets: [{
                    data: opProfits,
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
                                return label + '৳' + Number(value).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
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
