@php
    $image = $post->getFirstMediaUrl('posts') ?: asset('frontend/img/default.jpg');
    $video = $post->getFirstMediaUrl('videos');
    $liked = \App\Models\PostLike::where('post_id', $post->id)
        ->where(fn($q) => $q->where('ip_address', request()->ip())
                            ->orWhere('session_id', session()->getId()))
        ->exists();
    $avgRating   = round($post->ratings_data_avg_rating ?? 0, 1);
    $ratingCount = $post->ratings_data_count ?? 0;
    $fillPct     = $avgRating > 0 ? ($avgRating / 5) * 100 : 0;
@endphp

<article class="dh-lc">

    {{-- Media — blurred fill behind, full image (contain) on top --}}
    <div class="dh-lc-media">
        <a href="{{ $post->url }}" tabindex="-1" aria-label="{{ $post->title }}">
            @if($video)
                <video preload="metadata" muted playsinline><source src="{{ $video }}"></video>
            @else
                <img class="dh-lc-bg" src="{{ $image }}" alt="" aria-hidden="true" loading="lazy">
                <img class="dh-lc-fg" src="{{ $image }}" alt="{{ $post->title }}" loading="lazy">
            @endif
        </a>

        @if($post->is_featured)
            <span class="dh-lc-feat">Featured</span>
        @endif

        @if($post->company_name)
            <span class="dh-lc-verified"><i class="fas fa-circle-check"></i> Verified</span>
        @endif

        <button class="dh-lc-fav likeBtn {{ $liked ? 'liked' : '' }}" data-id="{{ $post->id }}" aria-label="Save">
            <i class="fas fa-heart"></i>
        </button>
    </div>

    {{-- Navy body --}}
    <div class="dh-lc-body">
        <div class="dh-lc-top">
            <span class="dh-lc-cat">{{ optional($post->category)->name ?? 'Deal' }}</span>
            @if($avgRating > 0)
                <span class="dh-lc-rate">
                    <i class="fas fa-star"></i>
                    <strong>{{ number_format($avgRating, 1) }}</strong>
                    <em>({{ number_format($ratingCount) }})</em>
                </span>
            @endif
        </div>

        <a href="{{ $post->url }}" class="dh-lc-title">{{ Str::limit($post->title, 52) }}</a>

        @if($post->locality)
            <div class="dh-lc-loc"><i class="fas fa-location-dot"></i> {{ $post->locality->name }}</div>
        @endif

        {{-- Shown only in list view — fills the wider body --}}
        <p class="dh-lc-desc">{{ Str::limit(strip_tags($post->description), 165) }}</p>

        <div class="dh-lc-divider"></div>

        <div class="dh-lc-foot">
            @if($post->offer_percentage)
                <span class="dh-lc-plabel">Offer</span>
                <span class="dh-lc-price">{{ $post->offer_percentage }}</span>
            @else
                <span class="dh-lc-plabel">Posted</span>
                <span class="dh-lc-price sm">{{ $post->created_at->diffForHumans(['parts' => 1]) }}</span>
            @endif
        </div>

        <a href="{{ $post->url }}" class="dh-lc-btn">
            <span class="dh-lc-btn-txt">View Deal</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
    </div>
</article>
