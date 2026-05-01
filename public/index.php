<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/funciones.php';

$pageTitle = 'Fika · Coffee & Good Moments';
$pdo = getDB();

// Productos destacados (los 6 más recientes y disponibles)
$productos = $pdo->query(
    "SELECT id, nombre, descripcion, categoria, precio, imagen
     FROM productos
     WHERE disponible = 1
     ORDER BY id DESC
     LIMIT 6"
)->fetchAll();

// Próximos cursos (3 más cercanos en el tiempo)
$cursos = $pdo->query(
    "SELECT id, titulo, descripcion, tipo, instructor, nivel, precio,
            duracion_min, fecha_inicio, imagen
     FROM cursos
     WHERE activo = 1 AND fecha_inicio >= NOW()
     ORDER BY fecha_inicio ASC
     LIMIT 3"
)->fetchAll();

// Iconos por categoría de producto
$iconoCategoria = [
    'cafe'       => 'bi-cup-hot-fill',
    'reposteria' => 'bi-cake2-fill',
    'bebida'     => 'bi-cup-straw',
    'otro'       => 'bi-egg-fried',
];

// Iconos por tipo de curso
$iconoCurso = [
    'reposteria' => 'bi-cake2-fill',
    'barista'    => 'bi-cup-hot',
    'cata'       => 'bi-droplet-half',
    'otro'       => 'bi-mortarboard-fill',
];

require __DIR__ . '/../includes/header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <span class="hero-tagline animar">Coffee &amp; Good Moments</span>
        <h1 class="animar fuente-decorativa delay-1">
            El café como excusa<br>para aprender y conectar
        </h1>
        <p class="lead animar delay-2">
            Una cafetería pensada para estudiar, trabajar y disfrutar.
            Reserva tu mesa, apúntate a cursos de barista o repostería,
            y haz tu pedido sin esperar cola.
        </p>
        <div class="animar delay-3 mt-4">
            <?php if (!estaLogueado()): ?>
                <a href="<?= BASE_URL ?>/registro.php" class="btn btn-terracota btn-lg me-2">
                    <i class="bi bi-person-plus"></i> Crear cuenta
                </a>
                <a href="<?= BASE_URL ?>/nosotros.php" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-info-circle"></i> Saber más
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/mesas/reservar.php" class="btn btn-terracota btn-lg me-2">
                    <i class="bi bi-geo-alt"></i> Reservar mesa
                </a>
                <a href="<?= BASE_URL ?>/cursos/listado.php" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-mortarboard"></i> Ver cursos
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- VENTAJAS -->
<section class="container my-5 py-3">
    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="ventaja-icono">
                <i class="bi bi-geo-alt-fill" style="color: var(--cafe-medio);"></i>
            </div>
            <h4>Reserva tu mesa</h4>
            <p class="text-muted">Elige tu sitio en el mapa interactivo y la franja horaria que necesites para concentrarte.</p>
        </div>
        <div class="col-md-4">
            <div class="ventaja-icono">
                <i class="bi bi-mortarboard-fill" style="color: var(--terracota);"></i>
            </div>
            <h4>Cursos profesionales</h4>
            <p class="text-muted">Repostería, barista y catas. Aprende con instructores expertos en clases prácticas y con plazas limitadas.</p>
        </div>
        <div class="col-md-4">
            <div class="ventaja-icono">
                <i class="bi bi-bag-check-fill" style="color: var(--verde-hoja);"></i>
            </div>
            <h4>Pedidos sin esperas</h4>
            <p class="text-muted">Pide tu café o repostería desde la web y recógelo cuando esté listo. Sin colas, sin perder tiempo.</p>
        </div>
    </div>
</section>

<!-- CARTA DESTACADA -->
<section class="container">
    <div class="section-title">
        <h2>De nuestra carta</h2>
        <p>Una pequeña selección de lo que te espera en Fika</p>
    </div>

    <?php if (count($productos) === 0): ?>
        <div class="empty-state">
            <i class="bi bi-cup-hot"></i>
            <h5>Próximamente</h5>
            <p class="mb-0">Estamos preparando nuestra carta. ¡Vuelve pronto!</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($productos as $p): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <?php if (!empty($p['imagen'])): ?>
                            <img src="<?= BASE_URL ?>/img/<?= e($p['imagen']) ?>"
                                 class="card-img-top" alt="<?= e($p['nombre']) ?>">
                        <?php else: ?>
                            <div class="card-img-placeholder">
                                <i class="bi <?= $iconoCategoria[$p['categoria']] ?? 'bi-cup' ?>"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="badge-categoria"><?= e($p['categoria']) ?></span>
                            <h5 class="card-title mt-2"><?= e($p['nombre']) ?></h5>
                            <p class="card-text text-muted small">
                                <?= e($p['descripcion']) ?>
                            </p>
                            <div class="d-flex justify-content-end align-items-center">
                                <span class="precio"><?= precio((float) $p['precio']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/pedidos/carta.php" class="btn btn-cafe">
                Ver carta completa <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    <?php endif; ?>
</section>

<!-- CURSOS DESTACADOS -->
<section class="container">
    <div class="section-title">
        <h2>Próximos cursos</h2>
        <p>Aprende con los mejores y eleva tu pasión por el café y la repostería</p>
    </div>

    <?php if (count($cursos) === 0): ?>
        <div class="empty-state">
            <i class="bi bi-mortarboard"></i>
            <h5>Próximamente</h5>
            <p class="mb-0">Estamos preparando los próximos cursos. ¡Vuelve pronto!</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($cursos as $c): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <?php if (!empty($c['imagen'])): ?>
                            <img src="<?= BASE_URL ?>/img/<?= e($c['imagen']) ?>"
                                 class="card-img-top" alt="<?= e($c['titulo']) ?>">
                        <?php else: ?>
                            <div class="card-img-placeholder">
                                <i class="bi <?= $iconoCurso[$c['tipo']] ?? 'bi-mortarboard' ?>"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="badge-nivel"><?= e($c['nivel']) ?></span>
                            <h5 class="card-title mt-2"><?= e($c['titulo']) ?></h5>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-person-badge"></i> <?= e($c['instructor']) ?>
                                <?php if ($c['duracion_min']): ?>
                                    · <i class="bi bi-clock"></i> <?= duracionTexto((int)$c['duracion_min']) ?>
                                <?php endif; ?>
                            </p>
                            <p class="card-text small text-muted">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?= fechaLarga($c['fecha_inicio']) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="precio"><?= precio((float) $c['precio']) ?></span>
                                <a href="<?= BASE_URL ?>/cursos/detalle.php?id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-cafe">
                                    Más info
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/cursos/listado.php" class="btn btn-cafe">
                Ver todos los cursos <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    <?php endif; ?>
</section>

<!-- CTA -->
<section class="container my-5">
    <div class="cta-banner">
        <h2 class="fuente-decorativa">¿Listo para empezar tu Fika?</h2>
        <p class="lead mb-4">
            <?php if (!estaLogueado()): ?>
                Crea tu cuenta gratis y empieza a reservar mesas, apuntarte a cursos y pedir sin esperas.
            <?php else: ?>
                Reserva tu mesa o apúntate al próximo curso. ¡Te esperamos!
            <?php endif; ?>
        </p>
        <?php if (!estaLogueado()): ?>
            <a href="<?= BASE_URL ?>/registro.php" class="btn btn-terracota btn-lg">
                <i class="bi bi-person-plus"></i> Crear cuenta gratis
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/mesas/reservar.php" class="btn btn-terracota btn-lg">
                <i class="bi bi-geo-alt"></i> Reservar mesa
            </a>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
