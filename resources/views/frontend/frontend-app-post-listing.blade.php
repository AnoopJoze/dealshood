<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../frontend/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../frontend/img/favicon.png">
    <title>DealsHood — Discover the Best Deals Near You</title>

    {{-- ═══════════════════════════════════════════════
         SEO & SOCIAL META
         WhatsApp, Telegram, Facebook all read og:image.
         Rules:
           • Must be absolute HTTPS URL
           • Min 300×300px  (1200×630 recommended)
           • Must be publicly accessible (no auth)
           • og:image:width / height must be present
    ═══════════════════════════════════════════════ --}}
    @php
        /* --- OG image ---------------------------------------------------- */
        $ogImage = '/frontend/img/favicon.png';
        if (!$ogImage) { $ogImage = asset('frontend/img/default.jpg'); }
        // Force absolute + HTTPS
        if (!str_starts_with($ogImage, 'http')) { $ogImage = url($ogImage); }
        $ogImage = str_replace('http://', 'https://', $ogImage);
        $post = ['meta_title'=>'DealsHood','title'=>'DealsHood','meta_description'=>'DealsHood','keywords'=>'DealsHood'];
        /* --- Other OG fields --------------------------------------------- */
        $ogTitle       = @$post->meta_title       ?: @$post->title;
        $ogDescription = @$post->meta_description ?: Str::limit(strip_tags(@$post->description), 160);
        $ogUrl         = url()->current();
    @endphp

    <meta name="description"  content="{{ $ogDescription }}">
    <meta name="keywords"     content="{{ @$post->keywords }}">
    <link rel="canonical"     href="{{ $ogUrl }}">

    {{-- Open Graph (WhatsApp / Facebook / Telegram / LinkedIn) --}}
    <meta property="og:site_name"        content="DealsHood">
    <meta property="og:type"             content="article">
    <meta property="og:title"            content="{{ $ogTitle }}">
    <meta property="og:description"      content="{{ $ogDescription }}">
    <meta property="og:url"              content="{{ $ogUrl }}">
    <meta property="og:image"            content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:type"       content="image/jpeg">
    <meta property="og:image:width"      content="1200">
    <meta property="og:image:height"     content="630">
    <meta property="og:image:alt"        content="{{ $ogTitle }}">
    <meta property="og:locale"           content="en_US">

    {{-- Article meta --}}

    {{-- Twitter Card (iMessage / Slack / Discord also use these) --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image"       content="{{ $ogImage }}">
    <meta name="twitter:image:alt"   content="{{ $ogTitle }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- CSS Files -->
    <link id="pagestyle" href="../frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet" />

    <style>
    /* ═══════════════════════════════════════════════════════
       DESIGN TOKENS
    ═══════════════════════════════════════════════════════ */
    :root {
        --ink:          #0d0d0d;
        --ink-mid:      #3a3a3a;
        --ink-muted:    #6b6b6b;
        --surface:      #faf9f7;
        --surface-2:    #f2f1ef;
        --white:        #ffffff;
        --accent:       #c8102e;
        --accent-dim:   rgba(200,16,46,.08);
        --accent-soft:  rgba(200,16,46,.2);
        --radius-sm:    8px;
        --radius:       14px;
        --radius-lg:    20px;
        --shadow-sm:    0 2px 12px rgba(0,0,0,.07);
        --shadow-md:    0 6px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
        --shadow-lg:    0 16px 56px rgba(0,0,0,.13), 0 4px 16px rgba(0,0,0,.06);
        --font-serif:  -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
        --font-sans:   -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
        --nav-h:        64px;
    }
    *, *::before, *::after { box-sizing: border-box; }
    body {
        font-family: var(--font-sans);
        background: var(--surface);
        color: var(--ink);
        margin: 0;
    }

    /* ═══════════════════════════════════════════════════════
       NAVBAR
    ═══════════════════════════════════════════════════════ */
    .dh-nav {
        position: fixed;
        top: 0; left: 0; right: 0;
        height: var(--nav-h);
        background: #fff;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(0,0,0,.07);
        z-index: 1000;
        display: flex;
        align-items: center;
    }
    .dh-nav-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 24px;
    }
    .dh-nav-logo img { height: 45px; width: auto; display: block; }
    .dh-nav-actions  { display: flex; align-items: center; gap: 10px; }

    .dh-btn-nav {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: var(--font-sans);
        font-size: .75rem;
        font-weight: 500;
        letter-spacing: .04em;
        border: none;
        cursor: pointer;
        border-radius: 100px;
        padding: 9px 18px;
        text-decoration: none;
        transition: transform .15s, opacity .15s;
    }
    .dh-btn-nav:hover { transform: translateY(-1px); opacity: .9; }
    .dh-btn-ig { background: #e1306c; color: #fff; }
    .dh-btn-wa { background: #25d366; color: #fff; }

    /* Mobile hamburger */
    .dh-nav-toggle {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        flex-direction: column;
        gap: 5px;
        padding: 6px;
    }
    .dh-nav-toggle span {
        display: block;
        width: 22px; height: 2px;
        background: var(--ink);
        border-radius: 2px;
        transition: transform .2s, opacity .2s;
    }
    @media (max-width: 640px) {
        .dh-nav-toggle { display: flex; }
        .dh-nav-actions {
            display: none;
            position: absolute;
            top: var(--nav-h); left: 0; right: 0;
            background: var(--white);
            border-bottom: 1px solid rgba(0,0,0,.08);
            padding: 16px 24px;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            box-shadow: var(--shadow-sm);
        }
        .dh-nav-actions.open { display: flex; }
    }

    /* ═══════════════════════════════════════════════════════
       HERO BANNER
    ═══════════════════════════════════════════════════════ */
    .dh-hero {
        padding-top: var(--nav-h);
        position: relative;
        overflow: hidden;
        min-height: 350px;
        display: flex;
        align-items: center;
        background: var(--ink);
    }
    .dh-hero-bg {
        position: absolute;
        inset: 0;
        background-image: url('../frontend/img/office-dark.jpg');
        background-size: cover;
        background-position: center;
        opacity: .42;
        will-change: transform;
    }
    .dh-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            160deg,
            rgba(13,13,13,.78) 0%,
            rgba(13,13,13,.28) 60%,
            rgba(200,16,46,.14) 100%
        );
    }
    .dh-hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 15px 24px 35px;
        text-align: center;
    }
    .dh-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        font-size: .68rem;
        font-weight: 500;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: #10407e;
        margin-bottom: 18px;
        animation: fadeUp .5s .05s both;
    }
    .dh-hero-eyebrow::before,
    .dh-hero-eyebrow::after {
        content: '';
        display: inline-block;
        width: 20px; height: 1.5px;
        background: #10407e;
        border-radius: 2px;
    }
    .dh-hero-title {
        
        font-size: clamp(2.1rem, 4.8vw, 3.7rem);
        font-weight: 700;
        color: #fff;
        line-height: 1.14;
        letter-spacing: -.02em;
        /* margin: 0 0 16px; */
        animation: fadeUp .55s .12s both;
    }
    .dh-hero-sub {
        font-size: 1.02rem;
        color: rgba(255,255,255,.62);
        font-weight: 300;
        /* margin-bottom: 30px; */
        animation: fadeUp .55s .18s both;
    }
    .dh-hero-socials {
        display: flex;
        justify-content: center;
        gap: 14px;
        animation: fadeUp .55s .24s both;
    }
    .dh-hero-socials a {
        width: 38px; height: 38px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.22);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,.65);
        font-size: .95rem;
        text-decoration: none;
        transition: border-color .15s, color .15s, background .15s;
    }
    .dh-hero-socials a:hover {
        border-color: #fff;
        color: #fff;
        background: rgba(255,255,255,.08);
    }
    /* Wave */
    .dh-hero-wave {
        position: absolute;
        bottom: -1px; left: 0; right: 0;
        z-index: 3;
        line-height: 0;
    }
    .dh-hero-wave svg { display: block; width: 100%; }

    /* ═══════════════════════════════════════════════════════
       FILTER BAR  (floats up over the wave)
    ═══════════════════════════════════════════════════════ */
    .dh-filter-section {
        background: var(--surface);
        padding: 0 0 10px;
    }
    .dh-filter-wrap {
        max-width: 1180px;
        margin: -38px auto 0;
        padding: 0 24px;
        position: relative;
        z-index: 10;
        animation: fadeUp .55s .3s both;
    }
    .dh-filter-inner {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(0,0,0,.05);
        padding: 26px 28px 22px;
    }
    .dh-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr) auto;
        gap: 14px;
        align-items: end;
    }
    @media (max-width: 860px) {
        .dh-filter-grid { grid-template-columns: 1fr 1fr; }
        .dh-filter-submit { grid-column: 1 / -1; }
    }
    @media (max-width: 480px) {
        .dh-filter-grid { grid-template-columns: 1fr; }
    }

    .dh-field-group { display: flex; flex-direction: column; gap: 5px; }
    .dh-field-label {
        font-size: .67rem;
        font-weight: 500;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--ink-muted);
    }
    .dh-field {
        font-family: var(--font-sans);
        font-size: .87rem;
        color: var(--ink);
        background: var(--surface);
        border: 1.5px solid rgba(0,0,0,.1);
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
    }
    .dh-field:focus {
        border-color: #10407e;
        box-shadow: 0 0 0 3px var(--accent-dim);
    }
    .dh-search-btn {
        font-family: var(--font-sans);
        font-size: .8rem;
        font-weight: 500;
        letter-spacing: .04em;
        background: var(--ink);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        padding: 11px 26px;
        cursor: pointer;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        height: 42px;
        transition: background .15s, transform .15s, box-shadow .15s;
    }
    .dh-search-btn:hover {
        background: #10407e;
        transform: translateY(-1px);
        box-shadow: 0 4px 16px var(--accent-soft);
    }

    /* ═══════════════════════════════════════════════════════
       POSTS SECTION
    ═══════════════════════════════════════════════════════ */
    .dh-posts-section {
        padding: 0 0 88px;
        background: var(--surface);
    }
    .dh-container {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* Section header */
    .dh-section-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .dh-section-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: .67rem;
        font-weight: 500;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #10407e;
        margin-bottom: 6px;
    }
    .dh-section-eyebrow::before {
        content: '';
        display: inline-block;
        width: 18px; height: 2px;
        background: #10407e;
        border-radius: 2px;
    }
    .dh-section-title {
        
        font-size: 1.55rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }
    .dh-result-count {
        font-size: .78rem;
        color: var(--ink-muted);
        font-weight: 300;
        padding-bottom: 4px;
    }

    /* ─── Cards grid ─── */
    .dh-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    @media (max-width: 900px) { .dh-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 560px) { .dh-grid { grid-template-columns: 1fr; } }

    /* ─── Card ─── */
    .dh-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid rgba(0,0,0,.05);
        display: flex;
        flex-direction: column;
        transition: transform .22s, box-shadow .22s;
        animation: fadeUp .4s both;
    }
    .dh-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    /* Card media */
    .dh-card-media {
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }
    .dh-card-media a { display: block; }
    .dh-card-media img,
    .dh-card-media video {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
        transition: transform .35s;
    }
    .dh-card:hover .dh-card-media img { transform: scale(1.04); }
    .dh-card-media .ratio { height: 220px; }
    .dh-card-media .ratio iframe { width: 100%; height: 100%; }

    /* Featured badge */
    .dh-badge-featured {
        position: absolute;
        top: 12px; right: 12px;
        background: #f59e0b;
        color: #fff;
        font-size: .6rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 100px;
        z-index: 2;
    }

    /* Card body */
    .dh-card-body {
        padding: 18px 20px 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .dh-card-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 10px;
    }
    .dh-badge {
        font-size: .6rem;
        font-weight: 500;
        letter-spacing: .07em;
        text-transform: uppercase;
        padding: 3px 9px;
        border-radius: 100px;
    }
    .dh-badge-loc { background: var(--surface-2); color: var(--ink-muted); }
    .dh-badge-cat { background: var(--accent-dim); color: #10407e; }
    .dh-badge-sub { background: rgba(59,130,246,.08); color: #1d4ed8; }

    .dh-card-title {
        
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.35;
        margin: 0 0 8px;
        text-decoration: none;
        display: block;
        transition: color .15s;
    }
    .dh-card-title:hover { color: #10407e; }

    .dh-card-desc {
        font-size: .82rem;
        line-height: 1.65;
        color: var(--ink-muted);
        font-weight: 300;
        flex: 1;
        margin-bottom: 16px;
    }

    /* Stats row */
    .dh-card-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: .74rem;
        color: var(--ink-muted);
        margin-bottom: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid rgba(0,0,0,.06);
        flex-wrap: wrap;
    }
    .dh-card-meta-item { display: flex; align-items: center; gap: 4px; }
    .dh-stat-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-family: var(--font-sans);
        font-size: .71rem;
        font-weight: 500;
        background: none;
        border: 1px solid rgba(0,0,0,.1);
        border-radius: 100px;
        padding: 4px 10px;
        cursor: pointer;
        color: var(--ink-muted);
        transition: all .15s;
    }
    .dh-stat-btn.likeBtn:hover { border-color: #10407e; color: #10407e; background: var(--accent-dim); }
    .dh-stat-btn.likeBtn.liked { border-color: #10407e; color: #10407e; background: var(--accent-dim); }

    /* Action buttons */
    .dh-card-actions { display: flex; gap: 8px; }
    .dh-card-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-family: var(--font-sans);
        font-size: .76rem;
        font-weight: 500;
        letter-spacing: .03em;
        border-radius: 100px;
        padding: 9px 18px;
        text-decoration: none;
        border: 1.5px solid;
        cursor: pointer;
        transition: all .15s;
        flex: 1;
    }
    .dh-card-btn-primary {
        background: var(--ink);
        color: #fff;
        border-color: var(--ink);
    }
    .dh-card-btn-primary:hover { background: #10407e; border-color: #10407e; color: #fff; }
    .dh-card-btn-ghost {
        background: transparent;
        color: var(--ink-muted);
        border-color: rgba(0,0,0,.12);
        flex: 0 0 auto;
        padding: 9px 14px;
    }
    .dh-card-btn-ghost:hover { background: var(--surface-2); color: var(--ink); }

    /* Empty state */
    .dh-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 72px 24px;
        color: var(--ink-muted);
    }
    .dh-empty-icon  { font-size: 2.6rem; margin-bottom: 14px; opacity: .35; }
    .dh-empty-title {  font-size: 1.2rem; color: var(--ink); margin-bottom: 8px; }
    .dh-empty-text  { font-size: .88rem; font-weight: 300; }

    /* Infinite scroll loader */
    .dh-loader {
        display: none;
        text-align: center;
        padding: 40px 0;
    }
    .dh-loader-dots {
        display: inline-flex;
        gap: 7px;
        align-items: center;
    }
    .dh-loader-dots span {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #10407e;
        animation: dotPulse 1.2s infinite both;
    }
    .dh-loader-dots span:nth-child(2) { animation-delay: .2s; }
    .dh-loader-dots span:nth-child(3) { animation-delay: .4s; }
    @keyframes dotPulse {
        0%, 80%, 100% { opacity: .2; transform: scale(.75); }
        40%            { opacity: 1;  transform: scale(1); }
    }

    /* End-of-feed message */
    .dh-end-msg {
        display: none;
        text-align: center;
        padding: 32px 0 0;
        font-size: .78rem;
        color: var(--ink-muted);
        letter-spacing: .06em;
    }
    .dh-end-msg::before,
    .dh-end-msg::after {
        content: '';
        display: inline-block;
        width: 36px; height: 1px;
        background: rgba(0,0,0,.15);
        vertical-align: middle;
        margin: 0 12px;
    }

    /* ═══════════════════════════════════════════════════════
       FOOTER
    ═══════════════════════════════════════════════════════ */
    .dh-footer {
        background: var(--ink);
        color: rgba(255,255,255,.7);
        padding: 64px 0 0;
        font-size: .85rem;
    }
    .dh-footer-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr 1fr;
        gap: 48px;
        padding-bottom: 48px;
    }
    @media (max-width: 720px) { .dh-footer-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 440px) { .dh-footer-grid { grid-template-columns: 1fr; } }

    .dh-footer-brand-name {
        
        font-size: 1.15rem;
        color: #fff;
        margin: 12px 0 5px;
    }
    .dh-footer-tagline { font-size: .78rem; color: rgba(255,255,255,.38); margin: 0; }
    .dh-footer-social { display: flex; gap: 8px; margin-top: 20px; }
    .dh-footer-social a {
        width: 34px; height: 34px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.15);
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,.6);
        font-size: .9rem;
        text-decoration: none;
        transition: border-color .15s, color .15s;
    }
    .dh-footer-social a:hover { border-color: rgba(255,255,255,.5); color: #fff; }
    .dh-footer-col-title {
        font-size: .65rem;
        font-weight: 600;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #10407e;
        margin-bottom: 16px;
    }
    .dh-footer-links { list-style: none; padding: 0; margin: 0; }
    .dh-footer-links li { margin-bottom: 10px; }
    .dh-footer-links a { color: rgba(255,255,255,.52); text-decoration: none; transition: color .15s; }
    .dh-footer-links a:hover { color: #fff; }
    .dh-footer-bottom {
        border-top: 1px solid rgba(255,255,255,.08);
        text-align: center;
        padding: 22px 0;
        font-size: .75rem;
        color: rgba(255,255,255,.3);
    }
    .dh-footer-bottom a { color: rgba(255,255,255,.48); text-decoration: none; }
    .dh-footer-bottom a:hover { color: #fff; }

    /* ═══════════════════════════════════════════════════════
       ANIMATIONS
    ═══════════════════════════════════════════════════════ */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    
    .dh-card-meta{
    display:flex;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
    padding-top:5px;
    border-top:1px solid rgba(255,255,255,0.08);
}

.dh-meta-btn,
.dh-meta-box{
    display:flex;
    align-items:center;
    gap:8px;
    padding:4px 10px;
    border-radius:14px;
    background:#fff;
    border:1px solid #edf0f5;
    transition:all .25s ease;
    box-shadow:0 2px 8px rgba(0,0,0,0.04);
}

.dh-meta-btn{
    cursor:pointer;
    outline:none;
}

.dh-meta-btn:hover,
.dh-meta-box:hover{
    transform:translateY(-2px);
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
}

.dh-meta-icon{
    width:10px;
    height:10px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#f5f7fb;
    font-size:13px;
    color:#6b7280;
}

.dh-meta-count{
    font-size:14px;
    font-weight:600;
    color:#1f2937;
}

.dh-meta-time{
    margin-left:auto;
    display:flex;
    align-items:center;
    gap:7px;
    font-size:13px;
    color:#6b7280;
}

.likeBtn.liked{
    background:rgba(255, 77, 109, 0.08);
    border-color:rgba(255, 77, 109, 0.18);
}

.likeBtn.liked .dh-meta-icon{
    background:rgba(255, 77, 109, 0.15);
    color:#ff4d6d;
}

.likeBtn.liked .dh-meta-count{
    color:#ff4d6d;
}

@media(max-width:576px){

    .dh-card-meta{
        gap:8px;
    }

    .dh-meta-btn,
    .dh-meta-box{
        padding:4px 10px;
    }

    .dh-meta-time{
        width:100%;
        margin-left:0;
        margin-top:4px;
    }
}
    </style>
</head>

<body>

    <!-- ═══════════════════════════════════════════════════════
         NAVBAR
    ═══════════════════════════════════════════════════════ -->
    <nav class="dh-nav">
        <div class="dh-nav-inner">
            <a class="dh-nav-logo" href="{{ route('home') }}">
                <img src="../frontend/img/dealshood.png" alt="DealsHood">
            </a>

            <button class="dh-nav-toggle" id="navToggle" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>

            <div class="dh-nav-actions" id="navActions">
                <a href="https://www.instagram.com/dealshood?igsh=NHJpdDhkYmJ2dTlj"
                   target="_blank" class="dh-btn-nav dh-btn-ig">
                    <i class="bi bi-instagram"></i> Follow Us
                </a>
                <a href="https://wa.me/918086087050?text=Hello%20I%20am%20interested%20in%20your%20listing"
                   target="_blank" class="dh-btn-nav dh-btn-wa">
                    <i class="bi bi-whatsapp"></i> Contact Us
                </a>
            </div>
        </div>
    </nav>

    <!-- ═══════════════════════════════════════════════════════
         HERO BANNER
    ═══════════════════════════════════════════════════════ -->
    <header class="dh-hero">
        <div class="dh-hero-bg" id="heroBg"></div>
        <div class="dh-hero-overlay"></div>

        <div class="dh-hero-content">
            <h1 class="dh-hero-title">DealsHood</h1>
            <p class="dh-hero-sub">Discover the Best Deals Near You <br> Find great offers from your neighbourhood, every day.</p>
            <div class="dh-hero-socials">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="https://www.instagram.com/dealshood" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Google Plus"><i class="fab fa-google-plus"></i></a>
            </div>
        </div>

        <div class="dh-hero-wave">
            <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 56H1440V28C1200 56 960 8 720 8C480 8 240 56 0 28V56Z" fill="#faf9f7"/>
            </svg>
        </div>
    </header>

    <!-- ═══════════════════════════════════════════════════════
         FILTER BAR
    ═══════════════════════════════════════════════════════ -->
    <section class="dh-filter-section">
        <div class="dh-filter-wrap">
            <div class="dh-filter-inner">
                {{-- action="javascript:void(0)" so AJAX handles submit --}}
                <form id="filterForm" action="javascript:void(0);" method="GET">
                    <div class="dh-filter-grid">

                        <div class="dh-field-group">
                            <select class="dh-field" name="locality_id" id="locality_id">
                                <option value="">All Localities</option>
                                @foreach ($localities as $locality)
                                    <option value="{{ $locality->slug }}"
                                        {{ request('locality_id') == $locality->slug ? 'selected' : '' }}>
                                        {{ $locality->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="dh-field-group">
                            <select class="dh-field" name="category_id" id="category_id">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}"
                                        {{ request('category_id') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="dh-field-group">
                            <select class="dh-field" name="subcategory_id" id="subcategory_id">
                                <option value="">All Sub Categories</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->slug }}"
                                        {{ request('subcategory_id') == $subcategory->slug ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="dh-field-group">
                            <input class="dh-field"
                                   name="keyword" id="keyword"
                                   placeholder="Search deals…"
                                   value="{{ request('keyword') }}"
                                   autocomplete="off">
                        </div>

                        <div class="dh-filter-submit">
                            <button type="submit" class="dh-search-btn">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         POSTS GRID
    ═══════════════════════════════════════════════════════ -->
    <section class="dh-posts-section">
        <div class="dh-container">

            <div class="dh-section-header">
                <div>
                    <div class="dh-section-eyebrow">Latest</div>
                    <h2 class="dh-section-title">Fresh Deals</h2>
                </div>
                <span class="dh-result-count" id="resultCount"></span>
            </div>

            {{-- .dh-grid is the CSS grid; cards slot directly into it --}}
            <div class="dh-grid" id="post-wrapper">
                @include('frontend.post-cards', ['posts' => $posts])
            </div>

            {{-- Animated loader (infinite scroll) --}}
            <div class="dh-loader" id="loading">
                <div class="dh-loader-dots">
                    <span></span><span></span><span></span>
                </div>
            </div>

            {{-- End-of-feed message --}}
            <div class="dh-end-msg" id="endMsg">You've seen all the deals</div>

            {{-- Pagination cursor for AJAX --}}
            <input type="hidden" id="next-page-url" value="{{ $posts->nextPageUrl() }}">

        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════ -->
    <footer class="dh-footer">
        <div class="dh-container">
            <div class="dh-footer-grid">

                <div>
                    <img src="../frontend/img/dealshood.png" alt="DealsHood"
                         style="height:34px;filter:brightness(0) invert(1);opacity:.8;">
                    <p class="dh-footer-brand-name">DealsHood</p>
                    <p class="dh-footer-tagline">Discover the best deals around you.</p>
                    <div class="dh-footer-social">
                        <a href="https://www.facebook.com/CreativeTim/" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="https://twitter.com/creativetim"        target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="https://dribbble.com/creativetim"       target="_blank"><i class="fab fa-dribbble"></i></a>
                        <a href="https://github.com/creativetimofficial"  target="_blank"><i class="fab fa-github"></i></a>
                        <a href="https://www.youtube.com/channel/UCVyTG4sCw-rOvB9oHkzZD1w" target="_blank"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div>
                    <p class="dh-footer-col-title">Company</p>
                    <ul class="dh-footer-links">
                        <li><a href="https://dealshood.com/" target="_blank">About Us</a></li>
                        <li><a href="https://dealshood.com/"          target="_blank">Ads</a></li>
                    </ul>
                </div>

                <div>
                    <p class="dh-footer-col-title">Help & Support</p>
                    <ul class="dh-footer-links">
                        <li><a href="https://dealshood.com/" target="_blank">Contact Us</a></li>
                    </ul>
                </div>

            </div>
            <div class="dh-footer-bottom">
                <p>All rights reserved. Copyright &copy; <span id="footerYear"></span>
                    <a href="https://www.creative-tim.com" target="_blank">DealsHood</a>.
                </p>
            </div>
        </div>
    </footer>

    <!-- ═══════════════════════════════════════════════════════
         SCRIPTS
    ═══════════════════════════════════════════════════════ -->
    <script src="../frontend/js/core/popper.min.js"></script>
    <script src="../frontend/js/core/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
    /* ──────────────────────────────────────────
       BOOT
    ────────────────────────────────────────── */
    document.getElementById('footerYear').textContent = new Date().getFullYear();

    /* ──────────────────────────────────────────
       NAV TOGGLE (mobile)
    ────────────────────────────────────────── */
    document.getElementById('navToggle').addEventListener('click', function () {
        document.getElementById('navActions').classList.toggle('open');
    });

    /* ──────────────────────────────────────────
       HERO PARALLAX
    ────────────────────────────────────────── */
    const heroBg = document.getElementById('heroBg');
    window.addEventListener('scroll', function () {
        if (heroBg) heroBg.style.transform = 'translateY(' + (window.scrollY * 0.28) + 'px)';
    }, { passive: true });

    /* ──────────────────────────────────────────
       LIKE TOGGLE
    ────────────────────────────────────────── */
    $(document).on('click', '.likeBtn', function () {
        const btn = $(this);
        const id  = btn.data('id');
        $.ajax({
            url: '/posts/' + id + '/toggle-like',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                $('#like-count-' + id).text(res.likes);
                res.liked ? btn.addClass('liked') : btn.removeClass('liked');
            }
        });
    });

    /* ──────────────────────────────────────────
       SHARE
    ────────────────────────────────────────── */
    $(document).on('click', '.shareBtn', function () {
        const id  = $(this).data('id');
        const url = $(this).data('url');
        navigator.share
            ? navigator.share({ url: url })
            : (navigator.clipboard.writeText(url), alert('Link copied!'));
        $.ajax({
            url: '/posts/' + id + '/share',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', platform: 'web' }
        });
    });

    /* ──────────────────────────────────────────
       DYNAMIC SUBCATEGORIES
    ────────────────────────────────────────── */
    $('#category_id').on('change', function () {
        const categoryId = $(this).val();
        const sub = $('#subcategory_id');
        sub.empty().append('<option value="">Loading…</option>');
        if (categoryId) {
            $.ajax({
                url: '/get-subcategories/' + categoryId,
                type: 'GET',
                success: function (data) {
                    sub.empty().append('<option value="">All Sub Categories</option>');
                    $.each(data, function (k, v) {
                        sub.append('<option value="' + v.slug + '">' + v.name + '</option>');
                    });
                },
                error: function () {
                    sub.empty().append('<option value="">Error loading</option>');
                }
            });
        } else {
            sub.empty().append('<option value="">All Sub Categories</option>');
        }
    });

    /* ──────────────────────────────────────────
       AJAX FILTER + INFINITE SCROLL
    ────────────────────────────────────────── */
    let loading = false;

    /* Form submit (Search button) */
    document.getElementById('filterForm').addEventListener('submit', function (e) {
        e.preventDefault();
        loadPosts(true);
    });

    /* Auto-trigger on any select change */
    document.querySelectorAll('#filterForm select').forEach(function (el) {
        el.addEventListener('change', function () { loadPosts(true); });
    });

    /* Core load function
       reset = true  → replace grid, update URL
       reset = false → append to grid (infinite scroll)
    */
    function loadPosts(reset, nextPage) {
        if (loading) return;
        loading = true;

        const loader = document.getElementById('loading');
        const endMsg = document.getElementById('endMsg');
        loader.style.display = 'block';
        endMsg.style.display = 'none';

        const form   = document.getElementById('filterForm');
        const params = new URLSearchParams(new FormData(form));
        const url    = nextPage || ('{{ route("home") }}?' + params.toString());

        if (reset) window.history.pushState({}, '', url);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                loader.style.display = 'none';

                const wrapper = document.getElementById('post-wrapper');

                if (reset) {
                    wrapper.innerHTML = data.html;
                } else {
                    wrapper.insertAdjacentHTML('beforeend', data.html);
                }

                /* Stagger-animate newly inserted cards */
                const allCards = wrapper.querySelectorAll('.dh-card');
                allCards.forEach(function (card, i) {
                    card.style.animationDelay = (i * 0.035) + 's';
                });

                /* Update next-page cursor */
                const nextUrl = data.next_page || '';
                document.getElementById('next-page-url').value = nextUrl;

                /* Show end-of-feed when no more pages */
                if (!nextUrl) endMsg.style.display = 'block';

                loading = false;
            })
            .catch(function (err) {
                loading = false;
                loader.style.display = 'none';
                console.error('loadPosts error:', err);
            });
    }

    /* Infinite scroll trigger */
    window.addEventListener('scroll', function () {
        if (loading) return;
        const fromBottom = document.body.offsetHeight - window.scrollY - window.innerHeight;
        if (fromBottom > 320) return;
        const nextPage = document.getElementById('next-page-url').value;
        if (!nextPage) return;
        loadPosts(false, nextPage);
    }, { passive: true });
    
    </script>
    <script type="application/ld+json">
    {!! json_encode([
        '@context'      => 'https://schema.org',
        '@type'         => 'Article',
        'headline'      => $ogTitle,
        'description'   => $ogDescription,
        'image'         => $ogImage,
        'url'           => $ogUrl,
        'datePublished' => '',
        'dateModified'  => '',
        'publisher'     => [
            '@type' => 'Organization',
            'name'  => 'DealsHood',
            'url'   => url('/'),
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

</body>
</html>