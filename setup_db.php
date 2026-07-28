<?php

$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS attendance_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE attendance_db;");

    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'faculty', 'student') NOT NULL,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    $users = [
        ['name' => 'Super Admin', 'email' => 'admin@portal.com', 'password' => 'Admin@123', 'role' => 'admin'],
        ['name' => 'Temporary Admin', 'email' => 'tempadmin@portal.com', 'password' => 'TempAdmin@123', 'role' => 'admin'],
        ['name' => 'John Doe', 'email' => 'faculty@portal.com', 'password' => 'Faculty@123', 'role' => 'faculty'],
        ['name' => 'Temporary Faculty', 'email' => 'tempfaculty@portal.com', 'password' => 'TempFaculty@123', 'role' => 'faculty'],
        ['name' => 'Jane Smith', 'email' => 'student@portal.com', 'password' => 'Student@123', 'role' => 'student'],
        ['name' => 'Temporary Student', 'email' => 'tempstudent@portal.com', 'password' => 'TempPassword@123', 'role' => 'student']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    foreach ($users as $u) {
        $hashed = password_hash($u['password'], PASSWORD_DEFAULT);
        $stmt->execute([$u['name'], $u['email'], $hashed, $u['role']]);
    }

    echo "Database setup completed successfully.\n";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage() . "\n");
}
