<?php

require_once __DIR__ . '/../Models/BackupModel.php';
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../Vistas/View.php';

class BackupController {

    private $model;

    public function __construct() {
        $this->model = new BackupModel();
    }

    /* ================================================================
       PÁGINA PRINCIPAL DEL MÓDULO DE BACKUPS
    ================================================================= */
    public function index() {
        $last = $this->model->getLastBackupDate();
        $files = $this->model->listarBackupsAgrupados();

        View::show("backupIndex", [
            "last" => $last,
            "files" => $files
        ]);
    }

    /* ================================================================
       CREAR BACKUP
    ================================================================= */
    public function crear() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $db = $_POST['db'] ?? null;
            $tipo = $_POST['tipo'] ?? 'full';

            if (!$db) {
                View::show("backupCrear", [
                    "error" => "Debes seleccionar una base de datos",
                    "bases" => $this->model->getBases()
                ]);
                return;
            }

            $filename = $this->model->crearBackup($db, $tipo);

            View::show("backupCrear", [
                "mensaje" => "Copia creada correctamente: $filename",
                "bases" => $this->model->getBases()
            ]);
            return;
        }

        // GET → formulario
        View::show("backupCrear", [
            "bases" => $this->model->getBases()
        ]);
    }

    /* ================================================================
       LISTAR
    ================================================================= */
    public function listar() {

        $files = $this->model->listarBackupsAgrupados();

        View::show("backupListar", [
            "files" => $files
        ]);
    }

    /* ================================================================
       DESCARGAR
    ================================================================= */
    public function descargar() {

        $file = $_GET['file'] ?? null;

        if (!$file) {
            View::show("errorView", ["mensaje" => "No se indicó el archivo a descargar"]);
            return;
        }

        $path = $this->model->getBackupPath($file);

        if (!file_exists($path)) {
            View::show("errorView", ["mensaje" => "El archivo no existe"]);
            return;
        }

        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$file\"");
        header("Content-Length: " . filesize($path));
        readfile($path);
        exit;
    }

    /* ================================================================
       ELIMINAR
    ================================================================= */
    public function eliminar() {

        $file = $_GET['file'] ?? null;

        if (!$file) {
            View::show("errorView", ["mensaje" => "Archivo no especificado"]);
            return;
        }

        $ok = $this->model->eliminarBackup($file);

        if (!$ok) {
            View::show("errorView", ["mensaje" => "No se pudo eliminar"]);
            return;
        }

        header("Location: index.php?controller=BackupController&action=listar");
        exit;
    }

    /* ================================================================
       RESTAURAR
    ================================================================= */
    public function restaurar() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $file = $_POST['file'] ?? null;
            $auto = isset($_POST['autoCreate']);
            $manualDB = $_POST['db'] ?? null;

            if (!$file) {
                View::show("backupRestaurar", [
                    "error" => "Debes seleccionar una copia",
                    "files" => $this->model->listarBackupsFlat(),
                    "bases" => $this->model->getBases()
                ]);
                return;
            }

            $path = $this->model->getBackupPath($file);

            if (!file_exists($path)) {
                View::show("backupRestaurar", [
                    "error" => "El archivo no existe",
                    "files" => $this->model->listarBackupsFlat(),
                    "bases" => $this->model->getBases()
                ]);
                return;
            }

            /* --------------------------------------------------------
               1. Determinar la BD destino
            -------------------------------------------------------- */
            if ($auto) {
                $partes = explode('_', $file);
                // he tenido que contar desde la derecha para quitar las partes fijas
                // del archivo, por si el nombre de la base de datos contiene un "_"
                // y que al crear la base de datos, lo haga con el nombre completo y no la mitad
                 $partes[count($partes)-1] = pathinfo($partes[count($partes)-1], PATHINFO_FILENAME);
                 $baseParts = array_slice($partes, 0, -3);
                 $base = implode('_', $baseParts);

                try {
                    $pdo = (new Database())->getConnection();
                    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$base`");
                } catch (Exception $e) {
                    View::show("backupRestaurar", [
                        "error" => "Error creando BD: " . $e->getMessage(),
                        "files" => $this->model->listarBackupsFlat(),
                        "bases" => $this->model->getBases()
                    ]);
                    return;
                }

            } else {

                if (!$manualDB) {
                    View::show("backupRestaurar", [
                        "error" => "Debes elegir base destino",
                        "files" => $this->model->listarBackupsFlat(),
                        "bases" => $this->model->getBases()
                    ]);
                    return;
                }

                $base = $manualDB;
            }

            /* --------------------------------------------------------
               2. Restaurar mediante modelo
            -------------------------------------------------------- */
            $ok = $this->model->restaurarBackup($path, $base);

            if ($ok !== true) {
                View::show("backupRestaurar", [
                    "error" => $ok,
                    "files" => $this->model->listarBackupsFlat(),
                    "bases" => $this->model->getBases()
                ]);
                return;
            }

            header("Location: index.php?controller=BackupController&action=listar");
            exit;
        }

        // GET
        View::show("backupRestaurar", [
            "files" => $this->model->listarBackupsFlat(),
            "bases" => $this->model->getBases()
        ]);
    }
}
