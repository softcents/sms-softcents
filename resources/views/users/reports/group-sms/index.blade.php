@extends('layouts.users.master')

@section('title')
{{ __('Group Sms History') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header d-flex justify-content-between">
                        <h4>{{ __('Group Sms History') }}</h4>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('users.reports.group-sms') }}" method="post">
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
                                <th>{{ __('Senderid ID') }}</th>
                                <th>{{ __('Number') }}</th>
                                <th>{{ __('SMS Count') }}</th>
                                <th>{{ __('Cost') }}</th>
                                <th>{{ __('Contents') }}</th>
                                <th>{{ __('Date & Time') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('users.reports.group-sms.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $groups->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('modal')
@include('users.components.sms-report-content-modal')
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
