<?php
include_once '../configuracion.php';

$obj = new Usuario();
$array = ['nombre' => 'Mateo', 'pass' => '98765' , 'email' => 'MiGmail@gmail.com'];
// print_r($array);
$obj -> mostrar('marcelo',1200,'usnombre');