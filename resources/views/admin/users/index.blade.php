@extends('layouts.admin.master')

@section('title')
    {{ __(request('users') == 'admin' ? __('Admin') : __('Customer') . 'List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header">
                        <h4 class="mt-2">{{ __(request('users') == 'admin' ? __('Staff List') : __('Customer List')) }}</h4>
                        @include('admin.users.buttons')
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('admin.users.index') }}" method="get">
                            <input type="hidden" name="users" value="{{ request('users', 'user') }}">
                            @if (request()->filled('low_balance'))
                                <input type="hidden" name="low_balance" value="{{ request('low_balance') }}">
                            @endif
                            <div class="table-search">
                                <input class="form-control searchInput" type="text" name="search" placeholder="{{ __('Search') }}..." value="{{ request('search') }}" autocomplete="off">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="responsive-table">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                @can('users-delete')
                                    <th class="w-60">
                                        <div class="d-flex align-items-center gap-3" >
                                            <input type="checkbox" class="selectAllCheckbox">
                                            <i class="fal fa-trash-alt delete-selected"></i>
                                        </div>
                                    </th>
                                @endcan
                                <th>{{ __('SL') }}.</th>
                                <th>{{ __('Image') }}</th>
                                <th>{{ __('Name / Username') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('E-mail') }}</th>
                                @if (request('users', 'user') != 'admin')
                                <th>{{ request('users') == 'user' ? __('Tariff / Rates') : __('Role / Parent') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('Created At') }}</th>
                                @endif
                                <th class="print-d-none">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.users.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $users->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="modal fade" id="User-view">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">{{__('View User')}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body order-form-section">
                    <div class="costing-list">
                        <ul>
                            <li><span>{{__('Full Name')}}</span> <span>:</span> <span id="name"></span></li>
                            <li><span>{{__('Username')}}</span> <span>:</span> <span id="username" class="fw-bold text-primary font-monospace"></span></li>
                            <li><span>{{__('Phone')}}</span> <span>:</span> <span id="phone"></span></li>
                            <li><span>{{__('Email')}}</span> <span>:</span> <span id="email"></span></li>
                            <li><span>{{__('Address')}} </span> <span>:</span> <span id="address"></span></li>
                            <li><span>{{__('Remarks')}}</span> <span>:</span> <span id="remarks"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    tr.clickable-user-row td {
        transition: background-color 0.15s ease;
    }
    tr.clickable-user-row:hover td {
        background-color: #f1f5f9 !important;
    }
</style>
@endpush

@push('modal')
    @include('admin.users.recharge-modal')
    @include('admin.users.tariff-modal')
    @include('admin.components.multi-delete-modal')
@endpush

@push('js')
<script>
    $(document).on('click', 'tr.clickable-user-row td', function(e) {
        if ($(e.target).closest('input, button, .dropdown, .print-d-none, .btn').length > 0) {
            return;
        }
        let url = $(this).closest('tr').data('href');
        if (url) {
            window.location.href = url;
        }
    });
</script>
@endpush
