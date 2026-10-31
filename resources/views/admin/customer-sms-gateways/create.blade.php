@extends('layouts.admin.master')

@section('title')
    {{ __('Create sender id') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4">
                        <h4>{{ __('Create sender id') }}</h4>
                        <a href="{{ route('admin.senderids.index') }}" class="theme-btn print-btn text-light">
                            <i class="fas fa-plus-circle me-1"></i>
                            {{ __('Manage List') }}
                        </a>
                    </div>
                    <div class="tab-content order-summary-tab">
                        <div class="tab-pane fade mt-1 show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('admin.senderids.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                                    @csrf

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Select Customer (Optional)') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="user_id" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a customer') }}</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}">{{ $user->name .' ('. $user->phone .')' }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Sender Id') }}</label>
                                                <input type="text" name="sender" required class="form-control" placeholder="{{ __('Enter sender id') }}">
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Type') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="type" required="" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a type') }}</option>
                                                        <option value="Masking">{{ __('Masking') }}</option>
                                                        <option value="Non-Masking">{{ __('Non-Masking') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Is Default') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="is_default" required="" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select One') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                        <option value="0">{{ __('No') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Status') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="status" required="" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a status') }}</option>
                                                        <option value="1">{{ __('Active') }}</option>
                                                        <option value="0">{{ __('Deactive') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="button-group text-center mt-5">
                                                    <a href="" class="theme-btn border-btn m-2">{{ __('Cancel') }}</a>
                                                    <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
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
