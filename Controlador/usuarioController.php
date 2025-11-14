<?php
require_once "../Configuracion/conexion.php";
require_once "../Modelo/ModeloUsuarios.php";

class UsuarioController {

    private $modelUser;

    public function __construct() {
        $this -> modelUser = new Usuario();
    }

    public function validarUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $this -> modelUser -> login($_POST['Email'], $_POST['Password']);

            if ($usuario) {
                session_start();
                $_SESSION['usuario_logueado'] = $usuario;

            if ($usuario['Rolusu'] === 'Administrador') {
                $_SESSION['lista_usuarios'] = $this->modelUser->listarUser();
                }

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

    public function registrar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $nombre = $_POST['Nombre'];
            $apellido = $_POST['Apellido'];
            $email = $_POST['Email'];
            $pass = $_POST['Password'];
            $rolusu = $_POST['rol'];
            $telefono = $_POST['Telefono'];

            $usuario = new Usuario();
            $usuario = $this -> modelUser -> crearUser($nombre, $apellido, $email, $pass, $rolusu, $telefono);
                
            header("Location: ../Vista/html/login.php");
        }
    }

    public function editarperfil() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['ID_User'];
        $nombre = $_POST['Nombre'];
        $apellido = $_POST['Apellido'];
        $pass = $_POST['Password'];
        $telefono = $_POST['Telefono'];

        $ImagenPerfil = $_FILES['Imagen']['name'] ?? '';

        if (!empty($ImagenPerfil)) {
            $ruta = __DIR__ . "/../Vista/img/uP/" . basename($ImagenPerfil);
            move_uploaded_file($_FILES['Imagen']['tmp_name'], $ruta);
        } else {
            $uP = $this->modelUser->obtenerUser($id); 
            $ImagenPerfil = $uP['ImagenPerfil'] ?? '';
        }


        $this->modelUser->EditarP($id, $nombre, $apellido, $pass, $telefono, $ImagenPerfil);

        $usuarioActualizado = $this -> modelUser -> obtenerUser($id);

        session_start();
        $_SESSION['usuario_logueado'] = $usuarioActualizado;

        header("Location: ../Vista/html/perfil.php");
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

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'login':
            $controller->validarUser();
            break;
        case 'registrar':
            $controller->registrar();
            break;

        case 'editarperfil':
            $controller->editarperfil();
            break;  
            
        case 'cerrarSesion':
            $controller->cerrarSesion();
            break;
        default:
        
            header("Location:../Vista/html/login.php");
            exit();
    }
} else {
    header("Location:../Vista/html/login.php");
    exit();
}
