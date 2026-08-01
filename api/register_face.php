<?php
header('Content-Type: application/json');
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in first.']);
    exit;
}

$role = $_SESSION['role'];
if ($role !== 'admin' && $role !== 'faculty') {
    echo json_encode(['success' => false, 'message' => 'Face ID is only available for Admin and Faculty.']);
    exit;
}

// Face descriptor comes as JSON payload
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['descriptor'])) {
    echo json_encode(['success' => false, 'message' => 'Face descriptor missing.']);
    exit;
}

// Convert descriptor back to json string to store in TEXT column
$descriptor_json = json_encode($data['descriptor']);

try {
    try {
        $stmt = $pdo->prepare("UPDATE users SET face_descriptor = ? WHERE id = ?");
        $stmt->execute([$descriptor_json, $_SESSION['user_id']]);
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Unknown column') !== false) {
            $pdo->exec("ALTER TABLE users ADD COLUMN face_descriptor TEXT DEFAULT NULL");
            $stmt = $pdo->prepare("UPDATE users SET face_descriptor = ? WHERE id = ?");
            $stmt->execute([$descriptor_json, $_SESSION['user_id']]);
        } else {
            throw $e;
        }
    }
    
    echo json_encode(['success' => true, 'message' => 'Face ID registered successfully.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
