<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: login.php");
    exit();
}
$conn = mysqli_connect('db','root','root_password','tienda');
if(!$conn) die('Error de conexión a la base de datos');

$id = $_SESSION['id_usuario'];
$consulta = mysqli_query($conn, "SELECT nombre, email FROM usuarios WHERE id_usuario=$id");
$usuario = mysqli_fetch_assoc($consulta);

// Historial simple: total de compras
$hist = mysqli_query($conn,"SELECT c.id_compra, c.fecha, SUM(d.cantidad*d.precio_unitario) total
                            FROM compras c
                            JOIN compras_detalle d ON c.id_compra=d.id_compra
                            WHERE c.id_usuario=$id
                            GROUP BY c.id_compra, c.fecha
                            ORDER BY c.fecha DESC");
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
<div class="container mt-4" style="max-width: 700px;">
    <h2>Perfil</h2>
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
            <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
        </div>
    </div>

    <h4>Historial de compras</h4>
    <?php if(mysqli_num_rows($hist)>0): ?>
        <table class="table table-sm">
            <thead>
                <tr><th>#</th><th>Fecha</th><th>Total</th></tr>
            </thead>
            <tbody>
            <?php while($c = mysqli_fetch_assoc($hist)): ?>
                <tr>
                    <td><?= $c['id_compra'] ?></td>
                    <td><?= $c['fecha'] ?></td>
                    <td>$<?= number_format($c['total'],2) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tienes compras registradas todavía.</p>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

