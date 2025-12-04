<?php

require_once __DIR__ . '/../db/Database.php';

class BackupModel {

    private $baseDir;
    private $configFile;

    public function __construct() {

        $this->baseDir = __DIR__ . '/../../backups';
        $this->configFile = $this->baseDir . '/last_backup.json';

        $this->ensureDir($this->baseDir . '/full');
        $this->ensureDir($this->baseDir . '/incremental');
        $this->ensureDir($this->baseDir . '/diferencial');
    }

    private function ensureDir($dir) {
        if (!is_dir($dir)) mkdir($dir, 0777, true);
    }

    public function getBases() {
        $db = new Database();
        $pdo = $db->getConnection();
        $res = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);

        return array_filter($res, fn($b) =>
            !in_array($b, ['information_schema', 'mysql', 'performance_schema', 'sys'])
        );
    }

    /* ============================================================
       RUTA REAL DEL ARCHIVO
    ============================================================ */
    public function getBackupPath($filename) {

        $tipos = ['full', 'incremental', 'diferencial'];

        foreach ($tipos as $tipo) {
            $ruta = "{$this->baseDir}/{$tipo}/{$filename}";
            if (file_exists($ruta)) {
                return $ruta;
            }
        }

        return null;
    }

    public function listarBackupsAgrupados() {

        return [
            "full" => glob($this->baseDir . "/full/*.sql") ?: [],
            "incremental" => glob($this->baseDir . "/incremental/*.sql") ?: [],
            "diferencial" => glob($this->baseDir . "/diferencial/*.sql") ?: []
        ];
    }

    public function listarBackupsFlat() {
        return array_merge(
            glob($this->baseDir . "/full/*.sql") ?: [],
            glob($this->baseDir . "/incremental/*.sql") ?: [],
            glob($this->baseDir . "/diferencial/*.sql") ?: []
        );
    }

    public function eliminarBackup($file) {

        $fullPath = $this->getBackupPath($file);

        return file_exists($fullPath) ? unlink($fullPath) : false;
    }

    public function getLastBackupDate() {

        if (!file_exists($this->configFile)) return null;

        $data = json_decode(file_get_contents($this->configFile), true);

        return $data['last'] ?? null;
    }

    private function saveLastBackupDate() {
        file_put_contents($this->configFile, json_encode([
            "last" => date("Y-m-d H:i:s")
        ]));
    }

    /* ============================================================
       CREAR BACKUPS
    ============================================================ */
    public function crearBackup($dbName, $tipo) {

        $date = date('Ymd_His');
        $filename = "{$dbName}_{$tipo}_{$date}.sql";
        $folder = "{$this->baseDir}/{$tipo}";
        $path = "{$folder}/{$filename}";

        $pdo = (new Database($dbName))->getConnection();

        if ($tipo === "full") {
            $sql = $this->dumpFull($pdo);
        }

        if ($tipo === "incremental") {
            $sql = "-- incremental no-operativa\n";
        }

        if ($tipo === "diferencial") {
            $sql = "-- diferencial no operativa\n";
        }

        file_put_contents($path, $sql);

        $this->saveLastBackupDate();

        return $filename;
    }

    private function dumpFull($pdo) {

        $out = "-- FULL BACKUP\n";

        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {

            $create = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
            $createSql = array_values($create)[1];

            $out .= "DROP TABLE IF EXISTS `$table`;\n";
            $out .= $createSql . ";\n\n";

            $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {

                foreach ($row as &$v) {
                    $v = $v === null ? "NULL" : $pdo->quote($v);
                }

                $cols = implode('`,`', array_keys($row));
                $vals = implode(',', array_values($row));

                $out .= "INSERT INTO `$table` (`$cols`) VALUES ($vals);\n";
            }

            $out .= "\n";
        }

        return $out;
    }

    /* ============================================================
       RESTAURAR BACKUP
    ============================================================ */
    public function restaurarBackup($path, $dbName) {

        $pdo = (new Database($dbName))->getConnection();
        $sql = file_get_contents($path);

        try {
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
            $pdo->exec($sql);
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
        }

        catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    /* ============================================================
       ESTADÍSTICAS
    ============================================================ */
    public function estadisticas() {

        $tipos = ['full', 'incremental', 'diferencial'];

        $stats = [
            "total" => 0,
            "porTipo" => [
                "full" => 0,
                "incremental" => 0,
                "diferencial" => 0,
            ],
            "tamano" => 0,
            "lista" => []
        ];

        $listaGlobal = [];

        foreach ($tipos as $tipo) {

            $folder = "{$this->baseDir}/{$tipo}";
            $files = glob("$folder/*.sql") ?: []; // ← ← ARREGLADO

            $stats["porTipo"][$tipo] = count($files);
            $stats["total"] += count($files);

            foreach ($files as $f) {

                $nombre = basename($f);
                $tamanoMB = round(filesize($f) / 1024 / 1024, 2);

                $partes = explode("_", $nombre);
                $tipoDetectado = $partes[count($partes) - 3] ?? $tipo;

                $listaGlobal[] = [
                    "archivo" => $nombre,
                    "tipo"    => $tipoDetectado,
                    "fecha"   => filemtime($f),
                    "tamano"  => $tamanoMB
                ];

                $stats["tamano"] += filesize($f);
            }
        }

        $stats["tamano"] = round($stats["tamano"] / 1024 / 1024, 2);

        usort($listaGlobal, fn($a, $b) => $b["fecha"] <=> $a["fecha"]);

        $stats["lista"] = array_slice($listaGlobal, 0, 5);

        return $stats;
    }

}
