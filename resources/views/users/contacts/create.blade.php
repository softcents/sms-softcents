@extends('layouts.users.master')

@section('title')
    {{ __('Add new Contact') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4">
                        <h4>{{ __('Add New Contact') }}</h4>
                        <a href="{{ route('users.contacts.index') }}" class="theme-btn print-btn text-light">
                            <i class="fas fa-plus-circle me-1"></i>
                            {{ __('Manage List') }}
                        </a>
                    </div>
                    <div class="tab-content order-summary-tab">
                        <div class="tab-pane fade show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('users.contacts.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                                    @csrf

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">

                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Select Group') }}</label>
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

                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Number') }}</label>
                                                <input type="text" name="number" required class="form-control" placeholder="{{ __('Enter number') }}" >
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
                                                <label>{{ __('Username') }}</label>
                                                <input type="text" name="name" class="form-control" placeholder="{{ __('Enter name here') }}">
                                            </div>

                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('User E-mail') }}</label>
                                                <input type="email" name="email" class="form-control" placeholder="{{ __('Enter email here') }}">
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
