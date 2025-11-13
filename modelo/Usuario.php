<?php
class Usuario
{

    private $idUsuario;
    private $usNombre;
    private $usPass;
    private $usEmail;
    private $usDeshabilitado;
    private $mensajeError;

    public function __construct()
    {
        $usNombre = '';
        $usPass = '';
        $usEmail = '';
        $usDeshabilitado = null;
        $mensajeError = "";
    }

    public function getId()
    {
        return $this->idUsuario;
    }
    public function getNombre()
    {
        return $this->usNombre;
    }
    public function getPass()
    {
        return $this->usPass;
    }
    public function getEmail()
    {

        return $this->usEmail;
    }
    public function getDeshabilitado()
    {
        return $this->usDeshabilitado;
    }

    public function setId($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }
    public function setNombre($usNombre)
    {
        $this->usNombre = $usNombre;
    }
    public function setPass($usPass)
    {
        $this->usPass = $usPass;
    }
    public function setEmail($usEmail)
    {
        $this->usEmail = $usEmail;
    }
    public function setDeshabilitado($usDeshabilitado)
    {
        $this->usDeshabilitado = $usDeshabilitado;
    }
    public function setMensajeError($mensaje)
    {
        $this->mensajeError = $mensaje;
    }
    public function setear($nombre, $pass, $email)
    {
        $this->setNombre($nombre);
        $this->setPass($pass);
        $this->setEmail($email);
    }

    public function insert()
    {
        $db = new BaseDatos;
        $salida = false;
        $sql = "INSERT INTO usuario (usnombre, uspass, usmail)
        VALUES('" . $this->getNombre() .  "','" . $this->getPass() . "', '" .  $this->getEmail() . "') ";
        if ($db->Iniciar()) {
            if ($db->Ejecutar($sql)) {
                $salida = true;
            } else {
            $this->setMensajeError("Usuario->insertar: ". $db->getError()); $db->getError();
            }
        } else {
           $this->setMensajeError("Usuario->insertar: ". $db->getError());
        }
        return $salida;
    }

    public function modificar()
    {
        $bd = new BaseDatos;
        $respuesta = false;
        $sql = "UPDATE Usuario SET 
            usnombre = '" . $this->getNombre() . "',
            uspass = '" . $this->getPass() . "',
            usmail = '" . $this->getEmail() . "' 
            WHERE idusuario = '" . $this->getId() . ";'";

                if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("rol->modificar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("rol->modificar: " . $bd->getError());
        }
        return $respuesta;
    }

    public function eliminar($id)
    {
        $salida = false;
        $this->setId($id);
        $db = new BaseDatos;
        $sql = "DELETE FROM Usuario WHERE idusuario ='" . $this->getId() . "'";
        if ($db->Iniciar()) {
            if ($db->Ejecutar($sql)) {
                $salida = true;
                echo 'nice';
            }
        }
        return $salida;
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
        $sql = "SELECT * FROM usuario WHERE idusuario = '" . $id . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                if ($fila = $bd->Registro()) {
                    $this->cargar(
                        $fila['idusuario'],
                        $fila['usnombre'],
                        $fila['uspass'],
                        $fila['usmail']
                    );

                    $respuesta = true;
                }
            } else {
                $this->setMensajeError("usuario->buscar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("usuario->buscar: " . $bd->getError());
        }

        return $respuesta;
    }
    public function listar($condicion = "")
    {
        $bd = new BaseDatos();
        $arreglo = null;
        $sql = "SELECT * FROM usuario";

        if ($condicion != "") {
            $sql .= " WHERE " . $condicion;
        }

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $arreglo = [];
                while ($fila = $bd->Registro()) {
                    $objUsuario = new Usuario();
                    $objUsuario->cargar(
                        $fila["idusuario"],
                        $fila["usnombre"],
                        $fila["uspass"],
                        $fila["usmail"],
                        $fila["usdeshabilitado"]
                    );
                    array_push($arreglo, $objUsuario);
                }
            } else {
                $this->setMensajeError("usuario->listar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("usuario->listar: " . $bd->getError());
        }

        return $arreglo;
    }
}
