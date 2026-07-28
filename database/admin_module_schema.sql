-- =========================================================================
-- Student Attendance Tracking Portal - Master Database Schema
-- Database Name: attendance_db
-- Server Compatibility: MySQL 5.7+ / MariaDB 10.4+ (XAMPP Server)
-- =========================================================================

DROP DATABASE IF EXISTS `attendance_db`;
CREATE DATABASE `attendance_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `attendance_db`;

-- Disable Foreign Key checks for clean table initialization
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Departments Table
CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` INT AUTO_INCREMENT PRIMARY KEY,
  `department_name` VARCHAR(100) NOT NULL UNIQUE,
  `department_code` VARCHAR(20) NOT NULL UNIQUE,
  `hod_name` VARCHAR(100) NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Courses Table
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_name` VARCHAR(100) NOT NULL,
  `course_code` VARCHAR(20) NOT NULL UNIQUE,
  `department_id` INT NOT NULL,
  `duration_years` INT NOT NULL DEFAULT 4,
  `total_semesters` INT NOT NULL DEFAULT 8,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_courses_department` FOREIGN KEY (`department_id`) 
    REFERENCES `departments` (`department_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Academic Years Table
CREATE TABLE IF NOT EXISTS `academic_years` (
  `academic_year_id` INT AUTO_INCREMENT PRIMARY KEY,
  `year_label` VARCHAR(20) NOT NULL UNIQUE,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `is_current` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Semesters Table
CREATE TABLE IF NOT EXISTS `semesters` (
  `semester_id` INT AUTO_INCREMENT PRIMARY KEY,
  `semester_number` INT NOT NULL,
  `course_id` INT NOT NULL,
  `academic_year_id` INT NOT NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_semesters_course` FOREIGN KEY (`course_id`) 
    REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_semesters_academic_year` FOREIGN KEY (`academic_year_id`) 
    REFERENCES `academic_years` (`academic_year_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY `uk_course_academic_semester` (`course_id`, `academic_year_id`, `semester_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Divisions Table
CREATE TABLE IF NOT EXISTS `divisions` (
  `division_id` INT AUTO_INCREMENT PRIMARY KEY,
  `division_name` VARCHAR(10) NOT NULL,
  `semester_id` INT NOT NULL,
  `capacity` INT NOT NULL DEFAULT 60,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_divisions_semester` FOREIGN KEY (`semester_id`) 
    REFERENCES `semesters` (`semester_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY `uk_semester_division` (`semester_id`, `division_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Academic Sessions Table
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` INT AUTO_INCREMENT PRIMARY KEY,
  `session_name` VARCHAR(50) NOT NULL UNIQUE,
  `start_time` TIME NULL,
  `end_time` TIME NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Subjects Table
CREATE TABLE IF NOT EXISTS `subjects` (
  `subject_id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject_name` VARCHAR(100) NOT NULL,
  `subject_code` VARCHAR(20) NOT NULL,
  `department_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `semester_number` INT NOT NULL,
  `credits` INT NOT NULL DEFAULT 3,
  `subject_type` ENUM('Theory', 'Practical', 'Both') NOT NULL DEFAULT 'Theory',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_subjects_department` FOREIGN KEY (`department_id`) 
    REFERENCES `departments` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_subjects_course` FOREIGN KEY (`course_id`) 
    REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY `uk_course_semester_subject_code` (`course_id`, `semester_number`, `subject_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Faculties Table
CREATE TABLE IF NOT EXISTS `faculties` (
  `faculty_id` INT AUTO_INCREMENT PRIMARY KEY,
  `employee_id` VARCHAR(30) NOT NULL UNIQUE,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL,
  `department_id` INT NOT NULL,
  `designation` VARCHAR(50) NOT NULL DEFAULT 'Assistant Professor',
  `qualification` VARCHAR(100) NULL,
  `joining_date` DATE NULL,
  `photo` VARCHAR(255) NULL,
  `experience_years` INT NOT NULL DEFAULT 3,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_faculties_department` FOREIGN KEY (`department_id`) 
    REFERENCES `departments` (`department_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Students Table
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` INT AUTO_INCREMENT PRIMARY KEY,
  `roll_number` VARCHAR(30) NOT NULL UNIQUE,
  `registration_number` VARCHAR(50) NOT NULL UNIQUE,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NULL,
  `gender` ENUM('Male', 'Female', 'Other') NOT NULL DEFAULT 'Male',
  `dob` DATE NULL,
  `department_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `semester_id` INT NULL,
  `division_id` INT NULL,
  `academic_year_id` INT NOT NULL,
  `photo` VARCHAR(255) NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_students_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_students_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_students_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`academic_year_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Subject Allocations Table
CREATE TABLE IF NOT EXISTS `subject_allocations` (
  `allocation_id` INT AUTO_INCREMENT PRIMARY KEY,
  `faculty_id` INT NOT NULL,
  `subject_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `semester_number` INT NOT NULL,
  `division_id` INT NOT NULL,
  `session_id` INT NOT NULL,
  `academic_year_id` INT NOT NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_alloc_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alloc_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alloc_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alloc_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alloc_session` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alloc_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`academic_year_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY `uk_unique_allocation` (`subject_id`, `division_id`, `session_id`, `academic_year_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Attendance Sessions Table
CREATE TABLE IF NOT EXISTS `attendance_sessions` (
  `attendance_id` INT AUTO_INCREMENT PRIMARY KEY,
  `allocation_id` INT NOT NULL,
  `attendance_date` DATE NOT NULL,
  `start_time` TIME NULL,
  `end_time` TIME NULL,
  `recorded_by_faculty_id` INT NOT NULL,
  `remarks` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_attendance_alloc` FOREIGN KEY (`allocation_id`) REFERENCES `subject_allocations` (`allocation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_attendance_faculty` FOREIGN KEY (`recorded_by_faculty_id`) REFERENCES `faculties` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Attendance Records Table
CREATE TABLE IF NOT EXISTS `attendance_records` (
  `record_id` INT AUTO_INCREMENT PRIMARY KEY,
  `attendance_id` INT NOT NULL,
  `student_id` INT NOT NULL,
  `status` ENUM('present', 'absent', 'late', 'excused') NOT NULL DEFAULT 'present',
  `remarks` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_record_session` FOREIGN KEY (`attendance_id`) REFERENCES `attendance_sessions` (`attendance_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_record_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE KEY `uk_attendance_student` (`attendance_id`, `student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Users Table (Super Admin & System Users)
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'Super Admin',
  `phone` VARCHAR(20) NULL DEFAULT '+1 (555) 019-2834',
  `designation` VARCHAR(100) NULL DEFAULT 'System Administrator',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Re-enable Foreign Key checks
SET FOREIGN_KEY_CHECKS = 1;


-- =========================================================================
-- Seed Data Initialization for All Input Options
-- =========================================================================

-- Seed Departments
INSERT INTO `departments` (`department_id`, `department_name`, `department_code`, `hod_name`, `status`) VALUES
(1, 'Computer Science & Engineering', 'CSE', 'Dr. Alan Turing', 'active'),
(2, 'Information Technology', 'IT', 'Dr. Grace Hopper', 'active'),
(3, 'Electronics & Telecommunication', 'ENTC', 'Dr. Claude Shannon', 'active')
ON DUPLICATE KEY UPDATE `department_name`=VALUES(`department_name`), `hod_name`=VALUES(`hod_name`);

-- Seed Courses
INSERT INTO `courses` (`course_id`, `course_name`, `course_code`, `department_id`, `duration_years`, `total_semesters`, `status`) VALUES
(1, 'B.Tech Computer Science', 'BTECH-CSE', 1, 4, 8, 'active'),
(2, 'M.Tech Computer Science', 'MTECH-CSE', 1, 2, 4, 'active'),
(3, 'B.Tech Information Technology', 'BTECH-IT', 2, 4, 8, 'active')
ON DUPLICATE KEY UPDATE `course_name`=VALUES(`course_name`);

-- Seed Academic Years
INSERT INTO `academic_years` (`academic_year_id`, `year_label`, `start_date`, `end_date`, `is_current`, `status`) VALUES
(1, '2025-2026', '2025-06-01', '2026-05-31', 0, 'active'),
(2, '2026-2027', '2026-06-01', '2027-05-31', 1, 'active')
ON DUPLICATE KEY UPDATE `year_label`=VALUES(`year_label`), `is_current`=VALUES(`is_current`);

-- Seed Semesters
INSERT INTO `semesters` (`semester_id`, `semester_number`, `course_id`, `academic_year_id`, `status`) VALUES
(1, 1, 1, 2, 'active'),
(2, 2, 1, 2, 'active'),
(3, 3, 1, 2, 'active'),
(4, 1, 3, 2, 'active')
ON DUPLICATE KEY UPDATE `semester_number`=VALUES(`semester_number`);

-- Seed Divisions
INSERT INTO `divisions` (`division_id`, `division_name`, `semester_id`, `capacity`, `status`) VALUES
(1, 'A', 1, 60, 'active'),
(2, 'B', 1, 60, 'active'),
(3, 'A', 2, 65, 'active')
ON DUPLICATE KEY UPDATE `division_name`=VALUES(`division_name`), `capacity`=VALUES(`capacity`);

-- Seed Academic Sessions
INSERT INTO `sessions` (`session_id`, `session_name`, `start_time`, `end_time`, `status`) VALUES
(1, 'Morning Session', '08:00:00', '12:00:00', 'active'),
(2, 'Afternoon Session', '12:30:00', '16:30:00', 'active'),
(3, 'Evening Session', '17:00:00', '21:00:00', 'active')
ON DUPLICATE KEY UPDATE `session_name`=VALUES(`session_name`), `start_time`=VALUES(`start_time`);

-- Seed Subjects
INSERT INTO `subjects` (`subject_id`, `subject_name`, `subject_code`, `department_id`, `course_id`, `semester_number`, `credits`, `subject_type`, `status`) VALUES
(1, 'Data Structures & Algorithms', 'CS101', 1, 1, 1, 4, 'Both', 'active'),
(2, 'Database Management Systems', 'CS102', 1, 1, 1, 4, 'Both', 'active'),
(3, 'Object Oriented Programming', 'IT101', 2, 3, 1, 3, 'Theory', 'active')
ON DUPLICATE KEY UPDATE `subject_name`=VALUES(`subject_name`);

-- Seed Faculties
INSERT INTO `faculties` (`faculty_id`, `employee_id`, `full_name`, `email`, `phone`, `department_id`, `designation`, `qualification`, `joining_date`, `photo`, `experience_years`, `status`) VALUES
(1, 'FAC-1001', 'Dr. Rahul Sharma', 'rahul.sharma@university.edu', '+91 9876543210', 1, 'Professor', 'Ph.D in Computer Science', '2018-07-15', 'faculty_1.jpg', 8, 'active'),
(2, 'FAC-1002', 'Dr. Priya Mehta', 'priya.mehta@university.edu', '+91 9876543211', 2, 'Associate Professor', 'Ph.D in Information Technology', '2019-08-10', 'faculty_2.jpg', 6, 'active'),
(3, 'FAC-1003', 'Prof. Amit Patil', 'amit.patil@university.edu', '+91 9876543212', 3, 'Assistant Professor', 'M.Tech in Electronics', '2021-01-20', 'faculty_3.jpg', 4, 'active')
ON DUPLICATE KEY UPDATE `full_name`=VALUES(`full_name`);

-- Seed Students
INSERT INTO `students` (`student_id`, `roll_number`, `registration_number`, `full_name`, `email`, `phone`, `gender`, `dob`, `department_id`, `course_id`, `semester_id`, `division_id`, `academic_year_id`, `status`) VALUES
(1, '2026-CSE-001', 'REG-2026-001', 'Aarav Patel', 'aarav.patel@student.edu', '+91 9876500001', 'Male', '2004-05-12', 1, 1, 1, 1, 2, 'active'),
(2, '2026-CSE-002', 'REG-2026-002', 'Ananya Roy', 'ananya.roy@student.edu', '+91 9876500002', 'Female', '2004-08-23', 1, 1, 1, 1, 2, 'active')
ON DUPLICATE KEY UPDATE `full_name`=VALUES(`full_name`);

-- Seed Subject Allocations
INSERT INTO `subject_allocations` (`allocation_id`, `faculty_id`, `subject_id`, `course_id`, `semester_number`, `division_id`, `session_id`, `academic_year_id`, `status`) VALUES
(1, 1, 1, 1, 1, 1, 1, 2, 'active'),
(2, 2, 2, 1, 1, 2, 2, 2, 'active'),
(3, 3, 3, 3, 1, 3, 1, 2, 'active')
ON DUPLICATE KEY UPDATE `allocation_id`=VALUES(`allocation_id`);

-- Seed Users (Super Admin)
INSERT INTO `users` (`user_id`, `username`, `full_name`, `email`, `password_hash`, `role`, `phone`, `designation`) VALUES
(1, 'admin', 'Super Admin', 'admin@university.edu', '$2y$10$e8w.x.9N3wU8H0e6L6vG1O.N/54gD.e4sL9W9V5u.5kQ8lO.Q5N1u', 'Super Admin', '+1 (555) 019-2834', 'System Administrator')
ON DUPLICATE KEY UPDATE `full_name`=VALUES(`full_name`), `email`=VALUES(`email`), `phone`=VALUES(`phone`), `designation`=VALUES(`designation`);
