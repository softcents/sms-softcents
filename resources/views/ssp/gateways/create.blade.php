@extends('layouts.ssp.master')

@section('title', __('Add Payment Gateway'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="card shadow-sm col-lg-9 mx-auto border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i>{{ __('Add New Sub-Reseller Payment Gateway') }}</h4>
                <a href="{{ route('ssp.gateways.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Back to List') }}
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('ssp.gateways.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Gateway Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. bKash Personal, Nagad Personal, City Bank" value="{{ old('name') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Gateway Type') }} <span class="text-danger">*</span></label>
                            <select name="is_manual" id="gatewayTypeSelect" class="form-select" required>
                                <option value="1" {{ old('is_manual', '1') == '1' ? 'selected' : '' }}>{{ __('Manual / Mobile Banking (bKash, Nagad, Rocket, Bank Transfer)') }}</option>
                                <option value="0" {{ old('is_manual') == '0' ? 'selected' : '' }}>{{ __('Automated Online Payment API') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Transaction Charge / Fee (%)') }}</label>
                            <input type="number" step="0.01" name="charge" class="form-control" placeholder="e.g. 1.5 or 0" value="{{ old('charge', 0) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Gateway Logo / Icon') }}</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Customer Requirements') }}</label>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="phone_required" value="1" id="phoneReq" checked>
                                <label class="form-check-label" for="phoneReq">{{ __('Sender Phone / Account Number Required') }}</label>
                            </div>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="accept_img" value="1" id="acceptImg" checked>
                                <label class="form-check-label" for="acceptImg">{{ __('Payment Screenshot / Receipt Upload Allowed') }}</label>
                            </div>
                        </div>

                        <div class="col-12" id="instructionsBlock">
                            <label class="form-label fw-bold">{{ __('Payment Instructions & Account Details') }} <span class="text-danger">*</span></label>
                            <textarea name="instructions" rows="4" class="form-control" placeholder="e.g.
1. Send Money to bKash Personal Number: 017XXXXXXXX
2. Use your Username as Reference.
3. Enter your Transaction ID (TrxID) below and submit.">{{ old('instructions') }}</textarea>
                            <small class="text-muted">{{ __('These instructions will be displayed directly to your customers on their recharge screen.') }}</small>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> {{ __('Create Gateway') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
