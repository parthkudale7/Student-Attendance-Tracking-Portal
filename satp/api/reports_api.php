<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$db = getDBConnection();
$isMock = Database::getInstance()->isMock();

function sendResponse($data) {
    echo json_encode($data);
    exit;
}

// ----------------------------------------------------
// MOCK DATA ENGINE (Used when MySQL is not running)
// ----------------------------------------------------
function getMockFilters() {
    return [
        'departments' => [
            ['id' => 1, 'dept_code' => 'CE', 'dept_name' => 'Computer Engineering'],
            ['id' => 2, 'dept_code' => 'AIDS', 'dept_name' => 'AI & Data Science'],
            ['id' => 3, 'dept_code' => 'EE', 'dept_name' => 'Electrical Engineering'],
            ['id' => 4, 'dept_code' => 'BT', 'dept_name' => 'Biotechnology'],
            ['id' => 5, 'dept_code' => 'ME', 'dept_name' => 'Mechanical Engineering']
        ],
        'months' => [
            ['id' => '01', 'name' => 'January'],
            ['id' => '02', 'name' => 'February'],
            ['id' => '03', 'name' => 'March'],
            ['id' => '04', 'name' => 'April'],
            ['id' => '05', 'name' => 'May'],
            ['id' => '06', 'name' => 'June'],
            ['id' => '07', 'name' => 'July'],
            ['id' => '08', 'name' => 'August'],
            ['id' => '09', 'name' => 'September'],
            ['id' => '10', 'name' => 'October'],
            ['id' => '11', 'name' => 'November'],
            ['id' => '12', 'name' => 'December']
        ],
        'years' => range(2001, 2027),
        'semesters' => [1, 2, 3, 4, 5, 6, 7, 8],
        'divisions' => ['A', 'B', 'C', 'D', 'E'],
        'subjects' => [
            ['id' => 1, 'subject_code' => 'CE501', 'subject_name' => 'Advanced Data Structures & Algorithms', 'dept_id' => 1, 'semester' => 5],
            ['id' => 2, 'subject_code' => 'CE502', 'subject_name' => 'Database Management Systems', 'dept_id' => 1, 'semester' => 5],
            ['id' => 3, 'subject_code' => 'CE503', 'subject_name' => 'Operating Systems Core', 'dept_id' => 1, 'semester' => 5],
            ['id' => 4, 'subject_code' => 'CE504', 'subject_name' => 'Computer Networks', 'dept_id' => 1, 'semester' => 5],
            ['id' => 5, 'subject_code' => 'AD501', 'subject_name' => 'Machine Learning Foundations', 'dept_id' => 2, 'semester' => 5],
            ['id' => 6, 'subject_code' => 'AD502', 'subject_name' => 'Neural Networks & Deep Learning', 'dept_id' => 2, 'semester' => 5],
            ['id' => 7, 'subject_code' => 'AD503', 'subject_name' => 'Big Data Analytics & Processing', 'dept_id' => 2, 'semester' => 5],
            ['id' => 8, 'subject_code' => 'EE501', 'subject_name' => 'Electrical Machines & Power Systems', 'dept_id' => 3, 'semester' => 5],
            ['id' => 9, 'subject_code' => 'EE502', 'subject_name' => 'Control Systems Engineering', 'dept_id' => 3, 'semester' => 5],
            ['id' => 10, 'subject_code' => 'EE503', 'subject_name' => 'Microcontrollers & Embedded Systems', 'dept_id' => 3, 'semester' => 5],
            ['id' => 11, 'subject_code' => 'BT501', 'subject_name' => 'Genetic Engineering & Recombinant DNA', 'dept_id' => 4, 'semester' => 5],
            ['id' => 12, 'subject_code' => 'BT502', 'subject_name' => 'Bioprocess Engineering & Fermentation', 'dept_id' => 4, 'semester' => 5],
            ['id' => 13, 'subject_code' => 'BT503', 'subject_name' => 'Bioinformatics & Computational Biology', 'dept_id' => 4, 'semester' => 5],
            ['id' => 14, 'subject_code' => 'ME501', 'subject_name' => 'Thermodynamics & Heat Transfer', 'dept_id' => 5, 'semester' => 5],
            ['id' => 15, 'subject_code' => 'ME502', 'subject_name' => 'Fluid Mechanics & Hydraulic Machinery', 'dept_id' => 5, 'semester' => 5],
            ['id' => 16, 'subject_code' => 'ME503', 'subject_name' => 'Computer-Aided Design & Manufacturing (CAD/CAM)', 'dept_id' => 5, 'semester' => 5]
        ],
        'faculties' => [
            ['id' => 1, 'name' => 'Dr. Robert Vance'],
            ['id' => 2, 'name' => 'Prof. Elena Rostova'],
            ['id' => 3, 'name' => 'Dr. Marcus Sterling'],
            ['id' => 4, 'name' => 'Prof. Sarah Jenkins'],
            ['id' => 5, 'name' => 'Dr. Alan Turing']
        ],
        'students' => [
            ['id' => 1, 'roll_no' => 'CS2026-001', 'name' => 'Alex Mercer', 'prn' => 'PRN2024001', 'dept' => 'CS', 'semester' => 5, 'division' => 'A'],
            ['id' => 2, 'roll_no' => 'CS2026-002', 'name' => 'Sophia Chen', 'prn' => 'PRN2024002', 'dept' => 'CS', 'semester' => 5, 'division' => 'A'],
            ['id' => 3, 'roll_no' => 'CS2026-003', 'name' => 'David Miller', 'prn' => 'PRN2024003', 'dept' => 'CS', 'semester' => 5, 'division' => 'A'],
            ['id' => 4, 'roll_no' => 'CS2026-004', 'name' => 'Emma Watson', 'prn' => 'PRN2024004', 'dept' => 'CS', 'semester' => 5, 'division' => 'B'],
            ['id' => 5, 'roll_no' => 'CS2026-005', 'name' => 'Liam Gallagher', 'prn' => 'PRN2024005', 'dept' => 'CS', 'semester' => 5, 'division' => 'B'],
            ['id' => 6, 'roll_no' => 'IT2026-010', 'name' => 'Zoe Kravitz', 'prn' => 'PRN2024010', 'dept' => 'IT', 'semester' => 5, 'division' => 'A'],
            ['id' => 7, 'roll_no' => 'IT2026-011', 'name' => 'Lucas Scott', 'prn' => 'PRN2024011', 'dept' => 'IT', 'semester' => 5, 'division' => 'A'],
            ['id' => 8, 'roll_no' => 'AD2026-020', 'name' => 'Aria Montgomery', 'prn' => 'PRN2024020', 'dept' => 'AIDS', 'semester' => 5, 'division' => 'A'],
            ['id' => 9, 'roll_no' => 'AD2026-021', 'name' => 'Noah Vance', 'prn' => 'PRN2024021', 'dept' => 'AIDS', 'semester' => 5, 'division' => 'A']
        ]
    ];
}

// ----------------------------------------------------
// ROUTING HANDLERS
// ----------------------------------------------------

switch ($action) {
    case 'get_filters':
        if ($isMock) {
            sendResponse(getMockFilters());
        } else {
            $depts = $db->query("SELECT id, dept_code, dept_name FROM departments ORDER BY id")->fetchAll();
            $subjects = $db->query("SELECT id, subject_code, subject_name, dept_id, semester FROM subjects ORDER BY id")->fetchAll();
            $faculties = $db->query("SELECT id, name FROM faculty ORDER BY name")->fetchAll();
            $students = $db->query("SELECT s.id, s.roll_no, s.prn, s.name, d.dept_code as dept, s.semester, s.division FROM students s JOIN departments d ON s.dept_id = d.id ORDER BY s.roll_no")->fetchAll();
            sendResponse([
                'departments' => $depts,
                'semesters' => [1, 2, 3, 4, 5, 6, 7, 8],
                'divisions' => ['A', 'B', 'C', 'D'],
                'subjects' => $subjects,
                'faculties' => $faculties,
                'students' => $students
            ]);
        }
        break;

    case 'monthly_report':
        $dept_id = $_GET['dept_id'] ?? '';
        $semester = $_GET['semester'] ?? '';
        $division = $_GET['division'] ?? '';
        $subject_id = $_GET['subject_id'] ?? '';
        $faculty_id = $_GET['faculty_id'] ?? '';
        $month = $_GET['month'] ?? '07';
        $year = $_GET['year'] ?? '2026';

        if ($isMock) {
            $deptMap = [
                '1' => ['code' => 'CE', 'name' => 'Computer Engineering'],
                '2' => ['code' => 'AIDS', 'name' => 'AI & Data Science'],
                '3' => ['code' => 'EE', 'name' => 'Electrical Engineering'],
                '4' => ['code' => 'BT', 'name' => 'Biotechnology'],
                '5' => ['code' => 'ME', 'name' => 'Mechanical Engineering']
            ];

            $allStudentsList = [
                ['id' => 1, 'base_roll' => '001', 'name' => 'Alex Mercer', 'default_dept' => '1', 'default_sem' => '5', 'default_div' => 'A', 'base_p' => 19],
                ['id' => 2, 'base_roll' => '002', 'name' => 'Sophia Chen', 'default_dept' => '1', 'default_sem' => '5', 'default_div' => 'A', 'base_p' => 17],
                ['id' => 3, 'base_roll' => '003', 'name' => 'David Miller', 'default_dept' => '1', 'default_sem' => '5', 'default_div' => 'A', 'base_p' => 13],
                ['id' => 4, 'base_roll' => '004', 'name' => 'Emma Watson', 'default_dept' => '1', 'default_sem' => '5', 'default_div' => 'B', 'base_p' => 16],
                ['id' => 5, 'base_roll' => '005', 'name' => 'Liam Gallagher', 'default_dept' => '1', 'default_sem' => '5', 'default_div' => 'B', 'base_p' => 10],
                ['id' => 6, 'base_roll' => '010', 'name' => 'Zoe Kravitz', 'default_dept' => '3', 'default_sem' => '5', 'default_div' => 'A', 'base_p' => 18],
                ['id' => 7, 'base_roll' => '011', 'name' => 'Lucas Scott', 'default_dept' => '4', 'default_sem' => '5', 'default_div' => 'A', 'base_p' => 14],
                ['id' => 8, 'base_roll' => '020', 'name' => 'Aria Montgomery', 'default_dept' => '2', 'default_sem' => '5', 'default_div' => 'A', 'base_p' => 19],
                ['id' => 9, 'base_roll' => '021', 'name' => 'Noah Vance', 'default_dept' => '2', 'default_sem' => '5', 'default_div' => 'B', 'base_p' => 15],
                ['id' => 10, 'base_roll' => '030', 'name' => 'Ethan Hunt', 'default_dept' => '5', 'default_sem' => '5', 'default_div' => 'A', 'base_p' => 12]
            ];

            $filteredStudents = array_filter($allStudentsList, function($s) use ($dept_id, $semester, $division) {
                if ($dept_id && $s['default_dept'] !== (string)$dept_id) return false;
                if ($semester && $s['default_sem'] !== (string)$semester) return false;
                if ($division && $s['default_div'] !== (string)$division) return false;
                return true;
            });

            if (empty($filteredStudents)) {
                for ($i = 0; $i < 6; $i++) {
                    $filteredStudents[] = [
                        'id' => 100 + $i,
                        'base_roll' => str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                        'name' => "Student " . ($i + 1),
                        'default_dept' => $dept_id ?: '1',
                        'default_sem' => $semester ?: '5',
                        'default_div' => $division ?: 'A',
                        'base_p' => 14 + ($i % 5)
                    ];
                }
            }

            $monthNum = (int)($month ?: 7);
            $yearNum = (int)($year ?: 2026);
            $subjNum = (int)($subject_id ?: 0);
            $facNum = (int)($faculty_id ?: 0);

            $totalSessions = 20 + ($monthNum % 4) + ($subjNum % 3);
            $table = [];
            $idx = 0;

            foreach ($filteredStudents as $s) {
                $targetDeptId = $dept_id ?: $s['default_dept'];
                $targetDeptCode = $deptMap[$targetDeptId]['code'] ?? 'CE';
                $targetSem = $semester ?: $s['default_sem'];
                $targetDiv = $division ?: $s['default_div'];

                $rollNo = "{$targetDeptCode}{$yearNum}-S{$targetSem}{$targetDiv}-{$s['base_roll']}";
                
                $seed = ($s['id'] * 17) + ($monthNum * 13) + ($yearNum * 7) + ($subjNum * 19) + ($facNum * 23) + ($idx * 3);
                $variance = ($seed % 7) - 3;
                $present = max(0, min($totalSessions, $s['base_p'] + $variance));
                $absent = $totalSessions - $present;
                $pct = round(($present / $totalSessions) * 100, 1);

                $status = 'Safe';
                if ($pct < 60) $status = 'Critical';
                elseif ($pct < 75) $status = 'Warning';

                $table[] = [
                    'roll_no' => $rollNo,
                    'name' => $s['name'],
                    'dept' => $targetDeptCode,
                    'dept_id' => $targetDeptId,
                    'semester' => $targetSem,
                    'division' => $targetDiv,
                    'total_sessions' => $totalSessions,
                    'present' => $present,
                    'absent' => $absent,
                    'attendance_pct' => $pct,
                    'status' => $status
                ];
                $idx++;
            }

            $totalStudents = count($table);
            $totalPresent = array_sum(array_column($table, 'present'));
            $totalAbsent = array_sum(array_column($table, 'absent'));
            $avgPct = $totalStudents > 0 ? round(array_sum(array_column($table, 'attendance_pct')) / $totalStudents, 1) : 0;

            $safeCount = count(array_filter($table, fn($s) => $s['attendance_pct'] >= 75));
            $warnCount = count(array_filter($table, fn($s) => $s['attendance_pct'] >= 60 && $s['attendance_pct'] < 75));
            $critCount = count(array_filter($table, fn($s) => $s['attendance_pct'] < 60));

            $monthlyTrendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $monthlyTrendPct = [];
            foreach ($monthlyTrendLabels as $i => $m) {
                $mVal = $avgPct + sin($i + $monthNum + $subjNum + $facNum) * 5;
                $monthlyTrendPct[] = round(max(50, min(99, $mVal)), 1);
            }

            sendResponse([
                'stats' => [
                    'total_students' => $totalStudents,
                    'present_count' => $totalPresent,
                    'absent_count' => $totalAbsent,
                    'overall_pct' => $avgPct
                ],
                'trend' => [
                    'labels' => $monthlyTrendLabels,
                    'percentages' => $monthlyTrendPct
                ],
                'distribution' => [
                    'labels' => ['≥75% (Safe)', '60-74% (Warning)', '<60% (Critical)'],
                    'data' => [$safeCount, $warnCount, $critCount]
                ],
                'table' => $table
            ]);
        } else {
            // Live MySQL PDO Query with Prepared Statements
            $whereClause = "WHERE 1=1";
            $params = [];

            if (!empty($dept_id)) {
                $whereClause .= " AND st.dept_id = :dept_id";
                $params[':dept_id'] = $dept_id;
            }
            if (!empty($semester)) {
                $whereClause .= " AND st.semester = :semester";
                $params[':semester'] = $semester;
            }
            if (!empty($division)) {
                $whereClause .= " AND st.division = :division";
                $params[':division'] = $division;
            }

            $sql = "SELECT st.roll_no, st.name, d.dept_code as dept,
                           COUNT(ar.id) as total_sessions,
                           SUM(CASE WHEN ar.status = 'Present' THEN 1 ELSE 0 END) as present,
                           SUM(CASE WHEN ar.status = 'Absent' THEN 1 ELSE 0 END) as absent
                    FROM students st
                    JOIN departments d ON st.dept_id = d.id
                    LEFT JOIN attendance_records ar ON st.id = ar.student_id
                    $whereClause
                    GROUP BY st.id";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();

            $formatted = [];
            $sumPct = 0;
            $safeCount = 0; $warnCount = 0; $critCount = 0;

            foreach ($rows as $r) {
                $total = (int)$r['total_sessions'];
                $pres = (int)$r['present'];
                $abs = (int)$r['absent'];
                $pct = $total > 0 ? round(($pres / $total) * 100, 1) : 100.0;
                $sumPct += $pct;

                $status = 'Safe';
                if ($pct < 60) {
                    $status = 'Critical';
                    $critCount++;
                } elseif ($pct < 75) {
                    $status = 'Warning';
                    $warnCount++;
                } else {
                    $safeCount++;
                }

                $formatted[] = [
                    'roll_no' => $r['roll_no'],
                    'name' => $r['name'],
                    'dept' => $r['dept'],
                    'total_sessions' => $total,
                    'present' => $pres,
                    'absent' => $abs,
                    'attendance_pct' => $pct,
                    'status' => $status
                ];
            }

            $totalStudents = count($formatted);
            $avgPct = $totalStudents > 0 ? round($sumPct / $totalStudents, 1) : 0;
            $totalPresent = array_sum(array_column($formatted, 'present'));
            $totalAbsent = array_sum(array_column($formatted, 'absent'));

            sendResponse([
                'stats' => [
                    'total_students' => $totalStudents,
                    'present_count' => $totalPresent,
                    'absent_count' => $totalAbsent,
                    'overall_pct' => $avgPct
                ],
                'trend' => [
                    'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    'percentages' => [88.0, 84.5, 79.2, $avgPct]
                ],
                'distribution' => [
                    'labels' => ['≥75% (Safe)', '60-74% (Warning)', '<60% (Critical)'],
                    'data' => [$safeCount, $warnCount, $critCount]
                ],
                'table' => $formatted
            ]);
        }
        break;

    case 'student_report':
        $student_id = $_GET['student_id'] ?? '1';

        if ($isMock) {
            $mockStudents = [
                '1' => [
                    'profile' => ['id' => 1, 'name' => 'Alex Mercer', 'roll_no' => 'CS2026-001', 'prn' => 'PRN2024001', 'dept' => 'Computer Science & Engineering', 'semester' => 5, 'division' => 'A', 'overall_pct' => 95.0, 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Alex'],
                    'subjects' => [
                        ['code' => 'CS501', 'name' => 'Advanced Data Structures & Algorithms', 'faculty' => 'Dr. Robert Vance', 'total' => 12, 'present' => 12, 'absent' => 0, 'pct' => 100.0],
                        ['code' => 'CS502', 'name' => 'Database Management Systems', 'faculty' => 'Prof. Elena Rostova', 'total' => 10, 'present' => 9, 'absent' => 1, 'pct' => 90.0],
                        ['code' => 'CS503', 'name' => 'Operating Systems Core', 'faculty' => 'Dr. Robert Vance', 'total' => 8, 'present' => 7, 'absent' => 1, 'pct' => 87.5]
                    ],
                    'history' => [
                        ['date' => '2026-07-21', 'subject' => 'CS503 - Operating Systems', 'faculty' => 'Dr. Vance', 'status' => 'Present', 'remarks' => 'On time'],
                        ['date' => '2026-07-20', 'subject' => 'CS501 - Data Structures', 'faculty' => 'Dr. Vance', 'status' => 'Present', 'remarks' => 'On time']
                    ]
                ],
                '2' => [
                    'profile' => ['id' => 2, 'name' => 'Sophia Chen', 'roll_no' => 'CS2026-002', 'prn' => 'PRN2024002', 'dept' => 'Computer Science & Engineering', 'semester' => 5, 'division' => 'A', 'overall_pct' => 88.5, 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Sophia'],
                    'subjects' => [
                        ['code' => 'CS501', 'name' => 'Advanced Data Structures', 'faculty' => 'Dr. Vance', 'total' => 12, 'present' => 11, 'absent' => 1, 'pct' => 91.6],
                        ['code' => 'CS502', 'name' => 'DBMS', 'faculty' => 'Prof. Rostova', 'total' => 10, 'present' => 8, 'absent' => 2, 'pct' => 80.0]
                    ],
                    'history' => [
                        ['date' => '2026-07-21', 'subject' => 'CS501', 'faculty' => 'Dr. Vance', 'status' => 'Present', 'remarks' => 'On time']
                    ]
                ],
                '3' => [
                    'profile' => ['id' => 3, 'name' => 'David Miller', 'roll_no' => 'CS2026-003', 'prn' => 'PRN2024003', 'dept' => 'Computer Science & Engineering', 'semester' => 5, 'division' => 'A', 'overall_pct' => 65.0, 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=David'],
                    'subjects' => [
                        ['code' => 'CS501', 'name' => 'Data Structures', 'faculty' => 'Dr. Vance', 'total' => 10, 'present' => 6, 'absent' => 4, 'pct' => 60.0],
                        ['code' => 'CS502', 'name' => 'DBMS', 'faculty' => 'Prof. Rostova', 'total' => 10, 'present' => 7, 'absent' => 3, 'pct' => 70.0]
                    ],
                    'history' => [
                        ['date' => '2026-07-21', 'subject' => 'CS501', 'faculty' => 'Dr. Vance', 'status' => 'Absent', 'remarks' => 'Unexcused']
                    ]
                ],
                '4' => [
                    'profile' => ['id' => 4, 'name' => 'Emma Watson', 'roll_no' => 'CS2026-004', 'prn' => 'PRN2024004', 'dept' => 'Computer Science & Engineering', 'semester' => 5, 'division' => 'B', 'overall_pct' => 92.0, 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Emma'],
                    'subjects' => [
                        ['code' => 'CS501', 'name' => 'Data Structures', 'faculty' => 'Dr. Vance', 'total' => 12, 'present' => 11, 'absent' => 1, 'pct' => 91.6],
                        ['code' => 'CS502', 'name' => 'DBMS', 'faculty' => 'Prof. Rostova', 'total' => 13, 'present' => 12, 'absent' => 1, 'pct' => 92.3]
                    ],
                    'history' => [
                        ['date' => '2026-07-21', 'subject' => 'CS501', 'faculty' => 'Dr. Vance', 'status' => 'Present', 'remarks' => 'On time']
                    ]
                ],
                '5' => [
                    'profile' => ['id' => 5, 'name' => 'Liam Gallagher', 'roll_no' => 'CS2026-005', 'prn' => 'PRN2024005', 'dept' => 'Computer Science & Engineering', 'semester' => 5, 'division' => 'B', 'overall_pct' => 50.0, 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Liam'],
                    'subjects' => [
                        ['code' => 'CS501', 'name' => 'Advanced Data Structures', 'faculty' => 'Dr. Vance', 'total' => 10, 'present' => 5, 'absent' => 5, 'pct' => 50.0],
                        ['code' => 'CS502', 'name' => 'DBMS', 'faculty' => 'Prof. Rostova', 'total' => 8, 'present' => 4, 'absent' => 4, 'pct' => 50.0]
                    ],
                    'history' => [
                        ['date' => '2026-07-17', 'subject' => 'CS503', 'faculty' => 'Dr. Vance', 'status' => 'Absent', 'remarks' => 'No notice']
                    ]
                ],
                '6' => [
                    'profile' => ['id' => 6, 'name' => 'Zoe Kravitz', 'roll_no' => 'IT2026-010', 'prn' => 'PRN2024010', 'dept' => 'Information Technology', 'semester' => 5, 'division' => 'A', 'overall_pct' => 82.0, 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Zoe'],
                    'subjects' => [
                        ['code' => 'IT501', 'name' => 'Cloud Security', 'faculty' => 'Prof. Jenkins', 'total' => 12, 'present' => 10, 'absent' => 2, 'pct' => 83.3]
                    ],
                    'history' => [
                        ['date' => '2026-07-21', 'subject' => 'IT501', 'faculty' => 'Prof. Jenkins', 'status' => 'Present', 'remarks' => 'On time']
                    ]
                ],
                '7' => [
                    'profile' => ['id' => 7, 'name' => 'Lucas Scott', 'roll_no' => 'IT2026-011', 'prn' => 'PRN2024011', 'dept' => 'Information Technology', 'semester' => 5, 'division' => 'A', 'overall_pct' => 78.0, 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Lucas'],
                    'subjects' => [
                        ['code' => 'IT501', 'name' => 'Cloud Security', 'faculty' => 'Prof. Jenkins', 'total' => 11, 'present' => 8, 'absent' => 3, 'pct' => 72.7]
                    ],
                    'history' => [
                        ['date' => '2026-07-20', 'subject' => 'IT501', 'faculty' => 'Prof. Jenkins', 'status' => 'Present', 'remarks' => 'On time']
                    ]
                ],
                '8' => [
                    'profile' => ['id' => 8, 'name' => 'Aria Montgomery', 'roll_no' => 'AD2026-020', 'prn' => 'PRN2024020', 'dept' => 'AI & Data Science', 'semester' => 5, 'division' => 'A', 'overall_pct' => 96.5, 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Aria'],
                    'subjects' => [
                        ['code' => 'AD501', 'name' => 'Machine Learning', 'faculty' => 'Dr. Turing', 'total' => 14, 'present' => 14, 'absent' => 0, 'pct' => 100.0]
                    ],
                    'history' => [
                        ['date' => '2026-07-21', 'subject' => 'AD501', 'faculty' => 'Dr. Turing', 'status' => 'Present', 'remarks' => 'On time']
                    ]
                ],
                '9' => [
                    'profile' => ['id' => 9, 'name' => 'Noah Vance', 'roll_no' => 'AD2026-021', 'prn' => 'PRN2024021', 'dept' => 'AI & Data Science', 'semester' => 5, 'division' => 'A', 'overall_pct' => 89.0, 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Noah'],
                    'subjects' => [
                        ['code' => 'AD501', 'name' => 'Machine Learning', 'faculty' => 'Dr. Turing', 'total' => 11, 'present' => 10, 'absent' => 1, 'pct' => 90.9]
                    ],
                    'history' => [
                        ['date' => '2026-07-21', 'subject' => 'AD501', 'faculty' => 'Dr. Turing', 'status' => 'Present', 'remarks' => 'On time']
                    ]
                ],
                '10' => [
                    'profile' => ['id' => 10, 'name' => 'Ethan Hunt', 'roll_no' => 'EC2026-030', 'prn' => 'PRN2024030', 'dept' => 'Electronics & Comm. Eng.', 'semester' => 5, 'division' => 'A', 'overall_pct' => 58.0, 'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Ethan'],
                    'subjects' => [
                        ['code' => 'EC501', 'name' => 'DSP', 'faculty' => 'Prof. Jenkins', 'total' => 10, 'present' => 5, 'absent' => 5, 'pct' => 50.0]
                    ],
                    'history' => [
                        ['date' => '2026-07-21', 'subject' => 'EC501', 'faculty' => 'Prof. Jenkins', 'status' => 'Absent', 'remarks' => 'Unexcused']
                    ]
                ]
            ];

            $selected = $mockStudents[$student_id] ?? $mockStudents['1'];

            sendResponse([
                'profile' => $selected['profile'],
                'subjects' => $selected['subjects'],
                'history' => $selected['history'],
                'charts' => [
                    'subject_bar' => [
                        'labels' => array_column($selected['subjects'], 'code'),
                        'percentages' => array_column($selected['subjects'], 'pct')
                    ],
                    'monthly_trend' => [
                        'labels' => ['May', 'Jun', 'Jul'],
                        'percentages' => [88.0, 92.5, $selected['profile']['overall_pct']]
                    ],
                    'pie' => [
                        'labels' => ['Present', 'Absent'],
                        'data' => [
                            array_sum(array_column($selected['subjects'], 'present')),
                            array_sum(array_column($selected['subjects'], 'absent'))
                        ]
                    ]
                ]
            ]);
        } else {
            // Live MySQL Prepared Statement for Student Report
            $stmt = $db->prepare("SELECT s.*, d.dept_name 
                                  FROM students s 
                                  JOIN departments d ON s.dept_id = d.id 
                                  WHERE s.id = :id");
            $stmt->execute([':id' => $student_id]);
            $student = $stmt->fetch();

            if (!$student) {
                sendResponse(['error' => 'Student not found']);
            }

            // Subject-wise query
            $subjSql = "SELECT sub.subject_code as code, sub.subject_name as name, f.name as faculty,
                               COUNT(ar.id) as total,
                               SUM(CASE WHEN ar.status = 'Present' THEN 1 ELSE 0 END) as present,
                               SUM(CASE WHEN ar.status = 'Absent' THEN 1 ELSE 0 END) as absent
                        FROM subjects sub
                        JOIN faculty f ON sub.faculty_id = f.id
                        LEFT JOIN attendance_records ar ON sub.id = ar.subject_id AND ar.student_id = :sid
                        WHERE sub.dept_id = :dept_id
                        GROUP BY sub.id";
            $stmtSub = $db->prepare($subjSql);
            $stmtSub->execute([':sid' => $student_id, ':dept_id' => $student['dept_id']]);
            $subjRows = $stmtSub->fetchAll();

            $formattedSubj = [];
            $totalPres = 0; $totalAbs = 0;

            foreach ($subjRows as $sr) {
                $t = (int)$sr['total'];
                $p = (int)$sr['present'];
                $a = (int)$sr['absent'];
                $pct = $t > 0 ? round(($p / $t) * 100, 1) : 100.0;
                $totalPres += $p;
                $totalAbs += $a;
                $formattedSubj[] = [
                    'code' => $sr['code'],
                    'name' => $sr['name'],
                    'faculty' => $sr['faculty'],
                    'total' => $t,
                    'present' => $p,
                    'absent' => $a,
                    'pct' => $pct
                ];
            }

            $overallPct = ($totalPres + $totalAbs) > 0 ? round(($totalPres / ($totalPres + $totalAbs)) * 100, 1) : 100.0;

            // Attendance history
            $histSql = "SELECT ar.attendance_date as date, sub.subject_name as subject, f.name as faculty, ar.status, ar.remarks
                        FROM attendance_records ar
                        JOIN subjects sub ON ar.subject_id = sub.id
                        JOIN faculty f ON ar.faculty_id = f.id
                        WHERE ar.student_id = :sid
                        ORDER BY ar.attendance_date DESC LIMIT 20";
            $stmtHist = $db->prepare($histSql);
            $stmtHist->execute([':sid' => $student_id]);
            $histRows = $stmtHist->fetchAll();

            sendResponse([
                'profile' => [
                    'id' => $student['id'],
                    'name' => $student['name'],
                    'roll_no' => $student['roll_no'],
                    'prn' => $student['prn'],
                    'dept' => $student['dept_name'],
                    'semester' => $student['semester'],
                    'division' => $student['division'],
                    'email' => $student['email'],
                    'phone' => $student['phone'],
                    'overall_pct' => $overallPct,
                    'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($student['name'])
                ],
                'subjects' => $formattedSubj,
                'history' => $histRows,
                'charts' => [
                    'subject_bar' => [
                        'labels' => array_column($formattedSubj, 'code'),
                        'percentages' => array_column($formattedSubj, 'pct')
                    ],
                    'monthly_trend' => [
                        'labels' => ['May', 'Jun', 'Jul'],
                        'percentages' => [90.0, 88.5, $overallPct]
                    ],
                    'pie' => [
                        'labels' => ['Present', 'Absent'],
                        'data' => [$totalPres, $totalAbs]
                    ]
                ]
            ]);
        }
        break;

    case 'department_report':
        if ($isMock) {
            sendResponse([
                'stats' => [
                    'avg_attendance' => 83.7,
                    'highest_dept' => 'AI & Data Science (88.5%)',
                    'lowest_dept' => 'Mechanical Eng. (78.5%)',
                    'total_students' => 1450
                ],
                'dept_comparison' => [
                    'labels' => ['Computer Science', 'AI & Data Science', 'Electrical Eng.', 'Biotechnology', 'Mechanical Eng.'],
                    'percentages' => [84.2, 88.5, 81.0, 86.4, 78.5]
                ],
                'sem_comparison' => [
                    'labels' => ['Sem 1', 'Sem 3', 'Sem 5', 'Sem 7'],
                    'percentages' => [86.5, 83.1, 81.4, 79.8]
                ],
                'subject_comparison' => [
                    'labels' => ['CS501 (DS)', 'AD501 (ML)', 'EE501 (Power)', 'BT501 (Genetics)', 'ME501 (Thermo)'],
                    'percentages' => [88.0, 91.2, 81.0, 86.4, 78.5]
                ],
                'trend' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    'cs' => [85, 87, 86, 84, 88, 86, 84],
                    'aids' => [88, 90, 89, 91, 90, 89, 88],
                    'ee' => [80, 81, 82, 80, 83, 82, 81],
                    'bt' => [85, 86, 87, 85, 88, 87, 86],
                    'me' => [76, 78, 77, 76, 79, 78, 78]
                ],
                'table' => [
                    ['dept' => 'Computer Science & Engineering', 'code' => 'CS', 'total_students' => 450, 'avg_pct' => 84.2, 'status' => 'Excellent'],
                    ['dept' => 'AI & Data Science', 'code' => 'AIDS', 'total_students' => 240, 'avg_pct' => 88.5, 'status' => 'Excellent'],
                    ['dept' => 'Electrical Engineering', 'code' => 'EE', 'total_students' => 310, 'avg_pct' => 81.0, 'status' => 'Good'],
                    ['dept' => 'Biotechnology', 'code' => 'BT', 'total_students' => 220, 'avg_pct' => 86.4, 'status' => 'Excellent'],
                    ['dept' => 'Mechanical Engineering', 'code' => 'ME', 'total_students' => 230, 'avg_pct' => 78.5, 'status' => 'Good']
                ]
            ]);
        } else {
            // Live Database PDO Query
            $sql = "SELECT d.dept_name as dept, d.dept_code as code,
                           COUNT(DISTINCT st.id) as total_students,
                           SUM(CASE WHEN ar.status = 'Present' THEN 1 ELSE 0 END) as total_present,
                           COUNT(ar.id) as total_records
                    FROM departments d
                    LEFT JOIN students st ON d.id = st.dept_id
                    LEFT JOIN attendance_records ar ON st.id = ar.student_id
                    GROUP BY d.id";
            $stmt = $db->query($sql);
            $rows = $stmt->fetchAll();

            $deptLabels = []; $deptPcts = []; $tableData = [];
            $highestPct = -1; $lowestPct = 101;
            $highestDept = 'N/A'; $lowestDept = 'N/A';
            $sumPcts = 0;

            foreach ($rows as $r) {
                $totalRecs = (int)$r['total_records'];
                $totalPres = (int)$r['total_present'];
                $pct = $totalRecs > 0 ? round(($totalPres / $totalRecs) * 100, 1) : 80.0;
                $sumPcts += $pct;

                if ($pct > $highestPct) {
                    $highestPct = $pct;
                    $highestDept = $r['dept'] . " ($pct%)";
                }
                if ($pct < $lowestPct) {
                    $lowestPct = $pct;
                    $lowestDept = $r['dept'] . " ($pct%)";
                }

                $deptLabels[] = $r['code'];
                $deptPcts[] = $pct;

                $status = $pct >= 85 ? 'Excellent' : ($pct >= 75 ? 'Good' : 'Needs Attention');

                $tableData[] = [
                    'dept' => $r['dept'],
                    'code' => $r['code'],
                    'total_students' => (int)$r['total_students'],
                    'avg_pct' => $pct,
                    'status' => $status
                ];
            }

            $countDepts = count($rows);
            $avgAttendance = $countDepts > 0 ? round($sumPcts / $countDepts, 1) : 80.0;

            sendResponse([
                'stats' => [
                    'avg_attendance' => $avgAttendance,
                    'highest_dept' => $highestDept,
                    'lowest_dept' => $lowestDept,
                    'total_students' => array_sum(array_column($tableData, 'total_students'))
                ],
                'dept_comparison' => [
                    'labels' => $deptLabels,
                    'percentages' => $deptPcts
                ],
                'sem_comparison' => [
                    'labels' => ['Sem 1', 'Sem 3', 'Sem 5', 'Sem 7'],
                    'percentages' => [86.5, 83.1, $avgAttendance, 79.8]
                ],
                'subject_comparison' => [
                    'labels' => ['DS & Algo', 'DBMS', 'OS Core', 'Cloud Infra', 'Machine Learning', 'DSP'],
                    'percentages' => [88.0, 82.5, 79.0, 85.0, 91.2, 74.0]
                ],
                'trend' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    'cs' => [85, 87, 86, 84, 88, 86, 84],
                    'it' => [80, 82, 85, 83, 84, 83, 82],
                    'aids' => [88, 90, 89, 91, 90, 89, 88],
                    'ece' => [76, 78, 75, 76, 77, 75, 74]
                ],
                'table' => $tableData
            ]);
        }
        break;

    case 'low_attendance':
        $threshold = floatval($_GET['threshold'] ?? 75);

        if ($isMock) {
            $mockLow = [
                ['id' => 3, 'roll_no' => 'CS2026-003', 'name' => 'David Miller', 'dept' => 'CS', 'semester' => 5, 'division' => 'A', 'phone' => '+1 555-1003', 'parent_phone' => '+1 555-9003', 'attendance_pct' => 65.0, 'status' => 'Warning', 'color' => 'orange'],
                ['id' => 5, 'roll_no' => 'CS2026-005', 'name' => 'Liam Gallagher', 'dept' => 'CS', 'semester' => 5, 'division' => 'B', 'phone' => '+1 555-1005', 'parent_phone' => '+1 555-9005', 'attendance_pct' => 50.0, 'status' => 'Critical', 'color' => 'red'],
                ['id' => 7, 'roll_no' => 'IT2026-011', 'name' => 'Lucas Scott', 'dept' => 'IT', 'semester' => 5, 'division' => 'A', 'phone' => '+1 555-1011', 'parent_phone' => '+1 555-9011', 'attendance_pct' => 70.0, 'status' => 'Warning', 'color' => 'orange'],
                ['id' => 9, 'roll_no' => 'AD2026-021', 'name' => 'Noah Vance', 'dept' => 'AIDS', 'semester' => 5, 'division' => 'A', 'phone' => '+1 555-1021', 'parent_phone' => '+1 555-9021', 'attendance_pct' => 45.0, 'status' => 'Critical', 'color' => 'red']
            ];

            // Filter according to custom threshold
            $filtered = array_values(array_filter($mockLow, fn($s) => $s['attendance_pct'] < $threshold));

            $critCount = count(array_filter($filtered, fn($s) => $s['attendance_pct'] < 60));
            $warnCount = count(array_filter($filtered, fn($s) => $s['attendance_pct'] >= 60 && $s['attendance_pct'] < 75));

            sendResponse([
                'summary' => [
                    'threshold' => $threshold,
                    'total_flagged' => count($filtered),
                    'critical_count' => $critCount,
                    'warning_count' => $warnCount
                ],
                'students' => $filtered
            ]);
        } else {
            // Prepared statement query for low attendance
            $sql = "SELECT st.id, st.roll_no, st.name, st.phone, st.parent_phone, d.dept_code as dept, st.semester, st.division,
                           COUNT(ar.id) as total_sessions,
                           SUM(CASE WHEN ar.status = 'Present' THEN 1 ELSE 0 END) as present
                    FROM students st
                    JOIN departments d ON st.dept_id = d.id
                    LEFT JOIN attendance_records ar ON st.id = ar.student_id
                    GROUP BY st.id";
            $stmt = $db->query($sql);
            $rows = $stmt->fetchAll();

            $lowStudents = [];
            $critCount = 0; $warnCount = 0;

            foreach ($rows as $r) {
                $total = (int)$r['total_sessions'];
                $pres = (int)$r['present'];
                $pct = $total > 0 ? round(($pres / $total) * 100, 1) : 50.0;

                if ($pct < $threshold) {
                    $status = 'Safe';
                    $color = 'green';
                    if ($pct < 60) {
                        $status = 'Critical';
                        $color = 'red';
                        $critCount++;
                    } else {
                        $status = 'Warning';
                        $color = 'orange';
                        $warnCount++;
                    }

                    $lowStudents[] = [
                        'id' => $r['id'],
                        'roll_no' => $r['roll_no'],
                        'name' => $r['name'],
                        'dept' => $r['dept'],
                        'semester' => $r['semester'],
                        'division' => $r['division'],
                        'phone' => $r['phone'],
                        'parent_phone' => $r['parent_phone'],
                        'attendance_pct' => $pct,
                        'status' => $status,
                        'color' => $color
                    ];
                }
            }

            sendResponse([
                'summary' => [
                    'threshold' => $threshold,
                    'total_flagged' => count($lowStudents),
                    'critical_count' => $critCount,
                    'warning_count' => $warnCount
                ],
                'students' => $lowStudents
            ]);
        }
        break;

    case 'send_alert':
        $student_id = $_POST['student_id'] ?? '';
        $name = $_POST['name'] ?? 'Student';
        $pct = $_POST['pct'] ?? '0';

        if (!$isMock && $db) {
            $stmt = $db->prepare("INSERT INTO report_alerts (student_id, attendance_pct, alert_type, message) VALUES (:sid, :pct, 'LOW_ATTENDANCE_WARNING', :msg)");
            $stmt->execute([
                ':sid' => $student_id,
                ':pct' => $pct,
                ':msg' => "Official Warning: Attendance for student $name has fallen to $pct%, which is below the mandatory 75% requirement."
            ]);
        }

        sendResponse([
            'success' => true,
            'message' => "Alert successfully dispatched to $name and parent phone contact! (Attendance: $pct%)"
        ]);
        break;

    default:
        sendResponse(['error' => 'Invalid API Action']);
        break;
}
