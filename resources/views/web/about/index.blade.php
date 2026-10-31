@extends('layouts.web.master')

@section('title')
    {{ __('About Us') }}
@endsection

@section('main_content')
    <section class="about-banner">
        <div class="container py-3 my-3">
            <div class="row">
                <div class="col-12 text-center mt-5">
                    <h4>{{ $page_data['headings']['about_us_title'] ?? '' }}</h4>
                    <p>{{ $page_data['headings']['about_us_description'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-us">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-5 align-self-center">
                    <h3 class="section-title mb-4">{{ $page_data['headings']['our_company_title'] ?? '' }}</h3>
                    <p class="mt-3">{{ $page_data['headings']['our_company_short_desc'] ?? '' }}</p>
                    <p class="mt-4">{{ $page_data['headings']['our_company_long_desc'] ?? '' }}</p>
                </div>
                <div class="col-md-6 z-index mt-4 mt-md-0">
                    <img src="{{ asset($page_data['our_company_image'] ?? 'assets/images/icons/img-upload.png') }}" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </section>

    <section class="vision-mission py-5">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-6 z-index">
                    <img src="{{ asset($page_data['our_mission_image'] ?? 'assets/images/icons/img-upload.png') }}" class="img-fluid" alt="">
                </div>
                <div class="col-md-5 align-self-center">
                    <h3 class="section-title mb-4 mt-5 mt-md-0">{{ $page_data['headings']['our_mission_title'] ?? '' }}</h3>
                    <p class="mt-3">{{ $page_data['headings']['our_mission_short_desc'] ?? '' }}</p>
                    <p class="mt-4">{{ $page_data['headings']['our_mission_long_desc'] ?? '' }}</p>
                </div>
            </div>
        </div>

        <div class="container pt-5">
            <div class="row justify-content-between">
                <div class="col-md-5 align-self-center">
                    <h3 class="section-title mb-4">{{ $page_data['headings']['our_vision_title'] ?? '' }}</h3>
                    <p class="mt-3 text-justify">{{ $page_data['headings']['our_vision_short_desc'] ?? '' }}</p>
                    <p class="mt-4">{{ $page_data['headings']['our_vision_long_desc'] ?? '' }}</p>
                </div>
                <div class="col-md-6 z-index mt-4 mt-md-0">
                    <img src="{{ asset($page_data['our_vision_image'] ?? 'assets/images/icons/img-upload.png') }}" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection
