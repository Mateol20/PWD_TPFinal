<?php
// =================================================================
// 1. CONFIGURACIÓN E INCLUSIONES (ANTES DE CUALQUIER HTML)
// =================================================================

require_once __DIR__ . '/../configuracion.php';
require_once __DIR__ . '/../util/VerificadorAcceso.php';


// Verificar acceso
verificarAcceso('gestionRoles.php');

// Incluir Header (usa $ROOT definido en configuracion.php)
require_once $ROOT . "Vista/estructura/header.php";

// Inicializar ABMs
$abmRol = new AbmRol();
$abmUsuario = new AbmUsuario();
$abmUsuarioRol = new AbmUsuarioRol();

$mensaje = '';


// =================================================================
// 2. PROCESAMIENTO DE FORMULARIOS
// =================================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = $_POST;

    // Convertir IDs a entero si existen
    if (isset($datos['idrol']))  $datos['idrol'] = (int)$datos['idrol'];
    if (isset($datos['idusuario'])) $datos['idusuario'] = (int)$datos['idusuario'];

    if (isset($datos['accion'])) {
        switch ($datos['accion']) {

            // -----------------------------------------
            // ALTA ROL
            // -----------------------------------------
            case 'nuevo':
                if (!empty(trim($datos['rodescripcion']))) {
                    $res = $abmRol->alta($datos);
                    $mensaje = $res['resultado']
                        ? "Rol '{$datos['rodescripcion']}' creado con éxito."
                        : "Error al crear el rol: " . ($res['error'] ?? "Error desconocido.");
                } else {
                    $mensaje = "La descripción no puede estar vacía.";
                }
                break;

            // -----------------------------------------
            // MODIFICAR ROL
            // -----------------------------------------
            case 'modificar':
                if (!empty(trim($datos['rodescripcion'])) && !empty($datos['idrol'])) {
                    $res = $abmRol->modificacion($datos);
                    $mensaje = $res['resultado']
                        ? "Rol modificado correctamente."
                        : "Error al modificar el rol: " . ($res['error'] ?? "Error desconocido.");
                } else {
                    $mensaje = "Faltan datos para modificar.";
                }
                break;

            // -----------------------------------------
            // ELIMINAR ROL
            // -----------------------------------------
            case 'eliminar':
                if (!empty($datos['idrol'])) {
                    $res = $abmRol->baja($datos);
                    $mensaje = $res['resultado']
                        ? "Rol eliminado correctamente."
                        : "Error al eliminar rol: " . ($res['error'] ?? "Error desconocido.");
                } else {
                    $mensaje = "ID de rol faltante.";
                }
                break;

            // -----------------------------------------
            // ASIGNAR / DESASIGNAR ROL A USUARIO
            // -----------------------------------------
            case 'asignarRol':
                $idUsuario = $datos['idusuario'];
                $idNuevoRol = $datos['idrol'];

                // borrar roles actuales
                $asignaciones = $abmUsuarioRol->buscar(['idusuario' => $idUsuario]);

                foreach ($asignaciones as $asig) {
                    $abmUsuarioRol->baja([
                        'idusuario' => $idUsuario,
                        'idrol' => $asig->getObjRol()->getIdRol()
                    ]);
                }

                // si seleccionó un rol, asignarlo
                if ($idNuevoRol > 0) {
                    $res = $abmUsuarioRol->alta([
                        'idusuario' => $idUsuario,
                        'idrol' => $idNuevoRol
                    ]);

                    $rolInfo = $abmRol->buscar(['idrol' => $idNuevoRol])[0];
                    $mensaje = $res['resultado']
                        ? "Rol '{$rolInfo->getDescripcion()}' asignado al usuario."
                        : "Error asignando nuevo rol.";
                } else {
                    $mensaje = "Rol desasignado correctamente.";
                }

                break;
        }
    }
}


// =================================================================
// 3. CARGA DE DATOS PARA MOSTRAR
// =================================================================

$roles = $abmRol->buscar(null);
$usuarios = $abmUsuario->buscar(null);

// obtener roles actuales por usuario
$rolesActualesPorUsuario = [];
foreach ($abmUsuarioRol->buscar(null) as $ur) {
    $rolesActualesPorUsuario[$ur->getObjUsuario()->getIdUsuario()] = $ur->getObjRol();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Roles</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans p-4 md:p-8">

    <div class="max-w-4xl mx-auto bg-white p-6 md:p-10 rounded-xl shadow-2xl">

        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 pb-2 border-indigo-200">
            Administración de Roles
        </h1>

        <!-- =============================== -->
        <!-- MENSAJE -->
        <!-- =============================== -->
        <?php if (!empty($mensaje)): ?>
            <div class="p-4 mb-6 bg-indigo-600 text-white rounded-xl shadow">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <!-- =============================== -->
        <!-- CREAR ROL -->
        <!-- =============================== -->
        <h2 class="text-2xl font-semibold mb-4">Crear Nuevo Rol</h2>

        <form method="POST" class="flex gap-4 mb-10">
            <input type="hidden" name="accion" value="nuevo">

            <input type="text" name="rodescripcion"
                placeholder="Descripción del Rol"
                class="flex-grow p-3 border rounded-lg">

            <button class="bg-indigo-600 text-white px-6 rounded-lg">Guardar</button>
        </form>

        <!-- =============================== -->
        <!-- LISTADO DE ROLES -->
        <!-- =============================== -->
        <h2 class="text-2xl font-semibold mb-4">Roles Existentes</h2>

        <table class="w-full border mb-10">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Descripción</th>
                    <th class="px-4 py-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $r): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?= $r->getIdRol() ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($r->getDescripcion()) ?></td>
                        <td class="px-4 py-2 text-center">

                            <!-- modificar -->
                            <button onclick="abrirModalModificar(<?= $r->getIdRol() ?>, '<?= htmlspecialchars($r->getDescripcion(), ENT_QUOTES) ?>')"
                                class="text-blue-600 mr-3">Modificar</button>

                            <!-- eliminar -->
                            <button onclick="abrirModalEliminar(<?= $r->getIdRol() ?>, '<?= htmlspecialchars($r->getDescripcion(), ENT_QUOTES) ?>')"
                                class="text-red-600">Eliminar</button>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <!-- =============================== -->
        <!-- ASIGNAR ROLES A USUARIOS -->
        <!-- =============================== -->
        <h2 class="text-2xl font-semibold mb-4">Asignar Roles a Usuarios</h2>

        <table class="w-full border">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Usuario</th>
                    <th class="px-4 py-2 text-left">Rol Actual</th>
                    <th class="px-4 py-2 text-center">Asignar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <?php
                    $id = $u->getIdUsuario();
                    $rolActual = $rolesActualesPorUsuario[$id] ?? null;
                    ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?= $id ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($u->getNombre()) ?></td>
                        <td class="px-4 py-2">
                            <?= $rolActual ? htmlspecialchars($rolActual->getDescripcion()) : "Sin Rol" ?>
                        </td>
                        <td class="px-4 py-2 text-center">

                            <form method="POST" class="inline-flex gap-2">
                                <input type="hidden" name="accion" value="asignarRol">
                                <input type="hidden" name="idusuario" value="<?= $id ?>">

                                <select name="idrol" class="p-2 border rounded">
                                    <option value="0">Desasignar rol</option>

                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= $r->getIdRol() ?>"
                                            <?= ($rolActual && $rolActual->getIdRol() == $r->getIdRol()) ? "selected" : "" ?>>
                                            <?= htmlspecialchars($r->getDescripcion()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <button class="bg-blue-600 text-white px-4 rounded">Asignar</button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>


    <!-- =============================== -->
    <!-- MODALES (iguales a los tuyos) -->
    <!-- =============================== -->
    <div id="modalModificar" class="modal">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="accion" value="modificar">
                <input type="hidden" name="idrol" id="modal_modificar_idrol">

                <input type="text" name="rodescripcion" id="modal_modificar_descripcion"
                    class="w-full p-2 border rounded mb-4">

                <button class="bg-indigo-600 text-white px-4 py-2 rounded">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <div id="modalEliminar" class="modal">
        <div class="modal-content bg-red-50 p-6 border rounded">
            <form method="POST">
                <input type="hidden" name="accion" value="eliminar">
                <input type="hidden" name="idrol" id="modal_eliminar_idrol">

                <p class="mb-4">¿Eliminar rol <b id="rol_a_eliminar_descripcion"></b>?</p>

                <button class="bg-red-600 text-white px-4 py-2 rounded">Eliminar</button>
            </form>
        </div>
    </div>

    <script>
        function abrirModalModificar(id, descripcion) {
            document.getElementById('modal_modificar_idrol').value = id;
            document.getElementById('modal_modificar_descripcion').value = descripcion;
            document.getElementById('modalModificar').style.display = "flex";
        }

        function abrirModalEliminar(id, descripcion) {
            document.getElementById('modal_eliminar_idrol').value = id;
            document.getElementById('rol_a_eliminar_descripcion').innerText = descripcion;
            document.getElementById('modalEliminar').style.display = "flex";
        }
    </script>

</body>

</html>