<?php
class FuncionesCompra{

    public function buscarCompraEstadoTipo($idCompra){
        new CompraEstado;
        $ABMcompraEstado = new ABMCompraEstado;
        $obj = $ABMcompraEstado -> buscarPorCompra($idCompra);
        $salida = $obj -> getIdCompraEstadoTipo();
        switch ($salida) {
            case '1':
                $salida = 'Iniciada';
                break;
            case '2':
                $salida = 'Enviado';
                break;
            case '3':
                $salida = 'Terminado';
                break;
            case '4':
                $salida = 'Cancelado';
                break;
            
            default:
                $salida = 'error';
                break;
        }
        return $salida;
    }
}
?>