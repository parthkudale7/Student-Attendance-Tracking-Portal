<?php
$activePage = $pageKey ?? 'monthly';
?>
<!-- Vertical Dark Glass Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="monthly_report.php" class="sidebar-logo">
            <div class="logo-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div class="logo-text">Student Attendance <span>Tracking Portal</span></div>
        </a>
    </div>

    <ul class="sidebar-menu">
        <li class="menu-category">Main Navigation</li>
        <li class="menu-item">
            <a href="monthly_report.php">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="menu-category">Analytics & Reports</li>
        <li class="menu-item <?= $activePage === 'monthly' ? 'active' : '' ?>">
            <a href="monthly_report.php">
                <i class="bi bi-calendar3-event-fill"></i>
                <span>Monthly Report</span>
            </a>
        </li>
        <li class="menu-item <?= $activePage === 'student' ? 'active' : '' ?>">
            <a href="student_report.php">
                <i class="bi bi-person-bounding-box"></i>
                <span>Student Report</span>
            </a>
        </li>
        <li class="menu-item <?= $activePage === 'department' ? 'active' : '' ?>">
            <a href="department_report.php">
                <i class="bi bi-building-fill-gear"></i>
                <span>Department Report</span>
            </a>
        </li>
        <li class="menu-item <?= $activePage === 'low_attendance' ? 'active' : '' ?>">
            <a href="low_attendance.php">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Low Attendance</span>
                <span class="badge bg-danger ms-auto rounded-pill" style="font-size: 0.65rem;">Alerts</span>
            </a>
        </li>

        <li class="menu-category">Account</li>
        <li class="menu-item <?= $activePage === 'faculty' ? 'active' : '' ?>">
            <a href="faculty_profile.php">
                <i class="bi bi-person-badge-fill"></i>
                <span>Faculty Profile</span>
            </a>
        </li>
        <li class="menu-item logout-item">
            <a href="#" onclick="confirmLogout(event)" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
            <div class="rounded-circle bg-success" style="width: 8px; height: 8px; box-shadow: 0 0 8px #22C55E;"></div>
            <span class="small text-muted" style="font-size: 0.75rem;">System Status: <strong>Online</strong></span>
        </div>
    </div>
</aside>
