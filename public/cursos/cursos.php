<?php
$titulo = 'Cursos';
require_once __DIR__ . '/../../includes/header.php';

$pdo = getDB();
$mensaje = ''; $tipo='success';

$errores = [
    'no_existe' => 'El curso seleccionado ya no está disponible.',
    'completo'  => 'Lo sentimos, ese curso está completo.',
];
if (isset($_GET['error']) && isset($errores[$_GET['error']])) {
    $mensaje = $errores[$_GET['error']];
    $tipo = 'warning';
}


$cursos = $pdo->query("
    SELECT c.*,
           (SELECT COUNT(*) FROM inscripciones i WHERE i.curso_id = c.id AND i.estado <> 'cancelada') AS ocupados
    FROM cursos c
    WHERE c.activo = 1
    ORDER BY c.fecha_inicio ASC
")->fetchAll();

$misCursosPagados = [];
if (estaLogueado()) {
    $stmt = $pdo->prepare("SELECT curso_id FROM inscripciones WHERE usuario_id=:u AND pagado=1");
    $stmt->execute([':u'=>$_SESSION['user_id']]);
    $misCursosPagados = array_column($stmt->fetchAll(), 'curso_id');
}
?>

<header class="hero" style="padding: 4rem 1rem;">
    <div class="container">
        <h1 class="fuente-decorativa">Nuestros Cursos</h1>
        <p class="lead">Conviértete en barista profesional con formación práctica</p>
    </div>
</header>

<section class="container my-5">

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= e($tipo) ?> alert-dismissible fade show">
            <?= e($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php foreach ($cursos as $c): ?>
            <?php
                $plazasLibres = max(0, $c['cupo_maximo'] - $c['ocupados']);
                $yaPagado     = in_array((int)$c['id'], $misCursosPagados);
            ?>
            <div class="col-md-6 col-lg-4" id="curso-<?= $c['id'] ?>">
                <div class="card h-100">
                    <img src="https://source.unsplash.com/600x400/?coffee,barista,<?= urlencode($c['titulo']) ?>"
                         class="card-img-top" alt="<?= e($c['titulo']) ?>">
                    <div class="card-body d-flex flex-column">
                        <span class="badge-nivel"><?= e($c['nivel']) ?></span>
                        <h5 class="card-title mt-2"><?= e($c['titulo']) ?></h5>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-person-badge"></i> <?= e($c['instructor']) ?>
                        </p>
                        <p class="card-text"><?= e($c['descripcion']) ?></p>

                        <ul class="list-unstyled small mb-3">
                            <li><i class="bi bi-calendar-event text-muted"></i>
                                Inicio: <strong><?= date('d/m/Y', strtotime($c['fecha_inicio'])) ?></strong></li>
                            <?php if ($c['fecha_fin']): ?>
                                <li><i class="bi bi-calendar-check text-muted"></i>
                                    Fin: <?= date('d/m/Y', strtotime($c['fecha_fin'])) ?></li>
                            <?php endif; ?>
                            <li><i class="bi bi-clock text-muted"></i> <?= e($c['duracion']) ?></li>
                            <li><i class="bi bi-people text-muted"></i>
                                Plazas: <strong><?= $plazasLibres ?></strong> de <?= $c['cupo_maximo'] ?></li>
                        </ul>

                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="precio"><?= number_format($c['precio'], 2) ?> €</span>

                            <?php if ($yaPagado): ?>
                                <button class="btn btn-success btn-sm" disabled>
                                    <i class="bi bi-check-circle"></i> Pagado
                                </button>
                            <?php elseif ($plazasLibres === 0): ?>
                                <button class="btn btn-secondary btn-sm" disabled>Completo</button>
                            <?php elseif (!estaLogueado()): ?>
                                <a href="auth/login.php" class="btn btn-cafe btn-sm">
                                    <i class="bi bi-box-arrow-in-right"></i> Inscribirme
                                </a>
                            <?php else: ?>
                                <form method="POST" action="pagos/checkout_curso.php" class="d-inline">
                                    <input type="hidden" name="curso_id" value="<?= $c['id'] ?>">
                                    <button class="btn btn-cafe btn-sm">
                                        <i class="bi bi-credit-card"></i> Inscribirme y pagar
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($cursos)): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-mortarboard display-1 text-muted"></i>
                <p class="text-muted mt-3">No hay cursos programados de momento.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if (estaLogueado()): ?>
        <div class="alert alert-cafe mt-4 small">
            <i class="bi bi-shield-lock"></i>
            Pago seguro mediante <strong>Stripe</strong>.
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
