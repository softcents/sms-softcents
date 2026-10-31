@extends('layouts.admin.master')

@section('title')
    {{ __('Cron Jobs Settings') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">

            <div class="cards-header shadow-sm">
                <div class="card-body">
                    <h4>{{ __('Cron Jobs Settings') }}</h4>
                </div>
            </div>

            <div class="cards shadow-sm mt-3 border-0">
                <div class="card-header">
                    <h5>{{ __('SMS status check cron job') }}</h5>
                </div>
                <div class="card-body">
                    <div class="order-form-section">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="{{ route('cron.sms-status') }}" aria-describedby="basic-addon5">
                            <button class="input-group-text btn btn-primary text-light copyLink" data-text="{{ route('cron.sms-status') }}" id="basic-addon5"><i class="fas fa-copy"></i></button>
                        </div>
                        <p>{{ __('SMS status check cron job. This cron job will run every 1 minute.') }}</p>
                    </div>
                </div>
            </div>

            <div class="cards shadow-sm mt-3 border-0">
                <div class="card-header">
                    <h5>{{ __('Schedule message cron job.') }}</h5>
                </div>
                <div class="card-body">
                    <div class="order-form-section">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="{{ route('cron.schedule-messages') }}" aria-describedby="basic-addon5">
                            <button class="input-group-text btn btn-primary text-light copyLink" data-text="{{ route('cron.schedule-messages') }}" id="basic-addon5"><i class="fas fa-copy"></i></button>
                        </div>
                        <p>{{ __('Schedule message cron job. This cron job will run every 1 minute.') }}</p>
                    </div>
                </div>
            </div>

            <div class="cards shadow-sm mt-3 border-0">
                <div class="card-header">
                    <h5>{{ __('Low balance notify cron job.') }}</h5>
                </div>
                <div class="card-body">
                    <div class="order-form-section">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="{{ route('cron.low-blanace-notify') }}" aria-describedby="basic-addon5">
                            <button class="input-group-text btn btn-primary text-light copyLink" data-text="{{ route('cron.low-blanace-notify') }}" id="basic-addon5"><i class="fas fa-copy"></i></button>
                        </div>
                        <p>{{ __('Low balance notify cron job. This cron job will send a notification to the superadmin when the user balance is low. This cron job will run every 12 hours, or whatever you prefer.') }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


