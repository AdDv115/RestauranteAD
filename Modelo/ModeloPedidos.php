<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}
require_once(ROOT_PATH . 'Configuracion/conexion.php');

class Pedidos {
    public $db;

    public function __construct() {
        $this->db = DB::connect();
    }

    public function RegistrarPedidos($NumeroPedido, $FechaPedido, $CantidadPlatos, $ID_Plato, $ID_User, $ID_Mesa, $Estado = 'Pendiente') {
        $sql = "INSERT INTO pedidos (NumeroPedido, FechaPedido, CantidadPlatos, ID_Plato, ID_User, ID_Mesa, Estado) 
                VALUES (:numeropedido, :fechapedido, :cantidadplatos, :id_plato, :id_user, :id_mesa, :estado)";

        $res = $this->db->prepare($sql);
        $res->bindParam(":numeropedido", $NumeroPedido);
        $res->bindParam(":fechapedido", $FechaPedido);
        $res->bindParam(":cantidadplatos", $CantidadPlatos);
        $res->bindParam(":id_plato", $ID_Plato);
        $res->bindParam(":id_user", $ID_User); 
        $res->bindParam(":id_mesa", $ID_Mesa);
        $res->bindParam(":estado", $Estado); 
        $res->execute();
    }
    
    public function obtenerPedidosPorUsuario($id_user) {
        $sql = "SELECT 
                    r.ID_P, 
                    r.NumeroPedido,
                    r.FechaPedido, 
                    r.Estado,
                    r.CantidadPlatos,
                    m.NumeroMesa,
                    p.NombrePlato
                FROM 
                    pedidos r
                LEFT JOIN 
                    mesas m ON r.ID_Mesa = m.ID_R 
                LEFT JOIN 
                    platos p ON r.ID_Plato = p.ID_Plato
                WHERE r.ID_User = ?";
                    
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidos() {
        $sql = "SELECT 
                    r.*,
                    m.NumeroMesa,
                    u.Nombre,
                    u.Apellido,
                    p.NombrePlato,
                    p.Precio,
                    p.ImagenUrl
                FROM 
                    pedidos r
                JOIN 
                    platos p ON r.ID_Plato = p.ID_Plato
                JOIN 
                    mesas m ON r.ID_Mesa = m.ID_R
                JOIN
                    usuarios u ON r.ID_User = u.ID_User";
                    
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedidosPorId($id) {
        $sql = "SELECT * FROM pedidos WHERE ID_P = ?"; 
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPedidos($id, $NumeroPedido, $FechaPedido, $CantidadPlatos, $ID_Plato, $ID_User, $ID_Mesa, $Estado) {
        $sql = "UPDATE pedidos SET 
                    NumeroPedido=?, 
                    FechaPedido=?, 
                    CantidadPlatos=?, 
                    ID_Plato=?, 
                    ID_User=?, 
                    ID_Mesa=?, 
                    Estado=? 
                WHERE ID_P=?";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $NumeroPedido, 
            $FechaPedido, 
            $CantidadPlatos, 
            $ID_Plato, 
            $ID_User, 
            $ID_Mesa, 
            $Estado,
            $id
        ]);
    }

    public function eliminarPedidos($id) {
        $sql = "DELETE FROM pedidos WHERE ID_P=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function mesaOcupadaEnFecha($idMesa, $fechaPedido) {
        $sql = "SELECT COUNT(*) AS total 
                FROM pedidos 
                WHERE ID_Mesa = :mesa 
                  AND DATE(FechaPedido) = DATE(:fecha)
                  AND Estado != 'Cancelado'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':mesa', $idMesa, PDO::PARAM_INT);
        $stmt->bindParam(':fecha', $fechaPedido);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row && $row['total'] > 0);
    }
}
