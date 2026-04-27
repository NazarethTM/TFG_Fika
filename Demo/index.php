<?php
require_once __DIR__ . '/auth.php';
$pagina = $pagina ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cafetería FIKA - Un espacio para hacer una pausa, disfrutar de buen café y repostería artesanal.">
    <title><?= e($titulo ?? 'Cafetería FIKA') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<header class="site-header">
    <nav class="nav">
        <a href="index.php" class="logo">F<span>I</span>KA</a>
        <button class="nav-toggle" aria-label="Abrir menú">&#9776;</button>
        <ul class="nav-links">
            <li><a href="index.php"    class="<?= $pagina==='inicio'   ? 'active':'' ?>">Inicio</a></li>
            <li><a href="menu.php"     class="<?= $pagina==='menu'     ? 'active':'' ?>">Carta</a></li>
            <li><a href="reservas.php" class="<?= $pagina==='reservas' ? 'active':'' ?>">Reservas</a></li>
            <li><a href="contacto.php" class="<?= $pagina==='contacto' ? 'active':'' ?>">Contacto</a></li>
            <?php if (usuario_logueado()): ?>
                <?php if (es_admin()): ?>
                    <li><a href="admin/index.php">Panel</a></li>
                <?php else: ?>
                    <li><a href="perfil.php">Hola, <?= e(explode(' ', $_SESSION['nombre'])[0]) ?></a></li>
                <?php endif; ?>
                <li><a href="logout.php" class="btn btn-peq btn-secundario">Salir</a></li>
            <?php else: ?>
                <li><a href="login.php" class="btn btn-peq btn-primario">Iniciar sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
