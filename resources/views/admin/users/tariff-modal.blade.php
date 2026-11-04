<!-- Reusable Admin Set/Update Tariff Modal -->
<div class="modal fade" id="modal-tariff" tabindex="-1" aria-labelledby="modalTariffLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="modalTariffLabel">
                    <i class="fas fa-tags"></i>
                    <span>{{ __('Set Customer Tariff (SMS Rates)') }}</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-update-tariff" action="{{ route('admin.users.tariff.update') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="tariff_user_id" value="">

                <div class="modal-body p-4">
                    <!-- Target User Info Card -->
                    <div class="card bg-light border mb-3" style="border-radius: 8px;">
                        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: #e0f2fe; color: #0284c7; font-size: 16px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-0" id="tariff-target-user-name">-</div>
                                    <small class="text-muted" id="tariff-target-user-details">-</small>
                                </div>
                            </div>
                            <span class="badge bg-primary text-white px-2 py-1" id="tariff-target-user-role">USER</span>
                        </div>
                    </div>

                    <div class="alert alert-info py-2 px-3 mb-3 small d-flex align-items-center gap-2" style="border-radius: 8px;">
                        <i class="fas fa-info-circle fs-5 text-info"></i>
                        <div>
                            {{ __('Set per-SMS charges for this client. SMS charges will be automatically billed from their wallet balance.') }}
                        </div>
                    </div>

                    <!-- Non-Masking Rate Input -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark d-flex justify-content-between">
                            <span><i class="fas fa-comment-dots text-primary me-1"></i> {{ __('Non-Masking Rate (BDT / SMS)') }} <span class="text-danger">*</span></span>
                            <small class="text-muted">{{ __('Standard: ৳0.39') }}</small>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">৳</span>
                            <input type="number" step="0.0001" min="0" name="non_masking_rate" id="tariff_non_masking_rate" class="form-control" required placeholder="0.3900">
                        </div>
                        <small class="text-muted">{{ __('Rate charged per part for Non-Masking sender IDs') }}</small>
                    </div>

                    <!-- Masking Rate Input -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark d-flex justify-content-between">
                            <span><i class="fas fa-shield-alt text-success me-1"></i> {{ __('Masking Rate (BDT / SMS)') }} <span class="text-danger">*</span></span>
                            <small class="text-muted">{{ __('Standard: ৳0.75') }}</small>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">৳</span>
                            <input type="number" step="0.0001" min="0" name="masking_rate" id="tariff_masking_rate" class="form-control" required placeholder="0.7500">
                        </div>
                        <small class="text-muted">{{ __('Rate charged per part for Masking sender IDs') }}</small>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold" id="btn-submit-tariff">
                        <i class="fas fa-check-circle me-1"></i> <span id="btn-submit-tariff-label">{{ __('Save Tariff Rates') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Open Tariff Modal handler
    $(document).on('click', '.btn-open-tariff-modal', function(e) {
        e.preventDefault();
        let btn = $(this);
        let userId = btn.data('id');
        let userName = btn.data('name') || '';
        let username = btn.data('username') || '';
        let role = btn.data('role') || 'USER';
        let nonMasking = btn.data('non-masking') !== undefined ? btn.data('non-masking') : 0.39;
        let masking = btn.data('masking') !== undefined ? btn.data('masking') : 0.75;

        $('#tariff_user_id').val(userId);
        $('#tariff-target-user-name').text(userName);
        $('#tariff-target-user-details').text('@' + username);
        $('#tariff-target-user-role').text(role);
        $('#tariff_non_masking_rate').val(parseFloat(nonMasking));
        $('#tariff_masking_rate').val(parseFloat(masking));

        let modalEl = document.getElementById('modal-tariff');
        let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.show();
    });

    // Submit Tariff Form handler via AJAX
    $('#form-update-tariff').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let submitBtn = $('#btn-submit-tariff');
        let originalText = $('#btn-submit-tariff-label').text();

        submitBtn.prop('disabled', true);
        $('#btn-submit-tariff-label').html('<i class="fas fa-spinner fa-spin me-1"></i> {{ __("Saving...") }}');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(res) {
                if (res.success) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(res.message);
                    } else {
                        alert(res.message);
                    }

                    // Update UI table cell if present
                    let tariffCell = $('.user-tariff-cell-' + res.user_id);
                    if (tariffCell.length) {
                        let formattedNM = '৳' + parseFloat(res.non_masking_rate).toFixed(2);
                        let formattedM = '৳' + parseFloat(res.masking_rate).toFixed(2);
                        tariffCell.find('.badge-nm').text('NM: ' + formattedNM);
                        tariffCell.find('.badge-m').text('M: ' + formattedM);
                    }

                    // Update button data attributes
                    let btn = $('.btn-open-tariff-modal[data-id="' + res.user_id + '"]');
                    if (btn.length) {
                        btn.attr('data-non-masking', res.non_masking_rate);
                        btn.attr('data-masking', res.masking_rate);
                        btn.data('non-masking', res.non_masking_rate);
                        btn.data('masking', res.masking_rate);
                    }

                    // Update show profile page rates if present
                    if ($('#profile-nm-rate').length) {
                        $('#profile-nm-rate').text('৳' + parseFloat(res.non_masking_rate).toFixed(2));
                    }
                    if ($('#profile-m-rate').length) {
                        $('#profile-m-rate').text('৳' + parseFloat(res.masking_rate).toFixed(2));
                    }

                    // Close modal
                    let modalInst = bootstrap.Modal.getInstance(document.getElementById('modal-tariff'));
                    if (modalInst) {
                        modalInst.hide();
                    }
                } else {
                    alert(res.message || '{{ __("Failed to update tariff.") }}');
                }
            },
            error: function(xhr) {
                let msg = '{{ __("Failed to update tariff.") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                $('#btn-submit-tariff-label').text(originalText);
            }
        });
    });
});
</script>
