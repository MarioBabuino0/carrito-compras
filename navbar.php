<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark px-4">
  <a class="navbar-brand text-white" href="index.php">Tienda Relojes</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="mainNavbar">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
      <li class="nav-item"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
      <li class="nav-item"><a class="nav-link" href="registro.php">Registro</a></li>
      <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
    </ul>

    <ul class="navbar-nav ms-auto">
      <?php if(isset($_SESSION['id_usuario'])): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="perfil.php" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= htmlspecialchars($_SESSION['email']) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="perfil.php">Perfil</a></li>
            <li><a class="dropdown-item" href="carrito.php">Mi carrito</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
          </ul>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Iniciar sesión</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
