<?php
// Incluir el controlador ABMRol para interactuar con la lógica de negocio
include_once 'estructura/header.php';

// Inicializar el ABM
$abmRol = new AbmRol();
$mensaje = ''; // Para mostrar mensajes de éxito o error al usuario

// ==========================================================
// 1. GESTIÓN DE ACCIONES (Alta, Baja, Modificación)
// ==========================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegurar que solo procesamos acciones si el formulario fue enviado
    $datos = $_POST;

    // Convertir el ID a entero si existe, para evitar problemas de tipo
    if (isset($datos['idrol'])) {
        $datos['idrol'] = (int)$datos['idrol'];
    }

    if (isset($datos['accion'])) {
        switch ($datos['accion']) {
            case 'nuevo':
                // ALTA: Crear un nuevo rol
                if (isset($datos['rodescripcion']) && !empty(trim($datos['rodescripcion']))) {
                    $resultado = $abmRol->alta($datos);
                    if ($resultado['resultado']) {
                        $mensaje = "✅ Rol '{$datos['rodescripcion']}' creado con éxito.";
                    } else {
                        $mensaje = "❌ Error al crear el rol: " . ($resultado['error'] ?: "Error desconocido.");
                    }
                } else {
                    $mensaje = "⚠️ La descripción del rol no puede estar vacía.";
                }
                break;

            case 'modificar':
                // MODIFICACIÓN: Actualizar la descripción de un rol existente
                if (isset($datos['idrol']) && isset($datos['rodescripcion']) && !empty(trim($datos['rodescripcion']))) {
                    $resultado = $abmRol->modificacion($datos);
                    if ($resultado['resultado']) {
                        $mensaje = "✅ Rol ID {$datos['idrol']} modificado con éxito.";
                    } else {
                        $mensaje = "❌ Error al modificar el rol: " . ($resultado['error'] ?: "Error desconocido.");
                    }
                } else {
                    $mensaje = "⚠️ Faltan datos necesarios para la modificación (ID o Descripción).";
                }
                break;

            case 'eliminar':
                // BAJA: Eliminar un rol
                if (isset($datos['idrol'])) {
                    $resultado = $abmRol->baja($datos);
                    if ($resultado['resultado']) {
                        $mensaje = "✅ Rol ID {$datos['idrol']} eliminado con éxito.";
                    } else {
                        $mensaje = "❌ Error al eliminar el rol. Posiblemente existan usuarios asignados a este rol. " . ($resultado['error'] ?: "Error desconocido.");
                    }
                } else {
                    $mensaje = "⚠️ Falta el ID del rol a eliminar.";
                }
                break;
        }
    }
}

// 2. OBTENER LISTA DE ROLES (para la tabla)
$roles = $abmRol->buscar(null);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    <!-- Usamos Tailwind CSS para un diseño moderno y responsive -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilos personalizados para el formulario modal de edición */
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
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }

        .mostrar-modal {
            display: flex;
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
            ⚙️ Administración de Roles
        </h1>

        <!-- Mensajes de Alerta -->
        <?php if (!empty($mensaje)): ?>
            <div id="alert-message" class="p-4 mb-6 text-sm text-white rounded-lg 
                <?= strpos($mensaje, '✅') !== false ? 'bg-green-500' : (strpos($mensaje, '❌') !== false ? 'bg-red-500' : 'bg-yellow-500') ?>"
                role="alert">
                <?= $mensaje ?>
            </div>
            <script>
                // Ocultar el mensaje después de 5 segundos
                setTimeout(() => {
                    const alert = document.getElementById('alert-message');
                    if (alert) alert.style.display = 'none';
                }, 5000);
            </script>
        <?php endif; ?>

        <!-- Formulario de Creación de Rol (Alta) -->
        <div class="mb-8 border p-6 rounded-lg bg-indigo-50/50">
            <h2 class="text-xl font-semibold text-indigo-700 mb-4">➕ Crear Nuevo Rol</h2>
            <form method="POST" action="gestionRoles.php" class="flex flex-col md:flex-row gap-4">
                <input type="hidden" name="accion" value="nuevo">

                <input type="text" name="rodescripcion" placeholder="Nombre del Rol (Ej: Admin, Editor)" required
                    class="flex-grow p-2 border border-indigo-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    maxlength="50">

                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out shadow-md">
                    Guardar Rol
                </button>
            </form>
        </div>

        <!-- Tabla de Roles (Listado) -->
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">📋 Roles Existentes (<?= count($roles) ?>)</h2>

        <?php if (count($roles) > 0): ?>
            <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200">
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
                                    <!-- Botón Modificar (Abre Modal) -->
                                    <button onclick="abrirModal(<?= $rol->getIdRol() ?>, '<?= htmlspecialchars($rol->getDescripcion(), ENT_QUOTES) ?>')"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3 p-1 rounded-md hover:bg-indigo-100 transition">
                                        ✏️ Modificar
                                    </button>

                                    <!-- Formulario/Botón Eliminar -->
                                    <form method="POST" action="gestionRoles.php" class="inline" onsubmit="return confirm('¿Está seguro de eliminar el rol \'<?= htmlspecialchars($rol->getDescripcion(), ENT_QUOTES) ?>\'? Esto podría afectar a los usuarios asociados.');">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="idrol" value="<?= $rol->getIdRol() ?>">
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-100 transition">
                                            🗑️ Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-500 p-4 border border-dashed rounded-lg bg-yellow-50/50">
                No hay roles definidos. Utilice el formulario de arriba para crear el primer rol.
            </p>
        <?php endif; ?>

        <!-- Aquí iría la sección de Asignación de Roles a Usuarios (próximo paso) -->
        <div class="mt-10 pt-6 border-t border-gray-200">
            <h2 class="text-xl font-semibold text-gray-700">Próximo Paso: Asignación de Roles</h2>
            <p class="text-gray-500">
                Una vez creados los roles, el siguiente paso lógico es implementar la interfaz
                para **asignar y desasignar estos roles a usuarios específicos** utilizando el modelo
                `UsuarioRol.php` que ya hemos definido.
            </p>
        </div>

    </div>

    <!-- Modal para Modificación de Rol -->
    <div id="modalModificar" class="modal">
        <div class="modal-content">
            <h3 class="text-xl font-bold mb-4 text-indigo-700">Editar Rol</h3>
            <form method="POST" action="gestionRoles.php">
                <input type="hidden" name="accion" value="modificar">
                <input type="hidden" name="idrol" id="modal_idrol">

                <label for="modal_descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción del Rol:</label>
                <input type="text" name="rodescripcion" id="modal_descripcion" required
                    class="w-full p-3 border border-gray-300 rounded-lg mb-4 focus:ring-indigo-500 focus:border-indigo-500"
                    maxlength="50">

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModal()"
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

    <script>
        const modal = document.getElementById('modalModificar');
        const modalIdRol = document.getElementById('modal_idrol');
        const modalDescripcion = document.getElementById('modal_descripcion');

        // Función para abrir el modal
        function abrirModal(id, descripcion) {
            modalIdRol.value = id;
            modalDescripcion.value = descripcion;
            modal.classList.add('mostrar-modal');
        }

        // Función para cerrar el modal
        function cerrarModal() {
            modal.classList.remove('mostrar-modal');
        }

        // Cierra el modal si se hace clic fuera de él
        window.onclick = function(event) {
            if (event.target == modal) {
                cerrarModal();
            }
        }
    </script>
</body>

</html>