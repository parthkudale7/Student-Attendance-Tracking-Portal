<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

// Include the connection to the faculty_attendance database (for Students table)
require_once 'db.php'; // This provides $pdo connected to faculty_attendance

// Connect to the attendance_db database (for users table)
$auth_host = '127.0.0.1';
$auth_user = 'root';
$auth_pass = '';
$auth_dbname = 'attendance_db';

try {
    $auth_pdo = new PDO("mysql:host=$auth_host;dbname=$auth_dbname;charset=utf8mb4", $auth_user, $auth_pass);
    $auth_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $auth_pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Auth Database connection failed.']);
    exit;
}

$jsonBody = json_decode(file_get_contents('php://input'), true);
if (!$jsonBody) {
    echo json_encode(['success' => false, 'message' => 'Invalid request payload.']);
    exit;
}

$name = trim($jsonBody['name'] ?? '');
$roll = trim($jsonBody['roll'] ?? '');
$email = trim($jsonBody['email'] ?? '');
$dept = trim($jsonBody['dept'] ?? '');
$sem = trim($jsonBody['sem'] ?? '');
$div = trim($jsonBody['div'] ?? '');

if (empty($name) || empty($roll) || empty($email) || empty($dept) || empty($sem) || empty($div)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

try {
    // 1. Check if user already exists
    $stmt = $auth_pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'User with this email already exists.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT student_id FROM Students WHERE roll_no = ?");
    $stmt->execute([$roll]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Student with this roll number already exists.']);
        exit;
    }

    // 2. Insert into Students table (faculty_attendance db)
    $stmt = $pdo->prepare("INSERT INTO Students (roll_no, student_name, department, semester, division) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$roll, $name, $dept, $sem, $div]);

    // 3. Insert into users table (attendance_db db)
    // The password will be the roll number by default
    $defaultPassword = $roll;
    $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

    $stmt = $auth_pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
    $stmt->execute([$name, $email, $hashedPassword]);

    echo json_encode(['success' => true, 'message' => 'Student added and login generated successfully.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
