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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/css/style.css" rel="stylesheet">
</head>
<body>
<?php require __DIR__ . '/navbar.php'; ?>
<main class="container py-4">
<?php
$msg = getMensaje();
if ($msg):
?>
    <div class="alert alert-<?= e($msg['tipo']) ?>"><?= e($msg['texto']) ?></div>
<?php endif; ?>
