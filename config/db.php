<?php
/**
 * Conexión a la base de datos
 * --------------------------------------------------
 * Devuelve siempre la MISMA instancia de PDO (patrón singleton simple).
 * Llamad a getDB() cada vez que necesitéis hacer una consulta.
 *
 * Ejemplo de uso:
 *   require_once __DIR__ . '/../config/db.php';
 *   $pdo = getDB();
 *   $stmt = $pdo->prepare("SELECT * FROM mesas WHERE activa = 1");
 *   $stmt->execute();
 *   $mesas = $stmt->fetchAll();
 *
 * IMPORTANTE: usad SIEMPRE prepare() + execute() con parámetros,
 * NUNCA concatenéis variables en la consulta. Si veo un INSERT con
 * "...VALUES ('$nombre')" os lo tiraré abajo en el code review.
 */

require_once __DIR__ . '/config.php';

function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST
             . ';dbname=' . DB_NAME
             . ';charset=' . DB_CHARSET;

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            if (DEBUG) {
                die('Error de conexión: ' . $e->getMessage());
            }
            die('Error de conexión a la base de datos.');
        }
    }

    return $pdo;
}
