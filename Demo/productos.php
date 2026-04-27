<?php
/**
 * Conexión a la base de datos de la cafetería FIKA
 * -------------------------------------------------
 * Usamos PDO porque permite:
 *   - Consultas preparadas (evita inyección SQL)
 *   - Manejo de excepciones
 *   - Portabilidad entre distintos motores
 */

$DB_HOST = 'localhost';
$DB_NAME = 'fika_db';
$DB_USER = 'root';
$DB_PASS = '';         // en XAMPP por defecto está vacío

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Error de conexión: ' . htmlspecialchars($e->getMessage()));
}
