<div class="modal fade" id="sms-confirm-modal" tabindex="-1" aria-labelledby="smsConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow border-0" style="border-radius: 14px; overflow: hidden;">
            {{-- Modal Header --}}
            <div class="modal-header bg-light py-3 border-bottom">
                <h5 class="modal-title fw-bold d-flex align-items-center mb-0" id="smsConfirmModalLabel">
                    <span class="rounded-circle bg-primary text-white p-2 d-inline-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; font-size: 15px;">
                        <i class="fas fa-paper-plane"></i>
                    </span>
                    <span>{{ __('SMS Confirmation & Cost Details') }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Total Cost & Balance Alert Banner --}}
                <div class="alert alert-primary d-flex align-items-center justify-content-between p-3 mb-4 rounded-3 border-0 shadow-sm" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white text-primary p-3 shadow-sm me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 20px;">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase d-block">{{ __('Total Estimated Charge') }}</span>
                            <h3 class="mb-0 fw-bold text-primary">৳<span id="modal-total-cost">0.00</span></h3>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="text-muted small d-block">{{ __('Current Balance:') }} <strong>{{ currency_format(auth()->user()->balance) }}</strong></span>
                        <span class="small d-block text-secondary">{{ __('Estimated Remaining:') }} <strong class="text-success" id="modal-remain-balance">৳0.00</strong></span>
                    </div>
                </div>

                {{-- Key Parameters 4-Box Grid --}}
                <div class="row g-3 mb-3">
                    {{-- Sender ID --}}
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded-3 text-center border h-100">
                            <span class="text-muted small d-block mb-1"><i class="fas fa-id-badge text-primary me-1"></i> {{ __('Sender ID') }}</span>
                            <span class="fw-bold font-monospace text-dark fs-6" id="modal-sender-id">-</span>
                        </div>
                    </div>

                    {{-- Total Recipients --}}
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded-3 text-center border h-100">
                            <span class="text-muted small d-block mb-1"><i class="fas fa-users text-info me-1"></i> {{ __('Recipients') }}</span>
                            <span class="fw-bold fs-6 text-dark" id="modal-recipients-count">1</span>
                        </div>
                    </div>

                    {{-- SMS Parts per Recipient --}}
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded-3 text-center border h-100">
                            <span class="text-muted small d-block mb-1"><i class="fas fa-layer-group text-warning me-1"></i> {{ __('SMS Parts') }}</span>
                            <span class="badge bg-warning text-dark fs-6" id="modal-sms-parts">1 Part</span>
                        </div>
                    </div>

                    {{-- Rate per SMS --}}
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded-3 text-center border h-100">
                            <span class="text-muted small d-block mb-1"><i class="fas fa-tag text-success me-1"></i> {{ __('Rate / SMS') }}</span>
                            <span class="fw-bold text-success fs-6" id="modal-sms-rate">৳0.39</span>
                        </div>
                    </div>
                </div>

                {{-- Encoding, Character Count & Unit Cost Row --}}
                <div class="card bg-white border rounded-3 p-3 mb-3 shadow-none">
                    <div class="row g-2 small text-secondary align-items-center">
                        <div class="col-6 col-md-4">
                            <strong>{{ __('SMS Encoding:') }}</strong>
                            <span class="badge bg-light text-dark border ms-1" id="modal-sms-type">{{ __('Text (English)') }}</span>
                        </div>
                        <div class="col-6 col-md-4">
                            <strong>{{ __('Total Characters:') }}</strong>
                            <span class="fw-semibold text-dark ms-1" id="modal-char-count">0</span>
                        </div>
                        <div class="col-12 col-md-4">
                            <strong>{{ __('Cost / Recipient:') }}</strong>
                            <span class="fw-bold text-danger ms-1">৳<span id="modal-unit-cost">0.39</span></span>
                        </div>
                    </div>
                </div>

                {{-- Destination Numbers Preview --}}
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted text-uppercase mb-1">
                        <i class="fas fa-mobile-alt me-1 text-primary"></i> {{ __('Destination Numbers') }} (<span id="modal-num-summary">1 number</span>)
                    </label>
                    <div class="p-2 bg-light rounded-2 font-monospace small border text-dark" style="max-height: 80px; overflow-y: auto;" id="modal-numbers-preview">
                    </div>
                </div>

                {{-- Message Content Preview Box --}}
                <div class="mb-2">
                    <label class="form-label fw-bold small text-muted text-uppercase mb-1">
                        <i class="fas fa-comment-dots me-1 text-primary"></i> {{ __('Message Content') }}
                    </label>
                    <div class="p-3 bg-light rounded-3 border text-dark" style="max-height: 140px; overflow-y: auto; white-space: pre-wrap; word-break: break-word; font-size: 13.5px;" id="modal-content-preview">
                    </div>
                </div>
            </div>

            {{-- Modal Footer with Action Buttons --}}
            <div class="modal-footer bg-light py-3 border-top d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('Edit Message') }}
                </button>
                <button type="button" class="btn btn-primary px-4 py-2 fw-semibold" id="btn-confirm-send-sms">
                    <i class="fas fa-paper-plane me-1"></i> {{ __('Confirm & Send Now') }}
                </button>
            </div>
        </div>
    </div>
</div>
