@extends('layouts.admin.master')

@section('title')
    {{ __('Senderids List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header">
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0">{{ __('Senderids List') }}</h4>
                            <span class="badge bg-primary text-white" id="total-count-pill" style="font-size: 13px;">
                                {{ $totalSenderidsCount }}
                            </span>
                        </div>
                        <a href="{{ route('admin.senderids.create') }}" class="theme-btn print-btn text-light">
                            <i class="far fa-plus" aria-hidden="true"></i>
                            {{ __('Create New') }}
                        </a>
                    </div>

                    <!-- Gateway Distribution & Fast-Filter Pills -->
                    <div class="gateway-stats-wrapper my-3 p-3 rounded" style="background-color: #f8f9fc; border: 1px solid #e3e6f0;">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2 pb-2 border-bottom">
                            <div class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 14px;">
                                <i class="fas fa-network-wired text-primary"></i>
                                <span>{{ __('Gateway-wise Sender ID Breakdown') }}</span>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="badge bg-white text-dark border px-2 py-1 shadow-sm" style="font-size: 12px;">
                                    <i class="fas fa-layer-group text-primary me-1"></i>{{ __('Total:') }} <strong>{{ $totalSenderidsCount }}</strong>
                                </span>
                                <span class="badge bg-warning bg-opacity-25 text-dark border border-warning px-2 py-1 shadow-sm" style="font-size: 12px;">
                                    <i class="fas fa-inbox text-warning me-1"></i>{{ __('Unused:') }} <strong class="text-danger">{{ $totalUnusedCount }}</strong>
                                </span>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 shadow-sm" style="font-size: 12px;">
                                    <i class="fas fa-user-check text-success me-1"></i>{{ __('In Use:') }} <strong>{{ $totalUsedCount }}</strong>
                                </span>
                            </div>
                        </div>

                        <!-- Usage Quick-Tabs: All vs Unused Only vs In-Use Only -->
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2 pt-1">
                            <span class="text-muted small fw-semibold me-1"><i class="fas fa-filter me-1 text-secondary"></i>{{ __('Show:') }}</span>
                            <button type="button" class="btn btn-xs usage-tab-btn {{ !request('usage') ? 'btn-dark text-white shadow-sm' : 'btn-outline-secondary bg-white' }}" data-usage="" style="border-radius: 14px; font-size: 11.5px; padding: 4px 12px; font-weight: 500;">
                                <i class="fas fa-list me-1"></i> {{ __('All') }} ({{ $totalSenderidsCount }})
                            </button>
                            <button type="button" class="btn btn-xs usage-tab-btn {{ request('usage') === 'unused' ? 'btn-danger text-white shadow-sm' : 'btn-outline-danger bg-white' }}" data-usage="unused" style="border-radius: 14px; font-size: 11.5px; padding: 4px 12px; font-weight: 600;">
                                <i class="fas fa-inbox me-1"></i> {{ __('Unused Only') }} ({{ $totalUnusedCount }})
                            </button>
                            <button type="button" class="btn btn-xs usage-tab-btn {{ request('usage') === 'used' ? 'btn-success text-white shadow-sm' : 'btn-outline-success bg-white' }}" data-usage="used" style="border-radius: 14px; font-size: 11.5px; padding: 4px 12px; font-weight: 500;">
                                <i class="fas fa-user-check me-1"></i> {{ __('In Use Only') }} ({{ $totalUsedCount }})
                            </button>
                            <span class="text-muted small ms-auto d-none d-md-inline">
                                <i class="far fa-hand-pointer me-1"></i>{{ __('Click any gateway to filter list instantly') }}
                            </span>
                        </div>

                        <!-- Gateway-wise Pills with Total & Unused Counts -->
                        <div class="d-flex flex-wrap gap-2 align-items-center gateway-filter-pills pt-1">
                            <button type="button" class="btn btn-sm gateway-pill-btn {{ !request('gateway_id') ? 'btn-primary text-white shadow-sm' : 'btn-outline-secondary bg-white' }}" data-gateway-id="" style="border-radius: 20px; font-weight: 500; font-size: 12.5px; padding: 5px 14px;">
                                <i class="fas fa-layer-group me-1"></i> {{ __('All Gateways') }}
                                <span class="badge {{ !request('gateway_id') ? 'bg-white text-primary' : 'bg-secondary text-white' }} ms-1" style="font-size: 11px;">{{ $totalSenderidsCount }}</span>
                                <span class="badge bg-warning text-dark border ms-1" style="font-size: 10px;" title="{{ __('Total Unused across all gateways') }}">{{ $totalUnusedCount }} Unused</span>
                            </button>
                            @foreach($gateways as $gw)
                                <button type="button" class="btn btn-sm gateway-pill-btn {{ request('gateway_id') == $gw->id ? 'btn-primary text-white shadow-sm' : 'btn-outline-secondary bg-white' }}" data-gateway-id="{{ $gw->id }}" style="border-radius: 20px; font-weight: 500; font-size: 12.5px; padding: 5px 14px;">
                                    <i class="fas fa-server me-1 text-primary"></i> {{ $gw->name }}
                                    <span class="badge {{ request('gateway_id') == $gw->id ? 'bg-white text-primary' : 'bg-primary text-white' }} ms-1" style="font-size: 11px;">{{ $gw->senderids_count }}</span>
                                    @if($gw->unused_senderids_count > 0)
                                        <span class="badge bg-warning text-dark border ms-1" title="{{ __(':count Unused Sender IDs in :gw', ['count' => $gw->unused_senderids_count, 'gw' => $gw->name]) }}" style="font-size: 10px; font-weight: 600;">
                                            <i class="fas fa-inbox me-1"></i>{{ $gw->unused_senderids_count }} Unused
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                            @if($unassignedCount > 0)
                                <button type="button" class="btn btn-sm gateway-pill-btn {{ request('gateway_id') === '0' || request('gateway_id') === 'unassigned' ? 'btn-warning text-dark shadow-sm' : 'btn-outline-warning text-dark bg-white' }}" data-gateway-id="0" style="border-radius: 20px; font-weight: 500; font-size: 12.5px; padding: 5px 14px;">
                                    <i class="fas fa-exclamation-triangle me-1 text-warning"></i> {{ __('Unassigned Gateway') }}
                                    <span class="badge bg-warning text-dark border ms-1" style="font-size: 11px;">{{ $unassignedCount }}</span>
                                    @if($unassignedUnusedCount > 0)
                                        <span class="badge bg-danger text-white border ms-1" style="font-size: 10px;">{{ $unassignedUnusedCount }} Unused</span>
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Advanced Multi-Filter Toolbar -->
                    <div class="table-top-form">
                        <form action="{{ route('admin.senderids.index') }}" method="get" id="senderid-filter-form">
                            <input type="hidden" name="usage" id="filter-usage-input" value="{{ request('usage') }}">
                            <div class="row g-2 align-items-center">
                                <!-- Search Input -->
                                <div class="col-xl-3 col-lg-3 col-md-6">
                                    <div class="table-search w-100">
                                        <input class="form-control searchInput" type="text" name="search" placeholder="{{ __('Search Sender ID, Customer...') }}" value="{{ request('search') }}" autocomplete="off">
                                    </div>
                                </div>
                                
                                <!-- Gateway Filter Dropdown -->
                                <div class="col-xl-3 col-lg-3 col-md-6">
                                    <select name="gateway_id" id="filter-gateway-select" class="form-select filter-dropdown" style="font-size: 13px; height: 44px; border-radius: 6px; border: 1px solid #ced4da;">
                                        <option value="">{{ __('All Gateways') }} ({{ $totalSenderidsCount }})</option>
                                        @foreach($gateways as $gw)
                                            <option value="{{ $gw->id }}" {{ request('gateway_id') == $gw->id ? 'selected' : '' }}>
                                                {{ $gw->name }} ({{ $gw->senderids_count }} total • {{ $gw->unused_senderids_count }} unused)
                                            </option>
                                        @endforeach
                                        @if($unassignedCount > 0)
                                            <option value="0" {{ request('gateway_id') === '0' || request('gateway_id') === 'unassigned' ? 'selected' : '' }}>
                                                ⚠️ {{ __('Unassigned Gateway') }} ({{ $unassignedCount }} total • {{ $unassignedUnusedCount }} unused)
                                            </option>
                                        @endif
                                    </select>
                                </div>

                                <!-- Usage / Assignment Dropdown -->
                                <div class="col-xl-2 col-lg-2 col-md-4">
                                    <select id="filter-usage-select" class="form-select filter-dropdown" style="font-size: 13px; height: 44px; border-radius: 6px; border: 1px solid #ced4da;">
                                        <option value="">{{ __('All Assignments') }} ({{ $totalSenderidsCount }})</option>
                                        <option value="unused" {{ request('usage') === 'unused' ? 'selected' : '' }}>
                                            📥 {{ __('Unused Only') }} ({{ $totalUnusedCount }})
                                        </option>
                                        <option value="used" {{ request('usage') === 'used' ? 'selected' : '' }}>
                                            👤 {{ __('In Use Only') }} ({{ $totalUsedCount }})
                                        </option>
                                    </select>
                                </div>

                                <!-- Type Filter Dropdown -->
                                <div class="col-xl-2 col-lg-2 col-md-4">
                                    <select name="type" class="form-select filter-dropdown" style="font-size: 13px; height: 44px; border-radius: 6px; border: 1px solid #ced4da;">
                                        <option value="">{{ __('All Types') }}</option>
                                        <option value="Masking" {{ request('type') == 'Masking' ? 'selected' : '' }}>{{ __('Masking') }} ({{ $maskingCount }})</option>
                                        <option value="Non-Masking" {{ request('type') == 'Non-Masking' ? 'selected' : '' }}>{{ __('Non-Masking') }} ({{ $nonMaskingCount }})</option>
                                    </select>
                                </div>

                                <!-- Status Filter Dropdown -->
                                <div class="col-xl-1 col-lg-1 col-md-4">
                                    <select name="status" class="form-select filter-dropdown" style="font-size: 13px; height: 44px; border-radius: 6px; border: 1px solid #ced4da;">
                                        <option value="">{{ __('Status') }}</option>
                                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>{{ __('Active') }} ({{ $activeCount }})</option>
                                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>{{ __('Deactive') }} ({{ $deactiveCount }})</option>
                                    </select>
                                </div>

                                <!-- Per Page & Reset Button -->
                                <div class="col-xl-1 col-lg-1 col-md-4 d-flex align-items-center gap-2">
                                    <select name="per_page" class="form-select filter-dropdown" style="font-size: 13px; height: 44px; border-radius: 6px; width: 68px; border: 1px solid #ced4da; padding-left: 6px; padding-right: 18px;" title="{{ __('Rows per page') }}">
                                        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                    <a href="{{ route('admin.senderids.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" title="{{ __('Reset Filters') }}" style="height: 44px; width: 44px; border-radius: 6px; flex-shrink: 0;">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </div>
                            </div>

                            <!-- Selected Bulk Action Toolbar (Appears when checkboxes are selected) -->
                            <div id="bulk-gateway-action-bar" class="mt-3 p-2 px-3 rounded d-none align-items-center justify-content-between" style="background-color: #e8f0fe; border: 1px solid #d2e3fc;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary text-white px-2 py-1" id="selected-count-badge" style="font-size: 13px;">0</span>
                                    <span class="fw-semibold text-dark" style="font-size: 13.5px;">{{ __('Sender IDs selected') }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-primary d-flex align-items-center gap-1 shadow-sm px-3 py-2" id="btn-open-bulk-assign-modal" style="border-radius: 6px; font-weight: 500;">
                                        <i class="fas fa-exchange-alt"></i> {{ __('Assign Gateway to Selected') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="responsive-table">
                    <table class="table align-middle" id="datatable">
                        <thead>
                            <tr>
                                @can('senderids-delete')
                                <th class="w-60">
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="checkbox" class="selectAllCheckbox">
                                        <i class="fal fa-trash-alt delete-selected" title="{{ __('Delete Selected') }}"></i>
                                    </div>
                                </th>
                                @endcan
                                <th>{{ __('SL') }}.</th>
                                <th>{{ __('Customer Name') }}</th>
                                <th>{{ __('Customer Phone') }}</th>
                                <th>{{ __('Sender Id') }}</th>
                                <th>{{ __('Assigned Gateway') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.senderids.datas')
                        </tbody>
                    </table>
                </div>

                <nav class="p-3 pagination-nav-wrapper">
                    <ul class="pagination mb-0 pagination-container">
                        <li class="page-item">{{ $senderids->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')

    <!-- Single Quick Change Gateway Modal -->
    <div class="modal fade" id="single-change-gateway-modal" tabindex="-1" aria-labelledby="singleChangeGatewayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header border-bottom py-3" style="background-color: #f8f9fc;">
                    <h5 class="modal-title fw-bold" id="singleChangeGatewayModalLabel" style="font-size: 16px;">
                        <i class="fas fa-exchange-alt text-primary me-2"></i>{{ __('Change Sender ID Gateway') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="single-change-gateway-form" action="{{ route('admin.senderids.assign-gateway') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ids[]" id="single-modal-sender-id" value="">
                    <div class="modal-body p-4">
                        <div class="mb-3 p-3 rounded" style="background-color: #f1f3f9;">
                            <div class="mb-2">
                                <span class="text-muted small d-block">{{ __('Sender ID') }}:</span>
                                <span class="fw-bold fs-6 text-dark" id="single-modal-sender-name">-</span>
                            </div>
                            <div>
                                <span class="text-muted small d-block">{{ __('Current Gateway') }}:</span>
                                <span class="badge bg-secondary" id="single-modal-current-gateway">-</span>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label fw-semibold" style="font-size: 13.5px;">
                                {{ __('Target SMS Gateway') }} <span class="text-danger">*</span>
                            </label>
                            <select name="gateway_id" id="single-modal-gateway-select" class="form-select" required style="border-radius: 6px; height: 42px;">
                                <option value="">{{ __('Select a Gateway') }}</option>
                                @foreach($gatewayList as $gw)
                                    <option value="{{ $gw->id }}">{{ $gw->name }}</option>
                                @endforeach
                                <option value="0">⚠️ {{ __('Unassigned / Remove Gateway') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2 px-4" style="background-color: #f8f9fc;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary px-3" id="btn-submit-single-assign">
                            <i class="fas fa-check me-1"></i> {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Assign Gateway Modal -->
    <div class="modal fade" id="bulk-assign-gateway-modal" tabindex="-1" aria-labelledby="bulkAssignGatewayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header border-bottom py-3" style="background-color: #f8f9fc;">
                    <h5 class="modal-title fw-bold" id="bulkAssignGatewayModalLabel" style="font-size: 16px;">
                        <i class="fas fa-network-wired text-primary me-2"></i>{{ __('Bulk Assign Gateway') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulk-assign-gateway-form" action="{{ route('admin.senderids.assign-gateway') }}" method="POST">
                    @csrf
                    <div id="bulk-hidden-ids-container"></div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info py-2 px-3 d-flex align-items-center gap-2 mb-3" style="font-size: 13.5px;">
                            <i class="fas fa-info-circle fa-lg"></i>
                            <div>
                                {{ __('You are assigning') }} <strong id="modal-bulk-count" class="text-primary">0</strong> {{ __('selected Sender IDs.') }}
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label fw-semibold" style="font-size: 13.5px;">
                                {{ __('Select Destination SMS Gateway') }} <span class="text-danger">*</span>
                            </label>
                            <select name="gateway_id" class="form-select" required style="border-radius: 6px; height: 42px;">
                                <option value="">{{ __('Choose SMS Gateway') }}</option>
                                @foreach($gatewayList as $gw)
                                    <option value="{{ $gw->id }}">{{ $gw->name }}</option>
                                @endforeach
                                <option value="0">⚠️ {{ __('Unassigned / Remove Gateway') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2 px-4" style="background-color: #f8f9fc;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary px-3" id="btn-submit-bulk-assign">
                            <i class="fas fa-check me-1"></i> {{ __('Apply to Selected') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Assigned Users Modal -->
    <div class="modal fade" id="assigned-users-modal" tabindex="-1" aria-labelledby="assignedUsersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header border-bottom py-3" style="background-color: #f8f9fc;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-users text-primary fa-lg"></i>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="assignedUsersModalLabel" style="font-size: 16px;">
                                {{ __('Assigned Customers for') }}: <span class="text-primary font-monospace" id="modal-assigned-sender-title">-</span>
                            </h5>
                            <small class="text-muted">{{ __('Total assigned customers') }}: <strong id="modal-assigned-total-badge" class="text-primary">0</strong></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="assigned-users-loading" class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                        <p class="text-muted mb-0">{{ __('Loading assigned customers...') }}</p>
                    </div>
                    <div id="assigned-users-table-container" class="responsive-table d-none" style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light sticky-top" style="font-size: 12.5px;">
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Phone / Email') }}</th>
                                    <th>{{ __('Assigned Gateway') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody id="assigned-users-tbody" style="font-size: 13px;"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-top py-2 px-4 justify-content-between" style="background-color: #f8f9fc;">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-filter-main-table-by-sender">
                        <i class="fas fa-filter me-1"></i> {{ __('Filter in Main Table') }}
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
$(document).ready(function () {
    const $filterForm = $('#senderid-filter-form');
    let filterAjaxTimeout = null;

    // Trigger filter reload
    function reloadSenderIds() {
        clearTimeout(filterAjaxTimeout);
        filterAjaxTimeout = setTimeout(function () {
            const formData = $filterForm.serialize();
            const targetUrl = $filterForm.attr('action');

            // Update browser URL query string gracefully
            if (window.history.replaceState) {
                const newUrl = targetUrl + '?' + formData;
                window.history.replaceState({ path: newUrl }, '', newUrl);
            }

            $('.searchResults').css('opacity', '0.5');

            $.ajax({
                url: targetUrl,
                type: 'GET',
                data: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function (res) {
                    $('.searchResults').css('opacity', '1');
                    if (res && res.data !== undefined) {
                        $('.searchResults').html(res.data);
                    }
                    if (res && res.pagination !== undefined) {
                        $('.pagination-container').html(res.pagination);
                    }
                    updateBulkActionBar();
                },
                error: function () {
                    $('.searchResults').css('opacity', '1');
                    toastr.error('{{ __("Failed to filter sender IDs.") }}');
                }
            });
        }, 200);
    }

    // Usage Quick-Tabs click (All vs Unused Only vs In Use Only)
    $(document).on('click', '.usage-tab-btn', function (e) {
        e.preventDefault();
        const usageVal = $(this).data('usage') || '';
        $('#filter-usage-input').val(usageVal);
        $('#filter-usage-select').val(usageVal);

        $('.usage-tab-btn').removeClass('btn-dark btn-danger btn-success text-white shadow-sm').addClass('btn-outline-secondary bg-white');
        if (usageVal === 'unused') {
            $(this).removeClass('btn-outline-secondary bg-white btn-outline-danger').addClass('btn-danger text-white shadow-sm');
        } else if (usageVal === 'used') {
            $(this).removeClass('btn-outline-secondary bg-white btn-outline-success').addClass('btn-success text-white shadow-sm');
        } else {
            $(this).removeClass('btn-outline-secondary bg-white').addClass('btn-dark text-white shadow-sm');
        }

        reloadSenderIds();
    });

    // When usage select changes in filter bar
    $(document).on('change', '#filter-usage-select', function () {
        const usageVal = $(this).val() || '';
        $('#filter-usage-input').val(usageVal);

        $('.usage-tab-btn').removeClass('btn-dark btn-danger btn-success text-white shadow-sm').addClass('btn-outline-secondary bg-white');
        const $matchingTab = $('.usage-tab-btn[data-usage="' + usageVal + '"]');
        if (usageVal === 'unused') {
            $matchingTab.removeClass('btn-outline-secondary bg-white btn-outline-danger').addClass('btn-danger text-white shadow-sm');
        } else if (usageVal === 'used') {
            $matchingTab.removeClass('btn-outline-secondary bg-white btn-outline-success').addClass('btn-success text-white shadow-sm');
        } else {
            $matchingTab.removeClass('btn-outline-secondary bg-white').addClass('btn-dark text-white shadow-sm');
        }

        reloadSenderIds();
    });

    // Fast filter pills click
    $(document).on('click', '.gateway-pill-btn', function (e) {
        e.preventDefault();
        const gatewayId = $(this).data('gateway-id');
        
        // Update pills active visual state
        $('.gateway-pill-btn').removeClass('btn-primary active text-white shadow-sm').addClass('btn-outline-secondary bg-white');
        $('.gateway-pill-btn .badge').removeClass('bg-white text-primary').addClass('bg-primary text-white');
        
        $(this).removeClass('btn-outline-secondary bg-white').addClass('btn-primary active text-white shadow-sm');
        $(this).find('.badge').removeClass('bg-primary text-white bg-secondary').addClass('bg-white text-primary');

        // Sync with dropdown and reload
        $('#filter-gateway-select').val(gatewayId !== undefined && gatewayId !== null ? String(gatewayId) : '');
        reloadSenderIds();
    });

    // Dropdowns change event
    $(document).on('change', '.filter-dropdown', function () {
        const val = $('#filter-gateway-select').val();
        
        // Update pill active state to reflect dropdown
        $('.gateway-pill-btn').removeClass('btn-primary active text-white shadow-sm').addClass('btn-outline-secondary bg-white');
        const $matchingPill = $('.gateway-pill-btn[data-gateway-id="' + (val || '') + '"]');
        if ($matchingPill.length) {
            $matchingPill.removeClass('btn-outline-secondary bg-white').addClass('btn-primary active text-white shadow-sm');
        }

        reloadSenderIds();
    });

    // Single Change Gateway Modal
    $(document).on('click', '.btn-quick-change-gateway', function () {
        const id = $(this).data('id');
        const sender = $(this).data('sender');
        const gatewayId = $(this).data('gateway-id');
        const gatewayName = $(this).data('gateway-name');

        $('#single-modal-sender-id').val(id);
        $('#single-modal-sender-name').text(sender);
        $('#single-modal-current-gateway').text(gatewayName);
        $('#single-modal-gateway-select').val(gatewayId !== undefined && gatewayId !== null ? String(gatewayId) : '0');

        $('#single-change-gateway-modal').modal('show');
    });

    $('#single-change-gateway-form').on('submit', function (e) {
        e.preventDefault();
        const $btn = $('#btn-submit-single-assign');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                $btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> {{ __("Save Changes") }}');
                $('#single-change-gateway-modal').modal('hide');
                toastr.success(res.message || '{{ __("Gateway updated successfully.") }}');
                reloadSenderIds();
            },
            error: function (xhr) {
                $btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> {{ __("Save Changes") }}');
                const msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : '{{ __("Failed to update gateway.") }}';
                toastr.error(msg);
            }
        });
    });

    // Bulk selection tracking
    function updateBulkActionBar() {
        const checkedCount = $('.checkbox-item:checked').length;
        if (checkedCount > 0) {
            $('#selected-count-badge').text(checkedCount);
            $('#bulk-gateway-action-bar').removeClass('d-none').addClass('d-flex');
        } else {
            $('#bulk-gateway-action-bar').removeClass('d-flex').addClass('d-none');
        }
    }

    $(document).on('change', '.selectAllCheckbox, .checkbox-item', function () {
        setTimeout(updateBulkActionBar, 50);
    });

    // Open Bulk Assign Modal
    $(document).on('click', '#btn-open-bulk-assign-modal', function () {
        const $checked = $('.checkbox-item:checked');
        if ($checked.length === 0) {
            toastr.warning('{{ __("Please select at least one Sender ID.") }}');
            return;
        }

        $('#modal-bulk-count').text($checked.length);
        const $hiddenContainer = $('#bulk-hidden-ids-container');
        $hiddenContainer.empty();

        $checked.each(function () {
            $hiddenContainer.append('<input type="hidden" name="ids[]" value="' + $(this).val() + '">');
        });

        $('#bulk-assign-gateway-modal').modal('show');
    });

    $('#bulk-assign-gateway-form').on('submit', function (e) {
        e.preventDefault();
        const $btn = $('#btn-submit-bulk-assign');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                $btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> {{ __("Apply to Selected") }}');
                $('#bulk-assign-gateway-modal').modal('hide');
                toastr.success(res.message || '{{ __("Sender IDs assigned successfully.") }}');
                reloadSenderIds();
            },
            error: function (xhr) {
                $btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> {{ __("Apply to Selected") }}');
                const msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : '{{ __("Failed to assign gateway.") }}';
                toastr.error(msg);
            }
        });
    });

    // View Assigned Users Modal
    let currentModalSender = '';
    $(document).on('click', '.btn-view-assigned-users', function (e) {
        e.preventDefault();
        const sender = $(this).data('sender');
        currentModalSender = sender;

        $('#modal-assigned-sender-title').text(sender);
        $('#modal-assigned-total-badge').text('...');
        $('#assigned-users-loading').removeClass('d-none');
        $('#assigned-users-table-container').addClass('d-none');
        $('#assigned-users-tbody').empty();

        $('#assigned-users-modal').modal('show');

        $.ajax({
            url: '{{ route("admin.senderids.assigned-users") }}',
            type: 'GET',
            data: { sender: sender },
            success: function (res) {
                $('#assigned-users-loading').addClass('d-none');
                $('#assigned-users-table-container').removeClass('d-none');
                $('#modal-assigned-total-badge').text(res.total_users || 0);

                const $tbody = $('#assigned-users-tbody');
                $tbody.empty();

                if (!res.users || res.users.length === 0) {
                    $tbody.append('<tr><td colspan="6" class="text-center py-4 text-muted">{{ __("No customers found.") }}</td></tr>');
                    return;
                }

                res.users.forEach(function (u, idx) {
                    const statusBadge = u.status
                        ? '<span class="badge bg-success" style="font-size: 11px;">{{ __("Active") }}</span>'
                        : '<span class="badge bg-danger" style="font-size: 11px;">{{ __("Deactive") }}</span>';
                    
                    const usernameTag = u.username ? '<small class="text-primary font-monospace d-block">@' + u.username + '</small>' : '';

                    const rowHtml = '<tr>' +
                        '<td>' + (idx + 1) + '</td>' +
                        '<td><div class="fw-bold">' + u.user_name + '</div>' + usernameTag + '</td>' +
                        '<td><div>' + u.phone + '</div><small class="text-muted">' + u.email + '</small></td>' +
                        '<td><span class="badge px-2 py-1" style="background-color: #4e73df; color: #fff; font-size: 11.5px;"><i class="fas fa-network-wired me-1" style="font-size: 9px;"></i>' + u.gateway_name + '</span></td>' +
                        '<td>' + statusBadge + '</td>' +
                        '</tr>';
                    $tbody.append(rowHtml);
                });
            },
            error: function () {
                $('#assigned-users-loading').addClass('d-none');
                toastr.error('{{ __("Failed to load assigned customers.") }}');
            }
        });
    });

    // "Filter in Main Table" button from Assigned Users Modal
    $('#btn-filter-main-table-by-sender').on('click', function () {
        if (!currentModalSender) return;
        $('#assigned-users-modal').modal('hide');
        $('.searchInput').val(currentModalSender);
        $('#filter-gateway-select').val('');
        reloadSenderIds();
    });
});
</script>
@endpush
