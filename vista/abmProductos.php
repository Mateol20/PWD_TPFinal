<?php
include_once "../configuracion.php";
include_once "../Control/pagPublica.php";

$objControl = new AbmProducto();
$List_Producto = $objControl->buscar(null);
?>

<?php include_once "./Estructura/header.php"; ?>

<div class="ui hidden divider"></div>
<div class="ui container grid center aligned">

    <div class="ui sixteen wide column">

        <h2>ABM - Productos (Depósito) 📦</h2>
        <p>Control de inventario: Agregue, edite o elimine productos.</p>

        <div id="messageContainer" class="ui hidden message"></div>

        <table class="ui celled table">
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Detalle</th>
                    <th>Cantidad en Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="productosTableBody">
                <?php foreach ($List_Producto as $producto) : ?>
                    <tr data-id="<?php echo $producto->getIdProducto(); ?>">
                        <td data-field="idproducto"><?php echo $producto->getIdProducto(); ?></td>
                        <td data-field="pronombre"><?php echo $producto->getProNombre(); ?></td>
                        <td data-field="prodetalle"><?php echo $producto->getProDetalle(); ?></td>
                        <td data-field="procantstock"><?php echo $producto->getProCantStock(); ?></td>
                        <td>
                            <button class="ui teal button" onclick="editProducto(<?php echo $producto->getIdProducto(); ?>)">Editar</button>
                            <button class="ui red button" onclick="confirmDestroyProducto(<?php echo $producto->getIdProducto(); ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="ui buttons">
            <button class="ui primary button" onclick="newProducto()">➕ Nuevo Producto</button>
        </div>

        <div id="dlgProductos" class="ui modal">
            <div class="header">Información del Producto</div>
            <div class="content">
                <div id="modalMessageContainer" class="ui hidden message"></div>
                <form id="fmProductos" class="ui form">
                    <input name="idproducto" id="idproducto" type="hidden">
                    <div class="required field">
                        <label for="pronombre">Nombre:</label>
                        <input name="pronombre" id="pronombre" required>
                    </div>
                    <div class="required field">
                        <label for="prodetalle">Detalle:</label>
                        <input name="prodetalle" id="prodetalle" required>
                    </div>
                    <div class="required field">
                        <label for="procantstock">Cantidad en Stock:</label>
                        <input name="procantstock" id="procantstock" type="number" min="0" required>
                    </div>
                </form>
            </div>
            <div class="actions">
                <button class="ui positive button" onclick="saveProducto()">Aceptar</button>
                <button class="ui button" onclick="closeDialog()">Cancelar</button>
            </div>
        </div>

        <div id="dlgConfirmDelete" class="ui modal">
            <div class="header">Confirmar Eliminación</div>
            <div class="content">
                <p>¿Seguro que desea eliminar el producto?</p>
                <div id="deleteMessageContainer" class="ui hidden message"></div>
            </div>
            <div class="actions">
                <button class="ui red button" onclick="destroyProducto()">Eliminar</button>
                <button class="ui button" onclick="closeConfirmDialog()">Cancelar</button>
            </div>
        </div>

        <script>
            var url;
            var idProductoEliminar;

            $(document).ready(function() {
                // Inicialización de Semantic UI form validation
                $('#fmProductos').form({
                    fields: {
                        pronombre: 'empty',
                        prodetalle: 'empty',
                        procantstock: 'integer'
                    }
                });
            });

            function newProducto() {
                $('#dlgProductos').modal('show');
                $('#fmProductos')[0].reset();
                $('#idproducto').val(''); // Asegurar que el ID esté vacío para el alta
                $('#modalMessageContainer').addClass('hidden');
                url = 'Accion/accionProductoTabla.php?accion=alta';
            }

            function editProducto(idproducto) {
                var row = $('tr[data-id="' + idproducto + '"]');
                if (row.length) {
                    $('#dlgProductos').modal('show');
                    $('#fmProductos')[0].reset();
                    $('#modalMessageContainer').addClass('hidden');

                    // Cargar datos en el formulario
                    $('#idproducto').val(idproducto);
                    $('#pronombre').val(row.find('td[data-field="pronombre"]').text());
                    $('#prodetalle').val(row.find('td[data-field="prodetalle"]').text());
                    $('#procantstock').val(row.find('td[data-field="procantstock"]').text());

                    url = 'Accion/accionProductoTabla.php?accion=mod&idproducto=' + idproducto;
                }
            }

            function saveProducto() {
                if ($('#fmProductos').form('is valid')) {
                    var formData = $('#fmProductos').serializeArray();
                    // Si es modificación, agregar idproducto a los datos
                    if ($('#idproducto').val() != '') {
                        formData.push({
                            name: 'idproducto',
                            value: $('#idproducto').val()
                        });
                    }

                    $.post(url, formData, function(result) {
                        try {
                            var result = JSON.parse(result);
                            if (!result.respuesta) {
                                showModalMessage('error', 'Error: ' + result.errorMsg);
                            } else {
                                $('#dlgProductos').modal('hide');
                                loadProductos();
                                showMessage('success', 'Producto guardado exitosamente.');
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            showModalMessage('error', 'Error: No se pudo procesar la respuesta del servidor.');
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        showModalMessage('error', 'Error de comunicación con el servidor.');
                    });
                } else {
                    showModalMessage('error', 'Por favor, complete todos los campos requeridos y asegúrese de que la cantidad en stock sea un número.');
                }
            }

            function confirmDestroyProducto(idproducto) {
                idProductoEliminar = idproducto;
                $('#dlgConfirmDelete').modal('show');
                $('#deleteMessageContainer').addClass('hidden');
            }

            function destroyProducto() {
                $.post('Accion/accionProductoTabla.php?accion=baja', {
                    idproducto: idProductoEliminar
                }, function(result) {
                    try {
                        var result = JSON.parse(result);
                        if (result.respuesta) {
                            loadProductos();
                            showMessage('success', 'Producto eliminado exitosamente.');
                        } else {
                            showDeleteMessage('error', 'Error: ' + result.errorMsg);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        showDeleteMessage('error', 'Error: No se pudo procesar la respuesta del servidor.');
                    }
                    $('#dlgConfirmDelete').modal('hide');
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                    showDeleteMessage('error', 'Error de comunicación con el servidor.');
                    $('#dlgConfirmDelete').modal('hide');
                });
            }

            function closeDialog() {
                $('#dlgProductos').modal('hide');
            }

            function closeConfirmDialog() {
                $('#dlgConfirmDelete').modal('hide');
            }

            function loadProductos() {
                // Función AJAX para recargar la tabla dinámicamente
                $.get('Accion/accionProductoTabla.php?accion=listar', function(data) {
                    var tableBody = $('#productosTableBody');
                    tableBody.empty();

                    try {
                        var productos = JSON.parse(data);
                        productos.forEach(function(producto) {
                            var row = '<tr data-id="' + producto.idproducto + '">' +
                                '<td data-field="idproducto">' + producto.idproducto + '</td>' +
                                '<td data-field="pronombre">' + producto.pronombre + '</td>' +
                                '<td data-field="prodetalle">' + producto.prodetalle + '</td>' +
                                '<td data-field="procantstock">' + producto.procantstock + '</td>' +
                                '<td>' +
                                '<button class="ui teal button" onclick="editProducto(' + producto.idproducto + ')">Editar</button>' +
                                '<button class="ui red button" onclick="confirmDestroyProducto(' + producto.idproducto + ')">Eliminar</button>' +
                                '</td>' +
                                '</tr>';
                            tableBody.append(row);
                        });
                    } catch (e) {
                        console.error('Error al parsear JSON de listado:', e);
                        showMessage('error', 'Error al cargar los productos. Revise la respuesta del servidor.');
                    }
                });
            }

            // Funciones de mensajería (se mantienen las de tu código original)
            function showMessage(type, message) {
                var messageContainer = $('#messageContainer');
                messageContainer.removeClass('hidden success error');
                messageContainer.addClass(type);
                messageContainer.html('<p>' + message + '</p>');
            }

            function showModalMessage(type, message) {
                var messageContainer = $('#modalMessageContainer');
                messageContainer.removeClass('hidden success error');
                messageContainer.addClass(type);
                messageContainer.html('<p>' + message + '</p>');
            }

            function showDeleteMessage(type, message) {
                var messageContainer = $('#deleteMessageContainer');
                messageContainer.removeClass('hidden success error');
                messageContainer.addClass(type);
                messageContainer.html('<p>' + message + '</p>');
            }

            // Carga inicial (redundante con el PHP, pero asegura la recarga)
            // loadProductos(); 
        </script>

    </div>

</div>
<?php include_once "./Estructura/footer.php"; ?>