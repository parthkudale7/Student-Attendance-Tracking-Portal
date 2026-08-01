<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'attendance_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL, ADD COLUMN designation VARCHAR(100) DEFAULT NULL");
    echo "Columns added.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Columns already exist.\n";
    } else {
        echo "Exception: " . $e->getMessage() . "\n";
    }
}
