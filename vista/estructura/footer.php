<?php

$year = date("Y");
?>
</main>

<footer class="ui inverted vertical segment" style="margin-top: auto; padding: 2em 0em;">
    <div class="ui center aligned container">
        <div class="ui stackable inverted divided grid">

            <!-- Columna 1: Información de la Aplicación -->
            <div class="three wide column">
                <h4 class="ui inverted header">Alquiler de autos</h4>
                <div class="ui inverted link list">
                    <a href="#" class="item">Acerca de</a>
                    <a href="#" class="item">Contacto</a>
                </div>
            </div>

            <div class="three wide column">
                <h4 class="ui inverted header">Recursos</h4>
                <div class="ui inverted link list">
                    <a href="https://github.com/Mateol20/PWD_TPFinal" target="_blank" rel="noopener noreferrer" class="item">
                        <i class="github icon"></i> Repositorio (GitHub)
                    </a>
                </div>
            </div>

            <div class="ten wide column">
                <h4 class="ui inverted header">GRUPO 7 PWD <?= $year ?>
                    <p style="opacity: 0.7; font-size: 0.9em;">
                        Integrantes:
                        <br>
                        Mateo Garcia FAI-4226
                        <br>
                        Ignacio Bonorino FAI-4863
                    </p>
            </div>

        </div>

        <div class="ui inverted section divider"></div>

    </div>
</footer>

</body>

</html>