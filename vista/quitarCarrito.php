<?php
include_once("../configuracion.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id'])) {
    die("ID no especificado.");
}

$id = intval($_GET['id']);

if (isset($_SESSION['carrito'])) {

    // Buscar una sola ocurrencia del ID y eliminarla
    $index = array_search($id, $_SESSION['carrito']);

    if ($index !== false) {
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // reindexar
    }
}

header("Location: carrito.php");
exit;
