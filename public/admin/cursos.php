<?php
$pageTitle = 'Gestión de cursos';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/funciones.php';
requerirAdmin();

$pdo = getDB();
$accion  = $_GET['accion'] ?? 'listar';
$id      = (int)($_GET['id'] ?? 0);
$mensaje = '';
$tipo    = 'success';

$DIR_IMG = __DIR__ . '/../assets/img/cursos/';
$URL_IMG = BASE_URL . '/assets/img/cursos/';
if (!is_dir($DIR_IMG)) mkdir($DIR_IMG, 0755, true);


function subirImagen(string $dir): ?string
{
    if (empty($_FILES['imagen']['name'])) return null;
    $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
        throw new Exception('Formato no permitido. Usa JPG, PNG, WEBP o GIF.');
    }
    $nombre = uniqid('curso_') . ".$ext";
    move_uploaded_file($_FILES['imagen']['tmp_name'], "$dir$nombre");
    return $nombre;
}


function borrarImagen(string $dir, ?string $nombre): void
{
    if ($nombre && file_exists("$dir$nombre")) unlink("$dir$nombre");
}

// --- ELIMINAR ---
if ($accion === 'eliminar' && $id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT imagen FROM cursos WHERE id=:id");
        $stmt->execute([':id' => $id]);
        borrarImagen($DIR_IMG, $stmt->fetchColumn());
        $pdo->prepare("DELETE FROM cursos WHERE id=:id")->execute([':id' => $id]);
        header('Location: cursos.php?msg=eliminado'); exit;
    } catch (PDOException) {
        $mensaje = 'No se puede eliminar: tiene inscripciones asociadas.';
        $tipo    = 'danger';
        $accion  = 'listar';
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPost = (int)($_POST['id'] ?? 0);
    $datos  = [
        'titulo'      => trim($_POST['titulo']       ?? ''),
        'instructor'  => trim($_POST['instructor']   ?? ''),
        'descripcion' => trim($_POST['descripcion']  ?? ''),
        'duracion'    => trim($_POST['duracion']      ?? ''),
        'nivel'       => $_POST['nivel']              ?? 'Principiante',
        'precio'      => (float)($_POST['precio']     ?? 0),
        'cupo_maximo' => (int)($_POST['cupo_maximo']  ?? 10),
        'fecha_inicio'=> $_POST['fecha_inicio']       ?? null,
        'fecha_fin'   => $_POST['fecha_fin']           ?: null,
        'activo'      => isset($_POST['activo']) ? 1 : 0,
    ];

    if (!$datos['titulo'] || !$datos['instructor'] || !$datos['fecha_inicio']) {
        $mensaje = 'Título, instructor y fecha de inicio son obligatorios.';
        $tipo    = 'danger';
        $accion  = $idPost > 0 ? 'editar' : 'nuevo';
        $id      = $idPost;
    } else {
        try {
            $nuevaImg = subirImagen($DIR_IMG);

            if ($idPost > 0) {
                $sqlImg = '';
                if ($nuevaImg) {
                    $s = $pdo->prepare("SELECT imagen FROM cursos WHERE id=:id");
                    $s->execute([':id' => $idPost]);
                    borrarImagen($DIR_IMG, $s->fetchColumn());
                    $datos['imagen'] = $nuevaImg;
                    $sqlImg = ', imagen=:imagen';
                }
                $pdo->prepare("
                    UPDATE cursos SET titulo=:titulo, descripcion=:descripcion,
                        instructor=:instructor, precio=:precio, duracion=:duracion,
                        nivel=:nivel, fecha_inicio=:fecha_inicio, fecha_fin=:fecha_fin,
                        cupo_maximo=:cupo_maximo, activo=:activo $sqlImg
                    WHERE id=:id
                ")->execute([...$datos, ':id' => $idPost]);
                header('Location: cursos.php?msg=actualizado');
            } else {
                $datos['imagen'] = $nuevaImg;
                $pdo->prepare("
                    INSERT INTO cursos
                        (titulo, descripcion, instructor, precio, duracion, nivel,
                         fecha_inicio, fecha_fin, cupo_maximo, activo, imagen)
                    VALUES
                        (:titulo, :descripcion, :instructor, :precio, :duracion, :nivel,
                         :fecha_inicio, :fecha_fin, :cupo_maximo, :activo, :imagen)
                ")->execute($datos);
                header('Location: cursos.php?msg=creado');
            }
            exit;
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $tipo    = 'danger';
            $accion  = $idPost > 0 ? 'editar' : 'nuevo';
            $id      = $idPost;
        }
    }
}


$flash = [
    'creado'      => ['Curso creado correctamente.',      'success'],
    'actualizado' => ['Curso actualizado correctamente.', 'success'],
    'eliminado'   => ['Curso eliminado.',                 'success'],
];
if (isset($_GET['msg'], $flash[$_GET['msg']])) [$mensaje, $tipo] = $flash[$_GET['msg']];

// --- CARGAR DATOS ---
$curso  = null;
$cursos = [];

if (in_array($accion, ['editar', 'nuevo']) && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM cursos WHERE id=:id");
    $stmt->execute([':id' => $id]);
    $curso = $stmt->fetch();
}
if ($accion === 'listar') {
    $cursos = $pdo->query("
        SELECT c.*, (SELECT COUNT(*) FROM inscripciones i WHERE i.curso_id = c.id) AS inscritos
        FROM cursos c ORDER BY c.fecha_inicio DESC
    ")->fetchAll();
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container my-4">
        <main>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?= e($tipo) ?> alert-dismissible fade show">
                    <?= e($mensaje) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($accion === 'listar'): ?>
                <!-- LISTADO -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h1 class="h3 mb-0"><i class="bi bi-mortarboard"></i> Cursos</h1>
                        <p class="text-muted small mb-0">Programa formativo de la cafetería</p>
                    </div>
                    <a href="cursos.php?accion=nuevo" class="btn btn-cafe">
                        <i class="bi bi-plus-circle"></i> Nuevo curso
                    </a>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <input type="text" class="form-control" placeholder="🔍 Buscar curso…"
                               data-buscar-tabla="#tablaCursos">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle" id="tablaCursos">
                        <thead>
                            <tr>
                                <th>#</th><th>Imagen</th><th>Título</th><th>Instructor</th>
                                <th>Nivel</th><th>Inicio</th><th>Inscritos</th>
                                <th>Precio</th><th>Estado</th><th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($cursos)): ?>
                                <tr><td colspan="10" class="text-center text-muted py-4">No hay cursos registrados.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($cursos as $c): ?>
                                <tr>
                                    <td><?= $c['id'] ?></td>
                                    <td>
                                        <?php if ($c['imagen']): ?>
                                            <img src="<?= e($URL_IMG . $c['imagen']) ?>" alt=""
                                                 style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= e($c['titulo']) ?></div>
                                        <small class="text-muted"><?= e($c['duracion']) ?></small>
                                    </td>
                                    <td><?= e($c['instructor']) ?></td>
                                    <td><span class="badge-nivel"><?= e($c['nivel']) ?></span></td>
                                    <td><?= date('d/m/Y', strtotime($c['fecha_inicio'])) ?></td>
                                    <td><?= $c['inscritos'] ?> / <?= $c['cupo_maximo'] ?></td>
                                    <td class="precio"><?= number_format($c['precio'], 2) ?> €</td>
                                    <td>
                                        <?= $c['activo']
                                            ? '<span class="badge bg-success">Activo</span>'
                                            : '<span class="badge bg-secondary">Inactivo</span>' ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="cursos.php?accion=editar&id=<?= $c['id'] ?>"
                                           class="btn btn-sm btn-outline-cafe"><i class="bi bi-pencil"></i></a>
                                        <a href="cursos.php?accion=eliminar&id=<?= $c['id'] ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           data-confirmar="¿Eliminar «<?= e($c['titulo']) ?>»? Se borrarán también sus inscripciones.">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>
                <!-- FORMULARIO NUEVO / EDITAR -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-<?= $curso ? 'pencil-square' : 'plus-circle' ?>"></i>
                        <?= $curso ? 'Editar' : 'Nuevo' ?> curso
                    </h1>
                    <a href="cursos.php" class="btn btn-outline-cafe">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <input type="hidden" name="id" value="<?= e((string)($curso['id'] ?? '')) ?>">

                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Título *</label>
                                    <input type="text" name="titulo" class="form-control" required
                                           value="<?= e($curso['titulo'] ?? '') ?>">
                                    <div class="invalid-feedback">Indica el título.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nivel</label>
                                    <select name="nivel" class="form-select">
                                        <?php foreach (['Principiante', 'Intermedio', 'Avanzado'] as $n): ?>
                                            <option <?= ($curso['nivel'] ?? '') === $n ? 'selected' : '' ?>><?= $n ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3"><?= e($curso['descripcion'] ?? '') ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Instructor *</label>
                                    <input type="text" name="instructor" class="form-control" required
                                           value="<?= e($curso['instructor'] ?? '') ?>">
                                    <div class="invalid-feedback">Indica el instructor.</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Duración</label>
                                    <input type="text" name="duracion" class="form-control"
                                           placeholder="ej: 4 semanas" value="<?= e($curso['duracion'] ?? '') ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Precio (€)</label>
                                    <input type="number" step="0.01" min="0" name="precio" class="form-control"
                                           value="<?= e((string)($curso['precio'] ?? '0.00')) ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Fecha inicio *</label>
                                    <input type="date" name="fecha_inicio" class="form-control" required
                                           value="<?= e($curso['fecha_inicio'] ?? '') ?>">
                                    <div class="invalid-feedback">Indica la fecha de inicio.</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Fecha fin</label>
                                    <input type="date" name="fecha_fin" class="form-control"
                                           value="<?= e($curso['fecha_fin'] ?? '') ?>">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Cupo máx.</label>
                                    <input type="number" min="1" name="cupo_maximo" class="form-control"
                                           value="<?= e((string)($curso['cupo_maximo'] ?? '10')) ?>">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="activo" name="activo"
                                               <?= ($curso['activo'] ?? 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="activo">Activo</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Imagen del curso</label>
                                <img id="preview-imagen"
                                     src="<?= !empty($curso['imagen']) ? e($URL_IMG . $curso['imagen']) : '#' ?>"
                                     alt="" style="height:120px;object-fit:cover;border-radius:8px;
                                     <?= empty($curso['imagen']) ? 'display:none;' : '' ?> display:block;margin-bottom:.5rem">
                                <input type="file" name="imagen" class="form-control" accept="image/*"
                                       onchange="this.files[0] && (preview.src=URL.createObjectURL(this.files[0]),preview.style.display='block')">
                                <div class="form-text">Formatos: JPG, PNG, WEBP, GIF.</div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="cursos.php" class="btn btn-outline-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-cafe">
                                    <i class="bi bi-check-lg"></i> Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
