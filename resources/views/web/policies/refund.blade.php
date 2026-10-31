@extends('layouts.web.master')

@section('title')
{{ __('Term Of Service') }}
@endsection

@section('main_content')
    <section class="about-banner  py-3 my-3">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mt-5">
                    <h4>{{ __('Refund Policy') }}</h4>
                    <p>{{ __('Home - Non Masking SMS') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-sm-12 align-self-center">
                    <h3 class="section-title mb-4">{{ $page_data['headings']['refund_title'] ?? '' }}</h3>
                    <p class="mt-3">{{ $page_data['headings']['refund_desc_one'] ?? '' }}</p>

                    <p class="mt-3">{{ $page_data['headings']['refund_desc_two'] ?? '' }} </p>

                    <p class="mt-3">{{ $page_data['headings']['refund_desc_three'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </section>

@endsection
