@extends('layouts.sp.master')

@section('title', __('My Downline Customers'))

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
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center py-3 gap-2">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="fas fa-users text-primary me-2"></i>{{ __('Downline Customers List') }}</h4>
                    <p class="text-muted small mb-0">{{ __('Manage and login to direct customers as well as customers under your sub-resellers (SSP).') }}</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('sp.customers.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i> {{ __('Add New Direct Customer') }}
                    </a>
                </div>
            </div>
            <div class="card-body p-3">
                <!-- Filter Pills & Search Form -->
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div class="btn-group btn-group-sm" role="group">
                        @php $currentFilter = request('filter', 'all'); @endphp
                        <a href="{{ route('sp.customers.index', ['filter' => 'all', 'search' => request('search')]) }}" class="btn {{ $currentFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ __('All Customers') }}
                        </a>
                        <a href="{{ route('sp.customers.index', ['filter' => 'direct', 'search' => request('search')]) }}" class="btn {{ $currentFilter === 'direct' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ __('Direct Customers') }}
                        </a>
                        <a href="{{ route('sp.customers.index', ['filter' => 'ssp', 'search' => request('search')]) }}" class="btn {{ $currentFilter === 'ssp' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ __('Under Sub-Resellers (SSP)') }}
                        </a>
                    </div>

                    <form action="{{ route('sp.customers.index') }}" method="GET" class="d-flex gap-2">
                        <input type="hidden" name="filter" value="{{ $currentFilter }}">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="{{ __('Search customer...') }}" style="min-width: 220px;">
                        <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('Customer / Username') }}</th>
                                <th>{{ __('Account Hierarchy') }}</th>
                                <th>{{ __('Contact') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('Rates (M / NM)') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $customer->name }}</strong>
                                    <br><small class="text-muted"><i class="fas fa-at"></i> {{ $customer->username }}</small>
                                </td>
                                <td>
                                    @if($customer->parent_id === auth()->id())
                                        <span class="badge bg-primary text-white"><i class="fas fa-user-check me-1"></i> {{ __('Direct Customer') }}</span>
                                    @else
                                        <span class="badge bg-info text-dark" title="Parent SSP: {{ $customer->parent->name ?? 'SSP' }}">
                                            <i class="fas fa-sitemap me-1"></i> SSP: {{ $customer->parent->name ?? ('#' . $customer->parent_id) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div><i class="fas fa-envelope text-muted me-1"></i> {{ $customer->email }}</div>
                                    <small><i class="fas fa-phone text-muted me-1"></i> {{ $customer->phone ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold fs-6 text-success">{{ currency_format($customer->balance) }}</span>
                                </td>
                                <td>
                                    {{ currency_format($customer->masking_rate) }} / {{ currency_format($customer->non_masking_rate) }}
                                </td>
                                <td>
                                    <span class="badge {{ $customer->status == 'active' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($customer->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('sp.customers.login-as', $customer->id) }}" class="btn btn-sm btn-primary shadow-sm" title="{{ __('Login As Customer') }}">
                                            <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As') }}
                                        </a>

                                        @if($customer->parent_id === auth()->id())
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#rechargeModal{{ $customer->id }}" title="{{ __('Transfer / Deduct Balance') }}">
                                            <i class="fas fa-coins me-1"></i> {{ __('Balance') }}
                                        </button>

                                        <a href="{{ route('sp.customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Edit') }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        @endif
                                    </div>

                                    @if($customer->parent_id === auth()->id())
                                    <!-- Modal Recharge -->
                                    <div class="modal fade" id="rechargeModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <form action="{{ route('sp.customers.recharge', $customer->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ __('Manage Balance for ') }}: {{ $customer->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2 text-muted">{{ __('Current Customer Balance') }}: <strong class="text-success">{{ currency_format($customer->balance) }}</strong></p>
                                                        <p class="mb-3 text-muted">{{ __('Your Available Balance') }}: <strong class="text-primary">{{ currency_format(auth()->user()->balance) }}</strong></p>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">{{ __('Action Type') }}</label>
                                                            <select name="type" class="form-select" required>
                                                                <option value="add">{{ __('Transfer Balance to Customer (Deduct from my balance)') }}</option>
                                                                <option value="deduct">{{ __('Deduct Balance from Customer (Return to my balance)') }}</option>
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
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">{{ __('No customers found for this filter.') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $customers->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
