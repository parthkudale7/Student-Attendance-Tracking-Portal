-- MariaDB / MySQL Database Dump
-- Host: localhost    Database: attendance_db
-- Target Server: XAMPP Server

CREATE DATABASE IF NOT EXISTS `attendance_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `attendance_db`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `attendance_records`;
DROP TABLE IF EXISTS `attendance_sessions`;
DROP TABLE IF EXISTS `subject_allocations`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `faculties`;
DROP TABLE IF EXISTS `subjects`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `divisions`;
DROP TABLE IF EXISTS `semesters`;
DROP TABLE IF EXISTS `academic_years`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) NOT NULL,
  `department_code` varchar(20) NOT NULL,
  `hod_name` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `department_name` (`department_name`),
  UNIQUE KEY `department_code` (`department_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `departments` VALUES 
(1,'Computer Science & Engineering','CSE','Dr. Alan Turing','active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(2,'Information Technology','IT','Dr. Grace Hopper','active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(3,'Electronics & Telecommunication','ENTC','Dr. Claude Shannon','active','2026-07-27 05:25:35','2026-07-27 05:25:35');

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(100) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `department_id` int(11) NOT NULL,
  `duration_years` int(11) NOT NULL DEFAULT 4,
  `total_semesters` int(11) NOT NULL DEFAULT 8,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `course_code` (`course_code`),
  KEY `fk_courses_department` (`department_id`),
  CONSTRAINT `fk_courses_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `courses` VALUES 
(1,'B.Tech Computer Science','BTECH-CSE',1,4,8,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(2,'M.Tech Computer Science','MTECH-CSE',1,2,4,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(3,'B.Tech Information Technology','BTECH-IT',2,4,8,'active','2026-07-27 05:25:35','2026-07-27 05:25:35');

CREATE TABLE `academic_years` (
  `academic_year_id` int(11) NOT NULL AUTO_INCREMENT,
  `year_label` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`academic_year_id`),
  UNIQUE KEY `year_label` (`year_label`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `academic_years` VALUES 
(1,'2025-2026','2025-06-01','2026-05-31',0,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(2,'2026-2027','2026-06-01','2027-05-31',1,'active','2026-07-27 05:25:35','2026-07-27 05:25:35');

CREATE TABLE `semesters` (
  `semester_id` int(11) NOT NULL AUTO_INCREMENT,
  `semester_number` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`semester_id`),
  UNIQUE KEY `uk_course_academic_semester` (`course_id`,`academic_year_id`,`semester_number`),
  KEY `fk_semesters_academic_year` (`academic_year_id`),
  CONSTRAINT `fk_semesters_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`academic_year_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_semesters_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `semesters` VALUES 
(1,1,1,2,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(2,2,1,2,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(3,3,1,2,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(4,1,3,2,'active','2026-07-27 05:25:35','2026-07-27 05:25:35');

CREATE TABLE `divisions` (
  `division_id` int(11) NOT NULL AUTO_INCREMENT,
  `division_name` varchar(10) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 60,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`division_id`),
  UNIQUE KEY `uk_semester_division` (`semester_id`,`division_name`),
  CONSTRAINT `fk_divisions_semester` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`semester_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `divisions` VALUES 
(1,'A',1,60,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(2,'B',1,60,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(3,'A',2,65,'active','2026-07-27 05:25:35','2026-07-27 05:25:35');

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_name` varchar(50) NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `session_name` (`session_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` VALUES 
(1,'Morning Session','08:00:00','12:00:00','active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(2,'Afternoon Session','12:30:00','16:30:00','active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(3,'Evening Session','17:00:00','21:00:00','active','2026-07-27 05:25:35','2026-07-27 05:25:35');

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(100) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `department_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester_number` int(11) NOT NULL,
  `credits` int(11) NOT NULL DEFAULT 3,
  `subject_type` enum('Theory','Practical','Both') NOT NULL DEFAULT 'Theory',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`subject_id`),
  UNIQUE KEY `uk_course_semester_subject_code` (`course_id`,`semester_number`,`subject_code`),
  KEY `fk_subjects_department` (`department_id`),
  CONSTRAINT `fk_subjects_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_subjects_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `subjects` VALUES 
(1,'Data Structures & Algorithms','CS101',1,1,1,4,'Both','active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(2,'Database Management Systems','CS102',1,1,1,4,'Both','active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(3,'Object Oriented Programming','IT101',2,3,1,3,'Theory','active','2026-07-27 05:25:35','2026-07-27 05:25:35');

CREATE TABLE `faculties` (
  `faculty_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(30) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `department_id` int(11) NOT NULL,
  `designation` varchar(50) NOT NULL DEFAULT 'Assistant Professor',
  `qualification` varchar(100) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `experience_years` int(11) NOT NULL DEFAULT 3,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`faculty_id`),
  UNIQUE KEY `employee_id` (`employee_id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_faculties_department` (`department_id`),
  CONSTRAINT `fk_faculties_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `faculties` VALUES 
(1,'FAC-1001','Dr. Rahul Sharma','rahul.sharma@university.edu','+91 9876543210',1,'Professor','Ph.D in Computer Science','2018-07-15','faculty_1.jpg',8,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(2,'FAC-1002','Dr. Priya Mehta','priya.mehta@university.edu','+91 9876543211',2,'Associate Professor','Ph.D in Information Technology','2019-08-10','faculty_2.jpg',6,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(3,'FAC-1003','Prof. Amit Patil','amit.patil@university.edu','+91 9876543212',3,'Assistant Professor','M.Tech in Electronics','2021-01-20','faculty_3.jpg',4,'active','2026-07-27 05:25:35','2026-07-27 05:25:35');

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `roll_number` varchar(30) NOT NULL,
  `registration_number` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL DEFAULT 'Male',
  `dob` date DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `division_id` int(11) DEFAULT NULL,
  `academic_year_id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `roll_number` (`roll_number`),
  UNIQUE KEY `registration_number` (`registration_number`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_students_department` (`department_id`),
  KEY `fk_students_course` (`course_id`),
  KEY `fk_students_academic_year` (`academic_year_id`),
  CONSTRAINT `fk_students_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`academic_year_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_students_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_students_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `students` VALUES 
(1,'2026-CSE-001','REG-2026-001','Aarav Patel','aarav.patel@student.edu','+91 9876500001','Male','2004-05-12',1,1,1,1,2,NULL,'active','2026-07-28 09:00:00','2026-07-28 09:00:00'),
(2,'2026-CSE-002','REG-2026-002','Ananya Roy','ananya.roy@student.edu','+91 9876500002','Female','2004-08-23',1,1,1,1,2,NULL,'active','2026-07-28 09:00:00','2026-07-28 09:00:00');

CREATE TABLE `subject_allocations` (
  `allocation_id` int(11) NOT NULL AUTO_INCREMENT,
  `faculty_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester_number` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `academic_year_id` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`allocation_id`),
  UNIQUE KEY `uk_unique_allocation` (`subject_id`,`division_id`,`session_id`,`academic_year_id`),
  KEY `fk_alloc_faculty` (`faculty_id`),
  KEY `fk_alloc_course` (`course_id`),
  KEY `fk_alloc_division` (`division_id`),
  KEY `fk_alloc_session` (`session_id`),
  KEY `fk_alloc_academic_year` (`academic_year_id`),
  CONSTRAINT `fk_alloc_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`academic_year_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alloc_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alloc_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`division_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alloc_faculty` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alloc_session` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`session_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_alloc_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `subject_allocations` VALUES 
(1,1,1,1,1,1,1,2,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(2,2,2,1,1,2,2,2,'active','2026-07-27 05:25:35','2026-07-27 05:25:35'),
(3,3,3,3,1,3,1,2,'active','2026-07-27 05:25:35','2026-07-27 05:25:35');

CREATE TABLE `attendance_sessions` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `allocation_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `recorded_by_faculty_id` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`attendance_id`),
  KEY `fk_attendance_alloc` (`allocation_id`),
  KEY `fk_attendance_faculty` (`recorded_by_faculty_id`),
  CONSTRAINT `fk_attendance_alloc` FOREIGN KEY (`allocation_id`) REFERENCES `subject_allocations` (`allocation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_attendance_faculty` FOREIGN KEY (`recorded_by_faculty_id`) REFERENCES `faculties` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `attendance_records` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` enum('present','absent','late','excused') NOT NULL DEFAULT 'present',
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`record_id`),
  UNIQUE KEY `uk_attendance_student` (`attendance_id`,`student_id`),
  KEY `fk_record_student` (`student_id`),
  CONSTRAINT `fk_record_session` FOREIGN KEY (`attendance_id`) REFERENCES `attendance_sessions` (`attendance_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_record_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'Super Admin',
  `phone` varchar(20) DEFAULT '+1 (555) 019-2834',
  `designation` varchar(100) DEFAULT 'System Administrator',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES 
(1,'admin','Super Admin','admin@university.edu','$2y$10$e8w.x.9N3wU8H0e6L6vG1O.N/54gD.e4sL9W9V5u.5kQ8lO.Q5N1u','Super Admin','+1 (555) 019-2834','System Administrator','2026-07-27 05:25:35','2026-07-27 05:25:35');

SET FOREIGN_KEY_CHECKS = 1;
