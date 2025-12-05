<?php
require_once __DIR__ . '/../db/Database.php';

class LogModel {

    private $pdo = null;
    private $logDb = null;
    //Detecta automáticamente si existe una base de datos que termine en "_logs".
    //Solo inicia el sistema de logs si hay credenciales válidas en sesión.
    //Si encuentra una BD de logs, abre una conexión con ella.
    public function __construct() {

                // usa la sesion, si no hay conexión no trabaja con ellos
        if (!isset($_SESSION['conexion'])) {
            return; // No hay conexión activa aún (evita errores)
        }

        $host = $_SESSION['conexion']['host'];
        $user = $_SESSION['conexion']['user'];
        $pass = $_SESSION['conexion']['pass'];

        //conexion temporal para para buscar BD *_logs, hace falta para la automatización
        try {
            $pdoTemp = new PDO("mysql:host={$host}", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $bases = $pdoTemp->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("No se pudo obtener lista de bases: " . $e->getMessage());
            return;
        }

        // busca BD cuyo nombre termine en "_logs"
        foreach ($bases as $b) {
            if (str_ends_with($b, "_logs")) {
                $this->logDb = $b;
                break;
            }
        }

        if (!$this->logDb) {
            // si noe existte simplemente no se logueará a nada.
            return;
        }

        // ahora si, se conecta a la base de datos de logs real usando logDb
        try {
            $db = new Database($this->logDb);
            $this->pdo = $db->getConnection();
        } catch (Exception $e) {
            error_log("No se pudo conectar a la base de logs: " . $e->getMessage());
            $this->pdo = null;
        }
    }

    //registrea accion en el log, se utilza mucho en todas las funciones que realizan
    //cambios o simplemente visualizaciones.
    // hace un statement sobre la base de datos de logs con la informacion de usuari
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

    //Obtiene como maximo los 10 ultimos logs,(esto es para el dashboard)
    //si ocurre un fallo, no peta, simplemente devuelve un array vacío para
    //no mostrar lienas de error en el dashboard, solo se vería en blanco
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
