@extends('layouts.users.master')

@section('title')
    {{__('Groups List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header">
                        <h4>{{ __('Groups List') }}</h4>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#create-group-modal" class="theme-btn print-btn text-light">
                            <i class="far fa-plus" aria-hidden="true"></i>
                            {{ __('Add new group') }}
                        </a>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('users.groups.index') }}" method="post">
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
                                <th class="w-60">
                                    <div class="d-flex align-items-center gap-3" >
                                        <input type="checkbox" class="selectAllCheckbox">
                                        <i class="fal fa-trash-alt delete-selected"></i>
                                    </div>
                                </th>
                                <th>{{ __('SL') }}.</th>
                                <th>{{ __('Group Name') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('users.groups.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $groups->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
@push('modal')
    @include('users.components.multi-delete-modal')

    <div class="modal fade" id="create-group-modal" tabindex="-1" aria-labelledby="multi-delete-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Add new group') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.groups.store') }}" method="post" class="ajaxform_instant_reload">
                    @csrf

                    <div class="modal-body pt-0 order-form-section">
                        <div class="row">
                            <div class="col-12 mt-2">
                                <label>{{ __('Group name') }}</label>
                                <input type="text" name="name" required class="form-control" placeholder="{{ __('Enter group name') }}" >
                            </div>
                            <div class="col-12 mt-2">
                                <label>{{ __('Status') }}</label>
                                <div class="gpt-up-down-arrow position-relative">
                                    <select name="status" required="" class="form-control select-dropdown">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactive') }}</option>
                                    </select>
                                    <span></span>
                                </div>
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
