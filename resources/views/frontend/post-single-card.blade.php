@php
    $image = $post->getFirstMediaUrl('posts') ?: asset('frontend/img/default.jpg');
    $video = $post->getFirstMediaUrl('videos');
    $liked = \App\Models\PostLike::where('post_id', $post->id)
        ->where(fn($q) => $q->where('ip_address', request()->ip())
                            ->orWhere('session_id', session()->getId()))
        ->exists();
    $avgRating = round($post->ratings_data_avg_rating ?? 0, 1);
    $fillPct   = $avgRating > 0 ? ($avgRating / 5) * 100 : 0;
@endphp

<div class="dh-card">

    {{-- Media --}}
    <div class="dh-card-media">
        <a href="{{ $post->url }}" tabindex="-1">
            @if($video)
                <video preload="metadata" muted><source src="{{ $video }}"></video>
            @elseif($post->video_url)
                <iframe src="{{ str_replace('watch?v=','embed/',$post->video_url) }}"
                        style="width:100%;height:100%;border:0;" allowfullscreen loading="lazy"></iframe>
            @else
                <img class="dh-card-bg" src="{{ $image }}" alt="" aria-hidden="true" loading="lazy">
                <img class="dh-card-fg" src="{{ $image }}" alt="{{ $post->title }}" loading="lazy">
            @endif
        </a>

        {{-- Corner badge --}}
        @if($post->is_featured)
            <span class="dh-card-badge trend">Trending</span>
        @elseif($post->offer_percentage)
            <span class="dh-card-badge hot">{{ $post->offer_percentage }}</span>
        @endif

        {{-- Location pill --}}
        @if($post->locality)
            <span class="dh-card-loc"><i class="fas fa-location-dot"></i> {{ $post->locality->name }}</span>
        @endif

        {{-- Favourite / like --}}
        <button class="dh-card-fav likeBtn {{ $liked ? 'liked':'' }}" data-id="{{ $post->id }}" aria-label="Like">
            <i class="fas fa-heart"></i>
        </button>
    </div>

    {{-- Body --}}
    <div class="dh-card-body">

        {{-- Taxonomy tags --}}
        <div class="dh-badges">
            @if($post->category)
                <span class="dh-b">{{ $post->category->name }}</span>
            @endif
            @if($post->subcategory)
                <span class="dh-b">{{ $post->subcategory->name }}</span>
            @endif
        </div>

        {{-- Title + rating --}}
        <div class="dh-card-title-row">
            <a href="{{ $post->url }}" class="dh-card-title">{{ Str::limit($post->title, 60) }}</a>
            <div class="dh-rating-view">
                <span class="dh-star-big-wrap">
                    <i class="fas fa-star"></i>
                    <span class="dh-star-big-fg" style="width: {{ $fillPct }}%;"><i class="fas fa-star"></i></span>
                </span>
                <span class="dh-rating-avg-sm">{{ $avgRating > 0 ? number_format($avgRating, 1) : '' }}</span>
                <span class="dh-rating-count-sm">({{ $post->ratings_data_count ?? 0 }})</span>
            </div>
        </div>

        {{-- Business name + location --}}
        @if($post->company_name || $post->locality)
            <div class="dh-card-biz">
                @if($post->company_name)
                    <span class="dh-card-biz-name"><i class="fas fa-store"></i> {{ $post->company_name }}</span>
                @endif
                @if($post->locality)
                    <span class="dh-card-biz-loc"><i class="fas fa-location-dot"></i> {{ $post->locality->name }}</span>
                @endif
            </div>
        @endif

        {{-- Stats --}}
        <div class="dh-card-meta">
            <button class="dh-meta-btn likeBtn {{ $liked ? 'liked':'' }}" data-id="{{ $post->id }}">
                <i class="fas fa-heart"></i>
                <span id="lc-{{ $post->id }}">{{ number_format($post->likes_data_count ?? 0) }}</span>
            </button>
            <div class="dh-meta-box"><i class="fas fa-eye"></i> {{ number_format($post->views ?? 0) }}</div>
            <div class="dh-meta-box"><i class="fas fa-share-nodes"></i> {{ number_format($post->shares_data_count ?? 0) }}</div>
            <div class="dh-meta-time">
                @if($post->expiry_date)
                    @if(\Carbon\Carbon::parse($post->expiry_date)->isPast())
                        <i class="fas fa-circle-xmark" style="color:#dc2626;"></i> <span style="color:#dc2626;">Expired</span>
                    @else
                        <i class="fas fa-calendar-check" style="color:#059669;"></i>
                        <span style="color:#059669;">{{ \Carbon\Carbon::parse($post->expiry_date)->format('d M Y') }}</span>
                    @endif
                @else
                    <i class="fas fa-clock"></i> <span>{{ $post->created_at->diffForHumans(['parts'=>1]) }}</span>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="dh-card-actions">
            <a href="{{ $post->url }}" class="dh-btn dh-btn-primary">View details</a>
            <button class="dh-btn dh-btn-ghost shareBtn" data-id="{{ $post->id }}" data-url="{{ $post->url }}" aria-label="Share">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
            </button>
        </div>
    </div>
</div>
