<?php
include_once("../../configuracion.php");
$objCompraEstado = new ABMCompraEstado;
$idCompra = $_GET['id'];
$objCompraEstado->cancelarCompra($idCompra);
 header("Location: ../Alquileres.php");
                exit();
?>