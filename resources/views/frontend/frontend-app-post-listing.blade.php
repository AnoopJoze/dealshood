<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ site_favicon_url() }}">
    <link rel="shortcut icon" href="{{ site_favicon_url() }}">
    @php
        $siteName    = setting('site_name', 'DealsHood');
        $siteTagline = setting('site_tagline', 'Discover the Best Deals Near You');
        $siteDesc    = setting('site_description',
            'Find the best local deals, offers and classifieds near you. Browse by category or locality.');
        $siteUrl  = url('/');
        $ogImage  = site_og_image_url();

        $catNames = $categories->pluck('name')->take(10)->implode(', ');
        $locNames = $localities->pluck('name')->take(8)->implode(', ');
        $keywords = trim($catNames . ($locNames ? ', ' . $locNames : ''))
            . ', deals, offers, classifieds, local deals';

        $topCats  = $categories->take(4)->pluck('name')->implode(', ');
        $topLocs  = $localities->take(4)->pluck('name')->implode(', ');
        $richDesc = $siteDesc;
        if ($topCats) $richDesc .= ' Categories include ' . $topCats . '.';
        if ($topLocs) $richDesc .= ' Available in ' . $topLocs . ' and more.';

        $canonical = $siteUrl;
        $activeCat = $categories->firstWhere('slug', request('category_id'));
        $activeLoc = $localities->firstWhere('slug', request('locality_id'));
        $heroBannerUrl = !empty(setting('banner_image'))
            ? Storage::url(setting('banner_image'))
            : '/frontend/img/illustrations/IMG_4871.png';
    @endphp

    <title>{{ $activeCat->name ?? 'Browse Deals' }}@if($activeLoc) in {{ $activeLoc->name }}@endif — {{ $siteName }}</title>

    <meta name="description"        content="{{ Str::limit($richDesc, 160) }}">
    <meta name="keywords"           content="{{ $keywords }}">
    <meta name="robots"             content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author"             content="{{ $siteName }}">
    <link rel="canonical"           href="{{ $canonical }}">

    <meta property="og:site_name"   content="{{ $siteName }}">
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="{{ $siteName }} — {{ $siteTagline }}">
    <meta property="og:description" content="{{ Str::limit($richDesc, 200) }}">
    <meta property="og:url"         content="{{ $canonical }}">
    <meta property="og:image"       content="{{ $ogImage }}">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">

    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $siteName }} — {{ $siteTagline }}">
    <meta name="twitter:description" content="{{ Str::limit($richDesc, 160) }}">
    <meta name="twitter:image"       content="{{ $ogImage }}">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0a2a68">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="/frontend/img/icons/icon-192x192.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet">
    <link href="/frontend/css/dh-header-footer.css?v=1.0.3" rel="stylesheet">

    <style>
    :root{
        --navy:#0a2a68; --navy-deep:#071e4d; --blue:#123f8f; --blue-2:#1b4dc4;
        --green:#16a34a; --orange:#f97316;
        --ink:#0f172a; --muted:#5b6b8c; --muted-2:#8090ad;
        --bg:#ffffff; --bg-soft:#f4f6fa; --line:#e4e9f2;
        --r:16px; --r-lg:22px; --r-sm:10px;
        --sh-sm:0 2px 10px rgba(10,42,104,.06);
        --sh-md:0 10px 30px rgba(10,42,104,.10);
        --sh-lg:0 24px 60px rgba(10,42,104,.16);
        --nav-h:74px;
        --font:'Poppins',-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;
    }
    *,*::before,*::after{ box-sizing:border-box; }
    body{ font-family:var(--font); background:var(--bg-soft); color:var(--ink); margin:0; -webkit-font-smoothing:antialiased; }
    a{ text-decoration:none; }
    img{ max-width:100%; }
    .wrap{ max-width:1240px; margin:0 auto; padding:0 24px; }

    /* NAVBAR / FOOTER — shared, see /frontend/css/dh-header-footer.css */

    /* ══════════ HERO ══════════ */
    .dh-hero{ position:relative; overflow:hidden; min-height:440px; display:flex; align-items:center;
              padding:104px 0 60px; background:var(--navy-deep); }
    .dh-hero-bg{ position:absolute; inset:0; background-size:cover; background-position:center; }
    .dh-hero-overlay{ position:absolute; inset:0; z-index:1; background:linear-gradient(180deg,rgba(7,30,77,.72) 0%,rgba(7,30,77,.5) 45%,rgba(7,30,77,.82) 100%); }
    .dh-hero-inner{ position:relative; z-index:3; width:100%; max-width:1240px; margin:0 auto; padding:0 24px; animation:fadeUp .5s .05s both; }
    .dh-crumb{ display:flex; align-items:center; gap:8px; color:rgba(255,255,255,.7); font-size:.82rem; font-weight:500; margin-bottom:16px; }
    .dh-crumb a{ color:rgba(255,255,255,.7); } .dh-crumb a:hover{ color:#fff; }
    .dh-crumb i{ font-size:.6rem; opacity:.6; }
    .dh-crumb .cur{ color:#fff; }
    .dh-hero-title{ font-size:clamp(2.2rem,5vw,3.6rem); font-weight:700; color:#fff; line-height:1.08; letter-spacing:-.02em; margin:0 0 12px; }
    .dh-hero-lead{ font-size:clamp(.95rem,1.5vw,1.12rem); color:rgba(255,255,255,.82); font-weight:300; max-width:620px; margin:0 0 28px; line-height:1.55; }
    .dh-hero-search{ display:flex; gap:12px; max-width:760px; }
    .dh-hs-box{ flex:1; display:flex; align-items:center; gap:12px; background:#fff; border-radius:var(--r); padding:4px 6px 4px 20px; box-shadow:var(--sh-md); }
    .dh-hs-box i{ color:var(--muted); font-size:.95rem; }
    .dh-hs-box input{ flex:1; border:none; outline:none; font-family:var(--font); font-size:.95rem; color:var(--ink); background:transparent; padding:14px 0; }
    .dh-hs-search{ background:var(--navy); color:#fff; border:none; border-radius:12px; padding:0 26px; font-family:var(--font); font-weight:600; font-size:.9rem; cursor:pointer; transition:background .15s; }
    .dh-hs-search:hover{ background:var(--navy-deep); }
    .dh-hs-filter{ display:inline-flex; align-items:center; gap:9px; background:#fff; color:var(--navy); border:none; border-radius:var(--r); padding:0 24px; font-family:var(--font); font-weight:600; font-size:.9rem; cursor:pointer; box-shadow:var(--sh-md); transition:transform .15s; }
    .dh-hs-filter:hover{ transform:translateY(-1px); }
    .dh-hs-filter i{ color:var(--navy); }
    .dh-hero-note{ display:flex; align-items:center; gap:20px; margin-top:18px; color:rgba(255,255,255,.72); font-size:.8rem; flex-wrap:wrap; }
    .dh-hero-note span{ display:inline-flex; align-items:center; gap:7px; }
    .dh-hero-note i{ color:#ffd34d; }

    /* ══════════ CHIP ROWS ══════════ */
    .dh-chips-sec{ background:var(--bg); border-bottom:1px solid var(--line); }
    .dh-chips-inner{ max-width:1240px; margin:0 auto; padding:16px 24px; }
    .dh-chips-row{ display:flex; gap:10px; overflow-x:auto; scrollbar-width:none; -webkit-overflow-scrolling:touch; padding-bottom:2px; }
    .dh-chips-row::-webkit-scrollbar{ display:none; }
    .dh-chip{ display:inline-flex; align-items:center; gap:7px; white-space:nowrap; flex-shrink:0; cursor:pointer;
              padding:9px 18px; border-radius:100px; font-size:.82rem; font-weight:500; color:var(--ink);
              background:#fff; border:1.5px solid var(--line); transition:all .15s; user-select:none; }
    .dh-chip:hover{ border-color:var(--navy); color:var(--navy); }
    .dh-chip.active{ background:var(--navy); color:#fff; border-color:var(--navy); }
    .dh-subchips-sec{ background:var(--bg-soft); }
    .dh-subchips-inner{ max-width:1240px; margin:0 auto; padding:14px 24px 2px; }
    .dh-subchips-inner .dh-chip{ font-size:.78rem; padding:7px 15px; }

    /* ══════════ TOOLBAR ══════════ */
    .dh-listwrap{ max-width:1240px; margin:0 auto; padding:30px 24px 80px; }
    .dh-toolbar{ display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px; margin-bottom:26px; }
    .dh-result-title{ font-size:1.5rem; font-weight:700; color:var(--navy); margin:0; }
    .dh-result-title span{ color:var(--blue-2); }
    .dh-toolbar-right{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .dh-sort-pills{ display:flex; gap:6px; background:#fff; border:1px solid var(--line); border-radius:100px; padding:4px; }
    .dh-sort-pill{ display:inline-flex; align-items:center; gap:6px; font-size:.8rem; font-weight:500; padding:7px 16px; border-radius:100px; cursor:pointer; color:var(--muted); transition:all .15s; user-select:none; }
    .dh-sort-pill:hover{ color:var(--navy); }
    .dh-sort-pill.active{ background:var(--navy); color:#fff; }
    .dh-view-toggle{ display:flex; gap:4px; background:#fff; border:1px solid var(--line); border-radius:12px; padding:4px; }
    .dh-vbtn{ width:36px; height:34px; border-radius:8px; border:none; background:transparent; cursor:pointer; color:var(--muted); display:flex; align-items:center; justify-content:center; font-size:.95rem; transition:all .15s; }
    .dh-vbtn.active{ background:var(--navy); color:#fff; }

    /* ══════════ CARD GRID (same card design as the home page) ══════════ */
    .dh-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
    .dh-grid.list-view{ grid-template-columns:1fr; }

    .dh-card{ background:#fff; border:1px solid var(--line); border-radius:var(--r); overflow:hidden;
              display:flex; flex-direction:column; transition:transform .18s, box-shadow .18s; animation:fadeUp .4s both; }
    .dh-grid .dh-card{ flex:none; width:auto; }
    .dh-card:hover{ transform:translateY(-3px); box-shadow:var(--sh-md); }
    .dh-card-media{ position:relative; aspect-ratio:16/11; background:#0b1e42; overflow:hidden; }
    .dh-card-media a{ display:block; width:100%; height:100%; }
    .dh-card-media video,.dh-card-media iframe{ width:100%; height:100%; object-fit:cover; display:block; border:0; }
    /* blurred copy fills the frame edge-to-edge */
    .dh-card-media .dh-card-bg{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; filter:blur(20px) brightness(.65) saturate(1.2); transform:scale(1.2); display:block; }
    /* real image on top — shown in full, never cropped */
    .dh-card-media .dh-card-fg{ position:relative; z-index:1; width:100%; height:100%; object-fit:contain; display:block; }
    /* z-index:2 — must sit above .dh-card-fg (z-index:1), the full post image,
       or it paints on top and hides these overlay elements */
    .dh-card-loc{ position:absolute; left:10px; bottom:10px; z-index:2; display:inline-flex; align-items:center; gap:5px;
                  background:rgba(10,20,40,.6); color:#fff; font-size:.72rem; font-weight:500;
                  padding:5px 11px; border-radius:100px; backdrop-filter:blur(4px); }
    .dh-card-fav{ position:absolute; right:10px; top:10px; z-index:2; width:34px; height:34px; border-radius:50%;
                  background:rgba(255,255,255,.9); border:none; color:var(--navy); cursor:pointer;
                  display:flex; align-items:center; justify-content:center; font-size:.82rem; transition:all .15s; }
    .dh-card-fav.liked{ background:#e11d48; color:#fff; }
    .dh-card-badge{ position:absolute; left:10px; top:10px; z-index:2; font-size:.6rem; font-weight:700;
                    letter-spacing:.06em; text-transform:uppercase; padding:5px 10px; border-radius:6px; color:#fff; }
    .dh-card-badge.hot{ background:var(--orange); } .dh-card-badge.trend{ background:#eab308; color:#3a2c00; }
    .dh-card-body{ padding:16px 16px 18px; display:flex; flex-direction:column; flex:1; }
    .dh-badges{ display:flex; flex-wrap:wrap; gap:6px; margin-bottom:12px; }
    .dh-b{ font-size:.64rem; font-weight:600; letter-spacing:.04em; text-transform:uppercase;
           color:var(--muted); background:var(--bg-soft); border-radius:6px; padding:5px 9px; }
    .dh-card-title-row{ display:flex; align-items:flex-start; justify-content:space-between; gap:10px; margin-bottom:12px; }
    .dh-card-title{ font-size:1rem; font-weight:600; color:var(--navy); line-height:1.32; }
    .dh-rating-view{ display:flex; align-items:center; gap:4px; flex-shrink:0; }
    .dh-star-big-wrap{ position:relative; font-size:.85rem; color:#e2e8f0; line-height:1; }
    .dh-star-big-fg{ position:absolute; top:0; left:0; overflow:hidden; white-space:nowrap; color:#f59e0b; }
    .dh-rating-avg-sm{ font-size:.8rem; font-weight:700; color:var(--navy); }
    .dh-rating-count-sm{ font-size:.72rem; color:var(--muted); }
    .dh-card-biz{ display:flex; align-items:center; gap:7px; font-size:.78rem; font-weight:600;
                  color:var(--ink); text-transform:uppercase; letter-spacing:.02em; margin-bottom:12px; }
    .dh-card-biz i{ color:var(--muted); }
    .dh-card-desc{ display:none; }
    .dh-card-meta{ display:flex; align-items:center; gap:14px; padding-top:12px; margin-top:auto;
                   border-top:1px solid var(--line); font-size:.74rem; color:var(--muted); }
    .dh-meta-btn{ background:none; border:none; padding:0; cursor:pointer; display:flex; align-items:center;
                  gap:5px; color:var(--muted); font-family:var(--font); font-size:.74rem; }
    .dh-meta-btn.liked{ color:#e11d48; }
    .dh-meta-box{ display:flex; align-items:center; gap:5px; }
    .dh-meta-time{ margin-left:auto; display:flex; align-items:center; gap:5px; font-size:.72rem; white-space:nowrap; }
    .dh-card-actions{ display:flex; gap:8px; margin-top:14px; }
    .dh-btn{ display:inline-flex; align-items:center; justify-content:center; gap:6px; font-family:var(--font);
             font-size:.82rem; font-weight:600; border-radius:10px; padding:11px 16px; cursor:pointer;
             border:none; transition:all .15s; }
    .dh-btn-primary{ flex:1; background:var(--navy); color:#fff; }
    .dh-btn-primary:hover{ background:var(--navy-deep); color:#fff; }
    .dh-btn-ghost{ width:46px; background:#fff; border:1.5px solid var(--line); color:var(--navy); }
    .dh-btn-ghost:hover{ border-color:var(--navy); }

    /* ── list view (horizontal list item), desktop ──
       capped to a proportionate thumbnail (aspect-ratio) instead of
       stretching full-height with the row, which made it look oversized */
    .dh-grid.list-view .dh-card{ flex-direction:row; align-items:center; padding:10px; gap:14px; }
    .dh-grid.list-view .dh-card-media{ flex:0 0 200px; aspect-ratio:4/3; border-radius:12px; align-self:center; }
    .dh-grid.list-view .dh-card-body{ padding:0; }

    /* ── list view, compact horizontal rows on mobile ── */
    @media(max-width:768px){
        .dh-grid.list-view .dh-card{ flex-direction:row; align-items:center; min-height:0; padding:8px; gap:10px; }
        .dh-grid.list-view .dh-card-media{ flex:0 0 96px; aspect-ratio:1/1; border-radius:10px; align-self:center; }
        .dh-grid.list-view .dh-card-fav,
        .dh-grid.list-view .dh-card-loc{ display:none; }
        .dh-grid.list-view .dh-card-body{ padding:0; }
        .dh-grid.list-view .dh-badges{ margin-bottom:6px; }
        .dh-grid.list-view .dh-b{ font-size:.6rem; padding:4px 8px; }
        .dh-grid.list-view .dh-card-title-row{ margin-bottom:6px; }
        .dh-grid.list-view .dh-card-title{ font-size:.86rem;
            display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        .dh-grid.list-view .dh-card-biz,
        .dh-grid.list-view .dh-card-meta{ display:none; }
        .dh-grid.list-view .dh-card-actions{ margin-top:8px; }
        .dh-grid.list-view .dh-card-actions .dh-btn-primary{ padding:8px 12px; font-size:.72rem; }
        .dh-grid.list-view .dh-card-actions .dh-btn-ghost{ width:30px; padding:8px; }
    }

    .dh-empty{ grid-column:1/-1; text-align:center; color:var(--muted); padding:64px 20px; }
    .dh-empty-icon{ font-size:2.6rem; margin-bottom:14px; }
    .dh-empty-title{ font-size:1.2rem; font-weight:700; color:var(--navy); margin:0 0 6px; }
    .dh-empty-text{ font-size:.9rem; }

    .dh-loader{ display:none; justify-content:center; gap:8px; padding:40px 0; }
    .dh-loader span{ width:11px; height:11px; border-radius:50%; background:var(--blue); opacity:.5; animation:bounce .8s infinite; }
    .dh-loader span:nth-child(2){ animation-delay:.15s; } .dh-loader span:nth-child(3){ animation-delay:.3s; }
    @keyframes bounce{ 0%,80%,100%{ transform:scale(.6); opacity:.4; } 40%{ transform:scale(1); opacity:1; } }
    .dh-end-msg{ display:none; text-align:center; padding:28px 0; font-size:.8rem; color:var(--muted); letter-spacing:.04em; }

    /* ══════════ FILTERS PANEL (offcanvas) ══════════ */
    .dh-filter-backdrop{ position:fixed; inset:0; background:rgba(7,30,77,.45); z-index:1200; opacity:0; pointer-events:none; transition:opacity .25s; }
    .dh-filter-backdrop.open{ opacity:1; pointer-events:auto; }
    /* NOTE: named .dh-filter-drawer (not .dh-filter-panel) — frontend-mobile.blade.php
       defines its own, incompatible !important rules for ".dh-filter-panel" (a legacy
       bottom-sheet component), which silently broke this panel's position/transform
       when the class names collided. Keep this name unique from that file's selectors. */
    .dh-filter-drawer{ position:fixed; top:0; right:0; bottom:0; width:380px; max-width:88vw; background:#fff; z-index:1201;
                      transform:translateX(100%); transition:transform .28s cubic-bezier(.4,0,.2,1); box-shadow:var(--sh-lg); display:flex; flex-direction:column; }
    .dh-filter-drawer.open{ transform:none; }
    .dh-filter-head{ display:flex; align-items:center; justify-content:space-between; padding:22px 24px; border-bottom:1px solid var(--line); }
    .dh-filter-head h3{ display:flex; align-items:center; gap:10px; font-size:1.1rem; font-weight:700; color:var(--navy); margin:0; }
    .dh-filter-reset{ display:inline-flex; align-items:center; gap:6px; background:none; border:none; color:var(--muted); font-family:var(--font); font-size:.84rem; font-weight:500; cursor:pointer; }
    .dh-filter-reset:hover{ color:var(--navy); }
    .dh-filter-close{ background:var(--bg-soft); border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; color:var(--navy); font-size:1rem; }
    .dh-filter-body{ padding:22px 24px 30px; overflow-y:auto; flex:1; }
    .dh-filter-group{ margin-bottom:26px; }
    .dh-filter-label{ font-size:.9rem; font-weight:700; color:var(--navy); margin:0 0 12px; }
    .dh-filter-search{ display:flex; align-items:center; gap:10px; border:1.5px solid var(--line); border-radius:12px; padding:11px 14px; }
    .dh-filter-search i{ color:var(--muted); }
    .dh-filter-search input{ flex:1; border:none; outline:none; font-family:var(--font); font-size:.9rem; color:var(--ink); }
    .dh-filter-select{ width:100%; border:1.5px solid var(--line); border-radius:12px; padding:12px 14px; font-family:var(--font); font-size:.9rem; color:var(--ink); background:#fff; cursor:pointer; }
    .dh-filter-chips{ display:flex; flex-wrap:wrap; gap:8px; }
    .dh-filter-apply{ display:block; width:100%; background:var(--navy); color:#fff; border:none; border-radius:12px; padding:14px; font-family:var(--font); font-weight:600; font-size:.92rem; cursor:pointer; }
    .dh-filter-apply:hover{ background:var(--navy-deep); }

    @keyframes fadeUp{ from{ opacity:0; transform:translateY(20px); } to{ opacity:1; transform:none; } }

    /* ══════════ RESPONSIVE ══════════ */
    @media(max-width:1024px){ .dh-grid{ grid-template-columns:repeat(2,1fr); } }
    @media(max-width:900px){
        .dh-nav-links,.dh-btn-signin{ display:none; }
        .dh-nav-icon-btn{ display:flex; }
    }
    @media(max-width:768px){
        .dh-hero{ min-height:auto; padding:94px 0 40px; }
        .dh-hero-search{ flex-wrap:wrap; }
        .dh-hs-box{ order:1; flex-basis:100%; }
        .dh-hs-search{ order:2; flex:1; padding:14px 0; }
        .dh-hs-filter{ order:3; padding:14px 20px; }
        .dh-grid{ grid-template-columns:1fr 1fr; gap:14px; }
        .dh-footer-grid{ grid-template-columns:1fr 1fr; gap:28px; }
        .dh-result-title{ font-size:1.2rem; }
    }
    @media(max-width:480px){
        .dh-grid{ grid-template-columns:1fr; }
        .dh-footer-grid{ grid-template-columns:1fr; }
        .dh-nav-download-txt{ display:none; }
    }
    </style>
</head>
<body>

@include('frontend.partials.nav', [
    'categories'      => $categories,
    'activeNav'       => 'listing',
    'activeLocName'   => $activeLoc->name ?? null,
    'transparent'     => true,
])

{{-- ═══════════ HERO ═══════════ --}}
<header class="dh-hero">
    <div class="dh-hero-bg" style="background-image:url('{{ $heroBannerUrl }}');"></div>
    <div class="dh-hero-overlay"></div>
    <div class="dh-hero-inner">
        <nav class="dh-crumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <i class="fas fa-chevron-right"></i>
            <span class="cur">@if($activeCat){{ $activeCat->name }}@else All Deals @endif</span>
        </nav>

        <h1 class="dh-hero-title" id="heroTitle">
            @if($activeCat && $activeLoc){{ $activeCat->name }} in {{ $activeLoc->name }}
            @elseif($activeCat){{ $activeCat->name }}
            @elseif($activeLoc){{ $activeLoc->name }}
            @elseif(request('keyword'))Results for "{{ request('keyword') }}"
            @else Discover Deals Near You @endif
        </h1>
        <p class="dh-hero-lead">Find verified deals, offers and services near you — compare, save and grab the best in your area.</p>

        <form class="dh-hero-search" onsubmit="event.preventDefault(); doSearch();">
            <div class="dh-hs-box">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" id="keywordInput" placeholder="Search deals by keyword…" value="{{ request('keyword') }}" autocomplete="off">
            </div>
            <button type="submit" class="dh-hs-search"><i class="fas fa-magnifying-glass"></i> Search</button>
            <button type="button" class="dh-hs-filter" onclick="openFilters()"><i class="fas fa-sliders"></i> Filters</button>
        </form>

        <div class="dh-hero-note">
            <span><i class="fas fa-location-dot"></i> Serving {{ max(10, $localities->count()) }}+ neighbourhoods</span>
            <span><i class="fas fa-bolt"></i> Filters update results instantly</span>
        </div>
    </div>
</header>

{{-- ═══════════ CATEGORY CHIPS ═══════════ --}}
<section class="dh-chips-sec">
    <div class="dh-chips-inner">
        <div class="dh-chips-row" id="catChips">
            <span class="dh-chip {{ !request('category_id') ? 'active' : '' }}" data-filter="category_id" data-val="">All</span>
            @foreach($categories as $cat)
                <span class="dh-chip {{ request('category_id') == $cat->slug ? 'active' : '' }}" data-filter="category_id" data-val="{{ $cat->slug }}">{{ $cat->name }}</span>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════ SUBCATEGORY CHIPS ═══════════ --}}
<section class="dh-subchips-sec" id="subcatSec" style="{{ request('category_id') && $subcategories->isNotEmpty() ? '' : 'display:none;' }}">
    <div class="dh-subchips-inner">
        <div class="dh-chips-row" id="subcatChips">
            @if(request('category_id') && $subcategories->isNotEmpty())
                <span class="dh-chip {{ !request('subcategory_id') ? 'active' : '' }}" data-filter="subcategory_id" data-val="">All {{ $activeCat?->name }}</span>
                @foreach($subcategories as $sub)
                    <span class="dh-chip {{ request('subcategory_id') == $sub->slug ? 'active' : '' }}" data-filter="subcategory_id" data-val="{{ $sub->slug }}">{{ $sub->name }}</span>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- ═══════════ RESULTS ═══════════ --}}
<div class="dh-listwrap">
    <div class="dh-toolbar">
        <h2 class="dh-result-title"><span id="resultCount">{{ number_format($posts->total()) }}</span> deals available</h2>
        <div class="dh-toolbar-right">
            <div class="dh-sort-pills">
                <span class="dh-sort-pill {{ request('sort','latest') === 'latest' ? 'active' : '' }}" data-sort="latest"><i class="bi bi-clock"></i> Newest</span>
                <span class="dh-sort-pill {{ request('sort') === 'trending' ? 'active' : '' }}" data-sort="trending"><i class="bi bi-fire"></i> Trending</span>
            </div>
            <div class="dh-view-toggle">
                <button class="dh-vbtn active" id="btnGrid" title="Grid view"><i class="bi bi-grid-3x3-gap-fill"></i></button>
                <button class="dh-vbtn" id="btnList" title="List view"><i class="bi bi-list-ul"></i></button>
            </div>
        </div>
    </div>

    <div class="dh-grid" id="post-wrapper">
        @forelse($posts as $post)
            @include('frontend.post-single-card', ['post' => $post])
        @empty
            <div class="dh-empty">
                <div class="dh-empty-icon">🔍</div>
                <p class="dh-empty-title">No Deals Found</p>
                <p class="dh-empty-text">Try a different locality, category, or keyword.</p>
            </div>
        @endforelse
    </div>

    <div class="dh-loader" id="loading"><span></span><span></span><span></span></div>
    <div class="dh-end-msg" id="endMsg">You've seen all the deals</div>
    <input type="hidden" id="next-page-url" value="{{ $posts->nextPageUrl() }}">
</div>

{{-- ═══════════ FILTERS PANEL ═══════════ --}}
<div class="dh-filter-backdrop" id="dhFilterBackdrop" onclick="closeFilters()"></div>
<aside class="dh-filter-drawer" id="dhFilterPanel" aria-hidden="true">
    <div class="dh-filter-head">
        <h3><i class="fas fa-sliders"></i> Filters</h3>
        <div style="display:flex;align-items:center;gap:10px;">
            <button class="dh-filter-reset" onclick="resetFilters()"><i class="fas fa-rotate-left"></i> Reset</button>
            <button class="dh-filter-close" onclick="closeFilters()"><i class="fas fa-xmark"></i></button>
        </div>
    </div>
    <div class="dh-filter-body">
        <div class="dh-filter-group">
            <p class="dh-filter-label">Search</p>
            <div class="dh-filter-search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" id="fKeyword" placeholder="Search services…" value="{{ request('keyword') }}">
            </div>
        </div>

        <div class="dh-filter-group">
            <p class="dh-filter-label">Categories</p>
            <div class="dh-filter-chips" id="fCatChips">
                <span class="dh-chip {{ !request('category_id') ? 'active' : '' }}" data-filter="category_id" data-val="">All</span>
                @foreach($categories as $cat)
                    <span class="dh-chip {{ request('category_id') == $cat->slug ? 'active' : '' }}" data-filter="category_id" data-val="{{ $cat->slug }}">{{ $cat->name }}</span>
                @endforeach
            </div>
        </div>

        <div class="dh-filter-group">
            <p class="dh-filter-label">Location</p>
            <select class="dh-filter-select" id="fLocality">
                <option value="">All locations</option>
                @foreach($localities as $loc)
                    <option value="{{ $loc->slug }}" {{ request('locality_id') == $loc->slug ? 'selected' : '' }}>
                        {{ str_repeat('— ', $loc->type === 'city' ? 1 : ($loc->type === 'area' ? 2 : 0)) }}{{ $loc->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="dh-filter-group">
            <p class="dh-filter-label">Sort by</p>
            <select class="dh-filter-select" id="fSort">
                <option value="latest"   {{ request('sort','latest') === 'latest' ? 'selected' : '' }}>Newest first</option>
                <option value="trending" {{ request('sort') === 'trending' ? 'selected' : '' }}>Trending</option>
                <option value="popular"  {{ request('sort') === 'popular' ? 'selected' : '' }}>Most viewed</option>
            </select>
        </div>

        <button class="dh-filter-apply" onclick="applyFilters()">Apply Filters</button>
    </div>
</aside>

{{-- ═══════════ FOOTER ═══════════ --}}
@include('frontend.partials.footer', ['categories' => $categories])

@include('frontend.frontend-mobile')
@include('frontend.post-ad-modal')

<script src="/frontend/js/core/popper.min.js"></script>
<script src="/frontend/js/core/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
const LISTING_URL = '{{ route("posts.listing") }}';
const CSRF        = '{{ csrf_token() }}';

const filters = {
    locality_id    : '{{ request("locality_id") }}',
    category_id    : '{{ request("category_id") }}',
    subcategory_id : '{{ request("subcategory_id") }}',
    keyword        : '{{ request("keyword") }}',
    sort           : '{{ request("sort", "latest") }}',
    page           : 1,
};
let isLoading = false;

/* ── Location trigger integration (used by location-popup) ── */
function setLocUI(slug, name, skipReload){
    const label = document.getElementById('locLabel');
    const trigger = document.getElementById('locTrigger');
    if(label) label.textContent = slug ? name : 'Location';
    if(trigger) slug ? trigger.classList.add('has-loc') : trigger.classList.remove('has-loc');
    filters.locality_id = slug || '';
    filters.page = 1;
    const fLoc = document.getElementById('fLocality');
    if(fLoc) fLoc.value = slug || '';
    if(!skipReload) loadPosts(true);
}
function clearLoc(){
    setLocUI('', '');
    try{ localStorage.setItem('dh_locality_v1', JSON.stringify({slug:'',name:'All Areas',ts:Date.now()})); }catch(e){}
}
function reloadContent(){ filters.page = 1; loadPosts(true); }
window.setLocUI = setLocUI; window.clearLoc = clearLoc; window.reloadContent = reloadContent;

/* ── Build URL from filters ── */
function buildUrl(page){
    const p = Object.assign({}, filters, {page: page || 1});
    const params = new URLSearchParams();
    Object.entries(p).forEach(([k,v]) => { if(v) params.set(k, v); });
    return LISTING_URL + (params.toString() ? '?' + params.toString() : '');
}

/* ── Main AJAX load ── */
function loadPosts(reset, nextUrl){
    if(isLoading) return;
    isLoading = true;
    const loader = document.getElementById('loading');
    const endMsg = document.getElementById('endMsg');
    loader.style.display = 'flex';
    endMsg.style.display = 'none';

    const url = nextUrl || buildUrl(1);
    if(reset) window.history.pushState({}, '', url);

    fetch(url, {headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(r => r.json())
        .then(data => {
            loader.style.display = 'none';
            const wrapper = document.getElementById('post-wrapper');
            reset ? wrapper.innerHTML = data.html : wrapper.insertAdjacentHTML('beforeend', data.html);
            wrapper.querySelectorAll('.dh-card').forEach((c,i) => c.style.animationDelay = (i*.03) + 's');
            const next = data.next_page || '';
            document.getElementById('next-page-url').value = next;
            if(!next) endMsg.style.display = 'block';
            if(data.total !== undefined) document.getElementById('resultCount').textContent = Number(data.total).toLocaleString();
            isLoading = false;
        })
        .catch(() => { isLoading = false; loader.style.display = 'none'; });
}

/* ── Category / subcategory chips ── */
$(document).on('click', '#catChips .dh-chip, #fCatChips .dh-chip', function(){
    const val = $(this).data('val') || '';
    $('#catChips .dh-chip, #fCatChips .dh-chip').removeClass('active');
    $('.dh-chip[data-filter="category_id"][data-val="'+ val +'"]').addClass('active');
    filters.category_id = val;
    filters.subcategory_id = '';
    filters.page = 1;
    refreshSubcats(val);
    loadPosts(true);
});
$(document).on('click', '#subcatChips .dh-chip', function(){
    const val = $(this).data('val') || '';
    $('#subcatChips .dh-chip').removeClass('active');
    $(this).addClass('active');
    filters.subcategory_id = val;
    filters.page = 1;
    loadPosts(true);
});

/* Pull subcategories for a category and render the sub-chip row */
function refreshSubcats(catSlug){
    const sec = document.getElementById('subcatSec');
    const row = document.getElementById('subcatChips');
    if(!catSlug){ sec.style.display = 'none'; row.innerHTML = ''; return; }
    fetch('/get-subcategories/' + catSlug, {headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(r => r.json())
        .then(subs => {
            if(!subs.length){ sec.style.display = 'none'; row.innerHTML = ''; return; }
            let html = '<span class="dh-chip active" data-filter="subcategory_id" data-val="">All</span>';
            subs.forEach(s => { html += '<span class="dh-chip" data-filter="subcategory_id" data-val="'+ s.slug +'">'+ s.name +'</span>'; });
            row.innerHTML = html;
            sec.style.display = '';
        })
        .catch(() => { sec.style.display = 'none'; });
}

/* ── Sort pills ── */
$(document).on('click', '.dh-sort-pill', function(){
    $('.dh-sort-pill').removeClass('active');
    $(this).addClass('active');
    filters.sort = $(this).data('sort');
    filters.page = 1;
    const fSort = document.getElementById('fSort');
    if(fSort) fSort.value = filters.sort;
    loadPosts(true);
});

/* ── Hero search ── */
function doSearch(){
    filters.keyword = document.getElementById('keywordInput').value.trim();
    filters.page = 1;
    loadPosts(true);
}
window.doSearch = doSearch;
$('#keywordInput').on('keydown', e => { if(e.key === 'Enter'){ e.preventDefault(); doSearch(); } });

/* ── Filters panel ── */
function openFilters(){
    // The location popup (z-index 9800) can be auto-shown on first visit and would
    // otherwise sit on top of this panel (z-index 1201), silently swallowing every
    // tap. Always close it first so filter options are guaranteed clickable.
    document.getElementById('lpOverlay')?.classList.remove('show');
    document.getElementById('dhFilterBackdrop').classList.add('open'); document.getElementById('dhFilterPanel').classList.add('open'); document.body.style.overflow = 'hidden'; }
function closeFilters(){ document.getElementById('dhFilterBackdrop').classList.remove('open'); document.getElementById('dhFilterPanel').classList.remove('open'); document.body.style.overflow = ''; }
window.openFilters = openFilters; window.closeFilters = closeFilters;
function applyFilters(){
    filters.keyword     = document.getElementById('fKeyword').value.trim();
    filters.locality_id = document.getElementById('fLocality').value;
    filters.sort        = document.getElementById('fSort').value;
    filters.subcategory_id = '';
    filters.page = 1;
    document.getElementById('keywordInput').value = filters.keyword;
    $('.dh-sort-pill').removeClass('active');
    $('.dh-sort-pill[data-sort="'+ filters.sort +'"]').addClass('active');
    setLocUI(filters.locality_id, document.querySelector('#fLocality option:checked')?.textContent.trim() || '', true);
    refreshSubcats(filters.category_id);
    closeFilters();
    loadPosts(true);
}
function resetFilters(){
    filters.keyword = ''; filters.category_id = ''; filters.subcategory_id = '';
    filters.locality_id = ''; filters.sort = 'latest'; filters.page = 1;
    document.getElementById('fKeyword').value = '';
    document.getElementById('keywordInput').value = '';
    document.getElementById('fLocality').value = '';
    document.getElementById('fSort').value = 'latest';
    $('.dh-chip[data-filter="category_id"]').removeClass('active');
    $('.dh-chip[data-filter="category_id"][data-val=""]').addClass('active');
    $('.dh-sort-pill').removeClass('active');
    $('.dh-sort-pill[data-sort="latest"]').addClass('active');
    setLocUI('', '', true);
    refreshSubcats('');
    closeFilters();
    loadPosts(true);
}
window.applyFilters = applyFilters; window.resetFilters = resetFilters;

/* ── Infinite scroll ── */
window.addEventListener('scroll', function(){
    if(isLoading) return;
    if(document.body.offsetHeight - window.scrollY - window.innerHeight > 360) return;
    const next = document.getElementById('next-page-url').value;
    if(!next) return;
    loadPosts(false, next);
}, {passive:true});

/* ── Grid / List toggle ── */
(function(){
    const KEY = 'dh_view_mode';
    const $grid = $('#post-wrapper'), $g = $('#btnGrid'), $l = $('#btnList');
    function setView(mode){
        if(mode === 'list'){ $grid.addClass('list-view'); $l.addClass('active'); $g.removeClass('active'); }
        else { $grid.removeClass('list-view'); $g.addClass('active'); $l.removeClass('active'); }
        try{ localStorage.setItem(KEY, mode); }catch(e){}
    }
    try{ setView(localStorage.getItem(KEY) || 'grid'); }catch(e){ setView('grid'); }
    $g.on('click', () => setView('grid'));
    $l.on('click', () => setView('list'));
})();

/* ── Like ── */
$(document).on('click', '.likeBtn', function(e){
    e.preventDefault(); e.stopPropagation();
    const btn = $(this), id = btn.data('id');
    $.post('/posts/' + id + '/toggle-like', {_token:CSRF}, function(res){
        $('.likeBtn[data-id="'+ id +'"]').toggleClass('liked', res.liked);
    });
});

/* ── Share ── */
$(document).on('click', '.shareBtn', async function(e){
    e.preventDefault(); e.stopPropagation();
    const id = $(this).data('id');
    let url = $(this).data('url');
    try{
        const res = await fetch('{{ route("shorten") }}', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
            body: JSON.stringify({url}),
        });
        const data = await res.json();
        if(res.ok && data.short_url) url = data.short_url;
    }catch(e){}
    navigator.share ? navigator.share({url}) : (navigator.clipboard.writeText(url), alert('Link copied!'));
    $.post('/posts/' + id + '/share', {_token:CSRF, platform:'web'});
});

/* NAV / FOOTER / PWA install / service worker — shared, see frontend.partials.nav */
</script>

{{-- ── Structured data ── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'WebSite',
    'name' => $siteName, 'description' => $richDesc, 'url' => $siteUrl, 'logo' => $ogImage,
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => ['@type' => 'EntryPoint', 'urlTemplate' => route('posts.listing') . '?keyword={search_term_string}'],
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'ItemList',
    'name' => 'Deal Categories on ' . $siteName, 'numberOfItems' => $categories->count(),
    'itemListElement' => $categories->map(fn($cat, $i) => [
        '@type' => 'ListItem', 'position' => $i + 1, 'name' => $cat->name,
        'url' => route('posts.listing', ['category_id' => $cat->slug]),
    ])->values()->all(),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

@include('frontend.location-popup', ['localities' => $localities])
</body>
</html>
