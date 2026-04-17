<?php
$titulo = 'Usuarios · FIKA';
$activa = 'usuarios';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$usuarios = $pdo->query("
    SELECT u.*,
           (SELECT COUNT(*) FROM reservas WHERE usuario_id = u.id AND estado <> 'cancelada') AS reservas
    FROM usuarios u
    ORDER BY u.fecha_registro DESC
")->fetchAll();

include 'header.php';
?>

<h1>Usuarios registrados</h1>

<div class="tabla-wrap mt-1">
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Email</th>
                <th>Teléfono</th><th>Rol</th><th>Reservas</th><th>Registro</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= e($u['nombre']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e($u['telefono'] ?: '—') ?></td>
                <td><span class="estado estado-<?= $u['rol']==='admin'?'confirmada':'pendiente' ?>">
                    <?= e($u['rol']) ?></span></td>
                <td><?= $u['reservas'] ?></td>
                <td><?= e(date('d/m/Y', strtotime($u['fecha_registro']))) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
