<?php
// =================================================================
// 1. INCLUSIONES Y RUTAS
// =================================================================

// Incluir configuracion.php (asumiendo que también incluye funciones.php y define $ROOT)
include_once __DIR__ . '/../../configuracion.php';

// Incluir el Autoloader de Composer (si lo usas para Respect\Validation)
include_once $ROOT . 'vendor/autoload.php';

// Incluimos el controlador ABMusuario
include_once $ROOT . 'Control/ABMusuario.php';

// La función data_submitted() debe estar disponible aquí


// =================================================================
// 2. RECEPCIÓN Y PREPARACIÓN DE DATOS (USANDO data_submitted)
// =================================================================

// 1. Obtener todos los datos enviados (POST o GET) a través de la función de utilidad
$datosRecibidos = data_submitted();

// 2. Verificar que se haya usado el método POST y que se hayan recibido datos.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($datosRecibidos)) {
    header("Location: " . URL_ROOT . "Vista/usuario/registro.php");
    exit();
}

// 3. Recibir solo las claves esperadas.
// NOTA: El campo de contraseña del formulario es 'usclave'.
$datos = [
    'usnombre' => $datosRecibidos['usnombre'] ?? '',
    'uspass' => $datosRecibidos['uspass'] ?? '', // Contraseña para hashear
    'usmail' => $datosRecibidos['usmail'] ?? ''
];


// =================================================================
// 3. LÓGICA DE NEGOCIO (Llamada al ABM)
// =================================================================

// El HASHING de $datos['usclave'] se realiza DENTRO de la función registrarUsuario del ABM.
$abmUsuario = new ABMUsuario;
$resultado = $abmUsuario->registrarUsuario($datos);


// =================================================================
// 4. GESTIÓN DE RESPUESTA Y REDIRECCIÓN
// =================================================================

if ($resultado['resultado']) {
    // Éxito: Guardamos el mensaje en la sesión (Flash Message)
    $_SESSION['mensaje_exito'] = "¡Registro exitoso! Ya puedes iniciar sesión.";
    header("Location: " . URL_ROOT . "Vista/login.php");
    exit();
} else {
    // Error: Guardamos los mensajes de error en la sesión (Flash Messages)
    $_SESSION['error_general'] = $resultado['mensaje'];

    if (!empty($resultado['errores_validacion'])) {
        $_SESSION['errores_detalle'] = $resultado['errores_validacion'];
    }

    // Redirigir de vuelta al formulario de registro
    header("Location: " . URL_ROOT . "Vista/usuario/registro.php");
    exit();
}
