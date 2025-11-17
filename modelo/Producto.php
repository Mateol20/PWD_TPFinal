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
    public function getIdProducto()
    {
        return $this->idProducto;
    }
    public function getProNombre()
    {
        return $this->proNombre;
    }
    public function getProDetalle()
    {
        return $this->proDetalle;
    }
    public function getProCantStock()
    {
        return $this->proCantStock;
    }
    public function getMensajeError()
    {
        return $this->mensajeError;
    }
    //SETTERS
    public function setIdProducto($idProducto)
    {
        $this->idProducto = $idProducto;
    }
    public function setProNombre($proNombre)
    {
        $this->proNombre = $proNombre;
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
    public function setear($proNombre, $proDetalle, $proCantStock)
    {
        $this->setProNombre($proNombre);
        $this->setProDetalle($proDetalle);
        $this->setProCantStock($proCantStock);
    }

public function insertar()
{
    $bd = new BaseDatos();
    $sql = "INSERT INTO producto (pronombre, prodetalle, procantstock) 
            VALUES ('{$this->getProNombre()}', '{$this->getProDetalle()}', '{$this->getProCantStock()}')";
    
    if ($bd->Iniciar() && $bd->Ejecutar($sql)) {
        return true;
    }
    $this->mensajeError = "producto->insertar: " . $bd->getError();
    return false;
}

public function modificar()
{
    $bd = new BaseDatos();
    $sql = "UPDATE producto SET 
                pronombre = '{$this->getProNombre()}',
                prodetalle = '{$this->getProDetalle()}',
                procantstock = '{$this->getProCantStock()}'
            WHERE idproducto = '{$this->getIdProducto()}'";

    if ($bd->Iniciar() && $bd->Ejecutar($sql)) {
        return true;
    }
    $this->mensajeError = "producto->modificar: " . $bd->getError();
    return false;
}

public static function listar($condicion = "")
{
    $bd = new BaseDatos();
    $sql = "SELECT * FROM producto ";
    if ($condicion != "") {
        $sql .= " WHERE " . $condicion;
    }

    $arreglo = [];
    if ($bd->Iniciar() && $bd->Ejecutar($sql)) {
        while ($fila = $bd->Registro()) {
            $obj = new Producto();
            $obj->setIdProducto($fila["idproducto"]);
            $obj->setear($fila["pronombre"], $fila["prodetalle"], $fila["procantstock"]);
            $arreglo[] = $obj;
        }
    }
    return $arreglo;
}
}
?>