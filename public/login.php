<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/funciones.php';

if (estaLogueado()) {
    redirigir('/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Completa todos los campos.';
    } else {
        $r = login($email, $password);
        if ($r['ok']) {
            setMensaje('success', '¡Bienvenido de nuevo!');
            redirigir('/index.php');
        }
        $error = $r['msg'];
    }
}

$pageTitle = 'Iniciar sesión · Fika';
require __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="auth-card">
        <div class="auth-header">
            <i class="bi bi-cup-hot-fill"></i>
            <h2>Bienvenido</h2>
            <p class="mb-0 mt-2 small" style="opacity:.85;">Inicia sesión para continuar</p>
        </div>
        <div class="auth-body">

            <?php if ($error): ?>
                <div class="alert alert-danger small">
                    <i class="bi bi-exclamation-circle me-1"></i> <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" novalidate>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" required
                               value="<?= e($_POST['email'] ?? '') ?>"
                               placeholder="tu@email.com">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" required
                               placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn btn-cafe w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
                </button>
            </form>

            <hr class="my-4">

            <p class="text-center mb-0 small text-muted">
                ¿No tienes cuenta?
                <a href="<?= BASE_URL ?>/registro.php" class="fw-semibold text-decoration-none"
                   style="color: var(--terracota);">Regístrate aquí</a>
            </p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
