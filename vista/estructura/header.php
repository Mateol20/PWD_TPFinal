<?php
// Incluye la configuración general del proyecto
include_once __DIR__ . '/../../configuracion.php';

// Inicializa la sesión y recupera el objeto Session
$session = new Session();


// Título de la página (usa el valor pasado o un predeterminado)
$page_title = $page_title ?? "Mi Aplicación con Semantic UI";

// Se usan los estilos de Semantic UI, por lo que se eliminan las referencias a Bootstrap y jQuery
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>

    <!-- Semantic UI CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.css">

    <!-- jQuery (Requerido por Semantic UI) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Semantic UI JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js"></script>

    <style>
        body {
            display: grid;
            min-height: 100vh;
            grid-template-rows: auto 1fr auto;
            /* header, contenido, footer */
            background-color: azure;
        }

        /* Ajuste para que el menú de Semantic UI se vea bien */
        .ui.inverted.menu.full-width {
            border-radius: 0;
            margin-bottom: 0;
        }
    </style>
</head>

<body>

    <header>
        <!-- 
            Incluimos el archivo navbar.php que contendrá el menú 
            y la lógica de ControlNav, junto con el saludo del usuario.
        -->
        <?php
        // La ruta puede variar. Asegúrate de que sea correcta.
        include_once $ROOT . 'vista/estructura/navbar.php';
        ?>
    </header>

    <!-- 
        El <main> aquí se abre, y se debe cerrar en el footer.php.
        Usamos "ui container" para que el contenido principal respete los márgenes de Semantic UI.
    -->
    <main class="ui container mt-4">

        <!-- NOTA: El </body> y </html> se cierran en el archivo footer.php -->