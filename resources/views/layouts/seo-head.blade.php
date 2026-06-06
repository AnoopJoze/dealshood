{{--
    resources/views/frontend/partials/seo-head.blade.php
    @include this inside <head> on every frontend page.
    Pass variables from the controller, fall back to site settings.

    Usage on home page:
        @include('frontend.partials.seo-head')

    Usage on post detail:
        @include('frontend.partials.seo-head', [
            'seoTitle'       => $post->meta_title ?: $post->title,
            'seoDescription' => $post->meta_description ?: Str::limit(strip_tags($post->description), 160),
            'seoImage'       => $post->getFirstMediaUrl('posts'),
            'seoUrl'         => url()->current(),
        ])

    Usage on listing page:
        @include('frontend.partials.seo-head', [
            'seoTitle' => ($activeCat ? $activeCat->name . ' Deals' : 'Browse Deals') . ' — DealsHood',
        ])
--}}

@php
    $siteName   = setting('site_name', 'DealsHood');
    $finalTitle = $seoTitle       ?? setting('meta_title', $siteName . ' — Discover the Best Deals');
    $finalDesc  = $seoDescription ?? setting('meta_description', 'Find great offers from your neighbourhood, every day.');
    $finalImage = $seoImage       ?? (setting('og_image') ? Storage::url(setting('og_image')) : asset('frontend/img/favicon.png'));
    $finalUrl   = $seoUrl         ?? url()->current();
    $gaId       = setting('google_analytics_id');

    // Force absolute HTTPS for OG image
    if (!str_starts_with($finalImage, 'http')) { $finalImage = url($finalImage); }
    $finalImage = str_replace('http://', 'https://', $finalImage);
@endphp

{{-- Primary meta --}}
<title>{{ $finalTitle }}</title>
<meta name="description" content="{{ $finalDesc }}">
@if(setting('meta_keywords'))
    <meta name="keywords" content="{{ setting('meta_keywords') }}">
@endif

{{-- Canonical URL — prevents duplicate content penalty --}}
<link rel="canonical" href="{{ $finalUrl }}">

{{-- Favicon --}}
@if(setting('site_favicon'))
    <link rel="icon" href="{{ Storage::url(setting('site_favicon')) }}">
    <link rel="apple-touch-icon" href="{{ Storage::url(setting('site_favicon')) }}">
@else
    <link rel="icon" type="image/png" href="/frontend/img/favicon.ico">
@endif

{{-- Open Graph (WhatsApp, Facebook, Telegram) --}}
<meta property="og:site_name"        content="{{ $siteName }}">
<meta property="og:type"             content="{{ $seoType ?? 'website' }}">
<meta property="og:title"            content="{{ $finalTitle }}">
<meta property="og:description"      content="{{ $finalDesc }}">
<meta property="og:url"              content="{{ $finalUrl }}">
<meta property="og:image"            content="{{ $finalImage }}">
<meta property="og:image:secure_url" content="{{ $finalImage }}">
<meta property="og:image:width"      content="1200">
<meta property="og:image:height"     content="630">
<meta property="og:image:alt"        content="{{ $finalTitle }}">
<meta property="og:locale"           content="en_US">

{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $finalTitle }}">
<meta name="twitter:description" content="{{ $finalDesc }}">
<meta name="twitter:image"       content="{{ $finalImage }}">

{{-- Prevent indexing on non-production --}}
@if(app()->environment('local', 'staging'))
    <meta name="robots" content="noindex, nofollow">
@endif

{{-- Google Analytics --}}
@if($gaId)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){ dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', '{{ $gaId }}', { page_path: window.location.pathname });
</script>
@endif