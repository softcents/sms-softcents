@extends('layouts.users.master')

@section('title')
    {{__('Subscriptions List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header">
                        <h4>{{__('Subscribers List')}}</h4>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('users.reports.subscriptions') }}" method="post">
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
                                <th>{{ __('Plan name') }}</th>
                                <th>{{ __('Payment Method') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Total SMS') }}</th>
                                <th>{{ __('Will Expire') }}</th>
                                <th>{{ __('Subscribe At') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.reports.subscribers.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $subscribers->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
