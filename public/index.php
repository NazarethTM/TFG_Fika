<?php
$pageTitle = 'Fika · Cafetería de estudio';
require __DIR__ . '/../includes/header.php';
?>

<div class="row align-items-center mb-5">
    <div class="col-md-6">
        <h1 class="display-4 fw-bold">Bienvenido a Fika</h1>
        <p class="lead">Una cafetería pensada para estudiar, trabajar y disfrutar.</p>
        <p>Reserva tu mesa, apúntate a cursos de repostería o barista y haz tu pedido sin esperas.</p>
        <?php if (!estaLogueado()): ?>
            <a href="<?= BASE_URL ?>/registro.php" class="btn btn-primary btn-lg">Crear cuenta</a>
            <a href="<?= BASE_URL ?>/nosotros.php" class="btn btn-outline-secondary btn-lg ms-2">Saber más</a>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <img src="<?= BASE_URL ?>/img/fika_plano.png" alt="Plano de Fika"
             class="img-fluid rounded shadow"
             onerror="this.style.display='none'">
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">📍 Reserva de mesas</h5>
                <p class="card-text">Elige tu mesa favorita en el mapa interactivo y reserva la franja horaria que necesites.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">🧁 Cursos</h5>
                <p class="card-text">Aprende repostería y técnicas de barista con nuestro equipo profesional.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">☕ Pedidos anticipados</h5>
                <p class="card-text">Pide café o repostería desde la web y recógelo sin esperar cola.</p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
