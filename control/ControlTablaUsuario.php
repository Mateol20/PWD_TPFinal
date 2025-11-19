<?php


class ControlTablaUsuario
{

    public function altaUsuario($data)
    {
        $abmUsuario = new ABMUsuario();
        $abmUsuarioRol = new abmUsuarioRol();
        $respuesta = false; // Inicializar

        // **Si usas la función de registro más completa (RECOMENDADO):**
        // Llama a ABMUsuario::registrarUsuario, que ya maneja hash y rol.
        $resultadoRegistro = $abmUsuario->registrarUsuario($data);
        $respuesta = $resultadoRegistro['resultado'];

        return $respuesta;
    }

    public function modificarUsuario($data)
    {
        $abmUsuario = new ABMUsuario();
        $abmUsuarioRol = new abmUsuarioRol();
        $respuesta = false; // 💡 1. Inicializar la variable de respuesta


        $usuarioList = $abmUsuario->buscar(['idusuario' => $data['idusuario']]);
        if (empty($usuarioList)) {
            return false;
        }
        $usuarioActual = $usuarioList[0];
        if (empty(trim($data['uspass']))) {
            $data['uspass'] = $usuarioActual->getPass();
        } else {
            // Si NO está vacío, hashea la nueva clave.
            $data['uspass'] = password_hash($data['uspass'], PASSWORD_DEFAULT);
        }
        if (isset($data['uspass']) && !empty(trim($data['uspass']))) {
            // ✅ Hashear la nueva clave de forma segura
            $data['uspass'] = password_hash($data['uspass'], PASSWORD_DEFAULT);
        } else {
            // ❌ Si el campo viene vacío, se mantiene la clave hasheada actual
            $data['uspass'] = $usuarioActual->getPass();
        }

        // 3. Manejo de Deshabilitado (Tu lógica ya es correcta)
        if (isset($data['usdeshabilitado']) && $data['usdeshabilitado'] == 'true') {
            $data['usdeshabilitado'] = date("Y-m-d H:i:s");
        } else {
            $data['usdeshabilitado'] = null;
        }

        // 4. Modificación del Usuario
        // **********************************************
        $respuestaUsuario = $abmUsuario->modificacion($data);
        // **********************************************

        if ($respuestaUsuario) {
            $respuesta = true; // El usuario se modificó exitosamente (Base)

            // 5. Manejo de Roles: Baja/Alta

            // 5.1. Buscar el rol actual
            $rolesActuales = $abmUsuarioRol->buscar(['idusuario' => $data['idusuario']]);
            $rolActual = count($rolesActuales) > 0 ? $rolesActuales[0] : null;

            // 5.2. Verificar si el rol es diferente al nuevo rol enviado ($data['idrol'])
            $rolNuevoId = $data['idrol'];
            $rolActualId = $rolActual ? $rolActual->getobjRol()->getidrol() : null;

            if ($rolNuevoId != $rolActualId) {
                // Si hay un rol actual, se da de baja
                if ($rolActual) {
                    $datosBajaRol = [
                        'idusuario' => $data['idusuario'],
                        'idrol' => $rolActualId
                    ];
                    $respuestaBajaRol = $abmUsuarioRol->baja($datosBajaRol);

                    if (!$respuestaBajaRol) {
                        // Si la baja del rol falla, la modificación global falla
                        $respuesta = false;
                    }
                }

                // Si la baja fue exitosa (o no había rol), se da de alta el nuevo rol
                if ($respuesta) {
                    $datosAltaRol = [
                        'idusuario' => $data['idusuario'],
                        'idrol' => $rolNuevoId
                    ];
                    $respuestaAltaRol = $abmUsuarioRol->alta($datosAltaRol);

                    if (!$respuestaAltaRol['resultado']) {
                        // Si el alta del rol falla, la modificación global falla
                        $respuesta = false;
                    }
                }
            }
            // Si los roles son iguales, no se hace nada y $respuesta sigue siendo true
        }

        return $respuesta;
    }




    public function bajaUsuario($data)
    {
        $abmUsuario = new ABMUsuario();
        $abmUsuarioRol = new ABMUsuarioRol();
        $respuesta = false;

        $idUsuario = $data['idusuario'] ?? null;

        if ($idUsuario) {
            // 2. Buscar roles actuales (asumiendo que solo tienen 1 rol)
            $rolesActuales = $abmUsuarioRol->buscar(['idusuario' => $idUsuario]);
            $idRol = count($rolesActuales) > 0 ? $rolesActuales[0]->getobjRol()->getidrol() : null;

            // 3. Si se encontró un rol, intentar dar de baja la relación
            if ($idRol) {
                $datosBajaRol = ['idusuario' => $idUsuario, 'idrol' => $idRol];
                $respuestaUsuarioRol = $abmUsuarioRol->baja($datosBajaRol);
            } else {
                // Si no tiene rol, asumimos éxito en el borrado de rol
                $respuestaUsuarioRol = true;
            }

            // 4. Si la baja de Rol fue exitosa (o no tenía rol), proceder con la baja del Usuario (Lógica)
            if ($respuestaUsuarioRol) {
                $respuesta = $abmUsuario->baja($data);
            }
        }
        return $respuesta;
    }

    public function listarUsuarios($data)
    {

        $abmUsuario = new ABMUsuario();
        $abmUsuarioRol = new abmUsuarioRol();
        $arreglo = [];


        $list = $abmUsuario->buscar($data);
        $arreglo = array();
        foreach ($list as $elem) {
            $nuevoElem['idusuario'] = $elem->getUsuarioId();
            $nuevoElem["usnombre"] = $elem->getUsuarioNombre();
            $nuevoElem["usmail"] = $elem->getUsuarioEmail();
            $nuevoElem["usdeshabilitado"] = $elem->getUsuarioDeshabilitado();
            $roles = $abmUsuarioRol->buscar(['idusuario' => $elem->getUsuarioId()]);
            if (count($roles) > 0) {
                $nuevoElem["idrol"] = $roles[0]->getobjRol()->getidrol();
                $nuevoElem["roldescripcion"] = $roles[0]->getobjRol()->getroldescripcion();
            } else {
                $nuevoElem["idrol"] = null;
                $nuevoElem["roldescripcion"] = null;
            }
            array_push($arreglo, $nuevoElem);
        }


        return $arreglo;
    }
}
