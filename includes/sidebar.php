<?php
/**
 * Admin Shared Sidebar & Topbar Component
 * Student Attendance Tracking Portal
 */
$userName = $_SESSION['user_name'] ?? 'Admin User';
$isFacultyActive = in_array($activeNav, ['faculty', 'faculty_registration', 'faculty_crud', 'subject_allocation', 'faculty_profile', 'assigned_subjects']);
?>
<!-- Sidebar Navigation -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-logo">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <div class="brand-title">Student Attendance<br>Tracking Portal</div>
            </div>
        </div>
        <!-- Square Arrow Button to toggle/collapse left sidebar -->
        <button class="sidebar-square-toggle-btn" id="sidebarCollapseBtn" title="Toggle Left Sidebar">
            <i class="fa-solid fa-chevron-left" id="sidebarHeaderArrow"></i>
        </button>
    </div>
    
    <ul class="sidebar-menu">
        <li class="sidebar-menu-label">ADMIN PANEL</li>
        
        <li class="sidebar-menu-item">
            <a href="dashboard.php" class="sidebar-menu-link <?php echo ($activeNav === 'dashboard') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-menu-item">
            <a href="department.php" class="sidebar-menu-link <?php echo ($activeNav === 'department') ? 'active' : ''; ?>">
                <i class="fa-solid fa-building-columns"></i>
                <span>Department Management</span>
            </a>
        </li>
        
        <li class="sidebar-menu-item">
            <a href="courses.php" class="sidebar-menu-link <?php echo ($activeNav === 'course') ? 'active' : ''; ?>">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Course Management</span>
            </a>
        </li>
        
        <li class="sidebar-menu-item">
            <a href="subjects.php" class="sidebar-menu-link <?php echo ($activeNav === 'subject') ? 'active' : ''; ?>">
                <i class="fa-solid fa-book-bookmark"></i>
                <span>Subject Management</span>
            </a>
        </li>
        
        <li class="sidebar-menu-item">
            <a href="semester_division.php" class="sidebar-menu-link <?php echo ($activeNav === 'semester_division') ? 'active' : ''; ?>">
                <i class="fa-solid fa-layer-group"></i>
                <span>Session & Division</span>
            </a>
        </li>
        
        <li class="sidebar-menu-item">
            <a href="academic_year.php" class="sidebar-menu-link <?php echo ($activeNav === 'academic_year') ? 'active' : ''; ?>">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Academic Year Configuration</span>
            </a>
        </li>

        <!-- Faculty Management Collapsible Main Topic -->
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-link d-flex align-items-center justify-content-between <?php echo $isFacultyActive ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#facultySubmenu" role="button" aria-expanded="<?php echo $isFacultyActive ? 'true' : 'false'; ?>" aria-controls="facultySubmenu">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>Faculty Management</span>
                </div>
                <i class="fa-solid fa-chevron-down submenu-arrow"></i>
            </a>
            
            <div class="collapse submenu-container <?php echo $isFacultyActive ? 'show' : ''; ?>" id="facultySubmenu">
                <ul class="submenu-list">
                    <li>
                        <a href="faculty_registration.php" class="submenu-link <?php echo ($activeNav === 'faculty_registration') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-user-plus text-primary"></i>
                            <span>Faculty Registration</span>
                        </a>
                    </li>
                    <li>
                        <a href="faculty.php" class="submenu-link <?php echo ($activeNav === 'faculty' || $activeNav === 'faculty_crud') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-users-gear text-info"></i>
                            <span>Faculty CRUD</span>
                        </a>
                    </li>
                    <li>
                        <a href="subject_allocation.php" class="submenu-link <?php echo ($activeNav === 'subject_allocation') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-diagram-project text-success"></i>
                            <span>Subject Allocation</span>
                        </a>
                    </li>
                    <li>
                        <a href="faculty_profile.php" class="submenu-link <?php echo ($activeNav === 'faculty_profile') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-address-card text-warning"></i>
                            <span>Faculty Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="assigned_subjects.php" class="submenu-link <?php echo ($activeNav === 'assigned_subjects') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-list-check text-danger"></i>
                            <span>Assigned Subjects</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="sidebar-menu-item mt-4">
            <a href="#" class="sidebar-menu-link text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>

<!-- Page Content Wrapper -->
<div id="content">
    <!-- Top Navbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-2">
            <div class="topbar-welcome ms-1">
                <span id="dynamicGreeting">Good Morning ☀️</span>, <span class="ms-1 fw-bold text-white text-uppercase" style="letter-spacing: 0.6px;">SUPER ADMIN</span>
            </div>
        </div>
        
        <div class="topbar-right">
            <!-- Command Palette (CTRL + K) Quick Trigger Button -->
            <button class="cmd-palette-btn d-flex align-items-center gap-2 me-1" id="cmdPaletteTrigger" title="Quick Search (Ctrl + K)">
                <i class="fa-solid fa-magnifying-glass text-primary" style="font-size: 0.85rem;"></i>
                <span class="d-none d-lg-inline" style="font-size: 0.82rem;">Search...</span>
                <kbd class="cmd-kbd">ctrl k</kbd>
            </button>

            <!-- Theme-Matching Dark Blue Custom Calendar Widget -->
            <div class="date-pill" title="Select Date">
                <i class="fa-solid fa-calendar-days text-primary"></i>
                <input type="text" id="topbarDatePicker" class="dark-date-input" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>

            <!-- Super Admin Profile Dropdown (Sleek Shield Logo Only, No Crown) -->
            <div class="dropdown">
                <button class="user-dropdown-btn dropdown-toggle no-arrow" type="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Super Admin Options">
                    <div class="user-avatar-circle">
                        <i class="fa-solid fa-user-shield" style="font-size: 1.15rem; color: #FFFFFF;"></i>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end dark-dropdown-menu mt-2" aria-labelledby="userProfileDropdown">
                    <li class="px-3 py-2 border-bottom border-secondary border-opacity-25">
                        <div class="fw-semibold text-white small"><?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="text-primary font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.6px;">SUPER ADMIN</div>
                    </li>
                    <li>
                        <a class="dropdown-item mt-1" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="fa-solid fa-user-gear text-primary"></i> Edit Super Admin Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fa-solid fa-key text-warning"></i> Change Password
                        </a>
                    </li>
                    <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Content Area Container -->
    <main class="main-content">
