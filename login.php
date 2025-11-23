<?php
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conectar a la base de datos tienda con usuario root
    $conn = mysqli_connect('db', 'root', 'root_password', 'tienda');
    if(!$conn){
        die('Error de conexión a la base de datos');
    }

    $email = trim($_POST['email']);
    $pass = $_POST['contrasena'];

    $stmt = mysqli_prepare($conn, "SELECT id_usuario, nombre, contrasena FROM usuarios WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_usuario, $nombre, $hash);

    if(mysqli_stmt_fetch($stmt)){
        if(password_verify($pass, $hash)){
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['email'] = $email;
            header("Location: perfil.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Correo no registrado.";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Tienda de Relojes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark justify-content-center p-3">
  <ul class="navbar-nav">
    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
    <li class="nav-item"><a class="nav-link" href="registro.php">Registro</a></li>
    <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
    <li class="nav-item"><a class="nav-link" href="perfil.php">Perfil</a></li>
  </ul>
</nav>
<div class="container mt-4" style="max-width: 500px;">
    <h2>Iniciar Sesión</h2>
    <?php if($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="contrasena" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>
</body>
</html>
