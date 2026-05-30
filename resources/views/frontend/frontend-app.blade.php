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
    <meta property="og:type"      content="website">
    <meta property="og:title"     content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:url"       content="{{ $ogUrl }}">
    <meta property="og:image"     content="{{ $ogImage }}">
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
        --ink:#0d0d0d; --ink-mid:#3a3a3a; --ink-muted:#6b6b6b;
        --surface:#faf9f7; --surface-2:#f2f1ef;
        --white:#ffffff; --accent:#0f3f7e;
        --r:14px; --rlg:20px;
        --sh-sm:0 2px 12px rgba(0,0,0,.07);
        --sh-md:0 6px 32px rgba(0,0,0,.10);
        --sh-lg:0 20px 60px rgba(0,0,0,.15);
        --nav-h:64px;
    }
    *,*::before,*::after{box-sizing:border-box;}
    body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;
         background:var(--surface);color:var(--ink);margin:0;}

    /* ── Navbar ───────────────────────────────────────── */
    .dh-nav{position:fixed;top:0;left:0;right:0;height:var(--nav-h);
            background:#fff;border-bottom:1px solid rgba(0,0,0,.07);
            z-index:1000;display:flex;align-items:center;}
    .dh-nav-inner{display:flex;align-items:center;justify-content:space-between;
                  width:100%;max-width:1180px;margin:0 auto;padding:0 24px;}
    .dh-nav-logo img{height:45px;display:block;}
    .dh-nav-actions{display:flex;align-items:center;gap:10px;}
    .dh-btn-nav{display:inline-flex;align-items:center;gap:6px;font-size:.75rem;font-weight:500;
                letter-spacing:.04em;border:none;cursor:pointer;border-radius:100px;
                padding:9px 18px;text-decoration:none;transition:transform .15s;}
    .dh-btn-nav:hover{transform:translateY(-1px);}
    .dh-btn-ig{background:#e1306c;color:#fff;}
    .dh-btn-wa{background:#25d366;color:#fff;}
    .dh-nav-toggle{display:none;background:none;border:none;cursor:pointer;
                   flex-direction:column;gap:5px;padding:6px;}
    .dh-nav-toggle span{display:block;width:22px;height:2px;background:var(--ink);border-radius:2px;}
    @media(max-width:640px){
        .dh-nav-toggle{display:flex;}
        .dh-nav-actions{display:none;position:absolute;top:var(--nav-h);left:0;right:0;
                        background:#fff;border-bottom:1px solid rgba(0,0,0,.08);
                        padding:16px 24px;flex-direction:column;align-items:flex-start;gap:10px;}
        .dh-nav-actions.open{display:flex;}
    }

    /* ═══════════════════════════════════════════════════
       HERO — with glassmorphism category tiles
    ═══════════════════════════════════════════════════ */
    .dh-hero{
        padding-top:var(--nav-h); position:relative; overflow:hidden;
        background:var(--ink);
        display:flex; flex-direction:column; align-items:center; justify-content:center;
    }
    .dh-hero-bg{position:absolute;inset:0;
                background:url('/frontend/img/office-dark.jpg') center/cover no-repeat;opacity:.42;}
    .dh-hero-overlay{position:absolute;inset:0;
                     background:linear-gradient(160deg,rgba(13,13,13,.78) 0%,rgba(13,13,13,.32) 55%,rgba(15,63,126,.2) 100%);}
    .dh-hero-fade{position:absolute;bottom:0;left:0;right:0;height:80px;
                  background:linear-gradient(to bottom,transparent,var(--surface));z-index:2;pointer-events:none;}
    .dh-hero-wave{position:absolute;bottom:-1px;left:0;right:0;z-index:3;line-height:0;pointer-events:none;}
    .dh-hero-wave svg{display:block;width:100%;}

    /* Text block */
    .dh-hero-text{position:relative;z-index:4;text-align:center;
                  max-width:600px;padding:40px 24px 0;animation:fadeUp .55s .1s both;}
    .dh-hero-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.66rem;
                     font-weight:500;letter-spacing:.18em;text-transform:uppercase;
                     color:rgba(255,255,255,.5);margin-bottom:14px;}
    .dh-hero-eyebrow::before,.dh-hero-eyebrow::after{content:'';display:inline-block;
                                                      width:18px;height:1.5px;
                                                      background:rgba(255,255,255,.35);border-radius:2px;}
    .dh-hero-title{font-size:clamp(2rem,4.5vw,3.2rem);font-weight:800;color:#fff;
                   line-height:1.14;letter-spacing:-.025em;margin:0 0 10px;}
    .dh-hero-sub{font-size:.95rem;color:rgba(255,255,255,.58);font-weight:300;
                 line-height:1.6;margin:0;}

    /* ── Category tile panel (inside hero, glassmorphism) ── */
    .dh-hero-panel{
        position:relative;z-index:4;width:100%;max-width:1100px;
        padding:24px 24px 60px;
        animation:fadeUp .55s .25s both;
    }
    .dh-hero-panel-label{
        font-size:.62rem;font-weight:600;letter-spacing:.16em;text-transform:uppercase;
        color:rgba(255,255,255,.42);text-align:center;margin-bottom:16px;
    }

    /* Centred wrapping tile grid */
    .dh-glass-grid{
        display:flex;
        flex-wrap:wrap;
        justify-content:center;   /* ← centres every row */
        gap:10px;
    }
    @media(max-width:480px){
        .dh-glass-grid{ gap:8px; }
    }

    /* Glassmorphism tile */
    .dh-gtile{
        width:84px;     /* fixed width so flex centres evenly */
        display:flex;flex-direction:column;align-items:center;justify-content:center;
        gap:7px;padding:13px 6px 11px;
        border-radius:14px;text-align:center;
        color:#fff;font-size:.68rem;font-weight:600;line-height:1.25;
        background:rgba(255,255,255,.1);
        backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
        border:1.5px solid rgba(255,255,255,.16);
        transition:transform .2s,background .2s,box-shadow .2s,border-color .2s;
        cursor:pointer;user-select:none;text-decoration:none;
    }
    .dh-gtile:hover{transform:translateY(-4px) scale(1.04);
                     background:rgba(255,255,255,.2);border-color:rgba(255,255,255,.4);
                     box-shadow:0 10px 32px rgba(0,0,0,.28);color:#fff;}
    .dh-gtile.gtile-active{background:rgba(255,255,255,.28);border-color:rgba(255,255,255,.6);
                            box-shadow:0 6px 24px rgba(0,0,0,.3);transform:translateY(-3px);}
    .dh-gtile .gtile-icon{width:36px;height:36px;border-radius:10px;
                           display:flex;align-items:center;justify-content:center;
                           font-size:.85rem;background:rgba(255,255,255,.16);
                           transition:transform .2s;flex-shrink:0;}
    .dh-gtile:hover .gtile-icon{transform:scale(1.1);}
    .dh-gtile .gtile-name{word-break:break-word;width:100%;}
    .dh-gtile .gtile-count{font-size:.58rem;font-weight:400;opacity:.6;}
    /* All Deals tile — opaque white */
    .dh-gtile.gtile-all{background:rgba(255,255,255,.95);color:var(--ink);
                         border-color:transparent;box-shadow:0 4px 16px rgba(0,0,0,.18);}
    .dh-gtile.gtile-all:hover{background:#fff;color:var(--accent);}
    .dh-gtile.gtile-all .gtile-icon{background:var(--surface-2);color:var(--accent);}

    /* Subcategory panel — below cat tiles, within hero */
    .dh-subcat-panel{
        background:rgba(255,255,255,.08);backdrop-filter:blur(10px);
        -webkit-backdrop-filter:blur(10px);
        border:1.5px solid rgba(255,255,255,.14);border-radius:14px;
        padding:16px 18px;margin-top:12px;
        display:none;animation:fadeUp .3s both;
    }
    .dh-subcat-panel.open{display:block;}
    .dh-subcat-panel-head{display:flex;align-items:center;justify-content:space-between;
                           margin-bottom:14px;}
    .dh-subcat-panel-title{font-size:.6rem;font-weight:600;letter-spacing:.14em;
                            text-transform:uppercase;color:rgba(255,255,255,.5);
                            display:flex;align-items:center;gap:7px;}
    .dh-back-btn{display:inline-flex;align-items:center;gap:5px;font-size:.7rem;font-weight:600;
                  background:rgba(255,255,255,.12);color:rgba(255,255,255,.8);
                  border:1px solid rgba(255,255,255,.2);border-radius:100px;
                  padding:5px 14px;cursor:pointer;transition:all .15s;backdrop-filter:blur(6px);}
    .dh-back-btn:hover{background:rgba(255,255,255,.22);color:#fff;}

    /* Loading dots */
    .dh-dot-loading{display:flex;gap:5px;justify-content:center;padding:12px 0;}
    .dh-dot-loading span{display:inline-block;width:7px;height:7px;border-radius:50%;
                          background:rgba(255,255,255,.6);animation:dotPulse 1.2s infinite both;}
    .dh-dot-loading span:nth-child(2){animation-delay:.2s;}
    .dh-dot-loading span:nth-child(3){animation-delay:.4s;}

    /* ── Shared wrapper ────────────────────────────────── */
    .dh-wrap{max-width:1180px;margin:0 auto;padding:0 24px;}

    /* ── Section heads ─────────────────────────────────── */
    .dh-sec-head{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:24px;}
    .dh-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.67rem;
                font-weight:500;letter-spacing:.14em;text-transform:uppercase;
                color:var(--accent);margin-bottom:5px;}
    .dh-eyebrow::before{content:'';display:inline-block;width:18px;height:2px;
                         background:var(--accent);border-radius:2px;}
    .dh-sec-title{font-size:1.5rem;font-weight:700;color:var(--ink);margin:0;}
    .dh-view-all{font-size:.77rem;font-weight:500;color:var(--ink-muted);text-decoration:none;
                  display:inline-flex;align-items:center;gap:4px;transition:color .15s;}
    .dh-view-all:hover{color:var(--accent);}

    /* ── Carousel ──────────────────────────────────────── */
    .dh-carousel-sec{padding:32px 0 12px;background:var(--surface);}
    .dh-carousel-block{margin-bottom:40px;}
    .dh-carousel-block:last-child{margin-bottom:0;}
    .dh-carousel-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
    .dh-carousel-title{font-size:1.12rem;font-weight:700;color:var(--ink);margin:0;
                        display:flex;align-items:center;gap:10px;}
    .cat-badge{font-size:.61rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;
               padding:3px 10px;border-radius:100px;}
    .dh-carousel-controls{display:flex;align-items:center;gap:8px;}
    .dh-c-btn{width:32px;height:32px;border-radius:50%;border:1.5px solid rgba(0,0,0,.12);
               background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;
               font-size:.8rem;color:var(--ink-muted);transition:all .15s;}
    .dh-c-btn:hover{background:var(--ink);color:#fff;border-color:var(--ink);}
    .dh-track-outer{position:relative;overflow:hidden;}
    .dh-track{display:flex;gap:20px;overflow-x:auto;padding-bottom:16px;
               scroll-behavior:smooth;scroll-snap-type:x mandatory;
               -ms-overflow-style:none;scrollbar-width:none;cursor:grab;user-select:none;}
    .dh-track.is-dragging{cursor:grabbing;scroll-behavior:auto;}
    .dh-track::-webkit-scrollbar{display:none;}
    .dh-track-outer::after{content:'';position:absolute;right:0;top:0;bottom:16px;width:64px;
                            pointer-events:none;
                            background:linear-gradient(to left,var(--surface),transparent);z-index:1;}
    /* Carousel cards use full post-single-card design */
    .dh-track .dh-card{flex:0 0 300px;scroll-snap-align:start;}
    .dh-track.is-dragging .dh-card{pointer-events:none;}
    @media(max-width:560px){.dh-track .dh-card{flex:0 0 260px;}}

    /* Section AJAX spinner */
    .dh-section-spinner{text-align:center;padding:32px 0;}
    .dh-section-spinner span{display:inline-block;width:9px;height:9px;border-radius:50%;
                              background:var(--accent);margin:0 3px;
                              animation:dotPulse 1.2s infinite both;}
    .dh-section-spinner span:nth-child(2){animation-delay:.2s;}
    .dh-section-spinner span:nth-child(3){animation-delay:.4s;}

    /* ── Post cards (carousel + grid) ─────────────────── */
    .dh-latest-sec{padding:0 0 80px;background:var(--surface);}
    .dh-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;}
    @media(max-width:900px){.dh-grid{grid-template-columns:repeat(2,1fr);}}
    @media(max-width:560px){.dh-grid{grid-template-columns:1fr;}}
    .dh-card{background:#fff;border-radius:var(--rlg);overflow:hidden;box-shadow:var(--sh-sm);
              border:1px solid rgba(0,0,0,.05);display:flex;flex-direction:column;
              transition:transform .22s,box-shadow .22s;}
    .dh-card:hover{transform:translateY(-5px);box-shadow:var(--sh-md);}
    .dh-card-media{position:relative;overflow:hidden;flex-shrink:0;}
    .dh-card-media img,.dh-card-media video{width:100%;height:200px;object-fit:cover;
                                             display:block;transition:transform .35s;}
    .dh-card:hover .dh-card-media img{transform:scale(1.04);}
    .dh-card-media .ratio{height:200px;}
    .badge-feat{position:absolute;top:10px;right:10px;background:#f59e0b;color:#fff;
                font-size:.6rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;
                padding:4px 10px;border-radius:100px;}
    .dh-card-body{padding:16px 18px 18px;display:flex;flex-direction:column;flex:1;}
    .dh-badges{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px;}
    .dh-b{font-size:.6rem;font-weight:500;letter-spacing:.07em;text-transform:uppercase;
           padding:3px 9px;border-radius:100px;}
    .dh-b-loc{background:var(--surface-2);color:var(--ink-muted);}
    .dh-b-cat{background:rgba(15,63,126,.08);color:var(--accent);}
    .dh-b-sub{background:rgba(59,130,246,.08);color:#1d4ed8;}
    .dh-card-title{font-size:.98rem;font-weight:700;color:var(--ink);line-height:1.35;
                    margin:0 0 7px;text-decoration:none;display:block;transition:color .15s;}
    .dh-card-title:hover{color:var(--accent);}
    .dh-card-desc{font-size:.8rem;line-height:1.6;color:var(--ink-muted);
                   font-weight:300;flex:1;margin-bottom:12px;}
    .dh-card-meta{display:flex;align-items:center;gap:8px;flex-wrap:wrap;
                   padding-top:10px;border-top:1px solid rgba(0,0,0,.06);margin-bottom:12px;}
    .dh-meta-btn,.dh-meta-box{display:flex;align-items:center;gap:6px;padding:4px 9px;
                               border-radius:14px;background:#fff;border:1px solid #edf0f5;
                               transition:all .2s;box-shadow:0 2px 8px rgba(0,0,0,.04);}
    .dh-meta-btn{cursor:pointer;outline:none;}
    .dh-meta-btn:hover,.dh-meta-box:hover{transform:translateY(-2px);box-shadow:0 4px 14px rgba(0,0,0,.08);}
    .dh-meta-icon{font-size:11px;color:#6b7280;}
    .dh-meta-count{font-size:12px;font-weight:600;color:#1f2937;}
    .dh-meta-time{margin-left:auto;display:flex;align-items:center;gap:5px;font-size:11px;color:#6b7280;}
    .likeBtn.liked{background:rgba(255,77,109,.08);border-color:rgba(255,77,109,.18);}
    .likeBtn.liked .dh-meta-icon{color:#ff4d6d;}
    .likeBtn.liked .dh-meta-count{color:#ff4d6d;}
    .dh-card-actions{display:flex;gap:8px;}
    .dh-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;
             font-size:.74rem;font-weight:500;border-radius:100px;padding:8px 16px;
             text-decoration:none;border:1.5px solid;cursor:pointer;transition:all .15s;flex:1;}
    .dh-btn-primary{background:var(--ink);color:#fff;border-color:var(--ink);}
    .dh-btn-primary:hover{background:var(--accent);border-color:var(--accent);color:#fff;}
    .dh-btn-ghost{background:transparent;color:var(--ink-muted);
                   border-color:rgba(0,0,0,.12);flex:0 0 auto;padding:8px 12px;}
    .dh-btn-ghost:hover{background:var(--surface-2);color:var(--ink);}
    .dh-empty{grid-column:1/-1;text-align:center;padding:64px 24px;color:var(--ink-muted);}
    .dh-show-more{text-align:center;margin-top:36px;}
    .dh-more-btn{display:inline-flex;align-items:center;gap:8px;font-size:.79rem;
                  font-weight:500;color:var(--ink);background:#fff;cursor:pointer;
                  border:1.5px solid rgba(0,0,0,.14);border-radius:100px;padding:12px 28px;
                  transition:all .18s;}
    .dh-more-btn:hover{background:var(--ink);color:#fff;transform:translateY(-2px);}

    /* ── Footer ────────────────────────────────────────── */
    .dh-footer{background:var(--ink);color:rgba(255,255,255,.7);padding:60px 0 0;font-size:.84rem;}
    .dh-footer-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr;gap:48px;padding-bottom:48px;}
    @media(max-width:720px){.dh-footer-grid{grid-template-columns:1fr 1fr;}}
    @media(max-width:440px){.dh-footer-grid{grid-template-columns:1fr;}}
    .dh-footer-brand{font-size:1.1rem;color:#fff;margin:12px 0 5px;}
    .dh-footer-tag{font-size:.77rem;color:rgba(255,255,255,.38);margin:0;}
    .dh-footer-social{display:flex;gap:8px;margin-top:18px;}
    .dh-footer-social a{width:34px;height:34px;border-radius:50%;border:1px solid rgba(255,255,255,.15);
                         display:flex;align-items:center;justify-content:center;
                         color:rgba(255,255,255,.6);font-size:.88rem;text-decoration:none;transition:.15s;}
    .dh-footer-social a:hover{border-color:rgba(255,255,255,.5);color:#fff;}
    .dh-footer-col-title{font-size:.64rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;
                          color:var(--accent);margin-bottom:14px;}
    .dh-footer-links{list-style:none;padding:0;margin:0;}
    .dh-footer-links li{margin-bottom:10px;}
    .dh-footer-links a{color:rgba(255,255,255,.52);text-decoration:none;transition:color .15s;}
    .dh-footer-links a:hover{color:#fff;}
    .dh-footer-bottom{border-top:1px solid rgba(255,255,255,.08);text-align:center;
                       padding:20px 0;font-size:.74rem;color:rgba(255,255,255,.3);}
    .dh-footer-bottom a{color:rgba(255,255,255,.48);text-decoration:none;}

    @keyframes fadeUp{from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);}}
    @keyframes dotPulse{0%,80%,100%{opacity:.2;transform:scale(.75);}40%{opacity:1;transform:scale(1);}}
    </style>
</head>
<body>

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
$paletteJson = json_encode($palette);
@endphp

{{-- ═══ HERO with glassmorphism category tiles ═══ --}}
<header class="dh-hero">
    <div class="dh-hero-bg" id="heroBg"></div>
    <div class="dh-hero-overlay"></div>

    {{-- Title block --}}
    <div class="dh-hero-text">
        <h1 class="dh-hero-title">Discover the best deals near you.</h1>
    </div>

    {{-- Category tiles panel — inside hero --}}
    <div class="dh-hero-panel" id="heroCatPanel">

        {{-- Category tiles --}}
        <div class="dh-glass-grid" id="catGlassGrid">

            <a href="{{ route('posts.listing') }}" class="dh-gtile gtile-all">
                <span class="gtile-icon"><i class="fas fa-th" style="font-size:.85rem;"></i></span>
                <span class="gtile-name">All Deals</span>
            </a>

            @foreach ($categories as $i => $cat)
                @php $p = $palette[$i % count($palette)]; @endphp
                <div class="dh-gtile"
                     role="button" tabindex="0"
                     data-cat-slug="{{ $cat->slug }}"
                     data-cat-name="{{ $cat->name }}"
                     data-cat-href="{{ route('posts.listing', ['category_id' => $cat->slug]) }}"
                     data-pal-idx="{{ $i % count($palette) }}">
                    <span class="gtile-icon" style="background:{{ $p['bg'] }};color:{{ $p['ic'] }};">
                        <i class="fas {{ $p['icon'] }}" style="font-size:.85rem;"></i>
                    </span>
                    <span class="gtile-name">{{ $cat->name }}</span>
                </div>
            @endforeach

        </div>

        {{-- Subcategory panel — appears below categories, stays in hero --}}
        <div class="dh-subcat-panel" id="subcatGlassPanel">
            <div class="dh-subcat-panel-head">
                <span class="dh-subcat-panel-title" id="subcatGlassTitle">
                    <i class="fas fa-sitemap" style="font-size:.58rem;"></i> Subcategories
                </span>
                <button class="dh-back-btn" id="subcatGlassClose">
                    <i class="bi bi-x"></i> Close
                </button>
            </div>
            <div class="dh-glass-grid" id="subcatGlassGrid">
                <div class="dh-dot-loading"><span></span><span></span><span></span></div>
            </div>
        </div>

    </div>

    <div class="dh-hero-fade"></div>
    <div class="dh-hero-wave">
        <svg viewBox="0 0 1440 56" fill="none"><path d="M0 56H1440V28C1200 56 960 8 720 8C480 8 240 56 0 28V56Z" fill="#faf9f7"/></svg>
    </div>
</header>

{{-- ═══ CAROUSELS ═══ --}}
<section class="dh-carousel-sec" style="padding-top:32px;">
    <div class="dh-wrap">

        <div class="dh-sec-head">
            <div>
                <div class="dh-eyebrow">Popular</div>
                <h2 class="dh-sec-title" id="carouselHeadTitle">Top Deals by Category</h2>
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
    <div class="dh-wrap">
        <div class="dh-sec-head">
            <div>
                <div class="dh-eyebrow">Just in</div>
                <h2 class="dh-sec-title" id="latestTitle">Latest Deals</h2>
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
const PALETTE     = {!! $paletteJson !!};
const LISTING_URL = '{{ route("posts.listing") }}';
const HOME_URL    = '{{ route("home") }}';
const CSRF        = '{{ csrf_token() }}';

document.getElementById('footerYear').textContent = new Date().getFullYear();
document.getElementById('navToggle').addEventListener('click',function(){
    document.getElementById('navActions').classList.toggle('open');
});
const heroBg = document.getElementById('heroBg');
window.addEventListener('scroll',function(){
    if(heroBg) heroBg.style.transform='translateY('+(scrollY*.25)+'px)';
},{passive:true});

/* ── Drag-to-scroll carousels ─────────────────────── */
function makeDraggable(el) {
    if (!el) return;

    let isDown   = false;
    let startX   = 0;
    let scrollLeft = 0;
    let wasDragged = false;   // true only after meaningful mouse movement

    el.addEventListener('mousedown', e => {
        isDown     = true;
        wasDragged = false;
        startX     = e.pageX - el.offsetLeft;
        scrollLeft = el.scrollLeft;
        el.style.cursor = 'grabbing';
    });

    el.addEventListener('mouseleave', () => {
        isDown = false;
        el.style.cursor = '';
        el.classList.remove('is-dragging');
    });

    el.addEventListener('mouseup', () => {
        isDown = false;
        el.style.cursor = '';
        el.classList.remove('is-dragging');
        // Reset wasDragged after a short delay so the
        // click event (which fires after mouseup) can read it
        setTimeout(() => { wasDragged = false; }, 50);
    });

    el.addEventListener('mousemove', e => {
        if (!isDown) return;
        e.preventDefault();
        const walk = (e.pageX - el.offsetLeft - startX) * 1.4;
        if (Math.abs(walk) > 6) {          // 6px threshold before "drag" kicks in
            wasDragged = true;
            el.classList.add('is-dragging');
        }
        el.scrollLeft = scrollLeft - walk;
    });

    // Capture-phase click: fires BEFORE the anchor's own click handler.
    // Block navigation only when a real drag happened.
    el.addEventListener('click', e => {
        if (wasDragged) {
            e.preventDefault();
            e.stopPropagation();
        }
    }, true /* capture */);

    // Touch
    let tx = 0, ts = 0;
    el.addEventListener('touchstart', e => {
        tx = e.touches[0].pageX; ts = el.scrollLeft;
    }, { passive: true });
    el.addEventListener('touchmove', e => {
        el.scrollLeft = ts + (tx - e.touches[0].pageX);
    }, { passive: true });
}

function initDrag() { document.querySelectorAll('.dh-track').forEach(makeDraggable); }
initDrag();

$(document).on('click','.c-prev,.c-next',function(){
    const $t=$('#'+$(this).data('target'));
    if(!$t.length)return;
    const w=$t.find('.dh-card').first().outerWidth(true)||300;
    $t[0].scrollBy({left:$(this).hasClass('c-prev')?-w*2:w*2,behavior:'smooth'});
});

/* ═══════════════════════════════════════════════════
   CATEGORY TILE CLICK
   1. Mark tile active (don't hide others)
   2. Show subcategory panel in hero
   3. AJAX update carousels + latest posts
═══════════════════════════════════════════════════ */
let activeCatSlug = null;

$(document).on('click','.dh-gtile[data-cat-slug]',function(){
    const slug    = $(this).data('cat-slug');
    const name    = $(this).data('cat-name');
    const catHref = $(this).data('cat-href');
    const $panel  = $('#subcatGlassPanel');
    const $grid   = $('#subcatGlassGrid');
    const $title  = $('#subcatGlassTitle');

    // Clicking active again → clear
    if(activeCatSlug===slug && $panel.hasClass('open')){
        clearFilter(); return;
    }

    // Mark active tile
    $('.dh-gtile[data-cat-slug]').removeClass('gtile-active');
    $(this).addClass('gtile-active');
    activeCatSlug = slug;

    // Show subcategory panel with loading state
    $title.html('<i class="fas fa-sitemap" style="font-size:.58rem;"></i> '+name+' — Subcategories');
    $grid.html('<div class="dh-dot-loading"><span></span><span></span><span></span></div>');
    $panel.addClass('open');

    // Fetch subcategories
    $.get('/get-subcategories/'+slug,function(data){
        let p=PALETTE[0];
        let html='<a href="'+catHref+'" class="dh-gtile gtile-all">'
                +'<span class="gtile-icon"><i class="fas fa-th" style="font-size:.8rem;"></i></span>'
                +'<span class="gtile-name">All '+name+'</span></a>';
        $.each(data,function(i,sub){
            p=PALETTE[i%PALETTE.length];
            html+='<a href="'+LISTING_URL+'?category_id='+slug+'&subcategory_id='+sub.slug+'"'
                +' class="dh-gtile">'
                +'<span class="gtile-icon" style="background:'+p.bg+';color:'+p.ic+';">'
                +'<i class="fas '+p.icon+'" style="font-size:.8rem;"></i></span>'
                +'<span class="gtile-name">'+sub.name+'</span></a>';
        });
        $grid.html(html);
    }).fail(()=>{ $panel.removeClass('open'); });

    // AJAX update carousels and latest posts
    const $cc=$('#carouselContent');
    const $pg=$('#postsGrid');
    const spinner='<div class="dh-section-spinner"><span></span><span></span><span></span></div>';

    $cc.html(spinner);
    $pg.html('<div style="grid-column:1/-1">'+spinner+'</div>');
    $('#showMoreWrap').hide();
    $('#latestTitle').text(name+' — Latest Deals');
    $('#latestViewAll').attr('href',catHref);
    $('#carouselHeadTitle').text(name+' — Popular');
    $('#carouselViewAll').attr('href',catHref);

    $.ajax({
        url:HOME_URL, type:'GET',
        data:{filter_category:slug},
        headers:{'X-Requested-With':'XMLHttpRequest'},
        success:function(res){
            $cc.html(res.carousel_html||'<p style="text-align:center;color:var(--ink-muted);padding:24px 0;font-size:.85rem;">No popular posts for this category yet.</p>');
            $pg.html(res.posts_html||'');
            if(res.next_page){
                if($('#showMoreWrap').length){
                    $('#showMoreWrap').show().find('#loadMoreBtn').data('next',res.next_page);
                } else {
                    $pg.after('<div class="dh-show-more" id="showMoreWrap"><button class="dh-more-btn" id="loadMoreBtn" data-next="'+res.next_page+'">Load More Deals <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button></div>');
                }
            } else {
                $('#showMoreWrap').hide();
            }
            initDrag();
        },
        error:function(){ $cc.html(''); }
    });
});

function clearFilter(){
    activeCatSlug=null;
    $('.dh-gtile[data-cat-slug]').removeClass('gtile-active');
    $('#subcatGlassPanel').removeClass('open');
    $('#latestTitle').text('Latest Deals');
    $('#latestViewAll').attr('href',LISTING_URL);
    $('#carouselHeadTitle').text('Top Deals by Category');
    $('#carouselViewAll').attr('href',LISTING_URL);
    location.reload();
}

$('#subcatGlassClose').on('click',function(){
    $('#subcatGlassPanel').removeClass('open');
});

$(document).on('keydown','.dh-gtile[data-cat-slug]',function(e){
    if(e.key==='Enter'||e.key===' '){e.preventDefault();$(this).trigger('click');}
});

/* ── Like ─────────────────────────────────────────── */
$(document).on('click','.likeBtn',function(){
    const btn=$(this),id=btn.data('id');
    $.post('/posts/'+id+'/toggle-like',{_token:CSRF},function(res){
        $('#lc-'+id).text(res.likes);
        res.liked?btn.addClass('liked'):btn.removeClass('liked');
    });
});

/* ── Share ────────────────────────────────────────── */
$(document).on('click','.shareBtn',function(){
    const id=$(this).data('id'),url=$(this).data('url');
    navigator.share?navigator.share({url}):(navigator.clipboard.writeText(url),alert('Link copied!'));
    $.post('/posts/'+id+'/share',{_token:CSRF,platform:'web'});
});

/* ── Load More ────────────────────────────────────── */
$(document).on('click','#loadMoreBtn',function(){
    const btn=$(this),next=btn.data('next');
    if(!next)return;
    btn.text('Loading…').prop('disabled',true);
    $.get(next,function(res){
        if(res.html){
            $('#postsGrid').append(res.html);
            res.next_page
                ?btn.data('next',res.next_page).text('Load More Deals').prop('disabled',false)
                :btn.closest('.dh-show-more').remove();
        }
    }).fail(function(){btn.text('Load More Deals').prop('disabled',false);});
});
</script>
</body>
</html>