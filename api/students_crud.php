<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

require_once 'db.php'; // Provides $pdo (faculty_attendance)

// Connect to attendance_db for user credentials
$auth_host = '127.0.0.1';
$auth_user = 'root';
$auth_pass = '';
$auth_dbname = 'attendance_db';

try {
    $auth_pdo = new PDO("mysql:host=$auth_host;dbname=$auth_dbname;charset=utf8mb4", $auth_user, $auth_pass);
    $auth_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $auth_pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Continue if auth DB fails, but flag it
    $auth_pdo = null;
}

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

// Helper to handle image upload
function handlePhotoUpload($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $uploadDir = '../assets/uploads/students/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $allowed)) {
        return null;
    }
    
    $fileName = 'student_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return 'assets/uploads/students/' . $fileName;
    }
    return null;
}

// 1. GET STUDENTS (Search, Filter, List)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($action)) {
    try {
        $dept = $_GET['dept'] ?? '';
        $sem = $_GET['sem'] ?? '';
        $div = $_GET['div'] ?? '';
        $search = trim($_GET['search'] ?? '');
        
        $sql = "SELECT s.*, 
                (SELECT COUNT(*) FROM Attendance a WHERE a.student_id = s.student_id) as total_lectures,
                (SELECT COUNT(*) FROM Attendance a WHERE a.student_id = s.student_id AND a.attendance_status = 'present') as attended_lectures
                FROM Students s WHERE 1=1";
        $params = [];
        
        if (!empty($dept)) {
            $sql .= " AND s.department = ?";
            $params[] = $dept;
        }
        if (!empty($sem)) {
            $sql .= " AND s.semester = ?";
            $params[] = $sem;
        }
        if (!empty($div)) {
            $sql .= " AND s.division = ?";
            $params[] = $div;
        }
        if (!empty($search)) {
            $sql .= " AND (s.student_name LIKE ? OR s.roll_no LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $sql .= " ORDER BY s.roll_no ASC LIMIT 200";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $students = $stmt->fetchAll();
        
        // Calculate percentage
        foreach ($students as &$st) {
            $total = (int)$st['total_lectures'];
            $attended = (int)$st['attended_lectures'];
            $st['attendance_percentage'] = $total > 0 ? round(($attended / $total) * 100, 1) : 0;
            if (empty($st['profile_photo'])) {
                $st['profile_photo'] = 'https://ui-avatars.com/api/?name=' . urlencode($st['student_name']) . '&background=0D8ABC&color=fff';
            } else if (!filter_var($st['profile_photo'], FILTER_VALIDATE_URL)) {
                $st['profile_photo'] = '../' . ltrim($st['profile_photo'], '/');
            }
        }
        
        echo json_encode(['success' => true, 'data' => $students]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// 2. GET SINGLE STUDENT PROFILE
if ($action === 'get') {
    $id = $_GET['id'] ?? 0;
    try {
        $stmt = $pdo->prepare("SELECT s.*, 
            (SELECT COUNT(*) FROM Attendance a WHERE a.student_id = s.student_id) as total_lectures,
            (SELECT COUNT(*) FROM Attendance a WHERE a.student_id = s.student_id AND a.attendance_status = 'present') as attended_lectures
            FROM Students s WHERE s.student_id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();
        
        if ($student) {
            $total = (int)$student['total_lectures'];
            $attended = (int)$student['attended_lectures'];
            $student['attendance_percentage'] = $total > 0 ? round(($attended / $total) * 100, 1) : 0;
            
            // Get email from attendance_db if available
            if ($auth_pdo) {
                $stmt = $auth_pdo->prepare("SELECT email FROM users WHERE name = ? OR email LIKE ? LIMIT 1");
                $stmt->execute([$student['student_name'], '%' . $student['roll_no'] . '%']);
                $u = $stmt->fetch();
                $student['email'] = $u['email'] ?? ($student['roll_no'] . '@student.college.edu');
            } else {
                $student['email'] = $student['roll_no'] . '@student.college.edu';
            }
            
            if (empty($student['profile_photo'])) {
                $student['profile_photo'] = 'https://ui-avatars.com/api/?name=' . urlencode($student['student_name']) . '&background=0D8ABC&color=fff';
            } else if (!filter_var($student['profile_photo'], FILTER_VALIDATE_URL)) {
                $student['profile_photo'] = '../' . ltrim($student['profile_photo'], '/');
            }
            
            echo json_encode(['success' => true, 'data' => $student]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// 3. CREATE / REGISTER STUDENT
if ($action === 'create' || ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($action))) {
    $name = trim($_POST['name'] ?? '');
    $roll = trim($_POST['roll'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dept = trim($_POST['dept'] ?? '');
    $sem = trim($_POST['sem'] ?? '');
    $div = trim($_POST['div'] ?? '');
    
    // JSON payload support
    if (empty($name)) {
        $json = json_decode(file_get_contents('php://input'), true);
        if ($json) {
            $name = trim($json['name'] ?? '');
            $roll = trim($json['roll'] ?? '');
            $email = trim($json['email'] ?? '');
            $dept = trim($json['dept'] ?? '');
            $sem = trim($json['sem'] ?? '');
            $div = trim($json['div'] ?? '');
        }
    }
    
    if (empty($name) || empty($roll) || empty($dept) || empty($sem) || empty($div)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }
    
    try {
        // Check duplicate roll
        $stmt = $pdo->prepare("SELECT student_id FROM Students WHERE roll_no = ?");
        $stmt->execute([$roll]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'A student with this Roll Number already exists.']);
            exit;
        }
        
        $photoPath = handlePhotoUpload($_FILES['photo'] ?? null);
        if (!$photoPath) {
            $photoPath = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=0D8ABC&color=fff';
        }
        
        // Insert into Students table
        $stmt = $pdo->prepare("INSERT INTO Students (roll_no, student_name, department, semester, division, profile_photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$roll, $name, $dept, $sem, $div, $photoPath]);
        $studentId = $pdo->lastInsertId();
        
        // Insert into users table if auth_pdo is available
        if ($auth_pdo && !empty($email)) {
            $hashedPassword = password_hash($roll, PASSWORD_DEFAULT);
            $stmt = $auth_pdo->prepare("INSERT IGNORE INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
            $stmt->execute([$name, $email, $hashedPassword]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Student registered successfully!', 'student_id' => $studentId]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// 4. UPDATE STUDENT
if ($action === 'update') {
    $id = $_POST['student_id'] ?? 0;
    $name = trim($_POST['name'] ?? '');
    $roll = trim($_POST['roll'] ?? '');
    $dept = trim($_POST['dept'] ?? '');
    $sem = trim($_POST['sem'] ?? '');
    $div = trim($_POST['div'] ?? '');
    
    if (empty($id) || empty($name) || empty($roll) || empty($dept) || empty($sem) || empty($div)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }
    
    try {
        // Check duplicate roll
        $stmt = $pdo->prepare("SELECT student_id FROM Students WHERE roll_no = ? AND student_id != ?");
        $stmt->execute([$roll, $id]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Another student already has this Roll Number.']);
            exit;
        }
        
        $photoPath = handlePhotoUpload($_FILES['photo'] ?? null);
        
        if ($photoPath) {
            $stmt = $pdo->prepare("UPDATE Students SET student_name = ?, roll_no = ?, department = ?, semester = ?, division = ?, profile_photo = ? WHERE student_id = ?");
            $stmt->execute([$name, $roll, $dept, $sem, $div, $photoPath, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE Students SET student_name = ?, roll_no = ?, department = ?, semester = ?, division = ? WHERE student_id = ?");
            $stmt->execute([$name, $roll, $dept, $sem, $div, $id]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Student details updated successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// 5. DELETE STUDENT
if ($action === 'delete') {
    $id = $_POST['student_id'] ?? ($_GET['student_id'] ?? 0);
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid Student ID.']);
        exit;
    }
    
    try {
        // Delete child records first if not cascaded
        $stmt = $pdo->prepare("DELETE FROM Attendance_Validation WHERE attendance_id IN (SELECT attendance_id FROM Attendance WHERE student_id = ?)");
        $stmt->execute([$id]);
        
        $stmt = $pdo->prepare("DELETE FROM Attendance WHERE student_id = ?");
        $stmt->execute([$id]);
        
        $stmt = $pdo->prepare("DELETE FROM Students WHERE student_id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true, 'message' => 'Student deleted successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to delete student: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
?>
