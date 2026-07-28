<?php
require 'api/db.php';
$depts = ['CE', 'AIDS', 'EE', 'BT', 'ME', 'Civil Engineering', 'Information Technology', 'Electronics & Telecommunication'];
$stmt = $pdo->prepare("INSERT IGNORE INTO departments (department_name) VALUES (?)");
foreach ($depts as $d) {
    $stmt->execute([$d]);
}
echo "Inserted.";
?>
