<?php
class ABMMenu
{
    private $mensajeError;

    public function __construct()
    {
        $this->mensajeError = "";
    }

    public function getMensajeError()
    {
        return $this->mensajeError;
    }

    /**
     * Espera un array de parámetros (ej: $_POST) y crea la instancia de Menu.
     * @param array $datos
     * @return Menu
     */
    private function cargarObjeto($datos)
    {
        $obj = null;

        if (isset($datos['menombre'])) {

            $obj = new Menu();

            $idMenu = $datos['idmenu'] ?? null;
            $idPadre = $datos['idpadre'] ?? null;

            // Construir objeto padre
            $objPadre = null;
            if (!empty($idPadre)) {
                $objPadre = new Menu();
                $objPadre->setIdmenu($idPadre);
                $objPadre->cargar();
            }

            $meDeshabilitado = $datos['medeshabilitado'] ?? null;
            if ($meDeshabilitado === "") $meDeshabilitado = null;

            $obj->setear(
                $idMenu,
                $datos['menombre'] ?? '',
                $datos['medescripcion'] ?? '',
                $objPadre,               // 👈 AHORA SÍ ES UN OBJETO
                $meDeshabilitado
            );
        }

        return $obj;
    }

    /**
     * Espera un array de búsqueda y devuelve una colección de objetos Menu.
     * @param array $param
     * @return array
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param != NULL) {
            if (isset($param['idmenu']))
                $where .= " and idmenu =" . $param['idmenu'];
            if (isset($param['menombre']))
                $where .= " and menombre ='" . $param['menombre'] . "'";
            if (isset($param['medescripcion']))
                $where .= " and medescripcion ='" . $param['medescripcion'] . "'";
            if (isset($param['idpadre']))
                $where .= " and idpadre =" . $param['idpadre'];
            if (isset($param['medeshabilitado']) && $param['medeshabilitado'] !== null)
                $where .= " and medeshabilitado ='" . $param['medeshabilitado'] . "'";
            if (isset($param['medeshabilitado']) && $param['medeshabilitado'] === null)
                $where .= " and medeshabilitado is null";
        }

        $arreglo = Menu::listar($where);
        return $arreglo;
    }
    

    // --- OPERACIONES CRUD PRINCIPALES ---

    /**
     * Inserta un objeto Menu.
     * @param array $datos
     * @return boolean
     */
    public function alta($datos)
    {
        $res = false;
        $objMenu = $this->cargarObjeto($datos);

        if ($objMenu != null) {
            if ($objMenu->insertar()) {
                $res = true;
            } else {
                $this->mensajeError = "ABMMenu->alta: Error de inserción: " . $objMenu->getMensajeError();
            }
        } else {
            $this->mensajeError = "ABMMenu->alta: Falló la carga del objeto con los datos proporcionados (datos insuficientes o incorrectos).";
        }
        return $res;
    }

    /**
     * Elimina un objeto Menu.
     * @param array $datos (Debe contener al menos 'idmenu')
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if (isset($param['idmenu'])) {
            $objMenu = new Menu();
            $objMenu->setIdmenu($param['idmenu']);
            if ($objMenu->cargar()) {
                $fechaBaja = date('Y-m-d H:i:s');
                $objMenu->setMeDeshabilitado($fechaBaja);
                if ($objMenu->modificar()) {
                    $resp = true;
                }
            }
        }

        return $resp;
    }

    /**
     * Modifica un objeto Menu.
     * @param array $datos
     * @return boolean
     */
    public function modificacion($param)
    {
        $resp = false;
        // ... (otras validaciones) ...

        $elObjtTabla = $this->cargarObjeto($param);

        // 💡 Asegúrate de que estás tomando el ID del parámetro correcto
        $id = $param['idmenu'] ?? $param['id'] ?? null;

        if ($id !== null) {
            $elObjtTabla->setIdMenu($id); // Setea el ID correcto
        }

        if ($elObjtTabla != null and $elObjtTabla->modificar()) { // Llama a Menu::modificar()
            $resp = true;
        }
        return $resp;
    }
}
