@extends('layouts.auth.app')

@section('title')
    {{ __('Log in') }}
@endsection

@php
    $general = get_option('general');
    $brandTitle = $general['title'] ?? env("APP_NAME", "Softcents SMS");
    $brandLogo = $general['front_logo'] ?? $general['logo'] ?? null;
@endphp

@push('css')
<style>
    body {
        background-color: #f0f2f5 !important;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
        margin: 0 !important;
        padding: 0 !important;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .fb-page-wrapper {
        flex: 1 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px 80px 20px;
    }

    .fb-main-container {
        width: 100%;
        max-width: 980px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 48px;
    }

    /* Left Brand Section */
    .fb-brand-section {
        flex: 1;
        max-width: 540px;
        padding-bottom: 50px;
    }

    .fb-brand-logo {
        max-height: 70px;
        max-width: 280px;
        object-fit: contain;
        margin-bottom: 12px;
        display: block;
    }

    .fb-brand-heading {
        color: #1877f2;
        font-size: 42px;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -1px;
        margin: 0 0 14px 0;
    }

    .fb-brand-tagline {
        color: #1c1e21;
        font-size: 26px;
        line-height: 32px;
        font-weight: 400;
        margin: 0;
    }

    /* Right Card Section */
    .fb-card-section {
        width: 396px;
        max-width: 100%;
        flex-shrink: 0;
        text-align: center;
    }

    .fb-login-card {
        background: #ffffff;
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .1), 0 8px 16px rgba(0, 0, 0, .1);
        padding: 20px 20px 24px 20px;
        text-align: left;
    }

    .fb-input-box {
        position: relative;
        margin-bottom: 14px;
    }

    .fb-input-box .form-control {
        width: 100%;
        height: 52px;
        font-size: 17px;
        padding: 14px 16px;
        border: 1px solid #dddfe2;
        border-radius: 6px;
        background-color: #ffffff;
        color: #1c1e21;
        line-height: normal;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .fb-input-box .form-control:focus {
        border-color: #1877f2;
        box-shadow: 0 0 0 2px #e7f3ff;
        outline: none;
    }

    .fb-input-box.password-box .form-control {
        padding-right: 46px;
    }

    .fb-input-box .hide-pass {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        user-select: none;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fb-input-box .hide-pass img {
        width: 20px;
        height: 20px;
        opacity: 0.6;
        transition: opacity 0.2s;
    }

    .fb-input-box .hide-pass:hover img {
        opacity: 1;
    }

    .fb-input-box .hide-pass img:last-child {
        display: none;
    }

    .fb-input-box .hide-pass.show-pass img:first-child {
        display: none;
    }

    .fb-input-box .hide-pass.show-pass img:last-child {
        display: block;
    }

    .fb-remember-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        font-size: 14px;
        color: #606770;
    }

    .fb-remember-row .form-check {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 0;
        padding-left: 0;
    }

    .fb-remember-row input[type="checkbox"] {
        margin: 0;
        cursor: pointer;
        width: 16px;
        height: 16px;
        accent-color: #1877f2;
    }

    .fb-remember-row label {
        margin: 0;
        cursor: pointer;
        font-size: 14px;
        color: #606770;
    }

    .fb-btn-login {
        width: 100%;
        height: 48px;
        background-color: #1877f2;
        color: #ffffff !important;
        font-size: 20px;
        font-weight: 700;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.15s ease-in-out;
    }

    .fb-btn-login:hover {
        background-color: #166fe5;
        color: #ffffff !important;
    }

    .fb-btn-login:active {
        background-color: #1464cc;
    }

    .fb-forgot-box {
        text-align: center;
        margin-top: 16px;
    }

    .fb-forgot-box a {
        color: #1877f2;
        font-size: 14px;
        text-decoration: none;
        font-weight: 500;
    }

    .fb-forgot-box a:hover {
        text-decoration: underline;
    }

    .fb-divider-line {
        border-bottom: 1px solid #dadde1;
        margin: 20px 0;
    }

    .fb-btn-register-wrap {
        text-align: center;
    }

    .fb-btn-register {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #42b72a;
        color: #ffffff !important;
        font-size: 17px;
        font-weight: 700;
        border: none;
        border-radius: 6px;
        padding: 0 20px;
        height: 48px;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }

    .fb-btn-register:hover {
        background-color: #36a420;
        color: #ffffff !important;
    }

    .fb-extra-note {
        font-size: 14px;
        color: #1c1e21;
        margin-top: 28px;
        line-height: 1.4;
    }

    .fb-extra-note a {
        font-weight: 700;
        color: #1c1e21;
        text-decoration: none;
    }

    .fb-extra-note a:hover {
        text-decoration: underline;
    }

    /* Footer */
    .fb-footer-section {
        background: #ffffff;
        padding: 24px 20px;
        font-size: 12px;
        color: #737373;
        flex-shrink: 0;
        border-top: 1px solid #dddfe2;
    }

    .fb-footer-content {
        max-width: 980px;
        margin: 0 auto;
    }

    .fb-footer-links {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        list-style: none;
        padding: 0;
        margin: 0 0 10px 0;
    }

    .fb-footer-links a {
        color: #737373;
        text-decoration: none;
    }

    .fb-footer-links a:hover {
        text-decoration: underline;
    }

    .fb-footer-copyright {
        margin: 0;
        color: #8a8d91;
    }

    @media (max-width: 900px) {
        .fb-page-wrapper {
            padding: 30px 16px 40px 16px;
        }

        .fb-main-container {
            flex-direction: column;
            gap: 28px;
            text-align: center;
        }

        .fb-brand-section {
            max-width: 100%;
            padding-bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .fb-brand-heading {
            font-size: 34px;
        }

        .fb-brand-tagline {
            font-size: 19px;
            line-height: 26px;
        }

        .fb-card-section {
            width: 100%;
            max-width: 400px;
        }
    }
</style>
@endpush

@section('main_content')
<div class="fb-page-wrapper">
    <div class="fb-main-container">
        <!-- Brand / Intro Left Side -->
        <div class="fb-brand-section">
            @if(!empty($brandLogo))
                <img src="{{ asset($brandLogo) }}" alt="{{ $brandTitle }}" class="fb-brand-logo">
            @endif
            <h1 class="fb-brand-heading">{{ $brandTitle }}</h1>
            <p class="fb-brand-tagline">
                {{ __('Connect and communicate with your audience instantly with') }} {{ $brandTitle }}.
            </p>
        </div>

        <!-- Login Card Right Side -->
        <div class="fb-card-section">
            <div class="fb-login-card">
                <form method="POST" action="{{ route('login') }}" class="ajaxform_instant_reload">
                    @csrf

                    <!-- Email / Username Input -->
                    <div class="fb-input-box">
                        <input type="text" name="email" class="form-control" placeholder="{{ __('Email address or username') }}" required autofocus>
                    </div>

                    <!-- Password Input -->
                    <div class="fb-input-box password-box">
                        <input type="password" name="password" class="form-control password" placeholder="{{ __('Password') }}" required>
                        <span class="hide-pass">
                            <img src="{{ asset('assets/images/icons/Hide.svg') }}" alt="Hide">
                            <img src="{{ asset('assets/images/icons/show.svg') }}" alt="Show">
                        </span>
                    </div>

                    <!-- Remember Me -->
                    <div class="fb-remember-row">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="fbRememberMe">
                            <label class="form-check-label" for="fbRememberMe">
                                {{ __('Remember password') }}
                            </label>
                        </div>
                    </div>

                    <!-- Submit / Login Button -->
                    <button type="submit" class="fb-btn-login submit-btn">{{ __('Log in') }}</button>

                    <!-- Forgot Password -->
                    <div class="fb-forgot-box">
                        <a href="{{ route('password.request') }}">{{ __('Forgotten password?') }}</a>
                    </div>

                    <div class="fb-divider-line"></div>

                    <!-- Create New Account Button -->
                    <div class="fb-btn-register-wrap">
                        <a href="{{ route('register') }}" class="fb-btn-register">{{ __('Create new account') }}</a>
                    </div>
                </form>
            </div>

            <!-- Page / Business Footer Note -->
            <div class="fb-extra-note">
                <a href="{{ route('register') }}">{{ __('Create an account') }}</a> {{ __('for a celebrity, brand or business.') }}
            </div>
        </div>
    </div>
</div>

<!-- Bottom Footer (FB style) -->
<footer class="fb-footer-section">
    <div class="fb-footer-content">
        <ul class="fb-footer-links">
            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ route('login') }}">{{ __('Log in') }}</a></li>
            <li><a href="{{ route('register') }}">{{ __('Sign Up') }}</a></li>
            <li><a href="{{ route('password.request') }}">{{ __('Forgot Password') }}</a></li>
            @if(!empty($general['phone']))
                <li><span>{{ __('Helpline') }}: {{ $general['phone'] }}</span></li>
            @endif
            @if(!empty($general['email']))
                <li><span>{{ __('Support') }}: {{ $general['email'] }}</span></li>
            @endif
        </ul>
        <p class="fb-footer-copyright">
            {{ $general['copyright'] ?? ('© ' . date('Y') . ' ' . $brandTitle . '. ' . __('All rights reserved.')) }}
        </p>
    </div>
</footer>

<input type="hidden" data-model="Login" id="auth">
@endsection

@push('js')
<script src="{{ asset('assets/js/auth.js') }}"></script>
@endpush
