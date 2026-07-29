<?php
session_start();
require_once '../includes/auth_guard.php';
check_auth(['admin']); // Only admin allowed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cyberpunk Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand"><i class="bi bi-shield-check text-neon-blue"></i> ADMIN OP</div>
        <a href="#" class="nav-link active"><i class="bi bi-grid-1x2"></i> Dashboard</a>
        <a href="#" class="nav-link"><i class="bi bi-building"></i> Departments</a>
        <a href="#" class="nav-link"><i class="bi bi-people"></i> Faculty</a>
        <a href="#" class="nav-link"><i class="bi bi-mortarboard"></i> Students</a>
        <a href="#" class="nav-link"><i class="bi bi-book"></i> Subjects</a>
        <a href="#" class="nav-link"><i class="bi bi-clipboard-data"></i> Attendance</a>
        <a href="#" class="nav-link"><i class="bi bi-bar-chart"></i> Analytics</a>
        <a href="#" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
        <a href="../auth/change_password.php" class="nav-link"><i class="bi bi-key"></i> Security</a>
        <a href="../auth/face_setup.php" class="nav-link text-neon-blue"><i class="bi bi-person-bounding-box"></i> Face ID Setup</a>
        <a href="../auth/logout.php" class="nav-link mt-auto"><i class="bi bi-box-arrow-right text-neon-pink"></i> Logout</a>
    </div>

    <!-- Topbar -->
    <div class="topbar">
        <div class="search-bar">
            <input type="text" placeholder="Search records...">
        </div>
        <div class="topbar-icons">
            <div class="notification-bell">
                <i class="bi bi-bell fs-5"></i>
                <span class="badge rounded-pill">3</span>
            </div>
            <div class="profile-dropdown dropdown">
                <div class="d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff" alt="Profile">
                    <span class="text-white fw-bold d-none d-md-block"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                </div>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark mt-2">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="../auth/change_password.php"><i class="bi bi-key me-2"></i>Change Password</a></li>
                    <li><a class="dropdown-item text-neon-blue" href="../auth/face_setup.php"><i class="bi bi-person-bounding-box me-2"></i>Setup Face ID</a></li>
                    <li><hr class="dropdown-divider border-secondary"></li>
                    <li><a class="dropdown-item text-neon-pink" href="../auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white"><i class="bi bi-grid-1x2 text-neon-blue me-2"></i> System Overview</h2>
            <div class="text-secondary"><?php echo date('l, F j, Y'); ?></div>
        </div>
        
        <!-- Stats Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Total Students</h5>
                    <h3 class="text-neon-blue">1,245</h3>
                    <i class="bi bi-mortarboard icon"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Total Faculty</h5>
                    <h3 class="text-neon-purple">86</h3>
                    <i class="bi bi-people icon"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Avg Attendance</h5>
                    <h3 class="text-neon-green">89.4%</h3>
                    <i class="bi bi-clipboard-data icon"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>System Status</h5>
                    <h3 class="text-neon-pink">Optimal</h3>
                    <i class="bi bi-hdd-network icon"></i>
                </div>
            </div>
        </div>

        <!-- Chart Placeholder -->
        <div class="row">
            <div class="col-12">
                <div class="stat-card" style="height: 400px; display: flex; align-items:center; justify-content:center; flex-direction:column;">
                    <i class="bi bi-bar-chart-line" style="font-size: 3rem; color: var(--neon-blue); opacity: 0.5;"></i>
                    <p class="text-secondary mt-3">Chart.js Analytics Canvas</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/session-manager.js"></script>
</body>
</html>
