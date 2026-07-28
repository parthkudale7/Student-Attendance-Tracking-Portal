<?php
$pageKey = 'low_attendance';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-content">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="page-container" id="reportExportArea">
        <!-- Header Title & Action Buttons -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold text-white mb-1">Low Attendance Alerts & Defaulter Log</h3>
                <p class="text-muted small mb-0">Monitor students falling below statutory attendance thresholds and
                    dispatch official notifications.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button onclick="exportToPDF('Low Attendance Defaulters')" class="btn-custom btn-secondary-custom">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF
                </button>
                <button onclick="exportToExcel('lowAttendanceTable', 'Low_Attendance_Defaulters')"
                    class="btn-custom btn-success-custom">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                </button>
                <button onclick="triggerPrint()" class="btn-custom btn-outline-glass">
                    <i class="bi bi-printer-fill"></i> Print
                </button>
            </div>
        </div>

        <!-- Threshold Config Card -->
        <div class="glass-card mb-4 animate-fade-in">
            <div class="card-header-flex">
                <div class="card-title text-warning"><i class="bi bi-sliders"></i> Alert Threshold Configuration</div>
                <span class="badge bg-warning text-dark fw-bold">Mandatory Standard: 75%</span>
            </div>
            <div class="row align-items-center g-3">
                <div class="col-md-4">
                    <label class="form-label text-white">Minimum Required Threshold (%):</label>
                    <div class="input-group">
                        <input type="number" class="form-control-dark w-100" id="thresholdInput" value="75" min="1"
                            max="100">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="d-flex gap-3 align-items-center flex-wrap mt-md-4">
                        <div class="badge-status badge-red"><i class="bi bi-x-octagon-fill"></i> Critical Risk (&lt;
                            60%)</div>
                        <div class="badge-status badge-orange"><i class="bi bi-exclamation-triangle-fill"></i> Moderate
                            Risk (60% - 74%)</div>
                        <div class="badge-status badge-green"><i class="bi bi-check-circle-fill"></i> Safe Threshold
                            (&ge; 75%)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="glass-card stats-card">
                    <div class="stats-info">
                        <span class="stats-label">Total Flagged Students</span>
                        <h2 class="stats-value text-white" id="statTotalFlagged">0</h2>
                        <span class="stats-sub text-warning"><i class="bi bi-exclamation-circle-fill"></i> Below
                            Cutoff</span>
                    </div>
                    <div class="stats-icon-wrapper icon-purple">
                        <i class="bi bi-flag-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card stats-card">
                    <div class="stats-info">
                        <span class="stats-label">Critical Risk (&lt; 60%)</span>
                        <h2 class="stats-value text-danger" id="statCriticalCount">0</h2>
                        <span class="stats-sub text-danger"><i class="bi bi-shield-slash-fill"></i> Immediate Action
                            Required</span>
                    </div>
                    <div class="stats-icon-wrapper icon-red">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card stats-card">
                    <div class="stats-info">
                        <span class="stats-label">Moderate Risk (60-74%)</span>
                        <h2 class="stats-value text-warning" id="statWarningCount">0</h2>
                        <span class="stats-sub text-warning"><i class="bi bi-bell-fill"></i> Warning Issued</span>
                    </div>
                    <div class="stats-icon-wrapper icon-purple">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Defaulter Table -->
        <div class="glass-card">
            <div class="card-header-flex">
                <div class="card-title text-danger"><i class="bi bi-person-x-fill"></i> Flagged Defaulter List</div>
                <div class="d-flex gap-2">
                    <button onclick="alert('Bulk SMS and Email alerts queued for dispatch to all flagged guardians.')"
                        class="btn-custom btn-danger-custom btn-sm">
                        <i class="bi bi-send-fill"></i> Send Bulk Alerts
                    </button>
                </div>
            </div>
            <div class="table-responsive-custom">
                <table class="table-dark-custom" id="lowAttendanceTable">
                    <thead>
                        <tr>
                            <th>Roll No</th>
                            <th>Student Name</th>
                            <th>Department</th>
                            <th>Sem & Div</th>
                            <th>Attendance %</th>
                            <th>Risk Status</th>
                            <th>Guardian Contact</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="lowAttendanceTableBody">
                        <!-- Dynamic Content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>