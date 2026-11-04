@extends('layouts.users.master')

@section('title')
    {{__('Send Quick SMS') }}
@endsection

@section('main_content')
    <style>
        .qs-box-left {
            background: #f8faff;
            border: 1.5px solid #c7d2fe;
            border-top: 3.5px solid #4f46e5;
            border-radius: 8px;
            padding: 10px 12px;
            box-shadow: 0 1px 3px rgba(79, 70, 229, 0.06);
        }
        .qs-box-right {
            background: #fbfdfc;
            border: 1.5px solid #a7f3d0;
            border-top: 3.5px solid #059669;
            border-radius: 8px;
            padding: 10px 12px;
            box-shadow: 0 1px 3px rgba(5, 150, 105, 0.06);
        }
        .qs-label-blue {
            color: #312e81 !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
        }
        .qs-label-green {
            color: #065f46 !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
        }
        .qs-input-blue {
            border: 1px solid #a5b4fc !important;
            border-radius: 6px !important;
            background: #ffffff !important;
        }
        .qs-input-blue:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.15) !important;
        }
        .qs-input-green {
            border: 1px solid #6ee7b7 !important;
            border-radius: 6px !important;
            background: #ffffff !important;
        }
        .qs-input-green:focus {
            border-color: #059669 !important;
            box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.15) !important;
        }
        .btn-send-sms-vibrant {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 6px !important;
            box-shadow: 0 3px 8px rgba(16, 185, 129, 0.35) !important;
            font-weight: 700 !important;
            transition: all 0.2s ease;
        }
        .btn-send-sms-vibrant:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.45) !important;
        }
        .btn-cancel-soft {
            background: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            font-weight: 600 !important;
        }
        .btn-cancel-soft:hover {
            background: #e2e8f0 !important;
            color: #1e293b !important;
        }
        @media (max-width: 991.98px) {
            .quick-sms-compact-section .card-body {
                padding: 6px 8px !important;
            }
            .qs-box-left, .qs-box-right {
                padding: 6px 8px !important;
                margin-bottom: 6px !important;
            }
            .qs-box-left select.form-control {
                height: 30px !important;
                font-size: 11px !important;
                padding: 2px 6px !important;
            }
            textarea#numbers {
                height: 46px !important;
                min-height: 42px !important;
                max-height: 70px !important;
                font-size: 11.5px !important;
                padding: 4px 6px !important;
            }
            textarea#contents {
                height: 52px !important;
                min-height: 46px !important;
                max-height: 80px !important;
                font-size: 11.5px !important;
                padding: 4px 6px !important;
            }
            #the-count {
                padding: 3px 6px !important;
                margin-bottom: 4px !important;
            }
            #the-count .badge {
                padding: 2px 5px !important;
                font-size: 10px !important;
            }
            .quick-sms-compact-section .button-group {
                margin-top: 4px !important;
                padding-top: 2px !important;
            }
            .quick-sms-compact-section .button-group .btn {
                height: 32px !important;
                line-height: 18px !important;
                font-size: 12px !important;
                padding: 5px 8px !important;
            }
        }
    </style>

    <div class="erp-table-section quick-sms-compact-section">
        <div class="container-fluid px-2 px-sm-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-2 p-sm-3 p-lg-4">
                    {{-- Page Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 13.5px;">
                            <span class="badge me-1 px-1.5 py-1" style="background: #4f46e5; color: #fff;"><i class="fas fa-bolt"></i></span>
                            {{ __('Send Quick SMS') }}
                        </h6>
                        <span class="badge bg-warning-subtle text-dark border border-warning" style="font-size: 11px;">
                            <i class="fas fa-wallet text-warning-emphasis me-1"></i>{{ __('Balance') }}: <strong>৳{{ number_format((float)auth()->user()->balance, 2) }}</strong>
                        </span>
                    </div>

                    <div class="quick-sms quick-sms-two-column">
                        <div class="order-form-section">
                            <form action="{{ route('users.quick-sms.store') }}" method="post" id="quick-sms-form" class="ajaxform_instant_reload">
                                @csrf

                                <div class="row g-2 g-lg-3">
                                    {{-- বাম পাশে (Left: Blue theme - Sender & Numbers) --}}
                                    <div class="col-12 col-lg-6">
                                        <div class="qs-box-left h-100">
                                            {{-- Header badge --}}
                                            <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom" style="border-color: #c7d2fe !important;">
                                                <span class="badge" style="background: #4f46e5; color: #ffffff; font-size: 11px; padding: 4px 8px;">
                                                    <i class="fas fa-user-tag me-1"></i>1. {{ __('Sender & Numbers') }}
                                                </span>
                                                <span class="badge" id="recipient-quick-counter" style="background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; font-size: 10.5px; padding: 3px 6px;">
                                                    0 numbers
                                                </span>
                                            </div>

                                            {{-- Row 1: Sender ID & Schedule --}}
                                            <div class="row g-2 mb-2">
                                                <div class="col-6">
                                                    <label class="form-label qs-label-blue mb-1">{{ __('Sender ID') }} <span class="text-danger">*</span></label>
                                                    <div class="gpt-up-down-arrow position-relative w-100">
                                                        <select name="senderid_id" required class="form-control select-dropdown qs-input-blue w-100 py-1" style="height: 34px; font-size: 12px;">
                                                            <option value="">{{ __('Select Sender') }}</option>
                                                            @foreach ($senderids as $senderid)
                                                            <option value="{{ $senderid->id }}" @selected($senderid->is_default)>{{ $senderid->sender }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span></span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label qs-label-blue mb-1">{{ __('Schedule') }} <span class="text-danger">*</span></label>
                                                    <div class="gpt-up-down-arrow position-relative w-100">
                                                        <select name="schedule_type" required class="form-control select-dropdown timer-option qs-input-blue w-100 py-1" style="height: 34px; font-size: 12px;">
                                                            <option value="sendnow">{{ __('Send Now') }}</option>
                                                            <option value="schedule">{{ __('Schedule') }}</option>
                                                        </select>
                                                        <span></span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Schedule Datetime (hidden by default) --}}
                                            <div class="mb-2 d-none schedule-sms">
                                                <label class="form-label qs-label-blue mb-1">{{ __('Schedule Time') }} <span class="text-danger">*</span></label>
                                                <input type="datetime-local" name="schedule" required class="form-control qs-input-blue w-100 py-1" style="height: 34px; font-size: 12px;">
                                            </div>

                                            {{-- Enter Numbers --}}
                                            <div class="mb-1">
                                                <label class="form-label qs-label-blue mb-1">
                                                    {{ __('Recipients Numbers') }} <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="numbers" id="numbers" rows="3" class="form-control qs-input-blue font-monospace w-100" placeholder="{{ __('88017..., 88018... (comma separated)') }}" style="font-size: 12px; line-height: 1.35; resize: vertical; min-height: 50px; height: 54px; padding: 6px 8px;"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ডান পাশে (Right: Green theme - Message & Actions) --}}
                                    <div class="col-12 col-lg-6">
                                        <div class="qs-box-right h-100 d-flex flex-column">
                                            {{-- Header badge & Template --}}
                                            <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom" style="border-color: #a7f3d0 !important;">
                                                <span class="badge" style="background: #059669; color: #ffffff; font-size: 11px; padding: 4px 8px;">
                                                    <i class="fas fa-comment-dots me-1"></i>2. {{ __('Message Content') }}
                                                </span>
                                                <div class="d-flex align-items-center gap-1">
                                                    <div class="d-flex align-items-center gap-1 bg-white px-2 py-0.5 rounded border border-success-subtle" style="font-size: 11px;">
                                                        <div class="form-check form-check-inline mb-0 me-1">
                                                            <input class="form-check-input text" type="radio" name="is_unicode_test" id="text" style="margin-top: 2px;">
                                                            <label class="form-check-label text-dark" for="text" style="font-size: 11px;">Eng</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mb-0 me-0">
                                                            <input class="form-check-input unicode" type="radio" name="is_unicode_test" id="unicode" checked style="margin-top: 2px;">
                                                            <label class="form-check-label text-success fw-bold" for="unicode" style="font-size: 11px;">বাংলা</label>
                                                        </div>
                                                    </div>
                                                    <a id="sms-templates" data-url="{{ route('users.get-templates') }}" class="badge text-decoration-none py-1 px-2" href="javascript:void(0)" style="background: #7c3aed; color: #ffffff; font-size: 11px;">
                                                        <i class="fas fa-file-alt me-1"></i>{{ __('Template') }}
                                                    </a>
                                                </div>
                                            </div>

                                            {{-- Message Textarea --}}
                                            <div class="mb-1 flex-grow-1">
                                                <textarea name="contents" id="contents" rows="3" class="form-control qs-input-green w-100" placeholder="{{ __('Type your SMS message here...') }}" style="font-size: 12.5px; line-height: 1.35; resize: vertical; min-height: 56px; height: 60px; padding: 6px 8px;"></textarea>
                                            </div>

                                            {{-- Multi-Color Counter & Cost Chips --}}
                                            <div id="the-count" class="py-1 px-2 rounded mb-2 d-flex flex-wrap align-items-center justify-content-between gap-1" data-rate="{{ auth()->user()->non_masking_rate ?? 0.39 }}" data-balance="{{ (float)auth()->user()->balance }}" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 11px;">
                                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                                    <span class="badge" style="background: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe; font-size: 10.5px;">
                                                        <i class="fas fa-font me-0.5"></i> <strong id="current">0</strong> Chars
                                                    </span>
                                                    <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 10.5px;">
                                                        <i class="fas fa-layer-group me-0.5"></i> <strong id="no_of_sms">0</strong> SMS
                                                    </span>
                                                    <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 10.5px;">
                                                        <strong id="char_per_sms">160</strong>/SMS
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="badge" style="background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; font-size: 11px; font-weight: 700;">
                                                        <i class="fas fa-coins me-1"></i>Cost: ৳<span id="est_cost">0.00</span>
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Action Buttons --}}
                                            <div class="button-group d-flex justify-content-end align-items-center gap-2 mt-auto pt-1">
                                                <a href="" class="btn btn-cancel-soft m-0 text-center" style="min-width: 80px; padding: 6px 14px; font-size: 12.5px; height: 34px; line-height: 20px;">
                                                    {{ __('Cancel') }}
                                                </a>
                                                <button type="button" class="btn btn-send-sms-vibrant m-0 shadow-sm" id="btn-trigger-sms-modal" style="min-width: 120px; padding: 6px 18px; font-size: 13px; height: 34px; line-height: 20px;">
                                                    <i class="fas fa-paper-plane me-1"></i> {{ __('Send SMS') }}
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
