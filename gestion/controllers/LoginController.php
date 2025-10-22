<?php
require_once __DIR__ . '/../Models/LoginModel.php';
require_once __DIR__ . '/../Vistas/View.php';

class LoginController {
    private $model;

    public function __construct() {
        $this->model = new LoginModel();
    }

    public function mostrarFormulario() {
        View::show('loginView');
    }

    public function autenticar() {
        $host = $_POST['host'] ?? '';
        $user = $_POST['user'] ?? '';
        $pass = $_POST['pass'] ?? '';
        $db   = $_POST['db'] ?? '';

        if ($this->model->probarConexion($host, $user, $pass, $db)) {
            $_SESSION['conexion'] = compact('host', 'user', 'pass', 'db');
            header("Location: index.php");
            exit;
        } else {
            View::show('loginView', ['error' => 'No se pudo conectar. Verifica los datos.']);
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
