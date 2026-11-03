@extends('layouts.admin.master')

@section('title')
    {{ __('IP Whitelist Management') }}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<style>
    /* Reset custom.css 100vh card issue for this page */
    .erp-senderips-section .card,
    .erp-senderips-section .cards,
    .erp-senderips-section .couter-box {
        min-height: auto !important;
        height: auto;
    }

    .erp-senderips-section {
        margin-top: 20px;
        margin-bottom: 40px;
        font-family: "Manrope", sans-serif;
    }

    /* Select2 ERP Custom Styling */
    .erp-senderips-section .select2-container {
        width: 100% !important;
    }

    .erp-senderips-section .select2-container .select2-selection--single {
        height: 31px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        padding-left: 8px !important;
        padding-right: 24px !important;
        box-shadow: none !important;
    }

    .erp-senderips-section .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 29px !important;
        color: #334155 !important;
        font-size: 13px !important;
        padding-left: 0 !important;
    }

    .erp-senderips-section .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 29px !important;
        right: 8px !important;
    }

    .erp-senderips-section .select2-container--default.select2-container--open .select2-selection--single,
    .erp-senderips-section .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15) !important;
    }

    /* Modal Select2 Styling */
    #addIpModal .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        background: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        padding-left: 10px !important;
        padding-right: 28px !important;
    }

    #addIpModal .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        color: #1e293b !important;
        font-size: 13.5px !important;
        padding-left: 0 !important;
    }

    #addIpModal .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        right: 10px !important;
    }

    .select2-dropdown {
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        z-index: 100000 !important;
        font-size: 13px !important;
        overflow: hidden !important;
    }

    .select2-search--dropdown {
        padding: 8px 10px !important;
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .select2-search--dropdown .select2-search__field {
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        padding: 6px 10px !important;
        font-size: 13px !important;
        outline: none !important;
    }

    .select2-results__option {
        padding: 8px 12px !important;
        font-size: 13px !important;
        border-bottom: 1px solid #f1f5f9;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        font-weight: 600;
    }

    /* Information Banner */
    .erp-senderips-section .info-banner-card {
        background: #ecfdf5;
        border-radius: 12px;
        padding: 16px 20px;
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-left: 4px solid #10b981 !important;
        margin-bottom: 20px;
        box-shadow: 0px 2px 14px rgba(195, 208, 228, 0.15);
    }

    .erp-senderips-section .info-icon-pill {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #d1fae5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #10b981;
        flex-shrink: 0;
    }

    /* Main Section Card */
    .erp-senderips-section .main-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0px 4px 20px rgba(195, 208, 228, 0.25);
        border: 1px solid rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .erp-senderips-section .card-header-custom {
        padding: 18px 20px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .erp-senderips-section .card-header-custom h4 {
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        font-size: 18px;
    }

    /* Filter Form Bar */
    .erp-senderips-section .filter-bar-wrap {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Responsive Table */
    .erp-senderips-section .responsive-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        margin-top: 0;
        min-height: auto;
    }

    .erp-senderips-section .table {
        margin-bottom: 0;
    }

    .erp-senderips-section .table th {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        padding: 12px 14px;
        vertical-align: middle;
    }

    .erp-senderips-section .table td {
        font-size: 13px;
        padding: 12px 14px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    @media (max-width: 767.98px) {
        .erp-senderips-section {
            margin-top: 10px;
            margin-bottom: 25px;
        }

        .erp-senderips-section .info-banner-card {
            padding: 14px;
        }

        .erp-senderips-section .card-header-custom {
            padding: 14px 16px;
        }

        .erp-senderips-section .filter-bar-wrap {
            padding: 14px 16px;
        }
    }
</style>
@endpush

@section('main_content')
    <div class="erp-table-section erp-senderips-section">
        <div class="container-fluid">

            <!-- 1. Information Policy Banner -->
            <div class="info-banner-card">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="info-icon-pill">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">{{ __('API IP Whitelist Policy') }}</h6>
                            <p class="mb-0 text-muted small">
                                {{ __('Manage whitelisted server IPs for customers & resellers. If active IPs are configured for a user, API requests are restricted strictly to those IPs. If no IPs are configured, unrestricted access is allowed.') }}
                            </p>
                        </div>
                    </div>
                    <div>
                        @can('senderips-update')
                        <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm text-light" style="border-radius: 8px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#addIpModal">
                            <i class="fas fa-plus me-1"></i> {{ __('Whitelist IP For User') }}
                        </button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- 2. Main Whitelist Table Section -->
            <div class="main-card">
                <div class="card-header-custom">
                    <div class="d-flex align-items-center gap-2">
                        <h4><i class="fas fa-shield-alt text-primary me-2"></i>{{ __('All Whitelisted IPs') }}</h4>
                        <span class="badge bg-light text-dark border">{{ $senderips->total() }}</span>
                    </div>
                    <span class="badge bg-light text-secondary border d-md-none">
                        <i class="fas fa-arrows-alt-h me-1"></i>{{ __('Scroll table') }}
                    </span>
                </div>

                <!-- Responsive Filter Form -->
                <div class="filter-bar-wrap">
                    <form action="{{ route('admin.senderips.index') }}" method="GET">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <input class="form-control form-control-sm" type="text" name="search" placeholder="{{ __('Search IP, customer name, username, note...') }}" value="{{ request('search') }}">
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                <select name="user_id" class="form-select form-select-sm select2-filter" style="width: 100%;">
                                    <option value="">{{ __('All Customers / Users') }}</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }} ({{ '@' . ($u->username ?: ('user' . $u->id)) }}) - {{ $u->phone ?? $u->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-2">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm px-3 text-light flex-grow-1" style="border-radius: 6px; font-weight: 600;">
                                    <i class="fas fa-filter me-1"></i> {{ __('Filter') }}
                                </button>
                                <a href="{{ route('admin.senderips.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 6px;">
                                    <i class="fas fa-redo me-1"></i> {{ __('Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Data Table -->
                <div class="responsive-table">
                    <table class="table table-hover table-striped text-nowrap align-middle" id="datatable" style="min-width: 950px;">
                        <thead>
                            <tr>
                                @can('senderips-update')
                                <th class="w-60" style="width: 40px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="checkbox" class="selectAllCheckbox">
                                        <i class="fal fa-trash-alt delete-selected" data-url="{{ route('admin.senderips.delete-all') }}" title="{{ __('Delete Selected') }}"></i>
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
                                            <div class="text-primary font-monospace small"><i class="fas fa-at text-muted me-0"></i><span>{{ $ip->user->username ?: ('user' . $ip->user->id) }}</span></div>
                                            <small class="text-muted"><i class="fas fa-phone-alt me-1"></i>{{ $ip->user->phone ?? '—' }} | <i class="fas fa-envelope me-1"></i>{{ $ip->user->email }}</small>
                                        @else
                                            <span class="text-muted">{{ __('User Deleted') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1 font-monospace" style="font-size: 13px;">
                                            <i class="fas fa-network-wired text-primary me-1"></i> {{ $ip->ip_address }}
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
                                            <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" style="border-radius: 6px;" title="{{ __('Delete') }}">
                                                <i class="fal fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="fas fa-network-wired fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                                        <p class="mb-0 fw-bold">{{ __('No whitelisted IPs found matching the criteria.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($senderips->hasPages())
                    <nav class="p-3 border-top">
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
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: 0; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <form action="{{ route('admin.senderips.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-light py-3 border-bottom">
                        <h5 class="modal-title fw-bold" id="addIpModalLabel">
                            <i class="fas fa-shield-alt text-primary me-2"></i>{{ __('Whitelist IP For Customer') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">{{ __('Select Customer / User') }} <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select select2-modal" required style="width: 100%;">
                                <option value="">{{ __('-- Select User --') }}</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ '@' . ($u->username ?: ('user' . $u->id)) }}) - {{ $u->phone ?? $u->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">{{ __('IP Address / Subnet (CIDR)') }} <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" class="form-control font-monospace" placeholder="e.g. 103.181.28.15 or 103.181.28.0/24" required>
                            <div class="form-text small text-muted">
                                {{ __('Supports IPv4 (e.g. 103.181.28.15), IPv6, or CIDR block (e.g. 103.181.28.0/24).') }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">{{ __('Note / Purpose (Optional)') }}</label>
                            <input type="text" name="note" class="form-control" placeholder="e.g. Client Webhook Server, App Backend">
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3 text-light fw-bold">{{ __('Save Whitelist') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2-filter').select2({
                    placeholder: "{{ __('All Customers / Users') }}",
                    allowClear: true,
                    width: '100%'
                });

                $('.select2-modal').select2({
                    placeholder: "{{ __('-- Select User --') }}",
                    dropdownParent: $('#addIpModal'),
                    width: '100%'
                });

                $('#addIpModal').on('shown.bs.modal', function () {
                    $('.select2-modal').select2('open');
                });
            }
        });
    </script>
    @endpush
@endsection
