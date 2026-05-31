<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="/frontend/img/favicon.png">
    <title>DealsHood — Discover the Best Deals Near You</title>
    @php
        $ogImage = str_replace('http://', 'https://', url('/frontend/img/favicon.png'));
        $ogTitle = 'DealsHood — Discover the Best Deals Near You';
        $ogDesc  = 'Find great offers from your neighbourhood, every day.';
        $ogUrl   = url()->current();
    @endphp
    <meta name="description"  content="{{ $ogDesc }}">
    <link rel="canonical"     href="{{ $ogUrl }}">
    <meta property="og:type"  content="website">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:url"   content="{{ $ogUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet">

    <style>
    /* ═══════════════════════════════════════════
       TOKENS
    ═══════════════════════════════════════════ */
    :root {
        --ink:#0d0d0d; --ink-mid:#3a3a3a; --ink-muted:#6b6b6b;
        --surf:#faf9f7; --surf-2:#f2f1ef;
        --white:#ffffff; --accent:#0f3f7e;
        --r:14px; --rlg:20px;
        --sh-sm:0 2px 12px rgba(0,0,0,.07);
        --sh-md:0 6px 32px rgba(0,0,0,.10);
        --nav-h:64px;
    }
    *,*::before,*::after { box-sizing:border-box; }
    body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;
           background:var(--surf); color:var(--ink); margin:0; }
    .wrap { max-width:1180px; margin:0 auto; padding:0 24px; }

    /* ═══════════════════════════════════════════
       NAVBAR
    ═══════════════════════════════════════════ */
    .dh-nav { position:fixed; top:0; left:0; right:0; height:var(--nav-h);
              background:#fff; border-bottom:1px solid rgba(0,0,0,.07);
              z-index:1000; display:flex; align-items:center; }
    .dh-nav-inner { display:flex; align-items:center; justify-content:space-between;
                    width:100%; max-width:1180px; margin:0 auto; padding:0 24px; }
    .dh-nav-logo img { height:45px; display:block; }
    .dh-nav-actions { display:flex; align-items:center; gap:10px; }
    .dh-btn-nav { display:inline-flex; align-items:center; gap:6px; font-size:.75rem;
                  font-weight:500; letter-spacing:.04em; border:none; cursor:pointer;
                  border-radius:100px; padding:9px 18px; text-decoration:none; transition:transform .15s; }
    .dh-btn-nav:hover { transform:translateY(-1px); }
    .dh-btn-ig { background:#e1306c; color:#fff; }
    .dh-btn-wa { background:#25d366; color:#fff; }
    .dh-nav-toggle { display:none; background:none; border:none; cursor:pointer;
                     flex-direction:column; gap:5px; padding:6px; }
    .dh-nav-toggle span { display:block; width:22px; height:2px; background:var(--ink); border-radius:2px; }
    @media(max-width:640px){
        .dh-nav-toggle { display:flex; }
        .dh-nav-actions { display:none; position:absolute; top:var(--nav-h); left:0; right:0;
                          background:#fff; border-bottom:1px solid rgba(0,0,0,.08);
                          padding:16px 24px; flex-direction:column; align-items:flex-start; gap:10px; }
        .dh-nav-actions.open { display:flex; }
    }

    /* ═══════════════════════════════════════════
       HERO
    ═══════════════════════════════════════════ */
    .dh-hero { padding-top:var(--nav-h); position:relative; overflow:hidden;
               background:var(--ink); display:flex; flex-direction:column;
               align-items:center; justify-content:center; }
    .dh-hero-bg { position:absolute; inset:0;
                  background:url('/frontend/img/office-dark.jpg') center/cover no-repeat; opacity:.42; }
    .dh-hero-overlay { position:absolute; inset:0;
                       background:linear-gradient(160deg,rgba(13,13,13,.78) 0%,rgba(13,13,13,.32) 55%,rgba(15,63,126,.2) 100%); }
    .dh-hero-wave { position:absolute; bottom:-1px; left:0; right:0; z-index:3; line-height:0; pointer-events:none; }
    .dh-hero-wave svg { display:block; width:100%; }

    /* Hero text */
    .dh-hero-text { position:relative; z-index:4; text-align:center;
                    max-width:600px; padding:40px 24px 0; animation:fadeUp .55s .1s both; }
    .dh-hero-title { font-size:clamp(2rem,4.5vw,3.2rem); font-weight:800; color:#fff;
                     line-height:1.14; letter-spacing:-.025em; margin:0 0 10px; }
    .dh-hero-sub { font-size:.95rem; color:rgba(255,255,255,.55); font-weight:300; margin:0; }

    /* Glassmorphism tile grid */
    .dh-hero-panel { position:relative; z-index:4; width:100%; max-width:1100px;
                     padding:24px 24px 60px; animation:fadeUp .55s .25s both; }
    .dh-glass-grid { display:flex; flex-wrap:wrap; justify-content:center; gap:10px; }
    @media(max-width:480px){ .dh-glass-grid { gap:8px; } }

    .dh-gtile { width:84px; display:flex; flex-direction:column; align-items:center;
                justify-content:center; gap:7px; padding:13px 6px 11px;
                border-radius:14px; text-align:center; color:#fff;
                font-size:.68rem; font-weight:600; line-height:1.25;
                background:rgba(255,255,255,.1); backdrop-filter:blur(10px);
                -webkit-backdrop-filter:blur(10px); border:1.5px solid rgba(255,255,255,.16);
                transition:transform .2s,background .2s,box-shadow .2s,border-color .2s;
                cursor:pointer; user-select:none; text-decoration:none; }
    .dh-gtile:hover { transform:translateY(-4px) scale(1.04); background:rgba(255,255,255,.2);
                      border-color:rgba(255,255,255,.4); box-shadow:0 10px 32px rgba(0,0,0,.28); color:#fff; }
    .dh-gtile .gtile-icon { width:36px; height:36px; border-radius:10px;
                             display:flex; align-items:center; justify-content:center;
                             font-size:.85rem; background:rgba(255,255,255,.16); transition:transform .2s; }
    .dh-gtile:hover .gtile-icon { transform:scale(1.1); }
    .dh-gtile .gtile-name { word-break:break-word; width:100%; }
    .dh-gtile .gtile-count { font-size:.58rem; font-weight:400; opacity:.6; }
    .dh-gtile.gtile-all { background:rgba(255,255,255,.95); color:var(--ink);
                           border-color:transparent; box-shadow:0 4px 16px rgba(0,0,0,.18); }
    .dh-gtile.gtile-all:hover { background:#fff; color:var(--accent); }
    .dh-gtile.gtile-all .gtile-icon { background:var(--surf-2); color:var(--accent); }

    /* ═══════════════════════════════════════════
       LOCALITY STRIP  (sticky, below hero)
    ═══════════════════════════════════════════ */
    .loc-strip {
        position:sticky;
        top:var(--nav-h);
        z-index:900;
        background:var(--white);
        border-bottom:1px solid rgba(0,0,0,.08);
        box-shadow:0 2px 12px rgba(0,0,0,.06);
        padding:0;
        transition:box-shadow .2s;
    }
    .loc-strip-inner {
        display:flex; align-items:stretch;
        height:48px; gap:0;
    }

    /* LEFT — "Near me" / icon label */
    .loc-label {
        display:flex; align-items:center; gap:7px;
        padding:0 16px 0 20px;
        font-size:.68rem; font-weight:700; letter-spacing:.1em;
        text-transform:uppercase; color:var(--ink-muted);
        border-right:1px solid rgba(0,0,0,.07);
        white-space:nowrap; flex-shrink:0;
        background:var(--surf);
    }
    .loc-label i { color:var(--accent); font-size:.75rem; }

    /* CHIPS SCROLL AREA */
    .loc-chips {
        display:flex; align-items:center; gap:7px;
        flex:1; overflow-x:auto; padding:0 16px;
        -ms-overflow-style:none; scrollbar-width:none;
        cursor:grab;
    }
    .loc-chips::-webkit-scrollbar { display:none; }
    .loc-chips.dragging { cursor:grabbing; }

    .loc-chip {
        display:inline-flex; align-items:center; gap:5px;
        padding:5px 14px; border-radius:100px;
        font-size:.73rem; font-weight:600; white-space:nowrap;
        color:var(--ink-muted); background:var(--surf);
        border:1.5px solid rgba(0,0,0,.09);
        cursor:pointer; transition:all .14s; user-select:none; flex-shrink:0;
    }
    .loc-chip:hover { border-color:var(--accent); color:var(--accent);
                      background:rgba(15,63,126,.05); transform:translateY(-1px); }
    .loc-chip.active {
        background:var(--ink); color:#fff;
        border-color:var(--ink); box-shadow:0 3px 10px rgba(0,0,0,.18);
    }
    .loc-chip .dot { width:7px; height:7px; border-radius:50%;
                     background:currentColor; flex-shrink:0; opacity:.7; }

    /* RIGHT — active locality clear + count badge */
    .loc-active-tag {
        display:none; align-items:center; gap:8px;
        padding:0 16px; border-left:1px solid rgba(0,0,0,.07);
        font-size:.73rem; font-weight:500; color:var(--accent);
        white-space:nowrap; flex-shrink:0; background:rgba(15,63,126,.04);
    }
    .loc-active-tag.show { display:flex; }
    .loc-clear-btn {
        width:22px; height:22px; border-radius:50%; border:1.5px solid rgba(15,63,126,.25);
        background:transparent; color:var(--accent); cursor:pointer;
        display:flex; align-items:center; justify-content:center;
        font-size:.65rem; transition:all .14s;
    }
    .loc-clear-btn:hover { background:var(--accent); color:#fff; }

    /* ═══════════════════════════════════════════
       SECTIONS
    ═══════════════════════════════════════════ */
    .dh-sec-head { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:24px; }
    .dh-eyebrow { display:inline-flex; align-items:center; gap:8px; font-size:.67rem;
                  font-weight:500; letter-spacing:.14em; text-transform:uppercase;
                  color:var(--accent); margin-bottom:5px; }
    .dh-eyebrow::before { content:''; display:inline-block; width:18px; height:2px;
                           background:var(--accent); border-radius:2px; }
    .dh-sec-title { font-size:1.5rem; font-weight:700; color:var(--ink); margin:0; }
    .dh-view-all { font-size:.77rem; font-weight:500; color:var(--ink-muted); text-decoration:none;
                   display:inline-flex; align-items:center; gap:4px; transition:color .15s; }
    .dh-view-all:hover { color:var(--accent); }

    /* ── Carousels ── */
    .dh-carousel-sec { padding:32px 0 12px; background:var(--surf); }
    .dh-carousel-block { margin-bottom:40px; }
    .dh-carousel-block:last-child { margin-bottom:0; }
    .dh-carousel-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
    .dh-carousel-title { font-size:1.12rem; font-weight:700; color:var(--ink); margin:0;
                          display:flex; align-items:center; gap:10px; }
    .cat-badge { font-size:.61rem; font-weight:600; letter-spacing:.07em; text-transform:uppercase;
                 padding:3px 10px; border-radius:100px; }
    .dh-carousel-controls { display:flex; align-items:center; gap:8px; }
    .dh-c-btn { width:32px; height:32px; border-radius:50%; border:1.5px solid rgba(0,0,0,.12);
                background:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center;
                font-size:.8rem; color:var(--ink-muted); transition:all .15s; }
    .dh-c-btn:hover { background:var(--ink); color:#fff; border-color:var(--ink); }
    .dh-track-outer { position:relative; overflow:hidden; }
    .dh-track { display:flex; gap:20px; overflow-x:auto; padding-bottom:16px;
                scroll-behavior:smooth; scroll-snap-type:x mandatory;
                -ms-overflow-style:none; scrollbar-width:none; cursor:grab; user-select:none; }
    .dh-track.is-dragging { cursor:grabbing; scroll-behavior:auto; }
    .dh-track::-webkit-scrollbar { display:none; }
    .dh-track-outer::after { content:''; position:absolute; right:0; top:0; bottom:16px; width:64px;
                              pointer-events:none; z-index:1;
                              background:linear-gradient(to left,var(--surf),transparent); }
    .dh-track .dh-card { flex:0 0 350px; scroll-snap-align:start; }
    .dh-track.is-dragging .dh-card { pointer-events:none; }
    @media(max-width:560px){ .dh-track .dh-card { flex:0 0 260px; } }

    /* Section spinner */
    .sec-spinner { text-align:center; padding:40px 0; }
    .sec-spinner span { display:inline-block; width:9px; height:9px; border-radius:50%;
                        background:var(--accent); margin:0 3px;
                        animation:dotPulse 1.2s infinite both; }
    .sec-spinner span:nth-child(2){ animation-delay:.2s; }
    .sec-spinner span:nth-child(3){ animation-delay:.4s; }

    /* ── Post cards ── */
    .dh-latest-sec { padding:0 0 80px; background:var(--surf); }
    .dh-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
    @media(max-width:900px){ .dh-grid { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:560px){ .dh-grid { grid-template-columns:1fr; } }
    .dh-card { background:#fff; border-radius:var(--rlg); overflow:hidden; box-shadow:var(--sh-sm);
               border:1px solid rgba(0,0,0,.05); display:flex; flex-direction:column;
               transition:transform .22s,box-shadow .22s; }
    .dh-card:hover { transform:translateY(-5px); box-shadow:var(--sh-md); }
    .dh-card-media { position:relative; overflow:hidden; flex-shrink:0; }
    .dh-card-media img,.dh-card-media video { width:100%; height:200px; object-fit:cover;
                                              display:block; transition:transform .35s; }
    .dh-card:hover .dh-card-media img { transform:scale(1.04); }
    .dh-card-media .ratio { height:200px; }
    .badge-feat { position:absolute; top:10px; right:10px; background:#f59e0b; color:#fff;
                  font-size:.6rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase;
                  padding:4px 10px; border-radius:100px; }
    .dh-card-body { padding:16px 18px 18px; display:flex; flex-direction:column; flex:1; }
    .dh-badges { display:flex; flex-wrap:wrap; gap:5px; margin-bottom:10px; }
    .dh-b { font-size:.6rem; font-weight:500; letter-spacing:.07em; text-transform:uppercase;
             padding:3px 9px; border-radius:100px; }
    .dh-b-loc { background:var(--surf-2); color:var(--ink-muted); }
    .dh-b-cat { background:rgba(15,63,126,.08); color:var(--accent); }
    .dh-b-sub { background:rgba(59,130,246,.08); color:#1d4ed8; }
    .dh-card-title { font-size:.98rem; font-weight:700; color:var(--ink); line-height:1.35;
                     margin:0 0 7px; text-decoration:none; display:block; transition:color .15s; }
    .dh-card-title:hover { color:var(--accent); }
    .dh-card-desc { font-size:.8rem; line-height:1.6; color:var(--ink-muted);
                    font-weight:300; flex:1; margin-bottom:12px; }
    .dh-card-meta { display:flex; align-items:center; gap:8px; flex-wrap:wrap;
                    padding-top:10px; border-top:1px solid rgba(0,0,0,.06); margin-bottom:12px; }
    .dh-meta-btn,.dh-meta-box { display:flex; align-items:center; gap:6px; padding:4px 9px;
                                 border-radius:14px; background:#fff; border:1px solid #edf0f5;
                                 transition:all .2s; box-shadow:0 2px 8px rgba(0,0,0,.04); }
    .dh-meta-btn { cursor:pointer; outline:none; }
    .dh-meta-btn:hover,.dh-meta-box:hover { transform:translateY(-2px); box-shadow:0 4px 14px rgba(0,0,0,.08); }
    .dh-meta-icon { font-size:11px; color:#6b7280; }
    .dh-meta-count { font-size:12px; font-weight:600; color:#1f2937; }
    .dh-meta-time { margin-left:auto; display:flex; align-items:center; gap:5px; font-size:11px; color:#6b7280; }
    .likeBtn.liked { background:rgba(255,77,109,.08); border-color:rgba(255,77,109,.18); }
    .likeBtn.liked .dh-meta-icon { color:#ff4d6d; }
    .likeBtn.liked .dh-meta-count { color:#ff4d6d; }
    .dh-card-actions { display:flex; gap:8px; }
    .dh-btn { display:inline-flex; align-items:center; justify-content:center; gap:6px;
               font-size:.74rem; font-weight:500; border-radius:100px; padding:8px 16px;
               text-decoration:none; border:1.5px solid; cursor:pointer; transition:all .15s; flex:1; }
    .dh-btn-primary { background:var(--ink); color:#fff; border-color:var(--ink); }
    .dh-btn-primary:hover { background:var(--accent); border-color:var(--accent); color:#fff; }
    .dh-btn-ghost { background:transparent; color:var(--ink-muted);
                    border-color:rgba(0,0,0,.12); flex:0 0 auto; padding:8px 12px; }
    .dh-btn-ghost:hover { background:var(--surf-2); color:var(--ink); }
    .dh-empty { grid-column:1/-1; text-align:center; padding:64px 24px; color:var(--ink-muted); }
    .dh-show-more { text-align:center; margin-top:36px; }
    .dh-more-btn { display:inline-flex; align-items:center; gap:8px; font-size:.79rem;
                   font-weight:500; color:var(--ink); background:#fff; cursor:pointer;
                   border:1.5px solid rgba(0,0,0,.14); border-radius:100px; padding:12px 28px;
                   transition:all .18s; }
    .dh-more-btn:hover { background:var(--ink); color:#fff; transform:translateY(-2px); }

    /* ── Footer ── */
    .dh-footer { background:var(--ink); color:rgba(255,255,255,.7); padding:60px 0 0; font-size:.84rem; }
    .dh-footer-grid { display:grid; grid-template-columns:1.6fr 1fr 1fr; gap:48px; padding-bottom:48px; }
    @media(max-width:720px){ .dh-footer-grid { grid-template-columns:1fr 1fr; } }
    @media(max-width:440px){ .dh-footer-grid { grid-template-columns:1fr; } }
    .dh-footer-brand { font-size:1.1rem; color:#fff; margin:12px 0 5px; }
    .dh-footer-tag { font-size:.77rem; color:rgba(255,255,255,.38); margin:0; }
    .dh-footer-social { display:flex; gap:8px; margin-top:18px; }
    .dh-footer-social a { width:34px; height:34px; border-radius:50%; border:1px solid rgba(255,255,255,.15);
                           display:flex; align-items:center; justify-content:center;
                           color:rgba(255,255,255,.6); font-size:.88rem; text-decoration:none; transition:.15s; }
    .dh-footer-social a:hover { border-color:rgba(255,255,255,.5); color:#fff; }
    .dh-footer-col-title { font-size:.64rem; font-weight:600; letter-spacing:.14em; text-transform:uppercase;
                            color:var(--accent); margin-bottom:14px; }
    .dh-footer-links { list-style:none; padding:0; margin:0; }
    .dh-footer-links li { margin-bottom:10px; }
    .dh-footer-links a { color:rgba(255,255,255,.52); text-decoration:none; transition:color .15s; }
    .dh-footer-links a:hover { color:#fff; }
    .dh-footer-bottom { border-top:1px solid rgba(255,255,255,.08); text-align:center;
                         padding:20px 0; font-size:.74rem; color:rgba(255,255,255,.3); }
    .dh-footer-bottom a { color:rgba(255,255,255,.48); text-decoration:none; }

    @keyframes fadeUp { from{opacity:0;transform:translateY(18px);} to{opacity:1;transform:translateY(0);} }
    @keyframes dotPulse { 0%,80%,100%{opacity:.2;transform:scale(.75);} 40%{opacity:1;transform:scale(1);} }
    </style>
</head>
<body>

{{-- ═══ NAVBAR ═══ --}}
<nav class="dh-nav">
    <div class="dh-nav-inner">
        <a href="{{ route('home') }}">
            <img src="/frontend/img/dealshood.png" alt="DealsHood" style="height:45px;">
        </a>
        <button class="dh-nav-toggle" id="navToggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <div class="dh-nav-actions" id="navActions">
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

{{-- ═══ HERO ═══ --}}
<header class="dh-hero">
    <div class="dh-hero-bg" id="heroBg"></div>
    <div class="dh-hero-overlay"></div>

    <div class="dh-hero-text">
        <h1 class="dh-hero-title">Discover the best deals near you.</h1>
        <p class="dh-hero-sub">Browse by category or pick your area below</p>
    </div>

    {{-- Category tiles → direct link to listing page --}}
    <div class="dh-hero-panel">
        <div class="dh-glass-grid" id="catGrid">

            <a href="{{ route('posts.listing') }}"
               class="dh-gtile gtile-all"
               data-base="{{ route('posts.listing') }}">
                <span class="gtile-icon"><i class="fas fa-th"></i></span>
                <span class="gtile-name">All Deals</span>
            </a>

            @foreach ($categories as $i => $cat)
                @php $p = $palette[$i % count($palette)]; @endphp
                <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}"
                   class="dh-gtile"
                   data-base="{{ route('posts.listing', ['category_id' => $cat->slug]) }}">
                    <span class="gtile-icon" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                        <i class="fas {{ $p['icon'] }}"></i>
                    </span>
                    <span class="gtile-name">{{ $cat->name }}</span>
                </a>
            @endforeach

        </div>
    </div>

    <div class="dh-hero-wave">
        <svg viewBox="0 0 1440 56" fill="none">
            <path d="M0 56H1440V28C1200 56 960 8 720 8C480 8 240 56 0 28V56Z" fill="#faf9f7"/>
        </svg>
    </div>
</header>

{{-- ═══════════════════════════════════════════════════════════
     LOCALITY STRIP  — sticky, right below hero
     Chips scroll horizontally. Active locality stored in JS,
     appended to category tile hrefs + used in AJAX reload.
═══════════════════════════════════════════════════════════ --}}
<div class="loc-strip" id="locStrip">
    <div class="wrap">
        <div class="loc-strip-inner">

            {{-- Label --}}
            <div class="loc-label">
                <i class="fas fa-map-marker-alt"></i>
                <span class="d-none d-sm-inline">Near</span>
            </div>

            {{-- Scrollable locality chips --}}
            <div class="loc-chips" id="locChips">
                <span class="loc-chip active" data-slug="" data-name="All Areas">
                    <i class="fas fa-globe" style="font-size:.6rem;opacity:.7;"></i>
                    All Areas
                </span>
                @foreach ($localities as $loc)
                    <span class="loc-chip" data-slug="{{ $loc->slug }}" data-name="{{ $loc->name }}">
                        <span class="dot"></span>
                        {{ $loc->name }}
                    </span>
                @endforeach
            </div>

            {{-- Active area tag + clear button (shown when a locality is selected) --}}
            <div class="loc-active-tag" id="locActiveTag">
                <i class="fas fa-map-marker-alt" style="font-size:.62rem;"></i>
                <span id="locActiveName"></span>
                <button class="loc-clear-btn" id="locClearBtn" title="Clear area filter">
                    <i class="bi bi-x"></i>
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ═══ CAROUSELS ═══ --}}
<section class="dh-carousel-sec">
    <div class="wrap">

        <div class="dh-sec-head">
            <div>
                <div class="dh-eyebrow">Popular</div>
                <h2 class="dh-sec-title" id="carouselHeading">Top Deals by Category</h2>
            </div>
            <a href="{{ route('posts.listing') }}" class="dh-view-all" id="carouselViewAll">
                See all <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div id="carouselContent">
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
                                <span class="cat-badge" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">Popular</span>
                            </h3>
                            <div class="dh-carousel-controls">
                                <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}"
                                   class="dh-view-all me-1">
                                    See all {{ number_format($cat->posts_count) }}
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                <button class="dh-c-btn c-prev" data-target="cr-{{ $cat->id }}">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="dh-c-btn c-next" data-target="cr-{{ $cat->id }}">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="dh-track-outer">
                            <div class="dh-track" id="cr-{{ $cat->id }}">
                                @foreach ($cat->posts as $post)
                                    @include('frontend.post-single-card', ['post' => $post])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

    </div>
</section>

{{-- ═══ LATEST DEALS ═══ --}}
<section class="dh-latest-sec">
    <div class="wrap">

        <div class="dh-sec-head">
            <div>
                <div class="dh-eyebrow">Just in</div>
                <h2 class="dh-sec-title" id="latestHeading">Latest Deals</h2>
            </div>
            <a href="{{ route('posts.listing') }}" class="dh-view-all" id="latestViewAll">
                View all <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="dh-grid" id="postsGrid">
            @forelse ($posts as $post)
                @include('frontend.post-single-card', ['post' => $post])
            @empty
                <div class="dh-empty">
                    <p style="font-size:2rem;opacity:.3;">🔍</p>
                    <p>No deals yet — check back soon!</p>
                </div>
            @endforelse
        </div>

        @if ($posts->hasMorePages())
            <div class="dh-show-more" id="showMoreWrap">
                <button class="dh-more-btn" id="loadMoreBtn"
                        data-next="{{ $posts->nextPageUrl() }}">
                    Load More Deals
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        @endif

    </div>
</section>

{{-- ═══ FOOTER ═══ --}}
<footer class="dh-footer">
    <div class="wrap">
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

{{-- ═══ SCRIPTS ═══ --}}
<script src="/frontend/js/core/popper.min.js"></script>
<script src="/frontend/js/core/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
/* ─── constants ──────────────────────────────────── */
const CSRF        = '{{ csrf_token() }}';
const LISTING_URL = '{{ route("posts.listing") }}';
const HOME_URL    = '{{ route("home") }}';

document.getElementById('footerYear').textContent = new Date().getFullYear();

/* ─── nav toggle ─────────────────────────────────── */
document.getElementById('navToggle').addEventListener('click', function () {
    document.getElementById('navActions').classList.toggle('open');
});

/* ─── hero parallax ──────────────────────────────── */
const heroBg = document.getElementById('heroBg');
window.addEventListener('scroll', function () {
    if (heroBg) heroBg.style.transform = 'translateY(' + (scrollY * .25) + 'px)';
}, { passive: true });

/* ─── drag-to-scroll helper ──────────────────────── */
function makeDraggable(el) {
    if (!el) return;
    let isDown = false, startX = 0, sl = 0, wasDragged = false;
    el.addEventListener('mousedown', e => {
        isDown = true; wasDragged = false;
        startX = e.pageX - el.offsetLeft; sl = el.scrollLeft;
        el.style.cursor = 'grabbing';
    });
    el.addEventListener('mouseleave', () => { isDown = false; el.style.cursor = ''; el.classList.remove('is-dragging','dragging'); });
    el.addEventListener('mouseup',    () => { isDown = false; el.style.cursor = ''; el.classList.remove('is-dragging','dragging'); setTimeout(() => wasDragged = false, 50); });
    el.addEventListener('mousemove', e => {
        if (!isDown) return; e.preventDefault();
        const walk = (e.pageX - el.offsetLeft - startX) * 1.4;
        if (Math.abs(walk) > 6) { wasDragged = true; el.classList.add('is-dragging','dragging'); }
        el.scrollLeft = sl - walk;
    });
    el.addEventListener('click', e => { if (wasDragged) { e.preventDefault(); e.stopPropagation(); } }, true);
    let tx = 0, ts = 0;
    el.addEventListener('touchstart', e => { tx = e.touches[0].pageX; ts = el.scrollLeft; }, { passive: true });
    el.addEventListener('touchmove',  e => { el.scrollLeft = ts + (tx - e.touches[0].pageX); },  { passive: true });
}

/* Init drag on all carousels */
function initDrag() {
    document.querySelectorAll('.dh-track:not([data-drag])').forEach(el => {
        el.setAttribute('data-drag','1');
        makeDraggable(el);
    });
}
initDrag();

/* Drag on locality chips */
makeDraggable(document.getElementById('locChips'));

/* ─── carousel prev / next buttons ──────────────── */
$(document).on('click', '.c-prev,.c-next', function () {
    const $t = $('#' + $(this).data('target'));
    if (!$t.length) return;
    const w = $t.find('.dh-card').first().outerWidth(true) || 300;
    $t[0].scrollBy({ left: $(this).hasClass('c-prev') ? -w * 2 : w * 2, behavior: 'smooth' });
});

/* ═══════════════════════════════════════════════════
   LOCALITY SELECTION
   ───────────────────────────────────────────────────
   • Clicking a chip stores the active locality slug
   • "View all" / "See all" links get locality appended
   • Category tile hrefs get locality appended
   • AJAX reloads carousels + latest deals for that area
   • Clear button resets everything
═══════════════════════════════════════════════════ */
let activeLocSlug = '';
let activeLocName = '';

/* Build a listing URL with optional locality param */
function listingUrl(base, extra) {
    let url = new URL(base, window.location.origin);
    if (activeLocSlug) url.searchParams.set('locality_id', activeLocSlug);
    if (extra) Object.entries(extra).forEach(([k,v]) => url.searchParams.set(k, v));
    return url.pathname + (url.search || '');
}

/* Update all links that point to the listing page */
function refreshLinks() {
    /* Category tile hrefs */
    document.querySelectorAll('#catGrid .dh-gtile[data-base]').forEach(el => {
        el.href = listingUrl(el.dataset.base);
    });
    /* "View all" / "See all" links in carousels */
    document.querySelectorAll('#carouselContent a.dh-view-all').forEach(el => {
        const base = el.href.split('?')[0];
        const catMatch = el.href.match(/category_id=([^&]+)/);
        const extra = catMatch ? { category_id: catMatch[1] } : {};
        el.href = listingUrl(base, extra);
    });
    /* Section "View all" links */
    document.getElementById('carouselViewAll').href = listingUrl(LISTING_URL);
    document.getElementById('latestViewAll').href   = listingUrl(LISTING_URL);
}

/* Update active-area UI */
function setLocUI(slug, name) {
    activeLocSlug = slug;
    activeLocName = name;

    $('.loc-chip').removeClass('active');
    $('.loc-chip[data-slug="' + slug + '"]').addClass('active');

    if (slug) {
        $('#locActiveName').text(name);
        $('#locActiveTag').addClass('show');
    } else {
        $('#locActiveTag').removeClass('show');
    }

    refreshLinks();
}

/* AJAX load carousels + latest posts for active locality */
function reloadContent() {
    const spinner = '<div class="sec-spinner"><span></span><span></span><span></span></div>';
    $('#carouselContent').html(spinner);
    $('#postsGrid').html('<div style="grid-column:1/-1;">' + spinner + '</div>');
    $('#showMoreWrap').hide();

    const params = {};
    if (activeLocSlug) params.filter_locality = activeLocSlug;

    $.ajax({
        url: HOME_URL, type: 'GET', data: params,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            $('#carouselContent').html(
                res.carousel_html ||
                '<p style="text-align:center;color:var(--ink-muted);padding:32px 0;font-size:.85rem;">No popular deals in this area yet.</p>'
            );
            $('#postsGrid').html(res.posts_html || '');

            if (res.next_page) {
                const btn = '<div class="dh-show-more" id="showMoreWrap"><button class="dh-more-btn" id="loadMoreBtn" data-next="' + res.next_page + '">Load More Deals <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button></div>';
                if ($('#showMoreWrap').length) {
                    $('#showMoreWrap').show().find('#loadMoreBtn').data('next', res.next_page);
                } else {
                    $('#postsGrid').after(btn);
                }
            } else {
                $('#showMoreWrap').hide();
            }

            /* update carousels heading if locality is set */
            $('#carouselHeading').text(activeLocName ? activeLocName + ' — Popular Deals' : 'Top Deals by Category');
            $('#latestHeading').text(activeLocName ? activeLocName + ' — Latest Deals' : 'Latest Deals');

            /* re-run refreshLinks so new carousel "See all" links also get locality */
            refreshLinks();
            initDrag();
        },
        error: function () {
            $('#carouselContent').html('');
        }
    });
}

/* Chip click */
$(document).on('click', '.loc-chip', function () {
    const slug = $(this).data('slug');
    const name = $(this).data('name');
    if (slug === activeLocSlug) return;  // no change
    setLocUI(slug, name);
    reloadContent();
});

/* Clear button */
$('#locClearBtn').on('click', function () {
    setLocUI('', 'All Areas');
    reloadContent();
});

/* ─── like ───────────────────────────────────────── */
$(document).on('click', '.likeBtn', function () {
    const btn = $(this), id = btn.data('id');
    $.post('/posts/' + id + '/toggle-like', { _token: CSRF }, function (res) {
        $('#lc-' + id).text(res.likes);
        res.liked ? btn.addClass('liked') : btn.removeClass('liked');
    });
});

/* ─── share ──────────────────────────────────────── */
$(document).on('click', '.shareBtn', function () {
    const id = $(this).data('id'), url = $(this).data('url');
    navigator.share ? navigator.share({ url }) : (navigator.clipboard.writeText(url), alert('Link copied!'));
    $.post('/posts/' + id + '/share', { _token: CSRF, platform: 'web' });
});

/* ─── load more ──────────────────────────────────── */
$(document).on('click', '#loadMoreBtn', function () {
    const btn = $(this), next = btn.data('next');
    if (!next) return;
    btn.text('Loading…').prop('disabled', true);
    $.get(next, function (res) {
        if (res.html) {
            $('#postsGrid').append(res.html);
            res.next_page
                ? btn.data('next', res.next_page).text('Load More Deals').prop('disabled', false)
                : btn.closest('.dh-show-more').remove();
        }
    }).fail(function () { btn.text('Load More Deals').prop('disabled', false); });
});
</script>
</body>
</html>