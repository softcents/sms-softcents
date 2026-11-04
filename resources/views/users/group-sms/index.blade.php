@extends('layouts.users.master')

@section('title')
    {{__('Send Group SMS') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4">
                        <h4>{{ __('Send Group SMS') }}</h4>
                    </div>
                    <div class="tab-content order-summary-tab mt-1 quick-sms">
                        <div class="tab-pane fade mt-1 show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('users.group-sms.store') }}" method="post" id="group-sms-form" class="ajaxform_instant_reload">
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
                                                        <option value="{{ $senderid->id }}" @selected($senderid->is_default)>{{ $senderid->sender }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 text-end mt-4">
                                                <label>{{ __('Select Group') }} </label>
                                            </div>
                                            <div class="col-sm-9 mt-4">
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="group_id" id="group_id" required="" class="form-control select-dropdown">
                                                        <option value="">{{ __('Select a group') }}</option>
                                                        @foreach ($groups as $group)
                                                        <option value="{{ $group->id }}" data-contacts="{{ $group->contacts_count ?? 0 }}">{{ $group->name }} ({{ $group->contacts_count ?? 0 }} contacts)</option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
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
                                                <div id="the-count" class="pull-right mt-1" data-rate="{{ auth()->user()->non_masking_rate ?? 0.39 }}" data-balance="{{ (float)auth()->user()->balance }}">
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
                                                    <button type="button" class="theme-btn m-2" id="btn-trigger-sms-modal">
                                                        <i class="fas fa-paper-plane me-1"></i> {{ __('Send') }}
                                                    </button>
                                                    <button type="submit" class="d-none submit-btn" id="real-submit-btn"></button>
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
@include('users.components.sms-confirm-modal')
<div class="modal fade" id="sms-template-modal" tabindex="-1" aria-labelledby="multi-delete-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('SMS Templates') }}</h5>
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
    <script src="{{ asset('assets/js/custom/sms.js') }}?v={{ time() }}"></script>
    <script>
        $(document).ready(function () {
            // Live trigger confirmation modal for Group SMS
            $(document).off('click', '#btn-trigger-sms-modal').on('click', '#btn-trigger-sms-modal', function (e) {
                e.preventDefault();

                var senderSelect = $('select[name="senderid_id"]');
                var groupSelect = $('#group_id');
                var contentInput = $('#contents');

                // Basic Validations
                if (!senderSelect.val()) {
                    if (typeof Notify === 'function') {
                        Notify('error', { responseJSON: { message: 'Please select a Sender ID.' } });
                    } else {
                        alert('Please select a Sender ID.');
                    }
                    senderSelect.focus();
                    return;
                }

                if (!groupSelect.val()) {
                    if (typeof Notify === 'function') {
                        Notify('error', { responseJSON: { message: 'Please select a contact group.' } });
                    } else {
                        alert('Please select a contact group.');
                    }
                    groupSelect.focus();
                    return;
                }

                var selectedOption = groupSelect.find('option:selected');
                var contactsCount = parseInt(selectedOption.data('contacts')) || 0;

                var contentText = (contentInput.val() || '').trim();
                if (!contentText) {
                    if (typeof Notify === 'function') {
                        Notify('error', { responseJSON: { message: 'Please enter message content.' } });
                    } else {
                        alert('Please enter message content.');
                    }
                    contentInput.focus();
                    return;
                }

                // Recipients count from selected group
                var recipientsCount = contactsCount;

                // Accurate Character count & Unicode detection
                var charCount = Array.from(contentText).length;
                var isUnicode = /[^\u0000-\u007F]/.test(contentText);
                var charPerSms = isUnicode ? 70 : 160;
                var charPerSmsLong = isUnicode ? 67 : 153;
                var effectiveCharPerSms = charCount > charPerSms ? charPerSmsLong : charPerSms;
                var parts = charCount > 0 ? Math.ceil(charCount / effectiveCharPerSms) : 1;

                // Rate & Cost
                var rate = parseFloat($('#the-count').attr('data-rate')) || 0.39;
                var unitCost = parts * rate;
                var totalCost = (unitCost * Math.max(1, recipientsCount)).toFixed(2);

                // User Balance: Read from data-balance or blade user balance
                var userBalanceAttr = $('#the-count').attr('data-balance');
                var oldBalance = userBalanceAttr !== undefined && userBalanceAttr !== null && userBalanceAttr !== '' 
                    ? parseFloat(userBalanceAttr) 
                    : parseFloat("{{ (float)auth()->user()->balance }}");
                if (isNaN(oldBalance)) oldBalance = 0;
                var newBalance = Math.max(0, oldBalance - parseFloat(totalCost)).toFixed(2);

                var senderText = senderSelect.find('option:selected').text().trim();
                var groupName = selectedOption.text().split('(')[0].trim();
                var charInfo = charCount + ' Chars (' + (isUnicode ? 'বাংলা / Unicode' : 'Text / English') + ')';

                // Populate Modal Elements
                $('#modal-old-balance').text('৳' + oldBalance.toFixed(2));
                $('#modal-new-balance').text('৳' + newBalance);
                $('#modal-total-cost').text(totalCost);
                $('#modal-sender-id').text(senderText);
                $('#modal-recipients-count').text(recipientsCount + (recipientsCount === 1 ? ' Contact (' : ' Contacts (') + groupName + ')');
                $('#modal-sms-parts').text(parts + (parts > 1 ? ' Parts' : ' Part'));
                $('#modal-sms-rate').text('৳' + rate.toFixed(2));
                $('#modal-char-info').text(charInfo);
                $('#modal-content-preview').text(contentText);

                // Show Modal
                $('#sms-confirm-modal').modal('show');
            });

            // Confirm & Send Button in Modal
            $(document).off('click', '#btn-confirm-send-sms').on('click', '#btn-confirm-send-sms', function () {
                var $btn = $(this);
                var originalHtml = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Sending...');

                $('#sms-confirm-modal').modal('hide');

                // Trigger the real form submit
                setTimeout(function () {
                    $('#group-sms-form').find('#real-submit-btn').trigger('click');
                    $btn.prop('disabled', false).html(originalHtml);
                }, 200);
            });
        });
    </script>
@endpush
