@extends('layouts.users.master')

@section('title')
    {{ __('SMS Histories') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header d-flex justify-content-between">
                        <h4>{{ __('SMS Histories') }}</h4>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('users.reports.quick-sms') }}" method="post">
                            @csrf
                            <div class="table-search">
                                <input class="form-control searchInput" type="text" name="search" placeholder="{{ __('Search') }}..." value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>


                </div>
                <div class="responsive-table">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>{{ __('SL') }}.</th>
                                <th>{{ __('Sender ID') }}</th>
                                <th>{{ __('Number') }}</th>
                                <th>{{ __('Contents') }}</th>
                                <th class="text-center">{{ __('SMS Count') }}</th>
                                <th>{{ __('Cost') }}</th>
                                <th>{{ __('Date & Time') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('users.reports.quick-sms.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $messages->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')

    {{-- Full SMS Content Details Modal --}}
    <div class="modal fade" id="smsContentModal" tabindex="-1" aria-labelledby="smsContentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
                {{-- Header --}}
                <div class="modal-header bg-light py-2 px-3 border-bottom">
                    <h6 class="modal-title fw-bold d-flex align-items-center mb-0" id="smsContentModalLabel">
                        <span class="rounded-circle bg-primary text-white p-1 d-inline-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 13px;">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <span>{{ __('SMS Details & Cost Balance') }}</span>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 11px;"></button>
                </div>

                <div class="modal-body p-3">
                    {{-- 1. Balance Summary Card: Old Balance, Cost, New Balance --}}
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="p-2 bg-light rounded-2 text-center border h-100">
                                <span class="small text-muted d-block text-uppercase fw-semibold" style="font-size: 10px;">{{ __('Old Balance') }}</span>
                                <span class="fw-bold text-dark fs-6" id="report-modal-old-balance">৳0.00</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded-2 text-center border h-100" style="background-color: #fff1f2; border-color: #fecdd3 !important;">
                                <span class="small text-danger d-block text-uppercase fw-semibold" style="font-size: 10px;">{{ __('Cost / Charge') }}</span>
                                <span class="fw-bold text-danger fs-6" id="report-modal-cost">৳0.00</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded-2 text-center border h-100" style="background-color: #f0fdf4; border-color: #bbf7d0 !important;">
                                <span class="small text-success d-block text-uppercase fw-semibold" style="font-size: 10px;">{{ __('New Balance') }}</span>
                                <span class="fw-bold text-success fs-6" id="report-modal-new-balance">৳0.00</span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Parameters List --}}
                    <div class="border rounded-2 p-2 mb-3 bg-white">
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span class="text-muted small"><i class="fas fa-keyboard text-primary me-1"></i> {{ __('Characters & Type') }}</span>
                            <span class="fw-bold text-dark small" id="report-modal-chars-type">0 Chars (Text)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span class="text-muted small"><i class="fas fa-layer-group text-warning me-1"></i> {{ __('SMS Parts (Count)') }}</span>
                            <span class="badge bg-warning text-dark" id="report-modal-parts">1 SMS</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span class="text-muted small"><i class="fas fa-id-badge text-info me-1"></i> {{ __('Sender ID') }}</span>
                            <span class="font-monospace fw-semibold text-dark small" id="report-modal-sender">-</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span class="text-muted small"><i class="fas fa-mobile-alt text-success me-1"></i> {{ __('Recipient Number') }}</span>
                            <span class="font-monospace fw-semibold text-dark small" id="report-modal-number">-</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1">
                            <span class="text-muted small"><i class="far fa-clock text-secondary me-1"></i> {{ __('Date & Time') }}</span>
                            <span class="text-muted small" id="report-modal-time">-</span>
                        </div>
                    </div>

                    {{-- 3. Message Content Box --}}
                    <div class="mb-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold text-muted text-uppercase mb-0" style="font-size: 11px;">
                                <i class="fas fa-comment-dots text-primary me-1"></i> {{ __('Full Message Content') }}
                            </label>
                            <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" id="btn-copy-sms-content" style="font-size: 11px;">
                                <i class="far fa-copy me-1"></i> <span id="copy-btn-text">{{ __('Copy') }}</span>
                            </button>
                        </div>
                        <div class="p-2 bg-light rounded-2 border text-dark" id="reportModalBody" style="white-space: pre-wrap; word-break: break-word; font-size: 12.5px; line-height: 1.45; max-height: 130px; overflow-y: auto; user-select: all;"></div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light py-2 px-3 border-top d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary px-4" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
    $(document).on('click', '.view-full-sms-content', function() {
        let $row = $(this).closest('tr');
        let content = $row.find('.sms-content-template').html() || $(this).data('content') || '';
        let sender = $(this).data('sender') || '-';
        let number = $(this).data('number') || '-';
        let time = $(this).data('time') || '-';
        let parts = $(this).data('parts') || '1 SMS';
        let charge = $(this).data('charge') || '0.00৳';
        let oldBalance = $(this).data('old-balance') || '৳0.00';
        let newBalance = $(this).data('new-balance') || '৳0.00';
        let charsType = $(this).data('chars-type') || '0 Chars';

        $('#report-modal-old-balance').text(oldBalance);
        $('#report-modal-cost').text(charge);
        $('#report-modal-new-balance').text(newBalance);

        $('#report-modal-chars-type').text(charsType);
        $('#report-modal-parts').text(parts);
        $('#report-modal-sender').text(sender);
        $('#report-modal-number').text(number);
        $('#report-modal-time').text(time);

        $('#reportModalBody').text(content);
        $('#copy-btn-text').text("{{ __('Copy') }}");

        $('#smsContentModal').modal('show');
    });

    $(document).on('click', '#btn-copy-sms-content', function() {
        let content = $('#reportModalBody').text();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(content).then(function() {
                $('#copy-btn-text').text("{{ __('Copied!') }}");
                setTimeout(function() {
                    $('#copy-btn-text').text("{{ __('Copy') }}");
                }, 2000);
            });
        }
    });
</script>
@endpush
