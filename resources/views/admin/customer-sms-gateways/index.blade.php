@extends('layouts.admin.master')

@section('title')
    {{__('Senderids List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys  p-16">
                    <div class="table-header">
                        <h4>{{ __('Senderids List') }}</h4>
                        <a href="{{ route('admin.senderids.create') }}" class="theme-btn print-btn text-light">
                            <i class="far fa-plus" aria-hidden="true"></i>
                            {{ __('Create New') }}
                        </a>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('admin.senderids.index') }}" method="post">
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
                                @can('senderids-delete')
                                <th class="w-60">
                                    <div class="d-flex align-items-center gap-3" >
                                        <input type="checkbox" class="selectAllCheckbox">
                                        <i class="fal fa-trash-alt delete-selected"></i>
                                    </div>
                                </th>
                                @endcan
                                <th>{{ __('SL') }}.</th>
                                <th>{{ __('Customer Name') }}</th>
                                <th>{{ __('Customer Phone') }}</th>
                                <th>{{ __('Sender Id') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Is Default') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.senderids.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $senderids->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
@push('modal')
    @include('admin.components.multi-delete-modal')
@endpush
