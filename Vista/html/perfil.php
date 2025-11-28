<?php
session_start();

require_once '../../Modelo/ModeloUsuarios.php';

if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario_logueado'];
$modelUser = new Usuario();

$usuarios = [];
$usuarioEditar = null;

if ($usuario['Rolusu'] === 'Administrador') {
    $_SESSION['lista_usuarios'] = $modelUser->listarUser();
}

$usuarios = $_SESSION['lista_usuarios'] ?? [];

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

if (isset($_GET['editarPerfil']) && $_GET['editarPerfil'] == $usuario['ID_User']) {
    $editarPerfil = $usuario;
}
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

<?php if ($usuario): ?>
    <?php if ($usuario['Rolusu'] === 'Administrador'): ?>
        <!-- NAV ADMIN -->
        <nav>
            <ul>
                <li>
                    <a href="../../index.php">
                        <img src="../img/Logo.png" id="logo" alt="Logo">
                    </a>
                </li>
                <div id="navbotones">
                    <li>
                        <a href="./perfil.php">
                            <img id="fpp" src="../img/uP/<?= htmlspecialchars($usuario['ImagenPerfil'] ?? 'default.png') ?>" alt="Foto de perfil">
                        </a>
                    </li>
                    <li><a class="botonesnav" href="./rplato.php">Gestionar Platos</a></a></li>
                    <li><a class="botonesnav" href="./mesas.php">Gestionar Mesas</a></li>
                    <li><a class="botonesnav" href="./reservas.php">Gestionar Reservas</a></li>
                    <li><a class="botonesnav" href="./pedidos.php">Gestionar Pedidos</a></li>

                    <li><a class="botonesnav" href="./menu.php">Menu</a></li>
        
                    <li><a class="botonesnav" href="../../Controlador/usuarioController.php?action=cerrarSesion">Cerrar Sesion</a></li>
                </div>
            </ul>
        </nav>
    <?php else: ?>
        <!-- NAV CLIENTE -->
        <nav>
            <ul>
                <li>
                    <a href="../../index.php">
                        <img src="../img/Logo.png" id="logo" alt="Logo">
                    </a>
                </li>
                <div id="navbotones">
                    <li>
                        <a href="./perfil.php">
                            <img id="fpp" src="../img/uP/<?= htmlspecialchars($usuario['ImagenPerfil'] ?? 'default.png') ?>" alt="Foto de perfil">
                        </a>
                    </li>
                    <li><a class="botonesnav" href="./pedidos.php">Mis Pedidos</a></li>
                    <li><a class="botonesnav" href="./reservas.php">Mis Reservas</a></li>
                    <li><a class="botonesnav" href="./domicilios.php">Mis Domicilios</a></li>
                    <li><a class="botonesnav" href="./menu.php">Menu</a></li>
                    <li><a class="botonesnav" href="../../Controlador/usuarioController.php?action=cerrarSesion">Cerrar Sesion</a></li>
                </div>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<div class="Contenedor">
    <div class="user">
        <h1>Bienvenido, <?= htmlspecialchars($usuario['Nombre']) ?> <?= htmlspecialchars($usuario['Apellido']) ?>!</h1>

        <img id="fp" src="../img/uP/<?= htmlspecialchars($usuario['ImagenPerfil'] ?? 'default.png') ?>" alt="Foto de perfil">

        <h2>Email: <?= htmlspecialchars($usuario['Email']) ?></h2>
        <h2>Teléfono: <?= htmlspecialchars($usuario['Telefono']) ?></h2>

        <div class="BotonEditar">
            <a href="perfil.php?editarPerfil=<?= htmlspecialchars($usuario['ID_User']) ?>#form-edicion-perfil" id="btn1" class="editar-perfil-btn">Editar</a>

            <form method="POST" action="../../Controlador/usuarioController.php?action=cerrarSesion">
                <input type="hidden" name="CerrarSesion" value="<?= htmlspecialchars($usuario['ID_User']) ?>">
                <button id="btn1" type="submit">Cerrar Sesion</button>
            </form>
        </div>
    </div>

    <div class="GesPerfil">
        <?php if ($editarPerfil): ?>
            <div class="form-edicion" id="form-edicion-perfil">
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

        <?php if ($usuario['Rolusu'] === 'Administrador'): ?>
            <h2>Rol: <?= htmlspecialchars($usuario['Rolusu']) ?></h2>
    </div>

     <div id="opciones">
        <a class="botonesnav" href="./pedidos.php">Gestionar Pedidos</a>
        <a class="botonesnav" href="./rplato.php">Gestionar Platos</a>
        <a class="botonesnav" href="./mesas.php">Gestionar Mesas</a>
        <a class="botonesnav" href="./reservas.php">Gestionar Reservas</a>
        <a class="botonesnav" href="./domicilios.php">Gestionar Domicilios</a>
    </div>

    <div class="GesAdmin">
        <h1>Gestión de Usuarios</h1>

        <h2 class="Crear">Crear nuevo usuario</h2>
        <form class="Crear" method="POST" action="../../Controlador/adminController.php?action=registrarA">
            <input type="text" name="Nombre" placeholder="Nombre" required>
            <input type="text" name="Apellido" placeholder="Apellido" required>
            <input type="email" name="Email" placeholder="Email" required>
            <input type="password" name="Password" placeholder="Contraseña" required>

            <select name="Rolusu">
                <option value="Administrador">Administrador</option>
                <option value="Cliente">Cliente</option>
            </select>

            <input type="text" name="Telefono" placeholder="Teléfono">

            <button id="btn" type="submit">Añadir Usuario</button>
        </form>
            

        <h2 class="Crear">Usuarios Registrados</h2>
        <table>
            <tr>
                <th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Rol</th><th>Teléfono</th><th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['ID_User']) ?></td>
                    <td><?= htmlspecialchars($u['Nombre']) ?></td>
                    <td><?= htmlspecialchars($u['Apellido']) ?></td>
                    <td><?= htmlspecialchars($u['Email']) ?></td>
                    <td><?= htmlspecialchars($u['Rolusu']) ?></td>
                    <td><?= htmlspecialchars($u['Telefono']) ?></td>
                    <td>
                        <form method="POST" action="perfil.php#form-edicion-admin">
                            <input type="hidden" name="editar_id" value="<?= htmlspecialchars($u['ID_User']) ?>">
                            <button id="btn" type="submit">Editar</button>
                        </form>

                        <form method="POST" action="../../Controlador/adminController.php?action=eliminarA">
                            <input type="hidden" name="ID_User" value="<?= htmlspecialchars($u['ID_User']) ?>">
                            <button id="btn" type="submit" class="delete">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($usuarioEditar): ?>
            <div class="form-edicion" id="form-edicion-admin">
                <h2 class="Crear">Editando usuario: <?= htmlspecialchars($usuarioEditar['Nombre']) ?></h2>

                <form class="Editar" method="POST" action="../../Controlador/adminController.php?action=actualizarA">
                    <input type="hidden" name="ID_User" value="<?= htmlspecialchars($usuarioEditar['ID_User']) ?>">
                    <input type="text" name="Nombre" placeholder="Nombre" value="<?= htmlspecialchars($usuarioEditar['Nombre']) ?>" required>
                    <input type="text" name="Apellido" placeholder="Apellido" value="<?= htmlspecialchars($usuarioEditar['Apellido']) ?>" required>
                    <input type="email" name="Email" placeholder="Correo Electronico" value="<?= htmlspecialchars($usuarioEditar['Email']) ?>" required>
                    <input type="password" name="Password" placeholder="Nueva contraseña (opcional)">
                    <select name="Rolusu">
                        <option value="Administrador" <?= $usuarioEditar['Rolusu'] == "Administrador" ? "selected" : "" ?>>Administrador</option>
                        <option value="Cliente" <?= $usuarioEditar['Rolusu'] == "Cliente" ? "selected" : "" ?>>Cliente</option>
                    </select>
                    <input type="text" name="Telefono" placeholder="Telefono" value="<?= htmlspecialchars($usuarioEditar['Telefono']) ?>">
                
                    <button id="btn" type="submit">Actualizar</button>
                </form>
            </div>
        <?php endif; ?>

    </div>
    <?php endif; ?>

</div>

</body>
</html>
