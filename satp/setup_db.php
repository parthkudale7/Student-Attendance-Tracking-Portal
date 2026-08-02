<?php
/**
 * SATP Database Installer / Initializer Helper
 * Creates 'satp_db' and imports 'database.sql' if missing.
 */
header('Content-Type: application/json; charset=utf-8');

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'satp_db');

try {
    // 1. Connect without database selected
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 2. Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");

    // 3. Read database.sql file
    $sqlFile = __DIR__ . '/database.sql';
    if (!file_exists($sqlFile)) {
        echo json_encode(['success' => false, 'error' => 'database.sql file not found']);
        exit;
    }

    $sqlContent = file_get_contents($sqlFile);

    // 4. Execute multi-query batch
    $statements = array_filter(array_map('trim', explode(';', $sqlContent)));

    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            $pdo->exec($stmt);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Database satp_db successfully initialized and seeded with tables & sample records!'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
