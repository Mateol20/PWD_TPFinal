<?php
class ABMCompraEstado
{
    private $mensajeError;

    public function __construct()
    {
        $this->mensajeError = "";
    }
    public function getMensajeError()
    {
        return $this->mensajeError;
    }
    public function setMensajeError($mensaje)
    {
        $this->mensajeError = $mensaje;
    }


    public function cargarObj($idCompra,$idCompraEstadoTipo)
    {
        $obj = new CompraEstado;
        $obj->setIdCompra($idCompra);
        $obj->setIdCompraEstadoTipo($idCompraEstadoTipo);
        return $obj;
    }

    public function alta($idCompra,$idCompraEstadoTipo)
    {
        $salida = false;
        $obj = $this->cargarObj($idCompra,$idCompraEstadoTipo);
        if ($ultimoID = $obj->insertar()) {

            $obj->setIdCompraEstado($ultimoID);
            $salida = $ultimoID;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }

    public function modificar($idCompraEstado,$idCompraEstadoTipo){
        $salida = false;
        $obj = new CompraEstado;

        if($idCompraEstado == ''){
         $idCompraEstado = $obj->obtenerUltimoId();
        }

        $obj->setIdCompraEstadoTipo($idCompraEstadoTipo);
        $obj->setIdCompraEstado($idCompraEstado);

        if ($obj->modificar()) {
            $salida = true;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }

    public function baja($id)
    {
        $salida = false;
        $obj = new CompraEstado;
        $obj->setIdCompraEstado($id);
        if ($obj->eliminar()) {
            $salida = true;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }

    public function buscar($id)
    {
        $salida = false;
        $obj = new CompraEstado;
        $obj->setIdCompraEstado($id);
        if ($resultado = $obj->obtenerPorId()) {
            $salida = $resultado;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }
    public function buscarPorCompra($id)
    {
        $salida = false;
        $obj = new CompraEstado;
        $obj->setIdCompra($id);
        if ($resultado = $obj->obtenerPorIdCompra()) {
            $salida = $resultado;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }

    public function listar()
    {
        $salida = false;
        $obj = new CompraEstado;
        if ($lista = $obj->listar()) {
            $salida = $lista;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }
    public function cancelarCompra($idCompra){
        $salida = false;
        $obj = new CompraEstado;
        $obj->setIdCompra($idCompra);
        if ($obj->cancelarCompra()) {
            $salida = true;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }
}
