<?php
require_once 'api/db.php';
try {
    $stmt = $pdo->query("SELECT * FROM Attendance LIMIT 1");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
