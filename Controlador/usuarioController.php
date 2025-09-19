<?php
require_once "../Configuracion/conexion.php";
require_once "../Modelo/usuario.php";

class UsuarioController {

    private $modelUser;

    public function __construct() {
        $this->modelUser = new Usuario();
    }

    public function validarUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $this->modelUser->login($_POST['Email'], $_POST['Password']);

            if ($usuario) {
                session_start();
                $_SESSION['usuarios'] = $usuario;
                header("Location:../Vista/html/perfil.php");
                exit();
            } else {
                header("Location:../Vista/html/login.php");
                exit();
            }
        } else {
            header("Location:../Vista/html/login.php");
            exit();
        }
    }

    public function cerrarSesion() {
        session_start();
        session_destroy();
        header("Location:../Vista/html/login.php");
        exit();
    }
}

// Ejecutar el controlador
$controller = new UsuarioController();
$controller->validarUser();
?>