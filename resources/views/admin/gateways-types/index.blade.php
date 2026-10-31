@extends('layouts.admin.master')

@section('title')
    {{ __('Gateways Types') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys p-16">
                    <div class="table-header">
                        <h4>{{ __('Gateways Types') }}</h4>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#gateway-types-modal" class="theme-btn print-btn text-light">
                            <i class="far fa-plus" aria-hidden="true"></i>
                            {{ __('Add New') }}
                        </a>
                    </div>
                    <div class="table-top-form">
                        <form action="{{ route('admin.gateways-types.index') }}" method="post">
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
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Created At') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="searchResults">
                            @include('admin.gateways-types.datas')
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination">
                        <li class="page-item">{{ $types->links('pagination::bootstrap-5') }}</li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('admin.components.multi-delete-modal')

    <div class="modal fade" id="gateway-types-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content order-form-section">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create new gateway</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.gateways-types.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                        @csrf

                        <div class="row">
                            <div class="col-lg-12 mt-2">
                                <label>{{__('Name')}}</label>
                                <input type="text" name="name" required class="form-control" placeholder="{{ __('Enter Name') }}">
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label>{{__('Driver')}}</label>
                                <input type="text" name="driver" required class="form-control" placeholder="{{ __('Enter driver name') }}">
                            </div>
                            <div class="col-lg-12 mt-2">
                                <label>{{__('Status')}}</label>
                                <select name="status" required class="form-control table-select w-100">
                                    <option value="1">{{__('Active')}}</option>
                                    <option value="0">{{__('Inactive')}}</option>
                                </select>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="manual-rows">
                                    <div class="row row-items">
                                        <div class="col-sm-5">
                                            <label for="">{{ __('Label') }}</label>
                                            <input type="text" name="labels[]" class="form-control" required placeholder="{{ __('Enter label name') }}">
                                        </div>
                                        <div class="col-sm-5">
                                            <label for="">{{ __('Input Name') }}</label>
                                            <input type="text" name="inputs[]" class="form-control" required placeholder="{{ __('Enter input name') }}">
                                        </div>
                                        <div class="col-sm-2 align-self-center mt-3">
                                            <button type="button" class="btn text-danger trash remove-input"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-2">
                                        <a href="javascript:void(0)" class="fw-bold text-primary add-new-input"><i class="fas fa-plus-circle me-1"></i>{{ __('Add new row') }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="button-group text-center mt-5">
                                    <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                    <button class="theme-btn m-2 submit-btn">{{__('Save')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
