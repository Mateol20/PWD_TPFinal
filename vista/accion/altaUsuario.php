<?php
include_once __DIR__ . '/../../configuracion.php';
$datosRecibidos = data_submitted();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($datosRecibidos)) {
    header("Location: " . URL_ROOT . "Vista/usuario/registro.php");
    exit();
}

$datos = [
    'usnombre' => $datosRecibidos['usnombre'] ?? '',
    'uspass' => $datosRecibidos['uspass'] ?? '',
    'usmail' => $datosRecibidos['usmail'] ?? ''
];

$abmUsuario = new ABMUsuario;
$resultado = $abmUsuario->registrarUsuario($datos);

if ($resultado['resultado']) {
    $_SESSION['mensaje_exito'] = "¡Registro exitoso! Ya puedes iniciar sesión.";
    header("Location: " . URL_ROOT . "vista/usuario/login.php");
    exit();
} else {
    $_SESSION['error_general'] = $resultado['mensaje'];

    if (!empty($resultado['errores_validacion'])) {
        $_SESSION['errores_detalle'] = $resultado['errores_validacion'];
    }
    header("Location: " . URL_ROOT . "Vista/usuario/registro.php");
    exit();
}
