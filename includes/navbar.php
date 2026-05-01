<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">☕ Fika</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/nosotros.php">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/cursos/cursos.php">Cursos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/pedidos/carta.php">Carta</a></li>
                <?php if (estaLogueado()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/mesas/reservar.php">Reservar mesa</a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav">
                <?php if (estaLogueado()): ?>
                    <?php if (esAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="<?= BASE_URL ?>/admin/index.php">⚙ Admin</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <?= e($_SESSION['user_nombre']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/mesas/mis_reservas.php">Mis reservas</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/cursos/mis_cursos.php">Mis cursos</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/pedidos/mis_pedidos.php">Mis pedidos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/login.php">Iniciar sesión</a></li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary px-3" href="<?= BASE_URL ?>/registro.php">Registrarse</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
