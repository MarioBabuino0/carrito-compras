<?php
session_start();
$conn = mysqli_connect('db','root','root_password','tienda');
if(!$conn) die('Error de conexión a la base de datos');

$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

// Inicializar carrito en sesión desde BD si hay usuario
if($id_usuario && !isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
    $rs = mysqli_query($conn,"SELECT c.id_producto, c.cantidad, p.nombre, p.precio, p.foto 
                              FROM carrito c JOIN productos p ON c.id_producto=p.id_producto
                              WHERE c.id_usuario=$id_usuario");
    while($r = mysqli_fetch_assoc($rs)){
        $_SESSION['carrito'][$r['id_producto']] = [
            'nombre'=>$r['nombre'],
            'precio'=>$r['precio'],
            'foto'=>$r['foto'],
            'cantidad'=>$r['cantidad']
        ];
    }
}

// Agregar al carrito
if (isset($_POST['id_producto'])) {
    $id = (int)$_POST['id_producto'];
    $cantidad = isset($_POST['cantidad']) ? max(1, (int)$_POST['cantidad']) : 1;
    $pagina_origen = isset($_POST['pagina_origen']) ? (int)$_POST['pagina_origen'] : 1;
    
    $consulta = mysqli_query($conn, "SELECT * FROM productos WHERE id_producto = $id");
    $producto = mysqli_fetch_assoc($consulta);
    if ($producto && $producto['cantidad'] >= $cantidad) {
        if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$id] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'foto'   => $producto['foto'],
                'cantidad' => $cantidad
            ];
        }
        // Actualizar tabla carrito si hay usuario
        if($id_usuario){
            $cantAct = $_SESSION['carrito'][$id]['cantidad'];
            mysqli_query($conn,"INSERT INTO carrito (id_usuario,id_producto,cantidad)
                                VALUES ($id_usuario,$id,$cantAct)
                                ON DUPLICATE KEY UPDATE cantidad=$cantAct");
        }
    }
    
    // REDIRIGIR a la misma página para evitar reenvío de formulario
    header("Location: catalogo.php?page=$pagina_origen#producto-$id");
    exit();
}

/* =========================
   PAGINACIÓN
   ========================= */
// productos por página
$por_pagina = 6;

// página actual (1 por defecto)
$pagina_actual = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

// cuántos productos hay en total
$res_total = mysqli_query($conn,"SELECT COUNT(*) AS total FROM productos");
$f_total = mysqli_fetch_assoc($res_total);
$total_productos = (int)$f_total['total'];

// cuántas páginas en total
$total_paginas = ceil($total_productos / $por_pagina);

// forzar mínimo 6 páginas para futuras altas
if ($total_paginas < 6) {
    $total_paginas = 6;
}

// limitar para que no se pase del rango
if($pagina_actual > $total_paginas) $pagina_actual = $total_paginas > 0 ? $total_paginas : 1;

// desde qué registro empezar
$offset = ($pagina_actual - 1) * $por_pagina;

// Cargar productos de esta página
$productos = mysqli_query($conn, "SELECT * FROM productos ORDER BY id_producto LIMIT $offset,$por_pagina");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2>Catálogo de Relojes</h2>
    <div class="row g-4">
        <?php while($row = mysqli_fetch_assoc($productos)): ?>
        <div class="col-md-4 d-flex" id="producto-<?= $row['id_producto'] ?>">
            <div class="card h-100 w-100 d-flex flex-column">
                <img src="ver_imagen.php?id=<?= $row['id_producto'] ?>" class="card-img-top" style="height: 200px; object-fit:cover;" alt="<?= htmlspecialchars($row['nombre']) ?>">
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($row['nombre']) ?></h5>
                            <span class="badge bg-secondary">
                                Stock: <?= (int)$row['cantidad'] ?>
                            </span>
                        </div>
                        <p class="card-text">Modelo: <strong><?= htmlspecialchars($row['modelo']) ?></strong></p>
                        <p class="card-text"><?= htmlspecialchars($row['descripcion']) ?></p>
                        <p class="card-text">Precio: $<?= number_format($row['precio'],2) ?></p>
                    </div>
                    <form method="post" class="mt-auto">
                        <input type="hidden" name="id_producto" value="<?= $row['id_producto'] ?>">
                        <input type="hidden" name="pagina_origen" value="<?= $pagina_actual ?>">
                        <input type="number" name="cantidad" min="1" max="<?= (int)$row['cantidad'] ?>" value="1" class="form-control mb-2" style="width:90px;">
                        <button class="btn btn-primary w-100" <?= $row['cantidad'] <= 0 ? 'disabled' : '' ?>>
                            Añadir al carrito
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Paginación -->
    <?php if($total_paginas > 1): ?>
    <nav aria-label="Paginación catálogo" class="mt-4">
      <ul class="pagination justify-content-center">

        <!-- Anterior -->
        <li class="page-item <?= $pagina_actual <= 1 ? 'disabled' : '' ?>">
          <a class="page-link" href="catalogo.php?page=<?= $pagina_actual-1 ?>">Anterior</a>
        </li>

        <?php for($i=1; $i<=$total_paginas; $i++): ?>
          <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
            <a class="page-link" href="catalogo.php?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <!-- Siguiente -->
        <li class="page-item <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?>">
          <a class="page-link" href="catalogo.php?page=<?= $pagina_actual+1 ?>">Siguiente</a>
        </li>

      </ul>
    </nav>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
