<?php
session_start();
$conn = mysqli_connect('db','root','root_password','tienda');
if(!$conn) die('Error de conexión a la base de datos');

$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

// Inicializar carrito en sesión desde BD si hay usuario
if($id_usuario && !isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
    $rs = mysqli_query($conn,"SELECT c.id_producto, c.cantidad, p.nombre, p.precio, p.foto 
                              FROM carrito c JOIN productos p ON c.id_producto=p.id_producto
                              WHERE c.id_usuario=$id_usuario");
    while($r = mysqli_fetch_assoc($rs)){
        $_SESSION['carrito'][$r['id_producto']] = [
            'nombre'=>$r['nombre'],
            'precio'=>$r['precio'],
            'foto'=>$r['foto'],
            'cantidad'=>$r['cantidad']
        ];
    }
}

// Agregar al carrito
if (isset($_POST['id_producto'])) {
    $id = (int)$_POST['id_producto'];
    $cantidad = isset($_POST['cantidad']) ? max(1, (int)$_POST['cantidad']) : 1;
    $consulta = mysqli_query($conn, "SELECT * FROM productos WHERE id_producto = $id");
    $producto = mysqli_fetch_assoc($consulta);
    if ($producto && $producto['cantidad'] >= $cantidad) {
        if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$id] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'foto'   => $producto['foto'],
                'cantidad' => $cantidad
            ];
        }
        // Actualizar tabla carrito si hay usuario
        if($id_usuario){
            $cantAct = $_SESSION['carrito'][$id]['cantidad'];
            mysqli_query($conn,"INSERT INTO carrito (id_usuario,id_producto,cantidad)
                                VALUES ($id_usuario,$id,$cantAct)
                                ON DUPLICATE KEY UPDATE cantidad=$cantAct");
        }
    }
}

// Cargar productos del catálogo
$productos = mysqli_query($conn, "SELECT * FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2>Catálogo de Relojes</h2>
    <div class="row g-4">
        <?php while($row = mysqli_fetch_assoc($productos)): ?>
        <div class="col-md-4 d-flex">
            <div class="card h-100 w-100 d-flex flex-column">
                <img src="ver_imagen.php?id=<?= $row['id_producto'] ?>" class="card-img-top" style="height: 200px; object-fit:cover;" alt="<?= htmlspecialchars($row['nombre']) ?>">
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($row['nombre']) ?></h5>
                            <!-- Badge de stock -->
                            <span class="badge bg-secondary">
                                Stock: <?= (int)$row['cantidad'] ?>
                            </span>
                        </div>
                        <p class="card-text">Modelo: <strong><?= htmlspecialchars($row['modelo']) ?></strong></p>
                        <p class="card-text"><?= htmlspecialchars($row['descripcion']) ?></p>
                        <p class="card-text">Precio: $<?= number_format($row['precio'],2) ?></p>
                    </div>
                    <form method="post" class="mt-auto">
                        <input type="hidden" name="id_producto" value="<?= $row['id_producto'] ?>">
                        <input type="number" name="cantidad" min="1" max="<?= (int)$row['cantidad'] ?>" value="1" class="form-control mb-2" style="width:90px;">
                        <button class="btn btn-primary w-100" <?= $row['cantidad'] <= 0 ? 'disabled' : '' ?>>
                            Añadir al carrito
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
mysqli_close($conn);