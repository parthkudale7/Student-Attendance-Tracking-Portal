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
                PDO::ATTR_TIMEOUT            => 3,
            ];
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (Throwable $e) {
            // Attempt auto-creation if database doesn't exist yet on MySQL
            try {
                $rootPdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USER, DB_PASS);
                $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                $this->conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);

                // Import database.sql if tables are missing
                $checkTable = $this->conn->query("SHOW TABLES LIKE 'departments'")->fetch();
                if (!$checkTable && file_exists(__DIR__ . '/../database.sql')) {
                    $sql = file_get_contents(__DIR__ . '/../database.sql');
                    $statements = array_filter(array_map('trim', explode(';', $sql)));
                    foreach ($statements as $stmt) {
                        if (!empty($stmt)) {
                            $this->conn->exec($stmt);
                        }
                    }
                }
            } catch (Throwable $ex) {
                // Fallback to mock mode if MySQL server is offline
                $this->usingMock = true;
                $this->conn = null;
            }
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

// Helper function to get active database connection
function getDBConnection() {
    return Database::getInstance()->getConnection();
}
