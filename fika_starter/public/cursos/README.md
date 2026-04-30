# Módulo: Cursos e inscripciones

**Responsable:** Laura
**Rama de Git:** `feature/cursos`

## Ficheros que tienes que crear aquí

| Archivo | Para qué sirve |
|---|---|
| `listado.php` | Página pública con todos los cursos activos. Tarjetas Bootstrap con título, fecha, precio, plazas. |
| `detalle.php?id=X` | Página de un curso concreto. Muestra info completa y botón de inscripción. |
| `inscribirse.php` | Recibe POST con `curso_id`, valida cupo, crea inscripción. |
| `mis_cursos.php` | Lista los cursos a los que está inscrito el usuario logueado. |
| `cancelar.php` | Cancela una inscripción del usuario. |

## Validación de cupo

Antes de inscribir, comprobar que quedan plazas:

```php
$stmt = $pdo->prepare(
    "SELECT c.cupo_maximo,
            (SELECT COUNT(*) FROM inscripciones
              WHERE curso_id = c.id AND estado != 'cancelada') AS inscritos
     FROM cursos c WHERE c.id = ?"
);
$stmt->execute([$cursoId]);
$info = $stmt->fetch();

if ($info['inscritos'] >= $info['cupo_maximo']) {
    setMensaje('warning', 'Este curso está completo.');
    redirigir('/cursos/listado.php');
}
```

La columna `UNIQUE (usuario_id, curso_id)` ya impide la doble inscripción.
Si intentas insertar dos veces, captura la excepción de PDO:

```php
try {
    $stmt = $pdo->prepare("INSERT INTO inscripciones (usuario_id, curso_id) VALUES (?, ?)");
    $stmt->execute([$userId, $cursoId]);
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {  // duplicate key
        setMensaje('warning', 'Ya estás inscrito en este curso.');
    } else {
        throw $e;
    }
}
```
