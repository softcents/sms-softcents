@extends('layouts.admin.master')

@section('title')
    {{ __('Transactions List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <h4 class="fw-bold">{{ __('Transactions List') }}</h4>
                    <div class="table-top-form">
                        <form action="{{ route('admin.reports.transactions') }}" method="post">
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
                                <th>{{ __('Reason') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.reports.transactions.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $transactions->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
