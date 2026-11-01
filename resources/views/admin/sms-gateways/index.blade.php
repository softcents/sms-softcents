@extends('layouts.admin.master')

@section('title')
    {{ __("SMS Gateways") }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4>{{ __("SMS Gateways & Live Balance") }}</h4>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="theme-btn btn-sm text-light" id="checkAllBalancesBtn" style="background:#0d6efd; border:none; padding:8px 16px; border-radius:4px; font-weight:500;">
                                <i class="fas fa-sync-alt me-1" id="refreshAllIcon"></i> {{ __("Check Live Balances") }}
                            </button>
                            <a href="{{ route('admin.sms-gateways.create') }}" class="theme-btn print-btn text-light">
                                <i class="far fa-plus" aria-hidden="true"></i>
                                {{ __("Add New") }}
                            </a>
                        </div>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('admin.sms-gateways.index',['type' => request('type')]) }}" method="post">
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
                                @can('smsgateways-delete')
                                    <th class="w-60">
                                        <div class="d-flex align-items-center gap-3" >
                                            <input type="checkbox" class="selectAllCheckbox">
                                            <i class="fal fa-trash-alt delete-selected"></i>
                                        </div>
                                    </th>
                                @endcan
                                <th>{{ __('SL') }}.</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Gateway Type') }}</th>
                                <th>{{ __('Priority') }}</th>
                                <th>{{ __('Purchase Rate (NM / M)') }}</th>
                                <th class="text-center">{{ __('Live Balance') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.sms-gateways.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $gateways->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        const checkAllUrl = "{{ route('admin.sms-gateways.all-balances') }}";

        function updateGatewayBadge(gatewayId, data) {
            const badge = $('.balance-badge-' + gatewayId);
            const textSpan = badge.find('.balance-text');

            badge.removeClass('bg-secondary bg-success bg-info bg-warning bg-danger text-dark');

            if (data.success) {
                if (data.balance !== null && data.balance !== undefined) {
                    badge.addClass('bg-success');
                    textSpan.text(data.formatted || ('৳' + parseFloat(data.balance).toFixed(2)));
                } else {
                    badge.addClass('bg-info');
                    textSpan.text(data.formatted || 'Connected');
                }
                badge.attr('title', data.message || 'Balance active');
            } else {
                badge.addClass('bg-warning text-dark');
                textSpan.text(data.formatted || 'Error');
                badge.attr('title', data.message || 'Balance check failed');
            }
        }

        // Single Balance Check
        $(document).on('click', '.check-single-balance', function(e) {
            e.preventDefault();
            const btn = $(this);
            const gatewayId = btn.data('id');
            const url = btn.data('url');
            const icon = btn.find('i');
            const badge = $('.balance-badge-' + gatewayId);
            const textSpan = badge.find('.balance-text');

            icon.addClass('fa-spin');
            textSpan.text('Checking...');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    updateGatewayBadge(gatewayId, res);
                },
                error: function(xhr) {
                    badge.removeClass('bg-secondary bg-success bg-info').addClass('bg-danger');
                    textSpan.text('Failed');
                    badge.attr('title', 'Server connection error');
                },
                complete: function() {
                    icon.removeClass('fa-spin');
                }
            });
        });

        // Check All Balances
        function loadAllBalances() {
            const refreshIcon = $('#refreshAllIcon');
            refreshIcon.addClass('fa-spin');

            $('.gateway-balance-cell').each(function() {
                const id = $(this).data('id');
                $('.balance-badge-' + id).find('.balance-text').text('Checking...');
            });

            $.ajax({
                url: checkAllUrl,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res && res.gateways) {
                        $.each(res.gateways, function(id, data) {
                            updateGatewayBadge(id, data);
                        });
                    }
                },
                complete: function() {
                    refreshIcon.removeClass('fa-spin');
                }
            });
        }

        $('#checkAllBalancesBtn').on('click', function(e) {
            e.preventDefault();
            loadAllBalances();
        });

        // Automatically load balances on page load
        loadAllBalances();
    });
</script>
@endpush
