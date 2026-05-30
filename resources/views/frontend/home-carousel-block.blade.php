@php
$palette = [
    ['bg'=>'#dbeafe','ic'=>'#1d4ed8','icon'=>'fa-tags'],
    ['bg'=>'#d1fae5','ic'=>'#059669','icon'=>'fa-leaf'],
    ['bg'=>'#fef3c7','ic'=>'#d97706','icon'=>'fa-fire'],
    ['bg'=>'#fce7f3','ic'=>'#db2777','icon'=>'fa-heart'],
    ['bg'=>'#ede9fe','ic'=>'#7c3aed','icon'=>'fa-gem'],
    ['bg'=>'#cffafe','ic'=>'#0891b2','icon'=>'fa-bolt'],
    ['bg'=>'#fef2f2','ic'=>'#dc2626','icon'=>'fa-percent'],
    ['bg'=>'#ecfdf5','ic'=>'#16a34a','icon'=>'fa-star'],
    ['bg'=>'#fff7ed','ic'=>'#ea580c','icon'=>'fa-house'],
    ['bg'=>'#f0f9ff','ic'=>'#0284c7','icon'=>'fa-car'],
    ['bg'=>'#fdf4ff','ic'=>'#a21caf','icon'=>'fa-shirt'],
    ['bg'=>'#f8fafc','ic'=>'#475569','icon'=>'fa-laptop'],
];
$p = $palette[0];
@endphp

<div class="dh-carousel-block" id="ajax-carousel-block">
    <div class="dh-carousel-head">
        <h3 class="dh-carousel-title">
            <span style="width:34px;height:34px;border-radius:9px;flex-shrink:0;
                         display:flex;align-items:center;justify-content:center;
                         font-size:.85rem;background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                <i class="fas {{ $p['icon'] }}"></i>
            </span>
            {{ $carousel->name }}
            <span class="cat-badge" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">Popular</span>
        </h3>
        <div class="dh-carousel-controls">
            <a href="{{ route('posts.listing', ['category_id' => $carousel->slug]) }}"
               class="dh-view-all me-1">
                See all {{ number_format($carousel->posts_count) }}
                <i class="bi bi-arrow-right"></i>
            </a>
            <button class="dh-c-btn c-prev" data-target="cr-ajax" aria-label="Prev">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="dh-c-btn c-next" data-target="cr-ajax" aria-label="Next">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
    <div class="dh-track-outer">
        <div class="dh-track" id="cr-ajax">
            @foreach ($carousel->posts as $post)
                @include('frontend.post-single-card', ['post' => $post, 'inCarousel' => true])
            @endforeach
        </div>
    </div>
</div>