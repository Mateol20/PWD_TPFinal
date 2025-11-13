<?php 
class ABMCompraEstadoTipo{

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
        $obj = new CompraEstadoTipo;
        $obj -> setear(
            $array['idcompraestadotipo'],
            $array['cetdescripcion'],
            $array['cetdetalle']); 
        return $obj;
    }
    public function alta($array){
        $obj = $this->cargarObj($array);

        echo $obj -> getCetDescripcion();
        echo $obj -> getCetDetalle();
        
        if($obj->insertar()){
           $salida = true; 
        }else{
            $this->getMensajeError();
        }
        return $salida;
    }
    
    public function modificar($array, $idCompraEstadoTipo){
        $salida = false;
        $obj = $this->cargarObj($array);
        $obj ->setIdCompraEstadoTipo($idCompraEstadoTipo);
        if($obj->modificar()){
           $salida = true; 
        }else{
            $this->getMensajeError();
        }
        return $salida;
    }
    public function baja($id){
        $salida = false;
        $obj = new CompraEstadoTipo;
        $obj -> setIdCompraEstadoTipo($id);
        if($obj->eliminar()){
           $salida = true; 
        }else{
            $this->getMensajeError();
        }
        return $salida;
    }
    public function buscar($id){
        $salida = false;
        $obj = new CompraEstadoTipo;
        $obj -> setIdCompraEstadoTipo($id);
        if($resultado = $obj->obtenerPorId()){
           $salida = $resultado; 
        }else{
            $this->getMensajeError();
        }
        return $salida;
    }
    public function listar(){
        $salida = false;
        $obj = new CompraEstadoTipo;
        if($lista = $obj->listar()){
           $salida = $lista; 
        }else{
            $this->getMensajeError();
        }
        return $salida;
    }
}
?>