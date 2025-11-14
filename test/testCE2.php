<?php
include_once '../configuracion.php'; // Asegúrate de que esta ruta sea correcta

// 🔹 Crear instancia del ABM
$abm = new ABMCompraEstado();
$array =   [
        'idcompraestadotipo' => 3,
        'idcompraestado' => 1 
    ];
$abm->modificar($array);
