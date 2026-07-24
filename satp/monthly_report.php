<?php
$pageKey = 'monthly';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-content">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="page-container" id="reportExportArea">
        <!-- Page Title & Quick Actions Header -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold text-white mb-1">Monthly Attendance Report</h3>
                <p class="text-muted small mb-0">Overview of student monthly attendance logs, statistics, and trends.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button onclick="exportToPDF('Monthly Attendance Report')" class="btn-custom btn-secondary-custom">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF
                </button>
                <button onclick="exportToExcel('monthlyTable', 'Monthly_Attendance_Report')" class="btn-custom btn-success-custom">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                </button>
                <button onclick="triggerPrint()" class="btn-custom btn-outline-glass">
                    <i class="bi bi-printer-fill"></i> Print
                </button>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="glass-card mb-4 animate-fade-in">
            <div class="card-header-flex">
                <div class="card-title">
                    <i class="bi bi-funnel-fill"></i> Attendance Filters
                </div>
                <span class="badge bg-secondary opacity-75">Multi-tier Filter Engine</span>
            </div>
            <div class="filter-grid">
                <div class="form-group">
                    <label class="form-label">Month</label>
                    <select class="form-control-dark" id="filterMonth">
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
                    <label class="form-label">Year</label>
                    <select class="form-control-dark" id="filterYear">
                        <?php for ($y = 2027; $y >= 2001; $y--): ?>
                            <option value="<?= $y ?>" <?= $y === 2026 ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Department</label>
                    <select class="form-control-dark filter-dept" id="filterDept">
                        <option value="">All Departments</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Semester</label>
                    <select class="form-control-dark" id="filterSem">
                        <option value="">All Semesters</option>
                        <?php for ($s = 1; $s <= 8; $s++): ?>
                            <option value="<?= $s ?>" <?= $s === 5 ? 'selected' : '' ?>>Sem <?= $s ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Division</label>
                    <select class="form-control-dark" id="filterDiv">
                        <option value="">All Divisions</option>
                        <?php foreach (['A', 'B', 'C', 'D', 'E'] as $d): ?>
                            <option value="<?= $d ?>">Division <?= $d ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <select class="form-control-dark filter-subject" id="filterSubject">
                        <option value="">All Subjects</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Faculty</label>
                    <select class="form-control-dark filter-faculty" id="filterFaculty">
                        <option value="">All Faculty</option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="btnGenerateMonthly" onclick="generateMonthlyReport()" class="btn-custom btn-primary-custom flex-grow-1">
                        <i class="bi bi-play-fill"></i> Generate
                    </button>
                    <button type="button" id="btnResetMonthly" onclick="resetMonthlyFilters()" class="btn-custom btn-outline-glass" title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Grid -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stats-card clickable-stat-card active-stat" id="cardTotalStudents" title="Click to view all student records">
                    <div class="stats-info">
                        <span class="stats-label">Total Students</span>
                        <h2 class="stats-value" id="statTotalStudents">0</h2>
                        <span class="stats-sub"><i class="bi bi-arrow-up-short"></i> Active Enrollment</span>
                    </div>
                    <div class="stats-icon-wrapper icon-blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stats-card clickable-stat-card" id="cardTotalPresent" title="Click to filter present/safe student records">
                    <div class="stats-info">
                        <span class="stats-label">Present Days</span>
                        <h2 class="stats-value text-success" id="statTotalPresent">0</h2>
                        <span class="stats-sub text-success"><i class="bi bi-check-circle-fill"></i> Total Attended</span>
                    </div>
                    <div class="stats-icon-wrapper icon-green">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stats-card clickable-stat-card" id="cardTotalAbsent" title="Click to filter absent/at-risk student records">
                    <div class="stats-info">
                        <span class="stats-label">Absent Days</span>
                        <h2 class="stats-value text-danger" id="statTotalAbsent">0</h2>
                        <span class="stats-sub text-danger"><i class="bi bi-x-circle-fill"></i> Total Unattended</span>
                    </div>
                    <div class="stats-icon-wrapper icon-red">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stats-card clickable-stat-card" id="cardOverallPct" title="Click to sort by overall attendance %">
                    <div class="stats-info">
                        <span class="stats-label">Attendance %</span>
                        <h2 class="stats-value" id="statOverallPct">0%</h2>
                        <span class="stats-sub"><i class="bi bi-graph-up-arrow"></i> Monthly Average</span>
                    </div>
                    <div class="stats-icon-wrapper icon-purple">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visual Analytics Grid -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="glass-card h-100">
                    <div class="card-header-flex">
                        <div class="card-title" id="trendCardTitle"><i class="bi bi-graph-up"></i> Monthly Attendance Trend</div>
                        <span class="text-muted small" id="trendSubtitle">Annual Monthly Performance</span>
                    </div>
                    <div style="height: 280px; position: relative;">
                        <canvas id="chartMonthlyTrend"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="glass-card h-100">
                    <div class="card-header-flex">
                        <div class="card-title"><i class="bi bi-pie-chart"></i> Attendance Standing</div>
                    </div>
                    <div style="height: 280px; position: relative;">
                        <canvas id="chartMonthlyDist"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Attendance Data Table -->
        <div class="glass-card">
            <div class="card-header-flex">
                <div class="card-title" id="tableHeaderTitle"><i class="bi bi-table"></i> Student Monthly Records</div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary px-3 py-2 rounded-pill" id="tableRecordCountBadge">0 Students</span>
                    <input type="text" class="form-control-dark" placeholder="Search table..." style="width: 200px;" onkeyup="filterTable(this, 'monthlyTable')">
                </div>
            </div>
            <div class="table-responsive-custom">
                <table class="table-dark-custom" id="monthlyTable">
                    <thead>
                        <tr>
                            <th>Roll Number</th>
                            <th>Student Name</th>
                            <th>Department</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Attendance %</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="monthlyTableBody">
                        <!-- Populated dynamically via JS -->
                    </tbody>
                </table>
            </div>
            <div class="pagination-container">
                <span class="text-muted">Showing active filtered student logs</span>
                <div class="pagination-btns">
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
