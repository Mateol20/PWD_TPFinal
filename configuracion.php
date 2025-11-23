<?php
session_start();

header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate ");

/////////////////////////////
// CONFIGURACION APP//
/////////////////////////////

$PROYECTO = 'PWD_TPFINAL';
define('URL_ROOT', 'http://localhost/PWD_TPFinal/');
//variable que almacena el directorio del proyecto (Ruta Absoluta del Sistema de Archivos)
$ROOT = $_SERVER['DOCUMENT_ROOT'] . "/" . $PROYECTO . "/";
$_SESSION['ROOT'] = $ROOT;
include_once($ROOT . 'util/funciones.php');
$PRINCIPAL = "Location: " . URL_ROOT . "Vista/index.php";
$RUTANAV = $ROOT . '/Vista/Estructura/navbar.php';
$RUTAVISTA = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/";
$URL_LOGIN = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/usuario/login.php";
$GLOBALS['RUTAVISTA'] = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/";
// ** CONSTANTES DE CONFIGURACIÓN DE CORREO **
define('MAIL_HOST', 'smtp.gmail.com'); // O el servidor de tu proveedor (ej. Outlook: smtp.office365.com)
define('MAIL_USERNAME', 'tu-correo-a-enviar@gmail.com'); // Tu dirección de correo
define('MAIL_PASSWORD', 'tu-contraseña-o-app-password'); // ¡Usa una contraseña de aplicación si es Gmail!
define('MAIL_PORT', 587); // Puerto SMTP estándar (587 para TLS)
define('MAIL_SECURE', 'tls'); // Encriptación
define('MAIL_FROM_NAME', 'Alquiler de Autos');
define('ROL_ADMIN', 1);
define('ROL_DEPOSITO', 2);
define('ROL_CLIENTE', 3);
// Roles Permitidos por Página (Usando los IDs de roles)
$PAGINAS_PROTEGIDAS = [
    'abmMenu.php' => [ROL_ADMIN],
    'gestionRoles.php'   => [ROL_ADMIN],
    'abmUsuarios.php'   => [ROL_ADMIN],
    'abmProductos' => [ROL_DEPOSITO],

    // accesibles por todos los logeados:
    'infoUsuario.php' => [ROL_ADMIN, ROL_DEPOSITO, ROL_CLIENTE],
    'index.php' => [ROL_ADMIN, ROL_DEPOSITO, ROL_CLIENTE],
    'login.php' => [ROL_ADMIN, ROL_DEPOSITO, ROL_CLIENTE],
    'registro.php' => [ROL_ADMIN, ROL_DEPOSITO, ROL_CLIENTE],
    'carrito.php' => [ROL_CLIENTE, ROL_ADMIN],
];


require_once $ROOT . "vendor/autoload.php";
