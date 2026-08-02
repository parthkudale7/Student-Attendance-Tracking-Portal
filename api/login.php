<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

if (empty($email) || empty($password) || empty($role)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Account not found.']);
        exit;
    }

    if (!password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
        exit;
    }

    if ($user['role'] !== $role) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized role access.']);
        exit;
    }

    // Authentication successful
    session_start();
    
    $displayName = $user['name'];
    $displayRole = $user['role'];
    $employeeId = 'FAC' . $user['id'];
    $designation = 'Faculty Member';
    $department = 'Computer Science';
    $qualification = '';
    $phone = $user['phone'] ?? '';
    $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=4F7CFF&color=fff';

    if ($role === 'faculty') {
        try {
            $facStmt = $pdo->prepare("SELECT f.*, d.department_name, d.department_code 
                                      FROM faculties f 
                                      LEFT JOIN departments d ON f.department_id = d.department_id 
                                      WHERE f.email = ? LIMIT 1");
            $facStmt->execute([$email]);
            $facData = $facStmt->fetch();
            if ($facData) {
                $displayName = $facData['full_name'];
                $employeeId = $facData['employee_id'];
                $designation = $facData['designation'] ?? 'Assistant Professor';
                $department = $facData['department_name'] ?? $facData['department_code'] ?? 'Computer Science';
                $qualification = $facData['qualification'] ?? '';
                $phone = $facData['phone'] ?? '';
                if (!empty($facData['photo'])) {
                    $avatar = '../assets/img/faculties/' . $facData['photo'];
                } else {
                    $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=4F7CFF&color=fff';
                }
            }
        } catch (Exception $e) {}
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $displayName;
    $_SESSION['user_name'] = $displayName;
    $_SESSION['role'] = $user['role'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['employee_id'] = $employeeId;
    $_SESSION['designation'] = $designation;
    $_SESSION['department'] = $department;
    $_SESSION['qualification'] = $qualification;
    $_SESSION['phone'] = $phone;
    $_SESSION['user_phone'] = $phone;
    $_SESSION['user_designation'] = $designation;
    $_SESSION['avatar'] = $avatar;

    // Determine redirect folder based on role
    $folder = strtolower(str_replace(' ', '_', $user['role']));
    if ($folder === 'super_admin' || $folder === 'admin') {
        $folder = 'admin';
    }

    echo json_encode([
        'success' => true,
        'redirect' => "../{$folder}/dashboard.php",
        'user' => [
            'id' => $employeeId,
            'user_id' => $user['id'],
            'name' => $displayName,
            'email' => $user['email'],
            'role' => $user['role'],
            'designation' => $designation,
            'department' => $department,
            'qualification' => $qualification,
            'mobile' => $phone,
            'avatar' => $avatar
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
}
