<?php
/**
 * Funciones de autenticación y utilidades generales
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Comprueba si hay un usuario con sesión iniciada. */
function usuario_logueado(): bool {
    return isset($_SESSION['usuario_id']);
}

/** Comprueba si el usuario actual es administrador. */
function es_admin(): bool {
    return usuario_logueado() && ($_SESSION['rol'] ?? '') === 'admin';
}

/** Obliga a iniciar sesión para acceder a una página. */
function requiere_login(): void {
    if (!usuario_logueado()) {
        header('Location: login.php');
        exit;
    }
}

/** Obliga a ser administrador para acceder a una página. */
function requiere_admin(): void {
    if (!es_admin()) {
        header('Location: ../login.php');
        exit;
    }
}

/** Escapa texto para impedir XSS al mostrarlo en HTML. */
function e(?string $texto): string {
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/** Devuelve un mensaje flash almacenado y lo elimina. */
function flash_get(string $clave): ?string {
    if (isset($_SESSION['flash'][$clave])) {
        $mensaje = $_SESSION['flash'][$clave];
        unset($_SESSION['flash'][$clave]);
        return $mensaje;
    }
    return null;
}

/** Guarda un mensaje flash temporal. */
function flash_set(string $clave, string $mensaje): void {
    $_SESSION['flash'][$clave] = $mensaje;
}
