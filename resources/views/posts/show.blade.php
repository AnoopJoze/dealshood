@extends('layouts.user_type.auth')

@section('content')

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
<style>
    /* ── Design tokens ─────────────────────────────────────────── */
    :root {
        --ink:        #0f0f0f;
        --ink-muted:  #6b7280;
        --ink-faint:  #9ca3af;
        --rule:       #e5e7eb;
        --surface:    #ffffff;
        --surface-2:  #f9fafb;
        --accent:     #1a56db;
        --accent-lt:  #eff6ff;
        --danger:     #ef4444;
        --success:    #10b981;
        --warn:       #f59e0b;
        --radius:     1rem;
        --shadow-sm:  0 1px 3px rgba(0,0,0,.08);
        --shadow-md:  0 4px 20px rgba(0,0,0,.1);
        --shadow-lg:  0 12px 40px rgba(0,0,0,.14);
    }

    body { font-family: 'DM Sans', sans-serif; color: var(--ink); }

    /* ── Breadcrumb ────────────────────────────────────────────── */
    .ps-breadcrumb { font-size: .78rem; color: var(--ink-muted); margin-bottom: 1.5rem; }
    .ps-breadcrumb a { color: var(--ink-muted); text-decoration: none; }
    .ps-breadcrumb a:hover { color: var(--accent); }
    .ps-breadcrumb .sep { margin: 0 .4rem; }

    /* ── Hero area ─────────────────────────────────────────────── */
    .ps-hero {
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        background: var(--ink);
        min-height: 340px;
        display: flex;
        align-items: flex-end;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
    }
    .ps-hero-img {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        opacity: .55;
        transition: opacity .4s;
    }
    .ps-hero:hover .ps-hero-img { opacity: .45; }
    .ps-hero-gradient {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.75) 0%, rgba(0,0,0,.1) 60%);
    }
    .ps-hero-body {
        position: relative;
        padding: 2.5rem;
        width: 100%;
    }
    .ps-hero-body .post-title {
        font-family: 'DM Serif Display', serif;
        font-size: clamp(1.6rem, 4vw, 2.8rem);
        line-height: 1.18;
        color: #fff;
        margin: 0 0 1rem;
        text-shadow: 0 2px 12px rgba(0,0,0,.4);
    }
    .ps-hero-meta { display: flex; flex-wrap: wrap; gap: .6rem; align-items: center; }
    .ps-hero-meta .badge-cat {
        background: rgba(255,255,255,.15);
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255,255,255,.25);
        color: #fff;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
        padding: .3rem .8rem;
        border-radius: 2rem;
    }
    .ps-hero-meta .badge-status {
        font-size: .72rem; font-weight: 600;
        letter-spacing: .04em; text-transform: uppercase;
        padding: .3rem .9rem; border-radius: 2rem;
    }
    .ps-hero-no-image {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        min-height: 200px;
    }

    /* ── Card shell ────────────────────────────────────────────── */
    .ps-card {
        background: var(--surface);
        border: 1px solid var(--rule);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 1.75rem;
        margin-bottom: 1.5rem;
    }
    .ps-card-title {
        font-family: 'DM Serif Display', serif;
        font-size: 1rem;
        font-style: italic;
        color: var(--ink-muted);
        letter-spacing: .02em;
        margin-bottom: 1.25rem;
        padding-bottom: .75rem;
        border-bottom: 1px solid var(--rule);
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .ps-card-title i { font-size: .85rem; color: var(--accent); }

    /* ── Stat pills row ────────────────────────────────────────── */
    .ps-stats { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem; }
    .ps-stat {
        flex: 1 1 140px;
        background: var(--surface-2);
        border: 1px solid var(--rule);
        border-radius: var(--radius);
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: .85rem;
    }
    .ps-stat-icon {
        width: 40px; height: 40px; border-radius: .6rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .ps-stat-icon.blue   { background: var(--accent-lt); color: var(--accent); }
    .ps-stat-icon.green  { background: #ecfdf5;          color: var(--success); }
    .ps-stat-icon.amber  { background: #fffbeb;          color: var(--warn); }
    .ps-stat-icon.red    { background: #fef2f2;          color: var(--danger); }
    .ps-stat-value { font-size: 1.4rem; font-weight: 700; line-height: 1; }
    .ps-stat-label { font-size: .72rem; color: var(--ink-muted); margin-top: .2rem; text-transform: uppercase; letter-spacing: .05em; }

    /* ── Meta list ─────────────────────────────────────────────── */
    .ps-meta-list { list-style: none; padding: 0; margin: 0; }
    .ps-meta-list li {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        padding: .65rem 0;
        border-bottom: 1px solid var(--rule);
        font-size: .875rem;
    }
    .ps-meta-list li:last-child { border-bottom: none; }
    .ps-meta-list .meta-label {
        width: 110px; flex-shrink: 0;
        color: var(--ink-muted);
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
        padding-top: .1rem;
    }
    .ps-meta-list .meta-value { color: var(--ink); font-weight: 500; word-break: break-word; }

    /* ── Description body ──────────────────────────────────────── */
    .ps-description {
        font-size: .925rem;
        line-height: 1.8;
        color: #374151;
    }
    .ps-description img  { max-width: 100%; border-radius: .5rem; }
    .ps-description h1,
    .ps-description h2,
    .ps-description h3   { font-family: 'DM Serif Display', serif; }
    .ps-description p    { margin-bottom: 1rem; }
    .ps-description a    { color: var(--accent); }

    /* ── Image gallery ─────────────────────────────────────────── */
    .ps-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: .6rem;
    }
    .ps-gallery a {
        display: block;
        aspect-ratio: 1;
        border-radius: .55rem;
        overflow: hidden;
        border: 2px solid var(--rule);
        transition: border-color .2s, transform .2s;
    }
    .ps-gallery a:hover { border-color: var(--accent); transform: scale(1.03); }
    .ps-gallery img { width: 100%; height: 100%; object-fit: cover; }
    .ps-gallery-empty { color: var(--ink-faint); font-size: .875rem; }

    /* ── Map embed ─────────────────────────────────────────────── */
    .ps-map-frame {
        width: 100%; height: 260px;
        border-radius: .65rem;
        border: 1px solid var(--rule);
        overflow: hidden;
    }
    .ps-map-frame iframe { width: 100%; height: 100%; border: none; }

    /* ── Video embed ───────────────────────────────────────────── */
    .ps-video-wrap {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        border-radius: .65rem;
        overflow: hidden;
        background: #000;
    }
    .ps-video-wrap iframe,
    .ps-video-wrap video {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        border: none;
    }

    /* ── Status & toggle row ───────────────────────────────────── */
    .ps-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        align-items: center;
        padding: 1rem 1.5rem;
        background: var(--surface-2);
        border: 1px solid var(--rule);
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
    }
    .ps-actions .spacer { flex: 1; }

    /* ── Activity log ──────────────────────────────────────────── */
    .ps-log { list-style: none; padding: 0; margin: 0; }
    .ps-log li {
        display: flex; gap: .75rem; align-items: flex-start;
        padding: .65rem 0; border-bottom: 1px solid var(--rule);
        font-size: .825rem;
    }
    .ps-log li:last-child { border-bottom: none; }
    .ps-log .dot {
        width: 8px; height: 8px; border-radius: 50%;
        margin-top: .35rem; flex-shrink: 0;
    }
    .ps-log .log-msg  { color: var(--ink); }
    .ps-log .log-time { color: var(--ink-faint); font-size: .75rem; }

    /* ── User card ─────────────────────────────────────────────── */
    .ps-user-card {
        display: flex; gap: .85rem; align-items: center;
        padding: .75rem;
        background: var(--surface-2);
        border-radius: .65rem;
        border: 1px solid var(--rule);
    }
    .ps-user-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        background: var(--accent);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 1rem; flex-shrink: 0;
        text-transform: uppercase;
    }
    .ps-user-name  { font-weight: 600; font-size: .875rem; }
    .ps-user-email { font-size: .75rem; color: var(--ink-muted); }

    /* ── Responsive tweaks ─────────────────────────────────────── */
    @media (max-width: 767px) {
        .ps-hero-body { padding: 1.5rem; }
        .ps-stat      { flex: 1 1 120px; }
        .ps-meta-list .meta-label { width: 90px; }
    }
</style>
@endpush

{{-- ── Breadcrumb ──────────────────────────────────────────────────────────── --}}
<nav class="ps-breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span class="sep">/</span>
    <a href="{{ route('posts.index') }}">Posts</a>
    <span class="sep">/</span>
    <span>{{ Str::limit($post->title, 40) }}</span>
</nav>

{{-- ── Action bar (status + quick buttons) ───────────────────────────────── --}}
<div class="ps-actions">

    {{-- Status badge --}}
    @php
        $statusColor = match($post->status) {
            'published' => 'bg-success',
            'archived'  => 'bg-warning text-dark',
            default     => 'bg-secondary',
        };
    @endphp
    <span class="badge {{ $statusColor }} px-3 py-2 rounded-pill">
        {{ ucfirst($post->status) }}
    </span>

    @if($post->is_featured)
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
            <i class="fas fa-star me-1"></i>Featured
        </span>
    @endif

    @if(!$post->is_active)
        <span class="badge bg-danger px-3 py-2 rounded-pill">Inactive</span>
    @endif

    <div class="spacer"></div>

    {{-- Edit button → opens the list page modal via JS --}}
    <a href="{{ route('posts.index') }}#edit-{{ $post->id }}"
       class="btn btn-sm btn-outline-primary"
       id="editPostBtn"
       data-id="{{ $post->id }}">
        <i class="fas fa-pen me-1"></i> Edit
    </a>

    {{-- Back --}}
    <a href="{{ route('posts.index') }}" class="btn btn-sm btn-light">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

    

</div>

<div class="row g-4">

    {{-- ── LEFT COLUMN ──────────────────────────────────────── --}}
    <div class="col-lg-8">

        {{-- Hero image / title block --}}
        <div class="ps-hero {{ $post->getMedia('posts')->isEmpty() ? 'ps-hero-no-image' : '' }}">
            @php $heroImg = $post->getMedia('posts')->first(); @endphp
            @if($heroImg)
                <img src="{{ $heroImg->getUrl() }}" class="ps-hero-img" alt="{{ $post->title }}">
            @endif
            <div class="ps-hero-gradient"></div>
            <div class="ps-hero-body">
                <h1 class="post-title">{{ $post->title }}</h1>
                <div class="ps-hero-meta">
                    @if($post->category)
                        <span class="badge-cat">{{ $post->category->name }}</span>
                    @endif
                    @if($post->subcategory)
                        <span class="badge-cat">{{ $post->subcategory->name }}</span>
                    @endif
                    @if($post->locality)
                        <span class="badge-cat"><i class="fas fa-map-marker-alt me-1"></i>{{ $post->locality->name }}</span>
                    @endif
                    <span class="badge-cat" style="background:rgba(255,255,255,.08)">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $post->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ── Stat pills ─────────────────────────────────── --}}
        <div class="ps-stats">
            <div class="ps-stat">
                <div class="ps-stat-icon blue"><i class="fas fa-eye"></i></div>
                <div>
                    <div class="ps-stat-value">{{ number_format($post->views ?? 0) }}</div>
                    <div class="ps-stat-label">Views</div>
                </div>
            </div>
            <div class="ps-stat">
                <div class="ps-stat-icon green"><i class="fas fa-heart"></i></div>
                <div>
                    <div class="ps-stat-value">{{ number_format($post->likesData->count()) }}</div>
                    <div class="ps-stat-label">Likes</div>
                </div>
            </div>
            <div class="ps-stat">
                <div class="ps-stat-icon amber"><i class="fas fa-share-alt"></i></div>
                <div>
                    <div class="ps-stat-value">{{ number_format($post->sharesData->count()) }}</div>
                    <div class="ps-stat-label">Shares</div>
                </div>
            </div>
            <div class="ps-stat">
                <div class="ps-stat-icon red"><i class="fas fa-images"></i></div>
                <div>
                    <div class="ps-stat-value">{{ $post->getMedia('posts')->count() }}</div>
                    <div class="ps-stat-label">Images</div>
                </div>
            </div>
        </div>

        {{-- ── Description ─────────────────────────────────── --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-align-left"></i> Description</div>
            @if($post->description)
                <div class="ps-description">
                    {!! $post->description !!}
                </div>
            @else
                <p class="text-muted mb-0" style="font-size:.875rem">No description provided.</p>
            @endif
        </div>

        {{-- ── Image gallery ───────────────────────────────── --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-images"></i> Gallery</div>
            @php $allMedia = $post->getMedia('posts'); @endphp
            @if($allMedia->count())
                <div class="ps-gallery">
                    @foreach($allMedia as $m)
                        <a href="{{ $m->getUrl() }}"
                           data-fancybox="post-gallery"
                           data-caption="{{ $m->name }}">
                            <img src="{{ $m->getUrl() }}" alt="{{ $m->name }}" loading="lazy">
                        </a>
                    @endforeach
                </div>
            @else
                <p class="ps-gallery-empty mb-0"><i class="fas fa-image me-2"></i>No images uploaded yet.</p>
            @endif
        </div>

        {{-- ── Video ───────────────────────────────────────── --}}
        @if($post->video_url || $post->getMedia('videos')->isNotEmpty())
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-film"></i> Video</div>

            @if($post->video_url)
                @php
                    // Convert YouTube watch URL → embed URL
                    $videoUrl = $post->video_url;
                    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $m)) {
                        $videoUrl = 'https://www.youtube.com/embed/' . $m[1];
                    } elseif (str_contains($videoUrl, 'vimeo.com')) {
                        preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m);
                        $videoUrl = 'https://player.vimeo.com/video/' . ($m[1] ?? '');
                    }
                @endphp
                <div class="ps-video-wrap">
                    <iframe src="{{ $videoUrl }}"
                            allow="autoplay; encrypted-media; fullscreen"
                            allowfullscreen></iframe>
                </div>
            @elseif($post->getMedia('videos')->isNotEmpty())
                @php $vid = $post->getMedia('videos')->first(); @endphp
                <div class="ps-video-wrap">
                    <video controls>
                        <source src="{{ $vid->getUrl() }}" type="{{ $vid->mime_type }}">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @endif
        </div>
        @endif

        {{-- ── Map ─────────────────────────────────────────── --}}
        @if($post->latitude && $post->longitude)
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-map-marked-alt"></i> Location</div>
            <div class="ps-map-frame">
                <iframe
                    src="https://maps.google.com/maps?q={{ $post->latitude }},{{ $post->longitude }}&z=15&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <div class="mt-2 d-flex gap-2">
                <span class="text-muted" style="font-size:.8rem">
                    <i class="fas fa-crosshairs me-1"></i>
                    {{ $post->latitude }}, {{ $post->longitude }}
                </span>
                <a href="https://www.google.com/maps?q={{ $post->latitude }},{{ $post->longitude }}"
                   target="_blank"
                   class="ms-auto btn btn-sm btn-outline-secondary">
                    <i class="fas fa-external-link-alt me-1"></i>Open in Maps
                </a>
            </div>
        </div>
        @elseif($post->google_map_url)
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-map-marked-alt"></i> Location</div>
            <a href="{{ $post->google_map_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-external-link-alt me-1"></i>Open in Google Maps
            </a>
        </div>
        @endif

    </div>{{-- /col-lg-8 --}}


    {{-- ── RIGHT COLUMN ─────────────────────────────────────── --}}
    <div class="col-lg-4">

        {{-- Post details meta --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-info-circle"></i> Post Details</div>
            <ul class="ps-meta-list">
                <li>
                    <span class="meta-label">ID</span>
                    <span class="meta-value text-muted">#{{ $post->id }}</span>
                </li>
                <li>
                    <span class="meta-label">Status</span>
                    <span class="meta-value">
                        @php
                            $sc = match($post->status) {
                                'published' => 'text-success',
                                'archived'  => 'text-warning',
                                default     => 'text-secondary',
                            };
                        @endphp
                        <span class="{{ $sc }} fw-semibold">{{ ucfirst($post->status) }}</span>
                    </span>
                </li>
                <li>
                    <span class="meta-label">Category</span>
                    <span class="meta-value">{{ $post->category?->name ?? '—' }}</span>
                </li>
                <li>
                    <span class="meta-label">Subcategory</span>
                    <span class="meta-value">{{ $post->subcategory?->name ?? '—' }}</span>
                </li>
                <li>
                    <span class="meta-label">Locality</span>
                    <span class="meta-value">{{ $post->locality?->name ?? '—' }}</span>
                </li>
                <li>
                    <span class="meta-label">Expiry</span>
                    <span class="meta-value">
                        @if($post->expiry_date)
                            @php $exp = \Carbon\Carbon::parse($post->expiry_date); @endphp
                            @if(now()->gt($exp))
                                <span class="text-danger fw-semibold">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    Expired {{ $exp->format('d M Y') }}
                                </span>
                            @else
                                {{ $exp->format('d M Y') }}
                                <small class="text-muted">({{ $exp->diffForHumans() }})</small>
                            @endif
                        @else
                            <span class="text-muted">No expiry</span>
                        @endif
                    </span>
                </li>
                <li>
                    <span class="meta-label">Featured</span>
                    <span class="meta-value">
                        @if($post->is_featured)
                            <span class="text-warning"><i class="fas fa-star"></i> Yes</span>
                        @else
                            <span class="text-muted">No</span>
                        @endif
                    </span>
                </li>
                <li>
                    <span class="meta-label">Active</span>
                    <span class="meta-value">
                        @if($post->is_active)
                            <span class="text-success"><i class="fas fa-check-circle"></i> Yes</span>
                        @else
                            <span class="text-danger"><i class="fas fa-times-circle"></i> No</span>
                        @endif
                    </span>
                </li>
                <li>
                    <span class="meta-label">Published</span>
                    <span class="meta-value text-muted">
                        {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d M Y, H:i') : '—' }}
                    </span>
                </li>
                <li>
                    <span class="meta-label">Created</span>
                    <span class="meta-value text-muted">
                        {{ $post->created_at->format('d M Y, H:i') }}
                        <small class="d-block">{{ $post->created_at->diffForHumans() }}</small>
                    </span>
                </li>
                <li>
                    <span class="meta-label">Updated</span>
                    <span class="meta-value text-muted">
                        {{ $post->updated_at->format('d M Y, H:i') }}
                    </span>
                </li>
            </ul>
        </div>

        {{-- Assigned user --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-user"></i> Assigned User</div>
            @if($post->user)
                <div class="ps-user-card">
                    <div class="ps-user-avatar">
                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="ps-user-name">{{ $post->user->name }}</div>
                        <div class="ps-user-email">{{ $post->user->email }}</div>
                    </div>
                </div>
            @else
                <p class="text-muted mb-0" style="font-size:.875rem">No user assigned.</p>
            @endif
        </div>

        {{-- SEO / Meta --}}
        @if($post->meta_title || $post->meta_description || $post->keywords)
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-search"></i> SEO</div>
            <ul class="ps-meta-list">
                @if($post->meta_title)
                <li>
                    <span class="meta-label">Meta Title</span>
                    <span class="meta-value">{{ $post->meta_title }}</span>
                </li>
                @endif
                @if($post->meta_description)
                <li>
                    <span class="meta-label">Meta Desc</span>
                    <span class="meta-value">{{ $post->meta_description }}</span>
                </li>
                @endif
                @if($post->keywords)
                <li>
                    <span class="meta-label">Keywords</span>
                    <span class="meta-value">
                        @foreach(explode(',', $post->keywords) as $kw)
                            <span class="badge bg-light text-dark border me-1 mb-1">{{ trim($kw) }}</span>
                        @endforeach
                    </span>
                </li>
                @endif
            </ul>
        </div>
        @endif

        {{-- Public URL --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-link"></i> Public URL</div>
                @php $publicUrl = $post->url; @endphp
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control border" id="publicUrlInput"
                           value="{{ $publicUrl }}" readonly>
                    <button class="btn btn-outline-secondary" id="copyUrlBtn" title="Copy">
                        <i class="fas fa-copy">Copy</i>
                    </button>
                </div>
                <a href="{{ $publicUrl }}" target="_blank"
                   class="btn btn-sm btn-outline-primary w-100 mt-2">
                    <i class="fas fa-external-link-alt me-1"></i>View Public Post
                </a>
        </div>

        {{-- Activity log (created / updated / published) --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-history"></i> Activity</div>
            <ul class="ps-log">
                @if($post->published_at)
                <li>
                    <span class="dot" style="background:var(--success)"></span>
                    <div>
                        <div class="log-msg">Post published</div>
                        <div class="log-time">{{ \Carbon\Carbon::parse($post->published_at)->format('d M Y, H:i') }}</div>
                    </div>
                </li>
                @endif
                <li>
                    <span class="dot" style="background:var(--accent)"></span>
                    <div>
                        <div class="log-msg">Post created</div>
                        <div class="log-time">{{ $post->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </li>
                @if($post->updated_at->ne($post->created_at))
                <li>
                    <span class="dot" style="background:var(--warn)"></span>
                    <div>
                        <div class="log-msg">Last updated</div>
                        <div class="log-time">{{ $post->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </li>
                @endif
                @if($post->deleted_at)
                <li>
                    <span class="dot" style="background:var(--danger)"></span>
                    <div>
                        <div class="log-msg">Soft deleted</div>
                        <div class="log-time">{{ $post->deleted_at->format('d M Y, H:i') }}</div>
                    </div>
                </li>
                @endif
            </ul>
        </div>

    </div>{{-- /col-lg-4 --}}
</div>


{{-- ══════════════════════════
     DELETE CONFIRM MODAL
     ══════════════════════════ --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Post
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-1 fw-semibold">{{ $post->title }}</p>
                <p class="text-muted mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2">
                <button class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger px-4" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection


@push('js')
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
// ── Fancybox ──────────────────────────────────────────────────────────────────
Fancybox.bind('[data-fancybox="post-gallery"]', { Toolbar: { display: ['close'] } });

// ── Copy public URL ───────────────────────────────────────────────────────────
$('#copyUrlBtn').on('click', function () {
    const val = $('#publicUrlInput').val();
    navigator.clipboard.writeText(val).then(() => {
        $(this).html('<i class="fas fa-check text-success"></i>');
        setTimeout(() => $(this).html('<i class="fas fa-copy"></i>'), 1800);
    });
});

// ── Delete post ───────────────────────────────────────────────────────────────
$('#deletePostBtn').on('click', function () {
    $('#deleteModal').modal('show');
});

$('#confirmDelete').on('click', function () {
    $.ajax({
        url   : '{{ url("admin/posts") }}/{{ $post->id }}',
        type  : 'POST',
        data  : { _token: '{{ csrf_token() }}', _method: 'DELETE' },
        success: function (res) {
            if (res.success) {
                $('#deleteModal').modal('hide');
                Swal.fire({
                    icon : 'success',
                    title: 'Post deleted',
                    timer: 1200,
                    showConfirmButton: false,
                }).then(() => window.location.href = '{{ route("posts.index") }}');
            }
        },
    });
});

// ── Edit button: go to list page and trigger edit modal ───────────────────────
// (stores the post ID in sessionStorage so list.blade can pick it up)
$('#editPostBtn').on('click', function (e) {
    e.preventDefault();
    sessionStorage.setItem('autoEditPostId', '{{ $post->id }}');
    window.location.href = '{{ route("posts.index") }}';
});
</script>
@endpush