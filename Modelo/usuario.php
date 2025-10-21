<?php

require_once __DIR__ . "/../Configuracion/conexion.php";

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
        $stmt = $this->db->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function crearUser($nombre, $apellido, $email, $pass, $rolusu, $telefono) {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (Nombre, Apellido, Email, Passwrd, Rolusu, Telefono) values (:nombre, :apellido, :email, :pass, :rolusu, :telefono)";
        $consul = $this -> db -> prepare($sql);
        return $consul -> execute([':nombre' => $nombre, ':apellido' => $apellido, ':email' => $email, ':pass' => $hash, ':rolusu' => $rolusu, ':telefono' => $telefono]);
    }

    public function actualizarUser($id, $nombre, $apellido, $email, $nuevaPass = null, $rolusu, $telefono) {
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

  public function eliminarUser($id) {
    
    $sql = "DELETE FROM usuarios WHERE ID_User = :id";
    
    $consul = $this->db->prepare($sql);
    return $consul->execute([':id' => $id]);
}  

}

?>