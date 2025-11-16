<?php
class ABMUsuarioRol
{
    private $objRol;
    private $objUsuario;
    private $mensajeError;
    public function __construct()
    {
        $this->objRol = new Rol();
        $this->objUsuario = new Usuario();
        $this->mensajeError = "";
    }
    // GETTERS
    public function getObjRol()
    {
        return $this->objRol;
    }
    public function getObjUsuario()
    {
        return $this->objUsuario;
    }
    public function getMensajeError()
    {
        return $this->mensajeError;
    }
    //SETTERS
    public function setObjRol($objRol)
    {
        $this->objRol = $objRol;
    }
    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
    }

    // METODOS ESTATICOS

    /**
     * Carga un objeto UsuarioRol desde un arreglo asociativo.
     * @param array $datos
     * @return UsuarioRol|null
     */
    private function cargarObjeto($param)
    {
        $objUsuarioRol = null;
        $objRol = null;
        $objUsuario = null;

        if (array_key_exists('idusuario', $param) && array_key_exists('idrol', $param)) {
            $objUsuario = new Usuario();
            $objUsuario->setIdUsuario($param['idusuario']);
            $objUsuario->cargar($param['idusuario'], $param['usnombre'], $param['uspass'], $param['usmail']);

            $objRol = new Rol();
            $objRol->setId($param['idrol']);
            $objRol->cargar($param['idrol'], $param['rodescripcion']);

            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->setear($objUsuario, $objRol);
        }
        return $objUsuarioRol;
    }
    /**
     * Permite crear una nueva relación entre Usuario y Rol.
     * @param array $datos
     * @return boolean
     */
    public function alta($datos)
    {
        $resp = false;
        $objUsuarioRol = $this->cargarObjeto($datos);
        if ($objUsuarioRol != null && $objUsuarioRol->insertar()) {
            $resp = true;
        }
        return $resp;
    }
    /**
     * Permite eliminar un rol asignado a un usuario.
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objUsuarioRol = $this->cargarObjetoConClave($param);
            if ($objUsuarioRol != null && $objUsuarioRol->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }
    /**
     * Permite modificar la relación entre usuario y rol.
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objUsuarioRol = $this->cargarObjeto($param);
            if ($objUsuarioRol != null && $objUsuarioRol->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite buscar una relación de usuario y rol.
     * @param array $param
     * @return array
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idusuario']))
                $where .= " and idusuario =" . $param['idusuario'];
            if (isset($param['idrol']))
                $where .= " and idrol =" . $param['idrol'];
        }
        $obj = new UsuarioRol();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }
}
