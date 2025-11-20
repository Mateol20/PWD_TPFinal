<?php
class ABMProducto
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
        if (isset($datos['pronombre']) && isset($datos['prodetalle']) && isset($datos['procantstock'])) {
            $obj = new Producto();
            $obj->setear(
                $datos['pronombre'],
                $datos['prodetalle'],
                $datos['procantstock']
            );

            // Si es modificación, setea el ID
            if (isset($datos['idproducto']) && $datos['idproducto'] != '') {
                $obj->setIdProducto($datos['idproducto']);
            }
        }
        return $obj;
    }
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Rol|null
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;

        if (isset($param['id']) || isset($param['idproducto'])) {
            $id = $param['id'] ?? $param['idproducto'];
            $obj = new Producto();
            $obj->setIdProducto($id);
            if ($obj->obtenerPorId() !== false) {
                return $obj;
            }
        }
        return null;
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
                if (is_numeric($valor)) {
                    $where .= " and " . $clave . "=" . $valor;
                } else {
                    $where .= " and " . $clave . "='" . $valor . "'";
                }
            }
        }
        $arreglo = Producto::listar($where);
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
        $objProducto = $this->cargarObjeto($datos);

        if ($objProducto != null) {
            if ($objProducto->insertar()) {
                $res = true;
            } else {
                $this->mensajeError = "ABMproducto->alta: Error de inserción: " . $objProducto->getMensajeError();
            }
        } else {
            $this->mensajeError = "ABMproducto->alta: Falló la carga del objeto con los datos proporcionados (datos insuficientes o incorrectos).";
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
        // CORRECCIÓN CLAVE: Usar 'idproducto' y verificar su existencia.
        if (array_key_exists('idproducto', $datos)) {
            $objProducto = new Producto();
            $objProducto->setIdProducto($datos['idproducto']);

            // Aquí se asume que obtenerPorId() funciona correctamente
            if ($objProducto->obtenerPorId() !== false) {
                if ($objProducto->eliminar()) { // Se asume que Producto tiene el método eliminar()
                    $res = true;
                } else {
                    $this->mensajeError = "ABMprodu->baja: " . $objProducto->getMensajeError();
                }
            } else {
                $this->mensajeError = "ABMprodu->baja: El producto con ID {$datos['idproducto']} no existe.";
            }
        } else {
            $this->mensajeError = "ABMprodu->baja: Faltan datos para la eliminación ('idproducto').";
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
        $objProducto = $this->cargarObjeto($datos);

        if ($objProducto != null && $objProducto->modificar()) {
            $res = true;
        } else {
            $this->mensajeError = "ABMprodu->modificacion: " . $objProducto->getMensajeError();
        }
        return $res;
    }

    public function actualizarStock()
    {
        if (!isset($_SESSION['carrito'])) return;

        $solicitados = $_SESSION['carrito'];

        foreach ($solicitados as $idAuto) {
            // buscar producto usando array asociativo
            $res = $this->buscar(['idproducto' => $idAuto]); // usar el nombre de columna correcto
            if (!empty($res)) {
                $objProducto = $res[0];

                // Reducir stock usando los getters y setters correctos
                $stockActual = $objProducto->getProCantStock(); // nombre correcto
                $nuevoStock = $stockActual - 1;
                $objProducto->setProCantStock($nuevoStock);

                $objProducto->modificar();
            }
        }
    }



    public function obtenerPorId($id)
    {
        $salida = false;
        $obj = new Producto;
        $obj->setIdProducto($id);
        if ($resultado = $obj->obtenerPorId()) {
            $salida = $resultado;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }
}
