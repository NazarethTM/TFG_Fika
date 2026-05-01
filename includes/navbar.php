<nav class="navbar navbar-expand-lg navbar-cafe sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">
            <i class="bi bi-cup-hot-fill me-1"></i> Fika
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
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
                        <a class="nav-link" href="<?= BASE_URL ?>/mesas/reservar.php">
                            <i class="bi bi-geo-alt"></i> Reservar mesa
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav align-items-lg-center">
                <?php if (estaLogueado()): ?>
                    <?php if (esAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="<?= BASE_URL ?>/admin/index.php">
                                <i class="bi bi-gear-fill"></i> Admin
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-person-circle"></i> <?= e($_SESSION['user_nombre']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/mesas/mis_reservas.php"><i class="bi bi-calendar-check me-2"></i>Mis reservas</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/cursos/mis_cursos.php"><i class="bi bi-mortarboard me-2"></i>Mis cursos</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/pedidos/mis_pedidos.php"><i class="bi bi-bag-check me-2"></i>Mis pedidos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/login.php">Iniciar sesión</a></li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-terracota px-3" href="<?= BASE_URL ?>/registro.php">
                            <i class="bi bi-person-plus"></i> Registrarse
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
