<?php

use Respect\Validation\Validator as v;

class ABMUsuario
{

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Usuario|null
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (
            array_key_exists('nombre', $param)
            and array_key_exists('pass', $param)
            and array_key_exists('mail', $param)
            and array_key_exists('deshabilitado', $param)
        ) {
            $obj = new Usuario();
            // Asumo que tu modelo Usuario tiene un método cargar($id, $nombre, $pass, $mail, $deshabilitado)
            $id = $param["id"] ?? null;
            $obj->cargar($id, $param["nombre"], $param["pass"], $param["mail"], $param["deshabilitado"]);
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

        if (isset($param['id'])) {
            $obj = new Usuario();
            $obj->buscar($param["id"]);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['id']))
            $resp = true;
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
        $resp = array();
        $elObjtTabla = $this->cargarObjeto($param);

        if ($elObjtTabla != null and $elObjtTabla->insertar()) {
            $resp = array('resultado' => true, 'error' => '', 'obj' => $elObjtTabla);
        } else {
            $resp = array('resultado' => false, 'error' => $elObjtTabla->getMensajeError());
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

            // Borrado lógico: se setea la fecha de deshabilitación a "NOW()"
            $elObjtTabla->setDeshabilitado("NOW()");

            if ($elObjtTabla != null and $elObjtTabla->modificar()) {
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
        if ($this->seteadosCamposClaves($param)) {
            // Cargar el objeto con los nuevos datos
            $elObjtTabla = $this->cargarObjeto($param);

            // Asegurar que el ID está seteado
            $elObjtTabla->setId($param["id"]);

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
        // Claves del array $param que recibes
        $claves = ["id", "nombre", "pass", "mail", "deshabilitado"];
        // Claves de la base de datos (DB) correspondientes
        $db = ["idusuario", "usnombre", "uspass", "usmail", "usdeshabilitado"];


        if ($param <> null) {
            for ($i = 0; $i < count($claves); $i++) {
                if (isset($param[$claves[$i]])) {
                    // Nota: Idealmente, usar prepared statements
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
     * Le otorga un rol al usuario
     * @param array $param: ['id' => idUsuario, 'idrol' => idRol]
     * @return boolean
     */
    public function darRol($param)
    {
        $resp = false;

        if ($this->seteadosCamposClaves($param) && isset($param["idrol"])) {
            $objUsuarioRol = new UsuarioRol();
            // Asumo que cargarClaves es cargar(idRol, idUsuario)
            $objUsuarioRol->cargarClaves($param["idrol"], $param["id"]);
            if ($objUsuarioRol->insertar()) {
                $resp = true;
            }
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

    // ==================================================================================
    // METODO DE REGISTRO
    // ==================================================================================

    /**
     * Valida los datos y registra un nuevo usuario asignándole un rol por defecto (ID 2).
     * @param array $datos Array asociativo con 'usnombre', 'usclave', 'usmail'.
     * @return array {'resultado': bool, 'mensaje': string, 'errores_validacion': array|null}
     */
    public function registrarUsuario($datos)
    {
        $respuesta = [
            'resultado' => false,
            'mensaje' => 'Error desconocido en el registro.',
            'errores_validacion' => null
        ];

        // 1. VERIFICAR EXISTENCIA (por nombre o email)
        try {
            $existeNombre = $this->buscar(['nombre' => $datos['usnombre']]);
            if (count($existeNombre) > 0) {
                $respuesta['mensaje'] = "El nombre de usuario ya existe.";
                return $respuesta;
            }

            $existeMail = $this->buscar(['mail' => $datos['usmail']]);
            if (count($existeMail) > 0) {
                $respuesta['mensaje'] = "El email ya está registrado.";
                return $respuesta;
            }
        } catch (\Exception $e) {
            $respuesta['mensaje'] = "Error en validación o campos faltantes: " . $e->getMessage();
            return $respuesta;
        }

        // 2. PROCESO DE ALTA
        // NOTA DE SEGURIDAD: Se usa MD5 para mantener la compatibilidad con el código original, 
        // pero se recomienda usar password_hash() para mayor seguridad.
        $passHash = md5($datos['usclave']);

        $datosParaModelo = [
            'nombre' => $datos['usnombre'],
            'pass' => $passHash,
            'mail' => $datos['usmail'],
            'deshabilitado' => 'null' // Nuevo usuario no está deshabilitado
        ];

        $resultadoAlta = $this->alta($datosParaModelo);

        if ($resultadoAlta['resultado']) {
            /** @var Usuario $objUsuarioRecienCreado */
            $objUsuarioRecienCreado = $resultadoAlta['obj'];

            // 3. ASIGNACIÓN DE ROL POR DEFECTO (Asumo ID de Rol 2 = Cliente/Usuario Estándar)
            $paramRol = [
                'id' => $objUsuarioRecienCreado->getIdUsuario(),
                'idrol' => 2
            ];

            if ($this->darRol($paramRol)) {
                $respuesta['resultado'] = true;
                $respuesta['mensaje'] = "Registro exitoso. Bienvenido.";
            } else {
                $respuesta['mensaje'] = "Registro exitoso, pero la asignación de rol por defecto falló.";
            }
        } else {
            $respuesta['mensaje'] = "Error al insertar el usuario en la base de datos: " . $resultadoAlta['error'];
        }

        return $respuesta;
    }
    
    // ==================================================================================
    // METODO DE LOGIN
    // ==================================================================================

    /**
     * Verifica las credenciales del usuario, establece la sesión y devuelve el resultado.
     * @param string $usuario Nombre de usuario o email.
     * @param string $clave Contraseña sin hashear.
     * @return array {'resultado': bool, 'mensaje': string}
     */
    public function verificarUsuario($usuario, $clave)
    {
        $respuesta = ['resultado' => false, 'mensaje' => ''];

        // 1. Buscar usuario por (nombre O email) Y que NO esté deshabilitado
        $condicion = " (usnombre = '{$usuario}' OR usmail = '{$usuario}') AND usdeshabilitado IS NULL ";

        $objUsuarioModelo = new Usuario();
        $usuarios = $objUsuarioModelo->listar($condicion);

        if (count($usuarios) == 1) {
            /** @var Usuario $usuarioEncontrado */
            $usuarioEncontrado = $usuarios[0];
            $claveIngresada = $clave;
            $hashGuardado = $usuarioEncontrado->getPass();

            // 2. Verificar la contraseña (usando MD5 para compatibilidad con el registro)
            if (md5($claveIngresada) === $hashGuardado) {

                // 3. Éxito: Obtener roles y Cargar la Sesión
                $idUsuario = $usuarioEncontrado->getIdUsuario();

                $rolesUsuario = $this->buscarRoles(['id' => $idUsuario]);

                if (empty($rolesUsuario)) {
                    $respuesta['mensaje'] = "Error: El usuario existe pero no tiene roles asignados.";
                    return $respuesta;
                }
                
                // Obtenemos el primer rol
                /** @var UsuarioRol $primerUsuarioRol */
                $primerUsuarioRol = $rolesUsuario[0];
                $objRol = $primerUsuarioRol->getObjRol();

                // Cargar la Sesión 
                $session = new Session();

                $_SESSION['idusuario'] = $idUsuario;
                $_SESSION['usuario_obj'] = $usuarioEncontrado;
                $_SESSION['rol_obj'] = $objRol;

                $respuesta['resultado'] = true;
                $respuesta['mensaje'] = "Bienvenido, " . $usuarioEncontrado->getNombre();
            } else {
                $respuesta['mensaje'] = "Contraseña incorrecta.";
            }
        } else {
            $respuesta['mensaje'] = "Usuario no encontrado o deshabilitado.";
        }

        return $respuesta;
    }

    /**
     * Función vacía original.
     */
    public function verificarRegistro()
    {
        return null;
    }
}
