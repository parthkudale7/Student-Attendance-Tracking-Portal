<?php
/**
 * Academic Year Configuration AJAX Action Handler
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
            $stmt = $pdo->query("SELECT * FROM academic_years ORDER BY academic_year_id DESC");
            $years = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'data' => $years]);
            break;

        case 'get':
            $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Academic Year ID.']);
                exit;
            }
            $stmt = $pdo->prepare("SELECT * FROM academic_years WHERE academic_year_id = ?");
            $stmt->execute([$id]);
            $year = $stmt->fetch();
            if ($year) {
                echo json_encode(['status' => 'success', 'data' => $year]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Academic Year not found.']);
            }
            break;

        case 'save':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $academic_year_id = !empty($_POST['academic_year_id']) ? (int)$_POST['academic_year_id'] : null;
            $year_label       = trim($_POST['year_label'] ?? '');
            $start_date       = trim($_POST['start_date'] ?? '');
            $end_date         = trim($_POST['end_date'] ?? '');
            $status           = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';
            $is_current       = !empty($_POST['is_current']) ? 1 : 0;

            if (empty($year_label)) {
                echo json_encode(['status' => 'error', 'message' => 'Academic Year Label (e.g. 2026-2027) is required.']);
                exit;
            }
            if (empty($start_date) || empty($end_date)) {
                echo json_encode(['status' => 'error', 'message' => 'Start Date and End Date are required.']);
                exit;
            }

            // Date validation: end_date must be strictly after start_date
            if (strtotime($end_date) <= strtotime($start_date)) {
                echo json_encode(['status' => 'error', 'message' => 'End Date must be strictly after Start Date.']);
                exit;
            }

            // Check unique year label
            if ($academic_year_id) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM academic_years WHERE year_label = ? AND academic_year_id != ?");
                $stmt->execute([$year_label, $academic_year_id]);
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM academic_years WHERE year_label = ?");
                $stmt->execute([$year_label]);
            }
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => "Academic Year Label '{$year_label}' already exists."]);
                exit;
            }

            if ($is_current == 1) {
                $pdo->exec("UPDATE academic_years SET is_current = 0");
                $status = 'active';
            }

            if ($academic_year_id) {
                $stmt = $pdo->prepare("UPDATE academic_years SET year_label = ?, start_date = ?, end_date = ?, status = ?, is_current = ? WHERE academic_year_id = ?");
                $stmt->execute([$year_label, $start_date, $end_date, $status, $is_current, $academic_year_id]);
                $msg = 'Academic Year updated successfully.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO academic_years (year_label, start_date, end_date, status, is_current) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$year_label, $start_date, $end_date, $status, $is_current]);
                $msg = 'Academic Year configured successfully.';
            }

            echo json_encode(['status' => 'success', 'message' => $msg]);
            break;

        case 'set_current':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Academic Year ID.']);
                exit;
            }

            try {
                $pdo->beginTransaction();
                $pdo->exec("UPDATE academic_years SET is_current = 0");
                $stmt = $pdo->prepare("UPDATE academic_years SET is_current = 1, status = 'active' WHERE academic_year_id = ?");
                $stmt->execute([$id]);
                $pdo->commit();

                echo json_encode(['status' => 'success', 'message' => 'Academic Year set as current active session successfully.']);
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                echo json_encode(['status' => 'error', 'message' => 'Failed to set current academic year: ' . $e->getMessage()]);
            }
            break;

        case 'delete':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }

            $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Academic Year ID.']);
                exit;
            }

            $delStmt = $pdo->prepare("DELETE FROM academic_years WHERE academic_year_id = ? AND is_current = 0");
            $delStmt->execute([$id]);

            echo json_encode(['status' => 'success', 'message' => 'Academic Year deleted successfully.']);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
