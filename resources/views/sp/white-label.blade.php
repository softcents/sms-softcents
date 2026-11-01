@extends('layouts.sp.master')

@section('title', __('White Label Settings'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm col-lg-10 mx-auto">
            <div class="card-header bg-white py-3 border-bottom">
                <h4 class="mb-0 fw-bold"><i class="fas fa-paint-brush text-primary me-2"></i>{{ __('Full White-Label Branding Configuration') }}</h4>
                <p class="text-muted small mb-0">{{ __('Your sub resellers and customers will inherit your customized brand name, logo, title, contact details, and footer text.') }}</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('sp.white-label.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Company / Brand Name') }}</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $sp->company_name) }}" class="form-control" placeholder="{{ __('e.g. My SMS Brand') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('White Label Portal Title') }}</label>
                            <input type="text" name="white_label_title" value="{{ old('white_label_title', $sp->white_label_title) }}" class="form-control" placeholder="{{ __('e.g. My Brand SMS Gateway') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Support Phone Number') }}</label>
                            <input type="text" name="white_label_phone" value="{{ old('white_label_phone', $sp->white_label_phone) }}" class="form-control" placeholder="{{ __('e.g. +88017XXXXXXXX') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Support Email Address') }}</label>
                            <input type="email" name="white_label_email" value="{{ old('white_label_email', $sp->white_label_email) }}" class="form-control" placeholder="{{ __('e.g. support@mybrand.com') }}">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">{{ __('Custom Domain / Subdomain') }} <span class="badge bg-info text-white">CNAME / A Record</span></label>
                            <input type="text" name="custom_domain" value="{{ old('custom_domain', $sp->custom_domain) }}" class="form-control" placeholder="{{ __('e.g. sms.yourcompany.com') }}">
                            <small class="text-muted">{{ __('Point your domain CNAME or A Record to our server IP. When users visit your domain, they will see your full branding.') }}</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">{{ __('Footer & Copyright Text') }}</label>
                            <textarea name="white_label_footer" rows="2" class="form-control" placeholder="{{ __('e.g. © 2026 My Brand Systems. All rights reserved.') }}">{{ old('white_label_footer', $sp->white_label_footer) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Custom Brand Logo') }}</label>
                            <input type="file" name="white_label_logo" class="form-control" accept="image/*">
                            @if($sp->white_label_logo)
                                <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ asset($sp->white_label_logo) }}" alt="Logo" style="max-height: 45px;">
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Custom Favicon') }}</label>
                            <input type="file" name="white_label_favicon" class="form-control" accept="image/*">
                            @if($sp->white_label_favicon)
                                <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ asset($sp->white_label_favicon) }}" alt="Favicon" style="max-height: 32px;">
                                </div>
                            @endif
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> {{ __('Save White-Label Branding') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
