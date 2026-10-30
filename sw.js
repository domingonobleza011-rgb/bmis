/**
 * Barangay San Pedro BMIS — Service Worker
 * Provides offline support, asset caching, background sync, AND
 * Firebase Cloud Messaging background push (merged in so there is only
 * ONE service worker registered at scope '/', avoiding the scope
 * collision that used to exist with a separate firebase-messaging-sw.js).
 */

importScripts('https://www.gstatic.com/firebasejs/10.14.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.14.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey:            "AIzaSyDsAtSrF718UU4oz-_c_s3Wu59DrUKVe4s",
    authDomain:        "barangay-management-syst-3512b.firebaseapp.com",
    projectId:         "barangay-management-syst-3512b",
    storageBucket:     "barangay-management-syst-3512b.firebasestorage.app",
    messagingSenderId: "560258747749",
    appId:             "1:560258747749:web:67b0cf36663c11e74fa58c"
});

// firebase-messaging-compat registers its own internal 'push' listener and
// calls this callback for messages with a `notification` payload while the
// site isn't in the foreground. We handle background display HERE instead
// of in the generic self.addEventListener('push', ...) below, to avoid
// showing the notification twice.
const messaging = firebase.messaging();
messaging.onBackgroundMessage(function (payload) {
    console.log('[SW] FCM background message:', payload);
    const title = payload.notification?.title || 'Barangay San Pedro';
    const body  = payload.notification?.body  || 'You have a new notification.';
    const url   = payload.data?.url || payload.fcmOptions?.link || '/';

    self.registration.showNotification(title, {
        body:    body,
        icon:    'icons/pwa/icon-192x192.png',
        badge:   'icons/pwa/icon-96x96.png',
        tag:     'bmis-notification',
        data:    url,
        vibrate: [200, 100, 200],
        actions: [
            { action: 'open',    title: 'Open',    icon: '/icons/pwa/icon-72x72.png' },
            { action: 'dismiss', title: 'Dismiss' },
        ],
    });
});

const CACHE_VERSION = 'v3';
const STATIC_CACHE  = `bmis-static-${CACHE_VERSION}`;
const DYNAMIC_CACHE = `bmis-dynamic-${CACHE_VERSION}`;
const OFFLINE_URL   = 'offline.php';

// Core assets to pre-cache on install
const STATIC_ASSETS = [
  './',
  'index.php',
  'login.php',
  'offline.php',
  'manifest.json',

  // CSS
  'css/index.css',
  'css/homestyle.css',
  'css/pagestyle.css',
  'css/sb-admin-2.min.css',

  // JS
  'js/sb-admin-2.min.js',

  // Icons / images
  'icons/pwa/icon-192x192.png',
  'icons/pwa/icon-512x512.png',
  'icons/logo.png',
];

// ─── Install ────────────────────────────────────────────────────────────────
self.addEventListener('install', event => {
  console.log('[SW] Installing…');
  event.waitUntil(
    caches.open(STATIC_CACHE).then(cache => {
      // Add assets one-by-one so a single 404 doesn't abort everything
      return Promise.allSettled(
        STATIC_ASSETS.map(url => cache.add(url).catch(() => {
          console.warn('[SW] Could not pre-cache:', url);
        }))
      );
    }).then(() => self.skipWaiting())
  );
});

// ─── Activate ───────────────────────────────────────────────────────────────
self.addEventListener('activate', event => {
  console.log('[SW] Activating…');
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys
          .filter(k => k !== STATIC_CACHE && k !== DYNAMIC_CACHE)
          .map(k => {
            console.log('[SW] Deleting old cache:', k);
            return caches.delete(k);
          })
      )
    ).then(() => self.clients.claim())
  );
});

// ─── Fetch ──────────────────────────────────────────────────────────────────
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET and cross-origin requests
  if (request.method !== 'GET') return;
  if (url.origin !== self.location.origin) return;

  // Strategy: Network-first for PHP pages, Cache-first for static assets
  if (url.pathname.endsWith('.php') || url.pathname === '/' || ['/privacy', '/terms', '/cookie-policy'].includes(url.pathname)) {
    event.respondWith(networkFirstStrategy(request));
  } else {
    event.respondWith(cacheFirstStrategy(request));
  }
});

/**
 * Network-first: try network, fall back to cache, fall back to offline page.
 */
async function networkFirstStrategy(request) {
  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(DYNAMIC_CACHE);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    const cached = await caches.match(request);
    if (cached) return cached;

    // If it's a navigation request, show the offline page
    if (request.mode === 'navigate') {
      const offline = await caches.match(OFFLINE_URL);
      if (offline) return offline;
    }
    return new Response('You are offline and this page is not cached.', {
      status: 503,
      headers: { 'Content-Type': 'text/plain' },
    });
  }
}

/**
 * Cache-first: serve from cache, update cache in background.
 */
async function cacheFirstStrategy(request) {
  const cached = await caches.match(request);
  if (cached) {
    // Stale-while-revalidate: update cache in background
    fetch(request).then(response => {
      if (response.ok) {
        caches.open(STATIC_CACHE).then(cache => cache.put(request, response));
      }
    }).catch(() => {});
    return cached;
  }

  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(STATIC_CACHE);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    return new Response('Asset unavailable offline.', { status: 503 });
  }
}

// ─── Push Notifications ─────────────────────────────────────────────────────
// NOTE: All FCM push display is now handled above by messaging.onBackgroundMessage().
// The raw 'push' listener that used to live here was removed because Firebase
// Messaging's compat library ALSO listens for the 'push' event internally —
// having both meant every background notification was shown twice.

self.addEventListener('notificationclick', event => {
  event.notification.close();
  if (event.action === 'dismiss') return;
  const url = event.notification.data || '/';
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(list => {
      for (const client of list) {
        if (client.url === url && 'focus' in client) return client.focus();
      }
      return clients.openWindow(url);
    })
  );
});

// ─── Background Sync ────────────────────────────────────────────────────────
self.addEventListener('sync', event => {
  if (event.tag === 'sync-pending-forms') {
    event.waitUntil(syncPendingForms());
  }
});

async function syncPendingForms() {
  console.log('[SW] Background sync: syncing pending form submissions…');
}