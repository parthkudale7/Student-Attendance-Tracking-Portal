<?php
require_once 'api/db.php';
try {
    $pdo->exec("ALTER TABLE Attendance MODIFY COLUMN attendance_status VARCHAR(20) NOT NULL");
    $pdo->exec("ALTER TABLE Attendance_Validation MODIFY COLUMN original_status VARCHAR(20)");
    $pdo->exec("ALTER TABLE Attendance_Validation MODIFY COLUMN new_status VARCHAR(20)");
    echo "DB Schema Updated Successfully\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
