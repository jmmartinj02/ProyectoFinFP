<?php
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../Models/LogModel.php';
require_once __DIR__ . '/../Vistas/View.php';

class LogsController {
    private $db;
// conexión al servidor sin DB, para poder crear modificar y demás
//sin especificar una BD en sí.
    public function __construct() {
        $this->db = new Database(); 
    }
    //funcion autodetección si no existe, me reenvia al formulario para crear la base de datos
    public function configurar() {
        // si ya hay una base configurada en sesión me devuelve al dashboard
        if (!empty($_SESSION['log_db'])) {
            header("Location: index.php?controller=gestionController&action=inicio");
            exit;
        }

        try {
            // Buscar bases de datos que contengan "logs" en el nombre
            $pdo = $this->db->getConnection();
            $stmt = $pdo->query("SHOW DATABASES LIKE '%logs%'");
            //las pilla todas
            $basesLogs = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // si encuentra alguna, si ha encontrado una o varias, usa la primera en el array
            if (!empty($basesLogs)) {
                $_SESSION['log_db'] = $basesLogs[0];

                // crea deirectamente la tabla dentro de la base de datos de logs si no existe
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS `{$basesLogs[0]}`.`logs` (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        usuario VARCHAR(100),
                        accion VARCHAR(100),
                        detalle TEXT,
                        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                ");

                header("Location: index.php?controller=gestionController&action=inicio");
                exit;
            }

            // si no ha entrado en el if anterior, me envía al formulario de creación
            View::show('configurarLogsView');
            //mensaje error
        } catch (PDOException $e) {
            View::show('errorView', ['mensaje' => 'Error al buscar bases de datos de logs: ' . $e->getMessage()]);
        }
    }
    //si relleno el formilario y lo envío, la crea, la guardo en sesión
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // no me pillaba el nombre, esto se supone que lo ha solucionado
            $nombre = trim($_POST['nombre'] ?? '');
            if (empty($nombre)) {
                View::show('configurarLogsView', ['error' => 'Debes indicar un nombre para la base de logs.']);
                return;
            }

            try {
                $pdo = $this->db->getConnection();

                // crea la base de datos
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$nombre` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

                // crea tabla de logs dentro de esa base
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS `$nombre`.`logs` (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        usuario VARCHAR(100),
                        accion VARCHAR(100),
                        detalle TEXT,
                        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                ");

                // Guardar en sesión
                $_SESSION['log_db'] = $nombre;

                header("Location: index.php?controller=gestionController&action=inicio");
                exit;

            } catch (PDOException $e) {
                View::show('errorView', ['mensaje' => 'Error al crear base de logs: ' . $e->getMessage()]);
            }
        }
    }
}
