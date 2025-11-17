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

include_once($ROOT . 'util/funciones.php');


// Variable que define la pagina de autenticacion del proyecto
// Ahora usa la constante URL_ROOT
$INICIO = "Location:" . URL_ROOT . "vista/login/login.php";

// variable que define la pagina principal del proyecto (menu principal)
// Ahora usa la constante URL_ROOT
$PRINCIPAL = "http://" . $_SERVER['HTTP_HOST'] . "/Vista/index.php";
$RUTANAV = $ROOT . '/Vista/Estructura/navbar.php';
$RUTAVISTA = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/";


$_SESSION['ROOT'] = $ROOT;
