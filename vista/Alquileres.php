<?php
include_once("../configuracion.php");
$abmCompra = new ABMCompra;
$funcionesCompra = new FuncionesCompra;
$session = new Session;
$verificar = new verificarRol;
$verificar->verificar(3); //verifica login, si el usuario tiene idRol 0 lo redirige a logeo

$comprasArray = $abmCompra->listarComprasDeUsuario($session->getUsuario());
?>

<!DOCTYPE html>
<html lang="es">
<head>
   
    <meta charset="UTF-8">
    <title>Historial de Alquileres</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            margin: 0;
            padding: 25px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #4e73df;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .empty {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>
 <?php include("estructura/header.php"); ?>
<?php if (empty($comprasArray)): ?>

    <p class="empty">Todavía no realizaste ningún alquiler.</p>

<?php else: ?>

<table>
    <thead>
        <tr>
            <th>ID Compra</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>

    <?php foreach ($comprasArray as $compra): ?>

        <?php  
        $fecha = date("d/m/Y", strtotime($compra["cofecha"]));
        $hora  = date("H:i", strtotime($compra["cofecha"]));
        ?>

        <tr>
            <td><?= $compra["idcompra"] ?></td>
            <td><?= $fecha ?></td>
            <td><?= $hora ?></td>
            <td><?= $estado=$funcionesCompra->buscarCompraEstadoTipo($compra["idcompra"]) ?></td>

            <td>
                <?php if ($estado === "Iniciada"): ?>
                    <a href="accion/accionCancelarCompra.php?id=<?= $compra["idcompra"] ?>"
                        style="color: red; font-weight: bold;">
                        Cancelar
                    </a>
                <?php else: ?>
                    <span style="color: gray;">—</span>
                <?php endif; ?>
            </td>
        </tr>

    <?php endforeach; ?>

    </tbody>
</table>


<?php endif; ?>
<?php include("estructura/footer.php"); ?>
</body>
</html>
