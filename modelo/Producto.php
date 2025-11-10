<?php
class Producto{
    private $idProducto;
    private $proNombre;
    private $proDetalle;
    private $proCantStock;
    private $mensajeError;

    public function __construct()
    {
        $this->idProducto = "";
        $this->proNombre = "";
        $this->proDetalle = "";
        $this->proCantStock = "";
    }
    //GETTERS
    public function getIdProducto(){return $this->idProducto;}
    public function getProNombre(){return $this->proNombre;}
    public function getProDetalle(){return $this->proDetalle;}
    public function getProCantStock(){return $this->proCantStock;}
    public function getMensajeError(){return $this->mensajeError;}
    //SETTERS
    public function setIdProducto($idProducto)
    {
        $this->idProducto=$idProducto;
    }
    public function setProNombre($proNombre)
    {
        $this->proNombre=$proNombre;
    }
    public function setProDetalle($proDetalle)
    {
        $this->proDetalle = $proDetalle;
    }
    public function setProCantStock($proCantStock)
    {
        $this->proCantStock = $proCantStock;
    }
        public function setMensajeError($mensajeError)
    {
        $this->mensajeError = $mensajeError;
    }
        // --- Métodos de Persistencia
    public function setear($idProducto,$proNombre,$proDetalle,$proCantStock)
    {
        $this->setIdProducto($idProducto);
        $this->setProNombre($proNombre);
        $this->setProDetalle($proDetalle);
        $this->setProCantStock($proCantStock);
    }
        public function insertar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "INSERT INTO producto (idproducto, pronombre, prodetalle, procantstock) 
            VALUES ('{$this->getIdProducto()}', '{$this->getProNombre()}', 
                    '{$this->getProDetalle()}', '{$this->getProCantStock()}')";
        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("menu->insertar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("menu->insertar: " . $bd->getError());
        }

        return $respuesta;
    }

    public function eliminar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
                $sql = "DELETE FROM producto WHERE idproducto = '{$this->getIdProducto()}'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("Producto->eliminar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("Producto->eliminar: " . $bd->getError());
        }

        return $respuesta;
    }

    public function modificar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "UPDATE producto SET 
            pronombre = '" . $this->getProNombre() . "', 
            prodetalle = '" . $this->getProDetalle() . "', 
            procantstock = '" . $this->getProCantStock() . "', 
        WHERE idproducto = '" . $this->getIdProducto() . "'";
        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("menu->modificar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("menu->modificar: " . $bd->getError());
        }

        return $respuesta;
    }

}