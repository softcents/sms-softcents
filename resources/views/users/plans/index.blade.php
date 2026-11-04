@extends('layouts.users.master')

@section('title')
    {{ __('My Tariff & Pricing') }}
@endsection

@section('main_content')
    <div class="erp-table-section plans-list">
        <div class="container-fluid">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                            <i class="fas fa-tags text-primary"></i> {{ __('My SMS Tariff & Rates') }}
                        </h3>
                        <p class="text-muted mb-0">
                            {{ __('Transparent per-SMS pricing. Your SMS will be billed automatically from your wallet balance with no package expiry.') }}
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white border rounded px-3 py-2 text-end">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 11px;">{{ __('Available Balance') }}</small>
                            <span class="fs-5 fw-bold text-success">{{ currency_format(auth()->user()->balance) }}</span>
                        </div>
                        <a href="{{ route('recharges-gateways.index') }}" class="btn btn-primary px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-wallet"></i> {{ __('Recharge Balance') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2 Main Tariff Cards -->
            <div class="row g-4 mb-4">
                <!-- Non-Masking Rate Card -->
                <div class="col-md-6 col-lg-6">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden; border-top: 4px solid #0d6efd !important;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1 fs-6 fw-bold">
                                    <i class="fas fa-comment-dots me-1"></i> {{ __('Non-Masking SMS') }}
                                </span>
                                <span class="badge bg-light text-muted border">{{ __('Standard Routing') }}</span>
                            </div>

                            <div class="my-3 py-2 border-bottom">
                                <h1 class="display-5 fw-bold text-dark mb-0">
                                    ৳{{ number_format((float)(auth()->user()->non_masking_rate ?? 0.39), 2) }}
                                    <span class="fs-6 text-muted fw-normal">/ {{ __('part') }}</span>
                                </h1>
                                <small class="text-muted">{{ __('Billed per sent part from your balance') }}</small>
                            </div>

                            <ul class="list-unstyled d-flex flex-column gap-2 text-dark mt-3 mb-4">
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                    <span>{{ __('160 Characters per part (English GSM)') }}</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                    <span>{{ __('70 Characters per part (Bangla / Unicode)') }}</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                    <span>{{ __('High throughput & fastest operator delivery') }}</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                    <span>{{ __('Supports Quick SMS, Bulk File, Campaign & API') }}</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                    <span>{{ __('Unlimited validity — no package expiration') }}</span>
                                </li>
                            </ul>

                            <a href="{{ route('users.quick-sms.index') }}" class="btn btn-outline-primary w-100 py-2 fw-semibold" style="border-radius: 8px;">
                                <i class="fas fa-paper-plane me-1"></i> {{ __('Send Non-Masking SMS') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Masking Rate Card -->
                <div class="col-md-6 col-lg-6">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden; border-top: 4px solid #198754 !important;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-success-subtle text-success border border-success px-3 py-1 fs-6 fw-bold">
                                    <i class="fas fa-shield-alt me-1"></i> {{ __('Masking SMS') }}
                                </span>
                                <span class="badge bg-light text-muted border">{{ __('Brand Sender ID') }}</span>
                            </div>

                            <div class="my-3 py-2 border-bottom">
                                <h1 class="display-5 fw-bold text-dark mb-0">
                                    ৳{{ number_format((float)(auth()->user()->masking_rate ?? 0.75), 2) }}
                                    <span class="fs-6 text-muted fw-normal">/ {{ __('part') }}</span>
                                </h1>
                                <small class="text-muted">{{ __('Billed per sent part from your balance') }}</small>
                            </div>

                            <ul class="list-unstyled d-flex flex-column gap-2 text-dark mt-3 mb-4">
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                    <span>{{ __('Sender name shows your official brand identity') }}</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                    <span>{{ __('Direct operator route with highest priority') }}</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                    <span>{{ __('160 Characters English / 70 Characters Bangla') }}</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                    <span>{{ __('Supports Quick SMS, Bulk File, Campaign & API') }}</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle text-success fs-6"></i>
                                    <span>{{ __('Unlimited validity — lifetime balance carryover') }}</span>
                                </li>
                            </ul>

                            <a href="{{ route('users.quick-sms.index') }}" class="btn btn-outline-success w-100 py-2 fw-semibold" style="border-radius: 8px;">
                                <i class="fas fa-paper-plane me-1"></i> {{ __('Send Masking SMS') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How It Works Information -->
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="fas fa-info-circle text-primary me-2"></i>{{ __('How Billing Works') }}
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-dark mb-1">1. {{ __('Recharge Wallet') }}</h6>
                                <p class="text-muted small mb-0">{{ __('Recharge any amount directly using bKash, Nagad, bank, or gateway without purchasing any package.') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-dark mb-1">2. {{ __('Send SMS') }}</h6>
                                <p class="text-muted small mb-0">{{ __('Create campaigns, bulk SMS, or use developer API. System calculates cost based on your active tariff rates.') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded border h-100">
                                <h6 class="fw-bold text-dark mb-1">3. {{ __('No Expiry') }}</h6>
                                <p class="text-muted small mb-0">{{ __('Your wallet balance never expires. You can use your balance anytime whenever you need to send SMS.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
