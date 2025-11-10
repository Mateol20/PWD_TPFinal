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
        $sql = "INSERT INTO menuRol (idmenu, idrol)
                VALUES ('{$this->getObjMenu()}','{$this->getObjRol()}')";
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
        $sql = "DELETE FROM menuRol WHERE idmenu = '{$this->getObjMenu()}'";

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
        $sql = "UPDATE menu SET 
            idmenu = '" . $this->getObjMenu() . "', 
            idrol = '" . $this->getObjRol() . "', 
        WHERE idmenu = '" . $this->getObjMenu() . "'";
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
}
