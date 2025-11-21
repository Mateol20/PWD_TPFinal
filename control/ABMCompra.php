<?php
class ABMCompra
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
    public function cargarObj($compra)
    {
        $objCompra = new Compra;
        $objCompra->setIdUsuario($compra['idusuario']);
        $objCompra->setIdcompra($compra['idcompra']);
        return $objCompra;
    }

    public function alta($idUsuario)
    {
        $salida = false;
        $objCompra = new Compra;
        $objCompra->setIdUsuario($idUsuario);
        if ($ultimoID = $objCompra->insertar()) {
            $salida = $ultimoID;
            
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }

    public function modificar($compra)
    {
        $salida = false;
        $objCompra = $this->cargarObj($compra);
        if ($objCompra->modificar()) {
            $salida = true;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }

    public function baja($id)
    {
        $salida = false;
        $objCompra = new Compra;
        $objCompra->setIdCompra($id);
        if ($objCompra->eliminar()) {
            $salida = true;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }

    public function buscar($id)
    {
        $salida = false;
        $objCompra = new Compra;
        $objCompra->setIdCompra($id);
        if ($resultado = $objCompra->seleccionar()) {
            $salida = $resultado;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }

    public function listar()
    {
        $salida = false;
        $objCompra = new Compra;
        $lista = $objCompra->listar();
        if (isset($lista)) {
            $salida = $lista;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }
    public function listarComprasDeUsuario($usuario)
    {
         $lista = [];
        $salida = false;
        $objCompra = new Compra;
        $objCompra -> setIdUsuario($usuario);
        $listaObj = $objCompra->listarCompraDeUsuario();
        if (isset($lista)) {
            foreach ($listaObj as $obj){
                    $lista[] = [
            "idcompra" => $obj->getIdCompra(),
            "cofecha" => $obj->getCoFecha()
        ];
            }
            $salida = $lista;
        } else {
            $salida = $this->getMensajeError();
        }
        return $salida;
    }

}
