<?php
// ====================================================================
// !!! INCLUIR ARCHIVOS REQUERIDOS !!!
// ====================================================================
// Asegúrate de que configuracion.php incluya Menu.php, Rol.php, menuRol.php y BaseDatos.php
require_once '../configuracion.php';

// Variables para almacenar los IDs de los registros de prueba
$idMenuPrueba = null;
$idRolPrueba = null;

// ====================================================================

echo "\n## 🚀 Iniciando Test Funcional de menuRol 🚀\n";
echo "--------------------------------------------------------\n";

// --- Paso 0: SETUP - Insertar dependencias (Menu y Rol) ---
echo "### 0. SETUP: Creando dependencias (Menu y Rol) ###\n";

// 0.1 Crear y guardar un Menu de prueba
$menuTest = new Menu();
$menuTest->setear(null, "Menu Test Rol", "Para la relación de prueba", null, null);

if ($menuTest->insertar()) {
    $idMenuPrueba = $menuTest->getIdMenu();
    echo "✅ Menú de prueba creado con ID: {$idMenuPrueba}\n";
} else {
    echo "❌ ERROR FATAL: No se pudo crear el Menu de prueba. Mensaje: " . $menuTest->getMensajeError() . "\n";
    die();
}

// 0.2 Crear y guardar un Rol de prueba
$rolTest = new Rol();
$rolTest->setDescripcion("Rol Test Menu");

if ($rolTest->insert()) { // <-- ÚNICA INSERCIÓN DE ROL. El ID queda guardado en $rolTest.
    $idRolPrueba = $rolTest->getIdRol();
    echo "✅ Rol de prueba creado con ID: {$idRolPrueba}\n";
} else {
    echo "❌ ERROR FATAL: No se pudo crear el Rol de prueba. Mensaje: (Revisa la salida de Rol::insert)\n";
    die();
}

// --------------------------------------------------------

// ===============================================
// 1. TEST DE INSERCIÓN (CREATE) en menuRol
// ===============================================
echo "### 1. Test de Inserción (Crear Relación) ###\n";

$relacion = new menuRol();
// Usamos los objetos Menu y Rol que ya tienen IDs válidos.
$relacion->setear($menuTest, $rolTest);

if ($relacion->insertar()) {
    echo "✅ Éxito: Relación (Menú {$idMenuPrueba} -> Rol {$idRolPrueba}) insertada en menuRol.\n";
} else {
    echo "❌ Fallo: Error al insertar la relación.\n";
    echo "Mensaje de error: " . $relacion->getMensajeError() . "\n";
}

echo "--------------------------------------------------------\n";

// ===============================================
// 2. TEST DE OBTENER POR ID (READ)
// ===============================================
echo "### 2. Test de Obtener Por ID (Leer Relación) ###\n";

$relacionLeida = new menuRol();
// Inicializamos la búsqueda con los IDs que esperamos encontrar.
$menuBusqueda = new Menu();
$menuBusqueda->setIdMenu($idMenuPrueba);
$rolBusqueda = new Rol();
$rolBusqueda->setIdRol($idRolPrueba);

$relacionLeida->setear($menuBusqueda, $rolBusqueda);

if ($relacionLeida->obtenerPorId()) {
    echo "✅ Éxito: Relación leída.\n";
    echo "  IDs recuperados: Menu ID " . $relacionLeida->getObjMenu()->getIdMenu() .
        " y Rol ID " . $relacionLeida->getObjRol()->getIdRol() . "\n";
} else {
    echo "❌ Fallo: No se encontró la relación.\n";
    echo "Mensaje de error: " . $relacionLeida->getMensajeError() . "\n";
}

echo "--------------------------------------------------------\n";

// ===============================================
// 3. CLEANUP - ELIMINACIÓN (DELETE)
// ===============================================
echo "### 3. CLEANUP: Eliminando registros de prueba ###\n";

$todoOK = true;

// // 3.1 Eliminar la relación menuRol
// if ($relacion->eliminar()) {
//     echo "✅ Éxito: La relación menuRol se eliminó.\n";
// } else {
//     echo "❌ Fallo: Error al eliminar la relación menuRol.\n";
//     $todoOK = false;
// }

// // 3.2 Eliminar el Rol de prueba
// if ($rolTest->eliminar($idRolPrueba)) {
//     echo "✅ Éxito: Rol de prueba eliminado.\n";
// } else {
//     echo "❌ Fallo: Error al eliminar el Rol de prueba.\n";
//     $todoOK = false;
// }

// // 3.3 Eliminar el Menu de prueba
// if ($menuTest->eliminar()) {
//     echo "✅ Éxito: Menu de prueba eliminado.\n";
// } else {
//     echo "❌ Fallo: Error al eliminar el Menu de prueba.\n";
//     $todoOK = false;
// }

// echo "--------------------------------------------------------\n";
// echo "## 🏁 Test menuRol Finalizado 🏁\n";
