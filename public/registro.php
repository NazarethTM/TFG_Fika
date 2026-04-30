<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/funciones.php';

if (estaLogueado()) {
    redirigir('/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre']    ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email     = trim($_POST['email']     ?? '');
    $telefono  = trim($_POST['telefono']  ?? '');
    $password  = $_POST['password']       ?? '';
    $password2 = $_POST['password2']      ?? '';

    if ($nombre === '' || $apellidos === '' || $email === '' || $password === '') {
        $error = 'Completa los campos obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $password2) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $r = registrarUsuario($nombre, $apellidos, $email, $telefono ?: null, $password);
        if ($r['ok']) {
            login($email, $password);
            setMensaje('success', '¡Bienvenido a Fika, ' . e($nombre) . '!');
            redirigir('/index.php');
        }
        $error = $r['msg'];
    }
}

$pageTitle = 'Crear cuenta · Fika';
require __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4">Crear cuenta</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" novalidate>
            <div class="row g-2">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" required
                           value="<?= e($_POST['nombre'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Apellidos *</label>
                    <input type="text" name="apellidos" class="form-control" required
                           value="<?= e($_POST['apellidos'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" required
                       value="<?= e($_POST['email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="tel" name="telefono" class="form-control"
                       value="<?= e($_POST['telefono'] ?? '') ?>">
            </div>

            <div class="row g-2">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Contraseña *</label>
                    <input type="password" name="password" class="form-control" minlength="6" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Repetir contraseña *</label>
                    <input type="password" name="password2" class="form-control" minlength="6" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Crear cuenta</button>
        </form>

        <p class="mt-3 text-center">
            ¿Ya tienes cuenta? <a href="<?= BASE_URL ?>/login.php">Inicia sesión</a>
        </p>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
