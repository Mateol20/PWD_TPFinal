<?php
class UsuarioRol {
    private $idUsuario;
    private $idRol;
    public function __construct() {
        $this->idUsuario = '';
        $this->idRol = '';
    }
    public function getIdUsuario(){
        return $this->idUsuario;
    }
    public function getIdRol(){
        return $this->idRol;
    }
    public function setIdUsuario($us){
        $this->idUsuario = $us;
    }
    public function setIdRol($rol){
        $this->idRol = $rol;
    }

    public function insert(){
        $idU= new Usuario;
        $idR= new Rol;
        $bd= new BaseDatos;
        $sql = "INSERT INTO UsuarioRol VALUES '" . $idU->getId() . "'," . $idR->getIdRol() . "'";
        if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                echo "nice";
            }else{
            ECHO "error";
        }
        }else{
            ECHO "error";
        }
    }
    public function modificar(){
        $bd = new BaseDatos;
        $sql = "UPDATE UsuarioRol SET
        idusuario ='" . $this->getIdUsuario() . "'," .
        "idrol ='" . $this->getIdRol(). "'";
         if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                echo "nice";
            }else{
            ECHO "error";
        }
        }else{
            ECHO "error";
        }
    }
    public function eliminar(){
        $bd= new BaseDatos;
        $sql = "DELETE FROM usuariorol WHERE idusuario = '" . $this->getIdUsuario() . "'";
            if($bd->Iniciar()){
            if($bd->Ejecutar($sql)){
                echo "nice";
            }else{
            ECHO "error";
        }
        }else{
            ECHO "error";
        }
    }
}
?>