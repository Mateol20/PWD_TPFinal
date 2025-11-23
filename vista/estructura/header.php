<?php
include_once __DIR__ . '../../../configuracion.php';

$session = new Session();
$rol = (int)$session->getRol();
$page_title = "Alquiler de Autos";
$idUsuario = null;
$idRol = null;
$mensajeSaludo = "Iniciar Sesión";
if ($session->validar()) {

    // 3. OBTENER LAS VARIABLES Y DEFINIRLAS
    $idUsuario = $session->getIdUsuario(); // Usando el método CORREGIDO (que devuelve el ID)
    $idRol = $session->getRol();           // Este método hace una consulta a la BD

    // 4. PREPARAR EL SALUDO
    $nombreUsuario = "ID: " . $idUsuario;

    $mensajeSaludo = "¡Hola, " . htmlspecialchars($nombreUsuario) . "!";

    // Si tu menú debe mostrar el rol:
    // $mensajeSaludo .= " (Rol ID: " . $idRol . ")"; 
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Semantic UI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
    <link rel="stylesheet" href="<?= URL_ROOT ?>/Vista/css/style.css">
</head>

<body>

    <?php include $RUTANAV; ?>

    <script>
        $(document).ready(function() {
            $('.ui.dropdown').dropdown();
        });
    </script>

    <main>