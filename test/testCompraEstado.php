<?php
include_once '../configuracion.php'; // Asegúrate de que esta ruta sea correcta

// 🔹 Crear instancia del ABM
$abm = new ABMCompraEstado();

// 🔸 1. Prueba de Alta (insertar nuevo registro)
echo "<h3>🔸 Prueba de alta()</h3>";
// Datos para un nuevo registro de CompraEstado
// Asegúrate de que el idcompra y idcompraestadotipo existen en sus respectivas tablas
$datosNuevo = [
    'idcompraestado' => 1, // ID de una compra existente
    'idcompra' => 6, // ID de una compra existente
    'idcompraestadotipo' => 3, // ID de un tipo de estado de compra existente (ej. 'iniciada')
    'cefechaini' => null,//date('Y-m-d H:i:s'), // O dejar null para que la DB ponga CURRENT_TIMESTAMP
    'cefechafin' => null
];

$idRecienCreado = null; // Para almacenar el ID del registro que creamos

if ($abm->alta($datosNuevo)) {
    echo "✅ Alta de CompraEstado realizada correctamente.<br>";
    // Intentar encontrar el ID del registro recién creado
    // Esto es un poco rudimentario; lo ideal sería que ABM->alta() devuelva el objeto o su ID.
    // Buscamos el último registro con los mismos idcompra e idcompraestadotipo.
    $listadoAux = $abm->listar("idcompra = {$datosNuevo['idcompra']} AND idcompraestadotipo = {$datosNuevo['idcompraestadotipo']} ORDER BY idcompraestado DESC LIMIT 1");
    if (!empty($listadoAux)) {
        $idRecienCreado = $listadoAux[0]->getIdCompraEstado();
        echo "   ID del CompraEstado recién creado: {$idRecienCreado}.<br>";
    } else {
        echo "   ⚠️ No se pudo recuperar el ID del registro recién creado.<br>";
    }
} else {
    echo "❌ Error al realizar el alta de CompraEstado: " . $abm->getMensajeError() . "<br>";
}

// 🔸 2. Prueba de Listar todos
echo "<h3>🔸 Prueba de listar()</h3>";
$listado = $abm->listar();
if (!empty($listado)) {
    echo "✅ Listado de CompraEstado obtenido:<br>";
    foreach ($listado as $obj) {
        echo "🧾 ID: {$obj->getIdCompraEstado()} - Compra: {$obj->getIdCompra()} - Tipo: {$obj->getIdCompraEstadoTipo()} - Fecha Ini: {$obj->getFechaIni()} - Fecha Fin: " . ($obj->getFechaFin() ?? 'N/A') . "<br>";
    }
} else {
    echo "❌ No se pudo obtener listado de CompraEstado: " . $abm->getMensajeError() . "<br>";
}

// 🔸 3. Prueba de Buscar por ID
echo "<h3>🔸 Prueba de buscar()</h3>";
// Usamos el ID del registro recién creado para esta prueba
$idBuscar = 18; 
if ($idBuscar !== null) {
    $buscado = $abm->buscar($idBuscar);
    if ($buscado) {
        echo "✅ CompraEstado encontrado con ID {$idBuscar}:<br>";
        echo "   ID: {$buscado->getIdCompraEstado()} - Compra: {$buscado->getIdCompra()} - Tipo: {$buscado->getIdCompraEstadoTipo()} - Fecha Ini: {$buscado->getFechaIni()} - Fecha Fin: " . ($buscado->getFechaFin() ?? 'N/A') . "<br>";
    } else {
        echo "❌ No se encontró el CompraEstado con ID {$idBuscar}: " . $abm->getMensajeError() . "<br>";
    }
} else {
    echo "⚠️ No se pudo realizar la prueba de buscar porque no se obtuvo un ID de alta.<br>";
}


// 🔸 4. Prueba de Modificar
echo "<h3>🔸 Prueba de modificar()</h3>";
// Modificamos el registro recién creado
$idModificar = 2;
if ($idModificar !== null) {
    $datosModificar = [
        'idcompraestadotipo' => 3, // Cambiamos a otro tipo de estado (ej. 'en proceso')
        'idcompraestado' => 2 // Establecemos una fecha de fin
    ];
    if ($abm->modificar($datosModificar)) {
        echo "✅ Modificación exitosa del CompraEstado con ID {$idModificar}.<br>";
        // Verificamos que se haya modificado
        $verificar = $abm->buscar($idModificar);
        if ($verificar) {
            echo "   Verificación: Nuevo Tipo: {$verificar->getIdCompraEstadoTipo()} - Nueva Fecha Fin: {$verificar->getFechaFin()}<br>";
        }
    } else {
        echo "❌ Error al modificar el CompraEstado con ID {$idModificar}: " . $abm->getMensajeError() . "<br>";
    }
} else {
    echo "⚠️ No se pudo realizar la prueba de modificar porque no se obtuvo un ID de alta.<br>";
}


// 🔸 5. Prueba de Baja (eliminar)
echo "<h3>🔸 Prueba de baja()</h3>";
// Eliminamos el registro recién creado
$idEliminar = 20;
if ($idEliminar !== null) {
    if ($abm->baja($idEliminar)) {
        echo "✅ CompraEstado con ID {$idEliminar} eliminado correctamente.<br>";
        // Intentar buscar para confirmar que fue eliminado
        $confirmarEliminacion = $abm->buscar($idEliminar);
        if ($confirmarEliminacion === null) {
            echo "   Confirmación: El registro ya no existe.<br>";
        } else {
            echo "   ⚠️ Confirmación: El registro AÚN existe después de intentar eliminar.<br>";
        }
    } else {
        echo "❌ Error al eliminar el CompraEstado con ID {$idEliminar}: " . $abm->getMensajeError() . "<br>";
    }
} else {
    echo "⚠️ No se pudo realizar la prueba de baja porque no se obtuvo un ID de alta.<br>";
}

        // $confirmarEliminacion = $abm->buscar(13);
        // if ($confirmarEliminacion === false) {
        //     echo "   Confirmación: El registro ya no existe.<br>";
        // } else {
        //     echo "   ⚠️ Confirmación: El registro AÚN existe después de intentar eliminar.<br>";
        // }
?>