<?php
/**
 * Course Management AJAX Action Handler
 * Student Attendance Tracking Portal
 */

define('IS_AJAX', true);
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/csrf.php';

$action = $_POST['action'] ?? $_GET['action'] ?? $_REQUEST['action'] ?? '';

try {
    switch ($action) {

        case 'get_options':
            $departments = $pdo->query("SELECT department_id, department_name, department_code FROM departments WHERE status='active' ORDER BY department_name")->fetchAll();
            $courses = $pdo->query("SELECT course_id, course_name, course_code, department_id FROM courses WHERE status='active' ORDER BY course_name")->fetchAll();
            echo json_encode(['status' => 'success', 'departments' => $departments, 'courses' => $courses]);
            break;

        case 'list':
            $dept_id = (int)($_GET['department_id'] ?? $_POST['department_id'] ?? 0);
            
            $sql = "SELECT c.*, d.department_name, d.department_code 
                    FROM courses c 
                    JOIN departments d ON c.department_id = d.department_id";
            $params = [];

            if ($dept_id) {
                $sql .= " WHERE c.department_id = ?";
                $params[] = $dept_id;
            }

            $sql .= " ORDER BY c.course_id DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $courses = $stmt->fetchAll();

            echo json_encode(['status' => 'success', 'data' => $courses]);
            break;

        case 'get':
            $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Course ID.']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT * FROM courses WHERE course_id = ?");
            $stmt->execute([$id]);
            $course = $stmt->fetch();
            if ($course) {
                echo json_encode(['status' => 'success', 'data' => $course]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Course not found.']);
            }
            break;

        case 'save':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $course_id       = !empty($_POST['course_id']) ? (int)$_POST['course_id'] : null;
            $course_name     = trim($_POST['course_name'] ?? '');
            $course_code     = trim($_POST['course_code'] ?? '');
            $department_id   = (int)($_POST['department_id'] ?? 0);
            $duration_years  = (int)($_POST['duration_years'] ?? 4);
            $total_semesters = (int)($_POST['total_semesters'] ?? 8);
            $status          = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

            if (empty($course_name)) {
                echo json_encode(['status' => 'error', 'message' => 'Course Name is required.']);
                exit;
            }
            if (empty($course_code)) {
                echo json_encode(['status' => 'error', 'message' => 'Course Code is required.']);
                exit;
            }
            if (!$department_id) {
                echo json_encode(['status' => 'error', 'message' => 'Please select a valid Department.']);
                exit;
            }
            if (!$duration_years || $duration_years < 1 || $duration_years > 10) {
                echo json_encode(['status' => 'error', 'message' => 'Duration must be between 1 and 10 years.']);
                exit;
            }
            if (!$total_semesters || $total_semesters < 1 || $total_semesters > 20) {
                echo json_encode(['status' => 'error', 'message' => 'Total semesters must be between 1 and 20.']);
                exit;
            }

            // Check unique course code
            if ($course_id) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE course_code = ? AND course_id != ?");
                $stmt->execute([$course_code, $course_id]);
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE course_code = ?");
                $stmt->execute([$course_code]);
            }
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Course Code already exists.']);
                exit;
            }

            if ($course_id) {
                $stmt = $pdo->prepare("UPDATE courses SET course_name = ?, course_code = ?, department_id = ?, duration_years = ?, total_semesters = ?, status = ? WHERE course_id = ?");
                $stmt->execute([$course_name, $course_code, $department_id, $duration_years, $total_semesters, $status, $course_id]);
                $msg = 'Course updated successfully.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO courses (course_name, course_code, department_id, duration_years, total_semesters, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$course_name, $course_code, $department_id, $duration_years, $total_semesters, $status]);
                $newCourseId = $pdo->lastInsertId();

                // Auto-create semester records for current active academic year
                $currentAyId = $pdo->query("SELECT academic_year_id FROM academic_years WHERE is_current = 1 LIMIT 1")->fetchColumn() ?: 1;
                $semIns = $pdo->prepare("INSERT INTO semesters (course_id, academic_year_id, semester_number, status) VALUES (?, ?, ?, 'active')");
                for ($i = 1; $i <= $total_semesters; $i++) {
                    $semIns->execute([$newCourseId, $currentAyId, $i]);
                }

                $msg = 'Course added successfully along with semester configurations.';
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
                echo json_encode(['status' => 'error', 'message' => 'Invalid Course ID.']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT status FROM courses WHERE course_id = ?");
            $stmt->execute([$id]);
            $currentStatus = $stmt->fetchColumn();
            if (!$currentStatus) {
                echo json_encode(['status' => 'error', 'message' => 'Course not found.']);
                exit;
            }
            $newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';
            $update = $pdo->prepare("UPDATE courses SET status = ? WHERE course_id = ?");
            $update->execute([$newStatus, $id]);

            echo json_encode(['status' => 'success', 'message' => "Course status changed to {$newStatus}.", 'new_status' => $newStatus]);
            break;

        case 'delete':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }
            $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Course ID.']);
                exit;
            }

            try {
                $pdo->beginTransaction();

                // Clean up dependent child records
                $pdo->prepare("DELETE FROM subject_allocations WHERE course_id = ?")->execute([$id]);
                $pdo->prepare("DELETE FROM subjects WHERE course_id = ?")->execute([$id]);
                $pdo->prepare("DELETE FROM divisions WHERE semester_id IN (SELECT semester_id FROM semesters WHERE course_id = ?)")->execute([$id]);
                $pdo->prepare("DELETE FROM semesters WHERE course_id = ?")->execute([$id]);

                // Delete target course
                $delStmt = $pdo->prepare("DELETE FROM courses WHERE course_id = ?");
                $delStmt->execute([$id]);

                $pdo->commit();
                echo json_encode(['status' => 'success', 'message' => 'Course deleted successfully.']);
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
            }
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
