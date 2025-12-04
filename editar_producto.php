<?php
session_start();
if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'){
    header("Location: index.php");
    exit();
}
$conn = mysqli_connect('db','root','root_password','tienda');
if(!$conn) die('Error de conexión a la base de datos');

$mensaje = '';
$productoActual = null;

// 1) Si se seleccionó un producto para editar (GET o POST id_producto)
if (isset($_GET['id_producto'])) {
    $id_sel = (int)$_GET['id_producto'];
} elseif (isset($_POST['id_producto'])) {
    $id_sel = (int)$_POST['id_producto'];
} else {
    $id_sel = 0;
}

// 2) Si se envió el formulario para guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $id_sel      = (int)$_POST['id_producto'];
    $nombre      = trim($_POST['nombre']);
    $modelo      = trim($_POST['modelo']);
    $descripcion = trim($_POST['descripcion']);
    $precio      = floatval($_POST['precio']);
    $cantidad    = intval($_POST['cantidad']);

    // Si hay nueva imagen
    $hayImagenNueva = (isset($_FILES['foto']) && $_FILES['foto']['tmp_name'] !== '');

    if($hayImagenNueva){
        $foto_data = file_get_contents($_FILES['foto']['tmp_name']);
        $stmt = mysqli_prepare($conn,"UPDATE productos 
                                      SET nombre=?, modelo=?, descripcion=?, foto=?, precio=?, cantidad=? 
                                      WHERE id_producto=?");
        mysqli_stmt_bind_param($stmt,"ssssdii",
                               $nombre,$modelo,$descripcion,$foto_data,$precio,$cantidad,$id_sel);
    } else {
        $stmt = mysqli_prepare($conn,"UPDATE productos 
                                      SET nombre=?, modelo=?, descripcion=?, precio=?, cantidad=? 
                                      WHERE id_producto=?");
        mysqli_stmt_bind_param($stmt,"sssdii",
                               $nombre,$modelo,$descripcion,$precio,$cantidad,$id_sel);
    }

    if(mysqli_stmt_execute($stmt)){
        $mensaje = "Producto actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar el producto.";
    }
    mysqli_stmt_close($stmt);
}

// 3) Cargar datos del producto seleccionado (si hay id_sel)
if ($id_sel > 0) {
    $res = mysqli_query($conn,"SELECT id_producto, nombre, modelo, descripcion, precio, cantidad 
                               FROM productos WHERE id_producto=$id_sel");
    $productoActual = mysqli_fetch_assoc($res);
}

// 4) Obtener lista de productos para el select
$lista = mysqli_query($conn,"SELECT id_producto, nombre, modelo FROM productos ORDER BY nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4" style="max-width: 800px;">
    <h2>Editar Producto del Catálogo</h2>

    <!-- Mostrar mensajes -->
    <?php if($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <!-- Selector de producto -->
    <form method="get" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label">Selecciona un producto para editar</label>
                <select name="id_producto" class="form-select" required>
                    <option value="">-- Elige un producto --</option>
                    <?php while($p = mysqli_fetch_assoc($lista)): ?>
                        <option value="<?= $p['id_producto'] ?>" <?= ($p['id_producto']==$id_sel)?'selected':'' ?>>
                            <?= htmlspecialchars($p['nombre']) ?> (<?= htmlspecialchars($p['modelo']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Cargar datos</button>
            </div>
        </div>
    </form>

    <?php if($productoActual): ?>
    <!-- Formulario de edición -->
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_producto" value="<?= $productoActual['id_producto'] ?>">

        <div class="mb-3">
            <label class="form-label">Nombre público</label>
            <input type="text" name="nombre" class="form-control" required
                   value="<?= htmlspecialchars($productoActual['nombre']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Modelo</label>
            <input type="text" name="modelo" class="form-control" required maxlength="50"
                   value="<?= htmlspecialchars($productoActual['modelo']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($productoActual['descripcion']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" required
                   value="<?= htmlspecialchars($productoActual['precio']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Cantidad en stock</label>
            <input type="number" name="cantidad" class="form-control" required
                   value="<?= htmlspecialchars($productoActual['cantidad']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen (foto)</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="form-text text-muted">
                Deja vacío este campo si no deseas cambiar la imagen actual.
            </small>
        </div>

        <button type="submit" name="guardar" class="btn btn-success w-100">Guardar cambios</button>
    </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
