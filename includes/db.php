<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'attendance_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Return JSON error if connection fails so the UI handles it gracefully
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}
