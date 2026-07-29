<?php
$pageTitle = "Subject Allocation";
$activeNav = "subject_allocation";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Subject Allocation Management</h2>
        <p class="page-header-subtitle">Assign subjects to faculty members across departments, courses, semesters, divisions, and sessions</p>
    </div>
    <button class="btn-primary-action" id="btnNewAllocation" data-bs-toggle="modal" data-bs-target="#allocationModal">
        <i class="fa-solid fa-diagram-project"></i> Allocate Subject
    </button>
</div>

<!-- Allocation Analytics Card Grid -->
<div class="row g-3 mb-4">
    <div class="col-12 col-lg-8">
        <div class="data-card h-100">
            <div class="data-card-header mb-3">
                <h4 class="data-card-title"><i class="fa-solid fa-chart-line text-primary me-2"></i>Subject Allocation Trend by Semester</h4>
            </div>
            <div style="height: 220px; position: relative;">
                <canvas id="allocationTrendChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="data-card h-100">
            <div class="data-card-header mb-3">
                <h4 class="data-card-title"><i class="fa-solid fa-chart-pie text-success me-2"></i>Division Split</h4>
            </div>
            <div style="height: 220px; position: relative;" class="d-flex align-items-center justify-content-center">
                <canvas id="divisionSplitChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Data Card Table -->
<div class="data-card">
    <div class="data-card-header mb-2">
        <h3 class="data-card-title">Subject Allocations Master Table</h3>
    </div>

    <!-- Filters Bar -->
    <div class="filter-bar">
        <div class="row g-2 w-100">
            <div class="col-12 col-md-4">
                <select class="form-select form-select-dark" id="filter_alloc_dept">
                    <option value="">All Departments</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <select class="form-select form-select-dark" id="filter_alloc_faculty">
                    <option value="">All Faculty Members</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <select class="form-select form-select-dark" id="filter_alloc_ay">
                    <option value="">All Academic Years</option>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="allocationTable" class="table align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Faculty Member</th>
                    <th>Subject Allocated</th>
                    <th>Course & Semester</th>
                    <th>Division</th>
                    <th>Session</th>
                    <th>Academic Year</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- AJAX Populated -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add / Edit Subject Allocation Modal -->
<div class="modal fade" id="allocationModal" tabindex="-1" aria-labelledby="allocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="allocationModalLabel">
                    <i class="fa-solid fa-diagram-project me-2 text-primary"></i><span>Allocate Subject to Faculty</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="allocationForm" novalidate>
                <div class="modal-body">
                    <?php echo getCsrfTokenInput(); ?>
                    <input type="hidden" name="allocation_id" id="allocation_id">

                    <div class="row g-3">
                        <!-- Cascading Dropdowns -->
                        <div class="col-md-6">
                            <label for="modal_faculty_id" class="form-label">Faculty Member <span class="text-danger">*</span></label>
                            <select class="form-select form-select-dark" id="modal_faculty_id" name="faculty_id" required>
                                <option value="">Select Faculty</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="modal_dept_id" class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select form-select-dark" id="modal_dept_id" name="department_id" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="modal_course_id" class="form-label">Course <span class="text-danger">*</span></label>
                            <select class="form-select form-select-dark" id="modal_course_id" name="course_id" required>
                                <option value="">Select Course</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="modal_subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                            <select class="form-select form-select-dark" id="modal_subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="modal_semester_number" class="form-label">Semester <span class="text-danger">*</span></label>
                            <select class="form-select form-select-dark" id="modal_semester_number" name="semester_number" required>
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                                <option value="3">Semester 3</option>
                                <option value="4">Semester 4</option>
                                <option value="5">Semester 5</option>
                                <option value="6">Semester 6</option>
                                <option value="7">Semester 7</option>
                                <option value="8">Semester 8</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="modal_division_id" class="form-label">Division <span class="text-danger">*</span></label>
                            <select class="form-select form-select-dark" id="modal_division_id" name="division_id" required>
                                <option value="">Select Division</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="modal_session_id" class="form-label">Session <span class="text-danger">*</span></label>
                            <select class="form-select form-select-dark" id="modal_session_id" name="session_id" required>
                                <option value="">Select Session</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="modal_academic_year_id" class="form-label">Academic Year <span class="text-danger">*</span></label>
                            <select class="form-select form-select-dark" id="modal_academic_year_id" name="academic_year_id" required>
                                <option value="">Select Academic Year</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="modal_status" class="form-label">Allocation Status</label>
                            <select class="form-select form-select-dark" id="modal_status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-action btn-sm" id="btnSaveAllocation">Save Allocation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Render Charts
    const ctxTrend = document.getElementById('allocationTrendChart').getContext('2d');
    window.allocTrendChart = new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5', 'Sem 6', 'Sem 7', 'Sem 8'],
            datasets: [{
                label: 'Active Allocations',
                data: [0, 0, 0, 0, 0, 0, 0, 0],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.15)',
                fill: true,
                tension: 0.35,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#94a3b8' } } },
            scales: {
                x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } }
            }
        }
    });

    const ctxDiv = document.getElementById('divisionSplitChart').getContext('2d');
    window.allocDivChart = new Chart(ctxDiv, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#ec4899', '#06b6d4'],
                borderColor: '#111728',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8', boxWidth: 10 } } }
        }
    });

    let masterOptions = {};

    function loadOptions() {
        $.ajax({
            url: 'actions/allocation_actions.php?action=get_options',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    masterOptions = res;
                    populateDropdowns();
                }
            }
        });
    }

    function populateDropdowns() {
        let deptFilter = '<option value="">All Departments</option>';
        let deptModal = '<option value="">Select Department</option>';
        masterOptions.departments.forEach(d => {
            deptFilter += `<option value="${d.department_id}">${d.department_name}</option>`;
            deptModal += `<option value="${d.department_id}">${d.department_name}</option>`;
        });
        $('#filter_alloc_dept').html(deptFilter);
        $('#modal_dept_id').html(deptModal);

        let facFilter = '<option value="">All Faculty Members</option>';
        let facModal = '<option value="">Select Faculty</option>';
        masterOptions.faculties.forEach(f => {
            facFilter += `<option value="${f.faculty_id}">${f.full_name} (${f.employee_id})</option>`;
            facModal += `<option value="${f.faculty_id}">${f.full_name} (${f.employee_id})</option>`;
        });
        $('#filter_alloc_faculty').html(facFilter);
        $('#modal_faculty_id').html(facModal);

        let ayFilter = '<option value="">All Academic Years</option>';
        let ayModal = '<option value="">Select Academic Year</option>';
        masterOptions.academic_years.forEach(ay => {
            const isCurr = ay.is_current ? ' (Current)' : '';
            ayFilter += `<option value="${ay.academic_year_id}">${ay.year_label}${isCurr}</option>`;
            ayModal += `<option value="${ay.academic_year_id}" ${ay.is_current ? 'selected' : ''}>${ay.year_label}${isCurr}</option>`;
        });
        $('#filter_alloc_ay').html(ayFilter);
        $('#modal_academic_year_id').html(ayModal);

        let divModal = '<option value="">Select Division</option>';
        masterOptions.divisions.forEach(dv => {
            divModal += `<option value="${dv.division_id}">Division ${dv.division_name}</option>`;
        });
        $('#modal_division_id').html(divModal);

        let sessModal = '<option value="">Select Session</option>';
        masterOptions.sessions.forEach(s => {
            sessModal += `<option value="${s.session_id}">${s.session_name}</option>`;
        });
        $('#modal_session_id').html(sessModal);

        populateCourseAndSubject();
    }

    function populateCourseAndSubject() {
        const selectedDept = $('#modal_dept_id').val();
        let courseHtml = '<option value="">Select Course</option>';
        if (masterOptions.courses) {
            masterOptions.courses.forEach(c => {
                if (!selectedDept || c.department_id == selectedDept) {
                    courseHtml += `<option value="${c.course_id}">${c.course_name} (${c.course_code})</option>`;
                }
            });
        }
        $('#modal_course_id').html(courseHtml);

        let subjectHtml = '<option value="">Select Subject</option>';
        if (masterOptions.subjects) {
            masterOptions.subjects.forEach(s => {
                if (!selectedDept || s.department_id == selectedDept) {
                    subjectHtml += `<option value="${s.subject_id}">${s.subject_name} (${s.subject_code})</option>`;
                }
            });
        }
        $('#modal_subject_id').html(subjectHtml);
    }

    $('#modal_dept_id').on('change', populateCourseAndSubject);

    loadOptions();

    let allocationTable = $('#allocationTable').DataTable({
        responsive: true,
        ordering: true,
        pageLength: 10,
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
            data: function(d) {
                d.department_id = $('#filter_alloc_dept').val();
                d.faculty_id = $('#filter_alloc_faculty').val();
                d.academic_year_id = $('#filter_alloc_ay').val();
            },
            dataSrc: function(json) {
                if (json.status === 'success') {
                    const allocs = json.data;
                    
                    if (window.allocTrendChart && window.allocDivChart) {
                        let semData = [0, 0, 0, 0, 0, 0, 0, 0];
                        let divCounts = {};
                        
                        allocs.forEach(a => {
                            if (a.status === 'active') {
                                // Semester Trend
                                let sem = parseInt(a.semester_number);
                                if (sem >= 1 && sem <= 8) {
                                    semData[sem - 1]++;
                                }
                                
                                // Division Split
                                let divName = 'Division ' + (a.division_name || 'Unknown');
                                divCounts[divName] = (divCounts[divName] || 0) + 1;
                            }
                        });
                        
                        // Update Trend Chart
                        window.allocTrendChart.data.datasets[0].data = semData;
                        window.allocTrendChart.update();
                        
                        // Update Division Chart
                        window.allocDivChart.data.labels = Object.keys(divCounts);
                        window.allocDivChart.data.datasets[0].data = Object.values(divCounts);
                        window.allocDivChart.update();
                    }

                    return allocs;
                }
                return [];
            }
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row, meta) { return meta.row + 1; }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `<div class="fw-semibold text-white">${row.faculty_name}</div>
                            <small class="text-muted">${row.employee_id}</small>`;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `<span class="fw-semibold text-primary">${row.subject_name}</span>
                            <small class="text-muted d-block">Code: ${row.subject_code} (${row.credits} Credits)</small>`;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `<div>${row.course_code}</div>
                            <small class="text-info">Semester ${row.semester_number}</small>`;
                }
            },
            {
                data: 'division_name',
                render: function(data) {
                    return `<span class="badge bg-secondary opacity-75">Div ${data}</span>`;
                }
            },
            { data: 'session_name' },
            {
                data: 'year_label',
                render: function(data) {
                    return `<span class="badge bg-primary opacity-75">${data}</span>`;
                }
            },
            {
                data: 'status',
                render: function(data) {
                    return data === 'active' 
                        ? '<span class="badge-status badge-status-active">Active</span>'
                        : '<span class="badge-status badge-status-inactive">Inactive</span>';
                }
            },
            {
                data: null,
                orderable: false,
                className: 'text-end',
                render: function(data, type, row) {
                    return `
                        <div class="d-inline-flex gap-2">
                            <button class="btn-action-edit btn-edit" data-id="${row.allocation_id}" data-bs-toggle="modal" data-bs-target="#allocationModal" title="Edit Allocation">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn-action-delete btn-delete" data-id="${row.allocation_id}" title="Delete Allocation">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: { search: "_INPUT_", searchPlaceholder: "Search allocation..." }
    });

    $('#filter_alloc_dept, #filter_alloc_faculty, #filter_alloc_ay').on('change', function() {
        allocationTable.ajax.reload();
    });

    const allocModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('allocationModal'));

    $('#btnNewAllocation').on('click', function() {
        $('#allocationForm')[0].reset();
        $('#allocation_id').val('');
        $('#allocationForm').removeClass('was-validated');
        $('#allocationModalLabel span').text('Allocate Subject to Faculty');
        loadOptions();
    });

    $('#allocationModal').on('hidden.bs.modal', function() {
        $('#allocationForm')[0].reset();
        $('#allocation_id').val('');
        $('#allocationForm').removeClass('was-validated');
        $('#allocationModalLabel span').text('Allocate Subject to Faculty');
    });

    $('#allocationForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $('#btnSaveAllocation')[0];
        setButtonLoading(btn, true, 'Saving...');

        const formData = $(form).serialize() + '&action=save';

        $.ajax({
            url: 'actions/allocation_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                setButtonLoading(btn, false);
                if (res.status === 'success') {
                    allocModal.hide();
                    showToast(res.message, 'success');
                    allocationTable.ajax.reload(null, false);
                } else {
                    showAlert('Allocation Failed', res.message, 'error');
                }
            },
            error: function() {
                setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred.', 'error');
            }
        });
    });

    $('#allocationTable').on('click', '.btn-delete', function() {
        const id = $(this).attr('data-id') || $(this).data('id');
        showConfirm('Delete Subject Allocation?', 'This action will remove the assigned subject from the faculty member.', 'Delete', 'error').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'actions/allocation_actions.php',
                    type: 'POST',
                    data: { action: 'delete', id: id, csrf_token: getCsrfToken() },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast(res.message, 'success');
                            allocationTable.ajax.reload(null, false);
                        } else {
                            showAlert('Error', res.message, 'error');
                        }
                    }
                });
            }
        });
    });
});
</script>
