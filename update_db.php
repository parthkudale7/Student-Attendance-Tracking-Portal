<?php
$pdo = new PDO('mysql:host=localhost;dbname=faculty_attendance;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $pdo->exec("ALTER TABLE attendance ADD COLUMN validation_status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending'");
    echo 'Added validation_status column. ';
} catch(Exception $e) {}

try {
    $pdo->exec("ALTER TABLE attendance ADD COLUMN validated_at TIMESTAMP NULL");
    echo 'Added validated_at column. ';
} catch(Exception $e) {}

try {
    $pdo->exec("ALTER TABLE attendance ADD COLUMN validated_by INT NULL");
    echo 'Added validated_by column. ';
} catch(Exception $e) {}

// Check if attendance is empty
$count = $pdo->query("SELECT COUNT(*) FROM attendance")->fetchColumn();
if ($count == 0) {
    echo 'Attendance table is empty. Inserting dummy data... ';
    // Get a lecture ID or create one
    $lectId = $pdo->query("SELECT lecture_id FROM lecture LIMIT 1")->fetchColumn();
    if (!$lectId) {
        $pdo->exec("INSERT INTO lecture (subject_id, lecture_name, lecture_date) VALUES (1, 'Lecture 1', CURDATE())");
        $lectId = $pdo->lastInsertId();
    }
    
    // Get some students
    $students = $pdo->query("SELECT student_id FROM students LIMIT 5")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($students as $sid) {
        $pdo->exec("INSERT INTO attendance (student_id, lecture_id, attendance_status, validation_status) VALUES ($sid, $lectId, 'Present', 'Pending')");
    }
    echo 'Inserted dummy data. ';
} else {
    // Update existing to Pending
    $pdo->exec("UPDATE attendance SET validation_status = 'Pending' WHERE validation_status IS NULL");
    echo 'Updated existing to Pending.';
}
?>
