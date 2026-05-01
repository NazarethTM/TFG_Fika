<?php

/**
 * Listado público de cursos
 * --------------------------------------------------
 * Versión integrada por Naza a partir del prototipo de Laura.
 * Se respeta su estructura visual (hero + tarjetas con plazas y precio)
 * y se adapta a la BBDD real del proyecto.
 *
 * Cambios respecto al prototipo:
 *   - getDB() en lugar de $pdo suelto
 *   - duracion_min en lugar de duracion (con helper duracionTexto)
 *   - $_SESSION['user_id'] en lugar de usuario_id
 *   - rutas correctas (login.php, inscribirse.php)
 *   - sin Stripe: redirige a inscribirse.php (que crea Laura)
 *   - icono temático cuando no hay imagen propia
 */

require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/funciones.php';

$pdo = getDB();

// Mensajes de error que pueden venir desde inscribirse.php
$mensaje = '';
$tipo    = 'success';
$errores = [
    'no_existe'   => 'El curso seleccionado ya no está disponible.',
    'completo'    => 'Lo sentimos, ese curso está completo.',
    'ya_inscrito' => 'Ya estabas inscrito en ese curso.',
];
if (isset($_GET['error']) && isset($errores[$_GET['error']])) {
    $mensaje = $errores[$_GET['error']];
    $tipo    = 'warning';
}
if (isset($_GET['ok']) && $_GET['ok'] === '1') {
    $mensaje = '¡Inscripción registrada con éxito! Te esperamos en clase.';
    $tipo    = 'success';
}

// Lista de cursos activos con número de inscritos
$cursos = $pdo->query(
    "SELECT c.*,
            (SELECT COUNT(*) FROM inscripciones i
             WHERE i.curso_id = c.id AND i.estado <> 'cancelada') AS ocupados
     FROM cursos c
     WHERE c.activo = 1
     ORDER BY c.fecha_inicio ASC"
)->fetchAll();

// Si está logueado, ¿en qué cursos ya está inscrito (no cancelado)?
$misInscripciones = [];
if (estaLogueado()) {
    $stmt = $pdo->prepare(
        "SELECT curso_id FROM inscripciones
         WHERE usuario_id = ? AND estado <> 'cancelada'"
    );
    $stmt->execute([$_SESSION['user_id']]);
    $misInscripciones = array_column($stmt->fetchAll(), 'curso_id');
}

// Iconos por tipo de curso (cuando no hay imagen propia)
$iconoCurso = [
    'reposteria' => 'bi-cake2-fill',
    'barista'    => 'bi-cup-hot-fill',
    'cata'       => 'bi-droplet-half',
    'otro'       => 'bi-mortarboard-fill',
];

$pageTitle = 'Cursos · Fika';
require __DIR__ . '/../../includes/header.php';
?>

<!-- Hero pequeño -->
<header class="hero" style="padding: 4rem 1rem;">
    <div class="container">
        <span class="hero-tagline animar">Formación práctica</span>
        <h1 class="fuente-decorativa animar delay-1">Nuestros Cursos</h1>
        <p class="lead animar delay-2">
            Conviértete en barista o experto pastelero con clases impartidas por profesionales.
        </p>
    </div>
</header>

<section class="container my-5">

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= e($tipo) ?> alert-dismissible fade show">
            <i class="bi bi-info-circle me-1"></i> <?= e($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($cursos)): ?>
        <div class="empty-state">
            <i class="bi bi-mortarboard"></i>
            <h5>Próximamente</h5>
            <p class="mb-0">Aún no hay cursos programados. Vuelve pronto.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($cursos as $c): ?>
                <?php
                $plazasLibres = max(0, (int)$c['cupo_maximo'] - (int)$c['ocupados']);
                $yaInscrito   = in_array((int)$c['id'], $misInscripciones, true);
                $tipoCurso    = $c['tipo'] ?? 'otro';
                ?>
                <div class="col-md-6 col-lg-4" id="curso-<?= (int)$c['id'] ?>">
                    <div class="card">

                        <?php if (!empty($c['imagen'])): ?>
                            <img src="<?= BASE_URL ?>/img/<?= e($c['imagen']) ?>"
                                class="card-img-top" alt="<?= e($c['titulo']) ?>">
                        <?php else: ?>
                            <div class="card-img-placeholder">
                                <i class="bi <?= $iconoCurso[$tipoCurso] ?? 'bi-mortarboard-fill' ?>"></i>
                            </div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <div class="d-flex gap-2 mb-2">
                                <span class="badge-categoria"><?= e($tipoCurso) ?></span>
                                <span class="badge-nivel"><?= e($c['nivel']) ?></span>
                            </div>

                            <h5 class="card-title"><?= e($c['titulo']) ?></h5>

                            <p class="text-muted small mb-2">
                                <i class="bi bi-person-badge"></i> <?= e($c['instructor']) ?>
                            </p>

                            <p class="card-text small">
                                <?= e($c['descripcion']) ?>
                            </p>

                            <ul class="list-unstyled small mb-3">
                                <li>
                                    <i class="bi bi-calendar-event text-muted me-1"></i>
                                    <strong><?= fechaLarga($c['fecha_inicio']) ?></strong>
                                </li>
                                <?php if (!empty($c['duracion_min'])): ?>
                                    <li>
                                        <i class="bi bi-clock text-muted me-1"></i>
                                        <?= duracionTexto((int)$c['duracion_min']) ?>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <i class="bi bi-people text-muted me-1"></i>
                                    Plazas: <strong><?= $plazasLibres ?></strong> de <?= (int)$c['cupo_maximo'] ?>
                                </li>
                            </ul>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="precio"><?= precio((float)$c['precio']) ?></span>

                                <?php if ($yaInscrito): ?>
                                    <button class="btn btn-success btn-sm" disabled>
                                        <i class="bi bi-check-circle"></i> Inscrito
                                    </button>
                                <?php elseif ($plazasLibres === 0): ?>
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="bi bi-x-circle"></i> Completo
                                    </button>
                                <?php elseif (!estaLogueado()): ?>
                                    <a href="<?= BASE_URL ?>/login.php" class="btn btn-cafe btn-sm">
                                        <i class="bi bi-box-arrow-in-right"></i> Inscribirme
                                    </a>
                                <?php else: ?>
                                    <form method="POST" action="<?= BASE_URL ?>/cursos/inscribirse.php" class="d-inline">
                                        <input type="hidden" name="curso_id" value="<?= (int)$c['id'] ?>">
                                        <button class="btn btn-cafe btn-sm">
                                            <i class="bi bi-pencil-square"></i> Inscribirme
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>

<?php require __DIR__ . '/../../includes/footer.php'; ?>