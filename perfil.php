<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: login.php");
    exit();
}
$conn = mysqli_connect('db','root','root_password','tienda');
if(!$conn) die('Error de conexión a la base de datos');

$id = $_SESSION['id_usuario'];

// Datos del usuario
$consulta = mysqli_query($conn, "SELECT nombre, email FROM usuarios WHERE id_usuario=$id");
$usuario = mysqli_fetch_assoc($consulta);

// Compras del usuario
$compras = mysqli_query($conn,"
    SELECT c.id_compra, c.fecha, 
           SUM(d.cantidad*d.precio_unitario) AS total
    FROM compras c
    JOIN compras_detalle d ON c.id_compra = d.id_compra
    WHERE c.id_usuario = $id
    GROUP BY c.id_compra, c.fecha
    ORDER BY c.fecha DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4" style="max-width: 900px;">
    <h2>Perfil</h2>
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
            <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
        </div>
    </div>

    <h4>Historial de compras</h4>

    <?php if(mysqli_num_rows($compras) == 0): ?>
        <p>No tienes compras registradas todavía.</p>
    <?php else: ?>
        <?php while($compra = mysqli_fetch_assoc($compras)): ?>
            <?php
            $id_compra = (int)$compra['id_compra'];
            $detalles = mysqli_query($conn,"
                SELECT p.nombre, p.modelo, d.cantidad, d.precio_unitario
                FROM compras_detalle d
                JOIN productos p ON d.id_producto = p.id_producto
                WHERE d.id_compra = $id_compra
            ");
            ?>
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <span><?= $compra['fecha'] ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-2">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Modelo</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($d = mysqli_fetch_assoc($detalles)): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['nombre']) ?></td>
                                <td><?= htmlspecialchars($d['modelo']) ?></td>
                                <td><?= (int)$d['cantidad'] ?></td>
                                <td>$<?= number_format($d['precio_unitario'],2) ?></td>
                                <td>$<?= number_format($d['cantidad']*$d['precio_unitario'],2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div class="text-end">
                        <strong>Total de la compra: $<?= number_format($compra['total'],2) ?></strong>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
