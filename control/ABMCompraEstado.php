<?php 
class ABMCompraEstado{

    private $mensajeError;

    public function __construct(){
        $this->mensajeError = "";
    }

    public function getMensajeError(){
        return $this->mensajeError;
    }
    public function setMensajeError($mensaje){
        $this->mensajeError = $mensaje;
    }

    public function cargarObj($array){
        $obj = new CompraEstado;
        $obj -> setIdCompra($array['idcompra']);
        $obj -> setIdCompraEstadoTipo($array['idcompraestadotipo']);
        return $obj;
    }

    public function alta($array){
        $salida = false;
        $obj = $this->cargarObj($array);
        if($obj->insertar()){
           $salida = true; 
        }else{
            $this->getMensajeError();
        }
        return $salida;
    }
    
    public function modificar($array, $idCompraEstado){
        $salida = false;
        $obj = $this->cargarObj($array);
        $obj ->setIdCompraEstado($idCompraEstado);
        if($obj->modificar()){
           $salida = true; 
        }else{
            $this->getMensajeError();
        }
        return $salida;
    }

        public function baja($id){
        $salida = false;
        $obj = new CompraEstado;
        $obj -> setIdCompraEstado($id);
        if($obj->eliminar()){
           $salida = true; 
        }else{
            $this->getMensajeError();
        }
        return $salida;
    }

    public function buscar($id){
        $salida = false;
        $obj = new CompraEstado;
        $obj -> setIdCompraEstado($id);
        if($resultado = $obj->obtenerPorId()){
           $salida = $resultado; 
        }else{
            $this->getMensajeError();
        }
        return $salida;
    }

        public function listar(){
        $salida = false;
        $obj = new CompraEstado;
        if($lista = $obj->listar()){
           $salida = $lista; 
        }else{
            $this->getMensajeError();
        }
        return $salida;
    }
}
?>