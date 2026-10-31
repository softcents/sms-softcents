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

                    <button class="sidebar-close-btn">
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
            </div>
            <div class="header-button-group">
                <a href="{{ route('login') }}" class="btn theme-btn">
                    {{ __('Get Start') }}
                </a>
                <button class="sidebar-btn">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</header>
