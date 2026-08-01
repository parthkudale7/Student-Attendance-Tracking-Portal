<?php
$pageTitle = "Admin Dashboard";
$activeNav = "dashboard";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header / Hero Banner -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Super Admin Dashboard</h2>
        <p class="page-header-subtitle">Overview of university portal masters, faculty management, and subject allocations</p>
    </div>
    <div class="d-flex gap-2">
        <a href="faculty_registration.php" class="btn-primary-action">
            <i class="fa-solid fa-user-plus"></i> Add Faculty
        </a>
        <a href="subject_allocation.php" class="btn btn-outline-primary btn-sm rounded-3 d-inline-flex align-items-center gap-2" style="background: rgba(79, 124, 255, 0.12); border-color: rgba(79, 124, 255, 0.35); color: #4F7CFF; font-weight: 600; padding: 0.6rem 1.2rem; border-radius: 10px !important;">
            <i class="fa-solid fa-diagram-project"></i> Allocate Subject
        </a>
    </div>
</div>

<!-- SaaS Productivity Banner & Dual Insights Grid -->
<div class="row g-3 mb-4">
    <!-- Productivity Greeting Banner -->
    <div class="col-12 col-xl-7">
        <div class="data-card mb-0 p-4 h-100 d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, rgba(11,23,48,0.92) 0%, rgba(18,31,61,0.85) 100%) !important;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-primary bg-opacity-15 border border-primary border-opacity-30 mb-2">
                        <span class="pulse-dot bg-success rounded-circle" style="width: 8px; height: 8px; display: inline-block;"></span>
                        <span class="text-primary font-weight-bold" style="font-size: 0.78rem; letter-spacing: 0.5px;">SYSTEM OPERATIONAL</span>
                    </div>
                    <h3 class="text-white fw-bold mb-1" style="letter-spacing: -0.4px;">
                        <span id="dynamicGreeting">Good Morning 👋</span>, Super Admin
                    </h3>
                    <p class="small mb-0" style="color: #CBD5E1; font-weight: 500;">Hope you're having a productive day. Here's today's attendance & allocation summary.</p>
                </div>

                <!-- Circular Progress Ring Widget -->
                <div class="d-flex align-items-center gap-3 bg-dark bg-opacity-50 p-2 px-3 rounded-4 border border-secondary border-opacity-25">
                    <div class="position-relative d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                        <svg class="progress-ring-svg" width="54" height="54">
                            <circle class="progress-ring-circle-bg" stroke-width="4.5" fill="transparent" r="21" cx="27" cy="27"/>
                            <circle class="progress-ring-circle-val" stroke-width="4.5" stroke-dasharray="131.9" stroke-dashoffset="19.7" fill="transparent" r="21" cx="27" cy="27"/>
                        </svg>
                        <span class="position-absolute text-white font-weight-bold" style="font-size: 0.75rem;">85%</span>
                    </div>
                    <div>
                        <div class="text-white fw-semibold small">Active Rate</div>
                        <small class="text-success" style="font-size: 0.72rem;"><i class="fa-solid fa-arrow-trend-up me-1"></i>+4.2% week</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dual Insights Column (Tip of the Day + Quote of the Day Cards) -->
    <div class="col-12 col-xl-5">
        <div class="row g-2 h-100">
            <!-- 1. Tip of the Day Card -->
            <div class="col-12 col-sm-6">
                <div class="data-card mb-0 p-3 h-100 d-flex flex-column justify-content-between" style="background: rgba(11,23,48,0.88) !important; border: 1px solid rgba(245, 158, 11, 0.25) !important;">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="stat-icon-wrapper p-0" style="width: 28px; height: 28px; background: rgba(245, 158, 11, 0.15); color: #F59E0B; border-radius: 8px;">
                                <i class="fa-solid fa-lightbulb" style="font-size: 0.8rem;"></i>
                            </div>
                            <span class="fw-bold text-white uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">Tip of the Day</span>
                        </div>
                        <p class="small mb-0" id="dailyTipText" style="color: #CBD5E1; font-weight: 500; font-size: 0.78rem; line-height: 1.35;">
                            Export attendance and course records directly to CSV or Excel from data tables.
                        </p>
                    </div>
                    <div class="mt-2 text-end">
                        <span class="badge bg-warning bg-opacity-15 text-warning border border-warning border-opacity-25" style="font-size: 0.65rem;">PRO TIP</span>
                    </div>
                </div>
            </div>

            <!-- 2. Quote of the Day Card -->
            <div class="col-12 col-sm-6">
                <div class="data-card mb-0 p-3 h-100 d-flex flex-column justify-content-between" style="background: rgba(11,23,48,0.88) !important; border: 1px solid rgba(79, 124, 255, 0.25) !important;">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="stat-icon-wrapper p-0" style="width: 28px; height: 28px; background: rgba(79, 124, 255, 0.15); color: #4F7CFF; border-radius: 8px;">
                                <i class="fa-solid fa-quote-left" style="font-size: 0.8rem;"></i>
                            </div>
                            <span class="fw-bold text-white uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">Quote of the Day</span>
                        </div>
                        <p class="small mb-1 fst-italic" id="dailyQuoteText" style="color: #E2E8F0; font-weight: 500; font-size: 0.78rem; line-height: 1.35;">
                            "Focus on being productive instead of busy."
                        </p>
                    </div>
                    <div class="text-end">
                        <small class="text-primary fw-semibold" id="dailyQuoteAuthor" style="font-size: 0.72rem;">ΓÇö Tim Ferriss</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Symmetrical 5-Column Row for KPI Stat Cards -->
<div class="stat-cards-5-grid mb-4">
    <!-- Card 1: Departments -->
    <div class="stat-card p-3">
        <div class="stat-card-top-accent accent-blue"></div>
        <div class="d-flex align-items-center justify-content-between w-100 mb-2">
            <div class="stat-icon-wrapper stat-icon-blue">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <span class="stat-trend-badge text-success"><i class="fa-solid fa-arrow-trend-up me-1"></i>Active</span>
        </div>
        <div>
            <div class="stat-label">DEPARTMENTS</div>
            <div class="stat-value" id="dashDepts">3</div>
        </div>
    </div>

    <!-- Card 2: Courses -->
    <div class="stat-card p-3">
        <div class="stat-card-top-accent accent-purple"></div>
        <div class="d-flex align-items-center justify-content-between w-100 mb-2">
            <div class="stat-icon-wrapper stat-icon-purple">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <span class="stat-trend-badge text-primary"><i class="fa-solid fa-layer-group me-1"></i>Master</span>
        </div>
        <div>
            <div class="stat-label">COURSES</div>
            <div class="stat-value" id="dashCourses">3</div>
        </div>
    </div>

    <!-- Card 3: Subjects -->
    <div class="stat-card p-3">
        <div class="stat-card-top-accent accent-green"></div>
        <div class="d-flex align-items-center justify-content-between w-100 mb-2">
            <div class="stat-icon-wrapper stat-icon-green">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <span class="stat-trend-badge text-success"><i class="fa-solid fa-circle-check me-1"></i>Live</span>
        </div>
        <div>
            <div class="stat-label">SUBJECTS</div>
            <div class="stat-value" id="dashSubjects">3</div>
        </div>
    </div>

    <!-- Card 4: Faculties -->
    <div class="stat-card p-3">
        <div class="stat-card-top-accent accent-sky"></div>
        <div class="d-flex align-items-center justify-content-between w-100 mb-2">
            <div class="stat-icon-wrapper stat-icon-sky">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <span class="stat-trend-badge text-info"><i class="fa-solid fa-user-check me-1"></i>Verified</span>
        </div>
        <div>
            <div class="stat-label">FACULTIES</div>
            <div class="stat-value" id="dashFaculties">3</div>
        </div>
    </div>

    <!-- Card 5: Allocations -->
    <div class="stat-card p-3">
        <div class="stat-card-top-accent accent-red"></div>
        <div class="d-flex align-items-center justify-content-between w-100 mb-2">
            <div class="stat-icon-wrapper stat-icon-red">
                <i class="fa-solid fa-diagram-project"></i>
            </div>
            <span class="stat-trend-badge text-warning"><i class="fa-solid fa-circle-nodes me-1"></i>Assigned</span>
        </div>
        <div>
            <div class="stat-label">ALLOCATIONS</div>
            <div class="stat-value" id="dashAllocations">3</div>
        </div>
    </div>
</div>

<!-- Side-by-Side Analytics Charts Grid -->
<div class="row g-3 mb-4">
    <!-- Department Subject Allocations Trend Line Chart -->
    <div class="col-12 col-xl-7">
        <div class="data-card h-100 mb-0">
            <div class="data-card-header mb-3">
                <h4 class="data-card-title"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Department Subject Allocations Trend</h4>
            </div>
            <div style="height: 270px; position: relative;">
                <canvas id="deptAllocationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Academic Session Share Doughnut Chart -->
    <div class="col-12 col-xl-5">
        <div class="data-card h-100 mb-0">
            <div class="data-card-header mb-3">
                <h4 class="data-card-title"><i class="fa-solid fa-chart-pie me-2 text-success"></i>Academic Session Share</h4>
            </div>
            <div style="height: 270px; position: relative;" class="d-flex align-items-center justify-content-center">
                <canvas id="sessionShareChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Allocations Data Card -->
<div class="data-card">
    <div class="data-card-header">
        <h3 class="data-card-title"><i class="fa-solid fa-list-check me-2 text-primary"></i>Recent Subject Allocations</h3>
        <a href="assigned_subjects.php" class="btn btn-outline-secondary btn-sm rounded-pill text-white">View All Allocations</a>
    </div>

    <div class="table-responsive">
        <table id="dashboardTable" class="table align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Faculty Name</th>
                    <th>Subject</th>
                    <th>Course & Sem</th>
                    <th>Division</th>
                    <th>Session</th>
                    <th>Academic Year</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- AJAX Populated -->
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Load Dynamic Counts for KPI Cards
    $.ajax({
        url: 'actions/allocation_actions.php?action=get_options',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                if (res.departments) $('#dashDepts').text(res.departments.length);
                if (res.courses) $('#dashCourses').text(res.courses.length);
                if (res.subjects) $('#dashSubjects').text(res.subjects.length);
                if (res.faculties) $('#dashFaculties').text(res.faculties.length);
            }
        }
    });

    // Render Line Chart for Department Subject Allocations Trend
    const ctxLine = document.getElementById('deptAllocationChart').getContext('2d');
    const gradient = ctxLine.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(79, 124, 255, 0.45)');
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0.02)');

    window.dashLineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Allocated Subjects Trend',
                data: [],
                backgroundColor: gradient,
                borderColor: '#4F7CFF',
                borderWidth: 3.5,
                fill: true,
                tension: 0.42,
                pointRadius: 5,
                pointHoverRadius: 5,
                pointBackgroundColor: '#6366F1',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: '#4F7CFF',
                pointHoverBorderColor: '#FFFFFF',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { labels: { color: '#94A3B8', font: { weight: '500' } } }
            },
            scales: {
                x: { ticks: { color: '#94A3B8' }, grid: { color: 'rgba(255,255,255,0.04)' } },
                y: { ticks: { color: '#94A3B8' }, grid: { color: 'rgba(255,255,255,0.04)' } }
            },
            animation: false
        }
    });

    const ctxPie = document.getElementById('sessionShareChart').getContext('2d');
    window.dashPieChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: ['#4F7CFF', '#10B981', '#6366F1', '#F59E0B', '#EF4444', '#EC4899', '#8B5CF6'],
                borderColor: '#0B1730',
                borderWidth: 3,
                hoverOffset: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { position: 'bottom', labels: { color: '#94A3B8', boxWidth: 12, padding: 16 } }
            },
            animation: false
        }
    });

    let dashTable = $('#dashboardTable').DataTable({
        responsive: true,
        ordering: true,
        pageLength: 5,
        dom: "<'row mb-3'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6 d-flex justify-content-md-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        buttons: [
            { extend: 'csv', text: '<i class="fa-solid fa-file-csv me-1"></i> CSV', className: 'dt-button' },
            { extend: 'excel', text: '<i class="fa-solid fa-file-excel me-1 text-success"></i> Excel', className: 'dt-button' },
            { extend: 'print', text: '<i class="fa-solid fa-print me-1 text-info"></i> Print', className: 'dt-button' }
        ],
        ajax: {
            url: 'actions/allocation_actions.php?action=list',
            dataSrc: function(json) {
                if (json.status === 'success') {
                    $('#dashAllocations').text(json.data.length);
                    
                    if (window.dashLineChart && window.dashPieChart) {
                        let deptCounts = {};
                        let sessionCounts = {};
                        
                        json.data.forEach(a => {
                            if (a.status === 'active') {
                                // Department Trend
                                let deptName = a.department_code || 'Unknown';
                                deptCounts[deptName] = (deptCounts[deptName] || 0) + 1;
                                
                                // Session Share
                                let sessName = a.session_name || 'Unknown';
                                sessionCounts[sessName] = (sessionCounts[sessName] || 0) + 1;
                            }
                        });
                        
                        // Update Dept Line Chart
                        window.dashLineChart.data.labels = Object.keys(deptCounts);
                        window.dashLineChart.data.datasets[0].data = Object.values(deptCounts);
                        window.dashLineChart.update();
                        
                        // Update Session Pie Chart
                        window.dashPieChart.data.labels = Object.keys(sessionCounts);
                        window.dashPieChart.data.datasets[0].data = Object.values(sessionCounts);
                        window.dashPieChart.update();
                    }

                    return json.data;
                }
                return [];
            }
        },
        columns: [
            {
                data: null,
                render: function(data, type, row) {
                    return `<div class="d-flex align-items-center gap-2">
                                <div class="user-avatar-circle" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    ${row.faculty_name ? row.faculty_name.substring(0,2).toUpperCase() : 'FC'}
                                </div>
                                <div>
                                    <div class="fw-semibold text-white">${row.faculty_name}</div>
                                    <small class="text-muted">${row.employee_id}</small>
                                </div>
                            </div>`;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `<span class="fw-semibold text-primary">${row.subject_name}</span> <small class="text-muted">(${row.subject_code})</small>`;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `${row.course_code} - Sem ${row.semester_number}`;
                }
            },
            {
                data: 'division_name',
                render: function(data) {
                    return `<span class="badge bg-secondary opacity-75">Div ${data}</span>`;
                }
            },
            { data: 'session_name' },
            { data: 'year_label' },
            {
                data: 'status',
                render: function(data) {
                    return data === 'active' 
                        ? '<span class="badge-status badge-status-active">Active</span>'
                        : '<span class="badge-status badge-status-inactive">Inactive</span>';
                }
            }
        ],
        language: { search: "_INPUT_", searchPlaceholder: "Search dashboard records..." }
    });
});
</script>
