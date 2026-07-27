-- Student Attendance Tracking Portal (SATP) Database Schema & Seed Data
-- Database Engine: MySQL (InnoDB)

CREATE DATABASE IF NOT EXISTS `satp_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `satp_db`;

-- Drop existing tables if needed for clean re-installation
DROP TABLE IF EXISTS `attendance_records`;
DROP TABLE IF EXISTS `report_alerts`;
DROP TABLE IF EXISTS `subjects`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `faculty`;
DROP TABLE IF EXISTS `departments`;

-- 1. Departments Table
CREATE TABLE `departments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `dept_code` VARCHAR(20) NOT NULL UNIQUE,
  `dept_name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Faculty Table
CREATE TABLE `faculty` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `faculty_id` VARCHAR(30) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `dept_id` INT NOT NULL,
  `phone` VARCHAR(20),
  FOREIGN KEY (`dept_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Subjects Table
CREATE TABLE `subjects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject_code` VARCHAR(20) NOT NULL UNIQUE,
  `subject_name` VARCHAR(100) NOT NULL,
  `dept_id` INT NOT NULL,
  `semester` INT NOT NULL,
  `faculty_id` INT NOT NULL,
  FOREIGN KEY (`dept_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`faculty_id`) REFERENCES `faculty`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Students Table
CREATE TABLE `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `roll_no` VARCHAR(30) NOT NULL UNIQUE,
  `prn` VARCHAR(30) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20),
  `parent_phone` VARCHAR(20),
  `dept_id` INT NOT NULL,
  `semester` INT NOT NULL,
  `division` VARCHAR(10) NOT NULL,
  `avatar` VARCHAR(255) DEFAULT 'assets/img/default-avatar.png',
  FOREIGN KEY (`dept_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Attendance Records Table
CREATE TABLE `attendance_records` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `subject_id` INT NOT NULL,
  `faculty_id` INT NOT NULL,
  `attendance_date` DATE NOT NULL,
  `status` ENUM('Present', 'Absent', 'Late') NOT NULL DEFAULT 'Present',
  `remarks` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`faculty_id`) REFERENCES `faculty`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Alert Log Table
CREATE TABLE `report_alerts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `attendance_pct` DECIMAL(5,2) NOT NULL,
  `alert_type` VARCHAR(50) NOT NULL,
  `message` TEXT NOT NULL,
  `sent_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================================
-- SEED DATA INSERTION
-- ========================================================

-- Insert Departments
INSERT INTO `departments` (`id`, `dept_code`, `dept_name`) VALUES
(1, 'CS', 'Computer Science & Engineering'),
(2, 'IT', 'Information Technology'),
(3, 'AIDS', 'AI & Data Science'),
(4, 'ECE', 'Electronics & Comm. Eng.');

-- Insert Faculty
INSERT INTO `faculty` (`id`, `faculty_id`, `name`, `email`, `dept_id`, `phone`) VALUES
(1, 'FAC101', 'Dr. Robert Vance', 'robert.vance@satp.edu', 1, '+1 555-0192'),
(2, 'FAC102', 'Prof. Elena Rostova', 'elena.rostova@satp.edu', 1, '+1 555-0193'),
(3, 'FAC201', 'Dr. Marcus Sterling', 'marcus.sterling@satp.edu', 2, '+1 555-0194'),
(4, 'FAC301', 'Prof. Sarah Jenkins', 'sarah.jenkins@satp.edu', 3, '+1 555-0195'),
(5, 'FAC401', 'Dr. Alan Turing', 'alan.turing@satp.edu', 4, '+1 555-0196');

-- Insert Subjects
INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `dept_id`, `semester`, `faculty_id`) VALUES
(1, 'CS501', 'Advanced Data Structures & Algorithms', 1, 5, 1),
(2, 'CS502', 'Database Management Systems', 1, 5, 2),
(3, 'CS503', 'Operating Systems Core', 1, 5, 1),
(4, 'IT501', 'Cloud Infrastructure & Security', 2, 5, 3),
(5, 'AD501', 'Machine Learning Foundations', 3, 5, 4),
(6, 'EC501', 'Digital Signal Processing', 4, 5, 5);

-- Insert Students
INSERT INTO `students` (`id`, `roll_no`, `prn`, `name`, `email`, `phone`, `parent_phone`, `dept_id`, `semester`, `division`) VALUES
(1, 'CS2026-001', 'PRN2024001', 'Alex Mercer', 'alex.mercer@student.satp.edu', '+1 555-1001', '+1 555-9001', 1, 5, 'A'),
(2, 'CS2026-002', 'PRN2024002', 'Sophia Chen', 'sophia.chen@student.satp.edu', '+1 555-1002', '+1 555-9002', 1, 5, 'A'),
(3, 'CS2026-003', 'PRN2024003', 'David Miller', 'david.miller@student.satp.edu', '+1 555-1003', '+1 555-9003', 1, 5, 'A'),
(4, 'CS2026-004', 'PRN2024004', 'Emma Watson', 'emma.watson@student.satp.edu', '+1 555-1004', '+1 555-9004', 1, 5, 'B'),
(5, 'CS2026-005', 'PRN2024005', 'Liam Gallagher', 'liam.gallagher@student.satp.edu', '+1 555-1005', '+1 555-9005', 1, 5, 'B'),
(6, 'IT2026-010', 'PRN2024010', 'Zoe Kravitz', 'zoe.kravitz@student.satp.edu', '+1 555-1010', '+1 555-9010', 2, 5, 'A'),
(7, 'IT2026-011', 'PRN2024011', 'Lucas Scott', 'lucas.scott@student.satp.edu', '+1 555-1011', '+1 555-9011', 2, 5, 'A'),
(8, 'AD2026-020', 'PRN2024020', 'Aria Montgomery', 'aria.m@student.satp.edu', '+1 555-1020', '+1 555-9020', 3, 5, 'A'),
(9, 'AD2026-021', 'PRN2024021', 'Noah Vance', 'noah.vance@student.satp.edu', '+1 555-1021', '+1 555-9021', 3, 5, 'A'),
(10, 'EC2026-030', 'PRN2024030', 'Ethan Hunt', 'ethan.hunt@student.satp.edu', '+1 555-1030', '+1 555-9030', 4, 5, 'A');

-- Insert Sample Attendance Records across recent dates
-- Generating attendance logs for students across multiple sessions
INSERT INTO `attendance_records` (`student_id`, `subject_id`, `faculty_id`, `attendance_date`, `status`, `remarks`) VALUES
-- Alex Mercer (High Attendance ~95%)
(1, 1, 1, '2026-07-01', 'Present', 'On time'),
(1, 2, 2, '2026-07-01', 'Present', 'Active participation'),
(1, 1, 1, '2026-07-02', 'Present', 'On time'),
(1, 2, 2, '2026-07-03', 'Present', 'On time'),
(1, 1, 1, '2026-07-04', 'Present', 'On time'),
(1, 3, 1, '2026-07-05', 'Present', 'On time'),
(1, 1, 1, '2026-07-08', 'Present', 'On time'),
(1, 2, 2, '2026-07-09', 'Present', 'On time'),
(1, 1, 1, '2026-07-10', 'Present', 'On time'),
(1, 3, 1, '2026-07-11', 'Absent', 'Sick leave notified'),
(1, 1, 1, '2026-07-12', 'Present', 'On time'),
(1, 2, 2, '2026-07-15', 'Present', 'On time'),
(1, 1, 1, '2026-07-16', 'Present', 'On time'),
(1, 3, 1, '2026-07-17', 'Present', 'On time'),
(1, 1, 1, '2026-07-18', 'Present', 'On time'),
(1, 2, 2, '2026-07-19', 'Present', 'On time'),
(1, 1, 1, '2026-07-20', 'Present', 'On time'),
(1, 3, 1, '2026-07-21', 'Present', 'On time'),

-- Sophia Chen (Moderate High ~85%)
(2, 1, 1, '2026-07-01', 'Present', 'On time'),
(2, 2, 2, '2026-07-01', 'Present', 'On time'),
(2, 1, 1, '2026-07-02', 'Present', 'On time'),
(2, 2, 2, '2026-07-03', 'Absent', 'Unexcused'),
(2, 1, 1, '2026-07-04', 'Present', 'On time'),
(2, 3, 1, '2026-07-05', 'Present', 'On time'),
(2, 1, 1, '2026-07-08', 'Present', 'On time'),
(2, 2, 2, '2026-07-09', 'Absent', 'Medical'),
(2, 1, 1, '2026-07-10', 'Present', 'On time'),
(2, 3, 1, '2026-07-11', 'Present', 'On time'),
(2, 1, 1, '2026-07-12', 'Present', 'On time'),
(2, 2, 2, '2026-07-15', 'Present', 'On time'),
(2, 1, 1, '2026-07-16', 'Absent', 'Medical'),
(2, 3, 1, '2026-07-17', 'Present', 'On time'),
(2, 1, 1, '2026-07-18', 'Present', 'On time'),
(2, 2, 2, '2026-07-19', 'Present', 'On time'),

-- David Miller (Warning Low Attendance ~68%)
(3, 1, 1, '2026-07-01', 'Present', 'On time'),
(3, 2, 2, '2026-07-01', 'Absent', 'No notice'),
(3, 1, 1, '2026-07-02', 'Absent', 'No notice'),
(3, 2, 2, '2026-07-03', 'Present', 'Late 10m'),
(3, 1, 1, '2026-07-04', 'Present', 'On time'),
(3, 3, 1, '2026-07-05', 'Absent', 'No notice'),
(3, 1, 1, '2026-07-08', 'Present', 'On time'),
(3, 2, 2, '2026-07-09', 'Absent', 'Unexcused'),
(3, 1, 1, '2026-07-10', 'Present', 'On time'),
(3, 3, 1, '2026-07-11', 'Absent', 'No notice'),
(3, 1, 1, '2026-07-12', 'Present', 'On time'),
(3, 2, 2, '2026-07-15', 'Absent', 'Unexcused'),
(3, 1, 1, '2026-07-16', 'Present', 'On time'),
(3, 3, 1, '2026-07-17', 'Present', 'On time'),
(3, 1, 1, '2026-07-18', 'Absent', 'No notice'),
(3, 2, 2, '2026-07-19', 'Present', 'On time'),

-- Liam Gallagher (Critical Low Attendance ~52%)
(5, 1, 1, '2026-07-01', 'Absent', 'No notice'),
(5, 2, 2, '2026-07-01', 'Present', 'On time'),
(5, 1, 1, '2026-07-02', 'Absent', 'No notice'),
(5, 2, 2, '2026-07-03', 'Absent', 'No notice'),
(5, 1, 1, '2026-07-04', 'Present', 'On time'),
(5, 3, 1, '2026-07-05', 'Absent', 'No notice'),
(5, 1, 1, '2026-07-08', 'Absent', 'No notice'),
(5, 2, 2, '2026-07-09', 'Present', 'Late 15m'),
(5, 1, 1, '2026-07-10', 'Absent', 'No notice'),
(5, 3, 1, '2026-07-11', 'Absent', 'No notice'),
(5, 1, 1, '2026-07-12', 'Present', 'On time'),
(5, 2, 2, '2026-07-15', 'Absent', 'No notice'),
(5, 1, 1, '2026-07-16', 'Present', 'On time'),
(5, 3, 1, '2026-07-17', 'Absent', 'No notice'),

-- Zoe Kravitz (IT - High Attendance ~92%)
(6, 4, 3, '2026-07-01', 'Present', 'On time'),
(6, 4, 3, '2026-07-03', 'Present', 'On time'),
(6, 4, 3, '2026-07-05', 'Present', 'On time'),
(6, 4, 3, '2026-07-08', 'Present', 'On time'),
(6, 4, 3, '2026-07-10', 'Absent', 'Sick'),
(6, 4, 3, '2026-07-12', 'Present', 'On time'),
(6, 4, 3, '2026-07-15', 'Present', 'On time'),

-- Lucas Scott (IT - Warning ~70%)
(7, 4, 3, '2026-07-01', 'Present', 'On time'),
(7, 4, 3, '2026-07-03', 'Absent', 'Unexcused'),
(7, 4, 3, '2026-07-05', 'Present', 'On time'),
(7, 4, 3, '2026-07-08', 'Absent', 'Unexcused'),
(7, 4, 3, '2026-07-10', 'Present', 'On time'),
(7, 4, 3, '2026-07-12', 'Present', 'On time'),

-- Aria Montgomery (AIDS - High ~96%)
(8, 5, 4, '2026-07-01', 'Present', 'On time'),
(8, 5, 4, '2026-07-03', 'Present', 'On time'),
(8, 5, 4, '2026-07-05', 'Present', 'On time'),
(8, 5, 4, '2026-07-08', 'Present', 'On time'),
(8, 5, 4, '2026-07-10', 'Present', 'On time'),

-- Noah Vance (AIDS - Critical Low ~45%)
(9, 5, 4, '2026-07-01', 'Absent', 'Unexcused'),
(9, 5, 4, '2026-07-03', 'Present', 'On time'),
(9, 5, 4, '2026-07-05', 'Absent', 'Unexcused'),
(9, 5, 4, '2026-07-08', 'Absent', 'Unexcused'),
(9, 5, 4, '2026-07-10', 'Present', 'On time'),
(9, 5, 4, '2026-07-12', 'Absent', 'Unexcused');
