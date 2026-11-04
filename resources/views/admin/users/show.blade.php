@extends('layouts.admin.master')

@section('title')
    {{ __('User Details') }} — {{ $user->name }}
@endsection

@push('css')
<style>
    /* Native software tab styling enhancements */
    .user-overview-tabs {
        border-bottom: 2px solid #eef2f6;
        margin-bottom: 20px;
    }
    .user-overview-tabs .nav-link {
        font-family: "Manrope", sans-serif;
        font-weight: 600;
        font-size: 14px;
        color: #64748b;
        border: none;
        padding: 10px 18px;
        border-radius: 8px 8px 0 0;
        background: transparent;
        transition: all 0.2s ease;
    }
    .user-overview-tabs .nav-link:hover {
        color: #4876FF;
        background: #f8fafc;
    }
    .user-overview-tabs .nav-link.active {
        color: #4876FF;
        background: #fff;
        border-bottom: 2px solid #4876FF;
    }
    .erp-dashboard-profile .list-group-item {
        border: none;
        border-bottom: 1px dashed #e5e7eb;
        padding: 11px 0;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .erp-dashboard-profile .list-group-item:last-child {
        border-bottom: none;
    }
    .erp-dashboard-profile .list-group-item span:first-child {
        color: #64748b;
        font-weight: 500;
    }
    .copy-pill-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 2px 6px;
        color: #4876FF;
        transition: opacity 0.15s;
    }
    .copy-pill-btn:hover {
        opacity: 0.75;
    }
</style>
@endpush

@section('main_content')
<div class="erp-state-overview-section">
    <div class="container-fluid">

        <!-- Top Native Counter Cards (gpt-dashboard-card grid-5) -->
        <div class="gpt-dashboard-card grid-5 mb-30">
            <!-- Balance Card -->
            <div class="couter-box">
                <div class="icons">
                    <img src="{{ asset('assets/img/icon/credit.svg') }}" alt="Balance">
                </div>
                <div class="content-side">
                    <h5 class="{{ $user->balance <= $user->low_blnc_alrt ? 'text-danger' : 'text-success' }}">
                        {{ currency_format($user->balance) }}
                    </h5>
                    <p>{{ __('Current Balance') }}</p>
                </div>
            </div>

            <!-- Total Sale Card -->
            <div class="couter-box">
                <div class="icons" style="background-color: #e0f2fe;">
                    <img src="{{ asset('assets/img/icon/banner.svg') }}" alt="Sale">
                </div>
                <div class="content-side">
                    <h5 class="text-primary fw-bold">
                        {{ currency_format($stats['total_revenue']) }}
                    </h5>
                    <p>{{ __('Total Sale') }}</p>
                </div>
            </div>

            <!-- Used Amount Card -->
            <div class="couter-box">
                <div class="icons" style="background-color: #fef3c7;">
                    <img src="{{ asset('assets/img/icon/reward.svg') }}" alt="Used">
                </div>
                <div class="content-side">
                    <h5 class="fw-bold" style="color: #d97706;">
                        {{ currency_format($stats['used_revenue']) }}
                    </h5>
                    <p>{{ __('Used Amount') }}</p>
                </div>
            </div>

            <!-- Profit from Used Card -->
            <div class="couter-box">
                <div class="icons" style="background-color: #d1fae5;">
                    <img src="{{ asset('assets/img/icon/crown-king.svg') }}" alt="Profit">
                </div>
                <div class="content-side">
                    <h5 class="text-success fw-bold">
                        {{ currency_format($stats['used_profit']) }}
                    </h5>
                    <p>
                        {{ __('Profit from Used') }} (<strong>{{ $stats['used_margin'] }}%</strong>)
                    </p>
                </div>
            </div>

            <!-- Gateway Cost Card -->
            <div class="couter-box">
                <div class="icons">
                    <img src="{{ asset('assets/img/icon/category.svg') }}" alt="Cost">
                </div>
                <div class="content-side">
                    <h5 class="text-danger fw-bold">
                        {{ currency_format($stats['total_cost']) }}
                    </h5>
                    <p>{{ __('Gateway Cost') }}</p>
                </div>
            </div>
        </div>

        <!-- Main Profile & Details Section -->
        <div class="row">
            <!-- Left Side: Profile Card & API Credentials (erp-dashboard-profile) -->
            <div class="col-lg-4 mb-4">
                <div class="erp-dashboard-profile">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <!-- Cover Photo -->
                            <div class="profile-bg">
                                <img src="{{ asset('assets/images/profile/cover-photo.jpg') }}" alt="profile-bg">
                            </div>

                            <!-- Avatar -->
                            <div class="profile-img">
                                <img id="profile_picture" src="{{ asset($user->image ?? 'assets/images/icons/default-user.png') }}" alt="{{ $user->name }}">
                            </div>

                            <!-- Profile Details -->
                            <div class="profile-details card-body text-center pt-2">
                                <h4 class="fw-bold mb-1 text-dark">{{ $user->name }}</h4>
                                <p class="text-primary font-monospace mb-2 small"><i class="fas fa-at"></i> {{ $user->username }}</p>
                                
                                <div class="d-flex justify-content-center flex-wrap gap-1 mb-3">
                                    <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                    <span class="badge bg-secondary">
                                        {{ strtoupper($user->role) }}
                                    </span>
                                    <span class="badge {{ $user->allow_api ? 'bg-info text-dark' : 'bg-secondary' }}">
                                        {{ $user->allow_api ? __('API Enabled') : __('API Disabled') }}
                                    </span>
                                </div>

                                @if($user->parent)
                                <div class="p-2 mb-3 bg-light rounded border text-muted small text-start">
                                    <i class="fas fa-level-up-alt text-primary me-1"></i> {{ __('Parent Reseller') }}: 
                                    <a href="{{ route('admin.users.show', $user->parent->id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $user->parent->name }} ({{ strtoupper($user->parent->role) }})
                                    </a>
                                </div>
                                @endif

                                <ul class="list-group text-start">
                                    <li class="list-group-item" style="background-color: #f8fafc;">
                                        <span><i class="fas fa-shopping-cart text-primary me-1"></i> {{ __('Total Sale') }}:</span>
                                        <strong class="text-primary">{{ currency_format($stats['total_revenue']) }}</strong>
                                    </li>
                                    <li class="list-group-item" style="background-color: #fffbeb;">
                                        <span><i class="fas fa-coins text-warning me-1"></i> {{ __('Used Amount') }}:</span>
                                        <strong style="color: #b45309;">{{ currency_format($stats['used_revenue']) }}</strong>
                                    </li>
                                    <li class="list-group-item" style="background-color: #f0fdf4;">
                                        <span><i class="fas fa-hand-holding-usd text-success me-1"></i> {{ __('Profit from Used') }}:</span>
                                        <strong class="text-success">{{ currency_format($stats['used_profit']) }} ({{ $stats['used_margin'] }}%)</strong>
                                    </li>
                                    <li class="list-group-item">
                                        <span><i class="fas fa-receipt text-danger me-1"></i> {{ __('Gateway Cost') }}:</span>
                                        <strong class="text-danger">{{ currency_format($stats['total_cost']) }}</strong>
                                    </li>
                                    <li class="list-group-item">
                                        <span><i class="fas fa-chart-line text-dark me-1"></i> {{ __('Total Sale Profit') }}:</span>
                                        <strong class="text-dark">{{ currency_format($stats['net_profit']) }} ({{ $stats['profit_margin'] }}%)</strong>
                                    </li>
                                    <li class="list-group-item">
                                        <span><i class="fas fa-wallet text-secondary me-1"></i> {{ __('Current Balance') }}:</span>
                                        <strong class="text-dark">{{ currency_format($user->balance) }}</strong>
                                    </li>
                                    <li class="list-group-item">
                                        <span><i class="fas fa-history text-muted me-1"></i> {{ __('Total Recharged') }}:</span>
                                        <strong class="text-dark">{{ currency_format($stats['total_recharged']) }}</strong>
                                    </li>
                                    <li class="list-group-item">
                                        <span><i class="fas fa-sms text-info me-1"></i> {{ __('Total SMS Sent') }}:</span>
                                        <strong class="text-dark">{{ number_format($stats['total_sms']) }} ({{ $stats['delivered_sms'] }} {{ __('Delivered') }})</strong>
                                    </li>
                                    <li class="list-group-item">
                                        <span>{{ __('Email') }}:</span>
                                        <strong class="text-dark text-break">{{ $user->email }}</strong>
                                    </li>
                                    <li class="list-group-item">
                                        <span>{{ __('Phone') }}:</span>
                                        <strong class="text-dark">{{ $user->phone ?: '—' }}</strong>
                                    </li>
                                    <li class="list-group-item">
                                        <span>{{ __('Client ID') }}:</span>
                                        <span class="d-inline-flex align-items-center">
                                            <strong class="font-monospace text-primary me-1">{{ $user->client_id }}</strong>
                                            <button type="button" class="copy-pill-btn" onclick="copyText('{{ $user->client_id }}', this)" title="{{ __('Copy Client ID') }}">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <span>{{ __('Client Secret') }}:</span>
                                        <div class="d-inline-flex align-items-center">
                                            <span id="secret-text-mask" class="font-monospace text-muted small me-1">••••••••••••••••</span>
                                            <span id="secret-text-real" class="font-monospace text-dark small me-1" style="display: none;">{{ $user->client_secret }}</span>
                                            <button type="button" class="copy-pill-btn" onclick="toggleSecretDisplay()" title="{{ __('Toggle Visibility') }}">
                                                <i class="fas fa-eye" id="secret-eye-icon"></i>
                                            </button>
                                            <button type="button" class="copy-pill-btn" onclick="copyText('{{ $user->client_secret }}', this)" title="{{ __('Copy Client Secret') }}">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <span>{{ __('Country') }}:</span>
                                        <strong class="text-dark">{{ $user->country ?: 'Bangladesh' }}</strong>
                                    </li>
                                    @if($user->company_name)
                                    <li class="list-group-item">
                                        <span>{{ __('Company') }}:</span>
                                        <strong class="text-dark">{{ $user->company_name }}</strong>
                                    </li>
                                    @endif
                                    @if($user->address)
                                    <li class="list-group-item">
                                        <span>{{ __('Address') }}:</span>
                                        <strong class="text-dark text-end">{{ $user->address }}</strong>
                                    </li>
                                    @endif
                                    <li class="list-group-item">
                                        <span>{{ __('Registration Date') }}:</span>
                                        <strong class="text-dark">{{ formatted_date($user->created_at) }}</strong>
                                    </li>
                                    @if($user->notes)
                                    <li class="list-group-item">
                                        <span>{{ __('Notes') }}:</span>
                                        <span class="text-muted small text-end">{{ $user->notes }}</span>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Whitelisted IPs Card -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-shield-alt text-success me-1"></i> {{ __('Whitelisted IPs') }}
                            </h5>
                            <span class="badge bg-light text-dark border">{{ $user->senderIps->count() }}</span>
                        </div>
                        @if($user->senderIps && $user->senderIps->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($user->senderIps as $ip)
                                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <code class="fw-bold text-dark">{{ $ip->ip_address }}</code>
                                            @if($ip->note)
                                                <br><small class="text-muted">{{ $ip->note }}</small>
                                            @endif
                                        </div>
                                        <span class="badge {{ $ip->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $ip->status == 1 ? __('Active') : __('Inactive') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-light border small text-muted mb-0 py-2">
                                <i class="fas fa-info-circle me-1"></i> {{ __('No IP whitelist restrictions (API is open to any IP).') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side: Action Buttons, Tabs & Data Tables -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <!-- Header Bar with Native Theme Buttons -->
                        <div class="table-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <h4>{{ __('Customer Overview') }} — {{ $user->name }}</h4>
                            <div class="user-btn d-flex flex-wrap gap-2 align-items-center">
                                @if(in_array($user->role, ['user', 'sp', 'ssp']))
                                <button type="button" class="theme-btn print-btn text-white btn-open-recharge-modal active" style="background-color: #198754; border-color: #198754;"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-username="{{ $user->username }}"
                                    data-role="{{ strtoupper($user->role) }}"
                                    data-balance="{{ (float)$user->balance }}"
                                    data-balance-formatted="{{ currency_format($user->balance) }}"
                                    title="{{ __('Recharge or Adjust Balance') }}">
                                    <i class="fas fa-wallet me-1"></i> {{ __('Recharge Balance') }}
                                </button>

                                @if($user->role == 'user')
                                <button type="button" class="theme-btn print-btn text-white btn-open-assign-plan-modal active" style="background-color: #0d6efd; border-color: #0d6efd;"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-username="{{ $user->username }}"
                                    data-plan="{{ $user->plan ? $user->plan->title : 'None' }}"
                                    data-plan-id="{{ $user->plan_id ?? '' }}"
                                    title="{{ __('Assign or Change Plan') }}">
                                    <i class="fas fa-layer-group me-1"></i> {{ __('Assign Plan') }}
                                </button>
                                @endif

                                <a href="{{ route('admin.users.login-as', $user->id) }}" class="theme-btn print-btn text-light active" title="{{ __('Login As This User') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As') }}
                                </a>
                                @endif

                                @can('users-update')
                                <a href="{{ route('admin.users.edit', [$user->id, 'users' => $user->role]) }}" class="theme-btn print-btn text-light active" title="{{ __('Edit User') }}">
                                    <i class="fal fa-pencil-alt me-1"></i> {{ __('Edit') }}
                                </a>
                                @endcan

                                <a href="{{ route('admin.users.index', ['users' => $user->role]) }}" class="theme-btn print-btn text-light" title="{{ __('Back to User List') }}">
                                    <i class="fas fa-arrow-left me-1"></i> {{ __('User List') }}
                                </a>
                            </div>
                        </div>

                        <!-- Native Navigation Tabs -->
                        <ul class="nav user-overview-tabs" id="userTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-senderids-btn" data-bs-toggle="tab" data-bs-target="#tab-senderids" type="button" role="tab">
                                    <i class="fas fa-sim-card me-1"></i> {{ __('Sender IDs') }} ({{ $senderids->count() }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-profit-btn" data-bs-toggle="tab" data-bs-target="#tab-profit" type="button" role="tab" style="color: #198754;">
                                    <i class="fas fa-chart-line me-1"></i> {{ __('Profit & Sale') }} ({{ currency_format($stats['used_profit']) }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-sms-btn" data-bs-toggle="tab" data-bs-target="#tab-sms" type="button" role="tab">
                                    <i class="fas fa-sms me-1"></i> {{ __('Recent SMS') }} ({{ $stats['total_sms'] }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-subscriptions-btn" data-bs-toggle="tab" data-bs-target="#tab-subscriptions" type="button" role="tab">
                                    <i class="fas fa-layer-group me-1"></i> {{ __('Subscriptions') }} ({{ $stats['total_subscriptions'] }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-recharges-btn" data-bs-toggle="tab" data-bs-target="#tab-recharges" type="button" role="tab">
                                    <i class="fas fa-wallet me-1"></i> {{ __('Recharges') }} ({{ $recentRecharges->count() }})
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Panes -->
                        <div class="tab-content" id="userTabsContent">
                            <!-- TAB 1: Sender IDs Table -->
                            <div class="tab-pane fade show active" id="tab-senderids" role="tabpanel">
                                <div class="responsive-table">
                                    <table class="table" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SL') }}.</th>
                                                <th>{{ __('Sender ID') }}</th>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('SMS Gateway') }}</th>
                                                <th>{{ __('Custom Rate') }}</th>
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
                                                            <strong>{{ $sid->gateway->name }}</strong>
                                                        @else
                                                            <span class="text-muted">{{ __('Not Assigned') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($sid->rate)
                                                            <strong class="text-dark">৳{{ number_format($sid->rate, 4) }}</strong>
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
                                                        <i class="fas fa-info-circle me-1"></i> {{ __('No sender IDs assigned to this account.') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB 2: Profit & Financial Breakdown -->
                            <div class="tab-pane fade" id="tab-profit" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0 text-dark">
                                        <i class="fas fa-chart-line text-success me-1"></i> {{ __('Profit & Sale Summary') }}
                                    </h5>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-warning-subtle text-dark fs-6 border px-3 py-1">
                                            {{ __('Profit from Used') }}: <strong class="text-success">{{ currency_format($stats['used_profit']) }}</strong> ({{ $stats['used_margin'] }}%)
                                        </span>
                                        <span class="badge bg-success-subtle text-success fs-6 border border-success px-3 py-1">
                                            {{ __('Total Profit') }}: <strong>{{ currency_format($stats['net_profit']) }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <!-- Highlight Performance Alert Banner -->
                                <div class="alert alert-success border-success-subtle d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 py-3 px-3 rounded-3" style="background-color: #f0fdf4;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: #dcfce7; color: #16a34a; font-size: 20px;">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1 text-success">{{ __('Total Sales & Profit from Used SMS') }}</h6>
                                            <p class="mb-0 text-muted small">
                                                {{ __('Total Sale') }}: <strong class="text-primary">{{ currency_format($stats['total_revenue']) }}</strong> &bull; 
                                                {{ __('Used Amount') }}: <strong style="color: #b45309;">{{ currency_format($stats['used_revenue']) }}</strong> &bull; 
                                                {{ __('Gateway Cost') }}: <strong class="text-danger">{{ currency_format($stats['used_cost']) }}</strong> &bull; 
                                                {{ __('Profit from Used') }}: <strong class="text-success">{{ currency_format($stats['used_profit']) }} ({{ $stats['used_margin'] }}%)</strong> &bull; 
                                                {{ __('Remaining Balance') }}: <strong class="text-dark">{{ currency_format($user->balance) }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 4 Financial Summary Boxes -->
                                <div class="row g-3 mb-4">
                                    <div class="col-sm-6 col-md-3">
                                        <div class="p-3 bg-light rounded border">
                                            <span class="text-muted small text-uppercase fw-bold">{{ __('Total Sale (Revenue)') }}</span>
                                            <h4 class="fw-bold text-primary mt-1 mb-0">{{ currency_format($stats['total_revenue']) }}</h4>
                                            <small class="text-muted">{{ __('Plan') }}: {{ currency_format($stats['subscription_revenue']) }} | {{ __('Used') }}: {{ currency_format($stats['used_revenue']) }}</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="p-3 bg-light rounded border">
                                            <span class="text-muted small text-uppercase fw-bold">{{ __('Used Amount (SMS Usage)') }}</span>
                                            <h4 class="fw-bold mt-1 mb-0" style="color: #d97706;">{{ currency_format($stats['used_revenue']) }}</h4>
                                            <small class="text-muted">{{ number_format($stats['total_sms']) }} {{ __('SMS Sent') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="p-3 rounded border" style="background-color: #ecfdf5; border-color: #a7f3d0 !important;">
                                            <span class="text-success small text-uppercase fw-bold">{{ __('Profit from Used') }}</span>
                                            <h4 class="fw-bold text-success mt-1 mb-0">{{ currency_format($stats['used_profit']) }}</h4>
                                            <small class="text-success">{{ $stats['used_margin'] }}% {{ __('Margin of Used') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="p-3 bg-light rounded border">
                                            <span class="text-muted small text-uppercase fw-bold">{{ __('Gateway Cost (Used)') }}</span>
                                            <h4 class="fw-bold text-danger mt-1 mb-0">{{ currency_format($stats['used_cost']) }}</h4>
                                            <small class="text-muted">{{ __('Operator Buying Cost') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Breakdown Table -->
                                <div class="responsive-table">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Revenue & Cost Category') }}</th>
                                                <th>{{ __('Billed to User (Revenue)') }}</th>
                                                <th>{{ __('Gateway Buying Cost') }}</th>
                                                <th>{{ __('Net Profit / (Loss)') }}</th>
                                                <th>{{ __('Margin') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong class="text-dark">{{ __('Non-Masking SMS') }}</strong>
                                                    <br><small class="text-muted">{{ __('User Rate') }}: ৳{{ number_format((float)($user->non_masking_rate ?? 0.39), 2) }}</small>
                                                </td>
                                                <td class="fw-bold text-dark">{{ currency_format($stats['non_masking_revenue']) }}</td>
                                                <td class="text-danger">{{ currency_format($stats['non_masking_cost']) }}</td>
                                                <td class="fw-bold {{ $stats['non_masking_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ currency_format($stats['non_masking_profit']) }}
                                                </td>
                                                <td>
                                                    @php
                                                        $nmMargin = $stats['non_masking_revenue'] > 0 ? round(($stats['non_masking_profit'] / $stats['non_masking_revenue']) * 100, 1) : 0;
                                                    @endphp
                                                    <span class="badge {{ $nmMargin >= 0 ? 'bg-success' : 'bg-danger' }}">{{ $nmMargin }}%</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong class="text-dark">{{ __('Masking SMS') }}</strong>
                                                    <br><small class="text-muted">{{ __('User Rate') }}: ৳{{ number_format((float)($user->masking_rate ?? 0.75), 2) }}</small>
                                                </td>
                                                <td class="fw-bold text-dark">{{ currency_format($stats['masking_revenue']) }}</td>
                                                <td class="text-danger">{{ currency_format($stats['masking_cost']) }}</td>
                                                <td class="fw-bold {{ $stats['masking_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ currency_format($stats['masking_profit']) }}
                                                </td>
                                                <td>
                                                    @php
                                                        $mMargin = $stats['masking_revenue'] > 0 ? round(($stats['masking_profit'] / $stats['masking_revenue']) * 100, 1) : 0;
                                                    @endphp
                                                    <span class="badge {{ $mMargin >= 0 ? 'bg-success' : 'bg-danger' }}">{{ $mMargin }}%</span>
                                                </td>
                                            </tr>
                                            <!-- Subtotal: Total Used SMS -->
                                            <tr style="background-color: #f8fafc; font-weight: 600;">
                                                <td class="text-dark">
                                                    <i class="fas fa-coins text-warning me-1"></i> {{ __('Subtotal: Total Used SMS') }}
                                                </td>
                                                <td class="fw-bold" style="color: #b45309;">{{ currency_format($stats['used_revenue']) }}</td>
                                                <td class="fw-bold text-danger">{{ currency_format($stats['used_cost']) }}</td>
                                                <td class="fw-bold text-success">{{ currency_format($stats['used_profit']) }}</td>
                                                <td><span class="badge bg-success">{{ $stats['used_margin'] }}%</span></td>
                                            </tr>
                                            @if($stats['subscription_revenue'] > 0)
                                            <tr>
                                                <td>
                                                    <strong class="text-dark">{{ __('Plan Subscriptions') }}</strong>
                                                    <br><small class="text-muted">{{ __('Package purchase revenue') }}</small>
                                                </td>
                                                <td class="fw-bold text-dark">{{ currency_format($stats['subscription_revenue']) }}</td>
                                                <td class="text-muted">৳0.00</td>
                                                <td class="fw-bold text-success">{{ currency_format($stats['subscription_revenue']) }}</td>
                                                <td><span class="badge bg-success">100%</span></td>
                                            </tr>
                                            @endif
                                            <tr class="table-light fw-bold" style="font-size: 15px;">
                                                <td>{{ __('Total All-Time Platform Net Profit') }}</td>
                                                <td class="text-primary">{{ currency_format($stats['total_revenue']) }}</td>
                                                <td class="text-danger">{{ currency_format($stats['total_cost']) }}</td>
                                                <td class="text-success">{{ currency_format($stats['net_profit']) }}</td>
                                                <td><span class="badge bg-success fs-6">{{ $stats['profit_margin'] }}%</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Wallet & Recharge Correlation -->
                                <div class="row mt-3 g-3">
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded border text-center">
                                            <span class="text-muted small">{{ __('Total Recharges Approved') }}</span>
                                            <h5 class="fw-bold text-dark mt-1 mb-0">{{ currency_format($stats['total_recharged']) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded border text-center">
                                            <span class="text-muted small">{{ __('Total Consumed in SMS') }}</span>
                                            <h5 class="fw-bold text-danger mt-1 mb-0">{{ currency_format($stats['sms_revenue']) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded border text-center">
                                            <span class="text-muted small">{{ __('Remaining Wallet Balance') }}</span>
                                            <h5 class="fw-bold text-success mt-1 mb-0">{{ currency_format($user->balance) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-sms" role="tabpanel">
                                <div class="responsive-table">
                                    <table class="table" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SL') }}.</th>
                                                <th>{{ __('Recipient') }}</th>
                                                <th>{{ __('Sender ID') }}</th>
                                                <th>{{ __('Gateway') }}</th>
                                                <th>{{ __('Parts / Charge') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Date & Time') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentSms as $idx => $item)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td><strong class="font-monospace text-dark">{{ $item->number }}</strong></td>
                                                    <td><span class="text-primary font-monospace">{{ $item->senderid->sender ?? '—' }}</span></td>
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
                                                            <br><small class="text-muted" style="font-size: 10px;">{{ Str::limit($item->infozillion_response, 25) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ formatted_date($item->created_at) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">
                                                        <i class="fas fa-inbox me-1"></i> {{ __('No SMS logs recorded yet for this user.') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TAB 3: Subscriptions -->
                            <div class="tab-pane fade" id="tab-subscriptions" role="tabpanel">
                                <div class="responsive-table">
                                    <table class="table" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Invoice No') }}.</th>
                                                <th>{{ __('Plan') }}</th>
                                                <th>{{ __('Price') }}</th>
                                                <th>{{ __('Total SMS') }}</th>
                                                <th>{{ __('Expires') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Subscribed At') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentSubscriptions as $sub)
                                                <tr>
                                                    <td><strong class="font-monospace">{{ $sub->invoice_no }}</strong></td>
                                                    <td>{{ $sub->plan->title ?? '—' }}</td>
                                                    <td><strong class="text-dark">{{ currency_format($sub->price) }}</strong></td>
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
                                <div class="responsive-table">
                                    <table class="table" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Invoice No') }}.</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Payment Gateway') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentRecharges as $rec)
                                                <tr>
                                                    <td><strong class="font-monospace">{{ $rec->invoice_no }}</strong></td>
                                                    <td><strong class="text-success">{{ currency_format($rec->amount) }}</strong></td>
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
                                                        <i class="fas fa-receipt me-1"></i> {{ __('No recharge history found.') }}
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
    function copyText(text, btnElement) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(function() {
            let originalHtml = $(btnElement).html();
            $(btnElement).html('<i class="fas fa-check text-success"></i>');
            setTimeout(function() {
                $(btnElement).html(originalHtml);
            }, 1500);
        }).catch(function(err) {
            console.error('Copy failed: ', err);
        });
    }

    function toggleSecretDisplay() {
        let mask = document.getElementById('secret-text-mask');
        let real = document.getElementById('secret-text-real');
        let icon = document.getElementById('secret-eye-icon');
        if (!mask || !real || !icon) return;

        if (real.style.display === "none") {
            real.style.display = "inline";
            mask.style.display = "none";
            icon.className = "fas fa-eye-slash text-warning";
        } else {
            real.style.display = "none";
            mask.style.display = "inline";
            icon.className = "fas fa-eye";
        }
    }
</script>
@endpush
