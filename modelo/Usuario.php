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

    // ... dentro de la clase Usuario
    public function insertar()
    {
        $db = new BaseDatos; // Asumo que BaseDatos está disponible
        $salida = false;

        // NOTA: Tu SQL original no usa prepared statements, lo mantendremos por ahora:
        $sql = "INSERT INTO usuario (usnombre, uspass, usmail)
    VALUES('" . $this->getNombre() . "', '" . $this->getPass() . "', '" . $this->getEmail() . "')";

        if ($db->Iniciar()) {
            if ($db->Ejecutar($sql)) {
                $salida = true;

                // 🚨 SOLUCIÓN CLAVE: Obtener el ID autoincremental generado
                // Debes tener un método en tu clase BaseDatos para esto.
                $idGenerado = $db->getLastId();

                if ($idGenerado > 0) {
                    // Setea el ID en el objeto Usuario
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
        // Se corrige la sentencia SQL removiendo el punto y coma final (;) y agregando el campo usdeshabilitado.
        $sql = "UPDATE Usuario SET 
            usnombre = '" . $this->getNombre() . "',
            uspass = '" . $this->getPass() . "',
            usmail = '" . $this->getEmail() . "',
            usdeshabilitado = " . ($this->getDeshabilitado() === null ? 'NULL' : "'" . $this->getDeshabilitado() . "'") . " 
            WHERE idusuario = '" . $this->getIdUsuario() . "'";

        if ($bd->Iniciar()) {
            if ($bd->Ejecutar($sql)) {
                $respuesta = true;
            } else {
                $this->setMensajeError("Usuario->modificar: " . $bd->getError());
            }
        } else {
            $this->setMensajeError("Usuario->modificar: " . $bd->getError());
        }
        return $respuesta;
    }

    // Se asume que el objeto ya tiene el ID cargado antes de llamar a eliminar
    public function eliminar()
    {
        $salida = false;
        $db = new BaseDatos;
        $sql = "DELETE FROM Usuario WHERE idusuario ='" . $this->getIdUsuario() . "'";
        if ($db->Iniciar()) {
            if ($db->Ejecutar($sql)) {
                $salida = true;
            } else {
                $this->setMensajeError("Usuario->eliminar: " . $db->getError());
            }
        } else {
            $this->setMensajeError("Usuario->eliminar: " . $db->getError());
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
