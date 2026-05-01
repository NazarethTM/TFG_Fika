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

<div class="container">
    <div class="auth-card" style="max-width: 540px;">
        <div class="auth-header">
            <i class="bi bi-person-plus-fill"></i>
            <h2>Crea tu cuenta</h2>
            <p class="mb-0 mt-2 small" style="opacity:.85;">Solo te llevará un minuto</p>
        </div>
        <div class="auth-body">

            <?php if ($error): ?>
                <div class="alert alert-danger small">
                    <i class="bi bi-exclamation-circle me-1"></i> <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required
                               value="<?= e($_POST['nombre'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Apellidos *</label>
                        <input type="text" name="apellidos" class="form-control" required
                               value="<?= e($_POST['apellidos'] ?? '') ?>">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label small fw-semibold">Email *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" required
                               value="<?= e($_POST['email'] ?? '') ?>"
                               placeholder="tu@email.com">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label small fw-semibold">Teléfono <span class="text-muted fw-normal">(opcional)</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-telephone"></i></span>
                        <input type="tel" name="telefono" class="form-control"
                               value="<?= e($_POST['telefono'] ?? '') ?>"
                               placeholder="+34 600 000 000">
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Contraseña *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" minlength="6" required
                                   placeholder="Mínimo 6 caracteres">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Repetir *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" name="password2" class="form-control" minlength="6" required
                                   placeholder="Repite la contraseña">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-terracota w-100 mt-4">
                    <i class="bi bi-person-plus me-1"></i> Crear mi cuenta
                </button>
            </form>

            <hr class="my-4">

            <p class="text-center mb-0 small text-muted">
                ¿Ya tienes cuenta?
                <a href="<?= BASE_URL ?>/login.php" class="fw-semibold text-decoration-none"
                   style="color: var(--terracota);">Inicia sesión</a>
            </p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
