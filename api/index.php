<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);
require_once 'db.php';

$request = isset($_GET['request']) ? $_GET['request'] : '';
$method = $_SERVER['REQUEST_METHOD'];

// Parse URL parts (e.g. validation-requests/123)
$parts = explode('/', rtrim($request, '/'));
$endpoint = $parts[0] ?? '';
$id = $parts[1] ?? null;

// Get JSON body if available
$jsonBody = json_decode(file_get_contents('php://input'), true);

function jsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

try {
    switch ($endpoint) {
        case 'faculty':
            if ($method === 'GET') {
                $email = $_GET['email'] ?? null;
                if ($email) {
                    $stmt = $pdo->prepare("SELECT * FROM Faculty WHERE email = ?");
                    $stmt->execute([$email]);
                } else {
                    $stmt = $pdo->query("SELECT * FROM Faculty LIMIT 1");
                }
                $faculty = $stmt->fetch();
                if ($faculty) {
                    jsonResponse([
                        'id' => $faculty['faculty_id'],
                        'email' => $faculty['email'],
                        'name' => $faculty['faculty_name'],
                        'role' => $faculty['department'],
                        'avatar' => $faculty['avatar'],
                        'subjects' => ['Data Structures', 'Algorithms', 'Operating Systems']
                    ]);
                } else {
                    jsonResponse(['error' => 'Not found'], 404);
                }
            }
            break;

        case 'students':
            if ($method === 'GET') {
                $dept = $_GET['dept'] ?? '';
                $sem = $_GET['sem'] ?? '';
                $div = $_GET['div'] ?? '';
                $stmt = $pdo->prepare("SELECT roll_no as roll, student_name as name, department as dept, semester as sem, division as `div` FROM Students WHERE department = ? AND semester = ? AND division = ?");
                $stmt->execute([$dept, $sem, $div]);
                $students = $stmt->fetchAll();
                
                if (empty($students)) {
                    jsonResponse(['error' => 'No students found. Debug: dept=' . $dept . ', sem=' . $sem . ', div=' . $div], 404);
                }
                
                jsonResponse($students);
            }
            break;

        case 'dashboard-stats':
            if ($method === 'GET') {
                $today = date('Y-m-d');
                // Total Students
                $stmt = $pdo->query("SELECT COUNT(*) FROM Students");
                $totalStudents = $stmt->fetchColumn();

                // Classes Today
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM Lecture WHERE lecture_date = ?");
                $stmt->execute([$today]);
                $classesToday = $stmt->fetchColumn();

                // Avg Attendance
                $stmt = $pdo->query("SELECT COUNT(*) as total, SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) as present FROM Attendance");
                $row = $stmt->fetch();
                $avgAttendance = ($row['total'] > 0) ? round(($row['present'] / $row['total']) * 100) : 0;

                // Pending Validation
                $stmt = $pdo->query("SELECT COUNT(*) FROM Attendance WHERE validation_status = 'Pending'");
                $pendingValidation = $stmt->fetchColumn();

                // Today's Schedule
                $scheduleSql = "SELECT 
                                    l.lecture_id,
                                    l.lecture_number,
                                    sub.subject_name,
                                    sub.semester,
                                    'Div A' as division, /* Assuming Div A for simple mock if not mapped to lecture directly, but let's query a student from this subject if possible. Actually, we don't have division in Lecture, but it's part of the schedule. Let's just return what we have. */
                                    (SELECT COUNT(*) FROM Attendance WHERE lecture_id = l.lecture_id) as attendanceCount
                                FROM Lecture l
                                JOIN Subjects sub ON l.subject_id = sub.subject_id
                                WHERE l.lecture_date = ?";
                $stmt = $pdo->prepare($scheduleSql);
                $stmt->execute([$today]);
                $scheduleRows = $stmt->fetchAll();
                
                $schedule = array_map(function($r) {
                    return [
                        'timeBlock' => $r['lecture_number'], // We use lecture_number as timeBlock for now
                        'subjectName' => $r['subject_name'],
                        'classInfo' => $r['semester'],
                        'status' => $r['attendanceCount'] > 0 ? 'Completed' : 'Mark Now'
                    ];
                }, $scheduleRows);

                jsonResponse([
                    'totalStudents' => $totalStudents,
                    'classesToday' => $classesToday,
                    'avgAttendance' => $avgAttendance,
                    'pendingValidation' => $pendingValidation,
                    'schedule' => $schedule
                ]);
            }
            break;

        case 'attendance':
            if ($method === 'POST') {
                $date = $jsonBody['date'] ?? '';
                $dept = $jsonBody['dept'] ?? '';
                $sem = $jsonBody['sem'] ?? '';
                $div = $jsonBody['div'] ?? '';
                $subject = $jsonBody['subject'] ?? '';
                $lectNo = $jsonBody['lectNo'] ?? '';
                $attendance = $jsonBody['attendance'] ?? [];
                $remarks = $jsonBody['remarks'] ?? [];

                $pdo->beginTransaction();
                try {
                    // Subject
                    $stmt = $pdo->prepare("SELECT subject_id FROM Subjects WHERE subject_name = ? AND semester = ? AND department = ?");
                    $stmt->execute([$subject, $sem, $dept]);
                    $subjectId = $stmt->fetchColumn();
                    if (!$subjectId) {
                        $stmt = $pdo->prepare("INSERT INTO Subjects (subject_name, semester, department) VALUES (?, ?, ?)");
                        $stmt->execute([$subject, $sem, $dept]);
                        $subjectId = $pdo->lastInsertId();
                    }

                    // Faculty
                    $stmt = $pdo->query("SELECT faculty_id FROM Faculty LIMIT 1");
                    $facultyId = $stmt->fetchColumn();

                    // Lecture
                    $stmt = $pdo->prepare("SELECT lecture_id FROM Lecture WHERE subject_id = ? AND lecture_number = ? AND faculty_id = ? AND lecture_date = ?");
                    $stmt->execute([$subjectId, $lectNo, $facultyId, $date]);
                    $lectureId = $stmt->fetchColumn();
                    if (!$lectureId) {
                        $stmt = $pdo->prepare("INSERT INTO Lecture (subject_id, lecture_number, faculty_id, lecture_date) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$subjectId, $lectNo, $facultyId, $date]);
                        $lectureId = $pdo->lastInsertId();
                    }

                    // Attendance
                    foreach ($attendance as $roll => $status) {
                        $stmt = $pdo->prepare("SELECT student_id FROM Students WHERE roll_no = ?");
                        $stmt->execute([$roll]);
                        $studentId = $stmt->fetchColumn();
                        if ($studentId) {
                            $remark = $remarks[$roll] ?? null;
                            // Use UPSERT to allow re-saving from the daily attendance screen
                            $stmt = $pdo->prepare("INSERT INTO Attendance (student_id, lecture_id, attendance_status, remarks, validation_status) VALUES (?, ?, ?, ?, 'Pending') ON DUPLICATE KEY UPDATE attendance_status=VALUES(attendance_status), remarks=VALUES(remarks), validation_status='Pending'");
                            $stmt->execute([$studentId, $lectureId, $status, $remark]);
                        }
                    }
                    $pdo->commit();
                    jsonResponse(['success' => true, 'message' => 'Attendance saved successfully!']);
                } catch (Exception $e) {
                    $pdo->rollBack();
                    jsonResponse(['error' => $e->getMessage()], 400);
                }
            } elseif ($method === 'GET') {
                $date = $_GET['date'] ?? '';
                $dept = $_GET['dept'] ?? '';
                $sem = $_GET['sem'] ?? '';
                $div = $_GET['div'] ?? '';
                $subject = $_GET['subject'] ?? '';
                $lectNo = $_GET['lectNo'] ?? '';
                
                $sql = "SELECT 
                            a.attendance_id as id, 
                            s.roll_no as roll, 
                            s.student_name as studentName,
                            a.attendance_status as status,
                            DATE_FORMAT(l.lecture_date, '%Y-%m-%d') as date,
                            sub.subject_name as subject,
                            sub.department as dept,
                            sub.semester as sem,
                            s.division as `div`,
                            l.lecture_number as lectNo,
                            a.remarks,
                            a.created_at
                        FROM Attendance a
                        JOIN Students s ON a.student_id = s.student_id
                        JOIN Lecture l ON a.lecture_id = l.lecture_id
                        JOIN Subjects sub ON l.subject_id = sub.subject_id
                        WHERE l.lecture_date = ? AND sub.department = ? AND sub.subject_name = ?";
                        
                $params = [$date, $dept, $subject];
                
                if (!empty($sem)) {
                    $sql .= " AND sub.semester = ?";
                    $params[] = $sem;
                }
                if (!empty($div)) {
                    $sql .= " AND s.division = ?";
                    $params[] = $div;
                }
                if (!empty($lectNo)) {
                    $sql .= " AND l.lecture_number = ?";
                    $params[] = $lectNo;
                }
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $rows = $stmt->fetchAll();
                
                if (empty($rows)) {
                    jsonResponse(['error' => 'No attendance record found for this date/subject.'], 404);
                } else {
                    jsonResponse($rows);
                }
            }
            break;

        case 'attendance-edit-direct':
            if ($method === 'POST') {
                $requests = $jsonBody['requests'] ?? [];
                if (empty($requests)) {
                    jsonResponse(['error' => 'No requests provided'], 400);
                }
                
                $pdo->beginTransaction();
                try {
                    $count = 0;
                    $stmt = $pdo->prepare("UPDATE Attendance SET attendance_status = ?, remarks = ?, validation_status = 'Pending' WHERE attendance_id = ?");
                    foreach ($requests as $reqData) {
                        $stmt->execute([$reqData['newStatus'], $reqData['remarks'] ?? null, $reqData['recordId']]);
                        $count++;
                    }
                    $pdo->commit();
                    jsonResponse(['success' => true, 'count' => $count]);
                } catch (Exception $e) {
                    $pdo->rollBack();
                    jsonResponse(['error' => 'Update failed: ' . $e->getMessage()], 400);
                }
            }
            break;

        case 'validation-list':
            if ($method === 'GET') {
                $status = $_GET['status'] ?? 'Pending';
                
                $sql = "SELECT 
                            a.attendance_id as id,
                            a.attendance_id as recordId,
                            s.student_id as studentId,
                            s.roll_no as roll,
                            s.student_name as name,
                            sub.department as dept,
                            s.division as `div`,
                            sub.semester as sem,
                            sub.subject_name as subject,
                            l.lecture_number as lecture,
                            a.attendance_status as status,
                            a.validation_status as validationStatus,
                            DATE_FORMAT(l.lecture_date, '%Y-%m-%d') as date,
                            COALESCE(f.faculty_name, 'Unknown') as faculty,
                            DATE_FORMAT(a.updated_at, '%h:%i %p') as time
                        FROM Attendance a
                        JOIN Students s ON a.student_id = s.student_id
                        JOIN Lecture l ON a.lecture_id = l.lecture_id
                        JOIN Subjects sub ON l.subject_id = sub.subject_id
                        LEFT JOIN Faculty f ON l.faculty_id = f.faculty_id
                        WHERE 1=1 ";
                
                $params = [];
                if ($status !== 'All') {
                    $sql .= " AND a.validation_status = ?";
                    $params[] = $status;
                }
                
                // Add optional filters
                if (!empty($_GET['dept'])) { $sql .= " AND sub.department = ?"; $params[] = $_GET['dept']; }
                if (!empty($_GET['sem'])) { $sql .= " AND sub.semester = ?"; $params[] = $_GET['sem']; }
                if (!empty($_GET['div'])) { $sql .= " AND s.division = ?"; $params[] = $_GET['div']; }
                if (!empty($_GET['subject'])) { $sql .= " AND sub.subject_name = ?"; $params[] = $_GET['subject']; }
                if (!empty($_GET['date'])) { $sql .= " AND DATE_FORMAT(l.lecture_date, '%Y-%m-%d') = ?"; $params[] = $_GET['date']; }
                if (!empty($_GET['lectNo'])) { $sql .= " AND l.lecture_number = ?"; $params[] = $_GET['lectNo']; }
                if (!empty($_GET['attStatus'])) { $sql .= " AND a.attendance_status = ?"; $params[] = $_GET['attStatus']; }
                
                $sql .= " ORDER BY a.updated_at DESC";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                jsonResponse($stmt->fetchAll());
            }
            break;

        case 'validate-attendance':
            if ($method === 'POST') {
                $ids = $jsonBody['ids'] ?? [];
                $action = $jsonBody['action'] ?? '';
                
                if (empty($ids) || !in_array($action, ['Approved', 'Rejected'])) {
                    jsonResponse(['error' => 'Invalid parameters'], 400);
                }
                
                $pdo->beginTransaction();
                try {
                    $stmt = $pdo->query("SELECT faculty_id FROM Faculty LIMIT 1");
                    $facultyId = $stmt->fetchColumn();
                    
                    // Update all selected ids
                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    $stmt = $pdo->prepare("UPDATE Attendance SET validation_status = ?, validated_at = NOW(), validated_by = ? WHERE attendance_id IN ($placeholders)");
                    $params = array_merge([$action, $facultyId], $ids);
                    $stmt->execute($params);
                    
                    $pdo->commit();
                    jsonResponse(['success' => true, 'count' => count($ids)]);
                } catch (Exception $e) {
                    $pdo->rollBack();
                    jsonResponse(['error' => $e->getMessage()], 400);
                }
            }
            break;

        case 'history':
            if ($method === 'GET') {
                $sql = "SELECT 
                            l.lecture_id as id,
                            DATE_FORMAT(l.lecture_date, '%Y-%m-%d') as date,
                            sub.department as dept,
                            sub.semester as sem,
                            s.division as `div`,
                            sub.subject_name as subject,
                            l.lecture_number as lectNo,
                            a.attendance_status as status,
                            a.remarks as remarks,
                            a.created_at as created_at,
                            s.roll_no as roll,
                            s.student_name as name,
                            f.faculty_name as faculty,
                            a.validation_status as validationStatus
                        FROM Attendance a
                        JOIN Lecture l ON a.lecture_id = l.lecture_id
                        JOIN Subjects sub ON l.subject_id = sub.subject_id
                        JOIN Students s ON a.student_id = s.student_id
                        LEFT JOIN Faculty f ON l.faculty_id = f.faculty_id
                        ORDER BY l.lecture_date DESC, l.lecture_id DESC";
                $stmt = $pdo->query($sql);
                $rows = $stmt->fetchAll();
                
                $history = [];
                foreach ($rows as $row) {
                    $key = $row['id'] . '-' . $row['div'];
                    if (!isset($history[$key])) {
                        // Extract time from created_at or default to 10:00 AM
                        $createdTime = $row['created_at'] ? date('h:i A', strtotime($row['created_at'])) : '10:00 AM';
                        $createdDate = $row['created_at'] ? date('Y-m-d', strtotime($row['created_at'])) : $row['date'];
                        
                        $history[$key] = [
                            'id' => $key, // Unique ID combining lecture and division
                            'date' => $row['date'],
                            'created_date' => $createdDate,
                            'created_time' => $createdTime,
                            'lecture' => $row['lectNo'],
                            'subject' => $row['subject'],
                            'dept' => $row['dept'],
                            'sem' => $row['sem'],
                            'div' => $row['div'],
                            'faculty' => $row['faculty'],
                            'validationStatus' => $row['validationStatus'],
                            'students' => []
                        ];
                    }
                    // Determine inferred status based on remarks if it exists
                    $inferredStatus = ucfirst($row['status']); // Present or Absent
                    if (!empty($row['remarks'])) {
                        if (stripos($row['remarks'], 'late') !== false) {
                            $inferredStatus = 'Late';
                        } else if (stripos($row['remarks'], 'leave') !== false) {
                            $inferredStatus = 'On Leave';
                        } else if (stripos($row['remarks'], 'not marked') !== false) {
                            $inferredStatus = 'Not Marked';
                        }
                    }
                    
                    $history[$key]['students'][] = [
                        'roll' => $row['roll'],
                        'name' => $row['name'],
                        'status' => $inferredStatus,
                        'original_status' => $row['status'],
                        'remarks' => $row['remarks']
                    ];
                }
                
                jsonResponse(array_values($history));
            }
            break;

        case 'master-data':
            if ($method === 'GET') {
                try {
                    // Departments
                    $deptStmt = $pdo->query("SELECT department_name FROM departments ORDER BY department_name");
                    $departments = $deptStmt->fetchAll(PDO::FETCH_COLUMN);

                    // Faculty
                    $facStmt = $pdo->query("SELECT faculty_name FROM faculty ORDER BY faculty_name");
                    $faculty = $facStmt->fetchAll(PDO::FETCH_COLUMN);

                    // Subjects, mapped by department and semester
                    $subStmt = $pdo->query("SELECT subject_name, semester, department FROM subjects");
                    $subjectRows = $subStmt->fetchAll(PDO::FETCH_ASSOC);
                    $subjects = [];
                    foreach ($subjectRows as $row) {
                        $d = $row['department'];
                        $s = $row['semester'];
                        if (!isset($subjects[$d])) $subjects[$d] = [];
                        if (!isset($subjects[$d][$s])) $subjects[$d][$s] = [];
                        $subjects[$d][$s][] = $row['subject_name'];
                    }

                    // Divisions, mapped by department and semester
                    $divStmt = $pdo->query("SELECT DISTINCT division, department, semester FROM students");
                    $divRows = $divStmt->fetchAll(PDO::FETCH_ASSOC);
                    $divisions = [];
                    foreach ($divRows as $row) {
                        $d = $row['department'];
                        $s = $row['semester'];
                        if (!isset($divisions[$d])) $divisions[$d] = [];
                        if (!isset($divisions[$d][$s])) $divisions[$d][$s] = [];
                        $divisions[$d][$s][] = $row['division'];
                    }

                    // Semesters (global distinct list) - Fallback to standard 8 semesters
                    $semStmt = $pdo->query("SELECT DISTINCT semester FROM subjects ORDER BY semester");
                    $dbSemesters = $semStmt->fetchAll(PDO::FETCH_COLUMN);
                    $semesters = array_unique(array_merge(['Semester 1', 'Semester 2', 'Semester 3', 'Semester 4', 'Semester 5', 'Semester 6', 'Semester 7', 'Semester 8'], $dbSemesters));

                    jsonResponse([
                        'departments' => $departments,
                        'faculty' => $faculty,
                        'subjects' => $subjects,
                        'divisions' => $divisions,
                        'semesters' => $semesters
                    ]);
                } catch (Exception $e) {
                    jsonResponse(['error' => 'Failed to fetch master data'], 500);
                }
            }
            break;

        default:
            jsonResponse(['error' => 'Endpoint not found'], 404);
            break;
    }
} catch (Exception $e) {
    jsonResponse(['error' => 'Server error: ' . $e->getMessage()], 500);
}
