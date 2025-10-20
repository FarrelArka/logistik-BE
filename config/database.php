<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

class Database {
    private $conn;

    public function connect() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->safeLoad();

        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '5432';
        $db   = $_ENV['DB_NAME'] ?? 'logistik';
        $user = $_ENV['DB_USER'] ?? 'postgres';
        $pass = $_ENV['DB_PASS'] ?? '';

        $dsn = "pgsql:host=$host;port=$port;dbname=$db";

        try {
            $this->conn = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (Exception $e) {
            die("DB connection failed: " . $e->getMessage());
        }

        return $this->conn;
    }
}
