<?php
include_once '../configuracion.php';

// 🔹 Crear instancia del ABM
$abm = new ABMCompraEstadoTipo();

// 🔸 1. Alta (insertar nuevo registro)
echo "<h3>🔸 Prueba de alta()</h3>";
$nuevo = [
    'cetdescripcion' => 'En preparación',
    'cetdetalle' => 'La compra fue registrada y está en proceso de armado'
];

if ($abm->alta($nuevo)) {
    echo "✅ Alta realizada correctamente.<br>";
} else {
    echo "❌ Error al realizar el alta: " . $abm->getMensajeError() . "<br>";
}

// 🔸 2. Listar todos
echo "<h3>🔸 Prueba de listar()</h3>";
$listado = $abm->listar();
if ($listado) {
    foreach ($listado as $obj) {
        echo "🧾 {$obj->getIdCompraEstadoTipo()} - {$obj->getCetDescripcion()} - {$obj->getCetDetalle()}<br>";
    }
} else {
    echo "❌ No se pudo obtener listado.<br>";
}

// 🔸 3. Buscar por ID (suponiendo el último ID insertado)
echo "<h3>🔸 Prueba de buscar()</h3>";
$ultimoId = 1; // ⚠️ Cambiar según tus datos reales
$buscado = $abm->buscar($ultimoId);
if ($buscado) {
    echo "✅ Registro encontrado: {$buscado->getCetDescripcion()} - {$buscado->getCetDetalle()}<br>";
} else {
    echo "❌ No se encontró el registro con ID {$ultimoId}.<br>";
}

// 🔸 4. Modificar
echo "<h3>🔸 Prueba de modificar()</h3>";
$cambios = [
    'cetdescripcion' => 'Listo para envío',
    'cetdetalle' => 'La compra ya fue empacada y está lista para ser despachada'
];
if ($abm->modificar($cambios, $ultimoId)) {
    echo "✅ Modificación exitosa.<br>";
} else {
    echo "❌ Error al modificar: " . $abm->getMensajeError() . "<br>";
}

// 🔸 5. Eliminar
echo "<h3>🔸 Prueba de baja()</h3>";
if ($abm->baja($ultimoId)) {
    echo "✅ Registro eliminado correctamente.<br>";
} else {
    echo "❌ Error al eliminar: " . $abm->getMensajeError() . "<br>";
}
?>
