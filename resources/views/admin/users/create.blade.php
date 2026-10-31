@extends('layouts.admin.master')

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header">
                        <h4>{{ request('users') == 'admin' ? __('Add New Staff') : __('Add New Customer') }}</h4>
                        @include('admin.users.buttons')
                    </div>

                    <div class="order-form-section">
                        {{-- form start --}}
                        <form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                            @csrf

                            <div class="add-suplier-modal-wrapper">
                                <div class="row">
                                    <div class="col-lg-6 mt-2">
                                        <label>{{('Full Name')}}</label>
                                        <input type="text" name="name" required class="form-control" placeholder="{{ __('Enter Name') }}" >
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('E-mail') }}</label>
                                        <input type="email" name="email"  autocomplete="off" required class="form-control" placeholder="{{ __('Enter Email Address') }}" >
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Phone')}}</label>
                                        <input type="text" name="phone" class="form-control" placeholder="{{ __('Enter Phone Number') }}" >
                                    </div>
                                    <div class="col-lg-6 mt-1">
                                        <div class="row">
                                            <div class="col-10">
                                                <label>{{ __('Image') }}</label>
                                                <input type="file" accept="image/*" name="image" class="form-control file-input-change" data-id="image">
                                            </div>
                                            <div class="col-2 align-self-center mt-3">
                                                <img src="{{ asset('assets/images/icons/upload.png') }}" id="image" class="table-img">
                                            </div>
                                        </div>
                                    </div>
                                    @if (request('users') == 'admin')
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Role')}}</label>
                                        <div class="blade-up-down-arrow position-relative">
                                            <select name="role" required class="select-2 form-control w-100" >
                                                <option value=""> {{__('Select a role')}}</option>
                                                @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" @selected(request('users') == $role->name)> {{ ucfirst($role->name) }} </option>
                                                @endforeach
                                            </select>
                                            <span></span>
                                        </div>
                                    </div>
                                    @else
                                    <input type="hidden" name="role" value="{{ request('users') }}">
                                    @endif

                                    @if (request('users') == 'user')
                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Plans') }}</label>
                                        <div class="blade-up-down-arrow position-relative">
                                            <select name="plan_id" class="select-2 form-control w-100" >
                                                <option value=""> {{__('Select a plan')}}</option>
                                                @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}"> {{ $plan->title }} </option>
                                                @endforeach
                                            </select>
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Balance')}}</label>
                                        <input type="number" step="any" name="balance" class="form-control" placeholder="{{ __('Enter balance here') }}">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Low balance alert')}}</label>
                                        <input type="number" step="any" name="low_blnc_alrt" class="form-control" placeholder="{{ __('Enter low balance alert') }}">
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label>{{ __('Status') }}</label>
                                        <div class="gpt-up-down-arrow position-relative">
                                            <select name="status" required="" class="form-control select-dropdown">
                                                <option value="active" selected>{{ __('Active') }}</option>
                                                <option value="pending">{{ __('Pending') }}</option>
                                                <option value="suspend">{{ __('Suspend') }}</option>
                                            </select>
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-2">
                                        <label>{{__('Allow Api?')}}</label>
                                        <div class="gpt-up-down-arrow position-relative">
                                            <select name="allow_api" required="" class="form-control select-dropdown">
                                                <option value="1" selected>{{ __('Yes') }}</option>
                                                <option value="0">{{ __('No') }}</option>
                                            </select>
                                            <span></span>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-lg-6 mt-2 password-input">
                                        <label>{{__('Password')}}</label>
                                        <input type="password" name="password" class="form-control pass-input" placeholder="{{ __('Enter Password') }}" aria-describedby="basic-addon2">
                                        <span class="hide-pass password input-group-text" id="basic-addon2">
                                            <img class="hide-pass-img" src="{{ asset('assets/images/icons/Hide.svg') }}" alt="img">
                                            <img class="d-none show-pass-img" src="{{ asset('assets/images/icons/show.svg') }}" alt="img">
                                        </span>
                                    </div>

                                    <div class="col-lg-6 mt-2 password-input">
                                        <label>{{__('Confirm password')}}</label>
                                        <input type="password" name="password_confirmation" class="form-control conf-pass-input" placeholder="{{ __('Enter Confirm password') }}" aria-describedby="basic-addon3">
                                        <span class="hide-pass confirm-password input-group-text" id="basic-addon3">
                                            <img class="hide-pass-img" src="{{ asset('assets/images/icons/Hide.svg') }}" alt="img">
                                            <img class="d-none show-pass-img" src="{{ asset('assets/images/icons/show.svg') }}" alt="img">
                                        </span>
                                    </div>

                                    @if (request('users') == 'user')
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Address')}}</label>
                                        <input type="text" name="address" class="form-control" placeholder="{{ __('Enter address here') }}">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Notes')}}</label>
                                        <input type="text" name="notes" class="form-control" placeholder="{{ __('Enter notes here') }}">
                                    </div>
                                    @endif

                                    <div class="col-lg-12">
                                        <div class="button-group text-center mt-5">
                                            <a href="" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
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
@endsection
