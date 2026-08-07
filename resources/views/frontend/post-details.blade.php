<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/frontend/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ site_favicon_url() }}">
    @php
    $siteName = setting('site_name', 'DealsHood');

    /* ── OG image ─────────────────────────────────── */
    /* ── OG image — must be absolute HTTPS, publicly accessible ── */
    $ogImage = $post->getFirstMediaUrl('posts');

    // Fallback to the site's default OG image / logo
    if (!$ogImage) {
        $ogImage = site_og_image_url();
    }

    // Make absolute if relative
    if (!str_starts_with($ogImage, 'http')) {
        $ogImage = url($ogImage);
    }

    // Force HTTPS — WhatsApp blocks HTTP images
    $ogImage = str_replace('http://', 'https://', $ogImage);

    // Strip query strings that can confuse crawlers (Spatie sometimes adds ?v=xxx)
    $ogImage = strtok($ogImage, '?');
    $ogImageExt  = strtolower(pathinfo(parse_url($ogImage, PHP_URL_PATH), PATHINFO_EXTENSION));
    $ogImageMime = match($ogImageExt) {
        'png'  => 'image/png',
        'webp' => 'image/webp',
        'gif'  => 'image/gif',
        default => 'image/jpeg',
    };

    /* ── Core fields ──────────────────────────────── */
    $ogTitle       = $post->meta_title       ?: $post->title;
    $ogDescription = $post->meta_description ?: Str::limit(strip_tags($post->description ?? ''), 160);
    $ogUrl         = url()->current();

    /* ── Keywords — merge post keywords + category + locality + subcategory ── */
    $kwParts = array_filter([
        $post->keywords,
        $post->category?->name,
        $post->subcategory?->name,
        $post->locality?->name,
        $post->company_name,
        $siteName,
        'deals', 'offers', 'classifieds',
    ]);
    $ogKeywords = implode(', ', $kwParts);

    /* ── Richer description — append location/category context ── */
    $richDesc = $ogDescription;
    $ctxParts = array_filter([
        $post->category?->name    ? 'Category: ' . $post->category->name       : null,
        $post->subcategory?->name ? $post->subcategory->name                   : null,
        $post->locality?->name    ? 'in ' . $post->locality->name              : null,
        $post->company_name       ? 'by ' . $post->company_name                : null,
        $post->offer_percentage   ? $post->offer_percentage : null,
        $post->expiry_date && !\Carbon\Carbon::parse($post->expiry_date)->isPast()
            ? 'Valid until ' . \Carbon\Carbon::parse($post->expiry_date)->format('d M Y')
            : null,
    ]);
    if ($ctxParts) $richDesc .= ' — ' . implode(', ', $ctxParts) . '.';
    $richDesc = Str::limit($richDesc, 200);

    /* ── Canonical ────────────────────────────────── */
    $canonical = route('post-details', [
        'locality'    => $post->locality?->slug    ?? 'na',
        'category'    => $post->category?->slug    ?? 'na',
        'subcategory' => $post->subcategory?->slug ?? 'na',
        'slug'        => $post->slug,
    ]);
@endphp

<title>{{ $ogTitle }} — {{ $siteName }}</title>

{{-- ── Core SEO ──────────────────────────────────────── --}}
<meta name="description"  content="{{ $richDesc }}">
<meta name="keywords"     content="{{ $ogKeywords }}">
<meta name="robots"       content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="author"       content="{{ $post->company_name ?: $siteName }}">
<link rel="canonical"     href="{{ $canonical }}">

{{-- ── Open Graph ────────────────────────────────────── --}}
<meta property="og:site_name"        content="{{ $siteName }}">
<meta property="og:type"             content="article">
<meta property="og:locale"           content="en_US">
<meta property="og:title"            content="{{ $ogTitle }}">
<meta property="og:description"      content="{{ $richDesc }}">
<meta property="og:url"              content="{{ $canonical }}">
<meta property="og:image"            content="{{ $ogImage }}">
<meta property="og:image:secure_url" content="{{ $ogImage }}">
<meta property="og:image:type" content="{{ $ogImageMime }}">
<meta property="og:image:width"      content="1200">
<meta property="og:image:height"     content="630">
<meta property="og:image:alt"        content="{{ $ogTitle }}">

{{-- ── Article meta ──────────────────────────────────── --}}
<meta property="article:published_time" content="{{ $post->created_at->toIso8601String() }}">
<meta property="article:modified_time"  content="{{ $post->updated_at->toIso8601String() }}">
@if ($post->category)
<meta property="article:section"        content="{{ $post->category->name }}">
@endif
@if ($post->subcategory)
<meta property="article:tag"            content="{{ $post->subcategory->name }}">
@endif
@if ($post->locality)
<meta property="article:tag"            content="{{ $post->locality->name }}">
@endif

{{-- ── Twitter / X Card ──────────────────────────────── --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $richDesc }}">
<meta name="twitter:image"       content="{{ $ogImage }}">
<meta name="twitter:image:alt"   content="{{ $ogTitle }}">

{{-- ── Geo meta (helps local SEO) ───────────────────── --}}
@if ($post->locality)
<meta name="geo.region"       content="{{ $post->locality->name }}">
<meta name="geo.placename"    content="{{ $post->locality->name }}">
@endif
@if ($post->latitude && $post->longitude)
<meta name="geo.position"     content="{{ $post->latitude }};{{ $post->longitude }}">
<meta name="ICBM"             content="{{ $post->latitude }}, {{ $post->longitude }}">
@endif

{{-- ── PWA / Mobile ──────────────────────────────────── --}}
<link rel="manifest"                              href="/manifest.json">
<meta name="theme-color"                          content="#0f172a">
<meta name="mobile-web-app-capable"               content="yes">
<meta name="apple-mobile-web-app-capable"         content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title"           content="{{ $siteName }}">
<link rel="apple-touch-icon"                      href="/frontend/img/icons/icon-192x192.png">
<link rel="icon" type="image/png"                 href="{{ site_favicon_url() }}">

<script>
// Register service worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => console.log('SW registered:', reg.scope))
            .catch(err => console.log('SW failed:', err));
    });
}

// PWA Install prompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', e => {
    e.preventDefault();
    deferredPrompt = e;
    // Show install button if you have one
    const installBtn = document.getElementById('pwaInstallBtn');
    if (installBtn) installBtn.style.display = 'flex';
});

function installPWA() {
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then(choice => {
        if (choice.outcome === 'accepted') {
            const installBtn = document.getElementById('pwaInstallBtn');
            if (installBtn) installBtn.style.display = 'none';
        }
        deferredPrompt = null;
    });
}
</script>
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Font Awesome --}}
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    {{-- CSS --}}
    <link id="pagestyle" href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet" />

    <style>
    /* ═══════════════════════════════════════════════
       DESIGN TOKENS
    ═══════════════════════════════════════════════ */
    :root {
        --ink:         #0d0d0d;
        --ink-mid:     #3a3a3a;
        --ink-muted:   #6b6b6b;
        --surface:     #faf9f7;
        --surface-2:   #f2f1ef;
        --white:       #ffffff;
        --accent:      #0f3f7e;
        --accent-dim:  rgba(200,16,46,.08);
        --accent-soft: rgba(200,16,46,.2);
        --radius-sm:   8px;
        --radius:      14px;
        --radius-lg:   20px;
        --shadow-sm:   0 2px 12px rgba(0,0,0,.07);
        --shadow-md:   0 6px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.06);
        --shadow-lg:   0 16px 56px rgba(0,0,0,.13), 0 4px 16px rgba(0,0,0,.07);
        --font-serif:  -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
        --font-sans:   -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
        --nav-h:       64px;
    }
    *, *::before, *::after { box-sizing: border-box; }
    body { font-family: var(--font-sans); background: var(--surface); color: var(--ink); margin: 0; }

    /* ── Reading progress ── */
    #reading-progress {
        position: fixed; top: 0; left: 0;
        height: 3px; width: 0%;
        background: var(--accent);
        z-index: 9999;
        transition: width .1s linear;
    }

    /* ── Navbar ── */
    .dh-nav {
        position: fixed; top: 0; left: 0; right: 0;
        height: var(--nav-h);
        background: #fff;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(0,0,0,.07);
        z-index: 1000;
        display: flex; align-items: center;
    }
    .dh-nav .dh-nav-inner {
        display: flex; align-items: center; justify-content: space-between;
        width: 100%; max-width: 1180px; margin: 0 auto; padding: 0 24px;
    }
    .dh-nav-logo img { height: 45px; width: auto; display: block; }
    .dh-nav-actions  { display: flex; align-items: center; gap: 10px; }
    .dh-btn-nav {
        display: inline-flex; align-items: center; gap: 6px;
        font-family: var(--font-sans); font-size: .75rem; font-weight: 500;
        letter-spacing: .04em; border: none; cursor: pointer;
        border-radius: 100px; padding: 9px 18px; text-decoration: none;
        transition: transform .15s;
    }
    .dh-btn-nav:hover { transform: translateY(-1px); }
    .dh-btn-ig { background: #e1306c; color: #fff; }
    .dh-btn-wa { background: #25d366; color: #fff; }
    .dh-nav-toggle {
        display: none; background: none; border: none; cursor: pointer;
        flex-direction: column; gap: 5px; padding: 6px;
    }
    .dh-nav-toggle span {
        display: block; width: 22px; height: 2px;
        background: var(--ink); border-radius: 2px; transition: .2s;
    }
    @media (max-width: 640px) {
        .dh-nav-toggle { display: flex; }
        .dh-nav-actions {
            display: none; position: absolute;
            top: var(--nav-h); left: 0; right: 0;
            background: var(--white);
            border-bottom: 1px solid rgba(0,0,0,.08);
            padding: 16px 24px; flex-direction: column;
            align-items: flex-start; gap: 10px;
            box-shadow: var(--shadow-sm);
        }
        .dh-nav-actions.open { display: flex; }
    }

    /* ── Hero ── */
    .dh-hero {
        padding-top: calc(var(--nav-h) + 48px);
        padding-bottom: 56px;
        background: var(--surface);
    }
    .dh-hero .dh-wrap {
        max-width: 1180px; margin: 0 auto; padding: 0 24px;
    }
    .dh-hero-grid {
        display: grid;
        grid-template-columns: 1fr 440px;
        gap: 48px; align-items: start;
    }
    @media (max-width: 960px) {
        .dh-hero-grid { grid-template-columns: 1fr; gap: 32px; }
        .dh-media-col { order: -1; }
    }

    /* Content column */
    .dh-eyebrow {
        display: inline-flex; align-items: center; gap: 9px;
        font-size: .68rem; font-weight: 500; letter-spacing: .14em;
        text-transform: uppercase; color: var(--accent); margin-bottom: 16px;
    }
    .dh-eyebrow::before {
        content: ''; display: inline-block;
        width: 24px; height: 2px;
        background: var(--accent); border-radius: 2px;
    }
    .dh-title {
        font-family: var(--font-serif);
        font-size: clamp(1.9rem, 3.5vw, 2.9rem);
        font-weight: 700; line-height: 1.17;
        color: var(--ink); margin: 0 0 20px; letter-spacing: -.02em;
    }
    .dh-meta {
        display: flex; flex-wrap: wrap; gap: 5px 16px;
        font-size: .78rem; color: var(--ink-muted);
        margin-bottom: 24px; padding-bottom: 22px;
        border-bottom: 1px solid rgba(0,0,0,.08);
    }
    .dh-meta-item { display: flex; align-items: center; gap: 5px; }
    .dh-excerpt {
        font-size: 1rem; line-height: 1.76;
        color: var(--ink-muted); font-weight: 300; margin-bottom: 34px;
    }
.dh-actions {
    display: flex;
    flex-wrap: nowrap;
    gap: 8px;
    align-items: center;
}
.dh-actions .dh-btn {
    flex: 1;
    padding: 10px 12px;
    font-size: .76rem;
    white-space: nowrap;
    justify-content: center;
}

    /* Buttons */
    .dh-btn {
        display: inline-flex; align-items: center; gap: 7px;
        font-family: var(--font-sans); font-size: .78rem; font-weight: 500;
        letter-spacing: .04em; border: none; cursor: pointer;
        border-radius: 100px; padding: 11px 24px;
        transition: transform .18s, box-shadow .18s, background .15s;
        text-decoration: none;
    }
    .dh-btn:hover  { transform: translateY(-2px); }
    .dh-btn:active { transform: translateY(0); }
    .dh-btn-read {
        background: var(--ink); color: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,.2);
    }
    .dh-btn-read:hover { box-shadow: 0 8px 28px rgba(0,0,0,.28); color: #fff; }
    .dh-btn-like {
        background: var(--white); color: var(--accent);
        box-shadow: var(--shadow-sm); border: 1.5px solid var(--accent-soft);
    }
    .dh-btn-like:hover { background: var(--accent-dim); }
    .dh-btn-like.liked { background: var(--accent); color: #fff; border-color: var(--accent); }
    .dh-btn-share {
        background: var(--white); color: var(--ink-muted);
        box-shadow: var(--shadow-sm); border: 1.5px solid rgba(0,0,0,.1);
    }
    .dh-btn-share:hover { background: var(--surface-2); }

    /* Media card */
    .dh-media-card {
        border-radius: var(--radius-lg); overflow: hidden;
        box-shadow: var(--shadow-lg); background: #111; position: relative;
    }
    .dh-media-badge {
        position: absolute; top: 14px; left: 14px;
        background: var(--accent); color: #fff;
        font-size: .62rem; font-weight: 600; letter-spacing: .12em;
        text-transform: uppercase; padding: 5px 12px;
        border-radius: 100px; z-index: 5;
    }
    .dh-media-count {
        position: absolute; top: 14px; right: 14px;
        background: rgba(0,0,0,.5); color: #fff;
        font-size: .68rem; font-weight: 500; padding: 4px 11px;
        border-radius: 100px; backdrop-filter: blur(6px); z-index: 5;
    }
    .dh-media-card video { width: 100%; display: block; max-height: 500px; object-fit: cover; }
    .dh-single-img { width: 100%; height: clamp(220px, 55vw, 480px); object-fit: contain; background: #111; display: block; cursor: zoom-in; }

    /* Carousel */
    .dh-carousel .carousel-item img { display: block; width: 100%; height: clamp(220px, 55vw, 480px); object-fit: contain; background: #111; cursor: zoom-in; }
    .dh-carousel .carousel-control-prev,
    .dh-carousel .carousel-control-next {
        width: 42px; height: 42px;
        background: rgba(255,255,255,.15); backdrop-filter: blur(6px);
        border-radius: 50%; top: 50%; bottom: auto;
        transform: translateY(-50%); opacity: 1;
    }
    .dh-carousel .carousel-control-prev { left: 14px; }
    .dh-carousel .carousel-control-next { right: 14px; }
    .dh-carousel .carousel-control-prev-icon,
    .dh-carousel .carousel-control-next-icon { width: 18px; height: 18px; }

    /* Thumbnails */
    .dh-thumbs {
        display: flex; gap: 6px; padding: 9px 10px;
        background: rgba(0,0,0,.6); backdrop-filter: blur(4px);
        overflow-x: auto; scrollbar-width: none;
    }
    .dh-thumbs::-webkit-scrollbar { display: none; }
    .dh-thumb {
        flex: 0 0 54px; height: 38px; border-radius: 6px; overflow: hidden;
        border: 2px solid transparent; cursor: pointer; opacity: .6; transition: all .15s;
    }
    .dh-thumb:hover { opacity: .85; }
    .dh-thumb.active { border-color: #fff; opacity: 1; }
    .dh-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .dh-thumb video { width: 100%; height: 100%; object-fit: cover; }
    .dh-thumb-video { position: relative; }
    .dh-thumb-play {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: .7rem; background: rgba(0,0,0,.35);
        pointer-events: none;
    }

    /* Video inside carousel — match image sizing */
    .dh-carousel .carousel-item video {
        display: block; width: 100%;
        height: clamp(220px, 55vw, 480px);
        object-fit: contain; background: #111;
    }

    /* ── Content section ── */
    .dh-content-section { padding: 56px 0 80px; background: var(--white); }
    .dh-content-section .dh-wrap {
        max-width: 820px; margin: 0 auto; padding: 0 24px;
    }
    .dh-section-label {
        display: inline-flex; align-items: center; gap: 9px;
        font-size: .68rem; font-weight: 500; letter-spacing: .14em;
        text-transform: uppercase; color: var(--accent); margin-bottom: 20px;
    }
    .dh-section-label::before {
        content: ''; display: inline-block;
        width: 24px; height: 2px; background: var(--accent); border-radius: 2px;
    }
    .dh-content-body {
        font-size: 1.02rem; line-height: 1.82;
        color: var(--ink-mid); font-weight: 300; margin-bottom: 48px;
    }
    .dh-content-body h1,.dh-content-body h2,
    .dh-content-body h3,.dh-content-body h4 {
        font-family: var(--font-serif); color: var(--ink); margin-top: 2em;
    }
    .dh-content-body img { width: 100%; border-radius: var(--radius); margin: 1.5em 0; }
    .dh-content-body a   { color: var(--accent); }

    /* Contact card */
    .dh-contact-card {
        background: var(--surface); border-radius: var(--radius-lg);
        border: 1px solid rgba(0,0,0,.07); padding: 32px 36px;
        box-shadow: var(--shadow-sm);
    }
    .dh-contact-title {
        font-family: var(--font-serif); font-size: 1.3rem;
        font-weight: 700; color: var(--ink); margin: 0 0 24px;
    }
    .dh-contact-row {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,.06); font-size: .9rem;
    }
    .dh-contact-row:last-of-type { border-bottom: none; }
    .dh-contact-icon { font-size: 1rem; width: 20px; flex-shrink: 0; margin-top: 2px; color: var(--accent); }
    .dh-contact-label { font-weight: 500; color: var(--ink); min-width: 80px; }
    .dh-contact-value { color: var(--ink-muted); font-weight: 300; }
    .dh-contact-value a { color: var(--accent); text-decoration: none; }
    .dh-contact-btns {
    display: flex;
    flex-wrap: nowrap;
    gap: 8px;
    margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(0,0,0,.06);
}
.dh-cta-btn {
    flex: 1;
    padding: 11px 10px;
    font-size: .78rem;
    white-space: nowrap;
    justify-content: center;
    text-align: center;
}
    .dh-cta-btn {
        display: inline-flex; align-items: center; gap: 7px;
        font-family: var(--font-sans); font-size: .8rem; font-weight: 500;
        border-radius: 100px; padding: 11px 22px;
        text-decoration: none; transition: transform .15s, box-shadow .15s; border: none;
    }
    .dh-cta-btn:hover { transform: translateY(-2px); }
    .dh-cta-call { background: var(--ink); color: #fff; box-shadow: 0 4px 16px rgba(0,0,0,.18); }
    .dh-cta-call:hover { color: #fff; box-shadow: 0 8px 24px rgba(0,0,0,.26); }
    .dh-cta-wa   { background: #25d366; color: #fff; box-shadow: 0 4px 16px rgba(37,211,102,.3); }
    .dh-cta-wa:hover { color: #fff; }

    /* ── Footer ── */
    .dh-footer { background: var(--ink); color: rgba(255,255,255,.7); padding: 64px 0 0; font-size: .85rem; }
    .dh-footer .dh-wrap { max-width: 1180px; margin: 0 auto; padding: 0 24px; }
    .dh-footer-grid {
        display: grid; grid-template-columns: 1.4fr repeat(2, 1fr);
        gap: 40px; padding-bottom: 48px;
    }
    @media (max-width: 720px) { .dh-footer-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 440px) { .dh-footer-grid { grid-template-columns: 1fr; } }
    .dh-footer-brand-name { font-family: var(--font-serif); font-size: 1.15rem; color: #fff; margin: 12px 0 5px; }
    .dh-footer-tagline    { font-size: .78rem; color: rgba(255,255,255,.4); margin: 0; }
    .dh-footer-social     { display: flex; gap: 8px; margin-top: 20px; }
    .dh-footer-social a {
        width: 34px; height: 34px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,.15);
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,.6); font-size: .9rem;
        text-decoration: none; transition: border-color .15s, color .15s;
    }
    .dh-footer-social a:hover { border-color: rgba(255,255,255,.5); color: #fff; }
    .dh-footer-col-title {
        font-size: .65rem; font-weight: 600; letter-spacing: .14em;
        text-transform: uppercase; color: var(--accent); margin-bottom: 16px;
    }
    .dh-footer-links { list-style: none; padding: 0; margin: 0; }
    .dh-footer-links li { margin-bottom: 10px; }
    .dh-footer-links a { color: rgba(255,255,255,.55); text-decoration: none; transition: color .15s; }
    .dh-footer-links a:hover { color: #fff; }
    .dh-footer-bottom {
        border-top: 1px solid rgba(255,255,255,.08); text-align: center;
        padding: 22px 0; font-size: .75rem; color: rgba(255,255,255,.3);
    }
    .dh-footer-bottom a { color: rgba(255,255,255,.48); text-decoration: none; }
    .dh-footer-bottom a:hover { color: #fff; }

    /* ── Lightbox ── */
    .dh-lightbox .modal-content { background: rgba(0,0,0,.96); border: none; border-radius: 0; }
    .dh-lightbox .dh-lb-img {
        max-height: 90vh; max-width: 90vw; object-fit: contain;
        border-radius: var(--radius); display: block; margin: auto; transition: opacity .25s;
    }
    .dh-lb-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 48px; height: 48px;
        background: rgba(255,255,255,.12); backdrop-filter: blur(6px);
        border: 1px solid rgba(255,255,255,.2); border-radius: 50%;
        color: #fff; font-size: 1.4rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .15s; z-index: 10;
    }
    .dh-lb-btn:hover { background: rgba(255,255,255,.22); }
    .dh-lb-prev { left: 20px; }
    .dh-lb-next { right: 20px; }
    .dh-lb-close {
        position: absolute; top: 18px; right: 18px;
        width: 40px; height: 40px;
        background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
        border-radius: 50%; color: #fff; font-size: 1.1rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 10; transition: background .15s;
    }
    .dh-lb-close:hover { background: rgba(255,255,255,.2); }
    .dh-lb-counter {
        position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%);
        color: rgba(255,255,255,.6); font-size: .78rem; letter-spacing: .06em;
    }

    /* ── Animations ── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .dh-hero .dh-eyebrow   { animation: fadeUp .5s .05s both; }
    .dh-hero .dh-title     { animation: fadeUp .5s .12s both; }
    .dh-hero .dh-meta      { animation: fadeUp .5s .18s both; }
    .dh-hero .dh-excerpt   { animation: fadeUp .5s .24s both; }
    .dh-hero .dh-actions   { animation: fadeUp .5s .30s both; }
    .dh-hero .dh-media-col { animation: fadeUp .6s .08s both; }

/* ── PWA / app feel ── */
@media (max-width: 768px) {

    /* Scroll snap on hero category tiles */
    .dh-hero-panel .dh-glass-grid {
        flex-wrap: nowrap;
        overflow-x: auto;
        justify-content: flex-start;
        padding: 0 16px 12px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        scroll-snap-type: x proximity;
    }
    .dh-hero-panel .dh-glass-grid::-webkit-scrollbar { display: none; }
    .dh-gtile { scroll-snap-align: start; flex-shrink: 0; }

    /* Hero height tighter on mobile */
    .dh-hero-panel { padding: 20px 0 52px; }
    .dh-hero-text  { padding: 28px 24px 0; }

    /* Carousels scroll snap */
    .dh-track {
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        padding-left: 16px;
    }
    .dh-track .dh-card { scroll-snap-align: start; }
    .dh-track .dh-card:last-child { margin-right: 16px; }

    /* Latest deals — 2-col grid on small phones, 1-col on very small */
    .dh-grid {
        grid-template-columns: repeat(2,1fr);
        gap: 12px;
    }
    @media (max-width: 380px) {
        .dh-grid { grid-template-columns: 1fr; }
    }

    /* Card body tighter */
    .dh-card-body { padding: 12px 14px 14px; }
    .dh-card-title { font-size: .88rem; }
    .dh-card-desc  { display: none; } /* hide on mobile to save space */

    /* Section heading tighter */
    .dh-sec-head   { margin-bottom: 14px; }
    .dh-sec-title  { font-size: 1.15rem; }

    /* Listing chips label hidden */
    .dh-chips-label .label-text { display: none; }

    /* Listing toolbar — stack on tiny screens */
    .dh-toolbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    /* Sort pills — scroll, don't wrap */
    .dh-sort-pills {
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding-bottom: 2px;
    }
    .dh-sort-pills::-webkit-scrollbar { display: none; }

    /* Post detail description font */
    .dh-content-body { font-size: .94rem; line-height: 1.78; }

    /* Reading progress bar */
    #reading-progress { height: 2px; }

    /* Load more button full width */
    .dh-more-btn { width: 100%; justify-content: center; }

    /* Footer — single column */
    .dh-footer-grid { grid-template-columns: 1fr !important; gap: 28px; }
    .dh-footer { padding: 36px 0 0; }
}

/* ── Smooth momentum scroll globally ── */
* { -webkit-overflow-scrolling: touch; }

/* ── Remove ugly mobile tap highlight everywhere ── */
* { -webkit-tap-highlight-color: transparent; }

/* ── Prevent zoom on input focus (iOS) ── */
@media (max-width: 768px) {
    input, select, textarea { font-size: 16px !important; }
}
@media(max-width: 400px) {
    .dh-actions .dh-btn  { font-size: .7rem; padding: 9px 8px; gap: 4px; }
    .dh-cta-btn          { font-size: .72rem; padding: 10px 8px; gap: 4px; }
    .dh-cta-btn i        { display: none; } /* hide icons on tiny screens to save space */
}
@media(max-width: 480px) {
    .dh-contact-btns {
        flex-direction: column;
        gap: 8px;
    }
    .dh-cta-btn {
        width: 100%;
        justify-content: center;
    }
    .dh-hero{
        padding-top:10px !important;
    }
}
.dh-cta-map {
    background: #f1f5f9;
    color: var(--ink);
    border: 1.5px solid rgba(0,0,0,.1);
}
.dh-cta-map:hover {
    background: var(--surface-2);
    color: var(--ink);
}
/* ── Offer stamp (overlaps bottom-right of media) ── */
.dh-media-col { position: relative; }

.dh-offer-stamp {
    position: absolute;
    bottom: -16px;
    right: 20px;
    width: 76px;
    height: 76px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626, #f97316);
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    line-height: 1;
    text-align: center;
    box-shadow: 0 8px 22px rgba(220,38,38,.35), 0 0 0 4px var(--white);
    z-index: 6;
    transform: rotate(-8deg);
}
.dh-offer-stamp .pct {
    font-size: 1.2rem;
    font-weight: 800;
    letter-spacing: -.02em;
}
.dh-offer-stamp .lbl {
    font-size: .56rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    margin-top: 1px;
    opacity: .9;
}

@media (max-width: 768px) {
    .dh-offer-stamp {
        width: 62px;
        height: 62px;
        bottom: -12px;
        right: 14px;
    }
    .dh-offer-stamp .pct { font-size: 1rem; }
    .dh-offer-stamp .lbl { font-size: .5rem; }
}
@media (max-width: 768px) {
    .dh-nav-toggle,
    .dh-nav-actions {
        display: none !important;
    }
}

.post-ad-fab {
    bottom: calc(var(--bot-nav-h, 60px) + env(safe-area-inset-bottom, 0px) + 75px) !important;
}
/* ── Disclaimer ── */
.dh-disclaimer {
    margin-top: 24px;
    padding: 18px 22px;
    background: var(--surface-2);
    border: 1px dashed rgba(0,0,0,.12);
    border-radius: var(--radius);
    font-size: .78rem;
    line-height: 1.6;
    color: var(--ink-muted);
}
.dh-disclaimer strong {
    color: var(--ink-mid);
    font-weight: 600;
}
/* ── Star rating ── */
/* ── Star rating — interactive (post detail page) ── */
.dh-rating-input { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
.dh-rating-input .dh-rating-stars { display: flex; gap: 3px; }
.dh-rating-input .dh-star {
    font-size: 1.05rem;
    color: rgba(0,0,0,.15);
    cursor: pointer;
    transition: color .12s, transform .12s;
}
.dh-rating-input .dh-star:hover,
.dh-rating-input .dh-star.hover { color: #f59e0b; transform: scale(1.12); }
.dh-rating-input .dh-star.active { color: #f59e0b; }
.dh-rating-input .dh-rating-avg  { font-weight: 700; font-size: .88rem; color: var(--ink); }
.dh-rating-input .dh-rating-count { font-size: .74rem; color: var(--ink-muted); }

/* ── Star rating — read-only average display (same as post-single-card) ── */
.dh-rating-view { display: flex; align-items: center; gap: 6px; }
.dh-rating-view .dh-star-big-wrap {
    position: relative;
    display: inline-block;
    font-size: 1.1rem;
    line-height: 1;
    color: rgba(0,0,0,.15);
}
.dh-rating-view .dh-star-big-fg {
    position: absolute;
    top: 0; left: 0;
    overflow: hidden;
    white-space: nowrap;
    color: #f59e0b;
}
.dh-rating-view .dh-rating-avg-sm { font-size: .84rem; font-weight: 700; color: var(--ink); }
.dh-rating-view .dh-rating-count-sm { font-size: .74rem; color: var(--ink-muted); }

/* ── Rate this deal (bottom of page) ── */
.dh-rate-card { margin-top: 24px; }
.dh-rate-card .dh-rating-input { margin-bottom: 0; }
</style>
</head>

<body>

    <div id="reading-progress"></div>
{{-- <nav class="dh-nav">
        <div class="dh-nav-inner">
            <a class="dh-nav-logo" href="{{ route('home') }}">
                <img src="/frontend/img/dealshood.png" alt="DealsHood">
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
    </nav> --}}
    {{-- ═══════════════════════════════════════ NAVBAR ════════════════════════════════════════ --}}
    <nav class="dh-nav" style="position:sticky;top:0;">
    <div class="dh-nav-inner" style="position:relative;">
        <a href="javascript:history.back()" class="dh-nav-back">
            <i class="fas fa-chevron-left"></i>
            <span class="d-none d-sm-inline">Back</span>
        </a>

        <span class="dh-nav-page-title">{{ Str::limit($post->title, 28) }}</span>

        <a href="{{ route('home') }}" class="dh-nav-logo d-none d-md-block">
            <img src="{{ site_logo_url() }}" alt="{{ $siteName }}">
        </a>
        <div class="dh-nav-actions" style="display:flex;align-items:center;gap:10px;">
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

    {{-- ═══════════════════════════════════════ HERO ═══════════════════════════════════════════ --}}
    <header class="dh-hero">
        @php
            $images = $post->getMedia('posts');
            $video  = $post->getFirstMediaUrl('videos');

            $ratingCount = $post->ratingsData->count();
            $avgRating   = $ratingCount ? round($post->ratingsData->avg('rating'), 1) : 0;
            $ratingFillPct = $avgRating > 0 ? ($avgRating / 5) * 100 : 0;
        @endphp

        <div class="dh-wrap">
            <div class="dh-hero-grid">

                {{-- CONTENT COLUMN --}}
                <div class="dh-content-col">
                    <span class="dh-eyebrow">{{ optional($post->category)->name ?? 'General' }}</span>
                    <h1 class="dh-title">{{ $post->title }}</h1>

                    <div class="dh-rating-view" style="margin-bottom:14px;">
                        <span class="dh-star-big-wrap">
                            <i class="fas fa-star"></i>
                            <span class="dh-star-big-fg" id="heroStarFg" style="width: {{ $ratingFillPct }}%;">
                                <i class="fas fa-star"></i>
                            </span>
                        </span>
                        <span class="dh-rating-avg-sm" id="heroRatingAvg">{{ $avgRating > 0 ? number_format($avgRating, 1) : '' }}</span>
                        <span class="dh-rating-count-sm" id="heroRatingCount">({{ number_format($ratingCount) }})</span>
                    </div>

                    <div class="dh-meta">
                        @if($post->locality)
                            <span class="dh-meta-item">📍 {{ $post->locality->name }}</span>
                        @endif
                        @if($post->additionalLocalities->isNotEmpty())
                            <span class="dh-meta-item">📍 Also in: {{ $post->additionalLocalities->pluck('name')->join(', ') }}</span>
                        @endif
                        @if($post->expiry_date)
                            @if(\Carbon\Carbon::parse($post->expiry_date)->isPast())
                                <span class="dh-meta-item" style="color:#dc2626;">
                                    ❌ Expired on {{ \Carbon\Carbon::parse($post->expiry_date)->format('d M Y') }}
                                </span>
                            @else
                                <span class="dh-meta-item" style="color:#059669;">
                                    ✅ Valid until {{ \Carbon\Carbon::parse($post->expiry_date)->format('d M Y') }}
                                </span>
                            @endif
                        @else
                            <span class="dh-meta-item">🕒 {{ $post->created_at->diffForHumans() }}</span>
                        @endif
                        <span class="dh-meta-item">👁 {{ number_format($post->viewsData->count()) }} views</span>
                    </div>

                    <p class="dh-excerpt">
                        {{ \Illuminate\Support\Str::limit(strip_tags($post->description), 200) }}
                    </p>

                    <div class="dh-actions">
                        <a href="#content" class="dh-btn dh-btn-read">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
                            Show Details
                        </a>
                        <button class="dh-btn dh-btn-like likeBtn" data-id="{{ $post->id }}">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            Like &nbsp;<strong id="like-count-{{ $post->id }}">{{ number_format($post->likesData->count()) }}</strong>
                        </button>
                        <button class="dh-btn dh-btn-share shareBtn"
                                data-id="{{ $post->id }}"
                                data-url="{{ $post->url }}">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                            Share
                        </button>
                    </div>
                </div>

                {{-- MEDIA COLUMN --}}
                @php
                    $hasVideo   = (bool) $video;
                    $imageCount = $images->count();
                    $totalMedia = ($hasVideo ? 1 : 0) + $imageCount;
                    // carousel slide offset for images when a video occupies slide 0
                    $imgOffset  = $hasVideo ? 1 : 0;
                @endphp
                <div class="dh-media-col">
                    <div class="dh-media-card">
                        @if($totalMedia > 1)
                            {{-- Combined gallery: video (if any) first, then images --}}
                            <span class="dh-media-count">1 / {{ $totalMedia }}</span>
                            <div id="dhCarousel" class="carousel slide dh-carousel" data-bs-ride="{{ $hasVideo ? 'false' : 'carousel' }}">
                                <div class="carousel-inner">
                                    @if($hasVideo)
                                        <div class="carousel-item active">
                                            <video controls preload="metadata"><source src="{{ $video }}"></video>
                                        </div>
                                    @endif
                                    @foreach($images as $k => $media)
                                        <div class="carousel-item {{ (!$hasVideo && $k == 0) ? 'active' : '' }}">
                                            <img src="{{ $media->getUrl() }}"
                                                 alt="Post image {{ $k + 1 }}"
                                                 class="openGallery"
                                                 data-image="{{ $media->getUrl() }}"
                                                 data-index="{{ $k }}">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#dhCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#dhCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>
                            <div class="dh-thumbs">
                                @if($hasVideo)
                                    <div class="dh-thumb dh-thumb-video active" data-index="0">
                                        <video muted preload="metadata"><source src="{{ $video }}"></video>
                                        <span class="dh-thumb-play"><i class="fas fa-play"></i></span>
                                    </div>
                                @endif
                                @foreach($images as $k => $media)
                                    <div class="dh-thumb {{ (!$hasVideo && $k == 0) ? 'active' : '' }}" data-index="{{ $k + $imgOffset }}">
                                        <img src="{{ $media->getUrl() }}" alt="">
                                    </div>
                                @endforeach
                            </div>

                        @elseif($hasVideo)
                            <span class="dh-media-badge">▶ Video</span>
                            <video controls><source src="{{ $video }}"></video>

                        @elseif($imageCount == 1)
                            <img src="{{ $images->first()->getUrl() }}"
                                 class="dh-single-img openGallery"
                                 data-image="{{ $images->first()->getUrl() }}"
                                 data-index="0"
                                 alt="{{ $post->title }}">

                        @else
                            <img src="{{ asset('frontend/img/default.jpg') }}" class="dh-single-img" alt="Default">
                        @endif

                    </div>
                    @if ($post->offer_percentage)
                        <div class="dh-offer-stamp">
                            <span class="pct">{{ $post->offer_percentage }}</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </header>

    {{-- ═══════════════════════════════════════ CONTENT ════════════════════════════════════════ --}}
    <section id="content" class="dh-content-section">
        <div class="dh-wrap">
            <span class="dh-section-label">Details</span>

            <div class="dh-content-body">
                {!! $post->description !!}
            </div>

            @php $user = $post->user; @endphp
            <div class="dh-contact-card">
                <p class="dh-contact-title">Contact Information</p>

                <div class="dh-contact-row">
                    <span class="dh-contact-icon">🏢</span>
                    <span class="dh-contact-label">{{ $post->company_name ?? 'N/A' }}</span>
                </div>
                <div class="dh-contact-row">
                    <span class="dh-contact-icon">📍</span>
                    <span class="dh-contact-label">{{ $post->location ?? 'Not available' }}</span>
                </div>

                @if($post->phone_number||$post->whatsapp_number||$post->google_map_url)
                <div class="dh-contact-btns">
                @if($post->phone_number)
                    <a href="tel:{{ $user->phone_number }}" class="dh-cta-btn dh-cta-call">
                        <i class="bi bi-telephone-fill"></i> Call Now
                    </a>
                @endif
                @if($post->whatsapp_number)
                    <a href="{{$post->whatsapp_link}}"
                       target="_blank" class="dh-cta-btn dh-cta-wa">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                @endif
                @if($post->google_map_url)
                    <a href="{{$post->google_map_url}}"
                       target="_blank" class="dh-cta-btn dh-cta-map">
                        <i class="bi bi-geo-alt-fill"></i> Get Directions
                    </a>
                @endif
                </div>
                @endif
            </div>

            {{-- Rate this deal --}}
            <div class="dh-contact-card dh-rate-card">
                <p class="dh-contact-title">Rate this Deal</p>
                <div class="dh-rating-input" data-post-id="{{ $post->id }}" data-current="{{ $userRating ?? 0 }}">
                    <div class="dh-rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star dh-star {{ $userRating && $i <= $userRating ? 'active' : '' }}" data-value="{{ $i }}"></i>
                        @endfor
                    </div>
                    <span class="dh-rating-avg">{{ $avgRating > 0 ? number_format($avgRating, 1) : '0.0' }}</span>
                    <span class="dh-rating-count">({{ number_format($ratingCount) }} ratings)</span>
                </div>
            </div>

            @if($post->disclaimer)
            <div class="dh-disclaimer">
                <strong>Disclaimer:</strong> {{ $post->disclaimer }}
            </div>
        @elseif($post->category_id==1)
            <div class="dh-disclaimer">
                <strong>Disclaimer:</strong>: We help you to discover the best offers and services in your area.Offers and services listed are provided by third parties. Please contact the business directly to confirm details before visiting. DealsHood is not responsible for any service issues , disputes or changes in offers.
            </div>
        @elseif($post->category_id==2||$post->category_id==3)
            <div class="dh-disclaimer">
                <strong>Disclaimer:</strong>Dealshood aims to connect customers with nearby services for better accessibility and convenience. If any listed business prefers not to appear on our platform, they may contact us directly and the listing will be removed upon request.
            </div>
        @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════ FOOTER ════════════════════════════════════════ --}}
    <footer class="dh-footer">
    <div class="wrap">
        <div class="dh-footer-grid">
            <div>
                <img src="{{ site_logo_url() }}" alt="{{ $siteName }}"
                     style="height:32px;filter:brightness(0) invert(1);opacity:.8;">
                <p class="dh-footer-brand">DealsHood</p>
                <p class="dh-footer-tag">Discover the best deals around you.</p>
                <div class="dh-footer-social">
                    <a href="https://www.facebook.com/share/1DA56kRCJp"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/dealshood" target="_blank"><i class="fab fa-instagram"></i></a>
                    {{-- <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a> --}}
                </div>
            </div>
            {{-- <div>
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
            </div> --}}
        </div>
        <div class="dh-footer-bottom">
            <p>&copy; <span id="footerYear"></span> <a href="#">DealsHood</a>. All rights reserved.</p>
        </div>
    </div>
</footer>

    {{-- ═══════════════════════════════════════ LIGHTBOX ═══════════════════════════════════════ --}}
    <div class="modal fade dh-lightbox" id="galleryLightbox" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content position-relative d-flex align-items-center justify-content-center">
                <button class="dh-lb-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                <img id="lightboxImage" src="" class="dh-lb-img" alt="">
                <button class="dh-lb-btn dh-lb-prev" id="prevImage">&#8249;</button>
                <button class="dh-lb-btn dh-lb-next" id="nextImage">&#8250;</button>
                <div class="dh-lb-counter">
                    <span id="currentImageIndex">1</span> / <span id="totalImages">1</span>
                </div>
            </div>
        </div>
    </div>

@include('frontend.frontend-mobile')

@include('frontend.post-ad-modal')
    {{-- ═══════════════════════════════════════ SCRIPTS ════════════════════════════════════════ --}}
    <script src="/frontend/js/core/popper.min.js"></script>
    <script src="/frontend/js/core/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
function updateHeroRating(avg, total) {
    $('#heroRatingAvg').text(avg > 0 ? avg.toFixed(1) : '');
    $('#heroRatingCount').text('(' + total + ')');
    $('#heroStarFg').css('width', (avg > 0 ? (avg / 5) * 100 : 0) + '%');
}
$(document).on('mouseenter', '.dh-rating-input .dh-star', function () {
    const val = $(this).data('value');
    $(this).parent().children('.dh-star').each(function () {
        $(this).toggleClass('hover', $(this).data('value') <= val);
    });
});
$(document).on('mouseleave', '.dh-rating-input .dh-rating-stars', function () {
    $(this).children('.dh-star').removeClass('hover');
});
$(document).on('click', '.dh-rating-input .dh-star', function () {
    const val     = $(this).data('value');
    const wrap    = $(this).closest('.dh-rating-input');
    const postId  = wrap.data('post-id');
    const current = parseInt(wrap.data('current') || 0);

    if (val === current) {
        $.ajax({
            url: '/posts/' + postId + '/rate',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                wrap.data('current', 0);
                wrap.find('.dh-star').removeClass('active');
                wrap.find('.dh-rating-avg').text(res.avg_rating.toFixed(1));
                wrap.find('.dh-rating-count').text('(' + res.total + ' ratings)');
                updateHeroRating(res.avg_rating, res.total);
            }
        });
        return;
    }

    $.ajax({
        url: '/posts/' + postId + '/rate',
        type: 'POST',
        data: { _token: '{{ csrf_token() }}', rating: val },
        success: function (res) {
            wrap.data('current', val);
            wrap.find('.dh-star').each(function () {
                $(this).toggleClass('active', $(this).data('value') <= val);
            });
            wrap.find('.dh-rating-avg').text(res.avg_rating.toFixed(1));
            wrap.find('.dh-rating-count').text('(' + res.total + ' ratings)');
            updateHeroRating(res.avg_rating, res.total);
        }
    });
});
    document.getElementById('footerYear').textContent = new Date().getFullYear();

    // Nav toggle
    // document.getElementById('navToggle').addEventListener('click', function () {
    //     document.getElementById('navActions').classList.toggle('open');
    // });

    // Reading progress
    window.addEventListener('scroll', function () {
        const doc = document.documentElement;
        const pct = (doc.scrollTop || document.body.scrollTop) / (doc.scrollHeight - doc.clientHeight) * 100;
        document.getElementById('reading-progress').style.width = pct + '%';
    }, { passive: true });

    // Carousel thumbnail sync
    (function () {
        const carousel = document.getElementById('dhCarousel');
        const thumbs   = document.querySelectorAll('.dh-thumb');
        const counter  = document.querySelector('.dh-media-count');
        if (!carousel || !thumbs.length) return;
        carousel.addEventListener('slid.bs.carousel', function (e) {
            thumbs.forEach(t => t.classList.remove('active'));
            thumbs[e.to]?.classList.add('active');
            if (counter) counter.textContent = (e.to + 1) + ' / ' + thumbs.length;
        });
        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                bootstrap.Carousel.getOrCreateInstance(carousel).to(parseInt(this.dataset.index));
            });
        });
    })();

    // Like
    $(document).on('click', '.likeBtn', function () {
        const btn = $(this), id = btn.data('id');
        $.ajax({
            url: '/posts/' + id + '/toggle-like', type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                $('#like-count-' + id).text(res.likes);
                res.liked ? btn.addClass('liked') : btn.removeClass('liked');
            }
        });
    });

    // Share
    $(document).on('click', '.shareBtn', async function () {
        const id = $(this).data('id');
        let url = $(this).data('url');

        try {
            const res  = await fetch('{{ route("shorten") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ url }),
            });
            const data = await res.json();
            if (res.ok && data.short_url) url = data.short_url;
        } catch (e) { /* keep the full URL */ }

        navigator.share ? navigator.share({ url }) : (navigator.clipboard.writeText(url), alert('Link copied!'));
        $.ajax({ url: '/posts/' + id + '/share', type: 'POST', data: { _token: '{{ csrf_token() }}', platform: 'web' } });
    });

    // Lightbox
    document.addEventListener('DOMContentLoaded', function () {
        let galleryImages = [], currentIndex = 0;

        document.querySelectorAll('.openGallery').forEach(function (item) {
            galleryImages.push(item.dataset.image);
            item.addEventListener('click', function () {
                currentIndex = parseInt(this.dataset.index);
                openLightbox(currentIndex);
            });
        });

        const modalEl        = document.getElementById('galleryLightbox');
        const modal          = modalEl ? new bootstrap.Modal(modalEl) : null;
        const lightboxImage  = document.getElementById('lightboxImage');
        const currentIndexEl = document.getElementById('currentImageIndex');
        const totalEl        = document.getElementById('totalImages');
        if (totalEl) totalEl.textContent = galleryImages.length;

        function openLightbox(index) {
            if (!modal) return;
            lightboxImage.style.opacity = '0';
            lightboxImage.src = galleryImages[index];
            lightboxImage.onload = function () { lightboxImage.style.opacity = '1'; };
            if (currentIndexEl) currentIndexEl.textContent = index + 1;
            modal.show();
        }

        document.getElementById('nextImage')?.addEventListener('click', function () {
            currentIndex = (currentIndex + 1) % galleryImages.length;
            openLightbox(currentIndex);
        });
        document.getElementById('prevImage')?.addEventListener('click', function () {
            currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
            openLightbox(currentIndex);
        });

        document.addEventListener('keydown', function (e) {
            if (!modalEl?.classList.contains('show')) return;
            if (e.key === 'ArrowRight') document.getElementById('nextImage').click();
            if (e.key === 'ArrowLeft')  document.getElementById('prevImage').click();
        });

        let touchStartX = 0;
        modalEl?.addEventListener('touchstart', function (e) { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
        modalEl?.addEventListener('touchend',   function (e) {
            const diff = touchStartX - e.changedTouches[0].screenX;
            if (diff >  50) document.getElementById('nextImage').click();
            if (diff < -50) document.getElementById('prevImage').click();
        });
    });
    </script>

    <script type="application/ld+json">
{!! json_encode([
    '@context'      => 'https://schema.org',
    '@type'         => 'Article',
    'headline'      => $ogTitle,
    'description'   => $ogDescription,
    'image'         => $ogImage,
    'url'           => $canonical,
    'datePublished' => $post->created_at->toIso8601String(),
    'dateModified'  => $post->updated_at->toIso8601String(),
    'author'        => [
        '@type' => 'Organization',
        'name'  => $post->company_name ?: $siteName,
    ],
    'publisher'     => [
        '@type' => 'Organization',
        'name'  => $siteName,
        'url'   => url('/'),
        'logo'  => [
            '@type' => 'ImageObject',
            'url'   => site_logo_url(),
        ],
    ],
    'about'         => $post->category ? [
        '@type' => 'Thing',
        'name'  => $post->category->name,
    ] : null,
    'keywords'      => $ogKeywords,
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
    @if($post->phone_number || $post->whatsapp_number)
<div class="mobile-cta-bar" id="mobileCta">
    @if($post->phone_number)
    <a href="tel:{{ $post->phone_number }}" class="mobile-cta-call">
        <i class="bi bi-telephone-fill"></i> Call Now
    </a>
    @endif
    @if($post->whatsapp_number)
    <a href="{{ $post->whatsapp_link }}" target="_blank" class="mobile-cta-wa">
        <i class="bi bi-whatsapp"></i> WhatsApp
    </a>
    @endif
</div>
<script>
// Add class so body gets extra padding for the CTA bar
document.body.classList.add('has-cta-bar');
</script>
@endif
{{-- ── Structured Data: Product/Offer (best type for a deal/listing) ── --}}
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => $post->title,
    'description' => $ogDescription,
    'image'       => [$ogImage],
    'url'         => $canonical,
    'brand'       => $post->company_name ? [
        '@type' => 'Brand',
        'name'  => $post->company_name,
    ] : null,
    'offers' => [
        '@type'         => 'Offer',
        'url'           => $canonical,
        'priceCurrency' => 'AED',
        'availability'  => ($post->expiry_date && \Carbon\Carbon::parse($post->expiry_date)->isPast())
            ? 'https://schema.org/OutOfStock'
            : 'https://schema.org/InStock',
        'validThrough'  => $post->expiry_date
            ? \Carbon\Carbon::parse($post->expiry_date)->toIso8601String()
            : null,
        'seller' => [
            '@type' => 'Organization',
            'name'  => $post->company_name ?: $siteName,
        ],
    ],
    'category' => $post->category?->name,
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

{{-- ── Structured Data: LocalBusiness (if contact info present) ────── --}}
@if ($post->company_name || $post->phone_number || $post->locality)
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context'  => 'https://schema.org',
    '@type'     => 'LocalBusiness',
    'name'      => $post->company_name ?: $post->title,
    'url'       => $canonical,
    'image'     => $ogImage,
    'description' => $ogDescription,
    'telephone' => $post->phone_number  ?: null,
    'address'   => ($post->locality || $post->city || $post->country) ? array_filter([
        '@type'           => 'PostalAddress',
        'addressLocality' => $post->locality?->name ?: $post->city,
        'addressRegion'   => $post->state  ?: null,
        'addressCountry'  => $post->country ?: null,
    ]) : null,
    'geo' => ($post->latitude && $post->longitude) ? [
        '@type'     => 'GeoCoordinates',
        'latitude'  => $post->latitude,
        'longitude' => $post->longitude,
    ] : null,
    'hasMap' => $post->google_map_url ?: null,
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

{{-- ── Structured Data: BreadcrumbList ─────────────────────────────── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => array_values(array_filter([
        [
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => $siteName,
            'item'     => url('/'),
        ],
        $post->category ? [
            '@type'    => 'ListItem',
            'position' => 2,
            'name'     => $post->category->name,
            'item'     => route('posts.listing', ['category_id' => $post->category->slug]),
        ] : null,
        $post->subcategory ? [
            '@type'    => 'ListItem',
            'position' => 3,
            'name'     => $post->subcategory->name,
            'item'     => route('posts.listing', [
                'category_id'    => $post->category?->slug ?? 'na',
                'subcategory_id' => $post->subcategory->slug,
            ]),
        ] : null,
        [
            '@type'    => 'ListItem',
            'position' => $post->subcategory ? 4 : ($post->category ? 3 : 2),
            'name'     => $post->title,
            'item'     => $canonical,
        ],
    ])),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
</body>
</html>
