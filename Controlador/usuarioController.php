<?php

require_once "../Configuracion/conexion.php";
require_once "../Modelo/usuario.php";

class UsuarioController{

    private $modelUser;

    private function __construct(){
        $this -> modelUser = new Usuario();
    }

    public function validarUser(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // $usuario = $this -> modelUser -> login($_POST['Email'],$_POST['Password']);

            if($usuario){
                session_start();
                $_SESSION['usuarios'] = $usuario;
                header("Location:../../../Vista/html/perfil.html");
            
            }else{
                echo "Credenciales no validas";
                header("Location:../../../Vista/html/user.html");
            }
        }
    }

    public function cerrarSesion(){

    }
}

?>