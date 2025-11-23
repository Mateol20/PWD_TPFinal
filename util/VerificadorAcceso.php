<?php
// util/VerificadorAcceso.php

require_once dirname(__DIR__) . '/configuracion.php';
require_once dirname(__DIR__) . '/Control/Session.php';

/**
 * Verifica si el usuario logueado tiene permiso para acceder a una página.
 */
function verificarAcceso($nombrePagina)
{
    global $PAGINAS_PROTEGIDAS;

    $session = new Session();

    // 1. Verificar si la página necesita protección
    if (!isset($PAGINAS_PROTEGIDAS[$nombrePagina])) {
        return; // página pública
    }

    $rolesPermitidos = $PAGINAS_PROTEGIDAS[$nombrePagina];

    // 2. ¿Está logueado?
    if (!$session->validar()) {
        $mensaje = "Debes iniciar sesión para acceder a esta página.";
        header("Location: " . URL_ROOT . "Vista/usuario/login.php?error=" . urlencode($mensaje));
        exit;
    }

    // 3. Obtener rol del usuario
    $rolUsuario = $session->getRolDirecto();

    // 4. Verificar si el rol está permitido
    if (!in_array($rolUsuario, $rolesPermitidos)) {
        $mensaje = "Acceso denegado. Tu rol no tiene permisos.";
        header("Location: " . URL_ROOT . "Vista/index.php?error_general=" . urlencode($mensaje));
        exit;
    }
}
