@extends('layouts.users.master')

@section('title')
    {{ __('Upload bulk contacts') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4">
                        <h4>{{ __('Upload Bulk Contacts') }}</h4>
                    </div>
                    <div class="tab-content order-summary-tab mt-1 quick-sms">
                        <div class="tab-pane fade mt-1 show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('users.bulk-contacts.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                                    @csrf

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">
                                            <div class="col-sm-3 text-end mt-4">
                                                <label>{{ __('Select Group') }}</label>
                                            </div>
                                            <div class="col-sm-9 mt-4">
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="group_id" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a Group') }}</option>
                                                        @foreach ($groups as $group)
                                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 text-end mt-4">
                                                <label>{{ __('Upload file (.csv/.ods/.xlsx/.xls)') }}</label>
                                            </div>
                                            <div class="col-sm-9 mt-4">
                                                <div class="row">
                                                    <div class="col-6 align-self-center">
                                                        <input type="file" name="file" required class="form-control" accept=".csv, .ods, .xlsx, .xls">
                                                    </div>
                                                    <div class="col-6 align-self-center">
                                                        <a href="{{ route('users.download-contacts-file') }}" class="theme-btn print-btn text-light w-100">
                                                            <img src="{{ asset('assets/images/icons/download-excel.svg') }}" alt="">
                                                            {{ __('Download Example File') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 text-end mt-4">
                                                <label>{{ __('Status') }}</label>
                                            </div>
                                            <div class="col-sm-9 mt-4">
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="status" required="" class="form-control select-dropdown">
                                                        <option value="1">{{ __('Active') }}</option>
                                                        <option value="0">{{ __('Deactive') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="button-group text-center mt-5">
                                                    <button type="reset" class="theme-btn border-btn m-2">{{__('Reset')}}</button>
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
