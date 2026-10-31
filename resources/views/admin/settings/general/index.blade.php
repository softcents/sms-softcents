@extends('layouts.admin.master')

@section('title')
    {{__('General Settings') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header">
                        <h4>{{__('General Settings')}}</h4>
                    </div>
                    <div class="order-form-section">
                        <form action="{{ route('admin.settings.update', $general->id) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                            @csrf
                            @method('put')
                            <div class="add-suplier-modal-wrapper d-block">
                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <label>{{__('Title')}}</label>
                                        <input type="text" name="title" value="{{ $general->value['title'] ?? '' }}"  required class="form-control" placeholder="{{ __('Enter Title') }}">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('E-mail') }}</label>
                                        <input type="email" name="email" value="{{ $general->value['email'] ?? '' }}" class="form-control" placeholder="{{ __('Enter email') }}">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Phone') }}</label>
                                        <input type="text" name="phone" value="{{ $general->value['phone'] ?? '' }}" class="form-control" placeholder="{{ __('Enter phone') }}">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Copyright') }}</label>
                                        <input type="text" name="copyright" value="{{ $general->value['copyright'] ?? '' }}" class="form-control" placeholder="{{ __('Enter Copyright') }}">
                                    </div>

                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Developed By') }}</label>
                                        <input type="text" name="develop_by" value="{{ $general->value['develop_by'] ?? '' }}" class="form-control" placeholder="{{ __('Enter Developers firm name') }}" >
                                    </div>
                                    <div class="col-lg-6 mt-0">
                                        <label>{{ __('Footer Description') }}</label>
                                        <textarea type="text" name="footer_desc" class="form-control" rows="3" placeholder="{{ __('Enter Footer Short Description.') }}">{{ $general->value['footer_desc'] ?? '' }}</textarea>
                                    </div>

                                    <div class="col-lg-6 settings-image-upload">
                                        <label class="title">{{__('Favicon')}}</label>
                                        <div class="upload-img-v2">
                                            <label class="upload-v4 settings-upload-v4">
                                                <div class="img-wrp">
                                                    <img src="{{ asset($general->value['favicon'] ?? 'assets/images/icons/upload-icon.svg') }}" alt="user" id="favicon">
                                                </div>
                                                <input type="file" name="favicon" class="d-none file-input-change" accept="image/*" data-id="favicon">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 settings-image-upload mt-2">
                                        <label class="title">{{__('Frontend Logo')}}</label>
                                        <div class="upload-img-v2">
                                            <label class="upload-v4 settings-upload-v4">
                                                <div class="img-wrp">
                                                    <img src="{{ asset($general->value['front_logo'] ?? 'assets/images/icons/upload-icon.svg') }}" alt="user" id="front_logo">
                                                </div>
                                                <input type="file" name="front_logo" class="d-none" accept="image/*" class="form-control file-input-change" data-id="front_logo">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 settings-image-upload">
                                        <label class="title">{{__('Backend Logo')}}</label>
                                        <div class="upload-img-v2">
                                            <label class="upload-v4 settings-upload-v4">
                                                <div class="img-wrp">
                                                    <img src="{{ asset($general->value['logo'] ?? 'assets/images/icons/upload-icon.svg') }}" alt="user" id="admin_logo">
                                                </div>
                                                <input type="file" name="logo" class="d-none" accept="image/*" class="form-control file-input-change" data-id="admin_logo">
                                            </label>
                                        </div>
                                    </div>
                                    @can('settings-update')
                                    <div class="col-lg-12">
                                        <div class="text-center mt-5">
                                            <button type="submit" class="theme-btn m-2 submit-btn">{{__('Update')}}</button>
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


