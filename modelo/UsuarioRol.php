<?php
class UsuarioRol
{
    private $idusuariorol;
    private $objusuario;
    private $objrol;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idusuariorol = null;
        $this->objusuario = new Usuario();
        $this->objrol = new Rol();
        $this->mensajeoperacion = "";
    }

    /**
     * Carga las claves foráneas (idrol e idusuario)
     * @param int $idRol
     * @param int $idUsuario
     */
    public function cargarClaves($idRol, $idUsuario)
    {
        $this->idusuariorol = null;

        // Crea objetos temporales para buscar/referenciar
        $rol = new Rol();
        $rol->buscar($idRol);
        $this->setObjRol($rol);

        $usuario = new Usuario();
        $usuario->buscar($idUsuario);
        $this->setObjUsuario($usuario);
    }

    /**
     * Carga el objeto completo con todos los atributos.
     * @param int $idur
     * @param Usuario $usuario
     * @param Rol $rol
     */
    public function cargar($idur, $usuario, $rol)
    {
        $this->idusuariorol = $idur;
        $this->objusuario = $usuario;
        $this->objrol = $rol;
    }

    // --- Getters ---

    public function getIdUsuarioRol()
    {
        return $this->idusuariorol;
    }

    public function getObjUsuario()
    {
        return $this->objusuario;
    }

    public function getObjRol()
    {
        return $this->objrol;
    }

    public function getMensajeError()
    {
        return $this->mensajeoperacion;
    }

    // --- Setters ---

    public function setIdUsuarioRol($idur)
    {
        $this->idusuariorol = $idur;
    }

    public function setObjUsuario($usuario)
    {
        $this->objusuario = $usuario;
    }

    public function setObjRol($rol)
    {
        $this->objrol = $rol;
    }

    public function setMensajeError($msg)
    {
        $this->mensajeoperacion = $msg;
    }

    // --- Metodos DB ---

    /**
     * Busca un registro de relación por las claves foráneas.
     * @param int $idRol
     * @param int $idUsuario
     * @return boolean
     */
    public function buscarPorClaves($idRol, $idUsuario)
    {
        $base = new BaseDatos();
        $resp = false;
        $sql = "SELECT * FROM usuariorol WHERE idrol = " . $idRol . " AND idusuario = " . $idUsuario;

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                if ($row = $base->Registro()) {
                    // Cargar el ID de la relación
                    $this->setIdUsuarioRol($row['idusuariorol']);

                    // Cargar los objetos de las claves foráneas
                    $objUsuario = new Usuario();
                    $objUsuario->buscar($row['idusuario']);
                    $this->setObjUsuario($objUsuario);

                    $objRol = new Rol();
                    $objRol->buscar($row['idrol']);
                    $this->setObjRol($objRol);

                    $resp = true;
                }
            } else {
                $this->setMensajeError("UsuarioRol->buscarPorClaves: " . $base->getError());
            }
        } else {
            $this->setMensajeError("UsuarioRol->buscarPorClaves: " . $base->getError());
        }
        return $resp;
    }


    /**
     * Lista las relaciones de usuario-rol basadas en una condición WHERE.
     * @param string $condicion
     * @return array
     */
    public static function listar($condicion = "")
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
                    $objUsuario->buscar($row['idusuario']);

                    $objRol = new Rol();
                    $objRol->buscar($row['idrol']);

                    $obj->cargar($row['idusuariorol'], $objUsuario, $objRol);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }

    /**
     * Inserta una nueva relación usuario-rol.
     * @return boolean
     */
    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $idUsuario = $this->getObjUsuario()->getId();
        $idRol = $this->getObjRol()->getId();
        // idusuariorol es NULL si es AUTO_INCREMENT
        $sql = "INSERT INTO usuariorol (idusuariorol, idusuario, idrol) 
                VALUES (NULL, " . $idUsuario . ", " . $idRol . ")";

        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($sql)) {
                $this->setIdUsuarioRol($id);
                $resp = true;
            } else {
                $this->setMensajeError("UsuarioRol->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeError("UsuarioRol->insertar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * Elimina una relación usuario-rol basada en las claves foráneas (idusuario e idrol).
     * @return boolean
     */
    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;

        $idUsuario = $this->getObjUsuario()->getId();
        $idRol = $this->getObjRol()->getId();

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
}
