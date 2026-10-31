@extends('layouts.users.master')

@section('title')
    {{ __('Recharges List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header">
                        <h4>{{ __('Recharges List') }}</h4>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#recharge-modal" class="theme-btn print-btn text-light">
                            <i class="far fa-plus" aria-hidden="true"></i>
                            {{ __('Make Recharge') }}
                        </a>
                    </div>

                    <div class="table-top-form">
                        <form action="{{ route('users.recharges.index') }}" method="post">
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
                                <th>{{ __('Invoice No') }}.</th>
                                <th>{{ __('Payment Gateway') }}</th>
                                <th>{{ __('Recharge Time') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('users.recharges.datas')
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

@include('admin.components.approve-reject-modal')

<div class="modal fade" id="recharge-modal" tabindex="-1" aria-labelledby="multi-delete-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Make Recharge') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.recharges.store') }}" method="post" class="ajaxform_instant_reload">
                @csrf

                <div class="modal-body pt-0 order-form-section">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <label>{{ __('Recharge Amount') }}</label>
                            <input type="number" step="any" name="amount" required class="form-control" placeholder="{{ __('Enter Recharge Amount') }}">
                        </div>

                        <div class="button-group text-center mt-5">
                            <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                            <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
