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
    <div class="impersonation-notification-bar" style="background: linear-gradient(90deg, #991b1b, #dc2626); color: #ffffff; padding: 6px 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.12); z-index: 99999; position: relative; font-size: 13px;">
        <div class="d-flex align-items-center flex-wrap gap-1">
            <span class="badge bg-white text-danger fw-bold me-1" style="font-size: 10.5px; padding: 3px 6px;">
                <i class="fas fa-user-secret me-1"></i>{{ $banner['impersonator_role_label'] }} Mode
            </span>
            <span>{{ __('Viewing as') }}: <strong class="text-white text-decoration-underline">{{ auth()->user()->name }}</strong> ({{ '@' . (auth()->user()->username ?? auth()->user()->phone ?? auth()->user()->email) }})</span>
            <span class="badge bg-dark-subtle text-white-50 border border-light-subtle d-none d-md-inline" style="font-size: 10.5px;">{{ __('By') }}: {{ $banner['impersonator']->name }}</span>
        </div>
        <a href="{{ $banner['switch_back_url'] }}" class="btn btn-sm btn-light fw-bold shadow-sm py-1 px-3" style="color: #b91c1c; font-size: 12px; border-radius: 6px; white-space: nowrap;">
            <i class="fas fa-sign-out-alt me-1"></i> {{ __('Return to Admin') }}
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
