// resources/views/frontend/sw.blade.php — DealsHood Service Worker
// Server-rendered so the precached logo/favicon always match Settings.

const CACHE_NAME    = 'dealshood-v2'; // bumped to purge any stale/poisoned entries
const OFFLINE_PAGE  = '/offline';

// Assets to cache immediately on install
const PRECACHE = [
    '/',
    '/offline',
    '/frontend/css/soft-design-system.css',
    '{!! $logoUrl !!}',
    '{!! $faviconUrl !!}',
];

// ── Install: pre-cache shell assets ──────────────────
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
              .then(cache => cache.addAll(PRECACHE))
              .then(() => self.skipWaiting())
    );
});

// ── Activate: clean up old caches ────────────────────
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.filter(k => k !== CACHE_NAME)
                    .map(k => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

// ── Fetch: Network-first for API/HTML, Cache-first for assets ──
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET and admin requests
    if (request.method !== 'GET') return;
    if (url.pathname.startsWith('/admin')) return;
    if (url.pathname.startsWith('/api')) return;

    // ── CRITICAL: never let the SW touch AJAX/XHR calls.
    // These hit /listing, /, etc. with the SAME URL as real pages but
    // return JSON. Let the browser handle them natively — no cache
    // read, no cache write, no interception at all.
    if (request.headers.get('X-Requested-With') === 'XMLHttpRequest') {
        return;
    }

    // Cache-first for static assets (images, CSS, JS, fonts)
    if (request.destination === 'image' ||
        request.destination === 'style'  ||
        request.destination === 'script' ||
        request.destination === 'font') {
        event.respondWith(
            caches.match(request).then(cached => {
                if (cached) return cached;
                return fetch(request).then(response => {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                    return response;
                });
            })
        );
        return;
    }

    // Network-first for real page navigations only.
    // Use request.mode === 'navigate' instead of request.destination —
    // it's the reliable signal for "this is an actual browser
    // navigation" (including back/forward), as opposed to a
    // same-URL fetch()/XHR call.
    const isNavigation = request.mode === 'navigate' || request.destination === 'document';

    if (!isNavigation) {
        // Anything else GET that isn't a navigation and isn't a static
        // asset (e.g. unexpected XHR without the header) — just pass
        // through to network, don't cache, don't intercept on failure.
        event.respondWith(fetch(request));
        return;
    }

    event.respondWith(
        // cache: 'no-store' bypasses the browser's HTTP disk cache for this
        // fetch entirely. Without this, a navigation to a URL that was
        // previously fetched via AJAX (and cached per normal HTTP rules)
        // can silently return that old AJAX response here.
        fetch(request, { cache: 'no-store' })
            .then(response => {
                // Only cache genuine HTML responses for navigations.
                // Guards against ever caching a JSON body under a page URL.
                const contentType = response.headers.get('Content-Type') || '';
                if (response.ok && contentType.includes('text/html')) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                }
                return response;
            })
            .catch(() => {
                // Offline fallback
                return caches.match(request)
                    .then(cached => cached || caches.match(OFFLINE_PAGE));
            })
    );
});
