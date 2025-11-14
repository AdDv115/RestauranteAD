<?php
session_start();

require_once "../Configuracion/conexion.php";
require_once "../Modelo/ModeloUsuarios.php";

class AdminController {

    private $modelUser;

    public function __construct() {
        $this -> modelUser = new Usuario();
    }

    public function registrarA(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $nombre = $_POST['Nombre'];
            $apellido = $_POST['Apellido'];
            $email = $_POST['Email'];
            $pass = $_POST['Password'];
            $rolusu = $_POST['Rolusu'];
            $telefono = $_POST['Telefono'];

            $this -> modelUser -> crearUser($nombre, $apellido, $email, $pass, $rolusu, $telefono);

            header("Location: ../Vista/html/perfil.php");
            exit();
        }
    }

    public function actualizarA() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['ID_User'];
        $nombre = $_POST['Nombre'];
        $apellido = $_POST['Apellido'];
        $email = $_POST['Email'];
        $pass = $_POST['Password'];
        $rolusu = $_POST['Rolusu'];
        $telefono = $_POST['Telefono'];

        $this->modelUser->actualizarUserAD($id, $nombre, $apellido, $email, $pass, $rolusu, $telefono);

        if (isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado']['Rolusu'] === 'Administrador') {
            $_SESSION['lista_usuarios'] = $this->modelUser->listarUser();
        }

        header("Location: ../Vista/html/perfil.php");
        exit();
    }
}

public function eliminarU() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['ID_User'];
        $this->modelUser->eliminarUser($id);
        
        if (isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado']['Rolusu'] === 'Administrador') {
            $_SESSION['lista_usuarios'] = $this->modelUser->listarUser();
        }

        header("Location: ../Vista/html/perfil.php");
        exit();
    }
}

    public function cerrarSesion() {
        session_destroy();
        
        header("Location: ../Vista/html/login.php");
        exit();
    }
}

$controller = new AdminController();

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        
        case 'registrarA':
            $controller->registrarA();
            break;

        case 'actualizarA':
            $controller->actualizarA();
            break;

        case 'eliminarA':
            $controller->eliminarU();
            break;

        default:
            
            header("Location:../Vista/html/perfil.php");
            exit();
    }
} else {
    header("Location:../Vista/html/perfil.php");
    exit();
}