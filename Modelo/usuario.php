<?php

require_once "../Configuracion/conexion.php";

class Usuario {
    
    public $db;

    public function __construct() {
        $this->db = DB::connect();
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
        // Implementar si lo necesitas
    }

    public function crearUser() {
        // Implementar si lo necesitas
    }
}

?>