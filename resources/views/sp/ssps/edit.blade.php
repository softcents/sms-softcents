@extends('layouts.sp.master')

@section('title', __('Edit Sub Reseller (SSP)'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="card shadow-sm col-lg-8 mx-auto" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h4 class="mb-0 fw-bold"><i class="fas fa-pencil-alt text-primary me-2"></i>{{ __('Edit Sub Reseller: ') }}{{ $ssp->name }}</h4>
                <div>
                    <a href="{{ route('sp.ssps.login-as', $ssp->id) }}" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As SSP') }}
                    </a>
                    <a href="{{ route('sp.ssps.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('Back to List') }}
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                {{-- Flash Message & Error Alerts --}}
                @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 8px;">
                        <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-1"></i> {{ __('Please fix the following errors:') }}</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 8px;">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius: 8px;">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('sp.ssps.update', $ssp->id) }}" method="POST" class="ajaxform_instant_reload">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $ssp->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Username') }}</label>
                            <input type="text" name="username" value="{{ old('username', $ssp->username) }}" class="form-control @error('username') is-invalid @enderror">
                            @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $ssp->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" value="{{ old('phone', $ssp->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Password (leave empty to keep current)') }}</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('New Password') }}">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Confirm Password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('Confirm New Password') }}">
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Masking Rate (BDT)') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">{{ default_currency()->symbol ?? '৳' }}</span>
                                <input type="number" step="any" min="0" name="masking_rate" value="{{ old('masking_rate', $ssp->masking_rate) }}" class="form-control @error('masking_rate') is-invalid @enderror" placeholder="0.75">
                            </div>
                            <small class="text-muted">{{ __('Set whatever rate you want to charge this Sub Reseller for Masking SMS.') }}</small>
                            @error('masking_rate')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Non-Masking Rate (BDT)') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">{{ default_currency()->symbol ?? '৳' }}</span>
                                <input type="number" step="any" min="0" name="non_masking_rate" value="{{ old('non_masking_rate', $ssp->non_masking_rate) }}" class="form-control @error('non_masking_rate') is-invalid @enderror" placeholder="0.39">
                            </div>
                            <small class="text-muted">{{ __('Set whatever rate you want to charge this Sub Reseller for Non-Masking SMS.') }}</small>
                            @error('non_masking_rate')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Company / Brand Name') }}</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $ssp->company_name) }}" class="form-control @error('company_name') is-invalid @enderror" placeholder="{{ __('Company Name') }}">
                            @error('company_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Status') }}</label>
                            <select name="status" class="form-select form-control @error('status') is-invalid @enderror">
                                <option value="active" @selected($ssp->status == 'active')>{{ __('Active') }}</option>
                                <option value="pending" @selected($ssp->status == 'pending')>{{ __('Pending') }}</option>
                                <option value="suspend" @selected($ssp->status == 'suspend')>{{ __('Suspended') }}</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4 text-end border-top pt-3">
                            <a href="{{ route('sp.ssps.index') }}" class="btn btn-secondary me-2">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary px-4 submit-btn">
                                <i class="fas fa-save me-1"></i> {{ __('Update SSP') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
