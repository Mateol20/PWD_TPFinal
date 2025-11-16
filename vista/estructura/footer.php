<?php
// Obtener el año actual para el copyright
$year = date("Y");
?>
</main>
<!-- Cierre del <main class="ui container mt-4"> abierto en header.php -->

<!-- ============================================== -->
<!-- FOOTER - Pie de Página Fijo en la parte inferior -->
<!-- ============================================== -->
<footer class="ui inverted vertical segment" style="margin-top: auto; padding: 2em 0em;">
    <div class="ui center aligned container">
        <div class="ui stackable inverted divided grid">

            <!-- Columna 1: Información de la Aplicación -->
            <div class="three wide column">
                <h4 class="ui inverted header">Mi App</h4>
                <div class="ui inverted link list">
                    <a href="../home/index.php" class="item">Inicio</a>
                    <a href="#" class="item">Acerca de</a>
                    <a href="#" class="item">Contacto</a>
                </div>
            </div>

            <!-- Columna 2: Redes Sociales / Links Secundarios -->
            <div class="three wide column">
                <h4 class="ui inverted header">Recursos</h4>
                <div class="ui inverted link list">
                    <a href="#" class="item">Términos y Condiciones</a>
                    <a href="#" class="item">Política de Privacidad</a>
                    <a href="#" class="item">Preguntas Frecuentes</a>
                </div>
            </div>

            <!-- Columna 3: Copyright y Aviso -->
            <div class="ten wide column">
                <h4 class="ui inverted header">Mi Aplicación PHP & Semantic UI</h4>
                <p>Desarrollo Web Dinámico - Derechos Reservados &copy; <?= $year ?></p>
                <p style="opacity: 0.7; font-size: 0.9em;">
                    Proyecto educativo. Los íconos y el diseño son cortesía de Semantic UI.
                </p>
            </div>

        </div>

        <div class="ui inverted section divider"></div>

        <!-- Logo y nombre al final -->
        <img src="https://placehold.co/60x60/1B1C1D/FFFFFF?text=APP" class="ui mini image" alt="Logo de la aplicación">
        <div class="ui horizontal inverted small divided link list">
            <a class="item" href="#">Contacto del Administrador</a>
        </div>
    </div>
</footer>

</body>

</html>