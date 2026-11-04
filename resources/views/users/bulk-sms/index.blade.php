@extends('layouts.users.master')

@section('title')
    {{__('Send Bulk SMS') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body p-3 p-sm-4">
                    <div class="table-header mb-3 mb-sm-4">
                        <h4>{{ __('Send Bulk SMS') }}</h4>
                    </div>
                    <div class="tab-content order-summary-tab mt-1 quick-sms">
                        <div class="tab-pane fade mt-1 show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('users.bulk-sms.store') }}" method="post" id="bulk-sms-form" class="ajaxform_instant_reload">
                                    @csrf

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">
                                            <div class="col-12 col-sm-3 text-start text-sm-end mt-3 mt-sm-4 align-self-sm-center">
                                                <label class="form-label mb-0 fw-bold">{{ __('Sender ID') }} : </label>
                                            </div>
                                            <div class="col-12 col-sm-9 mt-1 mt-sm-4">
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

                                            <div class="col-12 col-sm-3 text-start text-sm-end mt-3 mt-sm-4 align-self-sm-center">
                                                <label class="form-label mb-0 fw-bold">{{ __('Select SMS schedule :') }} </label>
                                            </div>
                                            <div class="col-12 col-sm-9 mt-1 mt-sm-4">
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="schedule_type" required="" class="form-control select-dropdown timer-option">
                                                        <option value="sendnow">{{ __('Send Now') }}</option>
                                                        <option value="schedule">{{ __('Schedule') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-3 text-start text-sm-end mt-3 mt-sm-4 d-none schedule-sms align-self-sm-center">
                                                <label class="form-label mb-0 fw-bold">{{ __('Schedule SMS :') }} </label>
                                            </div>
                                            <div class="col-12 col-sm-9 mt-1 mt-sm-4 d-none schedule-sms">
                                                <input type="datetime-local" name="schedule" required class="form-control">
                                            </div>

                                            <div class="col-12 col-sm-3 text-start text-sm-end mt-3 mt-sm-4 align-self-sm-center">
                                                <label class="form-label mb-0 fw-bold">{{ __('Upload file (.csv/.ods/.xlsx/.xls)') }}</label>
                                            </div>
                                            <div class="col-12 col-sm-9 mt-1 mt-sm-4">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-12 col-md-7">
                                                        <input type="file" name="file" id="bulk-file-input" required class="form-control" accept=".csv, .ods, .xlsx, .xls">
                                                        <div id="file-status-badge" class="mt-1 d-none"></div>
                                                    </div>
                                                    <div class="col-12 col-md-5">
                                                        <a href="{{ route('users.download-numbers-file') }}" class="theme-btn print-btn text-light w-100 d-inline-flex align-items-center justify-content-center text-decoration-none" style="min-width: unset; padding: 9px 12px;">
                                                            <img src="{{ asset('assets/images/icons/download-excel.svg') }}" alt="" class="me-2" style="height: 18px; width: 18px;">
                                                            <span>{{ __('Download Example File') }}</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-3 text-start text-sm-end mt-3 mt-sm-4 align-self-sm-center">
                                                <label class="form-label mb-0 fw-bold">{{ __('Range Start') }} <span class="text-danger">*</span> : </label>
                                            </div>
                                            <div class="col-12 col-sm-9 mt-1 mt-sm-4">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light text-muted small px-2">{{ __('Start') }}</span>
                                                            <input type="number" name="range_start" id="range_start" required class="form-control" value="1" min="1" placeholder="1">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light text-muted small px-2">{{ __('End') }}</span>
                                                            <input type="text" name="range_end" id="range_end" class="form-control" value="All" placeholder="All">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <small class="text-muted" id="range-preview-text">
                                                        <i class="fas fa-info-circle me-1"></i>{{ __('Sends from number 1 to All numbers in the file') }}
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-3 text-start text-sm-end mt-3 mt-sm-4 align-self-sm-center">
                                                <label class="form-label mb-0 fw-bold">{{ __('Select SMS Type :') }} </label>
                                            </div>
                                            <div class="col-12 col-sm-9 mt-1 mt-sm-4">
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

                                            <div class="col-12 col-sm-3 text-start text-sm-end mt-3 mt-sm-4">
                                                <label class="form-label mb-0 fw-bold">{{ __('Message Content') }} : </label>
                                            </div>
                                            <div class="col-12 col-sm-9 mt-1 mt-sm-4">
                                                <textarea name="contents" id="contents" rows="4" class="form-control" placeholder="{{ __('Enter your message content here') }}."></textarea>
                                                <div id="the-count" class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-2" data-rate="{{ auth()->user()->non_masking_rate ?? 0.39 }}" data-balance="{{ (float)auth()->user()->balance }}">
                                                    <div class="d-flex flex-wrap align-items-center gap-2 small text-muted">
                                                        <span><strong id="current" class="text-dark">0</strong> {{ __('Characters') }}</span>
                                                        <span class="text-primary fw-bold">|</span>
                                                        <span><strong id="no_of_sms" class="text-dark">0</strong> {{ __('SMS') }}</span>
                                                        <span class="text-primary fw-bold">|</span>
                                                        <span class="text-danger"><strong id="char_per_sms">160</strong> {{ __('Char per sms') }}</span>
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
    <script src="{{ asset('assets/js/xlsx.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/sms.js') }}?v={{ time() }}"></script>
    <script>
        $(document).ready(function () {
            window.lastTotalFileNumbers = 0;

            function calculateBulkRange(totalAvailable) {
                totalAvailable = totalAvailable || window.lastTotalFileNumbers || 0;
                var startVal = parseInt($('#range_start').val()) || 1;
                var endVal = ($('#range_end').val() || '').trim();

                var count = 0;
                var desc = '';
                if (endVal.toLowerCase() === 'all' || endVal === '') {
                    if (totalAvailable > 0) {
                        count = Math.max(1, totalAvailable - startVal + 1);
                        desc = 'Sends to <strong>' + count + '</strong> numbers (From #' + startVal + ' to #' + totalAvailable + ')';
                    } else {
                        count = 1;
                        desc = 'Sends from #' + startVal + ' to <strong>All</strong> numbers in the file';
                    }
                } else {
                    var endNum = parseInt(endVal) || startVal;
                    count = endNum >= startVal ? (endNum - startVal + 1) : endNum;
                    desc = 'Sends to <strong>' + count + '</strong> numbers (From #' + startVal + ' to #' + (startVal + count - 1) + ')';
                }

                $('#range-preview-text').html('<i class="fas fa-info-circle me-1"></i>' + desc);
                return { count: count, desc: desc, start: startVal, end: endVal };
            }

            // Real-time preview updates
            $(document).on('input change', '#range_start, #range_end', function () {
                calculateBulkRange();
            });

            // On file select
            $(document).on('change', 'input[name="file"]', function () {
                if (this.files && this.files.length > 0) {
                    var file = this.files[0];
                    window.parseBulkSmsNumberCount(file, function (totalCount) {
                        window.lastTotalFileNumbers = totalCount;
                        calculateBulkRange(totalCount);
                    });
                }
            });

            // Live trigger confirmation modal for Bulk SMS
            $(document).off('click', '#btn-trigger-sms-modal').on('click', '#btn-trigger-sms-modal', function (e) {
                e.preventDefault();

                var senderSelect = $('select[name="senderid_id"]');
                var fileInput = $('input[name="file"]');
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

                if (!fileInput[0].files || fileInput[0].files.length === 0) {
                    if (typeof Notify === 'function') {
                        Notify('error', { responseJSON: { message: 'Please upload an Excel or CSV file containing phone numbers.' } });
                    } else {
                        alert('Please upload an Excel or CSV file containing phone numbers.');
                    }
                    fileInput.focus();
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

                var file = fileInput[0].files[0];
                var fileName = file.name;
                var $btn = $(this);
                var originalHtml = $btn.html();

                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Reading file...');

                // Accurate Character count & Unicode detection
                var charCount = Array.from(contentText).length;
                var isUnicode = /[^\u0000-\u007F]/.test(contentText);
                var charPerSms = isUnicode ? 70 : 160;
                var charPerSmsLong = isUnicode ? 67 : 153;
                var effectiveCharPerSms = charCount > charPerSms ? charPerSmsLong : charPerSms;
                var parts = charCount > 0 ? Math.ceil(charCount / effectiveCharPerSms) : 1;

                // Rate & Unit Cost
                var rate = parseFloat($('#the-count').attr('data-rate')) || 0.39;
                var unitCost = parts * rate;

                // User Balance: Read from data-balance or blade user balance
                var userBalanceAttr = $('#the-count').attr('data-balance');
                var oldBalance = userBalanceAttr !== undefined && userBalanceAttr !== null && userBalanceAttr !== '' 
                    ? parseFloat(userBalanceAttr) 
                    : parseFloat("{{ (float)auth()->user()->balance }}");
                if (isNaN(oldBalance)) oldBalance = 0;

                var senderText = senderSelect.find('option:selected').text().trim();
                var charInfo = charCount + ' Chars (' + (isUnicode ? 'বাংলা / Unicode' : 'Text / English') + ')';

                // Parse number count from Excel or CSV file
                window.parseBulkSmsNumberCount(file, function (totalFileCount) {
                    $btn.prop('disabled', false).html(originalHtml);
                    window.lastTotalFileNumbers = totalFileCount;

                    var rangeInfo = calculateBulkRange(totalFileCount);
                    var count = Math.max(1, rangeInfo.count);
                    var totalCost = (unitCost * count).toFixed(2);
                    var newBalance = Math.max(0, oldBalance - parseFloat(totalCost)).toFixed(2);

                    var rangeBadge = rangeInfo.end.toLowerCase() === 'all' ? '#' + rangeInfo.start + ' to All' : '#' + rangeInfo.start + ' to #' + (rangeInfo.start + count - 1);

                    $('#modal-old-balance').text('৳' + oldBalance.toFixed(2));
                    $('#modal-new-balance').text('৳' + newBalance);
                    $('#modal-total-cost').text(totalCost);
                    $('#modal-sender-id').text(senderText);
                    $('#modal-recipients-count').text(count + (count === 1 ? ' Number (' : ' Numbers (') + fileName + ' [' + rangeBadge + '])');
                    $('#modal-sms-parts').text(parts + (parts > 1 ? ' Parts' : ' Part'));
                    $('#modal-sms-rate').text('৳' + rate.toFixed(2));
                    $('#modal-char-info').text(charInfo);
                    $('#modal-content-preview').text(contentText);

                    $('#sms-confirm-modal').modal('show');
                });
            });

            // Confirm & Send Button in Modal
            $(document).off('click', '#btn-confirm-send-sms').on('click', '#btn-confirm-send-sms', function () {
                var $btn = $(this);
                var originalHtml = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Sending...');

                $('#sms-confirm-modal').modal('hide');

                // Trigger the real form submit
                setTimeout(function () {
                    $('#bulk-sms-form').find('#real-submit-btn').trigger('click');
                    $btn.prop('disabled', false).html(originalHtml);
                }, 200);
            });
        });
    </script>
@endpush
