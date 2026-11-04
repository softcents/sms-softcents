@extends('layouts.users.master')

@section('title')
    {{__('Dashboard') }}
@endsection

@section('main_content')
    <div class="container-fluid">
        {{-- Date-wise Filter Bar (Default: Today) --}}
        <div class="dashboard-filter-bar shadow-sm border-0 rounded-3 mt-2 mb-3 bg-white" style="min-height: auto !important; height: auto !important;">
            <div class="py-2 px-3">
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
                <div class="icons bg-green2-light">
                    <img src="{{ asset('assets/images/icons/currencies.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="balance"> {{ currency_format(auth()->user()->balance) }} </h5>
                    <p>{{ __('Your Balance') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-green2-light">
                    <img src="{{ asset('assets/img/icon/crown-king.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="subscription_subscribe">{{ $stats['subscription_subscribe'] ?? '' }}</h5>
                    <p>{{ __('Tariff Rate (NM / M)') }}</p>
                </div>
            </div>
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
            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/user1.png') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="groups">{{ $stats['groups'] ?? 0 }}</h5>
                    <p>{{ __('Total Groups') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/user1.png') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="contacts">{{ $stats['contacts'] ?? 0 }}</h5>
                    <p>{{ __('Total Contacts') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/suggestions.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="templates">{{ $stats['templates'] ?? 0 }}</h5>
                    <p>{{ __('Total Templates') }}</p>
                </div>
            </div>
        </div>

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
                            <h4 class="mb-0">{{ __('Recent Messages') }}</h4>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal py-1 px-2" id="table-period-badge" style="font-size: 11px;">{{ __('Today') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive border-1 rounded-3 rounded-image">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('SL') }}.</th>
                                    <th>{{ __('Senderid ID') }}</th>
                                    <th>{{ __('Number') }}</th>
                                    <th>{{ __('Source') }}</th>
                                    <th>{{ __('Contents') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                                </thead>
                                <tbody id="recent-messages-tbody">
                                    @include('users.dashboard.message-datas')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" value="{{ route('users.dashboard.data') }}" id="get-dashboard">
    <input type="hidden" value="{{ route('users.dashboard.sms-overview') }}" id="get-sms-overview">
    <input type="hidden" value="{{ route('users.dashboard.statistics') }}" id="yearly-statistics-url">
    <input type="hidden" value="{{ get_option('notice')['status'] ?? '' }}" id="has-notice">

@endsection

@push('js')
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/user-dashboard.js') }}?v={{ time() }}"></script>
@endpush

@push('modal')
<div class="modal fade" id="notice-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ __('Important Notice') }}</h1>
        </div>
        <div class="modal-body">
            <h3 class="my-3">{{ get_option('notice')['title'] ?? '' }}</h3>
            <p class="mb-3">{{ get_option('notice')['description'] ?? '' }}</p>
            <button type="button" class="btn btn-primary btn-sm mt-3 text-light text-end float-end agree-btn" data-bs-dismiss="modal">{{ __('Agree') }}</button>
        </div>
    </div>
</div>
</div>
@endpush
