<?php

use Respect\Validation\Validator as v;

class ABMUsuario
{

    private function cargarObjeto($param)
    {
        $obj = null;

        if (
            array_key_exists('usnombre', $param) &&
            array_key_exists('uspass', $param) &&
            array_key_exists('usmail', $param)
        ) {
            $obj = new Usuario();

            $id = $param['idusuario'] ?? null;
            $deshab = $param['usdeshabilitado'] ?? null;

            $obj->cargar(
                $id,
                $param['usnombre'],
                $param['uspass'],
                $param['usmail'],
                $deshab
            );
        }

        return $obj;
    }


    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Usuario|null
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;

        $id = $param['idusuario'] ?? $param['id'] ?? null;

        if ($id !== null) {
            $obj = new Usuario();

            if ($obj->buscar($id)) {
                return $obj;
            }
        }
        return null;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        // ✅ Ahora acepta la clave 'idusuario' o 'id'
        if (isset($param['idusuario']) || isset($param['id'])) {
            $resp = true;
        }
        return $resp;
    }

    // ==================================================================================
    // METODOS CRUD PUBLICOS
    // ==================================================================================

    /**
     * Permite dar de alta un objeto
     * @param array $param
     */
    public function alta($param)
    {
        $resp = [];

        $elObjtTabla = $this->cargarObjeto($param);

        if ($elObjtTabla !== null) {

            if ($elObjtTabla->insertar()) {
                $resp = [
                    'resultado' => true,
                    'error'     => '',
                    'obj'       => $elObjtTabla
                ];
            } else {
                $resp = [
                    'resultado' => false,
                    'error'     => $elObjtTabla->getMensajeError()
                ];
            }
        } else {
            // 🛑 El objeto no se pudo cargar → evitar el fatal error
            $resp = [
                'resultado' => false,
                'error'     => "No se pudo cargar el objeto Usuario en alta()"
            ];
        }

        return $resp;
    }

    /**
     * Permite eliminar un objeto (se usa borrado lógico - setea usdeshabilitado a fecha actual)
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjetoConClave($param);
            if ($elObjtTabla != null and $elObjtTabla->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * Permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        $resp = false;
        // Debes asegurarte que seteadosCamposClaves acepte 'idusuario' (o simplemente pasamos el ID)

        if ($this->seteadosCamposClaves($param)) {

            $elObjtTabla = $this->cargarObjeto($param);

            // 💡 CORRECCIÓN: Determinar el ID correctamente
            $id = $param['id'] ?? $param['idusuario'] ?? null;

            if ($id !== null) {
                $elObjtTabla->setId($id); // Setea el ID en el objeto antes de modificar
            }


            if ($elObjtTabla != null and $elObjtTabla->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite buscar un objeto
     * @param array $param
     * @return array
     */
    public function buscar($param)
    {
        $where = " true ";

        // 💡 CORRECCIÓN: Agregar 'idusuario' a las claves esperadas
        $claves = ["id", "idusuario", "nombre", "pass", "mail", "deshabilitado"];
        $db     = ["idusuario", "idusuario", "usnombre", "uspass", "usmail", "usdeshabilitado"];


        if ($param <> null) {
            for ($i = 0; $i < count($claves); $i++) {
                if (isset($param[$claves[$i]])) {
                    // Asegurar que solo se añade una vez al WHERE
                    if ($claves[$i] == 'id' && isset($param['idusuario'])) continue;

                    $where .= " and " . $db[$i] . " = '" . $param[$claves[$i]]  . "'";
                }
            }
        }

        $obj = new Usuario();
        $arreglo = $obj->listar($where);

        // Si listar devuelve null, devolver un array vacío.
        return $arreglo ?? [];
    }

    // ==================================================================================
    // METODOS DE ROLES
    // ==================================================================================

    /**
     * Asigna un rol al usuario.
     * @param array $param: ['id' => idUsuario, 'idrol' => idRol]
     * @return array ['resultado' => bool, 'mensaje' => string]
     */
    public function asignarRol($param)
    {
        $resp = [
            'resultado' => false,
            'mensaje'   => 'Error desconocido en asignarRol.'
        ];

        if (isset($param["id"]) && isset($param["idrol"])) {

            // 🚨 CAMBIO CLAVE: Usamos ABMUsuarioRol para dar de alta la relación
            $abmUsuarioRol = new ABMUsuarioRol();

            // El ABMUsuarioRol necesita 'idusuario' y 'idrol', no solo 'id'
            $datosRelacion = [
                'idusuario' => $param['id'],
                'idrol' => $param['idrol']
            ];

            // Llamar al alta del ABMUsuarioRol
            $resultadoAltaRol = $abmUsuarioRol->alta($datosRelacion);

            if ($resultadoAltaRol['resultado']) {
                $resp['resultado'] = true;
                $resp['mensaje'] = 'Rol asignado exitosamente.';
            } else {
                // Error capturado del ABMUsuarioRol
                $resp['mensaje'] = "Fallo la asignación de rol: " . $resultadoAltaRol['error'];
            }
        } else {
            $resp['mensaje'] = "Faltan IDs de usuario o rol para asignar el rol.";
        }

        return $resp;
    }

    /**
     * Le quita un rol al usuario
     * @param array $param: ['id' => idUsuario, 'idrol' => idRol]
     * @return boolean
     */
    public function quitarRol($param)
    {
        $resp = false;

        if ($this->seteadosCamposClaves($param) && isset($param["idrol"])) {
            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->cargarClaves($param["idrol"], $param["id"]);
            if ($objUsuarioRol->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * Retorna los roles de un usuario
     * @param array $param: ['id' => idUsuario]
     * @return array
     */
    public function buscarRoles($param)
    {
        $where = " true ";
        $claves = ["id"];
        $clavesDB = ["idusuario"];


        if ($param <> null) {
            for ($i = 0; $i < count($claves); $i++) {
                if (isset($param[$claves[$i]])) {
                    $where .= " and " . $clavesDB[$i] . " = '" . $param[$claves[$i]]  . "'";
                }
            }
        }

        $obj = new UsuarioRol();
        $arreglo = $obj->listar($where);
        return $arreglo ?? [];
    }
    public function buscarUsuarioPorNombre($nombreUsuario)
    {
        // Uso correcto del método buscar() del ABM
        $param = ['nombre' => $nombreUsuario];

        $lista = $this->buscar($param);

        if (!empty($lista)) {
            return $lista[0]; // retorna objeto Usuario
        }

        return null;
    }

    public function verificarUsuario($nombreUsuario, $password)
    {
        $usuario = $this->buscarUsuarioPorNombre($nombreUsuario);

        if (!$usuario) {
            return null;
        }

        $hashAlmacenado = $usuario->getPass();



        if (!password_verify($password, $hashAlmacenado)) {
            // ... (Log de fallo)
            return null;
        }

        return $usuario;
    }

    public function registrarUsuario($param)
    {
        // Aplicar trim a la clave plana (corrigiendo el posible problema anterior)
        $clave_plana = trim($param['uspass']);

        // Hasheo seguro
        $param['uspass'] = password_hash($clave_plana, PASSWORD_DEFAULT);

        // El campo deshabilitado arranca NULL
        $param['usdeshabilitado'] = null;

        // (Punto 1) Intentar el alta del Usuario
        $resultadoAlta = $this->alta($param);

        if ($resultadoAlta['resultado']) {
            $objUsuario = $resultadoAlta['obj'];
            $idUsuarioNuevo = $objUsuario->getIdUsuario();
            $idRolPorDefecto = 3;

            // (Punto 2) Asignar el Rol por defecto
            // 🚨 CAMBIO DE LLAMADA: darRol() -> asignarRol()
            $resultadoRol = $this->asignarRol([
                'id'    => $idUsuarioNuevo,
                'idrol' => $idRolPorDefecto
            ]);

            if ($resultadoRol['resultado']) { // 🚨 Ahora verifica el índice 'resultado'
                // Éxito: Usuario creado y Rol asignado
                return ['resultado' => true, 'mensaje' => 'Registro y asignación de rol exitosos.'];
            } else {
                // Error: El usuario se creó, pero el rol falló.
                // 🚨 DEVOLVER EL MENSAJE DE ERROR CAPTURADO
                return [
                    'resultado' => false,
                    'mensaje'   => 'Registro de usuario exitoso, pero falló la asignación del rol: ' . $resultadoRol['mensaje']
                ];
            }
        } else {
            return $resultadoAlta;
        }
    }
}
