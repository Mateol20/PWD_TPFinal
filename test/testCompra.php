<?php
include_once '../configuracion.php';
// =====================
// 🔹 TEST DE abmCompra
// =====================
echo "===== TEST abmCompra =====\n";

$abm = new abmCompra();

// 1️⃣ ALTA
echo "\n-- Alta de compra --\n";
$idUsuario = 1; // <- usá un idusuario válido de tu tabla Usuario
if ($abm->alta($idUsuario)) {
    echo "✅ Alta exitosa\n";
} else {
    echo "❌ Error en alta: " . $abm->getMensajeError() . "\n";
}

// 2️⃣ LISTAR
echo "\n-- Listado de compras --\n";
$compras = $abm->listar();
if ($compras) {
    foreach ($compras as $c) {
        echo "Compra ID: {$c['idcompra']} | Usuario: {$c['idusuario']} | Fecha: {$c['cofecha']}\n";
    }
} else {
    echo "❌ No se pudieron listar las compras\n";
}

// 3️⃣ BUSCAR
echo "\n-- Buscar compra --\n";
$ultima = end($compras); // tomo la última compra insertada
$idCompra = $ultima['idcompra'] ?? null;
if ($idCompra) {
    $resultado = $abm->buscar($idCompra);
    if ($resultado) {
        echo "✅ Compra encontrada:\n";
        print_r($resultado);
    } else {
        echo "❌ No se encontró la compra con ID $idCompra\n";
    }
} else {
    echo "⚠️ No hay compras para buscar\n";
}

// 4️⃣ MODIFICAR
echo "\n-- Modificar compra --\n";
if ($idCompra) {
    $datos = [
        'idcompra' => $idCompra,
        'idusuario' => 3 // probá cambiar de usuario
    ];
    if ($abm->modificar($datos)) {
        echo "✅ Compra modificada correctamente\n";
    } else {
        echo "❌ Error al modificar: " . $abm->getMensajeError() . "\n";
    }
}

// 5️⃣ BAJA
echo "\n-- Eliminar compra --\n";
if ($idCompra && $abm->baja($idCompra)) {
    echo "✅ Compra eliminada correctamente\n";
} else {
    echo "❌ Error al eliminar compra ID $idCompra\n";
}

echo "\n===== FIN DEL TEST =====\n";
?>
