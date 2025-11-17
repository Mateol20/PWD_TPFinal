<?php foreach ($listaAutos as $auto) { 
    $id = $auto->getIdProducto();
    $rutaImg = "../../imagenes/autos/" . $id . ".jpg";
?>
    <div class="card auto-card">

        <?php if (file_exists($rutaImg)) { ?>
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
            <a class="ui primary button" href="../producto/ver.php?id=<?php echo $id; ?>">
                Ver Detalles
            </a>
        </div>

    </div>
<?php } ?>
