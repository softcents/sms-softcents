<div class="modal fade" id="modal-assign-plan" tabindex="-1" aria-labelledby="modalAssignPlanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-light py-3 border-bottom">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="modalAssignPlanLabel">
                    <span class="rounded-circle bg-primary text-white p-2 d-inline-flex align-items-center justify-content-center me-2" style="width: 34px; height: 34px; font-size: 14px;">
                        <i class="fas fa-layer-group"></i>
                    </span>
                    <span>{{ __('Assign Subscription Plan') }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-assign-plan" action="{{ route('admin.users.assign-plan') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- Target User Info -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">{{ __('Target Customer') }} <span class="text-danger">*</span></label>
                        
                        <div id="plan-user-select-wrap">
                            <select name="user_id" id="plan_user_select" class="form-select form-control" required>
                                <option value="" disabled selected>{{ __('-- Select Customer --') }}</option>
                                @if(isset($allRechargeableUsers))
                                    @foreach($allRechargeableUsers as $u)
                                        <option value="{{ $u->id }}" data-name="{{ $u->name }}" data-username="{{ $u->username }}" data-role="{{ strtoupper($u->role) }}" data-balance="{{ (float)$u->balance }}" data-phone="{{ $u->phone }}">
                                            {{ $u->name }} (@ {{ $u->username }}) [{{ strtoupper($u->role) }}]
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- User Info Card -->
                        <div id="plan-user-info-card" class="card bg-light border mt-2" style="display: none; border-radius: 8px;">
                            <div class="card-body p-2 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-white border text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 15px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0" id="plan-target-user-name">-</div>
                                        <small class="text-muted" id="plan-target-user-details">-</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-white text-dark border px-2 py-1" id="plan-target-user-plan" style="font-size: 12px;">{{ __('Current Plan: None') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Plan Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">{{ __('Select Package / Plan') }} <span class="text-danger">*</span></label>
                        <select name="plan_id" id="plan_select_id" class="form-select form-control" required>
                            <option value="" disabled selected>{{ __('-- Choose a Plan --') }}</option>
                            @if(isset($allPlans))
                                @foreach($allPlans as $p)
                                    <option value="{{ $p->id }}"
                                        data-title="{{ $p->title }}"
                                        data-price="{{ (float)$p->price }}"
                                        data-sms="{{ $p->total_sms }}"
                                        data-duration="{{ $p->duration }}"
                                        data-masking="{{ (float)$p->masking_rate }}"
                                        data-nonmasking="{{ (float)$p->non_masking_rate }}"
                                        data-api="{{ $p->allow_api ? 'Yes' : 'No' }}">
                                        {{ $p->title }} (৳{{ number_format((float)$p->price, 2) }} - {{ $p->total_sms }} SMS - {{ ucfirst(str_replace('_', ' ', $p->duration)) }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Plan Details Live Card -->
                    <div id="plan-details-preview" class="card border border-primary-subtle bg-primary-subtle mb-3" style="display: none; border-radius: 8px;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold text-primary mb-0" id="preview-plan-title">-</h6>
                                <span class="badge bg-primary text-white" id="preview-plan-price">৳0.00</span>
                            </div>
                            <div class="row g-2 text-dark small">
                                <div class="col-6">
                                    <i class="fas fa-clock text-muted me-1"></i> {{ __('Duration') }}: <strong id="preview-plan-duration">-</strong>
                                </div>
                                <div class="col-6">
                                    <i class="fas fa-sms text-muted me-1"></i> {{ __('Total SMS') }}: <strong id="preview-plan-sms">-</strong>
                                </div>
                                <div class="col-6">
                                    <i class="fas fa-tag text-muted me-1"></i> {{ __('Masking Rate') }}: <strong id="preview-plan-masking">৳0.00</strong>
                                </div>
                                <div class="col-6">
                                    <i class="fas fa-tag text-muted me-1"></i> {{ __('Non-Masking Rate') }}: <strong id="preview-plan-nonmasking">৳0.00</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Validity / Duration -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">{{ __('Validity Days') }}</label>
                        <div class="input-group">
                            <input type="number" name="duration_days" id="plan_duration_days" class="form-control" placeholder="{{ __('Leave blank to use plan default duration') }}" min="1">
                            <span class="input-group-text">{{ __('Days') }}</span>
                        </div>
                        <small class="text-muted">{{ __('Optional. Leave empty to automatically use the plan\'s standard duration (e.g. 365 days for yearly).') }}</small>
                    </div>

                    <!-- Options Checkboxes -->
                    <div class="bg-light p-3 rounded mb-3 border">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="update_rates" value="1" id="check_update_rates" checked>
                            <label class="form-check-label text-dark fw-medium small" for="check_update_rates">
                                {{ __('Update Customer SMS Rates (Masking & Non-Masking) from Plan') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="add_balance" value="1" id="check_add_balance" checked>
                            <label class="form-check-label text-dark fw-bold small" for="check_add_balance">
                                <i class="fas fa-coins text-success me-1"></i> {{ __('Credit Plan Price to Customer Account Balance (Recommended)') }}
                            </label>
                            <div class="text-muted small ps-4">
                                {{ __('When this package is activated, the plan price (e.g. ৳1,250) will be automatically credited to customer balance.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1">{{ __('Admin Note') }}</label>
                        <input type="text" name="notes" class="form-control" placeholder="{{ __('Optional note for subscription record') }}" maxlength="500">
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold" id="btn-submit-assign-plan">
                        <i class="fas fa-check-circle me-1"></i> <span id="btn-submit-plan-label">{{ __('Assign Plan Now') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
$(document).ready(function() {
    // Open modal directly from row button
    $(document).on('click', '.btn-open-assign-plan-modal', function(e) {
        e.preventDefault();
        let btn = $(this);
        let userId = btn.data('id');
        let userName = btn.data('name');
        let username = btn.data('username');
        let currentPlan = btn.data('plan') || 'None';
        let planId = btn.data('plan-id') || '';

        $('#plan_user_select').val(userId);
        $('#plan-user-select-wrap').hide();
        $('#plan-user-info-card').show();
        $('#plan-target-user-name').text(userName);
        $('#plan-target-user-details').text('@' + username);
        $('#plan-target-user-plan').text('{{ __("Current:") }} ' + currentPlan);

        if (planId) {
            $('#plan_select_id').val(planId).trigger('change');
        }

        let modal = new bootstrap.Modal(document.getElementById('modal-assign-plan'));
        modal.show();
    });

    // Reset when modal is closed
    $('#modal-assign-plan').on('hidden.bs.modal', function () {
        $('#form-assign-plan')[0].reset();
        $('#plan-user-select-wrap').show();
        $('#plan-user-info-card').hide();
        $('#plan-details-preview').hide();
    });

    // Handle user selection from dropdown
    $('#plan_user_select').on('change', function() {
        let opt = $(this).find('option:selected');
        if (opt.val()) {
            $('#plan-target-user-name').text(opt.data('name'));
            $('#plan-target-user-details').text('@' + opt.data('username') + ' | ' + opt.data('role'));
            $('#plan-user-info-card').show();
        }
    });

    // Handle Plan Selection Preview
    $('#plan_select_id').on('change', function() {
        let opt = $(this).find('option:selected');
        if (opt.val()) {
            let title = opt.data('title');
            let price = parseFloat(opt.data('price')) || 0;
            let sms = opt.data('sms');
            let duration = opt.data('duration');
            let masking = parseFloat(opt.data('masking')) || 0;
            let nonmasking = parseFloat(opt.data('nonmasking')) || 0;

            $('#preview-plan-title').text(title);
            $('#preview-plan-price').text('৳' + price.toFixed(2));
            $('#preview-plan-duration').text(duration.replace('_', ' '));
            $('#preview-plan-sms').text(sms ? Number(sms).toLocaleString() : 'N/A');
            $('#preview-plan-masking').text('৳' + masking.toFixed(3));
            $('#preview-plan-nonmasking').text('৳' + nonmasking.toFixed(3));

            $('#plan-details-preview').slideDown(200);
        } else {
            $('#plan-details-preview').slideUp(200);
        }
    });

    // AJAX Submission
    $('#form-assign-plan').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let submitBtn = $('#btn-submit-assign-plan');
        let originalText = $('#btn-submit-plan-label').text();

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> {{ __("Assigning...") }}');

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
                submitBtn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> ' + originalText);
                if (res.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(res.message);
                    } else {
                        alert(res.message);
                    }

                    // Update plan badge in table
                    if (res.user_id) {
                        let planBadge = $('.user-plan-cell-' + res.user_id);
                        if (planBadge.length) {
                            planBadge.html('<span class="badge bg-info text-dark">' + res.plan_title + '</span>');
                        }

                        // Update data on row button
                        let btn = $('.btn-open-assign-plan-modal[data-id="' + res.user_id + '"]');
                        if (btn.length) {
                            btn.attr('data-plan', res.plan_title);
                            btn.attr('data-plan-id', res.plan_id);
                            btn.data('plan', res.plan_title);
                            btn.data('plan-id', res.plan_id);
                        }
                    }

                    let modalInst = bootstrap.Modal.getInstance(document.getElementById('modal-assign-plan'));
                    if (modalInst) {
                        modalInst.hide();
                    }

                    setTimeout(function() {
                        window.location.reload();
                    }, 900);
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(res.message || '{{ __("Failed to assign plan.") }}');
                    } else {
                        alert(res.message || '{{ __("Failed to assign plan.") }}');
                    }
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> ' + originalText);
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
