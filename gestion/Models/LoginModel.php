<?php
class LoginModel {
    public function probarConexion($host, $user, $pass, $db) {
        try {
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
