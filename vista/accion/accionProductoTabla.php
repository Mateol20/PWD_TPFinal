<?php

include_once "../../configuracion.php";
include_once "../../Control/ControlPaginaAccion.php";
$data = data_submitted();
$respuesta = false;
$mensaje = "";
$abmProducto = new ABMProducto();

if (isset($data['accion'])) {
    $accion = $data['accion'];

    switch ($accion) {
        case 'alta':
            if (isset($data['pronombre']) && isset($data['prodetalle']) && isset($data['procantstock'])) {
                // Aquí $data debe tener los 3 campos. El ABM se encarga de setearlos.
                $respuesta = $abmProducto->alta($data);
                if (!$respuesta) {
                    $mensaje = "No se pudo dar de alta el producto: " . $abmProducto->getMensajeError();
                }
            } else {
                $mensaje = "Datos incompletos para dar de alta el producto";
            }
            break;

        case 'mod':
            if (isset($data['idproducto']) && isset($data['pronombre']) && isset($data['prodetalle']) && isset($data['procantstock'])) {
                // El ABM necesita el ID para la modificación.
                $respuesta = $abmProducto->modificacion($data);
                if (!$respuesta) {
                    $mensaje = "La acción MODIFICACIÓN no pudo concretarse: " . $abmProducto->getMensajeError();
                }
            } else {
                $mensaje = "Datos incompletos para modificar el producto";
            }
            break;

        case 'baja':
            if (isset($data['idproducto'])) {
                $respuesta = $abmProducto->baja($data);
                if (!$respuesta) {
                    $mensaje = "No se pudo eliminar el producto: " . $abmProducto->getMensajeError();
                }
            } else {
                $mensaje = "Datos incompletos para eliminar el producto";
            }
            break;

        case 'listar':
            // Se devuelve la lista de productos para actualizar la tabla
            $list = $abmProducto->buscar(null);
            $arreglo_salida = array();
            foreach ($list as $elem) {
                $nuevoElem['idproducto'] = $elem->getIdProducto();
                $nuevoElem["pronombre"] = $elem->getProNombre();
                $nuevoElem["prodetalle"] = $elem->getProDetalle();
                $nuevoElem["procantstock"] = $elem->getProCantStock();
                // NOTA: No se incluye proprecio aquí si no existe en la clase Producto, siguiendo tu código original.
                array_push($arreglo_salida, $nuevoElem);
            }
            // Devolver JSON y salir
            header('Content-Type: application/json');
            echo json_encode($arreglo_salida, JSON_PRETTY_PRINT);
            exit;

        default:
            $mensaje = "Acción no válida";
            break;
    }
} else {
    $mensaje = "No se especificó ninguna acción";
}

$retorno['respuesta'] = $respuesta;
if ($mensaje != "") {
    $retorno['errorMsg'] = $mensaje;
}
// Devolver JSON con el resultado de la operación CRUD (alta, mod, baja)
header('Content-Type: application/json');
echo json_encode($retorno);
