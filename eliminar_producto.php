<?php
session_start();
if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'){
    header("Location: index.php");
    exit();
}
$conn = mysqli_connect('db','root','root_password','tienda');
if(!$conn) die('Error de conexión a la base de datos');

$mensaje = '';
// Si se envió formulario de eliminación
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])){
    $id_eliminar = (int)$_POST['id_producto'];

    // Opcional: borrar relaciones (carrito, compras_detalle) si procede
    mysqli_query($conn, "DELETE FROM carrito WHERE id_producto=$id_eliminar");
    mysqli_query($conn, "DELETE FROM compras_detalle WHERE id_producto=$id_eliminar");

    // Borrar producto
    if(mysqli_query($conn, "DELETE FROM productos WHERE id_producto=$id_eliminar")){
        $mensaje = "Producto eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar el producto.";
    }
}

// Obtener lista de productos
$lista = mysqli_query($conn,"SELECT id_producto, nombre, modelo FROM productos ORDER BY nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4" style="max-width: 700px;">
    <h2>Eliminar Producto del Catálogo</h2>

    <?php if($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="post" onsubmit="return confirm('¿Seguro que deseas eliminar este producto? Esta acción no se puede deshacer.');">
        <div class="mb-3">
            <label class="form-label">Selecciona un producto para eliminar</label>
            <select name="id_producto" class="form-select" required>
                <option value="">-- Elige un producto --</option>
                <?php while($p = mysqli_fetch_assoc($lista)): ?>
                    <option value="<?= $p['id_producto'] ?>">
                        <?= htmlspecialchars($p['nombre']) ?> (<?= htmlspecialchars($p['modelo']) ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-danger w-100">Eliminar producto</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
