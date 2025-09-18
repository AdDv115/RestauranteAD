<?php

#Llamar a la conexion
require_once "conexion.php";

try{
    #Instanciar clase para la conexion
    $db = DB::connect();
    $email = "b@gmail.com";

    #Consultar si ese usuario se encuentra registrado
    $consul = $db -> prepare("SELECT * FROM usuarios WHERE Email = :Email");
    $consul -> execute([":Email" => $email]);

    #Registrar los datos de usuario y contraseña
    if(!$consul -> fetch()){
        $pass = password_hash("ad115",PASSWORD_BCRYPT);
        
        #Crear INSERT
        $sql = "INSERT INTO usuarios(Nombre,Apellido,Email,Passwrd,Rolusu,Telefono) VALUES('Admin','Principal',:Email,:Passw,'Administrador','3011011010')";
        $consult = $db -> prepare($sql);
        $consult -> execute([":Email" => $email,":Passw" => $pass]);
        echo "Usuario Admin Creado";
    
    }else{
        echo "Administrador ya existe";
    }

}catch(PDOException $e){
    die("Error".$e -> getMessage());
}
?>