<?php
class Rol
{
    private $id;
    private $rolDescripcion;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->id = null;
        $this->rolDescripcion = null;
        $this->mensajeOperacion = null;
    }

    /////////////////////////////
    // SET Y GET //
    /////////////////////////////

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getRolDescripcion()
    {
        return $this->rolDescripcion;
    }
    public function setRolDescripcion($rolDescripcion)
    {
        $this->rolDescripcion = $rolDescripcion;
    }
    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }
    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }
    /**
     * Carga datos al objeto
     * @param int $id
     * @param string $rolDescripcion
     */
    public function cargar($id, $rolDescripcion)
    {
        $this->setId($id);
        $this->setRolDescripcion($rolDescripcion);
    }


    /**
     * Busca un rol por id
     * Sus datos son colocados en el objeto
     * @param string $id
     * @return boolean true si encontro, false caso contrario
     */
    public function buscar($id)
    {
        $bd = new BaseDatos();
        $respuesta = false;
        $sql = "SELECT * FROM rol WHERE idrol = '" . $id . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                if ($fila = $this->Registro()) {
                    $this->cargar(
                        $id,
                        $fila["roldescripcion"]
                    );

                    $respuesta = true;
                }
            } else {
                $this->setMensajeOperacion("rol->buscar: " . $bd->getError());
            }
        } else {
            $this->setMensajeOperacion("rol->buscar: " . $bd->getError());
        }

        return $respuesta;
    }

    /**
     * Lista roles de la base de datos
     * @param string $condicion (opcional)
     * @return array|null colección de usuarios o null si no hay ninguno
     */
    public function listar($condicion = "")
    {
        $bd = new BaseDatos();
        $arreglo = null;
        $sql = "SELECT * FROM rol";

        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $arreglo = [];
                while ($fila = $this->Registro()) {
                    $objRol = new Rol();
                    $objRol->cargar($fila["idrol"], $fila["roldescripcion"]);
                    array_push($arreglo, $objRol);
                }
            } else {
                $this->setMensajeOperacion("rol->listar: " . $bd->getError());
            }
        } else {
            $this->setMensajeOperacion("rol->listar: " . $bd->getError());
        }

        return $arreglo;
    }

    /**
     * Inserta los datos del objeto Usuario actual a la base de datos.
     * @return boolean true si se concretó, false caso contrario
     */
    public function insertar()
    {
        $bd = new BaseDatos();
        $resp = null;
        $resultado = false;

        $sql = "INSERT INTO rol(roldescripcion)
        VALUES ('" . $this->getRolDescripcion() . "');";

        if ($bd->Iniciar()) {
            $resp = $bd->Ejecutar($sql);
            if ($resp) {
                $this->setId($resp);
                $resultado = true;
            } else {
                $this->setmensajeoperacion("rol->insertar: " . $bd->getError());
            }
        } else {
            $this->setMensajeOperacion("rol->insertar: " . $bd->getError());
        }

        return $resultado;
    }

    /**
     * Modifica los datos de la usuario, colocando los del objeto actual
     * @return boolean true si se concretó, false caso contrario
     */
    public function modificar()
    {
        $bd = new BaseDatos();
        $seConcreto = false;

        $sql = "UPDATE rol SET roldescripcion = '" . $this->getRolDescripcion() . "' WHERE idrol = '" . $this->getId() . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $seConcreto = true;
            } else {
                $this->setMensajeOperacion("rol->modificar: " . $bd->getError());
            }
        } else {
            $this->setMensajeOperacion("rol->modificar: " . $bd->getError());
        }

        return $seConcreto;
    }

    /**
     * Elimina el objeto actual de la base de datos
     * @return boolean true si se concretó, false caso contrario
     */
    public function eliminar()
    {
        $bd = new BaseDatos();
        $seConcreto = false;

        $sql = "DELETE FROM rol WHERE idrol = '" . $this->getId() . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $seConcreto = true;
            } else {
                $this->setMensajeOperacion("rol->eliminar: " . $bd->getError());
            }
        } else {
            $this->setMensajeOperacion("rol->eliminar: " . $bd->getError());
        }

        return $seConcreto;
    }
}
