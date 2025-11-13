<?php
session_start();

require_once "../Configuracion/conexion.php";
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . 'Modelo/reserva.php');

class ReservaController {
    private $modelreserva;

    public function __construct() {
        $this->modelreserva = new Reserva();
    }

    public function RegistrarMesas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $NumeroMesa = $_POST['NM'];
            $Capacidad = $_POST['CaP'];
            $Ubicacion = $_POST['UB'];
            $Disponibilidad = intval($_POST['DP'] ?? 0);

            $this -> modelreserva ->RegistrarMesas($NumeroMesa, $Capacidad, $Ubicacion, $Disponibilidad);

            $_SESSION['lista_mesas'] = $this->modelreserva->obtenerMesas();

            header("Location: ../Vista/html/reserva.php");
            exit();
        }
    }

    public function actualizarMesas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID_R'];
            $NumeroMesa = $_POST['NM'];
            $Capacidad = $_POST['CaP'];
            $Ubicacion = $_POST['UB'];
            $Disponibilidad = intval($_POST['DP']);

            $this -> modelreserva -> actualizarMesas($id, $NumeroMesa, $Capacidad,$Ubicacion, $Disponibilidad);

            $_SESSION['lista_mesas'] = $this->modelreserva->obtenerMesas();

            header("Location: ../Vista/html/reserva.php");
            exit();
         }
        }

    public function eliminarMesa() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID_R'];
            $this -> modelreserva -> eliminarMesa($id);

            $_SESSION['lista_mesas'] = $this->modelreserva->obtenerMesas();

            header("Location: ../Vista/html/reserva.php");
            exit();
        }
    }
}

$controller = new ReservaController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'RegistrarR':
            $controller->RegistrarMesas();
            break;
        case 'ActualizarR':
            $controller->actualizarMesas();
            break;
        case 'EliminarR':
            $controller->eliminarMesa();
            break;
        default:
            header("Location: ../Vista/html/reserva.php");
            exit();
    }
} else {
    header("Location: ../Vista/html/reserva.php");
    exit();
}
?>
