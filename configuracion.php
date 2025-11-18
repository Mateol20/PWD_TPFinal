<?php
session_start();

header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate ");

/////////////////////////////
// CONFIGURACION APP//
/////////////////////////////

$PROYECTO = 'PWD_TPFINAL';

// Definición de la constante URL_ROOT (SOLUCIÓN)
define('URL_ROOT', "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/");

//variable que almacena el directorio del proyecto (Ruta Absoluta del Sistema de Archivos)
$ROOT = $_SERVER['DOCUMENT_ROOT'] . "/" . $PROYECTO . "/";
$_SESSION['ROOT'] = $ROOT;
include_once($ROOT . 'util/funciones.php');


// Variable que define la pagina de autenticacion del proyecto
// Ahora usa la constante URL_ROOT
$INICIO = "Location:" . URL_ROOT . "vista/login/login.php";

// variable que define la pagina principal del proyecto (menu principal)
// Ahora usa la constante URL_ROOT
$PRINCIPAL = "Location: " . URL_ROOT . "Vista/index.php";
$RUTANAV = $ROOT . '/Vista/Estructura/navbar.php';
$RUTAVISTA = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/";
$URL_LOGIN = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/usuario/login.php";
$GLOBALS['RUTAVISTA'] = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/";

require_once $ROOT . "vendor/autoload.php";
