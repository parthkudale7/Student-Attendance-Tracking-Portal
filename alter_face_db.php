<?php
require_once 'includes/db.php';

try {
    $pdo->exec("ALTER TABLE users ADD COLUMN face_descriptor TEXT DEFAULT NULL");
    echo "Successfully added face_descriptor column.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
