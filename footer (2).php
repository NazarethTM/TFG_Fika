<?php
$titulo = 'Contacto · FIKA';
$pagina = 'contacto';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$error = $exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre']  ?? '');
    $email   = trim($_POST['email']   ?? '');
    $asunto  = trim($_POST['asunto']  ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre === '' || $mensaje === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Revisa los campos obligatorios (nombre, email válido y mensaje).';
    } else {
        $stmt = $pdo->prepare("INSERT INTO contactos (nombre,email,asunto,mensaje)
                               VALUES (?,?,?,?)");
        $stmt->execute([$nombre, $email, $asunto, $mensaje]);
        $exito = '¡Gracias por escribirnos! Te contestaremos lo antes posible.';
    }
}

include 'includes/header.php';
?>

<section>
    <div class="container">
        <h2 class="titulo-seccion">Contacto</h2>

        <div class="contacto-grid">
            <div class="contacto-info">
                <h3>Visítanos</h3>
                <p>Calle del Café, 12 · 02001 Albacete</p>
                <h3>Escríbenos</h3>
                <p>info@fika.com · +34 600 123 456</p>
                <h3>Horario</h3>
                <p>Lun – Vie · 8:00 – 20:00</p>
                <p>Sábados · 9:00 – 21:00</p>
                <p>Domingos · 10:00 – 14:00</p>
            </div>

            <div class="form-card" style="margin:0;">
                <?php if ($error): ?><div class="alerta alerta-error"><?= e($error) ?></div><?php endif; ?>
                <?php if ($exito): ?><div class="alerta alerta-exito"><?= e($exito) ?></div><?php endif; ?>

                <form method="post">
                    <div class="form-grupo">
                        <label>Nombre</label>
                        <input type="text" name="nombre" required>
                    </div>
                    <div class="form-grupo">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-grupo">
                        <label>Asunto</label>
                        <input type="text" name="asunto">
                    </div>
                    <div class="form-grupo">
                        <label>Mensaje</label>
                        <textarea name="mensaje" required></textarea>
                    </div>
                    <button class="btn btn-primario" style="width:100%;">Enviar mensaje</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
