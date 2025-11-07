<?php
include_once '../configuracion.php';

$objCompra = new Compra;
$objU = new Usuario;



// $objU ->setear('nacho',123,'TpFinal@gmail.com');
// $objU ->insert();

$objCompra->setear(1,01/04/2003);
echo $objCompra->insertar();