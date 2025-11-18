<?php
include_once("../configuracion.php");
include_once("../Control/ABMProducto.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$abmProducto = new ABMProducto();

// Si no existe el carrito, se crea vacío
$carrito = $_SESSION['carrito'] ?? [];
$autos = [];

// Obtener datos de cada auto agregado
foreach ($carrito as $idAuto) {
    $res = $abmProducto->buscar(['idproducto' => $idAuto]);
    if ($res) {
        $autos[] = $res[0];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carrito</title>
<link rel="stylesheet" href="../../css/semantic.min.css">
</head>

<body>
<?php include("estructura/header.php"); ?>

<div class="ui container" style="margin-top: 40px;">
    <h1 class="ui header">Carrito de Alquiler</h1>

    <?php if (empty($autos)): ?>
        <div class="ui message warning">
            <div class="header">El carrito está vacío</div>
            <p>No has agregado ningún vehículo todavía.</p>
        </div>

        <a href="index.php" class="ui primary button">Volver al catálogo</a>

    <?php else: ?>

        <div class="ui divided items">

            <?php foreach ($autos as $auto): ?>
           <div class="item">
    <div class="image">
        <img src="../imagenes/autos/<?php echo $auto->getIdProducto(); ?>.jpg" style="max-width: 150px;">
    </div>

    <div class="content">
        <h3 class="header"><?php echo $auto->getProNombre(); ?></h3>

        <div class="description">
            <p><?php echo $auto->getProDetalle(); ?></p>
        </div>

        <div class="extra">
            <strong>$10000 por día</strong>

            <a href="quitarCarrito.php?id=<?php echo $auto->getIdProducto(); ?>" 
               class="ui red right floated button">
                <i class="trash icon"></i> Eliminar
            </a>
        </div>
    </div>
</div>

            <?php endforeach; ?>

        </div>

        <a href="index.php" class="ui basic button">Seguir alquilando</a>
        <a href="confirmar.php" class="ui positive button">Confirmar Alquiler</a>

    <?php endif; ?>
</div>

</body>
</html>
