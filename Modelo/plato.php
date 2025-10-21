<?php

require_once(ROOT_PATH . 'Configuracion/conexion.php');



class Plato {
    
    public $db;

    public function __construct() {
        $this -> db = DB::connect();

    }

    //Crear funcion para agregar platos a la base de datos
    public function AgregarPlato($NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible){

        $sql = "INSERT INTO platos(NombrePlato, Descripcion, Precio, ImagenUrl, Disponible) 
        VALUES (:nombreP, :descripcion, :precio, :imagenurl, :disponible)";

        $res = $this -> db -> prepare($sql);
        $res -> bindParam(":nombreP", $NombrePlato);
        $res -> bindParam(":descripcion", $Descripcion);
        $res -> bindParam(":precio", $Precio);
        $res -> bindParam(":imagenurl", $ImagenUrl);
        $res -> bindParam(":disponible", $Disponible);

        $res -> execute();
    }

    // Listar todos los platos
    public function obtenerPlato() {
        $sql = "SELECT * FROM platos";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener plato por ID
    public function obtenerPlatoPorId($id) {
        $sql = "SELECT * FROM platos WHERE ID_plato = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar plato
    public function actualizarPlato($NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible) {
        $sql = "UPDATE platos SET NombrePlato=?, Descripcion=?, Precio=?, Disponible=? 
                WHERE ID_Plato=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible]);
    }

    // Eliminar Plato
    public function eliminaPlato($id) {
        $sql = "DELETE FROM platos WHERE ID_Plato=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

}

// Ejecutar el controlador
$controller = new platoController();

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'login':
            $controller->validarUser();
            break;
        case 'agregarp':
            $controller->agregarp();
            break;
        case 'cerrarSesion':
            $controller->cerrarSesion();
            break;
        default:
            // Acción por defecto o error
            header("Location:../Vista/html/rplato.php");
            exit();
    }
} else {
    header("Location:../Vista/html/rplato.php");
    exit();
}
?>

?>