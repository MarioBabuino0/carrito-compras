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
  </ul>
</nav>

<?php include("navbar.php"); ?>
<div class="container mt-4">
    <h2>Registro de Usuario</h2>
    <form>
        <!-- Formulario de registro: nombre, email, contraseña, etc. -->
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre">
        </div>
        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="contrasena">
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
</div>
</body>
</html>
