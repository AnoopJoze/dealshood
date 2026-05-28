<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/frontend/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/frontend/img/favicon.png">
    <title>DealsHood — Discover the Best Deals Near You</title>
    @php
        $ogImage = str_replace('http://', 'https://', url('/frontend/img/favicon.png'));
        $ogTitle = 'DealsHood — Discover the Best Deals Near You';
        $ogDesc  = 'Find great offers from your neighbourhood, every day.';
        $ogUrl   = url()->current();
    @endphp
    <meta name="description" content="{{ $ogDesc }}">
    <link rel="canonical" href="{{ $ogUrl }}">
    <meta property="og:site_name" content="DealsHood">
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:url"         content="{{ $ogUrl }}">
    <meta property="og:image"       content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDesc }}">
    <meta name="twitter:image"       content="{{ $ogImage }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet">

    <style>
    :root {
        --ink:       #0d0d0d;
        --ink-mid:   #3a3a3a;
        --ink-muted: #6b6b6b;
        --surface:   #faf9f7;
        --surface-2: #f2f1ef;
        --white:     #ffffff;
        --accent:    #0f3f7e;
        --r:         14px;
        --rlg:       20px;
        --sh-sm:     0 2px 12px rgba(0,0,0,.07);
        --sh-md:     0 6px 32px rgba(0,0,0,.10);
        --sh-lg:     0 20px 60px rgba(0,0,0,.15);
        --nav-h:     64px;
    }
    *,*::before,*::after { box-sizing:border-box; }
    body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;
           background:var(--surface); color:var(--ink); margin:0; }

    /* ── Navbar ─────────────────────────────────────────── */
    .dh-nav {
        position:fixed; top:0; left:0; right:0; height:var(--nav-h);
        background:#fff; border-bottom:1px solid rgba(0,0,0,.07);
        z-index:1000; display:flex; align-items:center;
    }
    .dh-nav-inner {
        display:flex; align-items:center; justify-content:space-between;
        width:100%; max-width:1180px; margin:0 auto; padding:0 24px;
    }
    .dh-nav-logo img { height:45px; display:block; }
    .dh-nav-actions  { display:flex; align-items:center; gap:10px; }
    .dh-btn-nav {
        display:inline-flex; align-items:center; gap:6px; font-size:.75rem;
        font-weight:500; letter-spacing:.04em; border:none; cursor:pointer;
        border-radius:100px; padding:9px 18px; text-decoration:none; transition:transform .15s;
    }
    .dh-btn-nav:hover { transform:translateY(-1px); }
    .dh-btn-ig { background:#e1306c; color:#fff; }
    .dh-btn-wa { background:#25d366; color:#fff; }
    .dh-nav-toggle {
        display:none; background:none; border:none; cursor:pointer;
        flex-direction:column; gap:5px; padding:6px;
    }
    .dh-nav-toggle span { display:block; width:22px; height:2px;
                          background:var(--ink); border-radius:2px; }
    @media(max-width:640px) {
        .dh-nav-toggle { display:flex; }
        .dh-nav-actions {
            display:none; position:absolute; top:var(--nav-h); left:0; right:0;
            background:#fff; border-bottom:1px solid rgba(0,0,0,.08);
            padding:16px 24px; flex-direction:column; align-items:flex-start; gap:10px;
        }
        .dh-nav-actions.open { display:flex; }
    }

    /* ── Hero ─────────────────────────────────────────────── */
    .dh-hero {
        padding-top:var(--nav-h); position:relative; overflow:hidden;
        background:var(--ink); min-height:500px;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
    }
    .dh-hero-bg {
        position:absolute; inset:0;
        background:url('/frontend/img/office-dark.jpg') center/cover no-repeat;
        opacity:.42;
    }
    .dh-hero-overlay {
        position:absolute; inset:0;
        background:linear-gradient(160deg,rgba(13,13,13,.78) 0%,rgba(13,13,13,.32) 55%,rgba(15,63,126,.2) 100%);
    }
    .dh-hero-fade {
        position:absolute; bottom:0; left:0; right:0; height:80px;
        background:linear-gradient(to bottom,transparent,var(--surface)); z-index:2;
    }
    .dh-hero-text {
        position:relative; z-index:3; text-align:center;
        max-width:600px; padding:44px 24px 0;
        animation:fadeUp .55s .1s both;
    }
    .dh-hero-eyebrow {
        display:inline-flex; align-items:center; gap:8px; font-size:.66rem;
        font-weight:500; letter-spacing:.18em; text-transform:uppercase;
        color:rgba(255,255,255,.5); margin-bottom:14px;
    }
    .dh-hero-eyebrow::before,.dh-hero-eyebrow::after {
        content:''; display:inline-block; width:18px; height:1.5px;
        background:rgba(255,255,255,.35); border-radius:2px;
    }
    .dh-hero-title {
        font-size:clamp(2rem,4.5vw,3.2rem); font-weight:800; color:#fff;
        line-height:1.14; letter-spacing:-.025em; margin:0 0 10px;
    }
    .dh-hero-sub {
        font-size:.95rem; color:rgba(255,255,255,.58); font-weight:300;
        line-height:1.6; margin:0 0 22px;
    }
    .dh-hero-cta {
        display:inline-flex; align-items:center; gap:8px;
        background:#fff; color:var(--ink); font-size:.8rem; font-weight:600;
        border-radius:100px; padding:11px 26px; text-decoration:none;
        box-shadow:0 4px 20px rgba(0,0,0,.25); transition:transform .15s,box-shadow .15s;
    }
    .dh-hero-cta:hover { transform:translateY(-2px); box-shadow:var(--sh-lg); color:var(--accent); }

    /* ── Category tiles inside hero ─────────────────────── */
    .dh-hero-cats {
        position:relative; z-index:4; width:100%; max-width:1100px;
        padding:28px 24px 56px; animation:fadeUp .55s .3s both;
    }
    .dh-hero-cats-label {
        font-size:.62rem; font-weight:600; letter-spacing:.16em; text-transform:uppercase;
        color:rgba(255,255,255,.4); text-align:center; margin-bottom:18px;
    }
    .dh-cat-grid {
        display:flex; flex-wrap:wrap; justify-content:center; gap:12px;
    }
    @media(max-width:600px) {
        .dh-cat-grid {
            flex-wrap:nowrap; overflow-x:auto; justify-content:flex-start;
            padding-bottom:6px; cursor:grab;
            -ms-overflow-style:none; scrollbar-width:none;
        }
        .dh-cat-grid::-webkit-scrollbar { display:none; }
        .dh-cat-grid:active { cursor:grabbing; }
    }

    /* Square tile */
    .dh-cat-tile {
        flex:0 0 auto; width:88px;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        gap:8px; padding:14px 8px 12px; border-radius:16px;
        text-decoration:none; text-align:center; color:#fff;
        font-size:.7rem; font-weight:600; line-height:1.25;
        background:rgba(255,255,255,.11);
        backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px);
        border:1.5px solid rgba(255,255,255,.16);
        transition:transform .2s,background .2s,box-shadow .2s,border-color .2s;
        user-select:none;
    }
    .dh-cat-tile:hover {
        transform:translateY(-5px) scale(1.05);
        background:rgba(255,255,255,.2);
        border-color:rgba(255,255,255,.4);
        box-shadow:0 10px 32px rgba(0,0,0,.28); color:#fff;
    }
    .dh-cat-tile .tile-icon {
        width:40px; height:40px; border-radius:12px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        font-size:.9rem; background:rgba(255,255,255,.16);
        transition:transform .2s;
    }
    .dh-cat-tile:hover .tile-icon { transform:scale(1.12); }
    .dh-cat-tile .tile-name { white-space:nowrap; overflow:hidden;
                               text-overflow:ellipsis; max-width:74px; }
    .dh-cat-tile .tile-count { font-size:.6rem; font-weight:400; opacity:.6; }

    /* "All" tile — white opaque */
    .dh-cat-tile.tile-all {
        background:rgba(255,255,255,.95); color:var(--ink);
        border-color:transparent; box-shadow:0 4px 16px rgba(0,0,0,.18);
    }
    .dh-cat-tile.tile-all:hover { background:#fff; color:var(--accent); }
    .dh-cat-tile.tile-all .tile-icon { background:var(--surface-2); color:var(--accent); }

    /* ── Section wrapper ─────────────────────────────────── */
    .dh-wrap { max-width:1180px; margin:0 auto; padding:0 24px; }

    /* ── Carousel section ────────────────────────────────── */
    .dh-carousel-sec { padding:32px 0 64px; background:var(--surface); }

    .dh-carousel-block { margin-bottom:52px; }
    .dh-carousel-block:last-child { margin-bottom:0; }

    .dh-carousel-head {
        display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;
    }
    .dh-carousel-title {
        font-size:1.12rem; font-weight:700; color:var(--ink); margin:0;
        display:flex; align-items:center; gap:10px;
    }
    .cat-badge {
        font-size:.61rem; font-weight:600; letter-spacing:.07em;
        text-transform:uppercase; padding:3px 10px; border-radius:100px;
    }
    .dh-carousel-controls { display:flex; align-items:center; gap:8px; }
    .dh-view-all {
        font-size:.77rem; font-weight:500; color:var(--ink-muted);
        text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:color .15s;
    }
    .dh-view-all:hover { color:var(--accent); }
    .dh-c-btn {
        width:32px; height:32px; border-radius:50%;
        border:1.5px solid rgba(0,0,0,.12); background:#fff; cursor:pointer;
        display:flex; align-items:center; justify-content:center;
        font-size:.8rem; color:var(--ink-muted); transition:all .15s;
    }
    .dh-c-btn:hover { background:var(--ink); color:#fff; border-color:var(--ink); }

    /* Track */
    .dh-track-outer { position:relative; overflow:hidden; }
    .dh-track {
        display:flex; gap:16px; overflow-x:auto; padding-bottom:12px;
        scroll-behavior:smooth; scroll-snap-type:x mandatory;
        -ms-overflow-style:none; scrollbar-width:none;
        cursor:grab; user-select:none;
    }
    .dh-track.is-dragging { cursor:grabbing; scroll-behavior:auto; }
    .dh-track::-webkit-scrollbar { display:none; }
    .dh-track-outer::after {
        content:''; position:absolute; right:0; top:0; bottom:12px; width:64px;
        pointer-events:none;
        background:linear-gradient(to left,var(--surface),transparent); z-index:1;
    }

    /* Carousel card */
    .dh-cc {
        flex:0 0 240px; scroll-snap-align:start;
        background:#fff; border-radius:var(--r); overflow:hidden;
        border:1px solid rgba(0,0,0,.06); box-shadow:var(--sh-sm);
        transition:transform .22s,box-shadow .22s;
        text-decoration:none; color:var(--ink);
        display:flex; flex-direction:column; -webkit-user-drag:none;
    }
    .dh-cc:hover { transform:translateY(-5px); box-shadow:var(--sh-md); }
    .dh-track.is-dragging .dh-cc { pointer-events:none; }
    @media(max-width:560px) { .dh-cc { flex:0 0 200px; } }

    .dh-cc-img-wrap { position:relative; overflow:hidden; flex-shrink:0; }
    .dh-cc-img { width:100%; height:152px; object-fit:cover; display:block; transition:transform .32s; }
    .dh-cc:hover .dh-cc-img { transform:scale(1.06); }
    .dh-cc-feat {
        position:absolute; top:8px; right:8px; background:#f59e0b; color:#fff;
        font-size:.55rem; font-weight:700; letter-spacing:.09em;
        text-transform:uppercase; padding:3px 8px; border-radius:100px;
    }
    .dh-cc-body { padding:12px 13px 14px; display:flex; flex-direction:column; flex:1; }
    .dh-cc-loc { font-size:.62rem; color:var(--ink-muted); margin-bottom:5px;
                 display:flex; align-items:center; gap:4px; }
    .dh-cc-title {
        font-size:.83rem; font-weight:600; line-height:1.35; margin:0 0 auto;
        display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    }
    .dh-cc-meta {
        display:flex; align-items:center; gap:10px; margin-top:10px; padding-top:8px;
        border-top:1px solid rgba(0,0,0,.05); font-size:.67rem; color:var(--ink-muted);
    }
    .dh-cc-meta span { display:flex; align-items:center; gap:3px; }

    /* ── Latest deals grid ───────────────────────────────── */
    .dh-latest-sec { padding:0 0 80px; background:var(--surface); }
    .dh-sec-head {
        display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:24px;
    }
    .dh-eyebrow {
        display:inline-flex; align-items:center; gap:8px; font-size:.67rem;
        font-weight:500; letter-spacing:.14em; text-transform:uppercase;
        color:var(--accent); margin-bottom:5px;
    }
    .dh-eyebrow::before { content:''; display:inline-block; width:18px; height:2px;
                          background:var(--accent); border-radius:2px; }
    .dh-sec-title { font-size:1.5rem; font-weight:700; color:var(--ink); margin:0; }

    .dh-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
    @media(max-width:900px) { .dh-grid { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:560px) { .dh-grid { grid-template-columns:1fr; } }

    .dh-card {
        background:#fff; border-radius:var(--rlg); overflow:hidden;
        box-shadow:var(--sh-sm); border:1px solid rgba(0,0,0,.05);
        display:flex; flex-direction:column; transition:transform .22s,box-shadow .22s;
    }
    .dh-card:hover { transform:translateY(-5px); box-shadow:var(--sh-md); }
    .dh-card-media { position:relative; overflow:hidden; flex-shrink:0; }
    .dh-card-media img,.dh-card-media video {
        width:100%; height:220px; object-fit:cover; display:block; transition:transform .35s;
    }
    .dh-card:hover .dh-card-media img { transform:scale(1.04); }
    .dh-card-media .ratio { height:220px; }
    .badge-feat {
        position:absolute; top:12px; right:12px; background:#f59e0b; color:#fff;
        font-size:.6rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase;
        padding:4px 10px; border-radius:100px;
    }
    .dh-card-body { padding:18px 20px 20px; display:flex; flex-direction:column; flex:1; }
    .dh-badges { display:flex; flex-wrap:wrap; gap:5px; margin-bottom:10px; }
    .dh-b { font-size:.6rem; font-weight:500; letter-spacing:.07em; text-transform:uppercase;
            padding:3px 9px; border-radius:100px; }
    .dh-b-loc { background:var(--surface-2); color:var(--ink-muted); }
    .dh-b-cat { background:rgba(15,63,126,.08); color:var(--accent); }
    .dh-b-sub { background:rgba(59,130,246,.08); color:#1d4ed8; }
    .dh-card-title {
        font-size:1rem; font-weight:700; color:var(--ink); line-height:1.35;
        margin:0 0 8px; text-decoration:none; display:block; transition:color .15s;
    }
    .dh-card-title:hover { color:var(--accent); }
    .dh-card-desc { font-size:.81rem; line-height:1.65; color:var(--ink-muted);
                    font-weight:300; flex:1; margin-bottom:14px; }
    .dh-card-meta {
        display:flex; align-items:center; gap:10px; flex-wrap:wrap;
        padding-top:10px; border-top:1px solid rgba(0,0,0,.06); margin-bottom:14px;
    }
    .dh-meta-btn,.dh-meta-box {
        display:flex; align-items:center; gap:7px; padding:4px 10px;
        border-radius:14px; background:#fff; border:1px solid #edf0f5;
        transition:all .2s; box-shadow:0 2px 8px rgba(0,0,0,.04);
    }
    .dh-meta-btn { cursor:pointer; outline:none; }
    .dh-meta-btn:hover,.dh-meta-box:hover { transform:translateY(-2px); box-shadow:0 5px 16px rgba(0,0,0,.08); }
    .dh-meta-icon { font-size:12px; color:#6b7280; }
    .dh-meta-count { font-size:13px; font-weight:600; color:#1f2937; }
    .dh-meta-time { margin-left:auto; display:flex; align-items:center;
                    gap:6px; font-size:12px; color:#6b7280; }
    .likeBtn.liked { background:rgba(255,77,109,.08); border-color:rgba(255,77,109,.18); }
    .likeBtn.liked .dh-meta-icon { color:#ff4d6d; }
    .likeBtn.liked .dh-meta-count { color:#ff4d6d; }
    .dh-card-actions { display:flex; gap:8px; }
    .dh-btn {
        display:inline-flex; align-items:center; justify-content:center; gap:6px;
        font-size:.75rem; font-weight:500; border-radius:100px;
        padding:9px 18px; text-decoration:none; border:1.5px solid;
        cursor:pointer; transition:all .15s; flex:1;
    }
    .dh-btn-primary { background:var(--ink); color:#fff; border-color:var(--ink); }
    .dh-btn-primary:hover { background:var(--accent); border-color:var(--accent); color:#fff; }
    .dh-btn-ghost { background:transparent; color:var(--ink-muted);
                   border-color:rgba(0,0,0,.12); flex:0 0 auto; padding:9px 14px; }
    .dh-btn-ghost:hover { background:var(--surface-2); color:var(--ink); }
    .dh-empty { grid-column:1/-1; text-align:center; padding:64px 24px; color:var(--ink-muted); }

    .dh-show-more { text-align:center; margin-top:36px; }
    .dh-more-btn {
        display:inline-flex; align-items:center; gap:8px; font-size:.79rem;
        font-weight:500; color:var(--ink); background:#fff; cursor:pointer;
        border:1.5px solid rgba(0,0,0,.14); border-radius:100px; padding:12px 28px;
        transition:all .18s;
    }
    .dh-more-btn:hover { background:var(--ink); color:#fff; transform:translateY(-2px); }

    /* ── Footer ─────────────────────────────────────────── */
    .dh-footer { background:var(--ink); color:rgba(255,255,255,.7); padding:60px 0 0; font-size:.84rem; }
    .dh-footer-grid { display:grid; grid-template-columns:1.6fr 1fr 1fr; gap:48px; padding-bottom:48px; }
    @media(max-width:720px) { .dh-footer-grid { grid-template-columns:1fr 1fr; } }
    @media(max-width:440px) { .dh-footer-grid { grid-template-columns:1fr; } }
    .dh-footer-brand { font-size:1.1rem; color:#fff; margin:12px 0 5px; }
    .dh-footer-tag   { font-size:.77rem; color:rgba(255,255,255,.38); margin:0; }
    .dh-footer-social { display:flex; gap:8px; margin-top:18px; }
    .dh-footer-social a {
        width:34px; height:34px; border-radius:50%; border:1px solid rgba(255,255,255,.15);
        display:flex; align-items:center; justify-content:center;
        color:rgba(255,255,255,.6); font-size:.88rem; text-decoration:none; transition:.15s;
    }
    .dh-footer-social a:hover { border-color:rgba(255,255,255,.5); color:#fff; }
    .dh-footer-col-title { font-size:.64rem; font-weight:600; letter-spacing:.14em;
                           text-transform:uppercase; color:var(--accent); margin-bottom:14px; }
    .dh-footer-links { list-style:none; padding:0; margin:0; }
    .dh-footer-links li { margin-bottom:10px; }
    .dh-footer-links a { color:rgba(255,255,255,.52); text-decoration:none; transition:color .15s; }
    .dh-footer-links a:hover { color:#fff; }
    .dh-footer-bottom { border-top:1px solid rgba(255,255,255,.08); text-align:center;
                        padding:20px 0; font-size:.74rem; color:rgba(255,255,255,.3); }
    .dh-footer-bottom a { color:rgba(255,255,255,.48); text-decoration:none; }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(20px); }
        to   { opacity:1; transform:translateY(0); }
    }
    </style>
</head>
<body>

{{-- ── Navbar ─────────────────────────────────────────────── --}}
<nav class="dh-nav">
    <div class="dh-nav-inner">
        <a href="{{ route('home') }}">
            <img src="/frontend/img/dealshood.png" alt="DealsHood" style="height:45px;">
        </a>
        <button class="dh-nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <div class="dh-nav-actions" id="navActions">
            <a href="{{ route('posts.listing') }}" class="dh-btn-nav"
               style="background:#f1f5f9;color:var(--ink);">
                <i class="bi bi-grid-3x3-gap"></i> Browse All
            </a>
            <a href="https://www.instagram.com/dealshood?igsh=NHJpdDhkYmJ2dTlj"
               target="_blank" class="dh-btn-nav dh-btn-ig">
                <i class="bi bi-instagram"></i> Follow
            </a>
            <a href="https://wa.me/918086087050?text=Hello%20I%20am%20interested%20in%20your%20listing"
               target="_blank" class="dh-btn-nav dh-btn-wa">
                <i class="bi bi-whatsapp"></i> Contact
            </a>
        </div>
    </div>
</nav>

@php
$palette = [
    ['bg'=>'rgba(219,234,254,.9)','ic'=>'#1d4ed8','icon'=>'fa-tags'],
    ['bg'=>'rgba(209,250,229,.9)','ic'=>'#059669','icon'=>'fa-leaf'],
    ['bg'=>'rgba(254,243,199,.9)','ic'=>'#d97706','icon'=>'fa-fire'],
    ['bg'=>'rgba(252,231,243,.9)','ic'=>'#db2777','icon'=>'fa-heart'],
    ['bg'=>'rgba(237,233,254,.9)','ic'=>'#7c3aed','icon'=>'fa-gem'],
    ['bg'=>'rgba(207,250,254,.9)','ic'=>'#0891b2','icon'=>'fa-bolt'],
    ['bg'=>'rgba(254,242,242,.9)','ic'=>'#dc2626','icon'=>'fa-percent'],
    ['bg'=>'rgba(236,253,245,.9)','ic'=>'#16a34a','icon'=>'fa-star'],
    ['bg'=>'rgba(255,247,237,.9)','ic'=>'#ea580c','icon'=>'fa-house'],
    ['bg'=>'rgba(240,249,255,.9)','ic'=>'#0284c7','icon'=>'fa-car'],
    ['bg'=>'rgba(253,244,255,.9)','ic'=>'#a21caf','icon'=>'fa-shirt'],
    ['bg'=>'rgba(248,250,252,.9)','ic'=>'#475569','icon'=>'fa-laptop'],
];
@endphp

{{-- ═════════════════════════════════════════════════════
     HERO + CATEGORY TILES
═════════════════════════════════════════════════════ --}}
<header class="dh-hero">
    <div class="dh-hero-bg" id="heroBg"></div>
    <div class="dh-hero-overlay"></div>

    <div class="dh-hero-text">
        <div class="dh-hero-eyebrow">Deals &bull; Offers &bull; Discounts</div>
        <h1 class="dh-hero-title">DealsHood</h1>
        <p class="dh-hero-sub">Discover the best deals near you.<br>Fresh offers, updated every day.</p>
        <a href="{{ route('posts.listing') }}" class="dh-hero-cta">
            <i class="bi bi-lightning-charge-fill"></i> Browse All Deals
        </a>
    </div>

    <div class="dh-hero-cats">
        <p class="dh-hero-cats-label">Browse by Category</p>
        <div class="dh-cat-grid" id="catStrip">

            <a href="{{ route('posts.listing') }}" class="dh-cat-tile tile-all">
                <span class="tile-icon">
                    <i class="fas fa-th" style="font-size:.85rem;"></i>
                </span>
                <span class="tile-name">All Deals</span>
                <span class="tile-count">Browse</span>
            </a>

            @foreach ($categories as $i => $cat)
                @php $p = $palette[$i % count($palette)]; @endphp
                <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}"
                   class="dh-cat-tile">
                    <span class="tile-icon"
                          style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                        <i class="fas {{ $p['icon'] }}" style="font-size:.85rem;"></i>
                    </span>
                    <span class="tile-name">{{ $cat->name }}</span>
                    <span class="tile-count">{{ number_format($cat->posts_count) }}</span>
                </a>
            @endforeach

        </div>
    </div>

    <div class="dh-hero-fade"></div>
</header>

{{-- ═════════════════════════════════════════════════════
     CATEGORY CAROUSELS
═════════════════════════════════════════════════════ --}}
<section class="dh-carousel-sec">
    <div class="dh-wrap">

        @foreach ($categoryCarousels as $i => $cat)
            @if ($cat->posts->isNotEmpty())
                @php $p = $palette[$i % count($palette)]; @endphp
                <div class="dh-carousel-block">
                    <div class="dh-carousel-head">
                        <h3 class="dh-carousel-title">
                            <span style="width:34px;height:34px;border-radius:9px;flex-shrink:0;
                                         display:flex;align-items:center;justify-content:center;
                                         font-size:.85rem;background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                                <i class="fas {{ $p['icon'] }}"></i>
                            </span>
                            {{ $cat->name }}
                            <span class="cat-badge" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                                Popular
                            </span>
                        </h3>
                        <div class="dh-carousel-controls">
                            <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}"
                               class="dh-view-all me-1">
                                See all {{ number_format($cat->posts_count) }}
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            <button class="dh-c-btn c-prev" data-target="cr-{{ $cat->id }}" aria-label="Prev">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="dh-c-btn c-next" data-target="cr-{{ $cat->id }}" aria-label="Next">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <div class="dh-track-outer">
                        <div class="dh-track" id="cr-{{ $cat->id }}">
                            @foreach ($cat->posts as $post)
                                @php
                                    $img = $post->getFirstMediaUrl('posts') ?: asset('frontend/img/default.jpg');
                                @endphp
                                <a href="{{ $post->url }}" class="dh-cc">
                                    <div class="dh-cc-img-wrap">
                                        <img src="{{ $img }}" alt="{{ $post->title }}"
                                             class="dh-cc-img" loading="lazy">
                                        @if ($post->is_featured)
                                            <span class="dh-cc-feat">⭐ Featured</span>
                                        @endif
                                    </div>
                                    <div class="dh-cc-body">
                                        @if ($post->locality)
                                            <div class="dh-cc-loc">
                                                <i class="fas fa-map-marker-alt"
                                                   style="color:var(--accent);font-size:.6rem;"></i>
                                                {{ $post->locality->name }}
                                            </div>
                                        @endif
                                        <div class="dh-cc-title">{{ Str::limit($post->title, 55) }}</div>
                                        <div class="dh-cc-meta">
                                            <span>
                                                <i class="fas fa-eye" style="font-size:.6rem;"></i>
                                                {{ number_format($post->views ?? 0) }}
                                            </span>
                                            <span>
                                                <i class="fas fa-heart" style="font-size:.6rem;color:#ff4d6d;"></i>
                                                {{ number_format($post->likes_data_count ?? 0) }}
                                            </span>
                                            <span style="margin-left:auto;font-size:.62rem;">
                                                {{ $post->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
</section>

{{-- ═════════════════════════════════════════════════════
     LATEST DEALS
═════════════════════════════════════════════════════ --}}
<section class="dh-latest-sec">
    <div class="dh-wrap">
        <div class="dh-sec-head">
            <div>
                <div class="dh-eyebrow">Just in</div>
                <h2 class="dh-sec-title">Latest Deals</h2>
            </div>
            <a href="{{ route('posts.listing') }}" class="dh-view-all">
                View all <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="dh-grid" id="postsGrid">
            @forelse ($posts as $post)
                @php
                    $img   = $post->getFirstMediaUrl('posts') ?: asset('frontend/img/default.jpg');
                    $liked = \App\Models\PostLike::where('post_id', $post->id)
                        ->where(fn($q) => $q->where('ip_address', request()->ip())
                                           ->orWhere('session_id', session()->getId()))
                        ->exists();
                    $video = $post->getFirstMediaUrl('videos');
                @endphp
                <div class="dh-card">
                    <div class="dh-card-media">
                        <a href="{{ $post->url }}">
                            @if ($video)
                                <video preload="metadata" muted><source src="{{ $video }}"></video>
                            @elseif ($post->video_url)
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{ str_replace('watch?v=','embed/',$post->video_url) }}"
                                            allowfullscreen loading="lazy"></iframe>
                                </div>
                            @else
                                <img src="{{ $img }}" alt="{{ $post->title }}" loading="lazy">
                            @endif
                        </a>
                        @if ($post->is_featured)<span class="badge-feat">⭐ Featured</span>@endif
                    </div>
                    <div class="dh-card-body">
                        <div class="dh-badges">
                            @if ($post->locality)<span class="dh-b dh-b-loc">📍 {{ $post->locality->name }}</span>@endif
                            @if ($post->category)<span class="dh-b dh-b-cat">{{ $post->category->name }}</span>@endif
                            @if ($post->subcategory)<span class="dh-b dh-b-sub">{{ $post->subcategory->name }}</span>@endif
                        </div>
                        <a href="{{ $post->url }}" class="dh-card-title">{{ Str::limit($post->title, 60) }}</a>
                        <p class="dh-card-desc">{{ Str::limit(strip_tags($post->description), 90) }}</p>
                        <div class="dh-card-meta">
                            <button class="dh-meta-btn likeBtn {{ $liked ? 'liked':'' }}" data-id="{{ $post->id }}">
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
                                <i class="fas fa-clock"></i>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="dh-card-actions">
                            <a href="{{ $post->url }}" class="dh-btn dh-btn-primary">View Details</a>
                            <button class="dh-btn dh-btn-ghost shareBtn"
                                    data-id="{{ $post->id }}" data-url="{{ $post->url }}">
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
            @empty
                <div class="dh-empty">
                    <p style="font-size:2rem;opacity:.3;">🔍</p>
                    <p>No deals yet — check back soon!</p>
                </div>
            @endforelse
        </div>

        @if ($posts->hasMorePages())
            <div class="dh-show-more">
                <button class="dh-more-btn" id="loadMoreBtn" data-next="{{ $posts->nextPageUrl() }}">
                    Load More Deals
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>
</section>

{{-- ── Footer ──────────────────────────────────────────────── --}}
<footer class="dh-footer">
    <div class="dh-wrap">
        <div class="dh-footer-grid">
            <div>
                <img src="/frontend/img/dealshood.png" alt="DealsHood"
                     style="height:32px;filter:brightness(0) invert(1);opacity:.8;">
                <p class="dh-footer-brand">DealsHood</p>
                <p class="dh-footer-tag">Discover the best deals around you.</p>
                <div class="dh-footer-social">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/dealshood" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <p class="dh-footer-col-title">Company</p>
                <ul class="dh-footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Advertise</a></li>
                </ul>
            </div>
            <div>
                <p class="dh-footer-col-title">Support</p>
                <ul class="dh-footer-links">
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="dh-footer-bottom">
            <p>&copy; <span id="footerYear"></span> <a href="#">DealsHood</a>. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="/frontend/js/core/popper.min.js"></script>
<script src="/frontend/js/core/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
document.getElementById('footerYear').textContent = new Date().getFullYear();
document.getElementById('navToggle').addEventListener('click',function(){
    document.getElementById('navActions').classList.toggle('open');
});
const heroBg = document.getElementById('heroBg');
window.addEventListener('scroll',function(){
    if(heroBg) heroBg.style.transform='translateY('+(scrollY*.28)+'px)';
},{passive:true});

// Drag-to-scroll
function makeDraggable(el){
    let down=false,startX=0,sl=0,moved=false;
    el.addEventListener('mousedown',function(e){
        down=true; moved=false; startX=e.pageX-el.offsetLeft; sl=el.scrollLeft;
        el.classList.add('is-dragging');
    });
    el.addEventListener('mouseleave',function(){down=false;el.classList.remove('is-dragging');});
    el.addEventListener('mouseup',function(e){
        down=false; el.classList.remove('is-dragging');
        if(!moved) return;
        e.preventDefault();
        el.querySelectorAll('a').forEach(a=>a.style.pointerEvents='none');
        setTimeout(()=>el.querySelectorAll('a').forEach(a=>a.style.pointerEvents=''),100);
    });
    el.addEventListener('mousemove',function(e){
        if(!down) return; e.preventDefault();
        const walk=(e.pageX-el.offsetLeft-startX)*1.4;
        if(Math.abs(walk)>4) moved=true;
        el.scrollLeft=sl-walk;
    });
    let tx=0,ts=0;
    el.addEventListener('touchstart',function(e){tx=e.touches[0].pageX;ts=el.scrollLeft;},{passive:true});
    el.addEventListener('touchmove',function(e){el.scrollLeft=ts+(tx-e.touches[0].pageX);},{passive:true});
}
document.querySelectorAll('.dh-track').forEach(makeDraggable);
const catStrip=document.getElementById('catStrip');
if(catStrip) makeDraggable(catStrip);

$(document).on('click','.c-prev,.c-next',function(){
    const $t=$('#'+$(this).data('target'));
    const w=$t.find('.dh-cc').first().outerWidth(true);
    $t[0].scrollBy({left:$(this).hasClass('c-prev')?-w*3:w*3,behavior:'smooth'});
});
$(document).on('click','.likeBtn',function(){
    const btn=$(this),id=btn.data('id');
    $.post('/posts/'+id+'/toggle-like',{_token:'{{ csrf_token() }}'},function(res){
        $('#lc-'+id).text(res.likes);
        res.liked?btn.addClass('liked'):btn.removeClass('liked');
    });
});
$(document).on('click','.shareBtn',function(){
    const id=$(this).data('id'),url=$(this).data('url');
    navigator.share?navigator.share({url}):(navigator.clipboard.writeText(url),alert('Link copied!'));
    $.post('/posts/'+id+'/share',{_token:'{{ csrf_token() }}',platform:'web'});
});
$(document).on('click','#loadMoreBtn',function(){
    const btn=$(this),next=btn.data('next');
    if(!next) return;
    btn.text('Loading…').prop('disabled',true);
    $.get(next,function(res){
        if(res.html){
            $('#postsGrid').append(res.html);
            res.next_page?btn.data('next',res.next_page).text('Load More Deals').prop('disabled',false)
                         :btn.closest('.dh-show-more').remove();
        }
    }).fail(function(){btn.text('Load More Deals').prop('disabled',false);});
});
</script>
</body>
</html>