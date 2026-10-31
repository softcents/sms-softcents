@extends('layouts.admin.master')

@section('title')
    {{__('Plans List') }}
@endsection

@section('main_content')
    <div class="erp-table-section plans-list">
        <div class="container-fluid">
            <div class="card shadow-sm h-0">
                <div class="card-body mb-3">
                    <div class="table-header">
                        <h4>{{__('Subscription Plan List')}}</h4>
                        <a href="{{ route('admin.plans.create') }}" class="theme-btn print-btn text-light">
                            <i class="far fa-plus" aria-hidden="true"></i>
                            {{ __('Add new plan') }}
                        </a>
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
                                </div>
                            </div>
                            <div class="action-buttons text-center mt-3">
                                @can('plans-update')
                                <a href="{{ route('admin.plans.edit', $plan->id) }}"><i class="far fa-edit"></i></a>
                                @endcan
                                @can('plans-delete')
                                <a  href="{{ route('admin.plans.destroy', $plan->id) }}" class="confirm-action" data-method="DELETE"><i class="fas fa-trash-alt"></i></a>
                                @endcan
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

@push('modal')
    @include('admin.components.multi-delete-modal')
@endpush
