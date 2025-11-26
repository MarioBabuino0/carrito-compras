<?php
session_start();
$conn = mysqli_connect('db','root','root_password','tienda');
if(!$conn) die('Error de conexión a la base de datos');

$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

// Inicializar desde BD si hay usuario y carrito vacío
if($id_usuario && empty($_SESSION['carrito'])){
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

// Quitar producto
if(isset($_GET['del'])){
    $id = (int)$_GET['del'];
    unset($_SESSION['carrito'][$id]);
    if($id_usuario){
        mysqli_query($conn,"DELETE FROM carrito WHERE id_usuario=$id_usuario AND id_producto=$id");
    }
    header("Location: carrito.php"); exit();
}

// Modificar cantidades (Actualizar carrito)
if(isset($_POST['update']) && isset($_POST['cantidades'])){
    foreach($_POST['cantidades'] as $id => $cant){
        $id = (int)$id;
        $cant = max(1,(int)$cant);
        if(isset($_SESSION['carrito'][$id])){
            $_SESSION['carrito'][$id]['cantidad'] = $cant;
            if($id_usuario){
                mysqli_query($conn,"UPDATE carrito SET cantidad=$cant WHERE id_usuario=$id_usuario AND id_producto=$id");
            }
        }
    }
}

// Completar compra
if(isset($_POST['comprar']) && !empty($_SESSION['carrito'])){
    $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
    if($id_usuario){
        mysqli_query($conn, "INSERT INTO compras (id_usuario, fecha) VALUES ($id_usuario, NOW())");
        $id_compra = mysqli_insert_id($conn);

        foreach($_SESSION['carrito'] as $id=>$prod){
            $idp = (int)$id;
            $cantidad = (int)$prod['cantidad'];
            $precio = (float)$prod['precio'];
            mysqli_query($conn, "INSERT INTO compras_detalle (id_compra, id_producto, cantidad, precio_unitario)
                                  VALUES ($id_compra, $idp, $cantidad, $precio)");
            mysqli_query($conn, "UPDATE productos SET cantidad=cantidad-$cantidad WHERE id_producto=$idp");
        }
        // Vaciar carrito en sesión y BD
        $_SESSION['carrito'] = [];
        if($id_usuario){
            mysqli_query($conn,"DELETE FROM carrito WHERE id_usuario=$id_usuario");
        }
        $mensaje = "Compra realizada y carrito vaciado correctamente.";
    } else {
        $mensaje = "Debes iniciar sesión antes de comprar.";
    }
}

$carrito = $_SESSION['carrito'];
$total = 0;
foreach($carrito as $item) $total += $item['precio'] * $item['cantidad'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
<div class="card">
    <div class="row">
        <div class="col-md-8 cart p-4">
            <div class="title mb-3">
                <div class="row">
                    <div class="col"><h4><b>Shopping Cart</b></h4></div>
                    <div class="col text-end text-muted"><?= count($carrito) ?> items</div>
                </div>
            </div>
            <?php if(isset($mensaje)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>
            <form method="post">
            <?php foreach($carrito as $id=>$prod): ?>
            <div class="row border-top border-bottom py-3">
                <div class="col-2">
                    <img class="img-fluid" src="ver_imagen.php?id=<?= $id ?>">
                </div>
                <div class="col">
                    <div class="row"><?= htmlspecialchars($prod['nombre']) ?></div>
                </div>
                <div class="col">
                    <input type="number" name="cantidades[<?= $id ?>]" min="1" value="<?= $prod['cantidad'] ?>" class="form-control" style="width:80px;">
                </div>
                <div class="col text-end">
                    $<?= number_format($prod['precio']*$prod['cantidad'],2) ?>
                    <a href="carrito.php?del=<?= $id ?>" class="text-danger ms-2" style="text-decoration:none;">&times;</a>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="mt-3">
                <button name="update" class="btn btn-outline-secondary">Actualizar carrito</button>
                <a href="catalogo.php" class="btn btn-outline-primary">Volver a catálogo</a>
            </div>
            </form>
        </div>
        <div class="col-md-4 summary p-4">
            <h5><b>Resumen</b></h5>
            <hr>
            <div class="row mb-3">
                <div class="col">Items <?= count($carrito) ?></div>
                <div class="col text-end">$<?= number_format($total,2) ?></div>
            </div>
            <form method="post">
                <button class="btn btn-dark w-100" name="comprar" <?= $total==0 ? "disabled":"" ?>>Finalizar compra</button>
            </form>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
