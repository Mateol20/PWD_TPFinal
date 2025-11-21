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
        font-family: "Segoe UI", Arial, sans-serif;
        background: #f2f2f2;
        margin: 0;
    }

    /* Contenedor principal */
    .contenedor-tabla {
        width: 95%;
        max-width: 1200px;
        margin: 40px auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        border-radius: 8px;
        overflow: hidden;
    }

    th {
        background: #3f51b5;
        color: white;
        padding: 14px;
        font-size: 15px;
        letter-spacing: 0.5px;
        text-align: center;
    }

    td {
        padding: 12px 15px;
        font-size: 14px;
        color: #444;
        text-align: center;
        border-bottom: 1px solid #e8e8e8;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover td {
        background: #f7f9ff;
    }

    /* Botón cancelar */
    .btn-cancelar {
        background: #ff4d4d;
        color: white;
        padding: 6px 14px;
        border-radius: 4px;
        font-weight: 600;
        text-decoration: none;
        font-size: 13px;
        transition: 0.25s;
        display: inline-block;
    }

    .btn-cancelar:hover {
        background: #d12f2f;
    }

    .disabled-action {
        color: #aaa;
        font-size: 18px;
    }

    .empty {
        margin-top: 40px;
        text-align: center;
        color: #666;
        font-size: 18px;
    }
</style>


</head>
<body>
 <?php include("estructura/header.php"); ?>
<?php if (empty($comprasArray)): ?>

    <p class="empty">Todavía no realizaste ningún alquiler.</p>

<?php else: ?>

<div class="contenedor-tabla">
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
        <a class="btn-cancelar" 
           href="accion/accionCancelarCompra.php?id=<?= $compra['idcompra'] ?>">
           Cancelar
        </a>
    <?php else: ?>
        <span class="disabled-action">—</span>
    <?php endif; ?>
        </td>

        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>



<?php endif; ?>
<?php include("estructura/footer.php"); ?>
</body>
</html>
