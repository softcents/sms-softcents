@extends('layouts.users.master')

@section('title')
    {{ __('Send Quick SMS') }}
@endsection

@section('main_content')
    <style>
        /* Modern Enterprise SaaS Quick SMS Design */
        :root {
            --qs-primary: #4f46e5;
            --qs-primary-hover: #4338ca;
            --qs-primary-soft: #eef2ff;
            --qs-secondary: #10b981;
            --qs-secondary-hover: #059669;
            --qs-secondary-soft: #ecfdf5;
            --qs-border: #e2e8f0;
            --qs-bg: #f8fafc;
            --qs-text-main: #1e293b;
            --qs-text-muted: #64748b;
        }

        .quick-sms-root {
            padding: 12px 0 24px 0;
        }

        /* Top Banner */
        .qs-top-banner {
            background: #ffffff;
            border: 1px solid var(--qs-border);
            border-radius: 12px;
            padding: 14px 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            margin-bottom: 16px;
        }
        .qs-page-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--qs-text-main);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .qs-page-subtitle {
            font-size: 13px;
            color: var(--qs-text-muted);
            margin-top: 2px;
        }

        /* Section Cards */
        .qs-card {
            background: #ffffff;
            border: 1px solid var(--qs-border);
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .qs-card:hover {
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        }
        .qs-card-indigo {
            border-top: 4px solid var(--qs-primary);
        }
        .qs-card-emerald {
            border-top: 4px solid var(--qs-secondary);
        }
        .qs-card-review {
            border: 1px solid #bfdbfe;
            border-top: 4px solid #0284c7;
            background: #ffffff;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.06);
            margin-top: 14px;
        }

        /* Card Section Header */
        .qs-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            margin-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }
        .qs-card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--qs-text-main);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .qs-badge-step {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
        }
        .qs-badge-step.indigo { background: var(--qs-primary); }
        .qs-badge-step.emerald { background: var(--qs-secondary); }

        /* Form Labels & Controls */
        .qs-label {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--qs-text-main);
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .qs-input {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13.5px;
            color: var(--qs-text-main);
            background-color: #ffffff;
            height: 40px;
            transition: all 0.2s ease;
            width: 100%;
        }
        .qs-input:focus {
            border-color: var(--qs-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
            outline: none;
        }
        textarea.qs-input {
            height: auto;
            min-height: 85px;
            line-height: 1.5;
            resize: vertical;
        }

        /* Segmented Switches */
        .qs-switch-group {
            display: flex;
            background: #f1f5f9;
            padding: 3px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            width: 100%;
        }
        .qs-switch-btn {
            flex: 1;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            border-radius: 6px;
            border: none;
            background: transparent;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .qs-switch-btn.active {
            background: #ffffff;
            color: var(--qs-primary);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .qs-switch-btn.active.emerald {
            color: var(--qs-secondary);
        }

        /* Toolbar Buttons */
        .qs-btn-action {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }
        .qs-btn-action:hover {
            background: #f8fafc;
            color: var(--qs-primary);
            border-color: #94a3b8;
        }
        .qs-btn-action.danger:hover {
            color: #dc2626;
            border-color: #fca5a5;
            background: #fef2f2;
        }

        /* Telemetry & Counters Box */
        .qs-telemetry-panel {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 10px;
            margin-bottom: 8px;
        }
        .qs-stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .qs-stat-badge.purple { background: #f3e8ff; color: #581c87; border-color: #e9d5ff; }
        .qs-stat-badge.amber { background: #fef3c7; color: #78350f; border-color: #fde68a; }
        .qs-stat-badge.blue { background: #e0f2fe; color: #075985; border-color: #bae6fd; }
        .qs-stat-badge.green { background: #dcfce7; color: #14532d; border-color: #bbf7d0; }
        .qs-stat-badge.red { background: #fee2e2; color: #991b1b; border-color: #fecaca; }

        /* Review Deck Stats */
        .qs-review-item {
            display: flex;
            flex-direction: column;
        }
        .qs-review-label {
            font-size: 11.5px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
        }
        .qs-review-val {
            font-size: 14.5px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Action Buttons */
        .btn-qs-send {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 9px 24px;
            font-size: 14.5px;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 42px;
            cursor: pointer;
        }
        .btn-qs-send:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.45);
            transform: translateY(-1px);
        }
        .btn-qs-send:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-qs-reset {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.2s ease;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .btn-qs-reset:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .qs-top-banner {
                padding: 10px 14px;
                margin-bottom: 12px;
            }
            .qs-page-title {
                font-size: 15px;
            }
            .qs-card {
                padding: 14px 14px;
                margin-bottom: 10px;
            }
            .qs-card-header {
                padding-bottom: 8px;
                margin-bottom: 12px;
            }
            textarea.qs-input {
                min-height: 70px;
            }
            .qs-review-val {
                font-size: 13.5px;
            }
            .btn-qs-send {
                width: 100%;
                font-size: 14px;
                height: 40px;
            }
            .btn-qs-reset {
                width: 100%;
                font-size: 13px;
                height: 38px;
            }
        }
    </style>

    <div class="quick-sms-root">
        <div class="container-fluid px-2 px-md-3">
            {{-- Top Header Banner --}}
            <div class="qs-top-banner d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="qs-page-title">
                        <span class="rounded-circle text-white d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px; font-size: 13px; background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);">
                            <i class="fas fa-paper-plane"></i>
                        </span>
                        {{ __('Send Quick SMS') }}
                    </h5>
                    <div class="qs-page-subtitle d-none d-sm-block">
                        {{ __('Send single or multiple SMS instantly with real-time routing, balance checking and delivery estimation.') }}
                    </div>
                </div>

                {{-- Account Metrics Pills --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5" style="font-size: 12px;">
                        <span class="spinner-grow spinner-grow-sm me-1 text-success" role="status" style="width: 7px; height: 7px;"></span>
                        {{ __('Gateway Active') }}
                    </span>
                    <span class="badge bg-light text-dark border px-2.5 py-1.5" style="font-size: 12px;">
                        <i class="fas fa-tag text-primary me-1"></i>{{ __('Rate') }}: <strong>৳{{ number_format(auth()->user()->non_masking_rate ?? 0.39, 2) }}</strong>
                    </span>
                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1.5" style="font-size: 12px;">
                        <i class="fas fa-wallet text-warning-emphasis me-1"></i>{{ __('Balance') }}: <strong>৳{{ number_format((float)auth()->user()->balance, 2) }}</strong>
                    </span>
                </div>
            </div>

            {{-- Main Form Container --}}
            <form action="{{ route('users.quick-sms.store') }}" method="post" id="quick-sms-form" class="ajaxform_instant_reload">
                @csrf
                <input type="hidden" name="schedule_type" id="hidden_schedule_type" value="sendnow">
                <input type="hidden" name="char_count" id="char_count" value="0">
                <input type="hidden" name="sms_type" id="sms_type" value="1">

                <div class="row g-3">
                    {{-- ================================================= --}}
                    {{-- SECTION 1: SENDER & RECIPIENTS (Indigo Theme)     --}}
                    {{-- ================================================= --}}
                    <div class="col-12 col-lg-6">
                        <div class="qs-card qs-card-indigo">
                            {{-- Header --}}
                            <div class="qs-card-header">
                                <h6 class="qs-card-title">
                                    <span class="qs-badge-step indigo">1</span>
                                    <span>{{ __('Sender & Recipients') }}</span>
                                </h6>
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="badge bg-primary text-white" id="recipient-valid-badge" style="font-size: 12px; padding: 4px 8px;">
                                        <i class="fas fa-check-circle me-1"></i><strong id="valid-recipients-count">0</strong> {{ __('Valid') }}
                                    </span>
                                    <span class="badge bg-warning text-dark d-none" id="recipient-dup-badge" style="font-size: 12px; padding: 4px 8px;">
                                        <strong id="dup-recipients-count">0</strong> {{ __('Duplicates') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Row 1: Sender ID & Dispatch Schedule Switch --}}
                            <div class="row g-2 mb-3">
                                <div class="col-12 col-sm-6">
                                    <label class="qs-label">
                                        <span>{{ __('Sender ID') }} <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="gpt-up-down-arrow position-relative w-100">
                                        <select name="senderid_id" required class="form-control qs-input" id="senderid_select">
                                            <option value="">{{ __('Select Sender ID') }}</option>
                                            @foreach ($senderids as $senderid)
                                                <option value="{{ $senderid->id }}" @selected($senderid->is_default)>
                                                    {{ $senderid->sender }} {{ $senderid->is_default ? '('.__('Default').')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label class="qs-label">
                                        <span>{{ __('Schedule Dispatch') }} <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="qs-switch-group">
                                        <button type="button" class="qs-switch-btn active" data-schedule="sendnow" id="btn-sched-sendnow">
                                            <i class="fas fa-bolt me-1"></i>{{ __('Send Now') }}
                                        </button>
                                        <button type="button" class="qs-switch-btn" data-schedule="schedule" id="btn-sched-schedule">
                                            <i class="fas fa-clock me-1"></i>{{ __('Schedule') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Schedule Datetime Picker (conditionally shown) --}}
                            <div class="mb-3 d-none" id="schedule-datetime-container">
                                <label class="qs-label text-primary">
                                    <span><i class="fas fa-calendar-alt me-1"></i>{{ __('Select Schedule Date & Time') }} <span class="text-danger">*</span></span>
                                </label>
                                <input type="datetime-local" name="schedule" id="schedule_datetime_input" class="form-control qs-input">
                            </div>

                            {{-- Recipient Numbers Field --}}
                            <div class="flex-grow-1 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-1.5 flex-wrap gap-1">
                                    <label class="qs-label mb-0">
                                        <span>{{ __('Recipient Phone Numbers') }} <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="d-flex align-items-center gap-1.5">
                                        <button type="button" class="qs-btn-action" id="btn-insert-sample" title="{{ __('Insert Bangladeshi test number') }}">
                                            <i class="fas fa-plus-circle text-primary"></i> {{ __('+ Sample') }}
                                        </button>
                                        <button type="button" class="qs-btn-action" id="btn-clean-numbers" title="{{ __('Clean and format numbers') }}">
                                            <i class="fas fa-magic text-info"></i> {{ __('Clean') }}
                                        </button>
                                        <button type="button" class="qs-btn-action danger" id="btn-clear-numbers" title="{{ __('Clear recipient list') }}">
                                            <i class="fas fa-trash-alt"></i> {{ __('Clear') }}
                                        </button>
                                    </div>
                                </div>

                                <textarea name="numbers" id="numbers" rows="4" class="form-control qs-input font-monospace flex-grow-1" placeholder="{{ __('Enter or paste recipient phone numbers (e.g. 01700000000, 8801800000000). Accepts comma, space, or newline separated list.') }}"></textarea>

                                {{-- Validation Helper Feedback --}}
                                <div class="d-flex justify-content-between align-items-center mt-1.5 text-muted" style="font-size: 12px;">
                                    <span>
                                        <i class="fas fa-info-circle me-1 text-primary"></i>{{ __('Supports standard 8801... / 01... numbers') }}
                                    </span>
                                    <span id="number-format-indicator" class="fw-semibold text-secondary">
                                        {{ __('0 items detected') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================================================= --}}
                    {{-- SECTION 2: MESSAGE CONTENT (Emerald Theme)        --}}
                    {{-- ================================================= --}}
                    <div class="col-12 col-lg-6">
                        <div class="qs-card qs-card-emerald">
                            {{-- Header --}}
                            <div class="qs-card-header">
                                <h6 class="qs-card-title">
                                    <span class="qs-badge-step emerald">2</span>
                                    <span>{{ __('Message Content') }}</span>
                                </h6>
                                <div class="d-flex align-items-center gap-1.5">
                                    <button type="button" class="qs-btn-action" id="btn-open-preview" title="{{ __('Live Phone Mockup Preview') }}">
                                        <i class="fas fa-mobile-alt text-success"></i> {{ __('Phone Preview') }}
                                    </button>
                                    <a id="sms-templates" data-url="{{ route('users.get-templates') }}" class="qs-btn-action" href="javascript:void(0)" title="{{ __('Browse Saved Templates') }}">
                                        <i class="fas fa-file-alt text-primary"></i> {{ __('Templates') }}
                                    </a>
                                </div>
                            </div>

                            {{-- Language & Encoding Selector Bar --}}
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="qs-label mb-0">{{ __('Encoding') }}:</span>
                                    <div class="qs-switch-group" style="width: auto;">
                                        <button type="button" class="qs-switch-btn active" id="btn-lang-eng" data-type="english">
                                            English (GSM-7)
                                        </button>
                                        <button type="button" class="qs-switch-btn emerald" id="btn-lang-bng" data-type="unicode">
                                            বাংলা (Unicode)
                                        </button>
                                    </div>
                                    {{-- Hidden radio buttons to preserve backend contracts --}}
                                    <input class="d-none text" type="radio" name="is_unicode_test" id="text">
                                    <input class="d-none unicode" type="radio" name="is_unicode_test" id="unicode" checked>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-muted border px-2 py-1" id="auto-detected-badge" style="font-size: 11.5px;">
                                        <i class="fas fa-language text-secondary me-1"></i><span id="detected-encoding-text">GSM 7-bit</span>
                                    </span>
                                    <button type="button" class="qs-btn-action danger py-0.5 px-1.5" id="btn-clear-contents" title="{{ __('Clear message text') }}">
                                        <i class="fas fa-times me-0.5"></i>{{ __('Clear') }}
                                    </button>
                                </div>
                            </div>

                            {{-- Message Textarea --}}
                            <div class="flex-grow-1 d-flex flex-column mb-2">
                                <textarea name="contents" id="contents" rows="4" class="form-control qs-input flex-grow-1" placeholder="{{ __('Type your SMS message here. Bengali or Unicode characters are automatically detected...') }}"></textarea>
                            </div>

                            {{-- Live Telemetry & Calculation Box --}}
                            <div id="the-count" class="qs-telemetry-panel" data-rate="{{ auth()->user()->non_masking_rate ?? 0.39 }}" data-balance="{{ (float)auth()->user()->balance }}">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="qs-stat-badge purple" title="{{ __('Total characters typed') }}">
                                            <i class="fas fa-font"></i> <strong id="current">0</strong> / <span id="chars_per_part">160</span>
                                        </span>
                                        <span class="qs-stat-badge amber" title="{{ __('SMS segments') }}">
                                            <i class="fas fa-layer-group"></i> <strong id="no_of_sms">0</strong> SMS
                                        </span>
                                        <span class="qs-stat-badge blue" title="{{ __('Characters per SMS part') }}">
                                            <span id="char_per_sms">160</span>/SMS
                                        </span>
                                        <span class="qs-stat-badge green d-none d-sm-inline-flex" title="{{ __('Remaining characters in current part') }}">
                                            <span id="chars_remaining_badge">160 left</span>
                                        </span>
                                    </div>

                                    <div>
                                        <span class="qs-stat-badge red fw-bold" title="{{ __('Calculated total dispatch cost') }}" style="font-size: 13px;">
                                            <i class="fas fa-coins me-1"></i>Cost: ৳<span id="est_cost">0.00</span>
                                        </span>
                                    </div>
                                </div>

                                {{-- Insufficient Balance Warning Banner --}}
                                <div id="insufficient-balance-alert" class="d-none mt-2 p-1.5 bg-danger-subtle text-danger border border-danger-subtle rounded text-center" style="font-size: 12px;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>{{ __('Insufficient Balance!') }}</strong>
                                    {{ __('Estimated cost exceeds available balance.') }}
                                    <a href="{{ route('users.recharges.index') }}" class="text-danger fw-bold text-decoration-underline ms-1">{{ __('Recharge Now') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================================================= --}}
                    {{-- SECTION 3: REVIEW & DISPATCH (Summary Deck)       --}}
                    {{-- ================================================= --}}
                    <div class="col-12">
                        <div class="qs-card-review">
                            <div class="row g-3 align-items-center">
                                {{-- Summary Metrics --}}
                                <div class="col-12 col-xl-9">
                                    <div class="row g-2 g-sm-3 text-center text-sm-start">
                                        <div class="col-6 col-sm-3 col-md-2">
                                            <div class="qs-review-item">
                                                <span class="qs-review-label"><i class="fas fa-bullhorn text-primary me-1"></i>{{ __('Sender') }}</span>
                                                <span class="qs-review-val font-monospace" id="review-sender-text">{{ __('None') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3 col-md-2">
                                            <div class="qs-review-item">
                                                <span class="qs-review-label"><i class="fas fa-users text-info me-1"></i>{{ __('Recipients') }}</span>
                                                <span class="qs-review-val text-dark" id="review-recipients-text">0</span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3 col-md-2">
                                            <div class="qs-review-item">
                                                <span class="qs-review-label"><i class="fas fa-layer-group text-warning me-1"></i>{{ __('Parts') }}</span>
                                                <span class="qs-review-val text-dark" id="review-parts-text">0</span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-3 col-md-2">
                                            <div class="qs-review-item">
                                                <span class="qs-review-label"><i class="fas fa-paper-plane text-success me-1"></i>{{ __('Total SMS') }}</span>
                                                <span class="qs-review-val text-success" id="review-total-sms-text">0 SMS</span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-6 col-md-2">
                                            <div class="qs-review-item">
                                                <span class="qs-review-label"><i class="fas fa-coins text-danger me-1"></i>{{ __('Total Cost') }}</span>
                                                <span class="qs-review-val text-danger" id="review-cost-text">৳0.00</span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-6 col-md-2">
                                            <div class="qs-review-item">
                                                <span class="qs-review-label"><i class="fas fa-wallet text-secondary me-1"></i>{{ __('Remaining') }}</span>
                                                <span class="qs-review-val text-dark" id="review-remaining-text">৳{{ number_format((float)auth()->user()->balance, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="col-12 col-xl-3 text-end d-flex gap-2 justify-content-end align-items-center">
                                    <button type="button" class="btn-qs-reset" id="btn-reset-form">
                                        <i class="fas fa-undo me-1"></i> {{ __('Reset') }}
                                    </button>
                                    <button type="button" class="btn-qs-send flex-grow-1 flex-sm-grow-0" id="btn-trigger-sms-modal">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>{{ __('Send Quick SMS') }}</span>
                                    </button>
                                    <button type="submit" class="d-none submit-btn" id="real-submit-btn"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('modal')
    {{-- Confirmation Modal --}}
    @include('users.components.sms-confirm-modal')

    {{-- Interactive Phone Mockup Preview Modal --}}
    <div class="modal fade" id="sms-phone-preview-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content shadow-lg border-0" style="border-radius: 28px; overflow: hidden; background: #0f172a;">
                {{-- Phone Top Notch --}}
                <div class="py-2.5 px-3 d-flex justify-content-between align-items-center text-white-50" style="font-size: 12px;">
                    <span>9:41</span>
                    <div style="width: 80px; height: 18px; background: #1e293b; border-radius: 10px;"></div>
                    <div class="d-flex align-items-center gap-1.5">
                        <i class="fas fa-signal" style="font-size: 10px;"></i>
                        <i class="fas fa-wifi" style="font-size: 10px;"></i>
                        <i class="fas fa-battery-full" style="font-size: 11px;"></i>
                    </div>
                </div>

                {{-- Phone Screen --}}
                <div class="p-3" style="background: #ffffff; min-height: 400px; display: flex; flex-direction: column;">
                    {{-- Contact Header --}}
                    <div class="text-center pb-2.5 mb-3 border-bottom">
                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center fw-bold mb-1" style="width: 48px; height: 48px; font-size: 18px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="fw-bold text-dark font-monospace" id="preview-sender-label" style="font-size: 15px;">-</div>
                        <small class="text-muted" style="font-size: 11px;">SMS Gateway Delivery</small>
                    </div>

                    {{-- Chat Bubble Container --}}
                    <div class="flex-grow-1 d-flex flex-column justify-content-start">
                        <div class="text-center my-1.5">
                            <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 10.5px;">Today</span>
                        </div>
                        <div class="p-3 rounded-3 mb-2 shadow-xs" style="background: #e0f2fe; color: #0c4a6e; max-width: 90%; border-bottom-left-radius: 4px; font-size: 13.5px; line-height: 1.5; word-break: break-word; white-space: pre-wrap;" id="preview-bubble-content">
                            Your SMS text preview will appear here...
                        </div>
                        <div class="text-muted ms-1" style="font-size: 10px;" id="preview-timestamp">Just now</div>
                    </div>

                    {{-- Close Preview Button --}}
                    <div class="pt-2.5 border-top mt-auto text-center">
                        <button type="button" class="btn btn-light border w-100 py-1.5" data-bs-dismiss="modal" style="font-size: 13px; border-radius: 8px;">
                            {{ __('Close Preview') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Templates Selection Modal --}}
    <div class="modal fade" id="sms-template-modal" tabindex="-1" aria-labelledby="sms-template-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header bg-light py-2.5 px-3 border-bottom">
                    <h6 class="modal-title fw-bold text-dark mb-0" style="font-size: 15px;">
                        <i class="fas fa-file-alt text-primary me-2"></i>{{ __('SMS Message Templates') }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 12px;"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="datatable">
                            <thead class="table-light">
                                <tr style="font-size: 13px;">
                                    <th style="width: 60px;">{{ __('SL') }}</th>
                                    <th style="width: 160px;">{{ __('Template Name') }}</th>
                                    <th>{{ __('Message Text') }}</th>
                                    <th style="width: 100px;" class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="templates-list" style="font-size: 13.5px;">
                                {{-- Dynamically populated via AJAX --}}
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
            var $numbers = $('#numbers');
            var $contents = $('#contents');
            var $senderSelect = $('#senderid_select');
            var userBalance = parseFloat($('#the-count').attr('data-balance')) || parseFloat("{{ (float)auth()->user()->balance }}") || 0;
            var userRate = parseFloat($('#the-count').attr('data-rate')) || 0.39;

            // ----------------------------------------------------
            // 1. RECIPIENT NUMBERS ENGINE & VALIDATION
            // ----------------------------------------------------
            function parseNumbers(raw) {
                if (!raw) return { valid: [], duplicates: 0, invalid: [], totalTokens: 0 };
                // Split by comma, newline, carriage return, space, or semicolon
                var tokens = raw.split(/[\r\n,;\s]+/).map(function (n) { return n.trim(); }).filter(function (n) { return n.length > 0; });
                
                var seen = {};
                var valid = [];
                var duplicates = 0;
                var invalid = [];

                tokens.forEach(function (token) {
                    var cleaned = token.replace(/[^0-9]/g, '');
                    var isBdValid = /^(8801[3-9]\d{8}|01[3-9]\d{8})$/.test(cleaned);
                    var isGeneralValid = cleaned.length >= 8 && cleaned.length <= 15;

                    if (isBdValid || isGeneralValid) {
                        if (seen[cleaned]) {
                            duplicates++;
                        } else {
                            seen[cleaned] = true;
                            valid.push(cleaned);
                        }
                    } else {
                        invalid.push(token);
                    }
                });

                return { valid: valid, duplicates: duplicates, invalid: invalid, totalTokens: tokens.length };
            }

            function updateRecipientUI() {
                var raw = $numbers.val();
                var result = parseNumbers(raw);

                var validCount = result.valid.length;
                $('#valid-recipients-count').text(validCount);
                $('#review-recipients-text').text(validCount);
                $('#number-format-indicator').text(result.totalTokens + ' items (' + validCount + ' valid)');

                if (result.duplicates > 0) {
                    $('#recipient-dup-badge').removeClass('d-none').find('#dup-recipients-count').text(result.duplicates);
                } else {
                    $('#recipient-dup-badge').addClass('d-none');
                }

                // Trigger telemetry recalculation
                recalculateTelemetry();
            }

            $numbers.on('input change paste', function () {
                setTimeout(updateRecipientUI, 50);
            });

            // Insert Sample Number
            $('#btn-insert-sample').on('click', function () {
                var current = ($numbers.val() || '').trim();
                var sample = '8801700000000';
                if (!current) {
                    $numbers.val(sample);
                } else if (current.indexOf(sample) === -1) {
                    $numbers.val(current + ',' + sample);
                }
                updateRecipientUI();
            });

            // Clean & Dedupe
            $('#btn-clean-numbers').on('click', function () {
                var result = parseNumbers($numbers.val());
                if (result.valid.length > 0) {
                    $numbers.val(result.valid.join(','));
                }
                updateRecipientUI();
            });

            // Clear Recipient List
            $('#btn-clear-numbers').on('click', function () {
                if ($numbers.val() && confirm("{{ __('Clear recipient list?') }}")) {
                    $numbers.val('');
                    updateRecipientUI();
                }
            });

            // ----------------------------------------------------
            // 2. SCHEDULE CONTROLS
            // ----------------------------------------------------
            $('#btn-sched-sendnow').on('click', function () {
                $(this).addClass('active');
                $('#btn-sched-schedule').removeClass('active');
                $('#hidden_schedule_type').val('sendnow');
                $('#schedule-datetime-container').addClass('d-none');
                $('#schedule_datetime_input').prop('required', false);
            });

            $('#btn-sched-schedule').on('click', function () {
                $(this).addClass('active');
                $('#btn-sched-sendnow').removeClass('active');
                $('#hidden_schedule_type').val('schedule');
                $('#schedule-datetime-container').removeClass('d-none');
                $('#schedule_datetime_input').prop('required', true);
            });

            // ----------------------------------------------------
            // 3. MESSAGE COMPOSER, ENCODING & TELEMETRY ENGINE
            // ----------------------------------------------------
            function didITypeUnicode(text) {
                return /[^\u0000-\u007F]/.test(text);
            }

            function recalculateTelemetry() {
                var contentText = $contents.val() || '';
                var isUnicode = didITypeUnicode(contentText);

                // Auto-sync segmented buttons & radios
                if (isUnicode) {
                    $('#btn-lang-bng').addClass('active');
                    $('#btn-lang-eng').removeClass('active');
                    $('#unicode').prop('checked', true);
                    $('#text').prop('checked', false);
                    $('#sms_type').val('3');
                    $('#detected-encoding-text').text('বাংলা (Unicode)');
                } else {
                    $('#btn-lang-eng').addClass('active');
                    $('#btn-lang-bng').removeClass('active');
                    $('#text').prop('checked', true);
                    $('#unicode').prop('checked', false);
                    $('#sms_type').val('1');
                    $('#detected-encoding-text').text('GSM 7-bit');
                }

                // Character counting (extended GSM brackets count as 2)
                var charCount = 0;
                var singlePerSms = isUnicode ? 70 : 160;
                var multiPerSms = isUnicode ? 67 : 153;

                if (!isUnicode) {
                    for (var i = 0; i < contentText.length; i++) {
                        if (/[\[\]{}|~^\\]/.test(contentText[i])) {
                            charCount += 2;
                        } else {
                            charCount += 1;
                        }
                    }
                } else {
                    charCount = Array.from(contentText).length;
                }

                var perSmsLimit = charCount > singlePerSms ? multiPerSms : singlePerSms;
                var parts = charCount > 0 ? Math.ceil(charCount / perSmsLimit) : (contentText.length > 0 ? 1 : 0);
                var charsRemainingInPart = parts > 0 ? (parts * perSmsLimit - charCount) : singlePerSms;

                // Update Telemetry Elements
                $('#current').text(charCount);
                $('#char_count').val(charCount);
                $('#chars_per_part').text(singlePerSms);
                $('#no_of_sms').text(parts);
                $('#char_per_sms').text(perSmsLimit);
                $('#chars_remaining_badge').text(charsRemainingInPart + ' left');

                // Recipients count
                var parsed = parseNumbers($numbers.val());
                var recipientCount = Math.max(0, parsed.valid.length);

                // Dispatches & Cost calculation
                var totalDispatches = (parts > 0 ? parts : 1) * (recipientCount > 0 ? recipientCount : 1);
                var unitCost = (parts > 0 ? parts : 1) * userRate;
                var grandCost = (unitCost * (recipientCount > 0 ? recipientCount : 1)).toFixed(2);

                if (charCount === 0 || recipientCount === 0) {
                    $('#est_cost').text('0.00');
                    $('#review-cost-text').text('৳0.00');
                    $('#review-total-sms-text').text('0 SMS');
                    $('#review-parts-text').text('0');
                    $('#review-remaining-text').text('৳' + userBalance.toFixed(2));
                    $('#insufficient-balance-alert').addClass('d-none');
                } else {
                    $('#est_cost').text(grandCost);
                    $('#review-cost-text').text('৳' + grandCost);
                    $('#review-total-sms-text').text(totalDispatches + ' SMS');
                    $('#review-parts-text').text(parts);

                    var remBal = Math.max(0, userBalance - parseFloat(grandCost)).toFixed(2);
                    $('#review-remaining-text').text('৳' + remBal);

                    // Insufficient Balance check
                    if (parseFloat(grandCost) > userBalance) {
                        $('#insufficient-balance-alert').removeClass('d-none');
                    } else {
                        $('#insufficient-balance-alert').addClass('d-none');
                    }
                }
            }

            $contents.on('input change paste keyup', recalculateTelemetry);

            // Manual Language Toggle Click
            $('#btn-lang-eng').on('click', function () {
                var contentText = $contents.val() || '';
                if (didITypeUnicode(contentText)) {
                    alert("{{ __('Message contains Unicode characters (e.g. বাংলা). Encoding is automatically locked to Unicode.') }}");
                    return;
                }
                $(this).addClass('active');
                $('#btn-lang-bng').removeClass('active');
                $('#text').prop('checked', true);
                $('#unicode').prop('checked', false);
                $('#sms_type').val('1');
                recalculateTelemetry();
            });

            $('#btn-lang-bng').on('click', function () {
                $(this).addClass('active');
                $('#btn-lang-eng').removeClass('active');
                $('#unicode').prop('checked', true);
                $('#text').prop('checked', false);
                $('#sms_type').val('3');
                recalculateTelemetry();
            });

            // Clear Content
            $('#btn-clear-contents').on('click', function () {
                if ($contents.val() && confirm("{{ __('Clear message text?') }}")) {
                    $contents.val('');
                    recalculateTelemetry();
                }
            });

            // Sender select update review text
            $senderSelect.on('change', function () {
                var text = $(this).find('option:selected').text().trim();
                $('#review-sender-text').text(text || "{{ __('None selected') }}");
            });
            $senderSelect.trigger('change');

            // ----------------------------------------------------
            // 4. MESSAGE PREVIEW (Mobile Mockup Modal)
            // ----------------------------------------------------
            $('#btn-open-preview').on('click', function () {
                var senderText = $senderSelect.find('option:selected').text().trim() || 'SMS-SENDER';
                var text = $contents.val() || 'Type your message text to preview...';
                $('#preview-sender-label').text(senderText);
                $('#preview-bubble-content').text(text);
                $('#sms-phone-preview-modal').modal('show');
            });

            // ----------------------------------------------------
            // 5. RESET FORM WITH CONFIRMATION
            // ----------------------------------------------------
            $('#btn-reset-form').on('click', function () {
                if (confirm("{{ __('Reset all form fields?') }}")) {
                    $('#quick-sms-form')[0].reset();
                    $('#btn-sched-sendnow').trigger('click');
                    $contents.val('');
                    $numbers.val('');
                    updateRecipientUI();
                    recalculateTelemetry();
                }
            });

            // ----------------------------------------------------
            // 6. CONFIRMATION MODAL & DISPATCH ENGINE
            // ----------------------------------------------------
            $('#btn-trigger-sms-modal').on('click', function (e) {
                e.preventDefault();

                // Validate Sender
                if (!$senderSelect.val()) {
                    if (typeof Notify === 'function') {
                        Notify('error', { responseJSON: { message: "{{ __('Please select a Sender ID.') }}" } });
                    } else {
                        alert("{{ __('Please select a Sender ID.') }}");
                    }
                    $senderSelect.focus();
                    return;
                }

                // Validate Recipients
                var parsed = parseNumbers($numbers.val());
                if (parsed.valid.length === 0) {
                    if (typeof Notify === 'function') {
                        Notify('error', { responseJSON: { message: "{{ __('Please enter at least one valid recipient phone number.') }}" } });
                    } else {
                        alert("{{ __('Please enter at least one valid recipient phone number.') }}");
                    }
                    $numbers.focus();
                    return;
                }

                // Normalize numbers to comma-separated list for backend compatibility
                $numbers.val(parsed.valid.join(','));

                // Validate Content
                var contentText = ($contents.val() || '').trim();
                if (!contentText) {
                    if (typeof Notify === 'function') {
                        Notify('error', { responseJSON: { message: "{{ __('Please enter message content.') }}" } });
                    } else {
                        alert("{{ __('Please enter message content.') }}");
                    }
                    $contents.focus();
                    return;
                }

                // Telemetry Calculation for Modal
                var isUnicode = didITypeUnicode(contentText);
                var charCount = Array.from(contentText).length;
                var singleLimit = isUnicode ? 70 : 160;
                var multiLimit = isUnicode ? 67 : 153;
                var perSms = charCount > singleLimit ? multiLimit : singleLimit;
                var parts = Math.max(1, Math.ceil(charCount / perSms));
                var recipientsCount = parsed.valid.length;
                var unitCost = parts * userRate;
                var totalCost = (unitCost * recipientsCount).toFixed(2);
                var newBalance = Math.max(0, userBalance - parseFloat(totalCost)).toFixed(2);

                var senderText = $senderSelect.find('option:selected').text().trim();
                var charInfo = charCount + ' Chars (' + (isUnicode ? 'বাংলা / Unicode' : 'English / GSM-7') + ')';

                // Populate Modal Elements
                $('#modal-old-balance').text('৳' + userBalance.toFixed(2));
                $('#modal-new-balance').text('৳' + newBalance);
                $('#modal-total-cost').text(totalCost);
                $('#modal-sender-id').text(senderText);
                $('#modal-recipients-count').text(recipientsCount + (recipientsCount > 1 ? ' Numbers' : ' Number'));
                $('#modal-sms-parts').text(parts + (parts > 1 ? ' Parts' : ' Part'));
                $('#modal-sms-rate').text('৳' + userRate.toFixed(2));
                $('#modal-char-info').text(charInfo);
                $('#modal-content-preview').text(contentText);

                // Show Enterprise Confirmation Modal
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

            // Initial UI Sync
            updateRecipientUI();
            recalculateTelemetry();
        });
    </script>
@endpush
