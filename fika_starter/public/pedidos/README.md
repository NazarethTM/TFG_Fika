# Módulo: Pedidos anticipados

**Responsable:** Ken
**Rama de Git:** `feature/pedidos` (o la que ya tengas creada)

## Ficheros que tienes que crear aquí

| Archivo | Para qué sirve |
|---|---|
| `carta.php` | Catálogo público de productos con botón "añadir al carrito". |
| `carrito.php` | Vista del carrito. Cantidades editables. Botón "confirmar pedido". |
| `procesar_carrito.php` | Recibe acciones añadir/quitar/limpiar. Guarda el carrito en `$_SESSION['carrito']`. |
| `confirmar.php` | Crea el pedido en BBDD a partir del carrito y vacía la sesión. |
| `mis_pedidos.php` | Lista los pedidos del usuario con su estado actual. |

## Estructura del carrito en sesión

```php
$_SESSION['carrito'] = [
    // producto_id => cantidad
    1 => 2,
    5 => 1,
];
```

## Confirmación del pedido (transacción)

Como hay que insertar en `pedidos` Y en `detalles_pedido` a la vez,
usa una transacción para que o se inserten todos o ninguno:

```php
$pdo->beginTransaction();
try {
    $pdo->prepare("INSERT INTO pedidos (usuario_id, total, metodo_pago) VALUES (?, ?, 'simulado')")
        ->execute([$userId, $total]);
    $pedidoId = $pdo->lastInsertId();

    $stmt = $pdo->prepare(
        "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario, subtotal)
         VALUES (?, ?, ?, ?, ?)"
    );
    foreach ($carrito as $prodId => $cant) {
        $precio = $precios[$prodId];
        $stmt->execute([$pedidoId, $prodId, $cant, $precio, $precio * $cant]);
    }

    $pdo->commit();
    unset($_SESSION['carrito']);
} catch (Exception $e) {
    $pdo->rollBack();
    throw $e;
}
```
