<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ site_favicon_url() }}">
    <link rel="shortcut icon" href="{{ site_favicon_url() }}">
    @php
        $siteName = setting('site_name', 'DealsHood');

        /* ── OG image — absolute HTTPS, publicly accessible ── */
        $ogImage = $post->getFirstMediaUrl('posts');
        if (!$ogImage) { $ogImage = site_og_image_url(); }
        if (!str_starts_with($ogImage, 'http')) { $ogImage = url($ogImage); }
        $ogImage = str_replace('http://', 'https://', $ogImage);
        $ogImage = strtok($ogImage, '?');
        $ogImageExt  = strtolower(pathinfo(parse_url($ogImage, PHP_URL_PATH), PATHINFO_EXTENSION));
        $ogImageMime = match($ogImageExt) {
            'png'  => 'image/png', 'webp' => 'image/webp', 'gif'  => 'image/gif', default => 'image/jpeg',
        };

        /* ── Core fields ── */
        $ogTitle       = $post->meta_title       ?: $post->title;
        $ogDescription = $post->meta_description ?: Str::limit(strip_tags($post->description ?? ''), 160);
        $ogUrl         = url()->current();

        $kwParts = array_filter([
            $post->keywords, $post->category?->name, $post->subcategory?->name,
            $post->locality?->name, $post->company_name, $siteName, 'deals', 'offers', 'classifieds',
        ]);
        $ogKeywords = implode(', ', $kwParts);

        $richDesc = $ogDescription;
        $ctxParts = array_filter([
            $post->category?->name    ? 'Category: ' . $post->category->name : null,
            $post->subcategory?->name ? $post->subcategory->name : null,
            $post->locality?->name    ? 'in ' . $post->locality->name : null,
            $post->company_name       ? 'by ' . $post->company_name : null,
            $post->offer_percentage   ? $post->offer_percentage : null,
            $post->expiry_date && !\Carbon\Carbon::parse($post->expiry_date)->isPast()
                ? 'Valid until ' . \Carbon\Carbon::parse($post->expiry_date)->format('d M Y') : null,
        ]);
        if ($ctxParts) $richDesc .= ' — ' . implode(', ', $ctxParts) . '.';
        $richDesc = Str::limit($richDesc, 200);

        $canonical = route('post-details', [
            'locality'    => $post->locality?->slug    ?? 'na',
            'category'    => $post->category?->slug    ?? 'na',
            'subcategory' => $post->subcategory?->slug ?? 'na',
            'slug'        => $post->slug,
        ]);
    @endphp

    <title>{{ $ogTitle }} — {{ $siteName }}</title>

    <meta name="description"  content="{{ $richDesc }}">
    <meta name="keywords"     content="{{ $ogKeywords }}">
    <meta name="robots"       content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author"       content="{{ $post->company_name ?: $siteName }}">
    <link rel="canonical"     href="{{ $canonical }}">

    <meta property="og:site_name"        content="{{ $siteName }}">
    <meta property="og:type"             content="article">
    <meta property="og:locale"           content="en_US">
    <meta property="og:title"            content="{{ $ogTitle }}">
    <meta property="og:description"      content="{{ $richDesc }}">
    <meta property="og:url"              content="{{ $canonical }}">
    <meta property="og:image"            content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:type"       content="{{ $ogImageMime }}">
    <meta property="og:image:width"      content="1200">
    <meta property="og:image:height"     content="630">
    <meta property="og:image:alt"        content="{{ $ogTitle }}">

    <meta property="article:published_time" content="{{ $post->created_at->toIso8601String() }}">
    <meta property="article:modified_time"  content="{{ $post->updated_at->toIso8601String() }}">
    @if($post->category)<meta property="article:section" content="{{ $post->category->name }}">@endif
    @if($post->subcategory)<meta property="article:tag" content="{{ $post->subcategory->name }}">@endif
    @if($post->locality)<meta property="article:tag" content="{{ $post->locality->name }}">@endif

    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $richDesc }}">
    <meta name="twitter:image"       content="{{ $ogImage }}">
    <meta name="twitter:image:alt"   content="{{ $ogTitle }}">

    @if($post->locality)
    <meta name="geo.region"    content="{{ $post->locality->name }}">
    <meta name="geo.placename" content="{{ $post->locality->name }}">
    @endif
    @if($post->latitude && $post->longitude)
    <meta name="geo.position"  content="{{ $post->latitude }};{{ $post->longitude }}">
    @endif

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0a2a68">
    <link rel="apple-touch-icon" href="/frontend/img/icons/icon-192x192.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet">

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
    body{ font-family:var(--font); background:var(--bg); color:var(--ink); margin:0; -webkit-font-smoothing:antialiased; }
    body.has-cta-bar{ padding-bottom:74px; }
    a{ text-decoration:none; }
    img{ max-width:100%; }
    .wrap{ max-width:1240px; margin:0 auto; padding:0 24px; }

    #reading-progress{ position:fixed; top:0; left:0; height:3px; width:0; background:var(--blue-2); z-index:2000; transition:width .1s; }

    /* ══════════ NAVBAR (solid navy) ══════════ */
    .dh-nav{ position:sticky; top:0; height:var(--nav-h); z-index:60; display:flex; align-items:center; background:var(--navy); box-shadow:0 2px 20px rgba(7,30,77,.25); }
    .dh-nav-inner{ display:flex; align-items:center; gap:18px; width:100%; max-width:1240px; margin:0 auto; padding:0 24px; }
    .dh-nav-logo img{ height:40px; display:block; filter:brightness(0) invert(1); }
    .dh-nav-search{ flex:1; max-width:520px; display:flex; align-items:center; gap:10px; color:rgba(255,255,255,.75);
                    background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.22); border-radius:100px; padding:10px 20px; font-size:.85rem; }
    .dh-nav-spacer{ flex:1; }
    .dh-nav-loc{ display:inline-flex; align-items:center; gap:8px; color:#fff; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.22); border-radius:100px; padding:9px 16px; font-size:.82rem; font-weight:500; }
    .dh-nav-loc i{ font-size:.72rem; }
    .dh-nav-actions{ display:flex; align-items:center; gap:10px; }
    .dh-btn-signin{ color:#fff; border:1.5px solid rgba(255,255,255,.5); border-radius:100px; padding:9px 20px; font-size:.82rem; font-weight:600; transition:all .15s; cursor:pointer; background:none; font-family:var(--font); }
    .dh-btn-signin:hover{ background:#fff; color:var(--navy); }
    .dh-btn-post{ color:var(--navy); background:#fff; border:none; border-radius:100px; padding:10px 22px; font-size:.82rem; font-weight:600; transition:transform .15s; cursor:pointer; font-family:var(--font); }
    .dh-btn-post:hover{ transform:translateY(-1px); }
    .dh-nav-icon-btn{ display:none; background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.3); width:44px; height:44px; border-radius:50%; cursor:pointer; align-items:center; justify-content:center; color:#fff; font-size:1rem; }

    /* ══════════ DETAIL LAYOUT ══════════ */
    .dh-detail{ padding:36px 0 0; }
    .dh-detail-grid{ display:grid; grid-template-columns:1fr 1fr; gap:52px; align-items:start; }

    /* Media column */
    .dh-media-card{ position:relative; border-radius:var(--r-lg); overflow:hidden; background:#0b1e42; aspect-ratio:4/3; box-shadow:var(--sh-md); }
    .dh-media-card img,.dh-media-card video,.dh-carousel .carousel-item img,.dh-carousel .carousel-item video{ width:100%; height:100%; object-fit:cover; display:block; }
    .dh-carousel,.dh-carousel .carousel-inner,.dh-carousel .carousel-item{ height:100%; }
    .dh-single-img{ width:100%; height:100%; object-fit:cover; cursor:zoom-in; }
    .dh-media-count{ position:absolute; right:14px; top:14px; z-index:3; background:rgba(7,30,77,.7); color:#fff; font-size:.75rem; font-weight:600; padding:5px 12px; border-radius:100px; }
    .dh-media-badge{ position:absolute; left:14px; top:14px; z-index:3; background:var(--orange); color:#fff; font-size:.7rem; font-weight:700; padding:5px 12px; border-radius:100px; }
    .dh-thumbs{ display:flex; gap:12px; margin-top:14px; }
    .dh-thumb{ position:relative; width:100px; height:76px; border-radius:12px; overflow:hidden; cursor:pointer; border:2px solid transparent; transition:border-color .15s; flex-shrink:0; background:#0b1e42; }
    .dh-thumb img,.dh-thumb video{ width:100%; height:100%; object-fit:cover; }
    .dh-thumb.active{ border-color:var(--navy); }
    .dh-thumb-play{ position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:#fff; background:rgba(0,0,0,.35); font-size:.8rem; }
    .carousel-control-prev,.carousel-control-next{ width:12%; }

    /* Info column */
    .dh-crumb{ display:flex; align-items:center; gap:9px; font-size:.74rem; font-weight:600; letter-spacing:.14em; text-transform:uppercase; color:var(--muted-2); margin-bottom:18px; flex-wrap:wrap; }
    .dh-crumb a{ color:var(--muted-2); } .dh-crumb a:hover{ color:var(--navy); }
    .dh-crumb .dot{ opacity:.5; }
    .dh-title{ font-size:clamp(1.9rem,3.4vw,2.9rem); font-weight:700; color:var(--navy); line-height:1.1; letter-spacing:-.01em; margin:0 0 16px; }
    .dh-rating-view{ display:inline-flex; align-items:center; gap:6px; margin-bottom:18px; }
    .dh-star-big-wrap{ position:relative; font-size:1.05rem; color:#e2e8f0; line-height:1; }
    .dh-star-big-fg{ position:absolute; top:0; left:0; overflow:hidden; white-space:nowrap; color:#f59e0b; }
    .dh-rating-avg-sm{ font-size:.95rem; font-weight:700; color:var(--navy); }
    .dh-rating-count-sm{ font-size:.85rem; color:var(--muted); }
    .dh-excerpt{ font-size:1rem; color:var(--muted); line-height:1.7; margin:0 0 26px; max-width:560px; }
    .dh-price{ display:flex; align-items:baseline; gap:12px; margin-bottom:26px; }
    .dh-price-big{ font-size:2.6rem; font-weight:800; color:var(--orange); line-height:1; }
    .dh-price-suffix{ font-size:1rem; color:var(--muted); }
    .dh-actions{ display:flex; gap:14px; flex-wrap:wrap; margin-bottom:26px; }
    .dh-btn{ display:inline-flex; align-items:center; justify-content:center; gap:10px; font-family:var(--font); font-size:.95rem; font-weight:600; border-radius:100px; padding:15px 32px; cursor:pointer; border:1.5px solid transparent; transition:all .15s; }
    .dh-btn-call{ background:var(--navy); color:#fff; }
    .dh-btn-call:hover{ background:var(--navy-deep); color:#fff; }
    .dh-btn-wa{ background:#fff; color:var(--ink); border-color:var(--line); }
    .dh-btn-wa:hover{ border-color:var(--green); color:var(--green); }
    .dh-btn-map{ background:#fff; color:var(--ink); border-color:var(--line); }
    .dh-btn-map:hover{ border-color:var(--navy); color:var(--navy); }
    .dh-metarow{ display:flex; align-items:center; gap:26px; color:var(--muted); font-size:.88rem; flex-wrap:wrap; }
    .dh-metarow span{ display:inline-flex; align-items:center; gap:8px; }
    .dh-metarow i{ color:var(--muted-2); }

    /* About section */
    .dh-about{ padding:56px 0; }
    .dh-section-label{ display:block; font-size:.74rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--blue); margin-bottom:18px; }
    .dh-about-body{ font-size:1rem; line-height:1.85; color:var(--ink); max-width:900px; }
    .dh-about-body h1,.dh-about-body h2,.dh-about-body h3{ color:var(--navy); margin:1.4em 0 .5em; }
    .dh-about-body img{ border-radius:12px; margin:14px 0; }
    .dh-about-body ul,.dh-about-body ol{ padding-left:22px; }
    .dh-about-body a{ color:var(--blue-2); text-decoration:underline; }

    /* Offer highlight (single value) */
    .dh-highlight{ display:flex; align-items:center; gap:12px; background:var(--bg-soft); border:1px solid var(--line); border-radius:14px; padding:16px 20px; max-width:900px; margin-top:22px; }
    .dh-highlight i{ color:var(--orange); font-size:1.1rem; }
    .dh-highlight strong{ color:var(--navy); }

    /* ══════════ NAVY BAND ══════════ */
    .dh-band{ background:var(--navy); color:#fff; padding:56px 0; }
    .dh-band-grid{ display:grid; grid-template-columns:1fr 1fr; gap:52px; }
    .dh-band-label{ font-size:.74rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:rgba(255,255,255,.55); margin:0 0 16px; }
    .dh-band h3{ font-size:1.5rem; font-weight:700; color:#fff; margin:0 0 12px; }
    .dh-band-addr{ font-size:1rem; line-height:1.7; color:rgba(255,255,255,.75); margin:0 0 20px; }
    .dh-band-map{ display:inline-flex; align-items:center; gap:9px; color:#fff; border:1.5px solid rgba(255,255,255,.4); border-radius:100px; padding:11px 24px; font-size:.88rem; font-weight:600; transition:all .15s; }
    .dh-band-map:hover{ background:#fff; color:var(--navy); }
    .dh-band-actions{ display:flex; gap:14px; margin-bottom:22px; flex-wrap:wrap; }
    .dh-band-btn{ display:inline-flex; align-items:center; gap:10px; color:#fff; background:rgba(255,255,255,.1); border:1.5px solid rgba(255,255,255,.28); border-radius:100px; padding:13px 28px; font-family:var(--font); font-size:.92rem; font-weight:600; cursor:pointer; transition:all .15s; }
    .dh-band-btn:hover{ background:rgba(255,255,255,.2); color:#fff; }
    .dh-band-btn.liked{ background:#e11d48; border-color:#e11d48; }
    .dh-rate-line{ display:flex; align-items:center; gap:12px; margin-bottom:22px; flex-wrap:wrap; }
    .dh-rate-line .lbl{ font-size:.9rem; color:rgba(255,255,255,.75); }
    .dh-rating-input{ display:inline-flex; align-items:center; gap:10px; }
    .dh-rating-stars{ display:inline-flex; gap:4px; cursor:pointer; }
    .dh-rating-stars .dh-star{ color:rgba(255,255,255,.3); font-size:1.15rem; transition:color .12s; }
    .dh-rating-stars .dh-star.active,.dh-rating-stars .dh-star.hover{ color:#fbbf24; }
    .dh-rating-avg{ font-size:.95rem; font-weight:700; color:#fff; }
    .dh-rating-count{ font-size:.82rem; color:rgba(255,255,255,.6); }
    .dh-band-note{ font-size:.85rem; line-height:1.65; color:rgba(255,255,255,.6); max-width:520px; }

    /* ══════════ SIMILAR (reuse navy cards) ══════════ */
    .dh-similar{ padding:56px 0 76px; }
    .dh-similar-head{ display:flex; align-items:center; justify-content:space-between; margin-bottom:26px; gap:12px; }
    .dh-similar-head h2{ font-size:1.5rem; font-weight:700; color:var(--navy); margin:0; }
    .dh-similar-head a{ display:inline-flex; align-items:center; gap:6px; color:var(--blue-2); font-weight:600; font-size:.9rem; }
    .dh-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }

    /* Navy listing card (shared partial styling) */
    .dh-lc{ background:var(--bg); border-radius:var(--r-lg); overflow:hidden; box-shadow:var(--sh-sm); border:1px solid var(--line); display:flex; flex-direction:column; transition:transform .2s, box-shadow .2s; }
    .dh-lc:hover{ transform:translateY(-4px); box-shadow:var(--sh-lg); }
    .dh-lc-media{ position:relative; aspect-ratio:16/11; background:#0b1e42; overflow:hidden; }
    .dh-lc-media a{ display:block; width:100%; height:100%; }
    .dh-lc-media img,.dh-lc-media video{ width:100%; height:100%; object-fit:cover; display:block; transition:transform .35s; }
    .dh-lc:hover .dh-lc-media img{ transform:scale(1.05); }
    .dh-lc-feat{ position:absolute; left:12px; top:12px; z-index:2; background:#f59e0b; color:#3a2c00; font-size:.62rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; padding:5px 12px; border-radius:100px; }
    .dh-lc-verified{ position:absolute; right:12px; top:12px; z-index:2; display:inline-flex; align-items:center; gap:5px; background:#fff; color:var(--green); font-size:.66rem; font-weight:600; padding:5px 11px; border-radius:100px; box-shadow:0 2px 8px rgba(0,0,0,.12); }
    .dh-lc-fav{ position:absolute; right:12px; bottom:12px; z-index:2; width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,.92); border:none; color:var(--navy); cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:.85rem; }
    .dh-lc-fav.liked{ background:#e11d48; color:#fff; }
    .dh-lc-body{ background:var(--navy); color:#fff; padding:20px 22px 22px; display:flex; flex-direction:column; flex:1; }
    .dh-lc-top{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:16px; }
    .dh-lc-cat{ display:inline-flex; align-items:center; background:rgba(255,255,255,.12); color:#fff; font-size:.74rem; font-weight:500; padding:6px 14px; border-radius:100px; }
    .dh-lc-rate{ display:inline-flex; align-items:center; gap:5px; color:#fff; font-size:.9rem; }
    .dh-lc-rate i{ color:#fbbf24; font-size:.85rem; }
    .dh-lc-rate em{ font-style:normal; color:rgba(255,255,255,.6); font-size:.82rem; }
    .dh-lc-title{ font-size:1.25rem; font-weight:700; color:#fff; line-height:1.24; margin:0 0 14px; display:block; }
    .dh-lc-loc{ display:flex; align-items:center; gap:8px; color:rgba(255,255,255,.62); font-size:.9rem; }
    .dh-lc-divider{ height:1px; background:rgba(255,255,255,.14); margin:18px 0; }
    .dh-lc-foot{ display:flex; flex-direction:column; gap:2px; margin-bottom:16px; }
    .dh-lc-plabel{ font-size:.8rem; color:rgba(255,255,255,.6); }
    .dh-lc-price{ font-size:1.5rem; font-weight:700; color:#fff; line-height:1.1; }
    .dh-lc-price.sm{ font-size:1.05rem; font-weight:600; }
    .dh-lc-btn{ display:flex; align-items:center; justify-content:center; gap:9px; margin-top:auto; background:#fff; color:var(--navy); font-weight:600; font-size:.92rem; border-radius:100px; padding:14px; transition:all .15s; }
    .dh-lc-btn:hover{ background:#eaf0ff; color:var(--navy); }

    /* ══════════ FOOTER ══════════ */
    .dh-footer{ background:var(--navy-deep); color:rgba(255,255,255,.72); padding:60px 0 0; }
    .dh-footer-grid{ display:grid; grid-template-columns:1.6fr repeat(3,1fr); gap:40px; padding-bottom:44px; }
    .dh-footer-logo img{ height:34px; filter:brightness(0) invert(1); }
    .dh-footer-tag{ font-size:.85rem; color:rgba(255,255,255,.55); max-width:280px; margin:16px 0 20px; line-height:1.6; }
    .dh-footer-social{ display:flex; gap:10px; }
    .dh-footer-social a{ width:38px; height:38px; border-radius:50%; border:1px solid rgba(255,255,255,.2); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.7); transition:all .15s; }
    .dh-footer-social a:hover{ border-color:#fff; color:#fff; }
    .dh-footer-col-title{ font-size:.9rem; font-weight:700; color:#fff; margin-bottom:16px; }
    .dh-footer-links{ list-style:none; padding:0; margin:0; }
    .dh-footer-links li{ margin-bottom:11px; }
    .dh-footer-links a{ color:rgba(255,255,255,.6); font-size:.88rem; }
    .dh-footer-links a:hover{ color:#fff; }
    .dh-footer-bottom{ border-top:1px solid rgba(255,255,255,.1); padding:22px 0; display:flex; align-items:center; justify-content:space-between; font-size:.78rem; color:rgba(255,255,255,.4); flex-wrap:wrap; gap:10px; }

    /* Mobile CTA bar */
    .mobile-cta-bar{ position:fixed; left:0; right:0; bottom:0; z-index:1500; display:none; gap:10px; padding:10px 14px calc(10px + env(safe-area-inset-bottom,0)); background:#fff; box-shadow:0 -6px 24px rgba(7,30,77,.14); }
    .mobile-cta-bar a{ flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:13px; border-radius:12px; font-weight:600; font-size:.9rem; }
    .mobile-cta-call{ background:var(--navy); color:#fff; }
    .mobile-cta-wa{ background:var(--green); color:#fff; }

    /* Lightbox */
    .dh-lightbox .modal-content{ background:rgba(7,15,35,.96); border:none; }
    .dh-lb-img{ max-width:92vw; max-height:88vh; object-fit:contain; border-radius:8px; transition:opacity .2s; }
    .dh-lb-close{ position:absolute; top:20px; right:24px; z-index:10; background:rgba(255,255,255,.15); border:none; color:#fff; width:46px; height:46px; border-radius:50%; font-size:1.1rem; cursor:pointer; }
    .dh-lb-btn{ position:absolute; top:50%; transform:translateY(-50%); background:rgba(255,255,255,.15); border:none; color:#fff; width:52px; height:52px; border-radius:50%; font-size:1.8rem; cursor:pointer; }
    .dh-lb-prev{ left:24px; } .dh-lb-next{ right:24px; }
    .dh-lb-counter{ position:absolute; bottom:24px; left:50%; transform:translateX(-50%); color:#fff; font-size:.85rem; background:rgba(255,255,255,.12); padding:6px 16px; border-radius:100px; }

    @keyframes fadeUp{ from{ opacity:0; transform:translateY(20px); } to{ opacity:1; transform:none; } }

    /* ══════════ RESPONSIVE ══════════ */
    @media(max-width:1024px){ .dh-grid{ grid-template-columns:repeat(2,1fr); } }
    @media(max-width:900px){
        .dh-nav-search,.dh-btn-signin,.dh-nav-loc{ display:none; }
        .dh-nav-icon-btn{ display:flex; }
        .dh-detail-grid{ grid-template-columns:1fr; gap:26px; }
        .dh-band-grid{ grid-template-columns:1fr; gap:34px; }
    }
    @media(max-width:768px){
        .dh-detail{ padding:22px 0 0; }
        .dh-title{ font-size:1.7rem; }
        .dh-actions .dh-btn{ flex:1; }
        .dh-metarow{ gap:16px; }
        .dh-grid{ grid-template-columns:1fr 1fr; gap:14px; }
        .dh-lc-title{ font-size:1.05rem; }
        .dh-lc-price{ font-size:1.25rem; }
        .dh-footer-grid{ grid-template-columns:1fr 1fr; gap:28px; }
        .mobile-cta-bar{ display:flex; }
        .dh-about{ padding:36px 0; }
        .dh-band{ padding:40px 0; }
        .dh-similar{ padding:36px 0 56px; }
    }
    @media(max-width:480px){
        .dh-grid{ grid-template-columns:1fr; }
        .dh-footer-grid{ grid-template-columns:1fr; }
        .dh-thumb{ width:74px; height:58px; }
        .dh-price-big{ font-size:2rem; }
    }
    </style>
</head>
<body>

<div id="reading-progress"></div>

{{-- ═══════════ NAVBAR ═══════════ --}}
<nav class="dh-nav">
    <div class="dh-nav-inner">
        <a href="{{ route('home') }}" class="dh-nav-logo"><img src="{{ site_logo_url() }}" alt="{{ $siteName }}"></a>
        <a href="{{ route('posts.listing') }}" class="dh-nav-search"><i class="fas fa-magnifying-glass"></i> Search deals…</a>
        <div class="dh-nav-spacer"></div>
        @if($post->locality)
            <span class="dh-nav-loc"><i class="fas fa-location-dot"></i> {{ $post->locality->name }}</span>
        @endif
        <button class="dh-nav-icon-btn" type="button" onclick="openPostAdModal && openPostAdModal()" aria-label="Post ad"><i class="fas fa-plus"></i></button>
        <div class="dh-nav-actions">
            <a href="{{ route('contact') }}" class="dh-btn-signin">Contact</a>
            <button class="dh-btn-post" type="button" onclick="openPostAdModal && openPostAdModal()">Post Free Ads</button>
        </div>
    </div>
</nav>

@php
    $images = $post->getMedia('posts');
    $video  = $post->getFirstMediaUrl('videos');
    $ratingCount   = $post->ratingsData->count();
    $avgRating     = $ratingCount ? round($post->ratingsData->avg('rating'), 1) : 0;
    $ratingFillPct = $avgRating > 0 ? ($avgRating / 5) * 100 : 0;
    $hasVideo   = (bool) $video;
    $imageCount = $images->count();
    $totalMedia = ($hasVideo ? 1 : 0) + $imageCount;
    $imgOffset  = $hasVideo ? 1 : 0;
@endphp

{{-- ═══════════ DETAIL ═══════════ --}}
<main class="dh-detail">
    <div class="wrap">
        <div class="dh-detail-grid">

            {{-- MEDIA COLUMN --}}
            <div class="dh-media-col">
                <div class="dh-media-card">
                    @if($totalMedia > 1)
                        <span class="dh-media-count">1 / {{ $totalMedia }}</span>
                        <div id="dhCarousel" class="carousel slide dh-carousel" data-bs-ride="{{ $hasVideo ? 'false' : 'carousel' }}">
                            <div class="carousel-inner">
                                @if($hasVideo)
                                    <div class="carousel-item active"><video controls preload="metadata"><source src="{{ $video }}"></video></div>
                                @endif
                                @foreach($images as $k => $media)
                                    <div class="carousel-item {{ (!$hasVideo && $k == 0) ? 'active' : '' }}">
                                        <img src="{{ $media->getUrl() }}" alt="Post image {{ $k + 1 }}" class="openGallery" data-image="{{ $media->getUrl() }}" data-index="{{ $k }}">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#dhCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                            <button class="carousel-control-next" type="button" data-bs-target="#dhCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
                        </div>
                    @elseif($hasVideo)
                        <span class="dh-media-badge">▶ Video</span>
                        <video controls><source src="{{ $video }}"></video>
                    @elseif($imageCount == 1)
                        <img src="{{ $images->first()->getUrl() }}" class="dh-single-img openGallery" data-image="{{ $images->first()->getUrl() }}" data-index="0" alt="{{ $post->title }}">
                    @else
                        <img src="{{ asset('frontend/img/default.jpg') }}" class="dh-single-img" alt="Default">
                    @endif
                </div>

                @if($totalMedia > 1)
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
                @endif
            </div>

            {{-- INFO COLUMN --}}
            <div class="dh-info-col">
                <nav class="dh-crumb" aria-label="Breadcrumb">
                    <a href="{{ route('posts.listing') }}">Deals</a>
                    @if($post->category)<span class="dot">·</span><a href="{{ route('posts.listing', ['category_id' => $post->category->slug]) }}">{{ $post->category->name }}</a>@endif
                    @if($post->locality)<span class="dot">·</span><a href="{{ route('posts.listing', ['locality_id' => $post->locality->slug]) }}">{{ $post->locality->name }}</a>@endif
                </nav>

                <h1 class="dh-title">{{ $post->title }}</h1>

                @if($avgRating > 0)
                    <div class="dh-rating-view">
                        <span class="dh-star-big-wrap"><i class="fas fa-star"></i><span class="dh-star-big-fg" id="heroStarFg" style="width:{{ $ratingFillPct }}%;"><i class="fas fa-star"></i></span></span>
                        <span class="dh-rating-avg-sm" id="heroRatingAvg">{{ number_format($avgRating, 1) }}</span>
                        <span class="dh-rating-count-sm" id="heroRatingCount">({{ number_format($ratingCount) }})</span>
                    </div>
                @else
                    <div class="dh-rating-view" style="display:none;">
                        <span class="dh-star-big-wrap"><i class="fas fa-star"></i><span class="dh-star-big-fg" id="heroStarFg" style="width:0%;"><i class="fas fa-star"></i></span></span>
                        <span class="dh-rating-avg-sm" id="heroRatingAvg"></span>
                        <span class="dh-rating-count-sm" id="heroRatingCount">(0)</span>
                    </div>
                @endif

                <p class="dh-excerpt">{{ Str::limit(strip_tags($post->description), 220) }}</p>

                @if($post->offer_percentage)
                    <div class="dh-price">
                        <span class="dh-price-big">{{ $post->offer_percentage }}</span>
                        <span class="dh-price-suffix">special offer</span>
                    </div>
                @endif

                <div class="dh-actions">
                    @if($post->phone_number)
                        <a href="tel:{{ $post->phone_number }}" class="dh-btn dh-btn-call"><i class="fas fa-phone"></i> Call now</a>
                    @endif
                    @if($post->whatsapp_number)
                        <a href="{{ $post->whatsapp_link }}" target="_blank" rel="noopener" class="dh-btn dh-btn-wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    @endif
                    @if($post->google_map_url)
                        <a href="{{ $post->google_map_url }}" target="_blank" rel="noopener" class="dh-btn dh-btn-map"><i class="fas fa-location-dot"></i> Directions</a>
                    @endif
                </div>

                <div class="dh-metarow">
                    @if($post->locality)<span><i class="fas fa-location-dot"></i> {{ $post->locality->name }}</span>@endif
                    @if($post->expiry_date && !\Carbon\Carbon::parse($post->expiry_date)->isPast())
                        <span><i class="fas fa-calendar-check"></i> Valid until {{ \Carbon\Carbon::parse($post->expiry_date)->format('d M Y') }}</span>
                    @else
                        <span><i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }}</span>
                    @endif
                    <span><i class="fas fa-eye"></i> {{ number_format($post->viewsData->count()) }} views</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ABOUT / DETAILS --}}
    <section class="dh-about" id="content">
        <div class="wrap">
            <span class="dh-section-label">About this deal</span>
            <div class="dh-about-body">{!! $post->description !!}</div>

            @if($post->offer_percentage)
                <div class="dh-highlight"><i class="fas fa-tag"></i> Offer: <strong>{{ $post->offer_percentage }}</strong></div>
            @endif
        </div>
    </section>

    {{-- ═══════════ NAVY BAND ═══════════ --}}
    <div class="dh-band">
        <div class="wrap">
            <div class="dh-band-grid">
                <div>
                    <p class="dh-band-label">Location</p>
                    <h3>{{ $post->company_name ?: ($post->locality->name ?? $siteName) }}</h3>
                    <p class="dh-band-addr">
                        {{ $post->location ?: ($post->locality->name ?? 'Location available on request') }}
                        @if($post->additionalLocalities->isNotEmpty())<br><small>Also in: {{ $post->additionalLocalities->pluck('name')->join(', ') }}</small>@endif
                    </p>
                    @if($post->google_map_url)
                        <a href="{{ $post->google_map_url }}" target="_blank" rel="noopener" class="dh-band-map"><i class="fas fa-diamond-turn-right"></i> Get Directions</a>
                    @endif
                </div>
                <div>
                    <p class="dh-band-label">This deal</p>
                    <div class="dh-band-actions">
                        <button class="dh-band-btn likeBtn" data-id="{{ $post->id }}">
                            <i class="fas fa-heart"></i> Like <strong id="like-count-{{ $post->id }}">{{ number_format($post->likesData->count()) }}</strong>
                        </button>
                        <button class="dh-band-btn shareBtn" data-id="{{ $post->id }}" data-url="{{ $post->url }}">
                            <i class="fas fa-share-nodes"></i> Share
                        </button>
                    </div>

                    <div class="dh-rate-line">
                        <span class="lbl">Rate this deal:</span>
                        <div class="dh-rating-input" data-post-id="{{ $post->id }}" data-current="{{ $userRating ?? 0 }}">
                            <div class="dh-rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star dh-star {{ $userRating && $i <= $userRating ? 'active' : '' }}" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <span class="dh-rating-avg">{{ $avgRating > 0 ? number_format($avgRating, 1) : '0.0' }}</span>
                            <span class="dh-rating-count">({{ number_format($ratingCount) }} ratings)</span>
                        </div>
                    </div>

                    <p class="dh-band-note">
                        @if($post->disclaimer)
                            {{ $post->disclaimer }}
                        @elseif($post->category_id == 1)
                            Offers and services listed are provided by third parties. Please contact the business directly to confirm details before visiting. {{ $siteName }} is not responsible for any service issues, disputes or changes in offers.
                        @else
                            {{ $siteName }} connects customers with nearby services for better accessibility. If any listed business prefers not to appear, they may contact us and the listing will be removed on request.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ SIMILAR DEALS ═══════════ --}}
    @if($relatedPosts->isNotEmpty())
    <section class="dh-similar">
        <div class="wrap">
            <div class="dh-similar-head">
                <h2>Similar Deals</h2>
                <a href="{{ route('posts.listing', ['category_id' => $post->category?->slug]) }}">View all <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="dh-grid">
                @foreach($relatedPosts as $rp)
                    @include('frontend.post-listing-card', ['post' => $rp])
                @endforeach
            </div>
        </div>
    </section>
    @endif
</main>

{{-- ═══════════ FOOTER ═══════════ --}}
<footer class="dh-footer">
    <div class="wrap">
        <div class="dh-footer-grid">
            <div>
                <div class="dh-footer-logo"><img src="{{ site_logo_url() }}" alt="{{ $siteName }}"></div>
                <p class="dh-footer-tag">Find the best local deals, offers and classifieds near you. Save smarter, shop happier.</p>
                <div class="dh-footer-social">
                    <a href="https://www.instagram.com/dealshood" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/share/1DA56kRCJp" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://wa.me/918086087050" target="_blank"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div>
                <p class="dh-footer-col-title">Explore</p>
                <ul class="dh-footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('posts.listing') }}">All Deals</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            <div>
                <p class="dh-footer-col-title">Category</p>
                <ul class="dh-footer-links">
                    @if($post->category)<li><a href="{{ route('posts.listing', ['category_id' => $post->category->slug]) }}">{{ $post->category->name }}</a></li>@endif
                    @if($post->subcategory)<li><a href="{{ route('posts.listing', ['category_id' => $post->category?->slug, 'subcategory_id' => $post->subcategory->slug]) }}">{{ $post->subcategory->name }}</a></li>@endif
                </ul>
            </div>
            <div>
                <p class="dh-footer-col-title">Area</p>
                <ul class="dh-footer-links">
                    @if($post->locality)<li><a href="{{ route('posts.listing', ['locality_id' => $post->locality->slug]) }}">{{ $post->locality->name }}</a></li>@endif
                </ul>
            </div>
        </div>
        <div class="dh-footer-bottom">
            <span>© <span id="footerYear"></span> {{ $siteName }}. All rights reserved.</span>
            <span>Made with <i class="fas fa-heart" style="color:#e11d48;"></i> in India</span>
        </div>
    </div>
</footer>

{{-- ═══════════ LIGHTBOX ═══════════ --}}
<div class="modal fade dh-lightbox" id="galleryLightbox" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content position-relative d-flex align-items-center justify-content-center">
            <button class="dh-lb-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            <img id="lightboxImage" src="" class="dh-lb-img" alt="">
            <button class="dh-lb-btn dh-lb-prev" id="prevImage">&#8249;</button>
            <button class="dh-lb-btn dh-lb-next" id="nextImage">&#8250;</button>
            <div class="dh-lb-counter"><span id="currentImageIndex">1</span> / <span id="totalImages">1</span></div>
        </div>
    </div>
</div>

@include('frontend.frontend-mobile')
@include('frontend.post-ad-modal')

@if($post->phone_number || $post->whatsapp_number)
<div class="mobile-cta-bar" id="mobileCta">
    @if($post->phone_number)<a href="tel:{{ $post->phone_number }}" class="mobile-cta-call"><i class="bi bi-telephone-fill"></i> Call Now</a>@endif
    @if($post->whatsapp_number)<a href="{{ $post->whatsapp_link }}" target="_blank" class="mobile-cta-wa"><i class="bi bi-whatsapp"></i> WhatsApp</a>@endif
</div>
<script>document.body.classList.add('has-cta-bar');</script>
@endif

<script src="/frontend/js/core/popper.min.js"></script>
<script src="/frontend/js/core/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function updateHeroRating(avg, total){
    $('#heroRatingAvg').text(avg > 0 ? avg.toFixed(1) : '');
    $('#heroRatingCount').text('(' + total + ')');
    $('#heroStarFg').css('width', (avg > 0 ? (avg / 5) * 100 : 0) + '%');
}
$(document).on('mouseenter', '.dh-rating-input .dh-star', function(){
    const val = $(this).data('value');
    $(this).parent().children('.dh-star').each(function(){ $(this).toggleClass('hover', $(this).data('value') <= val); });
});
$(document).on('mouseleave', '.dh-rating-input .dh-rating-stars', function(){ $(this).children('.dh-star').removeClass('hover'); });
$(document).on('click', '.dh-rating-input .dh-star', function(){
    const val = $(this).data('value');
    const wrap = $(this).closest('.dh-rating-input');
    const postId = wrap.data('post-id');
    const current = parseInt(wrap.data('current') || 0);
    if(val === current){
        $.ajax({ url:'/posts/' + postId + '/rate', type:'DELETE', data:{ _token:'{{ csrf_token() }}' },
            success:function(res){
                wrap.data('current', 0); wrap.find('.dh-star').removeClass('active');
                wrap.find('.dh-rating-avg').text(res.avg_rating.toFixed(1));
                wrap.find('.dh-rating-count').text('(' + res.total + ' ratings)');
                updateHeroRating(res.avg_rating, res.total);
            }});
        return;
    }
    $.ajax({ url:'/posts/' + postId + '/rate', type:'POST', data:{ _token:'{{ csrf_token() }}', rating:val },
        success:function(res){
            wrap.data('current', val);
            wrap.find('.dh-star').each(function(){ $(this).toggleClass('active', $(this).data('value') <= val); });
            wrap.find('.dh-rating-avg').text(res.avg_rating.toFixed(1));
            wrap.find('.dh-rating-count').text('(' + res.total + ' ratings)');
            updateHeroRating(res.avg_rating, res.total);
        }});
});

document.getElementById('footerYear').textContent = new Date().getFullYear();

/* Reading progress */
window.addEventListener('scroll', function(){
    const doc = document.documentElement;
    const pct = (doc.scrollTop || document.body.scrollTop) / (doc.scrollHeight - doc.clientHeight) * 100;
    const bar = document.getElementById('reading-progress');
    if(bar) bar.style.width = pct + '%';
}, { passive:true });

/* Carousel thumbnail sync */
(function(){
    const carousel = document.getElementById('dhCarousel');
    const thumbs   = document.querySelectorAll('.dh-thumb');
    const counter  = document.querySelector('.dh-media-count');
    if(!carousel || !thumbs.length) return;
    carousel.addEventListener('slid.bs.carousel', function(e){
        thumbs.forEach(t => t.classList.remove('active'));
        thumbs[e.to]?.classList.add('active');
        if(counter) counter.textContent = (e.to + 1) + ' / ' + thumbs.length;
    });
    thumbs.forEach(function(thumb){
        thumb.addEventListener('click', function(){ bootstrap.Carousel.getOrCreateInstance(carousel).to(parseInt(this.dataset.index)); });
    });
})();

/* Like */
$(document).on('click', '.likeBtn', function(e){
    e.preventDefault();
    const btn = $(this), id = btn.data('id');
    $.ajax({ url:'/posts/' + id + '/toggle-like', type:'POST', data:{ _token:'{{ csrf_token() }}' },
        success:function(res){
            $('#like-count-' + id).text(res.likes);
            $('.likeBtn[data-id="' + id + '"]').toggleClass('liked', res.liked);
        }});
});

/* Share */
$(document).on('click', '.shareBtn', async function(e){
    e.preventDefault();
    const id = $(this).data('id');
    let url = $(this).data('url');
    try{
        const res = await fetch('{{ route("shorten") }}', {
            method:'POST',
            headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' },
            body: JSON.stringify({ url }),
        });
        const data = await res.json();
        if(res.ok && data.short_url) url = data.short_url;
    }catch(e){}
    navigator.share ? navigator.share({ url }) : (navigator.clipboard.writeText(url), alert('Link copied!'));
    $.ajax({ url:'/posts/' + id + '/share', type:'POST', data:{ _token:'{{ csrf_token() }}', platform:'web' } });
});

/* Lightbox */
document.addEventListener('DOMContentLoaded', function(){
    let galleryImages = [], currentIndex = 0;
    document.querySelectorAll('.openGallery').forEach(function(item){
        galleryImages.push(item.dataset.image);
        item.addEventListener('click', function(){ currentIndex = parseInt(this.dataset.index); openLightbox(currentIndex); });
    });
    const modalEl = document.getElementById('galleryLightbox');
    const modal = modalEl ? new bootstrap.Modal(modalEl) : null;
    const lightboxImage = document.getElementById('lightboxImage');
    const currentIndexEl = document.getElementById('currentImageIndex');
    const totalEl = document.getElementById('totalImages');
    if(totalEl) totalEl.textContent = galleryImages.length;
    function openLightbox(index){
        if(!modal) return;
        lightboxImage.style.opacity = '0';
        lightboxImage.src = galleryImages[index];
        lightboxImage.onload = function(){ lightboxImage.style.opacity = '1'; };
        if(currentIndexEl) currentIndexEl.textContent = index + 1;
        modal.show();
    }
    document.getElementById('nextImage')?.addEventListener('click', function(){ currentIndex = (currentIndex + 1) % galleryImages.length; openLightbox(currentIndex); });
    document.getElementById('prevImage')?.addEventListener('click', function(){ currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length; openLightbox(currentIndex); });
    document.addEventListener('keydown', function(e){
        if(!modalEl?.classList.contains('show')) return;
        if(e.key === 'ArrowRight') document.getElementById('nextImage').click();
        if(e.key === 'ArrowLeft')  document.getElementById('prevImage').click();
    });
    let touchStartX = 0;
    modalEl?.addEventListener('touchstart', function(e){ touchStartX = e.changedTouches[0].screenX; }, { passive:true });
    modalEl?.addEventListener('touchend', function(e){
        const diff = touchStartX - e.changedTouches[0].screenX;
        if(diff > 50) document.getElementById('nextImage').click();
        if(diff < -50) document.getElementById('prevImage').click();
    });
});

if('serviceWorker' in navigator){ window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(()=>{})); }
</script>

{{-- ── Structured Data ── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'Article',
    'headline' => $ogTitle, 'description' => $ogDescription, 'image' => $ogImage, 'url' => $canonical,
    'datePublished' => $post->created_at->toIso8601String(), 'dateModified' => $post->updated_at->toIso8601String(),
    'author' => ['@type' => 'Organization', 'name' => $post->company_name ?: $siteName],
    'publisher' => ['@type' => 'Organization', 'name' => $siteName, 'url' => url('/'),
        'logo' => ['@type' => 'ImageObject', 'url' => site_logo_url()]],
    'about' => $post->category ? ['@type' => 'Thing', 'name' => $post->category->name] : null,
    'keywords' => $ogKeywords,
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org', '@type' => 'Product',
    'name' => $post->title, 'description' => $ogDescription, 'image' => [$ogImage], 'url' => $canonical,
    'brand' => $post->company_name ? ['@type' => 'Brand', 'name' => $post->company_name] : null,
    'offers' => [
        '@type' => 'Offer', 'url' => $canonical, 'priceCurrency' => 'INR',
        'availability' => ($post->expiry_date && \Carbon\Carbon::parse($post->expiry_date)->isPast())
            ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
        'validThrough' => $post->expiry_date ? \Carbon\Carbon::parse($post->expiry_date)->toIso8601String() : null,
        'seller' => ['@type' => 'Organization', 'name' => $post->company_name ?: $siteName],
    ],
    'category' => $post->category?->name,
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@if($post->company_name || $post->phone_number || $post->locality)
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org', '@type' => 'LocalBusiness',
    'name' => $post->company_name ?: $post->title, 'url' => $canonical, 'image' => $ogImage, 'description' => $ogDescription,
    'telephone' => $post->phone_number ?: null,
    'address' => ($post->locality || $post->city || $post->country) ? array_filter([
        '@type' => 'PostalAddress', 'addressLocality' => $post->locality?->name ?: $post->city,
        'addressRegion' => $post->state ?: null, 'addressCountry' => $post->country ?: null,
    ]) : null,
    'geo' => ($post->latitude && $post->longitude) ? ['@type' => 'GeoCoordinates', 'latitude' => $post->latitude, 'longitude' => $post->longitude] : null,
    'hasMap' => $post->google_map_url ?: null,
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org', '@type' => 'BreadcrumbList',
    'itemListElement' => array_values(array_filter([
        ['@type' => 'ListItem', 'position' => 1, 'name' => $siteName, 'item' => url('/')],
        $post->category ? ['@type' => 'ListItem', 'position' => 2, 'name' => $post->category->name,
            'item' => route('posts.listing', ['category_id' => $post->category->slug])] : null,
        $post->subcategory ? ['@type' => 'ListItem', 'position' => 3, 'name' => $post->subcategory->name,
            'item' => route('posts.listing', ['category_id' => $post->category?->slug ?? 'na', 'subcategory_id' => $post->subcategory->slug])] : null,
        ['@type' => 'ListItem', 'position' => $post->subcategory ? 4 : ($post->category ? 3 : 2), 'name' => $post->title, 'item' => $canonical],
    ])),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
</body>
</html>
