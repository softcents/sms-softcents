@extends('layouts.auth.app')

@section('title')
    {{ __('Login') }}
@endsection

@section('main_content')
<div class="mybazar-login-section">
    <div class="mybazar-login-avatar">
        <div class="logo">
            <img src="{{ asset('assets/images/login/login-small.png') }}" alt="">
        </div>

        <div class="left-img">
            <img class="login-img" src="{{ asset(get_option('manage-pages')['body_image']) }}" alt="">
        </div>
    </div>

    <div class="mybazar-login-wrapper">
        <div class="login-wrapper">
            <div class="login-body w-100">
                <h2>{{ __('Welcome to') }}<span> {{ get_option('general')['title'] ?? env("APP_NAME") }}</span></h2>
                <h6>{{ __('Welcome back, Please login in to your account') }}</h6>
                <form method="POST" action="{{ route('login') }}" class="ajaxform_instant_reload">
                    @csrf
                    <div class="input-group">
                        <span><img src="{{ asset('assets/images/icons/user.png') }}" alt="img"></span>
                        <input type="email" name="email" class="form-control email" placeholder="{{ __('Enter your Email') }}">
                    </div>

                    <div class="input-group">
                        <span><img src="{{ asset('assets/images/icons/lock.png') }}" alt="img"></span>
                        <span class="hide-pass">
                            <img src="{{ asset('assets/images/icons/Hide.svg') }}" alt="img">
                            <img src="{{ asset('assets/images/icons/show.svg') }}" alt="img">
                        </span>
                        <input type="password" name="password" class="form-control password" placeholder="Password">
                    </div>

                    <div class="mt-lg-3 mb-0 forget-password">
                        <label class="custom-control-label">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{ __('Remember me') }}
                                </label>
                            </div>
                        </label>
                        <a href="{{ route('password.request') }}">{{ __("Forget Password?") }}</a>
                    </div>

                    <button type="submit" class="btn login-btn submit-btn">{{ __('Login') }}</button>

                    <div class="row d-flex flex-wrap mt-2 justify-content-between">
                        <div class="col">
                            <a href="{{ route('register') }}">{{ __("Don't have an account?") }}</a>
                        </div>
                        <div class="col text-end">
                            <a class="text-primary" href="{{ route('register') }}">{{ __("Create an account.") }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <input type="hidden" data-model="Login" id="auth">
@endsection

@push('js')
<script src="{{ asset('assets/js/auth.js') }}"></script>
@endpush

