@extends('layouts.sp.master')

@section('title', __('Mail / SMTP Settings'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
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

        <div class="row g-4">
            <!-- SMTP Configuration Form -->
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-envelope text-primary me-2"></i>{{ __('Reseller SMTP Mail Configuration') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Configure your own SMTP mail server so welcome emails, notifications, and balance alerts sent to your downline customers will come directly from your email address.') }}</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('sp.mail-settings.update') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">{{ __('SMTP Host') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="host" value="{{ old('host', $mailSettings['host'] ?? '') }}" class="form-control" placeholder="e.g. smtp.gmail.com or mail.yourdomain.com" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">{{ __('SMTP Port') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="port" value="{{ old('port', $mailSettings['port'] ?? 587) }}" class="form-control" placeholder="587 / 465 / 25" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('SMTP Username / Email') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="username" value="{{ old('username', $mailSettings['username'] ?? '') }}" class="form-control" placeholder="e.g. info@yourdomain.com" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">{{ __('SMTP Password') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="password" value="{{ old('password', $mailSettings['password'] ?? '') }}" class="form-control" placeholder="••••••••••••" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">{{ __('Encryption') }}</label>
                                    <select name="encryption" class="form-select">
                                        <option value="tls" {{ old('encryption', $mailSettings['encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS (Port 587)</option>
                                        <option value="ssl" {{ old('encryption', $mailSettings['encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                                        <option value="none" {{ old('encryption', $mailSettings['encryption'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">{{ __('From Email Address') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="from_address" value="{{ old('from_address', $mailSettings['from_address'] ?? '') }}" class="form-control" placeholder="e.g. noreply@yourdomain.com" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">{{ __('From Name / Brand') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="from_name" value="{{ old('from_name', $mailSettings['from_name'] ?? $sp->white_label_title ?? $sp->company_name) }}" class="form-control" placeholder="e.g. My Brand SMS Portal" required>
                                </div>

                                <div class="col-12 mt-4 text-end">
                                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> {{ __('Save Mail Settings') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Send Test Email Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-paper-plane text-success me-2"></i>{{ __('Test SMTP Mail') }}</h5>
                        <p class="text-muted small mb-0">{{ __('Send a test email to verify your SMTP server connection and credentials.') }}</p>
                    </div>
                    <div class="card-body p-4">
                        @if(empty($mailSettings['host']))
                            <div class="alert alert-warning small">
                                <i class="fas fa-info-circle me-1"></i> {{ __('Please configure and save your SMTP settings on the left before sending a test mail.') }}
                            </div>
                        @endif

                        <form action="{{ route('sp.mail-settings.test') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Recipient Email Address') }}</label>
                                <input type="email" name="test_email" value="{{ old('test_email', $sp->email) }}" class="form-control" placeholder="recipient@example.com" required>
                                <small class="text-muted">{{ __('We will send a test email to this address.') }}</small>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2" {{ empty($mailSettings['host']) ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane me-1"></i> {{ __('Send Test Mail Now') }}
                            </button>
                        </form>

                        <div class="mt-4 p-3 bg-light rounded small">
                            <h6 class="fw-bold mb-1"><i class="fas fa-shield-alt text-primary me-1"></i>{{ __('Gmail Users Note:') }}</h6>
                            <p class="text-muted mb-0">
                                {{ __('If using Gmail SMTP, generate an "App Password" in your Google Account Security settings and use port 587 with TLS.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
