<?php
class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct() {
        $host = "127.0.0.1";
        $db = "inventaris_db";
        $user = "root";
        $pass = "";
        $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die("<div style='font-family:Arial;padding:30px'><h2>Koneksi database gagal</h2><p>Import database/inventaris_db.sql dan pastikan MySQL Laragon berjalan.</p></div>");
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) self::$instance = new Database();
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }

    private function __clone() {}
}
