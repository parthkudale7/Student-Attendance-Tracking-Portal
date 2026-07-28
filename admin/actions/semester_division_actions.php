<?php
/**
 * Semester, Division & Session Management AJAX Action Handler
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

        case 'get_options':
            $courses = $pdo->query("SELECT course_id, course_name, course_code FROM courses WHERE status='active' ORDER BY course_name")->fetchAll();
            $academic_years = $pdo->query("SELECT academic_year_id, year_label, is_current FROM academic_years WHERE status='active' ORDER BY academic_year_id DESC")->fetchAll();
            $semesters = $pdo->query("SELECT s.semester_id, s.semester_number, c.course_code FROM semesters s JOIN courses c ON s.course_id = c.course_id WHERE s.status='active' ORDER BY s.semester_id DESC")->fetchAll();

            echo json_encode([
                'status' => 'success',
                'courses' => $courses,
                'academic_years' => $academic_years,
                'semesters' => $semesters
            ]);
            break;

        /* ==================== SEMESTER ACTIONS ==================== */

        case 'list_semesters':
            $course_id = (int)($_GET['course_id'] ?? $_POST['course_id'] ?? 0);
            $year_id   = (int)($_GET['academic_year_id'] ?? $_POST['academic_year_id'] ?? 0);

            $sql = "SELECT s.*, c.course_name, c.course_code, c.total_semesters, ay.year_label, ay.is_current,
                           (SELECT COUNT(*) FROM divisions d WHERE d.semester_id = s.semester_id) as division_count
                    FROM semesters s
                    JOIN courses c ON s.course_id = c.course_id
                    JOIN academic_years ay ON s.academic_year_id = ay.academic_year_id";
            $conditions = [];
            $params = [];

            if ($course_id) {
                $conditions[] = "s.course_id = ?";
                $params[] = $course_id;
            }
            if ($year_id) {
                $conditions[] = "s.academic_year_id = ?";
                $params[] = $year_id;
            }

            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }

            $sql .= " ORDER BY s.semester_id DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $semesters = $stmt->fetchAll();

            echo json_encode(['status' => 'success', 'data' => $semesters]);
            break;

        case 'get_semester':
            $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Semester ID.']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT * FROM semesters WHERE semester_id = ?");
            $stmt->execute([$id]);
            $sem = $stmt->fetch();
            if ($sem) {
                echo json_encode(['status' => 'success', 'data' => $sem]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Semester not found.']);
            }
            break;

        case 'save_semester':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $semester_id      = !empty($_POST['semester_id']) ? (int)$_POST['semester_id'] : null;
            $course_id        = (int)($_POST['course_id'] ?? 0);
            $academic_year_id = (int)($_POST['academic_year_id'] ?? 0);
            $semester_number  = (int)($_POST['semester_number'] ?? 1);
            $status           = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

            if (!$course_id || !$academic_year_id || !$semester_number) {
                echo json_encode(['status' => 'error', 'message' => 'Please select Course, Academic Year, and Semester Number.']);
                exit;
            }

            // Check duplicate semester mapping
            if ($semester_id) {
                $dupCheck = $pdo->prepare("SELECT COUNT(*) FROM semesters WHERE course_id = ? AND academic_year_id = ? AND semester_number = ? AND semester_id != ?");
                $dupCheck->execute([$course_id, $academic_year_id, $semester_number, $semester_id]);
            } else {
                $dupCheck = $pdo->prepare("SELECT COUNT(*) FROM semesters WHERE course_id = ? AND academic_year_id = ? AND semester_number = ?");
                $dupCheck->execute([$course_id, $academic_year_id, $semester_number]);
            }
            if ($dupCheck->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => "Semester {$semester_number} is already configured for this course and academic year."]);
                exit;
            }

            if ($semester_id) {
                $stmt = $pdo->prepare("UPDATE semesters SET course_id = ?, academic_year_id = ?, semester_number = ?, status = ? WHERE semester_id = ?");
                $stmt->execute([$course_id, $academic_year_id, $semester_number, $status, $semester_id]);
                $msg = 'Semester updated successfully.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO semesters (course_id, academic_year_id, semester_number, status) VALUES (?, ?, ?, ?)");
                $stmt->execute([$course_id, $academic_year_id, $semester_number, $status]);
                $msg = 'Semester configured successfully.';
            }

            echo json_encode(['status' => 'success', 'message' => $msg]);
            break;

        case 'delete_semester':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
            
            // Check linked divisions
            $divCheck = $pdo->prepare("SELECT COUNT(*) FROM divisions WHERE semester_id = ?");
            $divCheck->execute([$id]);
            if ($divCheck->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Cannot delete semester: Divisions are linked to this semester. Delete linked divisions first.']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM semesters WHERE semester_id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Semester deleted successfully.']);
            break;


        /* ==================== DIVISION ACTIONS ==================== */

        case 'list_divisions':
            $sql = "SELECT d.*, s.semester_number, c.course_code 
                    FROM divisions d 
                    JOIN semesters s ON d.semester_id = s.semester_id 
                    JOIN courses c ON s.course_id = c.course_id 
                    ORDER BY d.division_id DESC";
            $stmt = $pdo->query($sql);
            $divisions = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'data' => $divisions]);
            break;

        case 'get_division':
            $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Division ID.']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT * FROM divisions WHERE division_id = ?");
            $stmt->execute([$id]);
            $div = $stmt->fetch();
            if ($div) {
                echo json_encode(['status' => 'success', 'data' => $div]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Division not found.']);
            }
            break;

        case 'save_division':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $division_id   = !empty($_POST['division_id']) ? (int)$_POST['division_id'] : null;
            $semester_id   = (int)($_POST['semester_id'] ?? 0);
            $division_name = strtoupper(trim($_POST['division_name'] ?? ''));
            $capacity      = (int)($_POST['capacity'] ?? 60);
            $status        = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

            if (!$semester_id || empty($division_name)) {
                echo json_encode(['status' => 'error', 'message' => 'Semester selection and Division Name are required.']);
                exit;
            }

            // Check duplicate division
            if ($division_id) {
                $dupCheck = $pdo->prepare("SELECT COUNT(*) FROM divisions WHERE semester_id = ? AND division_name = ? AND division_id != ?");
                $dupCheck->execute([$semester_id, $division_name, $division_id]);
            } else {
                $dupCheck = $pdo->prepare("SELECT COUNT(*) FROM divisions WHERE semester_id = ? AND division_name = ?");
                $dupCheck->execute([$semester_id, $division_name]);
            }
            if ($dupCheck->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => "Division '{$division_name}' already exists for the selected semester."]);
                exit;
            }

            if ($division_id) {
                $stmt = $pdo->prepare("UPDATE divisions SET semester_id = ?, division_name = ?, capacity = ?, status = ? WHERE division_id = ?");
                $stmt->execute([$semester_id, $division_name, $capacity, $status, $division_id]);
                $msg = 'Division updated successfully.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO divisions (semester_id, division_name, capacity, status) VALUES (?, ?, ?, ?)");
                $stmt->execute([$semester_id, $division_name, $capacity, $status]);
                $msg = 'Division added successfully.';
            }

            echo json_encode(['status' => 'success', 'message' => $msg]);
            break;

        case 'delete_division':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
            $stmt = $pdo->prepare("DELETE FROM divisions WHERE division_id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Division deleted successfully.']);
            break;


        /* ==================== SESSION ACTIONS ==================== */

        case 'list_sessions':
            $stmt = $pdo->query("SELECT * FROM sessions ORDER BY session_id ASC");
            $sessions = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'data' => $sessions]);
            break;

        case 'get_session':
            $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Session ID.']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT * FROM sessions WHERE session_id = ?");
            $stmt->execute([$id]);
            $sess = $stmt->fetch();
            if ($sess) {
                echo json_encode(['status' => 'success', 'data' => $sess]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Session not found.']);
            }
            break;

        case 'save_session':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $session_id   = !empty($_POST['session_id']) ? (int)$_POST['session_id'] : null;
            $session_name = trim($_POST['session_name'] ?? '');
            $start_time   = !empty($_POST['start_time']) ? $_POST['start_time'] : null;
            $end_time     = !empty($_POST['end_time']) ? $_POST['end_time'] : null;
            $status       = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

            if (empty($session_name)) {
                echo json_encode(['status' => 'error', 'message' => 'Session name is required.']);
                exit;
            }

            if ($session_id) {
                $dupCheck = $pdo->prepare("SELECT COUNT(*) FROM sessions WHERE session_name = ? AND session_id != ?");
                $dupCheck->execute([$session_name, $session_id]);
            } else {
                $dupCheck = $pdo->prepare("SELECT COUNT(*) FROM sessions WHERE session_name = ?");
                $dupCheck->execute([$session_name]);
            }
            if ($dupCheck->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => "Session name '{$session_name}' already exists."]);
                exit;
            }

            if ($session_id) {
                $stmt = $pdo->prepare("UPDATE sessions SET session_name = ?, start_time = ?, end_time = ?, status = ? WHERE session_id = ?");
                $stmt->execute([$session_name, $start_time, $end_time, $status, $session_id]);
                $msg = 'Session updated successfully.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO sessions (session_name, start_time, end_time, status) VALUES (?, ?, ?, ?)");
                $stmt->execute([$session_name, $start_time, $end_time, $status]);
                $msg = 'Session added successfully.';
            }

            echo json_encode(['status' => 'success', 'message' => $msg]);
            break;

        case 'delete_session':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
            $stmt = $pdo->prepare("DELETE FROM sessions WHERE session_id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Session deleted successfully.']);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);
            break;
    }
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'error', 'message' => 'This configuration record already exists or is linked to another master item.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
