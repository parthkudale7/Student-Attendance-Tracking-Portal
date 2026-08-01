<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Dropping database if exists...\n<br>";
    $pdo->exec("DROP DATABASE IF EXISTS attendance_db;");
    
    echo "Done! You can now run setup_db.php and setup_schema.php again.\n";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage() . "\n");
}
?>
