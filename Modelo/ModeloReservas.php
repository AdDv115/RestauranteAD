<?php
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . 'Configuracion/conexion.php');

class Reservas {
    public $db;

    public function __construct() {
        $this->db = DB::connect();
    }

    public function RegistrarReserva($NumeroPersonas, $FechaReserva, $HoraReserva, $Disponibilidad) {
        $sql = "INSERT INTO reservas(NumeroPersonas, FechaReserva, HoraReserva, Disponibilidad) VALUES (:numeropersonas, :fechareserva, :horareserva, :disponibilidad)";

        $res = $this->db->prepare($sql);
        $res->bindParam(":numeropersonas", $NumeroPersonas);
        $res->bindParam(":fechareserva", $FechaReserva);
        $res->bindParam(":horareserva", $HoraReserva);
        $res->bindParam(":disponibilidad", $Disponibilidad);

        $res->execute();
    }

    public function obtenerReservas() {
        $sql = "SELECT * FROM reservas";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerReservasPorId($id) {
        $sql = "SELECT * FROM reservas WHERE ID_RE = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarReservas($id, $NumeroPersonas, $FechaReserva, $HoraReserva, $Disponibilidad) {
        $sql = "UPDATE reservas SET NumeroPersonas=?, FechaReserva=?, HoraReserva=?, Disponibilidad=?  WHERE ID_R=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$NumeroPersonas, $FechaReserva, $HoraReserva, $Disponibilidad,$id]);
    }

    public function eliminarReservas($id) {
        $sql = "DELETE FROM reservas WHERE ID_RE=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
