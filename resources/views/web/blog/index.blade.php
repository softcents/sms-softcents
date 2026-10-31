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

    {{-- Blogs Section Code Start --}}
    <section class="blogs-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        @foreach ($recent_blogs as $post)
                            <div class="col-lg-4 pb-4">
                                <div class="blog-shadow rounded">
                                    <div class="text-center blog-image p-3">
                                        <a href="{{ route('blogs.show', $post->slug) }}">
                                            <img src="{{ asset($post->image ?? '') }}" alt="product-image"
                                                class=" rounded-3" />
                                        </a>
                                    </div>
                                    <div class="p-3 pt-0">
                                        <div class="d-flex align-items-center mb-2">
                                            <p class="ms-1 mb-0">{{ formatted_date($post->updated_at) }}</p>
                                        </div>
                                        <h6 class="h6-line-clamp">{{ Str::limit($post->title, 60, '...') }} </h6>
                                        <p>{{ $post->descriptions }}</p>
                                        <a href="{{ route('blogs.show', $post->slug) }}"
                                            class="custom-clr-primary">{{ __('Read More') }} <span
                                                class="font-monospace">></span></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
