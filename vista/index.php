<?php
include_once("../configuracion.php");
$abmProducto = new ABMProducto();
$listaAutos = $abmProducto->buscar(null);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Alquiler de Autos - Página Principal</title>

    <style>
        /* Estilos personalizados para las tarjetas */
        .auto-card {
            border-radius: 10px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .auto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15) !important;
        }

        .auto-card .ui.primary.button {
            background-color: #0078ff !important;
        }

        .auto-card .ui.primary.button:hover {
            background-color: #005fcc !important;
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
                <div class="ui card auto-card">
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
    <?php include_once "./Estructura/footer.php"; ?>
</body>

</html>