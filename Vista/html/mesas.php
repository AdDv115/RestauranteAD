<?php
session_start();
require_once '../../Modelo/ModeloUsuarios.php';
require_once '../../Modelo/ModeloMesas.php';

if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario_logueado'];
$mesas = $_SESSION['lista_mesas'] ?? [];

$ReEditar = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
    $idEditar = $_POST['editar_id'];
    foreach ($mesas as $m) {
        if ($m['ID_R'] == $idEditar) {
            $ReEditar = $m;
            break;
        }
    }
}

if ($usuario['Rolusu'] === 'Administrador' && empty($_SESSION['lista_mesas'])) {
    $modelreserva = new Mesas();
    $_SESSION['lista_mesas'] = $modelreserva->obtenerMesas();
    $mesas = $_SESSION['lista_mesas'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Mesas</title>
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
                    <li><a class="botonesnav" href="./pedidos.php">Mis pedidos</a></li>
                    <li><a class="botonesnav" href="./reservas.php">Mis reservas</a></li>
                    <li><a class="botonesnav" href="./reservas.php">Mis Domicilios</a></li>
                    <li><a class="botonesnav" href="./menu.php">Menu</a></li>
                    <li><a class="botonesnav" href="../../Controlador/usuarioController.php?action=cerrarSesion">Cerrar Sesion</a></li>
                </div>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<div class="Contenedor">

    <?php if ($usuario['Rolusu'] === 'Administrador'): ?>

     <div id="opciones">
        <a class="botonesnav" href="./perfil.php">Gestionar Usuarios</a>
        <a class="botonesnav" href="./rplato.php">Gestionar Platos</a>
        <a class="botonesnav" href="./pedidos.php">Gestionar Pedidos</a>
        <a class="botonesnav" href="./reservas.php">Gestionar Reservas</a>
        <a class="botonesnav" href="./domicilios.php">Gestionar Domicilios</a>
    </div>

    <div class="GesAdmin">
        
        <h1>Gestión de Mesas</h1>

        <h2 class="Crear">Registrar Mesa</h2>
        <form class="Crear" method="POST" action="../../Controlador/mesaController.php?action=RegistrarR">
            <input type="number" name="NM" placeholder="Numero de la Mesa" required>
            <input type="number" name="CaP" placeholder="Capacidad de la Mesa" required>
            <input type="text" name="UB" placeholder="Ubicacion" required>
            <select name="DP">
                <option value="1">Disponible</option>
                <option value="0">No disponible</option>
            </select>
            <button id="btn" type="submit">Registrar Mesa</button>
        </form>

        <h2 class="Crear">Mesas Registradas</h2>
        <table>
            <tr>
                <th>ID</th><th>Numero de Mesa</th><th>Capacidad</th><th>Ubicacion</th><th>Disponible</th><th>Acciones</th>
            </tr>
            <?php foreach ($mesas as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['ID_R']) ?></td>
                <td><?= htmlspecialchars($m['NumeroMesa']) ?></td>
                <td><?= htmlspecialchars($m['Capacidad']) ?></td>
                <td><?= htmlspecialchars($m['Ubicacion']) ?></td>
                <td><?= $m['Disponibilidad'] == 1 ? "Disponible" : "No disponible" ?></td>
                <td>
                    <form method="POST" action="mesas.php">
                        <input type="hidden" name="editar_id" value="<?= htmlspecialchars($m['ID_R']) ?>">
                        <button id="btn" type="submit">Editar</button>
                    </form>
                    <form method="POST" action="../../Controlador/mesaController.php?action=EliminarR">
                        <input type="hidden" name="ID_R" value="<?= htmlspecialchars($m['ID_R']) ?>">
                        <button id="btn" type="submit" class="delete">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($ReEditar): ?>
        <div class="form-edicion">
            <h2 class="Crear">Editando Mesa: <?= htmlspecialchars($ReEditar['NumeroMesa']) ?></h2>
            <form class="Editar" method="POST" action="../../Controlador/mesaController.php?action=ActualizarR">
                <input type="hidden" name="ID_R" value="<?= htmlspecialchars($ReEditar['ID_R']) ?>">
                <input type="number" name="NM" value="<?= htmlspecialchars($ReEditar['NumeroMesa']) ?>" required>
                <input type="number" name="CaP" value="<?= htmlspecialchars($ReEditar['Capacidad']) ?>" required>
                <input type="text" name="UB" value="<?= htmlspecialchars($ReEditar['Ubicacion']) ?>" required>
                <select name="DP">
                    <option value="1" <?= $ReEditar['Disponibilidad'] == 1 ? "selected" : "" ?>>Disponible</option>
                    <option value="0" <?= $ReEditar['Disponibilidad'] == 0 ? "selected" : "" ?>>No disponible</option>
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