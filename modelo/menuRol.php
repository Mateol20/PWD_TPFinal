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
        $idMenu = $this->getObjMenu()->getIdMenu();
        $idRol = $this->getObjRol()->getIdRol();
        $sql = "INSERT INTO menurol (idmenu, idrol)
                VALUES ('{$idMenu}','{$idRol}')";

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

        // Una tabla de unión suele tener una clave compuesta (idmenu Y idrol)
        // La eliminación por solo idmenu eliminaría TODOS los roles de ese menú.
        // Se corrige para eliminar UNA ÚNICA relación.
        $sql = "DELETE FROM menurol 
                WHERE idmenu = '{$this->getObjMenu()->getIdMenu()}' 
                AND idrol = '{$this->getObjRol()->getIdRol()}'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("menuRol->eliminar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("menuRol->eliminar: " . $bd->getError());
        }

        return $respuesta;
    }

    public function modificar()
    {
        // CORRECCIÓN 3: Se remueve la lógica de NULL innecesaria.
        // El método se deja vacío o se elimina, ya que Modificar una tabla de unión es atípico.
        $this->setMensajeError("menuRol->modificar: No se admite la modificación directa en la tabla de unión.");
        return false;
    }

    public function obtenerPorId() 
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "SELECT * FROM menurol 
                WHERE idmenu = '" . $this->getObjMenu()->getIdMenu() . "' 
                AND idrol = '" . $this->getObjRol()->getIdRol() . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                if ($registro = $bd->Registro()) {
                    $objM = new Menu();
                    $objR = new Rol();

                    $objM->setIdMenu($registro['idmenu']);
                    $objR->setIdRol($registro['idrol']);

                    $this->setObjMenu($objM);
                    $this->setObjRol($objR);
                    $respuesta = true;
                }
            } else {
                $this->setMensajeError("menuRol->obtenerPorId: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("menuRol->obtenerPorId: " . $bd->getError());
        }
        return $respuesta;
    }

    public static function listar($condicion = "")
    {
        $arregloMenuRol = [];
        $bd = new BaseDatos();
        $sql = "SELECT * FROM menurol ";

        if ($condicion != "") {
            $sql .= "WHERE " . $condicion;
        }

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                while ($registro = $bd->Registro()) {
                    $menuRol = new menuRol();

                    // CREAR INSTANCIAS DE LOS OBJETOS RELACIONADOS
                    $objM = new Menu();
                    $objR = new Rol();

                    // SÓLO SETEAR EL ID (La carga completa se hace fuera o en otro método)
                    $objM->setIdMenu($registro['idmenu']);
                    $objR->setIdRol($registro['idrol']);

                    // Cargar el objeto menuRol
                    $menuRol->setear($objM, $objR);

                    array_push($arregloMenuRol, $menuRol);
                }
            }
        }

        return $arregloMenuRol;
    }
}
