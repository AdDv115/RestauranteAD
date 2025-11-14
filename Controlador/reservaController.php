<?php
session_start();

require_once "../Configuracion/conexion.php";
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . 'Modelo/ModeloReservas.php');

class ReservaController {
    private $modelreservas;

    public function __construct() {
        $this->modelreservas = new Reservas();
    }

    public function RegistrarMesas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $NumeroMesa = $_POST['NM'];
            $Capacidad = $_POST['CaP'];
            $Ubicacion = $_POST['UB'];
            $Disponibilidad = intval($_POST['DP'] ?? 0);

            $this -> modelreservas ->RegistrarMesas($NumeroMesa, $Capacidad, $Ubicacion, $Disponibilidad);

            $_SESSION['lista_mesas'] = $this->modelreservas->obtenerMesas();

            header("Location: ../Vista/html/mesas.php");
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

            $this -> modelreservas -> actualizarMesas($id, $NumeroMesa, $Capacidad,$Ubicacion, $Disponibilidad);

            $_SESSION['lista_mesas'] = $this->modelreservas->obtenerMesas();

            header("Location: ../Vista/html/mesas.php");
            exit();
         }
        }

    public function eliminarMesa() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID_R'];
            $this -> modelreservas -> eliminarMesa($id);

            $_SESSION['lista_mesas'] = $this->modelreservas    ->obtenerMesas();

            header("Location: ../Vista/html/mesas.php");
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
            header("Location: ../Vista/html/mesas.php");
            exit();
    }
} else {
    header("Location: ../Vista/html/mesas.php");
    exit();
}
?>
