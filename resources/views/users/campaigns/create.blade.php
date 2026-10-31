@extends('layouts.users.master')

@section('title')
    {{ __('Campaigns SMS') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4">
                        <h4>{{ __('Campaigns SMS') }}</h4>
                    </div>
                    <div class="tab-content order-summary-tab mt-1 quick-sms">
                        <div class="tab-pane fade mt-1 show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('users.campaigns.store') }}" method="post" class="ajaxform_instant_reload">
                                    @csrf

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">

                                            <div class="col-sm-3 text-end mt-4">
                                                <label>{{ __('Sender ID') }} : </label>
                                            </div>
                                            <div class="col-sm-9 mt-4">
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="senderid_id" required class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a sender id') }}</option>
                                                        @foreach ($senderids as $senderid)
                                                        <option value="{{ $senderid->id }}">{{ $senderid->sender }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 text-end mt-4">
                                                <label>{{ __('Recipients') }} : </label>
                                            </div>
                                            <div class="col-sm-9 mt-4">
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="campaign_id" required="" class="form-control select-dropdown select-campaign">
                                                        <option value="">{{ __('Select Recipients') }}</option>
                                                        @foreach ($campaigns as $campaign)
                                                        <option data-total_numbers="{{ $campaign->total_numbers }}" value="{{ $campaign->id }}">{{ $campaign->name . ' - ' . $campaign->total_numbers }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 text-end mt-4">
                                                <label>{{ __('SMS Range') }} : </label>
                                            </div>
                                            <div class="col-sm-9 mt-4">
                                                <input type="number" name="range" required class="form-control total-range" placeholder="Enter total numbers of sms you want to send.">
                                            </div>

                                            <div class="col-sm-3 text-end mt-4">
                                                <label>{{ __('Select SMS schedule :') }} </label>
                                            </div>
                                            <div class="col-sm-9 mt-4">
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="schedule_type" required="" class="form-control select-dropdown timer-option">
                                                        <option value="sendnow">{{ __('Send Now') }}</option>
                                                        <option value="schedule">{{ __('Schedule') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 mt-4 text-end d-none schedule-sms">
                                                <label>{{ __('Schedule SMS :') }} </label>
                                            </div>
                                            <div class="col-lg-9 mt-4 d-none schedule-sms">
                                                <input type="datetime-local" name="schedule" required class="form-control">
                                            </div>

                                            <div class="col-sm-3 text-end mt-4">
                                                <label>{{ __('Select SMS Type :') }} </label>
                                            </div>
                                            <div class="col-sm-9 mt-4">
                                                <div class="d-flex">
                                                    <div class="form-check">
                                                        <input class="form-check-input text" type="radio" name="is_unicode_test" id="text">
                                                        <label class="form-check-label" for="text">
                                                            {{ __('Text') }}
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input unicode" type="radio" name="is_unicode_test" id="unicode" checked>
                                                        <label class="form-check-label" for="unicode">
                                                            {{ __('Unicode') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 text-end mt-4">
                                                <label>{{ __('Message Content') }} : </label>
                                            </div>
                                            <div class="col-sm-9 mt-4">
                                                <textarea name="contents" id="contents" class="form-control" placeholder="{{ __('Enter your message content here') }}."></textarea>
                                                <div id="the-count" class="pull-right mt-1">
                                                    <span id="current">{{ __('0') }}</span> <span class="ps-4">{{ __('Characters') }}</span> <span class="text-primary fw-bold
                                                    ">|</span>
                                                    <span id="no_of_sms"> {{ __('0') }} </span> {{ __('SMS') }} <span class="text-primary fw-bold">|</span>
                                                    <span class="text-danger"><span id="char_per_sms"> {{ __('160') }} </span> {{ __('Char per sms') }}</span>
                                                    <a id="sms-templates" data-url="{{ route('users.get-templates') }}" class="badge bg-primary" href="javascript:void(0)">{{ __('Insert SMS Template') }}</a>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
<div class="modal fade" id="sms-template-modal" tabindex="-1" aria-labelledby="multi-delete-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">SMS Templates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body pt-0">
                <div class="responsive-table">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}.</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Texts') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="templates-list">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush

@push('js')
    <script src="{{ asset('assets/js/custom/sms.js') }}"></script>
@endpush
