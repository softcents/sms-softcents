<style>
/* Hide mobile-only elements on desktop */
@media (min-width: 1000px) {
    .mobile-sidebar-auth {
        display: none !important;
    }
    .sidebar-btn {
        display: none !important;
    }
    .mobile-header-auth-btn {
        display: none !important;
    }
}

/* Mobile & Tablet view */
@media (max-width: 999px) {
    .mobile-sidebar-auth {
        display: block !important;
    }
    .sidebar-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 20px !important;
        width: 38px !important;
        height: 38px !important;
        border-radius: 8px !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        background: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
    }
    .header-button-group a.mobile-header-auth-btn {
        display: inline-flex !important;
        align-items: center;
        text-decoration: none;
    }
    .header-button-group {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }
}

@media (max-width: 480px) {
    .header-button-group a.mobile-header-auth-btn {
        padding: 6px 12px !important;
        font-size: 13px !important;
    }
    .header-logo img {
        max-width: 115px !important;
    }
}

.mobile-sidebar-auth .btn {
    text-transform: none;
}
.header-button-group .btn-outline-light:hover {
    background: #ffffff !important;
    color: #0d0d16 !important;
}
</style>

<div class="overlay"></div>
<!-- header-section -->
<header class="header-section">
    <div class="header-top clearfix">
        <div class="container">
            <div class="row justify-content-between py-1">
                <div class="col-md-9 contact align-self-center">
                    <ul>
                        <li><img src="{{ asset('web/img/Calling.png') }}" alt="Not found"> {{ $page_data['headings']['phone_number'] ?? '' }} ({{ $page_data['headings']['active_hours'] ?? '' }})</li>
                        <li><img src="{{ asset('web/img/Message.png') }}" alt="Not found">{{ $page_data['headings']['email_address'] ?? '' }}</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <div class="right-header">
                        <div class="language-change">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ languages()[app()->getLocale()] }}
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach (languages() as $key => $language)
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['lang' => $key]) }}">{{ $language }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="text-end social-icons align-self-center pe-0">
                            <ul>
                                @foreach ($page_data['headings']['sidebar_socials_links'] ?? [] as $key => $sidebar_socials_text)
                                <li>
                                    <a href="{{ $page_data['headings']['sidebar_socials_links'][$key] ?? '' }}">
                                        <img src="{{ asset($page_data['sidebar_socials_icons'][$key] ?? 'assets/img/demo-img.png') }}" alt="icon">
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container clearfix header-menu">
        <div class="header-wrapper">
            <a href="{{ route('home.index') }}" class="header-logo">
                <img src="{{ asset(get_option('general')['front_logo'] ?? 'web/img/logo.svg') }}" alt="header-logo" />
            </a>
            <div class="header-main-menu-wrapper mobile-menu">
                <div class="sidebar-mobile-header mobile-sidebar">
                    <a href="{{ route('home.index') }}" class="header-logo header-mobile-logo">
                        <img src="{{ asset(get_option('general')['front_logo'] ?? 'web/img/logo.svg') }}" alt="header-logo" />
                    </a>

                    <button class="sidebar-close-btn" type="button" aria-label="Close Menu">
                        <i class="far fa-times" aria-hidden="true"></i>
                    </button>
                </div>

                <ul>
                    <li><a href="{{ route('home.index') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('about.index') }}">{{ __('About Us') }}</a></li>
                    <li><a href="{{ route('home.index') }}#pricing">{{ __('Pricing') }}</a></li>
                    <li><a href="{{ route('home.index') }}#faqs">{{ __('FAQs') }}</a></li>
                    <li><a href="{{ route('blogs.index') }}">{{ __('Blogs') }}</a></li>
                    <li><a href="{{ route('contact.index') }}">{{ __('Contact Us') }}</a></li>
                </ul>

                <!-- Mobile Drawer Auth Buttons (Strictly Mobile Only) -->
                <div class="mobile-sidebar-auth mt-4 pt-3 border-top d-block d-lg-none">
                    @if (auth()->check())
                        <a href="{{ route('login') }}" class="btn theme-btn w-100 py-2 d-flex align-items-center justify-content-center" style="border-radius: 8px;">
                            <i class="fas fa-tachometer-alt me-2"></i> {{ __('Dashboard') }}
                        </a>
                    @else
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('register') }}" class="btn theme-btn w-100 py-2 fw-semibold d-flex align-items-center justify-content-center" style="border-radius: 8px; font-size: 15px;">
                                <i class="fas fa-user-plus me-2"></i> {{ __('Register') }}
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 py-2 fw-semibold d-flex align-items-center justify-content-center" style="border: 2px solid #4876FF; color: #4876FF; border-radius: 8px; font-size: 15px;">
                                <i class="fas fa-sign-in-alt me-2"></i> {{ __('Login') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="header-button-group d-flex align-items-center">
                @if (auth()->check())
                    <!-- Desktop button -->
                    <a href="{{ route('login') }}" class="btn theme-btn header-nav-btn d-none d-lg-inline-block">
                        <i class="fas fa-tachometer-alt me-1"></i> {{ __('Dashboard') }}
                    </a>
                    <!-- Mobile button -->
                    <a href="{{ route('login') }}" class="btn theme-btn mobile-header-auth-btn d-inline-flex d-lg-none align-items-center" style="border-radius: 6px; font-size: 13px; padding: 6px 12px !important;">
                        <i class="fas fa-tachometer-alt me-1"></i> {{ __('Dashboard') }}
                    </a>
                @else
                    <!-- Desktop buttons -->
                    <a href="{{ route('login') }}" class="btn btn-outline-light header-nav-btn d-none d-lg-inline-block px-3 py-2 fw-semibold" style="border-radius: 6px; border: 1.5px solid rgba(255,255,255,0.4); color: #fff;">
                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn theme-btn header-nav-btn d-none d-lg-inline-block px-3 py-2 fw-semibold" style="border-radius: 6px;">
                        <i class="fas fa-user-plus me-1"></i> {{ __('Register') }}
                    </a>

                    <!-- Mobile top navbar: Clean Login button -->
                    <a href="{{ route('login') }}" class="btn btn-outline-light mobile-header-auth-btn d-inline-flex d-lg-none align-items-center" style="border-radius: 6px; border: 1.5px solid rgba(255,255,255,0.6); color: #fff; font-size: 13px; padding: 6px 14px; font-weight: 600;">
                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}
                    </a>
                @endif
                <!-- Mobile 3-dot Menu Toggle Button (Strictly Mobile Only) -->
                <button class="sidebar-btn ms-2 d-inline-flex d-lg-none" type="button" aria-label="Toggle Navigation">
                    <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</header>
