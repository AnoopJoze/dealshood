@php
    /* |-------------------------------------------------------------------------- | IMAGE |-------------------------------------------------------------------------- */ $image = $post->getFirstMediaUrl(
        'posts',
    );
    if (!$image) {
        $image = asset('frontend/img/default.jpg');
    }
    /* |-------------------------------------------------------------------------- | LIKE STATUS (guest support) |-------------------------------------------------------------------------- */ $liked = \App\Models\PostLike::where(
        'post_id',
        $post->id,
    )
        ->where(function ($q) {
            $q->where('ip_address', request()->ip())->orWhere('session_id', session()->getId());
        })
        ->exists();
    /* |-------------------------------------------------------------------------- | VIDEO |-------------------------------------------------------------------------- */ $video = $post->getFirstMediaUrl(
        'videos',
    );
@endphp
<div class="col-xl-4 col-lg-4 col-md-6 mb-4">
    <div class="card card-blog card-plain h-100 shadow-sm border-0"> {{-- ========================================= IMAGE / VIDEO ========================================== --}} <div class="position-relative">
            <a href="{{ $post->url }}" class="d-block blur-shadow-image"> {{-- VIDEO --}} @if ($video)
                    <video class="img-fluid shadow border-radius-lg" controls preload="metadata"
                        style="height:250px;width:100%;object-fit:cover;">
                        <source src="{{ $video }}">
                    </video> {{-- EXTERNAL VIDEO URL --}}
                @elseif($post->video_url)
                    <div class="ratio ratio-16x9"> <iframe
                            src="{{ str_replace('watch?v=', 'embed/', $post->video_url) }}" allowfullscreen> </iframe>
                    </div> {{-- IMAGE --}}
                @else
                    <img src="{{ $image }}" alt="{{ $post->title }}" loading="lazy"
                        class="img-fluid shadow border-radius-lg" style="height:250px;width:100%;object-fit:cover;">
                    @endif </a> {{-- FEATURED BADGE --}} @if ($post->is_featured)
                <span class="badge bg-warning position-absolute top-0 end-0 m-2"> Featured </span>
            @endif
        </div> {{-- ========================================= BODY ========================================== --}} <div class="card-body px-1 pt-3 d-flex flex-column">
            {{-- CATEGORY + LOCATION --}}  {{-- TITLE --}} <a href="{{ $post->url }}" class="text-decoration-none text-dark">
                <h5 class="mb-2"> {{ \Illuminate\Support\Str::limit($post->title, 60) }} </h5>
            </a> <div class="mb-2">
                @if ($post->locality)
                    <span class="badge bg-light text-dark"> 📍 {{ $post->locality?->name }} </span>
                    @endif @if ($post->category)
                        <span class="badge bg-primary"> {{ $post->category?->name }} </span>
                        @endif @if ($post->subcategory)
                            <span class="badge bg-info"> {{ $post->subcategory?->name }} </span>
                        @endif
            </div>
            {{-- DESCRIPTION --}} <p class="text-sm text-muted flex-grow-1">
                {{ \Illuminate\Support\Str::limit(strip_tags($post->description), 80) }} </p> {{-- ========================================= META ========================================== --}}
            <div class="d-flex align-items-center justify-content-between mb-3"> {{-- DATE --}} <small
                    class="text-muted"> <i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }} </small>
            </div> {{-- ========================================= STATS ========================================== --}} <div class="d-flex align-items-center gap-3 mb-3"> {{-- LIKE --}}
                <button class="btn btn-sm likeBtn {{ $liked ? 'liked' : '' }}" data-id="{{ $post->id }}"> ❤️ <span
                        id="like-count-{{ $post->id }}"> {{ number_format($post->likes) }} </span> </button>
                <button class="btn btn-sm {{ $liked ? 'liked' : '' }}" data-id="{{ $post->id }}"> 👁 <span>
                        {{ number_format($post->views) }} </span> </button> <button
                    class="btn btn-sm {{ $liked ? 'liked' : '' }}" data-id="{{ $post->id }}"> 🔄 <span>
                        {{ number_format($post->shares) }} </span> </button>
            </div> {{-- ========================================= BUTTONS ========================================== --}} <div class="d-flex gap-2"> {{-- DETAILS --}} <a
                    href="{{ $post->url }}" class="btn btn-outline-primary btn-sm w-100"> View Details </a>
                {{-- SHARE --}} <button class="btn btn-outline-dark btn-sm shareBtn"
                    data-id="{{ $post->id }}" data-url="{{ $post->url }}"> Share </button> </div>
        </div>
    </div>
</div>
