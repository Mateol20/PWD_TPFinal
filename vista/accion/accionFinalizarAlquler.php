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

unset($_SESSION['carrito']);
unset($_SESSION['dias']);