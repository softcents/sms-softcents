@extends('layouts.web.master')

@section('title')
    {{ __(env('APP_NAME')) }}
@endsection

@section('main_content')
    <section class="banner-section" data-background="{{ asset('web/img/banner/banner.jpg') }}">
        <div class="container py-0 my-0 my-lg-5 py-lg-5">
            <div class="banner-section-wrapper">
                <div class="row align-items-center">
                    <div class="col-lg-6 mt-4 mt-md-0">
                        <div class="banner-content">
                            <h1>
                                {{ $page_data['headings']['slider_title'] ?? '' }}

                                <span data-typer-targets='{"targets": [
                                    @foreach ($page_data['headings']['sms_pro_text'] ?? [] as $key => $text)
                                           "{{ $text }}"@if (!$loop->last),@endif
                                    @endforeach
                                  ]}'>
                                </span>
                            </h1>
                            <p>
                                {{ $page_data['headings']['slider_description'] ?? '' }}
                            </p>

                            <div class="demo-btn-group">
                                <a href="{{ $page_data['headings']['slider_btn1_link'] ?? '' }}"
                                    class="btn theme-btn">{{ $page_data['headings']['slider_btn1'] ?? '' }}<i
                                        class="fas fa-arrow-right ms-1"></i></a>
                                <a href="#videoModal" data-bs-toggle="modal" class="mt-1 video-button">
                                    <span class="play-button"></span>
                                    <span>{{ $page_data['headings']['slider_btn2'] ?? '' }}</span>
                                </a>
                            </div>

                            <!-- Video Modal -->
                            <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="videoModalLabel">{{ __('Video') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item"
                                                    src="{{ asset($page_data['headings']['slider_btn2_link'] ?? '') }}"
                                                    allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-md-block">
                        <div class="banner-img">
                            <img src="{{ asset($page_data['slider_image'] ?? 'assets/images/icons/img-upload.png') }}"
                                alt="banner-img" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- envato-f-section -->
    <section class="envato-section">
        <div class="container envato-wrp">
            <div class="row justify-content-around">
                @foreach ($page_data['headings']['feature_section_title'] ?? [] as $key => $title)
                    <div class="col-sm-6 col-xl-3 mt-3 mt-xl-0">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                @if (isset($page_data['feature_section_logo'][$key]) && $page_data['feature_section_logo'][$key])
                                    <img src="{{ asset($page_data['feature_section_logo'][$key]) }}"
                                        alt="{{ $title ?? 'Not Found' }}" />
                                @else
                                    <img src="{{ asset('web/img/icons/1.png') }}" alt="{{ $title ?? 'Not Found' }}" />
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4>{{ $title ?? '' }}</h4>
                                <p>{{ $page_data['headings']['feature_section_short_des'][$key] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- our-feature-contact-section -->
    <section class="our-feature-section mt-xl-3" id="feature">
        <div class="container">
            <div class="section-title text-center">
                <h2> {{ $page_data['headings']['contact_title'] ?? '' }}</h2>
                <p>
                    {{ $page_data['headings']['contact_description'] ?? '' }} <br />
                </p>
            </div>
            <div class="row">
                @foreach ($page_data['headings']['contact_section_title'] ?? [] as $key => $contact_title)
                    <div class="col-xl-4 col-md-6">
                        <div class="feature-items">
                            <div class="flex-shrink-0">
                                @if (isset($page_data['contact_section_logo'][$key]) && $page_data['contact_section_logo'][$key])
                                    <img src="{{ asset($page_data['contact_section_logo'][$key]) }}"
                                        alt="{{ $title ?? 'Not Found' }}" />
                                @else
                                    <img src="{{ asset('web/img/icons/1.png') }}" alt="{{ $title ?? 'Not Found' }}" />
                                @endif
                            </div>
                            <h4>{{ $contact_title ?? '' }}</h4>
                            <p>{{ $page_data['headings']['contact_section_short_des'][$key] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- service-access-provider-section -->
    <section class="service-provide-section">
        <div class="container">
            <div class="row justify-content-around">
                <div class="col-md-6 service-banner-img">
                    <img src="{{ asset($page_data['access_section_image'] ?? 'assets/images/icons/img-upload.png') }}"
                        alt="banner-img" />
                </div>
                <div class="col-md-6 align-self-center">
                    <h2 class="mt-5 mt-sm-0">{{ $page_data['headings']['access-section_title'] ?? '' }}</h2>

                    <p class="short-description my-3">{{ $page_data['headings']['access-section_short_des'] ?? '' }}</p>
                    <p>{{ $page_data['headings']['access-section_description'] ?? '' }}</p>

                    <div class="mt-4 text-center text-md-start">
                        <a href="{{ $page_data['headings']['access-section_btn_link'] ?? '' }}"
                            class="btn theme-btn ">{{ $page_data['headings']['access-section_btn'] ?? '' }}<i
                                class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- premium-section -->
    <section class="premium-product-section" id="services">
        <div class="container">

            <div class="section-title text-center">
                <h2 class="mb-0">{{ $page_data['headings']['service_title'] ?? '' }}</h2>
            </div>

            <div class="slider-container">
                <div class="swiper-btn next1"><i class="far fa-arrow-right"></i></div>
                <div class="swiper-btn prev1"><i class="far fa-arrow-left"></i></div>
                <div class="premium-product-slide swiper-container pb-5">
                    <div class="swiper-wrapper">
                        @foreach ($services as $key => $service)
                            <div class="swiper-slide">
                                <div class="card product-card">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset($service->image ?? 'web/img/icons/1.png') }}"
                                            alt="{{ $service->title ?? 'Not Found' }}" />
                                    </div>
                                    <div class="card-body">
                                        <h5 class="mb-2">{{ $service->title }}</h5>
                                        <p class="mb-1">{{ Str::limit($service->description, 150, '...') }}</p>
                                        <div class="mt-4 text-center text-md-start">
                                            <a href="{{ route('services.show', $service->id) }}"
                                                class="read-more">{{ __('Read More') }} <i
                                                    class="fas fa-arrow-right ms-2"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-scrollbar"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Faqs Sections --}}
    <section class="faqs-section" id="faqs">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="mb-0">{{ $page_data['headings']['faqs_title'] ?? '' }}</h2>
            </div>
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <div class="accordion" id="accordionExample">
                        @foreach ($faqs as $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne-{{ $faq->id }}" aria-expanded="false"
                                        aria-controls="collapseOne-{{ $faq->id }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapseOne-{{ $faq->id }}" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p>{{ $faq->answer }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="faq-right-img col-lg-5 align-self-center">
                    <div class="mx-auto">
                        <img src="{{ asset($page_data['faqs_image'] ?? 'assets/images/icons/img-upload.png') }}"
                            alt="" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Faqs Section  --}}

    <!-- pricing-section -->
    @include('web.components.plans')

    <!-- testimonial-section -->
    <section class="testimonial-section">
        <div class="container">
            <div class="section-title text-center">
                <h2>{{ $page_data['headings']['client_title'] ?? '' }}</h2>
                <p>
                    {{ $page_data['headings']['client_description'] ?? '' }}
                </p>
            </div>
        </div>
        <div class="slider-container">
            <div class="swiper-btn prev2"><i class="far fa-arrow-left"></i></div>
            <div class="swiper-btn next2"><i class="far fa-arrow-right"></i></div>
            <div class="testimonial-slider swiper-container pb-5">
                <div class="swiper-wrapper">
                    @foreach ($testimonials as $testimonial)
                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="top-area">
                                    <ul>
                                        @for ($i = 0; $i < $testimonial->star; $i++)
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        @endfor
                                    </ul>
                                    <p><span>for</span> {{ $testimonial->satisfy_about }}</p>
                                </div>
                                <p class="testimonial">
                                    {{ $testimonial->text }}
                                </p>
                                <div class="media">
                                    <img src="{{ asset($testimonial->client_image ?? 'web/img/review/1.png') }}"
                                        alt="" />
                                    <div class="media-body">
                                        <strong>{{ $testimonial->client_name }}</strong>
                                        <small>{{ formatted_date($testimonial->created_at) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-scrollbar scrollbar3"></div>
            </div>
        </div>
    </section>
@endsection
