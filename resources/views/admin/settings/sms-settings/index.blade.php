@extends('layouts.admin.master')

@section('title')
    {{ __('Sms Settings') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header">
                        <h4>{{__('Sms Settings')}}</h4>
                    </div>
                    <div class="order-form-section sms-setting">
                        <form action="{{ route('admin.sms-settings.store') }}" method="post" class="ajaxform">
                            @csrf

                            <div class="add-suplier-modal-wrapper d-block">
                                <div class="row">
                                    <div class="col-sm-6 mt-3">
                                        <label>{{ __('Character per SMS (Entire Message Counts as 1 SMS)') }}</label>
                                        <input type="number" name="text_char_per_sms" value="{{ get_option('sms-settings')['text_char_per_sms'] ?? '' }}" required class="form-control" placeholder="{{ __('Enter the Total Character for an SMS (Text)') }}">
                                    </div>

                                    <div class="col-sm-6 mt-3">
                                        <label>{{ __('Number of Character Allowed per Long SMS (Entire Message Comprising 1 SMS)') }}</label>
                                        <input type="number" name="long_text_char_per_sms" value="{{ get_option('sms-settings')['long_text_char_per_sms'] ?? '' }}"  required class="form-control" placeholder="{{ __('Enter the Total Character for a Long SMS (Text)') }}">
                                    </div>

                                    <div class="col-sm-6 mt-3">
                                        <label>{{ __('Unicode Character per SMS (Entire Message Counts as 1 SMS)') }}</label>
                                        <input type="number" name="unicode_char_per_sms" value="{{ get_option('sms-settings')['unicode_char_per_sms'] ?? '' }}"  required class="form-control" placeholder="{{ __('Enter the Total Character for an SMS (Unicode)') }}">
                                    </div>

                                    <div class="col-sm-6 mt-3">
                                        <label>{{ __('Unicode Character per Long SMS (Entire Message Counts as 1 SMS)') }}</label>
                                        <input type="number" name="long_unicode_char_per_sms" value="{{ get_option('sms-settings')['long_unicode_char_per_sms'] ?? '' }}" required class="form-control" placeholder="{{ __('Enter the Total Character for a Long SMS (Unicode)') }}">
                                    </div>

                                    @can('sms-settings-update')
                                    <div class="col-lg-12">
                                        <div class="text-center mt-5">
                                            <button type="submit" class="theme-btn m-2 submit-btn">{{ __('Update') }}</button>
                                        </div>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


