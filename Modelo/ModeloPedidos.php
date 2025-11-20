<?php
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . 'Configuracion/conexion.php');

class Reservas {
    public $db;

    public function __construct() {
        $this->db = DB::connect();
    }

    public function buscarMesaDisponible($FechaReserva, $HoraReserva, $NumeroPersonas) {
        $sql = "SELECT ID_R FROM mesas 
                WHERE NumeroMesa >= :personas 
                AND ID_R NOT IN (
                    SELECT ID_M FROM reservas 
                    WHERE FechaReserva = :fecha 
                    AND HoraReserva = :hora
                ) 
                ORDER BY Capacidad ASC LIMIT 1"; 

        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(":personas", $NumeroPersonas);
        $stmt->bindParam(":fecha", $FechaReserva);
        $stmt->bindParam(":hora", $HoraReserva);
        
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['ID_R'] : false; 
    }

    public function RegistrarReservas($NumeroPersonas, $FechaReserva, $HoraReserva, $ID_User, $ID_M) {
        $sql = "INSERT INTO reservas(NumeroPersonas, FechaReserva, HoraReserva, ID_User, ID_M) 
                VALUES (:numeropersonas, :fechareserva, :horareserva, :id_user, :id_m)";

        $res = $this->db->prepare($sql);
        $res->bindParam(":numeropersonas", $NumeroPersonas);
        $res->bindParam(":fechareserva", $FechaReserva);
        $res->bindParam(":horareserva", $HoraReserva);
        $res->bindParam(":id_user", $ID_User); 
        $res->bindParam(":id_m", $ID_M);       

        $res->execute();
    }

    public function obtenerReservas() {
        $sql = "SELECT 
                    r.*,               
                    m.NumeroMesa,       
                    u.Nombre,
                    u.Apellido
                FROM 
                    reservas r
                JOIN 
                    mesas m ON r.ID_M = m.ID_R
                JOIN
                    usuarios u ON r.ID_User = u.ID_User";
                    
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerReservasPorId($id) {
        $sql = "SELECT * FROM reservas WHERE ID_RE = ?"; 
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarReservas($id, $NumeroPersonas, $FechaReserva, $HoraReserva, $ID_M) {
        $sql = "UPDATE reservas SET NumeroPersonas=?, FechaReserva=?, HoraReserva=?, ID_M=?  WHERE ID_RE=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$NumeroPersonas, $FechaReserva, $HoraReserva, $ID_M, $id]);
    }

    public function eliminarReservas($id) {
        $sql = "DELETE FROM reservas WHERE ID_RE=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>