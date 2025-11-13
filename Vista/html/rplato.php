<?php
session_start();
require_once '../../Modelo/usuario.php';
require_once '../../Modelo/plato.php';

// Validación de sesión
if (!isset($_SESSION['usuario_logueado'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario_logueado'];
$platos = $_SESSION['lista_platos'] ?? [];

$platoEditar = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
    $idEditar = $_POST['editar_id'];
    foreach ($platos as $p) {
        if ($p['ID_Plato'] == $idEditar) {
            $platoEditar = $p;
            break;
        }
    }
}

// Si es admin y no hay platos en sesión, los cargamos
if ($usuario['Rolusu'] === 'Administrador' && empty($_SESSION['lista_platos'])) {
    $modelPlato = new Plato();
    $_SESSION['lista_platos'] = $modelPlato->obtenerPlato();
    $platos = $_SESSION['lista_platos'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Platos</title>
    <link href="../css/perfil.css" rel="stylesheet">
</head>
<body>
<nav>
    <ul>
        <li><a href="../index.php"><img src="../img/Logo.png" id="logo"></a></li>
        <div id="navbotones">
            <li><a class="botonesnav" href="../../index.php">Inicio</a></li>
            <li><a class="botonesnav" href="./menu.php">Menu</a></li>
            <li><a class="botonesnav" href="./contacto.php">Contacto</a></li>
            <li><a class="botonesnav" href="./sn.php">Sobre Nosotros</a></li>
            <li><a class="botonesnav" href="./login.php">Usuario</a></li>
        </div>
    </ul>
</nav>

<div class="Contenedor">

    <?php if ($usuario['Rolusu'] === 'Administrador'): ?>
    <div class="GesAdmin">
        <a class="botonesnav" href="./perfil.php">Gestionar Usuarios</a>
        <a class="botonesnav" href="./reserva.php">Gestionar Reservas</a>
        <h1>Gestión de Platos</h1>

        <h2 class="Crear">Agregar nuevo plato</h2>
        <form class="Crear" method="POST" action="../../Controlador/platoController.php?action=agregarp" enctype="multipart/form-data">
            <input type="text" name="NombreP" placeholder="Nombre del Plato" required>
            <input type="text" name="Descripcion" placeholder="Descripción" required>
            <input type="number" name="PrecioP" placeholder="Precio" required>
            <input type="file" name="Imagen" required>
            <select name="Disponible">
                <option value="1">Disponible</option>
                <option value="0">No disponible</option>
            </select>
            <button id="btn" type="submit">Agregar Plato</button>
        </form>

        <h2 class="Crear">Platos Registrados</h2>
        <table>
            <tr>
                <th>ID</th><th>Nombre</th><th>Descripción</th><th>Imagen</th><th>Precio</th><th>Disponible</th><th>Acciones</th>
            </tr>
            <?php foreach ($platos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['ID_Plato']) ?></td>
                <td><?= htmlspecialchars($p['NombrePlato']) ?></td>
                <td><?= htmlspecialchars($p['Descripcion']) ?></td>
                <td><img src="<?= htmlspecialchars($p['ImagenUrl']) ?>""</td>
                <td>$<?= htmlspecialchars($p['Precio']) ?></td>
                <td><?= $p['Disponible'] == 1 ? "Disponible" : "No disponible" ?></td>
                <td>
                    <form method="POST" action="rplato.php">
                        <input type="hidden" name="editar_id" value="<?= htmlspecialchars($p['ID_Plato']) ?>">
                        <button id="btn" type="submit">Editar</button>
                    </form>
                    <form method="POST" action="../../Controlador/platoController.php?action=eliminarp">
                        <input type="hidden" name="ID_Plato" value="<?= htmlspecialchars($p['ID_Plato']) ?>">
                        <button id="btn" type="submit" class="delete">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($platoEditar): ?>
        <div class="form-edicion">
            <h2 class="Crear">Editando plato: <?= htmlspecialchars($platoEditar['NombrePlato']) ?></h2>
            <form class="Editar" method="POST" action="../../Controlador/platoController.php?action=actualizarp" enctype="multipart/form-data">
                <input type="hidden" name="ID_Plato" value="<?= htmlspecialchars($platoEditar['ID_Plato']) ?>">
                <input type="text" name="NombreP" value="<?= htmlspecialchars($platoEditar['NombrePlato']) ?>" required>
                <input type="text" name="Descripcion" value="<?= htmlspecialchars($platoEditar['Descripcion']) ?>" required>
                <input type="number" name="PrecioP" value="<?= htmlspecialchars($platoEditar['Precio']) ?>" required>
                <input type="file" name="Imagen">
                <select name="Disponible">
                    <option value="1" <?= $platoEditar['Disponible'] == 1 ? "selected" : "" ?>>Disponible</option>
                    <option value="0" <?= $platoEditar['Disponible'] == 0 ? "selected" : "" ?>>No disponible</option>
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
