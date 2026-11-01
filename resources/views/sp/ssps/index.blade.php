@extends('layouts.sp.master')

@section('title', __('My Sub Resellers (SSP)'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0 fw-bold"><i class="fas fa-sitemap text-primary me-2"></i>{{ __('Sub Resellers (SSP) List') }}</h4>
                <a href="{{ route('sp.ssps.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> {{ __('Add New SSP') }}
                </a>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('sp.ssps.index') }}" method="GET" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Search by name, username, email, phone...') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-search me-1"></i> {{ __('Search') }}</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('Name / Username') }}</th>
                                <th>{{ __('Contact') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('Masking Rate') }}</th>
                                <th>{{ __('Non-Masking Rate') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ssps as $ssp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $ssp->name }}</strong>
                                    <br><small class="text-muted"><i class="fas fa-at"></i> {{ $ssp->username }}</small>
                                </td>
                                <td>
                                    <div><i class="fas fa-envelope text-muted me-1"></i> {{ $ssp->email }}</div>
                                    <small><i class="fas fa-phone text-muted me-1"></i> {{ $ssp->phone ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold fs-6 text-success">{{ currency_format($ssp->balance) }}</span>
                                </td>
                                <td>{{ currency_format($ssp->masking_rate) }}</td>
                                <td>{{ currency_format($ssp->non_masking_rate) }}</td>
                                <td>
                                    <span class="badge {{ $ssp->status == 'active' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($ssp->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('sp.ssps.login-as', $ssp->id) }}" class="btn btn-sm btn-primary shadow-sm" title="{{ __('Login As Sub-Reseller (SSP)') }}">
                                            <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As') }}
                                        </a>

                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#rechargeModal{{ $ssp->id }}" title="{{ __('Transfer / Deduct Balance') }}">
                                            <i class="fas fa-coins me-1"></i> {{ __('Balance') }}
                                        </button>

                                        <a href="{{ route('sp.ssps.edit', $ssp->id) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Edit') }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </div>

                                    <!-- Modal Recharge -->
                                    <div class="modal fade" id="rechargeModal{{ $ssp->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <form action="{{ route('sp.ssps.recharge', $ssp->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('Manage Balance for ') }}: {{ $ssp->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2 text-muted">{{ __('Current SSP Balance') }}: <strong class="text-success">{{ currency_format($ssp->balance) }}</strong></p>
                                                        <p class="mb-3 text-muted">{{ __('Your Available Balance') }}: <strong class="text-primary">{{ currency_format(auth()->user()->balance) }}</strong></p>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">{{ __('Action Type') }}</label>
                                                            <select name="type" class="form-select" required>
                                                                <option value="add">{{ __('Transfer Balance to SSP (Deduct from my balance)') }}</option>
                                                                <option value="deduct">{{ __('Deduct Balance from SSP (Return to my balance)') }}</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">{{ __('Amount (BDT)') }}</label>
                                                            <input type="number" step="0.01" min="1" name="amount" class="form-control" placeholder="{{ __('Enter amount') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                                        <button type="submit" class="btn btn-success">{{ __('Confirm') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">{{ __('No Sub Resellers found under your account.') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $ssps->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
