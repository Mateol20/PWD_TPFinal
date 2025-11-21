<?php
include_once 'estructura/header.php';
// Inicializar los ABMs necesarios
$abmRol = new AbmRol();
$abmUsuario = new AbmUsuario();
$abmUsuarioRol = new AbmUsuarioRol();
$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = $_POST;

    // Convertir IDs a entero si existen
    if (isset($datos['idrol'])) {
        $datos['idrol'] = (int)$datos['idrol'];
    }
    if (isset($datos['idusuario'])) {
        $datos['idusuario'] = (int)$datos['idusuario'];
    }

    if (isset($datos['accion'])) {
        switch ($datos['accion']) {
            case 'nuevo':
                // ALTA: Crear un nuevo rol
                if (isset($datos['rodescripcion']) && !empty(trim($datos['rodescripcion']))) {
                    $resultado = $abmRol->alta($datos);
                    if ($resultado['resultado']) {
                        $mensaje = "Rol '{$datos['rodescripcion']}' creado con éxito.";
                    } else {
                        $mensaje = " Error al crear el rol: " . ($resultado['error'] ?: "Error desconocido.");
                    }
                } else {
                    $mensaje = " La descripción del rol no puede estar vacía.";
                }
                break;

            case 'modificar':
                // MODIFICACIÓN: Actualizar la descripción de un rol existente
                if (isset($datos['idrol']) && isset($datos['rodescripcion']) && !empty(trim($datos['rodescripcion']))) {
                    $resultado = $abmRol->modificacion($datos);
                    if ($resultado['resultado']) {
                        $mensaje = " Rol ID {$datos['idrol']} modificado con éxito.";
                    } else {
                        $mensaje = " Error al modificar el rol: " . ($resultado['error'] ?: "Error desconocido.");
                    }
                } else {
                    $mensaje = " Faltan datos necesarios para la modificación (ID o Descripción).";
                }
                break;

            case 'eliminar':
                // BAJA: Eliminar un rol
                if (isset($datos['idrol'])) {
                    $resultado = $abmRol->baja($datos);
                    if ($resultado['resultado']) {
                        $mensaje = " Rol ID {$datos['idrol']} eliminado con éxito.";
                    } else {
                        $mensaje = " Error al eliminar el rol. Posiblemente existan usuarios asignados. " . ($resultado['error'] ?: "Error desconocido.");
                    }
                } else {
                    $mensaje = " Falta el ID del rol a eliminar.";
                }
                break;

            case 'asignarRol':
                // ASIGNACIÓN: Asignar o cambiar el rol de un usuario
                if (isset($datos['idusuario']) && isset($datos['idrol'])) {
                    $idUsuario = (int)$datos['idusuario'];
                    $idNuevoRol = (int)$datos['idrol'];


                    $usuarioInfo = $abmUsuario->buscar(['idusuario' => $idUsuario]);
                    $nombreUsuario = count($usuarioInfo) > 0 ? htmlspecialchars($usuarioInfo[0]->getNombre()) : "Usuario ID $idUsuario";

                    $asignacionesActuales = $abmUsuarioRol->buscar(['idusuario' => $idUsuario]);
                    $exitoBaja = true;
                    foreach ($asignacionesActuales as $asignacion) {
                        $idRolABorrar = $asignacion->getObjRol()->getIdRol();

                        $bajaResult = $abmUsuarioRol->baja([
                            'idusuario' => $idUsuario,
                            'idrol' => $idRolABorrar
                        ]);

                        if (!$bajaResult['resultado']) {
                            $exitoBaja = false;
                            $mensaje = " Error al desasignar rol antiguo del {$nombreUsuario}: " . ($bajaResult['error'] ?: "Error desconocido.");
                            break;
                        }
                    }

                    if ($exitoBaja) {
                        // 2. Asignar nuevo rol (si se seleccionó uno, ID > 0)
                        if ($idNuevoRol > 0) {
                            $datosAsignacion = [
                                'idusuario' => $idUsuario,
                                'idrol' => $idNuevoRol,
                            ];
                            $resultado = $abmUsuarioRol->alta($datosAsignacion);

                            if ($resultado['resultado']) {
                                $rolInfo = $abmRol->buscar(['idrol' => $idNuevoRol]);
                                $nombreRol = count($rolInfo) > 0 ? $rolInfo[0]->getDescripcion() : "Rol ID $idNuevoRol";
                                $mensaje = " Rol '{$nombreRol}' asignado con éxito a {$nombreUsuario}.";
                            } else {
                                $mensaje = " Error al asignar el nuevo rol a {$nombreUsuario}: " . ($resultado['error'] ?: "Error desconocido.");
                            }
                        } else {
                            $mensaje = "Roles desasignados para {$nombreUsuario}.";
                        }
                    }
                } else {
                    $mensaje = " Faltan datos necesarios para la asignación de rol.";
                }
                break;
        }
    }
}



// Obtener lista de todos los roles
$roles = $abmRol->buscar(null);
$usuarios = $abmUsuario->buscar(null);
$rolesActualesPorUsuario = [];
$asignaciones = $abmUsuarioRol->buscar(null);
foreach ($asignaciones as $ur) {
    $rolesActualesPorUsuario[$ur->getObjUsuario()->getIdUsuario()] = $ur->getObjRol();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.2s ease-out;
        }

        .modal.mostrar-modal {
            display: flex;
        }

        .modal.mostrar-modal .modal-content {
            transform: scale(1);
            opacity: 1;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans p-4 md:p-8">

    <div class="max-w-4xl mx-auto bg-white p-6 md:p-10 rounded-xl shadow-2xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 pb-2 border-indigo-200">
            Administración de Roles
        </h1>

        <!-- Mensajes de Alerta -->
        <?php if (!empty($mensaje)): ?>
            <div id="alert-message" class="p-4 mb-6 text-sm text-white rounded-lg 
                <?= strpos($mensaje, '✅') !== false ? 'bg-green-500' : (strpos($mensaje, '❌') !== false ? 'bg-red-500' : 'bg-yellow-500') ?>"
                role="alert">
                <?= $mensaje ?>
            </div>
            <script>
                setTimeout(() => {
                    const alert = document.getElementById('alert-message');
                    if (alert) alert.style.opacity = 0;
                    setTimeout(() => {
                        if (alert) alert.style.display = 'none';
                    }, 500);
                }, 5000);
            </script>
        <?php endif; ?>

        <!-- Formulario de Creación de Rol (Alta) -->
        <div class="mb-8 border p-6 rounded-xl bg-indigo-50/50 shadow-inner">
            <h2 class="text-xl font-semibold text-indigo-700 mb-4">➕ Crear Nuevo Rol</h2>
            <form method="POST" action="gestionRoles.php" class="flex flex-col md:flex-row gap-4">
                <input type="hidden" name="accion" value="nuevo">

                <input type="text" name="rodescripcion" placeholder="Nombre del Rol (Ej: Admin, Editor)" required
                    class="flex-grow p-3 border border-indigo-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-150"
                    maxlength="50">

                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150 ease-in-out shadow-lg hover:shadow-xl">
                    Guardar Rol
                </button>
            </form>
        </div>

        <!-- Tabla de Roles (Listado) -->
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">📋 Roles Existentes (<?= count($roles) ?>)</h2>

        <?php if (count($roles) > 0): ?>
            <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 mb-10">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($roles as $rol): ?>
                            <tr class="hover:bg-gray-50 transition duration-100">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $rol->getIdRol() ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($rol->getDescripcion()) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <!-- Botón Modificar (Abre Modal de Edición) -->
                                    <button onclick="abrirModalModificar(<?= $rol->getIdRol() ?>, '<?= htmlspecialchars($rol->getDescripcion(), ENT_QUOTES) ?>')"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3 p-2 rounded-lg hover:bg-indigo-100 transition duration-150">
                                        Modificar
                                    </button>

                                    <!-- Botón Eliminar (Abre Modal de Confirmación) -->
                                    <button onclick="abrirModalEliminar(<?= $rol->getIdRol() ?>, '<?= htmlspecialchars($rol->getDescripcion(), ENT_QUOTES) ?>')"
                                        class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-100 transition duration-150">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-500 p-4 border border-dashed rounded-xl bg-yellow-50/50 mb-10">
                No hay roles definidos. Utilice el formulario de arriba para crear el primer rol.
            </p>
        <?php endif; ?>

        <div class="pt-6 border-t border-gray-200 mt-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4"> Asignación de Roles a Usuarios</h2>

            <?php if (count($usuarios) > 0): ?>
                <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nombre de Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Rol Actual</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Asignar Nuevo Rol</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($usuarios as $usuario):
                                $idUsuario = $usuario->getIdUsuario();
                                // Acceder al objeto Rol a través de la clave de mapeo
                                $rolActual = $rolesActualesPorUsuario[$idUsuario] ?? null;
                                // Si existe el objeto Rol, obtenemos su descripción
                                $descripcionRol = $rolActual ? $rolActual->getDescripcion() : 'Sin Rol Asignado';
                            ?>
                                <tr class="hover:bg-blue-50 transition duration-100">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $idUsuario ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"><?= htmlspecialchars($usuario->getNombre()) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-semibold"><?= $descripcionRol ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <form method="POST" action="gestionRoles.php" class="inline-flex gap-2 items-center">
                                            <input type="hidden" name="accion" value="asignarRol">
                                            <input type="hidden" name="idusuario" value="<?= $idUsuario ?>">

                                            <select name="idrol" class="p-2 border border-blue-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm">
                                                <option value="0">--- Desasignar Rol ---</option>
                                                <?php foreach ($roles as $rol): ?>
                                                    <?php
                                                    $selected = '';
                                                    if ($rolActual && $rolActual->getIdRol() === $rol->getIdRol()) {
                                                        $selected = 'selected';
                                                    }
                                                    ?>
                                                    <option value="<?= $rol->getIdRol() ?>" <?= $selected ?>>
                                                        <?= htmlspecialchars($rol->getDescripcion()) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                            <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-lg text-xs font-medium transition duration-150 shadow-md">
                                                Asignar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 p-4 border border-dashed rounded-xl bg-yellow-50/50">
                    No hay usuarios registrados para asignar roles.
                </p>
            <?php endif; ?>
        </div>

    </div>

    <!-- Modal para Modificación de Rol -->
    <div id="modalModificar" class="modal">
        <div class="modal-content">
            <h3 class="text-xl font-bold mb-4 text-indigo-700">Editar Rol</h3>
            <form method="POST" action="gestionRoles.php">
                <input type="hidden" name="accion" value="modificar">
                <input type="hidden" name="idrol" id="modal_modificar_idrol">

                <label for="modal_modificar_descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción del Rol:</label>
                <input type="text" name="rodescripcion" id="modal_modificar_descripcion" required
                    class="w-full p-3 border border-gray-300 rounded-lg mb-4 focus:ring-indigo-500 focus:border-indigo-500"
                    maxlength="50">

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModal('modalModificar')"
                        class="py-2 px-4 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Confirmación de Eliminación -->
    <div id="modalEliminar" class="modal">
        <div class="modal-content bg-red-50 border-2 border-red-300">
            <h3 class="text-xl font-bold mb-2 text-red-700">⚠️ Confirmar Eliminación</h3>
            <p class="text-gray-600 mb-6">
                ¿Está seguro de eliminar el rol **<span id="rol_a_eliminar_descripcion" class="font-semibold text-red-800"></span>**?
                Esta acción es irreversible y podría afectar a los usuarios asociados.
            </p>
            <form method="POST" action="gestionRoles.php">
                <input type="hidden" name="accion" value="eliminar">
                <input type="hidden" name="idrol" id="modal_eliminar_idrol">

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModal('modalEliminar')"
                        class="py-2 px-4 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                        Sí, Eliminar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mapeo de elementos de los modales
        const modalModificar = document.getElementById('modalModificar');
        const modalIdRolModificar = document.getElementById('modal_modificar_idrol');
        const modalDescripcionModificar = document.getElementById('modal_modificar_descripcion');

        const modalEliminar = document.getElementById('modalEliminar');
        const modalIdRolEliminar = document.getElementById('modal_eliminar_idrol');
        const modalDescripcionEliminar = document.getElementById('rol_a_eliminar_descripcion');

        // Función general para cerrar cualquier modal
        function cerrarModal(idModal) {
            document.getElementById(idModal).classList.remove('mostrar-modal');
        }

        // Función para abrir el modal de Modificar
        function abrirModalModificar(id, descripcion) {
            modalIdRolModificar.value = id;
            modalDescripcionModificar.value = descripcion;
            modalModificar.classList.add('mostrar-modal');
        }

        // Función para abrir el modal de Eliminar
        function abrirModalEliminar(id, descripcion) {
            modalIdRolEliminar.value = id;
            modalDescripcionEliminar.textContent = descripcion; // Usamos textContent para la seguridad
            modalEliminar.classList.add('mostrar-modal');
        }

        // Cierra el modal si se hace clic fuera de él
        window.onclick = function(event) {
            if (event.target == modalModificar) {
                cerrarModal('modalModificar');
            }
            if (event.target == modalEliminar) {
                cerrarModal('modalEliminar');
            }
        }
    </script>
</body>

</html>