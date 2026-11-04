@extends('layouts.admin.master')

@section('title')
    {{ __('User Details') }} — {{ $user->name }}
@endsection

@push('css')
<style>
    .user-detail-header-card {
        background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }
    .kpi-card {
        border-radius: 10px;
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.06);
    }
    .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .user-avatar-lg {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .info-list-group li {
        padding: 0.65rem 0;
        border-bottom: 1px dashed #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }
    .info-list-group li:last-child {
        border-bottom: none;
    }
    .copy-btn {
        cursor: pointer;
        transition: all 0.15s;
    }
    .copy-btn:hover {
        opacity: 0.8;
    }
    .secret-text {
        font-family: monospace;
        letter-spacing: 0.5px;
    }
    .nav-tabs .nav-link {
        font-weight: 500;
        color: #64748b;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.75rem 1.25rem;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        background: transparent;
        border-bottom: 2px solid #0d6efd;
    }
    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endpush

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <!-- Breadcrumb & Top Bar -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index', ['users' => $user->role]) }}">{{ __(ucfirst($user->role) . 's') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                    </ol>
                </nav>
                <h4 class="fw-bold mb-0 text-dark">
                    {{ $user->name }}
                    <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }} fs-6 ms-2">
                        {{ ucfirst($user->status) }}
                    </span>
                    <span class="badge bg-secondary fs-6 ms-1">
                        {{ strtoupper($user->role) }}
                    </span>
                </h4>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('admin.users.index', ['users' => $user->role]) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Back to List') }}
                </a>

                @if(in_array($user->role, ['user', 'sp', 'ssp']))
                <button type="button" class="btn btn-success btn-sm btn-open-recharge-modal"
                    data-id="{{ $user->id }}"
                    data-name="{{ $user->name }}"
                    data-username="{{ $user->username }}"
                    data-role="{{ strtoupper($user->role) }}"
                    data-balance="{{ (float)$user->balance }}"
                    data-balance-formatted="{{ currency_format($user->balance) }}">
                    <i class="fas fa-wallet me-1"></i> {{ __('Recharge / Adjust') }}
                </button>

                @if($user->role == 'user')
                <button type="button" class="btn btn-info btn-sm text-white btn-open-assign-plan-modal"
                    data-id="{{ $user->id }}"
                    data-name="{{ $user->name }}"
                    data-username="{{ $user->username }}"
                    data-plan="{{ $user->plan ? $user->plan->title : 'None' }}"
                    data-plan-id="{{ $user->plan_id ?? '' }}">
                    <i class="fas fa-layer-group me-1"></i> {{ __('Assign Plan') }}
                </button>
                @endif

                <a href="{{ route('admin.users.login-as', $user->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As User') }}
                </a>
                @endif

                @can('users-update')
                <a href="{{ route('admin.users.edit', [$user->id, 'users' => $user->role]) }}" class="btn btn-outline-warning btn-sm">
                    <i class="fal fa-pencil-alt me-1"></i> {{ __('Edit User') }}
                </a>
                @endcan
            </div>
        </div>

        <!-- Top KPI Overview Cards -->
        <div class="row g-3 mb-4">
            <!-- Current Balance -->
            <div class="col-sm-6 col-xl-3">
                <div class="card kpi-card shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small text-uppercase fw-semibold">{{ __('Current Balance') }}</span>
                                <h3 class="fw-bold mb-0 mt-1 {{ $user->balance <= $user->low_blnc_alrt ? 'text-danger' : 'text-success' }}">
                                    {{ currency_format($user->balance) }}
                                </h3>
                                <small class="text-muted">
                                    <i class="fas fa-bell me-1"></i>{{ __('Alert at') }}: {{ currency_format($user->low_blnc_alrt) }}
                                </small>
                            </div>
                            <div class="kpi-icon bg-success-subtle text-success">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Plan -->
            <div class="col-sm-6 col-xl-3">
                <div class="card kpi-card shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small text-uppercase fw-semibold">{{ __('Assigned Plan') }}</span>
                                <h4 class="fw-bold mb-0 mt-1 text-primary">
                                    {{ $user->plan->title ?? __('No Active Plan') }}
                                </h4>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    @if($user->will_expire)
                                        {{ __('Expires') }}: {{ formatted_date($user->will_expire) }}
                                    @else
                                        {{ __('No Expiry Date') }}
                                    @endif
                                </small>
                            </div>
                            <div class="kpi-icon bg-primary-subtle text-primary">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total SMS Sent & Success Rate -->
            <div class="col-sm-6 col-xl-3">
                <div class="card kpi-card shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small text-uppercase fw-semibold">{{ __('SMS Activity') }}</span>
                                <h3 class="fw-bold mb-0 mt-1 text-dark">
                                    {{ number_format($stats['total_sms']) }}
                                </h3>
                                <small class="text-muted">
                                    <span class="text-success fw-bold">{{ $stats['delivered_sms'] }}</span> {{ __('delivered') }}
                                    @if($stats['failed_sms'] > 0)
                                        &bull; <span class="text-danger fw-bold">{{ $stats['failed_sms'] }}</span> {{ __('failed') }}
                                    @endif
                                </small>
                            </div>
                            <div class="kpi-icon bg-info-subtle text-info">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMS Pricing Rates -->
            <div class="col-sm-6 col-xl-3">
                <div class="card kpi-card shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small text-uppercase fw-semibold">{{ __('SMS Rates') }}</span>
                                <div class="mt-1">
                                    <span class="badge bg-light text-dark border me-1">
                                        {{ __('Non-Masking') }}: <strong>৳{{ number_format((float)($user->non_masking_rate ?? 0.39), 4) }}</strong>
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <span class="badge bg-light text-dark border">
                                        {{ __('Masking') }}: <strong>৳{{ number_format((float)($user->masking_rate ?? 0.75), 4) }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="kpi-icon bg-warning-subtle text-warning">
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: User Profile & API Details -->
            <div class="col-lg-4">
                <!-- Profile Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-body p-4 text-center border-bottom bg-light rounded-top">
                        <img src="{{ asset($user->image ?? 'assets/images/profile/avatar.jpg') }}" alt="{{ $user->name }}" class="rounded-circle user-avatar-lg mb-3">
                        <h5 class="fw-bold mb-1 text-dark">{{ $user->name }}</h5>
                        <p class="text-muted font-monospace mb-2 small"><i class="fas fa-at"></i> {{ $user->username }}</p>
                        <span class="badge bg-primary px-3 py-1 text-uppercase">{{ $user->role }}</span>
                        @if($user->parent)
                            <div class="mt-2 text-muted small">
                                <i class="fas fa-level-up-alt"></i> {{ __('Under') }}: 
                                <a href="{{ route('admin.users.show', $user->parent->id) }}" class="fw-bold">{{ $user->parent->name }}</a>
                            </div>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase text-muted small mb-3">{{ __('Contact & Personal Info') }}</h6>
                        <ul class="info-list-group list-unstyled mb-0">
                            <li>
                                <span class="text-muted"><i class="fas fa-envelope me-2"></i>{{ __('Email') }}</span>
                                <span class="fw-semibold text-end text-break">{{ $user->email }}</span>
                            </li>
                            <li>
                                <span class="text-muted"><i class="fas fa-phone me-2"></i>{{ __('Phone') }}</span>
                                <span class="fw-semibold">{{ $user->phone ?: '—' }}</span>
                            </li>
                            <li>
                                <span class="text-muted"><i class="fas fa-globe me-2"></i>{{ __('Country') }}</span>
                                <span class="fw-semibold">{{ $user->country ?: 'Bangladesh' }}</span>
                            </li>
                            <li>
                                <span class="text-muted"><i class="fas fa-building me-2"></i>{{ __('Company') }}</span>
                                <span class="fw-semibold">{{ $user->company_name ?: '—' }}</span>
                            </li>
                            <li>
                                <span class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>{{ __('Address') }}</span>
                                <span class="fw-semibold text-end">{{ $user->address ?: '—' }}</span>
                            </li>
                            <li>
                                <span class="text-muted"><i class="fas fa-calendar-check me-2"></i>{{ __('Registered') }}</span>
                                <span class="fw-semibold">{{ formatted_date($user->created_at) }}</span>
                            </li>
                            @if($user->notes)
                            <li>
                                <span class="text-muted"><i class="fas fa-sticky-note me-2"></i>{{ __('Notes') }}</span>
                                <span class="text-end text-muted small">{{ $user->notes }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- API & Developer Credentials Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fas fa-key text-primary me-2"></i>{{ __('API & Credentials') }}
                        </h6>
                        <span class="badge {{ $user->allow_api ? 'bg-success' : 'bg-secondary' }}">
                            {{ $user->allow_api ? __('API Enabled') : __('API Disabled') }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <!-- Client ID -->
                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-semibold mb-1">{{ __('Client ID / Account ID') }}</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control font-monospace" value="{{ $user->client_id }}" id="client-id-field" readonly>
                                <button class="btn btn-outline-secondary copy-btn" type="button" onclick="copyToClipboard('{{ $user->client_id }}', this)" title="{{ __('Copy Client ID') }}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Client Secret -->
                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-semibold mb-1">{{ __('Client Secret / API Key') }}</label>
                            <div class="input-group input-group-sm">
                                <input type="password" class="form-control font-monospace" value="{{ $user->client_secret }}" id="client-secret-field" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleSecretVisibility('client-secret-field', this)" title="{{ __('Toggle Visibility') }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-secondary copy-btn" type="button" onclick="copyToClipboard('{{ $user->client_secret }}', this)" title="{{ __('Copy Client Secret') }}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Whitelisted IPs -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold small text-uppercase text-muted mb-2">
                                <i class="fas fa-shield-alt text-success me-1"></i> {{ __('Whitelisted IP Addresses') }}
                            </h6>
                            @if($user->senderIps && $user->senderIps->count() > 0)
                                <ul class="list-group list-group-flush small">
                                    @foreach($user->senderIps as $ipRecord)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                            <div>
                                                <code class="fw-bold text-dark">{{ $ipRecord->ip_address }}</code>
                                                @if($ipRecord->note)
                                                    <br><small class="text-muted">{{ $ipRecord->note }}</small>
                                                @endif
                                            </div>
                                            <span class="badge {{ $ipRecord->status == 1 ? 'bg-success' : 'bg-warning' }}">
                                                {{ $ipRecord->status == 1 ? __('Active') : __('Inactive') }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="alert alert-light border small text-muted mb-0 py-2">
                                    <i class="fas fa-info-circle me-1"></i> {{ __('No IP whitelist set (Open / Unrestricted access).') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sender IDs, SMS Logs, Subscriptions, Recharges -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom p-0">
                        <ul class="nav nav-tabs card-header-tabs m-0 px-3" id="userDetailTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="senderids-tab" data-bs-toggle="tab" data-bs-target="#tab-senderids" type="button" role="tab">
                                    <i class="fas fa-sim-card me-1 text-primary"></i> {{ __('Sender IDs') }} ({{ $senderids->count() }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sms-tab" data-bs-toggle="tab" data-bs-target="#tab-sms" type="button" role="tab">
                                    <i class="fas fa-sms me-1 text-info"></i> {{ __('Recent SMS') }} ({{ $stats['total_sms'] }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="subscriptions-tab" data-bs-toggle="tab" data-bs-target="#tab-subscriptions" type="button" role="tab">
                                    <i class="fas fa-layer-group me-1 text-warning"></i> {{ __('Subscriptions') }} ({{ $stats['total_subscriptions'] }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="recharges-tab" data-bs-toggle="tab" data-bs-target="#tab-recharges" type="button" role="tab">
                                    <i class="fas fa-wallet me-1 text-success"></i> {{ __('Recharge History') }} ({{ $recentRecharges->count() }})
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-3">
                        <div class="tab-content" id="userDetailTabsContent">
                            <!-- TAB 1: Assigned Sender IDs -->
                            <div class="tab-pane fade show active" id="tab-senderids" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0">{{ __('Approved Sender IDs Assigned to This User') }}</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>{{ __('Sender ID') }}</th>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('Gateway') }}</th>
                                                <th>{{ __('Rate') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($senderids as $index => $sid)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <strong class="text-primary font-monospace">{{ $sid->sender }}</strong>
                                                        @if($sid->is_default)
                                                            <span class="badge bg-primary-subtle text-primary ms-1 small">{{ __('Default') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ stripos($sid->type, 'mask') !== false && stripos($sid->type, 'non') === false ? 'bg-info' : 'bg-secondary' }}">
                                                            {{ $sid->type ?: 'Non-Masking' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($sid->gateway)
                                                            <span class="fw-semibold text-dark">{{ $sid->gateway->name }}</span>
                                                        @else
                                                            <span class="text-muted">{{ __('Not Assigned') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($sid->rate)
                                                            <strong>৳{{ number_format($sid->rate, 4) }}</strong>
                                                        @else
                                                            <span class="text-muted">{{ __('User Rate') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $sid->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $sid->status == 1 ? __('Active') : __('Inactive') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">
                                                        <i class="fas fa-info-circle me-1"></i> {{ __('No Sender IDs assigned to this account.') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB 2: Recent SMS Activity -->
                            <div class="tab-pane fade" id="tab-sms" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0">{{ __('Latest 10 SMS Sent') }}</h6>
                                    @if($stats['total_sms'] > 0)
                                        <a href="{{ route('admin.reports.quick-sms') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-list me-1"></i> {{ __('View Full Reports') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle small">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Recipient') }}</th>
                                                <th>{{ __('Sender') }}</th>
                                                <th>{{ __('Gateway') }}</th>
                                                <th>{{ __('SMS / Charge') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Date & Time') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentSms as $idx => $item)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td class="font-monospace fw-bold">{{ $item->number }}</td>
                                                    <td>{{ $item->senderid->sender ?? '—' }}</td>
                                                    <td>{{ $item->smsgateway->name ?? '—' }}</td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border">{{ $item->total_sms }} {{ __('part(s)') }}</span>
                                                        <br><small class="text-muted">৳{{ number_format((float)$item->charge, 4) }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $item->status == 'success' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ ucfirst($item->status) }}
                                                        </span>
                                                        @if($item->infozillion_response)
                                                            <br><small class="text-muted" style="font-size: 10px;">{{ Str::limit($item->infozillion_response, 20) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ formatted_date($item->created_at) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">
                                                        <i class="fas fa-inbox me-1"></i> {{ __('No SMS activity recorded yet.') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB 3: Subscriptions -->
                            <div class="tab-pane fade" id="tab-subscriptions" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0">{{ __('Subscription & Package History') }}</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle small">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Invoice') }}</th>
                                                <th>{{ __('Plan') }}</th>
                                                <th>{{ __('Price') }}</th>
                                                <th>{{ __('Total SMS') }}</th>
                                                <th>{{ __('Expires') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentSubscriptions as $sub)
                                                <tr>
                                                    <td class="fw-bold font-monospace">{{ $sub->invoice_no }}</td>
                                                    <td>{{ $sub->plan->title ?? '—' }}</td>
                                                    <td class="fw-bold">{{ currency_format($sub->price) }}</td>
                                                    <td>{{ number_format($sub->total_sms ?? 0) }}</td>
                                                    <td>{{ $sub->will_expire ?: '—' }}</td>
                                                    <td>
                                                        <span class="badge {{ $sub->status == 'approved' ? 'bg-success' : ($sub->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                            {{ ucfirst($sub->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ formatted_date($sub->created_at) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">
                                                        <i class="fas fa-info-circle me-1"></i> {{ __('No subscription history found.') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB 4: Recharge History -->
                            <div class="tab-pane fade" id="tab-recharges" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0">{{ __('Account Recharges') }}</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle small">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Invoice') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Gateway / Method') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentRecharges as $rec)
                                                <tr>
                                                    <td class="fw-bold font-monospace">{{ $rec->invoice_no }}</td>
                                                    <td class="fw-bold text-success">{{ currency_format($rec->amount) }}</td>
                                                    <td>{{ $rec->gateway->name ?? __('System / Admin') }}</td>
                                                    <td>
                                                        <span class="badge {{ $rec->status == 'approved' ? 'bg-success' : ($rec->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                            {{ ucfirst($rec->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ formatted_date($rec->created_at) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">
                                                        <i class="fas fa-receipt me-1"></i> {{ __('No recharge records found.') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modal')
    @include('admin.users.recharge-modal')
    @include('admin.users.assign-plan-modal')
@endpush

@push('js')
<script>
    function copyToClipboard(text, btnElement) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(function() {
            let originalHtml = $(btnElement).html();
            $(btnElement).html('<i class="fas fa-check text-success"></i>');
            setTimeout(function() {
                $(btnElement).html(originalHtml);
            }, 1500);
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
        });
    }

    function toggleSecretVisibility(fieldId, btnElement) {
        let field = document.getElementById(fieldId);
        if (!field) return;
        if (field.type === "password") {
            field.type = "text";
            $(btnElement).html('<i class="fas fa-eye-slash text-warning"></i>');
        } else {
            field.type = "password";
            $(btnElement).html('<i class="fas fa-eye"></i>');
        }
    }
</script>
@endpush
