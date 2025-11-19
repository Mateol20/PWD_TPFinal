<?php

class ABMRol
{
    /**
     * Carga un objeto Rol a partir de los parámetros recibidos.
     * @param array $param
     * @return Rol|null
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (array_key_exists('idrol', $param) && array_key_exists('roldescripcion', $param)) {


            $obj = new Rol();
            $obj->cargar($param['idrol'], $param['roldescripcion']);
        }
        return $obj;
    }

    /**
     * Busca y carga un objeto Rol si la clave 'idrol' está presente.
     * @param array $param
     * @return Rol|null
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idrol'])) {
            $obj = new Rol();
            if ($obj->buscar($param["idrol"])) {
                return $obj;
            }
        }
        return null;
    }

    /**
     * Verifica si el campo clave (idrol) está seteado.
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idrol']))
            $resp = true;
        return $resp;
    }

    // ==================================================================================
    // METODOS CRUD PUBLICOS
    // ==================================================================================

    /**
     * Permite dar de alta un objeto Rol.
     * @param array $param Array asociativo con 'rodescripcion'.
     * @return array {'resultado': bool, 'error': string, 'obj': Rol|null}
     */
    public function alta($param)
    {
        $resp = false;
        $param['idrol'] = null;
        $objUsuario = $this->cargarObjeto($param);
        if ($objUsuario != null and $objUsuario->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Permite eliminar un objeto Rol por su ID.
     * @param array $param Array asociativo con 'idrol'.
     * @return array {'resultado': bool, 'error': string}
     */
    public function baja($param)
    {
        $resp = array('resultado' => false, 'error' => '');
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjetoConClave($param);

            if ($elObjtTabla != null and $elObjtTabla->eliminar()) {
                $resp['resultado'] = true;
            } else {
                $resp['error'] = "Error al eliminar el Rol: " . $elObjtTabla->getMensajeError();
            }
        } else {
            $resp['error'] = "Falta el ID clave para la baja.";
        }
        return $resp;
    }

    /**
     * Permite modificar un objeto Rol.
     * @param array $param Array asociativo con 'idrol' y 'rodescripcion'.
     * @return array {'resultado': bool, 'error': string}
     */
    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objUsuario = $this->cargarObjeto($param);
            if ($objUsuario != null and $objUsuario->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }


    /**
     * Permite buscar objetos Rol.
     * @param array $param Array asociativo con posibles campos de búsqueda.
     * @return array Array de objetos Rol encontrados.
     */
    public function buscar($param)
    {
        $where = " true ";

        if ($param <> null) {
            if (isset($param['idrol'])) {
                $where .= " and idrol = " . $param['idrol'];
            }
            if (isset($param['rodescripcion'])) {
                // Se usa LIKE para búsqueda parcial o = para exacta, dependiendo de la necesidad.
                $where .= " and rodescripcion = '" . $param['rodescripcion'] . "'";
            }
        }

        $arreglo = Rol::listar($where);
        return $arreglo;
    }
}
