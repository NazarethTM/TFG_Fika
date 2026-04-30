# Panel de administración

**Responsables:** los tres (cada uno hace la parte de su módulo)

Esta carpeta es para el panel donde el administrador gestiona toda la cafetería.
Cada uno crea aquí los ficheros que correspondan a su módulo.

## Reparto

| Archivo | Quién | Qué hace |
|---|---|---|
| `index.php` | Naza | Dashboard con resumen (nº reservas hoy, pedidos pendientes, etc.) |
| `mesas.php` | Naza | CRUD de mesas (añadir, editar, dar de baja) |
| `reservas.php` | Naza | Lista todas las reservas, filtrar por fecha, cancelar |
| `cursos.php` | Laura | CRUD de cursos |
| `inscripciones.php` | Laura | Ver inscritos por curso |
| `productos.php` | Ken | CRUD de productos |
| `pedidos.php` | Ken | Ver pedidos, cambiar estado (pendiente → preparando → listo → entregado) |
| `chat.php` | Ken | Ver historial de conversaciones del chatbot |

## Plantilla base para cualquier página de admin

Lo primero que tiene que hacer cualquier página dentro de `/admin/`
es comprobar que el usuario es admin. Esa única línea ya redirige
si no lo es:

```php
<?php
require_once __DIR__ . '/../../includes/auth.php';
requerirAdmin();   // ← bloquea el acceso a no-admins

$pageTitle = 'Admin · Reservas';
require __DIR__ . '/../../includes/header.php';
?>

<h2>Gestión de reservas</h2>
<!-- contenido -->

<?php require __DIR__ . '/../../includes/footer.php'; ?>
```
