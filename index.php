<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ChronoShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Ajuste para que el carrusel no sea excesivamente alto en pantallas gigantes */
        .carousel-item img {
            height: 500px;
            object-fit: cover;
            filter: brightness(0.9); /* mas oscuro */
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<!-- Carrusel de Imágenes -->
<div id="carouselRelojes" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselRelojes" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselRelojes" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselRelojes" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/carrusel/carrusel1.jpg" class="d-block w-100" alt="Reloj elegante">
    </div>
    <div class="carousel-item">
      <img src="images/carrusel/carrusel2.jpg" class="d-block w-100" alt="Colección deportiva">
    </div>
    <div class="carousel-item">
      <img src="images/carrusel/carrusel3.jpg" class="d-block w-100" alt="Nuevos modelos">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselRelojes" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselRelojes" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Siguiente</span>
  </button>
</div>

<!-- Sección de Bienvenida (Hero) -->
<div class="container text-center mt-3 mb-3">
    <h1 class="display-3 fw-bold">ChronoShop</h1>
    <p class="lead text-muted mt-3" style="font-size: 1.5rem;">
        Precisión, estilo y elegancia en tu muñeca.
    </p>
    <div class="mt-4">
        <a href="catalogo.php" class="btn btn-dark btn-lg px-5 py-3 fs-5">Ver Catálogo Completo</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

