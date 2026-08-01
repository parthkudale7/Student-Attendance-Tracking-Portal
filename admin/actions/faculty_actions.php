<?php
/**
 * Faculty Management Action Handler
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
            $deptId = $_GET['department_id'] ?? '';
            $status = $_GET['status'] ?? '';

            $sql = "SELECT f.*, d.department_name, d.department_code 
                    FROM faculties f 
                    JOIN departments d ON f.department_id = d.department_id 
                    WHERE 1=1";
            $params = [];

            if (!empty($deptId)) {
                $sql .= " AND f.department_id = ?";
                $params[] = $deptId;
            }
            if (!empty($status)) {
                $sql .= " AND f.status = ?";
                $params[] = $status;
            }

            $sql .= " ORDER BY f.faculty_id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $faculties = $stmt->fetchAll();

            echo json_encode(['status' => 'success', 'data' => $faculties]);
            break;

        case 'get':
            $id = (int)($_GET['id'] ?? 0);
            $stmt = $pdo->prepare("SELECT * FROM faculties WHERE faculty_id = ?");
            $stmt->execute([$id]);
            $faculty = $stmt->fetch();

            if ($faculty) {
                echo json_encode(['status' => 'success', 'data' => $faculty]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Faculty member not found.']);
            }
            break;

        case 'get_profile':
            $id = (int)($_GET['id'] ?? 0);
            $stmt = $pdo->prepare("
                SELECT f.*, d.department_name, d.department_code 
                FROM faculties f 
                JOIN departments d ON f.department_id = d.department_id 
                WHERE f.faculty_id = ?
            ");
            $stmt->execute([$id]);
            $faculty = $stmt->fetch();

            if (!$faculty) {
                echo json_encode(['status' => 'error', 'message' => 'Faculty profile not found.']);
                exit;
            }

            // Get assigned subjects
            $allocStmt = $pdo->prepare("
                SELECT sa.*, s.subject_name, s.subject_code, s.credits, c.course_code, dv.division_name, sess.session_name, ay.year_label
                FROM subject_allocations sa
                JOIN subjects s ON sa.subject_id = s.subject_id
                JOIN courses c ON sa.course_id = c.course_id
                JOIN divisions dv ON sa.division_id = dv.division_id
                JOIN sessions sess ON sa.session_id = sess.session_id
                JOIN academic_years ay ON sa.academic_year_id = ay.academic_year_id
                WHERE sa.faculty_id = ?
                ORDER BY sa.allocation_id DESC
            ");
            $allocStmt->execute([$id]);
            $allocations = $allocStmt->fetchAll();

            // Calculate stats
            $totalSubjects = count($allocations);
            $totalCredits = array_sum(array_column($allocations, 'credits'));

            echo json_encode([
                'status' => 'success',
                'data' => [
                    'faculty' => $faculty,
                    'allocations' => $allocations,
                    'stats' => [
                        'total_subjects' => $totalSubjects,
                        'total_credits' => $totalCredits,
                        'total_classes' => $totalSubjects * 4
                    ]
                ]
            ]);
            break;

        case 'save':
            $faculty_id = !empty($_POST['faculty_id']) ? (int)$_POST['faculty_id'] : null;
            $employee_id = trim($_POST['employee_id'] ?? '');
            $full_name = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $department_id = (int)($_POST['department_id'] ?? 0);
            $designation = trim($_POST['designation'] ?? 'Assistant Professor');
            $qualification = trim($_POST['qualification'] ?? '');
            $joining_date = !empty($_POST['joining_date']) ? $_POST['joining_date'] : null;
            $experience_years = (int)($_POST['experience_years'] ?? 3);
            $status = $_POST['status'] ?? 'active';

            if (empty($employee_id) || empty($full_name) || empty($email) || empty($phone) || empty($department_id)) {
                echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
                exit;
            }

            // Check Duplicate Employee ID
            $checkEmp = $pdo->prepare("SELECT faculty_id FROM faculties WHERE employee_id = ? AND faculty_id != ?");
            $checkEmp->execute([$employee_id, $faculty_id ?? 0]);
            if ($checkEmp->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'Employee ID already exists. Please use a unique Employee ID.']);
                exit;
            }

            // Check Duplicate Email
            $checkEmail = $pdo->prepare("SELECT faculty_id FROM faculties WHERE email = ? AND faculty_id != ?");
            $checkEmail->execute([$email, $faculty_id ?? 0]);
            if ($checkEmail->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'Email address already exists.']);
                exit;
            }

            // Handle Photo upload / file assignment
            $photo = null;
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $photoName = 'faculty_' . time() . '_' . rand(1000, 9999) . '.' . strtolower($ext);
                $uploadDir = __DIR__ . '/../../assets/img/faculties/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName);
                $photo = $photoName;
            }

            // Determine login password (provided or auto-generated default from employee_id or fallback)
            $passwordPlain = !empty($_POST['password']) ? trim($_POST['password']) : (!empty($employee_id) ? $employee_id : 'Faculty@123');
            $hashedPassword = password_hash($passwordPlain, PASSWORD_DEFAULT);

            // Fetch department name/code for synced records
            $deptName = 'CE';
            try {
                $deptStmt = $pdo->prepare("SELECT department_code, department_name FROM departments WHERE department_id = ?");
                $deptStmt->execute([$department_id]);
                $deptRow = $deptStmt->fetch();
                if ($deptRow) {
                    $deptName = !empty($deptRow['department_code']) ? $deptRow['department_code'] : $deptRow['department_name'];
                }
            } catch (Exception $e) {}

            if ($faculty_id) {
                $sql = "UPDATE faculties SET 
                        employee_id = ?, full_name = ?, email = ?, phone = ?, department_id = ?, 
                        designation = ?, qualification = ?, joining_date = ?, experience_years = ?, status = ?";
                $params = [$employee_id, $full_name, $email, $phone, $department_id, $designation, $qualification, $joining_date, $experience_years, $status];

                if ($photo) {
                    $sql .= ", photo = ?";
                    $params[] = $photo;
                }

                $sql .= " WHERE faculty_id = ?";
                $params[] = $faculty_id;

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);

                // Update / Sync in attendance_db.users table
                try {
                    $userCheck = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                    $userCheck->execute([$email]);
                    if ($userCheck->fetch()) {
                        if (!empty($_POST['password'])) {
                            $userUpdate = $pdo->prepare("UPDATE users SET name = ?, password = ?, role = 'faculty', status = ? WHERE email = ?");
                            $userUpdate->execute([$full_name, $hashedPassword, $status, $email]);
                        } else {
                            $userUpdate = $pdo->prepare("UPDATE users SET name = ?, role = 'faculty', status = ? WHERE email = ?");
                            $userUpdate->execute([$full_name, $status, $email]);
                        }
                    } else {
                        $userInsert = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'faculty', ?)");
                        $userInsert->execute([$full_name, $email, $hashedPassword, $status]);
                    }
                } catch (Exception $e) {}

                // Sync in faculty_attendance.Faculty table
                try {
                    $facDb = new PDO("mysql:host=" . DB_HOST . ";dbname=faculty_attendance;charset=utf8mb4", DB_USER, DB_PASS);
                    $facDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                    $facCheck = $facDb->prepare("SELECT faculty_id FROM Faculty WHERE email = ?");
                    $facCheck->execute([$email]);
                    if ($facCheck->fetch()) {
                        $facUp = $facDb->prepare("UPDATE Faculty SET faculty_name = ?, department = ?, designation = ? WHERE email = ?");
                        $facUp->execute([$full_name, $deptName, $designation, $email]);
                    } else {
                        $facIns = $facDb->prepare("INSERT INTO Faculty (faculty_name, email, password, department, designation) VALUES (?, ?, ?, ?, ?)");
                        $facIns->execute([$full_name, $email, $passwordPlain, $deptName, $designation]);
                    }
                } catch (Exception $e) {}

                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Faculty details updated successfully!',
                    'credentials' => [
                        'name' => $full_name,
                        'email' => $email,
                        'password' => $passwordPlain,
                        'role' => 'faculty',
                        'employee_id' => $employee_id,
                        'login_url' => '../auth/login.html?role=faculty'
                    ]
                ]);
            } else {
                $sql = "INSERT INTO faculties (employee_id, full_name, email, phone, department_id, designation, qualification, joining_date, experience_years, photo, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$employee_id, $full_name, $email, $phone, $department_id, $designation, $qualification, $joining_date, $experience_years, $photo, $status]);

                // Create / Sync login in attendance_db.users table
                try {
                    $userCheck = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                    $userCheck->execute([$email]);
                    if ($userCheck->fetch()) {
                        $userUpdate = $pdo->prepare("UPDATE users SET name = ?, password = ?, role = 'faculty', status = ? WHERE email = ?");
                        $userUpdate->execute([$full_name, $hashedPassword, $status, $email]);
                    } else {
                        $userInsert = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'faculty', ?)");
                        $userInsert->execute([$full_name, $email, $hashedPassword, $status]);
                    }
                } catch (Exception $e) {}

                // Sync in faculty_attendance.Faculty table
                try {
                    $facDb = new PDO("mysql:host=" . DB_HOST . ";dbname=faculty_attendance;charset=utf8mb4", DB_USER, DB_PASS);
                    $facDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                    $facCheck = $facDb->prepare("SELECT faculty_id FROM Faculty WHERE email = ?");
                    $facCheck->execute([$email]);
                    if ($facCheck->fetch()) {
                        $facUp = $facDb->prepare("UPDATE Faculty SET faculty_name = ?, password = ?, department = ?, designation = ? WHERE email = ?");
                        $facUp->execute([$full_name, $passwordPlain, $deptName, $designation, $email]);
                    } else {
                        $facIns = $facDb->prepare("INSERT INTO Faculty (faculty_name, email, password, department, designation) VALUES (?, ?, ?, ?, ?)");
                        $facIns->execute([$full_name, $email, $passwordPlain, $deptName, $designation]);
                    }
                } catch (Exception $e) {}

                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Faculty member registered and login credentials generated successfully!',
                    'credentials' => [
                        'name' => $full_name,
                        'email' => $email,
                        'password' => $passwordPlain,
                        'role' => 'faculty',
                        'employee_id' => $employee_id,
                        'login_url' => '../auth/login.html?role=faculty'
                    ]
                ]);
            }
            break;

        case 'delete':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF).']);
                exit;
            }
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid Faculty ID.']);
                exit;
            }

            // Check linked subject allocations
            $allocCheck = $pdo->prepare("SELECT COUNT(*) FROM subject_allocations WHERE faculty_id = ?");
            $allocCheck->execute([$id]);
            $linkedCount = $allocCheck->fetchColumn();
            if ($linkedCount > 0) {
                echo json_encode(['status' => 'error', 'message' => "Cannot delete faculty member: {$linkedCount} subject allocation(s) are linked to this member. Remove subject allocations first."]);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM faculties WHERE faculty_id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Faculty member deleted successfully!']);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
