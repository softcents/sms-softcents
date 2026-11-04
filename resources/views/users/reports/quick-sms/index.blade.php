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
        <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
            <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header bg-light py-2 px-3 border-bottom">
                    <h6 class="modal-title fw-bold d-flex align-items-center mb-0" id="smsContentModalLabel">
                        <span class="rounded-circle bg-primary text-white p-1 d-inline-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 13px;">
                            <i class="fas fa-comment-dots"></i>
                        </span>
                        <span>{{ __('SMS Content & Delivery Details') }}</span>
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 11px;"></button>
                </div>
                <div class="modal-body p-3">
                    {{-- Mini Details Grid --}}
                    <div class="row g-2 mb-3 small">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded border">
                                <span class="text-muted d-block" style="font-size: 11px;">{{ __('Recipient') }}</span>
                                <strong class="text-dark font-monospace" id="modal-sms-number">-</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded border">
                                <span class="text-muted d-block" style="font-size: 11px;">{{ __('Sender ID') }}</span>
                                <strong class="text-dark font-monospace" id="modal-sms-sender">-</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded border">
                                <span class="text-muted d-block" style="font-size: 11px;">{{ __('Parts & Cost') }}</span>
                                <span><strong id="modal-sms-parts" class="badge bg-warning text-dark me-1">1 SMS</strong> <span class="text-danger fw-bold" id="modal-sms-charge">0.39৳</span></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded border">
                                <span class="text-muted d-block" style="font-size: 11px;">{{ __('Date & Time') }}</span>
                                <strong class="text-dark" id="modal-sms-time" style="font-size: 11px;">-</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Content Text Area --}}
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fw-bold text-muted text-uppercase mb-0" style="font-size: 11px;">
                            <i class="fas fa-align-left text-primary me-1"></i> {{ __('Full Message Content') }}
                        </label>
                        <span class="badge bg-light text-secondary border" id="modal-sms-charcount" style="font-size: 11px;">0 Characters</span>
                    </div>
                    <div class="p-3 bg-light rounded-2 border text-break text-dark" id="smsModalBody" style="white-space: pre-wrap; font-size: 13.5px; line-height: 1.5; max-height: 250px; overflow-y: auto; user-select: all;"></div>
                </div>
                <div class="modal-footer bg-light py-2 px-3 border-top d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-copy-sms-content">
                        <i class="far fa-copy me-1"></i> <span id="copy-btn-text">{{ __('Copy Content') }}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
    $(document).on('click', '.view-full-sms-content', function() {
        let content = $(this).data('content') || '';
        let sender = $(this).data('sender') || '-';
        let number = $(this).data('number') || '-';
        let time = $(this).data('time') || '-';
        let parts = $(this).data('parts') || 1;
        let charge = $(this).data('charge') || '0.39৳';
        let isUnicode = $(this).data('unicode') == 1;

        $('#modal-sms-number').text(number);
        $('#modal-sms-sender').text(sender);
        $('#modal-sms-parts').text(parts + ' SMS');
        $('#modal-sms-charge').text(charge);
        $('#modal-sms-time').text(time);
        $('#modal-sms-charcount').text(content.length + ' Chars (' + (isUnicode ? 'Unicode' : 'Text') + ')');
        $('#smsModalBody').text(content);

        $('#copy-btn-text').text("{{ __('Copy Content') }}");
        $('#smsContentModal').modal('show');
    });

    $(document).on('click', '#btn-copy-sms-content', function() {
        let content = $('#smsModalBody').text();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(content).then(function() {
                $('#copy-btn-text').text("{{ __('Copied!') }}");
                setTimeout(function() {
                    $('#copy-btn-text').text("{{ __('Copy Content') }}");
                }, 2000);
            });
        }
    });
</script>
@endpush
