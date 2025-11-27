<?php
session_start();

$fecha_minima = date('Y-m-d');

require_once '../../Modelo/ModeloUsuarios.php';
require_once '../../Modelo/ModeloReservas.php';

if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario_logueado'];
$modelUser = new Usuario();
$modelReservas = new Reservas();

$reservas = [];

if ($usuario['Rolusu'] === 'Administrador') {
    $reservas = $modelReservas->obtenerReservas();
} else {
    $reservas = $modelReservas->obtenerReservas();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservas</title>
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

    <?php if ($usuario['Rolusu'] === 'Cliente'): ?>

        <div class="FReserva">
            <h1 class="text">Reserva</h1>

            <form class="Reserva" action="../../Controlador/reservaController.php?action=RegistrarR" method="POST">
                <label>Fecha de Reserva</label>
                <input type="date" name="FR" required min="<?= $fecha_minima ?>">

                <label>Hora de Reserva</label>
                <input type="time" name="HR" required min="13:00" max="22:00">

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

        <div class="ListaReservas">
            <h2 class="Crear">Mis Reservas</h2>
            <table>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Personas</th>
                    <th>Mesa</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($reservas as $r): ?>
                    <?php if ($r['ID_User'] == $usuario['ID_User']): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['FechaReserva']) ?></td>
                            <td><?= htmlspecialchars($r['HoraReserva']) ?></td>
                            <td><?= htmlspecialchars($r['NumeroPersonas']) ?></td>
                            <td><?= htmlspecialchars($r['NumeroMesa']) ?></td>
                            <td>
                                <form method="POST" action="../../Controlador/reservaController.php?action=EliminarR" style="display: inline;">
                                    <input type="hidden" name="ID_R" value="<?= htmlspecialchars($r['ID_RE']) ?>">
                                    <button id="btn" type="submit" class="delete">Cancelar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        </div>

    <?php endif; ?>

    <?php if ($usuario['Rolusu'] === 'Administrador'): ?>

 <div id="opciones">
        <a class="botonesnav" href="./perfil.php">Gestionar Usuarios</a>
        <a class="botonesnav" href="./rplato.php">Gestionar Platos</a>
        <a class="botonesnav" href="./mesas.php">Gestionar Mesas</a>
        <a class="botonesnav" href="./pedidos.php">Gestionar Pedidos</a>
        <a class="botonesnav" href="./domicilios.php">Gestionar Domicilios</a>
    </div>

        <div class="GesReservas">
            <h1 class="Crear">Gestión de Reservas</h1>
            <table>
                <tr>
                    <th>ID Reserva</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Personas</th>
                    <th>Mesa Asignada</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($reservas as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['ID_RE']) ?></td>
                        <td><?= htmlspecialchars($r['Nombre']) ?> <?= htmlspecialchars($r['Apellido']) ?></td>
                        <td><?= htmlspecialchars($r['FechaReserva']) ?></td>
                        <td><?= htmlspecialchars($r['HoraReserva']) ?></td>
                        <td><?= htmlspecialchars($r['NumeroPersonas']) ?></td>
                        <td><?= htmlspecialchars($r['NumeroMesa']) ?></td>
                        <td>
                            <form method="POST" action="../../Controlador/reservaController.php?action=EliminarR" style="display: inline;">
                                <input type="hidden" name="ID_R" value="<?= htmlspecialchars($r['ID_RE']) ?>">
                                <button id="btn" type="submit" class="delete">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
