<?php
session_start();
$fecha_minima = date('Y-m-d');
require_once '../../Modelo/ModeloUsuarios.php';

if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario_logueado'];
$usuarios = [];

if ($usuario['Rolusu'] === 'Administrador') {
    $modelUser = new Usuario();
    $_SESSION['lista_usuarios'] = $modelUser->listarUser();
}

$usuarios = $_SESSION['lista_usuarios'] ?? [];

$usuarioEditar = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
    $idEditar = $_POST['editar_id'];
    foreach ($usuarios as $u) {
        if ($u['ID_User'] == $idEditar) {
            $usuarioEditar = $u;
            break;
        }
    }
}

$editarPerfil = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editarPerfil'])) {
    $EP = $_POST['editarPerfil'];
    if ($usuario['ID_User'] == $EP) {
        $editarPerfil = $usuario;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link href="../css/perfil.css" rel="stylesheet">
</head>
<body>
     <nav>
    <ul>
      <li>  <a href="../index.php"><img src="../img/Logo.png" id="logo"></a> </li>
      <div id="navbotones">
        <li>  <a class="botonesnav" href="../../index.php">Inicio</a>  </li>
        <li>  <a class="botonesnav" href="./menu.php">Menu</a>  </li>
        <li>  <a class="botonesnav" href="./contacto.php">Contacto</a> </li>
        <li>  <a class="botonesnav" href="./sn.php">Sobre Nosotros</a> </li>
        <li>  <a class="botonesnav" href="./login.php">Usuario</a> </li>
      </div>
    </ul> 
  </nav>

<div class="Contenedor">
        <div class="user">
            <h1>Bienvenido, <?= htmlspecialchars($usuario['Nombre'])?> <?= htmlspecialchars($usuario['Apellido']) ?>!</h1>
            <img src="perfil.php?imagen=<?= $usuario['ID_User'] ?>" alt="Foto de perfil">
            <h2>Email: <?= htmlspecialchars($usuario['Email']) ?></h2>
            <h2>Teléfono: <?= htmlspecialchars($usuario['Telefono']) ?></h2>

            <!-- Editar perfil -->
            <div class="BotonEditar">
                <form method="POST" action="perfil.php">
                    <input type="hidden" name="editarPerfil" value="<?= htmlspecialchars($usuario['ID_User']) ?>">
                    <button id="btn1" type="submit">Editar</button>
                </form>
                <form method="POST" action="../../Controlador/usuarioController.php?action=cerrarSesion">
                    <input type="hidden" name="CerrarSesion" value="<?= htmlspecialchars($usuario['ID_User']) ?>">
                    <button id="btn1" type="submit">Cerrar Sesion</button>
                </form>
            </div>
    
    </div>

    <?php if ($usuario['Rolusu'] === 'Cliente'): ?>
        
    <div class="FReserva">

        <h1 class="text">Reserva</h1>

        <form class="Reserva" action="../../Controlador/mesaController.php?action=login" method="POST">    

        <label>Fecha de Reserva</label>
        <input type="date" name="FR" required min="<?= $fecha_minima ?>"">

        <label>Hora de Reserva</label>
        <input type="time" name="TR" required min="13:00" max="22:00">

        <label>Numero de Personas</label>
        <select name="NP">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
        </select>
        
        <button type="submit" id="btn">Realizar Reserva</button>
        
    </form>
    </div>

<?php endif; ?> 

    <div class="GesPerfil">

            <?php if ($editarPerfil): ?>

            <div class="form-edicion">

            <h2 class="Crear">Editando Perfil: <?= htmlspecialchars($editarPerfil['Nombre']) ?></h2>

                <form class="Editar" method="POST" action="../../Controlador/usuarioController.php?action=editarperfil" enctype="multipart/form-data">
                
                <input type="hidden" name="ID_User" value="<?= htmlspecialchars($usuario['ID_User']) ?>">
                <input type="text" name="Nombre" placeholder="Nombre" value="<?= htmlspecialchars($usuario['Nombre']) ?>" required>
                <input type="text" name="Apellido" placeholder="Apellido" value="<?= htmlspecialchars($usuario['Apellido']) ?>" required>
                <input type="password" name="Password" placeholder="Nueva contraseña (opcional)">
                <input type="text" name="Telefono" placeholder="Telefono" value="<?= htmlspecialchars($usuario['Telefono']) ?>">
                <input type="file" name="Imagen">

                    <button id="btn" type="submit">Actualizar</button>
                    
                </form>
    </div>
    
        <?php endif; ?> 

            <!-- Si es Administrador, Visualizar Gestion Admin -->
            <?php if ($usuario['Rolusu'] === 'Administrador'): ?>
            <h2>Rol: <?= htmlspecialchars($usuario['Rolusu']) ?></h2>

    </div>        

            <div id="opciones">
                <a class="botonesnav" href="./rplato.php">Gestionar Platos</a> 
                <a class="botonesnav" href="./mesas.php">Gestionar Mesas</a>
            </div>

        <div class="GesAdmin">

            <h1>Gestión de Usuarios</h1>  

            <h2 class="Crear">Crear nuevo usuario</h2>

            <!-- Crear usuario -->
            <form class="Crear" method="POST" action="../../Controlador/adminController.php?action=registrarA">
                
                <input type="text" name="Nombre" placeholder="Nombre" required>
                <input type="text" name="Apellido" placeholder="Apellido" required>
                <input type="email" name="Email" placeholder="Email" required>
                <input type="password" name="Password" placeholder="Contraseña" required>
                <input type="text" name="Telefono" placeholder="Teléfono">

                <select name="Rolusu">
                    <option value="Administrador">Administrador</option>
                    <option value="Cliente">Cliente</option>
                </select>

                <button id="btn" type="submit">Añadir Usuario</button>

            </form>

            <!-- Tabla usuarios -->

            <h2 class="Crear">Usuarios Registrados</h2>

            <table>
                <tr>
                    <th>ID</th> <th>Nombre</th> <th>Apellido</th> <th>Email</th> <th>Rol</th> <th>Teléfono</th>  <th>Acciones</th>
                </tr>
                <?php foreach($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['ID_User']) ?></td>
                    <td><?= htmlspecialchars($u['Nombre']) ?></td>
                    <td><?= htmlspecialchars($u['Apellido']) ?></td>
                    <td><?= htmlspecialchars($u['Email']) ?></td>
                    <td><?= htmlspecialchars($u['Rolusu']) ?></td>
                    <td><?= htmlspecialchars($u['Telefono']) ?></td> 
                    <td>
                        <!-- Editar -->
                        <form method="POST" action="perfil.php">
                            <input type="hidden" name="editar_id" value="<?= htmlspecialchars($u['ID_User']) ?>">
                            <button id="btn" type="submit">Editar</button>
                        </form>

                        <!-- Eliminar -->
                        <form method="POST" action="../../Controlador/adminController.php?action=eliminarA">

                            <input type="hidden" name="ID_User" value="<?= htmlspecialchars($u['ID_User']) ?>">
                            <button id="btn" type="submit" class="delete">Eliminar</button>

                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <?php if ($usuarioEditar): ?>

            <div class="form-edicion">

            <h2 class="Crear">Editando usuario: <?= htmlspecialchars($usuarioEditar['Nombre']) ?></h2>

                <form class="Editar" method="POST" action="../../Controlador/adminController.php?action=actualizarA">
                
                <input type="hidden" name="ID_User" value="<?= htmlspecialchars($usuarioEditar['ID_User']) ?>">
                <input type="text" name="Nombre" placeholder="Nombre" value="<?= htmlspecialchars($usuarioEditar['Nombre']) ?>" required>
                <input type="text" name="Apellido" placeholder="Apellido" value="<?= htmlspecialchars($usuarioEditar['Apellido']) ?>" required>
                <input type="email" name="Email" placeholder="Correo Electronico"  value="<?= htmlspecialchars($usuarioEditar['Email']) ?>" required>
                <input type="password" name="Password" placeholder="Nueva contraseña (opcional)">
                <input type="text" name="Telefono" placeholder="Telefono" value="<?= htmlspecialchars($usuarioEditar['Telefono']) ?>">
                
                <select name="Rolusu">
                    <option value="Administrador" <?= $usuarioEditar['Rolusu'] == "Administrador" ? "selected" : "" ?>>Administrador</option>
                    <option value="Cliente" <?= $usuarioEditar['Rolusu'] == "Cliente" ? "selected" : "" ?>>Cliente</option>
                </select>

                    <button id="btn" type="submit">Actualizar</button>
                    
                </form>
            </div>
        <?php endif; ?>

        </div>
        <?php endif; ?>
    

</html>
