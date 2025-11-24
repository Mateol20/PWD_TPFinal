<?php
include_once("../configuracion.php");

$session = new Session;
$verifica = new verificarRol;
$verifica -> verificar(3);

$compra = new ABMCompra;
$compraEstado = new ABMCompraEstado;
$idcompra = $compra->alta($session->getUsuario());
$compraEstado->alta($idcompra,1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si no hay carrito, volver al catálogo
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}
?>