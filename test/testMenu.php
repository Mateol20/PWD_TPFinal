<?php
include_once '../configuracion.php';
## 🚀 Iniciando el Test de la Clase Menu 🚀

// ID de prueba que usaremos para todas las operaciones
$idMenuPrueba = 99;

// ------------------------------------
// PASO 1: INSERTAR (CREATE)
// ------------------------------------
echo "\n### 1. Test de Inserción (Crear) ###\n";

$menuNuevo = new Menu();
$menuNuevo->setIdMenu($idMenuPrueba);
$menuNuevo->setMeNombre("Menú Test");
$menuNuevo->setMeDescripcion("Descripción para prueba CRUD");
$menuNuevo->setIdPadre(null); // Menú raíz
$menuNuevo->setMeDeshabilitado(null);

if ($menuNuevo->insertar()) {
    echo "✅ Éxito: El menú se insertó correctamente.\n";
} else {
    echo "❌ Fallo: Error al insertar el menú.\n";
    echo "Mensaje de error: " . $menuNuevo->getMensajeError() . "\n";
}

// ------------------------------------
// PASO 2: OBTENER (READ) - Usando el ID
// ------------------------------------
echo "\n### 2. Test de Lectura (Obtener) ###\n";

$menuLeido = new Menu();
$menuLeido->setIdMenu(13);

if ($menuLeido->obtenerPorId()) {
    echo "✅ Éxito: Menú leído.\n";
    echo "Nombre: " . $menuLeido->getMeNombre() . "\n";
    echo "Descripción: " . $menuLeido->getMeDescripcion() . "\n";
} else {
    echo "❌ Fallo: Error al leer el menú con ID " . $idMenuPrueba . ".\n";
    echo "Mensaje de error: " . $menuLeido->getMensajeError() . "\n";
}

// ------------------------------------
// PASO 3: MODIFICAR (UPDATE)
// ------------------------------------
echo "\n### 3. Test de Modificación (Actualizar) ###\n";

// Usamos el objeto $menuLeido para modificar
$menuLeido->setMeNombre("Menú Test Actualizado");
$menuLeido->setMeDescripcion("Descripción actualizada del menú");

if ($menuLeido->modificar()) {
    echo "✅ Éxito: El menú se modificó correctamente.\n";

    // Verificamos la modificación intentando leerlo de nuevo
    $menuVerificar = new Menu();
    $menuVerificar->setIdMenu($idMenuPrueba);
    $menuVerificar->obtenerPorId();
    echo "Nuevo nombre verificado: " . $menuVerificar->getMeNombre() . "\n";
} else {
    echo "❌ Fallo: Error al modificar el menú.\n";
    echo "Mensaje de error: " . $menuLeido->getMensajeError() . "\n";
}

// ------------------------------------
// PASO 4: ELIMINAR (DELETE)
// ------------------------------------
echo "\n### 4. Test de Eliminación (Borrar) ###\n";

$menuEliminar = new Menu();
$menuEliminar->setIdMenu(14);

if ($menuEliminar->eliminar()) {
    echo "✅ Éxito: El menú se eliminó correctamente.\n";
} else {
    echo "❌ Fallo: Error al eliminar el menú.\n";
    echo "Mensaje de error: " . $menuEliminar->getMensajeError() . "\n";
}

// ------------------------------------
// PASO 5: VERIFICAR ELIMINACIÓN (Intento de lectura fallido)
// ------------------------------------
echo "\n### 5. Verificación de Eliminación ###\n";

$menuVerifDelete = new Menu();
$menuVerifDelete->setIdMenu($idMenuPrueba);

if (!$menuVerifDelete->obtenerPorId()) {
    echo "✅ Éxito: El menú no se pudo leer (se eliminó correctamente).\n";
} else {
    echo "❌ Fallo: El menú todavía existe en la base de datos.\n";
}
