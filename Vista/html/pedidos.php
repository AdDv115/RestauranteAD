<?php
session_start();
require_once '../../Modelo/ModeloUsuarios.php';
require_once '../../Modelo/ModeloPedidos.php';
require_once '../../Modelo/ModeloPlatos.php';
require_once '../../Modelo/ModeloMesas.php';

if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario_logueado'];
$modelPedidos = new Pedidos();
$modelPlatos = new Plato();
$modelMesas = new Mesas();
$modelUsuarios = new Usuario();

$platos = $modelPlatos->obtenerPlato() ?? [];
$mesasDisponibles = $modelMesas->obtenerMesasDisponibles() ?? [];

$usuarios = $modelUsuarios->obtenerUsuarios() ?? [];

$misPedidos = $modelPedidos->obtenerPedidosPorUsuario($usuario['ID_User']) ?? [];

$pedidosAdmin = [];
$PeEditar = null;

if ($usuario['Rolusu'] === 'Administrador') {
    $pedidosAdmin = $modelPedidos->obtenerPedidos() ?? [];
}

if ($usuario['Rolusu'] === 'Administrador' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
    $idEditar = $_POST['editar_id'];
    foreach ($pedidosAdmin as $p) {
        if ($p['ID_P'] == $idEditar) {
            $PeEditar = $p;
            break;
        }
    }
}

$carrito = $_SESSION['carrito'] ?? [];
$fecha_minima = date('Y-m-d');

$misPedidosAgrupados = [];
foreach ($misPedidos as $p) {
    $num = $p['NumeroPedido'] ?? $p['ID_P'];
    if (!isset($misPedidosAgrupados[$num])) {
        $misPedidosAgrupados[$num] = $p;
    }
}

$pedidosAdminAgrupados = [];
foreach ($pedidosAdmin as $p) {
    $num = $p['NumeroPedido'] ?? $p['ID_P'];
    if (!isset($pedidosAdminAgrupados[$num])) {
        $pedidosAdminAgrupados[$num] = $p;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos</title>
    <link href="../css/pedidos.css" rel="stylesheet">
</head>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="tipo_pedido"]');
    const bloqueLocal = document.getElementById('bloque-local');
    const selectMesa = document.getElementById('select-mesa');

    function actualizarVista() {
        const seleccionado = document.querySelector('input[name="tipo_pedido"]:checked').value;
        if (seleccionado === 'local') {
            bloqueLocal.style.display = 'block';
            selectMesa.required = true;
        } else {
            bloqueLocal.style.display = 'none';
            selectMesa.required = false;
        }
    }

    radios.forEach(r => r.addEventListener('change', actualizarVista));

    actualizarVista();
});
</script>
<body>
<?php if ($usuario): ?>
    <?php if ($usuario['Rolusu'] === 'Administrador'): ?>
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
        <h1>Bienvenido, <?= htmlspecialchars($usuario['Nombre'])?>!</h1>
        <img id="fp" src="../img/uP/<?= htmlspecialchars($usuario['ImagenPerfil'] ?? 'default.png') ?>" alt="Foto de perfil">
        <h2>Email: <?= htmlspecialchars($usuario['Email']) ?></h2>
        
        <div class="BotonEditar">
            <form method="POST" action="../../Controlador/usuarioController.php?action=cerrarSesion">
                <input type="hidden" name="CerrarSesion" value="<?= htmlspecialchars($usuario['ID_User']) ?>">
                <button id="btn1" type="submit">Cerrar Sesion</button>
            </form>
        </div>
    </div>
    
    <?php if ($usuario['Rolusu'] === 'Cliente'): ?>
    <div class="Pedidos">

        <div class="FormularioPedido">
            <h1 class="text">Realizar Nuevo Pedido</h1>

            <?php if (!empty($carrito)): ?>
                <h2 class="Crear">Platos en tu pedido</h2>
                <table>
                    <tr>
                        <th>Plato</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                    </tr>
                    <?php foreach ($carrito as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td><?= htmlspecialchars($item['cantidad']) ?></td>
                        <td><?= htmlspecialchars($item['precio']) ?></td>
                        <td><?= htmlspecialchars($item['precio'] * $item['cantidad']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>

    <form class="Crear2" action="../../Controlador/pedidoController.php?action=guardarPedido" method="POST">
        
    <!-- TIPO DE PEDIDO -->
    <label>¿Cómo deseas tu pedido?</label>
    <div class="tipo-pedido">

        <label>
            <input type="radio" name="tipo_pedido" value="local" checked>
            Consumir en el restaurante
        </label>
        <label>
            <input type="radio" name="tipo_pedido" value="domicilio">
            A domicilio
        </label>
    </div>

    <!-- SOLO PARA LOCAL: MESA -->
    <div id="bloque-local">
        <label>Seleccionar Mesa Disponible</label>
        
        <div>
                <select name="id_mesa" id="select-mesa">
                    <option value="">-- Selecciona una Mesa --</option>
                        <?php foreach($mesasDisponibles as $mesa): ?>
                            <option value="<?= htmlspecialchars($mesa['ID_R']) ?>">
                                Mesa <?= htmlspecialchars($mesa['NumeroMesa']) ?>
                            </option>
                        <?php endforeach; ?>
                </select>
            </div>
        </div>

                <label>Fecha del pedido</label>
                <input type="date" name="fecha_pedido" required min="<?= $fecha_minima ?>">

                <label>Hora del pedido</label>
                <input type="time" name="hora_pedido" required min="13:00" max="22:00">

                <button type="submit" id="btn">Confirmar Pedido</button>
        </form>

            <?php else: ?>
                <p class="text">No tienes platos en tu pedido. Ve al <a href="menu.php">menú</a> y añade algunos.</p>
            <?php endif; ?>
        </div>
        
        <div class="ListaPedidos">
            <div>
                <h2 class="Crear">Mis Pedidos Recientes</h2>
            </div>
            <table>
                <tr>
                    <th>N° Pedido</th>
                    <th>Fecha</th>
                    <th>Plato</th>
                    <th>Mesa</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach($misPedidosAgrupados as $numPedido => $p): ?>
                <tr>
                    <td><?= htmlspecialchars($numPedido) ?></td>
                    <td><?= htmlspecialchars($p['FechaPedido']) ?></td>
                    <td><?= htmlspecialchars($p['NombrePlato']) ?></td>
                    <td><?= htmlspecialchars($p['NumeroMesa'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($p['Estado'] ?? 'Pendiente') ?></td>
                    <td>
                        <a class="botonesnav" href="./domicilios.php">Ir a domicilios</a>
                        <form method="POST" action="../../Controlador/pedidoController.php?action=EliminarP" style="display: inline;">
                            <input type="hidden" name="ID_P" value="<?= htmlspecialchars($p['ID_P']) ?>">
                            <button id="btn" type="submit" class="delete">Cancelar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($usuario['Rolusu'] === 'Administrador'): ?>

    <div id="opciones">
        <a class="botonesnav" href="./perfil.php">Gestionar Usuarios</a>
        <a class="botonesnav" href="./rplato.php">Gestionar Platos</a>
        <a class="botonesnav" href="./mesas.php">Gestionar Mesas</a>
        <a class="botonesnav" href="./reservas.php">Gestionar Reservas</a>
        <a class="botonesnav" href="./domicilios.php">Gestionar Domicilios</a>
    </div>

    <div class="GesAdmin">
        
    <?php if (isset($_SESSION['error_pedido'])): ?>
            <p style="color: red; font-weight: bold;">
                <?= htmlspecialchars($_SESSION['error_pedido']) ?>
            </p>
        <?php unset($_SESSION['error_pedido']); ?>
    <?php endif; ?>


        <h1>Gestión de Pedidos</h1>

        <h2 class="Crear">Pedidos en el Sistema</h2>
        <table>
            <tr>
                <th>N° Pedido</th>
                <th>Fecha</th>
                <th>Plato</th>
                <th>Mesa</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($pedidosAdminAgrupados as $numPedido => $p): ?>
            <tr>
                <td><?= htmlspecialchars($numPedido) ?></td>
                <td><?= htmlspecialchars($p['FechaPedido']) ?></td>
                <td><?= htmlspecialchars($p['NombrePlato']) ?></td>
                <td><?= htmlspecialchars($p['NumeroMesa'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars(trim(($p['Nombre'] ?? '') . ' ' . ($p['Apellido'] ?? ''))) ?></td>
                <td><?= htmlspecialchars($p['Estado'] ?? 'Pendiente') ?></td>
                <td>
                    <form method="POST" action="pedidos.php">
                        <input type="hidden" name="editar_id" value="<?= htmlspecialchars($p['ID_P']) ?>">
                        <button id="btn" type="submit">Editar</button>
                    </form>
                    <form method="POST" action="../../Controlador/pedidoController.php?action=EliminarP">
                        <input type="hidden" name="ID_P" value="<?= htmlspecialchars($p['ID_P']) ?>">
                        <button id="btn" type="submit" class="delete">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($PeEditar): ?>
        <?php
            $platosPedido = [];
            foreach ($pedidosAdmin as $p) {
                if ($p['NumeroPedido'] == $PeEditar['NumeroPedido']) {
                    $platosPedido[] = $p;
                }
            }
        ?>
        <div class="form-edicion">
            <h2 class="Crear">Editando Pedido: #<?= htmlspecialchars($PeEditar['ID_P']) ?> (Nro: <?= htmlspecialchars($PeEditar['NumeroPedido']) ?>)</h2>
            
            <form class="Editar" method="POST" action="../../Controlador/pedidoController.php?action=ActualizarP">
                <input type="hidden" name="ID_P" value="<?= htmlspecialchars($PeEditar['ID_P']) ?>">
                <input type="hidden" name="ID_Plato" value="<?= htmlspecialchars($PeEditar['ID_Plato']) ?>">
                <input type="hidden" name="NumeroPedido" value="<?= htmlspecialchars($PeEditar['NumeroPedido']) ?>">

                <label>Cliente</label>
                <select name="ID_User" required>
                    <option value="">-- Selecciona un Cliente --</option>
                    <?php foreach ($usuarios as $user): ?>
                    <option value="<?= htmlspecialchars($user['ID_User']) ?>"<?= $user['ID_User'] == $PeEditar['ID_User'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['Nombre'] . ' ' . $user['Apellido']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

            
                <label>Mesa</label>
                <select name="ID_Mesa" required>
                    <option value="">-- Selecciona una Mesa --</option>
                    <?php foreach ($mesasDisponibles as $mesa): ?>
                    <option value="<?= htmlspecialchars($mesa['ID_R']) ?>" <?= $mesa['ID_R'] == $PeEditar['ID_Mesa'] ? 'selected' : '' ?>
                    >Mesa <?= htmlspecialchars($mesa['NumeroMesa']) ?></option>
                    <?php endforeach; ?>
                </select>
            
                <label>Fecha Pedido</label>
                <input type="datetime-local" name="FechaPedido" 
                    value="<?= htmlspecialchars(date('Y-m-d\TH:i:s', strtotime($PeEditar['FechaPedido']))) ?>" required>

                <label>Cantidad</label>
                <input type="number" name="CantidadPlatos" value="<?= htmlspecialchars($PeEditar['CantidadPlatos']) ?>" required min="1">

                <label>Estado</label>
                <select name="Estado">
                    <option value="Pendiente"      <?= $PeEditar['Estado'] == "Pendiente" ? "selected" : "" ?>>Pendiente</option>
                    <option value="En Preparacion" <?= $PeEditar['Estado'] == "En Preparacion" ? "selected" : "" ?>>En Preparación</option>
                    <option value="Servido"        <?= $PeEditar['Estado'] == "Servido" ? "selected" : "" ?>>Servido</option>
                    <option value="Cancelado"      <?= $PeEditar['Estado'] == "Cancelado" ? "selected" : "" ?>>Cancelado</option>
                </select>

                <button id="btn" type="submit">Actualizar</button>
            </form>

            <div>
                <h3 class="Crear">Platos en este pedido</h3>
                <table>
                    <tr>
                        <th>Plato</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                    <?php foreach ($platosPedido as $linea): ?>
                    <tr>
                        <td><?= htmlspecialchars($linea['NombrePlato']) ?></td>
                        <td><?= htmlspecialchars($linea['CantidadPlatos']) ?></td>
                        <td>
                            <form method="POST" action="../../Controlador/pedidoController.php?action=EliminarP">
                                <input type="hidden" name="ID_P" value="<?= htmlspecialchars($linea['ID_P']) ?>">
                                <button id="btn" type="submit" class="delete">Eliminar plato</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
