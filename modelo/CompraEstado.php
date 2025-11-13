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
    public function setear($idCompra,$idCompraEstadoTipo,$fechaI,$fechaF){
        $this->setIdCompra($idCompra);
        $this->setIdCompraEstadoTipo($idCompraEstadoTipo);
        $this->setFechaIni($fechaI);
        $this->setFechaFin($fechaF);
    }
    public function insertar(){
        $salida = false;
        $bd = new BaseDatos;
        $sql = "INSERT INTO compraestado (idcompra,idcompraestadotipo,fechaini,fechafin) VALUES
        ('" . $this->getIdCompra() . "';'" . $this->getIdCompraEstado() . "';'" . $this->getFechaIni() . "';'" . $this->getFechaFin() . "'";
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
        $bd = new BaseDatos;
        $sql = "UPDATE compraestado SET 
        (idcompra = '{$this->getIdCompra()}' , idcompraestadotipo = '{$this->getIdCompraEstadoTipo()}' , idcompra = '{$this->getIdCompra()}' , 
        idfechaini = '{$this->getFechaIni()}' , fechafin = '{$this->getFechaFin()}'";
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
    public function eliminar(){
        $salida = false;
        $bd = new BaseDatos;
        $sql = "DELETE FROM compraestado WHERE idcompraestado='" . $this->getIdCompraEstado() ."'";
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

    public function obtenerPorId(){
        $bd = new BaseDatos;
        $sql = "SELECT * FROM compraestado WHERE idcompraestado =" . $this->getIdCompraEstado();
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $linea = $bd->Registro();
                $obj = new CompraEstado;
                $obj->setear(
                $linea['idcompra'],
                $linea['idcompraestado'],
                $linea['fechaini'],
                $linea['fechafin']);
                $salida = $obj;
            }else{
                $bd->getError();
            }
        }else{
                $bd->getError();
            }
        return $salida;
    }

     /**
     * Obtiene una colección (array) de objetos Menu que cumplen una condición 
     * o todos los registros si no se especifica condición.
     * * @param string
     * @return array 
     */
    public function listar ($where = ""){
        $bd = new BaseDatos;
        $sql = "SELECT * FROM compraestado";
        if ($where != "") {
            $sql .= "WHERE ". $where;
        }
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $arreglo = [];
                $obj = new CompraEstado;
                foreach($bd->Registro() as $row){
                    $obj->setear(
                    $row['idcompra'],
                    $row['idcompraestado'],
                    $row['fechaini'],
                    $row['fechafin']);
                    array_push($arreglo,$obj);
                }
            $salida = $arreglo;
            }else{
                $bd->getError();
            }
        }else{
                $bd->getError();
            }
        return $salida;
    }


}