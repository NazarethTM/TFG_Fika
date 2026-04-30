# Módulo: Reservas de mesa

**Responsable:** Nazareth
**Rama de Git:** `feature/mesas`

## Ficheros que tienes que crear aquí

| Archivo | Para qué sirve |
|---|---|
| `reservar.php` | Página con el mapa interactivo del local. Usuario clica una mesa, abre modal, elige fecha y horario. |
| `procesar_reserva.php` | Recibe el POST del formulario, valida solapamientos y guarda en BBDD. Hace `redirigir()` al terminar. |
| `mis_reservas.php` | Lista las reservas del usuario logueado. Permite cancelar. |
| `cancelar.php` | Recibe id por GET o POST y pone la reserva en estado `cancelada`. |
| `api_disponibilidad.php` | Endpoint que devuelve JSON con las reservas de una fecha. Lo llama el JS del mapa para pintar las mesas ocupadas. |
| `mapa.js` | Lógica del mapa interactivo: posicionar mesas, gestionar clicks, llamar a la API. |

## Esqueleto sugerido para `reservar.php`

```php
<?php
require_once __DIR__ . '/../../includes/auth.php';
requerirLogin();

$pageTitle = 'Reservar mesa · Fika';
require __DIR__ . '/../../includes/header.php';

$pdo = getDB();
$mesas = $pdo->query("SELECT * FROM mesas WHERE activa = 1")->fetchAll();
?>

<h2>Reserva tu mesa</h2>
<!-- aquí el mapa interactivo -->

<?php require __DIR__ . '/../../includes/footer.php'; ?>
```

## Consulta clave: validar solapamientos

Antes de hacer el INSERT en `reservas`, comprueba si hay otra reserva activa
en la misma mesa, fecha y horario:

```php
$stmt = $pdo->prepare(
    "SELECT id FROM reservas
     WHERE mesa_id = ? AND fecha = ? AND estado = 'activa'
       AND hora_inicio < ? AND hora_fin > ?"
);
$stmt->execute([$mesaId, $fecha, $horaFin, $horaInicio]);
if ($stmt->fetch()) {
    setMensaje('danger', 'Esa mesa ya está reservada en ese horario.');
    redirigir('/mesas/reservar.php');
}
```
