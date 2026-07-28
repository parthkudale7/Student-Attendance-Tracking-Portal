<?php
require_once 'api/db.php';
try {
    $pdo->exec("UPDATE Students SET division = 'A' WHERE division = 'Div A'");
    $pdo->exec("UPDATE Students SET division = 'B' WHERE division = 'Div B'");
    echo "DB Division Updated Successfully\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
