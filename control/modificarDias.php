<?php
include_once("../configuracion.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_POST['id']) || !isset($_POST['dias'])) {
    die("Datos incompletos.");
}

$id = intval($_POST['id']);
$dias = intval($_POST['dias']);

if ($dias < 1) {
    $dias = 1;
}

$_SESSION['dias'][$id] = $dias;

header("Location: ../vista/carrito.php");
exit;
