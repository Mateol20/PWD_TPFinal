<?php
class Menu
{
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $ObjMenu;
    private $medeshabilitado;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idmenu = "";
        $this->menombre = "";
        $this->medescripcion = "";
        $this->ObjMenu;
        $this->medeshabilitado;
        $this->mensajeoperacion = "";
    }

    public function setear($idmenu, $menombre, $medescripcion, $ObjMenu, $medeshabilitado)
    {
        $this->setIdmenu($idmenu);
        $this->setMenombre($menombre);
        $this->setMedescripcion($medescripcion);
        $this->setObjMenuPadre($ObjMenu);
        $this->setMedeshabilitado($medeshabilitado);
    }

    public function getIdmenu()
    {
        return $this->idmenu;
    }

    public function setIdmenu($idmenu)
    {
        $this->idmenu = $idmenu;
    }

    public function getMenombre()
    {
        return $this->menombre;
    }

    public function setMenombre($menombre)
    {
        $this->menombre = $menombre;
    }

    public function getMedescripcion()
    {
        return $this->medescripcion;
    }
    public function obtenerPorId()
    {
        return $this->cargar();
    }

    public function setMedescripcion($medescripcion)
    {
        $this->medescripcion = $medescripcion;
    }

    public function getObjMenuPadre()
    {
        return $this->ObjMenu;
    }

    public function setObjMenuPadre($ObjMenu)
    {
        if ($ObjMenu instanceof Menu || $ObjMenu === null) {
            $this->ObjMenu = $ObjMenu;
        } else {
            // Cualquier otra cosa (string) lo convertimos a null
            $this->ObjMenu = null;
        }
    }

    public function getMedeshabilitado()
    {
        return $this->medeshabilitado;
    }
    public function setMedeshabilitado($medeshabilitado)
    {

        if ($medeshabilitado !== null && $medeshabilitado !== '') {
            $this->medeshabilitado = $medeshabilitado;
        } else {
            $this->medeshabilitado = null; // Menú Habilitado
        }
    }

    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM menu WHERE idmenu = " . $this->getIdmenu();

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);

            if ($res > 0) {
                $row = $base->Registro();

                $objMenuPadre = null;
                if ($row['idpadre'] != null && $row['idpadre'] != '') {
                    $objMenuPadre = new Menu();
                    $objMenuPadre->setIdmenu($row['idpadre']);
                    $objMenuPadre->cargar();
                }

                $this->setear(
                    $row['idmenu'],
                    $row['menombre'],
                    $row['medescripcion'],
                    $objMenuPadre,
                    $row['medeshabilitado']
                );

                $resp = true;  // ← IMPORTANTE
            }
        } else {
            $this->setMensajeoperacion("Menu->cargar: " . $base->getError());
        }
        return $resp;
    }
    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();

        // Valores base
        $nombre = $this->getMenombre();
        $descripcion = $this->getMedescripcion();

        // ID Padre
        $padre = $this->getObjMenuPadre();
        $idpadre = ($padre instanceof Menu) ? $padre->getIdmenu() : "null";

        // Deshabilitado
        $deshab = $this->getMedeshabilitado() !== null ? "'" . $this->getMedeshabilitado() . "'" : "null";

        // SQL CORRECTA
        $sql = "INSERT INTO menu (menombre, medescripcion, idpadre, medeshabilitado)
            VALUES ('$nombre', '$descripcion', $idpadre, $deshab);";

        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdmenu($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("Menu->insertar: " . $base->getError());
        }

        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();

        $sql = "UPDATE menu SET 
            menombre='" . $this->getMenombre() . "', 
            medescripcion='" . $this->getMedescripcion() . "'";

        // ID PADRE
        $padre = $this->getObjMenuPadre();
        if ($padre instanceof Menu) {
            $sql .= ", idpadre=" . $padre->getIdmenu();
        } else {
            $sql .= ", idpadre=null";
        }
        $fechaDeshabilitado = $this->getMedeshabilitado();

        if ($fechaDeshabilitado !== null) {
            $sql .= ", medeshabilitado='" . $fechaDeshabilitado . "'";
        } else {
            $sql .= ", medeshabilitado=null";
        }

        $sql .= " WHERE idmenu=" . $this->getIdmenu();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("Menu->modificar: " . $base->getError());
        }

        return $resp;
    }


    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM menu WHERE idmenu=" . $this->getIdmenu();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("Menu->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM menu";
        if ($parametro != "") {
            $sql .= " WHERE " . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new Menu();
                    $objMenuPadre = null;
                    if ($row['idpadre'] != null) {
                        $objMenuPadre = new Menu();
                        $objMenuPadre->setIdmenu($row['idpadre']);
                        $objMenuPadre->cargar();
                    }
                    $obj->setear($row['idmenu'], $row['menombre'], $row['medescripcion'], $objMenuPadre, $row['medeshabilitado']);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
