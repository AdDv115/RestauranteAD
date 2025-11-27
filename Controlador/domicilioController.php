<?php
session_start();

require_once "../Configuracion/conexion.php";
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
require_once ROOT_PATH . 'Modelo/ModeloDomicilios.php';
require_once ROOT_PATH . 'Modelo/ModeloUsuarios.php';
require_once ROOT_PATH . 'Modelo/ModeloPedidos.php';

class DomicilioController {
    private $model;
    private $modelPedidos;
    private $usuario;

    public function __construct() {
        $this->model = new Domicilios();
        $this->modelPedidos = new Pedidos();
        $this->usuario = $_SESSION['usuario_logueado'] ?? null;
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect();
        }
        if (!$this->isAdmin()) {
            $this->redirectLogin();
        }

        $id        = intval($_POST['ID_Domicilio'] ?? 0);
        $direccion = trim($_POST['DireccionEntrega'] ?? '');
        $contacto  = trim($_POST['ContactoEntrega'] ?? '');
        $estado    = trim($_POST['EstadoEntrega'] ?? 'Pendiente');

        if ($id > 0 && $direccion !== '' && $contacto !== '') {
            $this->model->actualizarDomicilio($id, $direccion, $contacto, $estado);
        }

        $this->redirect();
    }

    public function marcarEntregado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect();
        }
        if (!$this->isAdmin()) {
            $this->redirectLogin();
        }

        $id = intval($_POST['ID_Domicilio'] ?? 0);
        if ($id > 0) {
            $this->model->marcarEntregado($id);
        }

        $this->redirect();
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect();
        }
        if (!$this->usuario) {
            $this->redirectLogin();
        }

        $idPedido  = intval($_POST['ID_Pedido'] ?? 0);
        $direccion = trim($_POST['DireccionEntrega'] ?? '');
        $contacto  = trim($_POST['ContactoEntrega'] ?? '');

        if ($idPedido > 0 && $direccion !== '' && $contacto !== '') {
            $pedido = $this->modelPedidos->obtenerPedidosPorId($idPedido);
            if ($pedido && intval($pedido['ID_User']) === intval($this->usuario['ID_User'])) {
                $existente = $this->model->obtenerPorPedido($idPedido);
                if ($existente) {
                    $this->model->actualizarDomicilio($existente['ID_Domicilio'], $direccion, $contacto, $existente['EstadoEntrega']);
                } else {
                    $this->model->crearDomicilio($idPedido, $direccion, $contacto, 'Pendiente');
                }
            }
        }

        header("Location: ../Vista/html/pedidos.php");
        exit();
    }

    private function redirect() {
        header("Location: ../Vista/html/domicilios.php");
        exit();
    }

    private function redirectLogin() {
        header("Location: ../Vista/html/login.php");
        exit();
    }

    private function isAdmin() {
        return $this->usuario && ($this->usuario['Rolusu'] ?? '') === 'Administrador';
    }
}

$controller = new DomicilioController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'actualizar':
            $controller->actualizar();
            break;
        case 'entregado':
            $controller->marcarEntregado();
            break;
        case 'registrar':
            $controller->registrar();
            break;
    
    }
} 
