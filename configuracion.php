<?php
session_start();

header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate ");

/////////////////////////////
// CONFIGURACION APP//
/////////////////////////////

$PROYECTO = 'PWD_TPFINAL';
define('URL_ROOT', "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/");

//variable que almacena el directorio del proyecto (Ruta Absoluta del Sistema de Archivos)
$ROOT = $_SERVER['DOCUMENT_ROOT'] . "/" . $PROYECTO . "/";
$_SESSION['ROOT'] = $ROOT;
include_once($ROOT . 'util/funciones.php');
$PRINCIPAL = "Location: " . URL_ROOT . "Vista/index.php";
$RUTANAV = $ROOT . '/Vista/Estructura/navbar.php';
$RUTAVISTA = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/";
$URL_LOGIN = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/usuario/login.php";
$GLOBALS['RUTAVISTA'] = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/";

require_once $ROOT . "vendor/autoload.php";
