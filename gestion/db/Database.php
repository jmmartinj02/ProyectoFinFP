<?php
class Database {
    private $conexion;  // Aquí guardamos el objeto PDO
    private $host = 'mariadb'; // Cambia si tu contenedor/host es diferente
    private $user = 'root';
    private $pass = 'changepassword';
    private $charset = 'utf8mb4';

    // Constructor: puede recibir opcionalmente el nombre de la base de datos
    public function __construct($dbname = null) {
        $dsn = "mysql:host={$this->host}";
        if ($dbname) {
            $dsn .= ";dbname={$dbname}";
        }
        $dsn .= ";charset={$this->charset}";

        try {
            $this->conexion = new PDO($dsn, $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // Devuelve el objeto PDO por si se necesita directamente
    public function getConnection() {
        return $this->conexion;
    }

    // Ejecuta consultas directas
    public function query($sql) {
        $stmt = $this->conexion->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt;
}


    // Permite preparar consultas seguras
    public function prepare($sql) {
        return $this->conexion->prepare($sql);
    }

    // Listar bases de datos del usuario
    public function listarBasesDeDatos() {
        try {
            $stmt = $this->conexion->query("SHOW DATABASES");
            $todas = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Filtra bases del sistema
            return array_filter($todas, function ($db) {
                return !in_array($db, ['information_schema', 'mysql', 'performance_schema', 'sys']);
            });
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtener columnas de una tabla para el formulario de modificacion
    public function obtenerColumnasForm($tabla) {
        try {
            $stmt = $this->conexion->prepare("SHOW COLUMNS FROM `$tabla`");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Insertar registro dinámicamente utiliza implode que recoge el string
    // patra modificarlo y separar los campos por lo que uno quiera
    //el placeholder evita que se hagan inyecciones sql tranformando las columnas en un texto
    // que no puede ser usado como consulta.
    public function insertarRegistro($tabla, $datos) {
        try {
            $columnas = array_keys($datos);
            $placeholders = array_map(fn($c) => ':' . $c, $columnas);

            $sql = "INSERT INTO `$tabla` (" . implode(',', $columnas) . ")
                    VALUES (" . implode(',', $placeholders) . ")";

            $stmt = $this->conexion->prepare($sql);
            foreach ($datos as $col => $valor) {
                $stmt->bindValue(':' . $col, $valor);
            }
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
