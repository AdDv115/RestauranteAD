<?php

require_once "../Configuracion/conexion.php";

class Usuario {
    
    public $db;

    public function __construct() {
        $this -> db = DB::connect();
    }

    public function obtenerUser($email) {
        $sql = "SELECT * FROM usuarios WHERE Email = :Email LIMIT 1";
        $consul = $this->db->prepare($sql);
        $consul->execute([":Email" => $email]);

        return $consul->fetch();
    }

    public function login($email, $pass) {
        $usuario = $this->obtenerUser($email);
        if ($usuario && password_verify($pass, $usuario['Passwrd'])) {
            return $usuario;
        } else {
            return false;
        }
    }

    public function listarUser() {
       $stmt = $this -> db -> query("SELECT * FROM usuarios");
       return $stmt -> fetchAll();
    }

    public function crearUser($nombre,$apellido,$email, $pass, $rolusu, $telefono) {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (Nombre, Apellido, Email, Passwrd, Rolusu, Telefono) values (:nombre, :apellido, :email, :pass,:rolusu, :telefono)";
        $consul = $this -> db -> prepare($sql);
        return $consul -> execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':pass' => $hash, ':rolusu' => $rolusu, ':telefono' => $telefono]);
    }
}

?>