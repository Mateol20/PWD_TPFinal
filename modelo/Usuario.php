<?php
class Usuario{

    private $idUsuario;
    private $usNombre;
    private $usPass;
    private $usEmail;
    private $usDeshabilitado;
    
    public function __construct()
    {
        $usNombre = '';
        $usPass = '';
        $usEmail = '';
        $usDeshabilitado = null;
    }

    public function getId(){
        return $this->idUsuario;
    }
    public function getNombre(){
        return $this->usNombre;
    }
    public function getPass(){
        return $this->usPass;
    }
    public function getEmail(){

        return $this->usEmail;
    }
    public function getDeshabilitado(){
        return $this->usDeshabilitado;
    }

    public function setId($idUsuario){
        $this->idUsuario = $idUsuario;
    }
    public function setNombre($usNombre){
        $this->usNombre = $usNombre;
    }
    public function setPass($usPass){
        $this->usPass = $usPass;
    }
    public function setEmail($usEmail){
        $this->usEmail = $usEmail;
    }
    public function setDeshabilitado($usDeshabilitado){
        $this->usDeshabilitado = $usDeshabilitado;
    }
    
    public function setear($nombre,$pass,$email){
        $this->setNombre($nombre);
        $this->setPass($pass);
        $this->setEmail($email);
    }

    public function insert(){
        $db = new BaseDatos;
        $salida = false;
        $sql = "INSERT INTO usuario (usnombre, uspass, usmail)
        VALUES('" . $this->getNombre() .  "','". $this->getPass() . "', '" .  $this->getEmail() . "') " ; 
        if($db->Iniciar()){
            if($db->Ejecutar($sql)){
                $salida = true;
                echo'Usuario agregado👍';
            }else{
                echo('entro 1');
                $db->getError();
            }
        }else{
            echo('entro 2');
            $db->getError();
        }
        return $salida;
    }
    
    public function modificar($id){
        $this->setId($id);
        $db = new BaseDatos;
        $sql = "UPDATE Usuario SET 
            usnombre = '" . $this->getNombre() . "',
            uspass = '" . $this-> getPass() . "',
            usmail = '" . $this-> getEmail() . "' 
            WHERE idusuario = '" . $this->getId() . ";'" ;
            if($db->Iniciar()){
                if($db->Ejecutar($sql)){
                    echo "nice";
                }else{
                    $error = $db->getError();
                    if (is_array($error)) {
                        echo implode(' | ', $error);
                    } else {
                        echo $error;
                    }
                }
            }else{
                $error = $db->getError();
                if (is_array($error)) {
                    echo implode(' | ', $error);
                } else {
                    echo $error;
                }
            }
    } 

    public function eliminar($id){
        $salida = false;
        $this->setId($id);
        $db = new BaseDatos;
        $sql = "DELETE FROM Usuario WHERE idusuario ='" . $this->getId() . "'"; 
        if($db->Iniciar()){
            if($db->Ejecutar($sql)){
                $salida = true;
                echo 'nice';
            }
        }
        return $salida;
    }
    public function buscar($name,$pass,$datos){
        $db = new BaseDatos;
        $salida = false;
        $sql = "SELECT '". $datos . "' FROM usuario WHERE usnombre = '" . $name ."' && uspass" . $pass . "'";
                    if($db->Iniciar()){
                if($db->Ejecutar($sql)){
                    echo "nice";
                    $salida = true;
                }else{
                    $error = $db->getError();
                    if (is_array($error)) {
                        echo implode(' | ', $error);
                    } else {
                        echo $error;
                    }
                }
            }else{
                $error = $db->getError();
                if (is_array($error)) {
                    echo implode(' | ', $error);
                } else {
                    echo $error;
                }
            }
            return $salida;
    }
    public function mostrar($name,$pass,$datos){
        $db = new BaseDatos;
        $salida = false;
        $sql = "SELECT * FROM usuario WHERE usnombre = '" . $name . "' AND uspass = '". $pass ."'";
                    if($db->Iniciar()){
                if($db->Ejecutar($sql)){
                    $salida = $db -> Registro();
                }else{
                  echo'mal';
                }
            }else{
                echo'mal';
            }
            return $salida; 
    }
    
}
?>