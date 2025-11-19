<?php
include_once("../configuracion.php");
$abmProducto = new ABMProducto();
$listaAutos = $abmProducto->buscar(null); // Trae todos los productos
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Alquiler de Autos - Página Principal</title>
    <style>
        body {
            background-color: #f4f9ff;
        }

        .auto-card {
            margin-bottom: 25px;
        }

        .ui.card .header {
            font-size: 1.3em;
        }
    </style>
</head>

<body>

    <?php include("estructura/header.php"); ?>

    <div class="ui container" style="margin-top: 40px;">
        <h1 class="ui center aligned header">Autos Disponibles para Alquilar</h1>

        <div class="ui three stackable cards">
            <?php foreach ($listaAutos as $auto) {
                $id = $auto->getIdProducto();
                $rutaImg = "../imagenes/autos/" . $id . ".jpg";
            ?>
                <div class="card auto-card">

                    <?php
                    echo "<!-- Ruta generada: $rutaImg -->";
                    if (file_exists($rutaImg)) { ?>
                        <div class="image">
                            <img src="<?php echo $rutaImg; ?>" alt="<?php echo $auto->getProNombre(); ?>">
                        </div>
                    <?php } ?>

                    <div class="content">
                        <div class="header"><?php echo $auto->getProNombre(); ?></div>
                        <div class="description">
                            <?php echo $auto->getProDetalle(); ?>
                            <p><strong>Stock:</strong> <?php echo $auto->getProCantStock(); ?></p>
                        </div>
                    </div>

                    <div class="extra content">
                        <a class="ui primary button" href="ver.php?id=<?php echo $id; ?>">
                            Ver Detalles
                        </a>
                    </div>

                </div>
            <?php } ?>


        </div>

    </div>