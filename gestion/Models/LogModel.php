<?php
require_once __DIR__ . '/../db/Database.php';

class LogModel {

    private $pdo = null;
    private $logDb = null;

    public function __construct() {

        // 1. Tomar credenciales activas desde la sesión
        if (!isset($_SESSION['conexion'])) {
            return; // No hay conexión activa aún (evita errores)
        }

        $host = $_SESSION['conexion']['host'];
        $user = $_SESSION['conexion']['user'];
        $pass = $_SESSION['conexion']['pass'];

        // 2. Conexión temporal para buscar BD *_logs
        try {
            $pdoTemp = new PDO("mysql:host={$host}", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $bases = $pdoTemp->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("No se pudo obtener lista de bases: " . $e->getMessage());
            return;
        }

        // 3. Buscar BD que termine en "_logs"
        foreach ($bases as $b) {
            if (str_ends_with($b, "_logs")) {
                $this->logDb = $b;
                break;
            }
        }

        if (!$this->logDb) {
            // No existe BD de logs → no es un error crítico
            return;
        }

        // 4. Conectar a la base de datos de logs real
        try {
            $db = new Database($this->logDb);
            $this->pdo = $db->getConnection();
        } catch (Exception $e) {
            error_log("No se pudo conectar a la base de logs: " . $e->getMessage());
            $this->pdo = null;
        }
    }

    /**
     * Registrar una acción en logs
     */
    public function registrar($usuario, $accion, $detalle = null) {
        if (!$this->pdo) {
            return false; // no hay sistema de logs disponible
        }

        try {
            $sql = "INSERT INTO logs (usuario, accion, detalle) VALUES (:usuario, :accion, :detalle)";
            $stmt = $this->pdo->prepare($sql);

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

    /**
     * Obtener los últimos N logs
     */
    public function getLastLogs($limit = 10) {
        if (!$this->pdo) return [];

        try {
            $sql = "SELECT * FROM logs ORDER BY fecha DESC LIMIT :lim";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error leyendo logs: " . $e->getMessage());
            return [];
        }
    }

}
