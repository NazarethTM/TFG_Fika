<?php
require_once __DIR__ . '/../includes/auth.php';
requiere_admin();
$activa = $activa ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($titulo ?? 'Panel · FIKA') ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <a href="index.php" class="logo">F<span>I</span>KA</a>
        <ul>
            <li><a href="index.php"      class="<?= $activa==='dashboard'?'activo':'' ?>">Inicio</a></li>
            <li><a href="productos.php"  class="<?= $activa==='productos'?'activo':'' ?>">Productos</a></li>
            <li><a href="reservas.php"   class="<?= $activa==='reservas' ?'activo':'' ?>">Reservas</a></li>
            <li><a href="usuarios.php"   class="<?= $activa==='usuarios' ?'activo':'' ?>">Usuarios</a></li>
            <li><a href="mensajes.php"   class="<?= $activa==='mensajes' ?'activo':'' ?>">Mensajes</a></li>
            <li style="margin-top:1.5rem;"><a href="../index.php">← Volver a la web</a></li>
            <li><a href="../logout.php">Cerrar sesión</a></li>
        </ul>
    </aside>
    <main class="admin-main">
