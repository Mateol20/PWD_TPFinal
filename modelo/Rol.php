<?php
class Rol
{

    private $idRol;
    private $roDescripcion;

    public function __construct()
    {
        $this->roDescripcion = '';
    }

    public function getIdRol()
    {
        return $this->idRol;
    }

    public function getDescripcion()
    {
        return $this->roDescripcion;
    }

    public function setIdRol($id)
    {
        $this->idRol = $id;
    }

    public function setDescripcion($desc)
    {
        $this->roDescripcion = $desc;
    }

    public function insert()
    {
        $db = new BaseDatos();
        $sql = "INSERT INTO rol (roDescripcion)
                VALUES('" . $this->getDescripcion() . "')";

        if ($db->Iniciar()) {
            // Ejecutar devuelve el ID generado (>0) o 0/false si falla.
            $idGenerado = $db->Ejecutar($sql);

            if ($idGenerado > 0) { // Si retorna un ID válido (entero positivo)
                $this->setIdRol($idGenerado); // <-- ¡ASIGNAR EL ID AL OBJETO!
                echo 'Rol agregado 👍';
                $res = true;
            } else {
                // Manejo de error
                $error = $db->getError();
                echo is_array($error) ? implode(' | ', $error) : $error;
            }
        } else {
            // Manejo de error de conexión
            $error = $db->getError();
            echo is_array($error) ? implode(' | ', $error) : $error;
        }
        return $res; // Devolver el resultado
    }

    public function modificar($id)
    {
        $this->setIdRol($id);
        $db = new BaseDatos();
        $sql = "UPDATE rol SET 
                    roDescripcion = '" . $this->getDescripcion() . "' 
                WHERE idRol = '" . $this->getIdRol() . "'";

        if ($db->Iniciar()) {
            if ($db->Ejecutar($sql)) {
                echo 'Rol modificado ✅';
            } else {
                $error = $db->getError();
                echo is_array($error) ? implode(' | ', $error) : $error;
            }
        } else {
            $error = $db->getError();
            echo is_array($error) ? implode(' | ', $error) : $error;
        }
    }

    public function eliminar($id)
    {
        $this->setIdRol($id);
        $db = new BaseDatos();
        $sql = "DELETE FROM rol WHERE idRol = '" . $this->getIdRol() . "'";

        if ($db->Iniciar()) {
            if ($db->Ejecutar($sql)) {
                echo 'Rol eliminado 🗑️';
            } else {
                $error = $db->getError();
                echo is_array($error) ? implode(' | ', $error) : $error;
            }
        } else {
            $error = $db->getError();
            echo is_array($error) ? implode(' | ', $error) : $error;
        }
    }
}
