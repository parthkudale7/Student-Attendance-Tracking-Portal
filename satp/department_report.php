<?php
$pageKey = 'department';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-content">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="page-container" id="reportExportArea">
        <!-- Title & Action Buttons -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold text-white mb-1">Department Wise Attendance Report</h3>
                <p class="text-muted small mb-0">Cross-department analytics, semester comparisons, and top performing departments.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button onclick="exportToPDF('Department Attendance Report')" class="btn-custom btn-secondary-custom">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF
                </button>
                <button onclick="exportToExcel('deptTable', 'Department_Attendance_Report')" class="btn-custom btn-success-custom">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                </button>
                <button onclick="triggerPrint()" class="btn-custom btn-outline-glass">
                    <i class="bi bi-printer-fill"></i> Print
                </button>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="glass-card mb-4 animate-fade-in">
            <div class="filter-grid">
                <div class="form-group">
                    <label class="form-label">Academic Year</label>
                    <select class="form-control-dark">
                        <option value="2025-2026" selected>2025-2026</option>
                        <option value="2024-2025">2024-2025</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Semester Scope</label>
                    <select class="form-control-dark">
                        <option value="">All Semesters (1 - 8)</option>
                        <option value="odd">Odd Semesters (1, 3, 5, 7)</option>
                        <option value="even">Even Semesters (2, 4, 6, 8)</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button id="btnGenDept" class="btn-custom btn-primary-custom flex-grow-1">
                        <i class="bi bi-filter"></i> Apply Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Grid -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stats-card">
                    <div class="stats-info">
                        <span class="stats-label">Average Attendance</span>
                        <h2 class="stats-value text-info" id="statDeptAvg">0%</h2>
                        <span class="stats-sub"><i class="bi bi-shield-check"></i> Institution Average</span>
                    </div>
                    <div class="stats-icon-wrapper icon-cyan">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stats-card">
                    <div class="stats-info">
                        <span class="stats-label">Highest Dept</span>
                        <h2 class="stats-value text-success fs-5 fw-bold" id="statDeptHighest">--</h2>
                        <span class="stats-sub text-success"><i class="bi bi-trophy-fill"></i> Top Performing</span>
                    </div>
                    <div class="stats-icon-wrapper icon-green">
                        <i class="bi bi-award-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stats-card">
                    <div class="stats-info">
                        <span class="stats-label">Lowest Dept</span>
                        <h2 class="stats-value text-warning fs-5 fw-bold" id="statDeptLowest">--</h2>
                        <span class="stats-sub text-warning"><i class="bi bi-exclamation-circle-fill"></i> Requires Attention</span>
                    </div>
                    <div class="stats-icon-wrapper icon-red">
                        <i class="bi bi-graph-down-arrow"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stats-card">
                    <div class="stats-info">
                        <span class="stats-label">Total Enrollment</span>
                        <h2 class="stats-value text-white" id="statDeptTotalStudents">0</h2>
                        <span class="stats-sub"><i class="bi bi-people-fill"></i> Registered Students</span>
                    </div>
                    <div class="stats-icon-wrapper icon-purple">
                        <i class="bi bi-journal-text"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparative Charts Grid (3 Charts) -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="glass-card h-100">
                    <div class="card-header-flex">
                        <div class="card-title"><i class="bi bi-bar-chart-line-fill"></i> Department Comparison</div>
                    </div>
                    <div style="height: 260px; position: relative;">
                        <canvas id="chartDeptComparison"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="glass-card h-100">
                    <div class="card-header-flex">
                        <div class="card-title"><i class="bi bi-diagram-3-fill"></i> Semester Breakdown</div>
                    </div>
                    <div style="height: 260px; position: relative;">
                        <canvas id="chartSemComparison"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="glass-card">
                    <div class="card-header-flex">
                        <div class="card-title"><i class="bi bi-journal-code"></i> Subject-Wise Comparative Metrics</div>
                    </div>
                    <div style="height: 240px; position: relative;">
                        <canvas id="chartSubjComparison"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Table -->
        <div class="glass-card">
            <div class="card-header-flex">
                <div class="card-title"><i class="bi bi-table"></i> Department Summary Table</div>
            </div>
            <div class="table-responsive-custom">
                <table class="table-dark-custom" id="deptTable">
                    <thead>
                        <tr>
                            <th>Dept Code</th>
                            <th>Department Name</th>
                            <th>Total Students</th>
                            <th>Average Attendance</th>
                            <th>Performance Status</th>
                        </tr>
                    </thead>
                    <tbody id="deptTableBody">
                        <!-- Dynamic Content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
