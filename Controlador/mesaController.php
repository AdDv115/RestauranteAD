<?php
session_start();

require_once "../Configuracion/conexion.php";
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . 'Modelo/ModeloMesas.php');

class MesaController {
    private $modelmesas;

    public function __construct() {
        $this->modelmesas = new Mesas();
    }

    public function RegistrarMesas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $NumeroMesa = $_POST['NM'];
            $Capacidad = $_POST['CaP'];
            $Ubicacion = $_POST['UB'];
            $Disponibilidad = intval($_POST['DP'] ?? 0);

            $this -> modelmesas ->RegistrarMesas($NumeroMesa, $Capacidad, $Ubicacion, $Disponibilidad);

            $_SESSION['lista_mesas'] = $this->modelmesas->obtenerMesas();

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

            $this -> modelmesas -> actualizarMesas($id, $NumeroMesa, $Capacidad,$Ubicacion, $Disponibilidad);

            $_SESSION['lista_mesas'] = $this->modelmesas->obtenerMesas();

            header("Location: ../Vista/html/mesas.php");
            exit();
         }
        }

    public function eliminarMesa() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID_R'];
            $this -> modelmesas -> eliminarMesa($id);

            $_SESSION['lista_mesas'] = $this->modelmesas    ->obtenerMesas();

            header("Location: ../Vista/html/mesas.php");
            exit();
        }
    }
}

$controller = new MesaController();

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
