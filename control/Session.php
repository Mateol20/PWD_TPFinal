<?php
class Session{

    private $objUsuario;
    private $objRol;


    public function __construct(){
        SESSION_START();
        $this->objUsuario = '';
        $this->objRol = '';
    }

    public function getUsuario(){
        return $this->objUsuario;
    }
    public function setUsuario($dato){
        $this->objUsuario = $dato;
    }
    
    public function getRol(){
        return $this->objRol;
    }
    public function setRol($dato){
        $this->objRol = $dato;
    }
 

    public function iniciar($nombre,$pass){
        $_SESSION['usnombre'] = $nombre;
        $_SESSION['uspass'] = $pass;
    }

    public function validar(){
        $$objAbmU = new abmUsuario;
        $nombre = $_SESSION['usnombre'];
        $pass = $_SESSION['uspass'];
        $buscar = $objAbmU->buscar($nombre,$pass,'usnombre, uspass'); //Deberia devolver TRUE/FALSE 
        return $buscar;
    }
    public function activa(){
        $activa = false;
        if(session_status() == 'PHP_SESSION_ACTIVE' ){
            $activa = true;
        }
        return $activa;
    }
    public function cerrar(){
        session_unset();
        session_destroy();
    }
}
?>