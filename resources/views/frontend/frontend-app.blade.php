<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ site_favicon_url() }}">
    <link rel="shortcut icon" href="{{ site_favicon_url() }}">
    @php
    /* ── Site settings ──────────────────────────────── */
    $siteName    = setting('site_name', 'DealsHood');
    $siteTagline = setting('site_tagline', 'Discover the Best Deals Near You');
    $siteDesc    = setting('site_description',
        'Find the best local deals, offers and classifieds near you. Browse by category or locality.');
    $siteUrl     = url('/');
    $ogUrl       = url()->current();

    /* ── OG image ───────────────────────────────────── */
    $ogImage = site_og_image_url();

    /* ── Dynamic keywords from categories + localities ─ */
    $catNames = $categories->pluck('name')->take(10)->implode(', ');
    $locNames = $localities->pluck('name')->take(8)->implode(', ');
    $keywords = trim($catNames . ($locNames ? ', ' . $locNames : ''))
        . ', deals, offers, classifieds, local deals';

    /* ── Dynamic description enriched with top data ─── */
    $topCats  = $categories->take(4)->pluck('name')->implode(', ');
    $topLocs  = $localities->take(4)->pluck('name')->implode(', ');
    $richDesc = $siteDesc;
    if ($topCats) $richDesc .= ' Categories include ' . $topCats . '.';
    if ($topLocs) $richDesc .= ' Available in ' . $topLocs . ' and more.';

    /* ── Canonical & alternates ─────────────────────── */
    $canonical = $siteUrl;

    $heroBannerUrl = !empty(setting('banner_image'))
        ? Storage::url(setting('banner_image'))
        : '/frontend/img/illustrations/IMG_4871.png';

    /* ── Primary district name for eyebrow copy ─────── */
    $nearYou = optional($localities->firstWhere('type','district'))->name
             ?? optional($localities->first())->name
             ?? 'your area';
    @endphp

    <title>{{ $siteName }} — {{ $siteTagline }}</title>

    {{-- ── Core SEO ─────────────────────────────────────── --}}
    <meta name="description"        content="{{ Str::limit($richDesc, 160) }}">
    <meta name="keywords"           content="{{ $keywords }}">
    <meta name="robots"             content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author"             content="{{ $siteName }}">
    <link rel="canonical"           href="{{ $canonical }}">

    {{-- ── Open Graph ──────────────────────────────────── --}}
    <meta property="og:site_name"   content="{{ $siteName }}">
    <meta property="og:type"        content="website">
    <meta property="og:locale"      content="en_US">
    <meta property="og:title"       content="{{ $siteName }} — {{ $siteTagline }}">
    <meta property="og:description" content="{{ Str::limit($richDesc, 200) }}">
    <meta property="og:url"         content="{{ $canonical }}">
    <meta property="og:image"               content="{{ $ogImage }}">
    <meta property="og:image:secure_url"    content="{{ $ogImage }}">
    <meta property="og:image:type"          content="image/png">
    <meta property="og:image:width"         content="1200">
    <meta property="og:image:height"        content="630">
    <meta property="og:image:alt"           content="{{ $siteName }} — {{ $siteTagline }}">

    {{-- ── Twitter / X Card ────────────────────────────── --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $siteName }} — {{ $siteTagline }}">
    <meta name="twitter:description" content="{{ Str::limit($richDesc, 160) }}">
    <meta name="twitter:image"       content="{{ $ogImage }}">
    <meta name="twitter:image:alt"   content="{{ $siteName }} — {{ $siteTagline }}">

    {{-- ── PWA / Mobile ────────────────────────────────── --}}
    <link rel="manifest"                        href="/manifest.json">
    <meta name="theme-color"                    content="#0a2a68">
    <meta name="mobile-web-app-capable"         content="yes">
    <meta name="apple-mobile-web-app-capable"   content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title"     content="{{ $siteName }}">
    <link rel="apple-touch-icon"                href="/frontend/img/icons/icon-192x192.png">

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
    body{ font-family:var(--font); background:var(--bg); color:var(--ink); margin:0;
          -webkit-font-smoothing:antialiased; }
    a{ text-decoration:none; }
    img{ max-width:100%; }
    .wrap{ max-width:1240px; margin:0 auto; padding:0 24px; }

    .nd-eyebrow{ font-size:.72rem; font-weight:600; letter-spacing:.22em;
                 text-transform:uppercase; color:var(--navy); text-align:center; }
    .nd-h2{ font-family:var(--font); font-size:clamp(1.9rem,3.4vw,2.9rem); font-weight:700;
            color:var(--navy); text-align:center; margin:10px 0 12px; letter-spacing:-.01em; }
    .nd-sub{ text-align:center; color:var(--blue); font-size:1rem; font-weight:400;
             max-width:640px; margin:0 auto; opacity:.9; }
    .nd-sec{ padding:72px 0; }

    /* NAVBAR / FOOTER — shared, see /frontend/css/dh-header-footer.css */

    /* ══════════ HERO ══════════ */
    .dh-hero{ position:relative; overflow:hidden; min-height:760px;
              display:flex; flex-direction:column; align-items:center; justify-content:center;
              padding:114px 0 70px; background:var(--navy-deep); }
    .dh-hero-bg{ position:absolute; inset:0; background-size:cover; background-position:center;
                 will-change:transform; }
    .dh-hero-overlay{ position:absolute; inset:0; z-index:1;
        background:linear-gradient(180deg,rgba(7,30,77,.62) 0%,rgba(7,30,77,.35) 40%,rgba(7,30,77,.72) 100%); }
    .dh-hero-inner{ position:relative; z-index:3; width:100%; max-width:900px; padding:0 24px;
                    text-align:center; animation:fadeUp .6s .1s both; }
    .dh-hero-badge{ display:inline-flex; align-items:center; gap:8px; color:#fff; font-size:.78rem;
                    font-weight:500; background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.28);
                    border-radius:100px; padding:8px 18px; margin-bottom:22px; backdrop-filter:blur(6px); }
    .dh-hero-badge i{ color:#ffd34d; font-size:.7rem; }
    .dh-hero-title{ font-size:clamp(2.4rem,6vw,4.6rem); font-weight:700; color:#fff;
                    line-height:1.06; letter-spacing:-.02em; margin:0 0 20px; }
    .dh-hero-lead{ font-size:clamp(1rem,1.6vw,1.18rem); color:rgba(255,255,255,.86); font-weight:300;
                   max-width:620px; margin:0 auto 30px; line-height:1.6; }
    .dh-hero-search{ display:flex; align-items:center; max-width:560px; margin:0 auto 22px;
                     background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.3);
                     border-radius:100px; padding:6px 6px 6px 22px; backdrop-filter:blur(8px); }
    .dh-hero-search i.fa-magnifying-glass{ color:rgba(255,255,255,.8); font-size:.9rem; }
    .dh-hero-search input{ flex:1; background:transparent; border:none; outline:none; color:#fff;
                           font-family:var(--font); font-size:.95rem; padding:12px 14px; }
    .dh-hero-search input::placeholder{ color:rgba(255,255,255,.7); }
    .dh-hero-search .hs-loc{ width:44px; height:44px; border-radius:50%; background:#fff; color:var(--navy);
                             border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; }
    .dh-hero-cta{ display:flex; gap:14px; justify-content:center; flex-wrap:wrap; margin-bottom:44px; }
    .dh-btn-explore{ display:inline-flex; align-items:center; gap:10px; background:#fff; color:var(--navy);
                     font-weight:600; font-size:.9rem; border-radius:100px; padding:14px 30px; transition:transform .15s; }
    .dh-btn-explore:hover{ transform:translateY(-2px); color:var(--navy); }
    .dh-btn-cats{ display:inline-flex; align-items:center; gap:10px; color:#fff; font-weight:600;
                  font-size:.9rem; border:1.5px solid rgba(255,255,255,.6); border-radius:100px;
                  padding:14px 30px; transition:all .15s; }
    .dh-btn-cats:hover{ background:rgba(255,255,255,.14); color:#fff; }
    .dh-hero-stats{ display:grid; grid-template-columns:repeat(3,1fr); gap:16px; max-width:660px; margin:0 auto; }
    .dh-stat{ border:1px solid rgba(255,255,255,.28); border-radius:var(--r); padding:18px 12px;
              text-align:center; background:rgba(255,255,255,.06); backdrop-filter:blur(4px); }
    .dh-stat-num{ font-size:1.5rem; font-weight:700; color:#fff; line-height:1; }
    .dh-stat-lbl{ font-size:.64rem; font-weight:500; letter-spacing:.12em; text-transform:uppercase;
                  color:rgba(255,255,255,.7); margin-top:6px; }

    /* ══════════ SHOP BY CATEGORIES ══════════ */
    .dh-cat-grid{ display:grid; grid-template-columns:1fr 1fr; gap:22px; margin-top:44px; }
    .dh-cat-chip{ position:relative; overflow:hidden; display:flex; flex-direction:column;
                  justify-content:space-between; min-height:210px; padding:30px 30px 24px;
                  border-radius:var(--r-lg); color:#fff;
                  background:linear-gradient(135deg,var(--blue-2),var(--navy));
                  box-shadow:var(--sh-md); transition:transform .2s, box-shadow .2s; }
    .dh-cat-chip:nth-child(4n+2){ background:linear-gradient(135deg,#1f57d6,#0a2a68); }
    .dh-cat-chip:nth-child(4n+3){ background:linear-gradient(135deg,#2148b8,#08214f); }
    .dh-cat-chip:nth-child(4n){ background:linear-gradient(135deg,#173fa6,#0a2a68); }
    .dh-cat-chip:hover{ transform:translateY(-4px); box-shadow:var(--sh-lg); color:#fff; }
    .dh-cat-chip::after{ content:''; position:absolute; right:-60px; top:-60px; width:220px; height:220px;
                         border-radius:50%; background:radial-gradient(circle at 40% 40%,rgba(255,255,255,.18),transparent 70%);
                         pointer-events:none; }
    .cat-head{ position:relative; z-index:1; }
    /* 3D category illustration — no badge circle, the artwork carries its own shadow */
    .cat-illus{ position:absolute; right:14px; bottom:0; width:160px; height:160px; z-index:1;
                display:flex; align-items:flex-end; justify-content:center;
                pointer-events:none; transition:transform .25s; }
    .cat-illus img{ max-width:100%; max-height:100%; object-fit:contain;
                     filter:drop-shadow(0 10px 18px rgba(0,0,0,.28));
                     animation:catFloat 3.6s ease-in-out infinite; transform-origin:50% 85%; }
    .dh-cat-chip:hover .cat-illus{ transform:scale(1.06) rotate(-4deg); }
    /* stagger so the 4 illustrations don't bob in sync */
    .dh-cat-chip:nth-child(4n+2) .cat-illus img{ animation-delay:.5s; }
    .dh-cat-chip:nth-child(4n+3) .cat-illus img{ animation-delay:1s; }
    .dh-cat-chip:nth-child(4n) .cat-illus img{ animation-delay:1.5s; }
    @keyframes catFloat{
        0%,100%{ transform:translateY(0) rotate(-1.5deg); }
        50%    { transform:translateY(-10px) rotate(2deg); }
    }
    @media(prefers-reduced-motion:reduce){
        .cat-illus img{ animation:none; }
    }
    .dh-cat-name{ font-size:1.6rem; font-weight:700; line-height:1.1; position:relative; z-index:1; max-width:60%; }
    .dh-cat-count{ font-size:.9rem; opacity:.82; margin-top:6px; position:relative; z-index:1; }
    .dh-cat-more{ display:inline-flex; align-items:center; gap:10px; font-size:.72rem; font-weight:600;
                  letter-spacing:.12em; text-transform:uppercase; position:relative; z-index:1; }
    .dh-cat-more .cm-ico{ width:26px; height:26px; border-radius:50%; background:var(--green);
                          display:flex; align-items:center; justify-content:center; font-size:.7rem; }
    .ripple-wrap{ position:absolute; inset:0; overflow:hidden; border-radius:inherit; z-index:0; }
    .ripple-circle{ position:absolute; border-radius:50%; background:rgba(255,255,255,.35);
                    width:20px; height:20px; left:50%; top:50%; transform:translate(-50%,-50%) scale(0);
                    animation:ripple .45s ease-out; }
    @keyframes ripple{ to{ transform:translate(-50%,-50%) scale(24); opacity:0; } }

    /* ══════════ SECTION HEAD (reusable) ══════════ */
    .dh-sec-head{ display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; gap:12px; }
    .dh-eyebrow{ font-size:.7rem; font-weight:600; letter-spacing:.18em; text-transform:uppercase;
                 color:var(--blue); margin-bottom:4px; }
    .dh-sec-title{ font-size:1.4rem; font-weight:700; color:var(--navy); margin:0; }
    .dh-view-all{ display:inline-flex; align-items:center; gap:6px; color:var(--blue); font-weight:600;
                  font-size:.85rem; }

    /* ══════════ EVERYTHING LOCAL FEED ══════════ */
    .dh-feed{ background:var(--bg); }
    #carouselContent{ display:flex; flex-direction:column; gap:34px; }
    .dh-carousel-block{ border:1px solid var(--line); border-radius:var(--r-lg); padding:26px 26px 30px;
                        box-shadow:var(--sh-sm); background:#fff; }
    .dh-carousel-head{ display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; gap:12px; }
    .dh-carousel-title{ display:flex; align-items:center; gap:14px; margin:0; font-size:1.35rem;
                        font-weight:700; color:var(--navy); }
    .dh-cat-badge-icon,.dh-carousel-title>span:first-child{ width:44px; height:44px; border-radius:12px;
                        display:flex; align-items:center; justify-content:center; font-size:1rem;
                        background:var(--navy); color:#fff; flex-shrink:0; }
    .dh-carousel-title small{ display:block; font-size:.8rem; font-weight:400; color:var(--muted); margin-top:2px; }
    .dh-carousel-controls{ display:flex; align-items:center; gap:10px; }
    .dh-c-btn{ width:42px; height:42px; border-radius:50%; border:1px solid var(--line); background:#fff;
               color:var(--navy); cursor:pointer; display:flex; align-items:center; justify-content:center;
               transition:all .15s; }
    .dh-c-btn:hover{ background:var(--navy); color:#fff; border-color:var(--navy); }
    .dh-track-outer{ position:relative; }
    .dh-track{ display:flex; gap:18px; overflow-x:auto; scroll-snap-type:x mandatory;
               scrollbar-width:none; -webkit-overflow-scrolling:touch; padding-bottom:4px; }
    .dh-track::-webkit-scrollbar{ display:none; }
    .dh-track .dh-card{ scroll-snap-align:start; }

    /* ══════════ POST CARD (shared partial) ══════════ */
    .dh-card{ flex:0 0 290px; width:290px; background:#fff; border:1px solid var(--line);
              border-radius:var(--r); overflow:hidden; display:flex; flex-direction:column;
              transition:transform .18s, box-shadow .18s; }
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
    .dh-card-badge.free{ background:var(--green); }
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
    .badge-feat,.badge-offer{ position:absolute; z-index:2; font-size:.62rem; font-weight:700;
                  padding:5px 10px; border-radius:6px; }
    .badge-feat{ right:52px; top:10px; background:#fff; color:var(--navy); }
    .badge-offer{ left:10px; top:10px; background:var(--orange); color:#fff; }

    /* ══════════ LATEST GRID ══════════ */
    .dh-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:22px; }
    .dh-empty{ grid-column:1/-1; text-align:center; color:var(--muted); padding:50px 0; }
    .dh-show-more{ text-align:center; margin-top:38px; }
    .dh-more-btn{ display:inline-flex; align-items:center; gap:10px; background:var(--navy); color:#fff;
                  font-weight:600; font-size:.9rem; border:none; border-radius:100px; padding:14px 34px;
                  cursor:pointer; font-family:var(--font); transition:transform .15s; }
    .dh-more-btn:hover{ transform:translateY(-2px); color:#fff; }

    /* ══════════ TRENDING (navy) ══════════ */
    .dh-trend{ background:var(--navy); color:#fff; padding:76px 0; }
    .dh-trend .nd-eyebrow{ color:#fff; }
    .dh-trend .nd-h2{ color:#fff; }
    .dh-trend .nd-sub{ color:rgba(255,255,255,.75); }
    .dh-trend-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px; margin-top:44px; }
    .dh-trend-card{ background:#fff; border-radius:var(--r); overflow:hidden; color:var(--ink);
                    display:flex; flex-direction:column; }
    .dh-trend-img{ aspect-ratio:16/10; background:#0b1e42; overflow:hidden; }
    .dh-trend-img img{ width:100%; height:100%; object-fit:cover; }
    .dh-trend-body{ padding:18px 20px 20px; display:flex; flex-direction:column; flex:1; }
    .dh-trend-biz{ display:flex; align-items:center; gap:7px; color:var(--blue); font-size:.78rem; font-weight:600; margin-bottom:6px; }
    .dh-trend-title{ font-size:.98rem; font-weight:600; color:var(--ink); line-height:1.4; margin-bottom:12px; }
    .dh-trend-price{ display:flex; align-items:baseline; gap:8px; margin-bottom:10px; }
    .dh-trend-off{ font-size:1.35rem; font-weight:700; color:var(--navy); }
    .dh-trend-ends{ display:flex; align-items:center; gap:6px; font-size:.78rem; color:var(--muted); margin-bottom:14px; }
    .dh-trend-btn{ display:inline-flex; align-items:center; justify-content:center; gap:8px; margin-top:auto;
                   background:var(--navy); color:#fff; font-weight:600; font-size:.85rem; border-radius:10px; padding:12px; }
    .dh-trend-btn:hover{ background:var(--navy-deep); color:#fff; }
    .dh-trend-all{ display:flex; justify-content:center; margin-top:40px; }
    .dh-trend-all a{ border:1.5px solid rgba(255,255,255,.5); color:#fff; border-radius:100px;
                     padding:13px 34px; font-weight:600; font-size:.88rem; }
    .dh-trend-all a:hover{ background:#fff; color:var(--navy); }

    /* ══════════ FLASH SALE ══════════ */
    .dh-flash{ background:var(--navy); padding:0 0 76px; }
    .dh-flash-card{ display:grid; grid-template-columns:1fr 1fr; background:#fff; border-radius:var(--r-lg);
                    overflow:hidden; box-shadow:var(--sh-lg); }
    .dh-flash-left{ padding:48px 44px; }
    .dh-flash-tag{ display:inline-flex; align-items:center; gap:8px; font-size:.72rem; font-weight:600;
                   letter-spacing:.14em; text-transform:uppercase; color:var(--orange); margin-bottom:16px; }
    .dh-flash-h{ font-size:clamp(2rem,3.6vw,3rem); font-weight:700; color:var(--navy); line-height:1.08; margin:0 0 12px; }
    .dh-flash-p{ color:var(--blue); font-size:.95rem; max-width:360px; margin:0 0 26px; }
    .dh-count{ display:flex; gap:26px; margin-bottom:28px; }
    .dh-count-unit .cu-num{ font-size:2.4rem; font-weight:700; color:var(--orange); line-height:1; }
    .dh-count-unit .cu-lbl{ font-size:.66rem; font-weight:600; letter-spacing:.14em; text-transform:uppercase;
                            color:var(--muted); margin-top:6px; }
    .dh-flash-btn{ display:inline-flex; align-items:center; gap:10px; background:var(--navy); color:#fff;
                   font-weight:600; border-radius:100px; padding:14px 30px; font-size:.9rem; }
    .dh-flash-btn:hover{ color:#fff; background:var(--navy-deep); }
    .dh-flash-right{ background:#e11d48 center/cover; min-height:320px; position:relative;
                     display:flex; align-items:center; justify-content:center; color:#fff; text-align:center; }
    .dh-flash-right .fr-inner{ padding:30px; }
    .dh-flash-right .fr-big{ font-size:clamp(3rem,6vw,5.5rem); font-weight:800; line-height:.95;
                             text-shadow:0 4px 0 rgba(0,0,0,.15); }

    /* ══════════ WHY CHOOSE ══════════ */
    .dh-why-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:22px; margin-top:44px; }
    .dh-why-card{ background:linear-gradient(150deg,var(--blue-2),var(--navy)); color:#fff;
                  border-radius:var(--r-lg); padding:30px 26px; min-height:200px; box-shadow:var(--sh-md); }
    .dh-why-ic{ width:46px; height:46px; border-radius:12px; background:rgba(255,255,255,.16);
                display:flex; align-items:center; justify-content:center; font-size:1.1rem; margin-bottom:20px; }
    .dh-why-h{ font-size:1.12rem; font-weight:700; margin:0 0 8px; }
    .dh-why-p{ font-size:.85rem; color:rgba(255,255,255,.82); line-height:1.55; margin:0; }

    /* ══════════ PROMO STRIP ══════════ */
    .dh-promo-strip{ display:grid; grid-template-columns:repeat(3,1fr); gap:0; }
    .dh-promo-tile{ position:relative; aspect-ratio:4/3; overflow:hidden; display:block; background:#0b1e42; }
    .dh-promo-tile img{ width:100%; height:100%; object-fit:cover; transition:transform .3s; }
    .dh-promo-tile:hover img{ transform:scale(1.05); }
    .dh-promo-tile .pt-label{ position:absolute; left:0; right:0; bottom:0; padding:26px 20px 22px;
        background:linear-gradient(0deg,rgba(0,0,0,.55),transparent); color:#fff; font-size:.9rem; font-weight:600; }

    /* ══════════ FAQ ══════════ */
    .dh-faq{ max-width:820px; margin:44px auto 0; display:flex; flex-direction:column; gap:14px; }
    .dh-faq-item{ border:1px solid var(--line); border-radius:var(--r); overflow:hidden; background:#fff; }
    .dh-faq-q{ display:flex; align-items:center; justify-content:space-between; gap:14px; width:100%;
               background:none; border:none; cursor:pointer; padding:22px 26px; text-align:left;
               font-family:var(--font); font-size:1rem; font-weight:600; color:var(--navy); }
    .dh-faq-q i{ transition:transform .2s; color:var(--blue); }
    .dh-faq-item.open .dh-faq-q i{ transform:rotate(180deg); }
    .dh-faq-a{ max-height:0; overflow:hidden; transition:max-height .28s ease; }
    .dh-faq-a p{ padding:0 26px 22px; margin:0; color:var(--muted); font-size:.92rem; line-height:1.65; }

    /* FOOTER / PWA banner — shared, see /frontend/css/dh-header-footer.css */

    /* ══════════ SPINNER (AJAX reload) ══════════ */
    .sec-spinner{ display:flex; gap:8px; justify-content:center; padding:44px 0; }
    .sec-spinner span{ width:12px; height:12px; border-radius:50%; background:var(--blue); opacity:.5;
                       animation:bounce .8s infinite; }
    .sec-spinner span:nth-child(2){ animation-delay:.15s; } .sec-spinner span:nth-child(3){ animation-delay:.3s; }
    @keyframes bounce{ 0%,80%,100%{ transform:scale(.6); opacity:.4; } 40%{ transform:scale(1); opacity:1; } }
    @keyframes fadeUp{ from{ opacity:0; transform:translateY(24px); } to{ opacity:1; transform:none; } }

    /* ══════════ RESPONSIVE ══════════ */
    @media(max-width:1024px){
        .dh-grid{ grid-template-columns:repeat(3,1fr); }
        .dh-trend-grid{ grid-template-columns:repeat(2,1fr); }
        .dh-why-grid{ grid-template-columns:repeat(2,1fr); }
    }
    @media(max-width:900px){
        .dh-nav-links,.dh-nav-search,.dh-btn-signin{ display:none; }
        .dh-nav-toggle{ display:flex; }
        .dh-nav-actions.mobile-hidden{ }
    }
    @media(max-width:768px){
        .nd-sec{ padding:52px 0; }
        .dh-hero{ min-height:auto; padding:98px 0 48px; }
        .dh-cat-grid{ gap:14px; }
        .dh-cat-chip{ min-height:150px; padding:20px; }
        .dh-cat-name{ font-size:1.15rem; max-width:70%; }
        .cat-illus{ width:100px; height:100px; right:10px; bottom:0; }
        .dh-grid{ grid-template-columns:1fr 1fr; gap:14px; }
        .dh-trend-grid{ grid-template-columns:1fr 1fr; gap:14px; }
        .dh-why-grid{ grid-template-columns:1fr 1fr; gap:14px; }
        .dh-flash-card{ grid-template-columns:1fr; } .dh-flash-right{ order:-1; min-height:200px; }
        .dh-flash-left{ padding:32px 26px; }
        .dh-promo-strip{ grid-template-columns:1fr; }
        .dh-footer-grid{ grid-template-columns:1fr 1fr; gap:28px; }
        /* size carousel cards to the track so the next card clearly peeks in */
        .dh-card{ flex-basis:80%; width:80%; }
        .dh-carousel-block{ padding:20px 16px 24px; }
    }
    @media(max-width:480px){
        .dh-hero-stats{ gap:10px; } .dh-stat{ padding:14px 6px; } .dh-stat-num{ font-size:1.2rem; }
        .dh-grid{ grid-template-columns:1fr; }
        .dh-trend-grid,.dh-why-grid,.dh-footer-grid{ grid-template-columns:1fr; }
        .dh-count{ gap:16px; } .dh-count-unit .cu-num{ font-size:1.8rem; }
    }
    </style>
</head>
<body>

@include('frontend.partials.nav', ['categories' => $categories, 'activeNav' => 'home', 'transparent' => true])

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

/* 3D illustrations for the "Shop by Categories" tiles — cycles by
   category position, same as $palette. */
$catImages = [
    asset('frontend/img/categories/cat-1.png'),
    asset('frontend/img/categories/cat-2.png'),
    asset('frontend/img/categories/cat-3.png'),
    asset('frontend/img/categories/cat-4.png'),
];
@endphp

{{-- ═══════════════════════ HERO ═══════════════════════ --}}
<header class="dh-hero">
    <div class="dh-hero-bg" id="heroBg" style="background-image:url('{{ $heroBannerUrl }}');"></div>
    <div class="dh-hero-overlay"></div>

    <div class="dh-hero-inner">
        {{-- HIDDEN: "Over N+ Live Offers Today" badge --}}
        {{-- <span class="dh-hero-badge"><i class="fas fa-bolt"></i> Over {{ max(20, $categories->sum('posts_count')) }}+ Live Offers Today</span> --}}
        <h1 class="dh-hero-title">Discover the Best Deals Around You</h1>
        <p class="dh-hero-lead">Never miss a discount. Find verified promo codes for fashion, electronics, food, travel, and more.</p>

        <form class="dh-hero-search" action="{{ route('posts.listing') }}" method="GET">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" name="keyword" placeholder="Search deals, stores, brands">
            {{-- HIDDEN: location picker button in the search box --}}
            {{-- <button class="hs-loc" type="button" title="Choose your area"
                    onclick="window.openLocationPopup && window.openLocationPopup()">
                <i class="fas fa-location-dot"></i>
            </button> --}}
        </form>

        <div class="dh-hero-cta">
            {{-- <a href="{{ route('posts.listing') }}" class="dh-btn-explore">
                Explore Deals
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a> --}}
            <a href="#categories" class="dh-btn-cats">View Categories</a>
        </div>

        <div class="dh-hero-stats">
            <div class="dh-stat">
                <div class="dh-stat-num">{{ max(1, $localities->count()) }}+</div>
                <div class="dh-stat-lbl">Localities</div>
            </div>
            <div class="dh-stat">
                <div class="dh-stat-num">{{ max(1, $categories->sum('posts_count')) }}+</div>
                <div class="dh-stat-lbl">Local Businesses</div>
            </div>
            <div class="dh-stat">
                <div class="dh-stat-num">{{ max(1, $subcategoriesCount ?? 0) }}+</div>
                <div class="dh-stat-lbl">Subcategories</div>
            </div>
        </div>
    </div>
</header>

{{-- ═══════════════════════ SHOP BY CATEGORIES ═══════════════════════ --}}
<section class="nd-sec" id="categories">
    <div class="wrap">
        <p class="nd-eyebrow">Explore</p>
        <h2 class="nd-h2">Shop by Categories</h2>
        <p class="nd-sub">Handpicked deals across every category you love.</p>

        <div class="dh-cat-grid" id="catGrid">
            @foreach ($categories as $i => $cat)
                @php $catImg = $catImages[$i % count($catImages)]; @endphp
                <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}"
                   class="dh-cat-chip" data-base="{{ route('posts.listing', ['category_id' => $cat->slug]) }}">
                    <div class="ripple-wrap"></div>
                    <div class="cat-illus"><img src="{{ $catImg }}" alt="{{ $cat->name }}" loading="lazy"></div>
                    <div class="cat-head">
                        <div class="dh-cat-name">{{ $cat->name }}</div>
                        <div class="dh-cat-count">{{ number_format($cat->posts_count) }}+ listings</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════ EVERYTHING LOCAL FEED ═══════════════════════ --}}
<section class="nd-sec dh-feed" id="feed" style="padding-top:0;">
    <div class="wrap">
        <p class="nd-eyebrow">Near you in {{ $nearYou }}</p>
        <h2 class="nd-h2">Everything Local, In One Clean Feed</h2>
        <p class="nd-sub">Offers worth grabbing, services worth booking and deliveries that reach your door.</p>

        <div id="carouselContent" style="margin-top:44px;">
            @foreach ($categoryCarousels as $i => $cat)
                @if ($cat->posts->isNotEmpty())
                    @php $p = $palette[$i % count($palette)]; @endphp
                    <div class="dh-carousel-block">
                        <div class="dh-carousel-head">
                            <h3 class="dh-carousel-title">
                                <span><i class="fas {{ $p['icon'] }}"></i></span>
                                <span>
                                    {{ $cat->name }}
                                    {{-- <small>Limited-time offers from businesses near you</small> --}}
                                </span>
                            </h3>
                            <div class="dh-carousel-controls">
                                <a href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}" class="dh-view-all me-1">See all</a>
                                <button class="dh-c-btn c-prev" data-target="cr-{{ $cat->id }}" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
                                <button class="dh-c-btn c-next" data-target="cr-{{ $cat->id }}" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
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

{{-- ═══════════════════════ TODAY'S TRENDING DEALS ═══════════════════════ --}}
{{-- HIDDEN: "Hot right now" (Trending Deals) + "Special offer" (Flash Sale).
     To restore, change @if(false) back to @if($trending->isNotEmpty()) --}}
@if(false)
<section class="dh-trend" id="trending">
    <div class="wrap">
        <p class="nd-eyebrow">Hot right now</p>
        <h2 class="nd-h2">Today's Trending Deals</h2>
        <p class="nd-sub">Curated by our team. Updated every hour.</p>

        <div class="dh-trend-grid">
            @foreach($trending as $t)
                @php $timg = $t->getFirstMediaUrl('posts') ?: asset('frontend/img/default.jpg'); @endphp
                <div class="dh-trend-card">
                    <a href="{{ $t->url }}" class="dh-trend-img"><img src="{{ $timg }}" alt="{{ $t->title }}" loading="lazy"></a>
                    <div class="dh-trend-body">
                        @if($t->company_name)<div class="dh-trend-biz"><i class="fas fa-store"></i> {{ $t->company_name }}</div>@endif
                        <div class="dh-trend-title">{{ Str::limit($t->title, 70) }}</div>
                        @if($t->offer_percentage)
                            <div class="dh-trend-price"><span class="dh-trend-off">{{ $t->offer_percentage }}</span></div>
                        @endif
                        <div class="dh-trend-ends">
                            <i class="far fa-clock"></i>
                            @if($t->expiry_date && !\Carbon\Carbon::parse($t->expiry_date)->isPast())
                                Ends {{ \Carbon\Carbon::parse($t->expiry_date)->diffForHumans(['parts'=>1]) }}
                            @else
                                Limited time offer
                            @endif
                        </div>
                        <a href="{{ $t->url }}" class="dh-trend-btn">View Deal
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="dh-trend-all"><a href="{{ route('posts.listing', ['sort'=>'trending']) }}">View all deals</a></div>
    </div>
</section>

{{-- ═══════════════════════ FLASH SALE ═══════════════════════ --}}
<section class="dh-flash">
    <div class="wrap">
        <div class="dh-flash-card">
            <div class="dh-flash-left">
                <div class="dh-flash-tag"><i class="fas fa-bolt"></i> Flash Sale — Limited Time</div>
                <h2 class="dh-flash-h">Up to 50% on top brands</h2>
                <p class="dh-flash-p">Blink and you'll miss it. Our biggest flash sale of the week ends soon.</p>
                <div class="dh-count" id="flashCountdown">
                    <div class="dh-count-unit"><div class="cu-num" data-h>05</div><div class="cu-lbl">Hours</div></div>
                    <div class="dh-count-unit"><div class="cu-num" data-m>42</div><div class="cu-lbl">Minutes</div></div>
                    <div class="dh-count-unit"><div class="cu-num" data-s>10</div><div class="cu-lbl">Seconds</div></div>
                </div>
                <a href="{{ route('posts.listing', ['sort'=>'trending']) }}" class="dh-flash-btn">Shop the Sale
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
            <div class="dh-flash-right">
                <div class="fr-inner">
                    <div style="font-size:.9rem;font-weight:700;letter-spacing:.1em;">SPECIAL OFFER</div>
                    <div class="fr-big">50%</div>
                    <div style="font-weight:700;">LIMITED TIME ONLY</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════ WHY CHOOSE ═══════════════════════ --}}
{{-- HIDDEN: "Our Promise" (Why Choose) section. To restore, remove this @if(false)/@endif wrapper --}}
@if(false)
<section class="nd-sec">
    <div class="wrap">
        <p class="nd-eyebrow">Our Promise</p>
        <h2 class="nd-h2">Why Choose {{ $siteName }}</h2>
        <p class="nd-sub">Built for shoppers who love a good bargain — without the hassle.</p>
        <div class="dh-why-grid">
            <div class="dh-why-card">
                <div class="dh-why-ic"><i class="fas fa-shield-halved"></i></div>
                <h3 class="dh-why-h">Verified Coupons</h3>
                <p class="dh-why-p">Every code is tested by our team before publishing.</p>
            </div>
            <div class="dh-why-card">
                <div class="dh-why-ic"><i class="fas fa-arrows-rotate"></i></div>
                <h3 class="dh-why-h">Updated Daily</h3>
                <p class="dh-why-p">Fresh deals dropped every single day, all year round.</p>
            </div>
            <div class="dh-why-card">
                <div class="dh-why-ic"><i class="fas fa-wallet"></i></div>
                <h3 class="dh-why-h">Cashback Rewards</h3>
                <p class="dh-why-p">Earn real cash on every eligible purchase.</p>
            </div>
            <div class="dh-why-card">
                <div class="dh-why-ic"><i class="fas fa-award"></i></div>
                <h3 class="dh-why-h">Trusted Brands</h3>
                <p class="dh-why-p">Only official partnerships with top local retailers.</p>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════ PROMO STRIP ═══════════════════════ --}}
{{-- HIDDEN: 3 promo tiles before "Just in". To restore, change @if(false) back to @if($trending->count() >= 3) --}}
@if(false)
<section class="dh-promo-strip">
    @foreach($trending->take(3) as $t)
        @php $pimg = $t->getFirstMediaUrl('posts') ?: asset('frontend/img/default.jpg'); @endphp
        <a href="{{ $t->url }}" class="dh-promo-tile">
            <img src="{{ $pimg }}" alt="{{ $t->title }}" loading="lazy">
            <span class="pt-label">(view offer)</span>
        </a>
    @endforeach
</section>
@endif

{{-- ═══════════════════════ LATEST DEALS ═══════════════════════ --}}
<section class="nd-sec dh-latest-sec">
    <div class="wrap">
        <div class="dh-sec-head">
            <div>
                <div class="dh-eyebrow">Just in</div>
                <h2 class="dh-sec-title" id="latestHeading">Latest Deals</h2>
            </div>
            <a href="{{ route('posts.listing') }}" class="dh-view-all" id="latestViewAll">View all <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="dh-grid" id="postsGrid">
            @forelse ($posts as $post)
                @include('frontend.post-single-card', ['post' => $post])
            @empty
                <div class="dh-empty"><p style="font-size:2rem;opacity:.3;">🔍</p><p>No deals yet — check back soon!</p></div>
            @endforelse
        </div>
        <div class="dh-show-more" id="showMoreWrap">
            <a href="{{ route('posts.listing') }}" class="dh-more-btn">Load More Deals
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════ FAQ ═══════════════════════ --}}
<section class="nd-sec" id="faq" style="background:var(--bg-soft);">
    <div class="wrap">
        <p class="nd-eyebrow">FAQ</p>
        <h2 class="nd-h2">Frequently Asked</h2>
        <p class="nd-sub">Everything you need to know about {{ $siteName }}.</p>
        <div class="dh-faq">
            @php
            $faqs = [
                ['How does '.$siteName.' work?', 'We aggregate the best deals and offers from local brands and businesses. Browse a deal, tap View Details, and contact the business directly.'],
                // ['Are the deals verified?', 'Yes. Every listing is reviewed by our team before it goes live, typically within 24 hours.'],
                ['How do I find deals near me?', 'Use the Location selector to pick your area — the whole feed updates to show offers, services and deliveries around you.'],
                // ['Is '.$siteName.' free to use?', 'Absolutely. Browsing deals and contacting businesses is completely free for shoppers.'],
                ['Can I list my business?', 'Yes — reach out through the Contact page and our team will help you get your offers in front of local shoppers.'],
            ];
            @endphp
            @foreach($faqs as $f)
                <div class="dh-faq-item">
                    <button class="dh-faq-q" type="button">{{ $f[0] }} <i class="fas fa-chevron-down"></i></button>
                    <div class="dh-faq-a"><p>{{ $f[1] }}</p></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════ FOOTER ═══════════════════════ --}}
@include('frontend.partials.footer', ['categories' => $categories])

@include('frontend.frontend-mobile')
@include('frontend.post-ad-modal')

<script src="/frontend/js/core/popper.min.js"></script>
<script src="/frontend/js/core/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
/* ══════════ Vanilla-JS features (independent of jQuery) ══════════ */
/* NAV / FOOTER / PWA install — shared, see frontend.partials.nav / frontend.partials.footer */

var heroBg = document.getElementById('heroBg');
window.addEventListener('scroll', function () {
    if (heroBg) heroBg.style.transform = 'translateY(' + (scrollY * .18) + 'px)';
}, { passive: true });

/* ── Carousel arrows ── */
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.c-prev, .c-next');
    if (!btn) return;
    const track = document.getElementById(btn.dataset.target);
    if (!track) return;
    const card = track.querySelector('.dh-card');
    const cardW = card ? (card.offsetWidth + 18) : 300;
    const dir   = btn.classList.contains('c-prev') ? -1 : 1;
    track.style.scrollBehavior = 'smooth';
    track.scrollBy({ left: dir * cardW * 2 });
    setTimeout(() => track.style.scrollBehavior = '', 500);
});

/* ── FAQ accordion ── */
document.addEventListener('click', function (e) {
    const q = e.target.closest('.dh-faq-q');
    if (!q) return;
    const item = q.closest('.dh-faq-item');
    const ans  = item.querySelector('.dh-faq-a');
    const open = item.classList.toggle('open');
    ans.style.maxHeight = open ? ans.scrollHeight + 'px' : '0';
});

/* ── Flash sale countdown (rolling 6h window) ── */
(function () {
    const box = document.getElementById('flashCountdown');
    if (!box) return;
    const h = box.querySelector('[data-h]'), m = box.querySelector('[data-m]'), s = box.querySelector('[data-s]');
    let end = parseInt(localStorage.getItem('dh_flash_end') || '0', 10);
    if (!end || end < Date.now()) { end = Date.now() + 6 * 3600 * 1000; localStorage.setItem('dh_flash_end', end); }
    function pad(n){ return String(n).padStart(2,'0'); }
    function tick() {
        let diff = Math.max(0, end - Date.now());
        const hh = Math.floor(diff / 3600000); diff -= hh * 3600000;
        const mm = Math.floor(diff / 60000);   diff -= mm * 60000;
        const ss = Math.floor(diff / 1000);
        h.textContent = pad(hh); m.textContent = pad(mm); s.textContent = pad(ss);
        if (hh + mm + ss === 0) { end = Date.now() + 6 * 3600 * 1000; localStorage.setItem('dh_flash_end', end); }
    }
    tick(); setInterval(tick, 1000);
})();

</script>

<script>
/* ══════════ jQuery-powered features ══════════ */
const CSRF        = '{{ csrf_token() }}';
const LISTING_URL = '{{ route("posts.listing") }}';
const HOME_URL    = '{{ route("home") }}';

$(document).on('mouseenter', '.dh-star', function () {
    const val = $(this).data('value');
    $(this).parent().children('.dh-star').each(function () {
        $(this).toggleClass('hover', $(this).data('value') <= val);
    });
});
$(document).on('mouseleave', '.dh-rating-stars', function () {
    $(this).children('.dh-star').removeClass('hover');
});
$(document).on('click', '.dh-star', function () {
    const val    = $(this).data('value');
    const wrap   = $(this).closest('.dh-rating');
    const postId = wrap.data('post-id');
    const current = parseInt(wrap.data('current') || 0);
    if (val === current) {
        $.ajax({ url: '/posts/' + postId + '/rate', type: 'DELETE', data: { _token: CSRF },
            success: function (res) {
                wrap.data('current', 0); wrap.find('.dh-star').removeClass('active');
                wrap.find('.dh-rating-avg').text(res.avg_rating.toFixed(1));
                wrap.find('.dh-rating-count').text('(' + res.total + ' ratings)');
            } });
        return;
    }
    $.ajax({ url: '/posts/' + postId + '/rate', type: 'POST', data: { _token: CSRF, rating: val },
        success: function (res) {
            wrap.data('current', val);
            wrap.find('.dh-star').each(function () { $(this).toggleClass('active', $(this).data('value') <= val); });
            wrap.find('.dh-rating-avg').text(res.avg_rating.toFixed(1));
            wrap.find('.dh-rating-count').text('(' + res.total + ' ratings)');
        } });
});

/* ── Location filter (AJAX) ── */
let activeLocSlug = '';
let activeLocName = '';

function setLocUI(slug, name) {
    activeLocSlug = slug || '';
    activeLocName = name || '';
    document.getElementById('locLabel').textContent = slug ? name : 'Location';
    slug ? document.getElementById('locTrigger').classList.add('has-loc')
         : document.getElementById('locTrigger').classList.remove('has-loc');
    refreshLinks();
}
function clearLoc() {
    setLocUI('', ''); reloadContent();
    try { localStorage.setItem('dh_locality_v1', JSON.stringify({slug:'',name:'All Areas',ts:Date.now()})); } catch(e){}
}
function refreshLinks() {
    document.querySelectorAll('#catGrid .dh-cat-chip[data-base]').forEach(el => {
        let href = el.dataset.base;
        if (activeLocSlug) href += (href.includes('?') ? '&' : '?') + 'locality_id=' + activeLocSlug;
        el.href = href;
    });
    const base = LISTING_URL + (activeLocSlug ? '?locality_id=' + activeLocSlug : '');
    const lvAll = document.getElementById('latestViewAll');
    if (lvAll) lvAll.href = base;
}
function reloadContent() {
    const spinner = '<div class="sec-spinner"><span></span><span></span><span></span></div>';
    $('#carouselContent').html(spinner);
    $('#postsGrid').html('<div style="grid-column:1/-1;">' + spinner + '</div>');
    $('#showMoreWrap').hide();
    $.ajax({
        url: HOME_URL, type: 'GET',
        data: activeLocSlug ? { filter_locality: activeLocSlug } : {},
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (res) {
            $('#carouselContent').html(res.carousel_html ||
                '<p style="text-align:center;color:var(--muted);padding:32px 0;font-size:.9rem;">No popular deals in this area yet.</p>');
            $('#postsGrid').html(res.posts_html || '');
            if (res.next_page) {
                const btn = '<div class="dh-show-more" id="showMoreWrap"><button class="dh-more-btn" id="loadMoreBtn" data-next="'
                    + res.next_page + '">Load More Deals <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button></div>';
                $('#showMoreWrap').length
                    ? $('#showMoreWrap').show().find('#loadMoreBtn').data('next', res.next_page)
                    : $('#postsGrid').after(btn);
            } else { $('#showMoreWrap').hide(); }
            $('#latestHeading').text(activeLocSlug ? activeLocName + ' — Latest Deals' : 'Latest Deals');
            refreshLinks();
        },
        error: function () { $('#carouselContent').html(''); }
    });
}

$(document).on('click', '.likeBtn', function () {
    const btn=$(this), id=btn.data('id');
    $.post('/posts/'+id+'/toggle-like', {_token:CSRF}, function(res){
        $('#lc-'+id).text(res.likes);
        res.liked ? btn.addClass('liked') : btn.removeClass('liked');
    });
});
$(document).on('click', '.shareBtn', async function () {
    const id = $(this).data('id');
    let url = $(this).data('url');
    try {
        const res  = await fetch('{{ route("shorten") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ url }),
        });
        const data = await res.json();
        if (res.ok && data.short_url) url = data.short_url;
    } catch (e) {}
    navigator.share ? navigator.share({url}) : (navigator.clipboard.writeText(url), alert('Link copied!'));
    $.post('/posts/'+id+'/share', {_token:CSRF, platform:'web'});
});
$(document).on('click', '#loadMoreBtn', function () {
    const btn=$(this), next=btn.data('next');
    if (!next) return;
    btn.text('Loading…').prop('disabled',true);
    $.get(next, function(res){
        if (res.html) {
            $('#postsGrid').append(res.html);
            res.next_page
                ? btn.data('next',res.next_page).text('Load More Deals').prop('disabled',false)
                : btn.closest('.dh-show-more').remove();
        }
    }).fail(()=> btn.text('Load More Deals').prop('disabled',false));
});

/* ── Category card ripple ── */
$(document).on('click', '.dh-cat-chip', function(e) {
    const wrap = $(this).find('.ripple-wrap')[0];
    if (wrap) { const r = document.createElement('span'); r.className = 'ripple-circle'; wrap.appendChild(r); setTimeout(() => r.remove(), 450); }
});
</script>

{{-- ── Structured Data: WebSite ── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'WebSite',
    'name'            => $siteName,
    'description'     => $richDesc,
    'url'             => $siteUrl,
    'logo'            => $ogImage,
    'potentialAction' => [
        '@type'       => 'SearchAction',
        'target'      => ['@type' => 'EntryPoint', 'urlTemplate' => url('/listing') . '?keyword={search_term_string}'],
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

{{-- ── Structured Data: LocalBusiness / Organization ── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'  => 'https://schema.org',
    '@type'     => ['Organization', 'LocalBusiness'],
    'name'      => $siteName,
    'url'       => $siteUrl,
    'logo'      => $ogImage,
    'image'     => $ogImage,
    'description' => $siteDesc,
    'sameAs'    => array_filter(['https://www.instagram.com/dealshood','https://www.facebook.com/share/1DA56kRCJp']),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

{{-- ── Structured Data: ItemList of categories ── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'Deal Categories on ' . $siteName,
    'description'     => 'Browse deals by category on ' . $siteName,
    'numberOfItems'   => $categories->count(),
    'itemListElement' => $categories->map(fn($cat, $i) => [
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'name'     => $cat->name,
        'url'      => route('posts.listing', ['category_id' => $cat->slug]),
    ])->values()->all(),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

@if ($localities->count())
<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'Areas Served by ' . $siteName,
    'numberOfItems'   => $localities->count(),
    'itemListElement' => $localities->map(fn($loc, $i) => [
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'name'     => $loc->name,
        'url'      => route('posts.listing', ['locality_id' => $loc->slug]),
    ])->values()->all(),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@include('frontend.location-popup', ['localities' => $localities])
</body>
</html>
