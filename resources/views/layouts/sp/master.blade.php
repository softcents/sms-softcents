<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="auto">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title') | @endif {{ get_option('general')['title'] ?? 'SP Panel' }}</title>
    @include('layouts.admin.partials.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sp-badge { background: #2563eb; color: #fff; padding: 3px 8px; border-radius: 4px; font-size: 11px; }
        .card-stat { border-radius: 10px; border: 1px solid #e2e8f0; transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

<!-- Side Bar Start -->
@include('layouts.sp.partials.side-bar')
<!-- Side Bar End -->

<div class="section-container">
    @php
        $banner = \App\Services\ImpersonationService::getBannerInfo();
    @endphp
    @if ($banner)
    <div style="background: linear-gradient(90deg, #dc3545, #b02a37); color: #ffffff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.15); z-index: 99999; position: relative;">
        <div style="font-size: 14px; font-weight: 500;">
            <i class="fas fa-user-secret me-2" style="font-size: 16px;"></i>
            {{ __('You are currently logged in as Reseller (SP)') }}: <strong style="text-decoration: underline;">{{ auth()->user()->name }}</strong> ({{ '@' . (auth()->user()->username ?? auth()->user()->name) }})
            <span class="badge bg-light text-dark ms-2"><i class="fas fa-link me-1"></i>{{ __('Logged in by') }}: {{ $banner['impersonator_role_label'] }} ({{ $banner['impersonator']->name }})</span>
        </div>
        <a href="{{ $banner['switch_back_url'] }}" class="btn btn-sm btn-light fw-bold shadow-sm" style="color: #dc3545; font-size: 13px; border-radius: 6px; padding: 6px 18px;">
            <i class="fas fa-sign-out-alt me-1"></i> {{ __('Switch Back to ') . $banner['impersonator_role_label'] }}
        </a>
    </div>
    @endif

    <!-- header start -->
    @include('layouts.sp.partials.header')
    <!-- header end -->

    <!-- Main Content -->
    @yield('main_content')

    <!-- footer start -->
    @include('layouts.admin.partials.footer')
    <!-- footer end -->
    @stack('modal')
</div>

@include('layouts.admin.partials.script')
</body>
</html>
