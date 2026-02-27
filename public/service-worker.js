const CACHE_NAME = "laravel-pwa-v3";
const urlsToCache = [
  "/",             // página inicial
  "/css/app.css",  // seu CSS
  "/js/app.js",    // seu JS
];

// Instala e guarda arquivos principais
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      // Adiciona cada URL individualmente para evitar falha se uma não existir
      return Promise.allSettled(
        urlsToCache.map((url) => {
          return cache.add(url).catch((err) => {
            console.warn(`Falha ao adicionar ${url} ao cache:`, err);
            return null;
          });
        })
      );
    })
  );
  // Força a ativação imediata do service worker
  self.skipWaiting();
});

// Remove caches antigos
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
  // Força o controle imediato de todas as páginas
  return self.clients.claim();
});

// Estratégia: Network First + Cache Fallback
self.addEventListener("fetch", (event) => {
  // Ignora requisições que não são GET
  if (event.request.method !== 'GET') {
    return;
  }

  event.respondWith(
    fetch(event.request)
      .then((response) => {
        // Só cacheia respostas válidas (status 200-299)
        if (response.status >= 200 && response.status < 300) {
          const responseClone = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseClone).catch((err) => {
              console.warn(`Falha ao adicionar ao cache:`, err);
            });
          });
        }
        return response;
      })
      .catch(() => {
        // Se offline ou erro, tenta pegar do cache
        return caches.match(event.request).then((cachedResponse) => {
          return cachedResponse || new Response('Recurso não encontrado', { status: 404 });
        });
      })
  );
});
