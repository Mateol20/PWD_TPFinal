<?php
Class abmUsuario{

    public function cargarObjUsuario($usuario){
        $objUsuario = new Usuario;
        $objUsuario->setear(
            $usuario['nombre'],
            $usuario['pass'],
            $usuario['email']);
            return $objUsuario;
    }

    public function insert($usuario){
        $salida = false;
        $objUsuario = $this->cargarObjUsuario($usuario);
        if($objUsuario->insert()){
            $salida = true;
        }
        return $salida;
    }

    public function modificar($id, $usuario){
        $salida = false;
        $objUsuario = $this->cargarObjUsuario($usuario);
        if($objUsuario->modificar($id)){
            $salida = true;
        }
        return $salida;
    }
    public function eliminar($id){
        $salida = false;
        $objUsuario = new Usuario;
        if($objUsuario->eliminar($id)){
            $salida = true;
        }
        return $salida;
    }
    public function buscar($name,$pass,$dato = ''){
        $salida = false;
        $objUsuario = new Usuario;
        if($objUsuario -> buscar($name,$pass,$dato = '')){
            $salida = true;
        }
        return $salida;
    }
}
?>