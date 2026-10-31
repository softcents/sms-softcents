@extends('layouts.admin.master')

@section('title')
    {{ __('Add new plan') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm ">
                <div class="card-body">
                    @include('admin.plans.buttons')
                    <div class="tab-content order-summary-tab">
                        <div class="tab-pane fade show active" id="add-new-user">
                            <div class="table-header">
                                <h4>{{ __('Add new plan') }}</h4>
                            </div>
                            <div class="order-form-section">
                                <form action="{{ route('admin.plans.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                                    @csrf

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Title') }}</label>
                                                <input type="text" name="title" required class="form-control" placeholder="{{ __('Enter plan name') }}" >
                                            </div>

                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Price') }}</label>
                                                <input type="number" step="any" name="price" required class="form-control" placeholder="{{ __('Enter Plan Price') }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Masking Sms Rate') }}</label>
                                                <input type="number" name="masking_rate" required class="form-control" placeholder="{{ __('Enter masking sms rate') }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Non Masking Sms Rate') }}</label>
                                                <input type="number" name="non_masking_rate" required class="form-control" placeholder="{{ __('Enter non masking sms rate') }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('SMS') }}</label>
                                                <input type="number" name="total_sms" required class="form-control" placeholder="{{ __('Enter total sms') }}">
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{__('Allow Api?')}}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="allow_api" required="" class="form-control select-dropdown">
                                                        <option value="1" selected>{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Duration') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="duration" required="" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a Plan') }}</option>
                                                        <option value="weekly">{{ __('Weekly') }}</option>
                                                        <option value="15_days">{{ __('15 Days') }}</option>
                                                        <option value="monthly">{{ __('Monthly') }}</option>
                                                        <option value="3_monthly">{{ __('3-Monthly') }}</option>
                                                        <option value="6_monthly">{{ __('6-Monthly') }}</option>
                                                        <option value="yearly">{{ __('Yearly') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Status') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="status" required="" class="form-control select-dropdown">
                                                        <option value="1">{{ __('Active') }}</option>
                                                        <option value="0">{{ __('Deactive') }}</option>
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
                                                    {{-- Will added dynamically. --}}
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
                                {{-- form end --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
