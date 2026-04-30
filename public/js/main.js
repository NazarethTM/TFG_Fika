// ============================================
// Fika · JS común a toda la web
// ============================================
//
// Cada módulo puede tener su propio archivo JS
// dentro de su carpeta. Por ejemplo:
//   public/mesas/mapa.js
//   public/pedidos/carrito.js
// y enlazarlo solo desde la página que lo use.

document.addEventListener('DOMContentLoaded', function () {
    // Auto-cerrar mensajes flash a los 4 segundos
    setTimeout(function () {
        document.querySelectorAll('.alert').forEach(function (a) {
            a.style.transition = 'opacity .4s';
            a.style.opacity = '0';
            setTimeout(function () { a.remove(); }, 400);
        });
    }, 4000);
});
