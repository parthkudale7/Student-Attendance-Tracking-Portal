<?php
session_start();
require_once '../includes/auth_guard.php';
check_auth(['student']); // Only student allowed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Tracking Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- html2pdf and html2canvas for downloads -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Background Particles -->
    <div class="bg-particles" id="particles-container"></div>

    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h2>Student Attendance Tracking Portal</h2>
            </div>

            <div class="sidebar-section-title">DASHBOARD & ANALYTICS</div>
            
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active" data-target="view-dashboard" data-title="Dashboard">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-item" data-target="view-daily" data-title="View Attendance">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>View Attendance</span>
                </a>
                <a href="#" class="nav-item" data-target="view-subject" data-title="Subject-wise Attendance">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Subject-wise Attendance</span>
                </a>
                <a href="#" class="nav-item" data-target="view-percentage" data-title="Attendance Percentage">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Attendance Percentage</span>
                </a>
                <a href="#" class="nav-item" data-target="view-monthly" data-title="Monthly Trends">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Monthly Trends</span>
                </a>
                <a href="#" class="nav-item" data-target="view-download" data-title="Reports">
                    <i class="fa-solid fa-file-invoice"></i>
                    <span>Reports</span>
                </a>
                <a href="#" class="nav-item" data-target="view-settings" data-title="Settings">
                    <i class="fa-solid fa-gear"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="../auth/logout.php" class="nav-item logout" id="logout-btn">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <h1 class="page-title" id="topbar-title">Good Morning, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Student'); ?> 👋</h1>
                    <p class="page-subtitle" id="topbar-subtitle">Welcome back to your dashboard</p>
                </div>
                
                <div class="topbar-right">
                    <div class="date-display">
                        <i class="fa-regular fa-calendar"></i>
                        <span id="current-date">May 07, 2025</span>
                    </div>
                    
                    <div class="notification-bell">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge"></span>
                    </div>
                    
                    <div class="user-profile">
                        <img src="https://i.pravatar.cc/150?img=11" alt="Profile" class="avatar">
                        <div class="user-info">
                            <span class="user-name"><?php echo htmlspecialchars($_SESSION['name'] ?? 'Student'); ?></span>
                            <span class="user-role">Student</span>
                        </div>
                        <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                    </div>
                </div>
            </header>

            <!-- Views Container -->
            <div class="views-container">
                <!-- 1. Dashboard View -->
                <div class="view-section active" id="view-dashboard">
                    <div class="dashboard-grid">
                        <!-- Stats Cards -->
                        <div class="stats-row">
                            <div class="stat-card glass-panel">
                                <div class="stat-info">
                                    <span class="stat-label">Total Subjects</span>
                                    <span class="stat-value text-blue">07</span>
                                </div>
                                <div class="stat-icon icon-blue">
                                    <i class="fa-regular fa-calendar-days"></i>
                                </div>
                            </div>
                            
                            <div class="stat-card glass-panel">
                                <div class="stat-info">
                                    <span class="stat-label">Attendance Percentage</span>
                                    <span class="stat-value text-green">83.05%</span>
                                </div>
                                <div class="stat-chart-small">
                                    <svg viewBox="0 0 36 36" class="circular-chart green">
                                        <path class="circle-bg"
                                            d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831"
                                        />
                                        <path class="circle"
                                            stroke-dasharray="83.05, 100"
                                            d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831"
                                        />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="stat-card glass-panel">
                                <div class="stat-info">
                                    <span class="stat-label">Present Lectures</span>
                                    <span class="stat-value text-green">98</span>
                                </div>
                                <div class="stat-icon icon-green">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                            </div>
                            
                            <div class="stat-card glass-panel">
                                <div class="stat-info">
                                    <span class="stat-label">Absent Lectures</span>
                                    <span class="stat-value text-red">20</span>
                                </div>
                                <div class="stat-icon icon-red">
                                    <i class="fa-solid fa-xmark"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Middle Row -->
                        <div class="middle-row">
                            <!-- Attendance Overview Chart -->
                            <div class="chart-container glass-panel">
                                <div class="panel-header">
                                    <h3>Attendance Overview</h3>
                                    <input type="month" class="dropdown-filter" style="color-scheme: dark; outline: none;" value="2025-05">
                                </div>
                                <div class="canvas-wrapper">
                                    <canvas id="overviewChart"></canvas>
                                </div>
                            </div>

                            <!-- Recent Subjects -->
                            <div class="recent-subjects glass-panel">
                                <h3>Recent Subjects</h3>
                                <ul class="subject-list">
                                    <li>
                                        <div class="sub-icon icon-purple"><i class="fa-solid fa-database"></i></div>
                                        <div class="sub-details">
                                            <span class="sub-code">DBMS</span>
                                            <span class="sub-name">Database Management Systems</span>
                                        </div>
                                        <span class="sub-percent text-green">78%</span>
                                    </li>
                                    <li>
                                        <div class="sub-icon icon-teal"><i class="fa-solid fa-sitemap"></i></div>
                                        <div class="sub-details">
                                            <span class="sub-code">DSA</span>
                                            <span class="sub-name">Data Structures & Algorithms</span>
                                        </div>
                                        <span class="sub-percent text-green">82%</span>
                                    </li>
                                    <li>
                                        <div class="sub-icon icon-orange"><i class="fa-solid fa-network-wired"></i></div>
                                        <div class="sub-details">
                                            <span class="sub-code">Computer Networks</span>
                                            <span class="sub-name">Computer Networks</span>
                                        </div>
                                        <span class="sub-percent text-green">71%</span>
                                    </li>
                                    <li>
                                        <div class="sub-icon icon-purple"><i class="fa-brands fa-linux"></i></div>
                                        <div class="sub-details">
                                            <span class="sub-code">Operating Systems</span>
                                            <span class="sub-name">Operating Systems</span>
                                        </div>
                                        <span class="sub-percent text-green">75%</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="quick-actions-section">
                            <h3>Quick Actions</h3>
                            <div class="quick-actions-grid">
                                <div class="action-btn glass-panel" onclick="navigateTo('view-daily')">
                                    <div class="action-icon bg-blue-glow"><i class="fa-solid fa-calendar-check"></i></div>
                                    <span>View Daily<br>Attendance</span>
                                </div>
                                <div class="action-btn glass-panel" onclick="navigateTo('view-subject')">
                                    <div class="action-icon bg-purple-glow"><i class="fa-solid fa-book-open"></i></div>
                                    <span>Subject-wise<br>Attendance</span>
                                </div>
                                <div class="action-btn glass-panel" onclick="navigateTo('view-percentage')">
                                    <div class="action-icon bg-green-glow"><i class="fa-solid fa-chart-pie"></i></div>
                                    <span>Attendance<br>Percentage</span>
                                </div>
                                <div class="action-btn glass-panel" onclick="navigateTo('view-download')">
                                    <div class="action-icon bg-blue-glow"><i class="fa-solid fa-download"></i></div>
                                    <span>Download<br>Report</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Present / Absent Statistics -->
                <div class="view-section" id="view-daily">
                    <div class="stats-row">
                        <div class="stat-card glass-panel">
                            <div class="stat-info">
                                <span class="stat-label">Total Lectures</span>
                                <span class="stat-value text-blue">98</span>
                            </div>
                        </div>
                        <div class="stat-card glass-panel">
                            <div class="stat-info">
                                <span class="stat-label">Present Lectures</span>
                                <span class="stat-value text-green">75</span>
                            </div>
                        </div>
                        <div class="stat-card glass-panel">
                            <div class="stat-info">
                                <span class="stat-label">Absent Lectures</span>
                                <span class="stat-value text-red">23</span>
                            </div>
                        </div>
                        <div class="stat-card glass-panel flex-row">
                            <div class="stat-info">
                                <span class="stat-label">Attendance %</span>
                                <span class="stat-value text-green">76.45%</span>
                            </div>
                            <div class="stat-chart-small">
                                <svg viewBox="0 0 36 36" class="circular-chart green">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="circle" stroke-dasharray="76.45, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="middle-row" style="margin-top: 20px;">
                        <div class="chart-container glass-panel" style="flex: 1;">
                            <div class="panel-header">
                                <h3>Present vs Absent</h3>
                            </div>
                            <div class="canvas-wrapper" style="height: 250px; display: flex; justify-content: center; align-items: center; margin-top: 15px;">
                                <canvas id="presentAbsentChart" style="max-height: 230px;"></canvas>
                            </div>
                        </div>
                        
                        <div class="recent-subjects glass-panel" style="flex: 1;">
                            <h3>Statistics Summary</h3>
                            <ul class="subject-list" style="margin-top: 15px;">
                                <li style="display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                                    <span class="sub-name">This Month</span>
                                    <span class="sub-percent">18 (80.00%)</span>
                                </li>
                                <li style="display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                                    <span class="sub-name">Last Month</span>
                                    <span class="sub-percent">20 (74.07%)</span>
                                </li>
                                <li style="display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                                    <span class="sub-name">This Semester</span>
                                    <span class="sub-percent">75 (76.45%)</span>
                                </li>
                                <li style="display: flex; justify-content: space-between;">
                                    <span class="sub-name">Overall (All Time)</span>
                                    <span class="sub-percent">76.45%</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="stats-row" style="margin-top: 20px;">
                        <div class="stat-card glass-panel" style="flex: 1; flex-direction: row; justify-content: space-between; align-items: center;">
                            <div class="stat-info">
                                <span class="stat-label">Daily Average</span>
                                <span class="stat-value text-blue" style="font-size: 1.8rem;">81.25%</span>
                            </div>
                            <div class="stat-icon" style="color: var(--color-green); font-size: 0.9rem;">
                                <i class="fa-solid fa-arrow-up"></i> 5.23%
                            </div>
                        </div>
                        <div class="stat-card glass-panel" style="flex: 1; flex-direction: row; justify-content: space-between; align-items: center;">
                            <div class="stat-info">
                                <span class="stat-label">Longest Streak</span>
                                <span class="stat-value text-purple" style="font-size: 1.8rem;">12 <span style="font-size: 1rem; color: var(--text-muted);">Days</span></span>
                            </div>
                            <div class="stat-icon" style="color: var(--color-green); font-size: 0.9rem;">
                                <i class="fa-solid fa-arrow-up"></i> 2 Days
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Subject-wise Graphs -->
                <div class="view-section" id="view-subject">
                    <div class="subject-layout" style="display: flex; flex-direction: column; gap: 20px;">
                        <!-- Top: Bar Chart -->
                        <div class="glass-panel w-100" style="width: 100%;">
                            <div class="panel-header">
                                <h3>Subject-wise Attendance</h3>
                                <select class="dropdown-filter" style="background: transparent; border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 5px 10px; border-radius: 6px; outline: none; cursor: pointer;">
                                    <option value="sem3" style="background: #000;" selected>This Semester</option>
                                    <option value="sem2" style="background: #000;">Last Semester</option>
                                </select>
                            </div>
                            <div class="canvas-wrapper" style="height: 300px; margin-top: 20px;">
                                <canvas id="subjectBarChart"></canvas>
                            </div>
                        </div>

                        <!-- Bottom: Table -->
                        <div class="table-container glass-panel">
                            <div class="panel-header" style="margin-bottom: 15px;">
                                <h3>Subject Summary</h3>
                            </div>
                            <table class="attendance-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>Total Lectures</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                        <th>Attendance %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Database Management Systems</td>
                                        <td>15</td>
                                        <td>14</td>
                                        <td>4</td>
                                        <td><span class="text-green">78%</span></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Data Structures & Algorithms</td>
                                        <td>18</td>
                                        <td>15</td>
                                        <td>3</td>
                                        <td><span class="text-green">82%</span></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Computer Networks</td>
                                        <td>14</td>
                                        <td>10</td>
                                        <td>4</td>
                                        <td><span class="text-yellow">71%</span></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Operating Systems</td>
                                        <td>20</td>
                                        <td>15</td>
                                        <td>5</td>
                                        <td><span class="text-yellow">75%</span></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Discrete Mathematics</td>
                                        <td>16</td>
                                        <td>10</td>
                                        <td>3</td>
                                        <td><span class="text-green">81%</span></td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Software Engineering</td>
                                        <td>12</td>
                                        <td>9</td>
                                        <td>3</td>
                                        <td><span class="text-green">76%</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 4. Attendance Percentage -->
                <div class="view-section" id="view-percentage">
                    <div class="percentage-layout">
                        <div class="percentage-top glass-panel">
                            <h3>Overall Attendance</h3>
                            <div class="large-circular-chart glow-effect">
                                <svg viewBox="0 0 36 36" class="circular-chart-lg green">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="circle" stroke-dasharray="83.05, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <div class="chart-content">
                                    <span class="percentage">83.05%</span>
                                    <span class="status text-green">Excellent <span class="emoji">≡ƒöÑ</span></span>
                                </div>
                            </div>
                            <p class="status-msg">You have met the minimum attendance requirement.</p>

                            <div class="summary-pills">
                                <div class="pill">
                                    <span class="lbl">Total Lectures</span>
                                    <span class="val text-blue">118</span>
                                </div>
                                <div class="pill">
                                    <span class="lbl">Present Lectures</span>
                                    <span class="val text-green">98</span>
                                </div>
                                <div class="pill">
                                    <span class="lbl">Absent Lectures</span>
                                    <span class="val text-red">20</span>
                                </div>
                                <div class="pill">
                                    <span class="lbl">Required %</span>
                                    <span class="val text-purple">75%</span>
                                </div>
                            </div>
                        </div>

                        <div class="percentage-bottom glass-panel">
                            <div class="panel-header">
                                <h3>Attendance Trend</h3>
                                <input type="month" class="dropdown-filter" style="color-scheme: dark; outline: none;" value="2025-05">
                            </div>
                            <div class="canvas-wrapper trend-wrapper">
                                <canvas id="trendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Monthly Trends -->
                <div class="view-section" id="view-monthly">
                    <div class="monthly-layout">
                        <div class="monthly-top glass-panel">
                            <div class="panel-header">
                                <h3>Monthly Attendance Trends</h3>
                                <select class="dropdown-filter" style="color-scheme: dark; outline: none; cursor: pointer; background: transparent; border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 5px 10px; border-radius: 6px;">
                                    <option value="6months" style="background: #000;">Last 6 Months</option>
                                    <option value="12months" style="background: #000;">Last 12 Months</option>
                                </select>
                            </div>
                            
                            <div class="summary-pills" style="margin-bottom: 20px;">
                                <div class="pill">
                                    <span class="lbl">Lowest Month</span>
                                    <span class="val text-red" style="font-size: 1.2rem;">December</span>
                                    <span class="val-sub" style="font-size: 12px; color: var(--text-muted); display: block;">71.20%</span>
                                </div>
                                <div class="pill">
                                    <span class="lbl">Average (6 Months)</span>
                                    <span class="val text-blue" style="font-size: 1.5rem;">76.45%</span>
                                </div>
                                <div class="pill">
                                    <span class="lbl">Improvement</span>
                                    <span class="val text-green" style="font-size: 1.5rem;">+ 5.25%</span>
                                    <span class="val-sub" style="font-size: 12px; color: var(--text-muted); display: block;">vs last 6 months</span>
                                </div>
                            </div>
                            
                            <div class="canvas-wrapper trend-wrapper" style="height: 300px;">
                                <canvas id="monthlyTrendChart"></canvas>
                            </div>
                        </div>

                        <div class="table-container glass-panel" style="margin-top: 20px;">
                            <div class="panel-header" style="margin-bottom: 15px;">
                                <h3>Month-wise Details</h3>
                            </div>
                            <table class="attendance-table">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Total Lectures</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                        <th>Attendance %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>December</td>
                                        <td>15</td>
                                        <td>11</td>
                                        <td>4</td>
                                        <td><span class="text-red">71.20%</span></td>
                                    </tr>
                                    <tr>
                                        <td>January</td>
                                        <td>18</td>
                                        <td>13</td>
                                        <td>5</td>
                                        <td><span class="text-red">71.45%</span></td>
                                    </tr>
                                    <tr>
                                        <td>February</td>
                                        <td>17</td>
                                        <td>14</td>
                                        <td>3</td>
                                        <td><span class="text-green">82.35%</span></td>
                                    </tr>
                                    <tr>
                                        <td>March</td>
                                        <td>15</td>
                                        <td>12</td>
                                        <td>3</td>
                                        <td><span class="text-yellow">75.60%</span></td>
                                    </tr>
                                    <tr>
                                        <td>April</td>
                                        <td>20</td>
                                        <td>15</td>
                                        <td>5</td>
                                        <td><span class="text-yellow">76.85%</span></td>
                                    </tr>
                                    <tr>
                                        <td>May</td>
                                        <td>12</td>
                                        <td>10</td>
                                        <td>2</td>
                                        <td><span class="text-green">78.90%</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 6. Download Attendance Report -->
                <div class="view-section" id="view-download">
                    <div class="download-layout">
                        <div class="download-form-panel glass-panel">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <div class="input-wrapper">
                                        <input type="date" id="report-from-date" value="2025-04-01" style="color-scheme: dark;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>To Date</label>
                                    <div class="input-wrapper">
                                        <input type="date" id="report-to-date" value="2025-05-07" style="color-scheme: dark;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Select Subject</label>
                                    <div class="input-wrapper select">
                                        <select id="report-subject" style="background: transparent; border: none; color: var(--text-main); font-size: 14px; outline: none; width: 100%; cursor: pointer; appearance: none;">
                                            <option value="all">All Subjects</option>
                                            <option value="dbms">Database Management Systems</option>
                                            <option value="dsa">Data Structures & Algorithms</option>
                                            <option value="cn">Computer Networks</option>
                                            <option value="os">Operating Systems</option>
                                        </select>
                                        <i class="fa-solid fa-chevron-down" style="pointer-events: none; position: absolute; right: 16px;"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Select Format</label>
                                    <div class="input-wrapper select">
                                        <select id="report-format" style="background: transparent; border: none; color: var(--text-main); font-size: 14px; outline: none; width: 100%; cursor: pointer; appearance: none;">
                                            <option value="pdf">PDF Document</option>
                                            <option value="image">Image (PNG)</option>
                                        </select>
                                        <i class="fa-solid fa-chevron-down" style="pointer-events: none; position: absolute; right: 16px;"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="report-preview-box" id="report-preview-box">
                                <div class="preview-icon">
                                    <i class="fa-regular fa-file-lines"></i>
                                </div>
                                <div class="preview-details">
                                    <h3>Attendance Report</h3>
                                    <p id="preview-date-range">01 Apr 2025 - 07 May 2025</p>
                                    <ul class="preview-features">
                                        <li><i class="fa-solid fa-circle-check text-green"></i> <span id="preview-subject-text">Subject-wise attendance</span></li>
                                        <li><i class="fa-solid fa-circle-check text-green"></i> Daily attendance summary</li>
                                        <li><i class="fa-solid fa-circle-check text-green"></i> Overall attendance percentage</li>
                                        <li><i class="fa-solid fa-circle-check text-green"></i> Lectures attended vs missed</li>
                                    </ul>
                                </div>
                            </div>

                            <button class="btn-primary gradient-btn" id="download-report-btn">
                                <i class="fa-solid fa-download"></i> Download Report
                            </button>
                            
                            <p class="form-hint"><i class="fa-solid fa-circle-info text-blue"></i> Reports are generated based on the selected date range and subject.</p>
                        </div>
                    </div>
                </div>

                <div class="view-section" id="view-settings">
                    <div class="settings-layout" style="display: grid; gap: 24px; max-width: 800px; margin: 0 auto;">
                        <div class="glass-panel" style="padding: 24px;">
                            <h3 style="margin-bottom: 20px; font-weight: 600;"><i class="fa-solid fa-user-lock text-blue"></i> Account Settings</h3>
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 8px; color: var(--text-muted); font-size: 14px;">Email Address</label>
                                <input type="email" class="glass-input" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" readonly style="width: 100%; opacity: 0.7; cursor: not-allowed; background: rgba(255,255,255,0.02); border: 1px solid var(--panel-border); padding: 10px 14px; border-radius: 8px; color: white;">
                            </div>
                            <div class="form-group" style="margin-bottom: 24px;">
                                <label style="display: block; margin-bottom: 8px; color: var(--text-muted); font-size: 14px;">Role</label>
                                <input type="text" class="glass-input" value="Student" readonly style="width: 100%; opacity: 0.7; cursor: not-allowed; background: rgba(255,255,255,0.02); border: 1px solid var(--panel-border); padding: 10px 14px; border-radius: 8px; color: white;">
                            </div>
                            
                            <h4 style="margin: 30px 0 16px; font-weight: 500; font-size: 16px; border-bottom: 1px solid var(--panel-border); padding-bottom: 8px; color: white;">Change Password</h4>
                            <form id="change-password-form" onsubmit="event.preventDefault(); alert('Password change functionality will be implemented in the next update.');">
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label style="display: block; margin-bottom: 8px; color: var(--text-muted); font-size: 14px;">Current Password</label>
                                    <input type="password" class="glass-input" id="current-password" required style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--panel-border); padding: 10px 14px; border-radius: 8px; color: white; transition: 0.3s;">
                                </div>
                                <div class="form-group" style="margin-bottom: 16px;">
                                    <label style="display: block; margin-bottom: 8px; color: var(--text-muted); font-size: 14px;">New Password</label>
                                    <input type="password" class="glass-input" id="new-password" required style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--panel-border); padding: 10px 14px; border-radius: 8px; color: white; transition: 0.3s;">
                                </div>
                                <div class="form-group" style="margin-bottom: 24px;">
                                    <label style="display: block; margin-bottom: 8px; color: var(--text-muted); font-size: 14px;">Confirm New Password</label>
                                    <input type="password" class="glass-input" id="confirm-password" required style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--panel-border); padding: 10px 14px; border-radius: 8px; color: white; transition: 0.3s;">
                                </div>
                                <button type="submit" class="btn-primary" style="width: 100%; padding: 12px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; background: var(--primary-blue); color: white;">Update Password</button>
                            </form>
                        </div>
                        
                        <div class="glass-panel" style="padding: 24px;">
                            <h3 style="margin-bottom: 20px; font-weight: 600;"><i class="fa-solid fa-camera text-purple"></i> Biometric Settings</h3>
                            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px; line-height: 1.5;">Register or update your Face ID to allow automated attendance tracking in class.</p>
                            <a href="../auth/face_setup.php" class="btn-primary" style="display: inline-block; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 500; background: #7c3aed; color: white;"><i class="fa-solid fa-expand"></i> Manage Face ID</a>
                        </div>
                    </div>
                </div>

            </div>
        </main>
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

    <script src="script.js?v=2"></script>
</body>
</html>
