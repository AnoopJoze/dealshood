// public/sw.js — DealsHood Service Worker

const CACHE_NAME    = 'dealshood-v1';
const OFFLINE_PAGE  = '/offline';

// Assets to cache immediately on install
const PRECACHE = [
    '/',
    '/offline',
    '/frontend/css/soft-design-system.css',
    '/frontend/img/dealshood.png',
    '/frontend/img/favicon.png',
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

    // Network-first for HTML pages
    event.respondWith(
        fetch(request)
            .then(response => {
                // Cache successful HTML responses
                if (response.ok && request.destination === 'document') {
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