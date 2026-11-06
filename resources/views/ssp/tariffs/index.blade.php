@extends('layouts.ssp.master')

@section('title')
    {{ __('Tariff Management') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <!-- SSP Cost Banner & Customer Summary -->
        <div class="gpt-dashboard-card grid-3 mb-30">
            <div class="couter-box">
                <div class="icons bg-primary-light">
                    <img src="{{ asset('assets/img/icon/crown-king.svg') }}" alt="Buying Rate">
                </div>
                <div class="content-side">
                    <h5 class="text-primary fw-bold">৳{{ number_format($myNonMasking, 2) }} / ৳{{ number_format($myMasking, 2) }}</h5>
                    <p>{{ __('My Buying Rate (NM / M)') }}</p>
                </div>
            </div>

            <div class="couter-box">
                <div class="icons bg-green2-light">
                    <img src="{{ asset('assets/images/icons/currencies.svg') }}" alt="Balance">
                </div>
                <div class="content-side">
                    <h5 class="text-success fw-bold">{{ currency_format($ssp->balance) }}</h5>
                    <p>{{ __('My Wallet Balance') }}</p>
                </div>
            </div>

            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/user1.png') }}" alt="Customers">
                </div>
                <div class="content-side">
                    <h5 class="fw-bold" style="color: #6366f1;">{{ $totalCustomers }}</h5>
                    <p>{{ __('Total Customers') }}</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <!-- Header Bar -->
                <div class="table-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="fas fa-tags text-primary me-2"></i>{{ __('Customer Tariff Management') }}</h4>
                        <p class="text-muted small mb-0">{{ __('Manage SMS selling rates and profit margins for your direct customers.') }}</p>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="search-form-wrap mb-3">
                    <form action="{{ route('ssp.tariffs.index') }}" method="GET" class="d-flex gap-2">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('Search customers by name, username, email, phone...') }}" value="{{ $search }}">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i> {{ __('Search') }}</button>
                            @if($search)
                                <a href="{{ route('ssp.tariffs.index') }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Bulk Actions Toolbar -->
                <div class="card bg-light border mb-3">
                    <div class="card-body py-2 px-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold text-dark small"><i class="fas fa-layer-group text-primary me-1"></i> {{ __('Multiple Tariff') }}:</span>
                            <span id="ssp-bulk-count" class="badge bg-secondary">0 {{ __('selected') }}</span>
                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" id="btn-ssp-select-all">
                                <i class="fas fa-check-square me-1"></i> {{ __('Select All') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" id="btn-ssp-clear-selection" style="display:none;">
                                <i class="fas fa-times me-1"></i> {{ __('Clear') }}
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-primary fw-semibold" id="btn-open-ssp-bulk" disabled>
                                <i class="fas fa-edit me-1"></i> {{ __('Set Tariff for Selected') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="responsive-table">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;" class="text-center">
                                    <input type="checkbox" id="check-all-ssp-tariffs" class="form-check-input">
                                </th>
                                <th style="width: 50px;">{{ __('SL') }}</th>
                                <th>{{ __('Customer / Name') }}</th>
                                <th>{{ __('Non-Masking Rate & Margin') }}</th>
                                <th>{{ __('Masking Rate & Margin') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center" style="width: 130px;">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $index => $c)
                                @php
                                    $nmRate = (float)($c->non_masking_rate ?: 0.39);
                                    $mRate = (float)($c->masking_rate ?: 0.75);
                                    $nmMargin = $nmRate - $myNonMasking;
                                    $mMargin = $mRate - $myMasking;
                                @endphp
                                <tr id="row-tariff-{{ $c->id }}">
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input tariff-ssp-checkbox" value="{{ $c->id }}" data-name="{{ $c->name }}" data-user="{{ $c->username }}" data-nm="{{ $nmRate }}" data-m="{{ $mRate }}">
                                    </td>
                                    <td>{{ $customers->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $c->name }}</div>
                                        <small class="text-primary font-monospace"><i class="fas fa-at"></i> {{ $c->username }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fs-6 font-monospace" id="nm-badge-{{ $c->id }}">
                                            ৳{{ number_format($nmRate, 2) }}
                                        </span>
                                        <br>
                                        <small class="{{ $nmMargin >= 0 ? 'text-success' : 'text-danger' }}">
                                            <i class="fas {{ $nmMargin >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                            {{ __('Profit') }}: ৳{{ number_format($nmMargin, 2) }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fs-6 font-monospace" id="m-badge-{{ $c->id }}">
                                            ৳{{ number_format($mRate, 2) }}
                                        </span>
                                        <br>
                                        <small class="{{ $mMargin >= 0 ? 'text-success' : 'text-danger' }}">
                                            <i class="fas {{ $mMargin >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                            {{ __('Profit') }}: ৳{{ number_format($mMargin, 2) }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong class="{{ $c->balance <= 0 ? 'text-danger' : 'text-success' }}">
                                            {{ currency_format($c->balance) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge {{ $c->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($c->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 btn-ssp-open-tariff"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-ssp-tariff"
                                            data-id="{{ $c->id }}"
                                            data-name="{{ $c->name }}"
                                            data-username="{{ $c->username }}"
                                            data-non-masking="{{ $nmRate }}"
                                            data-masking="{{ $mRate }}"
                                            data-action="{{ route('ssp.tariffs.update', $c->id) }}"
                                            title="{{ __('Set Customer SMS Tariff') }}">
                                            <i class="fas fa-edit"></i>
                                            <span>{{ __('Set Tariff') }}</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-search me-1"></i> {{ __('No customers found.') }}
                                    </td>
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

<!-- Quick Tariff Edit Modal for SSP -->
<div class="modal fade" id="modal-ssp-tariff" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                    <i class="fas fa-tags"></i>
                    <span>{{ __('Set Customer Tariff') }}</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="form-ssp-tariff" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="card bg-light border mb-3" style="border-radius: 8px;">
                        <div class="card-body py-2 px-3">
                            <div class="fw-bold text-dark" id="ssp-modal-client-name">-</div>
                            <small class="text-muted" id="ssp-modal-client-user">-</small>
                        </div>
                    </div>

                    <div class="alert alert-warning py-2 small mb-3">
                        <i class="fas fa-coins me-1"></i>
                        {{ __('Your buying rate is') }}: <strong>NM: ৳{{ number_format($myNonMasking, 2) }}</strong> &bull; <strong>Masking: ৳{{ number_format($myMasking, 2) }}</strong>.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('Non-Masking Rate (BDT)') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">৳</span>
                            <input type="number" step="0.0001" min="0" name="non_masking_rate" id="ssp_modal_nm" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('Masking Rate (BDT)') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">৳</span>
                            <input type="number" step="0.0001" min="0" name="masking_rate" id="ssp_modal_m" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold" id="btn-ssp-tariff-save">
                        <i class="fas fa-check-circle me-1"></i> <span id="btn-ssp-tariff-label">{{ __('Save Tariff') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Tariff Edit Modal for SSP -->
<div class="modal fade" id="modal-ssp-bulk-tariff" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ __('Set Tariff for Selected Customers') }}</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="form-ssp-bulk-tariff" action="{{ route('ssp.tariffs.bulk-update') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-primary py-2 px-3 small mb-3 d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-users me-1"></i>
                            <span id="ssp-bulk-modal-count-text">0 {{ __('customers selected') }}</span>
                        </div>
                        <span class="badge bg-white text-primary fw-bold" id="ssp-bulk-modal-badge">0</span>
                    </div>

                    <div class="alert alert-warning py-2 small mb-3">
                        <i class="fas fa-coins me-1"></i>
                        {{ __('Your buying rate is') }}: <strong>NM: ৳{{ number_format($myNonMasking, 2) }}</strong> &bull; <strong>Masking: ৳{{ number_format($myMasking, 2) }}</strong>.
                    </div>

                    <!-- Selected Customers Preview Chips -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">{{ __('Selected Customers') }}:</label>
                        <div id="ssp-bulk-selected-chips" class="p-2 bg-light border rounded d-flex flex-wrap gap-1" style="max-height: 90px; overflow-y: auto;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('Non-Masking Rate (BDT)') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">৳</span>
                            <input type="number" step="0.0001" min="0" name="non_masking_rate" id="ssp_bulk_modal_nm" class="form-control" required placeholder="0.3900" value="0.3900">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('Masking Rate (BDT)') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">৳</span>
                            <input type="number" step="0.0001" min="0" name="masking_rate" id="ssp_bulk_modal_m" class="form-control" required placeholder="0.7500" value="0.7500">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold" id="btn-ssp-bulk-save">
                        <i class="fas fa-check-circle me-1"></i> <span id="btn-ssp-bulk-label">{{ __('Apply to Selected') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(function() {
    function populateSspTariffModal(btn) {
        if (!btn || !btn.length || !btn.data('action')) return;
        let name = btn.data('name');
        let username = btn.data('username');
        let nm = btn.data('non-masking');
        let m = btn.data('masking');
        let action = btn.data('action');

        $('#form-ssp-tariff').attr('action', action);
        $('#ssp-modal-client-name').text(name);
        $('#ssp-modal-client-user').text('@' + username);
        $('#ssp_modal_nm').val(parseFloat(nm));
        $('#ssp_modal_m').val(parseFloat(m));
    }

    $(document).on('click', '.btn-ssp-open-tariff', function(e) {
        populateSspTariffModal($(this));
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            let modalEl = document.getElementById('modal-ssp-tariff');
            let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        } else {
            $('#modal-ssp-tariff').modal('show');
        }
    });

    $('#modal-ssp-tariff').on('show.bs.modal', function(e) {
        if (e.relatedTarget) {
            populateSspTariffModal($(e.relatedTarget));
        }
    });

    $('#form-ssp-tariff').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let submitBtn = $('#btn-ssp-tariff-save');
        let origText = $('#btn-ssp-tariff-label').text();

        submitBtn.prop('disabled', true);
        $('#btn-ssp-tariff-label').html('<i class="fas fa-spinner fa-spin me-1"></i> {{ __("Saving...") }}');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
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
                        let modalEl = document.getElementById('modal-ssp-tariff');
                        let modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    } else {
                        $('#modal-ssp-tariff').modal('hide');
                    }
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                } else {
                    alert(res.message || '{{ __("Failed to update tariff.") }}');
                }
            },
            error: function(xhr) {
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '{{ __("Failed to update tariff.") }}';
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                $('#btn-ssp-tariff-label').text(origText);
            }
        });
    });

    // Bulk selection logic
    function updateSspBulkState() {
        let selected = $('.tariff-ssp-checkbox:checked');
        let count = selected.length;
        let total = $('.tariff-ssp-checkbox').length;

        $('#ssp-bulk-count').text(count + ' {{ __("selected") }}');
        $('#ssp-bulk-modal-count-text').text(count + ' {{ __("customers selected") }}');
        $('#ssp-bulk-modal-badge').text(count);

        if (count > 0) {
            $('#btn-open-ssp-bulk').prop('disabled', false);
            $('#btn-ssp-clear-selection').show();
        } else {
            $('#btn-open-ssp-bulk').prop('disabled', true);
            $('#btn-ssp-clear-selection').hide();
        }

        $('#check-all-ssp-tariffs').prop('checked', count > 0 && count === total);
    }

    $(document).on('change', '.tariff-ssp-checkbox', function() {
        updateSspBulkState();
    });

    $('#check-all-ssp-tariffs').on('change', function() {
        let isChecked = $(this).is(':checked');
        $('.tariff-ssp-checkbox').prop('checked', isChecked);
        updateSspBulkState();
    });

    $('#btn-ssp-select-all').on('click', function() {
        $('.tariff-ssp-checkbox').prop('checked', true);
        $('#check-all-ssp-tariffs').prop('checked', true);
        updateSspBulkState();
    });

    $('#btn-ssp-clear-selection').on('click', function() {
        $('.tariff-ssp-checkbox').prop('checked', false);
        $('#check-all-ssp-tariffs').prop('checked', false);
        updateSspBulkState();
    });

    $('#btn-open-ssp-bulk').on('click', function(e) {
        e.preventDefault();
        let selected = $('.tariff-ssp-checkbox:checked');
        if (!selected.length) return;

        let chipsHtml = '';
        selected.each(function() {
            let name = $(this).data('name') || '';
            let user = $(this).data('user') || '';
            chipsHtml += '<span class="badge bg-white text-dark border px-2 py-1"><i class="fas fa-user-circle text-primary me-1"></i>' + name + ' (@' + user + ')</span>';
        });
        $('#ssp-bulk-selected-chips').html(chipsHtml);

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            let modalEl = document.getElementById('modal-ssp-bulk-tariff');
            let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        } else {
            $('#modal-ssp-bulk-tariff').modal('show');
        }
    });

    $('#form-ssp-bulk-tariff').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let submitBtn = $('#btn-ssp-bulk-save');
        let origText = $('#btn-ssp-bulk-label').text();

        let userIds = [];
        $('.tariff-ssp-checkbox:checked').each(function() {
            userIds.push($(this).val());
        });

        if (!userIds.length) {
            alert('{{ __("Please select at least one customer.") }}');
            return;
        }

        submitBtn.prop('disabled', true);
        $('#btn-ssp-bulk-label').html('<i class="fas fa-spinner fa-spin me-1"></i> {{ __("Updating...") }}');

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
                        let modalEl = document.getElementById('modal-ssp-bulk-tariff');
                        let modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    } else {
                        $('#modal-ssp-bulk-tariff').modal('hide');
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
                $('#btn-ssp-bulk-label').text(origText);
            }
        });
    });
});
</script>
@endpush
