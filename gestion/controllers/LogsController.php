<?php
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../Vistas/View.php';

class LogsController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function configurar() {
        // Muestra la vista con el formulario para crear la base de logs
        View::show('configurarLogsView');
    }
    //crea la base de datos, y tambien crea una tabla dentro de ella para guardar la información.
    public function crearBaseLogs() {
        if (empty($_POST['nombreLogs'])) {
            View::show('mensajeView', ['mensaje' => 'Debes indicar un nombre para la base de logs.']);
            return;
        }

        $nombreLogs = preg_replace('/[^a-zA-Z0-9_]/', '_', $_POST['nombreLogs']); // sanitiza
        try {
            $this->db->query("CREATE DATABASE IF NOT EXISTS `$nombreLogs`");
            
            // Guardar la referencia en la sesión
            $_SESSION['log_db'] = $nombreLogs;

            // Crear la tabla de logs dentro de esa base
            $this->db = new Database($nombreLogs);
            $this->db->query("
                CREATE TABLE IF NOT EXISTS logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    usuario VARCHAR(100) DEFAULT 'admin',
                    accion VARCHAR(255),
                    detalle TEXT
                )
            ");

            View::show('mensajeView', [
                'mensaje' => "Base de datos de logs '$nombreLogs' creada correctamente.",
                'volver'  => 'index.php?controller=GestionController&action=inicio'
            ]);
        } catch (PDOException $e) {
            View::show('mensajeView', [
                'mensaje' => 'Error al crear la base de datos de logs: ' . $e->getMessage()
            ]);
        }
    }
}
