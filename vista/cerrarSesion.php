<?php

include_once "../configuracion.php";

$session = new Session();
$session->cerrar();
header("Location: usuario/login.php");
// header("Location:" . $PRINCIPAL);
exit;
