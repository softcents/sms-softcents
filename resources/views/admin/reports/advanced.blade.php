@extends('layouts.admin.master')

@section('title', __('System Advanced Traffic Analytics'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <!-- Page Title & Header Actions -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h3 class="fw-bold mb-1"><i class="fas fa-chart-line text-primary me-2"></i>{{ __('System-Wide SMS Traffic Analytics') }}</h3>
                <p class="text-muted small mb-0">{{ __('Global operator routing SLA, delivery performance, revenue analytics, and downline traffic inspection.') }}</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.reports.advanced.export', request()->all()) }}" class="btn btn-outline-success shadow-sm">
                    <i class="fas fa-file-excel me-1"></i> {{ __('Export to CSV') }}
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
                    <a href="{{ route('admin.reports.advanced', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'today'])) }}" class="btn btn-sm {{ $currentPreset == 'today' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Today') }}</a>
                    <a href="{{ route('admin.reports.advanced', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'yesterday'])) }}" class="btn btn-sm {{ $currentPreset == 'yesterday' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Yesterday') }}</a>
                    <a href="{{ route('admin.reports.advanced', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'last_7_days'])) }}" class="btn btn-sm {{ $currentPreset == 'last_7_days' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Last 7 Days') }}</a>
                    <a href="{{ route('admin.reports.advanced', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'this_month'])) }}" class="btn btn-sm {{ $currentPreset == 'this_month' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('This Month') }}</a>
                    <a href="{{ route('admin.reports.advanced', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'last_month'])) }}" class="btn btn-sm {{ $currentPreset == 'last_month' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('Last Month') }}</a>
                    <a href="{{ route('admin.reports.advanced', array_merge(request()->except(['date_preset', 'from_date', 'to_date', 'page']), ['date_preset' => 'all_time'])) }}" class="btn btn-sm {{ $currentPreset == 'all_time' ? 'btn-primary' : 'btn-outline-secondary bg-white' }}">{{ __('All Time') }}</a>
                </div>
                <div class="small text-muted">
                    <i class="fas fa-server text-primary me-1"></i> {{ __('PostgreSQL & RabbitMQ High-Throughput Aggregation') }}
                </div>
            </div>
        </div>

        <!-- 5 KPI Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #2563eb !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Total System SMS') }}</span>
                        <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-circle"><i class="fas fa-paper-plane fa-sm"></i></div>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($kpis['total_sms']) }}</h3>
                    <div class="small text-muted"><i class="fas fa-users me-1"></i> {{ number_format($kpis['unique_numbers']) }} {{ __('unique MSISDN') }}</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #10b981 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Delivered SLA') }}</span>
                        <div class="p-2 bg-success bg-opacity-10 text-success rounded-circle"><i class="fas fa-check-circle fa-sm"></i></div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mb-1">
                        <h3 class="fw-bold text-success mb-0">{{ $kpis['delivery_rate'] }}%</h3>
                        <span class="small text-muted">({{ number_format($kpis['delivered']) }} SMS)</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $kpis['delivery_rate'] }}%"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #ef4444 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Failed / Undelivered') }}</span>
                        <div class="p-2 bg-danger bg-opacity-10 text-danger rounded-circle"><i class="fas fa-times-circle fa-sm"></i></div>
                    </div>
                    <h3 class="fw-bold text-danger mb-1">{{ number_format($kpis['failed']) }}</h3>
                    <div class="small text-muted">{{ __('Failure Rate:') }} <strong>{{ $kpis['failure_rate'] }}%</strong></div>
                </div>
            </div>

            <div class="col-xl-2 col-md-6 col-sm-6">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #f59e0b !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Queued / Pending') }}</span>
                        <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-circle"><i class="fas fa-clock fa-sm"></i></div>
                    </div>
                    <h3 class="fw-bold text-warning mb-1">{{ number_format($kpis['pending']) }}</h3>
                    <div class="small text-muted">{{ __('In RabbitMQ queue') }}</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-sm-12">
                <div class="card shadow-sm border-0 h-100 p-3" style="border-left: 4px solid #8b5cf6 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-bold text-uppercase">{{ __('Total Revenue (BDT)') }}</span>
                        <div class="p-2 bg-purple bg-opacity-10 text-primary rounded-circle"><i class="fas fa-coins fa-sm"></i></div>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($kpis['total_cost'], 2) }} BDT</h3>
                    <div class="small text-muted"><i class="fas fa-receipt me-1"></i> {{ __('Total system billing') }}</div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-chart-area text-primary me-2"></i>{{ __('SMS Traffic Volume Trend') }}</h5>
                        <span class="badge bg-light text-secondary border">{{ __('Daily Volume') }}</span>
                    </div>
                    <div class="card-body p-4">
                        <div style="height: 290px; position: relative;">
                            <canvas id="adminTrafficTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-pie-chart text-primary me-2"></i>{{ __('Mobile Operator Distribution') }}</h5>
                        <span class="badge bg-light text-secondary border">{{ __('By Telco') }}</span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <div style="height: 200px; position: relative;" class="mb-3">
                            <canvas id="adminOperatorDonutChart"></canvas>
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

        <!-- Mobile Operator Telco Reconciliation Table -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold"><i class="fas fa-broadcast-tower text-primary me-2"></i>{{ __('Telco Operator SLA & Revenue Performance') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Mobile Operator') }}</th>
                                <th>{{ __('Total Messages') }}</th>
                                <th>{{ __('Delivered') }}</th>
                                <th>{{ __('Failed') }}</th>
                                <th>{{ __('Delivery SLA (%)') }}</th>
                                <th>{{ __('Traffic Share') }}</th>
                                <th class="text-end">{{ __('Total Revenue (BDT)') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($operators as $op)
                                <tr>
                                    <td>
                                        <i class="{{ $op['icon'] }} me-2"></i>
                                        <span class="fw-bold">{{ $op['name'] }}</span>
                                    </td>
                                    <td><span class="fw-bold">{{ number_format($op['total']) }}</span></td>
                                    <td><span class="badge bg-success">{{ number_format($op['delivered']) }}</span></td>
                                    <td>
                                        @if($op['failed'] > 0)
                                            <span class="badge bg-danger">{{ number_format($op['failed']) }}</span>
                                        @else
                                            <span class="badge bg-light text-muted">0</span>
                                        @endif
                                    </td>
                                    <td style="min-width: 140px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $op['success_rate'] }}%"></div>
                                            </div>
                                            <span class="small fw-bold">{{ $op['success_rate'] }}%</span>
                                        </div>
                                    </td>
                                    <td><div class="small fw-bold">{{ $op['percentage'] }}%</div></td>
                                    <td class="text-end fw-bold text-primary">{{ number_format($op['cost'], 2) }} BDT</td>
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
        </div>

        <!-- Filter Panel -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fas fa-filter text-primary me-2"></i>{{ __('Detailed Filters & Search') }}</h5>
                <a href="{{ route('admin.reports.advanced') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-redo me-1"></i> {{ __('Reset All Filters') }}
                </a>
            </div>
            <div class="card-body p-4 bg-light">
                <form action="{{ route('admin.reports.advanced') }}" method="GET">
                    <input type="hidden" name="date_preset" value="{{ request('date_preset', 'this_month') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">{{ __('Keyword / Number Search') }}</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Mobile number or message text...') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">{{ __('User / Account') }}</label>
                            <select name="user_id" class="form-select">
                                <option value="">{{ __('-- All Users --') }}</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->username }}) [{{ strtoupper($u->role) }}]
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-bold">{{ __('Operator') }}</label>
                            <select name="operator" class="form-select">
                                <option value="">{{ __('-- All Operators --') }}</option>
                                <option value="gp" {{ request('operator') == 'gp' ? 'selected' : '' }}>Grameenphone</option>
                                <option value="bl" {{ request('operator') == 'bl' ? 'selected' : '' }}>Banglalink</option>
                                <option value="robi" {{ request('operator') == 'robi' ? 'selected' : '' }}>Robi / Airtel</option>
                                <option value="teletalk" {{ request('operator') == 'teletalk' ? 'selected' : '' }}>Teletalk</option>
                                <option value="other" {{ request('operator') == 'other' ? 'selected' : '' }}>Other / Intl</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-bold">{{ __('Delivery Status') }}</label>
                            <select name="status" class="form-select">
                                <option value="">{{ __('-- All Statuses --') }}</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered / Success</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed / Undelivered</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending / Queued</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-bold">{{ __('Sender ID') }}</label>
                            <select name="senderid_id" class="form-select">
                                <option value="">{{ __('-- All Sender IDs --') }}</option>
                                @foreach($senderids as $s)
                                    <option value="{{ $s->id }}" {{ request('senderid_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->sender }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">{{ __('From Date (Custom)') }}</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">{{ __('To Date (Custom)') }}</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-search me-1"></i> {{ __('Apply Filters') }}
                            </button>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('admin.reports.advanced.export', request()->all()) }}" class="btn btn-success w-100 py-2">
                                <i class="fas fa-download me-1"></i> {{ __('Export CSV') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Detailed SMS Traffic Log Table -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="fas fa-list text-primary me-2"></i>{{ __('SMS Traffic Log') }}</h5>
                <span class="badge bg-light text-dark border">{{ __('Total records: :count', ['count' => number_format($smsList->total())]) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('Date & Time') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Sender ID') }}</th>
                                <th>{{ __('Recipient') }}</th>
                                <th>{{ __('Operator') }}</th>
                                <th>{{ __('Cost') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Message Preview') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($smsList as $sms)
                                @php
                                    $op = \App\Services\ReportAnalyticsService::detectOperator($sms->number ?? '');
                                @endphp
                                <tr>
                                    <td>{{ $sms->id }}</td>
                                    <td class="small text-muted">{{ $sms->created_at->format('d M Y, h:i:s A') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-bold">{{ $sms->user->name ?? 'User' }}</div>
                                                <div class="small text-muted">@<span>{{ $sms->user->username ?? 'N/A' }}</span> <span class="badge bg-light text-dark border">{{ strtoupper($sms->user->role ?? '') }}</span></div>
                                            </div>
                                            @if($sms->user_id)
                                                <a href="{{ route('admin.users.login-as', $sms->user_id) }}" class="btn btn-xs btn-outline-primary ms-2" title="{{ __('Login As') }}">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $sms->senderid->sender ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold font-monospace">{{ $sms->number }}</span>
                                    </td>
                                    <td>
                                        {!! operator_badge($op) !!}
                                    </td>
                                    <td class="fw-bold text-success">{{ number_format($sms->charge ?? 0, 2) }} BDT</td>
                                    <td>
                                        @if(in_array($sms->status, ['success', 'delivered']))
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>{{ __('Delivered') }}</span>
                                        @elseif(in_array($sms->status, ['failed', 'undelivered']))
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>{{ __('Failed') }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>{{ ucfirst($sms->status) }}</span>
                                        @endif
                                    </td>
                                    <td style="max-width: 260px;" class="text-truncate" title="{{ $sms->contents }}">
                                        {{ $sms->contents }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0 fw-bold">{{ __('No SMS records match your filter criteria.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($smsList->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $smsList->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Daily Traffic Trend Chart
    const trendCtx = document.getElementById('adminTrafficTrendChart').getContext('2d');
    const trendLabels = @json($trend['labels']);
    const trendDelivered = @json($trend['delivered']);
    const trendFailed = @json($trend['failed']);

    new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: trendLabels,
            datasets: [
                {
                    label: 'Delivered SMS',
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

    // 2. Mobile Operator Share Donut Chart
    const opCtx = document.getElementById('adminOperatorDonutChart').getContext('2d');
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
                legend: { display: false }
            },
            cutout: '68%'
        }
    });
</script>
@endpush
