<?php
class ambRol{

    public function insert($rol){
        $salida = false;
        $objRol = new Rol;
        $objRol->setDescripcion($rol);
        if($objRol->insert()){
            $salida = true;
        }
    return $salida;
    }
    public function modificar($id, $rol){
        $salida = false;
        $objRol = new Rol;
        $objRol -> setDescripcion($rol);
        if($objRol->modificar($id)){
            $salida = true;
        }
        return $salida;
    }
    public function eliminar($id){
        $salida = false;
        $objRol = new Rol;
        if($objRol->eliminar($id)){
            $salida = true;
        }
        return $salida;
    }
}
?>