@extends('layouts.sp.master')

@section('title', __('Reseller (SP) Profit & Loss (লাভ-লোকসান) Summary'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <!-- Page Title & Header Actions -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold mb-1"><i class="fas fa-hand-holding-usd text-success me-2"></i>{{ __('Reseller Profit & Loss (লাভ-লোকসান) Summary') }}</h3>
                <p class="text-muted small mb-0">{{ __('Downline customer & sub-reseller revenue, admin wholesale buying cost, reseller net profit, and profit margins.') }}</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('sp.reports.profit-loss.export', request()->all()) }}" class="btn btn-outline-success shadow-sm">
                    <i class="fas fa-file-excel me-1"></i> {{ __('Export CSV') }}
                </a>
                <button type="button" class="btn btn-outline-secondary shadow-sm" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> {{ __('Print') }}
                </button>
            </div>
        </div>

        <!-- Date Range Presets -->
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="fw-bold text-secondary small me-1"><i class="far fa-calendar-alt me-1"></i>{{ __('Quick Range:') }}</span>
                    @php
                        $currentPreset = request('date_preset', 'this_month');
                    @endphp
                    <a href="{{ route('sp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'today'])) }}" class="btn btn-sm {{ $currentPreset == 'today' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Today') }}</a>
                    <a href="{{ route('sp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'yesterday'])) }}" class="btn btn-sm {{ $currentPreset == 'yesterday' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Yesterday') }}</a>
                    <a href="{{ route('sp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'last_7_days'])) }}" class="btn btn-sm {{ $currentPreset == 'last_7_days' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Last 7 Days') }}</a>
                    <a href="{{ route('sp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'this_month'])) }}" class="btn btn-sm {{ $currentPreset == 'this_month' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('This Month') }}</a>
                    <a href="{{ route('sp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'last_month'])) }}" class="btn btn-sm {{ $currentPreset == 'last_month' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Last Month') }}</a>
                    <a href="{{ route('sp.reports.profit-loss', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'all_time'])) }}" class="btn btn-sm {{ $currentPreset == 'all_time' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('All Time') }}</a>
                </div>
                <div class="small text-muted">
                    <i class="fas fa-tag text-success me-1"></i> {{ __('Your Buying Rate: ৳' . (auth()->user()->non_masking_rate ?: '0.28') . ' Non-Masking | ৳' . (auth()->user()->masking_rate ?: '0.55') . ' Masking') }}
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('sp.reports.profit-loss') }}" class="row g-2 align-items-end">
                    <input type="hidden" name="date_preset" value="{{ request('date_preset', 'this_month') }}">
                    <div class="col-md-2">
                        <label class="form-label small fw-bold mb-1">{{ __('From Date') }}</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold mb-1">{{ __('To Date') }}</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">{{ __('Downline Account') }}</label>
                        <select name="user_id" class="form-select form-select-sm">
                            <option value="">{{ __('All Downlines (Customers & SSP)') }}</option>
                            @foreach($downlineUsers as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ '@' . ($u->username ?? $u->id) }}) - [{{ strtoupper($u->role) }}]
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold mb-1">{{ __('Operator') }}</label>
                        <select name="operator" class="form-select form-select-sm">
                            <option value="">{{ __('All Operators') }}</option>
                            <option value="gp" {{ request('operator') == 'gp' ? 'selected' : '' }}>Grameenphone</option>
                            <option value="bl" {{ request('operator') == 'bl' ? 'selected' : '' }}>Banglalink</option>
                            <option value="robi" {{ request('operator') == 'robi' ? 'selected' : '' }}>Robi / Airtel</option>
                            <option value="teletalk" {{ request('operator') == 'teletalk' ? 'selected' : '' }}>Teletalk</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold mb-1">{{ __('Route Type') }}</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">{{ __('All Route Types') }}</option>
                            <option value="masking" {{ request('type') == 'masking' ? 'selected' : '' }}>Masking</option>
                            <option value="non-masking" {{ request('type') == 'non-masking' ? 'selected' : '' }}>Non-Masking</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100" title="{{ __('Filter') }}"><i class="fas fa-filter"></i></button>
                        <a href="{{ route('sp.reports.profit-loss') }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Reset') }}"><i class="fas fa-redo"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- 6 Main KPI Cards -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Downline Sales -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #3b82f6 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Downline Sales') }}</span>
                        <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-circle"><i class="fas fa-receipt fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1 text-primary">৳{{ number_format($metrics['total_revenue'], 2) }}</h4>
                    <div class="small text-muted"><i class="fas fa-paper-plane me-1"></i> {{ number_format($metrics['total_sms']) }} SMS</div>
                </div>
            </div>

            <!-- 2. Cost to Admin -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #ef4444 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Cost to Admin') }}</span>
                        <div class="p-2 bg-danger bg-opacity-10 text-danger rounded-circle"><i class="fas fa-shopping-cart fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1 text-danger">৳{{ number_format($metrics['total_cost'], 2) }}</h4>
                    <div class="small text-muted">{{ __('Wholesale rate cost') }}</div>
                </div>
            </div>

            <!-- 3. Net Reseller Profit -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #10b981 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Net Profit (লাভ)') }}</span>
                        <div class="p-2 bg-success bg-opacity-10 text-success rounded-circle"><i class="fas fa-coins fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1 {{ $metrics['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">৳{{ number_format($metrics['net_profit'], 2) }}</h4>
                    <div class="small text-muted">
                        <span class="badge {{ $metrics['net_profit'] >= 0 ? 'bg-success' : 'bg-danger' }} text-white">{{ $metrics['net_profit'] >= 0 ? 'লাভজনক' : 'লোকসান' }}</span>
                    </div>
                </div>
            </div>

            <!-- 4. Profit Margin -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #8b5cf6 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Margin (%)') }}</span>
                        <div class="p-2 bg-purple bg-opacity-10 text-purple rounded-circle" style="color: #8b5cf6;"><i class="fas fa-percentage fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: #8b5cf6;">{{ $metrics['profit_margin'] }}%</h4>
                    <div class="small text-muted">{{ __('৳' . $metrics['avg_profit_per_sms'] . '/SMS profit') }}</div>
                </div>
            </div>

            <!-- 5. Total Downline Recharges -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #f59e0b !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Recharges') }}</span>
                        <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-circle"><i class="fas fa-wallet fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1 text-warning">৳{{ number_format($metrics['total_recharges'], 2) }}</h4>
                    <div class="small text-muted">{{ __('Approved deposits') }}</div>
                </div>
            </div>

            <!-- 6. Downline Balance Liability -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #6b7280 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Wallet Liability') }}</span>
                        <div class="p-2 bg-secondary bg-opacity-10 text-secondary rounded-circle"><i class="fas fa-balance-scale fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1 text-secondary">৳{{ number_format($metrics['wallet_liability'], 2) }}</h4>
                    <div class="small text-muted">{{ __('Outstanding downline bal') }}</div>
                </div>
            </div>
        </div>

        <!-- Route Breakdown: Masking vs Non-Masking -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-comment-alt text-primary me-2"></i>{{ __('Non-Masking Route Financials') }}</h6>
                            <span class="badge bg-primary">{{ number_format($metrics['non_masking_count']) }} SMS</span>
                        </div>
                        <div class="row g-2 mt-1 text-center">
                            <div class="col-4 border-end">
                                <div class="text-muted small">{{ __('Sales') }}</div>
                                <div class="fw-bold text-primary">৳{{ number_format($metrics['non_masking_revenue'], 2) }}</div>
                            </div>
                            <div class="col-4 border-end">
                                <div class="text-muted small">{{ __('Cost') }}</div>
                                <div class="fw-bold text-danger">৳{{ number_format($metrics['non_masking_cost'], 2) }}</div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted small">{{ __('Net Profit') }}</div>
                                <div class="fw-bold text-success">৳{{ number_format($metrics['non_masking_profit'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-envelope text-info me-2"></i>{{ __('Masking Route Financials') }}</h6>
                            <span class="badge bg-info text-dark">{{ number_format($metrics['masking_count']) }} SMS</span>
                        </div>
                        <div class="row g-2 mt-1 text-center">
                            <div class="col-4 border-end">
                                <div class="text-muted small">{{ __('Sales') }}</div>
                                <div class="fw-bold text-info">৳{{ number_format($metrics['masking_revenue'], 2) }}</div>
                            </div>
                            <div class="col-4 border-end">
                                <div class="text-muted small">{{ __('Cost') }}</div>
                                <div class="fw-bold text-danger">৳{{ number_format($metrics['masking_cost'], 2) }}</div>
                            </div>
                            <div class="col-4">
                                <div class="text-muted small">{{ __('Net Profit') }}</div>
                                <div class="fw-bold text-success">৳{{ number_format($metrics['masking_profit'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-3 mb-4">
            <!-- Financial Trend Chart -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="fas fa-chart-bar text-primary me-2"></i>{{ __('Daily Financial Flow (Sales vs Buying Cost vs Reseller Profit)') }}</h6>
                        <span class="badge bg-light text-secondary border">{{ __('Last 15 Active Days') }}</span>
                    </div>
                    <div class="card-body p-3">
                        <div style="height: 290px;">
                            <canvas id="spFinancialTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operator Profit Donut Chart -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0"><i class="fas fa-chart-pie text-success me-2"></i>{{ __('Operator Profit Breakdown') }}</h6>
                    </div>
                    <div class="card-body p-3">
                        <div style="height: 210px;" class="mb-3">
                            <canvas id="spOperatorProfitChart"></canvas>
                        </div>
                        <div class="small">
                            @foreach($operatorBreakdown as $op)
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                <div class="d-flex align-items-center">
                                    <span class="badge rounded-circle p-1 me-2" style="background-color: {{ $op['color'] }}; width: 10px; height: 10px;"></span>
                                    <span>{{ $op['name'] }}</span>
                                </div>
                                <div class="fw-bold text-success">৳{{ number_format($op['profit'], 2) }} <span class="text-muted fw-normal">({{ $op['margin'] }}%)</span></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Downline Profit Breakdown Table -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-1"><i class="fas fa-users text-primary me-2"></i>{{ __('Top Downline Accounts by Profit Contribution') }}</h6>
                <p class="text-muted small mb-0">{{ __('Sub-Resellers (SSP) and Direct Customers generating profit for your business.') }}</p>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Account Name') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('SMS Sent') }}</th>
                            <th>{{ __('Masking / Non') }}</th>
                            <th>{{ __('Sales Revenue') }}</th>
                            <th>{{ __('Admin Cost') }}</th>
                            <th>{{ __('Your Net Profit') }}</th>
                            <th>{{ __('Margin %') }}</th>
                            <th>{{ __('Wallet Balance') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userBreakdown as $item)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $item['name'] }}</div>
                                <div class="small text-muted">{{ '@' . ($item['username'] ?? $item['user_id']) }}</div>
                            </td>
                            <td>
                                @if($item['role'] === 'ssp')
                                    <span class="badge bg-info text-dark">{{ __('Sub-Reseller (SSP)') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Customer') }}</span>
                                @endif
                            </td>
                            <td><span class="fw-bold">{{ number_format($item['sms_count']) }}</span></td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item['masking_count'] }} M</span>
                                <span class="badge bg-light text-dark border">{{ $item['non_masking_count'] }} NM</span>
                            </td>
                            <td><span class="text-primary fw-bold">৳{{ number_format($item['revenue'], 2) }}</span></td>
                            <td><span class="text-danger">৳{{ number_format($item['cost'], 2) }}</span></td>
                            <td>
                                <span class="fw-bold {{ $item['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    ৳{{ number_format($item['profit'], 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $item['margin'] >= 25 ? 'bg-success' : ($item['margin'] > 0 ? 'bg-primary' : 'bg-danger') }}">
                                    {{ $item['margin'] }}%
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">৳{{ number_format($item['balance'], 2) }}</span>
                            </td>
                            <td class="text-end">
                                @if($item['role'] === 'ssp')
                                    <a href="{{ route('sp.ssps.login-as', $item['user_id']) }}" class="btn btn-xs btn-outline-primary">
                                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As') }}
                                    </a>
                                @else
                                    <a href="{{ route('sp.customers.login-as', $item['user_id']) }}" class="btn btn-xs btn-outline-primary">
                                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As') }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle me-1"></i> {{ __('No traffic or financial records found for downlines.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Operator P&L Table -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-broadcast-tower text-primary me-2"></i>{{ __('Mobile Operator Financial Breakdown') }}</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Operator') }}</th>
                            <th>{{ __('SMS Volume') }}</th>
                            <th>{{ __('Traffic Share') }}</th>
                            <th>{{ __('Sales Revenue') }}</th>
                            <th>{{ __('Buying Cost') }}</th>
                            <th>{{ __('Net Profit') }}</th>
                            <th>{{ __('Profit Margin') }}</th>
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
                            <td>{{ number_format($op['total_sms']) }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar" style="width: {{ $op['share'] }}%; background-color: {{ $op['color'] }};"></div>
                                    </div>
                                    <span class="small text-muted">{{ $op['share'] }}%</span>
                                </div>
                            </td>
                            <td><span class="text-primary fw-bold">৳{{ number_format($op['revenue'], 2) }}</span></td>
                            <td><span class="text-danger">৳{{ number_format($op['cost'], 2) }}</span></td>
                            <td><span class="text-success fw-bold">৳{{ number_format($op['profit'], 2) }}</span></td>
                            <td>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    const trendCtx = document.getElementById('spFinancialTrendChart').getContext('2d');
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
                    label: 'Downline Sales (৳)',
                    data: revenues,
                    backgroundColor: 'rgba(59, 130, 246, 0.75)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Admin Cost (৳)',
                    data: costs,
                    backgroundColor: 'rgba(239, 68, 68, 0.75)',
                    borderColor: '#ef4444',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Reseller Net Profit (৳)',
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
                x: { grid: { display: false } },
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

    const opProfitCtx = document.getElementById('spOperatorProfitChart').getContext('2d');
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
                legend: { display: false }
            },
            cutout: '68%'
        }
    });
</script>
@endpush
