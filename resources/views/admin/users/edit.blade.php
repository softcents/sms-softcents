@extends('layouts.admin.master')

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="tab-content order-summary-tab">
                        <div class="tab-pane fade show active" id="add-new-user"><br>
                            <div class="table-header d-flex justify-content-between align-items-center">
                                <h4>{{ $user->role == 'admin' ? __('Edit Staff') : __('Edit Customer') }}</h4>
                                @if ($user->role == 'user')
                                <a href="{{ route('admin.users.login-as', $user->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">
                                    <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login As Customer') }}
                                </a>
                                @endif
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
                                                <label>{{ __('Username') }}</label>
                                                <input type="text" name="username" value="{{ $user->username }}" class="form-control" placeholder="{{ __('Enter unique username') }}" autocomplete="off">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{__('Phone')}}</label>
                                                <input type="text" name="phone" value="{{ $user->phone }}" class="form-control" placeholder="{{ __('Enter Phone Number') }}" >
                                            </div>
                                            @if (in_array($user->role, ['sp', 'ssp', 'user']))
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Masking Rate') }}</label>
                                                <input type="number" step="0.001" name="masking_rate" value="{{ $user->masking_rate }}" class="form-control" placeholder="0.75">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Non-Masking Rate') }}</label>
                                                <input type="number" step="0.001" name="non_masking_rate" value="{{ $user->non_masking_rate }}" class="form-control" placeholder="0.39">
                                            </div>
                                            @endif
                                            @if (in_array($user->role, ['ssp', 'user']) && isset($resellers) && $resellers->count() > 0)
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Parent Reseller (SP / SSP)') }}</label>
                                                <select name="parent_id" class="form-control">
                                                    <option value="">{{ __('None (Direct Under Super Admin)') }}</option>
                                                    @foreach ($resellers as $res)
                                                    <option value="{{ $res->id }}" @selected($user->parent_id == $res->id)>{{ $res->name }} ({{ strtoupper($res->role) }} - {{ $res->username }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            @if (in_array($user->role, ['sp', 'ssp']))
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('Company / Brand Name') }}</label>
                                                <input type="text" name="company_name" value="{{ $user->company_name }}" class="form-control" placeholder="{{ __('Enter Company Name') }}">
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <label>{{ __('White Label Title') }}</label>
                                                <input type="text" name="white_label_title" value="{{ $user->white_label_title }}" class="form-control" placeholder="{{ __('Enter Portal Title') }}">
                                            </div>
                                            @endif
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
