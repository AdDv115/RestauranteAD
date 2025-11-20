<?php
require_once "../Configuracion/conexion.php";
require_once "../Modelo/ModeloUsuarios.php";

define('PROJECT_ROOT', '/Resad/'); 

class UsuarioController {

    private $modelUser;

    public function __construct() {
        $this->modelUser = new Usuario();
    }

    public function validarUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $usuario = $this->modelUser->login($_POST['Email'], $_POST['Password']);

            if ($usuario) {
                $_SESSION['usuario_logueado'] = $usuario;

                if ($usuario['Rolusu'] === 'Administrador') {
                    $_SESSION['lista_usuarios'] = $this->modelUser->listarUser();
                }

                header("Location:" . PROJECT_ROOT . "Vista/html/perfil.php");
                exit();

            } else {
                header("Location:" . PROJECT_ROOT . "Vista/html/login.php?error=credenciales_invalidas");
                exit();
            }
        } else {
            header("Location:" . PROJECT_ROOT . "Vista/html/login.php");
            exit();
        }
    }

    public function registrar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $nombre = $_POST['Nombre'];
            $apellido = $_POST['Apellido'];
            $email = $_POST['Email'];
            $pass = $_POST['Password'];
            $rolusu = $_POST['rol'] ?? 'Cliente'; 
            $telefono = $_POST['Telefono'];

            $this->modelUser->crearUser($nombre, $apellido, $email, $pass, $rolusu, $telefono);
                
            header("Location: " . PROJECT_ROOT . "Vista/html/login.php");
            exit();
        }
    }

    public function editarperfil() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            
            $id = $_POST['ID_User'];
            $nombre = $_POST['Nombre'];
            $apellido = $_POST['Apellido'];
            $pass = $_POST['Password']; 
            $telefono = $_POST['Telefono'];

            $imagenActual = $_SESSION['usuario_logueado']['ImagenPerfil'] ?? '';
            $ImagenPerfil = $imagenActual;

            if (isset($_FILES['Imagen']) && $_FILES['Imagen']['error'] === UPLOAD_ERR_OK) {
                
                $carpeta_destino = __DIR__ . "/../Vista/img/uP/";
                $nombre_archivo = basename($_FILES["Imagen"]["name"]);
                $ext = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                
                $nombre_unico = $id . "_" . time() . "." . $ext;
                $ruta_final = $carpeta_destino . $nombre_unico;

                if (move_uploaded_file($_FILES["Imagen"]["tmp_name"], $ruta_final)) {
                    $ImagenPerfil = $nombre_unico;
                    
                    if (!empty($imagenActual) && $imagenActual !== 'default.png' && file_exists($carpeta_destino . $imagenActual)) {
                        unlink($carpeta_destino . $imagenActual);
                    }
                }
            }
            
            $this->modelUser->EditarP($id, $nombre, $apellido, $pass, $telefono, $ImagenPerfil);

            $usuarioActualizado = $this->modelUser->obtenerUser($id);
            $_SESSION['usuario_logueado'] = $usuarioActualizado;

            header("Location: " . PROJECT_ROOT . "Vista/html/perfil.php");
            exit();
        }
    }

    public function cerrarSesion() {
        session_start();
        session_destroy();
        header("Location:" . PROJECT_ROOT . "Vista/html/login.php");
        exit();
    }
}

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
        
            header("Location:" . PROJECT_ROOT . "Vista/html/login.php");
            exit();
    }
} else {
    header("Location:" . PROJECT_ROOT . "Vista/html/login.php");
    exit();
}