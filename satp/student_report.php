<?php
$pageKey = 'student';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-content">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="page-container" id="reportExportArea">
        <!-- Header & Action Buttons -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold text-white mb-1">Student Wise Attendance Report</h3>
                <p class="text-muted small mb-0">Detailed individual student profile, subject breakdown, and history log.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button onclick="exportToPDF('Student Attendance Report')" class="btn-custom btn-secondary-custom">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF
                </button>
                <button onclick="exportToExcel('studentSubjectTable', 'Student_Subject_Report')" class="btn-custom btn-success-custom">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                </button>
                <button onclick="triggerPrint()" class="btn-custom btn-outline-glass">
                    <i class="bi bi-printer-fill"></i> Print
                </button>
            </div>
        </div>

        <!-- Student Selector Bar -->
        <div class="glass-card mb-4 animate-fade-in">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="form-label mb-0 fw-semibold text-white"><i class="bi bi-person-search me-2"></i> Select Student:</label>
                </div>
                <div class="col-md-9">
                    <select class="form-control-dark w-100" id="studentSelect">
                        <!-- Populated dynamically via JS -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Student Profile Card Header -->
        <div class="glass-card mb-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <img id="stAvatar" src="https://api.dicebear.com/7.x/bottts/svg?seed=Alex" alt="Student Profile" class="rounded-circle border border-2 border-info p-1" style="width: 90px; height: 90px; background: rgba(255,255,255,0.05);">
                </div>
                <div class="col">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <h3 class="fw-bold text-white mb-0" id="stName">Alex Mercer</h3>
                        <span id="stStatusBadge" class="badge-status badge-green"><i class="bi bi-shield-check"></i> Good Standing</span>
                    </div>
                    <p class="text-muted small mt-1 mb-2">Roll No: <strong class="text-white" id="stRollNo">CS2026-001</strong> | PRN: <strong class="text-white" id="stPRN">PRN2024001</strong></p>
                    <div class="d-flex gap-4 flex-wrap text-muted small">
                        <span><i class="bi bi-building text-info me-1"></i> Dept: <strong class="text-white" id="stDept">Computer Science</strong></span>
                        <span><i class="bi bi-mortarboard text-info me-1"></i> Class: <strong class="text-white" id="stSemDiv">Sem 5 - Div A</strong></span>
                    </div>
                </div>
                <div class="col-auto text-end">
                    <div class="p-3 rounded-4" style="background: rgba(108, 99, 255, 0.1); border: 1px solid rgba(108, 99, 255, 0.3);">
                        <span class="text-muted small d-block text-uppercase fw-semibold">Overall Attendance</span>
                        <h2 class="fw-bold text-info mb-0" id="stOverallPct">95.0%</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Charts Grid (3 Charts) -->
        <div class="row g-4 mb-4">
            <div class="col-lg-5">
                <div class="glass-card h-100">
                    <div class="card-header-flex">
                        <div class="card-title"><i class="bi bi-bar-chart-fill"></i> Subject-Wise Breakdown</div>
                    </div>
                    <div style="height: 240px; position: relative;">
                        <canvas id="chartStudentSubject"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="glass-card h-100">
                    <div class="card-header-flex">
                        <div class="card-title"><i class="bi bi-graph-up-arrow"></i> Monthly Attendance Trend</div>
                    </div>
                    <div style="height: 240px; position: relative;">
                        <canvas id="chartStudentTrend"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="glass-card h-100">
                    <div class="card-header-flex">
                        <div class="card-title"><i class="bi bi-pie-chart-fill"></i> Ratio</div>
                    </div>
                    <div style="height: 240px; position: relative;">
                        <canvas id="chartStudentPie"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Wise Attendance Table -->
        <div class="glass-card mb-4">
            <div class="card-header-flex">
                <div class="card-title"><i class="bi bi-journal-bookmark-fill"></i> Subject Attendance Summary</div>
            </div>
            <div class="table-responsive-custom">
                <table class="table-dark-custom">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Faculty In-Charge</th>
                            <th>Total Classes</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Attendance %</th>
                        </tr>
                    </thead>
                    <tbody id="studentSubjectTable">
                        <!-- Dynamic Content -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Attendance History Log -->
        <div class="glass-card">
            <div class="card-header-flex">
                <div class="card-title"><i class="bi bi-clock-history"></i> Recent Session Log</div>
            </div>
            <div class="table-responsive-custom">
                <table class="table-dark-custom">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Subject & Module</th>
                            <th>Faculty</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="studentHistoryTable">
                        <!-- Dynamic Content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
