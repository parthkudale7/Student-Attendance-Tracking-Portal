<?php
/**
 * Student Attendance Tracking Portal (SATP)
 * Database Connection & Configuration File
 * 
 * Uses MySQL with PDO & Prepared Statements
 */

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'satp_db');

class Database {
    private static $instance = null;
    private $conn;
    private $usingMock = false;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_TIMEOUT            => 2,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (Throwable $e) {
            // Fallback to standalone mode if MySQL is unreachable during development/preview
            $this->usingMock = true;
            $this->conn = null;
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function isMock() {
        return $this->usingMock;
    }
}

// Function helper to get active connection
function getDBConnection() {
    return Database::getInstance()->getConnection();
}
