<?php

define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
require_once(ROOT_PATH . 'Modelo/plato.php');

$plato = new Plato();
$platos = $plato -> obtenerPlato();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar platos</title>
    <link href="../css/user.css" rel="stylesheet">

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

    <h1 class="text">Añadir Plato</h1>

    <form action="../../Controlador/platoController.php" method="POST" enctype="multipart/form-data">    

        <label>Nombre del plato</label>
        <input type="text" name="NombreP">

        <label>Descripcion</label>
        <input type="text" name="Descripcion">

        <label>Precio del plato</label>
        <input type="number" name="PrecioP">

        <label>Imagen del Plato</label>
        <input type="file" name="Imagen">

        <label>Disponible?</label>
        <select name="Disponible">
            <option value="Si">Si</option>
            <option value="No">No</option>
        </select>

        <button type="submit" id="btn">Agregar Plato</button>
        
    </form>
 
    <table>
            <tr>
                <th>ID</th><th>Nombre Del Plato</th><th>Descripcion</th><th>Precio</th><th>Disponible</th><th>Acciones</th>
            </tr>
            <?php foreach($platos as $p): ?>
            <tr>
                <td><?= $p['ID_Plato'] ?></td>
                <td><?= $p['NombrePlato'] ?></td>
                <td><?= $p['Descripcion'] ?></td>
                <td><?= $p['Precio'] ?></td>
                <td><?= $p['Disponible'] ?></td>
                <td>
                    <!-- Editar -->
                    <form method="POST" action="../../Controlador/platoController.php" class="inline">

                        <input type="hidden" name="accion" value="Actualizar">
                        <input type="hidden" name="ID_Plato">
                        <input type="text" name="NombrePlato">
                        <input type="email" name="Descripcion">

                        <select name="Disponible">
                            <option value="Si" <?= $p['Disponible'] == "Si"?"selected":"" ?>>Si</option>
                            <option value="No" <?= $p['Disponible'] == "No"?"selected":"" ?>>No</option>
                        </select>

                        <button type="submit">Actualizar</button>
                    </form>

                    <!-- Eliminar -->
                    <form method="POST" action="../../controlador/UsuarioController.php" class="inline">
                        
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id_usuario" value="<?= $p['ID_Plato'] ?>">
                    
                        <button type="submit" class="delete">Eliminar</button>

                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
</div>
</html>