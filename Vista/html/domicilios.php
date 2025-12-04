<?php
session_start();
require_once '../../Modelo/ModeloDomicilios.php';
require_once '../../Modelo/ModeloUsuarios.php';
require_once '../../Modelo/ModeloPedidos.php';
require_once '../../Modelo/ModeloPlatos.php';

if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario_logueado'];
$esAdmin = ($usuario['Rolusu'] ?? '') === 'Administrador';

$modelDomicilios = new Domicilios();
$modelPedidos = new Pedidos();
$modelPlatos = new Plato();

$domicilios = [];
$misPedidos = [];
$misPedidosAgrupados = [];
$domiciliosPorPedido = [];
$platos = $modelPlatos->obtenerPlato() ?? [];

if ($esAdmin) {
    $domicilios = $modelDomicilios->obtenerDomicilios() ?? [];
} else {
    $misPedidos = $modelPedidos->obtenerPedidosPorUsuario($usuario['ID_User']) ?? [];
    foreach ($misPedidos as $p) {
        $num = $p['NumeroPedido'] ?? $p['ID_P'];
        if (!isset($misPedidosAgrupados[$num])) {
            $misPedidosAgrupados[$num] = $p;
        }
        $domicilio = $modelDomicilios->obtenerPorPedido($p['ID_P']);
        if ($domicilio) {
            $domiciliosPorPedido[$p['ID_P']] = $domicilio;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Domicilios</title>
    <link href="../css/perfil.css" rel="stylesheet">
</head>
<body>
    <?php if ($esAdmin): ?>
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
                    <li><a class="botonesnav" href="../../Controlador/usuarioController.php?action=cerrarSesion">Cerrar Sesion</a></li>
                </div>
            </ul>
        </nav>
    <?php else: ?>
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
                    <li><a class="botonesnav" href="./Domicilios.php">Mis Domicilios</a></li>
                    <li><a class="botonesnav" href="./menu.php">Menu</a></li>
                    <li><a class="botonesnav" href="../../Controlador/usuarioController.php?action=cerrarSesion">Cerrar Sesion</a></li>
                </div>
            </ul>
        </nav>
    <?php endif; ?>

    <div class="Contenedor">
        <?php if ($esAdmin): ?>

            <div id="opciones">
            <a class="botonesnav" href="./perfil.php">Gestionar Usuarios</a>
            <a class="botonesnav" href="./rplato.php">Gestionar Platos</a>
            <a class="botonesnav" href="./mesas.php">Gestionar Mesas</a>
            <a class="botonesnav" href="./pedidos.php">Gestionar Pedidos</a>
            <a class="botonesnav" href="./reservas.php">Gestionar Reservas</a>
        </div>

            <div class="GesAdmin">
                <h1>Gestion de Domicilios</h1>
                <h2 class="Crear">Pedidos con entrega a domicilio</h2>

                <table>
                    <tr>
                        <th>N° Pedido</th>
                        <th>Plato</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Direccion de entrega</th>
                        <th>Contacto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    <?php foreach ($domicilios as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['NumeroPedido'] ?? $d['ID_Pedido'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($platos['NombrePlato'] ?? '') ?></td>
                        <td><?= htmlspecialchars($d['FechaPedido'] ?? '') ?></td>
                        <td><?= htmlspecialchars(trim(($d['Nombre'] ?? '') . ' ' . ($d['Apellido'] ?? ''))) ?></td>
                        <td>
                            <form method="POST" action="../../Controlador/domicilioController.php?action=actualizar">
                                <input type="hidden" name="ID_Domicilio" value="<?= htmlspecialchars($d['ID_Domicilio']) ?>">
                                <input type="text" name="DireccionEntrega" value="<?= htmlspecialchars($d['DireccionEntrega'] ?? '') ?>" required>
                        </td>
                        <td>
                                <input type="text" name="ContactoEntrega" value="<?= htmlspecialchars($d['ContactoEntrega'] ?? '') ?>" required>
                        </td>
                        <td>
                                <select name="EstadoEntrega">
                                    <?php
                                        $estados = ['Pendiente', 'En camino', 'Entregado'];
                                        foreach ($estados as $estado):
                                    ?>
                                        <option value="<?= $estado ?>" <?= (($d['EstadoEntrega'] ?? 'Pendiente') === $estado) ? 'selected' : '' ?>>
                                            <?= $estado ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                        </td>
                        <td style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <button id="btn" type="submit">Actualizar</button>
                            </form>
                            <form method="POST" action="../../Controlador/domicilioController.php?action=entregado">
                                <input type="hidden" name="ID_Domicilio" value="<?= htmlspecialchars($d['ID_Domicilio']) ?>">
                                <button id="btn" type="submit">Marcar entregado</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php else: ?>
            <div class="GesAdmin">
                <h1>Mis domicilios</h1>
                <h2 class="Crear">Solicita o revisa la entrega a domicilio de tus pedidos</h2>

                <?php if (empty($misPedidosAgrupados)): ?>
                    <p class="text">No tienes pedidos para gestionar domicilios.</p>
                <?php else: ?>
                    <table>
                        <tr>
                            <th>N° Pedido</th>
                            <th>Fecha</th>
                            <th>Platos</th>
                            <th>Estado Pedido</th>
                            <th>Direccion</th>
                            <th>Contacto</th>
                            <th>Estado Entrega</th>
                            <th>Acciones</th>
                        </tr>
                        <?php foreach ($misPedidosAgrupados as $numPedido => $p):
                            $dom = $domiciliosPorPedido[$p['ID_P']] ?? null;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($numPedido) ?></td>
                            <td><?= htmlspecialchars($p['FechaPedido']) ?></td>
                            <td><?= htmlspecialchars($p['NombrePlato'] ?? '') ?></td>
                            <td><?= htmlspecialchars($p['Estado'] ?? 'Pendiente') ?></td>
                            <td><?= htmlspecialchars($dom['DireccionEntrega'] ?? 'No asignada') ?></td>
                            <td><?= htmlspecialchars($dom['ContactoEntrega'] ?? 'No asignado') ?></td>
                            <td><?= htmlspecialchars($dom['EstadoEntrega'] ?? 'Pendiente') ?></td>
                            <td>
                                <form method="POST" action="../../Controlador/domicilioController.php?action=registrar" style="display: grid; gap: 6px;">
                                    <input type="hidden" name="ID_Pedido" value="<?= htmlspecialchars($p['ID_P']) ?>">
                                    <input type="text" name="DireccionEntrega" placeholder="Direccion de entrega" value="<?= htmlspecialchars($dom['DireccionEntrega'] ?? '') ?>" required>
                                    <input type="text" name="ContactoEntrega" placeholder="Contacto (tel/correo)" value="<?= htmlspecialchars($dom['ContactoEntrega'] ?? '') ?>" required>
                                    <button id="btn" type="submit"><?= $dom ? 'Actualizar' : 'Solicitar' ?></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
