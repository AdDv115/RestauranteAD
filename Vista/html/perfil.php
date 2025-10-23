<?php
session_start();
// Validación de sesión

require_once '../../Modelo/usuario.php';

$usuario = $_SESSION['usuario_logueado'];
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

if ($usuario['Rolusu'] === 'Administrador' && empty($_SESSION['lista_usuarios'])) {
    $modelUser = new Usuario();
    $_SESSION['lista_usuarios'] = $modelUser->listarUser();
    $usuarios = $_SESSION['lista_usuarios'];
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

<div class="Contenedor">
        <div class="user">
            <h1>Bienvenido, <?= htmlspecialchars($usuario['Nombre']) ?>!</h1>
            <img src="https://www.iconpacks.net/icons/2/free-user-icon-3296-thumb.png">
            <h2>Email: <?= htmlspecialchars($usuario['Email']) ?></h2>
            <h2>Teléfono: <?= htmlspecialchars($usuario['Telefono']) ?></h2>
            <h2>Rol: <?= htmlspecialchars($usuario['Rolusu']) ?></h2>
</div>

    <?php if ($usuario['Rolusu'] === 'Administrador'): ?>

        <div class="GesAdmin">
            <a class="botonesnav" href="./rplato.php">Gestionar Platos</a> <h1>Gestión de Usuarios</h1>  

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
                        <form method="POST" action="../../controlador/adminController.php?action=eliminarA">

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
                <input type="text" name="Nombre" value="<?= htmlspecialchars($usuarioEditar['Nombre']) ?>" required>
                <input type="text" name="Apellido" value="<?= htmlspecialchars($usuarioEditar['Apellido']) ?>" required>
                <input type="email" name="Email" value="<?= htmlspecialchars($usuarioEditar['Email']) ?>" required>
                <input type="password" name="Password" placeholder="Nueva contraseña (opcional)">
                <input type="text" name="Telefono" value="<?= htmlspecialchars($usuarioEditar['Telefono']) ?>">
                
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
    </div>
</body>
</html>
