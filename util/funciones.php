<?php
function data_submitted()
{
    $data = [];

    // Combinar POST y GET
    if (!empty($_POST)) {
        $data = array_merge($data, $_POST);
    }
    if (!empty($_GET)) {
        $data = array_merge($data, $_GET);
    }

    // Convertir strings vacíos en "null"
    foreach ($data as $indice => $valor) {
        if ($valor === "" || $valor === "null")
            $_AAux[$indice] = null;
    }

    return $data;
}

function autoloader($class_name)
{
    $directorys = array(
        // RUTA CORRECTA A CONTROL/ (minúsculas)
        $_SESSION['ROOT'] . 'control/',

        // RUTA CORRECTA A MODELO/ (minúsculas)
        $_SESSION['ROOT'] . 'modelo/',

        // Otras rutas que uses (vista/, modelo/conector/, etc.)
        $_SESSION['ROOT'] . 'vista/',
        $_SESSION['ROOT'] . 'modelo/conector/',

        // Asegúrate de que no haya rutas duplicadas o innecesarias
    );

    foreach ($directorys as $directory) {
        if (file_exists($directory . $class_name . '.php')) {
            require_once($directory . $class_name . '.php');
            return;
        }
    };
}

spl_autoload_register('autoloader');
