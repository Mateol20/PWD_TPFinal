<?php
$nav = new ControlNav();
$menus = $nav->getSubMenus($rol);
$nombreUsuario = $session->getNombreUsuario();
?>

<nav class="ui menu">
    <style>
        .ui.menu {
            margin: 0 !important;
        }
    </style>
    <div class="ui container">

        <a href="<?= URL_ROOT ?>Vista/index.php" class="item">Inicio</a>

        <?php if ($rol > 0) { ?>

            <?php foreach ($menus as $menuActual) { ?>
                <a href="<?= $nav->getUrl($menuActual->getIdmenu()) ?>" class="item">
                    <?= $menuActual->getMedescripcion() ?>
                </a>
            <?php } ?>

            <div class="right menu">
                <div class="item">
                    👋 Hola, <?= $nombreUsuario ?>
                </div>

                <a href="cerrarSesion.php" class="item">
                    <i class="sign out alternate icon"></i>
                    Cerrar Sesión
                </a>
            </div>

        <?php } else { ?>

            <div class="right menu">
                <a href="/PWD_TPfinal/Vista/usuario/login.php" class="item">
                    <i class="user icon"></i> Iniciar Sesión
                </a>
                <a href="/PWD_TPfinal/Vista/usuario/registro.php" class="item">
                    <i class="sign out alternate icon"></i> Registrarse
                </a>
            </div>

        <?php } ?>

    </div>
</nav>