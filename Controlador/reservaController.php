<?php
session_start();

define('PROJECT_ROOT', '/Resad/'); 

require_once "../Configuracion/conexion.php";
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . 'Modelo/ModeloReservas.php');

class ReservaController {
    private $modelreservas;

    public function __construct() {
        $this->modelreservas = new Reservas();
    }

    public function RegistrarReservas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $NumeroPersonas = $_POST['NP'];
            $FechaReserva = $_POST['FR'];
            $HoraReserva = $_POST['HR'];
            
            $ID_User = $_SESSION['usuario_logueado']['ID_User'] ?? null;
            
            if (empty($ID_User)) {
                header("Location:" . PROJECT_ROOT . "Vista/html/login.php?error=notlogged");
                exit();
            }

            $ID_Mesa_Disponible = $this->modelreservas->buscarMesaDisponible($FechaReserva, $HoraReserva, $NumeroPersonas);

            if ($ID_Mesa_Disponible) {
                $this->modelreservas->RegistrarReservas(
                    $NumeroPersonas, 
                    $FechaReserva, 
                    $HoraReserva, 
                    $ID_User,
                    $ID_Mesa_Disponible
                );
                
                $_SESSION['reserva_msg'] = '¡Reserva realizada y mesa asignada!';
            } else {
                $_SESSION['reserva_msg'] = 'Lo sentimos, no hay mesas disponibles para esa hora o número de personas.';
            }

            $_SESSION['lista_reserva'] = $this-> modelreservas -> obtenerReservas();

            header("Location:" . PROJECT_ROOT . "Vista/html/perfil.php");
            exit();
        }
    }

    public function actualizarReservas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID_R'];
            $NumeroPersonas = $_POST['NP'];
            $FechaReserva = $_POST['FR'];
            $HoraReserva = $_POST['HR'];
            $ID_M = intval($_POST['ID_M']); 

            $this -> modelreservas -> actualizarReservas($id, $NumeroPersonas, $FechaReserva, $HoraReserva, $ID_M);

            $_SESSION['lista_reserva'] = $this->modelreservas->obtenerReservas(); 

            header("Location:" . PROJECT_ROOT . "Vista/html/perfil.php");
            exit();
         }
        }

    public function eliminarReservas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID_R'];
            $this -> modelreservas -> eliminarReservas($id);

            $_SESSION['lista_reserva'] = $this->modelreservas    ->obtenerReservas();

            header("Location:" . PROJECT_ROOT . "Vista/html/perfil.php");
            exit();
        }
    }
}

$controller = new ReservaController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'RegistrarR':
            $controller->RegistrarReservas();
            break;
        case 'ActualizarR':
            $controller->actualizarReservas();
            break;
        case 'EliminarR':
            $controller->eliminarReservas();
            break;
        default:
            header("Location:" . PROJECT_ROOT . "Vista/html/perfil.php");
            exit();
    }
} else {
    header("Location:" . PROJECT_ROOT . "Vista/html/perfil.php");
    exit();
}