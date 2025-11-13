<?php 
class CompraItem {
    private $idCompraItem;
    private $idProducto;
    private $idCompra;
    private $ciCantidad;
    private $mensajeError;

    public function __construct() {
        $this->idProducto = '';
        $this->idCompra = '';
        $this->ciCantidad = '';
        $this->mensajeError = "";
    }

        // GETTERS
    public function getidCompraItem() {
        return $this->idCompraItem;
    }

    public function getIdProducto() {
        return $this->idProducto;
    }

    public function getIdCompra() {
        return $this->idCompra;
    }

    public function getCiCantidad() {
        return $this->ciCantidad;
    }
    public function getMensajeError(){return $this->mensajeError;}
    // SETTERS
    public function setidCompraItem($idCompraItem) {
        $this->idCompraItem = $idCompraItem;
    }

    public function setIdProducto($idProducto) {
        $this->idProducto = $idProducto;
    }

    public function setIdCompra($idCompra) {
        $this->idCompra = $idCompra;
    }

    public function setCiCantidad($ciCantidad) {
        $this->ciCantidad = $ciCantidad;
    }
    public function setMensajeError($mensaje){$this->mensajeError = $mensaje;}

    public function setear($idProducto, $idCompra, $ciCantidad) {
        $this->idProducto = $idProducto;
        $this->idCompra = $idCompra;
        $this->ciCantidad = $ciCantidad;
    }

    public function insertar(){
        $bd = new BaseDatos;
        $sql = "INSERT INTO compraitem VALUES('" . $this->getIdProducto() . "','" . $this->getidCompra() . "','" . $this->getCiCantidad() . "'";
                if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $salida = true;
            }else{
                $this->setMensajeError("Compra Item->insert :".$bd->getError());
            }
        }else{
                $this->setMensajeError("Compra Item->insert :".$bd->getError());
            }
        return $salida;
    }

    public function modificar(){
        $salida = false;
        $bd = new BaseDatos;
        $sql = "UPDATE compraitem SET
        idproducto = '{$this->getidProducto()}' ,
        idcompra = '{$this->getIdCompra()}' ,
        cicantidad = '{$this->getCiCantidad()}'
        WHERE idcompraitem = '{$this->getIdCompraItem()}' " ; 
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $salida = true;
            }else{
                $this->setMensajeError("Compra Item->modificar :".$bd->getError());
            }
        }else{
                $this->setMensajeError("Compra Item->modificar :".$bd->getError());
            }
        return $salida;
    }

    public function eliminar(){
        $salida = false;
        $bd = new BaseDatos;
        $sql = "DELETE FROM compraitem WHERE idcompraitem='" . $this->getIdCompraItem() ."'";
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $salida = true;
            }else{
                $this->setMensajeError("Compra Item->eliminar :".$bd->getError());
            }
        }else{
               $this->setMensajeError("Compra Item->eliminar :".$bd->getError());
            }
        return $salida;
    }

    public function obtenerPorId(){
        $bd = new BaseDatos;
        $sql = "SELECT * FROM compraitem WHERE idcompraitem =" . $this->getidCompraItem();
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $row = $bd->Registro();
                $obj = new CompraItem;
                $obj->setear(
                $row['idproducto'],
                $row['idcompra'],
                $row['cicantidad']);
                $salida = $obj;
            }else{
                $this->setMensajeError("Compra Item->obtener :".$bd->getError());            
            }
        }else{
                $this->setMensajeError("Compra Item->obtener :".$bd->getError());
            }
        return $salida;
    }

           public function listar ($where = ""){
        $bd = new BaseDatos;
        $sql = "SELECT * FROM compraitem";
        if ($where != "") {
            $sql .= "WHERE ". $where;
        }
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                $arreglo = [];
                $obj = new CompraItem();
                foreach($bd->Registro() as $row){
                    $obj->setear(
                $row['idproducto'],
                $row['idcompra'],
                $row['cicantidad']);
                    array_push($arreglo,$obj);
                }
            $salida = $arreglo;
            }else{
                $this->setMensajeError("Compra Item->listar :".$bd->getError());
            }
        }else{
                $this->setMensajeError("Compra Item->listar :".$bd->getError());
            }
        return $salida;
    }
}
ola
?>