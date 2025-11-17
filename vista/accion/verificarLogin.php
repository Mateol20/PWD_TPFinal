<?php
// =================================================================
// 1. INCLUSIONES Y RUTAS
// =================================================================

// Incluir configuracion.php (asegura que $ROOT y URL_ROOT estén definidos, y que funciones.php esté incluido)
include_once __DIR__ . '/../../configuracion.php';


// Definición de la URL de redirección al Login
$URL_LOGIN = URL_ROOT . "Vista/usuario/login.php";

// =================================================================
// 2. RECEPCIÓN Y PREPARACIÓN DE DATOS
// =================================================================

// 1. Obtener todos los datos enviados a través de la función de utilidad
$datosRecibidos = data_submitted();

// 2. Verificar que se haya usado el método POST y que se hayan recibido datos.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($datosRecibidos)) {
    header("Location: " . $URL_LOGIN);
    exit();
}

// 3. Obtener solo los datos necesarios
$usuario = $datosRecibidos['usnombre'] ?? '';
$clave = $datosRecibidos['usclave'] ?? '';


// =================================================================
// 3. LÓGICA DE NEGOCIO (Llamada al ABM)
// =================================================================

$abmUsuario = new AbmUsuario();

// Intentar loguear y obtener el objeto Usuario
$resultadoLogin = $abmUsuario->verificarUsuario($usuario, $clave);


// =================================================================
// 4. GESTIÓN DE RESPUESTA Y REDIRECCIÓN
// =================================================================

if ($resultadoLogin['resultado']) {
    // Éxito: El ABM ya debió haber guardado el usuario y rol en la sesión.

    // Redirigir a la página principal (definida en configuracion.php)
    header($PRINCIPAL);
    exit();
} else {
    // Error: Guardamos el mensaje de error en la sesión (Flash Message)
    $_SESSION['error_general'] = $resultadoLogin['mensaje'];

    // Redirigir de vuelta al formulario de login
    header("Location: " . $URL_LOGIN);
    exit();
}
