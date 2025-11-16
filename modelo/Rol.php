<?php
class Rol
{
    private $idrol;
    private $rodescripcion;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idrol = null;
        $this->rodescripcion = "";
        $this->mensajeoperacion = "";
    }

    /**
     * Carga los datos del rol.
     * @param int|null $id
     * @param string $desc
     */
    public function cargar($id, $desc)
    {
        $this->idrol = $id;
        $this->rodescripcion = $desc;
    }

    // --- Getters ---

    public function getIdRol()
    {
        return $this->idrol;
    }

    public function getDescripcion()
    {
        return $this->rodescripcion;
    }

    public function getMensajeError()
    {
        return $this->mensajeoperacion;
    }

    // --- Setters ---

    public function setIdRol($id)
    {
        $this->idrol = $id;
    }

    public function setDescripcion($desc)
    {
        $this->rodescripcion = $desc;
    }

    public function setMensajeError($msg)
    {
        $this->mensajeoperacion = $msg;
    }

    // --- Metodos DB ---

    /**
     * Busca un rol por su ID.
     * @param int $id
     * @return boolean
     */
    public function buscar($id)
    {
        $base = new BaseDatos();
        $resp = false;
        $sql = "SELECT * FROM rol WHERE idrol = " . $id;

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                if ($row = $base->Registro()) {
                    $this->cargar($row['idrol'], $row['rodescripcion']);
                    $resp = true;
                }
            } else {
                $this->setMensajeError("Rol->buscar: " . $base->getError());
            }
        } else {
            $this->setMensajeError("Rol->buscar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * Lista roles basados en una condición WHERE.
     * @param string $condicion
     * @return array
     */
    public static function listar($condicion = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM rol ";
        if ($condicion != "") {
            $sql .= ' WHERE ' . $condicion;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new Rol();
                    $obj->cargar($row['idrol'], $row['rodescripcion']);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            "Rol->listar: " . $base->getError();
        }

        return $arreglo;
    }

    /**
     * Inserta un nuevo rol en la base de datos.
     * @return boolean
     */
    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;

        $desc = $base->escapeString($this->getDescripcion());

        // El ID es AUTO_INCREMENT, se asigna 'null' para que la DB lo genere
        $sql = "INSERT INTO rol (idrol, rodescripcion) VALUES (NULL, " . $desc . ")";

        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($sql)) {
                $this->setIdRol($id);
                $resp = true;
            } else {
                $this->setMensajeError("Rol->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeError("Rol->insertar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * Modifica el rol actual en la base de datos.
     * @return boolean
     */
    public function modificar()
    {
        $base = new BaseDatos();
        $resp = false;

        $desc = $base->escapeString($this->getDescripcion());

        $sql = "UPDATE rol SET rodescripcion = " . $desc . " WHERE idrol = " . $this->getIdRol();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeError("Rol->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeError("Rol->modificar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * Elimina el rol actual de la base de datos.
     * @return boolean
     */
    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        //  ON DELETE CASCADE en la base de datos.
        $sql = "DELETE FROM rol WHERE idrol = " . $this->getIdRol();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeError("Rol->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeError("Rol->eliminar: " . $base->getError());
        }
        return $resp;
    }
}
