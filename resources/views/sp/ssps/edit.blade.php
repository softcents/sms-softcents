@extends('layouts.sp.master')

@section('title', __('Edit Sub Reseller (SSP)'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="card shadow-sm col-lg-8 mx-auto">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="fas fa-pencil-alt text-primary me-2"></i>{{ __('Edit Sub Reseller: ') }}{{ $ssp->name }}</h4>
                <div>
                    <a href="{{ route('sp.ssps.login-as', $ssp->id) }}" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As SSP') }}
                    </a>
                    <a href="{{ route('sp.ssps.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('Back to List') }}</a>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('sp.ssps.update', $ssp->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $ssp->name) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Username') }}</label>
                            <input type="text" name="username" value="{{ old('username', $ssp->username) }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $ssp->email) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" value="{{ old('phone', $ssp->phone) }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Password (leave empty to keep current)') }}</label>
                            <input type="password" name="password" class="form-control" placeholder="{{ __('New Password') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Confirm Password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Confirm New Password') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Masking Rate (BDT)') }}</label>
                            <input type="number" step="0.001" name="masking_rate" value="{{ old('masking_rate', $ssp->masking_rate) }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Non-Masking Rate (BDT)') }}</label>
                            <input type="number" step="0.001" name="non_masking_rate" value="{{ old('non_masking_rate', $ssp->non_masking_rate) }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Company / Brand Name') }}</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $ssp->company_name) }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Status') }}</label>
                            <select name="status" class="form-select">
                                <option value="active" @selected($ssp->status == 'active')>{{ __('Active') }}</option>
                                <option value="pending" @selected($ssp->status == 'pending')>{{ __('Pending') }}</option>
                                <option value="suspend" @selected($ssp->status == 'suspend')>{{ __('Suspended') }}</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('sp.ssps.index') }}" class="btn btn-secondary me-2">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary px-4">{{ __('Update SSP') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
