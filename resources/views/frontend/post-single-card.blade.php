@php
    $image = $post->getFirstMediaUrl('posts') ?: asset('frontend/img/default.jpg');
    $video = $post->getFirstMediaUrl('videos');
    $liked = \App\Models\PostLike::where('post_id', $post->id)
        ->where(fn($q) => $q->where('ip_address', request()->ip())
                            ->orWhere('session_id', session()->getId()))
        ->exists();
@endphp

<div class="dh-card">

    {{-- Media --}}
    <div class="dh-card-media">
        <a href="{{ $post->url }}" tabindex="-1">
            @if($video)
                <video preload="metadata" muted><source src="{{ $video }}"></video>
            @elseif($post->video_url)
                <div class="ratio ratio-16x9" style="height:220px;">
                    <iframe src="{{ str_replace('watch?v=','embed/',$post->video_url) }}"
                            allowfullscreen loading="lazy"></iframe>
                </div>
            @else
                <div class="dh-card-img-wrap">
                    <img src="{{ $image }}" alt="" class="dh-card-bg" aria-hidden="true" loading="lazy">
                    <img src="{{ $image }}" alt="{{ $post->title }}" class="dh-card-fg" loading="lazy">
                </div>
            @endif
        </a>
        @if($post->is_featured)
            <span class="badge-feat">⭐ Featured</span>
        @endif
    @if($post->offer_percentage)
        <span class="badge-offer">
            <i class="fas fa-tag"></i> {{ rtrim(rtrim(number_format($post->offer_percentage, 2), '0'), '.') }}% OFF
        </span>
    @endif
    </div>

    {{-- Body --}}
    <div class="dh-card-body">

        {{-- Taxonomy badges --}}
        <div class="dh-badges">
            @if($post->locality)
                <span class="dh-b dh-b-loc">📍 {{ $post->locality->name }}</span>
            @endif
            @if($post->category)
                <span class="dh-b dh-b-cat">{{ $post->category->name }}</span>
            @endif
            @if($post->subcategory)
                <span class="dh-b dh-b-sub">{{ $post->subcategory->name }}</span>
            @endif
            @if($post->company_name)
                <span class="dh-b dh-b-company"><i class="fas fa-building"></i> {{ $post->company_name }}</span>
            @endif
        </div>
        {{-- Title --}}
        {{-- Title --}}
{{-- Title + Rating row --}}
<div class="dh-card-title-row">
    <a href="{{ $post->url }}" class="dh-card-title">
        {{ Str::limit($post->title, 60) }}
    </a>

    @php
        $avgRating = round($post->ratings_data_avg_rating ?? 0, 1);
        $fillPct   = $avgRating > 0 ? ($avgRating / 5) * 100 : 0;
    @endphp
    <div class="dh-rating-view">
        <span class="dh-star-big-wrap">
            <i class="fas fa-star"></i>
            <span class="dh-star-big-fg" style="width: {{ $fillPct }}%;">
                <i class="fas fa-star"></i>
            </span>
        </span>
        <span class="dh-rating-avg-sm">{{ $avgRating > 0 ? number_format($avgRating, 1) : '' }}</span>
        <span class="dh-rating-count-sm">({{ $post->ratings_data_count ?? 0 }})</span>
    </div>
</div>

{{-- Description --}}
<p class="dh-card-desc">
    {{ Str::limit(strip_tags($post->description), 90) }}
</p>

{{-- Rating — right under title, before description --}}

{{-- Description --}}
<p class="dh-card-desc">
    {{ Str::limit(strip_tags($post->description), 90) }}
</p>

        {{-- Stats --}}
        <div class="dh-card-meta">
            <button class="dh-meta-btn likeBtn {{ $liked ? 'liked':'' }}"
                    data-id="{{ $post->id }}">
                <span class="dh-meta-icon"><i class="fas fa-heart"></i></span>
                <span class="dh-meta-count" id="lc-{{ $post->id }}">
                    {{ number_format($post->likes_data_count ?? 0) }}
                </span>
            </button>
            <div class="dh-meta-box">
                <span class="dh-meta-icon"><i class="fas fa-eye"></i></span>
                <span class="dh-meta-count">{{ number_format($post->views ?? 0) }}</span>
            </div>
            <div class="dh-meta-box">
                <span class="dh-meta-icon"><i class="fas fa-share-nodes"></i></span>
                <span class="dh-meta-count">{{ number_format($post->shares_data_count ?? 0) }}</span>
            </div>
            <div class="dh-meta-time">
            @if($post->expiry_date)
                @if(\Carbon\Carbon::parse($post->expiry_date)->isPast())
                    <i class="fas fa-circle-xmark" style="color:#dc2626;"></i>
                    <span style="color:#dc2626;">Expired</span>
                @else
                    <i class="fas fa-calendar-check" style="color:#059669;"></i>
                    <span style="color:#059669;">Valid until {{ \Carbon\Carbon::parse($post->expiry_date)->format('d M Y') }}</span>
                @endif
            @else
                <i class="fas fa-clock"></i>
                <span>Posted {{ $post->created_at->diffForHumans() }}</span>
            @endif
        </div>
        </div>

        {{-- Actions --}}
        <div class="dh-card-actions">
            <a href="{{ $post->url }}" class="dh-btn dh-btn-primary">View Details</a>
            <button class="dh-btn dh-btn-ghost shareBtn"
                    data-id="{{ $post->id }}" data-url="{{ $post->url }}" aria-label="Share">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.2">
                    <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/>
                    <circle cx="18" cy="19" r="3"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
            </button>
        </div>

    </div>
</div>