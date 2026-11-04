@extends('layouts.admin.master')

@section('title')
    {{__('Dashboard') }}
@endsection

@section('main_content')
    <div class="container-fluid">
        {{-- Date-wise Filter Bar (Default: Today) --}}
        <div class="card shadow-sm border-0 rounded-3 mt-3 mb-3">
            <div class="card-body py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    {{-- Left Title & Active Date Badge --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="fw-bold text-dark" style="font-size: 14px;">
                            <i class="fas fa-filter text-primary me-1"></i>{{ __('Filter Data') }}:
                        </span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-2 fw-semibold" id="active-filter-badge" style="font-size: 11.5px;">
                            <i class="fas fa-calendar-day me-1"></i>{{ __('Today') }} ({{ now()->format('d M Y') }})
                        </span>
                    </div>

                    {{-- Right Filter Controls --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                        {{-- Preset Buttons --}}
                        <div class="btn-group btn-group-sm" role="group" id="dashboard-date-presets">
                            <button type="button" class="btn btn-outline-primary active py-1 px-2.5" data-filter="today" style="font-size: 12px;">{{ __('Today') }}</button>
                            <button type="button" class="btn btn-outline-primary py-1 px-2.5" data-filter="yesterday" style="font-size: 12px;">{{ __('Yesterday') }}</button>
                            <button type="button" class="btn btn-outline-primary py-1 px-2.5" data-filter="last_7_days" style="font-size: 12px;">{{ __('7 Days') }}</button>
                            <button type="button" class="btn btn-outline-primary py-1 px-2.5" data-filter="this_month" style="font-size: 12px;">{{ __('This Month') }}</button>
                            <button type="button" class="btn btn-outline-primary py-1 px-2.5" data-filter="all_time" style="font-size: 12px;">{{ __('All Time') }}</button>
                        </div>

                        {{-- Custom Date Inputs --}}
                        <div class="d-flex align-items-center gap-1">
                            <input type="date" class="form-control form-control-sm py-1 px-2" id="dash_start_date" value="{{ now()->format('Y-m-d') }}" style="width: 125px; font-size: 11.5px; height: 30px;">
                            <span class="text-muted small">-</span>
                            <input type="date" class="form-control form-control-sm py-1 px-2" id="dash_end_date" value="{{ now()->format('Y-m-d') }}" style="width: 125px; font-size: 11.5px; height: 30px;">
                            <button type="button" class="btn btn-sm btn-primary py-1 px-2" id="btn-apply-custom-date" style="font-size: 11.5px; height: 30px;" title="{{ __('Apply Custom Range') }}">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="gpt-dashboard-card grid-5 mt-2 mb-30">
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/banner.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="recharge_amount">{{ $stats['recharge_amount'] ?? '৳0.00' }}</h5>
                    <p>{{ __('Recharge Amount') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-green2-light">
                    <img src="{{ asset('assets/img/icon/user1.png') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="subcribers">{{ $stats['subcribers'] ?? 0 }}</h5>
                    <p>{{ __('Plan Subscribes') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/user.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="customers">{{ $stats['customers'] ?? 0 }}</h5>
                    <p id="customers-label">{{ __('Total Customers') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/subscription.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="plans">{{ $stats['plans'] ?? 0 }}</h5>
                    <p>{{ __('Total Plans') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/category.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="api_gateways">{{ $stats['api_gateways'] ?? 0 }}</h5>
                    <p>{{ __('Api Gateways') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/crown-king.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="andriod_gateways">{{ $stats['andriod_gateways'] ?? 0 }}</h5>
                    <p>{{ __('Andriod Gateways') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/subscription.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="total_sms">{{ $stats['total_sms'] ?? 0 }}</h5>
                    <p>{{ __('Total Sms') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-green2-light">
                    <img src="{{ asset('assets/img/icon/support-faq.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="pending_sms">{{ $stats['pending_sms'] ?? 0 }}</h5>
                    <p>{{ __('Pending Sms') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/suggestions.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="success_sms">{{ $stats['success_sms'] ?? 0 }}</h5>
                    <p>{{ __('Success Sms') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/reward.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="canceled_sms">{{ $stats['canceled_sms'] ?? 0 }}</h5>
                    <p>{{ __('Failed Sms') }}</p>
                </div>
            </div>
        </div>

        <!-- SMS Gateways Live Balance Section -->
        @if(isset($activeGateways) && $activeGateways->isNotEmpty())
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-20">
            <h4 class="mb-0 fw-bold" style="color: #101828; font-size: 18px;">
                <i class="fas fa-wallet text-primary me-2"></i>{{ __('SMS Gateways Live Balances') }}
            </h4>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" id="dashRefreshBalances" style="border-radius: 6px; padding: 4px 12px; font-weight: 500;">
                    <i class="fas fa-sync-alt me-1" id="dashRefreshIcon"></i> {{ __('Refresh') }}
                </button>
                <a href="{{ route('admin.sms-gateways.index') }}" class="btn btn-sm btn-primary text-light" style="border-radius: 6px; padding: 4px 14px; font-weight: 500;">
                    {{ __('All Gateways') }} &rarr;
                </a>
            </div>
        </div>

        <div class="gpt-dashboard-card grid-4 mb-30">
            @php
                $gatewayIcons = ['credit.svg', 'banner.svg', 'category.svg', 'crown-king.svg', 'subscription.svg', 'reward.svg'];
            @endphp
            @foreach($activeGateways as $gateway)
            <div class="couter-box">
                <div class="icons">
                    <img src="{{ asset('assets/img/icon/' . $gatewayIcons[$loop->index % count($gatewayIcons)]) }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="dash-gateway-{{ $gateway->id }}">৳6,407.98</h5>
                    <p>{{ __($gateway->name) }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="row gpt-dashboard-chart">
            <div class="col-xxl-8 col-xl-7 col-lg-7 mb-30">
                <div class="cards dashboard-card new-card border-0 p-0">
                    <div class="card-header subscription-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0">{{ __('Sms history statistics') }}</h4>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal py-1 px-2" id="chart-history-period" style="font-size: 11px;">{{ __('Today') }}</span>
                        </div>
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
                    <div class="card-header overview-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0">{{ __('SMS Report') }}</h4>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal py-1 px-2" id="chart-overview-period" style="font-size: 11px;">{{ __('Today') }}</span>
                        </div>
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

            <div class="col-xxl-12 mb-30">
                <div class="cards new-card border-0 p-0">
                    <div class="card-header users-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0">{{ __('New Registered Customers') }}</h4>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal py-1 px-2" id="table-period-badge" style="font-size: 11px;">{{ __('Today') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive border-1 rounded-3 rounded-image">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Plan (Duration)') }}</th>
                                    <th>{{ __('Expire Date') }}</th>
                                </tr>
                                </thead>
                                <tbody id="registered-customers-tbody">
                                    @include('admin.dashboard.customer-datas')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" value="{{ route('admin.dashboard.data') }}" id="get-dashboard">
    <input type="hidden" value="{{ route('admin.dashboard.sms-overview') }}" id="get-sms-overview">
    <input type="hidden" value="{{ route('admin.dashboard.statistics') }}" id="yearly-statistics-url">

@endsection

@push('js')
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/dashboard.js') }}?v={{ time() }}"></script>
    <script>
        $(document).ready(function() {
            function loadDashboardGatewayBalances() {
                const icon = $('#dashRefreshIcon');
                icon.addClass('fa-spin');

                $.ajax({
                    url: "{{ route('admin.sms-gateways.all-balances') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res && res.gateways) {
                            $.each(res.gateways, function(id, data) {
                                const el = $('#dash-gateway-' + id);
                                if (el.length) {
                                    if (data.success && data.formatted) {
                                        el.text(data.formatted);
                                    } else if (!data.success) {
                                        el.text(data.formatted || 'Offline');
                                    }
                                }
                            });
                        }
                    },
                    complete: function() {
                        icon.removeClass('fa-spin');
                    }
                });
            }

            $('#dashRefreshBalances').on('click', function(e) {
                e.preventDefault();
                loadDashboardGatewayBalances();
            });

            // Automatically refresh on load
            loadDashboardGatewayBalances();
        });
    </script>
@endpush
