<?php
include_once '../../configuracion.php';

$validador = new ValidadorLogin();
$abmUsuario = new ABMUsuario();
$session = new Session();

$usuario = $_POST['usnombre'] ?? '';
$clave = $_POST['uspass'] ?? '';

$errores = $validador->validar($usuario, $clave);

if (!empty($errores)) {
    // Redirigir con errores
    header("Location: ../usuario/login.php?error=" . urlencode(implode(", ", $errores)));
    exit;
}

$objUsuario = $abmUsuario->verificarUsuario($usuario, $clave);

if ($objUsuario) {

    $rolesUsuario = $abmUsuario->buscarRoles(['id' => $objUsuario->getIdUsuario()]);

    if (!empty($rolesUsuario)) {

        $objUsuarioRol = $rolesUsuario[0];


        $idRol = $objUsuarioRol->getIdRol();


        // 3. Guardar datos en sesión
        $session->setIdUsuario($objUsuario->getIdUsuario());
        $session->setRol($idRol); // ⬅️ ¡CORREGIDO!

        header("Location: ../index.php?login=ok");
        exit;
    } else {
        // El usuario existe pero no tiene roles asignados
        error_log("Login ERROR: Usuario sin rol asignado: " . $objUsuario->getIdUsuario());
        header("Location: ../usuario/login.php?error=Usuario sin rol asignado.");
        exit;
    }
}
