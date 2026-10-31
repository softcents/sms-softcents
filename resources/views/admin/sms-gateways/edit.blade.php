@extends('layouts.admin.master')

@section('title')
    {{ __("Edit gateway") }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="add-new-user">
                            <div class="table-header">
                                <h4>{{ __("Edit gateway") }}</h4>
                                @can('smsgateways-read')
                                <a href="{{ route('admin.sms-gateways.index') }}" class="theme-btn print-btn text-light"><i class="fas fa-list me-1"></i> {{ __("SMS Gateways") }}</a>
                                @endcan
                            </div>
                            <div class="order-form-section">
                                <form action="{{ route('admin.sms-gateways.update', $gateway->id) }}" method="post" class="ajaxform_instant_reload">
                                    @csrf
                                    @method('put')
                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">

                                            <div class="col-sm-6 mb-2">
                                                <label>{{ __('Gateway Name') }}</label>
                                                <input type="text" name="name" value="{{ $gateway->name }}"  class="form-control" placeholder="{{ __('Enter gateway name') }}">
                                            </div>

                                            <div class="col-lg-6 mb-2">
                                                <label>{{ __('Gateway Priority') }}</label>
                                                <input type="number" step="any" name="priority" value="{{ $gateway->priority }}" class="form-control" placeholder="{{ __('Enter gateway priority here') }}">
                                            </div>

                                            <div class="col-sm-6 mb-2">
                                                <label>{{ __('Gateway Type') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="type_id" required="" class="form-control select-dropdown gateway-type">
                                                        <option value="1">{{ __('Select a type') }}</option>
                                                        @foreach ($types as $type)
                                                        <option @selected($type->id == $gateway->type_id) data-names='@json($type->inputs['names'] ?? [])' data-labels='@json($type->inputs['labels'] ?? [])' value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 mb-2">
                                                <label>{{ __('Status') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="status" required="" class="form-control select-dropdown">
                                                        <option @selected($gateway->status == 1) value="1">{{ __('Active') }}</option>
                                                        <option @selected($gateway->status == 0) value="0">{{ __('Deactive') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="row" id="input-container">
                                                    @foreach ($gateway->params ?? [] as $key => $param)
                                                    <div class="col-sm-6 mb-2">
                                                        <label>{{ ucfirst($key) }}</label>
                                                        <input type="text" name="params[{{ $key }}]" value="{{ $param }}" class="form-control" placeholder="{{ ucfirst($key) }}">
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="button-group text-center mt-5">
                                                    <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                                    <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
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
