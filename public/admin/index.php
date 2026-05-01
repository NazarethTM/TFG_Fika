<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/funciones.php';

requerirAdmin();

$pdo = getDB();

// Estadísticas rápidas para el dashboard
$stats = [
    'usuarios'     => $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'")->fetchColumn(),
    'reservas_hoy' => $pdo->query("SELECT COUNT(*) FROM reservas WHERE fecha = CURDATE() AND estado = 'activa'")->fetchColumn(),
    'pedidos_pend' => $pdo->query("SELECT COUNT(*) FROM pedidos WHERE estado IN ('pendiente','preparando')")->fetchColumn(),
    'cursos'       => $pdo->query("SELECT COUNT(*) FROM cursos WHERE activo = 1")->fetchColumn(),
];

$pageTitle = 'Panel admin · Fika';
require __DIR__ . '/../../includes/header.php';
?>

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1"><i class="bi bi-gear-fill me-2" style="color: var(--terracota);"></i>Panel de administración</h1>
            <p class="text-muted mb-0">Bienvenido, <strong><?= e($_SESSION['user_nombre']) ?></strong></p>
        </div>
        <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver a la web
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="row g-3 mb-5">
        <div class="col-md-3 col-6">
            <div class="card text-center p-3">
                <div style="color: var(--cafe-medio); font-size: 2rem;"><i class="bi bi-people-fill"></i></div>
                <div class="display-6 fw-bold" style="color: var(--cafe-oscuro);"><?= (int)$stats['usuarios'] ?></div>
                <small class="text-muted">Usuarios registrados</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center p-3">
                <div style="color: var(--terracota); font-size: 2rem;"><i class="bi bi-calendar-check-fill"></i></div>
                <div class="display-6 fw-bold" style="color: var(--cafe-oscuro);"><?= (int)$stats['reservas_hoy'] ?></div>
                <small class="text-muted">Reservas hoy</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center p-3">
                <div style="color: var(--verde-hoja); font-size: 2rem;"><i class="bi bi-bag-fill"></i></div>
                <div class="display-6 fw-bold" style="color: var(--cafe-oscuro);"><?= (int)$stats['pedidos_pend'] ?></div>
                <small class="text-muted">Pedidos pendientes</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center p-3">
                <div style="color: var(--cafe-claro); font-size: 2rem;"><i class="bi bi-mortarboard-fill"></i></div>
                <div class="display-6 fw-bold" style="color: var(--cafe-oscuro);"><?= (int)$stats['cursos'] ?></div>
                <small class="text-muted">Cursos activos</small>
            </div>
        </div>
    </div>

    <!-- Módulos -->
    <h4 class="mb-3">Gestión</h4>
    <div class="row g-3">

        <!-- Módulo de Naza -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-geo-alt-fill" style="color: var(--cafe-medio); font-size: 2rem;"></i></div>
                    <h5>Mesas y reservas</h5>
                    <p class="text-muted small">Gestiona el mapa de mesas y las reservas de clientes.</p>
                    <a href="<?= BASE_URL ?>/admin/mesas.php" class="btn btn-sm btn-cafe">
                        <i class="bi bi-arrow-right"></i> Acceder
                    </a>
                </div>
            </div>
        </div>

        <!-- Módulo de Laura -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-mortarboard-fill" style="color: var(--terracota); font-size: 2rem;"></i></div>
                    <h5>Cursos e inscripciones</h5>
                    <p class="text-muted small">Crea cursos, gestiona inscripciones y cupos.</p>
                    <a href="<?= BASE_URL ?>/admin/cursos.php" class="btn btn-sm btn-cafe">
                        <i class="bi bi-arrow-right"></i> Acceder
                    </a>
                </div>
            </div>
        </div>

        <!-- Módulo de Ken (productos + pedidos + chat) -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-cup-hot-fill" style="color: var(--verde-hoja); font-size: 2rem;"></i></div>
                    <h5>Productos y pedidos</h5>
                    <p class="text-muted small">Gestiona la carta y el estado de los pedidos.</p>
                    <a href="<?= BASE_URL ?>/admin/productos.php" class="btn btn-sm btn-cafe">
                        <i class="bi bi-arrow-right"></i> Acceder
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-chat-dots-fill" style="color: var(--cafe-claro); font-size: 2rem;"></i></div>
                    <h5>Historial chatbot</h5>
                    <p class="text-muted small">Conversaciones recientes del asistente IA.</p>
                    <a href="<?= BASE_URL ?>/admin/chat.php" class="btn btn-sm btn-cafe">
                        <i class="bi bi-arrow-right"></i> Acceder
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-2"><i class="bi bi-people-fill" style="color: var(--cafe-oscuro); font-size: 2rem;"></i></div>
                    <h5>Usuarios</h5>
                    <p class="text-muted small">Listado de clientes registrados.</p>
                    <a href="<?= BASE_URL ?>/admin/usuarios.php" class="btn btn-sm btn-cafe">
                        <i class="bi bi-arrow-right"></i> Acceder
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="alert alert-warning mt-4 small">
        <i class="bi bi-info-circle me-1"></i>
        <strong>Nota:</strong> los enlaces a los módulos están preparados pero las páginas concretas
        las construye cada miembro del equipo en su rama. Si pulsas alguno y da 404, es normal por ahora.
    </div>

</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
