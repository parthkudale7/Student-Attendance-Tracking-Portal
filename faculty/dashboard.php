<?php
session_start();
require_once '../includes/auth_guard.php';
check_auth(['faculty', 'admin']);
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
    <link rel="stylesheet" href="style.css?v=2">
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
                <div class="nav-section-title">Faculty Module</div>
                <a href="#dashboard" class="nav-item active" data-view="dashboard">
                    <i class="fa-solid fa-border-all"></i>
                    <span>Dashboard</span>
                </a>
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
            </nav>

            <div class="sidebar-footer">
                <a href="../auth/logout.php" class="nav-item logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Navbar -->
            <header class="top-navbar">
                <div class="page-title" id="page-title">Dashboard</div>
                <div class="navbar-actions">
                    <div class="search-bar" id="global-search-container" style="position: relative;">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="global-search-input" placeholder="Search...">
                        <div class="dropdown-panel" id="search-suggestions">
                            <!-- Suggestions injected here -->
                        </div>
                    </div>
                    <div class="notification-btn" id="notification-toggle" style="position: relative;">
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
                    <div class="profile-dropdown" id="profile-toggle" style="position: relative;">
                        <img src="https://ui-avatars.com/api/?name=Prof+Smith&background=0D8ABC&color=fff" alt="Profile" class="profile-img" id="nav-profile-img">
                        <div class="profile-info">
                            <div class="profile-name" id="nav-profile-name"><?php echo htmlspecialchars($_SESSION['name'] ?? 'Faculty'); ?></div>
                            <div class="profile-role" id="nav-profile-role">Computer Science</div>
                        </div>
                        <i class="fa-solid fa-chevron-down"></i>
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
                    <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Faculty'); ?>!</h2>
                    <p>Here's an overview of your classes and attendance today.</p>
                </div>
                <div class="banner-image">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card glass-card">
                    <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-details">
                        <div class="stat-value" id="dash-total-students">--</div>
                        <div class="stat-label">Total Students</div>
                    </div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-icon purple"><i class="fa-solid fa-clipboard-user"></i></div>
                    <div class="stat-details">
                        <div class="stat-value" id="dash-classes-today">--</div>
                        <div class="stat-label">Classes Today</div>
                    </div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-icon green"><i class="fa-solid fa-check-circle"></i></div>
                    <div class="stat-details">
                        <div class="stat-value" id="dash-avg-attendance">--</div>
                        <div class="stat-label">Avg. Attendance</div>
                    </div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
                    <div class="stat-details">
                        <div class="stat-value" id="dash-pending-validation">--</div>
                        <div class="stat-label">Pending Validation</div>
                    </div>
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
            <div class="filters-card glass-card premium-filter-panel" style="padding: 24px; border-radius: 18px;">
                <div class="filters-grid premium-filters" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; align-items: end;">
                    <div class="form-group premium-group mb-0">
                        <label>Date Range</label>
                        <div class="date-range-wrapper" style="position: relative;">
                            <i class="fa-regular fa-calendar" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                            <input type="text" class="glass-input date-range-input" id="hist-date-range" placeholder="01/07/2025 - 27/07/2026" style="padding-left: 38px; width: 100%;">
                        </div>
                    </div>
                    <div class="form-group premium-group mb-0">
                        <label>Department</label>
                        <select class="glass-input" id="hist-dept" onchange="window.historyLogic.onDeptChange()">
                            <option value="">All Departments</option>
                        </select>
                    </div>
                    <div class="form-group premium-group mb-0">
                        <label>Semester</label>
                        <select class="glass-input" id="hist-sem" onchange="window.historyLogic.onSemChange()">
                            <option value="">All Semesters</option>
                        </select>
                    </div>
                    <div class="form-group premium-group mb-0">
                        <label>Division</label>
                        <select class="glass-input" id="hist-div">
                            <option value="">All Divisions</option>
                        </select>
                    </div>
                    <div class="form-group premium-group mb-0">
                        <label>Subject</label>
                        <select class="glass-input" id="hist-subject">
                            <option value="">All Subjects</option>
                        </select>
                    </div>
                    <div class="form-group filter-actions-premium mb-0" style="display: flex; gap: 12px; grid-column: span 1;">
                        <button class="btn btn-primary premium-btn-blue" onclick="window.historyLogic.applyFilters()" style="flex: 1;"><i class="fa-solid fa-filter"></i> Apply Filters</button>
                        <button class="btn btn-outline premium-btn-reset" onclick="window.historyLogic.resetFilters()"><i class="fa-solid fa-rotate-right"></i> Reset</button>
                    </div>
                </div>
            </div>

            <!-- History Table Container -->
            <div class="attendance-table-container glass-card premium-table-card" id="hist-table-container" style="display: flex; flex-direction: column; overflow: visible; border-radius: 18px; padding: 0;">
                
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

                <div class="table-scroll" style="max-height: 550px; overflow-y: auto;">
                    <table class="glass-table premium-history-table" style="width: 100%; border-collapse: collapse; min-width: 1400px;">
                        <thead style="position: sticky; top: 0; z-index: 2; background: rgba(7, 26, 58, 0.95); backdrop-filter: blur(10px);">
                            <tr>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Semester</th>
                                <th>Division</th>
                                <th>Subject</th>
                                <th>Lecture No.</th>
                                <th>Roll No.</th>
                                <th>Student Name</th>
                                <th>Attendance Status</th>
                                <th>Validation Status</th>
                                <th>Faculty</th>
                                <th>Validated By</th>
                                <th>Validation Date & Time</th>
                                <th>Remarks</th>
                                <th>Actions</th>
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
            <div class="glass-card profile-card" style="max-width: 600px; margin: 0 auto; text-align: center; padding: 2rem;">
                <img src="" alt="Profile" id="my-profile-img" style="width: 120px; height: 120px; border-radius: 50%; margin-bottom: 1rem; border: 3px solid rgba(255,255,255,0.2);">
                <h2 id="my-profile-name" style="margin-bottom: 0.5rem;">Faculty Name</h2>
                <p id="my-profile-designation" style="color: var(--text-secondary); margin-bottom: 1.5rem;">Designation</p>
                
                <div class="profile-details-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: left; margin-bottom: 2rem; background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 12px;">
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.85rem;">Faculty ID</span>
                        <div id="my-profile-id" style="font-weight: 500;">-</div>
                    </div>
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.85rem;">Department</span>
                        <div id="my-profile-dept" style="font-weight: 500;">-</div>
                    </div>
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.85rem;">Email ID</span>
                        <div id="my-profile-email" style="font-weight: 500;">-</div>
                    </div>
                    <div>
                        <span style="color: var(--text-secondary); font-size: 0.85rem;">Mobile Number</span>
                        <div id="my-profile-mobile" style="font-weight: 500;">-</div>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="navigateTo('edit-profile')"><i class="fa-solid fa-pen"></i> Edit Profile</button>
            </div>
        </div>
    </template>

    <!-- 8. Edit Profile View -->
    <template id="tpl-edit-profile">
        <div class="view-content fade-in">
            <div class="glass-card profile-card" style="max-width: 600px; margin: 0 auto; padding: 2rem;">
                <h3 class="card-title mb-4">Edit Profile</h3>
                <form id="edit-profile-form" onsubmit="saveProfile(event)">
                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text" class="glass-input" id="edit-name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email ID</label>
                        <input type="email" class="glass-input" id="edit-email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Mobile Number</label>
                        <input type="text" class="glass-input" id="edit-mobile" required>
                    </div>
                    <div class="form-group mb-4">
                        <label>Profile Picture URL</label>
                        <input type="url" class="glass-input" id="edit-picture">
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

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Export Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    <!-- App Logic -->
    <script src="historyLogic.js?v=6"></script>
    <script src="validationLogic.js?v=6"></script>
    <script src="app.js?v=6"></script>
</body>
</html>

