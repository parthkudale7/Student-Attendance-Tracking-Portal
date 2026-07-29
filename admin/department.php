<?php
$pageTitle = "Department Management";
$activeNav = "department";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Department Management</h2>
        <p class="page-header-subtitle">Configure academic departments, codes, Head of Department (HOD) assignments, and status</p>
    </div>
    <button class="btn-primary-action" id="btnAddDepartment" data-bs-toggle="modal" data-bs-target="#departmentModal">
        <i class="fa-solid fa-plus"></i> Add Department
    </button>
</div>

<!-- Stat Cards Grid -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-blue">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Total Departments</div>
                <div class="stat-value" id="statTotalDepts">3</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-green">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Active Departments</div>
                <div class="stat-value" id="statActiveDepts">3</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-red">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Inactive Departments</div>
                <div class="stat-value" id="statInactiveDepts">0</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-purple">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Assigned HODs</div>
                <div class="stat-value" id="statTotalHods">2</div>
            </div>
        </div>
    </div>
</div>

<!-- Department Analytics Graph Card -->
<div class="data-card mb-4">
    <div class="data-card-header mb-3">
        <h4 class="data-card-title"><i class="fa-solid fa-chart-column me-2 text-primary"></i>Department Faculty & Student Capacities</h4>
    </div>
    <div style="height: 220px; position: relative;">
        <canvas id="deptCapacityChart"></canvas>
    </div>
</div>

<!-- Data Card Table -->
<div class="data-card">
    <div class="data-card-header">
        <h3 class="data-card-title">Departments Master Directory</h3>
    </div>

    <div class="table-responsive">
        <table id="departmentTable" class="table align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Department Name</th>
                    <th>Code</th>
                    <th>HOD Name</th>
                    <th>Courses</th>
                    <th>Faculty Members</th>
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

<!-- Add / Edit Department Modal -->
<div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departmentModalLabel">
                    <i class="fa-solid fa-building-columns me-2 text-primary"></i><span>Add Department</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="departmentForm" novalidate>
                <div class="modal-body">
                    <?php echo getCsrfTokenInput(); ?>
                    <input type="hidden" name="department_id" id="department_id">

                    <div class="mb-3">
                        <label for="department_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-dark" id="department_name" name="department_name" placeholder="e.g. Computer Science & Engineering" required>
                    </div>

                    <div class="mb-3">
                        <label for="department_code" class="form-label">Department Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-dark" id="department_code" name="department_code" placeholder="e.g. CSE" required>
                    </div>

                    <div class="mb-3">
                        <label for="hod_name" class="form-label">Head of Department (HOD)</label>
                        <input type="text" class="form-control form-control-dark" id="hod_name" name="hod_name" placeholder="e.g. Dr. Rahul Sharma">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select form-select-dark" id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-action btn-sm" id="btnSaveDepartment">Save Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Initialize Empty Chart
    const ctx = document.getElementById('deptCapacityChart').getContext('2d');
    window.deptChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [
                { label: 'Assigned Faculty', data: [], backgroundColor: 'rgba(59, 130, 246, 0.7)' },
                { label: 'Active Courses', data: [], backgroundColor: 'rgba(16, 185, 129, 0.7)' }
            ]
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

    let deptTable = $('#departmentTable').DataTable({
        responsive: true,
        ordering: true,
        pageLength: 10,
        dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-md-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        ajax: {
            url: 'actions/department_actions.php?action=list',
            dataSrc: function(json) {
                if (json.status === 'success') {
                    const depts = json.data;
                    const activeCount = depts.filter(d => d.status === 'active').length;
                    const inactiveCount = depts.filter(d => d.status === 'inactive').length;
                    const hodCount = depts.filter(d => d.hod_name && d.hod_name.trim() !== '').length;

                    $('#statTotalDepts').text(depts.length);
                    $('#statActiveDepts').text(activeCount);
                    $('#statInactiveDepts').text(inactiveCount);
                    $('#statTotalHods').text(hodCount);

                    // Update Chart Data dynamically
                    if (window.deptChart) {
                        const labels = depts.slice(0, 10).map(d => d.department_code); // Top 10 to avoid crowding
                        const faculties = depts.slice(0, 10).map(d => parseInt(d.total_faculties || 0));
                        const courses = depts.slice(0, 10).map(d => parseInt(d.total_courses || 0));
                        
                        window.deptChart.data.labels = labels;
                        window.deptChart.data.datasets[0].data = faculties;
                        window.deptChart.data.datasets[1].data = courses;
                        window.deptChart.update();
                    }

                    return depts;
                }
                return [];
            }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'department_name', render: d => `<span class="fw-semibold text-white">${d}</span>` },
            { data: 'department_code', render: d => `<span class="fw-semibold text-primary">${d}</span>` },
            { data: 'hod_name', render: d => d ? d : '<span class="text-muted fst-italic">Not Assigned</span>' },
            { data: 'total_courses', render: d => `<span class="fw-semibold text-info">${d ?? 0}</span>` },
            { data: 'total_faculties', render: d => `<span class="fw-semibold text-success">${d ?? 0}</span>` },
            { 
                data: 'status',
                render: d => d === 'active' ? '<span class="badge-status badge-status-active">Active</span>' : '<span class="badge-status badge-status-inactive">Inactive</span>'
            },
            {
                data: null,
                orderable: false,
                className: 'text-end',
                render: (d, t, r) => `
                    <div class="d-inline-flex gap-2">
                        <button class="btn-action-edit btn-edit" data-id="${r.department_id}" data-bs-toggle="modal" data-bs-target="#departmentModal" title="Edit Department"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn-action-delete btn-delete" data-id="${r.department_id}" title="Delete Department"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                `
            }
        ],
        language: { search: "_INPUT_", searchPlaceholder: "Search department..." }
    });

    const deptModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('departmentModal'));

    $('#btnAddDepartment').on('click', function() {
        $('#departmentForm')[0].reset();
        $('#department_id').val('');
        $('#departmentForm').removeClass('was-validated');
        $('#departmentModalLabel span').text('Add Department');
    });

    $('#departmentModal').on('hidden.bs.modal', function() {
        $('#departmentForm')[0].reset();
        $('#department_id').val('');
        $('#departmentForm').removeClass('was-validated');
        $('#departmentModalLabel span').text('Add Department');
    });

    $('#departmentForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $('#btnSaveDepartment')[0];
        setButtonLoading(btn, true, 'Saving...');

        const formData = $(form).serialize() + '&action=save';

        $.ajax({
            url: 'actions/department_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                setButtonLoading(btn, false);
                if (res.status === 'success') {
                    deptModal.hide();
                    showToast(res.message, 'success');
                    deptTable.ajax.reload(null, false);
                } else {
                    showAlert('Error', res.message, 'error');
                }
            },
            error: function() {
                setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred.', 'error');
            }
        });
    });

    $('#departmentTable').on('click', '.btn-edit', function() {
        const id = $(this).attr('data-id') || $(this).data('id');
        $.ajax({
            url: 'actions/department_actions.php',
            type: 'GET',
            data: { action: 'get', id: id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#department_id').val(d.department_id);
                    $('#department_name').val(d.department_name);
                    $('#department_code').val(d.department_code);
                    $('#hod_name').val(d.hod_name);
                    $('#status').val(d.status);
                    $('#departmentModalLabel span').text('Edit Department');
                }
            }
        });
    });

    $('#departmentTable').on('click', '.btn-delete', function() {
        const id = $(this).attr('data-id') || $(this).data('id');
        showConfirm('Delete Department?', 'This action cannot be undone.', 'Delete', 'error').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'actions/department_actions.php',
                    type: 'POST',
                    data: { action: 'delete', id: id, csrf_token: getCsrfToken() },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast(res.message, 'success');
                            deptTable.ajax.reload(null, false);
                        } else {
                            showAlert('Deletion Blocked', res.message, 'error');
                        }
                    }
                });
            }
        });
    });
});
</script>
