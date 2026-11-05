@extends('layouts.users.master')

@section('title')
    {{__('Send Quick SMS') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body p-3 p-sm-4">
                    <div class="table-header mb-3 mb-sm-4">
                        <h4>{{ __('Send Quick SMS') }}</h4>
                    </div>
                    <div class="tab-content order-summary-tab mt-1 quick-sms">
                        <div class="tab-pane fade mt-1 show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('users.quick-sms.store') }}" method="post" id="quick-sms-form" class="ajaxform_instant_reload">
                                    @csrf

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">
                                            <div class="col-12 col-md-3 text-start text-md-end mt-2 mt-md-4 align-self-md-center">
                                                <label class="form-label mb-0 fw-bold">{{ __('Sender ID') }} : </label>
                                            </div>
                                            <div class="col-12 col-md-9 mt-1 mt-md-4">
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

                                            <div class="col-12 col-md-3 text-start text-md-end mt-2 mt-md-4 align-self-md-center">
                                                <label class="form-label mb-0 fw-bold">{{ __('Select SMS schedule :') }} </label>
                                            </div>
                                            <div class="col-12 col-md-9 mt-1 mt-md-4">
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="schedule_type" required="" class="form-control select-dropdown timer-option">
                                                        <option value="sendnow">{{ __('Send Now') }}</option>
                                                        <option value="schedule">{{ __('Schedule') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-3 text-start text-md-end mt-2 mt-md-4 d-none schedule-sms align-self-md-center">
                                                <label class="form-label mb-0 fw-bold">{{ __('Schedule SMS :') }} </label>
                                            </div>
                                            <div class="col-12 col-md-9 mt-1 mt-md-4 d-none schedule-sms">
                                                <input type="datetime-local" name="schedule" required class="form-control">
                                            </div>

                                            <div class="col-12 col-md-3 text-start text-md-end mt-2 mt-md-4">
                                                <label class="form-label mb-0 fw-bold">{{ __('Enter Numbers') }} : </label>
                                            </div>
                                            <div class="col-12 col-md-9 mt-1 mt-md-4">
                                                <textarea name="numbers" id="numbers" rows="3" class="form-control" placeholder="{{ __('Please start with country code and separated by comma. Ex: 880186545, 880186545') }}"></textarea>
                                            </div>

                                            <div class="col-12 col-md-3 text-start text-md-end mt-2 mt-md-4 align-self-md-center">
                                                <label class="form-label mb-0 fw-bold">{{ __('Select SMS Type :') }} </label>
                                            </div>
                                            <div class="col-12 col-md-9 mt-1 mt-md-4">
                                                <div class="d-flex gap-3">
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

                                            <div class="col-12 col-md-3 text-start text-md-end mt-2 mt-md-4">
                                                <label class="form-label mb-0 fw-bold">{{ __('Message Content') }} : </label>
                                            </div>
                                            <div class="col-12 col-md-9 mt-1 mt-md-4">
                                                <textarea name="contents" id="contents" rows="4" class="form-control" placeholder="{{ __('Enter your message content here') }}."></textarea>
                                                <div id="the-count" class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-2" data-rate="{{ auth()->user()->non_masking_rate ?? 0.39 }}" data-balance="{{ (float)auth()->user()->balance }}">
                                                    <div class="d-flex flex-wrap align-items-center gap-2 small text-muted">
                                                        <span><strong id="current" class="text-dark">0</strong> {{ __('Characters') }}</span>
                                                        <span class="text-primary fw-bold">|</span>
                                                        <span><strong id="no_of_sms" class="text-dark">0</strong> {{ __('SMS') }}</span>
                                                        <span class="text-primary fw-bold">|</span>
                                                        <span class="text-danger"><strong id="char_per_sms">160</strong> {{ __('Char per sms') }}</span>
                                                        <span class="text-primary fw-bold">|</span>
                                                        <span class="badge bg-danger ms-1" style="font-size: 13px;">{{ __('Estimated Cost:') }} ৳<span id="est_cost">0.00</span></span>
                                                    </div>
                                                    <a id="sms-templates" data-url="{{ route('users.get-templates') }}" class="badge bg-primary text-decoration-none py-1 px-2" href="javascript:void(0)">
                                                        <i class="fas fa-file-alt me-1"></i>{{ __('Insert SMS Template') }}
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="button-group text-center mt-4 mt-sm-5 d-flex flex-column flex-sm-row justify-content-center align-items-center gap-2">
                                                    <a href="" class="theme-btn border-btn m-1" style="min-width: 140px;">{{ __('Cancel') }}</a>
                                                    <button type="button" class="theme-btn m-1" id="btn-trigger-sms-modal" style="min-width: 140px;">
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
            // Live trigger confirmation modal
            $(document).off('click', '#btn-trigger-sms-modal').on('click', '#btn-trigger-sms-modal', function (e) {
                e.preventDefault();

                var senderSelect = $('select[name="senderid_id"]');
                var numbersInput = $('#numbers');
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

                var rawNums = (numbersInput.val() || '').trim();
                if (!rawNums) {
                    if (typeof Notify === 'function') {
                        Notify('error', { responseJSON: { message: 'Please enter at least one recipient phone number.' } });
                    } else {
                        alert('Please enter at least one recipient phone number.');
                    }
                    numbersInput.focus();
                    return;
                }

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

                // Recipients count
                var validNums = rawNums.split(',').map(function (n) { return n.trim(); }).filter(function (n) { return n.length >= 5; });
                var recipientsCount = Math.max(1, validNums.length);

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
                var totalCost = (unitCost * recipientsCount).toFixed(2);

                // User Balance: Read from data-balance or blade user balance
                var userBalanceAttr = $('#the-count').attr('data-balance');
                var oldBalance = userBalanceAttr !== undefined && userBalanceAttr !== null && userBalanceAttr !== '' 
                    ? parseFloat(userBalanceAttr) 
                    : parseFloat("{{ (float)auth()->user()->balance }}");
                if (isNaN(oldBalance)) oldBalance = 0;
                var newBalance = Math.max(0, oldBalance - parseFloat(totalCost)).toFixed(2);

                var senderText = senderSelect.find('option:selected').text().trim();
                var charInfo = charCount + ' Chars (' + (isUnicode ? 'বাংলা / Unicode' : 'Text / English') + ')';

                // Populate Modal Elements
                $('#modal-old-balance').text('৳' + oldBalance.toFixed(2));
                $('#modal-new-balance').text('৳' + newBalance);
                $('#modal-total-cost').text(totalCost);
                $('#modal-sender-id').text(senderText);
                $('#modal-recipients-count').text(recipientsCount + (recipientsCount > 1 ? ' Numbers' : ' Number'));
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
                    $('#quick-sms-form').find('#real-submit-btn').trigger('click');
                    $btn.prop('disabled', false).html(originalHtml);
                }, 200);
            });
        });
    </script>
@endpush
