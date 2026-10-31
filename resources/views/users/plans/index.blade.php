@extends('layouts.users.master')

@section('title')
    {{__('Plans List') }}
@endsection

@section('main_content')
    <div class="erp-table-section plans-list">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header">
                        <h4>{{__('Subscription Plan List')}}</h4>
                    </div>

                    <div class="row">
                        @foreach ($plans as $plan)
                        <div class="col-sm-6 col-md-4 col-lg-4 mt-4">
                            <div class="cards border-0">
                                <div class="card-header py-3 border-0">
                                    <h4 class="fw-bold text-light">{{ $plan->title }}</h4>
                                </div>
                                <div class="card-body">
                                    <h2 class="py-1">{{ currency_format($plan->price) }} <small>/ {{ str_replace("_", " ", $plan->duration) }}</small></h2>
                                    <h6>{{ $plan->total_sms }} {{ __('SMS') }}</h6>

                                    <ul>
                                        <li><i class="fas {{ $plan->non_masking_rate ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} me-1"></i> {{ __('Non Masking Rate') }} {{ $plan->non_masking_rate }}</li>

                                        <li><i class="fas {{ $plan->masking_rate ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} me-1"></i> {{ __('Masking Rate') }} {{ $plan->masking_rate }}</li>

                                        @foreach ($plan->features ?? [] as $key => $item)
                                        <li><i class="fas {{ isset($item[1]) ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} me-1"></i> {{ $item[0] ?? '' }}</li>
                                        @endforeach
                                    </ul>

                                    @if (auth()->user()->will_expire > now() && $plan->id == auth()->user()->plan_id)
                                    <a href="javascript:void(0)" class="btn btn-light d-block mt-3 mb-2 border subscribe-plan-background"> {{ __('Already Subscribed') }} </a>
                                    @else
                                    <a href="{{ route('payments-gateways.index', ['plan_id' => $plan->id]) }}" class="btn subscribe-plan text-custom-primary d-block mt-3 mb-2"> {{ __('Buy Now') }} </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <nav>
                        <ul class="pagination">
                            <li class="page-item">{{ $plans->links('pagination::bootstrap-5') }}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
