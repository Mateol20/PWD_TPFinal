<?php

/**
 * Retorna los datos enviados a través de POST o GET.
 * @return array
 */
function data_submitted()
{
    $_AAux = array();
    if (!empty($_POST))
        $_AAux = $_POST;
    else
            if (!empty($_GET)) {
        $_AAux = $_GET;
    }
    if (count($_AAux)) {
        foreach ($_AAux as $indice => $valor) {
            if ($valor == "")
                $_AAux[$indice] = 'null';
        }
    }
    return $_AAux;
}

function autoloader($class_name)
{
    $directorys = array(
        $_SESSION['ROOT'] . 'modelo/',
        $_SESSION['ROOT'] . 'vista/',
        $_SESSION['ROOT'] . 'control/',
        $_SESSION['ROOT'] . 'modelo/conector/',
        $_SESSION['ROOT'] . 'control/'
    );
    foreach ($directorys as $directory) {
        if (file_exists($directory . $class_name . '.php')) {
            require_once($directory . $class_name . '.php');
            return;
        }
    };
}

spl_autoload_register('autoloader');
