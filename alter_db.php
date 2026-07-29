<?php
require_once 'api/db.php';
try {
    $pdo->exec("ALTER TABLE Attendance ADD COLUMN validation_status VARCHAR(20) DEFAULT 'Pending'");
    $pdo->exec("ALTER TABLE Attendance ADD COLUMN validated_at DATETIME DEFAULT NULL");
    $pdo->exec("ALTER TABLE Attendance ADD COLUMN validated_by INT(11) DEFAULT NULL");
    echo "Columns added successfully.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Columns already exist.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
?>
