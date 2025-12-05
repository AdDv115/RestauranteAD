<?php
require_once "../Configuracion/conexion.php";
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . 'Modelo/ModeloPlatos.php');

class PlatoController {
    private $modelplato;

    public function __construct() {
        $this->modelplato = new Plato();
    }

    public function AgregarPlato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            
            $NombrePlato = $_POST['NombreP'];
            $Descripcion = $_POST['Descripcion'];
            $Precio = $_POST['PrecioP'];
            $ImagenUrl = $_FILES['Imagen']['name'];
            
            $ruta_destino = dirname(__DIR__) . "/Vista/img/platos/" . basename($ImagenUrl);
            
            if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $ruta_destino)) {
                $Disponible = intval($_POST['Disponible']);
                $this->modelplato->AgregarPlato($NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible);
            }

            $_SESSION['lista_platos'] = $this->modelplato->obtenerPlato();

            header("Location: ../Vista/html/rplato.php");
            exit();
        }
    }

    public function actualizarPlato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $id = $_POST['ID_Plato'];
            $NombrePlato = $_POST['NombreP'];
            $Descripcion = $_POST['Descripcion'];
            $Precio = $_POST['PrecioP'];
            $Disponible = intval($_POST['Disponible']);

            $ImagenUrl = $_FILES['Imagen']['name'] ?? '';
            $actualizacion_exitosa = true;

            if (!empty($ImagenUrl) && $_FILES['Imagen']['error'] == UPLOAD_ERR_OK) {

                $ruta_destino = dirname(__DIR__) . "/Vista/img/platos/" . basename($ImagenUrl);
                if (!move_uploaded_file($_FILES['Imagen']['tmp_name'], $ruta_destino)) {
                     $actualizacion_exitosa = false;
                }
            } else {
                $plato = $this->modelplato->obtenerPlatoPorId($id);
                $ImagenUrl = $plato['ImagenUrl'] ?? '';
            }

            if ($actualizacion_exitosa) {
                $this->modelplato->actualizarPlato($id, $NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible);
            }

            $_SESSION['lista_platos'] = $this->modelplato->obtenerPlato();

            header("Location: ../Vista/html/rplato.php");
            exit();
        }
    }

    public function eliminarPlato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $id = $_POST['ID_Plato'];
            $this->modelplato->eliminaPlato($id);

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