<?php


// 3. Incluir la estructura básica del header
include_once __DIR__ . '/Vista/estructura/header.php';
$session = new Session();

$usuario_logueado = $session->activa();
// Si la sesión está activa, el objeto Usuario ya está cargado y completo.
$nombre_usuario = $usuario_logueado ? $session->getUsuario()->getNombre() : null;


// Definición de URLS
$URL_LOGIN = URL_ROOT . 'Vista/usuario/login.php';
$URL_LOGOUT_ACTION = URL_ROOT . 'Vista/Accion/cerrarSesion.php';

?>

<div class="ui container mt-5" style="padding-top: 20px;">

    <!-- ============================================== -->
    <!-- SALUDO Y MENSAJES DE ESTADO -->
    <!-- ============================================== -->
    <?php if ($usuario_logueado): ?>
        <!-- Saludo personalizado para usuarios logueados -->
        <div class="ui floating message green">
            <h1 class="header">¡Bienvenido de vuelta, <?= htmlspecialchars($nombre_usuario) ?>!</h1>
            <p>Estás en el sistema de alquiler de autos. Explora las opciones a continuación o busca tu vehículo.</p>
        </div>

        <!-- Botón de Cerrar Sesión -->
        <form action="<?= $URL_LOGOUT_ACTION ?>" method="POST" class="mt-3 text-right">
            <button class="ui red basic button" type="submit">
                <i class="sign out icon"></i>
                Cerrar Sesión
            </button>
        </form>

    <?php else: ?>
        <!-- Mensaje para usuarios no logueados -->
        <div class="ui floating message blue">
            <h1 class="header">Sistema de Alquiler de Autos</h1>
            <p>Busca tu próximo vehículo. Para reservar y gestionar tus alquileres, por favor, inicia sesión.</p>
        </div>

        <!-- Botón de Iniciar Sesión -->
        <div class="text-right">
            <a href="<?= $URL_LOGIN ?>" class="ui large primary button">
                <i class="sign in alternate icon"></i>
                Iniciar Sesión / Registrarse
            </a>
        </div>
    <?php endif; ?>

    <h2 class="ui dividing header mt-5">Autos Disponibles para Alquiler</h2>

    <!-- ============================================== -->
    <!-- FORMULARIO DE BÚSQUEDA SIMPLE -->
    <!-- ============================================== -->
    <form class="ui form segment mb-5">
        <div class="fields">
            <div class="eight wide field">
                <label>Lugar de Retiro</label>
                <input type="text" placeholder="Ciudad o Sucursal">
            </div>
            <div class="four wide field">
                <label>Fecha de Retiro</label>
                <input type="date">
            </div>
            <div class="four wide field">
                <label>Fecha de Devolución</label>
                <input type="date">
            </div>
        </div>
        <button class="ui fluid blue button" type="submit">
            <i class="search icon"></i>
            Buscar Autos
        </button>
    </form>

    <!-- ============================================== -->
    <!-- TABLA DE AUTOS DISPONIBLES (Mock Data) -->
    <!-- ============================================== -->
    <table class="ui striped celled table responsive-table">
        <thead>
            <tr>
                <th class="five wide">Modelo</th>
                <th class="three wide">Categoría</th>
                <th class="two wide">Precio Diario</th>
                <th class="two wide">Disponibilidad</th>
                <th class="four wide">Acción</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td data-label="Modelo">Ford Fiesta (2023)</td>
                <td data-label="Categoría">Compacto</td>
                <td data-label="Precio Diario">$ 5.000</td>
                <td data-label="Disponibilidad">
                    <div class="ui green label">Inmediata</div>
                </td>
                <td data-label="Acción">
                    <button class="ui tiny primary button disabled-if-not-logged" <?= $usuario_logueado ? '' : 'disabled' ?>>Reservar</button>
                    <button class="ui tiny basic button">Ver Detalles</button>
                </td>
            </tr>
            <tr>
                <td data-label="Modelo">Toyota Hilux (2024)</td>
                <td data-label="Categoría">Camioneta 4x4</td>
                <td data-label="Precio Diario">$ 15.000</td>
                <td data-label="Disponibilidad">
                    <div class="ui orange label">Baja</div>
                </td>
                <td data-label="Acción">
                    <button class="ui tiny primary button disabled-if-not-logged" <?= $usuario_logueado ? '' : 'disabled' ?>>Reservar</button>
                    <button class="ui tiny basic button">Ver Detalles</button>
                </td>
            </tr>
            <tr>
                <td data-label="Modelo">Mercedes-Benz Clase C</td>
                <td data-label="Categoría">Lujo</td>
                <td data-label="Precio Diario">$ 25.000</td>
                <td data-label="Disponibilidad">
                    <div class="ui red label">Agotado</div>
                </td>
                <td data-label="Acción">
                    <button class="ui tiny primary button disabled-if-not-logged" disabled>Reservar</button>
                    <button class="ui tiny basic button">Ver Detalles</button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Aviso si no está logueado -->
    <?php if (!$usuario_logueado): ?>
        <div class="ui info message">
            <p>Debes iniciar sesión para poder reservar un vehículo.</p>
        </div>
    <?php endif; ?>

    <!-- Enlace a la administración (solo si tienes rol de administrador) -->
    <?php
    // Usamos $session->getRol() y getDescripcion() para verificar el rol
    if ($usuario_logueado && $session->getRol() && method_exists($session->getRol(), 'getDescripcion') && $session->getRol()->getDescripcion() === 'Administrador'): ?>
        <div class="ui clearing segment">
            <p class="float-right">Acceso Rápido:
                <a href="<?= URL_ROOT ?>Vista/admin/panel.php" class="ui teal button">Panel de Administración</a>
            </p>
        </div>
    <?php endif; ?>

</div>

<?php
// 4. Incluir la estructura básica del footer
include_once __DIR__ . '/Vista/estructura/footer.php';
?>