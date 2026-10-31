@extends('layouts.web.master')

@section('title')
    {{ __('Blog') }}
@endsection

@section('main_content')
    <section class="about-banner">
        <div class="container py-3 my-3">
            <div class="row">
                <div class="col-12 text-center mt-5">
                    <h4>{{ $page_data['headings']['blog_title'] ?? '' }}</h4>
                    <p>{{ $page_data['headings']['blog_desc'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="blogs-section">
        <div class="container">
            <div class="row" id="blogContainer">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 pb-4 blog" data-id="{{ $blog->id }}">
                        <div class="blog-shadow rounded">
                            <div class="text-center blog-image p-3">
                                <img src="{{ asset($blog->image ?? 'frontend/assets/images/news/news-01.jpg') }}"
                                    alt="product-image" class="w-100 h-100 object-fit-cover rounded-1">
                            </div>
                            <div class="p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <img class="blog-time-user-image" src="{{ asset('web/img/icons/clock.svg') }}"
                                        alt="">
                                    <p class="ms-1 mb-0">{{ formatted_date($blog->updated_at) }}</p>
                                </div>
                                <h6 class="h6-line-clamp">{{ $blog->title ?? '' }}</h6>
                                <p class="mb-4">{{ Str::limit($blog->descriptions, 105, '...') ?? '' }}</p>
                                <a href="{{ route('blog.show', $blog->slug) }}" class="custom-clr-primary">{{ __('Read More') }}
                                    <span class="font-monospace">></span></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
