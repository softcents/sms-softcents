@extends('layouts.admin.master')

@section('title')
    {{__ ('Add Currency') }}
@endsection

@section('main_content')
    <div class="order-form-section">
        <div class="erp-table-section">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="table-header">
                            <h4>{{__('Add Currency')}}</h4>
                            @can('currencies-create')
                                <a href="{{ route('admin.currencies.index') }}" class="add-order-btn rounded-2">
                                    <i class="fas fa-list me-1"></i>
                                    {{ __('View List') }}
                                </a>
                            @endcan
                        </div>
                        <form action="{{ route('admin.currencies.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 mt-2">
                                    <label>{{__('Name')}}</label>
                                    <input type="text" name="name" required class="form-control" placeholder="{{ __('Enter Name') }}">
                                </div>

                                <div class="col-lg-6 mt-2">
                                    <label>{{__('Code')}}</label>
                                    <input type="text" name="code" required class="form-control" placeholder="{{ __('Enter Code') }}">
                                </div>

                                <div class="col-lg-6 mt-2">
                                    <label>{{__('Symbol')}}</label>
                                    <input type="text" name="symbol" class="form-control" placeholder="{{ __('Enter Symbol') }}">
                                </div>

                                <div class="col-lg-6 mt-2">
                                    <label>{{__('Position')}}</label>
                                    <select name="position" class="form-control table-select w-100">
                                        <option value="">{{__('Select a position')}}</option>
                                        <option value="left">{{__('left')}}</option>
                                        <option value="right">{{__('right')}}</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mt-2">
                                    <label>{{__('Status')}}</label>
                                    <select name="status" required class="form-control table-select w-100">
                                        <option value="1">{{__('Active')}}</option>
                                        <option value="0">{{__('Inactive')}}</option>
                                    </select>
                                </div>

                                <div class="col-lg-12">
                                    <div class="button-group text-center mt-5">
                                        <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                        <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

