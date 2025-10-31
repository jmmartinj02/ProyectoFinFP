<?php
session_start();

require_once __DIR__ . '/Vistas/View.php';

// Si no hay conexión activa, forzar login
if (empty($_SESSION['conexion']) && (!isset($_GET['controller']) || $_GET['controller'] != 'LoginController')) {
    require_once __DIR__ . '/controllers/LoginController.php';
    $controller = new LoginController();
    $controller->mostrarFormulario();
    exit();
}

// Enrutador de la aplicacion utilizando MVC
if (isset($_GET['controller']) && isset($_GET['action'])) {
    $controllerName = $_GET['controller'];
    $action = $_GET['action'];

    $controllerFile = __DIR__ . "/controllers/$controllerName.php";
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $action)) {
                $controller->$action();
                exit();
            }
        }
    }

    View::show('errorView', ['mensaje' => 'Controlador o acción no encontrada']);
    exit();
}

// Página por defecto
require_once __DIR__ . '/controllers/GestionController.php';
$controller = new GestionController();
$controller->dashboard();

