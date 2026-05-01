<?php
/**
 * Configuración general del proyecto Fika
 * --------------------------------------------------
 * Este fichero se incluye desde todas partes a través de db.php.
 * Si vais a tocar algo aquí, avisadlo en el grupo antes.
 */

// ---------- URL BASE ----------
// Cambiad esta línea según donde tengáis el proyecto en XAMPP.
// Si lo ponéis en C:\xampp\htdocs\TFG_FIKA, queda así:
define('BASE_URL', 'http://localhost/FIKA/TFG_Fika/public');

// ---------- BASE DE DATOS ----------
define('DB_HOST', 'localhost');
define('DB_NAME', 'fika_tfg');
define('DB_USER', 'root');
define('DB_PASS', '');                  // XAMPP por defecto no tiene contraseña
define('DB_CHARSET', 'utf8mb4');

// ---------- MODO DESARROLLO ----------
// true mientras programamos, false al desplegar
define('DEBUG', true);

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// ---------- SESIONES ----------
define('SESSION_NAME', 'fika_session');

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}
