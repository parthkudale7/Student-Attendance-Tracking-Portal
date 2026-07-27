<?php
session_start();
require_once '../includes/auth_guard.php';
check_auth(['faculty']); // Only faculty allowed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - Cyberpunk Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="sidebar">
        <div class="brand"><i class="bi bi-person-workspace text-neon-purple"></i> FACULTY OP</div>
        <a href="#" class="nav-link active"><i class="bi bi-grid-1x2"></i> Dashboard</a>
        <a href="#" class="nav-link"><i class="bi bi-clipboard-check"></i> Attendance</a>
        <a href="#" class="nav-link"><i class="bi bi-mortarboard"></i> Students</a>
        <a href="#" class="nav-link"><i class="bi bi-book"></i> Subjects</a>
        <a href="#" class="nav-link"><i class="bi bi-file-earmark-text"></i> Reports</a>
        <a href="../auth/change_password.php" class="nav-link"><i class="bi bi-key"></i> Security</a>
        <a href="../auth/logout.php" class="nav-link mt-auto"><i class="bi bi-box-arrow-right text-neon-pink"></i> Logout</a>
    </div>

    <div class="topbar">
        <div class="search-bar">
            <input type="text" placeholder="Search students...">
        </div>
        <div class="topbar-icons">
            <div class="notification-bell">
                <i class="bi bi-bell fs-5"></i>
                <span class="badge rounded-pill">1</span>
            </div>
            <div class="profile-dropdown dropdown">
                <div class="d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name=Faculty&background=7B61FF&color=fff" alt="Profile">
                    <span class="text-white fw-bold d-none d-md-block"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                </div>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark mt-2">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="../auth/change_password.php"><i class="bi bi-key me-2"></i>Change Password</a></li>
                    <li><hr class="dropdown-divider border-secondary"></li>
                    <li><a class="dropdown-item text-neon-pink" href="../auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white"><i class="bi bi-grid-1x2 text-neon-purple me-2"></i> Faculty Overview</h2>
            <div class="text-secondary"><?php echo date('l, F j, Y'); ?></div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <h5>Assigned Subjects</h5>
                    <h3 class="text-neon-blue">4</h3>
                    <i class="bi bi-book icon"></i>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h5>Classes Today</h5>
                    <h3 class="text-neon-purple">3</h3>
                    <i class="bi bi-calendar-check icon"></i>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h5>Pending Attendance</h5>
                    <h3 class="text-neon-pink">1</h3>
                    <i class="bi bi-exclamation-circle icon"></i>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/session-manager.js"></script>
</body>
</html>
