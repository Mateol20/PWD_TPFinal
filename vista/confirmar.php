<?php
include_once("../configuracion.php");
include_once("../Control/ABMProducto.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no hay carrito, volver al catálogo
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}

$abmProducto = new ABMProducto();

$carrito = $_SESSION['carrito'];
$autos = [];

foreach ($carrito as $id) {
    $res = $abmProducto->buscar(['idproducto' => $id]);
    if ($res) {
        $autos[] = $res[0];
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Confirmar Alquiler</title>
<link rel="stylesheet" href="../../css/semantic.min.css">
</head>

<body>

<?php include("estructura/header.php"); ?>

<div class="ui container" style="margin-top: 40px;">

<h1 class="ui header">Confirmar Alquiler</h1>

<div class="ui segment">

<table class="ui celled table">
    <thead>
        <tr>
            <th>Vehículo</th>
            <th>Días</th>
            <th>Precio por Día</th>
            <th>Subtotal</th>
        </tr>
    </thead>

    <tbody>

    <?php 
    $precioDia = 10000;
    $total = 0;

    foreach ($autos as $auto):
        $id = $auto->getIdProducto();

        $dias = $_SESSION['dias'][$id] ?? 1;

        $subtotal = $dias * $precioDia;
        $total += $subtotal;
    ?>
        <tr>
            <td><?php echo $auto->getProNombre(); ?></td>
            <td><?php echo $dias; ?> días</td>
            <td>$<?php echo number_format($precioDia, 0, ',', '.'); ?></td>
            <td>$<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
        </tr>

    <?php endforeach; ?>

    </tbody>

    <tfoot>
        <tr>
            <th colspan="3" class="right aligned">TOTAL:</th>
            <th>$<?php echo number_format($total, 0, ',', '.'); ?></th>
        </tr>
    </tfoot>
</table>

</div>

<!-- Botones -->
<div class="ui buttons">
    <a href="carrito.php" class="ui button">Volver</a>

    <form action="finalizarAlquiler.php" method="POST">
        <button class="ui positive button">Finalizar Alquiler</button>
    </form>
</div>

</div>

</body>
</html>
