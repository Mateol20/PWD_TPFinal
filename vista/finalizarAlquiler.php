<?php
include_once("../configuracion.php");
$session = new Session;
$verifica = new verificarRol;
$verifica -> verificar(3);

$ABMCompraEstado = new ABMCompraEstado;
$abmProducto = new ABMProducto;
$compra = new ABMCompra;

$ABMCompraEstado->modificar('',3);
$abmProducto->actualizarStock();
// eliminar carrito y días
unset($_SESSION['carrito']);
unset($_SESSION['dias']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Alquiler Confirmado</title>
<link rel="stylesheet" href="../../css/semantic.min.css">
<style>
    body {
        background-color: #f7f9fa;
        padding-top: 50px;
    }
    .confirm-segment {
        max-width: 600px;
        margin: 0 auto;
        padding: 40px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-radius: 10px;
        background-color: #ffffff;
        text-align: center;
    }
    .confirm-header {
        margin-bottom: 20px;
    }
    .confirm-message p {
        font-size: 1.2em;
    }
</style>
</head>
<body>

<?php include("estructura/header.php"); ?>

<div class="ui container">
    <div class="ui segment confirm-segment">
        <h2 class="ui green header confirm-header">
            <i class="check circle icon"></i>
            ¡Alquiler Confirmado!
        </h2>
        <div class="confirm-message">
            <p>Tu reserva ha sido registrada con éxito.</p>
        </div>
        <a href="index.php" class="ui primary large button" style="margin-top: 20px;">
            Volver al Catálogo
        </a>
    </div>
</div>

</body>
</html>
