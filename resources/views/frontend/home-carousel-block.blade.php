{{--
    Partial: frontend/home-carousel-block.blade.php
    Variables: $cat (Category with posts), $palette (array), $index (int)
--}}
@php $p = $palette[$index % count($palette)]; @endphp

<div class="dh-carousel-block">
    <div class="dh-carousel-head">
        <h3 class="dh-carousel-title">
            <span style="width:34px;height:34px;border-radius:9px;flex-shrink:0;
                         display:flex;align-items:center;justify-content:center;
                         font-size:.85rem;background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                <i class="fas {{ $p['icon'] }}"></i>
            </span>
            {{ $cat->name }}
            {{-- <span class="cat-badge" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">Popular</span> --}}
        </h3>
        <div class="dh-carousel-controls">
            {{-- <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}"
               class="dh-view-all me-1">
                See all {{ number_format($cat->posts_count) }}
                <i class="bi bi-arrow-right"></i>
            </a> --}}
            <button class="dh-c-btn c-prev" data-target="cr-ajax-{{ $cat->id }}">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="dh-c-btn c-next" data-target="cr-ajax-{{ $cat->id }}">
                <i class="bi bi-chevron-right"></i>
            </button>
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
