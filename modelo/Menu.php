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

    public function insertar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        // 1. Manejo de idPadre (debe ser NULL si no hay padre)
        $idPadreSQL = $this->getIdPadre() === null ? 'NULL' : $this->getIdPadre();
        // 2. Manejo de meDeshabilitado (debe ser NULL o un valor entre comillas)
        $meDeshabilitadoSQL = $this->getMeDeshabilitado() === null ? 'NULL' : "'" . $this->getMeDeshabilitado() . "'";
        $sql = "INSERT INTO menu (menombre, medescripcion, idpadre, medeshabilitado) 
            VALUES ('{$this->getMeNombre()}', '{$this->getMeDescripcion()}', 
                    {$idPadreSQL}, {$meDeshabilitadoSQL})";
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

        // 1. Manejo de idPadre: Si es NULL, se usa la palabra NULL sin comillas.
        $idPadreSQL = $this->getIdPadre() === null ? 'NULL' : $this->getIdPadre();

        // 2. Manejo de meDeshabilitado: Si es NULL, se usa la palabra NULL. Si es un valor, va entre comillas.
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
     * @return boolean Devuelve true si la carga fue exitosa, false en caso contrario.
     */
    public function obtenerPorId()
    {
        $respuesta = false;
        $bd = new BaseDatos();

        // Consulta SQL para seleccionar la fila por el ID del menú
        // NOTA: Se sigue usando concatenación, ¡idealmente debe ser una sentencia preparada!
        $sql = "SELECT * FROM menu WHERE idmenu = '" . $this->getIdMenu() . "'";

        if ($bd->Iniciar()) {
            // Ejecuta la consulta SQL
            if ($bd->Ejecutar($sql)) {
                // Asume que $bd->Registro() obtiene la primera (y única) fila
                if ($registro = $bd->Registro()) {
                    // Asigna los valores del registro a las propiedades del objeto
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
     * * @param string $condicion Cláusula WHERE opcional para filtrar. Ejemplo: "idpadre IS NULL"
     * @return array Devuelve un array de objetos Menu.
     */
    public static function listar($condicion = "")
    {
        // Los métodos que devuelven colecciones suelen ser estáticos
        $arregloMenu = [];
        $bd = new BaseDatos();

        // Consulta base: selecciona todas las columnas de la tabla
        $sql = "SELECT * FROM menu ";

        // Agrega la condición si se proporciona
        if ($condicion != "") {
            $sql .= "WHERE " . $condicion;
        }

        // Asumiendo que BaseDatos tiene métodos Iniciar, Ejecutar y obtener_registro
        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                // Itera sobre todos los registros devueltos por la BD
                while ($registro = $bd->Registro()) {

                    // 1. Crea una nueva instancia de Menu
                    $menu = new Menu();

                    // 2. Carga los datos del registro en la instancia
                    $menu->setIdMenu($registro['idmenu']);
                    $menu->setMeNombre($registro['menombre']);
                    $menu->setMeDescripcion($registro['medescripcion']);
                    $menu->setIdPadre($registro['idpadre']);
                    $menu->setMeDeshabilitado($registro['medeshabilitado']);

                    // 3. Agrega el objeto cargado al array de resultados
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
