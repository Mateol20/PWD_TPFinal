<?php
class Compra{

    private $idCompra;
    private $coFecha;
    private $idUsuario;

    public function __construct()
    {
        $this->coFecha = '';
        $this->idUsuario = '';
    }

    public function getIdCompra(){
        return $this->idCompra;
    }
    public function getCoFecha(){
        return $this->coFecha;
    }
    public function getIdUsuario(){
        return $this->idUsuario;
    }

    public function setIdCompra($dato){
        $this->idCompra = $dato;
    }
    public function setcoFecha($dato){
        $this->coFecha = $dato;
    }
    public function setIdUsuario($dato){
        $this->idUsuario = $dato;
    }
    public function setear($idUsuario,$coFecha){
        $this->setIdUsuario($idUsuario);
        $this->setcoFecha($coFecha);
    }

    public function insertar(){
        $db = new BaseDatos;
        $sql = "INSERT INTO compra (cofecha,idusuario) VALUES(CURRENT_TIMESTAMP,'" . $this->getIdUsuario() . "')";
        $respuesta = false;
        if($db->Iniciar()){
            if($db->Ejecutar($sql)){
                $respuesta = $db->getLastId();
            }else{
            echo $db->getError();
        }
        }else{
            echo $db->getError();
        }
        return $respuesta;
    }

    public function modificar(){
        $db = new BaseDatos;
            $sql = "UPDATE compra 
            SET cofecha = CURRENT_TIMESTAMP
            WHERE idcompra = '" . $this->getIdCompra() . "'"; 
        $respuesta = false;
        if($db->Iniciar()){
            if($db->Ejecutar($sql)){
                $respuesta = true;
            }else{
            echo $db->getError();
        }
        }else{
            echo $db->getError();
        }
        return $respuesta;
    } 
    public function eliminar(){
        $db = new BaseDatos;
        $sql = "DELETE FROM compra WHERE idcompra = '" . $this->getIdCompra() . "'";
        $respuesta = false;
        if($db->Iniciar()){
            if($db->Ejecutar($sql)){
                $respuesta = true;
            }else{
            echo $db->getError();
        }
        }else{
            echo $db->getError();
        }
        return $respuesta;
    } 
    public function seleccionar(){
        $db = new BaseDatos;
        $sql = "SELECT * FROM compra WHERE idcompra=". $this->getIdCompra();
        $respuesta = false;
        if($db->Iniciar()){
            if($db->Ejecutar($sql)){
                $respuesta = $db->Registro();
                $this->setIdCompra($respuesta['idcompra']);
                $this->setcoFecha($respuesta['cofecha']);
                $this->setIdUsuario($respuesta['idusuario']);
            }else{
            echo $db->getError();
        }
        }else{
            echo $db->getError();
        }
        return $respuesta;
    }
    public function listarCompraDeUsuario(){
        $db = new BaseDatos;
        $sql = "SELECT * FROM compra WHERE idusuario=". $this->getIdUsuario() . " ORDER BY cofecha DESC";
        $objetos = [];
        if($db->Iniciar()){
            if($db->Ejecutar($sql)){
               while($respuesta = $db->Registro()){
                $objCompra = new Compra; 
                $objCompra->setIdCompra($respuesta['idcompra']);
                $objCompra->setcoFecha($respuesta['cofecha']);
                $objCompra->setIdUsuario($respuesta['idusuario']);
                array_push($objetos,$objCompra);
                }
            $respuesta = $objetos;
            }else{
            echo $db->getError();
        }
        }else{
            echo $db->getError();
        }
        return $respuesta;
    }
    public function listar(){
        $db = new BaseDatos;
        $sql = "SELECT * FROM compra";
        $respuesta = [];
        if($db->Iniciar()){
            if($db->Ejecutar($sql)){
                while($row = $db->Registro()){
                    array_push($respuesta, $row);
                }
            }else{
            echo $db->getError();
        }
        }else{
            echo $db->getError();
        }
        return $respuesta;
    }
}
?>