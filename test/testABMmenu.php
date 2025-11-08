<?php
include_once '../configuracion.php';
$idMenuPrueba = 16;

// Crear una instancia del ABM
$abmMenu = new ABMMenu();

echo "## 🚀 Iniciando Test de ABMMenu 🚀\n";
echo "-------------------------------------\n";

// ===============================================
// 1. TEST DE ALTA (CREATE)
// ===============================================
echo "### 1. Test de Alta (Crear) ###\n";

// Datos simulados que vendrían de un formulario POST para un nuevo menú
$datosAlta = [
    'idmenu' => $idMenuPrueba,           // Si el ID es autoincremental, esto sería null o se omitiría
    'menombre' => 'Menu de Prueba ABM',
    'medescripcion' => 'Descripción para el test de alta',
    'idpadre' => null,                  // Menú raíz (debe ser NULL en la BD)
    'medeshabilitado' => null
];

if ($abmMenu->alta($datosAlta)) {
    echo "✅ Éxito: El menú con ID {$idMenuPrueba} se dio de alta correctamente.\n";
} else {
    echo "❌ Fallo: Error al dar de alta el menú.\n";
    echo "Mensaje de error: " . $abmMenu->getMensajeError() . "\n";
}

echo "-------------------------------------\n";

// ===============================================
// 2. TEST DE BÚSQUEDA (READ)
// ===============================================
echo "### 2. Test de Búsqueda (Leer) ###\n";

// Parámetros para buscar el menú recién creado
$paramBuscar = ['idmenu' => $idMenuPrueba];
$menusEncontrados = $abmMenu->buscar($paramBuscar);

if (!empty($menusEncontrados) && count($menusEncontrados) == 1) {
    echo "✅ Éxito: Menú encontrado.\n";
    $menuEncontrado = $menusEncontrados[0];
    echo "  Nombre leído: " . $menuEncontrado->getMeNombre() . "\n";
} else {
    echo "❌ Fallo: No se encontró el menú con ID {$idMenuPrueba}.\n";
}

echo "-------------------------------------\n";

// ===============================================
// 3. TEST DE MODIFICACIÓN (UPDATE)
// ===============================================
echo "### 3. Test de Modificación (Actualizar) ###\n";

// Datos simulados para la modificación
$datosModificacion = [
    'idmenu' => $idMenuPrueba,
    'menombre' => 'Menu Modificado por Test', // Nuevo nombre
    'medescripcion' => 'Descripción actualizada exitosamente',
    'idpadre' => null,
    'medeshabilitado' => null
];

if ($abmMenu->modificacion($datosModificacion)) {
    echo "✅ Éxito: El menú con ID {$idMenuPrueba} se modificó correctamente.\n";

    // Verificación de la modificación
    $menusVerif = $abmMenu->buscar($paramBuscar);
    if (!empty($menusVerif)) {
        echo "  Nuevo nombre verificado: " . $menusVerif[0]->getMeNombre() . "\n";
    }
} else {
    echo "❌ Fallo: Error al modificar el menú.\n";
    echo "Mensaje de error: " . $abmMenu->getMensajeError() . "\n";
}

echo "-------------------------------------\n";

// ===============================================
// 4. TEST DE BAJA (DELETE)
// ===============================================
echo "### 4. Test de Baja (Eliminar) ###\n";

// Datos para la baja (solo necesitamos el ID)
$datosBaja = ['idmenu' => $idMenuPrueba];

if ($abmMenu->baja($datosBaja)) {
    echo "✅ Éxito: El menú con ID {$idMenuPrueba} se dio de baja correctamente.\n";

    // Verificación de la eliminación
    $menusPostBaja = $abmMenu->buscar($paramBuscar);
    if (empty($menusPostBaja)) {
        echo "✅ Éxito: Verificación de la baja: El menú ya no existe en la BD.\n";
    } else {
        echo "❌ Fallo: El menú todavía se encontró después de la baja.\n";
    }
} else {
    echo "❌ Fallo: Error al dar de baja el menú.\n";
    echo "Mensaje de error: " . $abmMenu->getMensajeError() . "\n";
}

echo "-------------------------------------\n";
echo "## 🏁 Test ABMMenu Finalizado 🏁\n";
