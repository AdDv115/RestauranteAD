<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}
require_once __DIR__ . "/../Configuracion/conexion.php";

class Usuario {
    
    public $db;

    public function __construct() {
        $this -> db = DB::connect();
    }

    public function obtenerUser($id) {
        $sql = "SELECT * FROM usuarios WHERE ID_User = :ID_User LIMIT 1";
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute([":ID_User" => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerUserPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE Email = :Email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":Email" => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function login($email, $pass) {
        $usuario = $this->obtenerUserPorEmail($email);
        if ($usuario && password_verify($pass, $usuario['Passwrd'])) {
            return $usuario;
        } else {
            return false;
        }
    }

    public function listarUser() {
        $stmt = $this->db->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearUser($nombre, $apellido, $email, $pass, $rolusu, $telefono) {

        $hash = password_hash($pass, PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuarios (Nombre, Apellido, Email, Passwrd, Rolusu, Telefono) values (:nombre, :apellido, :email, :pass, :rolusu, :telefono)";
        
        $consul = $this -> db -> prepare($sql);

        return $consul -> execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':pass' => $hash, ':rolusu' => $rolusu, ':telefono' => $telefono]);
    }

    public function actualizarUser($id, $nombre, $apellido, $email, $rolusu, $telefono, $ImagenPerfil, $nuevaPass = null) {
        if (!empty($nuevaPass)) {
            $hash = password_hash($nuevaPass, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET Nombre = :nombre, Apellido = :apellido, Email = :email, Passwrd = :pass, Rolusu = :rolusu, Telefono = :telefono, ImagenPerfil = :imaperfil WHERE ID_User = :id";

            $consul = $this-> db ->prepare($sql);

            return $consul -> execute([':id' => $id, ':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':pass' => $hash, ':rolusu' => $rolusu, ':telefono' => $telefono, ':imaperfil' => $ImagenPerfil]);

        } else {

            $sql = "UPDATE usuarios SET Nombre = :nombre, Apellido = :apellido, Email = :email, Rolusu = :rolusu, Telefono = :telefono, ImagenPerfil = :imaperfil WHERE ID_User = :id";
            $consul = $this -> db ->prepare($sql);

            return $consul -> execute([':id' => $id, ':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email,':rolusu' => $rolusu, ':telefono' => $telefono, ':imaperfil' => $ImagenPerfil]);
        }
    }

    public function actualizarUserAD($id, $nombre, $apellido, $email, $rolusu, $telefono, $nuevaPass = null) {
        if (!empty($nuevaPass)) {
            $hash = password_hash($nuevaPass, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET Nombre = :nombre, Apellido = :apellido, Email = :email, Passwrd = :pass, Rolusu = :rolusu, Telefono = :telefono WHERE ID_User = :id";

            $consul = $this-> db ->prepare($sql);

            return $consul -> execute([':id' => $id, ':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':pass' => $hash, ':rolusu' => $rolusu, ':telefono' => $telefono]);

        } else {

            $sql = "UPDATE usuarios SET Nombre = :nombre, Apellido = :apellido, Email = :email, Rolusu = :rolusu, Telefono = :telefono WHERE ID_User = :id";
            $consul = $this -> db ->prepare($sql);

            return $consul -> execute([':id' => $id, ':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email,':rolusu' => $rolusu, ':telefono' => $telefono]);
        }
    }

    public function EditarP($id, $nombre, $apellido, $pass, $telefono, $ImagenPerfil) {
        if (!empty($pass)) {
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET Nombre = :nombre, Apellido = :apellido, Passwrd = :pass, Telefono = :telefono, ImagenPerfil = :imaperfil WHERE ID_User = :id";

            $consul = $this-> db ->prepare($sql);

            return $consul -> execute([
                ':id' => $id, 
                ':nombre' => $nombre, 
                ':apellido' => $apellido, 
                ':pass' => $hash, 
                ':telefono' => $telefono, 
                ':imaperfil' => $ImagenPerfil
            ]);

        } else {

            $sql = "UPDATE usuarios SET Nombre = :nombre, Apellido = :apellido, Telefono = :telefono, ImagenPerfil = :imaperfil WHERE ID_User = :id";
            $consul = $this -> db ->prepare($sql);

            return $consul -> execute([
                ':id' => $id, 
                ':nombre' => $nombre, 
                ':apellido' => $apellido, 
                ':telefono' => $telefono, 
                ':imaperfil' => $ImagenPerfil
            ]);
        }

    }

    public function eliminarUser($id) {
        
        $sql = "DELETE FROM usuarios WHERE ID_User = :id";
        
        $consul = $this->db->prepare($sql);
        return $consul->execute([':id' => $id]);
    }  

}