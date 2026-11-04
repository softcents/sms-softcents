@extends('layouts.users.master')

@section('title')
    {{ __('Send Quick SMS') }}
@endsection

@section('main_content')
    <style>
        /* Enterprise SaaS Quick SMS Design System */
        :root {
            --qs-primary: #4f46e5;
            --qs-primary-hover: #4338ca;
            --qs-primary-light: #eef2ff;
            --qs-secondary: #10b981;
            --qs-secondary-hover: #059669;
            --qs-secondary-light: #ecfdf5;
            --qs-border: #e2e8f0;
            --qs-bg-subtle: #f8fafc;
            --qs-text-dark: #0f172a;
            --qs-text-muted: #64748b;
        }

        .quick-sms-root {
            padding: 10px 0;
            background: transparent;
        }

        /* Top Page Header Banner */
        .qs-header-banner {
            background: #ffffff;
            border: 1px solid var(--qs-border);
            border-radius: 10px;
            padding: 10px 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 12px;
        }

        /* Section Cards */
        .qs-card {
            background: #ffffff;
            border: 1px solid var(--qs-border);
            border-radius: 10px;
            padding: 12px 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .qs-card:hover {
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        }
        .qs-card-indigo {
            border-top: 3.5px solid var(--qs-primary);
        }
        .qs-card-emerald {
            border-top: 3.5px solid var(--qs-secondary);
        }
        .qs-card-review {
            border-top: 3.5px solid #0284c7;
            background: #f8fafc;
        }

        /* Section Step Header */
        .qs-step-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 8px;
            margin-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .qs-step-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .qs-step-pill.indigo { color: #3730a3; }
        .qs-step-pill.emerald { color: #065f46; }
        .qs-step-pill.sky { color: #0369a1; }
        .qs-step-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
        }
        .qs-step-num.indigo { background: var(--qs-primary); }
        .qs-step-num.emerald { background: var(--qs-secondary); }
        .qs-step-num.sky { background: #0284c7; }

        /* Form Controls */
        .qs-label {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--qs-text-dark);
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .qs-form-control {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 5px 9px;
            font-size: 12px;
            color: var(--qs-text-dark);
            background-color: #ffffff;
            transition: all 0.2s ease;
            height: 34px;
        }
        .qs-form-control:focus {
            border-color: var(--qs-primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.15);
            outline: none;
        }
        textarea.qs-form-control {
            height: auto;
            min-height: 56px;
            line-height: 1.4;
            resize: vertical;
        }

        /* Segmented Button Switches */
        .qs-segmented-switch {
            display: inline-flex;
            background: #f1f5f9;
            padding: 2px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            width: 100%;
        }
        .qs-segmented-btn {
            flex: 1;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 600;
            text-align: center;
            border-radius: 5px;
            border: none;
            background: transparent;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .qs-segmented-btn.active {
            background: #ffffff;
            color: var(--qs-primary);
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .qs-segmented-btn.active.emerald {
            color: var(--qs-secondary);
        }

        /* Toolbar Action Buttons */
        .qs-btn-tool {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            height: 22px;
            text-decoration: none;
        }
        .qs-btn-tool:hover {
            background: #f8fafc;
            color: var(--qs-primary);
            border-color: #94a3b8;
        }
        .qs-btn-tool.danger:hover {
            color: #dc2626;
            border-color: #fca5a5;
            background: #fef2f2;
        }

        /* Telemetry & Calculation Strip */
        .qs-telemetry-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 8px;
        }
        .qs-metric-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10.5px;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .qs-metric-chip.purple { background: #f3e8ff; color: #6b21a8; border-color: #e9d5ff; }
        .qs-metric-chip.amber { background: #fef3c7; color: #92400e; border-color: #fde68a; }
        .qs-metric-chip.blue { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
        .qs-metric-chip.green { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
        .qs-metric-chip.red { background: #fee2e2; color: #991b1b; border-color: #fecaca; }

        /* Action Buttons */
        .btn-qs-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 3px 8px rgba(16, 185, 129, 0.3);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            height: 36px;
            cursor: pointer;
        }
        .btn-qs-primary:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            transform: translateY(-1px);
        }
        .btn-qs-primary:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-qs-outline {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            border-radius: 6px;
            padding: 7px 14px;
            font-size: 12.5px;
            font-weight: 600;
            transition: all 0.2s ease;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-qs-outline:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        /* Mobile First Responsive Tweaks */
        @media (max-width: 991.98px) {
            .qs-header-banner {
                padding: 8px 10px;
                margin-bottom: 8px;
            }
            .qs-card {
                padding: 8px 10px;
                margin-bottom: 8px;
            }
            .qs-step-header {
                padding-bottom: 6px;
                margin-bottom: 6px;
            }
            textarea.qs-form-control#numbers {
                min-height: 48px;
                height: 50px;
                font-size: 11.5px;
                padding: 4px 6px;
            }
            textarea.qs-form-control#contents {
                min-height: 52px;
                height: 56px;
                font-size: 11.5px;
                padding: 4px 6px;
            }
            .qs-form-control {
                height: 32px;
                font-size: 11.5px;
                padding: 3px 6px;
            }
            .qs-telemetry-box {
                padding: 4px 6px;
                margin-bottom: 6px;
            }
            .qs-metric-chip {
                font-size: 9.5px;
                padding: 1px 4px;
            }
            .btn-qs-primary, .btn-qs-outline {
                height: 34px;
                font-size: 12px;
                padding: 5px 12px;
            }
        }
    </style>

    <div class="quick-sms-root">
        <div class="container-fluid px-2 px-sm-3">
            {{-- Modern Enterprise Header Banner --}}
            <div class="qs-header-banner d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle text-white d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 30px; height: 30px; font-size: 12px; background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);">
                        <i class="fas fa-paper-plane"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px; line-height: 1.2;">
                            {{ __('Send Quick SMS') }}
                        </h6>
                        <span class="text-muted small d-none d-sm-inline" style="font-size: 11px;">
                            {{ __('Enterprise instant message dispatch with live routing & rate validation') }}
                        </span>
                    </div>
                </div>

                {{-- Status Pills --}}
                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                    <span class="badge bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center py-1 px-2" style="font-size: 10.5px;">
                        <span class="spinner-grow spinner-grow-sm me-1 text-success" role="status" style="width: 7px; height: 7px;"></span>
                        {{ __('Active Route') }}
                    </span>
                    <span class="badge bg-light text-dark border py-1 px-2" style="font-size: 10.5px;">
                        <i class="fas fa-tag text-primary me-1"></i>{{ __('Rate') }}: <strong>৳{{ number_format(auth()->user()->non_masking_rate ?? 0.39, 2) }}</strong>
                    </span>
                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle py-1 px-2" style="font-size: 10.5px;">
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

                <div class="row g-2 g-lg-3">
                    {{-- ================================================= --}}
                    {{-- SECTION 1: SENDER & RECIPIENTS (Indigo Theme)     --}}
                    {{-- ================================================= --}}
                    <div class="col-12 col-lg-6">
                        <div class="qs-card qs-card-indigo">
                            {{-- Step Header & Recipient Counter Badge --}}
                            <div class="qs-step-header">
                                <span class="qs-step-pill indigo">
                                    <span class="qs-step-num indigo">1</span>
                                    {{ __('Sender & Recipients') }}
                                </span>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-primary text-white" id="recipient-valid-badge" style="font-size: 10.5px; padding: 3px 6px;">
                                        <i class="fas fa-check-circle me-0.5"></i> <strong id="valid-recipients-count">0</strong> {{ __('Valid') }}
                                    </span>
                                    <span class="badge bg-warning text-dark d-none" id="recipient-dup-badge" style="font-size: 10.5px; padding: 3px 6px;">
                                        <strong id="dup-recipients-count">0</strong> {{ __('Duplicates') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Row: Sender ID & Dispatch Schedule Segmented Switch --}}
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="qs-label">
                                        <span>{{ __('Sender ID') }} <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="gpt-up-down-arrow position-relative w-100">
                                        <select name="senderid_id" required class="form-control qs-form-control w-100" id="senderid_select">
                                            <option value="">{{ __('Select Sender') }}</option>
                                            @foreach ($senderids as $senderid)
                                                <option value="{{ $senderid->id }}" @selected($senderid->is_default)>
                                                    {{ $senderid->sender }} {{ $senderid->is_default ? '('.__('Default').')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span></span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label class="qs-label">
                                        <span>{{ __('Schedule Type') }} <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="qs-segmented-switch">
                                        <button type="button" class="qs-segmented-btn active" data-schedule="sendnow" id="btn-sched-sendnow">
                                            <i class="fas fa-bolt me-1"></i>{{ __('Send Now') }}
                                        </button>
                                        <button type="button" class="qs-segmented-btn" data-schedule="schedule" id="btn-sched-schedule">
                                            <i class="fas fa-clock me-1"></i>{{ __('Schedule') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Schedule Datetime Picker (conditionally shown) --}}
                            <div class="mb-2 d-none" id="schedule-datetime-container">
                                <label class="qs-label text-primary">
                                    <span><i class="fas fa-calendar-alt me-1"></i>{{ __('Schedule Time') }} <span class="text-danger">*</span></span>
                                </label>
                                <input type="datetime-local" name="schedule" id="schedule_datetime_input" class="form-control qs-form-control w-100">
                            </div>

                            {{-- Recipient Numbers Field & Helper Toolbar --}}
                            <div class="mb-1 flex-grow-1 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
                                    <label class="qs-label mb-0">
                                        <span>{{ __('Recipients Numbers') }} <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="qs-btn-tool" id="btn-insert-sample" title="{{ __('Insert Bangladeshi test number') }}">
                                            <i class="fas fa-plus-circle text-primary"></i> {{ __('+ Sample') }}
                                        </button>
                                        <button type="button" class="qs-btn-tool" id="btn-clean-numbers" title="{{ __('Remove invalid characters and deduplicate') }}">
                                            <i class="fas fa-magic text-info"></i> {{ __('Clean') }}
                                        </button>
                                        <button type="button" class="qs-btn-tool danger" id="btn-clear-numbers" title="{{ __('Clear recipient list') }}">
                                            <i class="fas fa-trash-alt"></i> {{ __('Clear') }}
                                        </button>
                                    </div>
                                </div>

                                <textarea name="numbers" id="numbers" rows="3" class="form-control qs-form-control font-monospace w-100 flex-grow-1" placeholder="{{ __('Paste phone numbers (e.g. 017..., 88018...). Accepts comma, space, or newline separated.') }}"></textarea>

                                {{-- Validation Helper Feedback --}}
                                <div class="d-flex justify-content-between align-items-center mt-1 text-muted" style="font-size: 10px;">
                                    <span>
                                        <i class="fas fa-info-circle me-0.5 text-primary"></i> {{ __('Auto-validates 8801... / 01... numbers') }}
                                    </span>
                                    <span id="number-format-indicator" class="text-muted">
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
                            {{-- Step Header & Actions --}}
                            <div class="qs-step-header">
                                <span class="qs-step-pill emerald">
                                    <span class="qs-step-num emerald">2</span>
                                    {{ __('Message Content') }}
                                </span>
                                <div class="d-flex align-items-center gap-1">
                                    <button type="button" class="qs-btn-tool" id="btn-open-preview" title="{{ __('Live Phone Mockup Preview') }}">
                                        <i class="fas fa-mobile-alt text-success"></i> {{ __('Preview') }}
                                    </button>
                                    <a id="sms-templates" data-url="{{ route('users.get-templates') }}" class="qs-btn-tool" href="javascript:void(0)" title="{{ __('Browse Saved Templates') }}">
                                        <i class="fas fa-file-alt text-primary"></i> {{ __('Templates') }}
                                    </a>
                                </div>
                            </div>

                            {{-- Language & Encoding Selector Bar --}}
                            <div class="d-flex justify-content-between align-items-center mb-1.5 flex-wrap gap-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="qs-label mb-0" style="font-size: 11px;">{{ __('Encoding') }}:</span>
                                    <div class="qs-segmented-switch" style="width: auto;">
                                        <button type="button" class="qs-segmented-btn active" id="btn-lang-eng" data-type="english">
                                            Eng (GSM-7)
                                        </button>
                                        <button type="button" class="qs-segmented-btn emerald" id="btn-lang-bng" data-type="unicode">
                                            বাংলা (Unicode)
                                        </button>
                                    </div>
                                    {{-- Hidden radio buttons to preserve backend/JS contracts --}}
                                    <input class="d-none text" type="radio" name="is_unicode_test" id="text">
                                    <input class="d-none unicode" type="radio" name="is_unicode_test" id="unicode" checked>
                                </div>

                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-light text-muted border" id="auto-detected-badge" style="font-size: 10px;">
                                        <i class="fas fa-language me-0.5"></i> <span id="detected-encoding-text">GSM 7-bit</span>
                                    </span>
                                    <button type="button" class="qs-btn-tool danger py-0 px-1" id="btn-clear-contents" title="{{ __('Clear message text') }}" style="height: 20px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Message Textarea --}}
                            <div class="mb-1.5 flex-grow-1 d-flex flex-column">
                                <textarea name="contents" id="contents" rows="3" class="form-control qs-form-control w-100 flex-grow-1" placeholder="{{ __('Type or paste your SMS text here. Automatic Unicode detection enabled for বাংলা...') }}"></textarea>
                            </div>

                            {{-- Live Telemetry & Calculation Box --}}
                            <div id="the-count" class="qs-telemetry-box" data-rate="{{ auth()->user()->non_masking_rate ?? 0.39 }}" data-balance="{{ (float)auth()->user()->balance }}">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <span class="qs-metric-chip purple" title="{{ __('Total characters typed') }}">
                                            <i class="fas fa-font"></i> <strong id="current">0</strong>/<span id="chars_per_part">160</span>
                                        </span>
                                        <span class="qs-metric-chip amber" title="{{ __('SMS segments') }}">
                                            <i class="fas fa-layer-group"></i> <strong id="no_of_sms">0</strong> SMS
                                        </span>
                                        <span class="qs-metric-chip blue" title="{{ __('Characters per SMS part') }}">
                                            <span id="char_per_sms">160</span>/SMS
                                        </span>
                                        <span class="qs-metric-chip green d-none d-sm-inline-flex" title="{{ __('Remaining characters in current part') }}">
                                            <span id="chars_remaining_badge">160 left</span>
                                        </span>
                                    </div>

                                    <div>
                                        <span class="qs-metric-chip red fw-bold" title="{{ __('Calculated total dispatch cost') }}" style="font-size: 11px;">
                                            <i class="fas fa-coins me-0.5"></i>Cost: ৳<span id="est_cost">0.00</span>
                                        </span>
                                    </div>
                                </div>

                                {{-- Insufficient Balance Warning Banner --}}
                                <div id="insufficient-balance-alert" class="d-none mt-1 p-1 bg-danger-subtle text-danger border border-danger-subtle rounded text-center" style="font-size: 10.5px;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>{{ __('Insufficient Balance!') }}</strong>
                                    {{ __('Estimated cost exceeds available balance.') }}
                                    <a href="{{ route('users.recharges.index') }}" class="text-danger fw-bold text-decoration-underline ms-1">{{ __('Recharge') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================================================= --}}
                    {{-- SECTION 3: REVIEW & DISPATCH (Sky Theme Deck)     --}}
                    {{-- ================================================= --}}
                    <div class="col-12">
                        <div class="qs-card qs-card-review">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                {{-- Live Review Metrics Bar --}}
                                <div class="d-flex align-items-center gap-3 flex-wrap" style="font-size: 11.5px;">
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-muted"><i class="fas fa-bullhorn text-primary me-0.5"></i>{{ __('Sender') }}:</span>
                                        <strong class="text-dark font-monospace" id="review-sender-text">{{ __('None selected') }}</strong>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-muted"><i class="fas fa-users text-info me-0.5"></i>{{ __('Recipients') }}:</span>
                                        <strong class="text-dark" id="review-recipients-text">0</strong>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-muted"><i class="fas fa-layer-group text-warning me-0.5"></i>{{ __('Parts') }}:</span>
                                        <strong class="text-dark" id="review-parts-text">0</strong>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-muted"><i class="fas fa-paper-plane text-success me-0.5"></i>{{ __('Total SMS') }}:</span>
                                        <strong class="text-success" id="review-total-sms-text">0 SMS</strong>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-muted"><i class="fas fa-coins text-danger me-0.5"></i>{{ __('Total Cost') }}:</span>
                                        <strong class="text-danger" id="review-cost-text">৳0.00</strong>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 d-none d-md-flex">
                                        <span class="text-muted"><i class="fas fa-wallet text-secondary me-0.5"></i>{{ __('Remaining') }}:</span>
                                        <strong class="text-dark" id="review-remaining-text">৳{{ number_format((float)auth()->user()->balance, 2) }}</strong>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="d-flex align-items-center gap-2 ms-auto">
                                    <button type="button" class="btn-qs-outline" id="btn-reset-form">
                                        <i class="fas fa-undo me-1"></i> {{ __('Reset') }}
                                    </button>
                                    <button type="button" class="btn-qs-primary shadow-sm" id="btn-trigger-sms-modal">
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
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content shadow-lg border-0" style="border-radius: 24px; overflow: hidden; background: #0f172a;">
                {{-- Phone Top Notch --}}
                <div class="py-2 px-3 d-flex justify-content-between align-items-center text-white-50" style="font-size: 11px;">
                    <span>9:41</span>
                    <div style="width: 70px; height: 16px; background: #1e293b; border-radius: 10px;"></div>
                    <div class="d-flex align-items-center gap-1">
                        <i class="fas fa-signal" style="font-size: 9px;"></i>
                        <i class="fas fa-wifi" style="font-size: 9px;"></i>
                        <i class="fas fa-battery-full" style="font-size: 10px;"></i>
                    </div>
                </div>

                {{-- Phone Screen --}}
                <div class="p-3" style="background: #ffffff; min-height: 380px; display: flex; flex-direction: column;">
                    {{-- Contact Header --}}
                    <div class="text-center pb-2 mb-3 border-bottom">
                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center fw-bold mb-1" style="width: 44px; height: 44px; font-size: 16px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="fw-bold text-dark font-monospace" id="preview-sender-label" style="font-size: 13.5px;">-</div>
                        <small class="text-muted" style="font-size: 10px;">SMS Delivery Service</small>
                    </div>

                    {{-- Chat Bubble Container --}}
                    <div class="flex-grow-1 d-flex flex-column justify-content-start">
                        <div class="text-center my-1">
                            <span class="badge bg-light text-muted border" style="font-size: 9.5px;">Today</span>
                        </div>
                        <div class="p-2.5 rounded-3 mb-2 shadow-xs" style="background: #e0f2fe; color: #0c4a6e; max-width: 88%; border-bottom-left-radius: 4px; font-size: 12.5px; line-height: 1.45; word-break: break-word; white-space: pre-wrap;" id="preview-bubble-content">
                            Your SMS text preview will appear here...
                        </div>
                        <div class="text-muted ms-1" style="font-size: 9px;" id="preview-timestamp">Just now</div>
                    </div>

                    {{-- Close Preview Button --}}
                    <div class="pt-2 border-top mt-auto text-center">
                        <button type="button" class="btn btn-sm btn-light border w-100" data-bs-dismiss="modal" style="font-size: 12px; border-radius: 8px;">
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
                <div class="modal-header bg-light py-2 px-3 border-bottom">
                    <h6 class="modal-title fw-bold text-dark mb-0">
                        <i class="fas fa-file-alt text-primary me-1.5"></i>{{ __('SMS Message Templates') }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 11px;"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="datatable">
                            <thead class="table-light">
                                <tr style="font-size: 12px;">
                                    <th style="width: 50px;">{{ __('SL') }}</th>
                                    <th style="width: 140px;">{{ __('Template Name') }}</th>
                                    <th>{{ __('Message Text') }}</th>
                                    <th style="width: 90px;" class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="templates-list" style="font-size: 12.5px;">
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
                if (!raw) return { valid: [], duplicates: 0, invalid: [] };
                // Split by comma, newline, carriage return, space, or semicolon
                var tokens = raw.split(/[\r\n,;\s]+/).map(function (n) { return n.trim(); }).filter(function (n) { return n.length > 0; });
                
                var seen = {};
                var valid = [];
                var duplicates = 0;
                var invalid = [];

                tokens.forEach(function (token) {
                    // Normalize: remove non-digits
                    var cleaned = token.replace(/[^0-9]/g, '');
                    // Valid if standard BD number (11 digits e.g. 017... or 13 digits 8801...)
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
            // 2. SCHEDULE SEGMENTED CONTROLS
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
