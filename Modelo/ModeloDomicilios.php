<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}
require_once ROOT_PATH . 'Configuracion/conexion.php';

class Domicilios {
    public $db;

    public function __construct() {
        $this->db = DB::connect();
    }

    public function obtenerPorPedido($idPedido) {
        $sql = "SELECT * FROM domicilios WHERE ID_Pedido = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idPedido]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearDomicilio($ID_Pedido, $DireccionEntrega, $ContactoEntrega, $EstadoEntrega = 'Pendiente') {
        $sql = "INSERT INTO domicilios (ID_Pedido, DireccionEntrega, ContactoEntrega, EstadoEntrega) 
                VALUES (:id_pedido, :direccion, :contacto, :estado)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_pedido', $ID_Pedido, PDO::PARAM_INT);
        $stmt->bindParam(':direccion', $DireccionEntrega, PDO::PARAM_STR);
        $stmt->bindParam(':contacto', $ContactoEntrega, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $EstadoEntrega, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function obtenerDomicilios() {
        $sql = "SELECT 
                    d.*, 
                    p.NumeroPedido,
                    p.FechaPedido,
                    u.Nombre,
                    u.Apellido
                FROM domicilios d
                LEFT JOIN pedidos p ON d.ID_Pedido = p.ID_P
                LEFT JOIN usuarios u ON p.ID_User = u.ID_User
                ORDER BY d.ID_Domicilio DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDomicilioPorId($id) {
        $sql = "SELECT * FROM domicilios WHERE ID_Domicilio = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarDomicilio($id, $DireccionEntrega, $ContactoEntrega, $EstadoEntrega) {
        $sql = "UPDATE domicilios 
                SET DireccionEntrega = ?, ContactoEntrega = ?, EstadoEntrega = ?
                WHERE ID_Domicilio = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$DireccionEntrega, $ContactoEntrega, $EstadoEntrega, $id]);
    }

    public function marcarEntregado($id) {
        $sql = "UPDATE domicilios SET EstadoEntrega = 'Entregado' WHERE ID_Domicilio = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
