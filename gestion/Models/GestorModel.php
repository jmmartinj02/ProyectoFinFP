<?php
require_once __DIR__ . '/../db/Database.php';   // ✅ CORREGIDO

class GestorModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();

    }

public function listarBasesDeDatos() {
    try {
        $stmt = $this->db->query("SHOW DATABASES");
        $bases = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Filtrar bases de datos del sistema
        $bases = array_filter($bases, function($db) {
            return !in_array($db, ['information_schema', 'mysql', 'performance_schema', 'sys']);
        });

        return array_values($bases); // Reindexar el array
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
    //solucionado, me duplicaba los campos en la vista de registros.
    // me sacaba tanto campo como el lugar en el array y me pintaba doble infoamacioón
    public function obtenerRegistros($db, $table) {
        try {
            $this->db->exec("USE `$db`");
            $stmt = $this->db->query("SELECT * FROM `$table` LIMIT 50");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    public function obtenerBasesDeDatos() {
    $bases = [];

    try {
        // Leer conexión actual (sin base fija)
        $c = $_SESSION['conexion'] ?? [];
        $host = $c['host'] ?? '127.0.0.1';
        $user = $c['user'] ?? 'root';
        $pass = $c['pass'] ?? '';

        // Conexión sin dbname para listar todas
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_COLUMN,
        ]);

        $stmt = $pdo->query("SHOW DATABASES");
        $bases = $stmt->fetchAll();

        // Opcional: excluir bases del sistema
        $bases = array_filter($bases, function($db) {
            return !in_array($db, ['information_schema', 'mysql', 'performance_schema', 'sys']);
        });

    } catch (PDOException $e) {
        $bases = ['Error: ' . $e->getMessage()];
    }

    return $bases;
}
 public function ejecutarConsultaSQL(string $sql, int $limit = 100) {
    $resp = ['error' => null, 'type' => null, 'data' => null, 'affected' => null];

    try {
        $c = $_SESSION['conexion'] ?? [];
        $host = $c['host'] ?? '127.0.0.1';
        $user = $c['user'] ?? 'root';
        $pass = $c['pass'] ?? '';
        $db   = $c['db'] ?? null;

        // Conectar con la base seleccionada (si existe)
        $dsn = $db
            ? "mysql:host=$host;dbname=$db;charset=utf8mb4"
            : "mysql:host=$host;charset=utf8mb4";

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $firstWord = strtoupper(strtok($sql, " \t\n\r\0\x0B"));
        if (in_array($firstWord, ['SELECT', 'SHOW', 'DESCRIBE', 'EXPLAIN'])) {
            if ($firstWord === 'SELECT' && stripos($sql, 'LIMIT') === false) {
                $sql .= " LIMIT " . intval($limit);
            }
            $stmt = $pdo->query($sql);
            $resp['type'] = 'select';
            $resp['data'] = $stmt->fetchAll();
        } else {
            $resp['type'] = 'action';
            $resp['affected'] = $pdo->exec($sql);
        }

    } catch (PDOException $e) {
        $resp['error'] = $e->getMessage();
    }

    return $resp;
}
public function obtenerRegistroPorIdEdit($db, $table, $id) {
    $conexion = (new Database($db))->getConnection();
    $stmt = $conexion->prepare("SELECT * FROM `$table` WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function actualizarRegistroEdit($db, $table, $id, $datos) {
    $conexion = (new Database($db))->getConnection();

    $setPartes = [];
    foreach ($datos as $columna => $valor) {
        $setPartes[] = "`$columna` = :$columna";
    }

    $sql = "UPDATE `$table` SET " . implode(',', $setPartes) . " WHERE id = :id";
    $stmt = $conexion->prepare($sql);

    foreach ($datos as $columna => $valor) {
        $stmt->bindValue(':' . $columna, $valor);
    }
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    return $stmt->execute();
}
//como no todas las tablas tiene un campo ID, ME HA DADO FALLO Y HE TENIDO QUE CAMBIARLO POR ESTO.

public function obtenerRegistroPorId($db, $table, $id) {
    $conexion = (new Database($db))->getConnection();

    // Detectar columnas de la tabla
    $colsStmt = $conexion->query("SHOW COLUMNS FROM `$table`");
    $columnas = $colsStmt->fetchAll(PDO::FETCH_COLUMN);

    // Si la tabla tiene 'id', usamos ese campo; si no, el primero
    $columnaClave = in_array('id', $columnas) ? 'id' : $columnas[0];

    // Preparar la consulta dinámica
    $sql = "SELECT * FROM `$table` WHERE `$columnaClave` = :valor";
    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':valor', $id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function actualizarRegistro($db, $table, $id, $datos) {
    $conexion = (new Database($db))->getConnection();

    $setPartes = [];
    foreach ($datos as $columna => $valor) {
        $setPartes[] = "`$columna` = :$columna";
    }

    $sql = "UPDATE `$table` SET " . implode(',', $setPartes) . " WHERE id = :id";
    $stmt = $conexion->prepare($sql);

    foreach ($datos as $columna => $valor) {
        $stmt->bindValue(':' . $columna, $valor);
    }
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    return $stmt->execute();
}
public function eliminarRegistroPorId($db, $table, $id) {
    try {
        $conexion = (new Database($db))->getConnection();

        // Detectar columnas de la tabla
        $colsStmt = $conexion->query("SHOW COLUMNS FROM `$table`");
        $columnas = $colsStmt->fetchAll(PDO::FETCH_COLUMN);

        // Usar 'id' si existe, sino la primera columna
        $columnaClave = in_array('id', $columnas) ? 'id' : $columnas[0];

        $sql = "DELETE FROM `$table` WHERE `$columnaClave` = :valor LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':valor', $id);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}



}