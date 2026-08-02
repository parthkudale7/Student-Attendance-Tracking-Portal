<?php
session_start();
require_once '../includes/auth_guard.php';
check_auth(['faculty', 'admin']);
require_once '../includes/db.php';

// Fetch detailed faculty information from database using logged-in user email
$facultyEmail = $_SESSION['email'] ?? '';
$facultyName = $_SESSION['name'] ?? 'Faculty Member';
$facultyDept = $_SESSION['department'] ?? 'Computer Science';
$facultyDesignation = $_SESSION['designation'] ?? 'Assistant Professor';
$facultyQualification = $_SESSION['qualification'] ?? '';
$facultyEmpId = $_SESSION['employee_id'] ?? ('FAC' . ($_SESSION['user_id'] ?? ''));
$facultyPhone = $_SESSION['phone'] ?? ($_SESSION['user_phone'] ?? '');
$facultyAvatar = $_SESSION['avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($facultyName) . '&background=4F7CFF&color=fff');

if (!empty($facultyEmail)) {
    try {
        $stmt = $pdo->prepare("SELECT f.*, d.department_name, d.department_code 
                               FROM faculties f 
                               LEFT JOIN departments d ON f.department_id = d.department_id 
                               WHERE f.email = ? LIMIT 1");
        $stmt->execute([$facultyEmail]);
        $fac = $stmt->fetch();
        if ($fac) {
            $facultyName = $fac['full_name'] ?: $facultyName;
            $facultyDept = $fac['department_name'] ?: ($fac['department_code'] ?: $facultyDept);
            $facultyDesignation = $fac['designation'] ?: $facultyDesignation;
            $facultyQualification = $fac['qualification'] ?: $facultyQualification;
            $facultyEmpId = $fac['employee_id'] ?: $facultyEmpId;
            $facultyPhone = $fac['phone'] ?: $facultyPhone;
            if (!empty($fac['photo'])) {
                $facultyAvatar = '../assets/img/faculties/' . $fac['photo'];
            }
        }
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Attendance Tracking</title>
    <!-- Google Fonts: Inter and Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <script>
        window.PHP_USER = {
            id: <?php echo json_encode($facultyEmpId); ?>,
            name: <?php echo json_encode($facultyName); ?>,
            email: <?php echo json_encode($facultyEmail); ?>,
            department: <?php echo json_encode($facultyDept); ?>,
            designation: <?php echo json_encode($facultyDesignation); ?>,
            qualification: <?php echo json_encode($facultyQualification); ?>,
            mobile: <?php echo json_encode($facultyPhone); ?>,
            avatar: <?php echo json_encode($facultyAvatar); ?>,
            role: <?php echo json_encode($facultyDesignation); ?>
        };
    </script>
</head>
<body>
    <!-- Ambient Background Elements -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>
    <div class="ambient-glow glow-3"></div>

    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <div class="logo-text">Attendance<span>Portal</span></div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section-title">Overview</div>
                <a href="#dashboard" class="nav-item active" data-view="dashboard">
                    <i class="fa-solid fa-border-all"></i>
                    <span>Dashboard</span>
                </a>

                <div class="nav-section-title" style="margin-top: 15px;">Attendance Management</div>
                <a href="#daily-attendance" class="nav-item" data-view="daily-attendance">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Daily Attendance</span>
                </a>
                <a href="#edit-attendance" class="nav-item" data-view="edit-attendance">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Edit Attendance</span>
                </a>
                <a href="#attendance-validation" class="nav-item" data-view="attendance-validation">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Attendance Validation</span>
                </a>
                <a href="#attendance-history" class="nav-item" data-view="attendance-history">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Attendance History</span>
                </a>

                <!-- Separate Student Management Section (Issue #4) -->
                <div class="nav-section-title" style="margin-top: 15px;">Student Management</div>
                <a href="#student-management" class="nav-item" data-view="student-management">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Student Directory (CRUD)</span>
                </a>
                <a href="#student-registration" class="nav-item" data-view="student-registration">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Student Registration</span>
                </a>
                <a href="#student-profiles" class="nav-item" data-view="student-profiles">
                    <i class="fa-solid fa-id-card-clip"></i>
                    <span>Profiles & Photo Upload</span>
                </a>

                <div class="nav-section-title" style="margin-top: 15px;">Reports & Analytics</div>
                <a href="#monthly-report" class="nav-item" data-view="monthly-report">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Monthly Report</span>
                </a>
                <a href="#student-report" class="nav-item" data-view="student-report">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Student Report</span>
                </a>
                <a href="#department-report" class="nav-item" data-view="department-report">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Department Report</span>
                </a>
                <a href="#low-attendance" class="nav-item" data-view="low-attendance">
                    <i class="fa-solid fa-triangle-exclamation text-warning"></i>
                    <span>Low Attendance Alerts</span>
                    <span class="badge" style="background: #EF4444; color: #fff; font-size: 0.65rem; padding: 2px 7px; border-radius: 10px; margin-left: auto;">Alerts</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="../auth/face_setup.php" class="nav-item" style="color: var(--neon-blue);">
                    <i class="fa-solid fa-camera"></i>
                    <span>Register Face ID</span>
                </a>
                <a href="#" class="nav-item logout" onclick="logout(event)">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left" style="display: flex; flex-direction: column;">
                    <h1 class="page-title" id="page-title">Dashboard</h1>
                    <p class="page-subtitle">Welcome to Faculty Portal</p>
                </div>
                
                <div class="topbar-right" style="display: flex; align-items: center;">
                    <button class="btn btn-primary btn-sm" onclick="openAddStudentModal()" style="margin-right: 15px; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-user-plus"></i> Add Student</button>
                    <div class="search-bar" id="global-search-container" style="position: relative; display: flex; align-items: center; background: var(--panel-bg); padding: 8px 16px; border-radius: 8px; border: 1px solid var(--panel-border);">
                        <i class="fa-solid fa-search text-muted"></i>
                        <input type="text" id="global-search-input" placeholder="Search..." style="background: transparent; border: none; outline: none; color: var(--text-main); margin-left: 8px;">
                        <div class="dropdown-panel" id="search-suggestions">
                            <!-- Suggestions injected here -->
                        </div>
                    </div>

                    <div class="notification-btn notification-bell" id="notification-toggle" style="position: relative; display: flex; align-items: center;">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge" id="notification-badge" style="display: none;">0</span>
                        <div class="dropdown-panel notification-panel" id="notification-dropdown">
                            <div class="panel-header">
                                <h4>Notifications</h4>
                                <button class="btn-text text-danger" onclick="clearAllNotifications()">Clear All</button>
                            </div>
                            <div class="panel-body" id="notification-list">
                                <!-- Notifications injected here -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="user-profile" id="profile-toggle" style="position: relative; cursor: pointer;">
                        <img src="<?php echo htmlspecialchars($facultyAvatar); ?>" alt="Profile" class="profile-img" id="nav-profile-img">
                        <div class="profile-info">
                            <span class="profile-name" id="nav-profile-name"><?php echo htmlspecialchars($facultyName); ?></span>
                            <span class="profile-role" id="nav-profile-role"><?php echo htmlspecialchars($facultyDesignation . (!empty($facultyDept) ? ' • ' . $facultyDept : '')); ?></span>
                        </div>
                        <i class="fa-solid fa-chevron-down" style="margin-left: 8px; color: var(--text-muted); font-size: 12px;"></i>
                        
                        <div class="dropdown-panel profile-menu" id="profile-menu">
                            <a href="#my-profile" class="dropdown-item" data-view="my-profile"><i class="fa-solid fa-user"></i> My Profile</a>
                            <a href="#edit-profile" class="dropdown-item" data-view="edit-profile"><i class="fa-solid fa-user-pen"></i> Edit Profile</a>
                            <a href="#change-password" class="dropdown-item" data-view="change-password"><i class="fa-solid fa-key"></i> Change Password</a>
                            <a href="#settings" class="dropdown-item" data-view="settings"><i class="fa-solid fa-gear"></i> Settings</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item text-danger" onclick="logout(event)"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Views Container -->
            <div class="view-container" id="view-container">
                <!-- Views will be injected or toggled here -->
            </div>
        </main>
    </div>

    <!-- Templates for different views -->
    
    <!-- 1. Dashboard View -->
    <template id="tpl-dashboard">
        <div class="view-content fade-in">
            <div class="welcome-banner glass-card">
                <div class="banner-content">
                    <div style="margin-bottom: 8px;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.75rem; font-weight: 700; background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3); padding: 4px 10px; border-radius: 20px;">
                            <span style="width: 6px; height: 6px; background: #10B981; border-radius: 50%; display: inline-block;"></span> FACULTY PORTAL ACTIVE
                        </span>
                    </div>
                    <h2>Welcome back, <?php echo htmlspecialchars($facultyName); ?>! 👋</h2>
                    <p>Here is an overview of your academic schedules, attendance statistics, and pending validations for today.</p>
                </div>
                <div class="banner-image">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Total Students</div>
                        <div class="stat-value" id="dash-total-students">--</div>
                        <div class="stat-subtitle text-blue"><i class="fa-solid fa-graduation-cap"></i> Enrolled</div>
                    </div>
                    <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-value" id="dash-classes-today">--</div>
                        <div class="stat-label">Classes Today</div>
                        <div class="stat-subtitle text-purple"><i class="fa-solid fa-calendar-day"></i> Scheduled</div>
                    </div>
                    <div class="stat-icon purple"><i class="fa-solid fa-clipboard-user"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Avg. Attendance</div>
                        <div class="stat-value" id="dash-avg-attendance">--</div>
                        <div class="stat-subtitle text-green"><i class="fa-solid fa-arrow-trend-up"></i> Present Rate</div>
                    </div>
                    <div class="stat-icon green"><i class="fa-solid fa-check-circle"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Pending Validation</div>
                        <div class="stat-value" id="dash-pending-validation">--</div>
                        <div class="stat-subtitle text-amber"><i class="fa-solid fa-clock"></i> Action Needed</div>
                    </div>
                    <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
                </div>
            </div>

            <div class="dashboard-grid">
                <div class="recent-classes glass-card">
                    <h3 class="card-title">Today's Schedule</h3>
                    <div class="schedule-list" id="dash-schedule-list">
                        <div class="schedule-item">
                            <div class="time-block">09:00 AM</div>
                            <div class="class-info">
                                <h4>Data Structures (CS-301)</h4>
                                <span>Sem 3 - Div A</span>
                            </div>
                            <div class="status-badge completed">Completed</div>
                        </div>
                        <div class="schedule-item">
                            <div class="time-block">11:30 AM</div>
                            <div class="class-info">
                                <h4>Algorithms (CS-302)</h4>
                                <span>Sem 3 - Div B</span>
                            </div>
                            <div class="status-badge pending">Mark Now</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- 2. Daily Attendance View -->
    <template id="tpl-daily-attendance">
        <div class="view-content fade-in">
            <div class="filters-card glass-card">
                <div class="filters-grid">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="glass-input" id="daily-date">
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select class="glass-input" id="daily-dept">
                            <option value="">Select Department</option>
                            <option value="CE">CE - Computer Engineering</option>
                            <option value="AIDS">AIDS - AI & Data Science</option>
                            <option value="EE">EE - Electrical Engineering</option>
                            <option value="BT">BT - Biotechnology</option>
                            <option value="ME">ME - Mechanical Engineering</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select class="glass-input" id="daily-sem">
                            <option value="">Select Semester</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                        <select class="glass-input" id="daily-div">
                            <option value="">Select Division</option>
                            <option value="Div A">Div A</option>
                            <option value="Div B">Div B</option>
                            <option value="Div C">Div C</option>
                            <option value="Div D">Div D</option>
                            <option value="Div E">Div E</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <select class="glass-input" id="daily-subject" data-populate="subjects">
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lecture Number</label>
                        <select class="glass-input" id="daily-lecture">
                            <option value="">Select Lecture</option>
                            <option value="Lecture 1">Lecture 1</option>
                            <option value="Lecture 2">Lecture 2</option>
                            <option value="Lecture 3 (Practical)">Lecture 3 (Practical)</option>
                            <option value="Lecture 4">Lecture 4</option>
                            <option value="Lecture 5 (Practical)">Lecture 5 (Practical)</option>
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <button class="btn btn-primary" onclick="loadStudents()">Load Students</button>
                </div>
            </div>

            <div class="attendance-workspace glass-card" id="student-list-container" style="display: none;">
                <div class="workspace-header">
                    <h3 class="card-title">Mark Attendance</h3>
                    <div class="bulk-actions">
                        <button class="btn btn-outline success" onclick="markBulk('present')"><i class="fa-solid fa-check"></i> Bulk Present</button>
                        <button class="btn btn-outline danger" onclick="markBulk('absent')"><i class="fa-solid fa-xmark"></i> Bulk Absent</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-tbody">
                            <!-- Student rows will be injected here -->
                        </tbody>
                    </table>
                </div>
                <div class="workspace-footer">
                    <button class="btn btn-primary btn-glow" onclick="saveAttendance()">Save Attendance</button>
                </div>
            </div>
        </div>
    </template>

    <!-- 3. Edit Attendance View -->
    <template id="tpl-edit-attendance">
        <div class="view-content fade-in">
            <!-- Top Filters Section -->
            <div class="filters-card glass-card">
                <div class="filters-grid">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="glass-input" id="edit-date">
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select class="glass-input" id="edit-dept">
                            <option value="">Select Department</option>
                            <option value="CE">CE - Computer Engineering</option>
                            <option value="AIDS">AIDS - AI & Data Science</option>
                            <option value="EE">EE - Electrical Engineering</option>
                            <option value="BT">BT - Biotechnology</option>
                            <option value="ME">ME - Mechanical Engineering</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                        <select class="glass-input" id="edit-div">
                            <option value="">Select Division</option>
                            <option value="Div A">Div A</option>
                            <option value="Div B">Div B</option>
                            <option value="Div C">Div C</option>
                            <option value="Div D">Div D</option>
                            <option value="Div E">Div E</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select class="glass-input" id="edit-sem">
                            <option value="">Select Semester</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <select class="glass-input" id="edit-subject" disabled>
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lecture Number</label>
                        <select class="glass-input" id="edit-lecture">
                            <option value="">Select Lecture</option>
                            <option value="Lecture 1">Lecture 1</option>
                            <option value="Lecture 2">Lecture 2</option>
                            <option value="Lecture 3 (Practical)">Lecture 3 (Practical)</option>
                            <option value="Lecture 4">Lecture 4</option>
                            <option value="Lecture 5 (Practical)">Lecture 5 (Practical)</option>
                        </select>
                    </div>

                    <div class="form-group" style="display: flex; align-items: flex-end; gap: 10px;">
                        <button class="btn btn-primary" onclick="searchEditAttendance()">Search Attendance</button>
                        <button class="btn btn-outline" onclick="resetEditFilters()">Reset Filters</button>
                    </div>
                </div>
            </div>
            
            <!-- Main Edit Workspace -->
            <div class="edit-workspace glass-card mt-3" id="edit-workspace-main" style="display: none; padding: 25px;">
                
                <!-- Actions Toolbar -->
                <div class="actions-toolbar mb-4 glass-card" style="display: flex; gap: 10px; align-items: center; justify-content: space-between; padding: 15px; overflow: visible;">
                    <div style="display: flex; gap: 10px;">
                        <button class="btn btn-outline success" onclick="saveEditChanges()"><i class="fa-solid fa-save"></i> Save Changes</button>
                        <button class="btn btn-outline danger" onclick="cancelEditChanges()"><i class="fa-solid fa-ban"></i> Cancel Changes</button>
                        <button class="btn btn-outline warning" onclick="undoLastEditChange()"><i class="fa-solid fa-rotate-left"></i> Undo Last Changes</button>
                    </div>
                </div>

                <div class="mb-4 text-muted" style="font-size: 0.9rem;">
                    Showing <span id="edit-top-page-start">0</span> to <span id="edit-top-page-end">0</span> of <span id="edit-top-total-students">0</span> students
                </div>

                <!-- Footer Summaries Moved Up -->
                <div class="edit-footer-grid mb-4" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="attendance-legend glass-card" style="padding: 15px;">
                        <h4 class="mb-3" style="font-size: 0.9rem; font-weight: 500;">Attendance Legend</h4>
                        <div style="display: flex; justify-content: space-around; text-align: center; font-size: 0.85rem; padding-top: 5px;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <span style="color: var(--text-primary);">Present</span>
                                <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--success);"></div>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <span style="color: var(--text-primary);">Absent</span>
                                <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--danger);"></div>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <span style="color: var(--text-primary);">Late</span>
                                <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--warning);"></div>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <span style="color: var(--text-primary);">On Leave</span>
                                <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--info);"></div>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <span style="color: var(--text-primary);">Not Marked</span>
                                <div style="width: 12px; height: 12px; border-radius: 50%; background: #94A3B8;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="attendance-summary glass-card" style="padding: 15px;">
                        <h4 class="mb-3" style="font-size: 0.9rem; font-weight: 500;">Attendance Summary (Current Filter)</h4>
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; text-align: center;">
                            <div style="background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); padding: 10px; border-radius: 8px;">
                                <div style="font-size: 0.8rem; margin-bottom: 5px; color: var(--text-primary);">Total Students</div>
                                <div id="edit-summary-total" style="font-size: 1.5rem; font-weight: 600; color: var(--text-primary);">0</div>
                            </div>
                            <div style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2); padding: 10px; border-radius: 8px;">
                                <div style="font-size: 0.8rem; margin-bottom: 5px; color: var(--text-primary);">Present</div>
                                <div id="edit-summary-present" style="font-size: 1.5rem; font-weight: 600; color: var(--success);">0</div>
                            </div>
                            <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); padding: 10px; border-radius: 8px;">
                                <div style="font-size: 0.8rem; margin-bottom: 5px; color: var(--text-primary);">Absent</div>
                                <div id="edit-summary-absent" style="font-size: 1.5rem; font-weight: 600; color: var(--danger);">0</div>
                            </div>
                            <div style="background: rgba(74, 58, 255, 0.05); border: 1px solid rgba(74, 58, 255, 0.2); padding: 10px; border-radius: 8px;">
                                <div style="font-size: 0.8rem; margin-bottom: 5px; color: var(--text-primary);">Attendance %</div>
                                <div id="edit-summary-percent" style="font-size: 1.5rem; font-weight: 600; color: var(--text-primary);">0%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="attendance-table-container glass-card" style="display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--card-border); margin-top: 20px;">
                    <div class="table-responsive" style="overflow-y: auto; max-height: 500px;">
                        <table class="glass-table" style="margin-bottom: 0;">
                            <thead style="position: sticky; top: 0; z-index: 2; background: var(--bg-darker);">
                                <tr style="text-transform: uppercase; font-size: 0.75rem; white-space: nowrap;">
                                    <th>
                                        <label class="custom-checkbox mb-0">
                                            <input type="checkbox" id="edit-select-all" onclick="toggleEditSelectAll()">
                                            <span class="checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Roll No.</th>
                                    <th>Student Name</th>
                                    <th>Department</th>
                                    <th>Division</th>
                                    <th>Semester</th>
                                    <th style="white-space: normal; word-wrap: break-word; min-width: 160px; max-width: 220px;">Subject Name</th>
                                    <th>Lecture No.</th>
                                    <th>Attendance Status</th>
                                    <th>Remarks</th>
                                    <th>Date</th>
                                    <th>Last Updated By</th>
                                    <th>Last Updated Time</th>
                                </tr>
                            </thead>
                            <tbody id="edit-attendance-tbody">
                                <!-- Students will be injected here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Footer -->
                    <div class="pagination-footer" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: rgba(16, 23, 45, 0.95); border-top: 1px solid var(--card-border);">
                        <div class="pagination-info text-muted" style="font-size: 0.9rem; flex: 1;">
                            Showing <span id="edit-page-start">0</span> to <span id="edit-page-end">0</span> of <span id="edit-total-students">0</span> students
                        </div>
                        
                        <div class="pagination-controls" id="edit-pagination" style="display: flex; justify-content: center; align-items: center; gap: 8px; flex: 1;">
                            <!-- Pagination buttons injected here -->
                        </div>

                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; flex: 1;" class="text-muted">
                            <span style="font-size: 0.9rem;">Rows per page:</span>
                            <select class="glass-input" style="padding: 5px; width: 70px; cursor: pointer; background-color: var(--bg-dark);" onchange="changeEditRowsPerPage(this.value)">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- 4. Attendance Validation View -->
    <template id="tpl-attendance-validation">
        <div class="view-content fade-in" style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Pending Section -->
            <div class="attendance-table-container glass-card theme-pending" style="display: flex; flex-direction: column; overflow: visible; border-radius: 16px; padding: 0;">
                <div style="padding: 20px 24px;">
                    <h2 class="card-title">Pending <span class="count-badge" id="badge-pending">0</span></h2>
                </div>
                <div class="validation-table-wrapper table-responsive" style="overflow-x: auto;">
                    <table class="glass-table validation-table" style="min-width: 1200px;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Semester</th>
                                <th>Division</th>
                                <th>Subject</th>
                                <th>Lecture Number</th>
                                <th>Attendance Status</th>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Faculty</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="val-tbody-pending">
                            <!-- Pending Records injected here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Approved Section -->
            <div class="attendance-table-container glass-card theme-approved" style="display: flex; flex-direction: column; overflow: visible; border-radius: 16px; padding: 0;">
                <div style="padding: 20px 24px;">
                    <h2 class="card-title">Approved <span class="count-badge" id="badge-approved">0</span></h2>
                </div>
                <div class="validation-table-wrapper table-responsive" style="overflow-x: auto;">
                    <table class="glass-table validation-table" style="min-width: 1200px;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Semester</th>
                                <th>Division</th>
                                <th>Subject</th>
                                <th>Lecture Number</th>
                                <th>Attendance Status</th>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Faculty</th>
                                <th>Validated Date & Time</th>
                            </tr>
                        </thead>
                        <tbody id="val-tbody-approved">
                            <!-- Approved Records injected here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rejected Section -->
            <div class="attendance-table-container glass-card theme-rejected" style="display: flex; flex-direction: column; overflow: visible; border-radius: 16px; padding: 0;">
                <div style="padding: 20px 24px;">
                    <h2 class="card-title">Rejected <span class="count-badge" id="badge-rejected">0</span></h2>
                </div>
                <div class="validation-table-wrapper table-responsive" style="overflow-x: auto;">
                    <table class="glass-table validation-table" style="min-width: 1200px;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Semester</th>
                                <th>Division</th>
                                <th>Subject</th>
                                <th>Lecture Number</th>
                                <th>Attendance Status</th>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Faculty</th>
                                <th>Validated Date & Time</th>
                            </tr>
                        </thead>
                        <tbody id="val-tbody-rejected">
                            <!-- Rejected Records injected here -->
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </template>

    <!-- 4.5. Student Directory & CRUD View -->
    <template id="tpl-student-management">
        <div class="view-content fade-in" style="display: flex; flex-direction: column; gap: 24px;">
            <div class="view-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 class="view-title">Student Directory & CRUD</h2>
                    <p class="view-subtitle" style="color: var(--text-secondary);">Comprehensive student database, profile management, and attendance stats</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="#student-registration" class="btn btn-outline" onclick="navigateTo('student-registration')"><i class="fa-solid fa-user-plus"></i> Registration Form</a>
                    <a href="#student-profiles" class="btn btn-outline" onclick="navigateTo('student-profiles')"><i class="fa-solid fa-id-card-clip"></i> Profile Gallery</a>
                    <button class="btn btn-primary" onclick="openAddStudentModal()"><i class="fa-solid fa-plus"></i> Add Student (Modal)</button>
                </div>
            </div>

            <!-- Summary Stat Cards -->
            <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div class="glass-card stat-card" style="display: flex; align-items: center; gap: 16px; padding: 18px 22px;">
                    <div class="stat-icon" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; background: rgba(59, 130, 246, 0.15); color: #3B82F6;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="crud-stat-total" style="font-size: 1.6rem; font-weight: 700;">--</div>
                        <div class="stat-label" style="font-size: 0.85rem; color: var(--text-secondary);">Total Students</div>
                    </div>
                </div>
                <div class="glass-card stat-card" style="display: flex; align-items: center; gap: 16px; padding: 18px 22px;">
                    <div class="stat-icon" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; background: rgba(16, 185, 129, 0.15); color: #10B981;">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="crud-stat-safe" style="font-size: 1.6rem; font-weight: 700;">--</div>
                        <div class="stat-label" style="font-size: 0.85rem; color: var(--text-secondary);">Above 75% Attendance</div>
                    </div>
                </div>
                <div class="glass-card stat-card" style="display: flex; align-items: center; gap: 16px; padding: 18px 22px;">
                    <div class="stat-icon" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; background: rgba(239, 68, 68, 0.15); color: #EF4444;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <div class="stat-value" id="crud-stat-defaulters" style="font-size: 1.6rem; font-weight: 700;">--</div>
                        <div class="stat-label" style="font-size: 0.85rem; color: var(--text-secondary);">Low Attendance (<75%)</div>
                    </div>
                </div>
                <div class="glass-card stat-card" style="display: flex; align-items: center; gap: 16px; padding: 18px 22px;">
                    <div class="stat-icon" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; background: rgba(168, 85, 247, 0.15); color: #A855F7;">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size: 1.6rem; font-weight: 700;">5</div>
                        <div class="stat-label" style="font-size: 0.85rem; color: var(--text-secondary);">Active Departments</div>
                    </div>
                </div>
            </div>

            <!-- Filters & Search Bar -->
            <div class="filters-card glass-card" style="padding: 20px; border-radius: 12px;">
                <div class="filters-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: end;">
                    <div class="form-group mb-0">
                        <label>Department</label>
                        <select class="glass-input" id="filter-dept" onchange="loadStudentManagement()">
                            <option value="">All Departments</option>
                            <option value="CE">CE - Computer Engineering</option>
                            <option value="AIDS">AIDS - AI & Data Science</option>
                            <option value="EE">EE - Electrical Engineering</option>
                            <option value="BT">BT - Biotechnology</option>
                            <option value="ME">ME - Mechanical Engineering</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Semester</label>
                        <select class="glass-input" id="filter-sem" onchange="loadStudentManagement()">
                            <option value="">All Semesters</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Division</label>
                        <select class="glass-input" id="filter-div" onchange="loadStudentManagement()">
                            <option value="">All Divisions</option>
                            <option value="Div A">Div A</option>
                            <option value="Div B">Div B</option>
                            <option value="Div C">Div C</option>
                            <option value="Div D">Div D</option>
                            <option value="Div E">Div E</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Search Student</label>
                        <input type="text" class="glass-input" id="filter-search" placeholder="Search by name or roll..." oninput="loadStudentManagement()">
                    </div>
                    <div class="form-group mb-0" style="display: flex; gap: 8px;">
                        <button class="btn btn-outline" style="flex: 1;" onclick="document.getElementById('filter-dept').value='';document.getElementById('filter-sem').value='';document.getElementById('filter-div').value='';document.getElementById('filter-search').value='';loadStudentManagement();"><i class="fa-solid fa-rotate-right"></i> Reset</button>
                        <button class="btn btn-outline" onclick="exportStudentRosterExcel()" title="Export Excel"><i class="fa-solid fa-file-excel text-green"></i></button>
                    </div>
                </div>
            </div>

            <!-- Student List Table -->
            <div class="glass-card" style="padding: 0; overflow: hidden; border-radius: 12px;">
                <div class="table-responsive">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Photo</th>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Department</th>
                                <th>Semester</th>
                                <th>Division</th>
                                <th>Attendance %</th>
                                <th style="width: 160px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="student-management-list">
                            <!-- Student rows dynamically rendered -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </template>

    <!-- 4.6. Student Registration View (Issue #4) -->
    <template id="tpl-student-registration">
        <div class="view-content fade-in" style="display: flex; flex-direction: column; gap: 24px;">
            <div class="view-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 class="view-title">Student Registration Portal</h2>
                    <p class="view-subtitle" style="color: var(--text-secondary);">Enroll new students, upload profile photos, assign academic classes and provision portal login</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="#student-management" class="btn btn-outline" onclick="navigateTo('student-management')"><i class="fa-solid fa-arrow-left"></i> Back to Directory</a>
                    <a href="#student-profiles" class="btn btn-outline" onclick="navigateTo('student-profiles')"><i class="fa-solid fa-id-card-clip"></i> View Profiles</a>
                </div>
            </div>

            <!-- Registration Form Container -->
            <div class="glass-card" style="padding: 30px; border-radius: 16px;">
                <form id="reg-student-form" onsubmit="handleRegistrationSubmit(event)">
                    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px; align-items: start;">
                        <!-- Left: Photo Upload & Live Preview -->
                        <div style="display: flex; flex-direction: column; align-items: center; text-align: center; background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.15); border-radius: 14px; padding: 24px;">
                            <div style="position: relative; width: 140px; height: 140px; margin-bottom: 16px;">
                                <img id="reg-photo-preview" src="https://ui-avatars.com/api/?name=New+Student&background=3B82F6&color=fff&size=200" alt="Preview" style="width: 140px; height: 140px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary-color); box-shadow: 0 8px 24px rgba(0,0,0,0.3);">
                                <label for="reg-student-photo" style="position: absolute; bottom: 4px; right: 4px; background: var(--primary-color); color: #fff; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.4);">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                            </div>
                            <input type="file" id="reg-student-photo" accept="image/*" style="display: none;" onchange="handleRegPhotoSelect(event)">
                            <h4 style="margin: 0 0 6px 0; font-size: 1.05rem;">Student Profile Photo</h4>
                            <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0 0 14px 0;">Upload a clear passport-size photo (JPG, PNG, WEBP - Max 2MB)</p>
                            <label for="reg-student-photo" class="btn btn-outline btn-sm" style="cursor: pointer;"><i class="fa-solid fa-upload"></i> Browse Photo</label>
                        </div>

                        <!-- Right: Student Metadata Form -->
                        <div style="display: flex; flex-direction: column; gap: 18px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div class="form-group mb-0">
                                    <label style="font-weight: 500; font-size: 0.9rem; margin-bottom: 6px; display: block;">Student Full Name <span style="color: var(--danger);">*</span></label>
                                    <input type="text" class="glass-input w-100" id="reg-student-name" required placeholder="e.g. Aryan Jedhe" oninput="updateRegPhotoPlaceholder()">
                                </div>
                                <div class="form-group mb-0">
                                    <label style="font-weight: 500; font-size: 0.9rem; margin-bottom: 6px; display: block;">Roll Number / Enrollment ID <span style="color: var(--danger);">*</span></label>
                                    <input type="text" class="glass-input w-100" id="reg-student-roll" required placeholder="e.g. CE3A01">
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label style="font-weight: 500; font-size: 0.9rem; margin-bottom: 6px; display: block;">Official Email Address <span style="color: var(--danger);">*</span></label>
                                <input type="email" class="glass-input w-100" id="reg-student-email" required placeholder="e.g. aryan.jedhe@college.edu">
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                                <div class="form-group mb-0">
                                    <label style="font-weight: 500; font-size: 0.9rem; margin-bottom: 6px; display: block;">Department <span style="color: var(--danger);">*</span></label>
                                    <select class="glass-input w-100" id="reg-student-dept" required>
                                        <option value="">Select Branch</option>
                                        <option value="CE">CE - Computer Engineering</option>
                                        <option value="AIDS">AIDS - AI & Data Science</option>
                                        <option value="EE">EE - Electrical Engineering</option>
                                        <option value="BT">BT - Biotechnology</option>
                                        <option value="ME">ME - Mechanical Engineering</option>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label style="font-weight: 500; font-size: 0.9rem; margin-bottom: 6px; display: block;">Semester <span style="color: var(--danger);">*</span></label>
                                    <select class="glass-input w-100" id="reg-student-sem" required>
                                        <option value="">Select Semester</option>
                                        <option value="Semester 1">Semester 1</option>
                                        <option value="Semester 2">Semester 2</option>
                                        <option value="Semester 3">Semester 3</option>
                                        <option value="Semester 4">Semester 4</option>
                                        <option value="Semester 5">Semester 5</option>
                                        <option value="Semester 6">Semester 6</option>
                                        <option value="Semester 7">Semester 7</option>
                                        <option value="Semester 8">Semester 8</option>
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label style="font-weight: 500; font-size: 0.9rem; margin-bottom: 6px; display: block;">Division <span style="color: var(--danger);">*</span></label>
                                    <select class="glass-input w-100" id="reg-student-div" required>
                                        <option value="">Select Division</option>
                                        <option value="Div A">Div A</option>
                                        <option value="Div B">Div B</option>
                                        <option value="Div C">Div C</option>
                                        <option value="Div D">Div D</option>
                                        <option value="Div E">Div E</option>
                                    </select>
                                </div>
                            </div>

                            <div style="background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 10px; padding: 14px 18px; display: flex; align-items: center; gap: 12px;">
                                <i class="fa-solid fa-shield-halved text-blue" style="font-size: 1.2rem;"></i>
                                <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                    <strong>Automatic Account Provisioning:</strong> Registering this student creates their portal login credentials. Initial password will match their <strong>Roll Number</strong>.
                                </div>
                            </div>

                            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px;">
                                <button type="reset" class="btn btn-outline" onclick="resetRegForm()"><i class="fa-solid fa-rotate-left"></i> Reset Form</button>
                                <button type="submit" class="btn btn-primary" id="reg-submit-btn"><i class="fa-solid fa-user-check"></i> Register Student</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Recently Registered Students Preview -->
            <div class="glass-card" style="padding: 24px; border-radius: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 style="font-size: 1.15rem; margin: 0;"><i class="fa-solid fa-clock-rotate-left text-blue"></i> Recently Registered Students</h3>
                    <a href="#student-management" class="btn btn-outline btn-sm" onclick="navigateTo('student-management')">View All in Directory</a>
                </div>
                <div class="table-responsive">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">Photo</th>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Department</th>
                                <th>Semester</th>
                                <th>Division</th>
                                <th>Registered Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reg-recent-tbody">
                            <!-- Injected dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </template>

    <!-- 4.7. Student Profile Management & Photo Upload View (Issue #4) -->
    <template id="tpl-student-profiles">
        <div class="view-content fade-in" style="display: flex; flex-direction: column; gap: 24px;">
            <div class="view-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h2 class="view-title">Student Profile Management & Photo Upload</h2>
                    <p class="view-subtitle" style="color: var(--text-secondary);">Interactive student profile gallery, instant photo updater, and academic attendance overview</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="#student-registration" class="btn btn-primary" onclick="navigateTo('student-registration')"><i class="fa-solid fa-user-plus"></i> New Student Registration</a>
                    <a href="#student-management" class="btn btn-outline" onclick="navigateTo('student-management')"><i class="fa-solid fa-table-list"></i> Table View</a>
                </div>
            </div>

            <!-- Search & Filters -->
            <div class="filters-card glass-card" style="padding: 20px; border-radius: 12px;">
                <div class="filters-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: end;">
                    <div class="form-group mb-0">
                        <label>Department</label>
                        <select class="glass-input" id="profile-filter-dept" onchange="loadStudentProfilesGallery()">
                            <option value="">All Departments</option>
                            <option value="CE">CE - Computer Engineering</option>
                            <option value="AIDS">AIDS - AI & Data Science</option>
                            <option value="EE">EE - Electrical Engineering</option>
                            <option value="BT">BT - Biotechnology</option>
                            <option value="ME">ME - Mechanical Engineering</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Semester</label>
                        <select class="glass-input" id="profile-filter-sem" onchange="loadStudentProfilesGallery()">
                            <option value="">All Semesters</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5">Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Division</label>
                        <select class="glass-input" id="profile-filter-div" onchange="loadStudentProfilesGallery()">
                            <option value="">All Divisions</option>
                            <option value="Div A">Div A</option>
                            <option value="Div B">Div B</option>
                            <option value="Div C">Div C</option>
                            <option value="Div D">Div D</option>
                            <option value="Div E">Div E</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Search Profile</label>
                        <input type="text" class="glass-input" id="profile-filter-search" placeholder="Search by name or roll..." oninput="loadStudentProfilesGallery()">
                    </div>
                    <div class="form-group mb-0">
                        <button class="btn btn-outline w-100" onclick="document.getElementById('profile-filter-dept').value='';document.getElementById('profile-filter-sem').value='';document.getElementById('profile-filter-div').value='';document.getElementById('profile-filter-search').value='';loadStudentProfilesGallery();"><i class="fa-solid fa-rotate-right"></i> Reset</button>
                    </div>
                </div>
            </div>

            <!-- Profiles Grid Container -->
            <div id="student-profiles-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                <!-- Profile Cards dynamically loaded here -->
            </div>

            <!-- Hidden File Input for quick photo upload -->
            <input type="file" id="quick-photo-input" accept="image/*" style="display: none;" onchange="handleQuickPhotoUpload(event)">
        </div>
    </template>

    <!-- 5. Attendance History View -->
    <template id="tpl-attendance-history">
        <div class="view-content fade-in attendance-history-page" style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Summary Cards Section -->
            <div class="history-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px;">
                <!-- Total Records -->
                <div class="glass-card stat-card-premium premium-blue">
                    <div class="stat-info">
                        <div class="stat-label">Total Attendance Records</div>
                        <div class="stat-value" id="hist-sum-total">1,248</div>
                        <div class="stat-subtitle">All time records</div>
                    </div>
                    <div class="stat-icon-wrapper"><i class="fa-solid fa-file-lines"></i></div>
                </div>
                <!-- Approved -->
                <div class="glass-card stat-card-premium premium-green">
                    <div class="stat-info">
                        <div class="stat-label">Approved Records</div>
                        <div class="stat-value" id="hist-sum-approved">842</div>
                        <div class="stat-subtitle">67.47% of total</div>
                    </div>
                    <div class="stat-icon-wrapper"><i class="fa-solid fa-circle-check"></i></div>
                </div>
                <!-- Pending -->
                <div class="glass-card stat-card-premium premium-cyan">
                    <div class="stat-info">
                        <div class="stat-label">Pending Records</div>
                        <div class="stat-value" id="hist-sum-pending">236</div>
                        <div class="stat-subtitle">18.91% of total</div>
                    </div>
                    <div class="stat-icon-wrapper"><i class="fa-solid fa-clock"></i></div>
                </div>
                <!-- Rejected -->
                <div class="glass-card stat-card-premium premium-red">
                    <div class="stat-info">
                        <div class="stat-label">Rejected Records</div>
                        <div class="stat-value" id="hist-sum-rejected">170</div>
                        <div class="stat-subtitle">13.62% of total</div>
                    </div>
                    <div class="stat-icon-wrapper"><i class="fa-regular fa-circle-xmark"></i></div>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-card glass-card premium-filter-panel" style="padding: 22px 24px; border-radius: 18px; width: 100%; box-sizing: border-box;">
                <div class="filters-flex-container" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; width: 100%;">
                    <div class="form-group premium-group mb-0" style="flex: 1 1 180px; min-width: 160px;">
                        <label>Date Range</label>
                        <div class="date-range-wrapper" style="position: relative;">
                            <i class="fa-regular fa-calendar" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                            <input type="text" class="glass-input date-range-input" id="hist-date-range" placeholder="01/07/2025 - 27/07/2026" style="padding-left: 38px; width: 100%;">
                        </div>
                    </div>
                    <div class="form-group premium-group mb-0" style="flex: 1 1 160px; min-width: 140px;">
                        <label>Department</label>
                        <select class="glass-input" id="hist-dept" onchange="window.historyLogic.onDeptChange()" style="width: 100%;">
                            <option value="">All Departments</option>
                        </select>
                    </div>
                    <div class="form-group premium-group mb-0" style="flex: 1 1 140px; min-width: 130px;">
                        <label>Semester</label>
                        <select class="glass-input" id="hist-sem" onchange="window.historyLogic.onSemChange()" style="width: 100%;">
                            <option value="">All Semesters</option>
                        </select>
                    </div>
                    <div class="form-group premium-group mb-0" style="flex: 1 1 120px; min-width: 110px;">
                        <label>Division</label>
                        <select class="glass-input" id="hist-div" style="width: 100%;">
                            <option value="">All Divisions</option>
                        </select>
                    </div>
                    <div class="form-group premium-group mb-0" style="flex: 1 1 180px; min-width: 160px;">
                        <label>Subject</label>
                        <select class="glass-input" id="hist-subject" style="width: 100%;">
                            <option value="">All Subjects</option>
                        </select>
                    </div>
                    <div class="form-group filter-actions-premium mb-0" style="flex: 0 0 auto; display: flex; gap: 10px; align-items: flex-end;">
                        <button class="btn btn-primary premium-btn-blue" onclick="window.historyLogic.applyFilters()" style="white-space: nowrap; padding: 10px 18px;"><i class="fa-solid fa-filter"></i> Apply Filters</button>
                        <button class="btn btn-outline premium-btn-reset" onclick="window.historyLogic.resetFilters()" style="white-space: nowrap; padding: 10px 16px;"><i class="fa-solid fa-rotate-right"></i> Reset</button>
                    </div>
                </div>
            </div>

            <!-- History Table Container -->
            <div class="attendance-table-container glass-card premium-table-card" id="hist-table-container" style="display: flex; flex-direction: column; overflow: hidden; border-radius: 18px; padding: 0; width: 100%; max-width: 100%;">
                
                <!-- Table Header Actions -->
                <div class="table-header-actions" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <h3 style="margin: 0; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-list-ul" style="color: #37C5FF;"></i> Attendance History Records
                    </h3>
                    <div class="export-actions" style="display: flex; gap: 12px;">
                        <button class="btn btn-sm btn-outline premium-export-btn btn-export-excel" onclick="window.historyLogic.exportData('excel')"><i class="fa-regular fa-file-excel"></i> Export Excel</button>
                        <button class="btn btn-sm btn-outline premium-export-btn btn-export-pdf" onclick="window.historyLogic.exportData('pdf')"><i class="fa-regular fa-file-pdf"></i> Download PDF</button>
                        <button class="btn btn-sm btn-outline premium-export-btn btn-export-print" onclick="window.historyLogic.printHistory()"><i class="fa-solid fa-print"></i> Print</button>
                    </div>
                </div>

                <div class="table-scroll" style="max-height: 550px; overflow-x: auto; overflow-y: auto; width: 100%; max-width: 100%; display: block; position: relative;">
                    <table class="glass-table premium-history-table" style="width: 100%; border-collapse: collapse; min-width: 1950px;">
                        <thead style="position: sticky; top: 0; z-index: 10; background: rgba(7, 26, 58, 0.98); backdrop-filter: blur(12px);">
                            <tr>
                                <th style="min-width: 110px; white-space: nowrap;">Date</th>
                                <th style="min-width: 140px; white-space: nowrap;">Department</th>
                                <th style="min-width: 100px; white-space: nowrap;">Semester</th>
                                <th style="min-width: 90px; white-space: nowrap;">Division</th>
                                <th style="min-width: 220px; white-space: nowrap;">Subject</th>
                                <th style="min-width: 110px; white-space: nowrap;">Lecture No.</th>
                                <th style="min-width: 110px; white-space: nowrap;">Roll No.</th>
                                <th style="min-width: 160px; white-space: nowrap;">Student Name</th>
                                <th style="min-width: 140px; white-space: nowrap;">Attendance Status</th>
                                <th style="min-width: 140px; white-space: nowrap;">Validation Status</th>
                                <th style="min-width: 130px; white-space: nowrap;">Faculty</th>
                                <th style="min-width: 130px; white-space: nowrap;">Validated By</th>
                                <th style="min-width: 190px; white-space: nowrap;">Validation Date & Time</th>
                                <th style="min-width: 140px; white-space: nowrap;">Remarks</th>
                                <th style="min-width: 80px; text-align: center; white-space: nowrap;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="hist-tbody">
                            <!-- Dynamic rows injected here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Footer -->
                <div class="pagination-footer premium-pagination" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 24px; background: rgba(0, 0, 0, 0.2); border-top: 1px solid rgba(255,255,255,0.05); border-radius: 0 0 18px 18px;">
                    <div class="pagination-info text-muted" style="font-size: 0.9rem;">
                        Showing <span id="hist-page-start">0</span> to <span id="hist-page-end">0</span> of <span id="hist-total-records">0</span> records
                    </div>
                    
                    <div class="pagination-controls-wrapper" style="display: flex; align-items: center; gap: 20px;">
                        <div class="rows-per-page text-muted" style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 0.9rem;">Rows per page:</span>
                            <select class="glass-input premium-select-sm" id="hist-rows-per-page" style="padding: 5px 30px 5px 10px; border-radius: 8px; width: auto; background-color: rgba(0,0,0,0.3);" onchange="window.historyLogic.changeRowsPerPage()">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
    
                        <div class="pagination-controls premium-page-controls" id="hist-pagination-controls" style="display: flex; gap: 5px;">
                            <button class="page-btn"><i class="fa-solid fa-angles-left"></i></button>
                            <button class="page-btn"><i class="fa-solid fa-angle-left"></i></button>
                            <button class="page-btn active">1</button>
                            <button class="page-btn">2</button>
                            <button class="page-btn">3</button>
                            <span class="page-dots" style="color: var(--text-secondary); padding: 5px;">...</span>
                            <button class="page-btn">125</button>
                            <button class="page-btn"><i class="fa-solid fa-angle-right"></i></button>
                            <button class="page-btn"><i class="fa-solid fa-angles-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </template>

    <!-- 7. My Profile View -->
    <template id="tpl-my-profile">
        <div class="view-content fade-in">
            <div class="glass-card profile-card" style="max-width: 650px; margin: 0 auto; text-align: center; padding: 2rem;">
                <img src="<?php echo htmlspecialchars($facultyAvatar); ?>" alt="Profile" id="my-profile-img" style="width: 120px; height: 120px; border-radius: 50%; margin-bottom: 1rem; border: 3px solid rgba(255,255,255,0.2); object-fit: cover;">
                <h2 id="my-profile-name" style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($facultyName); ?></h2>
                <p id="my-profile-designation" style="color: var(--text-secondary); margin-bottom: 1.5rem; font-weight: 500;"><?php echo htmlspecialchars($facultyDesignation); ?></p>
                
                <div class="profile-details-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; text-align: left; margin-bottom: 2rem; background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.85rem; display: block; margin-bottom: 3px;">Faculty ID / Employee ID</span>
                        <div id="my-profile-id" style="font-weight: 600; color: var(--neon-blue);"><?php echo htmlspecialchars($facultyEmpId); ?></div>
                    </div>
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.85rem; display: block; margin-bottom: 3px;">Department</span>
                        <div id="my-profile-dept" style="font-weight: 600;"><?php echo htmlspecialchars($facultyDept); ?></div>
                    </div>
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.85rem; display: block; margin-bottom: 3px;">Designation</span>
                        <div id="my-profile-grid-desig" style="font-weight: 600;"><?php echo htmlspecialchars($facultyDesignation); ?></div>
                    </div>
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.85rem; display: block; margin-bottom: 3px;">Qualification</span>
                        <div id="my-profile-qualification" style="font-weight: 600;"><?php echo htmlspecialchars($facultyQualification ?: 'Not Specified'); ?></div>
                    </div>
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.85rem; display: block; margin-bottom: 3px;">Email ID</span>
                        <div id="my-profile-email" style="font-weight: 500; word-break: break-all;"><?php echo htmlspecialchars($facultyEmail); ?></div>
                    </div>
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.85rem; display: block; margin-bottom: 3px;">Mobile Number</span>
                        <div id="my-profile-mobile" style="font-weight: 500;"><?php echo htmlspecialchars($facultyPhone ?: 'Not Specified'); ?></div>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="navigateTo('edit-profile')"><i class="fa-solid fa-pen"></i> Edit Profile</button>
            </div>
        </div>
    </template>

    <!-- 8. Edit Profile View -->
    <template id="tpl-edit-profile">
        <div class="view-content fade-in">
            <div class="glass-card profile-card" style="max-width: 650px; margin: 0 auto; padding: 2rem;">
                <h3 class="card-title mb-4"><i class="fa-solid fa-user-pen"></i> Edit Faculty Profile</h3>
                <form id="edit-profile-form" onsubmit="saveProfile(event)">
                    <div class="form-group mb-3">
                        <label>Full Name</label>
                        <input type="text" class="glass-input" id="edit-name" value="<?php echo htmlspecialchars($facultyName); ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Department</label>
                        <input type="text" class="glass-input" id="edit-department" value="<?php echo htmlspecialchars($facultyDept); ?>" readonly style="opacity: 0.7; cursor: not-allowed;">
                    </div>
                    <div class="form-group mb-3">
                        <label>Designation</label>
                        <input type="text" class="glass-input" id="edit-designation" value="<?php echo htmlspecialchars($facultyDesignation); ?>" readonly style="opacity: 0.7; cursor: not-allowed;">
                    </div>
                    <div class="form-group mb-3">
                        <label>Qualification</label>
                        <input type="text" class="glass-input" id="edit-qualification" value="<?php echo htmlspecialchars($facultyQualification); ?>" placeholder="e.g. Ph.D, M.Tech, B.E.">
                    </div>
                    <div class="form-group mb-3">
                        <label>Email ID</label>
                        <input type="email" class="glass-input" id="edit-email" value="<?php echo htmlspecialchars($facultyEmail); ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Mobile Number</label>
                        <input type="text" class="glass-input" id="edit-mobile" value="<?php echo htmlspecialchars($facultyPhone); ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label>Profile Picture URL</label>
                        <input type="url" class="glass-input" id="edit-picture" value="<?php echo htmlspecialchars($facultyAvatar); ?>">
                    </div>
                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <button type="button" class="btn btn-outline" onclick="navigateTo('my-profile')">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- 9. Change Password View -->
    <template id="tpl-change-password">
        <div class="view-content fade-in">
            <div class="glass-card profile-card" style="max-width: 500px; margin: 0 auto; padding: 2rem;">
                <h3 class="card-title mb-4">Change Password</h3>
                <form id="change-password-form" onsubmit="changePassword(event)">
                    <div class="form-group mb-3">
                        <label>Current Password</label>
                        <input type="password" class="glass-input" id="pwd-current" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>New Password</label>
                        <input type="password" class="glass-input" id="pwd-new" required minlength="6">
                    </div>
                    <div class="form-group mb-4">
                        <label>Confirm Password</label>
                        <input type="password" class="glass-input" id="pwd-confirm" required minlength="6">
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- 11. Settings View -->
    <template id="tpl-settings">
        <div class="view-header">
            <h2 class="view-title">Settings</h2>
            <p class="view-subtitle">Manage your account and application preferences</p>
        </div>
        <div class="glass-card fade-in" style="margin-top: 20px;">
            <div class="card-header">
                <h3>Application Settings</h3>
            </div>
            <div class="card-body">
                <div class="settings-group" style="padding: 20px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--card-border); padding-bottom: 15px; margin-bottom: 15px;">
                        <div>
                            <h4>Email Notifications</h4>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Receive alerts when attendance is modified.</p>
                        </div>
                        <div>
                            <input type="checkbox" checked style="accent-color: var(--primary);">
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h4>Compact View</h4>
                            <p style="color: var(--text-secondary); font-size: 0.9rem;">Display denser tables in attendance history.</p>
                        </div>
                        <div>
                            <input type="checkbox" style="accent-color: var(--primary);">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- 10. Search Results View -->
    <template id="tpl-search-results">
        <div class="view-content fade-in">
            <h3 class="mb-4">Search Results for: <span id="search-query-display" style="color: var(--primary);"></span></h3>
            <div class="glass-card">
                <div class="table-responsive">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Result Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="search-results-tbody">
                            <!-- Results injected here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </template>

    <!-- 11. Monthly Attendance Report View -->
    <template id="tpl-monthly-report">
        <div class="view-content fade-in" id="monthly-report-wrapper">
            <!-- Header with Quick Action Exports -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                <div>
                    <h3 style="margin-bottom: 5px; font-weight: 700;">Monthly Attendance Report</h3>
                    <p class="text-muted" style="font-size: 0.9rem; margin: 0;">Comprehensive aggregate attendance breakdown, trend statistics, and class summaries.</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button class="btn btn-outline" onclick="exportMonthlyReportPDF()"><i class="fa-solid fa-file-pdf text-danger"></i> Export PDF</button>
                    <button class="btn btn-outline" onclick="exportMonthlyReportExcel()"><i class="fa-solid fa-file-excel text-success"></i> Export Excel</button>
                    <button class="btn btn-outline" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
                    <a href="../satp/monthly_report.php" target="_blank" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-up-right-from-square"></i> Standalone SATP View</a>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="filters-card glass-card mb-4">
                <div class="filters-grid">
                    <div class="form-group">
                        <label>Month</label>
                        <select class="glass-input" id="rep-month">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07" selected>July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Year</label>
                        <select class="glass-input" id="rep-year">
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select class="glass-input" id="rep-dept">
                            <option value="">All Departments</option>
                            <option value="CE" selected>CE - Computer Engineering</option>
                            <option value="AIDS">AIDS - AI & Data Science</option>
                            <option value="EE">EE - Electrical Engineering</option>
                            <option value="BT">BT - Biotechnology</option>
                            <option value="ME">ME - Mechanical Engineering</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select class="glass-input" id="rep-sem">
                            <option value="">All Semesters</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5" selected>Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                        <select class="glass-input" id="rep-div">
                            <option value="">All Divisions</option>
                            <option value="Div A" selected>Div A</option>
                            <option value="Div B">Div B</option>
                            <option value="Div C">Div C</option>
                            <option value="Div D">Div D</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <select class="glass-input" id="rep-subject">
                            <option value="">All Subjects</option>
                        </select>
                    </div>
                </div>
                <div class="filter-actions mt-3" style="display: flex; gap: 10px;">
                    <button class="btn btn-primary" onclick="generateMonthlyReport()"><i class="fa-solid fa-filter"></i> Generate Report</button>
                    <button class="btn btn-outline" onclick="resetMonthlyFilters()"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                </div>
            </div>

            <!-- Summary Stat Cards -->
            <div class="stats-grid mb-4" id="monthly-stats-grid">
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Total Sessions</div>
                        <div class="stat-value" id="rep-total-sessions">--</div>
                        <div class="stat-subtitle text-blue"><i class="fa-solid fa-calendar-check"></i> Recorded Classes</div>
                    </div>
                    <div class="stat-icon blue"><i class="fa-solid fa-chalkboard"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Average Attendance</div>
                        <div class="stat-value" id="rep-avg-attendance">--%</div>
                        <div class="stat-subtitle text-green"><i class="fa-solid fa-arrow-trend-up"></i> Class Average</div>
                    </div>
                    <div class="stat-icon green"><i class="fa-solid fa-chart-line"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Above 75% Cutoff</div>
                        <div class="stat-value text-success" id="rep-safe-count">--</div>
                        <div class="stat-subtitle text-green"><i class="fa-solid fa-circle-check"></i> Safe Zone</div>
                    </div>
                    <div class="stat-icon green"><i class="fa-solid fa-user-shield"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Below 75% Defaulters</div>
                        <div class="stat-value text-danger" id="rep-defaulter-count">--</div>
                        <div class="stat-subtitle text-amber"><i class="fa-solid fa-triangle-exclamation"></i> Alerts Required</div>
                    </div>
                    <div class="stat-icon orange"><i class="fa-solid fa-bell"></i></div>
                </div>
            </div>

            <!-- Report Table -->
            <div class="glass-card">
                <div class="workspace-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h4 style="margin: 0; font-weight: 600;"><i class="fa-solid fa-table-list text-primary"></i> Attendance Registry (<span id="monthly-table-month-name">July 2026</span>)</h4>
                    <span class="badge" id="monthly-record-count-badge" style="background: rgba(79, 124, 255, 0.15); color: var(--primary); border: 1px solid rgba(79, 124, 255, 0.3); padding: 4px 10px; border-radius: 20px;">0 Records</span>
                </div>
                <div class="table-responsive">
                    <table class="glass-table" id="monthly-report-table">
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Department</th>
                                <th>Semester & Div</th>
                                <th>Total Classes</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Attendance %</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="monthly-report-tbody">
                            <tr><td colspan="10" class="text-center text-muted" style="padding: 30px;">Select filters and click Generate Report</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </template>

    <!-- 12. Student-wise Attendance Report View -->
    <template id="tpl-student-report">
        <div class="view-content fade-in" id="student-report-wrapper">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                <div>
                    <h3 style="margin-bottom: 5px; font-weight: 700;">Student-wise Attendance Report</h3>
                    <p class="text-muted" style="font-size: 0.9rem; margin: 0;">Detailed attendance performance, subject-wise breakdown, and log history for individual students.</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button class="btn btn-outline" onclick="exportStudentReportPDF()"><i class="fa-solid fa-file-pdf text-danger"></i> Export PDF</button>
                    <button class="btn btn-outline" onclick="exportStudentReportExcel()"><i class="fa-solid fa-file-excel text-success"></i> Export Excel</button>
                    <button class="btn btn-outline" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
                </div>
            </div>

            <!-- Student Filter / Search Bar -->
            <div class="filters-card glass-card mb-4">
                <div class="filters-grid">
                    <div class="form-group">
                        <label>Department</label>
                        <select class="glass-input" id="sr-dept" onchange="filterStudentReportList()">
                            <option value="">All Departments</option>
                            <option value="CE" selected>CE</option>
                            <option value="AIDS">AIDS</option>
                            <option value="EE">EE</option>
                            <option value="BT">BT</option>
                            <option value="ME">ME</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select class="glass-input" id="sr-sem" onchange="filterStudentReportList()">
                            <option value="">All Semesters</option>
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                            <option value="Semester 3">Semester 3</option>
                            <option value="Semester 4">Semester 4</option>
                            <option value="Semester 5" selected>Semester 5</option>
                            <option value="Semester 6">Semester 6</option>
                            <option value="Semester 7">Semester 7</option>
                            <option value="Semester 8">Semester 8</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                        <select class="glass-input" id="sr-div" onchange="filterStudentReportList()">
                            <option value="">All Divisions</option>
                            <option value="Div A" selected>Div A</option>
                            <option value="Div B">Div B</option>
                            <option value="Div C">Div C</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Select Student</label>
                        <select class="glass-input" id="sr-student-select" onchange="loadSelectedStudentReport()">
                            <option value="">-- Choose a Student --</option>
                        </select>
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end;">
                        <button class="btn btn-primary w-100" onclick="loadSelectedStudentReport()"><i class="fa-solid fa-magnifying-glass"></i> View Report</button>
                    </div>
                </div>
            </div>

            <!-- Student Profile & Overview Card -->
            <div id="student-report-details" style="display: none;">
                <div class="glass-card mb-4" style="padding: 25px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <img id="sr-avatar" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150" alt="Student Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary);">
                            <div>
                                <h3 id="sr-student-name" style="margin-bottom: 4px; font-weight: 700;">Student Name</h3>
                                <div style="display: flex; gap: 15px; flex-wrap: wrap; font-size: 0.9rem; color: var(--text-muted);">
                                    <span><i class="fa-solid fa-id-badge text-primary"></i> <strong id="sr-roll-no" class="text-white">CE5A01</strong></span>
                                    <span><i class="fa-solid fa-building text-info"></i> <span id="sr-dept-name">CE</span></span>
                                    <span><i class="fa-solid fa-layer-group text-purple"></i> <span id="sr-sem-div">Sem 5 - Div A</span></span>
                                    <span><i class="fa-solid fa-envelope text-amber"></i> <span id="sr-email">email@college.edu</span></span>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right; min-width: 180px;">
                            <span class="text-muted" style="font-size: 0.85rem;">Overall Attendance</span>
                            <div id="sr-overall-pct" style="font-size: 2.2rem; font-weight: 800; color: var(--success);">88.5%</div>
                            <span id="sr-status-pill" class="badge" style="background: rgba(16,185,129,0.2); color: #10B981; border: 1px solid rgba(16,185,129,0.4); padding: 4px 12px; border-radius: 20px;">Regular / Safe</span>
                        </div>
                    </div>
                </div>

                <!-- Subject-wise Breakdown Table -->
                <div class="glass-card mb-4">
                    <h4 class="mb-3" style="font-weight: 600;"><i class="fa-solid fa-book-open text-primary"></i> Subject-wise Attendance Breakdown</h4>
                    <div class="table-responsive">
                        <table class="glass-table" id="sr-subject-table">
                            <thead>
                                <tr>
                                    <th>Subject Code & Name</th>
                                    <th>Faculty In-Charge</th>
                                    <th>Total Conducted</th>
                                    <th>Attended</th>
                                    <th>Missed</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="sr-subject-tbody">
                                <!-- Injected dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Action to Dispatch Alert if low -->
                <div class="glass-card" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; padding: 15px 25px;">
                    <div>
                        <h5 style="margin: 0 0 4px 0; font-weight: 600;"><i class="fa-solid fa-bell text-warning"></i> Need to notify this student or parents?</h5>
                        <p class="text-muted" style="font-size: 0.85rem; margin: 0;">Send instant attendance warning notifications directly to registered email and student inbox.</p>
                    </div>
                    <button class="btn btn-primary" onclick="openSendAlertModalForStudent()"><i class="fa-solid fa-paper-plane"></i> Send Attendance Alert</button>
                </div>
            </div>
        </div>
    </template>

    <!-- 13. Department-wise Attendance Report View -->
    <template id="tpl-department-report">
        <div class="view-content fade-in" id="dept-report-wrapper">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                <div>
                    <h3 style="margin-bottom: 5px; font-weight: 700;">Department-wise Attendance Report</h3>
                    <p class="text-muted" style="font-size: 0.9rem; margin: 0;">Comparative institutional overview across departments, semester cohorts, and faculty metrics.</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button class="btn btn-outline" onclick="exportDeptReportPDF()"><i class="fa-solid fa-file-pdf text-danger"></i> Export PDF</button>
                    <button class="btn btn-outline" onclick="exportDeptReportExcel()"><i class="fa-solid fa-file-excel text-success"></i> Export Excel</button>
                    <button class="btn btn-outline" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
                </div>
            </div>

            <!-- Department Cards Grid -->
            <div class="stats-grid mb-4" id="dept-cards-grid">
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Computer Engineering</div>
                        <div class="stat-value text-blue" id="dept-stat-ce">84.2%</div>
                        <div class="stat-subtitle text-muted">120 Students Enrolled</div>
                    </div>
                    <div class="stat-icon blue"><i class="fa-solid fa-laptop-code"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">AI & Data Science</div>
                        <div class="stat-value text-purple" id="dept-stat-aids">82.8%</div>
                        <div class="stat-subtitle text-muted">95 Students Enrolled</div>
                    </div>
                    <div class="stat-icon purple"><i class="fa-solid fa-brain"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Electrical Engineering</div>
                        <div class="stat-value text-amber" id="dept-stat-ee">76.5%</div>
                        <div class="stat-subtitle text-muted">80 Students Enrolled</div>
                    </div>
                    <div class="stat-icon orange"><i class="fa-solid fa-bolt"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Mechanical Engineering</div>
                        <div class="stat-value text-green" id="dept-stat-me">79.1%</div>
                        <div class="stat-subtitle text-muted">110 Students Enrolled</div>
                    </div>
                    <div class="stat-icon green"><i class="fa-solid fa-gears"></i></div>
                </div>
            </div>

            <!-- Department Breakdown Table -->
            <div class="glass-card">
                <h4 class="mb-3" style="font-weight: 600;"><i class="fa-solid fa-building-columns text-primary"></i> Academic Division Performance Table</h4>
                <div class="table-responsive">
                    <table class="glass-table" id="dept-report-table">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Semester</th>
                                <th>Total Enrolled</th>
                                <th>Classes Conducted</th>
                                <th>Avg Present %</th>
                                <th>Defaulters (< 75%)</th>
                                <th>Performance Indicator</th>
                            </tr>
                        </thead>
                        <tbody id="dept-report-tbody">
                            <!-- Injected dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </template>

    <!-- 14. Low Attendance Alerts & Defaulter Log View -->
    <template id="tpl-low-attendance">
        <div class="view-content fade-in" id="low-attendance-wrapper">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                <div>
                    <h3 style="margin-bottom: 5px; font-weight: 700;"><i class="fa-solid fa-triangle-exclamation text-warning"></i> Low Attendance Alerts & Defaulter List</h3>
                    <p class="text-muted" style="font-size: 0.9rem; margin: 0;">Identify students below statutory attendance thresholds and dispatch official notifications.</p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button class="btn btn-primary" onclick="openBulkAlertModal()"><i class="fa-solid fa-paper-plane"></i> Send Alert to All Defaulters</button>
                    <button class="btn btn-outline" onclick="exportLowAttendancePDF()"><i class="fa-solid fa-file-pdf text-danger"></i> Export PDF</button>
                    <button class="btn btn-outline" onclick="exportLowAttendanceExcel()"><i class="fa-solid fa-file-excel text-success"></i> Export Excel</button>
                    <a href="../satp/low_attendance.php" target="_blank" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 6px;"><i class="fa-solid fa-up-right-from-square"></i> Standalone Alerts Hub</a>
                </div>
            </div>

            <!-- Threshold Configuration Bar -->
            <div class="glass-card mb-4" style="padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                    <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                        <label style="font-weight: 600; margin: 0; color: #fff;"><i class="fa-solid fa-sliders text-warning"></i> Attendance Alert Threshold:</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="number" class="glass-input" id="low-att-threshold" value="75" min="10" max="100" style="width: 85px; font-weight: 700; text-align: center;">
                            <span style="font-weight: 700; color: var(--text-muted);">%</span>
                            <button class="btn btn-primary btn-sm" onclick="applyLowAttendanceThreshold()"><i class="fa-solid fa-check"></i> Apply</button>
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap; font-size: 0.85rem;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; color: #EF4444;"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Critical Risk (&lt; 60%)</span>
                        <span style="display: inline-flex; align-items: center; gap: 6px; color: #F59E0B;"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Moderate Risk (60% - 74%)</span>
                        <span style="display: inline-flex; align-items: center; gap: 6px; color: #10B981;"><i class="fa-solid fa-circle" style="font-size: 8px;"></i> Safe Zone (&ge; 75%)</span>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid mb-4">
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Total Flagged Defaulters</div>
                        <div class="stat-value text-danger" id="low-stat-total">0</div>
                        <div class="stat-subtitle text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Below Threshold</div>
                    </div>
                    <div class="stat-icon red"><i class="fa-solid fa-user-xmark"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Critical Risk (&lt; 60%)</div>
                        <div class="stat-value text-red" id="low-stat-critical">0</div>
                        <div class="stat-subtitle text-danger"><i class="fa-solid fa-radiation"></i> Immediate Action</div>
                    </div>
                    <div class="stat-icon red"><i class="fa-solid fa-fire"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Moderate Risk (60-74%)</div>
                        <div class="stat-value text-warning" id="low-stat-moderate">0</div>
                        <div class="stat-subtitle text-amber"><i class="fa-solid fa-bell"></i> Warning Advised</div>
                    </div>
                    <div class="stat-icon orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-details">
                        <div class="stat-label">Alerts Sent Today</div>
                        <div class="stat-value text-blue" id="low-stat-alerts-sent">0</div>
                        <div class="stat-subtitle text-blue"><i class="fa-solid fa-paper-plane"></i> Dispatched</div>
                    </div>
                    <div class="stat-icon blue"><i class="fa-solid fa-paper-plane"></i></div>
                </div>
            </div>

            <!-- Defaulter Table -->
            <div class="glass-card">
                <div class="workspace-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h4 style="margin: 0; font-weight: 600;"><i class="fa-solid fa-list-check text-warning"></i> Flagged Defaulters List</h4>
                    <span class="badge" id="low-att-count-badge" style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 4px 10px; border-radius: 20px;">0 Students</span>
                </div>
                <div class="table-responsive">
                    <table class="glass-table" id="low-attendance-table">
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Department</th>
                                <th>Semester & Div</th>
                                <th>Attended / Total</th>
                                <th>Percentage</th>
                                <th>Risk Category</th>
                                <th>Notification Action</th>
                            </tr>
                        </thead>
                        <tbody id="low-attendance-tbody">
                            <!-- Injected dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </template>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Send Alert / Notification Modal -->
    <div class="modal-overlay" id="send-alert-modal">
        <div class="modal-content glass-card" style="max-width: 580px;">
            <div class="modal-header">
                <h3><i class="fa-solid fa-paper-plane text-primary"></i> Dispatch Attendance Alert</h3>
                <button class="close-modal" onclick="closeSendAlertModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="alert-form" onsubmit="event.preventDefault(); submitSendAlert();">
                    <input type="hidden" id="alert-recipient-type" value="single">
                    <input type="hidden" id="alert-student-id" value="">
                    <input type="hidden" id="alert-student-roll" value="">
                    <input type="hidden" id="alert-student-email" value="">

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Recipient(s)</label>
                        <input type="text" class="glass-input w-100" id="alert-recipient-display" readonly style="background: rgba(255,255,255,0.05); color: var(--primary);">
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Alert Title / Subject</label>
                        <input type="text" class="glass-input w-100" id="alert-subject" required value="Urgent: Low Attendance Warning Notice">
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Notification Channels</label>
                        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 5px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" id="chan-email" checked> <i class="fa-solid fa-envelope text-info"></i> Email Notice
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" id="chan-inapp" checked> <i class="fa-solid fa-bell text-warning"></i> Portal Notification
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" id="chan-sms" checked> <i class="fa-solid fa-comment-sms text-success"></i> Parent SMS Alert
                            </label>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Message Content</label>
                        <textarea class="glass-input w-100" id="alert-message" rows="4" style="resize: vertical; font-family: inherit;" required>Dear Student/Parent, your current attendance is below the mandatory 75% threshold. Please meet your faculty advisor and improve attendance immediately to avoid examination debarment.</textarea>
                    </div>

                    <div style="font-size: 0.85em; color: var(--text-muted); padding: 10px; background: rgba(255,255,255,0.03); border-radius: 8px; border: 1px solid var(--border-color);">
                        <i class="fa-solid fa-shield-halved text-success"></i> Official institutional notification log will be stamped with timestamp and faculty identifier.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeSendAlertModal()">Cancel</button>
                <button class="btn btn-primary" id="btn-dispatch-alert" onclick="submitSendAlert()"><i class="fa-solid fa-paper-plane"></i> Send Alert Now</button>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal-overlay" id="logout-modal">
        <div class="modal-content glass-panel">
            <div class="modal-header">
                <h3><i class="fa-solid fa-arrow-right-from-bracket text-red"></i> Confirm Logout</h3>
                <button class="close-modal" id="close-modal-btn"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to log out of your account? You will need to sign in again to access the portal.</p>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" id="cancel-logout-btn">Cancel</button>
                <button class="btn-danger" id="confirm-logout-btn">Yes, Logout</button>
            </div>
        </div>
    </div>

    <!-- Export Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    <!-- App Logic -->
    <script src="historyLogic.js?v=<?php echo time(); ?>"></script>
    <script src="validationLogic.js?v=<?php echo time(); ?>"></script>
    <script src="app.js?v=<?php echo time(); ?>"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown toggles
        const profileToggle = document.getElementById('profile-toggle');
        const profileMenu = document.getElementById('profile-menu');
        
        const notificationToggle = document.getElementById('notification-toggle');
        const notificationMenu = document.getElementById('notification-dropdown');
        
        if (profileToggle && profileMenu) {
            profileToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle('show');
                if (notificationMenu) notificationMenu.classList.remove('show');
            });
        }
        
        if (notificationToggle && notificationMenu) {
            notificationToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationMenu.classList.toggle('show');
                if (profileMenu) profileMenu.classList.remove('show');
            });
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            if (profileMenu) profileMenu.classList.remove('show');
            if (notificationMenu) notificationMenu.classList.remove('show');
        });
    });
    </script>
    <!-- Add Student Modal -->
    <div class="modal-overlay" id="add-student-modal">
        <div class="modal-content glass-card" style="max-width: 600px;">
            <div class="modal-header">
                <h3 id="student-modal-title"><i class="fa-solid fa-user-plus"></i> Add New Student</h3>
                <button class="close-modal" onclick="closeAddStudentModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="student-form">
                    <input type="hidden" id="student-id" value="">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Profile Photo</label>
                        <input type="file" class="glass-input w-100" id="new-student-photo" accept="image/*">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Student Name</label>
                        <input type="text" class="glass-input w-100" id="new-student-name" required placeholder="e.g. John Doe">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Roll Number (Enrollment ID)</label>
                        <input type="text" class="glass-input w-100" id="new-student-roll" required placeholder="e.g. CE3A01">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Email Address</label>
                        <input type="email" class="glass-input w-100" id="new-student-email" required placeholder="e.g. john@portal.com">
                    </div>
                    <div class="form-row" style="display: flex; gap: 15px; margin-bottom: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Department</label>
                            <select class="glass-input w-100" id="new-student-dept" required>
                                <option value="">Select</option>
                                <option value="CE">CE</option>
                                <option value="IT">IT</option>
                                <option value="AIDS">AIDS</option>
                                <option value="EE">EE</option>
                                <option value="ME">ME</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Semester</label>
                            <select class="glass-input w-100" id="new-student-sem" required>
                                <option value="">Select</option>
                                <option value="Semester 1">Semester 1</option>
                                <option value="Semester 2">Semester 2</option>
                                <option value="Semester 3">Semester 3</option>
                                <option value="Semester 4">Semester 4</option>
                                <option value="Semester 5">Semester 5</option>
                                <option value="Semester 6">Semester 6</option>
                                <option value="Semester 7">Semester 7</option>
                                <option value="Semester 8">Semester 8</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Division</label>
                            <select class="glass-input w-100" id="new-student-div" required>
                                <option value="">Select</option>
                                <option value="Div A">Div A</option>
                                <option value="Div B">Div B</option>
                                <option value="Div C">Div C</option>
                                <option value="Div D">Div D</option>
                                <option value="Div E">Div E</option>
                            </select>
                        </div>
                    </div>
                    <div style="font-size: 0.85em; color: var(--text-muted); margin-top: 10px;">
                        <i class="fa-solid fa-info-circle"></i> A login account will be automatically generated. The default password will be the Roll Number.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeAddStudentModal()">Cancel</button>
                <button class="btn btn-primary" id="save-student-btn" onclick="submitAddStudent()">Save Student</button>
            </div>
        </div>
    </div>

    <!-- Student Profile Modal -->
    <div class="modal-overlay" id="student-profile-modal">
        <div class="modal-content glass-card" style="max-width: 500px;">
            <div class="modal-header">
                <h3><i class="fa-solid fa-id-card"></i> Student Profile</h3>
                <button class="close-modal" onclick="closeStudentProfileModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" style="text-align: center;">
                <img id="profile-modal-photo" src="" alt="Profile" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-light); margin-bottom: 15px;">
                <h4 id="profile-modal-name" style="margin-bottom: 5px; font-size: 1.2rem;"></h4>
                <p id="profile-modal-roll" style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px;"></p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; text-align: left; background: var(--bg-card); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
                    <div>
                        <small style="color: var(--text-muted);">Department</small>
                        <div id="profile-modal-dept" style="font-weight: 500;"></div>
                    </div>
                    <div>
                        <small style="color: var(--text-muted);">Email</small>
                        <div id="profile-modal-email" style="font-weight: 500; word-break: break-all;"></div>
                    </div>
                    <div>
                        <small style="color: var(--text-muted);">Semester</small>
                        <div id="profile-modal-sem" style="font-weight: 500;"></div>
                    </div>
                    <div>
                        <small style="color: var(--text-muted);">Division</small>
                        <div id="profile-modal-div" style="font-weight: 500;"></div>
                    </div>
                    <div style="grid-column: span 2;">
                        <small style="color: var(--text-muted);">Attendance</small>
                        <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
                            <div class="progress-bar" style="flex: 1; height: 8px; background: var(--border-color); border-radius: 4px; overflow: hidden;">
                                <div id="profile-modal-attendance-bar" style="height: 100%; background: var(--primary-color); width: 0%;"></div>
                            </div>
                            <span id="profile-modal-attendance-text" style="font-weight: 500; font-size: 0.9rem;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

