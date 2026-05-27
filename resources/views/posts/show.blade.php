@extends('layouts.user_type.auth')

@section('content')

@push('css')
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<style>
    :root {
        --ink:     #111827; --ink-2: #6b7280; --ink-3: #9ca3af;
        --rule:    #e5e7eb; --surf:  #fff;    --surf-2: #f9fafb;
        --accent:  #1a56db; --success:#10b981; --warn:#f59e0b; --danger:#ef4444;
        --r: 1rem; --sh: 0 1px 3px rgba(0,0,0,.08);
    }
    body { font-family:'DM Sans',sans-serif; color:var(--ink); }

    /* Breadcrumb */
    .bc { font-size:.78rem; color:var(--ink-2); margin-bottom:1.5rem; }
    .bc a { color:var(--ink-2); text-decoration:none; }
    .bc a:hover { color:var(--accent); }
    .bc .sep { margin:0 .4rem; color:var(--ink-3); }

    /* Action bar */
    .act-bar {
        display:flex; flex-wrap:wrap; gap:.6rem; align-items:center;
        padding:.9rem 1.25rem; background:var(--surf);
        border:1px solid var(--rule); border-radius:var(--r);
        margin-bottom:1.5rem; box-shadow:var(--sh);
    }
    .act-bar .spacer { flex:1; }

    /* Hero */
    .ps-hero {
        position:relative; border-radius:var(--r); overflow:hidden;
        background:var(--ink); min-height:320px;
        display:flex; align-items:flex-end;
        margin-bottom:1.5rem; box-shadow:0 8px 30px rgba(0,0,0,.18);
    }
    .ps-hero img.hero-img {
        position:absolute; inset:0; width:100%; height:100%;
        object-fit:cover; opacity:.55;
    }
    .ps-hero .hero-grad {
        position:absolute; inset:0;
        background:linear-gradient(to top,rgba(0,0,0,.78) 0%,rgba(0,0,0,.05) 65%);
    }
    .ps-hero .hero-body { position:relative; padding:2rem 2.25rem; width:100%; }
    .ps-hero .hero-title {
        font-family:'DM Serif Display',serif;
        font-size:clamp(1.5rem,3.5vw,2.5rem); line-height:1.2;
        color:#fff; margin:0 0 .9rem;
        text-shadow:0 2px 12px rgba(0,0,0,.5);
    }
    .ps-hero .hero-meta { display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; }
    .ps-hero .hero-badge {
        background:rgba(255,255,255,.15); backdrop-filter:blur(6px);
        border:1px solid rgba(255,255,255,.2); color:#fff;
        font-size:.7rem; font-weight:600; letter-spacing:.05em;
        text-transform:uppercase; padding:.28rem .75rem; border-radius:2rem;
    }
    .ps-hero-plain { background:linear-gradient(135deg,#1e293b,#334155); min-height:180px; }

    /* Stats */
    .ps-stats { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:1.5rem; }
    .ps-stat {
        flex:1 1 120px; background:var(--surf-2); border:1px solid var(--rule);
        border-radius:var(--r); padding:1rem 1.1rem;
        display:flex; align-items:center; gap:.75rem;
    }
    .ps-stat .icon {
        width:36px; height:36px; border-radius:.55rem;
        display:flex; align-items:center; justify-content:center;
        font-size:.9rem; flex-shrink:0;
    }
    .ps-stat .val { font-size:1.25rem; font-weight:700; line-height:1; }
    .ps-stat .lbl { font-size:.65rem; color:var(--ink-3); text-transform:uppercase; letter-spacing:.05em; margin-top:2px; }

    /* Cards */
    .ps-card {
        background:var(--surf); border:1px solid var(--rule);
        border-radius:var(--r); padding:1.5rem;
        margin-bottom:1.25rem; box-shadow:var(--sh);
    }
    .ps-card-title {
        font-size:.7rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.06em; color:var(--ink-3);
        margin-bottom:1rem; padding-bottom:.75rem; border-bottom:1px solid var(--rule);
        display:flex; align-items:center; gap:.45rem;
    }
    .ps-card-title i { color:var(--accent); }

    /* Meta list */
    .ps-meta { list-style:none; padding:0; margin:0; }
    .ps-meta li {
        display:flex; align-items:flex-start; gap:.75rem;
        padding:.6rem 0; border-bottom:1px solid var(--rule); font-size:.85rem;
    }
    .ps-meta li:last-child { border-bottom:none; }
    .ps-meta .ml {
        width:105px; flex-shrink:0; font-size:.7rem; font-weight:600;
        letter-spacing:.04em; text-transform:uppercase; color:var(--ink-3); padding-top:.1rem;
    }
    .ps-meta .mv { color:var(--ink); font-weight:500; word-break:break-word; }
    .ps-meta .mv.empty { color:var(--ink-3); font-style:italic; font-weight:400; }

    /* Description */
    .ps-body { font-size:.9rem; line-height:1.85; color:#374151; }
    .ps-body img { max-width:100%; border-radius:.5rem; }
    .ps-body h1,.ps-body h2,.ps-body h3 { font-family:'DM Serif Display',serif; }
    .ps-body p { margin-bottom:.85rem; }

    /* Gallery */
    .ps-gallery {
        display:grid; grid-template-columns:repeat(auto-fill,minmax(88px,1fr)); gap:.5rem;
    }
    .ps-gallery a {
        display:block; aspect-ratio:1; border-radius:.5rem; overflow:hidden;
        border:2px solid var(--rule); transition:border-color .18s,transform .18s;
    }
    .ps-gallery a:hover { border-color:var(--accent); transform:scale(1.04); }
    .ps-gallery img { width:100%; height:100%; object-fit:cover; }

    /* Map */
    .ps-map { border-radius:.65rem; overflow:hidden; border:1px solid var(--rule); height:230px; }
    .ps-map iframe { width:100%; height:100%; border:none; }

    /* Video */
    .ps-video { position:relative; padding-bottom:56.25%; border-radius:.65rem; overflow:hidden; background:#000; }
    .ps-video iframe,.ps-video video { position:absolute; inset:0; width:100%; height:100%; border:none; }

    /* User card */
    .ps-user {
        display:flex; gap:.8rem; align-items:center; padding:.75rem;
        background:var(--surf-2); border:1px solid var(--rule); border-radius:.65rem;
    }
    .ps-user .av {
        width:42px; height:42px; border-radius:50%; background:var(--accent);
        color:#fff; font-weight:700; display:flex; align-items:center;
        justify-content:center; font-size:1rem; flex-shrink:0; text-transform:uppercase;
    }
    .ps-user .uname { font-weight:600; font-size:.875rem; }
    .ps-user .umail { font-size:.74rem; color:var(--ink-2); }

    /* Timeline */
    .ps-log { list-style:none; padding:0; margin:0; }
    .ps-log li {
        display:flex; gap:.7rem; align-items:flex-start;
        padding:.6rem 0; border-bottom:1px solid var(--rule); font-size:.82rem;
    }
    .ps-log li:last-child { border-bottom:none; }
    .ps-log .dot { width:8px; height:8px; border-radius:50%; margin-top:.35rem; flex-shrink:0; }
    .ps-log .lt { color:var(--ink-3); font-size:.74rem; }

    /* SEO preview */
    .seo-preview {
        background:#fff; border:1px solid #dadce0; border-radius:.5rem;
        padding:1rem 1.25rem; font-family:Arial,sans-serif;
    }
    .seo-title { color:#1a0dab; font-size:1.05rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .seo-url   { color:#006621; font-size:.78rem; margin:.15rem 0; }
    .seo-desc  { color:#545454; font-size:.83rem; line-height:1.5; }

    /* ── Edit modal tabs ──────────────────────────────────── */
    .modal-tab-nav .nav-link {
        color:#6c757d; border-radius:8px;
        padding:6px 14px; font-size:.75rem; font-weight:600;
    }
    .modal-tab-nav .nav-link.active {
        background:linear-gradient(195deg,#42424a,#191919); color:#fff;
    }
    .modal-tab-nav .tab-err-dot {
        display:inline-block; width:7px; height:7px;
        background:#ea0606; border-radius:50%;
        margin-left:5px; vertical-align:middle;
    }

    /* ── Dropzone ─────────────────────────────────────────── */
    .dropzone {
        border:2px dashed #dee2e6; border-radius:.75rem;
        background:#f8f9fa; min-height:80px; padding:1rem;
    }
    .dropzone:hover { border-color:#6c757d; }
    .dropzone .dz-message { margin:.5em 0; font-size:.85rem; color:#9e9e9e; }

    /* ── Image strip ──────────────────────────────────────── */
    .img-strip { display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:.75rem; }
    .img-strip .img-wrap { position:relative; }
    .img-strip img {
        width:58px; height:58px; object-fit:cover;
        border-radius:.45rem; border:2px solid #dee2e6;
        cursor:pointer; transition:border-color .2s;
    }
    .img-strip img:hover { border-color:#6c757d; }
    .img-strip .btn-del-media {
        position:absolute; top:-6px; right:-6px;
        width:18px; height:18px; padding:0; font-size:9px;
        border-radius:50%; line-height:18px; text-align:center;
    }
</style>
@endpush

{{-- Breadcrumb --}}
<nav class="bc">
    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home me-1"></i>Dashboard</a>
    <span class="sep">/</span>
    <a href="{{ route('posts.index') }}">Posts</a>
    <span class="sep">/</span>
    <span>{{ Str::limit($post->title, 45) }}</span>
</nav>

{{-- ── Action bar ─────────────────────────────────────────── --}}
<div class="act-bar">

    @php
        [$sbg, $stc] = match($post->status) {
            'published' => ['bg-success-subtle','text-success'],
            'archived'  => ['bg-warning-subtle','text-warning'],
            default     => ['bg-secondary-subtle','text-secondary'],
        };
    @endphp

    <span class="badge {{ $sbg }} {{ $stc }} rounded-pill px-3 py-2">
        <span style="display:inline-block;width:6px;height:6px;border-radius:50%;
                     background:currentColor;vertical-align:middle;margin-right:5px;"></span>
        {{ ucfirst($post->status) }}
    </span>

    @if ($post->is_featured)
        <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
            <i class="fas fa-star me-1" style="font-size:.65rem;"></i>Featured
        </span>
    @endif

    @if (!$post->is_active)
        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
            <i class="fas fa-eye-slash me-1" style="font-size:.65rem;"></i>Inactive
        </span>
    @endif

    @if ($post->expiry_date && now()->gt($post->expiry_date))
        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
            <i class="fas fa-clock me-1" style="font-size:.65rem;"></i>Expired
        </span>
    @endif

    <div class="spacer"></div>

    <button class="btn btn-sm bg-gradient-warning" data-bs-toggle="modal" data-bs-target="#editModal">
        <i class="fas fa-pen me-1"></i> Edit Post
    </button>
    <button class="btn btn-sm btn-outline-danger" id="deletePostBtn">
        <i class="fas fa-trash me-1"></i> Delete
    </button>
    <a href="{{ route('posts.index') }}" class="btn btn-sm btn-light border">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

</div>

{{-- ════════════════════ PAGE BODY ════════════════════ --}}
<div class="row g-4">

    {{-- ── LEFT ──────────────────────────────────────── --}}
    <div class="col-lg-8">

        {{-- Hero --}}
        @php $heroImg = $post->getMedia('posts')->first(); @endphp
        <div class="ps-hero {{ !$heroImg ? 'ps-hero-plain' : '' }}">
            @if ($heroImg)
                <img src="{{ $heroImg->getUrl() }}" class="hero-img" alt="{{ $post->title }}">
            @endif
            <div class="hero-grad"></div>
            <div class="hero-body">
                <h1 class="hero-title">{{ $post->title }}</h1>
                <div class="hero-meta">
                    @if ($post->category)
                        <span class="hero-badge">{{ $post->category->name }}</span>
                    @endif
                    @if ($post->subcategory)
                        <span class="hero-badge">{{ $post->subcategory->name }}</span>
                    @endif
                    @if ($post->locality)
                        <span class="hero-badge">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $post->locality->name }}
                        </span>
                    @endif
                    <span class="hero-badge">
                        <i class="fas fa-calendar me-1"></i>{{ $post->created_at->format('d M Y') }}
                    </span>
                    @if ($post->views)
                        <span class="hero-badge">
                            <i class="fas fa-eye me-1"></i>{{ number_format($post->views) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="ps-stats">
            <div class="ps-stat">
                <div class="icon" style="background:#dbeafe;color:#1d4ed8;"><i class="fas fa-eye"></i></div>
                <div><div class="val">{{ number_format($post->views ?? 0) }}</div><div class="lbl">Views</div></div>
            </div>
            <div class="ps-stat">
                <div class="icon" style="background:#d1fae5;color:#059669;"><i class="fas fa-heart"></i></div>
                <div><div class="val">{{ number_format($post->likesData->count()) }}</div><div class="lbl">Likes</div></div>
            </div>
            <div class="ps-stat">
                <div class="icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-share-alt"></i></div>
                <div><div class="val">{{ number_format($post->sharesData->count()) }}</div><div class="lbl">Shares</div></div>
            </div>
            <div class="ps-stat">
                <div class="icon" style="background:#fce7f3;color:#db2777;"><i class="fas fa-images"></i></div>
                <div><div class="val">{{ $post->getMedia('posts')->count() }}</div><div class="lbl">Images</div></div>
            </div>
        </div>

        {{-- Description --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-align-left"></i> Description</div>
            @if ($post->description)
                <div class="ps-body">{!! $post->description !!}</div>
            @else
                <p class="text-muted text-sm mb-0">
                    <i class="fas fa-pen me-2 opacity-25"></i>No description added yet.
                </p>
            @endif
        </div>

        {{-- Gallery --}}
        @php $allMedia = $post->getMedia('posts'); @endphp
        <div class="ps-card">
            <div class="ps-card-title">
                <i class="fas fa-images"></i> Gallery
                <span class="ms-auto badge bg-light text-secondary rounded-pill">
                    {{ $allMedia->count() }} image(s)
                </span>
            </div>
            @if ($allMedia->count())
                <div class="ps-gallery">
                    @foreach ($allMedia as $m)
                        <a href="{{ $m->getUrl() }}" data-fancybox="post-gallery" data-caption="{{ $m->name }}">
                            <img src="{{ $m->getUrl() }}" alt="{{ $m->name }}" loading="lazy">
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-sm mb-0">
                    <i class="fas fa-image me-2 opacity-25"></i>No images uploaded yet.
                </p>
            @endif
        </div>

        {{-- Video --}}
        @if ($post->video_url || $post->getMedia('videos')->isNotEmpty())
            <div class="ps-card">
                <div class="ps-card-title"><i class="fas fa-film"></i> Video</div>
                @if ($post->video_url)
                    @php
                        $vUrl = $post->video_url;
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vUrl, $vm)) {
                            $vUrl = 'https://www.youtube.com/embed/' . $vm[1];
                        } elseif (str_contains($vUrl, 'vimeo.com')) {
                            preg_match('/vimeo\.com\/(\d+)/', $vUrl, $vm);
                            $vUrl = 'https://player.vimeo.com/video/' . ($vm[1] ?? '');
                        }
                    @endphp
                    <div class="ps-video">
                        <iframe src="{{ $vUrl }}" allow="autoplay;encrypted-media;fullscreen" allowfullscreen></iframe>
                    </div>
                @else
                    @php $vid = $post->getMedia('videos')->first(); @endphp
                    <div class="ps-video">
                        <video controls>
                            <source src="{{ $vid->getUrl() }}" type="{{ $vid->mime_type }}">
                        </video>
                    </div>
                @endif
            </div>
        @endif

        {{-- Map --}}
        @if ($post->latitude && $post->longitude)
            <div class="ps-card">
                <div class="ps-card-title"><i class="fas fa-map-marked-alt"></i> Location</div>
                @if ($post->country || $post->state || $post->city || $post->location)
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach (array_filter([$post->country, $post->state, $post->city, $post->location]) as $loc)
                            <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs">
                                <i class="fas fa-map-marker-alt me-1 text-muted"></i>{{ $loc }}
                            </span>
                        @endforeach
                    </div>
                @endif
                <div class="ps-map mb-3">
                    <iframe src="https://maps.google.com/maps?q={{ $post->latitude }},{{ $post->longitude }}&z=15&output=embed" loading="lazy"></iframe>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-light text-dark border rounded-3 px-3 py-2 text-xs fw-semibold">
                        <span class="text-muted me-1">LAT</span>{{ $post->latitude }}
                    </span>
                    <span class="badge bg-light text-dark border rounded-3 px-3 py-2 text-xs fw-semibold">
                        <span class="text-muted me-1">LNG</span>{{ $post->longitude }}
                    </span>
                    <a href="https://www.google.com/maps?q={{ $post->latitude }},{{ $post->longitude }}"
                       target="_blank" class="btn btn-sm btn-outline-primary ms-auto rounded-3 px-3">
                        <i class="fas fa-external-link-alt me-1"></i>Open in Google Maps
                    </a>
                </div>
            </div>
        @elseif ($post->google_map_url)
            <div class="ps-card">
                <div class="ps-card-title"><i class="fas fa-map-marked-alt"></i> Location</div>
                <a href="{{ $post->google_map_url }}" target="_blank"
                   class="btn btn-sm btn-outline-primary rounded-3 px-3">
                    <i class="fas fa-external-link-alt me-1"></i>Open in Google Maps
                </a>
            </div>
        @endif

        {{-- SEO Preview --}}
        @if ($post->meta_title || $post->meta_description || $post->keywords)
            <div class="ps-card">
                <div class="ps-card-title"><i class="fas fa-search"></i> SEO Preview</div>
                <div class="seo-preview mb-3">
                    <div class="seo-title">{{ $post->meta_title ?: $post->title }}</div>
                    <div class="seo-url">{{ url('/posts/' . $post->slug) }}</div>
                    <div class="seo-desc">
                        {{ $post->meta_description ?: Str::limit(strip_tags($post->description ?? ''), 160) }}
                    </div>
                </div>
                @if ($post->keywords)
                    <div class="d-flex flex-wrap gap-1 mt-2">
                        @foreach (explode(',', $post->keywords) as $kw)
                            <span class="badge bg-light text-secondary border rounded-pill text-xs">
                                {{ trim($kw) }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

    </div>{{-- /col-lg-8 --}}

    {{-- ── RIGHT ─────────────────────────────────────── --}}
    <div class="col-lg-4">

        {{-- Post Details --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-info-circle"></i> Post Details</div>
            <ul class="ps-meta">
                <li>
                    <span class="ml">ID</span>
                    <span class="mv text-muted">#{{ $post->id }}</span>
                </li>
                <li>
                    <span class="ml">Status</span>
                    <span class="mv">
                        @php $sc2 = match($post->status) { 'published'=>'text-success','archived'=>'text-warning',default=>'text-secondary' }; @endphp
                        <span class="{{ $sc2 }} fw-semibold">{{ ucfirst($post->status) }}</span>
                    </span>
                </li>
                <li>
                    <span class="ml">Category</span>
                    <span class="mv {{ !$post->category ? 'empty':'' }}">{{ $post->category?->name ?? 'Not set' }}</span>
                </li>
                <li>
                    <span class="ml">Subcategory</span>
                    <span class="mv {{ !$post->subcategory ? 'empty':'' }}">{{ $post->subcategory?->name ?? 'Not set' }}</span>
                </li>
                <li>
                    <span class="ml">Locality</span>
                    <span class="mv {{ !$post->locality ? 'empty':'' }}">{{ $post->locality?->name ?? 'Not set' }}</span>
                </li>
                <li>
                    <span class="ml">Featured</span>
                    <span class="mv">
                        @if ($post->is_featured)
                            <span class="text-warning"><i class="fas fa-star me-1" style="font-size:.75rem;"></i>Yes</span>
                        @else
                            <span class="text-muted">No</span>
                        @endif
                    </span>
                </li>
                <li>
                    <span class="ml">Active</span>
                    <span class="mv">
                        @if ($post->is_active)
                            <span class="text-success"><i class="fas fa-check-circle me-1" style="font-size:.75rem;"></i>Yes</span>
                        @else
                            <span class="text-danger"><i class="fas fa-times-circle me-1" style="font-size:.75rem;"></i>No</span>
                        @endif
                    </span>
                </li>
                <li>
                    <span class="ml">Expiry</span>
                    <span class="mv">
                        @if ($post->expiry_date)
                            @php $exp = \Carbon\Carbon::parse($post->expiry_date); @endphp
                            @if (now()->gt($exp))
                                <span class="text-danger fw-semibold">
                                    <i class="fas fa-exclamation-circle me-1" style="font-size:.75rem;"></i>
                                    Expired · {{ $exp->format('d M Y') }}
                                </span>
                            @else
                                {{ $exp->format('d M Y') }}
                                <small class="text-muted d-block">{{ $exp->diffForHumans() }}</small>
                            @endif
                        @else
                            <span class="empty">No expiry</span>
                        @endif
                    </span>
                </li>
                <li>
                    <span class="ml">Published</span>
                    <span class="mv text-muted {{ !$post->published_at ? 'empty':'' }}">
                        {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d M Y, H:i') : 'Not published' }}
                    </span>
                </li>
            </ul>
        </div>

        {{-- Assigned User --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-user"></i> Assigned User</div>
            @if ($post->user)
                <div class="ps-user">
                    <div class="av">{{ strtoupper(substr($post->user->name, 0, 1)) }}</div>
                    <div>
                        <div class="uname">{{ $post->user->name }}</div>
                        <div class="umail">{{ $post->user->email }}</div>
                    </div>
                </div>
            @else
                <p class="text-muted text-sm mb-0">No user assigned.</p>
            @endif
        </div>

        {{-- Public URL --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-link"></i> Public URL</div>
            <div class="input-group input-group-sm mb-2">
                <input type="text" class="form-control border text-xs" id="publicUrl"
                       value="{{ $post->url ?? url('/posts/' . $post->slug) }}" readonly>
                <button class="btn btn-outline-secondary" id="copyBtn" title="Copy">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <a href="{{ $post->url ?? url('/posts/' . $post->slug) }}" target="_blank"
               class="btn btn-sm btn-outline-primary w-100 rounded-3">
                <i class="fas fa-external-link-alt me-1"></i>View Public Post
            </a>
        </div>

        {{-- Activity --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-history"></i> Activity</div>
            <ul class="ps-log">
                @if ($post->published_at)
                    <li>
                        <span class="dot" style="background:var(--success)"></span>
                        <div>
                            <div class="fw-semibold">Published</div>
                            <div class="lt">{{ \Carbon\Carbon::parse($post->published_at)->format('d M Y, H:i') }}</div>
                        </div>
                    </li>
                @endif
                <li>
                    <span class="dot" style="background:var(--accent)"></span>
                    <div>
                        <div class="fw-semibold">Created</div>
                        <div class="lt">{{ $post->created_at->format('d M Y, H:i') }}</div>
                        <div class="lt">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </li>
                @if ($post->updated_at->ne($post->created_at))
                    <li>
                        <span class="dot" style="background:var(--warn)"></span>
                        <div>
                            <div class="fw-semibold">Last updated</div>
                            <div class="lt">{{ $post->updated_at->format('d M Y, H:i') }}</div>
                            <div class="lt">{{ $post->updated_at->diffForHumans() }}</div>
                        </div>
                    </li>
                @endif
            </ul>
        </div>

    </div>{{-- /col-lg-4 --}}
</div>

{{-- ═══════════════════════════════════════════════════════════
     EDIT MODAL  —  4 tabs, all fields, inline on show page
═══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">

            {{-- Header --}}
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-gradient-warning text-white d-flex align-items-center
                                justify-content-center shadow-sm"
                         style="width:44px;height:44px;flex-shrink:0;">
                        <i class="fas fa-pen"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Edit Post</h5>
                        <p class="text-xs text-muted mb-0">
                            #{{ $post->id }} &mdash; {{ Str::limit($post->title, 50) }}
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Tab nav --}}
            <div class="px-4 pt-2 pb-0 border-bottom">
                <ul class="nav modal-tab-nav gap-1 pb-2" id="editModalTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-tab="etab-basic" href="javascript:void(0)">
                            <i class="fas fa-align-left me-1"></i> Basic Info
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-tab="etab-location" href="javascript:void(0)">
                            <i class="fas fa-map-marker-alt me-1"></i> Location
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-tab="etab-media" href="javascript:void(0)">
                            <i class="fas fa-images me-1"></i> Media
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-tab="etab-seo" href="javascript:void(0)">
                            <i class="fas fa-search me-1"></i> SEO
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Body --}}
            <div class="modal-body px-4 py-3">

                {{-- ══ TAB 1 : Basic Info ══ --}}
                <div class="modal-tab-pane" id="etab-basic">

                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Post Information</p>
                    <div class="row g-3 mb-3">

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="e_title" class="form-control"
                                   value="{{ old('title', $post->title) }}"
                                   placeholder="Post title…">
                            <small class="text-danger d-none" id="e_err_title"></small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Description</label>
                            <textarea id="e_description" class="form-control" rows="5">{{ old('description', $post->description) }}</textarea>
                            <small class="text-danger d-none" id="e_err_description"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select id="e_category_id" class="form-select">
                                <option value="">— Select Category —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $post->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="e_err_category_id"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Subcategory</label>
                            <select id="e_subcategory_id" class="form-select">
                                <option value="">— Select Subcategory —</option>
                                {{-- populated via JS after category cascade --}}
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Locality</label>
                            <select id="e_locality_id" class="form-select">
                                <option value="">— Select Locality —</option>
                                @foreach ($localities as $loc)
                                    <option value="{{ $loc->id }}"
                                        {{ $post->locality_id == $loc->id ? 'selected' : '' }}>
                                        {{ $loc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select id="e_status" class="form-select">
                                @foreach (['draft'=>'Draft','published'=>'Published','archived'=>'Archived'] as $v => $l)
                                    <option value="{{ $v }}" {{ $post->status === $v ? 'selected' : '' }}>
                                        {{ $l }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="e_err_status"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Assigned User</label>
                            <select id="e_user_id" class="form-select">
                                <option value="">— Select User —</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}"
                                        {{ $post->user_id == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Expiry Date</label>
                            <input type="date" id="e_expiry_date" class="form-control"
                                   value="{{ $post->expiry_date ? \Carbon\Carbon::parse($post->expiry_date)->format('Y-m-d') : '' }}">
                        </div>

                    </div>

                    <hr class="horizontal dark my-3">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2">Options</p>
                    <div class="d-flex gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="e_is_featured"
                                   {{ $post->is_featured ? 'checked' : '' }}>
                            <label class="form-check-label text-sm" for="e_is_featured">
                                <i class="fas fa-star me-1 text-warning"></i> Featured
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="e_is_active"
                                   {{ $post->is_active ? 'checked' : '' }}>
                            <label class="form-check-label text-sm" for="e_is_active">
                                <i class="fas fa-toggle-on me-1 text-success"></i> Active
                            </label>
                        </div>
                    </div>

                </div>

                {{-- ══ TAB 2 : Location ══ --}}
                <div class="modal-tab-pane d-none" id="etab-location">

                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Location Details</p>
                    <div class="row g-3 mb-3">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Country</label>
                            <input type="text" id="e_country" class="form-control"
                                   value="{{ $post->country }}" placeholder="e.g. UAE">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">State</label>
                            <input type="text" id="e_state" class="form-control"
                                   value="{{ $post->state }}" placeholder="e.g. Dubai">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">City</label>
                            <input type="text" id="e_city" class="form-control"
                                   value="{{ $post->city }}" placeholder="e.g. Downtown">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Location Description</label>
                            <input type="text" id="e_location" class="form-control"
                                   value="{{ $post->location }}" placeholder="Landmark or area…">
                        </div>

                    </div>

                    <hr class="horizontal dark my-3">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2">
                        GPS Coordinates <span class="text-muted fw-normal">(optional)</span>
                    </p>
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Latitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs text-muted">LAT</span>
                                <input type="number" id="e_latitude" class="form-control border-start-0"
                                       value="{{ $post->latitude }}" placeholder="25.2048" step="any">
                            </div>
                            <small class="text-danger d-none" id="e_err_latitude"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Longitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs text-muted">LNG</span>
                                <input type="number" id="e_longitude" class="form-control border-start-0"
                                       value="{{ $post->longitude }}" placeholder="55.2708" step="any">
                            </div>
                            <small class="text-danger d-none" id="e_err_longitude"></small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-sm mb-1">Google Maps URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-map text-muted" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="e_google_map_url" class="form-control border-start-0"
                                       value="{{ $post->google_map_url }}"
                                       placeholder="https://maps.google.com/…">
                            </div>
                        </div>

                    </div>

                </div>

                {{-- ══ TAB 3 : Media ══ --}}
                <div class="modal-tab-pane d-none" id="etab-media">

                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Images</p>

                    {{-- Existing images pre-loaded from server --}}
                    <div id="e_existingImages" class="img-strip">
                        @foreach ($post->getMedia('posts') as $m)
                            <div class="img-wrap" id="media-wrap-{{ $m->id }}">
                                <a href="{{ $m->getUrl() }}" data-fancybox="edit-gallery">
                                    <img src="{{ $m->getUrl() }}" alt="{{ $m->name }}">
                                </a>
                                <button type="button"
                                        class="btn btn-danger btn-del-media"
                                        data-id="{{ $m->id }}" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('posts.mediaUpload') }}" class="dropzone" id="editDropzone">
                        @csrf
                        <div class="dz-message">
                            <i class="fas fa-cloud-upload-alt me-2 text-muted"></i>
                            <span class="fw-semibold">Drop images here</span>
                            <span class="text-muted"> or click to upload</span>
                            <br><small class="text-muted">Max 5 MB · JPG, PNG, WEBP</small>
                        </div>
                    </form>

                    <hr class="horizontal dark my-4">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2">Video</p>
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Upload Video File</label>
                            <input type="file" id="e_video" class="form-control" accept="video/*">
                            @if ($post->getMedia('videos')->isNotEmpty())
                                <small class="text-success text-xs mt-1">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Video already uploaded — upload a new file to replace it
                                </small>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Video URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fab fa-youtube text-danger" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="e_video_url" class="form-control border-start-0"
                                       value="{{ $post->video_url }}"
                                       placeholder="https://youtube.com/watch?v=…">
                            </div>
                        </div>

                    </div>

                </div>

                {{-- ══ TAB 4 : SEO ══ --}}
                <div class="modal-tab-pane d-none" id="etab-seo">

                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">SEO & Meta</p>
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Meta Title</label>
                            <input type="text" id="e_meta_title" class="form-control"
                                   value="{{ $post->meta_title }}"
                                   placeholder="SEO title (defaults to post title)">
                            <small class="text-muted text-xs">Recommended: 50–60 characters</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Meta Description</label>
                            <textarea id="e_meta_description" class="form-control" rows="3"
                                      placeholder="Short description for search engines…">{{ $post->meta_description }}</textarea>
                            <small class="text-muted text-xs">Recommended: 150–160 characters</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Keywords</label>
                            <input type="text" id="e_keywords" class="form-control"
                                   value="{{ $post->keywords }}"
                                   placeholder="keyword1, keyword2, keyword3…">
                            <small class="text-muted text-xs">Comma-separated</small>
                        </div>

                        {{-- Live preview --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">
                                Search Preview <span class="text-muted fw-normal">(live)</span>
                            </label>
                            <div class="seo-preview">
                                <div class="seo-title" id="seo_prev_title">{{ $post->meta_title ?: $post->title }}</div>
                                <div class="seo-url">{{ url('/posts/' . $post->slug) }}</div>
                                <div class="seo-desc" id="seo_prev_desc">
                                    {{ $post->meta_description ?: Str::limit(strip_tags($post->description ?? ''), 160) }}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>{{-- /modal-body --}}

            {{-- Footer --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <div class="text-xs text-muted me-auto">
                    <i class="fas fa-clock me-1"></i>
                    Last updated {{ $post->updated_at->format('d M Y, H:i') }}
                    ({{ $post->updated_at->diffForHumans() }})
                </div>
                <button class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button class="btn bg-gradient-warning px-4" id="saveEditPost">
                    <span id="saveEditText"><i class="fas fa-save me-2"></i> Save Changes</span>
                    <span id="saveEditSpinner" class="d-none">
                        <span class="spinner-border spinner-border-sm me-2"></span> Saving…
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

<script>
// ── Globals ──────────────────────────────────────────────────────────────────
const POST_ID    = {{ $post->id }};
let   editorInst = null;

// ── CKEditor ─────────────────────────────────────────────────────────────────
ClassicEditor.create(document.querySelector('#e_description'))
    .then(e => { editorInst = e; })
    .catch(console.error);

// ── Dropzone ─────────────────────────────────────────────────────────────────
Dropzone.autoDiscover = false;

const editDZ = new Dropzone('#editDropzone', {
    url             : '{{ route("posts.mediaUpload") }}',
    autoProcessQueue: true,
    parallelUploads : 3,
    maxFilesize     : 5,
    acceptedFiles   : 'image/*',
    headers         : { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    sending         : (file, xhr, fd) => fd.append('post_id', POST_ID),
    success         : function (file, res) {
        if (res.url) appendThumb(res.id, res.url);
        this.removeFile(file);
    },
    error: (f, msg) => console.error('Dropzone error:', msg),
});

function appendThumb(id, url) {
    $('#e_existingImages').append(`
        <div class="img-wrap" id="media-wrap-${id}">
            <a href="${url}" data-fancybox="edit-gallery"><img src="${url}" alt=""></a>
            <button type="button" class="btn btn-danger btn-del-media" data-id="${id}" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>`);
}

// ── Fancybox ─────────────────────────────────────────────────────────────────
Fancybox.bind('[data-fancybox="post-gallery"]', { Toolbar:{ display:['close'] } });
Fancybox.bind('[data-fancybox="edit-gallery"]', { Toolbar:{ display:['close'] } });

// ── Tab switching ─────────────────────────────────────────────────────────────
$(document).on('click', '#editModalTabs .nav-link', function () {
    var target = $(this).data('tab');
    $('#editModalTabs .nav-link').removeClass('active');
    $(this).addClass('active');
    $('.modal-tab-pane').addClass('d-none');
    $('#' + target).removeClass('d-none');
});

function switchToTab(tabId) {
    $('#editModalTabs .nav-link[data-tab="' + tabId + '"]').trigger('click');
}

// ── Error helpers ─────────────────────────────────────────────────────────────
var fieldTabMap = {
    title: 'etab-basic', description: 'etab-basic',
    category_id: 'etab-basic', status: 'etab-basic',
    latitude: 'etab-location', longitude: 'etab-location',
    meta_title: 'etab-seo', meta_description: 'etab-seo', keywords: 'etab-seo',
};

function clearErrors() {
    $('.text-danger[id^="e_err_"]').addClass('d-none').text('');
    $('#editModal .form-control, #editModal .form-select').removeClass('is-invalid');
    $('#editModalTabs .tab-err-dot').remove();
}

function showErrors(errors) {
    var firstTab = null, tabsWithErrors = {};
    $.each(errors, function (field, msgs) {
        $('#e_err_' + field).removeClass('d-none').text(msgs[0]);
        $('#e_' + field).addClass('is-invalid');
        var tab = fieldTabMap[field] || 'etab-basic';
        tabsWithErrors[tab] = true;
        if (!firstTab) firstTab = tab;
    });
    $.each(tabsWithErrors, function (tab) {
        var link = $('#editModalTabs .nav-link[data-tab="' + tab + '"]');
        if (!link.find('.tab-err-dot').length) {
            link.append('<span class="tab-err-dot"></span>');
        }
    });
    if (firstTab) switchToTab(firstTab);
}

// ── Category → Subcategory cascade ───────────────────────────────────────────
// Pre-load subcategories for the current category on page load
$(function () {
    var catId  = '{{ $post->category_id }}';
    var subId  = '{{ $post->subcategory_id }}';

    if (catId) {
        $.get('{{ url("admin/get-subcategories") }}/' + catId, function (res) {
            var html = '<option value="">— Select Subcategory —</option>';
            res.forEach(function (item) {
                var sel = (item.id == subId) ? ' selected' : '';
                html += '<option value="' + item.id + '"' + sel + '>' + item.name + '</option>';
            });
            $('#e_subcategory_id').html(html);
        });
    }

    // Live cascade on change
    $('#e_category_id').on('change', function () {
        var id = $(this).val();
        $('#e_subcategory_id').html('<option value="">— Select Subcategory —</option>');
        if (!id) return;
        $.get('{{ url("admin/get-subcategories") }}/' + id, function (res) {
            var html = '<option value="">— Select Subcategory —</option>';
            res.forEach(function (item) {
                html += '<option value="' + item.id + '">' + item.name + '</option>';
            });
            $('#e_subcategory_id').html(html);
        });
    });
});

// ── Live SEO preview ──────────────────────────────────────────────────────────
$(document).on('input', '#e_meta_title', function () {
    var v = $(this).val().trim();
    $('#seo_prev_title').text(v || '{{ addslashes($post->title) }}');
});
$(document).on('input', '#e_meta_description', function () {
    $('#seo_prev_desc').text($(this).val().trim() || '{{ Str::limit(addslashes(strip_tags($post->description ?? "")), 160) }}');
});

// ── Delete media image ────────────────────────────────────────────────────────
$(document).on('click', '.btn-del-media', function () {
    var id    = $(this).data('id');
    var $wrap = $('#media-wrap-' + id);

    Swal.fire({
        title: 'Remove image?', icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, remove',
    }).then(function (r) {
        if (!r.isConfirmed) return;
        $.ajax({
            url  : '{{ url("admin/posts/media") }}/' + id,
            type : 'POST',
            data : { _token: '{{ csrf_token() }}', _method: 'DELETE' },
            success: function (res) { if (res.success) $wrap.remove(); }
        });
    });
});

// ── Save changes ──────────────────────────────────────────────────────────────
$('#saveEditPost').on('click', function () {
    clearErrors();
    $('#saveEditText').addClass('d-none');
    $('#saveEditSpinner').removeClass('d-none');
    $('#saveEditPost').prop('disabled', true);

    // Build FormData so we can attach the video file
    var fd = new FormData();
    fd.append('_token',           '{{ csrf_token() }}');
    fd.append('_method',          'PUT');
    // Basic
    fd.append('title',            $('#e_title').val());
    fd.append('description',      editorInst ? editorInst.getData() : $('#e_description').val());
    fd.append('category_id',      $('#e_category_id').val());
    fd.append('subcategory_id',   $('#e_subcategory_id').val());
    fd.append('locality_id',      $('#e_locality_id').val());
    fd.append('status',           $('#e_status').val());
    fd.append('user_id',          $('#e_user_id').val());
    fd.append('expiry_date',      $('#e_expiry_date').val());
    fd.append('is_featured',      $('#e_is_featured').is(':checked') ? 1 : 0);
    fd.append('is_active',        $('#e_is_active').is(':checked')   ? 1 : 0);
    // Location
    fd.append('country',          $('#e_country').val());
    fd.append('state',            $('#e_state').val());
    fd.append('city',             $('#e_city').val());
    fd.append('location',         $('#e_location').val());
    fd.append('latitude',         $('#e_latitude').val());
    fd.append('longitude',        $('#e_longitude').val());
    fd.append('google_map_url',   $('#e_google_map_url').val());
    // Media
    fd.append('video_url',        $('#e_video_url').val());
    var videoFile = document.getElementById('e_video').files[0];
    if (videoFile) fd.append('video', videoFile);
    // SEO
    fd.append('meta_title',       $('#e_meta_title').val());
    fd.append('meta_description', $('#e_meta_description').val());
    fd.append('keywords',         $('#e_keywords').val());

    $.ajax({
        url         : '{{ url("admin/posts") }}/' + POST_ID,
        type        : 'POST',
        data        : fd,
        processData : false,
        contentType : false,

        success: function (res) {
            if (res.success) {
                $('#editModal').modal('hide');
                Swal.fire({
                    icon : 'success',
                    title: 'Post updated!',
                    text : 'Changes have been saved.',
                    timer: 1600, showConfirmButton: false,
                }).then(function () {
                    window.location.reload();
                });
            }
        },

        error: function (xhr) {
            if (xhr.status === 422) {
                showErrors(xhr.responseJSON.errors ?? {});
            } else {
                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
            }
        },

        complete: function () {
            $('#saveEditText').removeClass('d-none');
            $('#saveEditSpinner').addClass('d-none');
            $('#saveEditPost').prop('disabled', false);
        }
    });
});

// ── Copy URL ──────────────────────────────────────────────────────────────────
$('#copyBtn').on('click', function () {
    navigator.clipboard.writeText($('#publicUrl').val()).then(() => {
        $(this).html('<i class="fas fa-check text-success"></i>');
        setTimeout(() => $(this).html('<i class="fas fa-copy"></i>'), 1800);
    });
});

// ── Delete post ───────────────────────────────────────────────────────────────
$('#deletePostBtn').on('click', function () {
    Swal.fire({
        title            : 'Delete Post?',
        html             : 'You are about to permanently delete<br><strong>{{ addslashes($post->title) }}</strong>',
        icon             : 'warning',
        showCancelButton : true,
        confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
    }).then(function (r) {
        if (!r.isConfirmed) return;
        $.ajax({
            url  : '{{ url("admin/posts") }}/{{ $post->id }}',
            type : 'POST',
            data : { _token: '{{ csrf_token() }}', _method: 'DELETE' },
            success: function (res) {
                if (res.success) {
                    Swal.fire({ icon:'success', title:'Deleted!', timer:1200, showConfirmButton:false })
                        .then(() => window.location.href = '{{ route("posts.index") }}');
                }
            }
        });
    });
});
</script>
@endpush