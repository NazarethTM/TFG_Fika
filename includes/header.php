<?php
/**
 * Cabecera HTML que se incluye al principio de cada página.
 * Antes de require, podéis definir:
 *   $pageTitle = 'Mi página · Fika';
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/funciones.php';

$pageTitle = $pageTitle ?? 'Fika · Cafetería de estudio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>

    <!-- Tipografías -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos Fika -->
    <link href="<?= BASE_URL ?>/css/style.css" rel="stylesheet">
</head>
<body>
<?php require __DIR__ . '/navbar.php'; ?>

<main>
<?php
$msg = getMensaje();
if ($msg):
?>
    <div class="container mt-3">
        <div class="alert alert-<?= e($msg['tipo']) ?> alert-dismissible fade show">
            <?= e($msg['texto']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>
