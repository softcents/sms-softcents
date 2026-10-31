@extends('layouts.web.master')

@section('title')
    {{ __($service->title) }}
@endsection

@section('main_content')
    <section class="about-banner  py-3 my-3">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mt-5">
                    <h4>{{ __('Service Details') }}</h4>
                    <p>{{ __('Home - Non Masking SMS') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-us">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-sm-5 align-self-center">
                    <h3 class="section-title mb-4">{{ $service->title }}</h3>
                    <p class="mt-3">{{ $service->description }}</p>
                </div>
                <div class="col-sm-6 z-index">
                    <img src="{{ asset($service->image) }}" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </section>

    <!-- pricing-section -->
    @include('web.components.plans', ['class' => 'pricing-details'])

@endsection
