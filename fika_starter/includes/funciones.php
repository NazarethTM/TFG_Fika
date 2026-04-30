<?php
/**
 * Funciones de utilidad
 * --------------------------------------------------
 * Pequeñas ayudas que usaremos los tres en muchos sitios.
 * Si alguien necesita algo nuevo y muy general, lo añade aquí.
 * Si es algo específico de su módulo, lo deja en su carpeta.
 */

/**
 * Escapa cadenas para imprimir en HTML (anti-XSS).
 * USAR SIEMPRE al imprimir cualquier cosa que venga del usuario o BBDD.
 *   <?= e($variable) ?>
 */
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/** Redirige a una ruta relativa (con BASE_URL antepuesta) y termina. */
function redirigir(string $ruta): void
{
    header('Location: ' . BASE_URL . $ruta);
    exit;
}

/**
 * Mensajes flash que se muestran una vez y desaparecen.
 *   setMensaje('success', 'Reserva creada');
 *   setMensaje('danger', 'Algo falló');
 * Tipos válidos: success, danger, warning, info (clases de Bootstrap).
 */
function setMensaje(string $tipo, string $texto): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'texto' => $texto];
}

function getMensaje(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $msg = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $msg;
}

/** Formatea un número como precio en euros: 3.5 -> "3,50 €" */
function precio(float $cantidad): string
{
    return number_format($cantidad, 2, ',', '.') . ' €';
}

/** Fecha corta: "2026-05-15" -> "15/05/2026" */
function fechaCorta(string $fecha): string
{
    return date('d/m/Y', strtotime($fecha));
}

/** Fecha larga en español: "15 de mayo de 2026" */
function fechaLarga(string $fecha): string
{
    $meses = ['enero','febrero','marzo','abril','mayo','junio',
              'julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $ts = strtotime($fecha);
    return date('j', $ts) . ' de ' . $meses[(int) date('n', $ts) - 1] . ' de ' . date('Y', $ts);
}

/** Hora corta: "18:30:00" -> "18:30" */
function hora(string $hora): string
{
    return substr($hora, 0, 5);
}
