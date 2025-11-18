<?php
include_once("../configuracion.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id'])) {
    die("ID no especificado.");
}

$id = intval($_GET['id']);

// Crear carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto (auto)
$_SESSION['carrito'][] = $id;

// Redirigir al carrito
header("Location: carrito.php");
exit;
