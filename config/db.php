<?php
/**
 * Database Connection Configuration
 * Student Attendance Tracking Portal
 */

if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_PORT')) define('DB_PORT', '3306');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_NAME')) define('DB_NAME', 'attendance_db');

try {
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $ex) {
            $dsnFallback = "mysql:host=localhost;port=3306;dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsnFallback, DB_USER, DB_PASS, $options);
        }
    }
} catch (PDOException $e) {
    if (defined('IS_AJAX') && IS_AJAX) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection error: ' . $e->getMessage()
        ]);
        exit;
    } else {
        die("Database connection failed: " . $e->getMessage());
    }
}
return $pdo;
