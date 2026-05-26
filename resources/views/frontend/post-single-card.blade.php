@php
    /*
    |--------------------------------------------------------------------------
    | IMAGE
    |--------------------------------------------------------------------------
    */
    $image = $post->getFirstMediaUrl('posts');
    if (!$image) {
        $image = asset('frontend/img/default.jpg');
    }

    /*
    |--------------------------------------------------------------------------
    | LIKE STATUS (guest support)
    |--------------------------------------------------------------------------
    */
    $liked = \App\Models\PostLike::where('post_id', $post->id)
        ->where(function ($q) {
            $q->where('ip_address', request()->ip())
              ->orWhere('session_id', session()->getId());
        })
        ->exists();

    /*
    |--------------------------------------------------------------------------
    | VIDEO
    |--------------------------------------------------------------------------
    */
    $video = $post->getFirstMediaUrl('videos');
@endphp

<div class="dh-card">

    {{-- =============================================
        MEDIA
    ============================================== --}}
    <div class="dh-card-media">

        <a href="{{ $post->url }}" tabindex="-1">

            {{-- UPLOADED VIDEO --}}
            @if($video)
                <video preload="metadata" muted>
                    <source src="{{ $video }}">
                </video>

            {{-- EMBEDDED VIDEO URL (YouTube etc.) --}}
            @elseif($post->video_url)
                <div class="ratio ratio-16x9" style="height:220px;">
                    <iframe src="{{ str_replace('watch?v=', 'embed/', $post->video_url) }}"
                            allowfullscreen loading="lazy"></iframe>
                </div>

            {{-- IMAGE --}}
            @else
                <img src="{{ $image }}"
                     alt="{{ $post->title }}"
                     loading="lazy">
            @endif

        </a>

        {{-- FEATURED BADGE --}}
        @if($post->is_featured)
            <span class="dh-badge-featured">⭐ Featured</span>
        @endif

    </div>

    {{-- =============================================
        BODY
    ============================================== --}}
    <div class="dh-card-body">

        {{-- TAXONOMY BADGES --}}
        <div class="dh-card-badges">

            @if($post->locality)
                <span class="dh-badge dh-badge-loc">
                    📍 {{ $post->locality->name }}
                </span>
            @endif

            @if($post->category)
                <span class="dh-badge dh-badge-cat">
                    {{ $post->category->name }}
                </span>
            @endif

            @if($post->subcategory)
                <span class="dh-badge dh-badge-sub">
                    {{ $post->subcategory->name }}
                </span>
            @endif

        </div>

        {{-- TITLE --}}
        <a href="{{ $post->url }}" class="dh-card-title">
            {{ \Illuminate\Support\Str::limit($post->title, 60) }}
        </a>

        {{-- DESCRIPTION --}}
        <p class="dh-card-desc">
            {{ \Illuminate\Support\Str::limit(strip_tags($post->description), 90) }}
        </p>

        {{-- =============================================
            STATS ROW
        ============================================== --}}
        <div class="dh-card-meta">

            {{-- LIKE BUTTON --}}
            <button class="dh-stat-btn likeBtn {{ $liked ? 'liked' : '' }}"
                    data-id="{{ $post->id }}">
                ❤️ <span id="like-count-{{ $post->id }}">{{ number_format($post->likes) }}</span>
            </button>

            {{-- VIEWS --}}
            <span class="dh-card-meta-item">
                👁 {{ number_format($post->views) }}
            </span>

            {{-- SHARES --}}
            <span class="dh-card-meta-item">
                🔄 {{ number_format($post->shares) }}
            </span>

            {{-- DATE --}}
            <span class="dh-card-meta-item" style="margin-left:auto;">
                🕒 {{ $post->created_at->diffForHumans() }}
            </span>

        </div>

        {{-- =============================================
            ACTION BUTTONS
        ============================================== --}}
        <div class="dh-card-actions">

            {{-- VIEW DETAILS --}}
            <a href="{{ $post->url }}" class="dh-card-btn dh-card-btn-primary">
                View Details
            </a>

            {{-- SHARE --}}
            <button class="dh-card-btn dh-card-btn-ghost shareBtn"
                    data-id="{{ $post->id }}"
                    data-url="{{ $post->url }}"
                    aria-label="Share">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.2">
                    <circle cx="18" cy="5" r="3"/>
                    <circle cx="6" cy="12" r="3"/>
                    <circle cx="18" cy="19" r="3"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
            </button>

        </div>

    </div>

</div>