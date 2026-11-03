@extends('layouts.users.master')

@section('title')
    {{ __('IP Whitelist') }}
@endsection

@push('css')
<style>
    .erp-table-section .card, .erp-table-section .cards { min-height: auto !important; height: auto; }
</style>
@endpush

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <!-- Information Banner -->
            <div class="card shadow-sm mb-4 border-0" style="border-left: 4px solid #3b82f6 !important; background: #f0f7ff;">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: #dbeafe; width: 44px; height: 44px;">
                                <i class="fas fa-shield-alt text-primary" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold text-dark">{{ __('API IP Whitelist Protection') }}</h6>
                                <p class="mb-0 text-muted small">
                                    {{ __('Your detected client IP is:') }} <strong class="text-primary font-monospace">{{ $currentIp }}</strong>.
                                    @if($senderips->total() == 0)
                                        <span class="badge bg-warning text-dark ms-2">{{ __('Unrestricted Mode') }}</span>
                                        <span class="d-block text-muted mt-1 small">{{ __('No whitelist rules configured. API requests are permitted from any IP address.') }}</span>
                                    @else
                                        <span class="badge bg-success ms-2">{{ __('Enforced Mode') }}</span>
                                        <span class="d-block text-muted mt-1 small">{{ __('API access is restricted strictly to active whitelisted IPs or CIDR subnets.') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('users.senderips.store') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="ip_address" value="{{ $currentIp }}">
                                <input type="hidden" name="note" value="My Current IP (Quick Added)">
                                <button type="submit" class="btn btn-outline-primary btn-sm px-3 shadow-sm">
                                    <i class="fas fa-plus-circle me-1"></i> {{ __('Whitelist My Current IP') }}
                                </button>
                            </form>
                            <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm text-light" data-bs-toggle="modal" data-bs-target="#addIpModal">
                                <i class="fas fa-plus me-1"></i> {{ __('Add IP / CIDR') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h4>{{ __('Whitelisted IP Addresses') }} ({{ $senderips->total() }})</h4>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('users.senderips.index') }}" method="GET">
                            <div class="table-search">
                                <input class="form-control searchInput" type="text" name="search" placeholder="{{ __('Search IP or note...') }}" value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="responsive-table" style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; min-height: auto; margin-top: 0;">
                    <table class="table table-hover table-striped text-nowrap align-middle" id="datatable" style="min-width: 750px;">
                        <thead>
                            <tr>
                                <th style="width: 50px;">{{ __('SL') }}</th>
                                <th>{{ __('IP Address / Subnet') }}</th>
                                <th>{{ __('Note / Purpose') }}</th>
                                <th>{{ __('Added Date') }}</th>
                                <th class="text-center" style="width: 120px;">{{ __('Status') }}</th>
                                <th class="text-center" style="width: 100px;">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($senderips as $ip)
                                <tr>
                                    <td>{{ $loop->iteration + ($senderips->currentPage() - 1) * $senderips->perPage() }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 font-monospace" style="font-size: 13px;">
                                            <i class="fas fa-network-wired text-muted me-1"></i> {{ $ip->ip_address }}
                                        </span>
                                        @if($ip->ip_address === $currentIp)
                                            <span class="badge bg-info text-white ms-1" style="font-size: 10px;">{{ __('Current IP') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $ip->note ?: '—' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $ip->created_at ? $ip->created_at->format('d M, Y h:i A') : '—' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <label class="switch">
                                            <input type="checkbox" {{ $ip->status == 1 ? 'checked' : '' }} class="status" data-url="{{ route('users.senderips.status', $ip->id) }}">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('users.senderips.destroy', $ip->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to remove this IP from your whitelist?') }}');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="{{ __('Delete') }}">
                                                <i class="fal fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-shield-slash fa-2x mb-2 text-secondary d-block"></i>
                                        {{ __('No IP addresses whitelisted yet. API is currently open to any server.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($senderips->hasPages())
                    <nav class="p-3">
                        <ul class="pagination mb-0">
                            <li class="page-item">{{ $senderips->links('pagination::bootstrap-5') }}</li>
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>

    <!-- Add IP Modal -->
    <div class="modal fade" id="addIpModal" tabindex="-1" aria-labelledby="addIpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('users.senderips.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addIpModalLabel">
                            <i class="fas fa-shield-alt text-primary me-2"></i>{{ __('Whitelist New Server IP') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('IP Address / Subnet (CIDR)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" class="form-control" placeholder="e.g. 103.181.28.15 or 103.181.28.0/24" required>
                            <div class="form-text">
                                {{ __('Supports IPv4 (e.g. 103.181.28.15), IPv6, or CIDR block (e.g. 103.181.28.0/24).') }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Note / Purpose (Optional)') }}</label>
                            <input type="text" name="note" class="form-control" placeholder="e.g. Production Backend, ERP Server">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary text-light">{{ __('Add to Whitelist') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
