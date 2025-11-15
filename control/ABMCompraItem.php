<?php
class abmCompraItem
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
        $objCompra = new CompraItem;
        $objCompra->setIdProducto($compra['idproducto']);
        $objCompra->setIdcompra($compra['idcompra']);
        $objCompra->setCiCantidad($compra['cicantidad']);
        return $objCompra;
    }

    public function alta($array)
    {
        $obj = $this->cargarObj($array);
        if ($obj->insertar()) {
            $salida = true;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }

    public function modificar($array, $idCompraItem)
    {
        $salida = false;
        $obj = $this->cargarObj($array);
        $obj->setidCompraItem($idCompraItem);
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
        $obj = new CompraItem;
        $obj->setIdCompraItem($id);
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
        $obj = new CompraItem;
        $obj->setIdCompraitem($id);
        if ($resultado = $obj->obtenerPorId()) {
            $salida = $resultado;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }

    public function listar()
    {
        $salida = false;
        $obj = new CompraItem;
        if ($lista = $obj->listar()) {
            $salida = $lista;
        } else {
            $this->getMensajeError();
        }
        return $salida;
    }
}
