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
    <link rel="stylesheet" href="../../css/semantic.min.css">
    <style>
        body { background-color: #f4f9ff; }
        .auto-card { margin-bottom: 25px; }
        .ui.card .header { font-size: 1.3em; }
    </style>
</head>

<body>

<?php include("estructura/header.php"); ?>

<div class="ui container" style="margin-top: 40px;">
    <h1 class="ui center aligned header">Autos Disponibles para Alquilar</h1>
    
    <div class="ui three stackable cards">
        <?php foreach ($listaAutos as $auto) { ?>
            <div class="card auto-card">
                <div class="content">
                    <div class="header"><?php echo $auto->getProNombre(); ?></div>
                    <div class="description">
                        <?php echo $auto->getProDetalle(); ?>
                        <p><strong>Stock:</strong> <?php echo $auto->getProCantStock(); ?></p>
                    </div>
                </div>
                <div class="extra content">
                    <a class="ui primary button" href="../producto/ver.php?id=<?php echo $auto->getIdProducto(); ?>">
                        Ver Detalles
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

<?php include("estructura/footer.php"); ?>

</body>
</html>
