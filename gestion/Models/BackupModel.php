<?php

class BackupModel {

    private $baseDir = __DIR__ . '/../../backups';
    private $configFile = __DIR__ . '/../../backups/last_backup.json';
    private $log;

    public function __construct() {
        $this->ensureFolder("$this->baseDir/full");
        $this->ensureFolder("$this->baseDir/incremental");
        $this->ensureFolder("$this->baseDir/diferencial");
    }

    private function ensureFolder($folder) {
        if (!is_dir($folder)) mkdir($folder, 0777, true);
    }

    private function saveLastBackupDate() {
        file_put_contents($this->configFile, json_encode([
            "last" => date('Y-m-d H:i:s')
        ]));
    }

    public function getLastBackupDate() {
        if (!file_exists($this->configFile)) return null;
        $data = json_decode(file_get_contents($this->configFile), true);
        return $data['last'] ?? null;
    }

    public function crearBackup($dbName, $tipo) {
        $date = date('Ymd_His');
        $filename = "{$dbName}_{$tipo}_{$date}.sql";

        $folder = "{$this->baseDir}/{$tipo}";
        $filepath = "{$folder}/{$filename}";

        // obtener PDO
        $pdo = (new Database($dbName))->getConnection();

        // FULL → estructura + datos
        if ($tipo === 'full') {
            $sql = $this->dumpFull($pdo);
        }

        // DIFERENCIAL → desde último full
        if ($tipo === 'diferencial') {
            $sql = $this->dumpChanges($pdo, 'full');
        }

        // INCREMENTAL → desde último backup de cualquier tipo
        if ($tipo === 'incremental') {
            $sql = $this->dumpChanges($pdo, 'any');
        }

        file_put_contents($filepath, $sql);

        // guardar la fecha del último backup
        $this->saveLastBackupDate();
        if ($this->log) {
            $this->log->registrar('admin', 'BACKUP', "Copia creada ($tipo) → $filename");
        }
        return $filename;
    }

    private function dumpFull($pdo) {
        $output = "-- FULL BACKUP\n\n";

        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $t) {
            $create = $pdo->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_ASSOC);
            $createSQL = array_values($create)[1];

            $output .= "DROP TABLE IF EXISTS `$t`;\n";
            $output .= "$createSQL;\n\n";

            $rows = $pdo->query("SELECT * FROM `$t`")->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $r) {
                $cols = implode('`,`', array_keys($r));
                $vals = array_map(function($v) use ($pdo) {
                    return $v === null ? 'NULL' : $pdo->quote($v);
                }, array_values($r));
                $vals = implode(',', $vals);

                $output .= "INSERT INTO `$t` (`$cols`) VALUES ($vals);\n";
            }

            $output .= "\n";
        }

        return $output;
    }

    private function dumpChanges($pdo, $mode) {
        $output = "-- BACKUP DIF/INC\n\n";

        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        $lastDate = $this->getLastBackupDate();
        if (!$lastDate) return "-- No hay backup anterior\n";

        foreach ($tables as $t) {
            // Requiere que las tablas tengan columna updated_at
            $sql = "SELECT * FROM `$t` WHERE updated_at >= :last";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':last' => $lastDate]);

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $r) {
                $cols = implode('`,`', array_keys($r));
                $vals = array_map([$pdo, 'quote'], array_values($r));
                $vals = implode(',', $vals);

                $output .= "INSERT INTO `$t` (`$cols`) VALUES ($vals);\n";
            }
        }

        return $output;
    }
    public function listarBackups() {
    $tipos = ['full', 'incremental', 'diferencial'];
    $result = [];

    foreach ($tipos as $t) {
        $folder = "{$this->baseDir}/$t";

        if (!is_dir($folder)) continue;

        $files = scandir($folder);
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;

            $path = "$folder/$f";
            $result[] = [
                'archivo' => $f,
                'tipo' => $t,
                'fecha' => date('Y-m-d H:i:s', filemtime($path)),
                'tamano' => filesize($path)
            ];
        }
    }

    // ordenando los archivos por fecha
    usort($result, function($a, $b) {
        return strtotime($b['fecha']) <=> strtotime($a['fecha']);
    });

    return $result;
}

public function estadisticas() {
    $lista = $this->listarBackups();

    $total = count($lista);
    $sumaTam = array_sum(array_column($lista, 'tamano'));

    $porTipo = [
        'full' => 0,
        'incremental' => 0,
        'diferencial' => 0
    ];

    foreach ($lista as $b) {
        $porTipo[$b['tipo']]++;
    }

    return [
        'total' => $total,
        'tamano' => round($sumaTam / 1024 / 1024, 2),
        'porTipo' => $porTipo,
        'lista' => array_slice($lista, 0, 5) // últimas 5
    ];
}

}
