@extends('layouts.admin.master')

@section('title')
    {{__('Dashboard') }}
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="gpt-dashboard-card grid-5 mt-30 mb-30">
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/banner.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="recharge_amount"></h5>
                    <p>{{ __('Recharge Amount') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-green2-light">
                    <img src="{{ asset('assets/img/icon/user1.png') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="subcribers"></h5>
                    <p>{{ __('Plan Subscribes') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/user.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="customers"></h5>
                    <p>{{ __('Total Customers') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/subscription.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="plans"></h5>
                    <p>{{ __('Total Plans') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/category.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="api_gateways"></h5>
                    <p>{{ __('Api Gateways') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/crown-king.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="andriod_gateways"></h5>
                    <p>{{ __('Andriod Gateways') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/subscription.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="total_sms"></h5>
                    <p>{{ __('Total Sms') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-green2-light">
                    <img src="{{ asset('assets/img/icon/support-faq.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="pending_sms"></h5>
                    <p>{{ __('Pending Sms') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/suggestions.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="success_sms"></h5>
                    <p>{{ __('Success Sms') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/reward.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="canceled_sms"></h5>
                    <p>{{ __('Failed Sms') }}</p>
                </div>
            </div>
        </div>

        <!-- SMS Gateways Live Balance Section -->
        <div class="card mb-30 border-0 shadow-sm" style="border-radius: 12px; background: #fff;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                        <i class="fas fa-wallet text-primary"></i> {{ __('SMS Gateways Live Balances') }}
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="dashRefreshBalances">
                            <i class="fas fa-sync-alt me-1" id="dashRefreshIcon"></i> {{ __('Refresh') }}
                        </button>
                        <a href="{{ route('admin.sms-gateways.index') }}" class="btn btn-sm btn-primary text-light">
                            {{ __('All Gateways') }} &rarr;
                        </a>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-xl-3 col-md-6">
                        <div class="p-3 rounded border d-flex align-items-center justify-content-between" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
                            <div>
                                <small class="text-muted d-block fw-semibold">{{ __('White Info BD') }}</small>
                                <span class="fs-5 fw-bold text-success" id="dash-gateway-31">৳6,408.44</span>
                                <small class="d-block text-muted" style="font-size: 11px;">API: sms.whiteinfobd.com</small>
                            </div>
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="p-3 rounded border d-flex align-items-center justify-content-between" style="background: #eff6ff; border-color: #bfdbfe !important;">
                            <div>
                                <small class="text-muted d-block fw-semibold">{{ __('PanelSms1') }}</small>
                                <span class="fs-5 fw-bold text-primary" id="dash-gateway-27">৳13,643.51</span>
                                <small class="d-block text-muted" style="font-size: 11px;">Panel SMS BD</small>
                            </div>
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="p-3 rounded border d-flex align-items-center justify-content-between" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                            <div>
                                <small class="text-muted d-block fw-semibold">{{ __('SYSSMS') }}</small>
                                <span class="fs-5 fw-bold text-dark" id="dash-gateway-29">Connected</span>
                                <small class="d-block text-muted" style="font-size: 11px;">SysSms / REVE</small>
                            </div>
                            <span class="badge bg-info"><i class="fas fa-link me-1"></i>Online</span>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="p-3 rounded border d-flex align-items-center justify-content-between" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                            <div>
                                <small class="text-muted d-block fw-semibold">{{ __('REVE SMS') }}</small>
                                <span class="fs-5 fw-bold text-dark" id="dash-gateway-30">Connected</span>
                                <small class="d-block text-muted" style="font-size: 11px;">REVE Systems</small>
                            </div>
                            <span class="badge bg-info"><i class="fas fa-link me-1"></i>Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

            <div class="col-xxl-12 mb-30">
                <div class="cards new-card border-0 p-0">
                    <div class="card-header users-header">
                        <h4>{{ __('New Registered Customers') }}</h4>
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
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><img class="table-img rounded-circle" src="{{ asset($user->image ?? 'assets/images/icons/default-user.png') }}" alt=""></td>
                                        <td>{{ ucwords($user->name) }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <p>{{ optional($user->plan)->title }} (<small>{{ optional($user->plan)->duration ?? 'N/A' }}</small>)</p>
                                        </td>
                                        <td>{{ formatted_date($user->will_expire) }}</td>
                                    </tr>
                                @endforeach
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
    <script src="{{ asset('assets/plugins/custom/dashboard.js') }}"></script>
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
