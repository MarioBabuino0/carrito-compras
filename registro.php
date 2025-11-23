<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
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


<?php
$conn = mysqli_connect('db', 'root', 'root_password', 'tienda');
if(!$conn){
    die('Error de conexión a la base de datos');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $pass = $_POST['contrasena'];

    // Verificar si ya existe el email
    $existe = mysqli_query($conn, "SELECT id_usuario FROM usuarios WHERE email = '$email'");
    if(mysqli_num_rows($existe) > 0){
        echo "<p style='color:red;'>Ya existe una cuenta con ese email.</p>";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO usuarios (nombre,email,contrasena) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $hash);
        if(mysqli_stmt_execute($stmt)){
            echo "<p style='color:green;'>Usuario registrado correctamente. <a href='login.php'>Iniciar sesión</a></p>";
        } else {
            echo "<p style='color:red;'>Error al registrar usuario.</p>";
        }
    }
}
?>

<h2>Crear cuenta</h2>
<form method="post">
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Contraseña: <input type="password" name="contrasena" required></label><br>
    <button type="submit">Registrarse</button>
</form>

</body>
</html>
