<?php
include_once '../configuracion.php'; // Ajusta la ruta si es necesario

// 🔹 Crear instancia del ABM
$abm = new ABMCompraEstado();

// 🔸 1. Prueba de Alta (insertar nuevo registro)
echo "<h3>🔸 Prueba de alta()</h3>";
$datosAlta = [
    'idcompra' => 101, // ID de una compra existente (asegúrate de que exista)
    'idcompraestadotipo' => 1, // ID de un tipo de estado de compra existente (ej. 'iniciada')
    'cefechaini' => date('Y-m-d H:i:s'), // Fecha de inicio actual
    'cefechafin' => null // Sin fecha de fin por ahora
];

if ($abm->alta($datosAlta)) {
    echo "✅ Alta realizada correctamente.<br>";
    // Intentar buscar el último ID insertado (requiere que el método insertar lo retorne/guarde)
    $ultimoIdInsertado = 0; // Debes implementar getLastInsertId en BaseDatos y que CompraEstado lo use.
    // Por ahora, para pruebas, asumimos un ID
    echo "ID asignado (aproximado si es AUTO_INCREMENT): " . $ababm->buscar(null)->getIdCompraEstado()."<br>"; // Este es un placeholder
    // Mejor si el método alta de ABM te devuelve el objeto completo o su ID.
    // Por ahora, asumiremos que si se hace el alta, podemos buscar uno que debería existir.
    
    // Para el test, vamos a buscar el último creado si lo necesitamos en baja/modificar
    $listadoAux = $abm->listar("idcompra = 101 ORDER BY idcompraestado DESC LIMIT 1");
    $idRecienCreado = count($listadoAux) > 0 ? $listadoAux[0]->getIdCompraEstado() : null;

} else {
    echo "❌ Error al realizar el alta: " . $abm->getMensajeError() . "<br>";
    $idRecienCreado = null;
}

// 🔸 2. Prueba de Listar todos
echo "<h3>🔸 Prueba de listar()</h3>";
$listado = $abm->listar();
if (!empty($listado)) {
    echo "✅ Listado obtenido:<br>";
    foreach ($listado as $obj) {
        echo "🧾 ID: {$obj->getIdCompraEstado()} - Compra: {$obj->getIdCompra()} - Tipo: {$obj->getIdCompraEstadoTipo()} - Ini: {$obj->getFechaIni()} - Fin: " . ($obj->getFechaFin() ?? 'N/A') . "<br>";
    }
} else {
    echo "❌ No se pudo obtener listado o está vacío: " . $abm->getMensajeError() . "<br>";
}

// Si no se creó nada, las siguientes pruebas no tienen sentido
if ($idRecienCreado === null) {
    echo "⚠️ No se pudo obtener el ID del registro creado. Saltando pruebas de buscar, modificar y baja.<br>";
} else {
    echo "<h4>ID del registro recién creado para pruebas subsiguientes: {$idRecienCreado}</h4>";

    // 🔸 3. Prueba de Buscar por ID
    echo "<h3>🔸 Prueba de buscar({$idRecienCreado})</h3>";
    $buscado = $abm->buscar($idRecienCreado);
    if ($buscado) {
        echo "✅ Registro encontrado:<br>";
        echo "  ID: {$buscado->getIdCompraEstado()} - Compra: {$buscado->getIdCompra()} - Tipo: {$buscado->getIdCompraEstadoTipo()} - Ini: {$buscado->getFechaIni()} - Fin: " . ($buscado->getFechaFin() ?? 'N/A') . "<br>";
    } else {
        echo "❌ No se encontró el registro con ID {$idRecienCreado}: " . $abm->getMensajeError() . "<br>";
    }

    // 🔸 4. Prueba de Modificar
    echo "<h3>🔸 Prueba de modificar({$idRecienCreado})</h3>";
    $datosModificar = [
        'idcompraestado' => $idRecienCreado, // ID del registro a modificar
        'idcompraestadotipo' => 2, // Nuevo tipo de estado (ej. 'procesando')
        'cefechafin' => date('Y-m-d H:i:s') // Establecer fecha de fin
    ];
    if ($abm->modificar($datosModificar)) {
        echo "✅ Modificación exitosa del ID {$idRecienCreado}.<br>";
        // Verificar la modificación
        $verificar = $abm->buscar($idRecienCreado);
        if ($verificar) {
            echo "  Verificación: Tipo: {$verificar->getIdCompraEstadoTipo()} - Fin: {$verificar->getFechaFin()}<br>";
        }
    } else {
        echo "❌ Error al modificar ID {$idRecienCreado}: " . $abm->getMensajeError() . "<br>";
    }

    // 🔸 5. Prueba de Baja (eliminar)
    echo "<h3>🔸 Prueba de baja({$idRecienCreado})</h3>";
    if ($abm->baja($idRecienCreado)) {
        echo "✅ Registro con ID {$idRecienCreado} eliminado correctamente.<br>";
        // Intentar buscar para confirmar eliminación
        $confirmarEliminacion = $abm->buscar($idRecienCreado);
        if ($confirmarEliminacion === null) {
            echo "  Confirmación: El registro ya no existe.<br>";
        } else {
            echo "  ⚠️ Confirmación: El registro AÚN existe después de intentar eliminar.<br>";
        }
    } else {
        echo "❌ Error al eliminar ID {$idRecienCreado}: " . $abm->getMensajeError() . "<br>";
    }
}
?>