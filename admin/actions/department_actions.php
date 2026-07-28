<?php
/**
 * Department Management AJAX Action Handler
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

        case 'list':
            $stmt = $pdo->query("SELECT d.*, 
                    (SELECT COUNT(*) FROM courses c WHERE c.department_id = d.department_id) as total_courses,
                    (SELECT COUNT(*) FROM faculties f WHERE f.department_id = d.department_id) as total_faculties
                    FROM departments d ORDER BY d.department_id DESC");
            $departments = $stmt->fetchAll();
            echo json_encode([
                'status' => 'success',
                'data' => $departments
            ]);
            break;

        case 'get':
            $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Department ID.']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT * FROM departments WHERE department_id = ?");
            $stmt->execute([$id]);
            $dept = $stmt->fetch();
            if ($dept) {
                echo json_encode(['status' => 'success', 'data' => $dept]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Department not found.']);
            }
            break;

        case 'save':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF). Please refresh and try again.']);
                exit;
            }

            $department_id   = !empty($_POST['department_id']) ? (int)$_POST['department_id'] : null;
            $department_name = trim($_POST['department_name'] ?? '');
            $department_code = trim($_POST['department_code'] ?? '');
            $hod_name        = trim($_POST['hod_name'] ?? '');
            $status          = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

            // Server-side validations
            if (empty($department_name)) {
                echo json_encode(['status' => 'error', 'message' => 'Department Name is required.']);
                exit;
            }
            if (empty($department_code)) {
                echo json_encode(['status' => 'error', 'message' => 'Department Code is required.']);
                exit;
            }

            // Check unique name
            if ($department_id) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM departments WHERE department_name = ? AND department_id != ?");
                $stmt->execute([$department_name, $department_id]);
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM departments WHERE department_name = ?");
                $stmt->execute([$department_name]);
            }
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Department Name already exists.']);
                exit;
            }

            // Check unique code
            if ($department_id) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM departments WHERE department_code = ? AND department_id != ?");
                $stmt->execute([$department_code, $department_id]);
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM departments WHERE department_code = ?");
                $stmt->execute([$department_code]);
            }
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Department Code already exists.']);
                exit;
            }

            if ($department_id) {
                // Update
                $stmt = $pdo->prepare("UPDATE departments SET department_name = ?, department_code = ?, hod_name = ?, status = ? WHERE department_id = ?");
                $stmt->execute([$department_name, $department_code, $hod_name, $status, $department_id]);
                $msg = 'Department updated successfully.';
            } else {
                // Insert
                $stmt = $pdo->prepare("INSERT INTO departments (department_name, department_code, hod_name, status) VALUES (?, ?, ?, ?)");
                $stmt->execute([$department_name, $department_code, $hod_name, $status]);
                $msg = 'Department added successfully.';
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
                echo json_encode(['status' => 'error', 'message' => 'Invalid Department ID.']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT status FROM departments WHERE department_id = ?");
            $stmt->execute([$id]);
            $currentStatus = $stmt->fetchColumn();
            if (!$currentStatus) {
                echo json_encode(['status' => 'error', 'message' => 'Department not found.']);
                exit;
            }
            $newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';
            $update = $pdo->prepare("UPDATE departments SET status = ? WHERE department_id = ?");
            $update->execute([$newStatus, $id]);

            echo json_encode(['status' => 'success', 'message' => "Department status changed to {$newStatus}.", 'new_status' => $newStatus]);
            break;

        case 'delete':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }
            $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Department ID.']);
                exit;
            }

            try {
                $pdo->beginTransaction();

                // Clean up dependent allocations, subjects, faculties, courses
                $pdo->prepare("DELETE FROM subject_allocations WHERE course_id IN (SELECT course_id FROM courses WHERE department_id = ?) OR faculty_id IN (SELECT faculty_id FROM faculties WHERE department_id = ?)")->execute([$id, $id]);
                $pdo->prepare("DELETE FROM subjects WHERE department_id = ?")->execute([$id]);
                $pdo->prepare("DELETE FROM faculties WHERE department_id = ?")->execute([$id]);
                $pdo->prepare("DELETE FROM divisions WHERE semester_id IN (SELECT semester_id FROM semesters WHERE course_id IN (SELECT course_id FROM courses WHERE department_id = ?))")->execute([$id]);
                $pdo->prepare("DELETE FROM semesters WHERE course_id IN (SELECT course_id FROM courses WHERE department_id = ?)")->execute([$id]);
                $pdo->prepare("DELETE FROM courses WHERE department_id = ?")->execute([$id]);

                // Delete target department
                $delStmt = $pdo->prepare("DELETE FROM departments WHERE department_id = ?");
                $delStmt->execute([$id]);

                $pdo->commit();
                echo json_encode(['status' => 'success', 'message' => 'Department deleted successfully.']);
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
