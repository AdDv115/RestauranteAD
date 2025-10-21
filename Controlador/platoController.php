<?php
require_once "../Configuracion/conexion.php";

define('ROOT_PATH', dirname(__DIR__, 1) . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . 'Modelo/plato.php');

class PlatoController{

private $modelplato;

    public function __construct() {
        $this -> modelplato = new Plato();
    }

public function AgregarPlato(){

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        
        $NombrePlato = $_POST['NombreP'];
        $Descripcion = $_POST['Descripcion'];
        $Precio = $_POST['PrecioP'];
    
        //Guardar imagen
        $ImagenUrl = ($_FILES['Imagen']['name']);

        $ruta = __DIR__ . "/../Vista/img/platos" . basename($ImagenUrl);
        move_uploaded_file($_FILES['Imagen']['tmp_name'], $ruta);
        $Disponible = $_POST['Disponible'];

        $plato = new Plato();
        $plato = $this -> modelplato -> AgregarPlato($NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible);

        header("Location: ../Vista/html/rplato.php");

        }
    }
}

$objeto = new PlatoController();
$objeto -> AgregarPlato();
?>