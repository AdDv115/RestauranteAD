<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}
require_once(ROOT_PATH . 'Configuracion/conexion.php');

class Plato {
    public $db;

    public function __construct() {
        $this->db = DB::connect();
    }

    public function AgregarPlato($NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible) {
        $sql = "INSERT INTO platos(NombrePlato, Descripcion, Precio, ImagenUrl, Disponible) 
                VALUES (:nombreP, :descripcion, :precio, :imagenurl, :disponible)";
        $res = $this->db->prepare($sql);
        $res->bindParam(":nombreP", $NombrePlato);
        $res->bindParam(":descripcion", $Descripcion);
        $res->bindParam(":precio", $Precio);
        $res->bindParam(":imagenurl", $ImagenUrl);
        $res->bindParam(":disponible", $Disponible, PDO::PARAM_INT);
        $res->execute();
    }

    public function obtenerPlato() {
        $sql = "SELECT * FROM platos";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPlatoPorId($id) {
        $sql = "SELECT * FROM platos WHERE ID_Plato = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPlato($id, $NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible) {
        $sql = "UPDATE platos SET NombrePlato=?, Descripcion=?, Precio=?, ImagenUrl=?, Disponible=? 
                WHERE ID_Plato=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$NombrePlato, $Descripcion, $Precio, $ImagenUrl, $Disponible, $id]);
    }

    public function eliminaPlato($id) {
        $sql = "DELETE FROM platos WHERE ID_Plato=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}