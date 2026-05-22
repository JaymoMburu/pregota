const CACHE = 'pregota-v1';
const SHELL = ['/', '/redeem', '/track'];

self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE).then(c => c.addAll(SHELL))
    );
    self.skipWaiting();
});

self.addEventListener('activate', e => {
    e.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', e => {
    const url = new URL(e.request.url);

    // Never cache: POST requests, API calls, admin panel
    if (e.request.method !== 'GET') return;
    if (url.pathname.startsWith('/gift/')) return;
    if (url.pathname.startsWith('/mpesa/')) return;
    if (url.pathname.startsWith('/admin')) return;

    // Network-first for shell pages — fall back to cache if offline
    e.respondWith(
        fetch(e.request)
            .then(res => {
                const clone = res.clone();
                caches.open(CACHE).then(c => c.put(e.request, clone));
                return res;
            })
            .catch(() => caches.match(e.request))
    );
});
