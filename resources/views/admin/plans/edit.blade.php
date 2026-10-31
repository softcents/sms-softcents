@extends('layouts.admin.master')

@section('title')
    {{ __('Edit plan') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4">
                        <h4>{{ __('Edit Plan') }}</h4>
                        <a href="{{ route('admin.plans.index') }}" class="theme-btn print-btn text-light">
                            <i class="fas fa-plus-circle me-1"></i>
                            {{ __('Manage List') }}
                        </a>
                    </div>
                    <div class="tab-content order-summary-tab">
                        <div class="tab-pane fade show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('admin.plans.update', $plan->id) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                                    @csrf
                                    @method('put')

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Title') }}</label>
                                                <input type="text" name="title" value="{{ $plan->title }}" required class="form-control" placeholder="{{ __('Enter plan name') }}" >
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Price') }}</label>
                                                <input type="number" step="any" name="price" value="{{ $plan->price }}" required class="form-control" placeholder="{{ __('Enter Plan Price') }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Masking Sms Rate') }}</label>
                                                <input type="number" name="masking_rate" required step="any" value="{{ $plan->masking_rate }}" class="form-control" placeholder="{{ __('Enter masking sms rate') }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Non Masking Sms Rate') }}</label>
                                                <input type="number" name="non_masking_rate" required step="any" value="{{ $plan->non_masking_rate }}" class="form-control" placeholder="{{ __('Enter non masking sms rate') }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Total SMS') }}</label>
                                                <input type="number" name="total_sms" value="{{ $plan->total_sms }}" required class="form-control" placeholder="{{ __('Enter total sms') }}">
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{__('Allow Api?')}}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="allow_api" required="" class="form-control select-dropdown">
                                                        <option @selected($plan->allow_api == 1) value="1" selected>{{ __('Yes') }}</option>
                                                        <option @selected($plan->allow_api == 0) value="0">{{ __('No') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Duration') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="duration" required="" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a Plan') }}</option>
                                                        <option @selected($plan->duration == 'weekly') value="weekly">{{ __('Weekly') }}</option>
                                                        <option @selected($plan->duration == '15_days') value="15_days">{{ __('15 Days') }}</option>
                                                        <option @selected($plan->duration == 'monthly') value="monthly">{{ __('Monthly') }}</option>
                                                        <option @selected($plan->duration == '3_monthly') value="3_monthly">{{ __('3-Monthly') }}</option>
                                                        <option @selected($plan->duration == '6_monthly') value="6_monthly">{{ __('6-Monthly') }}</option>
                                                        <option @selected($plan->duration == 'yearly') value="yearly">{{ __('Yearly') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Status') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="status" required="" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a status') }}</option>
                                                        <option @selected($plan->status == '1') value="1">{{ __('Active') }}</option>
                                                        <option @selected($plan->status == '0') value="0">{{ __('Deactive') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Add New Features') }}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control add-feature border-0 bg-transparent" placeholder="{{ __('Enter features') }}">
                                                    <button class="feature-btn" id="feature-btn">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="row feature-list">
                                                    @foreach ($plan->features ?? [] as $key => $item)
                                                    <div class="col-lg-6 mt-4">
                                                        <div class="form-control d-flex justify-content-between align-items-center">
                                                            <input name="features[features_{{ $key }}][]" class="form-control" type="text" value="{{ $item[0] ?? '' }}">
                                                            <label class="switch m-0">
                                                                <input type="checkbox" name="features[features_{{ $key }}][]" @checked(isset($item[1])) value="1">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="button-group text-center mt-5">
                                                    <a href="" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                                                    <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
