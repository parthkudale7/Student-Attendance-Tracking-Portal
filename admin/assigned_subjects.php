<?php
$pageTitle = "Assigned Subjects";
$activeNav = "assigned_subjects";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Assigned Subjects Overview</h2>
        <p class="page-header-subtitle">Comprehensive directory of allocated subjects, faculty assignments, sessions, and academic divisions</p>
    </div>
    <a href="subject_allocation.php" class="btn-primary-action">
        <i class="fa-solid fa-plus"></i> New Subject Allocation
    </a>
</div>

<!-- Data Card -->
<div class="data-card">
    <div class="data-card-header mb-2">
        <h3 class="data-card-title"><i class="fa-solid fa-list-check me-2 text-primary"></i>Assigned Subjects Master Directory</h3>
    </div>

    <!-- Filters Bar -->
    <div class="filter-bar">
        <div class="row g-2 w-100">
            <div class="col-12 col-md-3">
                <select class="form-select form-select-dark" id="filter_as_dept">
                    <option value="">All Departments</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select form-select-dark" id="filter_as_faculty">
                    <option value="">All Faculty Members</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select form-select-dark" id="filter_as_ay">
                    <option value="">All Academic Years</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select class="form-select form-select-dark" id="filter_as_session">
                    <option value="">All Sessions</option>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="assignedSubjectsTable" class="table align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Department</th>
                    <th>Subject Name</th>
                    <th>Faculty Assigned</th>
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
    function loadOptions() {
        $.ajax({
            url: 'actions/allocation_actions.php?action=get_options',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let deptHtml = '<option value="">All Departments</option>';
                    res.departments.forEach(d => deptHtml += `<option value="${d.department_id}">${d.department_name}</option>`);
                    $('#filter_as_dept').html(deptHtml);

                    let facHtml = '<option value="">All Faculty Members</option>';
                    res.faculties.forEach(f => facHtml += `<option value="${f.faculty_id}">${f.full_name}</option>`);
                    $('#filter_as_faculty').html(facHtml);

                    let ayHtml = '<option value="">All Academic Years</option>';
                    res.academic_years.forEach(ay => ayHtml += `<option value="${ay.academic_year_id}">${ay.year_label}</option>`);
                    $('#filter_as_ay').html(ayHtml);

                    let sessHtml = '<option value="">All Sessions</option>';
                    res.sessions.forEach(s => sessHtml += `<option value="${s.session_id}">${s.session_name}</option>`);
                    $('#filter_as_session').html(sessHtml);
                }
            }
        });
    }
    loadOptions();

    let asTable = $('#assignedSubjectsTable').DataTable({
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
                d.department_id = $('#filter_as_dept').val();
                d.faculty_id = $('#filter_as_faculty').val();
                d.academic_year_id = $('#filter_as_ay').val();
                d.session_id = $('#filter_as_session').val();
            },
            dataSrc: function(json) { return json.status === 'success' ? json.data : []; }
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row, meta) { return meta.row + 1; }
            },
            { 
                data: 'course_code',
                render: function(data) { return `<span class="fw-semibold text-white">${data}</span>`; }
            },
            { 
                data: 'semester_number',
                render: function(data) { return `Sem ${data}`; }
            },
            { data: 'department_name' },
            { 
                data: null,
                render: function(data, type, row) {
                    return `<span class="fw-semibold text-primary">${row.subject_name}</span> <small class="text-muted">(${row.subject_code})</small>`;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `<div class="d-flex align-items-center gap-2">
                                <div class="user-avatar-circle" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                    ${row.faculty_name ? row.faculty_name.substring(0,2).toUpperCase() : 'FC'}
                                </div>
                                <span class="fw-semibold text-white">${row.faculty_name}</span>
                            </div>`;
                }
            },
            { 
                data: 'division_name',
                render: function(data) { return `<span class="badge bg-secondary opacity-75">Div ${data}</span>`; }
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
        language: { search: "_INPUT_", searchPlaceholder: "Search assigned subjects..." }
    });

    $('#filter_as_dept, #filter_as_faculty, #filter_as_ay, #filter_as_session').on('change', function() {
        asTable.ajax.reload();
    });
});
</script>
