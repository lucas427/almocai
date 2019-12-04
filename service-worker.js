const cacheName = 'almocai-v12';
var assets = [
  // Base
  '/almocai/assets/css/almocai.css',
  '/almocai/assets/js/almocai.min.js',
  '/almocai/offline.html',

  // Imagens
  '/almocai/assets/img/logo-verde.svg',
  '/almocai/assets/img/logo-branco.svg',
  '/almocai/assets/img/entrar/fundo.png',
  '/almocai/assets/img/fundo-banner.jpg',
  '/almocai/assets/img/aluno/cartoes/cartao.jpg'
];

self.addEventListener('install', event => {
  console.log('Attempting to install service worker and cache static assets');
  event.waitUntil(
    caches.open(cacheName)
    .then(cache => {
      return cache.addAll(assets);
    })
  );
});


self.addEventListener('activate', event => {
  console.log('Activating new service worker...');
  const cacheWhitelist = [cacheName];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

cacheAssets(assets)
  .then(() => {
      console.log('All assets cached')
});


self.addEventListener('fetch', event => {
  var request = event.request;
  if (request.url.indexOf('fabricadetecnologias.ifc-riodosul.edu.br') > -1) { // Link do servidor onde se encontram os arquivos
    event.respondWith(
      caches.match(event.request)
      .then(function(response) {
        return response || fetch(request);
      })
      .catch(error => {
        console.log('Error, ', error);
        return caches.match('/almocai/offline.html');
      })
    );
  }
});

function cacheAssets( assets ) {
  return new Promise( function (resolve, reject) {
    caches.open('assets')
      .then(cache => {
        cache.addAll(assets)
          .then(() => {
            console.log('all assets added to cache')
            resolve()
          })
          .catch(err => {
            console.log('error when syncing assets', err)
            reject()
          })
      }).catch(err => {
        console.log('error when opening cache', err)
        reject()
      })
  });
}
