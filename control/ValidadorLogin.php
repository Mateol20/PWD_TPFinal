<?php
// control/ValidadorLogin.php
use Respect\Validation\Validator as v;

class ValidadorLogin
{
    public function validar($nombreUsuario, $password)
    {
        $errores = [];

        // Validar nombre de usuario (acepta letras, números, guión y guión bajo)
        if (!v::stringType()->notEmpty()->regex('/^[a-zA-Z0-9_-]{3,30}$/')->validate($nombreUsuario)) {
            $errores[] = "El usuario es inválido. Solo permite letras, números, guión y guión bajo.";
        }

        // Validar contraseña (NO restringimos caracteres)
        if (!v::stringType()->notEmpty()->length(3, 255)->validate($password)) {
            $errores[] = "La contraseña tiene un formato inválido.";
        }

        return $errores;
    }
}
