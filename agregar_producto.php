<?php
session_start();
if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'){
    header("Location: index.php");
    exit();
}
$conn = mysqli_connect('db', 'root', 'root_password', 'tienda');
if(!$conn) die('Error de conexión a la base de datos');

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $modelo = trim($_POST['modelo']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $cantidad = intval($_POST['cantidad']);

    // Procesar imagen
    if (isset($_FILES['foto']) && $_FILES['foto']['tmp_name'] !== '') {
        $foto_data = file_get_contents($_FILES['foto']['tmp_name']);
        
        $stmt = mysqli_prepare($conn, "INSERT INTO productos (nombre, modelo, descripcion, foto, precio, cantidad) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssdi", $nombre, $modelo, $descripcion, $foto_data, $precio, $cantidad);
        
        if(mysqli_stmt_execute($stmt)){
            $mensaje = "Producto agregado correctamente al catálogo.";
        } else {
            $mensaje = "Error al agregar el producto.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $mensaje = "Debes subir una imagen del producto.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4" style="max-width: 700px;">
    <h2>Agregar Producto al Catálogo</h2>

    <?php if($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nombre público</label>
            <input type="text" name="nombre" class="form-control" required maxlength="200">
        </div>

        <div class="mb-3">
            <label class="form-label">Modelo</label>
            <input type="text" name="modelo" class="form-control" required maxlength="50">
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen (foto)</label>
            <input type="file" name="foto" class="form-control" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Cantidad en stock</label>
            <input type="number" name="cantidad" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Agregar producto</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
