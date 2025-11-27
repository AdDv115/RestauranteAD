<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}
require_once(ROOT_PATH . 'Configuracion/conexion.php');

class Mesas {
    public $db;

    public function __construct() {
        $this->db = DB::connect();
    }

    public function RegistrarMesas($NumeroMesa, $Capacidad, $Ubicacion, $Disponibilidad) {
        $sql = "INSERT INTO mesas(NumeroMesa, Capacidad, Ubicacion, Disponibilidad) VALUES (:numeromesa, :capacidad, :ubicacion, :disponibilidad)";

        $res = $this->db->prepare($sql);
        $res->bindParam(":numeromesa", $NumeroMesa);
        $res->bindParam(":capacidad", $Capacidad);
        $res->bindParam(":ubicacion", $Ubicacion);
        $res->bindParam(":disponibilidad", $Disponibilidad);

        $res->execute();
    }
    
    public function obtenerMesasDisponibles() {
        $sql = "SELECT ID_R, NumeroMesa FROM mesas WHERE Disponibilidad = 1";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMesas() {
        $sql = "SELECT * FROM mesas";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMesasPorId($id) {
        $sql = "SELECT * FROM mesas WHERE ID_R = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarMesas($id, $NumeroMesa, $Capacidad, $Ubicacion, $Disponibilidad) {
        $sql = "UPDATE mesas SET NumeroMesa=?, Capacidad=?, Ubicacion=?, Disponibilidad=? WHERE ID_R=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$NumeroMesa, $Capacidad, $Ubicacion, $Disponibilidad,$id]);
    }

    public function eliminarMesa($id) {
        $sql = "DELETE FROM mesas WHERE ID_R=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}