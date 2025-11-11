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
    public function setear($proNombre,$proDetalle,$proCantStock)
    {
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
    public function obtenerPorId()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "SELECT * FROM producto WHERE idproducto = '" . $this->getIdProducto() . "'";
                if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                if ($registro = $bd->Registro()) {
                    $this->setProNombre($registro['pronombre']);
                    $this->setProDetalle($registro['prodetalle']);
                    $this->setProCantStock($registro['procantstock']);
                    $respuesta = true;
                }
            } else {
                $this->setMensajeError("producto->obtenerPorId: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("producto->obtenerPorId: " . $bd->getError());
        }
        return $respuesta;
    }
     public static function listar($condicion = "")
    {
        $arregloProducto = [];
        $bd = new BaseDatos();
        $sql = "SELECT * FROM menu ";

        if ($condicion != "") {
            $sql .= "WHERE " . $condicion;
        }

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {

                while ($registro = $bd->Registro()) {
                    $producto = new Producto();
                    $producto->setProNombre($registro['pronombre']);
                    $producto->setProDetalle($registro['prodetalle']);
                    $producto->setProCantStock($registro['procantstock']);
                    $producto->setIdProducto($registro['idproducto']);
                    array_push($arregloMenu, $producto);
                }
            } else {
                echo "Error al listar: " . $bd->getError(); 
            }
        } else {
            echo "Error de conexión: " . $bd->getError();
        }

        return $arregloMenu;
    }
}