<link rel="shortcut icon" type="image/x-icon" href="{{ asset(get_option('general')['favicon'] ?? 'assets/images/logo/favicon.png')}}">
<link rel="stylesheet" href="{{ asset('web/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('web/css/swiper-bundle.min.css') }}" />
<link rel="stylesheet" href="{{ asset('web/fonts/fontawesome/css/fontawesome-all.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('web/css/lity.min.css') }}" />
<link rel="stylesheet" href="{{ asset('web/css/style.css') }}" />

@if (app()->getLocale() == 'ar')
<link rel="stylesheet" href="{{ asset('web/css/arabic.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.rtl.min.css') }}">
@endif

@stack('css')
