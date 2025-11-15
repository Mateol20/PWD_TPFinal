<?php
// Este archivo asume que las variables $session, $rol, $usuario,
// y la clase ControlNav ya están disponibles.

// Instancia de ControlNav para obtener los menús.
$nav = new ControlNav();

// Obtener el ID del rol. Si el usuario no está logueado, se usa 0 (Invitado).
$rolId = $rol ? $rol->getIdrol() : 0;

// Obtiene los menús visibles para el rol actual
$menus = $nav->getSubMenus($rolId);
?>

<!-- Menú principal con estilos de Semantic UI -->
<!-- Importante: Agregamos 'fixed top' para que la barra se mantenga visible arriba de la página. -->
<nav class="ui inverted menu full-width fixed top">
    <div class="ui container">

        <!-- Botón de Inicio Fijo (Izquierda) -->
        <a href="../home/index.php" class="header item">
            Mi App
        </a>

        <!-- Opciones de Navegación Dinámicas (Centro) -->
        <?php if ($session->activa()) { ?>

            <!-- Menús Generados por ControlNav para el usuario logueado -->
            <?php foreach ($menus as $menuActual) {
                // Evitar imprimir enlaces vacíos
                if (!empty($menuActual->getMeUrl())) {
            ?>
                    <a href="<?= $nav->getUrl($menuActual->getIdmenu()) ?>" class="item">
                        <?= $menuActual->getMedescripcion() ?>
                    </a>
            <?php
                }
            } ?>

        <?php } ?>

        <!-- ============================================== -->
        <!-- Menú de la Derecha (Control de Sesión)         -->
        <!-- La clave es que el 'right menu' siempre está aquí, solo su contenido cambia -->
        <!-- ============================================== -->
        <div class="right menu">

            <?php if ($session->activa()) { ?>
                <?php if (isset($usuario)) { ?>

                    <!-- Contenido si la sesión está ACTIVA: Saludo y Cerrar Sesión -->

                    <!-- Saludo al Usuario -->
                    <div class="item">
                        <!-- Usando getNombre() según tu último código -->
                        <span class="ui inverted text">¡Hola, <?= $usuario->getNombre() ?>!</span>
                    </div>

                    <!-- Botón de Cerrar Sesión -->
                    <a href="../login/cerrarSesion.php" class="item">
                        <i class="sign out alternate icon"></i>
                        Cerrar Sesión
                    </a>

                <?php } ?>
                <!-- FIN DE LA CORRECCIÓN -->

            <?php } else { ?>

                <!-- Contenido si la sesión NO está ACTIVA: Iniciar Sesión y Registrarse -->

                <!-- Botón para Iniciar Sesión - RUTA ABSOLUTA WEB -->
                <!-- RUTA CORREGIDA: Apunta a Vista/usuario/login.php -->
                <a href="<?= URL_ROOT ?>Vista/usuario/login.php" class="item">
                    <i class="user icon"></i>
                    Iniciar Sesión
                </a>

                <!-- Botón para Registrarse - RUTA ABSOLUTA WEB -->
                <a href="<?= URL_ROOT ?>Vista/usuario/registro.php" class="item">
                    <i class="address card outline icon"></i>
                    Registrarse
                </a>

            <?php } ?>

        </div>

    </div>
</nav>

<!-- Div de Espacio: Necesario para que el contenido principal no quede oculto por el menú fijo (fixed top) -->
<div style="padding-top: 55px;"></div>