@extends('layouts.sp.master')

@section('title', __('Create Direct Customer'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="card shadow-sm col-lg-8 mx-auto">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold"><i class="fas fa-user-plus text-primary me-2"></i>{{ __('Create Direct Customer') }}</h4>
                <a href="{{ route('sp.customers.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('Back to List') }}</a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('sp.customers.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required placeholder="{{ __('Enter Full Name') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Username') }}</label>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="{{ __('Enter unique username') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required placeholder="{{ __('Enter Email Address') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="{{ __('Enter Phone Number') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required placeholder="{{ __('Password (min 6 characters)') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="{{ __('Confirm Password') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Masking Rate (BDT)') }}</label>
                            <input type="number" step="any" min="0" name="masking_rate" value="{{ old('masking_rate', auth()->user()->masking_rate) }}" class="form-control" placeholder="0.75">
                            <small class="text-muted">{{ __('Set whatever rate you want to charge this customer for Masking SMS.') }}</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Non-Masking Rate (BDT)') }}</label>
                            <input type="number" step="any" min="0" name="non_masking_rate" value="{{ old('non_masking_rate', auth()->user()->non_masking_rate) }}" class="form-control" placeholder="0.39">
                            <small class="text-muted">{{ __('Set whatever rate you want to charge this customer for Non-Masking SMS.') }}</small>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('sp.customers.index') }}" class="btn btn-secondary me-2">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary px-4">{{ __('Create Customer') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
