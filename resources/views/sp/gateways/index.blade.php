@extends('layouts.sp.master')

@section('title', __('My Payment Gateways'))

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 fw-bold"><i class="fas fa-credit-card text-primary me-2"></i>{{ __('My Reseller Payment Gateways') }}</h4>
                    <p class="text-muted small mb-0">{{ __('Configure your personal/merchant bKash, Nagad, Rocket, Bank accounts or automated gateways. When your customers recharge, they will pay directly to your gateways.') }}</p>
                </div>
                <a href="{{ route('sp.gateways.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> {{ __('Add Payment Gateway') }}
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('Logo') }}</th>
                                <th>{{ __('Gateway Name') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Fee / Charge') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gateways as $gateway)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($gateway->image)
                                            <img src="{{ asset($gateway->image) }}" alt="{{ $gateway->name }}" style="max-height: 35px; max-width: 80px; object-fit: contain;">
                                        @else
                                            <div class="badge bg-light text-dark p-2"><i class="fas fa-wallet fa-lg"></i></div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $gateway->name }}</div>
                                        <div class="text-muted small">{{ Str::limit($gateway->instructions, 50) }}</div>
                                    </td>
                                    <td>
                                        @if($gateway->is_manual)
                                            <span class="badge bg-info">{{ __('Manual / Mobile Banking') }}</span>
                                        @else
                                            <span class="badge bg-success">{{ __('Automated API') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $gateway->charge }}%</td>
                                    <td>
                                        @if($gateway->status == 1)
                                            <span class="badge bg-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('sp.gateways.edit', $gateway->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i> {{ __('Edit') }}
                                        </a>
                                        <form action="{{ route('sp.gateways.destroy', $gateway->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this payment gateway?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-wallet fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-1 fw-bold">{{ __('No payment gateways configured yet.') }}</p>
                                        <p class="small mb-3">{{ __('Add your bKash, Nagad, Rocket or Bank account so your customers can recharge balance online!') }}</p>
                                        <a href="{{ route('sp.gateways.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i> {{ __('Add Your First Gateway') }}
                                        </a>
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
@endsection
