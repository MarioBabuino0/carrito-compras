<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark px-4 py-3">
  <a class="navbar-brand text-white fs-4 fw-bold" href="<?= (isset($_SESSION['rol']) && $_SESSION['rol']==='admin') ? 'index_admin.php' : 'index.php' ?>">
    ChronoShop
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="mainNavbar">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <?php if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'): ?>
        <!-- Menú para clientes -->
        <li class="nav-item">
          <a class="nav-link fs-5" href="catalogo.php">Catálogo</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fs-5" href="carrito.php">Carrito</a>
        </li>
      <?php else: ?>
        <!-- Menú para admin -->
        <li class="nav-item">
          <a class="nav-link fs-5" href="index_admin.php">Panel admin</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fs-5" href="agregar_producto.php">Agregar producto</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fs-5" href="editar_producto.php">Editar producto</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fs-5" href="eliminar_producto.php">Eliminar producto</a>
        </li>
      <?php endif; ?>

      <?php if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link fs-5" href="registro.php">Registro</a>
        </li>
      <?php endif; ?>
    </ul>

    <ul class="navbar-nav ms-auto">
      <?php if(isset($_SESSION['id_usuario'])): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fs-6" href="perfil.php" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= htmlspecialchars($_SESSION['email']) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <?php if($_SESSION['rol'] === 'admin'): ?>
              <li><a class="dropdown-item fs-6" href="index_admin.php">Panel admin</a></li>
            <?php else: ?>
              <li><a class="dropdown-item fs-6" href="perfil.php">Perfil</a></li>
              <li><a class="dropdown-item fs-6" href="carrito.php">Mi carrito</a></li>
            <?php endif; ?>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item fs-6" href="logout.php">Cerrar sesión</a></li>
          </ul>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link fs-5" href="login.php">Iniciar sesión</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

