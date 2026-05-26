@extends('layouts.user_type.auth')

@section('content')

@push('css')
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
<style>
    :root {
        --ink:       #111827; --ink-2: #6b7280; --ink-3: #9ca3af;
        --rule:      #e5e7eb; --surf: #fff;     --surf-2: #f9fafb;
        --accent:    #1a56db; --success: #10b981; --warn: #f59e0b; --danger: #ef4444;
        --r:         1rem;    --sh: 0 1px 3px rgba(0,0,0,.08);
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
        padding:.9rem 1.25rem;
        background:var(--surf); border:1px solid var(--rule);
        border-radius:var(--r); margin-bottom:1.5rem;
        box-shadow:var(--sh);
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
    .ps-hero .hero-body {
        position:relative; padding:2rem 2.25rem; width:100%;
    }
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
    .ps-hero-plain {
        background:linear-gradient(135deg,#1e293b 0%,#334155 100%); min-height:180px;
    }

    /* Stat pills */
    .ps-stats { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:1.5rem; }
    .ps-stat {
        flex:1 1 120px; background:var(--surf-2);
        border:1px solid var(--rule); border-radius:var(--r);
        padding:1rem 1.1rem; display:flex; align-items:center; gap:.75rem;
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
        border-radius:var(--r); padding:1.5rem; margin-bottom:1.25rem;
        box-shadow:var(--sh);
    }
    .ps-card-title {
        font-size:.7rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.06em; color:var(--ink-3);
        margin-bottom:1rem; padding-bottom:.75rem; border-bottom:1px solid var(--rule);
        display:flex; align-items:center; gap:.45rem;
    }
    .ps-card-title i { color:var(--accent); font-size:.8rem; }

    /* Meta list */
    .ps-meta { list-style:none; padding:0; margin:0; }
    .ps-meta li {
        display:flex; align-items:flex-start; gap:.75rem;
        padding:.6rem 0; border-bottom:1px solid var(--rule);
        font-size:.85rem;
    }
    .ps-meta li:last-child { border-bottom:none; }
    .ps-meta .ml {
        width:105px; flex-shrink:0; font-size:.7rem; font-weight:600;
        letter-spacing:.04em; text-transform:uppercase; color:var(--ink-3);
        padding-top:.1rem;
    }
    .ps-meta .mv { color:var(--ink); font-weight:500; word-break:break-word; }
    .ps-meta .mv.empty { color:var(--ink-3); font-style:italic; font-weight:400; }

    /* Description */
    .ps-body { font-size:.9rem; line-height:1.85; color:#374151; }
    .ps-body img  { max-width:100%; border-radius:.5rem; }
    .ps-body h1,.ps-body h2,.ps-body h3 { font-family:'DM Serif Display',serif; }
    .ps-body p { margin-bottom:.85rem; }

    /* Gallery */
    .ps-gallery {
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(88px,1fr));
        gap:.5rem;
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
        display:flex; gap:.8rem; align-items:center;
        padding:.75rem; background:var(--surf-2);
        border:1px solid var(--rule); border-radius:.65rem;
    }
    .ps-user .av {
        width:42px; height:42px; border-radius:50%;
        background:var(--accent); color:#fff; font-weight:700;
        display:flex; align-items:center; justify-content:center;
        font-size:1rem; flex-shrink:0; text-transform:uppercase;
    }
    .ps-user .uname { font-weight:600; font-size:.875rem; }
    .ps-user .umail { font-size:.74rem; color:var(--ink-2); }

    /* Timeline */
    .ps-log { list-style:none; padding:0; margin:0; }
    .ps-log li {
        display:flex; gap:.7rem; align-items:flex-start;
        padding:.6rem 0; border-bottom:1px solid var(--rule);
        font-size:.82rem;
    }
    .ps-log li:last-child { border-bottom:none; }
    .ps-log .dot { width:8px; height:8px; border-radius:50%; margin-top:.35rem; flex-shrink:0; }
    .ps-log .lt { color:var(--ink-3); font-size:.74rem; }

    /* SEO preview */
    .seo-preview {
        background:#fff; border:1px solid #dadce0; border-radius:.5rem;
        padding:1rem 1.25rem; font-family:Arial,sans-serif;
    }
    .seo-title { color:#1a0dab; font-size:1.05rem; font-weight:400; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .seo-url   { color:#006621; font-size:.78rem; margin:.15rem 0; }
    .seo-desc  { color:#545454; font-size:.83rem; line-height:1.5; }

    @media(max-width:767px) {
        .ps-hero .hero-body { padding:1.25rem 1.5rem; }
        .ps-stat { flex:1 1 110px; }
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

{{-- Action bar --}}
<div class="act-bar">

    @php
        $sc = match($post->status) {
            'published' => ['bg-success-subtle','text-success'],
            'archived'  => ['bg-warning-subtle','text-warning'],
            default     => ['bg-secondary-subtle','text-secondary'],
        };
    @endphp

    <span class="badge {{ $sc[0] }} {{ $sc[1] }} rounded-pill px-3 py-2">
        <i class="fas fa-circle me-1" style="font-size:.45rem;vertical-align:middle;"></i>
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

    <button class="btn btn-sm bg-gradient-warning" id="editPostBtn" data-id="{{ $post->id }}">
        <i class="fas fa-pen me-1"></i> Edit
    </button>

    {{-- <button class="btn btn-sm btn-outline-danger" id="deletePostBtn">
        <i class="fas fa-trash me-1"></i> Delete
    </button> --}}

    <a href="{{ route('posts.index') }}" class="btn btn-sm btn-light border">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

</div>

<div class="row g-4">

    {{-- ══════════════════ LEFT ══════════════════ --}}
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
                <div>
                    <div class="val">{{ number_format($post->views ?? 0) }}</div>
                    <div class="lbl">Views</div>
                </div>
            </div>
            <div class="ps-stat">
                <div class="icon" style="background:#d1fae5;color:#059669;"><i class="fas fa-heart"></i></div>
                <div>
                    <div class="val">{{ number_format($post->likesData->count()) }}</div>
                    <div class="lbl">Likes</div>
                </div>
            </div>
            <div class="ps-stat">
                <div class="icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-share-alt"></i></div>
                <div>
                    <div class="val">{{ number_format($post->sharesData->count()) }}</div>
                    <div class="lbl">Shares</div>
                </div>
            </div>
            <div class="ps-stat">
                <div class="icon" style="background:#fce7f3;color:#db2777;"><i class="fas fa-images"></i></div>
                <div>
                    <div class="val">{{ $post->getMedia('posts')->count() }}</div>
                    <div class="lbl">Images</div>
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-align-left"></i> Description</div>
            @if ($post->description)
                <div class="ps-body">{!! $post->description !!}</div>
            @else
                <p class="text-muted text-sm mb-0">
                    <i class="fas fa-pen-to-square me-2 opacity-25"></i>No description added yet.
                </p>
            @endif
        </div>

        {{-- Gallery --}}
        @php $allMedia = $post->getMedia('posts'); @endphp
        <div class="ps-card">
            <div class="ps-card-title">
                <i class="fas fa-images"></i> Gallery
                <span class="ms-auto badge bg-light text-secondary">{{ $allMedia->count() }} image(s)</span>
            </div>
            @if ($allMedia->count())
                <div class="ps-gallery">
                    @foreach ($allMedia as $m)
                        <a href="{{ $m->getUrl() }}"
                           data-fancybox="post-gallery"
                           data-caption="{{ $m->name }}">
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
                @elseif ($post->getMedia('videos')->isNotEmpty())
                    @php $vid = $post->getMedia('videos')->first(); @endphp
                    <div class="ps-video">
                        <video controls>
                            <source src="{{ $vid->getUrl() }}" type="{{ $vid->mime_type }}">
                        </video>
                    </div>
                @endif
            </div>
        @endif

        {{-- Location / Map --}}
        @if ($post->latitude && $post->longitude)
            <div class="ps-card">
                <div class="ps-card-title"><i class="fas fa-map-marked-alt"></i> Location</div>

                {{-- Location breadcrumb --}}
                @if ($post->country || $post->state || $post->city)
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach ([$post->country, $post->state, $post->city, $post->location] as $loc)
                            @if ($loc)
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs">
                                    <i class="fas fa-map-marker-alt me-1 text-muted"></i>{{ $loc }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="ps-map mb-3">
                    <iframe
                        src="https://maps.google.com/maps?q={{ $post->latitude }},{{ $post->longitude }}&z=15&output=embed"
                        loading="lazy">
                    </iframe>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-light text-dark border rounded-3 px-3 py-2 text-xs fw-semibold">
                        <span class="text-muted me-1">LAT</span>{{ $post->latitude }}
                    </span>
                    <span class="badge bg-light text-dark border rounded-3 px-3 py-2 text-xs fw-semibold">
                        <span class="text-muted me-1">LNG</span>{{ $post->longitude }}
                    </span>
                    <a href="https://www.google.com/maps?q={{ $post->latitude }},{{ $post->longitude }}"
                       target="_blank"
                       class="btn btn-sm btn-outline-primary ms-auto rounded-3 px-3">
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
                    <div class="d-flex flex-wrap gap-1">
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

    {{-- ══════════════════ RIGHT ══════════════════ --}}
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
                    <span class="mv {{ !$post->category ? 'empty' : '' }}">
                        {{ $post->category?->name ?? 'Not set' }}
                    </span>
                </li>
                <li>
                    <span class="ml">Subcategory</span>
                    <span class="mv {{ !$post->subcategory ? 'empty' : '' }}">
                        {{ $post->subcategory?->name ?? 'Not set' }}
                    </span>
                </li>
                <li>
                    <span class="ml">Locality</span>
                    <span class="mv {{ !$post->locality ? 'empty' : '' }}">
                        {{ $post->locality?->name ?? 'Not set' }}
                    </span>
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
                    <span class="mv text-muted {{ !$post->published_at ? 'empty' : '' }}">
                        {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d M Y, H:i') : 'Not published' }}
                    </span>
                </li>

            </ul>
        </div>

        {{-- Location meta (if no map) --}}
        @if ($post->country || $post->state || $post->city || $post->location)
            <div class="ps-card">
                <div class="ps-card-title"><i class="fas fa-map-marker-alt"></i> Location Details</div>
                <ul class="ps-meta">
                    @foreach (['country' => 'Country', 'state' => 'State', 'city' => 'City', 'location' => 'Location'] as $field => $label)
                        @if ($post->$field)
                            <li>
                                <span class="ml">{{ $label }}</span>
                                <span class="mv">{{ $post->$field }}</span>
                            </li>
                        @endif
                    @endforeach
                    @if ($post->google_map_url)
                        <li>
                            <span class="ml">Maps URL</span>
                            <span class="mv">
                                <a href="{{ $post->google_map_url }}" target="_blank" class="text-primary">
                                    <i class="fas fa-external-link-alt me-1" style="font-size:.65rem;"></i>
                                    Open Map
                                </a>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif

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
        @php
            $publicUrl = $post->url ?? url('/posts/' . $post->slug);
        @endphp
        <div class="ps-card">
            <div class="ps-card-title"><i class="fas fa-link"></i> Public URL</div>
            <div class="input-group input-group-sm mb-2">
                <input type="text" class="form-control border text-xs" id="publicUrl"
                       value="{{ $publicUrl }}" readonly>
                <button class="btn btn-outline-secondary" id="copyBtn" title="Copy">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <a href="{{ $publicUrl }}" target="_blank"
               class="btn btn-sm btn-outline-primary w-100 rounded-3">
                <i class="fas fa-external-link-alt me-1"></i>View Public Post
            </a>
        </div>

        {{-- Activity log --}}
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
                @if ($post->deleted_at)
                    <li>
                        <span class="dot" style="background:var(--danger)"></span>
                        <div>
                            <div class="fw-semibold text-danger">Soft deleted</div>
                            <div class="lt">{{ $post->deleted_at->format('d M Y, H:i') }}</div>
                        </div>
                    </li>
                @endif
            </ul>
        </div>

    </div>{{-- /col-lg-4 --}}
</div>

@endsection

@push('js')
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
// Fancybox
Fancybox.bind('[data-fancybox="post-gallery"]', { Toolbar:{ display:['close'] } });

// Copy URL
$('#copyBtn').on('click', function () {
    navigator.clipboard.writeText($('#publicUrl').val()).then(() => {
        $(this).html('<i class="fas fa-check text-success"></i>');
        setTimeout(() => $(this).html('<i class="fas fa-copy"></i>'), 1800);
    });
});

// Edit → go to list and open modal
$('#editPostBtn').on('click', function () {
    sessionStorage.setItem('autoEditPostId', $(this).data('id'));
    window.location.href = '{{ route("posts.index") }}';
});

// Delete
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
            url   : '{{ url("admin/posts") }}/{{ $post->id }}',
            type  : 'POST',
            data  : { _token: '{{ csrf_token() }}', _method: 'DELETE' },
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