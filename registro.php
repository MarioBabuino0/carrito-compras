<?php
session_start();
$conn = mysqli_connect('db','root','root_password','tienda');
if(!$conn) die('Error de conexión a la base de datos');

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email  = trim($_POST['email']);
    $pass   = $_POST['contrasena'];

    if($nombre === '' || $email === '' || $pass === ''){
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        // Verificar si ya existe el correo
        $stmt = mysqli_prepare($conn, "SELECT id_usuario FROM usuarios WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) > 0){
            $mensaje = "Ya existe una cuenta con ese correo.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($conn, "INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $hash);
            if(mysqli_stmt_execute($stmt)){
                $mensaje = "Usuario registrado correctamente. Ahora puedes iniciar sesión.";
            } else {
                $mensaje = "Error al registrar usuario.";
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4" style="max-width: 600px;">
    <h2>Registro de Usuario</h2>

    <?php if($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required maxlength="100">
        </div>
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required maxlength="100">
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="contrasena" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
    </form>

    <p class="mt-3">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
