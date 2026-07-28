<?php
$pageTitle = "Course Management";
$activeNav = "course";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Course Management</h2>
        <p class="page-header-subtitle">Manage degree programs, duration, credit distribution, and course configurations</p>
    </div>
    <button class="btn-primary-action" id="btnAddCourse" data-bs-toggle="modal" data-bs-target="#courseModal">
        <i class="fa-solid fa-plus"></i> Add Course
    </button>
</div>

<!-- Stat Cards Grid -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-blue">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Total Courses</div>
                <div class="stat-value" id="statTotalCourses">3</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-green">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Active Courses</div>
                <div class="stat-value" id="statActiveCourses">3</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-purple">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Total Semesters</div>
                <div class="stat-value" id="statTotalSemesters">18</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-sky">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Departments</div>
                <div class="stat-value" id="statDeptsCount">3</div>
            </div>
        </div>
    </div>
</div>

<!-- Course Analytics Chart Card -->
<div class="data-card mb-4">
    <div class="data-card-header mb-3">
        <h4 class="data-card-title"><i class="fa-solid fa-chart-column me-2 text-primary"></i>Course Duration & Semester Distribution</h4>
    </div>
    <div style="height: 220px; position: relative;">
        <canvas id="courseSemesterChart"></canvas>
    </div>
</div>

<!-- Data Card Table -->
<div class="data-card">
    <div class="data-card-header">
        <h3 class="data-card-title">All Degree Courses</h3>
    </div>

    <div class="table-responsive">
        <table id="courseTable" class="table align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Course Name</th>
                    <th>Code</th>
                    <th>Department</th>
                    <th>Duration (Yrs)</th>
                    <th>Semesters</th>
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

<!-- Add / Edit Course Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="courseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseModalLabel">
                    <i class="fa-solid fa-graduation-cap me-2 text-primary"></i><span>Add Course</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="courseForm" method="POST" action="javascript:void(0);" novalidate>
                <div class="modal-body">
                    <?php echo getCsrfTokenInput(); ?>
                    <input type="hidden" name="course_id" id="course_id">

                    <div class="mb-3">
                        <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-dark" id="course_name" name="course_name" placeholder="e.g. B.Tech Computer Science" required>
                    </div>

                    <div class="mb-3">
                        <label for="course_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-dark" id="course_code" name="course_code" placeholder="e.g. BTECH-CSE" required>
                    </div>

                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <select class="form-select form-select-dark" id="department_id" name="department_id" required>
                            <option value="">Loading Departments...</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="duration_years" class="form-label">Duration (Years)</label>
                            <input type="number" class="form-control form-control-dark" id="duration_years" name="duration_years" value="4" min="1" max="6">
                        </div>
                        <div class="col-6">
                            <label for="total_semesters" class="form-label">Total Semesters</label>
                            <input type="number" class="form-control form-control-dark" id="total_semesters" name="total_semesters" value="8" min="1" max="12">
                        </div>
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
                    <button type="submit" class="btn-primary-action btn-sm" id="btnSaveCourse">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    function loadDepts(selectedId) {
        $.ajax({
            url: 'actions/department_actions.php?action=list',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success' && Array.isArray(res.data)) {
                    let html = '<option value="">Select Department</option>';
                    res.data.forEach(function(d) {
                        if (d.status === 'active') {
                            html += `<option value="${d.department_id}">${d.department_name} (${d.department_code})</option>`;
                        }
                    });
                    $('#department_id').html(html);
                    $('#statDeptsCount').text(res.data.length);
                    if (selectedId) {
                        $('#department_id').val(selectedId);
                    }
                } else {
                    $('#department_id').html('<option value="">No Active Departments Available</option>');
                }
            },
            error: function() {
                $('#department_id').html('<option value="">Error Loading Departments</option>');
            }
        });
    }

    loadDepts();

    window.courseChart = null;

    let courseTable = $('#courseTable').DataTable({
        responsive: true,
        ordering: true,
        pageLength: 10,
        dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-md-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        ajax: {
            url: 'actions/course_actions.php?action=list',
            dataSrc: function(json) {
                if (json.status === 'success') {
                    const courses = json.data;
                    const activeCount = courses.filter(c => c.status === 'active').length;
                    const totalSems = courses.reduce((acc, c) => acc + parseInt(c.total_semesters || 8), 0);

                    $('#statTotalCourses').text(courses.length);
                    $('#statActiveCourses').text(activeCount);
                    $('#statTotalSemesters').text(totalSems);

                    // Update Chart Data dynamically
                    if (window.courseChart) {
                        const labels = courses.slice(0, 10).map(c => c.course_code); // Top 10 to avoid crowding
                        const durations = courses.slice(0, 10).map(c => parseInt(c.duration_years));
                        const semesters = courses.slice(0, 10).map(c => parseInt(c.total_semesters));
                        
                        window.courseChart.data.labels = labels;
                        window.courseChart.data.datasets[0].data = durations;
                        window.courseChart.data.datasets[1].data = semesters;
                        window.courseChart.update();
                    }

                    return courses;
                }
                return [];
            }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'course_name', render: d => `<span class="fw-semibold text-white">${d}</span>` },
            { data: 'course_code', render: d => `<span class="fw-semibold text-primary">${d}</span>` },
            { data: 'department_name' },
            { data: 'duration_years' },
            { data: 'total_semesters' },
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
                        <button class="btn-action-edit btn-edit" onclick="editCourse(${r.course_id})" data-id="${r.course_id}" data-bs-toggle="modal" data-bs-target="#courseModal" title="Edit Course"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn-action-delete btn-delete" onclick="deleteCourse(${r.course_id})" data-id="${r.course_id}" title="Delete Course"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                `
            }
        ],
        language: { search: "_INPUT_", searchPlaceholder: "Search course..." }
    });

    window.courseTable = courseTable;

    // Initialize Empty Chart
    const ctxCourse = document.getElementById('courseSemesterChart');
    if (ctxCourse) {
        window.courseChart = new Chart(ctxCourse, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    { 
                        label: 'Duration (Years)', 
                        data: [], 
                        backgroundColor: 'rgba(79, 124, 255, 0.8)',
                        hoverBackgroundColor: 'rgba(79, 124, 255, 1)',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    { 
                        label: 'Semesters', 
                        data: [], 
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        hoverBackgroundColor: 'rgba(16, 185, 129, 1)',
                        borderRadius: 6,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#94a3b8', font: { weight: '500' } } } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } }
                },
                animation: false
            }
        });
    }

    window.editCourse = function(id) {
        if (!id) return;
        $.ajax({
            url: 'actions/course_actions.php',
            type: 'GET',
            data: { action: 'get', id: id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#course_id').val(d.course_id);
                    $('#course_name').val(d.course_name);
                    $('#course_code').val(d.course_code);
                    loadDepts(d.department_id);
                    $('#duration_years').val(d.duration_years);
                    $('#total_semesters').val(d.total_semesters);
                    $('#status').val(d.status);
                    $('#courseModalLabel span').text('Edit Course');
                    const modalEl = document.getElementById('courseModal');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                }
            }
        });
    };

    function doDeleteCourse(id) {
        $.ajax({
            url: 'actions/course_actions.php',
            type: 'POST',
            data: { action: 'delete', id: id, csrf_token: typeof getCsrfToken === 'function' ? getCsrfToken() : '' },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    if (typeof showToast === 'function') {
                        showToast(res.message, 'success');
                    } else {
                        alert(res.message);
                    }
                    if (window.courseTable) {
                        window.courseTable.ajax.reload(null, false);
                    } else {
                        location.reload();
                    }
                } else {
                    if (typeof showAlert === 'function') {
                        showAlert('Error', res.message, 'error');
                    } else {
                        alert('Error: ' + res.message);
                    }
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while deleting the course: ' + error);
            }
        });
    }

    window.deleteCourse = function(id) {
        if (!id) return;
        if (typeof showConfirm === 'function') {
            showConfirm('Delete Course?', 'This will permanently delete the course and all linked semester records.', 'Delete', 'error').then(function(result) {
                if (result && result.isConfirmed) {
                    doDeleteCourse(id);
                }
            }).catch(function() {
                if (confirm('Delete Course?\nThis will permanently delete the course and all linked semester records.')) {
                    doDeleteCourse(id);
                }
            });
        } else {
            if (confirm('Delete Course?\nThis will permanently delete the course and all linked semester records.')) {
                doDeleteCourse(id);
            }
        }
    };

    const courseModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('courseModal'));

    $('#btnAddCourse').on('click', function() {
        $('#courseForm')[0].reset();
        $('#course_id').val('');
        $('#courseForm').removeClass('was-validated');
        $('#courseModalLabel span').text('Add Course');
        loadDepts();
    });

    $('#courseModal').on('hidden.bs.modal', function() {
        $('#courseForm')[0].reset();
        $('#course_id').val('');
        $('#courseForm').removeClass('was-validated');
        $('#courseModalLabel span').text('Add Course');
    });

    $('#courseForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $('#btnSaveCourse')[0];
        if (typeof setButtonLoading === 'function') setButtonLoading(btn, true, 'Saving...');

        const formData = $(form).serialize() + '&action=save';

        $.ajax({
            url: 'actions/course_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                if (res.status === 'success') {
                    courseModal.hide();
                    if (typeof showToast === 'function') {
                        showToast(res.message, 'success');
                    } else {
                        alert(res.message);
                    }
                    if (window.courseTable) {
                        window.courseTable.ajax.reload(null, false);
                    }
                } else {
                    if (typeof showAlert === 'function') {
                        showAlert('Error', res.message, 'error');
                    } else {
                        alert('Error: ' + res.message);
                    }
                }
            },
            error: function(xhr, status, error) {
                if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                if (typeof showAlert === 'function') {
                    showAlert('Error', 'An unexpected error occurred while saving the course.', 'error');
                } else {
                    alert('An error occurred while saving the course: ' + (res?.message || error));
                }
            }
        });
    });

    $('#courseTable').on('click', '.btn-edit', function(e) {
        e.preventDefault();
        const id = $(this).attr('data-id') || $(this).data('id');
        window.editCourse(id);
    });

    $('#courseTable').on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const id = $(this).attr('data-id') || $(this).data('id');
        window.deleteCourse(id);
    });
});
</script>
