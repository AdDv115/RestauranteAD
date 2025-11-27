<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="../css/user.css" rel="stylesheet">

  <nav>
    <ul>
      <li>  <a href="../index.php"><img src="../img/Logo.png" id="logo"></a> </li>
      <div id="navbotones">
        <li>  <a class="botonesnav" href="../../index.php">Inicio</a>  </li>
        
      </div>
    </ul> 
  </nav>
</head>
<body>

<div class="Contenedor">
  <h1 class="text">Registro</h1>
  
    <form action="../../Controlador/usuarioController.php?action=registrar" method="POST">

        <label>Nombre</label>
        <input type="text" name="Nombre">
    
        <label>Apellido</label>
        <input type="text" name="Apellido">
    
        <label>Email</label>
        <input type="email" name="Email">

        <label>Telefono</label>
        <input type="tel" name="Telefono">

        <input hidden name="rol" value="Cliente">

        <label>Contraseña</label>
        <input type="password" name="Password">

        <button type="submit" id="btn">Registrarse</button>
    </form>
</div>

</html>