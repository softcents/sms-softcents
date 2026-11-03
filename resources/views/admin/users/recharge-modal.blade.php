<div class="modal fade" id="modal-admin-recharge" tabindex="-1" aria-labelledby="modalAdminRechargeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-light py-3 border-bottom">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="modalAdminRechargeLabel">
                    <span class="rounded-circle bg-success text-white p-2 d-inline-flex align-items-center justify-content-center me-2" style="width: 34px; height: 34px; font-size: 14px;">
                        <i class="fas fa-wallet"></i>
                    </span>
                    <span id="recharge-modal-title">{{ __('Recharge / Adjust Balance') }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-admin-recharge" action="{{ route('admin.users.recharge') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- User Selection / Summary Card -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">{{ __('Target Account') }} <span class="text-danger">*</span></label>
                        
                        <!-- Select dropdown when opened from generic button -->
                        <div id="recharge-user-select-wrap">
                            <select name="user_id" id="recharge_user_select" class="form-select form-control" required>
                                <option value="" disabled selected>{{ __('-- Select Reseller or Customer --') }}</option>
                                @if(isset($allRechargeableUsers))
                                    @php
                                        $groupedUsers = $allRechargeableUsers->groupBy('role');
                                    @endphp
                                    @if(isset($groupedUsers['sp']))
                                        <optgroup label="{{ __('Resellers (SP)') }}">
                                            @foreach($groupedUsers['sp'] as $u)
                                                <option value="{{ $u->id }}" data-name="{{ $u->name }}" data-username="{{ $u->username }}" data-role="SP" data-balance="{{ (float)$u->balance }}" data-phone="{{ $u->phone }}">
                                                    {{ $u->name }} (@ {{ $u->username }}) - Balance: {{ currency_format($u->balance) }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                    @if(isset($groupedUsers['ssp']))
                                        <optgroup label="{{ __('Sub Resellers (SSP)') }}">
                                            @foreach($groupedUsers['ssp'] as $u)
                                                <option value="{{ $u->id }}" data-name="{{ $u->name }}" data-username="{{ $u->username }}" data-role="SSP" data-balance="{{ (float)$u->balance }}" data-phone="{{ $u->phone }}">
                                                    {{ $u->name }} (@ {{ $u->username }}) - Balance: {{ currency_format($u->balance) }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                    @if(isset($groupedUsers['user']))
                                        <optgroup label="{{ __('Customers') }}">
                                            @foreach($groupedUsers['user'] as $u)
                                                <option value="{{ $u->id }}" data-name="{{ $u->name }}" data-username="{{ $u->username }}" data-role="CUSTOMER" data-balance="{{ (float)$u->balance }}" data-phone="{{ $u->phone }}">
                                                    {{ $u->name }} (@ {{ $u->username }}) - Balance: {{ currency_format($u->balance) }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endif
                            </select>
                        </div>

                        <!-- Info Card (shown when user is selected or opened directly from row) -->
                        <div id="recharge-user-info-card" class="card bg-light border mt-2" style="display: none; border-radius: 8px;">
                            <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold text-dark" id="recharge-card-name">--</div>
                                    <div class="small text-muted">
                                        <span class="badge bg-primary text-white me-1" id="recharge-card-role">ROLE</span>
                                        <span id="recharge-card-username">@username</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted">{{ __('Current Balance') }}</div>
                                    <div class="fw-bold fs-6 text-success" id="recharge-card-balance">৳0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Type: Add (Credit) vs Deduct (Debit) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">{{ __('Operation Type') }} <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="type" id="recharge_type_add" value="add" checked autocomplete="off">
                            <label class="btn btn-outline-success flex-fill py-2 fw-semibold" for="recharge_type_add">
                                <i class="fas fa-plus-circle me-1"></i> {{ __('Add Balance (Credit)') }}
                            </label>

                            <input type="radio" class="btn-check" name="type" id="recharge_type_deduct" value="deduct" autocomplete="off">
                            <label class="btn btn-outline-danger flex-fill py-2 fw-semibold" for="recharge_type_deduct">
                                <i class="fas fa-minus-circle me-1"></i> {{ __('Deduct Balance (Debit)') }}
                            </label>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="recharge_amount" class="form-label fw-bold small text-muted text-uppercase mb-1">
                            {{ __('Amount') }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white fw-bold">{{ default_currency()->symbol ?? '৳' }}</span>
                            <input type="number" step="any" min="0.01" name="amount" id="recharge_amount" class="form-control form-control-lg fw-bold" placeholder="0.00" required>
                        </div>
                        <!-- Quick Presets -->
                        <div class="d-flex flex-wrap gap-1 mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-quick-amt" data-amt="100">+৳100</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-quick-amt" data-amt="500">+৳500</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-quick-amt" data-amt="1000">+৳1,000</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-quick-amt" data-amt="5000">+৳5,000</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 btn-quick-amt" data-amt="10000">+৳10,000</button>
                            <button type="button" class="btn btn-sm btn-outline-light text-muted py-1 px-2 border btn-clear-amt">{{ __('Clear') }}</button>
                        </div>
                    </div>

                    <!-- Live Balance Preview -->
                    <div id="recharge-preview-card" class="p-2 mb-3 rounded border text-center" style="background-color: #f8fbf9; display: none;">
                        <span class="small text-muted">{{ __('Calculated New Balance:') }}</span>
                        <span class="fw-bold fs-6 ms-1 text-primary" id="recharge-preview-val">৳0.00</span>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label for="recharge_payment_method" class="form-label fw-bold small text-muted text-uppercase mb-1">
                            {{ __('Payment Method / Source') }}
                        </label>
                        <select name="payment_method" id="recharge_payment_method" class="form-select form-control">
                            <option value="Cash / Handover" selected>{{ __('Cash / Handover') }}</option>
                            <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                            <option value="bKash">{{ __('bKash') }}</option>
                            <option value="Nagad">{{ __('Nagad') }}</option>
                            <option value="Rocket">{{ __('Rocket') }}</option>
                            <option value="System Adjustment">{{ __('System Adjustment') }}</option>
                            <option value="Other">{{ __('Other') }}</option>
                        </select>
                    </div>

                    <!-- Transaction ID / Reference (Optional) -->
                    <div class="mb-3">
                        <label for="recharge_transaction_id" class="form-label fw-bold small text-muted text-uppercase mb-1">
                            {{ __('Transaction ID / TrxID (Optional)') }}
                        </label>
                        <input type="text" name="transaction_id" id="recharge_transaction_id" class="form-control" placeholder="e.g. TRX123456789">
                    </div>

                    <!-- Notes -->
                    <div class="mb-2">
                        <label for="recharge_notes" class="form-label fw-bold small text-muted text-uppercase mb-1">
                            {{ __('Admin Note / Remarks (Optional)') }}
                        </label>
                        <textarea name="notes" id="recharge_notes" rows="2" class="form-control" placeholder="{{ __('e.g. Reseller balance top-up by Admin') }}"></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-3 border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-success px-4" id="btn-submit-recharge">
                        <i class="fas fa-check-circle me-1"></i>
                        <span id="btn-submit-recharge-label">{{ __('Confirm Recharge') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let currentUserBalance = 0;

    function formatCurrency(num) {
        let n = parseFloat(num) || 0;
        let symbol = '{{ default_currency()->symbol ?? "৳" }}';
        return symbol + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateCalculatedPreview() {
        let amt = parseFloat($('#recharge_amount').val()) || 0;
        let type = $('input[name="type"]:checked').val();
        let newBalance = currentUserBalance;

        if (type === 'add') {
            newBalance = currentUserBalance + amt;
            $('#btn-submit-recharge-label').text('{{ __("Confirm Recharge") }}');
            $('#btn-submit-recharge').removeClass('btn-danger').addClass('btn-success');
        } else {
            newBalance = currentUserBalance - amt;
            $('#btn-submit-recharge-label').text('{{ __("Confirm Deduction") }}');
            $('#btn-submit-recharge').removeClass('btn-success').addClass('btn-danger');
        }

        if (amt > 0) {
            $('#recharge-preview-card').show();
            let previewEl = $('#recharge-preview-val');
            previewEl.text(formatCurrency(newBalance));
            if (newBalance < 0) {
                previewEl.removeClass('text-primary text-success').addClass('text-danger');
            } else {
                previewEl.removeClass('text-danger').addClass('text-success');
            }
        } else {
            $('#recharge-preview-card').hide();
        }
    }

    // Trigger on row button click or header button click
    $(document).on('click', '.btn-open-recharge-modal', function(e) {
        e.preventDefault();
        let btn = $(this);
        let userId = btn.data('id');
        let userName = btn.data('name');
        let username = btn.data('username');
        let userRole = btn.data('role') || 'USER';
        let balance = parseFloat(btn.data('balance')) || 0;
        let formattedBalance = btn.data('balance-formatted') || formatCurrency(balance);

        // Reset form
        $('#form-admin-recharge')[0].reset();
        $('#recharge_type_add').prop('checked', true);
        $('#btn-submit-recharge').prop('disabled', false).removeClass('btn-danger').addClass('btn-success');
        $('#btn-submit-recharge-label').text('{{ __("Confirm Recharge") }}');
        $('#recharge-preview-card').hide();

        if (userId) {
            // Opened directly for a specific user
            currentUserBalance = balance;
            $('#recharge_user_select').val(userId).prop('disabled', false);
            $('#recharge-card-name').text(userName);
            $('#recharge-card-role').text(userRole);
            $('#recharge-card-username').text('@' + username);
            $('#recharge-card-balance').text(formattedBalance);
            $('#recharge-user-info-card').slideDown(150);
            $('#recharge-modal-title').text('{{ __("Recharge") }} - ' + userName);
        } else {
            // Opened generically without preselected user
            currentUserBalance = 0;
            $('#recharge_user_select').val('').prop('disabled', false);
            $('#recharge-user-info-card').hide();
            $('#recharge-modal-title').text('{{ __("Recharge / Adjust Balance") }}');
        }

        let modalEl = new bootstrap.Modal(document.getElementById('modal-admin-recharge'));
        modalEl.show();
    });

    // When selecting a user in dropdown
    $('#recharge_user_select').on('change', function() {
        let opt = $(this).find('option:selected');
        if (opt.val()) {
            let name = opt.data('name');
            let username = opt.data('username');
            let role = opt.data('role') || 'USER';
            let balance = parseFloat(opt.data('balance')) || 0;
            currentUserBalance = balance;

            $('#recharge-card-name').text(name);
            $('#recharge-card-role').text(role);
            $('#recharge-card-username').text('@' + username);
            $('#recharge-card-balance').text(formatCurrency(balance));
            $('#recharge-user-info-card').slideDown(150);
            updateCalculatedPreview();
        } else {
            currentUserBalance = 0;
            $('#recharge-user-info-card').slideUp(150);
            $('#recharge-preview-card').hide();
        }
    });

    // Type change
    $('input[name="type"]').on('change', function() {
        updateCalculatedPreview();
    });

    // Amount input
    $('#recharge_amount').on('input keyup', function() {
        updateCalculatedPreview();
    });

    // Quick presets
    $('.btn-quick-amt').on('click', function(e) {
        e.preventDefault();
        let amt = parseFloat($(this).data('amt')) || 0;
        let curr = parseFloat($('#recharge_amount').val()) || 0;
        $('#recharge_amount').val(curr + amt);
        updateCalculatedPreview();
    });

    $('.btn-clear-amt').on('click', function(e) {
        e.preventDefault();
        $('#recharge_amount').val('');
        updateCalculatedPreview();
    });

    // AJAX Submission
    $('#form-admin-recharge').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let submitBtn = $('#btn-submit-recharge');
        let originalLabel = $('#btn-submit-recharge-label').text();

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> {{ __("Processing...") }}');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(res) {
                submitBtn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> ' + originalLabel);
                if (res.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(res.message);
                    } else {
                        alert(res.message);
                    }

                    // Update balance cell in datatable if present
                    if (res.user_id) {
                        let cell = $('.user-balance-cell-' + res.user_id);
                        if (cell.length) {
                            cell.text(res.formatted_balance);
                            if (res.is_low_balance) {
                                cell.removeClass('text-success').addClass('text-danger');
                            } else {
                                cell.removeClass('text-danger').addClass('text-success');
                            }
                        }

                        // Also update data attribute on action button
                        let btn = $('.btn-open-recharge-modal[data-id="' + res.user_id + '"]');
                        if (btn.length) {
                            btn.attr('data-balance', res.balance);
                            btn.attr('data-balance-formatted', res.formatted_balance);
                            btn.data('balance', res.balance);
                            btn.data('balance-formatted', res.formatted_balance);
                        }

                        // Also update select option data
                        let opt = $('#recharge_user_select option[value="' + res.user_id + '"]');
                        if (opt.length) {
                            opt.data('balance', res.balance);
                            opt.attr('data-balance', res.balance);
                        }
                    }

                    // Hide modal
                    let modalInst = bootstrap.Modal.getInstance(document.getElementById('modal-admin-recharge'));
                    if (modalInst) {
                        modalInst.hide();
                    }

                    // If on recharges table, reload after short delay so new record appears
                    if (window.location.pathname.includes('/admin/recharges')) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 800);
                    }
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(res.message || '{{ __("Failed to recharge.") }}');
                    } else {
                        alert(res.message || '{{ __("Failed to recharge.") }}');
                    }
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> ' + originalLabel);
                let msg = '{{ __("Something went wrong.") }}';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                }
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            }
        });
    });
});
</script>
@endpush
