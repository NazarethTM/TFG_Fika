<?php
$titulo = 'Mi perfil · FIKA';
$pagina = 'perfil';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requiere_login();

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

include 'includes/header.php';
?>

<section>
    <div class="container">
        <h2 class="titulo-seccion">Mi perfil</h2>
        <div class="form-card fade-in">
            <p><strong>Nombre:</strong> <?= e($usuario['nombre']) ?></p>
            <p><strong>Email:</strong>  <?= e($usuario['email']) ?></p>
            <p><strong>Teléfono:</strong> <?= e($usuario['telefono'] ?: '—') ?></p>
            <p><strong>Miembro desde:</strong> <?= e(date('d/m/Y', strtotime($usuario['fecha_registro']))) ?></p>

            <div class="mt-2" style="display:flex; gap:0.5rem;">
                <a href="reservas.php" class="btn btn-primario">Mis reservas</a>
                <a href="logout.php"   class="btn btn-secundario">Cerrar sesión</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
