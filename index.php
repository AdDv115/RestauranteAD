<?php
define('ROOT_PATH', __DIR__ . '/');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Principal</title>
    <link href="./Vista/css/index.css" rel="stylesheet">

  <nav>
    <ul>
      <li>  <a href="./index.php"><img src="./Vista/img/Logo.png" id="logo"></a> </li>
      <div id="navbotones">
        <li>  <a class="botonesnav" href="./Vista/html/menu.php">Menu</a>  </li>
        <li>  <a class="botonesnav" href="./Vista/html/contacto.php">Contacto</a> </li>
        <li>  <a class="botonesnav" href="./Vista/html/sn.php">Sobre Nosotros</a> </li>
        <li>  <a class="botonesnav" href="./Vista/html/login.php">Usuario</a> </li>
      </div>
    </ul> 
  </nav>
</head>
<body>



<div class="Contenedor">

<h1 class="text">EL BUEN SABOR</h1>

<div class="Galeria">

  <div class="item">
      <img class="fotos" src="./Vista/img/Salchipa.jpg" alt="Salchipapa">
      <h3 class="text">Salchipapa</h3>
  </div>

  <div class="item">
      <img class="fotos" src="./Vista/img/burge.jpg" alt="Hamburguesa">
      <h3 class="text">Hamburguesa</h3>
  </div>

  <div class="item">
      <img class="fotos" src="./Vista/img/perro.jpg" alt="Perro Suizo">
      <h3 class="text">Perro Suizo</h3>
  </div>

  <div class="item">
      <img class="fotos" src="./Vista/img/pizza.jpg" alt="Pizza">
      <h3 class="text">Pizza</h3>
  </div>

  </div>
  
</div>
</div>
</html>