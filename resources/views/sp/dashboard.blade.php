@extends('layouts.sp.master')

@section('title', __('Service Provider Dashboard'))

@section('main_content')
<div class="container-fluid">
    <!-- Top Stat Cards (Counter Boxes) -->
    <div class="gpt-dashboard-card grid-5 mt-30 mb-30">
        <div class="couter-box">
            <div class="icons">
                <img src="{{ asset('assets/img/icon/credit.svg') }}" alt="">
            </div>
            <div class="content-side">
                <h5>{{ currency_format($sp->balance) }}</h5>
                <p>{{ __('My Balance') }}</p>
            </div>
        </div>
        <div class="couter-box">
            <div class="icons">
                <img src="{{ asset('assets/img/icon/crown-king.svg') }}" alt="">
            </div>
            <div class="content-side">
                <h5 id="ssps">{{ $totalSsps }}</h5>
                <p>{{ __('Sub Resellers') }}</p>
            </div>
        </div>
        <div class="couter-box">
            <div class="icons">
                <img src="{{ asset('assets/img/icon/user.svg') }}" alt="">
            </div>
            <div class="content-side">
                <h5 id="customers">{{ $totalCustomers }}</h5>
                <p>{{ __('Direct Customers') }}</p>
            </div>
        </div>
        <div class="couter-box">
            <div class="icons">
                <img src="{{ asset('assets/img/icon/banner.svg') }}" alt="">
            </div>
            <div class="content-side">
                <h5 id="recharge_amount">{{ currency_format($rechargeAmount) }}</h5>
                <p>{{ __('Recharge Amount') }}</p>
            </div>
        </div>
        <div class="couter-box">
            <div class="icons">
                <img src="{{ asset('assets/img/icon/user1.png') }}" alt="">
            </div>
            <div class="content-side">
                <h5>{{ $totalDownlineUsers }}</h5>
                <p>{{ __('Total Downline') }}</p>
            </div>
        </div>
        <div class="couter-box">
            <div class="icons">
                <img src="{{ asset('assets/img/icon/subscription.svg') }}" alt="">
            </div>
            <div class="content-side">
                <h5 id="total_sms">{{ number_format($totalSmsSent) }}</h5>
                <p>{{ __('Total Sms') }}</p>
            </div>
        </div>
        <div class="couter-box">
            <div class="icons">
                <img src="{{ asset('assets/img/icon/reward.svg') }}" alt="">
            </div>
            <div class="content-side">
                <h5>{{ number_format($todaySmsSent) }}</h5>
                <p>{{ __('Today Sms') }}</p>
            </div>
        </div>
        <div class="couter-box">
            <div class="icons">
                <img src="{{ asset('assets/img/icon/suggestions.svg') }}" alt="">
            </div>
            <div class="content-side">
                <h5 id="success_sms">{{ number_format($successSms) }}</h5>
                <p>{{ __('Success Sms') }}</p>
            </div>
        </div>
        <div class="couter-box">
            <div class="icons">
                <img src="{{ asset('assets/img/icon/support-faq.svg') }}" alt="">
            </div>
            <div class="content-side">
                <h5 id="pending_sms">{{ number_format($pendingSms) }}</h5>
                <p>{{ __('Pending Sms') }}</p>
            </div>
        </div>
        <div class="couter-box">
            <div class="icons">
                <img src="{{ asset('assets/img/icon/category.svg') }}" alt="">
            </div>
            <div class="content-side">
                <h5 id="canceled_sms">{{ number_format($failedSms) }}</h5>
                <p>{{ __('Failed Sms') }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Management Actions -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-20">
        <h4 class="mb-0 fw-bold" style="color: #101828; font-size: 18px;">
            <i class="fas fa-bolt text-warning me-2"></i>{{ __('Quick Management Actions') }}
        </h4>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <a href="{{ route('sp.ssps.create') }}" class="btn btn-sm btn-primary text-light" style="border-radius: 6px; padding: 6px 14px; font-weight: 500;">
                <i class="fas fa-plus-circle me-1"></i> {{ __('New SSP') }}
            </a>
            <a href="{{ route('sp.customers.create') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 6px; padding: 6px 14px; font-weight: 500;">
                <i class="fas fa-user-plus me-1"></i> {{ __('New Customer') }}
            </a>
            <a href="{{ route('sp.ssps.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px; padding: 6px 14px; font-weight: 500;">
                <i class="fas fa-sitemap me-1"></i> {{ __('All SSPs') }}
            </a>
            <a href="{{ route('sp.customers.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px; padding: 6px 14px; font-weight: 500;">
                <i class="fas fa-users me-1"></i> {{ __('All Customers') }}
            </a>
            <a href="{{ route('sp.gateways.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px; padding: 6px 14px; font-weight: 500;">
                <i class="fas fa-wallet me-1"></i> {{ __('Payment Gateways') }}
            </a>
            <a href="{{ route('sp.white-label.index') }}" class="btn btn-sm btn-outline-info" style="border-radius: 6px; padding: 6px 14px; font-weight: 500;">
                <i class="fas fa-paint-brush me-1"></i> {{ __('White Label') }}
            </a>
        </div>
    </div>

    <!-- Charts & Activity Row -->
    <div class="row gpt-dashboard-chart">
        <div class="col-xxl-8 col-xl-7 col-lg-7 mb-30">
            <div class="cards dashboard-card new-card border-0 p-0">
                <div class="card-header subscription-header">
                    <h4>{{ __('Sms history statistics') }}</h4>
                    <div class="gpt-up-down-arrow position-relative">
                        <select class="form-control sms-statistics">
                            @for ($i = date('Y'); $i >= 2022; $i--)
                                <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <span></span>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <canvas id="monthly-statistics" class="chart-css"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-xl-5 col-lg-5 mb-30">
            <div class="cards sms-report new-card border-0 p-0">
                <div class="card-header overview-header">
                    <h4>{{ __('SMS Report') }}</h4>
                    <div class="gpt-up-down-arrow position-relative">
                        <select class="form-control overview-year">
                            @for ($i = date('Y'); $i >= 2022; $i--)
                                <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <span></span>
                    </div>
                </div>
                <div class="card-body ShareProfit-body">
                    <canvas id="ShareProfit" class="chart-css"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="col-xxl-12 mb-30">
            <div class="cards new-card border-0 p-0">
                <div class="card-header users-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('Recent Downline SMS Activity') }}</h4>
                    <a href="{{ route('sp.reports.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 6px; padding: 4px 14px; font-weight: 500;">
                        {{ __('View All Activity') }} &rarr;
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive border-1 rounded-3 rounded-image">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Recipient') }}</th>
                                    <th>{{ __('Message') }}</th>
                                    <th>{{ __('Charge') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSms as $sms)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $sms->user->name ?? 'User' }}</strong><br>
                                        <small class="text-muted">{{ '@' . ($sms->user->username ?? '') }}</small>
                                    </td>
                                    <td>{{ $sms->number }}</td>
                                    <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $sms->contents }}
                                    </td>
                                    <td>{{ currency_format($sms->charge ?? 0) }}</td>
                                    <td>
                                        <span class="badge {{ $sms->status === 'success' ? 'bg-success' : ($sms->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                            {{ ucfirst($sms->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $sms->created_at ? $sms->created_at->diffForHumans() : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">{{ __('No SMS activity recorded yet.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" value="{{ route('sp.dashboard.data') }}" id="get-dashboard">
<input type="hidden" value="{{ route('sp.dashboard.sms-overview') }}" id="get-sms-overview">
<input type="hidden" value="{{ route('sp.dashboard.statistics') }}" id="yearly-statistics-url">

@endsection

@push('js')
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/dashboard.js') }}"></script>
@endpush
