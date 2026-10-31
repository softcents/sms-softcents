@extends('layouts.web.master')

@section('title')
    {{ __('Blog') }}
@endsection

@section('main_content')
    <section class="about-banner">
        <div class="container py-3 my-3">
            <div class="row">
                <div class="col-12 text-center mt-5">
                    <h4>{{ $page_data['headings']['blog_details'] ?? '' }}</h4>
                    <p>{{ Str::limit($blog->title, 60) }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="blogs-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-8">
                    <div class="blog-details-content mb-3">
                        <img src="{{ asset($blog->image ?? 'frontend/assets/images/news/news-01.jpg') }}" alt="" class="w-100 rounded-top">

                        <div class="p-3">
                            <div class="d-flex align-items-center">
                                <p class="ms-1 mb-0">{{ formatted_date($blog->updated_at) }}</p>
                            </div>
                            <h6 class="mt-2">{{ $blog->title }}</h6>
                            <p class="mt-2">{{ $blog->descriptions ?? '' }}</p>

                             <div class="comments mt-3">
                                <h6 class="mb-2">{{ $comments->count() }} {{ __('Comment') }}</h6>
                                <hr class="m-0 custom-bg-light-sm">
                                 @foreach ($comments as $comment)
                                     <div class="d-flex align-items-start justify-content-between mt-3">
                                         <div class="d-flex align-items-start">
                                             <div class="ms-2">
                                                 <h6 class="mb-0">{{ $comment->name }}</h6>
                                                 <p class="mb-2"><small>{{ $comment->updated_at->format('F d, Y \a\t g:i a') }}</small></p>
                                                 <p>{{ $comment->comment }}</p>
                                             </div>
                                         </div>
                                     </div>
                                 @endforeach
                             </div>

                            <h6 class="my-3">{{ __('Leave a Comment Here') }}</h6>
                            <p class="mb-2">{{ __('Your email address will not be published') }} *</p>
                            <hr class="custom-bg-light-sm">
                            <form action="{{ route('blogs.store') }}" method="post"  class="form-section ajaxform_instant_reload">
                                @csrf
                                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                <input type="hidden" name="blog_slug" value="{{ $blog->slug }}">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                      <label for="full-name" class="col-form-label fw-medium">{{ __('Full Name') }}</label>
                                      <input type="text" name="name" class="form-control" id="full-name" placeholder="{{ __('Enter your name') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                      <label for="email" class="col-form-label fw-medium">{{ __('Email') }}</label>
                                      <input type="email" name="email" class="form-control" id="email" placeholder="{{ __('Enter your email') }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                      <label for="message" class="col-form-label fw-medium">{{ __('Comment') }}</label>
                                      <textarea class="form-control" name="comment" id="message" rows="4" placeholder="{{ __('Enter your comment') }}"></textarea>
                                    </div>
                                </div>
                                <div class="py-1">
                                    <button type="submit" class="btn theme-btn submit-btn">{{ __('Comment') }}</button>
                                  </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <h6>{{ __('Recent Posts') }}</h6>
                    @foreach ($recent_blogs as $post)
                    <div class="blog-shadow rounded mb-2">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('blogs.show', $post->slug) }}">
                                <img src="{{ asset($post->image ?? '') }}" class="object-fit-cover rounded-1 p-2 blog-small-image" alt="...">
                            </a>
                            <div class="mx-3">
                                <p class="p-2nd-line-clamp mb-1">
                                    <strong>{{ Str::limit($post->title, 60, '...') }}</strong>
                                </p>
                                <a href="{{ route('blogs.show', $post->slug) }}" class="custom-clr-primary">
                                    {{ __('Read More') }}
                                    <span class="font-monospace">></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
