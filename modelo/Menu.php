<?php
class Menu
{
    // Propiedades con CamelCase
    private $idMenu;
    private $meNombre;
    private $meDescripcion;
    private $idPadre;
    private $meDeshabilitado;
    private $mensajeError;

    public function __construct()
    {
        $this->idMenu = null;
        $this->meNombre = "";
        $this->meDescripcion = "";
        $this->idPadre = null;
        $this->meDeshabilitado = null;
        $this->mensajeError = "";
    }

    // --- GETTERS 
    public function getIdMenu()
    {
        return $this->idMenu;
    }
    public function getMeDescripcion()
    {
        return $this->meDescripcion;
    }
    public function getMeNombre()
    {
        return $this->meNombre;
    }
    public function getIdPadre()
    {
        return $this->idPadre;
    }
    public function getMensajeError()
    {
        return $this->mensajeError;
    }
    public function getMeDeshabilitado()
    {
        return $this->meDeshabilitado;
    }

    // --- SETTERS 
    public function setIdMenu($idMenu)
    {
        $this->idMenu = $idMenu;
    }
    public function setMeDescripcion($meDescripcion)
    {
        $this->meDescripcion = $meDescripcion;
    }
    public function setMeNombre($meNombre)
    {
        $this->meNombre = $meNombre;
    }
    public function setIdPadre($idPadre)
    {
        $this->idPadre = $idPadre;
    }
    public function setMeDeshabilitado($meDeshabilitado)
    {
        $this->meDeshabilitado = $meDeshabilitado;
    }
    public function setMensajeError($mensajeError)
    {
        $this->mensajeError = $mensajeError;
    }

    // --- Métodos de Persistencia
    public function setear($idMenu, $meNombre, $meDescripcion, $idPadre, $meDeshabilitado)
    {
        $this->setIdMenu($idMenu);
        $this->setMeNombre($meNombre);
        $this->setMeDescripcion($meDescripcion);
        $this->setIdPadre($idPadre);
        $this->setMeDeshabilitado($meDeshabilitado);
    }
    public function insertar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $idPadreSQL = $this->getIdPadre() === null ? 'NULL' : $this->getIdPadre();
        $meDeshabilitadoSQL = $this->getMeDeshabilitado() === null ? 'NULL' : "'" . $this->getMeDeshabilitado() . "'";
        $sql = "INSERT INTO menu (menombre, medescripcion, idpadre, medeshabilitado) 
            VALUES ('{$this->getMeNombre()}', '{$this->getMeDescripcion()}', 
                    {$idPadreSQL}, {$meDeshabilitadoSQL})";
        if ($bd->Iniciar()) {
            $idGenerado = $bd->Ejecutar($sql);

            if ($idGenerado > 0) {
                $this->setIdMenu($idGenerado);
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
        $sql = "DELETE FROM menu WHERE idmenu = '{$this->getIdMenu()}'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("menu->eliminar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("menu->eliminar: " . $bd->getError());
        }

        return $respuesta;
    }

    public function modificar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $idPadreSQL = $this->getIdPadre() === null ? 'NULL' : $this->getIdPadre();
        $meDeshabilitadoSQL = $this->getMeDeshabilitado() === null ? 'NULL' : "'" . $this->getMeDeshabilitado() . "'";
        $sql = "UPDATE menu SET 
            menombre = '" . $this->getMeNombre() . "', 
            medescripcion = '" . $this->getMeDescripcion() . "', 
            idpadre = {$idPadreSQL}, 
            medeshabilitado = {$meDeshabilitadoSQL} 
        WHERE idmenu = '" . $this->getIdMenu() . "'";
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
    /**
     * Busca un registro de menú en la base de datos por su ID y carga 
     * sus datos en las propiedades del objeto actual.
     * @return boolean 
     */
    public function obtenerPorId()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "SELECT * FROM menu WHERE idmenu = '" . $this->getIdMenu() . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                if ($registro = $bd->Registro()) {
                    $this->setMeNombre($registro['menombre']);
                    $this->setMeDescripcion($registro['medescripcion']);
                    $this->setIdPadre($registro['idpadre']);
                    $this->setMeDeshabilitado($registro['medeshabilitado']);
                    $respuesta = true;
                }
            } else {
                $this->setMensajeError("menu->obtenerPorId: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("menu->obtenerPorId: " . $bd->getError());
        }
        return $respuesta;
    }
    /**
     * Obtiene una colección (array) de objetos Menu que cumplen una condición 
     * o todos los registros si no se especifica condición.
     * * @param string
     * @return array 
     */
    public static function listar($condicion = "")
    {
        $arregloMenu = [];
        $bd = new BaseDatos();
        $sql = "SELECT * FROM menu ";

        if ($condicion != "") {
            $sql .= "WHERE " . $condicion;
        }

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {

                while ($registro = $bd->Registro()) {
                    $menu = new Menu();
                    $menu->setIdMenu($registro['idmenu']);
                    $menu->setMeNombre($registro['menombre']);
                    $menu->setMeDescripcion($registro['medescripcion']);
                    $menu->setIdPadre($registro['idpadre']);
                    $menu->setMeDeshabilitado($registro['medeshabilitado']);

                    array_push($arregloMenu, $menu);
                }
            } else {
                // Manejo de error si la ejecución falla
                // Nota: En un método estático, el error debería manejarse de otra forma
                // Ej: Loggear o lanzar una excepción, ya que no podemos usar $this->setMensajeError.
                // Para simplicidad, aquí solo imprimiremos:
                // echo "Error al listar: " . $bd->getError(); 
            }
        } else {
            // Manejo de error si la conexión falla
            // echo "Error de conexión: " . $bd->getError();
        }

        return $arregloMenu;
    }
}
