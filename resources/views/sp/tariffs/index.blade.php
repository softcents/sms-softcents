@extends('layouts.sp.master')

@section('title')
    {{ __('Tariff Management') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <!-- SP Cost Banner & Downline Summary -->
        <div class="gpt-dashboard-card grid-4 mb-30">
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
                    <h5 class="text-success fw-bold">{{ currency_format($sp->balance) }}</h5>
                    <p>{{ __('My Wallet Balance') }}</p>
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

            <div class="couter-box">
                <div class="icons bg-violet-light">
                    <img src="{{ asset('assets/img/icon/user1.png') }}" alt="Customers">
                </div>
                <div class="content-side">
                    <h5 class="fw-bold" style="color: #6366f1;">{{ $totalCustomers }}</h5>
                    <p>{{ __('Direct Customers') }}</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <!-- Header Bar -->
                <div class="table-header d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="fas fa-tags text-primary me-2"></i>{{ __('Downline Tariff Management') }}</h4>
                        <p class="text-muted small mb-0">{{ __('Manage SMS selling rates and profit margins for your Sub-Resellers and direct customers.') }}</p>
                    </div>

                    <!-- Role Filters -->
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <a href="{{ route('sp.tariffs.index', ['role' => 'all', 'search' => $search]) }}" class="btn btn-sm {{ $role == 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ __('All Downlines') }} ({{ $totalSsp + $totalCustomers }})
                        </a>
                        <a href="{{ route('sp.tariffs.index', ['role' => 'ssp', 'search' => $search]) }}" class="btn btn-sm {{ $role == 'ssp' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ __('Sub-Resellers (SSP)') }} ({{ $totalSsp }})
                        </a>
                        <a href="{{ route('sp.tariffs.index', ['role' => 'user', 'search' => $search]) }}" class="btn btn-sm {{ $role == 'user' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ __('Customers') }} ({{ $totalCustomers }})
                        </a>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="search-form-wrap mb-3">
                    <form action="{{ route('sp.tariffs.index') }}" method="GET" class="d-flex gap-2">
                        <input type="hidden" name="role" value="{{ $role }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('Search downlines by name, username, email, phone...') }}" value="{{ $search }}">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i> {{ __('Search') }}</button>
                            @if($search)
                                <a href="{{ route('sp.tariffs.index', ['role' => $role]) }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="responsive-table">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">{{ __('SL') }}</th>
                                <th>{{ __('Account / Name') }}</th>
                                <th>{{ __('Role / Type') }}</th>
                                <th>{{ __('Non-Masking Rate & Margin') }}</th>
                                <th>{{ __('Masking Rate & Margin') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-center" style="width: 130px;">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $index => $c)
                                @php
                                    $nmRate = (float)($c->non_masking_rate ?: 0.39);
                                    $mRate = (float)($c->masking_rate ?: 0.75);
                                    $nmMargin = $nmRate - $myNonMasking;
                                    $mMargin = $mRate - $myMasking;
                                @endphp
                                <tr id="row-tariff-{{ $c->id }}">
                                    <td>{{ $clients->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $c->name }}</div>
                                        <small class="text-primary font-monospace"><i class="fas fa-at"></i> {{ $c->username }}</small>
                                    </td>
                                    <td>
                                        @if($c->role == 'ssp')
                                            <span class="badge bg-warning text-dark">{{ __('SUB-RESELLER (SSP)') }}</span>
                                        @else
                                            <span class="badge bg-info text-dark">{{ __('CUSTOMER') }}</span>
                                        @endif
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
                                        <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 btn-sp-open-tariff"
                                            data-id="{{ $c->id }}"
                                            data-name="{{ $c->name }}"
                                            data-username="{{ $c->username }}"
                                            data-role="{{ strtoupper($c->role) }}"
                                            data-non-masking="{{ $nmRate }}"
                                            data-masking="{{ $mRate }}"
                                            data-action="{{ route('sp.tariffs.update', $c->id) }}"
                                            title="{{ __('Set Custom SMS Tariff') }}">
                                            <i class="fas fa-edit"></i>
                                            <span>{{ __('Set Tariff') }}</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-search me-1"></i> {{ __('No downline accounts found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $clients->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Tariff Edit Modal for SP -->
<div class="modal fade" id="modal-sp-tariff" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                    <i class="fas fa-tags"></i>
                    <span>{{ __('Set SMS Tariff') }}</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="form-sp-tariff" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="card bg-light border mb-3" style="border-radius: 8px;">
                        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold text-dark" id="sp-modal-client-name">-</div>
                                <small class="text-muted" id="sp-modal-client-user">-</small>
                            </div>
                            <span class="badge bg-primary text-white" id="sp-modal-client-role">USER</span>
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
                            <input type="number" step="0.0001" min="0" name="non_masking_rate" id="sp_modal_nm" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('Masking Rate (BDT)') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">৳</span>
                            <input type="number" step="0.0001" min="0" name="masking_rate" id="sp_modal_m" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold" id="btn-sp-tariff-save">
                        <i class="fas fa-check-circle me-1"></i> <span id="btn-sp-tariff-label">{{ __('Save Tariff') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $(document).on('click', '.btn-sp-open-tariff', function(e) {
        e.preventDefault();
        let btn = $(this);
        let id = btn.data('id');
        let name = btn.data('name');
        let username = btn.data('username');
        let role = btn.data('role');
        let nm = btn.data('non-masking');
        let m = btn.data('masking');
        let action = btn.data('action');

        $('#form-sp-tariff').attr('action', action);
        $('#sp-modal-client-name').text(name);
        $('#sp-modal-client-user').text('@' + username);
        $('#sp-modal-client-role').text(role);
        $('#sp_modal_nm').val(parseFloat(nm));
        $('#sp_modal_m').val(parseFloat(m));

        let modalEl = document.getElementById('modal-sp-tariff');
        let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.show();
    });

    $('#form-sp-tariff').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let submitBtn = $('#btn-sp-tariff-save');
        let origText = $('#btn-sp-tariff-label').text();

        submitBtn.prop('disabled', true);
        $('#btn-sp-tariff-label').html('<i class="fas fa-spinner fa-spin me-1"></i> {{ __("Saving...") }}');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(res) {
                if (res.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(res.message);
                    } else {
                        alert(res.message);
                    }
                    setTimeout(function() {
                        location.reload();
                    }, 600);
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
                $('#btn-sp-tariff-label').text(origText);
            }
        });
    });
});
</script>
@endpush
