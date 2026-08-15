{{--
    Partial: frontend/home-carousel-block.blade.php
    Variables: $cat (Category with posts), $palette (array), $index (int)
--}}
@php $p = $palette[$index % count($palette)]; @endphp

<div class="dh-carousel-block">
    <div class="dh-carousel-head">
        <h3 class="dh-carousel-title">
            <span><i class="fas {{ $p['icon'] }}"></i></span>
            <span>
                {{ $cat->name }}
                {{-- <small>Limited-time offers from businesses near you</small> --}}
            </span>
        </h3>
        <div class="dh-carousel-controls">
            <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}" class="dh-view-all me-1">See all</a>
            <button class="dh-c-btn c-prev" data-target="cr-ajax-{{ $cat->id }}" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
            <button class="dh-c-btn c-next" data-target="cr-ajax-{{ $cat->id }}" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
        </div>
    </div>
    <div class="dh-track-outer">
        <div class="dh-track" id="cr-ajax-{{ $cat->id }}">
            @foreach ($cat->posts as $post)
                @include('frontend.post-single-card', ['post' => $post])
            @endforeach
        </div>
    </div>
</div>
