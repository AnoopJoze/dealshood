<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/frontend/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/frontend/img/favicon.png">
    <title>Browse All Deals — DealsHood</title>
    @php
        $ogImage = str_replace('http://', 'https://', url('/frontend/img/favicon.png'));
        $ogTitle = 'Browse All Deals — DealsHood';
        $ogDesc  = 'Find the best deals near you. Filter by locality, category and more.';
        $ogUrl   = url()->current();
    @endphp
    <meta name="description" content="{{ $ogDesc }}">
    <link rel="canonical" href="{{ $ogUrl }}">
    <meta property="og:title"       content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:image"       content="{{ $ogImage }}">
    <meta property="og:url"         content="{{ $ogUrl }}">
    <meta name="twitter:card"       content="summary_large_image">

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

    /* ═══════════════════════════════════════════════════
       COMPACT HERO
    ═══════════════════════════════════════════════════ */
    .dh-hero {
        padding-top:var(--nav-h); position:relative; overflow:hidden;
        background:var(--ink); min-height:220px;
        display:flex; align-items:center;
    }
    .dh-hero-bg {
        position:absolute; inset:0;
        background:url('/frontend/img/office-dark.jpg') center/cover no-repeat;
        opacity:.35;
    }
    .dh-hero-overlay {
        position:absolute; inset:0;
        background:linear-gradient(160deg,rgba(13,13,13,.82) 0%,rgba(13,13,13,.4) 60%,rgba(15,63,126,.2) 100%);
    }
    .dh-hero-body {
        position:relative; z-index:2; width:100%; max-width:1180px;
        margin:0 auto; padding:28px 24px 40px;
        animation:fadeUp .45s .05s both;
    }
    .dh-hero-eyebrow {
        display:inline-flex; align-items:center; gap:8px; font-size:.65rem;
        font-weight:500; letter-spacing:.16em; text-transform:uppercase;
        color:rgba(255,255,255,.45); margin-bottom:8px;
    }
    .dh-hero-eyebrow::before {
        content:''; display:inline-block; width:16px; height:1.5px;
        background:rgba(255,255,255,.35); border-radius:2px;
    }
    .dh-hero-title {
        font-size:clamp(1.7rem,3.5vw,2.4rem); font-weight:800; color:#fff;
        line-height:1.15; letter-spacing:-.02em; margin:0 0 6px;
    }
    .dh-hero-sub { font-size:.88rem; color:rgba(255,255,255,.52); font-weight:300; margin:0; }
    .dh-hero-wave { position:absolute; bottom:-1px; left:0; right:0; z-index:3; line-height:0; }
    .dh-hero-wave svg { display:block; width:100%; }

    /* ═══════════════════════════════════════════════════
       FILTER CARD  (floats over wave)
    ═══════════════════════════════════════════════════ */
    .dh-filter-sec { background:var(--surface); padding:0 0 8px; }
    .dh-filter-wrap {
        max-width:1180px; margin:-38px auto 0; padding:0 24px;
        position:relative; z-index:10; animation:fadeUp .5s .3s both;
    }
    .dh-filter-card {
        background:#fff; border-radius:var(--rlg); box-shadow:var(--sh-lg);
        border:1px solid rgba(0,0,0,.05); padding:24px 26px 20px;
    }
    .dh-filter-grid {
        display:grid; grid-template-columns:repeat(4,1fr) auto; gap:14px; align-items:end;
    }
    @media(max-width:860px) {
        .dh-filter-grid { grid-template-columns:1fr 1fr; }
        .dh-filter-grid .dh-filter-submit { grid-column:1/-1; }
    }
    @media(max-width:480px) { .dh-filter-grid { grid-template-columns:1fr; } }

    .dh-field-group { display:flex; flex-direction:column; gap:5px; }
    .dh-field-label {
        font-size:.66rem; font-weight:500; letter-spacing:.1em;
        text-transform:uppercase; color:var(--ink-muted);
    }
    .dh-field {
        font-size:.87rem; color:var(--ink); background:var(--surface-2);
        border:1.5px solid rgba(0,0,0,.08); border-radius:var(--r);
        padding:10px 14px; outline:none; width:100%; appearance:none;
        transition:border-color .15s, box-shadow .15s, background .15s;
    }
    .dh-field:focus { border-color:var(--accent); background:#fff;
                      box-shadow:0 0 0 3px rgba(15,63,126,.08); }
    .dh-search-btn {
        font-size:.8rem; font-weight:600; letter-spacing:.04em;
        background:var(--ink); color:#fff; border:none;
        border-radius:var(--r); padding:11px 26px; cursor:pointer;
        display:inline-flex; align-items:center; gap:7px; height:43px;
        white-space:nowrap; transition:background .15s,transform .15s,box-shadow .15s;
    }
    .dh-search-btn:hover { background:var(--accent); transform:translateY(-1px);
                           box-shadow:0 4px 16px rgba(15,63,126,.3); }

    /* ═══════════════════════════════════════════════════
       CATEGORY QUICK-FILTER PILLS
    ═══════════════════════════════════════════════════ */
    .dh-cat-strip-sec { background:var(--surface); padding:20px 0 4px; }
    .dh-cat-strip {
        display:flex; gap:8px; overflow-x:auto; padding-bottom:4px;
        -ms-overflow-style:none; scrollbar-width:none; cursor:grab;
    }
    .dh-cat-strip::-webkit-scrollbar { display:none; }
    .dh-cat-strip:active { cursor:grabbing; }

    .dh-cat-chip {
        flex:0 0 auto; display:inline-flex; align-items:center; gap:7px;
        padding:7px 16px; border-radius:100px; text-decoration:none; white-space:nowrap;
        font-size:.74rem; font-weight:600; color:var(--ink-muted);
        background:#fff; border:1.5px solid rgba(0,0,0,.1);
        transition:all .15s; user-select:none;
    }
    .dh-cat-chip:hover { border-color:var(--accent); color:var(--accent);
                         background:rgba(15,63,126,.05); transform:translateY(-1px); }
    .dh-cat-chip.active {
        background:var(--ink); color:#fff; border-color:var(--ink);
        box-shadow:0 3px 12px rgba(0,0,0,.2);
    }
    .dh-cat-chip .chip-icon {
        width:22px; height:22px; border-radius:6px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center; font-size:.65rem;
    }
    .dh-cat-chip.active .chip-icon { background:rgba(255,255,255,.18); color:#fff; }

    /* ═══════════════════════════════════════════════════
       TOOLBAR  (result count + sort)
    ═══════════════════════════════════════════════════ */
    .dh-toolbar {
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap:wrap; gap:10px; margin-bottom:24px;
    }
    .dh-result-info { font-size:.82rem; color:var(--ink-muted); font-weight:300; }
    .dh-result-info strong { color:var(--ink); font-weight:700; }
    .dh-sort-pills { display:flex; gap:6px; }
    .dh-sort-pill {
        font-size:.72rem; font-weight:500; padding:6px 14px; border-radius:100px;
        text-decoration:none; border:1.5px solid rgba(0,0,0,.1); transition:all .15s;
        color:var(--ink-muted); background:#fff;
    }
    .dh-sort-pill:hover { border-color:var(--accent); color:var(--accent); }
    .dh-sort-pill.active {
        background:var(--ink); color:#fff; border-color:var(--ink);
    }

    /* ═══════════════════════════════════════════════════
       POSTS GRID + CARDS
    ═══════════════════════════════════════════════════ */
    .dh-posts-sec { padding:0 0 88px; background:var(--surface); }
    .dh-wrap { max-width:1180px; margin:0 auto; padding:0 24px; }

    .dh-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
    @media(max-width:900px) { .dh-grid { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:560px) { .dh-grid { grid-template-columns:1fr; } }

    .dh-card {
        background:#fff; border-radius:var(--rlg); overflow:hidden;
        box-shadow:var(--sh-sm); border:1px solid rgba(0,0,0,.05);
        display:flex; flex-direction:column; transition:transform .22s,box-shadow .22s;
        animation:fadeUp .4s both;
    }
    .dh-card:hover { transform:translateY(-5px); box-shadow:var(--sh-md); }
    .dh-card-media { position:relative; overflow:hidden; flex-shrink:0; }
    .dh-card-media a { display:block; }
    .dh-card-media img,.dh-card-media video {
        width:100%; height:220px; object-fit:cover; display:block; transition:transform .35s;
    }
    .dh-card:hover .dh-card-media img { transform:scale(1.04); }
    .dh-card-media .ratio { height:220px; }
    .badge-feat {
        position:absolute; top:12px; right:12px; background:#f59e0b; color:#fff;
        font-size:.6rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase;
        padding:4px 10px; border-radius:100px; z-index:2;
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

    /* Empty */
    .dh-empty { grid-column:1/-1; text-align:center; padding:72px 24px; color:var(--ink-muted); }
    .dh-empty-icon { font-size:2.8rem; margin-bottom:14px; opacity:.32; }
    .dh-empty-title { font-size:1.15rem; font-weight:700; color:var(--ink); margin-bottom:8px; }
    .dh-empty-text  { font-size:.85rem; font-weight:300; }

    /* Loading dots */
    .dh-loader { display:none; text-align:center; padding:40px 0; }
    .dh-dots { display:inline-flex; gap:7px; align-items:center; }
    .dh-dots span {
        width:8px; height:8px; border-radius:50%; background:var(--accent);
        animation:dotPulse 1.2s infinite both;
    }
    .dh-dots span:nth-child(2) { animation-delay:.2s; }
    .dh-dots span:nth-child(3) { animation-delay:.4s; }
    @keyframes dotPulse {
        0%,80%,100% { opacity:.2; transform:scale(.75); }
        40%          { opacity:1; transform:scale(1); }
    }
    .dh-end-msg {
        display:none; text-align:center; padding:28px 0;
        font-size:.77rem; color:var(--ink-muted); letter-spacing:.06em;
    }
    .dh-end-msg::before,.dh-end-msg::after {
        content:''; display:inline-block; width:32px; height:1px;
        background:rgba(0,0,0,.14); vertical-align:middle; margin:0 10px;
    }

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


    /* ─── Subcategory tiles ──────────────────────────── */
    .dh-subcat-tile-sec { background:var(--surface); padding:16px 0 8px; }
    .dh-subcat-tile-label {
        font-size:.64rem; font-weight:600; letter-spacing:.13em; text-transform:uppercase;
        color:var(--ink-muted); margin-bottom:12px;
    }
    .dh-subcat-tile-grid {
        display:flex; flex-wrap:wrap; gap:10px;
    }
    @media(max-width:600px) {
        .dh-subcat-tile-grid {
            flex-wrap:nowrap; overflow-x:auto; padding-bottom:4px;
            -ms-overflow-style:none; scrollbar-width:none; cursor:grab;
        }
        .dh-subcat-tile-grid::-webkit-scrollbar { display:none; }
    }
    .dh-stile {
        flex:0 0 auto; width:82px; display:flex; flex-direction:column;
        align-items:center; justify-content:center; gap:7px;
        padding:12px 6px 10px; border-radius:14px; text-decoration:none;
        text-align:center; color:var(--ink); font-size:.68rem; font-weight:600;
        line-height:1.25; background:#fff; border:1.5px solid rgba(0,0,0,.08);
        box-shadow:var(--sh-sm); transition:transform .2s,box-shadow .2s,border-color .2s;
        user-select:none;
    }
    .dh-stile:hover { transform:translateY(-3px); box-shadow:var(--sh-md);
                      border-color:var(--accent); color:var(--accent); }
    .dh-stile.stile-active { background:var(--ink); color:#fff; border-color:var(--ink);
                               box-shadow:0 4px 16px rgba(0,0,0,.2); }
    .dh-stile.stile-active .stile-icon { background:rgba(255,255,255,.18) !important;
                                          color:#fff !important; }
    .stile-icon {
        width:36px; height:36px; border-radius:10px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        transition:transform .2s;
    }
    .dh-stile:hover .stile-icon { transform:scale(1.1); }
    .stile-name { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:70px; }

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
            <a href="{{ route('home') }}" class="dh-btn-nav" style="background:#f1f5f9;color:var(--ink);">
                <i class="bi bi-house"></i> Home
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

{{-- ═════════════════════════════════════════════════════
     COMPACT HERO
═════════════════════════════════════════════════════ --}}
<header class="dh-hero">
    <div class="dh-hero-bg"></div>
    <div class="dh-hero-overlay"></div>
    <div class="dh-hero-body">
        <div class="dh-hero-eyebrow">DealsHood</div>
        <h1 class="dh-hero-title">
            @if (request('category_id'))
                @php $activeCat = $categories->firstWhere('slug', request('category_id')); @endphp
                {{ $activeCat?->name ?? 'Category' }} Deals
            @elseif (request('keyword'))
                Results for "{{ request('keyword') }}"
            @else
                Browse All Deals
            @endif
        </h1>
        <p class="dh-hero-sub">
            {{ number_format($posts->total()) }} deals found
            @if(request('locality_id')) · {{ $localities->firstWhere('slug', request('locality_id'))?->name }} @endif
        </p>
    </div>
    <div class="dh-hero-wave">
        <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 56H1440V28C1200 56 960 8 720 8C480 8 240 56 0 28V56Z" fill="#faf9f7"/>
        </svg>
    </div>
</header>


{{-- ═════════════════════════════════════════════════════
     CATEGORY QUICK-FILTER CHIPS
═════════════════════════════════════════════════════ --}}
@php
$palette = [
    ['bg'=>'#dbeafe','ic'=>'#1d4ed8','icon'=>'fa-tags'],
    ['bg'=>'#d1fae5','ic'=>'#059669','icon'=>'fa-leaf'],
    ['bg'=>'#fef3c7','ic'=>'#d97706','icon'=>'fa-fire'],
    ['bg'=>'#fce7f3','ic'=>'#db2777','icon'=>'fa-heart'],
    ['bg'=>'#ede9fe','ic'=>'#7c3aed','icon'=>'fa-gem'],
    ['bg'=>'#cffafe','ic'=>'#0891b2','icon'=>'fa-bolt'],
    ['bg'=>'#fef2f2','ic'=>'#dc2626','icon'=>'fa-percent'],
    ['bg'=>'#ecfdf5','ic'=>'#16a34a','icon'=>'fa-star'],
    ['bg'=>'#fff7ed','ic'=>'#ea580c','icon'=>'fa-house'],
    ['bg'=>'#f0f9ff','ic'=>'#0284c7','icon'=>'fa-car'],
    ['bg'=>'#fdf4ff','ic'=>'#a21caf','icon'=>'fa-shirt'],
    ['bg'=>'#f8fafc','ic'=>'#475569','icon'=>'fa-laptop'],
];
@endphp

<section class="dh-cat-strip-sec">
    <div class="dh-wrap">
        <div class="dh-cat-strip" id="catChips">

            <a href="{{ route('posts.listing') }}"
               class="dh-cat-chip {{ !request('category_id') ? 'active':'' }}">
                <span class="chip-icon" style="background:rgba(0,0,0,.06);color:var(--ink);">
                    <i class="fas fa-th" style="font-size:.6rem;"></i>
                </span>
                All
            </a>

            @foreach ($categories as $i => $cat)
                @php $p = $palette[$i % count($palette)]; @endphp
                <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}"
                   class="dh-cat-chip {{ request('category_id') == $cat->slug ? 'active':'' }}">
                    <span class="chip-icon" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                        <i class="fas {{ $p['icon'] }}" style="font-size:.6rem;"></i>
                    </span>
                    {{ $cat->name }}
                    <span style="opacity:.6;font-size:.65rem;">{{ number_format($cat->posts_count) }}</span>
                </a>
            @endforeach

        </div>
    </div>
</section>


{{-- ═════════════════════════════════════════════════════
     SUBCATEGORY TILES (shown when a category is active)
═════════════════════════════════════════════════════ --}}
@if(request('category_id') && $subcategories->isNotEmpty())
    @php $activeCatForSub = $categories->firstWhere('slug', request('category_id')); @endphp
    <section class="dh-subcat-tile-sec">
        <div class="dh-wrap">
            <p class="dh-subcat-tile-label">
                {{ $activeCatForSub?->name }} — Subcategories
            </p>
            <div class="dh-subcat-tile-grid" id="subcatTileGrid">

                {{-- All [category] tile --}}
                <a href="{{ route('posts.listing', ['category_id' => request('category_id')]) }}"
                   class="dh-stile {{ !request('subcategory_id') ? 'stile-active':'' }}">
                    <span class="stile-icon" style="background:#f1f5f9;color:var(--accent);">
                        <i class="fas fa-th" style="font-size:.8rem;"></i>
                    </span>
                    <span class="stile-name">All</span>
                </a>

                @foreach ($subcategories as $i => $sub)
                    @php $p = $palette[$i % count($palette)]; @endphp
                    <a href="{{ route('posts.listing', ['category_id' => request('category_id'), 'subcategory_id' => $sub->slug]) }}"
                       class="dh-stile {{ request('subcategory_id') == $sub->slug ? 'stile-active':'' }}">
                        <span class="stile-icon" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                            <i class="fas {{ $p['icon'] }}" style="font-size:.8rem;"></i>
                        </span>
                        <span class="stile-name">{{ $sub->name }}</span>
                    </a>
                @endforeach

            </div>
        </div>
    </section>
@endif

{{-- ═════════════════════════════════════════════════════
     LOCALITY QUICK-FILTER CHIPS
═════════════════════════════════════════════════════ --}}
<section class="dh-cat-strip-sec" style="padding-top:18px;">
    <div class="dh-wrap">

        <div class="dh-subcat-tile-label" style="margin-bottom:10px;">
            Browse by Locality
        </div>

        <div class="dh-cat-strip" id="localityChips">

            {{-- All --}}
            <a href="{{ route('posts.listing', request()->except('locality_id')) }}"
               class="dh-cat-chip {{ !request('locality_id') ? 'active':'' }}">

                <span class="chip-icon"
                      style="background:rgba(0,0,0,.06);color:var(--ink);">
                    <i class="fas fa-location-dot" style="font-size:.6rem;"></i>
                </span>

                All Areas
            </a>

            @foreach ($localities as $i => $loc)

                @php
                    $p = $palette[$i % count($palette)];
                @endphp

                <a href="{{ request()->fullUrlWithQuery(['locality_id' => $loc->slug]) }}"
                   class="dh-cat-chip {{ request('locality_id') == $loc->slug ? 'active':'' }}">

                    <span class="chip-icon"
                          style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                        <i class="fas fa-location-dot" style="font-size:.6rem;"></i>
                    </span>

                    {{ $loc->name }}

                    @if(isset($loc->posts_count))
                        <span style="opacity:.6;font-size:.65rem;">
                            {{ number_format($loc->posts_count) }}
                        </span>
                    @endif

                </a>

            @endforeach

        </div>
    </div>
</section>


{{-- ═════════════════════════════════════════════════════
     POSTS GRID
═════════════════════════════════════════════════════ --}}
<section class="dh-posts-sec">
    <div class="dh-wrap" style="padding-top:28px;">

        {{-- Toolbar --}}
        <div class="dh-toolbar">
            <p class="dh-result-info mb-0">
                Showing <strong id="resultCount">{{ number_format($posts->total()) }}</strong> deals
                @if (request('category_id'))
                    in <strong>{{ $categories->firstWhere('slug', request('category_id'))?->name }}</strong>
                @endif
            </p>
            <div class="dh-sort-pills">
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}"
                   class="dh-sort-pill {{ request('sort','latest') === 'latest' ? 'active':'' }}">
                    <i class="bi bi-clock me-1"></i> Newest
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}"
                   class="dh-sort-pill {{ request('sort') === 'popular' ? 'active':'' }}">
                    <i class="bi bi-eye me-1"></i> Popular
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'trending']) }}"
                   class="dh-sort-pill {{ request('sort') === 'trending' ? 'active':'' }}">
                    <i class="bi bi-fire me-1"></i> Trending
                </a>
            </div>
        </div>

        {{-- Grid --}}
        <div class="dh-grid" id="post-wrapper">
            @forelse($posts as $post)
            @include('frontend.post-single-card', ['post' => $post])
        @empty
            <div class="dh-empty" style="grid-column:1/-1;">
                <div class="dh-empty-icon">🔍</div>
                <p class="dh-empty-title">No Deals Found</p>
                <p class="dh-empty-text">Try adjusting your filters or search keywords.</p>
            </div>
        @endforelse
        </div>

        {{-- Loader --}}
        <div class="dh-loader" id="loading">
            <div class="dh-dots">
                <span></span><span></span><span></span>
            </div>
        </div>
        <div class="dh-end-msg" id="endMsg">You've seen all the deals</div>
        <input type="hidden" id="next-page-url" value="{{ $posts->nextPageUrl() }}">

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

// ── Drag-to-scroll (chips + subcat tiles) ────────────────────
function makeDragScroll(el) {
    if (!el) return;
    let down=false,sx=0,sl=0;
    el.addEventListener('mousedown',e=>{down=true;sx=e.pageX-el.offsetLeft;sl=el.scrollLeft;el.style.cursor='grabbing';});
    el.addEventListener('mouseleave',()=>{down=false;el.style.cursor='grab';});
    el.addEventListener('mouseup',()=>{down=false;el.style.cursor='grab';});
    el.addEventListener('mousemove',e=>{if(!down)return;e.preventDefault();el.scrollLeft=sl-(e.pageX-el.offsetLeft-sx)*1.4;});
}
const chips = document.getElementById('catChips');
makeDragScroll(chips);
makeDragScroll(document.getElementById('subcatTileGrid'));

// ── Dynamic subcategories ─────────────────────────────────────
$('#category_id').on('change', function(){
    const id=$(this).val(), sub=$('#subcategory_id');
    sub.empty().append('<option value="">Loading…</option>');
    if(id){
        $.get('/get-subcategories/'+id,function(data){
            sub.empty().append('<option value="">All Subcategories</option>');
            $.each(data,function(k,v){
                sub.append('<option value="'+v.slug+'">'+v.name+'</option>');
            });
        }).fail(function(){ sub.empty().append('<option value="">All Subcategories</option>'); });
    } else {
        sub.empty().append('<option value="">All Subcategories</option>');
    }
});

// ── AJAX filter + infinite scroll ────────────────────────────
let loading = false;

document.getElementById('filterForm').addEventListener('submit', function(e){
    e.preventDefault(); loadPosts(true);
});
document.querySelectorAll('#filterForm select').forEach(function(el){
    el.addEventListener('change', function(){ loadPosts(true); });
});

function loadPosts(reset, nextPage){
    if(loading) return;
    loading = true;
    const loader = document.getElementById('loading');
    const endMsg = document.getElementById('endMsg');
    loader.style.display = 'block';
    endMsg.style.display = 'none';

    const form   = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form));
    const url    = nextPage || ('{{ route("posts.listing") }}?' + params.toString());

    if(reset) window.history.pushState({}, '', url);

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            loader.style.display = 'none';
            const wrapper = document.getElementById('post-wrapper');
            reset ? wrapper.innerHTML = data.html : wrapper.insertAdjacentHTML('beforeend', data.html);

            // Stagger animate new cards
            wrapper.querySelectorAll('.dh-card').forEach((c,i) => c.style.animationDelay = (i*.03)+'s');

            const next = data.next_page || '';
            document.getElementById('next-page-url').value = next;
            if(!next) endMsg.style.display = 'block';

            // Update result count
            if(data.total !== undefined)
                document.getElementById('resultCount').textContent = data.total.toLocaleString();

            loading = false;
        })
        .catch(err => { loading=false; loader.style.display='none'; console.error(err); });
}

// Infinite scroll
window.addEventListener('scroll', function(){
    if(loading) return;
    if(document.body.offsetHeight - window.scrollY - window.innerHeight > 320) return;
    const next = document.getElementById('next-page-url').value;
    if(!next) return;
    loadPosts(false, next);
}, { passive: true });

// ── Like ──────────────────────────────────────────────────────
$(document).on('click', '.likeBtn', function(){
    const btn=$(this), id=btn.data('id');
    $.post('/posts/'+id+'/toggle-like',{_token:'{{ csrf_token() }}'},function(res){
        $('#lc-'+id).text(res.likes);
        res.liked?btn.addClass('liked'):btn.removeClass('liked');
    });
});

// ── Share ─────────────────────────────────────────────────────
$(document).on('click', '.shareBtn', function(){
    const id=$(this).data('id'), url=$(this).data('url');
    navigator.share ? navigator.share({url}) : (navigator.clipboard.writeText(url), alert('Link copied!'));
    $.post('/posts/'+id+'/share',{_token:'{{ csrf_token() }}',platform:'web'});
});
</script>

</body>
</html>