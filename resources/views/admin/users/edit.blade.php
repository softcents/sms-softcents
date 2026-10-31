@extends('layouts.admin.master')

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="tab-content order-summary-tab">
                        <div class="tab-pane fade show active" id="add-new-user"><br>
                            <div class="table-header">
                                <h4>{{ $user->role == 'admin' ? __('Edit Staff') : __('Edit Customer') }}</h4>
                            </div>
                            <div class="order-form-section">
                                <form action="{{ route('admin.users.update',$user->id) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                                    @csrf
                                    @method('put')

                                    <div class="add-suplier-modal-wrapper">
                                        <div class="row">
                                            <div class="col-lg-6 mt-2">
                                                <label>{{('Full Name')}}</label>
                                                <input type="text" name="name" value="{{ $user->name }}" required class="form-control" placeholder="{{ __('Enter Name') }}" >
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('Phone')}}</label>
                                                <input type="text" name="phone" value="{{ $user->phone }}" class="form-control" placeholder="{{ __('Enter Phone Number') }}" >
                                            </div>
                                            @if (request('users') == 'admin')
                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('Role')}}</label>
                                                <div>
                                                    <select name="role" required class="select-2 form-control w-100" >
                                                        <option value=""> {{__('Select a role')}}</option>
                                                        @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}" @selected($user->role == $role->name)> {{ ucfirst($role->name) }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('E-mail')}}</label>
                                                <input type="text" name="email" value="{{ $user->email }}" required class="form-control" placeholder="{{ __('Enter Email Address') }}" >
                                            </div>
                                            <div class="col-lg-6 mt-1">
                                                <div class="row">
                                                    <div class="col-10">
                                                        <label>{{ __('Image') }}</label>
                                                        <input type="file" accept="image/*" name="image" class="form-control file-input-change" data-id="image">
                                                    </div>
                                                    <div class="col-2 align-self-center mt-3">
                                                        <img src="{{ asset($user->image ?? 'assets/images/icons/upload.png') }}" id="image" class="table-img">
                                                    </div>
                                                </div>
                                            </div>

                                            @if (request('users') == 'user')
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Plans') }}</label>
                                                <div class="blade-up-down-arrow position-relative">
                                                    <select name="plan_id" class="select-2 form-control w-100" >
                                                        <option value=""> {{__('Select a plan')}}</option>
                                                        @foreach ($plans as $plan)
                                                        <option @selected($user->plan_id == $plan->id) value="{{ $plan->id }}"> {{ $plan->title }} </option>
                                                        @endforeach
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Balance') }}</label>
                                                <input type="number" step="any" name="balance" class="form-control" placeholder="{{ __('Enter balance here') }}" value="{{ $user->balance }}">
                                            </div>

                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('Low balance alert')}}</label>
                                                <input type="number" step="any" name="low_blnc_alrt" class="form-control" placeholder="{{ __('Enter low balance alert') }}" value="{{ $user->low_blnc_alrt }}">
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{ __('Status') }}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="status" required="" class="form-control select-dropdown">
                                                        <option @selected($user->status == 'active') value="active" selected>{{ __('Active') }}</option>
                                                        <option @selected($user->status == 'pending') value="pending">{{ __('Pending') }}</option>
                                                        <option @selected($user->status == 'suspend') value="suspend">{{ __('Suspend') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-2">
                                                <label>{{__('Allow Api?')}}</label>
                                                <div class="gpt-up-down-arrow position-relative">
                                                    <select name="allow_api" required="" class="form-control select-dropdown">
                                                        <option @selected($user->allow_api == 1) value="1" selected>{{ __('Yes') }}</option>
                                                        <option @selected($user->allow_api == 0) value="0">{{ __('No') }}</option>
                                                    </select>
                                                    <span></span>
                                                </div>
                                            </div>

                                            @endif

                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('New Password')}}</label>
                                                <input type="password" name="password" class="form-control" placeholder="{{ __('Enter Password') }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('Confirm password')}}</label>
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Enter Confirm password') }}">
                                            </div>

                                            @if (request('users') == 'user')
                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('Address')}}</label>
                                                <input type="text" name="address" class="form-control" placeholder="{{ __('Enter address here') }}" value="{{ $user->address }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('Notes')}}</label>
                                                <input type="text" name="notes" class="form-control" placeholder="{{ __('Enter notes here') }}" value="{{ $user->notes }}">
                                            </div>
                                            @endif

                                            <div class="col-lg-12">
                                                <div class="button-group text-center mt-5">
                                                    <a href="{{ route('admin.users.index',['users'=>$user->role]) }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                                                    <button class="theme-btn m-2 submit-btn">{{__('Update')}}</button>
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
