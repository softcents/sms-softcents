@extends('layouts.web.master')

@section('title')
    {{ __('Contact Us') }}
@endsection

@section('main_content')
    <section class="about-banner">
        <div class="container  py-3 my-3">
            <div class="row">
                <div class="col-12 text-center mt-5">
                    <h4>{{ $page_data['headings']['contact_us_main_title'] ?? '' }}</h4>
                    <p>{{ $page_data['headings']['contact_us_main_description'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- -----------------------------------------------
        Contact Code Start
    -------------------------------------------------->
    <section class="contact-section form-section">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="mb-3">{{ $page_data['headings']['contact_us_title'] ?? '' }}</h2>
                <p>{{ $page_data['headings']['contact_us_description'] ?? '' }}</p>
            </div>
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-5 col-xl-6 mb-3 align-self-xl-center">
                    <div class="rounded-top-8 text-center custom-bg-secondary p-2 mb-2">
                        <p class="text-white mb-0"> {{ $page_data['headings']['contact_us_availability'] ?? '' }}</p>
                    </div>
                    <img src="{{ asset($page_data['contact_us_image'] ?? 'assets/images/icons/img-upload.png') }}" alt="image" class="w-100 rounded-bottom-8">
                </div>
                <div class="col-lg-6 col-xl-6 mb-3 align-self-xl-center">
                    <form action="{{ route('contact.store') }}" method="post"  class="ajaxform_instant_reload">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                            <label for="full-name" class="col-form-label fw-medium">{{ __('Full Name') }} <span class="custom-clr-primary">*</span></label>
                            <input type="text" name="name" required class="form-control" id="full-name" placeholder="{{ __('Enter full name') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                            <label for="phone-number" class="col-form-label fw-medium">{{ __('Phone Number') }} <span class="custom-clr-primary">*</span></label>
                            <input type="number" name="phone" required class="form-control" id="phone-number" placeholder="{{ __('Enter phone number') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                            <label for="email" class="col-form-label fw-medium">{{ __('Email') }} <span class="custom-clr-primary">*</span></label>
                            <input type="email" name="email" required class="form-control" id="email" placeholder="{{ __('Enter email address') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                            <label for="company-name" class="col-form-label fw-medium">{{ __('Company') }}<small class="text-body-secondary">{{ __('(Optional)') }}</small></label>
                            <input type="text" name="company_name" class="form-control" id="company-name" placeholder="{{ __('Enter company name') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                            <label for="message" class="col-form-label fw-medium">{{ __('Message') }}<span class="custom-clr-primary">*</span></label>
                            <textarea name="message" required class="form-control" id="message" rows="4" placeholder="{{ __('Enter your message') }}"></textarea>
                            </div>
                            <div class="py-1">
                                <button type="submit" class="btn theme-btn submit-btn">{{ __('Send message') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------------------------
        Location Code Start
    -------------------------------------------------->
    <section class="location-section bg-body-tertiary pb-0">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="mb-3">{{ __('Our locations') }}</h2>
                <p>{{ __('Come visit our friendly team at one of our offices.') }}</p>
            </div>
            <div class="row mb-4">
                <div class="col-lg-8 mb-3">
                    <div class="d-flex align-items-start mb-3">
                        <img class="circle-image" src="{{ asset($page_data['contact_us_branch_icon'] ?? 'assets/images/icons/img-upload.png') }}" alt="">
                        <div class="ms-3 location">
                            <h6 class="mb-0 lh-1 pb-2">{{ $page_data['headings']['contact_us_branch_name'] ?? '' }}</h6>
                            <p class="mb-2">{{ $page_data['headings']['contact_us_branch_address'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-3">
                    <div class="d-flex align-items-start mb-3">
                        <img class="circle-image" src="{{ asset($page_data['contact_us_option_icon'] ?? 'assets/images/icons/img-upload.png') }}" alt="">
                        <div class="ms-3">
                            <h6 class="mb-0 lh-1 pb-2">{{ $page_data['headings']['contact_us_option_title'] ?? '' }}</h6>
                            <p class="mb-2">{{ $page_data['headings']['contact_us_option_contact'] ?? '' }}</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <img class="circle-image" src="{{ asset($page_data['contact_us_option_icon2'] ?? 'assets/images/icons/img-upload.png') }}" alt="">
                        <div class="ms-3">
                            <h6 class="mb-0 lh-1 pb-2">{{ $page_data['headings']['contact_us_option_title2'] ?? '' }}</h6>
                            <p class="mb-2">{{ $page_data['headings']['contact_us_option_contact2'] ?? '' }}</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <img class="circle-image" src="{{ asset($page_data['contact_us_option_icon3'] ?? 'assets/images/icons/img-upload.png') }}" alt="">
                        <div class="ms-3">
                            <h6 class="mb-0 lh-1 pb-2">{{ $page_data['headings']['contact_us_option_title3'] ?? '' }}</h6>
                            <p class="mb-2">{{ $page_data['headings']['contact_us_option_contact3'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
