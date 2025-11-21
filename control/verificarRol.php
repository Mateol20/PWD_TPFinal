<?php
include_once("../configuracion.php");
class verificarRol{
    
    public function verificar($idUsuario){
        $session = new Session;
        $id = $session->getUsuario();

        switch ($idUsuario) {
            case '1': if($id !== $idUsuario){
                 header("Location: ../vista/index.php");
                    exit();
            }
                break;
            case '2':
                if($id !== $idUsuario){
                 header("Location: ../vista/index.php");
                    exit();
            }
                break;
            case '3':
                if($id == ''){
                 header("Location: ../vista/usuario/login.php");
                    exit();
            }
                break;
            
            default:
                header("Location: ../vista/usuario/login.php");
                exit();
                break;
        }

    }
}
?>