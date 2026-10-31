<!-- footer-section -->
<footer class="footer-section" data-background="{{ asset('web/img/footer/footer-bg.png') }}">
    <div class="container">
        <div class="row align-items-center pb-4">
            <div class="col-12 col-md-6">
                <a href="{{ route('home.index') }}" class="footer-logo">
                    <img src="{{ asset(get_option('general')['front_logo'] ?? 'web/img/logo.svg') }}" alt="" />
                </a>
            </div>
            <div class="col-12 col-md-6 social-icon-section">
                <ul class="social-link">
                    @foreach ($page_data['headings']['footer_socials_links'] ?? [] as $key => $footer_socials_link)
                        <Li>
                            <a href="{{ url($footer_socials_link) }}" target="_blank">
                                <img src="{{ asset($page_data['footer_socials_icons'][$key] ?? 'assets/img/demo-img.png') }}" alt="icon">
                            </a>
                        </Li>
                    @endforeach
                </ul>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-xl-3 mt-4">
                <div class="footer-items">
                    <h4 class="footer-title">{{ __('About Us') }}</h4>
                    <p>
                        {{ get_option('general')['footer_desc'] ?? '' }}
                     </p>
                 </div>
             </div>
             <div class="col-xl-3 mt-4">
                 <div class="footer-items">
                     <h4 class="footer-title">{{ __('Our Company') }}</h4>
                     <div class="row">
                         <div class="col-md-6">
                             <ul class="footer-link">
                                 <li>
                                     <a href="{{ route('about.index') }}">{{ __('About Us') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('privacy.index') }}">{{ __('Privacy Policy') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('refund.index') }}">{{ __('Refund Policy') }}</a>
                                </li>
                                 <li>
                                     <a href="{{ route('services.index') }}">{{ __('Terms of Service') }}</a>
                                 </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mt-4">
                <div class="footer-items">
                    <h4 class="footer-title">{{ __('Our Services') }}</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="footer-link">
                                <li>
                                    <a href="{{ route('services.show',1) }}">{{ __('Non Masking SMS') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('services.show',2) }}">{{ __('Masking SMS') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('services.show',3) }}">{{ __('API Services') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mt-4">
                <div class="footer-items">
                    <h4 class="footer-title">{{ __('Newsletter') }}</h4>
                    <p>{{ __('Subscribe to our newsletter and get update from us!') }}</p>
                    <form action="{{ route('home.newsletter') }}" method="post" class="ajaxform_reset_form">
                        @csrf
                        <div class="newsletter-form">
                            <input type="email" name="email" required class="form-control" placeholder="{{ __('yourname@gmail.com') }}" />
                            <button class="btn theme-btn submit-button" type="submit"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>
                {{ get_option('general')['copyright'] ?? '' }}
            </p>
        </div>
    </div>
</footer>
