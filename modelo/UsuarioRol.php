<?php
class UsuarioRol
{
    private $objRol;
    private $objUsuario;
    private $mensajeOperacion;
    public function __construct()
    {
        $this->objRol = new Rol();
        $this->objUsuario = new Usuario();
        $this->mensajeOperacion = null;
    }

    /////////////////////////////
    // SET Y GET //
    /////////////////////////////


    public function getObjRol()
    {
        return $this->objRol;
    }
    public function setObjRol($objRol)
    {
        $this->objRol = $objRol;
    }
    public function getObjUsuario()
    {
        return $this->objUsuario;
    }
    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
    }
    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }
    public function setMensajeError($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }
    /**
     * Carga datos al objeto
     * @param object $objRol
     * @param object $objUsuario
     */
    public function cargar($objRol, $objUsuario)
    {
        $this->setObjRol($objRol);
        $this->setObjUsuario($objUsuario);
    }
    /**
     * Carga claves al objeto
     * @param int $idRol
     * @param int $idUsuario
     */
    public function cargarClaves($idRol, $idUsuario)
    {
        $objRol = $this->getObjRol();
        $objUsuario = $this->getObjUsuario();

        $objRol->setId($idRol);
        $objUsuario->setId($idUsuario);

        $this->setObjRol($objRol);
        $this->setObjUsuario($objUsuario);
    }


    /**
     * Busca si un usuario tiene un rol
     * @param int $idRol
     * @param int $idUsuario
     * @return boolean true si encontro, false caso contrario
     */
    public function buscar($idRol, $idUsuario)
    {
        $bd = new BaseDatos();
        $respuesta = false;
        $sql = "SELECT * FROM usuariorol WHERE idusuario = '" . $idUsuario . "' AND
        idrol = '" . $idRol . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                if ($fila = $bd->Registro()) {
                    // Rol
                    $objRol = new Rol();
                    $objRol->buscar($fila["idrol"]);

                    // Usuario
                    $objUsuario = new Usuario();
                    $objUsuario->buscar($fila["idusuario"]);

                    $this->cargar(
                        $objRol,
                        $objUsuario
                    );

                    $encontro = true;
                }
            } else {
                $this->setMensajeError("usuariorol->buscar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("usuariorol->buscar: " . $bd->getError());
        }

        return $encontro;
    }

    /**
     * Lista los usuarios y sus roles de la base de datos
     * @param string $condicion (opcional)
     * @return array|null
     */
    public function listar($condicion = "")
    {
        $bd = new BaseDatos();
        $arreglo = null;
        $sql = "SELECT * FROM usuariorol";

        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $arreglo = [];
                while ($fila = $bd->Registro()) {
                    $objUsuarioRol = new UsuarioRol();
                    $objUsuarioRol->buscar($fila["idrol"], $fila["idusuario"]);

                    array_push($arreglo, $objUsuarioRol);
                }
            } else {
                $this->setMensajeError("usuariorol->listar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("usuariorol->listar: " . $bd->getError());
        }

        return $arreglo;
    }

    /**
     * Inserta los datos del objeto UsuarioRol actual a la base de datos.
     * @return boolean true si se concretó, false caso contrario
     */
    public function insertar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $sql = "INSERT INTO usuariorol(idrol, idusuario)
        VALUES ('" . $this->getObjRol()->getId() . "','" . $this->getObjUsuario()->getId() . "');";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("usuariorol->insertar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("usuariorol->insertar: " . $bd->getError());
        }

        return $respuesta;
    }

    /**
     * Elimina el objeto actual de la base de datos
     * @return boolean true si se concretó, false caso contrario
     */
    public function eliminar()
    {
        $respuesta = false;
        $bd = new BaseDatos();
        $consulta = "DELETE FROM usuariorol WHERE idusuario = '" . $this->getObjUsuario()->getId() . "'
        AND idrol = '" . $this->getObjRol()->getId() . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($consulta)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("usuariorol->eliminar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("usuariorol->eliminar: " . $bd->getError());
        }

        return $respuesta;
    }
}
