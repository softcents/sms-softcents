@extends('layouts.admin.master')

@section('title')
    {{ __('Edit Campaign') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header mb-4">
                        <h4>{{ __('Edit Campaign') }}</h4>
                        <a href="{{ route('admin.gateways-types.index') }}" class="theme-btn print-btn text-light">
                            <i class="fas fa-plus-circle me-1"></i>
                            {{ __('Manage List') }}
                        </a>
                    </div>
                    <div class="tab-content order-summary-tab">
                        <div class="tab-pane fade mt-1 show active" id="add-new-user">
                            <div class="order-form-section">
                                <form action="{{ route('admin.gateways-types.update', $type->id) }}" method="post" class="ajaxform_instant_reload">
                                    @csrf
                                    @method('put')

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">
                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('Name')}}</label>
                                                <input type="text" name="name" required class="form-control" value="{{ $type->name }}" placeholder="{{ __('Enter Name') }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('Driver')}}</label>
                                                <input type="text" name="driver" required class="form-control" value="{{ $type->driver }}" placeholder="{{ __('Enter driver name') }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('Status')}}</label>
                                                <select name="status" required class="form-control table-select w-100">
                                                    <option @selected($type->status == 0) value="1">{{__('Active')}}</option>
                                                    <option @selected($type->status == 0) value="0">{{__('Inactive')}}</option>
                                                </select>
                                            </div>

                                            <div class="col-12 mt-2">
                                                <div class="manual-rows">
                                                    <div class="row row-items">
                                                        @foreach ($type->inputs['names'] ?? [] as $key => $name)
                                                        <div class="col-sm-5">
                                                            <label for="">{{ __('Label') }}</label>
                                                            <input type="text" name="labels[]" class="form-control" required placeholder="{{ __('Enter label name') }}" value="{{ $type->inputs['labels'][$key] ?? '' }}">
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <label for="">{{ __('Input Name') }}</label>
                                                            <input type="text" name="inputs[]" class="form-control" required placeholder="{{ __('Enter input name') }}" value="{{ $name ?? '' }}">
                                                        </div>
                                                        <div class="col-sm-2 align-self-center mt-3">
                                                            <button type="button" class="btn text-danger trash remove-input"><i class="fas fa-trash"></i></button>
                                                        </div>
                                                        @endforeach
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
                                    </div>
                                </form>
                                {{-- form end --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
