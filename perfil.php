<?php
session_start();
// Redirige a login.php si no hay sesión iniciada
if(!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Conecta a la base de datos
$conn = mysqli_connect('db', 'root', 'root_password', 'tienda');
if(!$conn){
    die('Error de conexión a la base de datos');
}

$id = $_SESSION['id_usuario'];
$consulta = mysqli_query($conn, "SELECT nombre, email FROM usuarios WHERE id_usuario = $id");
$usuario = mysqli_fetch_assoc($consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark justify-content-center p-3">
  <ul class="navbar-nav">
    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
    <li class="nav-item"><a class="nav-link" href="registro.php">Registro</a></li>
    <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
    <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
  </ul>
</nav>
<div class="container mt-4" style="max-width: 500px;">
    <h2>Perfil</h2>
    <div class="card p-3">
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
        <!-- Aquí puedes mostrar historial de compras y más información -->
        <a href="logout.php" class="btn btn-secondary">Cerrar sesión</a>
    </div>
</div>
</body>
</html>
