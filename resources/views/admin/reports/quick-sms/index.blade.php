@extends('layouts.admin.master')

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
                        <form action="{{ route('admin.reports.quick-sms') }}" method="post" >
                            @csrf
                            <div class="table-search">
                                <input class="form-control searchInput" type="text" name="search" placeholder="{{ __('Search') }}..." value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="responsive-table" style="overflow-x: auto; width: 100%;">
                    <table class="table table-bordered table-hover text-nowrap align-middle mb-0" id="datatable" style="min-width: 2400px;">
                        <thead class="table-light align-middle">
                            <tr>
                                <th style="width: 50px;">{{ __('SL') }}.</th>
                                <th>{{ __('User Name') }}</th>
                                <th>{{ __('Recipient Number') }}</th>
                                <th>{{ __('Content') }}</th>
                                <th>{{ __('Caller ID') }}</th>
                                <th>{{ __('SMS Type') }}</th>
                                <th class="text-center">{{ __('SMS Count') }}</th>
                                <th>{{ __('Cost') }}</th>
                                <th>{{ __('Previous Balance') }}</th>
                                <th>{{ __('Next Balance') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Failure Reason') }}</th>
                                <th>{{ __('Operator') }}</th>
                                <th>{{ __('Channel') }}</th>
                                <th>{{ __('Submit Time') }}</th>
                                <th>{{ __('Delivery Time') }}</th>
                                <th>{{ __('Tariff') }}</th>
                                <th>{{ __('API User') }}</th>
                                <th>{{ __('Source IP') }}</th>
                                <th>{{ __('Request ID') }}</th>
                                <th>{{ __('Gateway TxnId') }}</th>
                                <th>{{ __('Gateway Response Code') }}</th>
                                <th>{{ __('Infozillion Response') }}</th>
                                <th>{{ __('MNO TxnId') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.reports.quick-sms.datas')
                        </tbody>
                    </table>
                </div>
                <nav class="p-3">
                    <ul class="pagination mb-0">
                        <li class="page-item">{{ $messages->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')
    @include('admin.components.numbers-list-modal')

    <div class="modal fade" id="smsContentModal" tabindex="-1" aria-labelledby="smsContentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smsContentModalLabel">{{ __('Message Content') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3 bg-light rounded text-break" id="smsModalBody" style="white-space: pre-wrap; font-size: 14px; line-height: 1.6;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
<script>
    $(document).on('click', '.view-full-sms-content', function() {
        let content = $(this).data('content');
        $('#smsModalBody').text(content);
        $('#smsContentModal').modal('show');
    });
</script>
@endpush
