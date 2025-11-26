<?php
$conn = mysqli_connect('db', 'root', 'root_password', 'tienda');
if(!$conn) exit("Sin conexión DB");
$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT foto FROM productos WHERE id_producto=$id LIMIT 1");
if($row = mysqli_fetch_assoc($result)){
    header('Content-Type: image/jpeg'); // Cambia a image/png si tus imágenes son PNG
    echo $row['foto'];
}
?>
