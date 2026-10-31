@extends('layouts.admin.master')

@section('title')
    {{ __('Notice Alert') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header">
                        <h4>{{__('Notice Alert')}}</h4>
                    </div>
                    <div class="order-form-section">
                        <form action="{{ route('admin.notice.store') }}" method="post" class="ajaxform">
                            @csrf

                            <div class="add-suplier-modal-wrapper d-block">
                                <div class="row">
                                    <div class="col-sm-6 mt-2">
                                        <label>{{ __('Notice Title') }}</label>
                                        <input type="text" name="title" value="{{ $notice->value['title'] ?? '' }}" required class="form-control" placeholder="{{ __('Enter Notice Title') }}">
                                    </div>

                                    <div class="col-sm-6 mt-2">
                                        <label for="status">{{ __('Status') }}</label>
                                        <select name="status" id="status" required class="form-control">
                                            <option @selected(isset($notice) && $notice->value['status'] == 1) value="1">{{ __("Active") }}</option>
                                            <option @selected(isset($notice) && $notice->value['status'] == 0) value="0">{{ __("Deactive") }}</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-12 mt-2">
                                        <label>{{ __('Notice Description') }}</label>
                                        <textarea name="description" required class="form-control" rows="5" placeholder="{{ __('Enter Notice Description') }}">{{ $notice->value['description'] ?? '' }}</textarea>
                                    </div>

                                    @can('notice-update')
                                    <div class="col-lg-12">
                                        <div class="text-center mt-5">
                                            <button type="submit" class="theme-btn m-2 submit-btn">{{ __('Update') }}</button>
                                        </div>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


