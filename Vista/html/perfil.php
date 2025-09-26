<?php session_start() ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="../css/perfil.css" rel="stylesheet">

  <nav>
    <ul>
      <li>  <a href="../index.php"><img src="../img/logo.png" id="logo"></a> </li>
      <div id="navbotones">
        <li>  <a class="botonesnav" href="../../index.php">Inicio</a>  </li>
        <li>  <a class="botonesnav" href="./menu.php">Menu</a>  </li>
        <li>  <a class="botonesnav" href="./contacto.php">Contacto</a> </li>
        <li>  <a class="botonesnav" href="./sn.php">Sobre Nosotros</a> </li>
        <li>  <a class="botonesnav" href="./login.php">Usuario</a> </li>
      </div>
    </ul> 
  </nav>
</head>
<body>

<div class="Contenedor">
    <div class="user">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?>></h1>
        <img src="https://www.iconpacks.net/icons/2/free-user-icon-3296-thumb.png">
        <h2>Email: </h2>
        <h2>Telefono: </h2>
        <h2>Rol: </h2>
    </div>
</div>

</html>