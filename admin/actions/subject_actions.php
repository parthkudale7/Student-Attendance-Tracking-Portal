<?php
/**
 * Subject Management AJAX Action Handler
 * Student Attendance Tracking Portal
 */

define('IS_AJAX', true);
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/csrf.php';

$action = $_REQUEST['action'] ?? '';

try {
    switch ($action) {

        case 'list':
            $dept_id = (int)($_GET['department_id'] ?? $_POST['department_id'] ?? 0);
            $course_id = (int)($_GET['course_id'] ?? $_POST['course_id'] ?? 0);

            $sql = "SELECT s.*, d.department_name, d.department_code, c.course_name, c.course_code 
                    FROM subjects s 
                    JOIN departments d ON s.department_id = d.department_id 
                    JOIN courses c ON s.course_id = c.course_id";
            $conditions = [];
            $params = [];

            if ($dept_id) {
                $conditions[] = "s.department_id = ?";
                $params[] = $dept_id;
            }
            if ($course_id) {
                $conditions[] = "s.course_id = ?";
                $params[] = $course_id;
            }

            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }

            $sql .= " ORDER BY s.subject_id DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $subjects = $stmt->fetchAll();

            echo json_encode(['status' => 'success', 'data' => $subjects]);
            break;

        case 'get':
            $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Subject ID.']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT * FROM subjects WHERE subject_id = ?");
            $stmt->execute([$id]);
            $subject = $stmt->fetch();
            if ($subject) {
                echo json_encode(['status' => 'success', 'data' => $subject]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Subject not found.']);
            }
            break;

        case 'get_courses_by_dept':
            $dept_id = (int)($_GET['department_id'] ?? $_POST['department_id'] ?? 0);
            if (!$dept_id) {
                echo json_encode(['status' => 'success', 'data' => []]);
                exit;
            }
            $stmt = $pdo->prepare("SELECT course_id, course_name, course_code, total_semesters FROM courses WHERE department_id = ? AND status = 'active' ORDER BY course_name ASC");
            $stmt->execute([$dept_id]);
            $courses = $stmt->fetchAll();

            echo json_encode(['status' => 'success', 'data' => $courses]);
            break;

        case 'get_semesters_by_course':
            $course_id = (int)($_GET['course_id'] ?? $_POST['course_id'] ?? 0);
            if (!$course_id) {
                echo json_encode(['status' => 'success', 'data' => []]);
                exit;
            }
            $stmt = $pdo->prepare("SELECT total_semesters FROM courses WHERE course_id = ?");
            $stmt->execute([$course_id]);
            $totalSemesters = $stmt->fetchColumn() ?: 8;

            $semesters = [];
            for ($i = 1; $i <= $totalSemesters; $i++) {
                $semesters[] = [
                    'semester_number' => $i,
                    'label' => "Semester " . $i
                ];
            }

            echo json_encode(['status' => 'success', 'total' => $totalSemesters, 'data' => $semesters]);
            break;

        case 'save':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $subject_id      = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;
            $subject_name    = trim($_POST['subject_name'] ?? '');
            $subject_code    = trim($_POST['subject_code'] ?? '');
            $department_id  = (int)($_POST['department_id'] ?? 0);
            $course_id      = (int)($_POST['course_id'] ?? 0);
            $semester_number = (int)($_POST['semester_number'] ?? 1);
            $credits         = (int)($_POST['credits'] ?? 3);
            $subject_type    = in_array($_POST['subject_type'] ?? '', ['Theory', 'Practical', 'Both']) ? $_POST['subject_type'] : 'Theory';
            $status          = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

            if (empty($subject_name)) {
                echo json_encode(['status' => 'error', 'message' => 'Subject Name is required.']);
                exit;
            }
            if (empty($subject_code)) {
                echo json_encode(['status' => 'error', 'message' => 'Subject Code is required.']);
                exit;
            }
            if (!$department_id) {
                echo json_encode(['status' => 'error', 'message' => 'Department selection is required.']);
                exit;
            }
            if (!$course_id) {
                echo json_encode(['status' => 'error', 'message' => 'Course selection is required.']);
                exit;
            }
            if (!$semester_number || $semester_number < 1) {
                echo json_encode(['status' => 'error', 'message' => 'Valid Semester Number is required.']);
                exit;
            }
            if (!$credits || $credits < 1) {
                echo json_encode(['status' => 'error', 'message' => 'Credits must be at least 1.']);
                exit;
            }

            // Check unique subject_code within course + semester
            if ($subject_id) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM subjects WHERE course_id = ? AND semester_number = ? AND subject_code = ? AND subject_id != ?");
                $stmt->execute([$course_id, $semester_number, $subject_code, $subject_id]);
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM subjects WHERE course_id = ? AND semester_number = ? AND subject_code = ?");
                $stmt->execute([$course_id, $semester_number, $subject_code]);
            }
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Subject Code already exists for this course and semester.']);
                exit;
            }

            if ($subject_id) {
                $stmt = $pdo->prepare("UPDATE subjects SET subject_name = ?, subject_code = ?, department_id = ?, course_id = ?, semester_number = ?, credits = ?, subject_type = ?, status = ? WHERE subject_id = ?");
                $stmt->execute([$subject_name, $subject_code, $department_id, $course_id, $semester_number, $credits, $subject_type, $status, $subject_id]);
                $msg = 'Subject updated successfully.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO subjects (subject_name, subject_code, department_id, course_id, semester_number, credits, subject_type, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$subject_name, $subject_code, $department_id, $course_id, $semester_number, $credits, $subject_type, $status]);
                $msg = 'Subject added successfully.';
            }

            echo json_encode(['status' => 'success', 'message' => $msg]);
            break;

        case 'toggle_status':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }
            $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Subject ID.']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT status FROM subjects WHERE subject_id = ?");
            $stmt->execute([$id]);
            $currentStatus = $stmt->fetchColumn();
            if (!$currentStatus) {
                echo json_encode(['status' => 'error', 'message' => 'Subject not found.']);
                exit;
            }
            $newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';
            $update = $pdo->prepare("UPDATE subjects SET status = ? WHERE subject_id = ?");
            $update->execute([$newStatus, $id]);

            echo json_encode(['status' => 'success', 'message' => "Subject status changed to {$newStatus}.", 'new_status' => $newStatus]);
            break;

        case 'delete':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }
            $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Subject ID.']);
                exit;
            }

            $delStmt = $pdo->prepare("DELETE FROM subjects WHERE subject_id = ?");
            $delStmt->execute([$id]);

            echo json_encode(['status' => 'success', 'message' => 'Subject deleted successfully.']);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
