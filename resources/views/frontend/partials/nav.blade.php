{{--
    Shared site nav — identical on every public-facing page.
    Include with: @include('frontend.partials.nav', ['activeNav' => 'home'])

    Optional variables:
      $categories      Collection of categories (falls back to empty — nav still works)
      $activeNav        'home' | 'listing' | 'contact' | null — highlights the matching link
      $mobileNavAction  JS to run on the mobile icon button's onclick (default: open post-ad modal)
      $activeLocName    Pre-fills the location trigger (e.g. the locality currently filtered on)
      $transparent      true = nav starts see-through over a dark hero, turns solid navy on scroll.
                         Only use on pages with a dark/image section directly beneath the nav —
                         defaults to false (always-solid), which is safe everywhere.
--}}
@php
    $categories      = $categories ?? collect();
    $activeNav       = $activeNav ?? null;
    $mobileNavAction = $mobileNavAction ?? 'openPostAdModal && openPostAdModal()';
    $activeLocName   = $activeLocName ?? null;
    $transparent     = $transparent ?? false;
    $navSiteName     = setting('site_name', 'DealsHood');
@endphp

<nav class="dh-nav {{ $transparent ? 'dh-nav-transparent' : '' }}" id="dhSiteNav">
    <div class="dh-nav-inner">
        <a href="{{ route('home') }}" class="dh-nav-logo"><img src="{{ site_logo_url() }}" alt="{{ $navSiteName }}"></a>
        <div class="dh-nav-links">
            <a href="{{ route('posts.listing') }}" class="{{ $activeNav === 'listing' ? 'active' : '' }}">All Deals</a>
            @foreach($categories->take(3) as $navCat)
                <a href="{{ route('posts.listing', ['category_id' => $navCat->slug]) }}">{{ Str::limit($navCat->name, 16) }}</a>
            @endforeach
            <a href="{{ route('contact') }}" class="{{ $activeNav === 'contact' ? 'active' : '' }}">Contact</a>
        </div>
        <a href="{{ route('posts.listing') }}" class="dh-nav-search">
            <i class="fas fa-magnifying-glass"></i> search
        </a>
        <div class="dh-nav-spacer"></div>
        <button class="loc-trigger {{ $activeLocName ? 'has-loc' : '' }}" id="locTrigger" type="button"
                onclick="window.openLocationPopup && window.openLocationPopup()">
            <span class="lt-pin"><i class="fas fa-map-marker-alt"></i></span>
            <span class="lt-dot"></span>
            <span class="lt-label" id="locLabel">{{ $activeLocName ?: 'Location' }}</span>
            <i class="fas fa-chevron-down lt-chevron"></i>
        </button>
        <button class="dh-nav-icon-btn" type="button" onclick="{{ $mobileNavAction }}" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>
        <div class="dh-nav-actions" id="navActions">
            <a href="{{ Route::has('login') ? route('login') : '#' }}" class="dh-btn-signin">Sign In</a>
            <a href="#" class="dh-btn-download" onclick="return dhInstallApp(event)">
                <i class="fas fa-download" style="font-size:.72rem;"></i> <span class="dh-nav-download-txt">Download App</span>
            </a>
        </div>
    </div>

{{-- Android/Chrome PWA banner --}}
<div class="pwa-banner" id="pwaBanner" style="display:none;">
    <div class="pwa-banner-icon"><img src="/frontend/img/icons/icon-192x192.png" alt="{{ $navSiteName }}"></div>
    <div class="pwa-banner-body">
        <div class="pwa-banner-title">Install {{ $navSiteName }}</div>
        <div class="pwa-banner-sub">Add to home screen for quick access</div>
    </div>
    <div class="pwa-banner-actions">
        <button class="pwa-install-btn" id="pwaInstallBtn">Install</button>
        <button class="pwa-dismiss-btn" id="pwaDismissBtn">✕</button>
    </div>
</div>

{{-- iOS Safari instruction sheet --}}
<div class="pwa-backdrop" id="pwaBackdrop"></div>
<div class="pwa-ios-sheet" id="pwaIosSheet">
    <div class="pwa-ios-handle"></div>
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:4px;">
        <img src="/frontend/img/icons/icon-72x72.png" style="width:44px;height:44px;border-radius:10px;" alt="">
        <div>
            <div style="font-size:1rem;font-weight:800;color:#0f172a;">Install {{ $navSiteName }}</div>
            <div style="font-size:.78rem;color:#64748b;">Add to your home screen</div>
        </div>
    </div>
    <ul class="pwa-ios-steps">
        <li class="pwa-ios-step">
            <span class="pwa-ios-step-num">1</span>
            <span>Tap the <span class="pwa-ios-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/>
                    <line x1="12" y1="2" x2="12" y2="15"/></svg> Share</span> button at the bottom of Safari</span>
        </li>
        <li class="pwa-ios-step">
            <span class="pwa-ios-step-num">2</span>
            <span>Scroll down and tap <span class="pwa-ios-icon">＋ Add to Home Screen</span></span>
        </li>
        <li class="pwa-ios-step">
            <span class="pwa-ios-step-num">3</span>
            <span>Tap <strong>Add</strong> in the top right corner</span>
        </li>
    </ul>
    <button onclick="closePwaIos()" style="width:100%;margin-top:16px;padding:13px;background:#0a2a68;color:#fff;
            border:none;border-radius:12px;font-size:.88rem;font-weight:700;cursor:pointer;">Got it</button>
</div>
</nav>

<script>
/* ── PWA install (shared) ── */
let __dhDeferredPrompt = null;
window.addEventListener('beforeinstallprompt', function (e) {
    e.preventDefault();
    __dhDeferredPrompt = e;
    setTimeout(function(){
        var b = document.getElementById('pwaBanner');
        if (b) b.style.display = 'flex';
    }, 3000);
});
function dhInstallApp(ev) {
    ev.preventDefault();
    var isIOS    = /iphone|ipad|ipod/i.test(navigator.userAgent);
    var isSafari = /safari/i.test(navigator.userAgent) && !/chrome/i.test(navigator.userAgent);
    if (__dhDeferredPrompt) {
        __dhDeferredPrompt.prompt();
        __dhDeferredPrompt.userChoice.finally(function(){ __dhDeferredPrompt = null; });
    } else if (isIOS && isSafari) {
        openPwaIos();
    } else {
        alert('Open your browser menu → "Add to Home Screen" to install {{ $navSiteName }}.');
    }
    return false;
}
window.dhInstallApp = dhInstallApp;

function openPwaIos(){
    document.getElementById('pwaIosSheet')?.classList.add('open');
    document.getElementById('pwaBackdrop')?.classList.add('open');
}
function closePwaIos(){
    document.getElementById('pwaIosSheet')?.classList.remove('open');
    document.getElementById('pwaBackdrop')?.classList.remove('open');
}
window.closePwaIos = closePwaIos;
document.getElementById('pwaBackdrop')?.addEventListener('click', closePwaIos);
document.getElementById('pwaDismissBtn')?.addEventListener('click', function(){
    var b = document.getElementById('pwaBanner');
    if (b) b.style.display = 'none';
});
document.getElementById('pwaInstallBtn')?.addEventListener('click', function(ev){ dhInstallApp(ev); });

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js').catch(function(){});
    });
}

@if($transparent)
/* ── Transparent-until-scrolled nav ── */
(function () {
    var nav = document.getElementById('dhSiteNav');
    if (!nav) return;
    function onScroll() {
        nav.classList.toggle('scrolled', window.scrollY > 12);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();
@endif
</script>
