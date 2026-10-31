@extends('layouts.admin.master')

@section('title')
    {{__ ('Currency List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header">
                        <h4>{{__('Currency List')}}</h4>
                        @can('currencies-create')
                            <a href="{{ route('admin.currencies.create') }}" class="add-order-btn rounded-2"><i class="fas fa-plus-circle"></i> {{__('Add Currency')}} </a>
                        @endcan
                    </div>
                    <div class="table-top-form daily-transaction-between-wrapper">
                        <form action="{{ route('admin.currencies.index') }}" method="post" >
                            @csrf
                            <div class="table-search">
                                <input class="form-control searchInput" type="text" name="search" placeholder="{{ __('Search') }}..." value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>

                </div>
                <div class="responsive-table">
                    <table class="table" id="erp-table">
                        <thead>
                        <tr>
                            <th>{{ __('SL') }}.</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Symbol') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Default') }}</th>
                            <th class="print-d-none">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.currencies.datas')
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $currencies->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

