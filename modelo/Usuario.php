<?php
// Clase BaseDatos (asumo que existe en algún lugar, por ejemplo, /Modelo/BaseDatos.php)
// require_once 'BaseDatos.php'; 

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
        $this->idUsuario = null;
        $this->usNombre = '';
        $this->usPass = '';
        $this->usEmail = '';
        $this->usDeshabilitado = null;
        $this->mensajeError = "";
    }

    public function getMensajeError()
    {
        return $this->mensajeError;
    }

    public function getIdUsuario()
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

    /**
     * Carga el objeto Usuario con todos sus atributos.
     * La firma fue corregida para aceptar los 5 parámetros, con el ID y deshabilitado opcionales.
     * * @param int|null $id
     * @param string $nombre
     * @param string $pass
     * @param string $email
     * @param string|null $deshabilitado
     */
    public function cargar($id = null, $nombre = null, $pass = null, $email = null, $deshabilitado = null)
    {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setPass($pass);
        $this->setEmail($email);
        $this->setDeshabilitado($deshabilitado);
    }
    public function insertar()
    {
        $db = new BaseDatos;
        $salida = false;
        $sql = "INSERT INTO usuario (usnombre, uspass, usmail)
    VALUES('" . $this->getNombre() . "', '" . $this->getPass() . "', '" . $this->getEmail() . "')";

        if ($db->Iniciar()) {
            if ($db->Ejecutar($sql)) {
                $salida = true;
                $idGenerado = $db->getLastId();
                if ($idGenerado > 0) {
                    $this->setId($idGenerado);
                }
            } else {
                $this->setMensajeError("Usuario->insertar: " . $db->getError());
            }
        } else {
            $this->setMensajeError("Usuario->insertar: " . $db->getError());
        }
        return $salida;
    }

    public function modificar()
    {
        $bd = new BaseDatos;
        $respuesta = false;
        $deshabilitadoSQL = $this->getDeshabilitado() === null ? 'NULL' : "'" . $this->getDeshabilitado() . "'";

        $sql = "UPDATE Usuario SET 
        usnombre = '" . $this->getNombre() . "',
        uspass = '" . $this->getPass() . "',
        usmail = '" . $this->getEmail() . "',
        usdeshabilitado = " . $deshabilitadoSQL . " 
        WHERE idusuario = " . $this->getIdUsuario(); // 💡 Asegúrate de quitar comillas si idusuario es INT

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $errorDB = $bd->getError(); // 💡 Capturar el error de la DB
                $this->setMensajeError("Usuario->modificar: " . $errorDB);
            }
        } else {
            $this->setMensajeError("Usuario->modificar (Iniciar DB): " . $bd->getError());
        }
        return $respuesta; // Devuelve true si fue exitoso
    }

    // Se asume que el objeto ya tiene el ID cargado antes de llamar a eliminar
    public function eliminar()
    {
        // Esto es la BAJA LÓGICA (lo que el ABMUsuario espera)
        $salida = false;
        $db = new BaseDatos;

        // Obtener la fecha y hora actual para deshabilitar
        $fechaActual = date('Y-m-d H:i:s');

        // Sentencia SQL para la BAJA LÓGICA (UPDATE)
        $sql = "UPDATE Usuario SET 
            usdeshabilitado = '" . $fechaActual . "' 
            WHERE idusuario ='" . $this->getIdUsuario() . "'";

        if ($db->Iniciar()) {
            if ($db->Ejecutar($sql)) {
                $salida = true;
                $this->setDeshabilitado($fechaActual); // Actualiza el objeto
            } else {
                $this->setMensajeError("Usuario->eliminar (Baja Lógica): " . $db->getError());
            }
        } else {
            $this->setMensajeError("Usuario->eliminar (Baja Lógica): " . $db->getError());
        }
        return $salida;
    }

    /**
     * Busca un usuario por id y carga los 5 atributos.
     * @param string $id
     * @return boolean true si encontró, false caso contrario
     */
    public function buscar($id)
    {
        $bd = new BaseDatos();
        $respuesta = false;
        $sql = "SELECT * FROM usuario WHERE idusuario = '" . $id . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                if ($fila = $bd->Registro()) {
                    // Carga con los 5 argumentos (incluyendo idusuario y usdeshabilitado)
                    $this->cargar(
                        $fila['idusuario'],
                        $fila['usnombre'],
                        $fila['uspass'],
                        $fila['usmail'],
                        $fila['usdeshabilitado'] // Dato faltante en tu versión original
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
                    // Esta llamada ahora es compatible con la nueva firma de cargar
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
