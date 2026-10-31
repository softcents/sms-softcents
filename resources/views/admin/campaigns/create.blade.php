@extends('layouts.admin.master')

@section('title')
    {{ __('Create Campaign') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4">
                        <h4>{{ __('Create Campaign') }}</h4>
                        <a href="{{ route('admin.campaigns.index') }}" class="theme-btn print-btn text-light">
                            <i class="fas fa-plus-circle me-1"></i>
                            {{ __('Manage List') }}
                        </a>
                    </div>
                    <div class="tab-content order-summary-tab">
                        <div class="tab-pane fade mt-1 show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('admin.campaigns.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                                    @csrf

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Campaign Name') }}</label>
                                                <input type="text" name="name" required class="form-control" placeholder="{{ __('Enter campaign name') }}">
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Status') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="status" required class="form-control select-dropdown">
                                                        <option value="1">{{ __('Active') }}</option>
                                                        <option value="0">{{ __('Deactive') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Numbers List') }}</label>
                                                <textarea name="numbers" class="form-control" required placeholder="{{ __('Please start with country code and separated by comma, Ex: 880186545, 880186545') }}"></textarea>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Campaign Description') }}</label>
                                                <textarea name="description" class="form-control" placeholder="{{ __('Enter campaign description') }}"></textarea>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
