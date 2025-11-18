<?php


class UsuarioRol
{

    private $objUsuario; // Objeto Usuario
    private $objRol;     // Objeto Rol
    private $mensajeoperacion;

    public function __construct()
    {
        $this->objUsuario = null;
        $this->objRol = null;
        $this->mensajeoperacion = "";
    }

    /**
     * Carga el objeto UsuarioRol con los objetos completos de Usuario y Rol.
     * @param Usuario $usuario
     * @param Rol $rol
     */
    public function setear($usuario, $rol)
    {
        $this->objUsuario = $usuario;
        $this->objRol = $rol;
    }

    // --- Getters ---

    // Ya no existe getIdUsuarioRol()

    public function getObjUsuario()
    {
        return $this->objUsuario;
    }

    public function getObjRol()
    {
        return $this->objRol;
    }

    public function getMensajeError()
    {
        return $this->mensajeoperacion;
    }

    // --- Setters ---

    public function setMensajeError($msg)
    {
        $this->mensajeoperacion = $msg;
    }
    
    // --- Métodos DB ---

    /**
     * Busca la relación por sus dos claves (idusuario y idrol).
     * @param array $param - Debe contener 'idusuario' y 'idrol'.
     * @return boolean
     */
    public function buscar($param)
    {
        $base = new BaseDatos();
        $resp = false;

        $idusuario = $param['idusuario'] ?? null;
        $idrol = $param['idrol'] ?? null;

        if ($idusuario === null || $idrol === null) {
            $this->setMensajeError("UsuarioRol->buscar: Faltan IDs de usuario o rol.");
            return false;
        }

        $sql = "SELECT * FROM usuariorol WHERE idusuario = " . $idusuario . " AND idrol = " . $idrol;

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                if ($row = $base->Registro()) {
                    // Carga los objetos Usuario y Rol completos
                    $objUsuario = new Usuario();
                    $objRol = new Rol();

                    if ($objUsuario->buscar($row['idusuario']) && $objRol->buscar($row['idrol'])) {
                        // Llamada a setear con solo dos parámetros (sin idusuariorol)
                        $this->setear($objUsuario, $objRol);
                        $resp = true;
                    } else {
                        $this->setMensajeError("UsuarioRol->buscar: No se pudo cargar Usuario o Rol asociado.");
                    }
                }
            } else {
                $this->setMensajeError("UsuarioRol->buscar: " . $base->getError());
            }
        } else {
            $this->setMensajeError("UsuarioRol->buscar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * Inserta la relación en la base de datos.
     * @return boolean
     */
    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;

        if ($this->getObjUsuario() === null || $this->getObjRol() === null) {
            $this->setMensajeError("UsuarioRol->insertar: No se han seteado los objetos Usuario o Rol.");
            return false;
        }

        $idUsuario = $this->getObjUsuario()->getIdUsuario();
        $idRol = $this->getObjRol()->getIdRol();

        // No hay ID autoincremental
        $sql = "INSERT INTO usuariorol (idusuario, idrol) VALUES (" . $idUsuario . ", " . $idRol . ")";

        if ($base->Iniciar()) {
            // El Execute ahora devuelve true/false, no un ID
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                // Manejar error de duplicado (clave primaria duplicada)
                $error = $base->getError();
                if (strpos($error, 'Duplicate entry') !== false) {
                    $this->setMensajeError("UsuarioRol->insertar: La relación ya existe. " . $error);
                } else {
                    $this->setMensajeError("UsuarioRol->insertar: " . $error);
                }
            }
        } else {
            $this->setMensajeError("UsuarioRol->insertar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * Elimina la relación de la base de datos usando la clave compuesta.
     * @return boolean
     */
    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;

        $idUsuario = $this->getObjUsuario()->getIdUsuario();
        $idRol = $this->getObjRol()->getIdRol();

        $sql = "DELETE FROM usuariorol WHERE idusuario = " . $idUsuario . " AND idrol = " . $idRol;

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeError("UsuarioRol->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeError("UsuarioRol->eliminar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * Lista las relaciones de usuario y rol.
     * @param string $condicion
     * @return array de objetos UsuarioRol
     */
    public function listar($condicion = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuariorol ";
        if ($condicion != "") {
            $sql .= ' WHERE ' . $condicion;
        }

        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new UsuarioRol();
                    $objUsuario = new Usuario();
                    $objRol = new Rol();

                    // Se cargan los objetos Usuario y Rol completos
                    if ($objUsuario->buscar($row['idusuario']) && $objRol->buscar($row['idrol'])) {
                        // Se llama a setear con solo los dos objetos
                        $obj->setear($objUsuario, $objRol);

                        array_push($arreglo, $obj);
                    }
                }
            }
        } else {
            $this->setMensajeError("UsuarioRol->listar: " . $base->getError());
        }
        return $arreglo;
    }
    public function getIdRol()
    {
        // Verifica si el objeto Rol existe y devuelve su ID
        $objRol = $this->getObjRol();
        if ($objRol !== null) {
            return $objRol->getIdRol();
        }
        return null;
    }
}
