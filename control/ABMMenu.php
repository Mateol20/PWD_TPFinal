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
        if (array_key_exists('idmenu', $datos) && array_key_exists('menombre', $datos)) {
            $obj = new Menu();
            $idMenu = $datos['idmenu'] ?? null;
            $idPadre = $datos['idpadre'] ?? null;
            $meDeshabilitado = $datos['medeshabilitado'] ?? null;
            // Si idPadre viene como cadena vacía desde el formulario, lo convertimos a NULL
            if ($idPadre === "") {
                $idPadre = null;
            }
            // Si meDeshabilitado viene como cadena vacía, lo convertimos a NULL
            if ($meDeshabilitado === "") {
                $meDeshabilitado = null;
            }
            $obj->setear(
                $idMenu,
                $datos['menombre'] ?? '',
                $datos['medescripcion'] ?? '',
                $idPadre,
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
        if ($param != null) {
            foreach ($param as $clave => $valor) {
                // Si la clave no es numérica, la tratamos como un campo
                if (is_numeric($valor)) {
                    $where .= " and " . $clave . "=" . $valor;
                } else {
                    $where .= " and " . $clave . "='" . $valor . "'";
                }
            }
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
    public function baja($datos)
    {
        $res = false;
        if (array_key_exists('idmenu', $datos)) {
            $objMenu = new Menu();
            $objMenu->setIdMenu($datos['idmenu']);

            // Intenta leer el objeto primero para asegurar que existe
            if ($objMenu->obtenerPorId()) {
                if ($objMenu->eliminar()) {
                    $res = true;
                } else {
                    $this->mensajeError = "ABMMenu->baja: " . $objMenu->getMensajeError();
                }
            } else {
                $this->mensajeError = "ABMMenu->baja: El objeto a eliminar no existe.";
            }
        }
        return $res;
    }

    /**
     * Modifica un objeto Menu.
     * @param array $datos
     * @return boolean
     */
    public function modificacion($datos)
    {
        $res = false;
        $objMenu = $this->cargarObjeto($datos);

        if ($objMenu != null && $objMenu->modificar()) {
            $res = true;
        } else {
            $this->mensajeError = "ABMMenu->modificacion: " . $objMenu->getMensajeError();
        }
        return $res;
    }
}
