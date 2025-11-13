<?php 
class CompraEstadoTipo {
    private $idCompraEstadoTipo;
    private $cetDescripcion;
    private $cetDetalle;

    public function __construct($idCompraEstadoTipo = null, $cetDescripcion = "", $cetDetalle = "") {
        $this->idCompraEstadoTipo = $idCompraEstadoTipo;
        $this->cetDescripcion = $cetDescripcion;
        $this->cetDetalle = $cetDetalle;
    }

    public function getIdCompraEstadoTipo() {
        return $this->idCompraEstadoTipo;
    }

    public function getCetDescripcion() {
        return $this->cetDescripcion;
    }

    public function getCetDetalle() {
        return $this->cetDetalle;
    }

    public function setIdCompraEstadoTipo($idCompraEstadoTipo) {
        $this->idCompraEstadoTipo = $idCompraEstadoTipo;
    }

    public function setCetDescripcion($cetDescripcion) {
        $this->cetDescripcion = $cetDescripcion;
    }

    public function setCetDetalle($cetDetalle) {
        $this->cetDetalle = $cetDetalle;
    }

    public function insertar(){
        $bd = new BaseDatos;
        $sql = "INSERT INTO compraestadotipo VALUES('" . $this->getCetDescripcion() . "','" . $this->getCetDetalle() . "'";
                if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $salida = true;
            }else{
                $bd->getError();
            }
        }else{
                $bd->getError();
            }
        return $salida;
    }
    
    public function modificar(){
        $salida = false;
        $db = new BaseDatos;
        $sql = "UPDATE compraestadotipo SET
        cetdescripcion = '{$this->getCetDescripcion()}' ,
        cetdetalle = '{$this->getCetDetalle()}'
        WHERE idcompraestadotipo = '{$this->getIdCompraEstadoTipo()}' " ; 
        if($db->Iniciar()){
            if($db->Ejecutar($sql)){
                $salida = true;
            }else{
                $db->getError();
            }
        }else{
                $db->getError();
            }
        return $salida;
    }
}

?>