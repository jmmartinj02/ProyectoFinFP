<?php
class Database {
    private static $connection = null;

    public static function conectar() {
        if (self::$connection === null) {
            $host = 'mariadb'; // nombre del servicio en Docker
            $db = 'pagina_escalada'; // puedes cambiarlo si quieres usar otra BD
            $user = 'root';
            $pass = 'changepassword';
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

            try {
                self::$connection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
