@extends('layouts.admin.master')

@section('title')
    {{ __('Services List') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-bodys p-16">
                <div class="table-header">
                    <h4>{{ __('Services List') }}</h4>
                    <a href="{{ route('admin.services.create') }}" class="theme-btn print-btn text-light">
                        <i class="far fa-plus" aria-hidden="true"></i>
                        {{ __('Create New') }}
                    </a>
                </div>
                <div class="table-top-form">
                    <form action="{{ route('admin.services.index') }}" method="post">
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
                            @can('services-delete')
                            <th width="10px">
                                <div class="d-flex align-items-center gap-3">
                                    <input type="checkbox" class="selectAllCheckbox">
                                    <i class="fal fa-trash-alt delete-selected"></i>
                                </div>
                            </th>
                            @endcan
                            <th>{{ __('SL') }}.</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="searchResults">
                        @include('admin.website-setting.services.datas')
                    </tbody>
                </table>
            </div>
            <nav>
                <ul class="pagination">
                    <li class="page-item">{{ $services->links('pagination::bootstrap-5') }}</li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')
@endpush
