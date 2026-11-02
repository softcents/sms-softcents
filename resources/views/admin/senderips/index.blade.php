@extends('layouts.admin.master')

@section('title')
    {{ __('IP Whitelist Management') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <!-- Information Banner -->
            <div class="card shadow-sm mb-4 border-0" style="border-left: 4px solid #10b981 !important; background: #ecfdf5;">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: #d1fae5; width: 44px; height: 44px;">
                                <i class="fas fa-network-wired text-success" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold text-dark">{{ __('API IP Whitelist Policy') }}</h6>
                                <p class="mb-0 text-muted small">
                                    {{ __('Manage whitelisted server IPs for customers & resellers. If a customer has configured active whitelisted IPs, API requests are restricted strictly to those IPs. If no IPs are configured, unrestricted access is allowed.') }}
                                </p>
                            </div>
                        </div>
                        <div>
                            @can('senderips-update')
                            <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm text-light" data-bs-toggle="modal" data-bs-target="#addIpModal">
                                <i class="fas fa-plus me-1"></i> {{ __('Whitelist IP For User') }}
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header d-flex justify-content-between align-items-center">
                        <h4>{{ __('All Whitelisted IPs') }} ({{ $senderips->total() }})</h4>
                    </div>

                    <div class="table-top-form">
                        <form action="{{ route('admin.senderips.index') }}" method="GET">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <div class="table-search">
                                        <input class="form-control" type="text" name="search" placeholder="{{ __('Search IP, customer, note...') }}" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="user_id" class="form-select select2">
                                        <option value="">{{ __('All Customers / Users') }}</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }} ({{ $u->phone ?? $u->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">{{ __('All Statuses') }}</option>
                                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm px-3 text-light">
                                        <i class="fas fa-filter me-1"></i> {{ __('Filter') }}
                                    </button>
                                    <a href="{{ route('admin.senderips.index') }}" class="btn btn-light btn-sm px-3 border">
                                        <i class="fas fa-redo me-1"></i> {{ __('Reset') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="responsive-table">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                @can('senderips-update')
                                <th class="w-60">
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="checkbox" class="selectAllCheckbox">
                                        <i class="fal fa-trash-alt delete-selected" data-url="{{ route('admin.senderips.delete-all') }}"></i>
                                    </div>
                                </th>
                                @endcan
                                <th style="width: 50px;">{{ __('SL') }}</th>
                                <th>{{ __('Customer / User') }}</th>
                                <th>{{ __('Whitelisted IP / Subnet') }}</th>
                                <th>{{ __('Note / Purpose') }}</th>
                                <th>{{ __('Added Date') }}</th>
                                <th class="text-center" style="width: 120px;">{{ __('Status') }}</th>
                                <th class="text-center" style="width: 100px;">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($senderips as $ip)
                                <tr>
                                    @can('senderips-update')
                                    <td class="checkbox text-start">
                                        <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $ip->id }}" data-url="{{ route('admin.senderips.delete-all') }}">
                                    </td>
                                    @endcan
                                    <td>{{ $loop->iteration + ($senderips->currentPage() - 1) * $senderips->perPage() }}</td>
                                    <td>
                                        @if($ip->user)
                                            <div class="fw-bold text-dark">{{ $ip->user->name }}</div>
                                            <small class="text-muted"><i class="fas fa-phone-alt me-1"></i>{{ $ip->user->phone ?? '—' }} | <i class="fas fa-envelope me-1"></i>{{ $ip->user->email }}</small>
                                        @else
                                            <span class="text-muted">{{ __('User Deleted') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 font-monospace" style="font-size: 13px;">
                                            <i class="fas fa-network-wired text-muted me-1"></i> {{ $ip->ip_address }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $ip->note ?: '—' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $ip->created_at ? $ip->created_at->format('d M, Y h:i A') : '—' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @can('senderips-update')
                                            <label class="switch">
                                                <input type="checkbox" {{ $ip->status == 1 ? 'checked' : '' }} class="status" data-url="{{ route('admin.senderips.status', $ip->id) }}">
                                                <span class="slider round"></span>
                                            </label>
                                        @else
                                            <span class="badge bg-{{ $ip->status == 1 ? 'success' : 'danger' }}">
                                                {{ $ip->status == 1 ? __('Active') : __('Inactive') }}
                                            </span>
                                        @endcan
                                    </td>
                                    <td class="text-center">
                                        @can('senderips-update')
                                        <form action="{{ route('admin.senderips.destroy', $ip->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this IP rule?') }}');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="{{ __('Delete') }}">
                                                <i class="fal fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-network-wired fa-2x mb-2 text-secondary d-block"></i>
                                        {{ __('No whitelisted IPs found matching the criteria.') }}
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
                <form action="{{ route('admin.senderips.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addIpModalLabel">
                            <i class="fas fa-shield-alt text-primary me-2"></i>{{ __('Whitelist IP For Customer') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Select Customer / User') }} <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select select2" required style="width: 100%;">
                                <option value="">{{ __('-- Select User --') }}</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone ?? $u->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('IP Address / Subnet (CIDR)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" class="form-control" placeholder="e.g. 103.181.28.15 or 103.181.28.0/24" required>
                            <div class="form-text">
                                {{ __('Supports IPv4 (e.g. 103.181.28.15), IPv6, or CIDR block (e.g. 103.181.28.0/24).') }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Note / Purpose (Optional)') }}</label>
                            <input type="text" name="note" class="form-control" placeholder="e.g. Client Webhook Server, App Backend">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary text-light">{{ __('Save Whitelist') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
