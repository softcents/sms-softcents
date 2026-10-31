@extends('layouts.admin.master')

@section('title')
    {{ __('Website Settings') }}
@endsection

@section('main_content')
    <div class="erp-table-section system-settings">
        <div class="container-fluid">
            <div class="cards-header shadow-sm">
                <div class="card-body">
                    <h4>🚩 {{ __('Page for Updating Website Sections') }}</h4>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="add-new-petty" role="tabpanel">
                    <div class="table-header border-0">
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <div class="order-form-section">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <ul class="nav nav-pills flex-column w-280">
                                                    <li class="nav-item">
                                                        <a href="#slider" id="home-tab4"
                                                            class="add-report-btn nav-link active"
                                                            data-bs-toggle="tab">{{ __('Slider Section') }}</a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="#top_contact_section" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Top Contact section') }}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#blog" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Blog Section') }}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#feature" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Features section') }}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#contact" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Connect Section') }}</a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="#access-section" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Access Section') }}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#service" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Service Section') }}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#faqs" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Faqs Section') }}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#client" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Client Section') }}</a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="#pricing" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Pricing Section') }}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#about_us" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('About us Page') }}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#contact_us" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Contuct us Page') }}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#term_of_service_section" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Term Of Service Page') }}</a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="#privacy" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Privacy Page') }}</a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="#refund" class="add-report-btn nav-link"
                                                            data-bs-toggle="tab">{{ __('Refund Page') }}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#socials" class="add-report-btn nav-link" data-bs-toggle="tab">
                                                            {{ __('Social Medias') }}
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#body-image" class="add-report-btn nav-link" data-bs-toggle="tab">
                                                            {{ __('Body Image') }}
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-8">
                                        <div class="card shadow">
                                            <div class="card-body">
                                                <form action="{{ route('admin.website-settings.update', 'manage-pages') }}"
                                                    method="post" class="ajaxform">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="tab-content no-padding">
                                                        {{-- Slider Section Start --}}
                                                        <div class="tab-pane fade show active" id="slider">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <label>{{ __('Title') }}</label>
                                                                    <input type="text" name="slider_title"
                                                                        value="{{ $page_data['headings']['slider_title'] ?? '' }}"
                                                                        required class="form-control">
                                                                </div>

                                                                <div class="col-6">
                                                                    <label>{{ __('Button One') }}</label>
                                                                    <input type="text" name="slider_btn1"
                                                                        value="{{ $page_data['headings']['slider_btn1'] ?? '' }}"
                                                                        required class="form-control">
                                                                </div>

                                                                <div class="col-6">
                                                                    <label>{{ __('Button One Link') }}</label>
                                                                    <input type="text" name="slider_btn1_link"
                                                                        value="{{ $page_data['headings']['slider_btn1_link'] ?? '' }}"
                                                                        required class="form-control">
                                                                </div>

                                                                <div class="col-6">
                                                                    <label>{{ __('Button Two') }}</label>
                                                                    <input type="text" name="slider_btn2"
                                                                        value="{{ $page_data['headings']['slider_btn2'] ?? '' }}"
                                                                        required class="form-control">
                                                                </div>

                                                                <div class="col-6">
                                                                    <label>{{ __('Button Two Link') }}</label>
                                                                    <input type="text" name="slider_btn2_link"
                                                                        value="{{ $page_data['headings']['slider_btn2_link'] ?? '' }}"
                                                                        required class="form-control">
                                                                    <span class="text-danger">{{ __('Note: Enter embedded video link') }}</span>
                                                                </div>

                                                                <div class="col-12">
                                                                    <label>{{ __('Description') }}</label>
                                                                    <textarea name="slider_description" class="form-control">{{ $page_data['headings']['slider_description'] ?? '' }}</textarea>
                                                                </div>

                                                                <div class="col-sm-10 align-self-center">
                                                                    <label>{{ __('Section Image') }}</label>
                                                                    <input type="file" name="slider_image" accept="image/*" class="form-control file-input-change" data-id="slider_image">
                                                                </div>

                                                                <div class="col-sm-2 align-self-center mt-3">
                                                                    <img class="table-img" id="slider_image"
                                                                        src="{{ asset($page_data['slider_image'] ?? 'assets/images/icons/img-upload.png') }}"
                                                                        alt="img">
                                                                </div>

                                                                <div class="col-12 mt-3">
                                                                    <h4 class="mb-3">{{ __('Sms Pro Text') }}</h4>
                                                                    <button class="w-100 py-3 d-block text-center fw-bold mt-3 admin-collapse bg-light text-primary border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sms_pro" aria-expanded="false" aria-controls="sms_pro">
                                                                        {{ __('Sms Pro Text') }} <i class="fas fa-arrow-circle-down ms-2"></i>
                                                                    </button>
                                                                    <div class="collapse mt-3" id="sms_pro">
                                                                        @foreach ($page_data['headings']['sms_pro_text'] ?? [] as $key => $sms_pro_text)
                                                                        <div class="col-lg-12 row">
                                                                            <div class="col-lg-12 feature-row sample-form-wrp duplicate-feature">
                                                                                <div class="">
                                                                                    <button type="button" class="btn btn-secondary service-btn-possition feature-add-btn">{{ __('+') }}</button>
                                                                                    <button type="button" class="btn text-danger remove-btn-features feature-remove-btn"><i class="fas fa-trash"></i></button>
                                                                                </div>
                                                                                <div class="grid-1 col-lg-12">
                                                                                    <div class="row mb-4">
                                                                                        <div class="col-lg-12">
                                                                                            <label>{{ __('Sms Pro Text') }}- {{ $key + 1 }} </label>
                                                                                            <input type="text" name="sms_pro_text[]" value="{{ $sms_pro_text ?? '' }}" required  class="form-control" placeholder="Enter text">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Slider section End --}}

                                                        {{-- About Us Section Start --}}
                                                        <div class="tab-pane fade" id="about_us">
                                                            <div class="form-group">
                                                                <label>{{ __('Title') }}</label>
                                                                <input type="text" name="about_us_title"
                                                                    value="{{ $page_data['headings']['about_us_title'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>{{ __('Description') }}</label>
                                                                <textarea name="about_us_description" class="form-control">{{ $page_data['headings']['about_us_description'] ?? '' }}</textarea>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h4 class="mb-3">{{ __('About Company') }}</h4>
                                                                    <button
                                                                        class="w-100 py-3 d-block text-center fw-bold mt-3 admin-collapse bg-light text-primary btn-custom-style"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#our-company"
                                                                        aria-expanded="false" aria-controls="our-company">
                                                                        {{ __('About Company') }}
                                                                        <i class="fas fa-arrow-circle-down ms-2"></i>
                                                                    </button>
                                                                    <div class="mt-3 collapse row" id="our-company">
                                                                        <div class="col-lg-12">
                                                                            <label>{{ __('Title') }}</label>
                                                                            <input type="text" name="our_company_title"
                                                                                value="{{ $page_data['headings']['our_company_title'] ?? '' }}"
                                                                                required class="form-control">
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <label>{{ __('Short Description') }}</label>
                                                                            <textarea name="our_company_short_desc" class="form-control">{{ $page_data['headings']['our_company_short_desc'] ?? '' }}</textarea>
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <label>{{ __('Long Description') }}</label>
                                                                            <textarea name="our_company_long_desc" class="form-control">{{ $page_data['headings']['our_company_long_desc'] ?? '' }}</textarea>
                                                                        </div>

                                                                        <div class="col-sm-10 align-self-center">
                                                                            <label>{{ __('Company Image') }}</label>
                                                                            <input type="file" name="our_company_image" accept="image/*" class="form-control file-input-change" data-id="our_company_image">
                                                                        </div>

                                                                        <div class="col-sm-2 align-self-center mt-3">
                                                                            <img class="table-img" id="our_company_image"
                                                                                src="{{ asset($page_data['our_company_image'] ?? 'assets/images/icons/img-upload.png') }}"
                                                                                alt="img">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h4 class="mb-3">{{ __('Our Mission') }}</h4>
                                                                    <button
                                                                        class="w-100 py-3 d-block text-center fw-bold mt-3 admin-collapse bg-light text-primary btn-custom-style"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#our-mission"
                                                                        aria-expanded="false" aria-controls="our-mission">
                                                                        {{ __('Our Mission') }}
                                                                        <i class="fas fa-arrow-circle-down ms-2"></i>
                                                                    </button>
                                                                    <div class="mt-3 collapse row" id="our-mission">
                                                                        <div class="col-lg-12">
                                                                            <label>{{ __('Title') }}</label>
                                                                            <input type="text" name="our_mission_title"
                                                                                value="{{ $page_data['headings']['our_mission_title'] ?? '' }}"
                                                                                required class="form-control">
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <label>{{ __('Short Description') }}</label>
                                                                            <textarea name="our_mission_short_desc" class="form-control">{{ $page_data['headings']['our_mission_short_desc'] ?? '' }}</textarea>
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <label>{{ __('Long Description') }}</label>
                                                                            <textarea name="our_mission_long_desc" class="form-control">{{ $page_data['headings']['our_mission_long_desc'] ?? '' }}</textarea>
                                                                        </div>

                                                                        <div class="col-sm-10 align-self-center">
                                                                            <label>{{ __('Mission Image') }}</label>
                                                                            <input type="file" name="our_mission_image" accept="image/*" class="form-control file-input-change" data-id="our_mission_image">
                                                                        </div>

                                                                        <div class="col-sm-2 align-self-center mt-3">
                                                                            <img class="table-img" id="our_mission_image" src="{{ asset($page_data['our_mission_image'] ?? 'assets/images/icons/img-upload.png') }}" alt="img">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h4 class="mb-3">{{ __('Our Vision') }}</h4>
                                                                    <button
                                                                        class="w-100 py-3 d-block text-center fw-bold mt-3 admin-collapse bg-light text-primary btn-custom-style"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#our-vision" aria-expanded="false"
                                                                        aria-controls="our-vision">
                                                                        {{ __('Our Vision') }}
                                                                        <i class="fas fa-arrow-circle-down ms-2"></i>
                                                                    </button>
                                                                    <div class="mt-3 collapse row" id="our-vision">
                                                                        <div class="col-lg-12">
                                                                            <label>{{ __('Title') }}</label>
                                                                            <input type="text" name="our_vision_title"
                                                                                value="{{ $page_data['headings']['our_vision_title'] ?? '' }}"
                                                                                required class="form-control">
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <label>{{ __('Short Description') }}</label>
                                                                            <textarea name="our_vision_short_desc" class="form-control">{{ $page_data['headings']['our_vision_short_desc'] ?? '' }}</textarea>
                                                                        </div>

                                                                        <div class="col-lg-12">
                                                                            <label>{{ __('Long Description') }}</label>
                                                                            <textarea name="our_vision_long_desc" class="form-control">{{ $page_data['headings']['our_vision_long_desc'] ?? '' }}</textarea>
                                                                        </div>

                                                                        <div class="col-sm-10 align-self-center">
                                                                            <label>{{ __('Vision Image') }}</label>
                                                                            <input type="file" name="our_vision_image" accept="image/*" class="form-control file-input-change" data-id="our_vision_image">
                                                                        </div>

                                                                        <div class="col-sm-2 align-self-center mt-3">
                                                                            <img class="table-img" id="our_vision_image"
                                                                                src="{{ asset($page_data['our_vision_image'] ?? 'assets/images/icons/img-upload.png') }}"
                                                                                alt="img">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        {{-- About Us Section End --}}

                                                        {{-- Blog section start --}}
                                                        <div class="tab-pane fade" id="blog">
                                                            <div class="form-group">
                                                                <label>{{ __('Blog Title') }}</label>
                                                                <input type="text" name="blog_title"
                                                                    value="{{ $page_data['headings']['blog_title'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>{{ __('Blog Description') }}</label>
                                                                <input type="text" name="blog_desc"
                                                                    value="{{ $page_data['headings']['blog_desc'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>{{ __('Blog Details') }}</label>
                                                                <input type="text" name="blog_details"
                                                                    value="{{ $page_data['headings']['blog_details'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                        </div>
                                                        {{-- Blog section End --}}

                                                        {{-- Feature Section Start --}}
                                                        <div class="tab-pane fade" id="feature">
                                                            @foreach ($page_data['headings']['feature_section_title'] ?? [] as $key => $title)
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label>{{ __('Title') }}</label>
                                                                        <input type="text"
                                                                            name="feature_section_title[]"
                                                                            value="{{ $title ?? '' }}" required=""
                                                                            class="form-control">
                                                                    </div>

                                                                    <div class="col-sm-6">
                                                                        <label>{{ __('Short Description') }}</label>
                                                                        <input type="text" name="feature_section_short_des[]" value="{{ $page_data['headings']['feature_section_short_des'][$key] ?? '' }}" required="" class="form-control">
                                                                    </div>

                                                                    <div class="col-sm-6 align-self-center">
                                                                        <label>{{ __('Feature Icon') }}</label>
                                                                        <input type="file" name="feature_section_logo" accept="image/*" class="form-control file-input-change" data-id="card_icons-{{ $loop->index }}">
                                                                    </div>

                                                                    <div class="col-1 mt-2 align-self-center mt-3">
                                                                        <img width="100%" height="auto" class="image-preview" id="card_icons-{{ $loop->index }}" src="{{ asset($page_data['feature_section_logo'][$key] ?? 'assets/images/icons/img-upload.png') }}" alt="img">
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        {{-- Feature Section End --}}

                                                        {{-- Top Contact section start --}}
                                                        <div class="tab-pane fade" id="top_contact_section">
                                                            <div class="form-group">
                                                                <label>{{ __('Phone Number') }}</label>
                                                                <input type="text" name="phone_number"
                                                                    value="{{ $page_data['headings']['phone_number'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>{{ __('Email Address') }}</label>
                                                                <input type="text" name="email_address"
                                                                    value="{{ $page_data['headings']['email_address'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>{{ __('Active Hours') }}</label>
                                                                <input type="text" name="active_hours"
                                                                    value="{{ $page_data['headings']['active_hours'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                        </div>
                                                        {{-- Top Contact section End --}}

                                                        {{-- Contact Section Start --}}
                                                        <div class="tab-pane fade" id="contact">
                                                            <div class="form-group">
                                                                <label>{{ __('Title') }}</label>
                                                                <input type="text" name="contact_title"
                                                                    value="{{ $page_data['headings']['contact_title'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>{{ __('Description') }}</label>
                                                                <textarea name="contact_description" class="form-control">{{ $page_data['headings']['contact_description'] ?? '' }}</textarea>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h4 class="mb-3">{{ __('Contact Section') }}</h4>
                                                                    <button
                                                                        class="w-100 py-3 d-block text-center fw-bold mt-3 admin-collapse bg-light text-primary btn-custom-style"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#feature-section"
                                                                        aria-expanded="false"
                                                                        aria-controls="feature-section">
                                                                        {{ __('Contact Section') }}
                                                                        <i class="fas fa-arrow-circle-down ms-2"></i>
                                                                    </button>
                                                                    <div class="mt-3 collapse" id="feature-section">
                                                                        @foreach ($page_data['headings']['contact_section_title'] ?? [] as $key => $con_sec_title)
                                                                            <div class="row mt-3">
                                                                                <div class="col-12">
                                                                                    <h6>{{ __('Card') }} {{ $key + 1 }}</h6>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <label>{{ __('Title') }}</label>
                                                                                    <input type="text" name="contact_section_title[]" value="{{ $con_sec_title ?? '' }}" required="" class="form-control">
                                                                                </div>

                                                                                <div class="col-sm-5 align-self-center">
                                                                                    <label>{{ __('Icon') }}</label>
                                                                                    <input type="file" name="contact_section_logo[]" accept="image/*" class="form-control file-input-change" data-id="contact_section_logo-{{ $loop->index }}">
                                                                                </div>

                                                                                <div class="col-sm-1 mt-2 align-self-center mt-2">
                                                                                    <img height="auto" class="img-fluid" id="contact_section_logo-{{ $loop->index }}"
                                                                                        src="{{ asset($page_data['contact_section_logo'][$key] ?? 'assets/images/icons/img-upload.png') }}" alt="img">
                                                                                </div>

                                                                                <div class="col-12">
                                                                                    <label>{{ __('Short Description') }}</label>
                                                                                    <textarea name="contact_section_short_des[]" id="" class="form-control" rows="2">{{ $page_data['headings']['contact_section_short_des'][$key] ?? '' }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Contact Section End --}}

                                                        {{-- Access Section Start --}}
                                                        <div class="tab-pane fade" id="access-section">
                                                            <div class="row">
                                                                <div class="col-sm-6 mb-3">
                                                                    <label>{{ __('Section Title') }}</label>
                                                                    <input type="text" name="access-section_title"
                                                                        value="{{ $page_data['headings']['access-section_title'] ?? '' }}"
                                                                        required class="form-control">
                                                                </div>

                                                                <div class="col-sm-6 mb-3">
                                                                    <label>{{ __('Button Text') }}</label>
                                                                    <input type="text" name="access-section_btn"
                                                                        value="{{ $page_data['headings']['access-section_btn'] ?? '' }}"
                                                                        required class="form-control">
                                                                </div>

                                                                <div class="col-sm-6 mb-3">
                                                                    <label>{{ __('Button Link') }}</label>
                                                                    <input type="text" name="access-section_btn_link"
                                                                        value="{{ $page_data['headings']['access-section_btn_link'] ?? '' }}"
                                                                        required class="form-control">
                                                                </div>

                                                                <div class="col-sm-5 mb-3">
                                                                    <label>{{ __('Image') }}</label>
                                                                    <input type="file" name="access_section_image" accept="image/*" class="form-control file-input-change" data-id="access_section_image">
                                                                </div>

                                                                <div class="col-sm-1 align-self-center">
                                                                    <img class="img-fluid" src="{{ asset($page_data['access_section_image'] ?? 'assets/images img-upload.png') }}" id="access_section_image" alt="">
                                                                </div>

                                                                <div class="col-sm-12 mb-3">
                                                                    <label>{{ __('Short Description') }}</label>
                                                                    <textarea name="access-section_short_des" class="form-control">{{ $page_data['headings']['access-section_short_des'] ?? '' }}</textarea>
                                                                </div>

                                                                <div class="col-sm-12 mb-3">
                                                                    <label>{{ __('Description') }}</label>
                                                                    <textarea name="access-section_description" class="form-control">{{ $page_data['headings']['access-section_description'] ?? '' }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Access Section End --}}

                                                        {{-- Service section start --}}
                                                        <div class="tab-pane fade" id="service">
                                                            <div class="form-group">
                                                                <label>{{ __('Section Title') }}</label>
                                                                <input type="text" name="service_title"
                                                                    value="{{ $page_data['headings']['service_title'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                        </div>
                                                        {{-- Service section End --}}

                                                        {{-- faqs section --}}
                                                        <div class="tab-pane fade" id="faqs">
                                                            <div class="col-12">
                                                                <label>{{ __('Title') }}</label>
                                                                <input type="text" name="faqs_title"
                                                                    value="{{ $page_data['headings']['faqs_title'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-11">
                                                                    <label>{{ __('Image') }}</label>
                                                                    <input type="file" name="faqs_image" accept="image/*" class="form-control file-input-change" data-id="faqs_image">
                                                                </div>

                                                                <div class="col-sm-1 align-self-center">
                                                                    <img class="img-fluid" id="faqs_image" src="{{ asset($page_data['faqs_image'] ?? 'assets/images/icons/img-upload.png') }}" alt="">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Client section --}}
                                                        <div class="tab-pane fade" id="client">
                                                            <div class="form-group">
                                                                <label>{{ __('Title') }}</label>
                                                                <input type="text" name="client_title"
                                                                    value="{{ $page_data['headings']['client_title'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>{{ __('Description') }}</label>
                                                                <textarea class="form-control" name="client_description" id="" cols="30" rows="3">{{ $page_data['headings']['client_description'] ?? '' }}</textarea>
                                                            </div>
                                                        </div>

                                                        {{-- Contact Us Section Start --}}
                                                        <div class="tab-pane fade" id="contact_us">

                                                            <div>
                                                                <div class="form-group">
                                                                    <label>{{ __('Title') }}</label>
                                                                    <input type="text" name="contact_us_main_title"
                                                                        value="{{ $page_data['headings']['contact_us_main_title'] ?? '' }}"
                                                                        required class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{ __('Description') }}</label>
                                                                    <textarea name="contact_us_main_description" class="form-control">{{ $page_data['headings']['contact_us_main_description'] ?? '' }}</textarea>
                                                                </div>
                                                            </div>

                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="row mt-3">
                                                                        <div class="col-12">
                                                                            <h4>{{ __('Location Section') }}</h4>
                                                                        </div>

                                                                        <div class="col-lg-6">
                                                                            <label>{{ __('Branch Name') }}</label>
                                                                            <input type="text"
                                                                                name="contact_us_branch_name"
                                                                                value="{{ $page_data['headings']['contact_us_branch_name'] ?? '' }}"
                                                                                required class="form-control">
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <label>{{ __('Branch Address') }}</label>
                                                                            <input type="text"
                                                                                name="contact_us_branch_address"
                                                                                value="{{ $page_data['headings']['contact_us_branch_address'] ?? '' }}"
                                                                                required class="form-control">
                                                                        </div>
                                                                        <div class="col-sm-10 align-self-center">
                                                                            <label>{{ __('Icon') }}</label>
                                                                            <input type="file" name="contact_us_branch_icon" accept="image/*" class="form-control file-input-change" data-id="contact_us_branch_icon">
                                                                        </div>

                                                                        <div class="col-sm-2 align-self-center mt-3">
                                                                            <img class="table-img"
                                                                                id="contact_us_branch_icon"
                                                                                src="{{ asset($page_data['contact_us_branch_icon'] ?? 'assets/images/icons/img-upload.png') }}"
                                                                                alt="img">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h4 class="mb-3">{{ __('Starter Section') }}</h4>
                                                                    <button
                                                                        class="w-100 py-3 d-block text-center fw-bold mt-3 admin-collapse bg-light text-primary btn-custom-style"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#starter-section"
                                                                        aria-expanded="false"
                                                                        aria-controls="starter-section">
                                                                        {{ __('Starter Section') }}
                                                                        <i class="fas fa-arrow-circle-down ms-2"></i>
                                                                    </button>
                                                                    <div class="mt-3 collapse" id="starter-section">
                                                                        <div>
                                                                            <div class="form-group">
                                                                                <label>{{ __('Title') }}</label>
                                                                                <input type="text"
                                                                                    name="contact_us_title"
                                                                                    value="{{ $page_data['headings']['contact_us_title'] ?? '' }}"
                                                                                    required class="form-control">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>{{ __('Availability') }}</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="contact_us_availability"
                                                                                    value="{{ $page_data['headings']['contact_us_availability'] ?? '' }}">
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-10 align-self-center">
                                                                                    <label>{{ __('Section Image') }}</label>
                                                                                    <input type="file" name="contact_us_image" accept="image/*" class="form-control file-input-change" data-id="contact_us_image">
                                                                                </div>

                                                                                <div
                                                                                    class="col-sm-2 align-self-center mt-3">
                                                                                    <img class="table-img"
                                                                                        id="contact_us_image"
                                                                                        src="{{ asset($page_data['contact_us_image'] ?? 'assets/images/icons/img-upload.png') }}"
                                                                                        alt="img">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>{{ __('Description') }}</label>
                                                                                <textarea name="contact_us_description" class="form-control">{{ $page_data['headings']['contact_us_description'] ?? '' }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h4 class="mb-3">{{ __('Contact Section') }}</h4>
                                                                    <button class="w-100 py-3 d-block text-center fw-bold mt-3 admin-collapse bg-light text-primary btn-custom-style" type="button" data-bs-toggle="collapse" data-bs-target="#contact-us-option-section" aria-expanded="false" aria-controls="contact-us-option-section">
                                                                        {{ __('Contact Section') }}
                                                                        <i class="fas fa-arrow-circle-down ms-2"></i>
                                                                    </button>
                                                                    <div class="mt-3 collapse" id="contact-us-option-section">
                                                                        <div class="row mt-3">
                                                                            <div class="col-12">
                                                                                <h6>{{ __('Card 1') }}</h6>
                                                                            </div>

                                                                            <div class="col-lg-6">
                                                                                <label>{{ __('Title') }}</label>
                                                                                <input type="text"
                                                                                    name="contact_us_option_title"
                                                                                    value="{{ $page_data['headings']['contact_us_option_title'] ?? '' }}"
                                                                                    required class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <label>{{ __('Contact') }}</label>
                                                                                <input type="text" name="contact_us_option_contact" value="{{ $page_data['headings']['contact_us_option_contact'] ?? '' }}" required class="form-control">
                                                                            </div>
                                                                            <div class="col-sm-10 align-self-center">
                                                                                <label>{{ __('Contact Icon') }}</label>
                                                                                <input type="file" name="contact_us_option_icon" class="form-control file-input-change" data-id="contact_us_option_icon" accept="image/*">
                                                                            </div>

                                                                            <div class="col-sm-2 align-self-center mt-3">
                                                                                <img class="table-img" id="contact_us_option_icon" src="{{ asset($page_data['contact_us_option_icon'] ?? 'assets/images/icons/img-upload.png') }}" alt="img">
                                                                            </div>


                                                                            <div class="col-12 mt-3">
                                                                                <h6>{{ __('Card 2') }}</h6>
                                                                            </div>

                                                                            <div class="col-lg-6">
                                                                                <label>{{ __('Title') }}</label>
                                                                                <input type="text"
                                                                                    name="contact_us_option_title2"
                                                                                    value="{{ $page_data['headings']['contact_us_option_title2'] ?? '' }}"
                                                                                    required class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <label>{{ __('Contact') }}</label>
                                                                                <input type="text"
                                                                                    name="contact_us_option_contact2"
                                                                                    value="{{ $page_data['headings']['contact_us_option_contact2'] ?? '' }}"
                                                                                    required class="form-control">
                                                                            </div>
                                                                            <div class="col-sm-10 align-self-center">
                                                                                <label>{{ __('Contact Icon') }}</label>
                                                                                <input type="file" name="contact_us_option_icon2" accept="image/*" class="form-control file-input-change" data-id="contact_us_option_icon2">
                                                                            </div>

                                                                            <div class="col-sm-2 align-self-center mt-3">
                                                                                <img class="table-img"
                                                                                    id="contact_us_option_icon2"
                                                                                    src="{{ asset($page_data['contact_us_option_icon2'] ?? 'assets/images/icons/img-upload.png') }}"
                                                                                    alt="img">
                                                                            </div>
                                                                            <div class="col-12 mt-3">
                                                                                <h6>{{ __('Card 3') }}</h6>
                                                                            </div>

                                                                            <div class="col-lg-6">
                                                                                <label>{{ __('Title') }}</label>
                                                                                <input type="text"
                                                                                    name="contact_us_option_title3"
                                                                                    value="{{ $page_data['headings']['contact_us_option_title3'] ?? '' }}"
                                                                                    required class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <label>{{ __('Contact') }}</label>
                                                                                <input type="text"
                                                                                    name="contact_us_option_contact3"
                                                                                    value="{{ $page_data['headings']['contact_us_option_contact3'] ?? '' }}"
                                                                                    required class="form-control">
                                                                            </div>
                                                                            <div class="col-sm-10 align-self-center">
                                                                                <label>{{ __('Contact Icon') }}</label>
                                                                                <input type="file" name="contact_us_option_icon3" accept="image/*" class="form-control file-input-change" data-id="contact_us_option_icon3">
                                                                            </div>

                                                                            <div class="col-sm-2 align-self-center mt-3">
                                                                                <img class="table-img" id="contact_us_option_icon3" src="{{ asset($page_data['contact_us_option_icon3'] ?? 'assets/images/icons/img-upload.png') }}" alt="img">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h4 class="mb-3">{{ __('Map Location') }}</h4>
                                                                    <button
                                                                        class="w-100 py-3 d-block text-center fw-bold mt-3 admin-collapse bg-light text-primary btn-custom-style"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#map-section"
                                                                        aria-expanded="false" aria-controls="map-section">
                                                                       {{ __('Map Location(Embed)') }}
                                                                        <i class="fas fa-arrow-circle-down ms-2"></i>
                                                                    </button>
                                                                    <div class="mt-3 collapse" id="map-section">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <label>{{ __('Map Location (Embed)') }}</label>
                                                                                <textarea name="map_location_embed" rows="8" class="form-control"
                                                                                    placeholder="Please enter map location embed code.">{{ $page_data['headings']['map_location_embed'] ?? '' }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Contact Us Section End --}}

                                                        {{-- Pricing section --}}
                                                        <div class="tab-pane fade" id="pricing">
                                                            <div class="form-group">
                                                                <label>{{ __('Title') }}</label>
                                                                <input type="text" name="pricing_title"
                                                                    value="{{ $page_data['headings']['pricing_title'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                        </div>
                                                        {{-- pricing section end --}}

                                                        {{-- Term Of service section start --}}
                                                        <div class="tab-pane fade" id="term_of_service_section">
                                                            <div class="form-group">
                                                                <label>{{ __('Title') }}</label>
                                                                <input type="text" name="term_of_service_title"
                                                                    value="{{ $page_data['headings']['term_of_service_title'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>{{ __('Description One') }}</label>
                                                                <textarea name="term_of_service_desc_one" class="form-control">{{ $page_data['headings']['term_of_service_desc_one'] ?? '' }}</textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>{{ __('Description Two') }}</label>
                                                                <textarea name="term_of_service_desc_two" class="form-control">{{ $page_data['headings']['term_of_service_desc_two'] ?? '' }}</textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>{{ __('Description Three') }}</label>
                                                                <textarea name="term_of_service_desc_three" class="form-control">{{ $page_data['headings']['term_of_service_desc_three'] ?? '' }}</textarea>
                                                            </div>

                                                        </div>
                                                        {{--  Term Of service section End --}}

                                                         {{-- Privacy section start --}}
                                                         <div class="tab-pane fade" id="privacy">
                                                            <div class="form-group">
                                                                <label>{{ __('Title') }}</label>
                                                                <input type="text" name="privacy_title"
                                                                    value="{{ $page_data['headings']['privacy_title'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>{{ __('Description One') }}</label>
                                                                <textarea name="privacy_desc_one" class="form-control">{{ $page_data['headings']['privacy_desc_one'] ?? '' }}</textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>{{ __('Description Two') }}</label>
                                                                <textarea name="privacy_desc_two" class="form-control">{{ $page_data['headings']['privacy_desc_two'] ?? '' }}</textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>{{ __('Description Three') }}</label>
                                                                <textarea name="privacy_desc_three" class="form-control">{{ $page_data['headings']['privacy_desc_three'] ?? '' }}</textarea>
                                                            </div>

                                                        </div>
                                                        {{--  Privacy section End --}}

                                                        {{-- Refund section start --}}
                                                        <div class="tab-pane fade" id="refund">
                                                            <div class="form-group">
                                                                <label>{{ __('Title') }}</label>
                                                                <input type="text" name="refund_title"
                                                                    value="{{ $page_data['headings']['refund_title'] ?? '' }}"
                                                                    required class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>{{ __('Description One') }}</label>
                                                                <textarea name="refund_desc_one" class="form-control">{{ $page_data['headings']['refund_desc_one'] ?? '' }}</textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>{{ __('Description Two') }}</label>
                                                                <textarea name="refund_desc_two" class="form-control">{{ $page_data['headings']['refund_desc_two'] ?? '' }}</textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>{{ __('Description Three') }}</label>
                                                                <textarea name="refund_desc_three" class="form-control">{{ $page_data['headings']['refund_desc_three'] ?? '' }}</textarea>
                                                            </div>

                                                        </div>
                                                        {{--  Refund section End --}}

                                                        {{-- Social section start --}}
                                                        <div class="tab-pane fade" id="socials">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h4 class="mb-3">{{ __('Footer Socials') }}</h4>
                                                                    <button class="w-100 py-3 d-block text-center fw-bold mt-3 admin-collapse bg-light text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#footer-socials" aria-expanded="false" aria-controls="footer-socials">
                                                                        {{ __('Footer Socials') }} <i class="fas fa-arrow-circle-down ms-2"></i>
                                                                    </button>
                                                                    <div class="collapse mt-3" id="footer-socials">
                                                                        @foreach ($page_data['headings']['footer_socials_links'] ?? [] as $key => $footer_socials_links)
                                                                            <div class="sample-form-wrp duplicate-feature pe-3">
                                                                                <div class="row mb-4">
                                                                                    <div class="col-sm-6">
                                                                                        <label>{{ __('Link') }}</label>
                                                                                        <input type="text" name="footer_socials_links[]" value="{{ $footer_socials_links ?? '' }}" required  class="form-control">
                                                                                    </div>
                                                                                    <div class="col-sm-5 align-self-center">
                                                                                        <label>{{ __('Icon') }}</label>
                                                                                        <input type="file" name="footer_socials_icons[]" accept="image/*" class="form-control image-input">
                                                                                    </div>
                                                                                    <div class="col-sm-1 align-self-center mt-2">
                                                                                        <img  width="100%" height="auto" class="image-preview" data-default-src="{{ asset('assets/img/demo-img.png') }}" id="footer_socials_icons" src="{{ asset($page_data['footer_socials_icons'][$key] ?? 'assets/img/demo-img.png') }}" alt="img">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 mt-2">
                                                                    <hr>
                                                                </div>

                                                                <div class="col-12">
                                                                    <h4 class="mb-3">{{ __('Topbar Socials') }}</h4>
                                                                    <button class="w-100 py-3 d-block text-center fw-bold mt-3 admin-collapse bg-light text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-socials" aria-expanded="false" aria-controls="sidebar-socials">
                                                                        {{ __('Topbar Socials') }} <i class="fas fa-arrow-circle-down ms-2"></i>
                                                                    </button>
                                                                    <div class="collapse" id="sidebar-socials">
                                                                        @foreach ($page_data['headings']['sidebar_socials_links'] ?? [] as $key => $sidebar_socials_texts)
                                                                            <div class="sample-form-wrp duplicate-feature pe-3">

                                                                                <div class="row mb-4">
                                                                                    <div class="col-sm-6">
                                                                                        <label>{{ __('Link') }}</label>
                                                                                        <input type="text" name="sidebar_socials_links[]" value="{{ $page_data['headings']['sidebar_socials_links'][$key] ?? '' }}" required  class="form-control">
                                                                                    </div>
                                                                                    <div class="col-sm-5 align-self-center">
                                                                                        <label>{{ __('Icon') }}</label>
                                                                                        <input type="file" name="sidebar_socials_icons[]" accept="image/*" class="form-control image-input">
                                                                                    </div>
                                                                                    <div class="col-sm-1 align-self-center mt-2">
                                                                                        <img  width="100%" height="auto" class="image-preview" data-default-src="{{ asset('assets/img/demo-img.png') }}" id="sidebar_socials_icons" src="{{ asset($page_data['sidebar_socials_icons'][$key] ?? 'assets/img/demo-img.png') }}" alt="img">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Social section emd --}}

                                                        {{-- body image section --}}
                                                        <div class="tab-pane fade" id="body-image">
                                                            <div class="row">
                                                                <div class="col-sm-11">
                                                                    <label>{{ __('Image') }}</label>
                                                                    <input type="file" name="body_image" accept="image/*" class="form-control file-input-change" data-id="body_image">
                                                                </div>
                                                                <div class="col-sm-1 align-self-center">
                                                                    <img class="img-fluid" id="body_image" src="{{ asset($page_data['body_image'] ?? 'assets/images/icons/img-upload.png') }}" alt="">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="button-group text-center mt-4">
                                                                    <button
                                                                        class="theme-btn m-2 submit-btn">{{ __('Update') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>
@endpush
