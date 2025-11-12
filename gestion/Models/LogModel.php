<?php
require_once __DIR__ . '/../db/Database.php';

class LogModel {
    private $db;

    public function __construct() {
        // Verifica si la sesión ya tiene configurada la base de logs
        $nombreBD = $_SESSION['log_db'] ?? null;

        // Si no hay base de logs configurada, no conecta (evita errores en instalación)
        if (!$nombreBD) {
            $this->db = null;
            return;
        }

        // Conecta directamente a la base de datos de logs
        $this->db = new Database($nombreBD);
    }
    //Registra una acción en la tabla de logs

    public function registrar($usuario, $accion, $detalle = null) {
        if (!$this->db) return false;

        try {
            $sql = "INSERT INTO logs (usuario, accion, detalle) VALUES (:usuario, :accion, :detalle)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':usuario' => $usuario ?? 'admin',
                ':accion'  => $accion,
                ':detalle' => $detalle
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al registrar log: " . $e->getMessage());
            return false;
        }
    }

    
    //Devuelve los últimos N registros (por si quiero enseñarlo en el dashboard)
    
    public function obtenerUltimosLogs($limite = 20) {
        if (!$this->db) return [];
        try {
            $stmt = $this->db->prepare("SELECT * FROM logs ORDER BY fecha DESC LIMIT :limite");
            $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
