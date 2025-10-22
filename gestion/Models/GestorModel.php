<?php
require_once __DIR__ . '/../db/Database.php';   // ✅ CORREGIDO

class GestorModel {
    private $db;

    public function __construct() {
        $this->db = Database::conectar();
    }

    public function listarBasesDeDatos() {
        try {
            $stmt = $this->db->query("SHOW DATABASES");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function listarTablas($db) {
        try {
            $this->db->exec("USE `$db`");
            $stmt = $this->db->query("SHOW TABLES");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerRegistros($db, $table) {
        try {
            $this->db->exec("USE `$db`");
            $stmt = $this->db->query("SELECT * FROM `$table` LIMIT 50");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
    public function obtenerResumen() {
        $data = [
            'bases' => 0,
            'tablas' => 0,
            'registros' => 0,
            'tamano' => 0
        ];

        try {
            // Total de bases de datos
            $stmt = $this->db->query("SHOW DATABASES");
            $bases = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $data['bases'] = count($bases);

            // Contar tablas y tamaño aproximado
            $totalTablas = 0;
            $totalRegistros = 0;
            $totalTamano = 0;

            foreach ($bases as $db) {
                $this->db->exec("USE `$db`");
                $tablas = $this->db->query("SHOW TABLE STATUS")->fetchAll();

                $totalTablas += count($tablas);
                foreach ($tablas as $t) {
                    $totalRegistros += $t['Rows'] ?? 0;
                    $totalTamano += ($t['Data_length'] + $t['Index_length']) / (1024 * 1024);
                }
            }

            $data['tablas'] = $totalTablas;
            $data['registros'] = $totalRegistros;
            $data['tamano'] = round($totalTamano, 2);
        } catch (PDOException $e) {
            // Dejar valores en 0 si algo falla
        }

        return $data;
    }

    public function tablasPorBaseDeDatos() {
        $result = [];
        try {
            $stmt = $this->db->query("SHOW DATABASES");
            $bases = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($bases as $db) {
                $this->db->exec("USE `$db`");
                $count = $this->db->query("SHOW TABLES")->rowCount();
                $result[$db] = $count;
            }
        } catch (PDOException $e) {
        // Ignorar errores
        }
        return $result;
    }
    public function detalleBasesDeDatos() {
        $detalles = [];

        try {
            $stmt = $this->db->query("SHOW DATABASES");
            $bases = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($bases as $db) {
                $this->db->exec("USE `$db`");
                $tablas = $this->db->query("SHOW TABLE STATUS")->fetchAll();

                $numTablas = count($tablas);
                $totalRegistros = 0;
                $totalTamano = 0;

                foreach ($tablas as $t) {
                    $totalRegistros += $t['Rows'] ?? 0;
                    $totalTamano += ($t['Data_length'] + $t['Index_length']) / (1024 * 1024);
                }

                $detalles[] = [
                    'nombre' => $db,
                    'tablas' => $numTablas,
                    'registros' => $totalRegistros,
                    'tamano' => round($totalTamano, 2)
                ];
            }
        } catch (PDOException $e) {
            // Si hay error, devolvemos lo que tengamos
        }

        return $detalles;
    }
    public function crearBaseDeDatos($db) {
        try {
            $sql = "CREATE DATABASE `$db`";
            $this->db->exec($sql);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    public function eliminarBaseDeDatos($db) {
        try {
            $sql = "DROP DATABASE `$db`";
            $this->db->exec($sql);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    public function crearTabla($db, $nombre, $columnas) {
        try {
            $this->db->exec("USE `$db`");

            $defs = [];
            foreach ($columnas as $col) {
                $nombreCol = trim($col['nombre']);
                $tipo = trim($col['tipo']);
                if ($nombreCol !== '' && $tipo !== '') {
                    $defs[] = "`$nombreCol` $tipo";
                }
            }

            if (empty($defs)) {
                return "No se definieron columnas válidas.";
            }

            $sql = "CREATE TABLE `$nombre` (" . implode(', ', $defs) . ")";
            $this->db->exec($sql);

            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // ELIMINA LA TABLA
    public function eliminarTabla($db, $tabla) {
        try {
            $this->db->exec("USE `$db`");
            $this->db->exec("DROP TABLE `$tabla`");
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

// Obtiene las columnas actuales de una tabla
    public function obtenerColumnas($db, $tabla) {
        try {
            $this->db->exec("USE `$db`");
            $stmt = $this->db->query("DESCRIBE `$tabla`");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
}

// Actualiza la estructura de una tabla (añadir/eliminar columnas)
    public function actualizarEstructuraTabla($db, $tabla, $nuevas, $eliminar) {
        try {
            $this->db->exec("USE `$db`");

            // 1️⃣ Eliminar columnas marcadas
            foreach ($eliminar as $columna) {
                $columna = trim($columna);
                if ($columna !== '') {
                    $this->db->exec("ALTER TABLE `$tabla` DROP COLUMN `$columna`");
                }
            }

            // 2️⃣ Añadir nuevas columnas
            foreach ($nuevas as $col) {
                $nombre = trim($col['nombre']);
                $tipo = trim($col['tipo']);
                if ($nombre !== '' && $tipo !== '') {
                    $this->db->exec("ALTER TABLE `$tabla` ADD COLUMN `$nombre` $tipo");
                }
            }

            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}