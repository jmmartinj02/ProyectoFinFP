<?php
require_once __DIR__ . '/../db/Database.php';

class LogsModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Verifica si existe una base de datos de logs
    public function existeBaseDeDatos($nombre) {
        $stmt = $this->db->query("SHOW DATABASES LIKE '$nombre'");
        return $stmt->fetchColumn() !== false;
    }

    // Crea la base de datos de logs y su tabla inicial
    public function crearBaseDeDatosLogs($nombre) {
        try {
            $this->db->query("CREATE DATABASE `$nombre` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

            $dbLogs = new Database($nombre);
            $sql = "CREATE TABLE IF NOT EXISTS eventos_log (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usuario VARCHAR(100) DEFAULT 'admin',
                accion VARCHAR(255),
                tabla_afectada VARCHAR(100),
                detalle TEXT,
                fecha DATETIME DEFAULT CURRENT_TIMESTAMP
            )";
            $dbLogs->query($sql);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Lista las bases de datos existentes (para el selector)
    public function listarBases() {
        return $this->db->listarBasesDeDatos();
    }
}
