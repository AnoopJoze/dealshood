{{--
  ═══════════════════════════════════════════════════
  SHARED MOBILE PARTIAL — frontend/mobile.blade.php
  @include('frontend.mobile') in every frontend page.
  Contains: bottom nav + all shared mobile CSS.
  ═══════════════════════════════════════════════════
--}}

{{-- Meta for app-like feel --}}
@push('head')
<meta name="theme-color" content="#0d0d0d">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
@endpush

<style>
/* ══════════════════════════════════════════════════
   SHARED MOBILE APP STYLES
   All rules are mobile-only (@media ≤ 768px).
   Desktop styles unchanged.
══════════════════════════════════════════════════ */

/* Safe area variables */
:root {
    --safe-bottom: env(safe-area-inset-bottom, 0px);
    --safe-top:    env(safe-area-inset-top, 0px);
    --bot-nav-h:   60px;
}

@media (max-width: 768px) {

    /* ── Base ──────────────────────────────────────── */
    html { -webkit-text-size-adjust: 100%; }
    body {
        -webkit-overflow-scrolling: touch;
        /* Space for bottom nav */
        padding-bottom: calc(var(--bot-nav-h) + var(--safe-bottom));
    }

    /* Prevent text selection on interactive elements */
    button, a, .dh-gtile, .loc-btn, .dh-chip, .loc-chip, .lp-item {
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }

    /* Touch press feedback */
    .dh-card:active              { transform: scale(.985); }
    .dh-gtile:active             { transform: scale(.94) translateY(-2px); }
    .dh-btn:active,
    .ps-btn:active,
    .btn-submit:active           { transform: scale(.97); }
    .loc-chip:active,
    .lp-item:active              { opacity: .7; }

    /* ── Navbar — minimal on mobile ────────────────── */
    .dh-nav { height: 52px; }
    :root   { --nav-h: 52px; }
    .dh-nav-logo img { height: 36px; }
    /* Hide desktop nav actions — bottom nav handles them */
    .dh-nav-toggle,
    .dh-nav-actions { display: none !important; }
    /* Show page title in navbar on sub-pages */
    .dh-nav-page-title {
        font-size: .88rem; font-weight: 700;
        color: var(--ink, #0d0d0d);
        position: absolute; left: 50%; transform: translateX(-50%);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        max-width: 55vw;
    }
    /* Back button */
    .dh-nav-back {
        display: flex; align-items: center; gap: 6px;
        font-size: .82rem; font-weight: 600; color: var(--ink, #0d0d0d);
        text-decoration: none; padding: 8px 4px;
        -webkit-tap-highlight-color: transparent;
    }
    .dh-nav-back i { font-size: .9rem; }

    /* ── Hero ──────────────────────────────────────── */
    .dh-hero-title { font-size: 1.75rem; }
    .dh-hero-sub   { font-size: .82rem; }

    /* ── Location picker button ────────────────────── */
    .loc-btn { padding: 10px 18px; font-size: .8rem; }

    /* ── Category tiles — bigger touch target ──────── */
    .dh-gtile {
        width: 72px; padding: 11px 4px 9px;
        font-size: .64rem;
    }
    .dh-gtile .gtile-icon { width: 32px; height: 32px; }

    /* ── Cards — full width, better touch ──────────── */
    .dh-card { border-radius: 14px; }
    .dh-card-media img,
    .dh-card-media video { height: 180px; }

    /* ── Section padding ───────────────────────────── */
    .dh-carousel-sec { padding-top: 24px; }
    .dh-carousel-block { margin-bottom: 28px; }

    /* ── Listing chips — full scroll, no wrap ──────── */
    .dh-chips-row:not(.chips-scroll) {
        flex-wrap: nowrap !important;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .dh-chips-row:not(.chips-scroll)::-webkit-scrollbar { display: none; }

    /* ── Filter panel → bottom sheet ──────────────── */
    .ps-filter-panel,
    .dh-filter-panel {
        position: fixed !important;
        bottom: calc(var(--bot-nav-h) + var(--safe-bottom)) !important;
        left: 0 !important; right: 0 !important;
        top: auto !important;
        background: #fff !important;
        border-radius: 20px 20px 0 0 !important;
        box-shadow: 0 -8px 40px rgba(0,0,0,.18) !important;
        padding: 20px 20px calc(16px + var(--safe-bottom)) !important;
        max-height: 80vh; overflow-y: auto;
        transform: translateY(100%);
        transition: transform .28s cubic-bezier(.4,0,.2,1);
        z-index: 800;
        display: block !important; /* always in DOM, slide with transform */
    }
    .ps-filter-panel.open,
    .dh-filter-panel.open {
        transform: translateY(0);
    }
    /* Sheet drag handle */
    .ps-filter-panel::before,
    .dh-filter-panel::before {
        content: '';
        display: block; width: 36px; height: 4px;
        background: #e2e8f0; border-radius: 2px;
        margin: 0 auto 18px;
    }

    /* Filter backdrop */
    .filter-backdrop {
        position: fixed; inset: 0; background: rgba(0,0,0,.45);
        z-index: 799; display: none;
        -webkit-backdrop-filter: blur(2px);
    }
    .filter-backdrop.open { display: block; }

    /* ── Post detail ────────────────────────────────── */
    /* Sticky bottom CTA on mobile */
    .mobile-cta-bar {
        position: fixed;
        bottom: calc(var(--bot-nav-h) + var(--safe-bottom));
        left: 0; right: 0;
        background: #fff;
        border-top: 1px solid #f1f5f9;
        padding: 10px 16px calc(10px + var(--safe-bottom));
        display: flex; gap: 10px; z-index: 700;
        box-shadow: 0 -4px 20px rgba(0,0,0,.10);
    }
    .mobile-cta-bar a {
        flex: 1; display: flex; align-items: center; justify-content: center;
        gap: 7px; padding: 12px 10px; border-radius: 12px;
        font-size: .82rem; font-weight: 700; text-decoration: none;
        -webkit-tap-highlight-color: transparent;
    }
    .mobile-cta-call { background: #0d0d0d; color: #fff; }
    .mobile-cta-wa   { background: #25d366; color: #fff; }
    /* Add bottom padding when CTA bar is shown */
    body.has-cta-bar {
        padding-bottom: calc(var(--bot-nav-h) + 72px + var(--safe-bottom));
    }

    /* ── Wrap padding ───────────────────────────────── */
    .wrap { padding: 0 16px; }

} /* end @media mobile */

/* ══════════════════════════════════════════════════
   BOTTOM NAVIGATION BAR
   Shown on mobile only.
══════════════════════════════════════════════════ */
.bot-nav {
    display: none; /* hidden on desktop */
}

@media (max-width: 768px) {
    .bot-nav {
        display: flex;
        position: fixed;
        bottom: 0; left: 0; right: 0;
        height: calc(var(--bot-nav-h) + var(--safe-bottom));
        padding-bottom: var(--safe-bottom);
        background: rgba(255,255,255,.96);
        backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
        border-top: 1px solid rgba(0,0,0,.08);
        box-shadow: 0 -4px 24px rgba(0,0,0,.10);
        z-index: 990;
        align-items: stretch;
    }
    .bot-nav-item {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 4px; text-decoration: none;
        font-size: .6rem; font-weight: 600; letter-spacing: .02em;
        color: #94a3b8; padding: 0 4px;
        -webkit-tap-highlight-color: transparent;
        transition: color .15s;
        position: relative;
    }
    .bot-nav-item.active { color: #0d0d0d; }
    .bot-nav-item:active  { opacity: .7; }

    /* Icon bubble */
    .bot-nav-icon {
        width: 40px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 20px; font-size: 1.05rem;
        transition: background .15s, transform .15s;
    }
    .bot-nav-item.active .bot-nav-icon {
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    /* Active indicator dot */
    .bot-nav-item.active::after {
        content: '';
        position: absolute; bottom: 6px;
        width: 4px; height: 4px; border-radius: 50%;
        background: #0d0d0d;
    }

    /* WhatsApp special color */
    .bot-nav-item.bot-wa { color: #25d366; }
    .bot-nav-item.bot-wa.active .bot-nav-icon { background: #dcfce7; }
    .bot-nav-item.bot-wa::after { background: #25d366; }
}
</style>

{{-- Bottom navigation bar (hidden on desktop via CSS) --}}
<nav class="bot-nav" aria-label="Bottom navigation">
    <a href="{{ route('home') }}"
       class="bot-nav-item {{ request()->routeIs('home') ? 'active':'' }}">
        <span class="bot-nav-icon"><i class="fas fa-house"></i></span>
        <span>Home</span>
    </a>

    <a href="{{ route('posts.listing') }}"
       class="bot-nav-item {{ request()->routeIs('posts.listing') ? 'active':'' }}">
        <span class="bot-nav-icon"><i class="fas fa-compass"></i></span>
        <span>Browse</span>
    </a>

    <a href="{{ route('posts.listing') }}?sort=popular"
       class="bot-nav-item {{ request()->get('sort')==='popular' ? 'active':'' }}">
        <span class="bot-nav-icon"><i class="fas fa-fire"></i></span>
        <span>Popular</span>
    </a>

    <a href="https://wa.me/918086087050?text=Hello%20I%20am%20interested%20in%20your%20listing"
       target="_blank" class="bot-nav-item bot-wa">
        <span class="bot-nav-icon"><i class="fab fa-whatsapp"></i></span>
        <span>Contact</span>
    </a>
</nav>

{{-- Filter backdrop (for mobile bottom-sheet filter) --}}
<div class="filter-backdrop" id="filterBackdrop"></div>

<script>
/* Filter backdrop close */
document.getElementById('filterBackdrop')?.addEventListener('click', function () {
    document.querySelectorAll('.ps-filter-panel.open, .dh-filter-panel.open')
            .forEach(el => el.classList.remove('open'));
    this.classList.remove('open');
});

/* On mobile, filter toggle should also show backdrop */
(function () {
    const toggleBtns = document.querySelectorAll('#toggleFilters, #filterToggle');
    const backdrop   = document.getElementById('filterBackdrop');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const panel = document.querySelector('.ps-filter-panel, .dh-filter-panel');
            if (!panel) return;
            const isOpen = panel.classList.toggle('open');
            backdrop.classList.toggle('open', isOpen);
        });
    });
})();
</script>