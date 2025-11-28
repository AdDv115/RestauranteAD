<?php
session_start();

require_once "../Configuracion/conexion.php";
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'Modelo/ModeloPedidos.php';
require_once ROOT_PATH . 'Modelo/ModeloPlatos.php';

class PedidoController {
    private $modelPedidos;
    private $modelPlatos;

    public function __construct() {
        $this->modelPedidos = new Pedidos();
        $this->modelPlatos  = new Plato();
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    private function obtenerFechaPedidoDesdePost() {
        $fecha = $_POST['fecha_pedido'] ?? '';
        $hora  = $_POST['hora_pedido'] ?? '';
        if ($fecha !== '' && $hora !== '') {
            return $fecha . ' ' . $hora . ':00';
        }
        return date('Y-m-d H:i:s');
    }

    public function registrarDesdePerfil() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['usuario_logueado'])) {
                header("Location: ../Vista/html/login.php");
                exit();
            }

            $usuario = $_SESSION['usuario_logueado'];
            $ID_User = $usuario['ID_User'];

            $ID_Mesa = intval($_POST['ID_Mesa'] ?? 0);
            $Cantidad = intval($_POST['Cantidad'] ?? 1);
            $platosSeleccionados = $_POST['ID_Plato'] ?? [];

            $FechaPedido = $this->obtenerFechaPedidoDesdePost();
            $NumeroPedido = time();
            $Estado       = 'Pendiente';

            if ($ID_Mesa > 0 && $Cantidad > 0 && is_array($platosSeleccionados) && count($platosSeleccionados) > 0) {
                if ($this->modelPedidos->mesaOcupadaEnFecha($ID_Mesa, $FechaPedido)) {
                    $_SESSION['error_pedido'] = 'La mesa ya tiene un pedido para esa fecha.';
                    header("Location: ../Vista/html/pedidos.php");
                    exit();
                }

                foreach ($platosSeleccionados as $idPlato) {
                    $idPlato = intval($idPlato);
                    if ($idPlato <= 0) {
                        continue;
                    }

                    $this->modelPedidos->RegistrarPedidos(
                        $NumeroPedido,
                        $FechaPedido,
                        $Cantidad,
                        $idPlato,
                        $ID_User,
                        $ID_Mesa,
                        $Estado
                    );
                }
            }

            header("Location: ../Vista/html/pedidos.php");
            exit();
        }
    }

    public function eliminarPedido() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['ID_P'] ?? 0);
            if ($id > 0) {
                $this->modelPedidos->eliminarPedidos($id);
            }

            header("Location: ../Vista/html/pedidos.php");
            exit();
        }
    }

    public function actualizarPedido() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['ID_P'] ?? 0);

        $NumeroPedido   = $_POST['NumeroPedido'] ?? '';
        $FechaPedido    = $_POST['FechaPedido'] ?? '';
        $CantidadPlatos = intval($_POST['CantidadPlatos'] ?? 0);
        $ID_Plato       = intval($_POST['ID_Plato'] ?? 0);
        $ID_User        = intval($_POST['ID_User'] ?? 0);
        $ID_Mesa        = intval($_POST['ID_Mesa'] ?? 0);
        $Estado         = $_POST['Estado'] ?? 'Pendiente';

        if ($FechaPedido !== '') {
            $FechaPedido = str_replace('T', ' ', $FechaPedido);
        }

        if (
            $id > 0 &&
            $NumeroPedido !== '' &&
            $FechaPedido !== '' &&
            $CantidadPlatos > 0 &&
            $ID_Plato > 0 &&
            $ID_User > 0 &&
            $ID_Mesa > 0
        ) {
            if ($this->modelPedidos->mesaOcupadaEnFecha($ID_Mesa, $FechaPedido)) {
                $_SESSION['error_pedido'] = 'La mesa ya tiene un pedido para esa fecha.';
                header("Location: ../Vista/html/pedidos.php");
                exit();
            }

            $this->modelPedidos->actualizarPedidos(
                $id,
                $NumeroPedido,
                $FechaPedido,
                $CantidadPlatos,
                $ID_Plato,
                $ID_User,
                $ID_Mesa,
                $Estado
            );
        }

        header("Location: ../Vista/html/pedidos.php");
        exit();
    }
}

    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_plato = intval($_POST['id_plato'] ?? 0);
            $cantidad = intval($_POST['cantidad'] ?? 1);

            if ($id_plato > 0 && $cantidad > 0) {
                $plato = $this->modelPlatos->obtenerPlatoPorId($id_plato);

                if ($plato) {
                    if (!isset($_SESSION['carrito'][$id_plato])) {
                        $_SESSION['carrito'][$id_plato] = [
                            'id_plato' => $id_plato,
                            'nombre'   => $plato['NombrePlato'],
                            'precio'   => $plato['Precio'],
                            'cantidad' => $cantidad
                        ];
                    } else {
                        $_SESSION['carrito'][$id_plato]['cantidad'] += $cantidad;
                    }
                }
            }
        }

        header("Location: ../Vista/html/menu.php");
        exit();
    }

    public function guardarPedidoDesdeCarrito() {
        if (!isset($_SESSION['usuario_logueado'])) {
            header("Location: ../Vista/html/login.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_SESSION['usuario_logueado'];
            $ID_User = $usuario['ID_User'];
            $ID_Mesa = intval($_POST['id_mesa'] ?? 0);

            $FechaPedido  = $this->obtenerFechaPedidoDesdePost();
            $NumeroPedido = time();
            $Estado       = 'Pendiente';

            if (!empty($_SESSION['carrito']) && $ID_Mesa > 0) {
                if ($this->modelPedidos->mesaOcupadaEnFecha($ID_Mesa, $FechaPedido)) {
                    $_SESSION['error_pedido'] = 'La mesa ya tiene un pedido para esa fecha.';
                    header("Location: ../Vista/html/pedidos.php");
                    exit();
                }

                foreach ($_SESSION['carrito'] as $item) {
                    $this->modelPedidos->RegistrarPedidos(
                        $NumeroPedido,
                        $FechaPedido,
                        $item['cantidad'],
                        $item['id_plato'],
                        $ID_User,
                        $ID_Mesa,
                        $Estado
                    );
                }

                $_SESSION['carrito'] = [];
            }

            header("Location: ../Vista/html/pedidos.php");
            exit();
        }
    }

    public function removeFromCart() {
        $id_plato = intval($_GET['id_plato'] ?? 0);
        if ($id_plato > 0 && isset($_SESSION['carrito'][$id_plato])) {
            unset($_SESSION['carrito'][$id_plato]);
        }

        header("Location: ../Vista/html/menu.php");
        exit();
    }
}

$controller = new PedidoController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'RegistrarP':
            $controller->registrarDesdePerfil();
            break;
        case 'EliminarP':
            $controller->eliminarPedido();
            break;
        case 'ActualizarP':
            $controller->actualizarPedido();
            break;
        case 'addToCart':
            $controller->addToCart();
            break;
        case 'guardarPedido':
            $controller->guardarPedidoDesdeCarrito();
            break;
        case 'removeFromCart':
            $controller->removeFromCart();
            break;
        default:
            header("Location: ../Vista/html/menu.php");
            exit();
    }
} else {
    header("Location: ../Vista/html/menu.php");
    exit();
}
