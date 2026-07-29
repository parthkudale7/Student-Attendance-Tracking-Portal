<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("USE attendance_db;");

    $sql = "
    CREATE TABLE IF NOT EXISTS `departments` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `dept_name` varchar(100) NOT NULL,
      `dept_code` varchar(20) NOT NULL,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `dept_code` (`dept_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `subjects` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `department_id` int(11) NOT NULL,
      `subject_name` varchar(100) NOT NULL,
      `subject_code` varchar(20) NOT NULL,
      `semester` int(2) NOT NULL,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `subject_code` (`subject_code`),
      FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `faculty_subjects` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `faculty_id` int(11) NOT NULL,
      `subject_id` int(11) NOT NULL,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`faculty_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `student_profiles` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `department_id` int(11) NOT NULL,
      `enrollment_no` varchar(50) NOT NULL,
      `semester` int(2) NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `enrollment_no` (`enrollment_no`),
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `attendance_records` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `student_id` int(11) NOT NULL,
      `faculty_id` int(11) NOT NULL,
      `subject_id` int(11) NOT NULL,
      `attendance_date` date NOT NULL,
      `status` enum('Present','Absent','Late') NOT NULL,
      `remarks` text DEFAULT NULL,
      `recorded_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`faculty_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `system_settings` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `setting_key` varchar(50) NOT NULL,
      `setting_value` varchar(255) NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `setting_key` (`setting_key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    INSERT IGNORE INTO `departments` (`id`, `dept_name`, `dept_code`) VALUES
    (1, 'Computer Science', 'CS'),
    (2, 'Information Technology', 'IT');

    INSERT IGNORE INTO `system_settings` (`setting_key`, `setting_value`) VALUES
    ('academic_year', '2023-2024'),
    ('portal_status', 'Online');
    ";

    $pdo->exec($sql);
    echo "Schema successfully created in attendance_db!\n";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage() . "\n");
}
