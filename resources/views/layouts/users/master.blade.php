<!DOCTYPE html>
@if (app()->getLocale() == 'ar')
<html lang="ar" dir="rtl">
@else
<html lang="en" dir="auto">
@endif
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="{{__('IE=edge')}}">
    <meta name="viewport" content="{{__('width=device-width, initial-scale=1.0')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title') | @endif {{ get_option('general')['title'] ?? config('app.name') }}</title>
    @include('layouts.users.partials.css')
</head>
<body>

<!-- Side Bar Start -->
@include('layouts.users.partials.side-bar')
<!-- Side Bar End -->
<div class="section-container">
    @php
        $banner = \App\Services\ImpersonationService::getBannerInfo();
    @endphp
    @if ($banner)
    <div style="background: linear-gradient(90deg, #dc3545, #b02a37); color: #ffffff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.15); z-index: 99999; position: relative;">
        <div style="font-size: 14px; font-weight: 500;">
            <i class="fas fa-user-secret me-2" style="font-size: 16px;"></i>
            {{ __('You are currently logged in as :role', ['role' => $banner['current_role_label']]) }}: <strong style="text-decoration: underline;">{{ auth()->user()->name }}</strong> ({{ '@' . (auth()->user()->username ?? auth()->user()->phone ?? auth()->user()->email) }})
            <span class="badge bg-light text-dark ms-2"><i class="fas fa-link me-1"></i>{{ __('Logged in by') }}: {{ $banner['impersonator_role_label'] }} ({{ $banner['impersonator']->name }})</span>
        </div>
        <a href="{{ $banner['switch_back_url'] }}" class="btn btn-sm btn-light fw-bold shadow-sm" style="color: #dc3545; font-size: 13px; border-radius: 6px; padding: 6px 18px;">
            <i class="fas fa-sign-out-alt me-1"></i> {{ __('Switch Back to ') . $banner['impersonator_role_label'] }}
        </a>
    </div>
    @endif
    <!-- header start -->
    @include('layouts.users.partials.header')
    <!-- header end -->
    <!-- erp-state-overview-section start -->
    @yield('main_content')
    <!-- erp-state-overview-section end -->
    <!-- footer start -->
    @include('layouts.admin.partials.footer')
    <!-- footer end -->
    @stack('modal')
</div>

@include('layouts.users.partials.script')
</body>
</html>
