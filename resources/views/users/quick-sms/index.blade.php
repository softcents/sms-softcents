@extends('layouts.users.master')

@section('title')
    {{__('Send Quick SMS') }}
@endsection

@section('main_content')
    <div class="erp-table-section quick-sms-compact-section">
        <div class="container-fluid px-2 px-sm-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-2 p-sm-3 p-lg-4">
                    {{-- Page Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">
                            <i class="fas fa-paper-plane text-primary me-1"></i>{{ __('Send Quick SMS') }}
                        </h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle small px-2 py-0" style="font-size: 11px;">
                            <i class="fas fa-bolt me-1"></i>{{ __('Fast Dispatch') }}
                        </span>
                    </div>

                    <div class="quick-sms quick-sms-two-column">
                        <div class="order-form-section">
                            <form action="{{ route('users.quick-sms.store') }}" method="post" id="quick-sms-form" class="ajaxform_instant_reload">
                                @csrf

                                <div class="row g-2 g-lg-4">
                                    {{-- বাম পাশে (Left: Sender & Numbers) --}}
                                    <div class="col-12 col-lg-6">
                                        {{-- Row 1: Sender ID & Schedule --}}
                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <label class="form-label fw-bold text-dark mb-1" style="font-size: 11.5px;">{{ __('Sender ID') }} <span class="text-danger">*</span></label>
                                                <div class="gpt-up-down-arrow position-relative w-100">
                                                    <select name="senderid_id" required class="form-control select-dropdown w-100 py-1" style="height: 34px; font-size: 12px;">
                                                        <option value="">{{ __('Select Sender') }}</option>
                                                        @foreach ($senderids as $senderid)
                                                        <option value="{{ $senderid->id }}" @selected($senderid->is_default)>{{ $senderid->sender }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-bold text-dark mb-1" style="font-size: 11.5px;">{{ __('Schedule') }} <span class="text-danger">*</span></label>
                                                <div class="gpt-up-down-arrow position-relative w-100">
                                                    <select name="schedule_type" required class="form-control select-dropdown timer-option w-100 py-1" style="height: 34px; font-size: 12px;">
                                                        <option value="sendnow">{{ __('Send Now') }}</option>
                                                        <option value="schedule">{{ __('Schedule') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Schedule Datetime (hidden by default) --}}
                                        <div class="mb-2 d-none schedule-sms">
                                            <label class="form-label fw-bold text-dark mb-1" style="font-size: 11.5px;">{{ __('Schedule Time') }} <span class="text-danger">*</span></label>
                                            <input type="datetime-local" name="schedule" required class="form-control w-100 py-1" style="height: 34px; font-size: 12px;">
                                        </div>

                                        {{-- Enter Numbers --}}
                                        <div class="mb-2 mb-lg-0">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <label class="form-label fw-bold text-dark mb-0" style="font-size: 11.5px;">
                                                    {{ __('Numbers') }} <span class="text-danger">*</span>
                                                </label>
                                                <span class="badge bg-light text-secondary border py-0 px-1" id="recipient-quick-counter" style="font-size: 10.5px;">0 numbers</span>
                                            </div>
                                            <textarea name="numbers" id="numbers" rows="3" class="form-control font-monospace w-100" placeholder="{{ __('88017..., 88018... (comma separated)') }}" style="font-size: 12px; line-height: 1.35; resize: vertical; min-height: 52px; height: 56px; padding: 6px 8px;"></textarea>
                                        </div>
                                    </div>

                                    {{-- ডান পাশে (Right: Message & Actions) --}}
                                    <div class="col-12 col-lg-6 d-flex flex-column">
                                        {{-- Type & Template Bar --}}
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold text-dark" style="font-size: 11.5px;">{{ __('Type') }}:</span>
                                                <div class="form-check form-check-inline mb-0 me-1">
                                                    <input class="form-check-input text" type="radio" name="is_unicode_test" id="text" style="margin-top: 2px;">
                                                    <label class="form-check-label text-dark" for="text" style="font-size: 11.5px;">{{ __('English') }}</label>
                                                </div>
                                                <div class="form-check form-check-inline mb-0 me-0">
                                                    <input class="form-check-input unicode" type="radio" name="is_unicode_test" id="unicode" checked style="margin-top: 2px;">
                                                    <label class="form-check-label text-dark" for="unicode" style="font-size: 11.5px;">{{ __('বাংলা') }}</label>
                                                </div>
                                            </div>
                                            <a id="sms-templates" data-url="{{ route('users.get-templates') }}" class="badge bg-primary text-decoration-none py-1 px-2" href="javascript:void(0)" style="font-size: 11px;">
                                                <i class="fas fa-file-alt me-1"></i>{{ __('Template') }}
                                            </a>
                                        </div>

                                        {{-- Message Textarea --}}
                                        <div class="mb-1 flex-grow-1">
                                            <textarea name="contents" id="contents" rows="3" class="form-control w-100" placeholder="{{ __('Type your message here...') }}" style="font-size: 12.5px; line-height: 1.35; resize: vertical; min-height: 60px; height: 64px; padding: 6px 8px;"></textarea>
                                        </div>

                                        {{-- Compact Counter & Cost --}}
                                        <div id="the-count" class="py-1 px-2 bg-light rounded border mb-2 d-flex flex-wrap align-items-center justify-content-between" data-rate="{{ auth()->user()->non_masking_rate ?? 0.39 }}" data-balance="{{ (float)auth()->user()->balance }}" style="font-size: 11px;">
                                            <div class="d-flex align-items-center gap-1 text-muted">
                                                <span><strong id="current" class="text-dark">0</strong> Chars</span>
                                                <span class="text-muted">|</span>
                                                <span><strong id="no_of_sms" class="text-dark">0</strong> SMS</span>
                                                <span class="text-muted">|</span>
                                                <span class="text-danger"><strong id="char_per_sms">160</strong>/SMS</span>
                                            </div>
                                            <div class="fw-semibold text-danger">
                                                <span>Cost: ৳<span id="est_cost">0.00</span></span>
                                            </div>
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="button-group d-flex justify-content-end align-items-center gap-2 mt-auto pt-1">
                                            <a href="" class="theme-btn border-btn m-0 text-center" style="min-width: 80px; padding: 6px 14px; font-size: 12.5px; height: 34px; line-height: 20px;">{{ __('Cancel') }}</a>
                                            <button type="button" class="theme-btn m-0 fw-bold shadow-sm" id="btn-trigger-sms-modal" style="min-width: 120px; padding: 6px 18px; font-size: 13px; height: 34px; line-height: 20px;">
                                                <i class="fas fa-paper-plane me-1"></i> {{ __('Send SMS') }}
                                            </button>
                                            <button type="submit" class="d-none submit-btn" id="real-submit-btn"></button>
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
            function updateRecipientCount() {
                var rawNums = ($('#numbers').val() || '').trim();
                var validNums = rawNums.split(',').map(function (n) { return n.trim(); }).filter(function (n) { return n.length >= 5; });
                var count = validNums.length;
                $('#recipient-quick-counter').text(count + (count === 1 ? ' number' : ' numbers'));
            }
            $('#numbers').on('input change', updateRecipientCount);
            updateRecipientCount();

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
