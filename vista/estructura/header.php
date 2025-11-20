<?php
include_once __DIR__ . '../../../configuracion.php';

$session = new Session();
$rol = (int)$session->getRol();
$page_title = "Alquiler de Autos";
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

    <style>
        body {
            display: grid;
            min-height: 100vh;
            grid-template-rows: auto 1fr auto;
            background-color: azure;
        }
    </style>
</head>

<body>

    <?php include $RUTANAV; ?>

    <script>
        $(document).ready(function() {
            $('.ui.dropdown').dropdown(); // Inicializar dropdowns de Semantic UI
        });
    </script>

    <main>