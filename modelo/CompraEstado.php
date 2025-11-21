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
    public function setear($idCompra,$idCompraEstadoTipo){
        $this->setIdCompra($idCompra);
        $this->setIdCompraEstadoTipo($idCompraEstadoTipo);

    }
    public function insertar(){
        $salida = false;
        $bd = new BaseDatos;
        $sql = "INSERT INTO compraestado (idcompra, idcompraestadotipo, cefechaIni, cefechaFin)
        VALUES (" . $this->getIdCompra() . ",
                " . $this->getIdCompraEstadoTipo() . ",
                '" . date('Y-m-d H:i:s') . "',
                NULL)";
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $salida = $bd->getLastId();;
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
        idcompraestadotipo = '" . $this->getIdCompraEstadoTipo() . "' , cefechafin = '". date('Y-m-d H:i:s') .
        "' WHERE idcompraestado = '" . $this->getIdCompraEstado() . "'";
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $salida = true;
            }else{
                echo $bd->getError();
            }
        }else{
                echo $bd->getError();
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
         $salida = false;
        $bd = new BaseDatos;
        $sql = "SELECT * FROM compraestado WHERE idcompraestado =" . $this->getIdCompraEstado();
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $linea = $bd->Registro();
                $obj = new CompraEstado;
                $obj -> setIdCompraEstado($linea['idcompraestado']);
                $obj -> setIdCompra($linea['idcompra']);
                $obj -> setIdCompraEstadoTipo($linea['idcompraestadotipo']);
                $obj -> setFechaIni($linea['cefechaini']);
                $obj -> setFechaFin($linea['cefechafin']);
                $salida = $obj;
            }else{
                $bd->getError();
            }
        }else{
                $bd->getError();
            }
        return $salida;
    }
    public function obtenerPorIdCompra(){
         $salida = false;
        $bd = new BaseDatos;
        $sql = "SELECT * FROM compraestado WHERE idcompra =" . $this->getIdCompra();
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $linea = $bd->Registro();
                $obj = new CompraEstado;
                $obj -> setIdCompraEstado($linea['idcompraestado']);
                $obj -> setIdCompra($linea['idcompra']);
                $obj -> setIdCompraEstadoTipo($linea['idcompraestadotipo']);
                $obj -> setFechaIni($linea['cefechaini']);
                $obj -> setFechaFin($linea['cefechafin']);
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
                while($linea = $bd->Registro()){
                    $obj = new CompraEstado;
                $obj -> setIdCompraEstado($linea['idcompraestado']);
                $obj -> setIdCompra($linea['idcompra']);
                $obj -> setIdCompraEstadoTipo($linea['idcompraestado']);
                $obj -> setFechaIni($linea['cefechaini']);
                $obj -> setFechaFin($linea['cefechafin']);
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

    public function obtenerUltimoId() {
        $bd = new BaseDatos;
        $sql = "SELECT MAX(idcompraestado) AS ultimo FROM compraestado";
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $data = $bd->Registro();
                $ultimoId = $data['ultimo'];
            }
        }
        return $ultimoId;
    }

        public function cancelarCompra(){
        $salida = false;
        $bd = new BaseDatos;
       $sql = "UPDATE compraestado SET idcompraestadotipo = 4 WHERE idcompra = " . $this->getIdCompra();

        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $salida = true;
            }else{
                echo $bd->getError();
            }
        }else{
                echo $bd->getError();
            }
        return $salida;
    }
}