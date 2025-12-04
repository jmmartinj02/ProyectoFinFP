<?php
require_once __DIR__ . '/../db/Database.php';

class VistasModel {

    private $dbName = "vistas";
    private $pdo;

    public function __construct() {
        $this->ensureDatabase();
        $this->ensureTable();
        $this->pdo = (new Database($this->dbName))->getConnection();
    }

    private function ensureDatabase() {
        $pdo = (new Database())->getConnection();
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `vistas`");
    }

    private function ensureTable() {
        $pdo = (new Database("vistas"))->getConnection();

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS vista (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255) NOT NULL,
                descripcion TEXT,
                sql_text LONGTEXT NOT NULL,
                db VARCHAR(255) NOT NULL,
                fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM vista ORDER BY fecha DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar($nombre, $descripcion, $sql, $db) {
        $stmt = $this->pdo->prepare("
            INSERT INTO vista (nombre, descripcion, sql_text, db)
            VALUES (:n, :d, :s, :db)
        ");

        return $stmt->execute([
            ":n" => $nombre,
            ":d" => $descripcion,
            ":s" => $sql,
            ":db" => $db
        ]);
    }

    public function getBases() {
        $pdo = (new Database())->getConnection();
        $res = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);

        return array_filter($res, fn($b) =>
            !in_array($b, ['information_schema','mysql','performance_schema','sys'])
        );
    }
    public function getVista($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM vista WHERE id = :id");
    $stmt->execute([":id" => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function actualizarVista($id, $nombre, $descripcion, $sql, $db) {
    $stmt = $this->pdo->prepare("
        UPDATE vista
        SET nombre = :n, descripcion = :d, sql_text = :s, db = :db
        WHERE id = :id
    ");

    return $stmt->execute([
        ":n" => $nombre,
        ":d" => $descripcion,
        ":s" => $sql,
        ":db" => $db,
        ":id" => $id
    ]);
}

    public function eliminarVista($id) {
        $stmt = $this->pdo->prepare("DELETE FROM vista WHERE id = :id");
        return $stmt->execute([":id" => $id]);
    }

    public function ejecutarVista($vista) {
        $pdo = (new Database($vista['db']))->getConnection();
        $sql = $vista["sql_text"];

        try {
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return "ERROR: " . $e->getMessage();
        }
    }

}
