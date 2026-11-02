@extends('layouts.users.master')

@section('title', __('SMS Expense & Billing Summary'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <!-- Page Title & Header Actions -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold mb-1"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>{{ __('SMS Expense & Billing Summary') }}</h3>
                <p class="text-muted small mb-0">{{ __('Transparent overview of your SMS campaign expenditures, operator distribution, and wallet balance.') }}</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('users.reports.expenses.export', request()->all()) }}" class="btn btn-outline-success shadow-sm">
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
                    <a href="{{ route('users.reports.expenses', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'today'])) }}" class="btn btn-sm {{ $currentPreset == 'today' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Today') }}</a>
                    <a href="{{ route('users.reports.expenses', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'yesterday'])) }}" class="btn btn-sm {{ $currentPreset == 'yesterday' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Yesterday') }}</a>
                    <a href="{{ route('users.reports.expenses', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'last_7_days'])) }}" class="btn btn-sm {{ $currentPreset == 'last_7_days' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Last 7 Days') }}</a>
                    <a href="{{ route('users.reports.expenses', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'this_month'])) }}" class="btn btn-sm {{ $currentPreset == 'this_month' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('This Month') }}</a>
                    <a href="{{ route('users.reports.expenses', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'last_month'])) }}" class="btn btn-sm {{ $currentPreset == 'last_month' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Last Month') }}</a>
                    <a href="{{ route('users.reports.expenses', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'all_time'])) }}" class="btn btn-sm {{ $currentPreset == 'all_time' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('All Time') }}</a>
                </div>
                <div class="small text-muted">
                    <i class="fas fa-wallet text-success me-1"></i> {{ __('Current Wallet Balance: ৳' . number_format($summary['current_balance'], 2)) }}
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('users.reports.expenses') }}" class="row g-2 align-items-end">
                    <input type="hidden" name="date_preset" value="{{ request('date_preset', 'this_month') }}">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">{{ __('From Date') }}</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">{{ __('To Date') }}</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold mb-1">{{ __('Sender ID') }}</label>
                        <select name="senderid_id" class="form-select form-select-sm">
                            <option value="">{{ __('All Sender IDs') }}</option>
                            @foreach($senderids as $sid)
                                <option value="{{ $sid->id }}" {{ request('senderid_id') == $sid->id ? 'selected' : '' }}>{{ $sid->sender }}</option>
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
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100" title="{{ __('Filter') }}"><i class="fas fa-filter me-1"></i> {{ __('Filter') }}</button>
                        <a href="{{ route('users.reports.expenses') }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Reset') }}"><i class="fas fa-redo"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- 6 Main KPI Cards -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Spent -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #ef4444 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Total Spent') }}</span>
                        <div class="p-2 bg-danger bg-opacity-10 text-danger rounded-circle"><i class="fas fa-money-bill-wave fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1 text-danger">৳{{ number_format($summary['total_spent'], 2) }}</h4>
                    <div class="small text-muted"><i class="fas fa-paper-plane me-1"></i> {{ number_format($summary['total_sms']) }} SMS parts</div>
                </div>
            </div>

            <!-- 2. Current Wallet Balance -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #10b981 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Current Balance') }}</span>
                        <div class="p-2 bg-success bg-opacity-10 text-success rounded-circle"><i class="fas fa-wallet fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1 text-success">৳{{ number_format($summary['current_balance'], 2) }}</h4>
                    <div class="small text-muted">{{ __('Available credit') }}</div>
                </div>
            </div>

            <!-- 3. Total Recharges -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #3b82f6 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Total Recharged') }}</span>
                        <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-circle"><i class="fas fa-credit-card fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1 text-primary">৳{{ number_format($summary['total_recharge'], 2) }}</h4>
                    <div class="small text-muted">{{ __('Lifetime deposits') }}</div>
                </div>
            </div>

            <!-- 4. Avg Cost Per SMS -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #8b5cf6 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Avg Rate') }}</span>
                        <div class="p-2 bg-purple bg-opacity-10 text-purple rounded-circle" style="color: #8b5cf6;"><i class="fas fa-tag fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: #8b5cf6;">৳{{ $summary['avg_cost_per_sms'] }}</h4>
                    <div class="small text-muted">{{ __('Per SMS part avg') }}</div>
                </div>
            </div>

            <!-- 5. Non-Masking Spent -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #f59e0b !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Non-Masking') }}</span>
                        <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-circle"><i class="fas fa-comment-alt fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1 text-warning">৳{{ number_format($summary['non_masking_spent'], 2) }}</h4>
                    <div class="small text-muted">{{ number_format($summary['non_masking_count']) }} SMS</div>
                </div>
            </div>

            <!-- 6. Masking Spent -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #06b6d4 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Masking') }}</span>
                        <div class="p-2 bg-info bg-opacity-10 text-info rounded-circle"><i class="fas fa-envelope fa-sm"></i></div>
                    </div>
                    <h4 class="fw-bold mb-1 text-info">৳{{ number_format($summary['masking_spent'], 2) }}</h4>
                    <div class="small text-muted">{{ number_format($summary['masking_count']) }} SMS</div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-3 mb-4">
            <!-- Daily Expense Trend Chart -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="fas fa-chart-line text-primary me-2"></i>{{ __('Daily SMS Expense & Activity') }}</h6>
                        <span class="badge bg-light text-secondary border">{{ __('Last 15 Active Days') }}</span>
                    </div>
                    <div class="card-body p-3">
                        <div style="height: 270px;">
                            <canvas id="userExpenseTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operator Spending Donut Chart -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0"><i class="fas fa-chart-pie text-success me-2"></i>{{ __('Operator Expense Breakdown') }}</h6>
                    </div>
                    <div class="card-body p-3">
                        <div style="height: 190px;" class="mb-3">
                            <canvas id="userOperatorDonutChart"></canvas>
                        </div>
                        <div class="small">
                            @foreach($operatorBreakdown as $op)
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                <div class="d-flex align-items-center">
                                    <span class="badge rounded-circle p-1 me-2" style="background-color: {{ $op['color'] }}; width: 10px; height: 10px;"></span>
                                    <span>{{ $op['name'] }}</span>
                                </div>
                                <div class="fw-bold text-dark">৳{{ number_format($op['revenue'], 2) }} <span class="text-muted fw-normal">({{ $op['share'] }}%)</span></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Paginated Expenses Table -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="fas fa-list text-primary me-2"></i>{{ __('Itemized SMS Expense Logs') }}</h6>
                <span class="badge bg-light text-secondary border">{{ $expenses->total() }} {{ __('Total Records') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Date & Time') }}</th>
                            <th>{{ __('Sender ID') }}</th>
                            <th>{{ __('Route Type') }}</th>
                            <th>{{ __('Recipient') }}</th>
                            <th>{{ __('Operator') }}</th>
                            <th>{{ __('SMS Parts') }}</th>
                            <th>{{ __('Charge (৳)') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $sms)
                        <tr>
                            <td><span class="text-muted small">#{{ $sms->id }}</span></td>
                            <td>{{ $sms->created_at ? $sms->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $sms->senderid->sender ?? 'N/A' }}</span></td>
                            <td>
                                @if($sms->senderid && stripos($sms->senderid->type, 'masking') !== false && stripos($sms->senderid->type, 'non') === false)
                                    <span class="badge bg-info text-dark">Masking</span>
                                @else
                                    <span class="badge bg-secondary">Non-Masking</span>
                                @endif
                            </td>
                            <td><code>{{ $sms->number }}</code></td>
                            <td>
                                @php $opName = \App\Services\ReportAnalyticsService::detectOperator($sms->number); @endphp
                                <span class="badge bg-light text-dark border">{{ $opName }}</span>
                            </td>
                            <td><span class="badge bg-light text-secondary border">{{ $sms->total_sms ?? 1 }}</span></td>
                            <td><strong class="text-danger">৳{{ number_format((float) $sms->charge, 4) }}</strong></td>
                            <td>
                                @if($sms->status === 'success' || $sms->status === 'delivered')
                                    <span class="badge bg-success">{{ __('Delivered') }}</span>
                                @elseif($sms->status === 'failed')
                                    <span class="badge bg-danger">{{ __('Failed') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ ucfirst($sms->status ?? 'Sent') }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle me-1"></i> {{ __('No expense logs found for this filter.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($expenses->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $expenses->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // 1. Daily Trend
    const trendCtx = document.getElementById('userExpenseTrendChart').getContext('2d');
    const trendLabels = @json($dailyTrend['labels']);
    const trendDelivered = @json($dailyTrend['delivered']);
    const trendFailed = @json($dailyTrend['failed']);

    new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: trendLabels,
            datasets: [
                {
                    label: 'Successful SMS',
                    data: trendDelivered,
                    backgroundColor: 'rgba(16, 185, 129, 0.75)',
                    borderColor: '#10b981',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Failed SMS',
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
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true }
            },
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

    // 2. Operator Donut Chart
    const opDonutCtx = document.getElementById('userOperatorDonutChart').getContext('2d');
    const opLabels = @json(array_column($operatorBreakdown, 'name'));
    const opTotals = @json(array_column($operatorBreakdown, 'revenue'));
    const opColors = @json(array_column($operatorBreakdown, 'color'));

    new Chart(opDonutCtx, {
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
                legend: { display: false }
            },
            cutout: '68%'
        }
    });
</script>
@endpush
