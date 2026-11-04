<div class="modal fade" id="sms-confirm-modal" tabindex="-1" aria-labelledby="smsConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mx-auto" style="max-width: 480px; width: calc(100% - 24px);">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
            {{-- Enterprise Modal Header --}}
            <div class="modal-header py-2.5 px-3 border-bottom" style="background: linear-gradient(135deg, #f8faff 0%, #f1f5f9 100%);">
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-circle text-white d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px; font-size: 13px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-paper-plane"></i>
                    </span>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="smsConfirmModalLabel" style="font-size: 14.5px;">{{ __('Confirm SMS Dispatch') }}</h6>
                        <span class="text-muted" style="font-size: 11px;">{{ __('Review recipient metrics and billing details before dispatch') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 11px;"></button>
            </div>

            <div class="modal-body p-3">
                {{-- Balance Flow Widget --}}
                <div class="p-2.5 rounded-3 border mb-3" style="background: #f8fafc;">
                    <div class="row g-2 align-items-center text-center">
                        <div class="col-4">
                            <span class="small text-muted d-block" style="font-size: 10.5px;">{{ __('Current Balance') }}</span>
                            <strong class="text-dark" id="modal-old-balance" style="font-size: 13.5px;">৳0.00</strong>
                        </div>
                        <div class="col-4 border-start border-end">
                            <span class="small text-danger d-block fw-semibold" style="font-size: 10.5px;">{{ __('Dispatch Cost') }}</span>
                            <strong class="text-danger" style="font-size: 14px;">-৳<span id="modal-total-cost">0.00</span></strong>
                        </div>
                        <div class="col-4">
                            <span class="small text-success d-block fw-semibold" style="font-size: 10.5px;">{{ __('Remaining') }}</span>
                            <strong class="text-success" id="modal-new-balance" style="font-size: 13.5px;">৳0.00</strong>
                        </div>
                    </div>
                </div>

                {{-- Dispatch Specifications Table --}}
                <div class="border rounded-3 p-2.5 mb-3 bg-white" style="font-size: 12px;">
                    <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom">
                        <span class="text-muted"><i class="fas fa-id-badge text-primary me-1.5"></i> {{ __('Sender ID') }}</span>
                        <span class="font-monospace fw-bold text-dark badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" id="modal-sender-id">-</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom">
                        <span class="text-muted"><i class="fas fa-users text-info me-1.5"></i> {{ __('Total Recipients') }}</span>
                        <span class="fw-bold text-dark" id="modal-recipients-count">1</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom">
                        <span class="text-muted"><i class="fas fa-layer-group text-warning me-1.5"></i> {{ __('Parts & Tariff Rate') }}</span>
                        <span>
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle me-1" id="modal-sms-parts">1 Part</span>
                            <span class="text-muted">×</span>
                            <span class="fw-semibold text-dark ms-1" id="modal-sms-rate">৳0.39</span>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-1.5">
                        <span class="text-muted"><i class="fas fa-keyboard text-secondary me-1.5"></i> {{ __('Encoding & Length') }}</span>
                        <span class="text-dark fw-medium" id="modal-char-info">0 Chars (Text)</span>
                    </div>
                </div>

                {{-- Message Preview in Chat Bubble --}}
                <div>
                    <label class="form-label fw-bold text-muted text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.3px;">
                        <i class="fas fa-comment-dots text-primary me-1"></i> {{ __('Message Content Preview') }}
                    </label>
                    <div class="p-2.5 rounded-3 border position-relative" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
                        <div style="max-height: 85px; overflow-y: auto; white-space: pre-wrap; word-break: break-word; font-size: 12px; line-height: 1.45; color: #166534;" id="modal-content-preview">
                        </div>
                        <div class="text-end mt-1">
                            <span class="badge bg-white text-muted border py-0 px-1" style="font-size: 9.5px;">SMS Gateway Dispatch</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Buttons with Loading State --}}
            <div class="modal-footer py-2 px-3 border-top d-flex justify-content-between" style="background: #f8fafc;">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1.5 fw-medium" data-bs-dismiss="modal" style="font-size: 12.5px; border-radius: 6px;">
                    <i class="fas fa-times me-1"></i> {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-sm text-white px-4 py-1.5 fw-bold shadow-sm" id="btn-confirm-send-sms" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 13px; border-radius: 6px;">
                    <i class="fas fa-paper-plane me-1"></i> {{ __('Confirm & Dispatch SMS') }}
                </button>
            </div>
        </div>
    </div>
</div>
