<div class="modal fade" id="sms-confirm-modal" tabindex="-1" aria-labelledby="smsConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
        <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
            {{-- Modal Header --}}
            <div class="modal-header bg-light py-2 px-3 border-bottom">
                <h6 class="modal-title fw-bold d-flex align-items-center mb-0" id="smsConfirmModalLabel">
                    <span class="rounded-circle bg-primary text-white p-1 d-inline-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 13px;">
                        <i class="fas fa-paper-plane"></i>
                    </span>
                    <span>{{ __('Confirm SMS Dispatch') }}</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 11px;"></button>
            </div>

            <div class="modal-body p-3">
                {{-- Old Balance vs New Balance --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-2 text-center border">
                            <span class="small text-muted d-block" style="font-size: 11px;">{{ __('Old Balance') }}</span>
                            <span class="fw-bold text-dark fs-6" id="modal-old-balance">৳0.00</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center border" style="background-color: #f0fdf4; border-color: #bbf7d0 !important;">
                            <span class="small text-success d-block fw-semibold" style="font-size: 11px;">{{ __('New Balance') }}</span>
                            <span class="fw-bold text-success fs-6" id="modal-new-balance">৳0.00</span>
                        </div>
                    </div>
                </div>

                {{-- Compact Details List --}}
                <div class="border rounded-2 p-2 mb-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <span class="text-muted small"><i class="fas fa-coins text-warning me-1"></i> {{ __('Total Charge') }}</span>
                        <span class="fw-bold text-danger fs-6">৳<span id="modal-total-cost">0.00</span></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <span class="text-muted small"><i class="fas fa-id-badge text-primary me-1"></i> {{ __('Sender ID') }}</span>
                        <span class="font-monospace fw-semibold text-dark small" id="modal-sender-id">-</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <span class="text-muted small"><i class="fas fa-users text-info me-1"></i> {{ __('Recipients') }}</span>
                        <span class="fw-semibold text-dark small" id="modal-recipients-count">1</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <span class="text-muted small"><i class="fas fa-layer-group text-secondary me-1"></i> {{ __('Parts & Rate') }}</span>
                        <span class="small">
                            <span class="badge bg-warning text-dark me-1" id="modal-sms-parts">1 Part</span>
                            <span class="text-muted">×</span>
                            <span class="fw-semibold text-dark ms-1" id="modal-sms-rate">৳0.39</span>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-muted small"><i class="fas fa-keyboard text-muted me-1"></i> {{ __('Characters & Type') }}</span>
                        <span class="small text-dark" id="modal-char-info">0 Chars (Text)</span>
                    </div>
                </div>

                {{-- Message Preview --}}
                <div class="mb-1">
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 11px;">
                        <i class="fas fa-comment-dots me-1 text-primary"></i> {{ __('Content Preview') }}
                    </label>
                    <div class="p-2 bg-light rounded-2 border text-dark" style="max-height: 85px; overflow-y: auto; white-space: pre-wrap; word-break: break-word; font-size: 12.5px; line-height: 1.4;" id="modal-content-preview">
                    </div>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div class="modal-footer bg-light py-2 px-3 border-top d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-sm btn-primary px-3 fw-semibold" id="btn-confirm-send-sms">
                    <i class="fas fa-paper-plane me-1"></i> {{ __('Confirm & Send') }}
                </button>
            </div>
        </div>
    </div>
</div>
