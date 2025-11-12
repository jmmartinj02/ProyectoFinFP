<?php
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../Models/LogModel.php';
require_once __DIR__ . '/../Vistas/View.php';

class LogsController {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Conexión al servidor sin DB
    }
    //funcion autodetección si no existe, me reenvia al formulario
    public function configurar() {
        // Si ya hay una base configurada en sesión → ir al inicio
        if (!empty($_SESSION['log_db'])) {
            header("Location: index.php?controller=GestionController&action=inicio");
            exit;
        }

        try {
            // Buscar bases de datos que contengan "logs" en el nombre
            $pdo = $this->db->getConnection();
            $stmt = $pdo->query("SHOW DATABASES LIKE '%logs%'");
            $basesLogs = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Si encuentra alguna → usar la primera
            if (!empty($basesLogs)) {
                $_SESSION['log_db'] = $basesLogs[0];

                // Asegurar que la tabla `logs` existe
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS `{$basesLogs[0]}`.`logs` (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        usuario VARCHAR(100),
                        accion VARCHAR(100),
                        detalle TEXT,
                        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                ");

                header("Location: index.php?controller=GestionController&action=inicio");
                exit;
            }

            // Si no hay ninguna base de logs → mostrar formulario
            View::show('configurarLogsView');

        } catch (PDOException $e) {
            View::show('errorView', ['mensaje' => 'Error al buscar bases de datos de logs: ' . $e->getMessage()]);
        }
    }
    //si relleno el formilario y lo envío, la crea.
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ✅ corregido: debe recoger `nombreLogs` del formulario
            $nombre = trim($_POST['nombre'] ?? '');
            if (empty($nombre)) {
                View::show('configurarLogsView', ['error' => 'Debes indicar un nombre para la base de logs.']);
                return;
            }

            try {
                $pdo = $this->db->getConnection();

                // Crear la base de datos
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$nombre` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

                // Crear tabla de logs dentro de esa base
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

                header("Location: index.php?controller=GestionController&action=inicio");
                exit;

            } catch (PDOException $e) {
                View::show('errorView', ['mensaje' => 'Error al crear base de logs: ' . $e->getMessage()]);
            }
        }
    }
}
