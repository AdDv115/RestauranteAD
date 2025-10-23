<?php
require_once "../Configuracion/conexion.php";
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . 'Modelo/plato.php');

class PlatoController {
    private $modelplato;

    public function __construct() {
        $this->modelplato = new Plato();
    }

    public function AgregarPlato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $NombrePlato = $_POST['NombreP'];
            $Descripcion = $_POST['Descripcion'];
            $Precio = $_POST['PrecioP'];
            $ImagenUrl = $_FILES['Imagen']['name'];
            $ruta = __DIR__ . "/../Vista/img/platos/" . basename($ImagenUrl);
            move_uploaded_file($_FILES['Imagen']['tmp_name'], $ruta);
            $Disponible = intval($_POST['Disponible']);

            $this->modelplato->AgregarPlato($NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible);

            session_start();
            $_SESSION['lista_platos'] = $this->modelplato->obtenerPlato();

            header("Location: ../Vista/html/rplato.php");
            exit();
        }
    }

    public function actualizarPlato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID_Plato'];
            $NombrePlato = $_POST['NombreP'];
            $Descripcion = $_POST['Descripcion'];
            $Precio = $_POST['PrecioP'];
            $Disponible = intval($_POST['Disponible']);

            $ImagenUrl = $_FILES['Imagen']['name'] ?? '';
            if (!empty($ImagenUrl)) {
                $ruta = __DIR__ . "/../Vista/img/platos/" . basename($ImagenUrl);
                move_uploaded_file($_FILES['Imagen']['tmp_name'], $ruta);
            } else {
                $plato = $this->modelplato->obtenerPlatoPorId($id);
                $ImagenUrl = $plato['ImagenUrl'];
            }

            $this->modelplato->actualizarPlato($id, $NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible);

            session_start();
            $_SESSION['lista_platos'] = $this->modelplato->obtenerPlato();

            header("Location: ../Vista/html/rplato.php");
            exit();
        }
    }

    public function eliminarPlato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID_Plato'];
            $this->modelplato->eliminaPlato($id);

            session_start();
            $_SESSION['lista_platos'] = $this->modelplato->obtenerPlato();

            header("Location: ../Vista/html/rplato.php");
            exit();
        }
    }
}

$controller = new PlatoController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'agregarp':
            $controller->AgregarPlato();
            break;
        case 'actualizarp':
            $controller->actualizarPlato();
            break;
        case 'eliminarp':
            $controller->eliminarPlato();
            break;
        default:
            header("Location: ../Vista/html/rplato.php");
            exit();
    }
} else {
    header("Location: ../Vista/html/rplato.php");
    exit();
}
?>
