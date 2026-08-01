<?php
require_once 'api/db.php';
try {
    $pdo->exec("ALTER TABLE Students ADD COLUMN profile_photo VARCHAR(255) DEFAULT 'default-avatar.png'");
    echo "Column added successfully";
} catch (PDOException $e) {
    echo "Column may already exist: " . $e->getMessage();
}
?>
