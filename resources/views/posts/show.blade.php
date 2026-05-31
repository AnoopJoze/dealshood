@extends('layouts.user_type.auth')

@section('content')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
<style>
/* ── Design tokens (shared across admin) ─────────────────── */
:root {
    --dk:      #0f172a;
    --dk2:     #1e293b;
    --accent:  #6366f1;
    --surface: #f8fafc;
    --border:  #f1f5f9;
    --muted:   #64748b;
    --muted2:  #94a3b8;
    --r:       10px;
    --sh:      0 2px 16px rgba(15,23,42,.07);
    --sh-hover:0 6px 28px rgba(15,23,42,.12);
}

/* ── Action bar ───────────────────────────────────────────── */
.ps-actbar {
    display: flex; flex-wrap: wrap; gap: 8px; align-items: center;
    background: #fff; border: 1px solid var(--border);
    border-radius: var(--r); padding: .7rem 1rem;
    margin-bottom: 1.5rem; box-shadow: var(--sh);
}
.ps-actbar .spacer { flex: 1 1 0; }

/* Status pill */
.ps-status-pill {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .72rem; font-weight: 700; letter-spacing: .04em;
    padding: 5px 13px; border-radius: 100px;
}
.ps-status-pill .dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: currentColor;
}

/* Action buttons */
.ps-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .76rem; font-weight: 600; border-radius: 8px;
    padding: .45rem .9rem; cursor: pointer; border: 1.5px solid;
    transition: background .14s, color .14s, box-shadow .14s;
    text-decoration: none;
}
.ps-btn-warn {
    background: linear-gradient(135deg,#d97706,#f59e0b);
    color: #fff; border-color: transparent;
}
.ps-btn-warn:hover {
    filter: brightness(1.08); color: #fff;
    box-shadow: 0 3px 12px rgba(217,119,6,.35);
}
.ps-btn-ghost { background: #fff; color: var(--muted); border-color: var(--border); }
.ps-btn-ghost:hover { background: var(--surface); color: var(--dk); }
.ps-btn-danger { background: #fff; color: #dc2626; border-color: #fecaca; }
.ps-btn-danger:hover { background: #fef2f2; }

/* ── Hero ─────────────────────────────────────────────────── */
.ps-hero {
    position: relative; border-radius: var(--r); overflow: hidden;
    background: var(--dk); min-height: 300px;
    display: flex; align-items: flex-end;
    margin-bottom: 1.25rem;
    box-shadow: 0 8px 32px rgba(15,23,42,.2);
}
.ps-hero img {
    position: absolute; inset: 0; width: 100%; height: 100%;
    object-fit: cover; opacity: .5;
}
.ps-hero .hg {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(15,23,42,.88) 0%, rgba(15,23,42,.1) 60%);
}
.ps-hero-plain { background: linear-gradient(135deg, var(--dk) 0%, #312e81 100%); min-height: 200px; }
.ps-hero-body { position: relative; padding: 1.75rem 2rem; width: 100%; }
.ps-hero-title {
    font-size: clamp(1.4rem, 3.2vw, 2.2rem);
    font-weight: 800; color: #fff; line-height: 1.2;
    letter-spacing: -.02em; margin: 0 0 .85rem;
    text-shadow: 0 2px 12px rgba(0,0,0,.4);
}
.ps-hero-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.12); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.18); color: #fff;
    font-size: .68rem; font-weight: 600; letter-spacing: .04em;
    text-transform: uppercase; padding: .25rem .7rem; border-radius: 100px;
}

/* ── KPI stat row ─────────────────────────────────────────── */
.ps-stats { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
.ps-stat {
    flex: 1 1 110px; background: #fff; border: 1px solid var(--border);
    border-radius: var(--r); padding: .9rem 1.1rem;
    display: flex; align-items: center; gap: .75rem;
    box-shadow: var(--sh); transition: transform .16s, box-shadow .16s;
}
.ps-stat:hover { transform: translateY(-2px); box-shadow: var(--sh-hover); }
.ps-stat .si {
    width: 38px; height: 38px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.ps-stat .sv { font-size: 1.3rem; font-weight: 800; line-height: 1; color: var(--dk); }
.ps-stat .sl {
    font-size: .62rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: var(--muted2); margin-top: 3px;
}

/* ── Cards ────────────────────────────────────────────────── */
.ps-card {
    background: #fff; border: 1px solid var(--border);
    border-radius: var(--r); margin-bottom: 1.1rem;
    box-shadow: var(--sh); overflow: hidden;
}
.ps-card-hd {
    display: flex; align-items: center; justify-content: space-between;
    padding: .85rem 1.2rem; border-bottom: 1px solid var(--border);
}
.ps-card-title {
    font-size: .63rem; font-weight: 700; letter-spacing: .11em;
    text-transform: uppercase; color: var(--muted2);
    margin: 0; display: flex; align-items: center; gap: 7px;
}
.ps-card-title i { color: var(--accent); font-size: .72rem; }
.ps-card-body { padding: 1.1rem 1.2rem; }

/* ── Description body ────────────────────────────────────── */
.ps-body { font-size: .9rem; line-height: 1.85; color: #374151; }
.ps-body img { max-width: 100%; border-radius: 8px; }
.ps-body p { margin-bottom: .85rem; }
.ps-body h1,.ps-body h2,.ps-body h3 { color: var(--dk); font-weight: 700; }

/* ── Gallery grid ────────────────────────────────────────── */
.ps-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 8px;
}
.ps-gallery a {
    display: block; aspect-ratio: 1; border-radius: 8px;
    overflow: hidden; border: 2px solid var(--border);
    transition: border-color .16s, transform .16s;
}
.ps-gallery a:hover { border-color: var(--accent); transform: scale(1.04); }
.ps-gallery img { width: 100%; height: 100%; object-fit: cover; }

/* ── Video embed ─────────────────────────────────────────── */
.ps-video {
    position: relative; padding-bottom: 56.25%;
    border-radius: 8px; overflow: hidden; background: #000;
}
.ps-video iframe, .ps-video video {
    position: absolute; inset: 0; width: 100%; height: 100%; border: none;
}

/* ── Map ─────────────────────────────────────────────────── */
.ps-map { border-radius: 8px; overflow: hidden; height: 220px; }
.ps-map iframe { width: 100%; height: 100%; border: none; display: block; }

/* ── Meta list ───────────────────────────────────────────── */
.ps-meta { list-style: none; padding: 0; margin: 0; }
.ps-meta li {
    display: flex; align-items: flex-start; gap: .7rem;
    padding: .55rem 0; border-bottom: 1px solid var(--border);
    font-size: .83rem;
}
.ps-meta li:last-child { border-bottom: none; }
.ps-meta .ml {
    width: 100px; flex-shrink: 0;
    font-size: .68rem; font-weight: 700; letter-spacing: .07em;
    text-transform: uppercase; color: var(--muted2); padding-top: .15rem;
}
.ps-meta .mv { color: var(--dk); font-weight: 500; word-break: break-word; }
.ps-meta .mv.empty { color: var(--muted2); font-style: italic; font-weight: 400; }

/* ── User card ───────────────────────────────────────────── */
.ps-user {
    display: flex; gap: .85rem; align-items: center;
    padding: .85rem 1rem; background: var(--surface);
    border: 1px solid var(--border); border-radius: 9px;
}
.ps-user .av {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff; font-weight: 800; font-size: 1rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; text-transform: uppercase;
}
.ps-user .uname { font-weight: 700; font-size: .85rem; color: var(--dk); }
.ps-user .umail { font-size: .73rem; color: var(--muted); }

/* ── Activity log ────────────────────────────────────────── */
.ps-log { list-style: none; padding: 0; margin: 0; }
.ps-log li {
    display: flex; gap: .75rem; align-items: flex-start;
    padding: .6rem 0; border-bottom: 1px solid var(--border);
    font-size: .82rem;
}
.ps-log li:last-child { border-bottom: none; }
.ps-log .log-dot {
    width: 8px; height: 8px; border-radius: 50%;
    margin-top: .3rem; flex-shrink: 0;
}
.ps-log .log-time { font-size: .7rem; color: var(--muted2); margin-top: 2px; }

/* ── URL copy box ────────────────────────────────────────── */
.ps-url-box {
    display: flex; gap: 6px; align-items: center;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 8px; padding: .5rem .75rem; margin-bottom: .75rem;
}
.ps-url-box input {
    border: none; background: transparent; font-size: .77rem;
    color: var(--muted); flex: 1; outline: none;
}
.ps-url-copy {
    width: 28px; height: 28px; border-radius: 7px;
    border: 1px solid var(--border); background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; cursor: pointer; color: var(--muted);
    transition: background .14s, color .14s; flex-shrink: 0;
}
.ps-url-copy:hover { background: var(--surface); color: var(--accent); }

/* ── SEO preview ─────────────────────────────────────────── */
.seo-preview {
    background: #fff; border: 1px solid #dadce0; border-radius: 8px;
    padding: 1rem 1.2rem;
}
.seo-title {
    color: #1a0dab; font-size: 1rem;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    font-weight: 500;
}
.seo-url   { color: #006621; font-size: .75rem; margin: .15rem 0; }
.seo-desc  { color: #545454; font-size: .82rem; line-height: 1.55; }

/* ── Modal overrides ─────────────────────────────────────── */
.ps-modal .modal-content {
    border: none; border-radius: 14px;
    box-shadow: 0 24px 60px rgba(15,23,42,.18);
}
.ps-modal .modal-header { padding: 1.2rem 1.4rem .9rem; border-bottom: 1px solid var(--border); }
.ps-modal-icon {
    width: 44px; height: 44px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg,#d97706,#f59e0b);
    color: #fff; display: flex; align-items: center;
    justify-content: center; font-size: 1rem;
}

/* Modal tabs */
.ps-tab-nav {
    display: flex; gap: 2px; padding: 0 1.4rem;
    border-bottom: 1px solid var(--border);
}
.ps-tab-link {
    font-size: .75rem; font-weight: 600; padding: .6rem .85rem;
    border: none; background: transparent; cursor: pointer;
    color: var(--muted); border-bottom: 2px solid transparent;
    margin-bottom: -1px; transition: color .14s, border-color .14s;
    display: inline-flex; align-items: center; gap: 5px;
}
.ps-tab-link.active { color: var(--dk); border-bottom-color: var(--dk); }
.ps-tab-link .tab-err-dot {
    width: 6px; height: 6px; border-radius: 50%; background: #ef4444;
}

/* Section labels inside modal */
.modal-section-lbl {
    font-size: .62rem; font-weight: 700; letter-spacing: .11em;
    text-transform: uppercase; color: var(--muted2); margin: 0 0 .75rem;
}
.ps-modal .form-label {
    font-size: .78rem; font-weight: 600; color: var(--dk); margin-bottom: 5px;
}
.ps-modal .form-control,
.ps-modal .form-select {
    font-size: .84rem; border-color: var(--border);
    border-radius: 8px; color: var(--dk);
}
.ps-modal .form-control:focus,
.ps-modal .form-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}
.ps-modal .modal-footer { padding: .9rem 1.4rem; border-top: 1px solid var(--border); }

/* Dropzone */
.dropzone {
    border: 2px dashed var(--border); border-radius: 10px;
    background: var(--surface); min-height: 80px; padding: 1rem;
    transition: border-color .15s;
}
.dropzone:hover { border-color: var(--accent); }
.dropzone .dz-message { margin: .5em 0; font-size: .84rem; color: var(--muted2); }

/* Image strip */
.img-strip { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: .75rem; }
.img-strip .img-wrap { position: relative; }
.img-strip img {
    width: 56px; height: 56px; object-fit: cover;
    border-radius: 8px; border: 2px solid var(--border); cursor: pointer;
}
.img-strip img:hover { border-color: var(--accent); }
.img-strip .btn-del-media {
    position: absolute; top: -6px; right: -6px;
    width: 18px; height: 18px; padding: 0; font-size: 9px;
    border-radius: 50%; line-height: 18px; text-align: center;
}
</style>
@endpush

{{-- ═══ ACTION BAR ════════════════════════════════════════════ --}}
<div class="ps-actbar">

    @php
        [$sbg, $stc] = match($post->status) {
            'published' => ['background:#d1fae5;color:#059669;', '#059669'],
            'archived'  => ['background:#fef3c7;color:#d97706;', '#d97706'],
            default     => ['background:#f1f5f9;color:#64748b;', '#64748b'],
        };
    @endphp

    <span class="ps-status-pill" style="{{ $sbg }}">
        <span class="dot"></span>
        {{ ucfirst($post->status) }}
    </span>

    @if ($post->is_featured)
        <span class="ps-status-pill" style="background:#fef3c7;color:#d97706;">
            <i class="fas fa-star" style="font-size:.62rem;"></i> Featured
        </span>
    @endif

    @if (!$post->is_active)
        <span class="ps-status-pill" style="background:#fef2f2;color:#dc2626;">
            <i class="fas fa-eye-slash" style="font-size:.62rem;"></i> Inactive
        </span>
    @endif

    @if ($post->expiry_date && now()->gt($post->expiry_date))
        <span class="ps-status-pill" style="background:#fef2f2;color:#dc2626;">
            <i class="fas fa-clock" style="font-size:.62rem;"></i> Expired
        </span>
    @endif

    <div class="spacer"></div>

    <button class="ps-btn ps-btn-warn" data-bs-toggle="modal" data-bs-target="#editModal">
        <i class="fas fa-pen"></i> Edit Post
    </button>
    <button class="ps-btn ps-btn-danger" id="deletePostBtn">
        <i class="fas fa-trash"></i> Delete
    </button>
    <a href="{{ route('posts.index') }}" class="ps-btn ps-btn-ghost">
        <i class="fas fa-arrow-left"></i> Back
    </a>

</div>

{{-- ═══ LAYOUT ═════════════════════════════════════════════════ --}}
<div class="row g-4">

    {{-- ── LEFT COLUMN ───────────────────────────────────────── --}}
    <div class="col-lg-8">

        {{-- Hero image --}}
        @php $heroImg = $post->getMedia('posts')->first(); @endphp
        <div class="ps-hero {{ !$heroImg ? 'ps-hero-plain' : '' }}">
            @if ($heroImg)
                <img src="{{ $heroImg->getUrl() }}" alt="{{ $post->title }}">
                <div class="hg"></div>
            @endif
            <div class="ps-hero-body">
                <h1 class="ps-hero-title">{{ $post->title }}</h1>
                <div class="d-flex flex-wrap gap-2">
                    @if ($post->category)
                        <span class="ps-hero-chip">
                            <i class="fas fa-tags" style="font-size:.6rem;"></i>
                            {{ $post->category->name }}
                        </span>
                    @endif
                    @if ($post->subcategory)
                        <span class="ps-hero-chip">{{ $post->subcategory->name }}</span>
                    @endif
                    @if ($post->locality)
                        <span class="ps-hero-chip">
                            <i class="fas fa-map-marker-alt" style="font-size:.6rem;"></i>
                            {{ $post->locality->name }}
                        </span>
                    @endif
                    <span class="ps-hero-chip">
                        <i class="fas fa-calendar" style="font-size:.6rem;"></i>
                        {{ $post->created_at->format('d M Y') }}
                    </span>
                    <span class="ps-hero-chip">
                        <i class="fas fa-eye" style="font-size:.6rem;"></i>
                        {{ number_format($post->views ?? 0) }} views
                    </span>
                </div>
            </div>
        </div>

        {{-- KPI stats --}}
        <div class="ps-stats">
            <div class="ps-stat">
                <div class="si" style="background:#dbeafe;"><i class="fas fa-eye" style="color:#1d4ed8;"></i></div>
                <div><div class="sv">{{ number_format($post->views ?? 0) }}</div><div class="sl">Views</div></div>
            </div>
            <div class="ps-stat">
                <div class="si" style="background:#d1fae5;"><i class="fas fa-heart" style="color:#059669;"></i></div>
                <div><div class="sv">{{ number_format($post->likesData->count()) }}</div><div class="sl">Likes</div></div>
            </div>
            <div class="ps-stat">
                <div class="si" style="background:#fef3c7;"><i class="fas fa-share-alt" style="color:#d97706;"></i></div>
                <div><div class="sv">{{ number_format($post->sharesData->count()) }}</div><div class="sl">Shares</div></div>
            </div>
            <div class="ps-stat">
                <div class="si" style="background:#ede9fe;"><i class="fas fa-images" style="color:#7c3aed;"></i></div>
                <div><div class="sv">{{ $post->getMedia('posts')->count() }}</div><div class="sl">Images</div></div>
            </div>
        </div>

        {{-- Description --}}
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-align-left"></i> Description</p>
            </div>
            <div class="ps-card-body">
                @if ($post->description)
                    <div class="ps-body">{!! $post->description !!}</div>
                @else
                    <p class="mb-0" style="font-size:.82rem;color:var(--muted2);">
                        <i class="fas fa-pen me-2" style="opacity:.3;"></i>No description added yet.
                    </p>
                @endif
            </div>
        </div>

        {{-- Gallery --}}
        @php $allMedia = $post->getMedia('posts'); @endphp
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-images"></i> Gallery</p>
                <span class="badge rounded-pill px-2"
                      style="background:var(--surface);color:var(--muted);font-size:.7rem;border:1px solid var(--border);">
                    {{ $allMedia->count() }} image(s)
                </span>
            </div>
            <div class="ps-card-body">
                @if ($allMedia->count())
                    <div class="ps-gallery">
                        @foreach ($allMedia as $m)
                            <a href="{{ $m->getUrl() }}" data-fancybox="post-gallery" data-caption="{{ $m->name }}">
                                <img src="{{ $m->getUrl() }}" alt="{{ $m->name }}" loading="lazy">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="mb-0" style="font-size:.82rem;color:var(--muted2);">
                        <i class="fas fa-image me-2" style="opacity:.3;"></i>No images uploaded yet.
                    </p>
                @endif
            </div>
        </div>

        {{-- Video --}}
        @if ($post->video_url || $post->getMedia('videos')->isNotEmpty())
            <div class="ps-card">
                <div class="ps-card-hd">
                    <p class="ps-card-title"><i class="fas fa-film"></i> Video</p>
                </div>
                <div class="ps-card-body">
                    @if ($post->video_url)
                        @php
                            $vUrl = $post->video_url;
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vUrl, $vm))
                                $vUrl = 'https://www.youtube.com/embed/' . $vm[1];
                            elseif (str_contains($vUrl, 'vimeo.com')) {
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
            </div>
        @endif

        {{-- Map --}}
        @if ($post->latitude && $post->longitude)
            <div class="ps-card">
                <div class="ps-card-hd">
                    <p class="ps-card-title"><i class="fas fa-map-marked-alt"></i> Location Map</p>
                    <a href="https://www.google.com/maps?q={{ $post->latitude }},{{ $post->longitude }}"
                       target="_blank" class="ps-btn ps-btn-ghost" style="font-size:.7rem;padding:.3rem .7rem;">
                        <i class="fas fa-external-link-alt"></i> Open in Maps
                    </a>
                </div>
                <div class="ps-card-body">
                    @if ($post->country || $post->state || $post->city || $post->location)
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach (array_filter([$post->country, $post->state, $post->city, $post->location]) as $loc)
                                <span class="badge rounded-pill px-2"
                                      style="background:var(--surface);color:var(--muted);border:1px solid var(--border);font-size:.72rem;">
                                    <i class="fas fa-map-marker-alt me-1" style="color:var(--accent);font-size:.6rem;"></i>{{ $loc }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    <div class="ps-map mb-3">
                        <iframe src="https://maps.google.com/maps?q={{ $post->latitude }},{{ $post->longitude }}&z=15&output=embed"
                                loading="lazy"></iframe>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge rounded-pill px-3 py-2"
                              style="background:var(--surface);color:var(--muted);border:1px solid var(--border);font-size:.72rem;">
                            <span style="color:var(--muted2);margin-right:4px;">LAT</span>{{ $post->latitude }}
                        </span>
                        <span class="badge rounded-pill px-3 py-2"
                              style="background:var(--surface);color:var(--muted);border:1px solid var(--border);font-size:.72rem;">
                            <span style="color:var(--muted2);margin-right:4px;">LNG</span>{{ $post->longitude }}
                        </span>
                    </div>
                </div>
            </div>
        @elseif ($post->google_map_url)
            <div class="ps-card">
                <div class="ps-card-hd">
                    <p class="ps-card-title"><i class="fas fa-map-marked-alt"></i> Location</p>
                </div>
                <div class="ps-card-body">
                    <a href="{{ $post->google_map_url }}" target="_blank" class="ps-btn ps-btn-ghost">
                        <i class="fas fa-external-link-alt"></i> Open in Google Maps
                    </a>
                </div>
            </div>
        @endif

        {{-- SEO --}}
        @if ($post->meta_title || $post->meta_description || $post->keywords)
            <div class="ps-card">
                <div class="ps-card-hd">
                    <p class="ps-card-title"><i class="fas fa-search"></i> SEO Preview</p>
                </div>
                <div class="ps-card-body">
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
                                <span class="badge rounded-pill px-2"
                                      style="background:var(--surface);color:var(--muted);border:1px solid var(--border);font-size:.7rem;">
                                    {{ trim($kw) }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>{{-- /col-lg-8 --}}

    {{-- ── RIGHT COLUMN ──────────────────────────────────────── --}}
    <div class="col-lg-4">

        {{-- Post Details --}}
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-info-circle"></i> Post Details</p>
            </div>
            <div class="ps-card-body" style="padding-top:.6rem;padding-bottom:.6rem;">
                <ul class="ps-meta">
                    <li>
                        <span class="ml">ID</span>
                        <span class="mv" style="color:var(--muted2);">#{{ $post->id }}</span>
                    </li>
                    <li>
                        <span class="ml">Status</span>
                        <span class="mv">
                            @php $sc = match($post->status) { 'published'=>'#059669','archived'=>'#d97706',default=>'#64748b' }; @endphp
                            <span class="fw-semibold" style="color:{{ $sc }};">{{ ucfirst($post->status) }}</span>
                        </span>
                    </li>
                    <li>
                        <span class="ml">Category</span>
                        <span class="mv {{ !$post->category ? 'empty':'' }}">
                            @if ($post->category)
                                <span class="badge rounded-pill px-2"
                                      style="background:#ede9fe;color:#7c3aed;font-size:.7rem;">
                                    {{ $post->category->name }}
                                </span>
                            @else Not set @endif
                        </span>
                    </li>
                    <li>
                        <span class="ml">Subcategory</span>
                        <span class="mv {{ !$post->subcategory ? 'empty':'' }}">
                            {{ $post->subcategory?->name ?? 'Not set' }}
                        </span>
                    </li>
                    <li>
                        <span class="ml">Locality</span>
                        <span class="mv {{ !$post->locality ? 'empty':'' }}">
                            @if ($post->locality)
                                <i class="fas fa-map-marker-alt me-1" style="color:var(--accent);font-size:.65rem;"></i>
                                {{ $post->locality->name }}
                            @else Not set @endif
                        </span>
                    </li>
                    <li>
                        <span class="ml">Featured</span>
                        <span class="mv">
                            @if ($post->is_featured)
                                <span style="color:#d97706;">
                                    <i class="fas fa-star me-1" style="font-size:.7rem;"></i>Yes
                                </span>
                            @else <span style="color:var(--muted2);">No</span> @endif
                        </span>
                    </li>
                    <li>
                        <span class="ml">Active</span>
                        <span class="mv">
                            @if ($post->is_active)
                                <span style="color:#059669;">
                                    <i class="fas fa-check-circle me-1" style="font-size:.7rem;"></i>Yes
                                </span>
                            @else
                                <span style="color:#dc2626;">
                                    <i class="fas fa-times-circle me-1" style="font-size:.7rem;"></i>No
                                </span>
                            @endif
                        </span>
                    </li>
                    <li>
                        <span class="ml">Expiry</span>
                        <span class="mv">
                            @if ($post->expiry_date)
                                @php $exp = \Carbon\Carbon::parse($post->expiry_date); @endphp
                                @if (now()->gt($exp))
                                    <span style="color:#dc2626;">
                                        <i class="fas fa-exclamation-circle me-1" style="font-size:.7rem;"></i>
                                        Expired · {{ $exp->format('d M Y') }}
                                    </span>
                                @else
                                    {{ $exp->format('d M Y') }}
                                    <span style="font-size:.7rem;color:var(--muted2);display:block;">
                                        {{ $exp->diffForHumans() }}
                                    </span>
                                @endif
                            @else <span class="empty">No expiry</span> @endif
                        </span>
                    </li>
                    <li>
                        <span class="ml">Published</span>
                        <span class="mv {{ !$post->published_at ? 'empty':'' }}" style="color:var(--muted);">
                            {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d M Y, H:i') : 'Not published' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Assigned User --}}
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-user"></i> Assigned User</p>
            </div>
            <div class="ps-card-body">
                @if ($post->user)
                    <div class="ps-user">
                        <div class="av">{{ strtoupper(substr($post->user->name, 0, 1)) }}</div>
                        <div>
                            <div class="uname">{{ $post->user->name }}</div>
                            <div class="umail">{{ $post->user->email }}</div>
                        </div>
                    </div>
                @else
                    <p class="mb-0" style="font-size:.82rem;color:var(--muted2);">No user assigned.</p>
                @endif
            </div>
        </div>

        {{-- Public URL --}}
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-link"></i> Public URL</p>
            </div>
            <div class="ps-card-body">
                <div class="ps-url-box">
                    <input type="text" id="publicUrl"
                           value="{{ $post->url ?? url('/posts/' . $post->slug) }}" readonly>
                    <button class="ps-url-copy" id="copyBtn" title="Copy URL">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <a href="{{ $post->url ?? url('/posts/' . $post->slug) }}" target="_blank"
                   class="ps-btn ps-btn-ghost w-100 justify-content-center">
                    <i class="fas fa-external-link-alt"></i> View Public Post
                </a>
            </div>
        </div>

        {{-- Activity --}}
        <div class="ps-card">
            <div class="ps-card-hd">
                <p class="ps-card-title"><i class="fas fa-history"></i> Activity</p>
            </div>
            <div class="ps-card-body" style="padding-top:.6rem;padding-bottom:.6rem;">
                <ul class="ps-log">
                    @if ($post->published_at)
                        <li>
                            <span class="log-dot" style="background:#059669;"></span>
                            <div>
                                <div class="fw-semibold" style="font-size:.82rem;color:var(--dk);">Published</div>
                                <div class="log-time">
                                    {{ \Carbon\Carbon::parse($post->published_at)->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </li>
                    @endif
                    @if ($post->updated_at->ne($post->created_at))
                        <li>
                            <span class="log-dot" style="background:#d97706;"></span>
                            <div>
                                <div class="fw-semibold" style="font-size:.82rem;color:var(--dk);">Last Updated</div>
                                <div class="log-time">
                                    {{ $post->updated_at->format('d M Y, H:i') }} ·
                                    {{ $post->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        </li>
                    @endif
                    <li>
                        <span class="log-dot" style="background:var(--accent);"></span>
                        <div>
                            <div class="fw-semibold" style="font-size:.82rem;color:var(--dk);">Created</div>
                            <div class="log-time">
                                {{ $post->created_at->format('d M Y, H:i') }} ·
                                {{ $post->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>{{-- /col-lg-4 --}}
</div>

{{-- ═══════════════════════════════════════════════════════════
     EDIT MODAL — 4 tabs
═══════════════════════════════════════════════════════════ --}}
<div class="modal fade ps-modal" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="ps-modal-icon"><i class="fas fa-pen"></i></div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:.98rem;color:var(--dk);">
                            Edit Post
                        </h5>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:var(--muted2);">
                            #{{ $post->id }} &mdash; {{ Str::limit($post->title, 50) }}
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Tab nav --}}
            <div class="ps-tab-nav" id="editModalTabs">
                <button class="ps-tab-link active" data-tab="etab-basic">
                    <i class="fas fa-align-left"></i> Basic Info
                </button>
                <button class="ps-tab-link" data-tab="etab-location">
                    <i class="fas fa-map-marker-alt"></i> Location
                </button>
                <button class="ps-tab-link" data-tab="etab-media">
                    <i class="fas fa-images"></i> Media
                </button>
                <button class="ps-tab-link" data-tab="etab-seo">
                    <i class="fas fa-search"></i> SEO
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body px-4 py-3">

                {{-- ── Basic ─── --}}
                <div class="modal-tab-pane" id="etab-basic">
                    <p class="modal-section-lbl">Post Information</p>
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" id="e_title" class="form-control"
                                   value="{{ $post->title }}" placeholder="Post title…">
                            <small class="text-danger d-none" id="e_err_title"></small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea id="e_description" class="form-control" rows="5">{{ $post->description }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select id="e_category_id" class="form-select">
                                <option value="">— Select Category —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $post->category_id == $cat->id ? 'selected':'' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="e_err_category_id"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Subcategory</label>
                            <select id="e_subcategory_id" class="form-select">
                                <option value="">— Select Subcategory —</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Locality</label>
                            <select id="e_locality_id" class="form-select">
                                <option value="">— Select Locality —</option>
                                @foreach ($localities as $loc)
                                    <option value="{{ $loc->id }}"
                                        {{ $post->locality_id == $loc->id ? 'selected':'' }}>
                                        {{ $loc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select id="e_status" class="form-select">
                                @foreach (['draft'=>'Draft','published'=>'Published','archived'=>'Archived'] as $v => $l)
                                    <option value="{{ $v }}" {{ $post->status === $v ? 'selected':'' }}>
                                        {{ $l }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="e_err_status"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Assigned User</label>
                            <select id="e_user_id" class="form-select">
                                <option value="">— Select User —</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" {{ $post->user_id == $u->id ? 'selected':'' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" id="e_expiry_date" class="form-control"
                                   value="{{ $post->expiry_date ? \Carbon\Carbon::parse($post->expiry_date)->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                    <hr style="border-color:var(--border);margin:1rem 0;">
                    <p class="modal-section-lbl">Options</p>
                    <div class="d-flex gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="e_is_featured"
                                   {{ $post->is_featured ? 'checked':'' }}>
                            <label class="form-check-label" style="font-size:.82rem;" for="e_is_featured">
                                <i class="fas fa-star me-1 text-warning"></i> Featured
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="e_is_active"
                                   {{ $post->is_active ? 'checked':'' }}>
                            <label class="form-check-label" style="font-size:.82rem;" for="e_is_active">
                                <i class="fas fa-toggle-on me-1 text-success"></i> Active
                            </label>
                        </div>
                    </div>
                </div>

                {{-- ── Location ─── --}}
                <div class="modal-tab-pane d-none" id="etab-location">
                    <p class="modal-section-lbl">Contact Information</p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Company Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-building" style="font-size:.75rem;color:var(--muted2);"></i>
                                </span>
                                <input type="text" id="e_company_name" class="form-control border-start-0"
                                    value="{{ $post->company_name }}" placeholder="e.g. Acme Ltd.">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-phone" style="font-size:.75rem;color:var(--muted2);"></i>
                                </span>
                                <input type="text" id="e_phone_number" class="form-control border-start-0"
                                    value="{{ $post->phone_number }}" placeholder="+971 50 123 4567">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">WhatsApp Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="background:#d1fae5!important;">
                                    <i class="fab fa-whatsapp" style="font-size:.85rem;color:#25d366;"></i>
                                </span>
                                <input type="text" id="e_whatsapp_number" class="form-control border-start-0"
                                    value="{{ $post->whatsapp_number }}" placeholder="+971 50 123 4567">
                            </div>
                        </div>
                    </div>
                    <hr style="border-color:var(--border);margin:1rem 0;">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" id="e_country" class="form-control" value="{{ $post->country }}" placeholder="e.g. UAE">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" id="e_state" class="form-control" value="{{ $post->state }}" placeholder="e.g. Dubai">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" id="e_city" class="form-control" value="{{ $post->city }}" placeholder="e.g. Downtown">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Location Description</label>
                            <input type="text" id="e_location" class="form-control" value="{{ $post->location }}" placeholder="Landmark or area…">
                        </div>
                    </div>
                    <hr style="border-color:var(--border);margin:1rem 0;">
                    <p class="modal-section-lbl">GPS Coordinates
                        <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--muted);">(optional)</span>
                    </p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Latitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs" style="color:var(--muted2);">LAT</span>
                                <input type="number" id="e_latitude" class="form-control border-start-0" value="{{ $post->latitude }}" placeholder="25.2048" step="any">
                            </div>
                            <small class="text-danger d-none" id="e_err_latitude"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs" style="color:var(--muted2);">LNG</span>
                                <input type="number" id="e_longitude" class="form-control border-start-0" value="{{ $post->longitude }}" placeholder="55.2708" step="any">
                            </div>
                            <small class="text-danger d-none" id="e_err_longitude"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Google Maps URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-map" style="font-size:.75rem;color:var(--muted2);"></i>
                                </span>
                                <input type="text" id="e_google_map_url" class="form-control border-start-0" value="{{ $post->google_map_url }}" placeholder="https://maps.google.com/…">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Media ─── --}}
                <div class="modal-tab-pane d-none" id="etab-media">
                    <p class="modal-section-lbl">Images</p>
                    <div id="e_existingImages" class="img-strip">
                        @foreach ($post->getMedia('posts') as $m)
                            <div class="img-wrap" id="media-wrap-{{ $m->id }}">
                                <a href="{{ $m->getUrl() }}" data-fancybox="edit-gallery">
                                    <img src="{{ $m->getUrl() }}" alt="{{ $m->name }}">
                                </a>
                                <button type="button" class="btn btn-danger btn-del-media"
                                        data-id="{{ $m->id }}" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <form action="{{ route('posts.mediaUpload') }}" class="dropzone" id="editDropzone">
                        @csrf
                        <div class="dz-message">
                            <i class="fas fa-cloud-upload-alt me-2" style="color:var(--accent);"></i>
                            <span class="fw-semibold" style="color:var(--dk);">Drop images here</span>
                            <span style="color:var(--muted2);"> or click to upload</span>
                            <br><small style="color:var(--muted2);">Max 5 MB · JPG, PNG, WEBP</small>
                        </div>
                    </form>
                    <hr style="border-color:var(--border);margin:1.25rem 0;">
                    <p class="modal-section-lbl">Video</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Upload Video File</label>
                            <input type="file" id="e_video" class="form-control" accept="video/*">
                            @if ($post->getMedia('videos')->isNotEmpty())
                                <small style="font-size:.72rem;color:#059669;">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Video already uploaded — upload to replace
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Video URL</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fab fa-youtube text-danger" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="e_video_url" class="form-control border-start-0"
                                       value="{{ $post->video_url }}" placeholder="https://youtube.com/watch?v=…">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── SEO ─── --}}
                <div class="modal-tab-pane d-none" id="etab-seo">
                    <p class="modal-section-lbl">SEO & Meta</p>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Meta Title</label>
                            <input type="text" id="e_meta_title" class="form-control"
                                   value="{{ $post->meta_title }}"
                                   placeholder="SEO title (defaults to post title)">
                            <small style="font-size:.72rem;color:var(--muted2);">Recommended: 50–60 characters</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Meta Description</label>
                            <textarea id="e_meta_description" class="form-control" rows="3"
                                      placeholder="Short description for search engines…">{{ $post->meta_description }}</textarea>
                            <small style="font-size:.72rem;color:var(--muted2);">Recommended: 150–160 characters</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keywords</label>
                            <input type="text" id="e_keywords" class="form-control"
                                   value="{{ $post->keywords }}" placeholder="keyword1, keyword2, keyword3…">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="color:var(--muted2);">
                                Live Search Preview
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
            <div class="modal-footer justify-content-between">
                <span style="font-size:.72rem;color:var(--muted2);">
                    <i class="fas fa-clock me-1"></i>
                    Last updated {{ $post->updated_at->format('d M Y, H:i') }}
                    ({{ $post->updated_at->diffForHumans() }})
                </span>
                <div class="d-flex gap-2">
                    <button class="ps-btn ps-btn-ghost" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="ps-btn ps-btn-warn" id="saveEditPost">
                        <span id="saveEditText"><i class="fas fa-save"></i> Save Changes</span>
                        <span id="saveEditSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm"></span> Saving…
                        </span>
                    </button>
                </div>
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
const POST_ID = {{ $post->id }};
let editorInst = null;

// ── CKEditor ──────────────────────────────────────────────────
ClassicEditor.create(document.querySelector('#e_description'))
    .then(e => { editorInst = e; }).catch(console.error);

// ── Dropzone ──────────────────────────────────────────────────
Dropzone.autoDiscover = false;
const editDZ = new Dropzone('#editDropzone', {
    url: '{{ route("posts.mediaUpload") }}',
    autoProcessQueue: true, parallelUploads: 3,
    maxFilesize: 5, acceptedFiles: 'image/*',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    sending: (file, xhr, fd) => fd.append('post_id', POST_ID),
    success: function(file, res) {
        if (res.url) appendThumb(res.id, res.url);
        this.removeFile(file);
    },
    error: (f, msg) => console.error('Dropzone error:', msg),
});

function appendThumb(id, url) {
    $('#e_existingImages').append(`
        <div class="img-wrap" id="media-wrap-${id}">
            <a href="${url}" data-fancybox="edit-gallery"><img src="${url}" alt=""></a>
            <button type="button" class="btn btn-danger btn-del-media"
                    data-id="${id}" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>`);
}

// ── Fancybox ──────────────────────────────────────────────────
Fancybox.bind('[data-fancybox="post-gallery"]', { Toolbar: { display: ['close'] } });
Fancybox.bind('[data-fancybox="edit-gallery"]', { Toolbar: { display: ['close'] } });

// ── Modal tabs ────────────────────────────────────────────────
$(document).on('click', '#editModalTabs .ps-tab-link', function() {
    var target = $(this).data('tab');
    $('#editModalTabs .ps-tab-link').removeClass('active');
    $(this).addClass('active');
    $('.modal-tab-pane').addClass('d-none');
    $('#' + target).removeClass('d-none');
});
function switchToTab(id) {
    $('#editModalTabs .ps-tab-link[data-tab="' + id + '"]').trigger('click');
}

// ── Error helpers ─────────────────────────────────────────────
var fieldTabMap = {
    title:'etab-basic', description:'etab-basic',
    category_id:'etab-basic', status:'etab-basic',
    latitude:'etab-location', longitude:'etab-location',
    meta_title:'etab-seo', meta_description:'etab-seo', keywords:'etab-seo',
};
function clearErrors() {
    $('.text-danger[id^="e_err_"]').addClass('d-none').text('');
    $('#editModal .form-control, #editModal .form-select').removeClass('is-invalid');
    $('#editModalTabs .tab-err-dot').remove();
}
function showErrors(errors) {
    var firstTab = null, tabsWithErrors = {};
    $.each(errors, function(field, msgs) {
        $('#e_err_' + field).removeClass('d-none').text(msgs[0]);
        $('#e_' + field).addClass('is-invalid');
        var tab = fieldTabMap[field] || 'etab-basic';
        tabsWithErrors[tab] = true;
        if (!firstTab) firstTab = tab;
    });
    $.each(tabsWithErrors, function(tab) {
        var link = $('#editModalTabs .ps-tab-link[data-tab="' + tab + '"]');
        if (!link.find('.tab-err-dot').length)
            link.append('<span class="tab-err-dot ms-1"></span>');
    });
    if (firstTab) switchToTab(firstTab);
}

// ── Category cascade ──────────────────────────────────────────
$(function() {
    var catId = '{{ $post->category_id }}';
    var subId = '{{ $post->subcategory_id }}';
    if (catId) {
        $.get('{{ url("admin/get-subcategories") }}/' + catId, function(res) {
            var html = '<option value="">— Select Subcategory —</option>';
            res.forEach(r => {
                html += `<option value="${r.id}"${r.id == subId?' selected':''}>${r.name}</option>`;
            });
            $('#e_subcategory_id').html(html);
        });
    }
    $('#e_category_id').on('change', function() {
        var id = $(this).val();
        $('#e_subcategory_id').html('<option value="">— Select Subcategory —</option>');
        if (!id) return;
        $.get('{{ url("admin/get-subcategories") }}/' + id, function(res) {
            var html = '<option value="">— Select Subcategory —</option>';
            res.forEach(r => { html += `<option value="${r.id}">${r.name}</option>`; });
            $('#e_subcategory_id').html(html);
        });
    });
});

// ── Live SEO preview ──────────────────────────────────────────
$('#e_meta_title').on('input', function() {
    $('#seo_prev_title').text($(this).val().trim() || '{{ addslashes($post->title) }}');
});
$('#e_meta_description').on('input', function() {
    $('#seo_prev_desc').text($(this).val().trim() || '{{ Str::limit(addslashes(strip_tags($post->description ?? "")), 160) }}');
});

// ── Delete media ──────────────────────────────────────────────
$(document).on('click', '.btn-del-media', function() {
    var id = $(this).data('id');
    Swal.fire({
        title:'Remove image?', icon:'warning',
        showCancelButton:true, confirmButtonColor:'#dc2626',
        confirmButtonText:'Remove',
    }).then(r => {
        if (!r.isConfirmed) return;
        $.ajax({
            url:'{{ url("admin/posts/media") }}/' + id, type:'POST',
            data:{ _token:'{{ csrf_token() }}', _method:'DELETE' },
            success: res => { if (res.success) $('#media-wrap-' + id).remove(); }
        });
    });
});

// ── Save ──────────────────────────────────────────────────────
$('#saveEditPost').on('click', function() {
    clearErrors();
    $('#saveEditText').addClass('d-none');
    $('#saveEditSpinner').removeClass('d-none');
    $('#saveEditPost').prop('disabled', true);

    var fd = new FormData();
    fd.append('_token','{{ csrf_token() }}'); fd.append('_method','PUT');
    fd.append('title',$('#e_title').val());
    fd.append('description', editorInst ? editorInst.getData() : $('#e_description').val());
    fd.append('category_id',$('#e_category_id').val());
    fd.append('subcategory_id',$('#e_subcategory_id').val());
    fd.append('locality_id',$('#e_locality_id').val());
    fd.append('status',$('#e_status').val());
    fd.append('company_name', $('#e_company_name').val());
    fd.append('phone_number',  $('#e_phone_number').val());
    fd.append('whatsapp_number', $('#e_whatsapp_number').val());
    fd.append('user_id',$('#e_user_id').val());
    fd.append('expiry_date',$('#e_expiry_date').val());
    fd.append('is_featured',$('#e_is_featured').is(':checked')?1:0);
    fd.append('is_active',$('#e_is_active').is(':checked')?1:0);
    fd.append('country',$('#e_country').val()); fd.append('state',$('#e_state').val());
    fd.append('city',$('#e_city').val()); fd.append('location',$('#e_location').val());
    fd.append('latitude',$('#e_latitude').val()); fd.append('longitude',$('#e_longitude').val());
    fd.append('google_map_url',$('#e_google_map_url').val());
    fd.append('video_url',$('#e_video_url').val());
    var vf = document.getElementById('e_video').files[0];
    if (vf) fd.append('video', vf);
    fd.append('meta_title',$('#e_meta_title').val());
    fd.append('meta_description',$('#e_meta_description').val());
    fd.append('keywords',$('#e_keywords').val());

    $.ajax({
        url:'{{ url("admin/posts") }}/' + POST_ID, type:'POST',
        data:fd, processData:false, contentType:false,
        success: function(res) {
            if (res.success) {
                $('#editModal').modal('hide');
                Swal.fire({ icon:'success', title:'Post updated!',
                    timer:1500, showConfirmButton:false })
                    .then(() => location.reload());
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) showErrors(xhr.responseJSON.errors ?? {});
            else Swal.fire('Error', 'Something went wrong.', 'error');
        },
        complete: function() {
            $('#saveEditText').removeClass('d-none');
            $('#saveEditSpinner').addClass('d-none');
            $('#saveEditPost').prop('disabled', false);
        }
    });
});

// ── Copy URL ──────────────────────────────────────────────────
$('#copyBtn').on('click', function() {
    navigator.clipboard.writeText($('#publicUrl').val()).then(() => {
        $(this).html('<i class="fas fa-check" style="color:#059669;"></i>');
        setTimeout(() => $(this).html('<i class="fas fa-copy"></i>'), 1800);
    });
});

// ── Delete post ───────────────────────────────────────────────
$('#deletePostBtn').on('click', function() {
    Swal.fire({
        title:'Move to Trash?',
        html:'<strong>{{ addslashes($post->title) }}</strong> will be moved to the trash.',
        icon:'warning', showCancelButton:true,
        confirmButtonColor:'#dc2626', cancelButtonColor:'#64748b',
        confirmButtonText:'Move to trash',
    }).then(r => {
        if (!r.isConfirmed) return;
        $.ajax({
            url:'{{ url("admin/posts") }}/{{ $post->id }}', type:'POST',
            data:{ _token:'{{ csrf_token() }}', _method:'DELETE' },
            success: res => {
                if (res.success) {
                    Swal.fire({ icon:'success', title:'Moved to trash!',
                        timer:1200, showConfirmButton:false })
                        .then(() => window.location.href = '{{ route("posts.index") }}');
                }
            }
        });
    });
});
</script>
@endpush