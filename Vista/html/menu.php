<?php
session_start();

require_once '../../Modelo/ModeloPlatos.php';
require_once '../../Configuracion/conexion.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$modelPlato = new Plato();
$platos_disponibles = $modelPlato->obtenerPlato() ?? [];

$usuario = $_SESSION['usuario_logueado'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="../css/menu.css" rel="stylesheet">
</head>
<body>
    <?php if ($usuario): ?>
        <nav>
            <ul>
                <li><a href="../../index.php"><img src="../img/Logo.png" id="logo" alt="Logo"></a></li>
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
    <?php else: ?>
        <nav>
            <ul>
                <li><a href="../../index.php"><img src="../img/Logo.png" id="logo" alt="Logo"></a></li>
                <div id="navbotones">
                    <li><a class="botonesnav" href="../../index.php">Inicio</a></li>
                    <li><a class="botonesnav" href="./menu.php">Menu</a></li>
                    <li><a class="botonesnav" href="./login.php">Usuario</a></li>
                </div>
            </ul> 
        </nav>
    <?php endif; ?>

    <div class="Contenedor">

        <?php if ($usuario && !empty($_SESSION['carrito'])): ?>
            <div style="text-align: center; margin: 20px 0;">
                <a class="botonesnav" href="./pedidos.php">Finalizar pedido</a>
            </div>
        <?php endif; ?>

        <div class="Galeria">

    <?php if (!empty($platos_disponibles)): ?>
        <?php 
            $hayDisponibles = false;
                foreach ($platos_disponibles as $plato): if ((int)$plato['Disponible'] !== 1) { continue; }
                        $hayDisponibles = true;
                        ?>
                        
                    <div class="item">
                        <img class="fotos" 
                             src="../img/platos/<?= htmlspecialchars($plato['ImagenUrl'] ?? 'default.jpg') ?>" 
                             alt="<?= htmlspecialchars($plato['NombrePlato']) ?>">
                        <h3 class="text"><?= htmlspecialchars($plato['NombrePlato']) ?></h3>
                        <p class="precio">$<?= htmlspecialchars(number_format($plato['Precio'], 0, ',', '.')) ?></p>
                        <p class="descripcion"><?= htmlspecialchars($plato['Descripcion']) ?></p>

                        <?php if ($usuario): ?>
                            <form action="../../Controlador/PedidoController.php?action=addToCart" method="POST">
                                <input type="hidden" name="id_plato" value="<?= htmlspecialchars($plato['ID_Plato']) ?>">
                                <input type="number" name="cantidad" value="1" min="1" max="8" required>
                                <button type="submit" id="btn1">
                                    Añadir al Pedido
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text">Lo sentimos, no hay platos disponibles en este momento.</p>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>
