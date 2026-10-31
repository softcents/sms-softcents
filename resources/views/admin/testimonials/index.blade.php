@extends('layouts.admin.master')

@section('title')
    {{ __('Testimonials List') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-bodys p-16">
                <div class="table-header">
                    <h4>{{ __('Testimonials List') }}</h4>
                    <a href="{{ route('admin.testimonials.create') }}" class="theme-btn print-btn text-light">
                        <i class="far fa-plus" aria-hidden="true"></i>
                        {{ __('Create New') }}
                    </a>
                </div>
                <div class="table-top-form">
                    <form action="{{ route('admin.testimonials.index') }}" method="post">
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
                            @can('testimonials-delete')
                            <th class="w-60">
                                <div class="d-flex align-items-center gap-3" >
                                    <input type="checkbox" class="selectAllCheckbox">
                                    <i class="fal fa-trash-alt delete-selected"></i>
                                </div>
                            </th>
                            @endcan
                            <th>{{ __('SL') }}.</th>
                            <th>{{ __('Satisfy About') }}</th>
                            <th>{{ __('Stars') }}</th>
                            <th>{{ __('Client Name') }}</th>
                            <th>{{ __('Client Image') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="searchResults">
                        @include('admin.testimonials.datas')
                    </tbody>
                </table>
            </div>
            <nav>
                <ul class="pagination">
                    <li class="page-item">{{ $testimonials->links('pagination::bootstrap-5') }}</li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')
@endpush
