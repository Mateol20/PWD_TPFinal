<?php
// NOTA: Se asume que las clases Usuario, Rol y UsuarioRol están incluidas
// y que la tabla usuariorol usa una clave compuesta (idusuario, idrol).

class ABMUsuarioRol
{
    private $mensajeError;

    public function __construct()
    {
        $this->mensajeError = "";
    }

    // GETTER
    public function getMensajeError()
    {
        return $this->mensajeError;
    }

    // ==========================================================
    // MÉTODOS INTERNOS
    // ==========================================================

    /**
     * Carga un objeto UsuarioRol a partir de un arreglo de IDs.
     * Se encarga de BUSCAR y cargar los objetos Usuario y Rol completos desde la DB.
     * @param array $param - Debe contener 'idusuario' y 'idrol'.
     * @return UsuarioRol|null
     */
    private function cargarObjeto($param)
    {
        $objUsuarioRol = null;

        // Verificación de claves para la clave compuesta
        if (!isset($param['idusuario']) || !isset($param['idrol'])) {
            $this->mensajeError = "Faltan los IDs de usuario o rol para cargar la relación.";
            return null;
        }

        $idUsuario = (int)$param['idusuario'];
        $idRol = (int)$param['idrol'];


        // 1. Instanciar y BUSCAR el objeto Usuario completo
        $objUsuario = new Usuario();
        if (!$objUsuario->buscar($idUsuario)) {
            $this->mensajeError = "No se pudo encontrar y cargar el objeto Usuario con ID: " . $idUsuario;
            return null;
        }

        // 2. Instanciar y BUSCAR el objeto Rol completo
        $objRol = new Rol();
        if (!$objRol->buscar($idRol)) {
            $this->mensajeError = "No se pudo encontrar y cargar el objeto Rol con ID: " . $idRol;
            return null;
        }

        // 3. Crear el objeto UsuarioRol y setear los objetos completos
        $objUsuarioRol = new UsuarioRol();
        // Llamada a setear con solo dos objetos
        $objUsuarioRol->setear($objUsuario, $objRol);

        return $objUsuarioRol;
    }

    // ==========================================================
    // MÉTODOS PÚBLICOS DEL ABM
    // ==========================================================

    /**
     * Permite crear una nueva relación entre Usuario y Rol.
     * @param array $datos - Debe contener 'idusuario' y 'idrol'.
     * @return array ['resultado' => bool, 'error' => string|null]
     */
    public function alta($datos)
    {
        $resp = ['resultado' => false, 'error' => null];
        $this->mensajeError = "";

        $objUsuarioRol = $this->cargarObjeto($datos);

        if ($objUsuarioRol !== null) {
            if ($objUsuarioRol->insertar()) {
                $resp['resultado'] = true;
            } else {
                $errorMsg = method_exists($objUsuarioRol, 'getMensajeError') ? $objUsuarioRol->getMensajeError() : "Error desconocido.";
                $resp['error'] = "Error al intentar registrar la relación UsuarioRol. " . $errorMsg;
            }
        } else {
            $resp['error'] = $this->mensajeError;
        }
        return $resp;
    }
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idusuario']) && isset($param['idrol'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Permite eliminar un rol asignado a un usuario (una relación) usando la clave compuesta.
     * @param array $param - Debe contener 'idusuario' y 'idrol'.
     * @return array ['resultado' => bool, 'error' => string|null]
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjeto($param);
            if ($elObjtTabla != null && $elObjtTabla->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite buscar relaciones de usuario y rol.
     * @param array $param - Puede contener 'idusuario' y/o 'idrol'.
     * @return array de objetos UsuarioRol
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param != NULL) {
            if (isset($param['idusuario'])) {
                $where .= " and idusuario =" . $param['idusuario'];
            }
            if (isset($param['idrol'])) {
                $where .= " and idrol =" . $param['idrol'];
            }
        }
        $arreglo = UsuarioRol::listar($where);
        return $arreglo;
    }

    /**
     * La modificación directa no es posible en una tabla de clave compuesta.
     * La "modificación" se realiza mediante una Baja seguida de un Alta si cambian los IDs.
     */
    public function modificacion($param)
    {
        $resp = ['resultado' => false, 'error' => "La modificación directa no está implementada. Use Baja y Alta si necesita cambiar una clave (idrol)."];
        return $resp;
    }
}
