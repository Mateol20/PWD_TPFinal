<?php
class CompraEstado{
    private $idCompraEstado;
    private $idCompra;
    private $idCompraEstadoTipo;
    private $fechaIni;
    private $fechaFin;

    public function __construct (){
        $this->fechaIni = 'CURRENT_TIMESTAMP';
        $this->fechaFin = NULL;
    }

    public function getIdCompraEstado()
    {
        return $this->idCompraEstado;
    }

    public function getIdCompra()
    {
        return $this->idCompra;
    }

    public function getIdCompraEstadoTipo()
    {
        return $this->idCompraEstadoTipo;
    }

    public function getFechaIni()
    {
        return $this->fechaIni;
    }

    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    public function setIdCompraEstado($idCompraEstado)
    {
        $this->idCompraEstado = $idCompraEstado;
        return $this;
    }

    public function setIdCompra($idCompra)
    {
        $this->idCompra = $idCompra;
        return $this;
    }

    public function setIdCompraEstadoTipo($idCompraEstadoTipo)
    {
        $this->idCompraEstadoTipo = $idCompraEstadoTipo;
        return $this;
    }

    public function setFechaIni($fechaIn)
    {
        $this->fechaIni = $fechaIn;
        return $this;
    }

    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
        return $this;
    }
    public function setear($idCompraEstado,$idCompra,$idCompraEstadoTipo,$fechaI,$fechaF){
        $this->setIdCompraEstado($idCompraEstado);
        $this->setIdCompra($idCompra);
        $this->setIdCompraEstadoTipo($idCompraEstadoTipo);
        $this->setFechaIni($fechaI);
        $this->setFechaFin($fechaF);
    }
    public function insertar(){
        $salida = false;
        $db = new BaseDatos;
        $sql = "INSERT INTO compraestado (idcompra,idcompraestadotipo,fechaini,fechafin) VALUES
        ('" . $this->getIdCompra() . "';'" . $this->getIdCompraEstado() . "';'" . $this->getFechaIni() . "';'" . $this->getFechaFin() . "'";
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
    public function modificar(){
        $salida = false;
        $db = new BaseDatos;
        $sql = "UPDATE compraestado SET 
        (idcompra = '{$this->getIdCompra()}' , idcompraestadotipo = '{$this->getIdCompraEstadoTipo()}' , idcompra = '{$this->getIdCompra()}' , 
        idfechaini = '{$this->getFechaIni()}' , fechafin = '{$this->getFechaFin()}'";
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
    public function eliminar(){
        $salida = false;
        $db = new BaseDatos;
        $sql = "DELETE FROM compraestado WHERE idcompraestado='" . $this->getIdCompraEstado() ."'";
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