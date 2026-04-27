/* =============================================================
   FIKA - Cafetería · Hoja de estilos principal
   Diseño responsive, paleta cálida, tipografía serif elegante
   ============================================================= */

/* ---------- 1. Variables de diseño ---------- */
:root {
    --color-fondo:      #faf6f1;
    --color-crema:      #f1e7d8;
    --color-marron:     #5b3a29;
    --color-marron-os:  #3b2518;
    --color-acento:     #b8865a;   /* caramelo */
    --color-acento-os:  #8f6234;
    --color-texto:      #2b211b;
    --color-tenue:      #8a7a6d;
    --color-blanco:     #ffffff;
    --color-error:      #b33a3a;
    --color-exito:      #4a7a3a;

    --fuente-titulo: 'Playfair Display', Georgia, serif;
    --fuente-texto:  'Inter', 'Helvetica Neue', Arial, sans-serif;

    --sombra-suave: 0 4px 20px rgba(91, 58, 41, 0.08);
    --sombra-media: 0 10px 30px rgba(91, 58, 41, 0.15);
    --radio:        10px;
    --transicion:   0.3s ease;
}

/* ---------- 2. Reset y base ---------- */
* { box-sizing: border-box; margin: 0; padding: 0; }

html { scroll-behavior: smooth; }

body {
    font-family: var(--fuente-texto);
    color: var(--color-texto);
    background: var(--color-fondo);
    line-height: 1.6;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

main { flex: 1; }

h1, h2, h3, h4 {
    font-family: var(--fuente-titulo);
    color: var(--color-marron-os);
    font-weight: 600;
    line-height: 1.2;
}

h1 { font-size: clamp(2rem, 5vw, 3.5rem); }
h2 { font-size: clamp(1.6rem, 3.5vw, 2.4rem); margin-bottom: 1rem; }
h3 { font-size: 1.3rem; margin-bottom: 0.5rem; }

a { color: var(--color-acento-os); text-decoration: none; transition: color var(--transicion); }
a:hover { color: var(--color-marron); }

img { max-width: 100%; display: block; }

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* ---------- 3. Cabecera y navegación ---------- */
.site-header {
    background: var(--color-blanco);
    box-shadow: var(--sombra-suave);
    position: sticky;
    top: 0;
    z-index: 100;
}

.nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

.logo {
    font-family: var(--fuente-titulo);
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--color-marron-os);
    letter-spacing: 3px;
}
.logo span { color: var(--color-acento); }

.nav-links {
    display: flex;
    gap: 2rem;
    list-style: none;
    align-items: center;
}
.nav-links a {
    color: var(--color-texto);
    font-weight: 500;
    font-size: 0.95rem;
    position: relative;
    padding: 0.2rem 0;
}
.nav-links a::after {
    content: "";
    position: absolute;
    left: 0; bottom: -4px;
    width: 0; height: 2px;
    background: var(--color-acento);
    transition: width var(--transicion);
}
.nav-links a:hover::after,
.nav-links a.active::after { width: 100%; }

.nav-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.6rem;
    cursor: pointer;
    color: var(--color-marron-os);
}

/* ---------- 4. Botones ---------- */
.btn {
    display: inline-block;
    padding: 0.8rem 2rem;
    border-radius: var(--radio);
    font-weight: 600;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
    cursor: pointer;
    border: none;
    transition: all var(--transicion);
    text-align: center;
}
.btn-primario {
    background: var(--color-marron-os);
    color: var(--color-blanco);
}
.btn-primario:hover {
    background: var(--color-acento-os);
    color: var(--color-blanco);
    transform: translateY(-2px);
    box-shadow: var(--sombra-media);
}
.btn-secundario {
    background: transparent;
    color: var(--color-marron-os);
    border: 2px solid var(--color-marron-os);
}
.btn-secundario:hover {
    background: var(--color-marron-os);
    color: var(--color-blanco);
}
.btn-peq { padding: 0.5rem 1rem; font-size: 0.85rem; }
.btn-peligro { background: var(--color-error); color: #fff; }
.btn-peligro:hover { background: #922; transform: translateY(-1px); }

/* ---------- 5. Hero (portada) ---------- */
.hero {
    position: relative;
    min-height: 78vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 4rem 1.5rem;
    background:
        linear-gradient(rgba(43, 33, 27, 0.55), rgba(43, 33, 27, 0.55)),
        url('../img/hero.jpg') center/cover no-repeat;
    color: var(--color-blanco);
}
.hero-contenido { max-width: 800px; }
.hero h1 { color: var(--color-blanco); margin-bottom: 1rem; }
.hero .subtitulo {
    font-family: var(--fuente-titulo);
    font-style: italic;
    font-size: 1.3rem;
    margin-bottom: 2rem;
    opacity: 0.92;
}
.hero .botones {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}
.hero .btn-secundario { color: #fff; border-color: #fff; }
.hero .btn-secundario:hover { background: #fff; color: var(--color-marron-os); }

/* ---------- 6. Secciones ---------- */
section {
    padding: 5rem 0;
}
.seccion-alt {
    background: var(--color-crema);
}
.titulo-seccion {
    text-align: center;
    margin-bottom: 3rem;
}
.titulo-seccion::after {
    content: "";
    display: block;
    width: 60px;
    height: 3px;
    background: var(--color-acento);
    margin: 1rem auto 0;
}

/* Sobre nosotros */
.sobre-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}
.sobre-grid p { margin-bottom: 1rem; color: var(--color-texto); }
.sobre-img {
    border-radius: var(--radio);
    box-shadow: var(--sombra-media);
    aspect-ratio: 4/3;
    background: linear-gradient(135deg, var(--color-acento), var(--color-marron-os));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-family: var(--fuente-titulo);
    font-size: 4rem;
    font-style: italic;
}

/* Características */
.caracteristicas {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}
.carac {
    background: var(--color-blanco);
    padding: 2rem;
    border-radius: var(--radio);
    text-align: center;
    box-shadow: var(--sombra-suave);
    transition: transform var(--transicion);
}
.carac:hover { transform: translateY(-5px); }
.carac .icono {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: var(--color-acento);
}

/* ---------- 7. Menú (productos) ---------- */
.filtros {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
    margin-bottom: 3rem;
}
.filtro-btn {
    background: transparent;
    border: 2px solid var(--color-marron-os);
    color: var(--color-marron-os);
    padding: 0.5rem 1.3rem;
    border-radius: 30px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all var(--transicion);
}
.filtro-btn.activo,
.filtro-btn:hover {
    background: var(--color-marron-os);
    color: var(--color-blanco);
}

.productos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 2rem;
}
.producto {
    background: var(--color-blanco);
    border-radius: var(--radio);
    overflow: hidden;
    box-shadow: var(--sombra-suave);
    transition: transform var(--transicion), box-shadow var(--transicion);
    display: flex;
    flex-direction: column;
}
.producto:hover {
    transform: translateY(-4px);
    box-shadow: var(--sombra-media);
}
.producto-img {
    height: 180px;
    background: linear-gradient(135deg, var(--color-crema), var(--color-acento));
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--fuente-titulo);
    font-size: 3rem;
    color: var(--color-marron-os);
    font-style: italic;
}
.producto-info {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.producto h3 { margin-bottom: 0.4rem; }
.producto-categoria {
    font-size: 0.8rem;
    color: var(--color-tenue);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 0.5rem;
}
.producto-desc {
    color: var(--color-texto);
    font-size: 0.92rem;
    flex: 1;
    margin-bottom: 1rem;
}
.producto-pie {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.producto-precio {
    font-family: var(--fuente-titulo);
    font-size: 1.4rem;
    color: var(--color-acento-os);
    font-weight: 700;
}

/* ---------- 8. Formularios ---------- */
.form-card {
    max-width: 500px;
    margin: 3rem auto;
    background: var(--color-blanco);
    padding: 3rem;
    border-radius: var(--radio);
    box-shadow: var(--sombra-suave);
}
.form-card h2 { text-align: center; }

.form-grupo {
    margin-bottom: 1.2rem;
}
.form-grupo label {
    display: block;
    margin-bottom: 0.4rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--color-marron-os);
}
.form-grupo input,
.form-grupo select,
.form-grupo textarea {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 1.5px solid #e5ddd3;
    border-radius: 8px;
    font-family: var(--fuente-texto);
    font-size: 0.95rem;
    background: var(--color-fondo);
    color: var(--color-texto);
    transition: border-color var(--transicion);
}
.form-grupo input:focus,
.form-grupo select:focus,
.form-grupo textarea:focus {
    outline: none;
    border-color: var(--color-acento);
    background: #fff;
}
.form-grupo textarea { resize: vertical; min-height: 120px; }

.form-ayuda {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: var(--color-tenue);
}

/* Mensajes de alerta */
.alerta {
    padding: 0.9rem 1.2rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}
.alerta-error { background: #fbe8e8; color: var(--color-error); border-left: 4px solid var(--color-error); }
.alerta-exito { background: #e8f4e3; color: var(--color-exito); border-left: 4px solid var(--color-exito); }

/* ---------- 9. Contacto (dos columnas) ---------- */
.contacto-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 3rem;
    align-items: start;
}
.contacto-info h3 { margin-top: 1.5rem; }
.contacto-info p { margin-bottom: 0.5rem; color: var(--color-texto); }
.contacto-info .dato { display: flex; align-items: center; gap: 0.6rem; }

/* ---------- 10. Tablas (admin y reservas) ---------- */
.tabla-wrap { overflow-x: auto; }
.tabla {
    width: 100%;
    border-collapse: collapse;
    background: var(--color-blanco);
    border-radius: var(--radio);
    overflow: hidden;
    box-shadow: var(--sombra-suave);
}
.tabla th,
.tabla td {
    padding: 0.9rem 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-size: 0.92rem;
}
.tabla th {
    background: var(--color-marron-os);
    color: #fff;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.tabla tr:hover td { background: var(--color-fondo); }

.estado {
    display: inline-block;
    padding: 0.2rem 0.7rem;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.estado-pendiente   { background: #fde9c7; color: #8f6a1a; }
.estado-confirmada  { background: #e0f0d6; color: var(--color-exito); }
.estado-cancelada   { background: #fbe0e0; color: var(--color-error); }

/* ---------- 11. Panel admin ---------- */
.admin-layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
.admin-sidebar {
    background: var(--color-marron-os);
    color: var(--color-blanco);
    padding: 2rem 1rem;
}
.admin-sidebar .logo { color: #fff; display: block; text-align: center; margin-bottom: 2rem; }
.admin-sidebar .logo span { color: var(--color-acento); }
.admin-sidebar ul { list-style: none; }
.admin-sidebar a {
    display: block;
    padding: 0.7rem 1rem;
    color: #ece4d7;
    border-radius: 8px;
    margin-bottom: 0.3rem;
    font-size: 0.95rem;
    transition: background var(--transicion);
}
.admin-sidebar a:hover,
.admin-sidebar a.activo {
    background: rgba(255,255,255,0.08);
    color: #fff;
}
.admin-main { padding: 2.5rem; background: var(--color-fondo); }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}
.stat-card {
    background: #fff;
    padding: 1.5rem;
    border-radius: var(--radio);
    box-shadow: var(--sombra-suave);
    border-left: 4px solid var(--color-acento);
}
.stat-card .etiqueta {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--color-tenue);
}
.stat-card .valor {
    font-family: var(--fuente-titulo);
    font-size: 2.3rem;
    color: var(--color-marron-os);
    margin-top: 0.3rem;
}

/* ---------- 12. Footer ---------- */
.site-footer {
    background: var(--color-marron-os);
    color: #d8ccbe;
    padding: 3rem 1.5rem 1.5rem;
    margin-top: 4rem;
}
.footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
}
.site-footer h4 { color: #fff; margin-bottom: 1rem; font-size: 1rem; }
.site-footer a { color: #d8ccbe; font-size: 0.9rem; }
.site-footer a:hover { color: var(--color-acento); }
.site-footer ul { list-style: none; }
.site-footer li { margin-bottom: 0.4rem; }
.footer-bottom {
    text-align: center;
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 1.5rem;
    margin-top: 2rem;
    font-size: 0.85rem;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

/* ---------- 13. Utilidades ---------- */
.text-center { text-align: center; }
.mt-1 { margin-top: 1rem; }
.mt-2 { margin-top: 2rem; }
.mb-2 { margin-bottom: 2rem; }

/* Fade in al cargar */
.fade-in { animation: fadeIn 0.6s ease; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ---------- 14. Responsive ---------- */
@media (max-width: 900px) {
    .sobre-grid,
    .contacto-grid { grid-template-columns: 1fr; gap: 2rem; }
    .footer-grid { grid-template-columns: 1fr 1fr; }
    .admin-layout { grid-template-columns: 1fr; }
    .admin-sidebar { padding: 1rem; }
    .admin-sidebar ul { display: flex; flex-wrap: wrap; gap: 0.3rem; }
    .admin-sidebar li { flex: 1 1 auto; }
}
@media (max-width: 640px) {
    .nav-toggle { display: block; }
    .nav-links {
        display: none;
        position: absolute;
        top: 100%;
        left: 0; right: 0;
        flex-direction: column;
        background: #fff;
        padding: 1rem 1.5rem;
        gap: 1rem;
        box-shadow: var(--sombra-suave);
    }
    .nav-links.abierto { display: flex; }
    .hero .botones { flex-direction: column; }
    .hero .btn { width: 100%; }
    .form-card { padding: 2rem 1.5rem; }
    .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
    section { padding: 3rem 0; }
}
