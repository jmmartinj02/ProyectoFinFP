<?php

require_once __DIR__ . '/../Models/BackupModel.php';
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../Vistas/View.php';

class BackupController {

    private $model;

    public function __construct() {
        $this->model = new BackupModel();
    }

    /* ===============================
       PÁGINA PRINCIPAL DEL MÓDULO
       =============================== */
    public function index() {
        $last = $this->model->getLastBackupDate();

        View::show("backupIndex", [
            "last" => $last
        ]);
    }

    /* ===============================
       CREAR COPIA (mostrar y procesar)
       =============================== */
    public function generar() {

        // Si es POST, generamos la copia
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $db = $_POST['db'] ?? '';
            $tipo = $_POST['tipo'] ?? 'full';

            if (!$db) {
                View::show("backupCrear", [
                    "error" => "Debes seleccionar una base de datos."
                ]);
                return;
            }

            $file = $this->model->crearBackup($db, $tipo);

            View::show("backupCrear", [
                "mensaje" => "Copia generada: $file"
            ]);

            return;
        }

        // GET → formulario
        View::show("backupCrear");
    }

    /* ===============================
       LISTAR COPIAS REALIZADAS
       =============================== */
    public function listar() {

        $files = [
            "full"        => glob(__DIR__ . '/../../backups/full/*.sql'),
            "incremental" => glob(__DIR__ . '/../../backups/incremental/*.sql'),
            "diferencial" => glob(__DIR__ . '/../../backups/diferencial/*.sql'),
        ];

        View::show("backupListar", [
            "files" => $files
        ]);
    }

    /* ===============================
       RESTAURAR COPIA
       =============================== */
    public function restaurar() {

        // POST → procesar restauración
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $file = $_POST['file'] ?? '';
            $db   = $_POST['db'] ?? '';

            if (!$file || !$db) {
                View::show("backupRestaurar", [
                    "error" => "Debes seleccionar archivo y base de datos."
                ]);
                return;
            }

            $path = $file;

            if (!file_exists($path)) {
                View::show("backupRestaurar", [
                    "error" => "Archivo no encontrado."
                ]);
                return;
            }

            // Ejecutar SQL
            $pdo = (new Database($db))->getConnection();
            $sql = file_get_contents($path);

            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
            $pdo->exec($sql);
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

            View::show("backupRestaurar", [
                "mensaje" => "Copia restaurada correctamente."
            ]);

            return;
        }

        // GET → mostrar vista
        $files = array_merge(
            glob(__DIR__.'/../../backups/full/*.sql'),
            glob(__DIR__.'/../../backups/incremental/*.sql'),
            glob(__DIR__.'/../../backups/diferencial/*.sql')
        );

        View::show("backupRestaurar", [
            "files" => $files
        ]);
    }
    /* ===============================
       DESCARGAR COPIA
       =============================== */
    public function descargar() {
    if (!isset($_GET['tipo']) || !isset($_GET['file'])) {
        die("Parámetros inválidos.");
    }

    $tipo = $_GET['tipo'];
    $file = basename($_GET['file']); // seguridad

    $path = __DIR__ . "/../../backups/$tipo/$file";

    if (!file_exists($path)) {
        die("El archivo no existe.");
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    header('Content-Length: ' . filesize($path));
    flush();

    readfile($path);
    exit;
}
    /* ===============================
       DESCARGAR COPIA
       =============================== */
public function eliminar() {
    if (!isset($_GET['tipo']) || !isset($_GET['file'])) {
        die("Parámetros inválidos.");
    }

    $tipo = $_GET['tipo'];
    $file = basename($_GET['file']); // seguridad

    $path = __DIR__ . "/../../backups/$tipo/$file";

    if (!file_exists($path)) {
        die("El archivo no existe.");
    }

    unlink($path);

    header("Location: index.php?controller=BackupController&action=listar");
    exit;
}


}
