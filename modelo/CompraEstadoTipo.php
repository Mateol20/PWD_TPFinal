<?php 
class CompraEstadoTipo {
    private $idCompraEstadoTipo;
    private $cetDescripcion;
    private $cetDetalle;

    public function __construct() {
        $this->cetDescripcion = '';
        $this->cetDetalle = '';
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

    public function setear($id,$desc,$detalle){
        $this->setIdCompraEstadoTipo($id);
        $this->setCetDescripcion($desc);
        $this->setCetDetalle($detalle);
    }

    public function insertar(){
        $bd = new BaseDatos;
<<<<<<< HEAD
        $sql = "INSERT INTO compraestadotipo (cetdescripcion, cetdetalle) VALUES('" . $this->getCetDescripcion() . "','" . $this->getCetDetalle() . "')";
=======
        $sql = "INSERT INTO compraestadotipo (idcompraestadotipo, cetdescripcion, cetdetalle) VALUES('" . $this->getIdCompraEstadoTipo() . "','"  . $this->getCetDescripcion() . "','" . $this->getCetDetalle() . "')";
>>>>>>> e03b0e29bd87a013804f641c3fcd4c6717da89bb
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

        public function eliminar(){
        $salida = false;
        $bd = new BaseDatos;
        $sql = "DELETE FROM compraestadotipo WHERE idcompraestadotipo='" . $this->getIdCompraEstadoTipo() ."'";
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
        $sql = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo =" . $this->getIdCompraEstadoTipo();
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $row = $bd->Registro();
                $obj = new CompraEstadoTipo;
                $obj->setear(
                $row['idcompraestadotipo'],
                $row['cetdescripcion'],
                $row['cetdetalle']);
                $salida = $obj;
            }else{
                $bd->getError();
            }
        }else{
                $bd->getError();
            }
        return $salida;
    }

       public function listar ($where = ""){
        $bd = new BaseDatos;
        $sql = "SELECT * FROM compraestadotipo";
        if ($where != "") {
            $sql .= "WHERE ". $where;
        }
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $arreglo = [];
                while($row = $bd->Registro()){
                    $obj = new CompraEstadoTipo();
                    $obj->setear(
                        $row['idcompraestadotipo'],
                        $row['cetdescripcion'],
                        $row['cetdetalle']);
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

?>