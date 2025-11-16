<?php
// IMPORTANTE: Ruta corregida. Sube un nivel (../) para llegar a /Vista/, 
// y luego entra en /estructura/.
include_once __DIR__ . '/../estructura/header.php';

// =================================================================
// LÓGICA DE MANEJO DE MENSAJES DE RESPUESTA (FLASH MESSAGES)
// =================================================================

// 1. Obtener los mensajes de la SESIÓN (Prioritario)
// Si no están en sesión, revisamos $_GET (solo para compatibilidad con otras redirecciones)
$error_general = $_SESSION['error_general'] ?? ($_GET['error'] ?? null);
$errores_detalle = $_SESSION['errores_detalle'] ?? ($_GET['errores'] ?? []);
$mensaje_exito = $_SESSION['mensaje_exito'] ?? ($_GET['mensaje'] ?? null);

// 2. Limpiar inmediatamente la sesión para que los mensajes no reaparezcan al refrescar (FLASH)
unset($_SESSION['error_general']);
unset($_SESSION['errores_detalle']);
unset($_SESSION['mensaje_exito']);


// 3. Preparar el mensaje de error general
$mensaje_final = $error_general;

// 4. Si hay errores detallados, agregarlos al mensaje general
if (!empty($errores_detalle)) {
    $mensaje_final .= "<ul class='list mt-2'>";
    foreach ($errores_detalle as $campo => $error_texto) {
        // Formatear el error para que sea legible
        // Ejemplo: "usclave must have a length greater than or equal to 8" -> "Contraseña debe tener 8 o más caracteres."
        $campo_traducido = $campo; // Aquí puedes agregar un switch para traducir el nombre del campo

        if (strpos($error_texto, 'length greater than or equal to') !== false) {
            $num = filter_var($error_texto, FILTER_SANITIZE_NUMBER_INT);
            $mensaje_final .= "<li>La Contraseña debe tener al menos {$num} caracteres.</li>";
        } elseif (strpos($error_texto, 'alnum') !== false) {
            $mensaje_final .= "<li>El Nombre de Usuario solo debe contener letras y números.</li>";
        } elseif (strpos($error_texto, 'email') !== false) {
            $mensaje_final .= "<li>El Correo Electrónico no tiene un formato válido.</li>";
        } else {
            $mensaje_final .= "<li>{$error_texto}</li>";
        }
    }
    $mensaje_final .= "</ul>";
}
?>

<div class="ui container mt-5" style="max-width: 500px; padding-top: 20px;">

    <h2 class="ui dividing header">Registro de Nuevo Usuario</h2>

    <!-- ============================================== -->
    <!-- MUESTRA DE MENSAJES (ÉXITO/ERROR) -->
    <!-- ============================================== -->

    <?php if ($mensaje_final): ?>
        <div class="ui negative message transition visible">
            <i class="close icon"></i>
            <div class="header">
                Error en el Formulario
            </div>
            <!-- Nota: No usamos htmlspecialchars en $mensaje_final porque contiene etiquetas HTML (<ul>, <li>) -->
            <p><?= $mensaje_final ?></p>
        </div>
    <?php elseif ($mensaje_exito): ?>
        <div class="ui positive message transition visible">
            <i class="close icon"></i>
            <div class="header">
                ¡Éxito!
            </div>
            <p><?= htmlspecialchars($mensaje_exito) ?></p>
        </div>
    <?php endif; ?>

    <!-- Script para cerrar mensajes -->
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });
    </script>

    <!-- ============================================== -->
    <!-- FORMULARIO DE REGISTRO -->
    <!-- ============================================== -->

    <!-- Apunta a la acción de registro -->
    <!-- CORRECCIÓN DE RUTA: Se agrega 'Vista/' al path de la acción como solicitó el usuario. -->
    <form class="ui form segment" action="<?= URL_ROOT ?>Vista/Accion/altaUsuario.php" method="POST" id="form-registro">

        <!-- Nombre de Usuario -->
        <div class="field">
            <label>Nombre de Usuario</label>
            <input type="text" name="usnombre" placeholder="Mínimo 4 caracteres alfanuméricos" required>
        </div>

        <!-- Contraseña -->
        <div class="field">
            <label>Contraseña</label>
            <input type="password" name="usclave" placeholder="Mínimo 8 caracteres" required>
        </div>

        <!-- Correo Electrónico -->
        <div class="field">
            <label>Correo Electrónico</label>
            <input type="email" name="usmail" placeholder="ejemplo@dominio.com" required>
        </div>

        <!-- Botón de Envío -->
        <button class="ui fluid large blue submit button" type="submit">
            Registrarse
        </button>

        <div class="ui divider"></div>
        <div class="ui center aligned segment basic">
            ¿Ya tienes cuenta? <a href="<?= URL_ROOT ?>Vista/login.php">Iniciar Sesión</a>
        </div>

    </form>
</div>

<?php
include_once __DIR__ . '/../estructura/footer.php';
?>