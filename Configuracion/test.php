<?php

require_once "conexion.php";

    try{
    $db = DB::connect();
        echo "Conexion Exitosa";
    }
    catch(PDOException $e){
        echo "Error de conexion".$e -> getMessage;
    }

?>