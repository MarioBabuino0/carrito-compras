<?php
session_start();
if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'){
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Tienda de Relojes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2>Panel de Administración</h2>
    <p>Desde aquí puedes gestionar los productos del catálogo.</p>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Agregar producto</h5>
                    <p class="card-text">Registrar un nuevo reloj en el catálogo.</p>
                    <a href="agregar_producto.php" class="btn btn-primary w-100">Ir a agregar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Editar producto</h5>
                    <p class="card-text">Modificar datos de productos existentes.</p>
                    <a href="editar_producto.php" class="btn btn-warning w-100">Ir a editar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Eliminar producto</h5>
                    <p class="card-text">Eliminar productos del catálogo.</p>
                    <a href="eliminar_producto.php" class="btn btn-danger w-100">Ir a eliminar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
