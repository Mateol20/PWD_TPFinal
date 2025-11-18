<?php
// Incluimos la estructura básica. La ruta es un nivel superior (../) para llegar a /Vista/estructura/
include_once __DIR__ . '/../estructura/header.php';

// =================================================================
// LÓGICA DE MANEJO DE MENSAJES DE RESPUESTA (FLASH MESSAGES)
// =================================================================

// 1. Obtener los mensajes de la SESIÓN
$error_general = $_SESSION['error_general'] ?? ($_GET['error'] ?? null);
$mensaje_exito = $_SESSION['mensaje_exito'] ?? ($_GET['mensaje'] ?? null);

// 2. Limpiar inmediatamente la sesión para que los mensajes no reaparezcan al refrescar (FLASH)
unset($_SESSION['error_general']);
unset($_SESSION['mensaje_exito']);
?>

<div class="ui container mt-5" style="max-width: 400px; padding-top: 20px;">

    <h2 class="ui dividing header">Iniciar Sesión</h2>

    <!-- ============================================== -->
    <!-- MUESTRA DE MENSAJES (ERROR) -->
    <!-- ============================================== -->

    <?php if ($error_general): ?>
        <div class="ui negative message transition visible">
            <i class="close icon"></i>
            <div class="header">
                Error de Acceso
            </div>
            <p><?= htmlspecialchars($error_general) ?></p>
        </div>
    <?php elseif ($mensaje_exito): ?>
        <div class="ui positive message transition visible">
            <i class="close icon"></i>
            <div class="header">
                <?= htmlspecialchars($mensaje_exito) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Script para cerrar mensajes -->
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });
    </script>

    <!-- ============================================== -->
    <!-- FORMULARIO DE LOGIN -->
    <!-- ============================================== -->

    <!-- Apunta a la acción de verificación de login -->
    <form class="ui form segment" action="<?= URL_ROOT ?>Vista/Accion/verificarLogin.php" method="POST" id="form-login">

        <!-- Nombre de Usuario / Correo (Puedes elegir) -->
        <div class="field">
            <label>Nombre de Usuario o Email</label>
            <!-- Usaremos 'usnombre' como clave para simplificar, el controlador decidirá si busca por nombre o email -->
            <input type="text" name="usnombre" placeholder="Usuario o correo" required>
        </div>

        <!-- Contraseña -->
        <div class="field">
            <label>Contraseña</label>
            <!-- La clave debe ser 'usclave' como en el registro -->
            <input type="password" name="uspass" placeholder="Contraseña" required>
        </div>

        <!-- Botón de Envío -->
        <button class="ui fluid large green submit button" type="submit">
            <i class="sign in alternate icon"></i>
            Acceder
        </button>

        <div class="ui divider"></div>
        <div class="ui center aligned segment basic">
            <!-- RUTA CORREGIDA: Apunta a registro.php dentro de la misma carpeta -->
            ¿No tienes cuenta? <a href="<?= URL_ROOT ?>Vista/usuario/registro.php">Regístrate aquí</a>
        </div>

    </form>
</div>

<?php
// La ruta es un nivel superior (../) para llegar a /Vista/estructura/
include_once __DIR__ . '/../estructura/footer.php';
?>