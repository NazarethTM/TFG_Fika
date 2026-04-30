<?php
/**
 * Autenticación y sesiones
 * --------------------------------------------------
 * Mantenido por: Laura (módulo común, pero si hay que tocar algo
 * gordo lo discutimos los tres).
 *
 * Funciones expuestas:
 *   registrarUsuario(...)   crea cuenta nueva
 *   login($email, $pass)    inicia sesión
 *   logout()                cierra sesión
 *   estaLogueado()          true/false
 *   esAdmin()               true/false
 *   requerirLogin()         redirige si no hay sesión
 *   requerirAdmin()         redirige si no es admin
 *   usuarioActual()         array con datos del usuario en sesión
 */

require_once __DIR__ . '/../config/db.php';

function registrarUsuario(string $nombre, string $apellidos, string $email,
                          ?string $telefono, string $password): array
{
    $pdo = getDB();

    // ¿Email ya registrado?
    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['ok' => false, 'msg' => 'Este email ya está registrado'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        'INSERT INTO usuarios (nombre, apellidos, email, telefono, password)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$nombre, $apellidos, $email, $telefono, $hash]);

    return ['ok' => true, 'id' => (int) $pdo->lastInsertId()];
}

function login(string $email, string $password): array
{
    $pdo = getDB();

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ? AND activo = 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        return ['ok' => false, 'msg' => 'Email o contraseña incorrectos'];
    }

    $_SESSION['user_id']     = (int) $user['id'];
    $_SESSION['user_nombre'] = $user['nombre'];
    $_SESSION['user_email']  = $user['email'];
    $_SESSION['user_rol']    = $user['rol'];

    return ['ok' => true];
}

function logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain']);
    }
    session_destroy();
}

function estaLogueado(): bool
{
    return isset($_SESSION['user_id']);
}

function esAdmin(): bool
{
    return estaLogueado() && ($_SESSION['user_rol'] ?? '') === 'admin';
}

function requerirLogin(): void
{
    if (!estaLogueado()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

function requerirAdmin(): void
{
    requerirLogin();
    if (!esAdmin()) {
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

function usuarioActual(): ?array
{
    if (!estaLogueado()) {
        return null;
    }
    return [
        'id'     => $_SESSION['user_id'],
        'nombre' => $_SESSION['user_nombre'],
        'email'  => $_SESSION['user_email'],
        'rol'    => $_SESSION['user_rol'],
    ];
}
