<?php
class menuRol
{
    private $objMenu;
    private $objRol;
    private $mensajeError;
    public function __construct()
    {
        $this->objMenu = "";
        $this->objRol = "";
    }
    //GETTERS
    public function getObjMenu()
    {
        return $this->objMenu;
    }
    public function getObjRol()
    {
        return $this->objRol;
    }
    public function getMensajeError()
    {
        return $this->mensajeError;
    }
    //SETTERS
    public function setObjMenu($objMenu)
    {
        $this->objMenu = $objMenu;
    }
    public function setObjRol($objRol)
    {
        $this->objRol = $objRol;
    }
    public function setMensajeError($mensajeError)
    {
        $this->mensajeError = $mensajeError;
    }
    // --- Métodos de Persistencia
    public function setear($objMenu, $objRol)
    {
        $this->setObjMenu($objMenu);
        $this->setObjRol($objRol);
    }
    public function insertar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "INSERT INTO menursol (idmenu, idrol)
                VALUES ('{$this->getObjMenu()->getIdMenu()}','{$this->getObjRol->getIdRol()}')";
        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("menuRol->insertar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("menuRol->insertar: " . $bd->getError());
        }

        return $respuesta;
    }
    public function eliminar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "DELETE FROM menurol WHERE idmenu = '{$this->getObjMenu()->getIdMenu()}'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("menu->eliminar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("menu->eliminar: " . $bd->getError());
        }

        return $respuesta;
    }
    public function modificar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $idMenuSQL = $this->getObjMenu() === null ? 'NULL' : $this->getObjMenu();
        $idRolSQL = $this->getObjRol() === null ? 'NULL' : "'" . $this->getObjRol() . "'";
        $sql = "UPDATE menurol SET 
            idmenu = '" . $this->getObjMenu()->getIdMenu() . "', 
            idrol = '" . $this->getObjRol()->getIdRol() . "', 
        WHERE idmenu = '" . $this->getObjMenu()->getIdMenu() . "'";
        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("menu->modificar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("menu->modificar: " . $bd->getError());
        }

        return $respuesta;
    }
        /**
     * Busca un registro de menú en la base de datos por su ID y carga 
     * sus datos en las propiedades del objeto actual.
     * @return boolean 
     */
    public function obtenerPorId()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "SELECT * FROM menurol WHERE idmenu = '" . $this->getObjMenu->getIdMenu() . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                if ($registro = $bd->Registro()) {
                    $this->setObjMenu->setIdMenu($registro['idmenu']);
                    $this->setObjRol->setIdRol($registro['idrol']);
                    $respuesta = true;
                }
            } else {
                $this->setMensajeError("menu->obtenerPorId: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("menu->obtenerPorId: " . $bd->getError());
        }
        return $respuesta;
    }
 /**
     * Obtiene una colección (array) de objetos Menu que cumplen una condición 
     * o todos los registros si no se especifica condición.
     * * @param string
     * @return array 
     */
    public static function listar($condicion = "")
    {
        $arregloMenu = [];
        $bd = new BaseDatos();
        $sql = "SELECT * FROM menurol ";

        if ($condicion != "") {
            $sql .= "WHERE " . $condicion;
        }

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {

                while ($registro = $bd->Registro()) {
                    $menu = new menuRol();
                    $menu->setObjMenu->setIdMenu($registro['idmenu']);
                    $menu->setObjRol->setIdRol($registro['idrol']);
                    array_push($arregloMenu, $menu);
                }
            } else {
                // Manejo de error si la ejecución falla
                // Nota: En un método estático, el error debería manejarse de otra forma
                // Ej: Loggear o lanzar una excepción, ya que no podemos usar $this->setMensajeError.
                // Para simplicidad, aquí solo imprimiremos:
                // echo "Error al listar: " . $bd->getError(); 
            }
        } else {
            // Manejo de error si la conexión falla
            // echo "Error de conexión: " . $bd->getError();
        }

        return $arregloMenu;
    }
}
