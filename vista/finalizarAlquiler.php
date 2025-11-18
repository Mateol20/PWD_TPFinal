<?php
session_start();

// eliminar carrito y días
unset($_SESSION['carrito']);
unset($_SESSION['dias']);

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Alquiler Confirmado</title>
<link rel="stylesheet" href="../../css/semantic.min.css">
</head>
<body>

<div class="ui container" style="margin-top:50px;">
    <div class="ui positive message">
        <div class="header">¡Alquiler Confirmado!</div>
        <p>Tu reserva ha sido registrada con éxito.</p>
    </div>

    <a href="index.php" class="ui primary button">Volver al Catálogo</a>
</div>

</body>
</html>
