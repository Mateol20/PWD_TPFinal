<?php
class Menu{
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $idpadre;
    private $medeshabilitado;
    private $mensajeError;

    public function __construct()
    {
        $this->idmenu = "";
        $this->menombre = "";
        $this-> medescripcion = "";
        $this-> idpadre = "";
        $this->medeshabilitado = "";
    }
    //GETTERS
    public function getIdMenu(){
        return $this->idmenu;
    }
    public function getMeDescripcion()
    {
        return $this->medescripcion;
    }
    public function getMeNombre()
    {
        return $this->menombre;
    }
    public function getIdpadre()
    {
        return $this->idpadre;
    }
    public function getMensajeError()
    {
        return $this->mensajeError;
    }
    public function getMeDeshabilitado()
    {
        return $this->medeshabilitado;
    }
    //SETTERS
    public function setIdmenu($idmenu)
    {
        $this->idmenu=$idmenu;
    }
    public function setMedescripcion($medescripcion)
    {
        $this->medescripcion=$medescripcion;
    }
    public function setMenombre($menombre)
    {
        $this->menombre=$menombre;
    }
    public function setIdpadre($idpadre)
    {
        $this->idpadre=$idpadre;
    }
    public function setMedeshabilitado($medeshabilitado)
    {
        $this->medeshabilitado=$medeshabilitado;
    }
    public function setMensajeError($mensajeError)
    {
        $this->mensajeError = $mensajeError;
    }
    public function insertar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "INSERT INTO menu ('idmenu','menombre','medescripcion','idpadre','medeshabilitado') 
                VALUES idmenu ='".$this->getIdMenu()."', menombre = ".$this->getMeNombre().", 
                medescripcion = '".$this->getMeDescripcion()."', idpadre ='".$this->getIdpadre(). "', medeshabilitado = ".$this->getMeDeshabilitado()."' "; 
            if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("menu->insertar: ".$bd->getError());
            }
        } else {
            $this->setMensajeError("menu->insertar: ".$bd->getError());
        }
        
        return $respuesta;
    }
    public function eliminar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "DELETE FROM menu WHERE idmenu = '". $this->getIdMenu()."'"; 
                    if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("menu->eliminar: ".$bd->getError());
            }
        } else {
            $this->setMensajeError("menu->eliminar: ".$bd->getError());
        }
        
        return $respuesta;
    }
    public function modificar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "DELETE FROM menu WHERE idmenu = '".$this->getIdMenu()."'";
                    if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("menu->eliminar: ".$bd->getError());
            }
        } else {
            $this->setMensajeError("menu->eliminar: ".$bd->getError());
        }
        
        return $respuesta;
    }
        }

