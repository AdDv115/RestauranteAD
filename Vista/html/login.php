<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion</title>
    <link href="../css/user.css" rel="stylesheet">
</head>

<body>

<nav>
    <ul>
        <li><a href="../index.php"><img src="../img/Logo.png" id="logo"></a></li>
        <div id="navbotones">
            <li><a class="botonesnav" href="../../index.php">Inicio</a></li>
        </div>
    </ul>
</nav>

<div class="Contenedor">

    <h1 class="text">Iniciar Sesion</h1>

    <form action="../../Controlador/usuarioController.php?action=login" method="POST">    
        <label>Email</label>
        <input type="email" name="Email">

        <label>Contraseña</label>
        <input type="password" name="Password">

        <button type="submit" id="btn">Iniciar Sesion</button>
    </form>

    <h3 class="text">¿No tiene cuenta? <a href="./registro.php">Registrarse</a></h3> 
</div>

</body>
</html>
