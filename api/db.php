<?php
$host = '127.0.0.1';
$user = 'root';
$password = '';
$dbname = 'faculty_attendance';

try {
    // Connect without DB to create it if it doesn't exist
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    
    // Re-connect with DB
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Create Tables
    $tables = [
        "CREATE TABLE IF NOT EXISTS Departments (
            department_id INT AUTO_INCREMENT PRIMARY KEY,
            department_name VARCHAR(100) UNIQUE NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS Faculty (
            faculty_id INT AUTO_INCREMENT PRIMARY KEY,
            faculty_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            department VARCHAR(100) NOT NULL,
            designation VARCHAR(100) NOT NULL,
            avatar VARCHAR(255)
        )",
        "CREATE TABLE IF NOT EXISTS Students (
            student_id INT AUTO_INCREMENT PRIMARY KEY,
            roll_no VARCHAR(50) UNIQUE NOT NULL,
            student_name VARCHAR(100) NOT NULL,
            department VARCHAR(100) NOT NULL,
            semester VARCHAR(50) NOT NULL,
            division VARCHAR(50) NOT NULL,
            profile_photo VARCHAR(255) DEFAULT 'default-avatar.png'
        )",
        "CREATE TABLE IF NOT EXISTS Subjects (
            subject_id INT AUTO_INCREMENT PRIMARY KEY,
            subject_name VARCHAR(100) NOT NULL,
            semester VARCHAR(50) NOT NULL,
            department VARCHAR(100) NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS Lecture (
            lecture_id INT AUTO_INCREMENT PRIMARY KEY,
            subject_id INT NOT NULL,
            lecture_number VARCHAR(50) NOT NULL,
            faculty_id INT NOT NULL,
            lecture_date DATE NOT NULL,
            UNIQUE(subject_id, lecture_number, faculty_id, lecture_date),
            FOREIGN KEY (subject_id) REFERENCES Subjects(subject_id),
            FOREIGN KEY (faculty_id) REFERENCES Faculty(faculty_id)
        )",
        "CREATE TABLE IF NOT EXISTS Attendance (
            attendance_id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL,
            lecture_id INT NOT NULL,
            attendance_status ENUM('present', 'absent') NOT NULL,
            remarks VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE(student_id, lecture_id),
            FOREIGN KEY (student_id) REFERENCES Students(student_id),
            FOREIGN KEY (lecture_id) REFERENCES Lecture(lecture_id)
        )",
        "CREATE TABLE IF NOT EXISTS Attendance_Validation (
            validation_id INT AUTO_INCREMENT PRIMARY KEY,
            attendance_id INT NOT NULL,
            status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            approved_by INT,
            approved_at TIMESTAMP NULL,
            rejection_reason VARCHAR(255),
            edit_reason VARCHAR(255),
            original_status ENUM('present', 'absent'),
            new_status ENUM('present', 'absent'),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (attendance_id) REFERENCES Attendance(attendance_id),
            FOREIGN KEY (approved_by) REFERENCES Faculty(faculty_id)
        )"
    ];
    
    foreach($tables as $sql) {
        $pdo->exec($sql);
    }
    
    // Seed Faculty and Departments
    $stmt = $pdo->query("SELECT COUNT(*) FROM Faculty");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT IGNORE INTO Departments (department_name) VALUES ('CE'), ('AIDS'), ('EE'), ('BT'), ('ME')");
        
        $pdo->exec("INSERT INTO Faculty (faculty_name, email, password, department, designation, avatar) VALUES 
            ('Prof. Smith', 'smith@college.edu', 'pass123', 'CE', 'Professor', 'https://ui-avatars.com/api/?name=Prof+Smith&background=0D8ABC&color=fff'),
            ('Dr. Davis', 'davis@college.edu', 'pass123', 'AIDS', 'Associate Professor', 'https://ui-avatars.com/api/?name=Dr+Davis&background=10B981&color=fff')");
    }

    // Automatically insert sample student records for all departments, semesters, and divisions
    $stmt = $pdo->query("SELECT COUNT(*) FROM Students");
    if ($stmt->fetchColumn() < 100) { // If fewer than 100 students, reseed
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $pdo->exec("TRUNCATE TABLE Attendance_Validation");
        $pdo->exec("TRUNCATE TABLE Attendance");
        $pdo->exec("TRUNCATE TABLE Students");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        $depts = ['CE', 'AIDS', 'EE', 'BT', 'ME'];
        $sems = ['Semester 1', 'Semester 2', 'Semester 3', 'Semester 4', 'Semester 5', 'Semester 6', 'Semester 7', 'Semester 8'];
        $divs = ['Div A', 'Div B', 'Div C', 'Div D', 'Div E'];
        
        $firstNames = ['Aarav', 'Meera', 'Vikramaditya', 'Tanvi', 'Dhruv', 'Natasha', 'Ananya', 'Aditya', 'Kabir', 'Riya', 'Devansh', 'Varun', 'Rohan', 'Sneha', 'Yash', 'Avani', 'Priya', 'Diya', 'Siddharth', 'Kavya', 'Shreya', 'Arjun', 'Ishaan', 'Pooja', 'Nikhil'];
        $lastNames = ['Sharma', 'Deshmukh', 'Singh', 'Agarwal', 'Kapoor', 'Fernandez', 'Iyer', 'Patel', 'Mehta', 'Sen', 'Saxena', 'Nambiar', 'Verma', 'Nair', 'Vardhan', 'Menon', 'Kulkarni', 'Banerjee', 'Rao', 'Bhatt', 'Ghoshal', 'Reddy', 'Joshi', 'Hegde', 'Gupta'];
        
        $studentValues = [];
        foreach ($depts as $dept) {
            foreach ($sems as $semIndex => $sem) {
                foreach ($divs as $div) {
                    // Generate 5 students per combo
                    for ($i = 1; $i <= 5; $i++) {
                        $rollNo = $dept . ($semIndex + 1) . substr($div, -1) . sprintf('%02d', $i);
                        $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
                        $studentValues[] = "('$rollNo', '$name', '$dept', '$sem', '$div')";
                    }
                }
            }
        }
        
        // Insert in chunks to avoid query length limits
        $chunks = array_chunk($studentValues, 100);
        foreach ($chunks as $chunk) {
            $values = implode(", ", $chunk);
            $pdo->exec("INSERT INTO Students (roll_no, student_name, department, semester, division) VALUES $values ON DUPLICATE KEY UPDATE student_name=VALUES(student_name)");
        }
        
        $pdo->exec("INSERT IGNORE INTO Subjects (subject_name, semester, department) VALUES 
            ('210241 - Discrete Mathematics', 'Semester 3', 'CE'),
            ('210242 - Fundamentals of Data Structures', 'Semester 3', 'CE'),
            ('210243 - Object Oriented Programming', 'Semester 3', 'CE')");
    }

    // Auto-migrate old database records to match new UI dropdown acronyms
    $pdo->exec("UPDATE Students SET department = 'CE' WHERE department = 'Computer Science'");
    $pdo->exec("UPDATE Students SET department = 'EE' WHERE department = 'Electrical Engineering'");
    $pdo->exec("UPDATE Students SET department = 'AIDS' WHERE department = 'Information Tech'");
    
    // Auto-migrate divisions from A, B, C to Div A, Div B, Div C
    $pdo->exec("UPDATE Students SET division = CONCAT('Div ', division) WHERE division IN ('A', 'B', 'C', 'D', 'E')");
    
    $pdo->exec("UPDATE Faculty SET department = 'CE' WHERE department = 'Computer Science'");
    $pdo->exec("UPDATE Faculty SET department = 'EE' WHERE department = 'Electrical Engineering'");
    $pdo->exec("UPDATE Faculty SET department = 'AIDS' WHERE department = 'Information Tech'");

    $pdo->exec("UPDATE Subjects SET department = 'CE' WHERE department = 'Computer Science'");
    $pdo->exec("UPDATE Subjects SET department = 'EE' WHERE department = 'Electrical Engineering'");
    $pdo->exec("UPDATE Subjects SET department = 'AIDS' WHERE department = 'Information Tech'");


    
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
}
