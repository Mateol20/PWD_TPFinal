<?php
include_once("../configuracion.php");
include_once("../Control/ABMProducto.php");
$session = new Session ;
$verificar = new verificarRol;
$verificar->verificar(3);//verifica login, si el usuario tiene idRol 0 lo redirige a logeo

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