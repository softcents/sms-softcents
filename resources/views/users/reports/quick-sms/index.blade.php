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
                        <form action="{{ route('users.reports.quick-sms') }}" method="post" class="d-flex align-items-center flex-wrap gap-2">
                            @csrf
                            <div class="table-search">
                                <input class="form-control searchInput" type="text" name="search" placeholder="{{ __('Search') }}..." value="{{ request('search') }}">
                            </div>
                            <div>
                                <select name="source" class="form-select form-select-sm" style="height: 38px; border-radius: 6px;" onchange="$(this).closest('form').submit()">
                                    <option value="">{{ __('All Sources (Web & API)') }}</option>
                                    <option value="web" @selected(request('source') == 'web')>{{ __('Web') }}</option>
                                    <option value="api" @selected(request('source') == 'api')>{{ __('API') }}</option>
                                </select>
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
                                <th>{{ __('Source') }}</th>
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
    @include('users.components.sms-report-content-modal')
@endpush

@push('js')
<script>
    $(document).on('click', '.view-full-sms-content', function() {
        let $row = $(this).closest('tr');
        let content = $row.find('.sms-content-template').html() || $(this).data('content') || '';
        let sender = $(this).data('sender') || '-';
        let number = $(this).data('number') || '-';
        let source = $(this).data('source') || 'Web';
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
        if (source.toLowerCase() === 'api') {
            $('#report-modal-source').removeClass('bg-primary text-white').addClass('bg-info text-dark').html('<i class="fas fa-code me-1"></i>API');
        } else {
            $('#report-modal-source').removeClass('bg-info text-dark').addClass('bg-primary text-white').html('<i class="fas fa-globe me-1"></i>Web');
        }
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
