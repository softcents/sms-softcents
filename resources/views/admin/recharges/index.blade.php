@extends('layouts.admin.master')

@section('title')
    {{ __('Recharges List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h4 class="fw-bold mb-0">{{ __('Recharges List') }}</h4>
                        <button type="button" class="theme-btn print-btn text-white btn-open-recharge-modal active" style="background-color: #198754; border-color: #198754;" data-id="" title="{{ __('Recharge Reseller / User') }}">
                            <i class="fas fa-wallet me-1"></i>
                            {{ __('Recharge Reseller / User') }}
                        </button>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('admin.recharges.index') }}" method="post">
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
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Invoice No') }}.</th>
                                <th>{{ __('Payment Gateway') }}</th>
                                <th>{{ __('Recharge Time') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.recharges.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $recharges->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('admin.users.recharge-modal')
    @include('admin.components.approve-reject-modal')
@endpush
