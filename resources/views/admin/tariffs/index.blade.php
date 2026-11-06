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

                <!-- Bulk Actions Toolbar -->
                <div class="card bg-light border mb-3">
                    <div class="card-body py-2 px-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold text-dark small"><i class="fas fa-layer-group text-primary me-1"></i> {{ __('Multiple Tariff') }}:</span>
                            <span id="admin-bulk-count" class="badge bg-secondary">0 {{ __('selected') }}</span>
                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" id="btn-admin-select-all">
                                <i class="fas fa-check-square me-1"></i> {{ __('Select All') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" id="btn-admin-clear-selection" style="display:none;">
                                <i class="fas fa-times me-1"></i> {{ __('Clear') }}
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-primary fw-semibold" id="btn-open-admin-bulk" disabled>
                                <i class="fas fa-edit me-1"></i> {{ __('Set Tariff for Selected') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tariffs Table -->
                <div class="responsive-table">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;" class="text-center">
                                    <input type="checkbox" id="check-all-admin-tariffs" class="form-check-input">
                                </th>
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
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input tariff-admin-checkbox" value="{{ $u->id }}" data-name="{{ $u->name }}" data-user="{{ $u->username }}">
                                    </td>
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
                                    <td colspan="10" class="text-center py-4 text-muted">
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

    <!-- Bulk Tariff Edit Modal for Admin -->
    <div class="modal fade" id="modal-admin-bulk-tariff" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                    <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                        <i class="fas fa-layer-group"></i>
                        <span>{{ __('Set Tariff for Selected Accounts') }}</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="form-admin-bulk-tariff" action="{{ route('admin.tariffs.bulk-update') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-primary py-2 px-3 small mb-3 d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fas fa-users me-1"></i>
                                <span id="admin-bulk-modal-count-text">0 {{ __('accounts selected') }}</span>
                            </div>
                            <span class="badge bg-white text-primary fw-bold" id="admin-bulk-modal-badge">0</span>
                        </div>

                        <div class="alert alert-info py-2 small mb-3">
                            <i class="fas fa-coins me-1"></i>
                            {{ __('Platform wholesale buying costs') }}: <strong>NM: ৳{{ number_format($gwNonMasking, 2) }}</strong> &bull; <strong>Masking: ৳{{ number_format($gwMasking, 2) }}</strong>.
                        </div>

                        <!-- Selected Accounts Preview Chips -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">{{ __('Selected Accounts') }}:</label>
                            <div id="admin-bulk-selected-chips" class="p-2 bg-light border rounded d-flex flex-wrap gap-1" style="max-height: 90px; overflow-y: auto;"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('Non-Masking Rate (BDT)') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text fw-bold">৳</span>
                                <input type="number" step="0.0001" min="0" name="non_masking_rate" id="admin_bulk_modal_nm" class="form-control" required placeholder="0.3900" value="0.3900">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('Masking Rate (BDT)') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text fw-bold">৳</span>
                                <input type="number" step="0.0001" min="0" name="masking_rate" id="admin_bulk_modal_m" class="form-control" required placeholder="0.7500" value="0.7500">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold" id="btn-admin-bulk-save">
                            <i class="fas fa-check-circle me-1"></i> <span id="btn-admin-bulk-label">{{ __('Apply to Selected') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
$(function() {
    function updateAdminBulkState() {
        let selected = $('.tariff-admin-checkbox:checked');
        let count = selected.length;
        let total = $('.tariff-admin-checkbox').length;

        $('#admin-bulk-count').text(count + ' {{ __("selected") }}');
        $('#admin-bulk-modal-count-text').text(count + ' {{ __("accounts selected for tariff update") }}');
        $('#admin-bulk-modal-badge').text(count);

        if (count > 0) {
            $('#btn-open-admin-bulk').prop('disabled', false);
            $('#btn-admin-clear-selection').show();
        } else {
            $('#btn-open-admin-bulk').prop('disabled', true);
            $('#btn-admin-clear-selection').hide();
        }

        $('#check-all-admin-tariffs').prop('checked', count > 0 && count === total);
    }

    $(document).on('change', '.tariff-admin-checkbox', function() {
        updateAdminBulkState();
    });

    $('#check-all-admin-tariffs').on('change', function() {
        let isChecked = $(this).is(':checked');
        $('.tariff-admin-checkbox').prop('checked', isChecked);
        updateAdminBulkState();
    });

    $('#btn-admin-select-all').on('click', function() {
        $('.tariff-admin-checkbox').prop('checked', true);
        $('#check-all-admin-tariffs').prop('checked', true);
        updateAdminBulkState();
    });

    $('#btn-admin-clear-selection').on('click', function() {
        $('.tariff-admin-checkbox').prop('checked', false);
        $('#check-all-admin-tariffs').prop('checked', false);
        updateAdminBulkState();
    });

    $('#btn-open-admin-bulk').on('click', function(e) {
        e.preventDefault();
        let selected = $('.tariff-admin-checkbox:checked');
        if (!selected.length) return;

        let chipsHtml = '';
        selected.each(function() {
            let name = $(this).data('name') || '';
            let user = $(this).data('user') || '';
            chipsHtml += '<span class="badge bg-white text-dark border px-2 py-1"><i class="fas fa-user-circle text-primary me-1"></i>' + name + ' (@' + user + ')</span>';
        });
        $('#admin-bulk-selected-chips').html(chipsHtml);

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            let modalEl = document.getElementById('modal-admin-bulk-tariff');
            let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        } else {
            $('#modal-admin-bulk-tariff').modal('show');
        }
    });

    $('#form-admin-bulk-tariff').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let submitBtn = $('#btn-admin-bulk-save');
        let origText = $('#btn-admin-bulk-label').text();

        let userIds = [];
        $('.tariff-admin-checkbox:checked').each(function() {
            userIds.push($(this).val());
        });

        if (!userIds.length) {
            alert('{{ __("Please select at least one account.") }}');
            return;
        }

        submitBtn.prop('disabled', true);
        $('#btn-admin-bulk-label').html('<i class="fas fa-spinner fa-spin me-1"></i> {{ __("Updating...") }}');

        let data = form.serializeArray();
        userIds.forEach(function(id) {
            data.push({ name: 'user_ids[]', value: id });
        });

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: $.param(data),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(res.message);
                    } else {
                        alert(res.message);
                    }
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        let modalEl = document.getElementById('modal-admin-bulk-tariff');
                        let modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    } else {
                        $('#modal-admin-bulk-tariff').modal('hide');
                    }
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                } else {
                    alert(res.message || '{{ __("Failed to update tariffs.") }}');
                }
            },
            error: function(xhr) {
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '{{ __("Failed to update tariffs.") }}';
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                $('#btn-admin-bulk-label').text(origText);
            }
        });
    });
});
</script>
@endpush
