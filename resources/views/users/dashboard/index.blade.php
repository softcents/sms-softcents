@extends('layouts.users.master')

@section('title')
    {{__('Dashboard') }}
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="gpt-dashboard-card grid-5 mt-30 mb-30">
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
                    <img src="{{ asset('assets/img/icon/subscription.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="subscription_subscribe"></h5>
                    <p>{{ __('Plan Subscribed') }}</p>
                </div>
            </div>
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
            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/user1.png') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="groups"></h5>
                    <p>{{ __('Total Groups') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/user1.png') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="contacts"></h5>
                    <p>{{ __('Total Contacts') }}</p>
                </div>
            </div>
            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/suggestions.svg') }}" alt="">
                </div>
                <div class="content-side">
                    <h5 id="templates"></h5>
                    <p>{{ __('Total Templates') }}</p>
                </div>
            </div>
        </div>

        <div class="row gpt-dashboard-chart">
            <div class="col-xxl-8 col-xl-7 col-lg-7 mb-30">
                <div class="cards  dashboard-card new-card border-0 p-0">
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
                        <h4>{{ __('Recent Messages') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive border-1 rounded-3 rounded-image">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('SL') }}.</th>
                                    <th>{{ __('Senderid ID') }}</th>
                                    <th>{{ __('Number') }}</th>
                                    <th>{{ __('Contents') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
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
    <script src="{{ asset('assets/plugins/custom/user-dashboard.js') }}"></script>
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
