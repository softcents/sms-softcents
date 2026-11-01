@extends('layouts.sp.master')

@section('title', __('Customer Recharge Requests'))

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

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h4 class="mb-0 fw-bold"><i class="fas fa-money-bill-wave text-primary me-2"></i>{{ __('Downline Recharge Requests') }}</h4>
                    <p class="text-muted small mb-0">{{ __('Approve or reject balance recharge requests submitted by your customers and sub-resellers.') }}</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary fs-6">{{ __('My Balance: :bal BDT', ['bal' => number_format(auth()->user()->balance, 2)]) }}</span>
                    <a href="{{ route('sp.my-recharge') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-plus-circle me-1"></i> {{ __('Recharge My Account') }}
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="card-body border-bottom bg-light py-2">
                <form action="{{ route('sp.recharges.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="{{ __('Search by invoice, customer username, name, or phone...') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fas fa-filter me-1"></i> {{ __('Filter') }}</button>
                    </div>
                    @if(request()->hasAny(['search', 'status']))
                        <div class="col-md-2">
                            <a href="{{ route('sp.recharges.index') }}" class="btn btn-sm btn-outline-secondary w-100">{{ __('Reset') }}</a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Invoice') }}</th>
                                <th>{{ __('Customer / User') }}</th>
                                <th>{{ __('Payment Method') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Transaction Details / Proof') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recharges as $recharge)
                                <tr>
                                    <td><span class="fw-bold">{{ $recharge->invoice_no }}</span></td>
                                    <td>
                                        <div class="fw-bold">{{ $recharge->user->name ?? 'N/A' }}</div>
                                        <div class="text-muted small">@<span>{{ $recharge->user->username ?? 'N/A' }}</span> | {{ $recharge->user->phone ?? '' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $recharge->gateway->name ?? __('Direct Transfer') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success fs-6">{{ number_format($recharge->amount, 2) }} BDT</span>
                                    </td>
                                    <td>
                                        @if(!empty($recharge->manual_data))
                                            <div class="small text-muted mb-1">
                                                @if(is_array($recharge->manual_data))
                                                    @foreach($recharge->manual_data as $k => $v)
                                                        <strong>{{ ucfirst($k) }}:</strong> {{ $v }}<br>
                                                    @endforeach
                                                @else
                                                    {{ $recharge->manual_data }}
                                                @endif
                                            </div>
                                        @endif
                                        @if($recharge->attachment)
                                            <a href="{{ asset($recharge->attachment) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                                <i class="fas fa-image me-1"></i> {{ __('View Receipt') }}
                                            </a>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $recharge->created_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td>
                                        @if($recharge->status == 'approved')
                                            <span class="badge bg-success">{{ __('Approved') }}</span>
                                        @elseif($recharge->status == 'pending')
                                            <span class="badge bg-warning text-dark">{{ __('Pending') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($recharge->status == 'pending')
                                            <form action="{{ route('sp.recharges.approve', $recharge->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Approve this recharge? :amount BDT will be deducted from your balance and added to the customer.', ['amount' => $recharge->amount]) }}');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success me-1">
                                                    <i class="fas fa-check me-1"></i> {{ __('Approve') }}
                                                </button>
                                            </form>
                                            <form action="{{ route('sp.recharges.reject', $recharge->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to reject this recharge request?') }}');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times me-1"></i> {{ __('Reject') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">{{ __('Processed') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0 fw-bold">{{ __('No recharge requests found.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($recharges->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $recharges->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
