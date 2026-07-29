<?php
/**
 * Subject Allocation Action Handler
 * Student Attendance Tracking Portal
 */
header('Content-Type: application/json');
define('IS_AJAX', true);

require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/csrf.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            $faculty_id = $_GET['faculty_id'] ?? '';
            $dept_id = $_GET['department_id'] ?? '';
            $ay_id = $_GET['academic_year_id'] ?? '';
            $session_id = $_GET['session_id'] ?? '';

            $sql = "SELECT sa.*, f.full_name as faculty_name, f.employee_id, f.email as faculty_email,
                           s.subject_name, s.subject_code, s.credits,
                           c.course_name, c.course_code,
                           d.department_name, d.department_code,
                           dv.division_name,
                           sess.session_name,
                           ay.year_label
                    FROM subject_allocations sa
                    JOIN faculties f ON sa.faculty_id = f.faculty_id
                    JOIN subjects s ON sa.subject_id = s.subject_id
                    JOIN courses c ON sa.course_id = c.course_id
                    JOIN departments d ON s.department_id = d.department_id
                    JOIN divisions dv ON sa.division_id = dv.division_id
                    JOIN sessions sess ON sa.session_id = sess.session_id
                    JOIN academic_years ay ON sa.academic_year_id = ay.academic_year_id
                    WHERE 1=1";
            $params = [];

            if (!empty($faculty_id)) {
                $sql .= " AND sa.faculty_id = ?";
                $params[] = $faculty_id;
            }
            if (!empty($dept_id)) {
                $sql .= " AND s.department_id = ?";
                $params[] = $dept_id;
            }
            if (!empty($ay_id)) {
                $sql .= " AND sa.academic_year_id = ?";
                $params[] = $ay_id;
            }
            if (!empty($session_id)) {
                $sql .= " AND sa.session_id = ?";
                $params[] = $session_id;
            }

            $sql .= " ORDER BY sa.allocation_id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $allocations = $stmt->fetchAll();

            echo json_encode(['status' => 'success', 'data' => $allocations]);
            break;

        case 'get_options':
            $departments = $pdo->query("SELECT department_id, department_name, department_code FROM departments WHERE status='active' ORDER BY department_name")->fetchAll();
            $courses = $pdo->query("SELECT course_id, course_name, course_code, department_id FROM courses WHERE status='active' ORDER BY course_name")->fetchAll();
            $subjects = $pdo->query("SELECT subject_id, subject_name, subject_code, department_id, course_id, semester_number FROM subjects WHERE status='active' ORDER BY subject_name")->fetchAll();
            $faculties = $pdo->query("SELECT faculty_id, full_name, employee_id, department_id FROM faculties WHERE status='active' ORDER BY full_name")->fetchAll();
            $divisions = $pdo->query("SELECT division_id, division_name, semester_id FROM divisions WHERE status='active' ORDER BY division_name")->fetchAll();
            $sessions = $pdo->query("SELECT session_id, session_name FROM sessions WHERE status='active' ORDER BY session_id")->fetchAll();
            $academic_years = $pdo->query("SELECT academic_year_id, year_label, is_current FROM academic_years WHERE status='active' ORDER BY academic_year_id DESC")->fetchAll();

            echo json_encode([
                'status' => 'success',
                'departments' => $departments,
                'courses' => $courses,
                'subjects' => $subjects,
                'faculties' => $faculties,
                'divisions' => $divisions,
                'sessions' => $sessions,
                'academic_years' => $academic_years
            ]);
            break;

        case 'save':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }
            $allocation_id = !empty($_POST['allocation_id']) ? (int)$_POST['allocation_id'] : null;
            $faculty_id = (int)($_POST['faculty_id'] ?? 0);
            $subject_id = (int)($_POST['subject_id'] ?? 0);
            $course_id = (int)($_POST['course_id'] ?? 0);
            $semester_number = (int)($_POST['semester_number'] ?? 1);
            $division_id = (int)($_POST['division_id'] ?? 0);
            $session_id = (int)($_POST['session_id'] ?? 0);
            $academic_year_id = (int)($_POST['academic_year_id'] ?? 0);
            $status = $_POST['status'] ?? 'active';

            if (!$faculty_id || !$subject_id || !$course_id || !$division_id || !$session_id || !$academic_year_id) {
                echo json_encode(['status' => 'error', 'message' => 'Please select all required allocation fields.']);
                exit;
            }

            // Check Duplicate Allocation (Same subject + division + session + academic_year)
            $checkSql = "SELECT allocation_id FROM subject_allocations 
                         WHERE subject_id = ? AND division_id = ? AND session_id = ? AND academic_year_id = ? AND allocation_id != ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$subject_id, $division_id, $session_id, $academic_year_id, $allocation_id ?? 0]);
            if ($checkStmt->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'Duplicate Allocation Detected! This Subject is already allocated for the selected Division, Session, and Academic Year.']);
                exit;
            }

            if ($allocation_id) {
                $sql = "UPDATE subject_allocations SET 
                        faculty_id = ?, subject_id = ?, course_id = ?, semester_number = ?, division_id = ?, session_id = ?, academic_year_id = ?, status = ?
                        WHERE allocation_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$faculty_id, $subject_id, $course_id, $semester_number, $division_id, $session_id, $academic_year_id, $status, $allocation_id]);
                echo json_encode(['status' => 'success', 'message' => 'Subject Allocation updated successfully!']);
            } else {
                $sql = "INSERT INTO subject_allocations (faculty_id, subject_id, course_id, semester_number, division_id, session_id, academic_year_id, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$faculty_id, $subject_id, $course_id, $semester_number, $division_id, $session_id, $academic_year_id, $status]);
                echo json_encode(['status' => 'success', 'message' => 'Subject Allocated successfully!']);
            }
            break;

        case 'delete':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("DELETE FROM subject_allocations WHERE allocation_id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Subject Allocation deleted successfully!']);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
