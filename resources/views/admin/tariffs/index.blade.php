@extends('layouts.admin.master')

@section('title')
    {{ __('Tariff Management') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <!-- Top Metrics Cards -->
        <div class="gpt-dashboard-card grid-4 mb-30">
            <div class="couter-box">
                <div class="icons bg-primary-light">
                    <img src="{{ asset('assets/img/icon/crown-king.svg') }}" alt="Wholesale">
                </div>
                <div class="content-side">
                    <h5 class="text-primary fw-bold">৳{{ number_format($gwNonMasking, 2) }} / ৳{{ number_format($gwMasking, 2) }}</h5>
                    <p>{{ __('Gateway Buying Cost (NM / M)') }}</p>
                </div>
            </div>

            <div class="couter-box">
                <div class="icons bg-green2-light">
                    <img src="{{ asset('assets/img/icon/user1.png') }}" alt="Customers">
                </div>
                <div class="content-side">
                    <h5 class="text-success fw-bold">{{ $totalUsers }}</h5>
                    <p>{{ __('Direct Customers') }}</p>
                </div>
            </div>

            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/banner.svg') }}" alt="Resellers">
                </div>
                <div class="content-side">
                    <h5 class="fw-bold" style="color: #6366f1;">{{ $totalSp }}</h5>
                    <p>{{ __('Resellers (SP)') }}</p>
                </div>
            </div>

            <div class="couter-box">
                <div class="icons bg-orange-light">
                    <img src="{{ asset('assets/img/icon/subscription.svg') }}" alt="Sub-Resellers">
                </div>
                <div class="content-side">
                    <h5 class="fw-bold" style="color: #ea580c;">{{ $totalSsp }}</h5>
                    <p>{{ __('Sub-Resellers (SSP)') }}</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <!-- Header Bar -->
                <div class="table-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="fas fa-tags text-primary me-2"></i>{{ __('Tariff & Rate Management') }}</h4>
                        <p class="text-muted small mb-0">{{ __('Manage per-SMS tariff rates for Customers, Resellers, and Sub-Resellers.') }}</p>
                    </div>

                    <!-- Role Filter Tabs -->
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <a href="{{ route('admin.tariffs.index', ['role' => 'all', 'search' => $search]) }}" class="btn btn-sm {{ $role == 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ __('All Accounts') }} ({{ $totalUsers + $totalSp + $totalSsp }})
                        </a>
                        <a href="{{ route('admin.tariffs.index', ['role' => 'user', 'search' => $search]) }}" class="btn btn-sm {{ $role == 'user' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ __('Customers') }} ({{ $totalUsers }})
                        </a>
                        <a href="{{ route('admin.tariffs.index', ['role' => 'sp', 'search' => $search]) }}" class="btn btn-sm {{ $role == 'sp' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ __('Resellers (SP)') }} ({{ $totalSp }})
                        </a>
                        <a href="{{ route('admin.tariffs.index', ['role' => 'ssp', 'search' => $search]) }}" class="btn btn-sm {{ $role == 'ssp' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ __('Sub Resellers (SSP)') }} ({{ $totalSsp }})
                        </a>
                    </div>
                </div>

                <!-- Search Form -->
                <div class="search-form-wrap mb-3">
                    <form action="{{ route('admin.tariffs.index') }}" method="GET" class="d-flex gap-2">
                        <input type="hidden" name="role" value="{{ $role }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name, username, email, or phone...') }}" value="{{ $search }}">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i> {{ __('Search') }}</button>
                            @if($search)
                                <a href="{{ route('admin.tariffs.index', ['role' => $role]) }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Tariffs Table -->
                <div class="responsive-table">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">{{ __('SL') }}</th>
                                <th>{{ __('Account / User') }}</th>
                                <th>{{ __('Role / Type') }}</th>
                                <th>{{ __('Parent Reseller') }}</th>
                                <th>{{ __('Non-Masking Tariff') }}</th>
                                <th>{{ __('Masking Tariff') }}</th>
                                <th>{{ __('Wallet Balance') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center" style="width: 130px;">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $u)
                                <tr class="user-tariff-cell-{{ $u->id }}">
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div>
                                                <a href="{{ route('admin.users.show', $u->id) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $u->name }}
                                                </a>
                                                <br><small class="text-primary font-monospace"><i class="fas fa-at"></i> {{ $u->username }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($u->role == 'sp')
                                            <span class="badge bg-primary">{{ __('RESELLER (SP)') }}</span>
                                        @elseif($u->role == 'ssp')
                                            <span class="badge bg-warning text-dark">{{ __('SUB-RESELLER (SSP)') }}</span>
                                        @else
                                            <span class="badge bg-info text-dark">{{ __('CUSTOMER') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($u->parent)
                                            <a href="{{ route('admin.users.show', $u->parent->id) }}" class="text-decoration-none">
                                                <i class="fas fa-level-up-alt text-primary me-1"></i>
                                                <strong class="text-dark">{{ $u->parent->name }}</strong>
                                                <br><small class="text-muted">({{ strtoupper($u->parent->role) }})</small>
                                            </a>
                                        @else
                                            <span class="text-muted">— ({{ __('Direct Softcents') }})</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fs-6 badge-nm font-monospace">
                                            ৳{{ number_format((float)($u->non_masking_rate ?? 0.39), 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fs-6 badge-m font-monospace">
                                            ৳{{ number_format((float)($u->masking_rate ?? 0.75), 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="{{ $u->balance <= $u->low_blnc_alrt ? 'text-danger' : 'text-success' }}">
                                            {{ currency_format($u->balance) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge {{ $u->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($u->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 btn-open-tariff-modal"
                                            data-id="{{ $u->id }}"
                                            data-name="{{ $u->name }}"
                                            data-username="{{ $u->username }}"
                                            data-role="{{ strtoupper($u->role) }}"
                                            data-non-masking="{{ (float)($u->non_masking_rate ?? 0.39) }}"
                                            data-masking="{{ (float)($u->masking_rate ?? 0.75) }}"
                                            title="{{ __('Update Tariff Rates') }}">
                                            <i class="fas fa-edit"></i>
                                            <span>{{ __('Set Tariff') }}</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fas fa-search me-1"></i> {{ __('No accounts found matching your criteria.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modal')
    @include('admin.users.tariff-modal')
@endpush
