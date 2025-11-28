<?php
session_start();

if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', '/Resad/');
}

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}

require_once ROOT_PATH . 'Configuracion/conexion.php';
require_once ROOT_PATH . 'Modelo/ModeloReservas.php';

class ReservaController {
    private $modelreservas;

    public function __construct() {
        $this->modelreservas = new Reservas();
    }

    public function RegistrarReservas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $NumeroPersonas = $_POST['NP'] ?? null;
            $FechaReserva = $_POST['FR'] ?? null;
            $HoraReserva = $_POST['HR'] ?? null;
            $Estado = $_POST['Estado'] ?? 'Pendiente';
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
                    $Estado,
                    $ID_User,
                    $ID_Mesa_Disponible
                );

                $_SESSION['lista_reserva'] = $this->modelreservas->obtenerReservas();

                header("Location:" . PROJECT_ROOT . "Vista/html/reservas.php");
                exit();
            } else {
                header("Location:" . PROJECT_ROOT . "Vista/html/reservas.php?error=sin_mesa");
                exit();
            }
        }
    }

    public function actualizarReservas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID_R'] ?? null;
            $NumeroPersonas = $_POST['NP'] ?? null;
            $FechaReserva = $_POST['FR'] ?? null;
            $HoraReserva = $_POST['HR'] ?? null;
            $Estado = $_POST['Estado'] ?? null;
            $ID_M = intval($_POST['ID_M'] ?? 0);

            $this->modelreservas->actualizarReservas($id, $NumeroPersonas, $FechaReserva, $HoraReserva, $Estado, $ID_M);

            $_SESSION['lista_reserva'] = $this->modelreservas->obtenerReservas();

            header("Location:" . PROJECT_ROOT . "Vista/html/reservas.php");
            exit();
        }
    }

    public function eliminarReservas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID_R'] ?? null;

            $this->modelreservas->eliminarReservas($id);

            $_SESSION['lista_reserva'] = $this->modelreservas->obtenerReservas();

            header("Location:" . PROJECT_ROOT . "Vista/html/reservas.php");
            exit();
        }
    }
}

$controller = new ReservaController();

$action = $_GET['action'] ?? null;

if ($action) {
    switch ($action) {
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
            header("Location:" . PROJECT_ROOT . "Vista/html/reservas.php");
            exit();
    }
} else {
    header("Location:" . PROJECT_ROOT . "Vista/html/reservas.php");
    exit();
}
