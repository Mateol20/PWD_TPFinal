<?php
// control/ValidadorLogin.php

use Respect\Validation\Validator as v;

class ValidadorLogin
{
    public function validar($nombreUsuario, $password)
    {
        $errores = [];

        // Validar nombre de usuario (la lógica que ya me mostraste)
        if (!v::stringType()->notEmpty()->regex('/^[a-zA-Z0-9_-]{3,30}$/')->validate($nombreUsuario)) {
            $errores[] = "El usuario es inválido. Solo permite letras, números, guión y guión bajo.";
        }

        // Validar contraseña 
        if (!v::stringType()->notEmpty()->length(3, 255)->validate($password)) {
            $errores[] = "La contraseña tiene un formato inválido.";
        }

        return $errores; // Devuelve un array vacío si no hay errores
    }
    // ... Tu función 'validar' existente ...

    public function verificarAccionLogin($nombreUsuario, $password)
    {
        // 1. Validar el formato
        $errores = $this->validar($nombreUsuario, $password);

        if (empty($errores)) {

            // Reemplazamos la función inexistente por las clases que SÍ usas:
            $abmUsuario = new ABMUsuario(); // Asumiendo que esta clase maneja la verificación de credenciales
            $session = new Session(); // Asumiendo que esta clase maneja la sesión

            // 2. VERIFICACIÓN DE CREDENCIALES (Tu lógica real)
            $objUsuario = $abmUsuario->verificarUsuario($nombreUsuario, $password);

            if ($objUsuario) {

                // 3. OBTENER EL ROL (Tu lógica real)
                $rolesUsuario = $abmUsuario->buscarRoles(['id' => $objUsuario->getIdUsuario()]);

                if (!empty($rolesUsuario)) {
                    $objUsuarioRol = $rolesUsuario[0];
                    $idRol = $objUsuarioRol->getIdRol();

                    // 4. ASIGNAR A LA SESIÓN (Tu lógica real)
                    $session->setIdUsuario($objUsuario->getIdUsuario());
                    $session->setRol($idRol);

                    // 5. Redirigir (La parte de la acción)
                    header('Location: ../index.php?login=ok');
                    exit();
                } else {
                    $errores[] = "Usuario logeado pero sin rol asignado.";
                }
            } else {
                // Credenciales incorrectas
                $errores[] = "Usuario o contraseña incorrectos.";
            }
        }

        return $errores;
    }
}
