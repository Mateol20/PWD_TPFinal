<?php
class Session
{

    private $objUsuario;
    private $objRol;

    public function __construct()
    {
        // Asegurarse de que la sesión comience si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->objUsuario = null;
        $this->objRol = null;

        // Cargar el usuario y rol de la sesión si existen
        if ($this->activa()) {
            if (isset($_SESSION['usuario_obj'])) {
                $this->objUsuario = $_SESSION['usuario_obj'];
            }
            if (isset($_SESSION['rol_obj'])) {
                $this->objRol = $_SESSION['rol_obj'];
            }
        }
    }

    public function getUsuario()
    {
        return $this->objUsuario;
    }
    public function setUsuario($dato)
    {
        $this->objUsuario = $dato;
        // Opcional: guardar en la sesión para persistencia si estás usando __construct para cargar
        // $_SESSION['usuario_obj'] = $dato; 
    }

    public function getRol()
    {
        return $this->objRol;
    }
    public function setRol($dato)
    {
        $this->objRol = $dato;
        // Opcional: guardar en la sesión para persistencia
        // $_SESSION['rol_obj'] = $dato;
    }

    public function iniciar($nombre, $pass)
    {
        // Esto solo inicia la sesión, la validación debería ir en ControlUsuario/Login
        $_SESSION['usnombre'] = $nombre;
        $_SESSION['uspass'] = $pass;
    }

    /**
     * Valida si la sesión actual tiene credenciales válidas.
     * En un login exitoso, esta función se encargaría de guardar el ID y el objeto Usuario en $_SESSION.
     * @return boolean
     */
    public function validar()
    {
        // Esta función típicamente contiene la lógica de base de datos para verificar credenciales.
        // Si tienes un ID en la sesión, la usas para validar.
        $resp = false;
        if ($this->activa() && isset($_SESSION["idusuario"])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     * Devuelve true o false si hay un USUARIO logueado (NO solo si la sesión de PHP está activa).
     * @return boolean
     */
    public function activa()
    {
        // La sesión está activa si:
        // 1. PHP ha iniciado la sesión (session_status() == PHP_SESSION_ACTIVE).
        // 2. Y existe una clave que solo se pone al loguearse (como 'idusuario').
        $resp = false;
        if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['idusuario'])) {
            $resp = true;
        }
        return $resp;
    }

    public function cerrar()
    {
        session_unset();
        session_destroy();
    }
}
