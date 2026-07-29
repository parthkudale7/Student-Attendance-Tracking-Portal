<?php
$pageTitle = "Subject Management";
$activeNav = "subject";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Subject Management</h2>
        <p class="page-header-subtitle">Manage subject curriculum, course mappings, credit assignments, and semester distributions</p>
    </div>
    <button class="btn-primary-action" id="btnAddSubject" data-bs-toggle="modal" data-bs-target="#subjectModal">
        <i class="fa-solid fa-plus"></i> Add Subject
    </button>
</div>

<!-- Stat Cards Grid -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-green">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Total Subjects</div>
                <div class="stat-value" id="statTotalSubjects">3</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-blue">
                <i class="fa-solid fa-award"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Total Credits</div>
                <div class="stat-value" id="statTotalCredits">11</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-purple">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Departments</div>
                <div class="stat-value" id="statDeptsCount">3</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-sky">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Courses</div>
                <div class="stat-value" id="statCoursesCount">3</div>
            </div>
        </div>
    </div>
</div>

<!-- Subject Analytics Graph Card -->
<div class="data-card mb-4">
    <div class="data-card-header mb-3">
        <h4 class="data-card-title"><i class="fa-solid fa-chart-simple me-2 text-primary"></i>Subject Credits Distribution by Semester</h4>
    </div>
    <div style="height: 220px; position: relative;">
        <canvas id="subjectCreditsChart"></canvas>
    </div>
</div>

<!-- Data Card Table -->
<div class="data-card">
    <div class="data-card-header mb-2">
        <h3 class="data-card-title">Curriculum Subjects Master List</h3>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="row g-2 w-100">
            <div class="col-12 col-md-4">
                <select class="form-select form-select-dark" id="filter_department">
                    <option value="">All Departments</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <select class="form-select form-select-dark" id="filter_course">
                    <option value="">All Courses</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <select class="form-select form-select-dark" id="filter_semester">
                    <option value="">All Semesters</option>
                    <?php for($i = 1; $i <= 8; $i++): ?>
                        <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="subjectsTable" class="table align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Subject Name</th>
                    <th>Subject Code</th>
                    <th>Department</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Credits</th>
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

<!-- Add / Edit Subject Modal -->
<div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subjectModalLabel">
                    <i class="fa-solid fa-book-bookmark me-2 text-primary"></i><span>Add Subject</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="subjectForm" novalidate>
                <div class="modal-body">
                    <?php echo getCsrfTokenInput(); ?>
                    <input type="hidden" name="subject_id" id="subject_id">

                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-dark" id="subject_name" name="subject_name" placeholder="e.g. Data Structures & Algorithms" required>
                    </div>

                    <div class="mb-3">
                        <label for="subject_code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-dark" id="subject_code" name="subject_code" placeholder="e.g. CS101" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal_department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <select class="form-select form-select-dark" id="modal_department_id" name="department_id" required>
                            <option value="">Select Department</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="modal_course_id" class="form-label">Course <span class="text-danger">*</span></label>
                        <select class="form-select form-select-dark" id="modal_course_id" name="course_id" required>
                            <option value="">Select Course</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="semester_number" class="form-label">Semester</label>
                            <select class="form-select form-select-dark" id="semester_number" name="semester_number" required>
                                <?php for($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="credits" class="form-label">Credits</label>
                            <input type="number" class="form-control form-control-dark" id="credits" name="credits" value="4" min="1" max="10">
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
                    <button type="submit" class="btn-primary-action btn-sm" id="btnSaveSubject">Save Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Initialize Empty Chart
    const ctx = document.getElementById('subjectCreditsChart').getContext('2d');
    window.subjectChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5', 'Sem 6', 'Sem 7', 'Sem 8'],
            datasets: [
                { label: 'Core Theory Subjects', data: [0, 0, 0, 0, 0, 0, 0, 0], backgroundColor: 'rgba(59, 130, 246, 0.7)' },
                { label: 'Practical Labs & Projects', data: [0, 0, 0, 0, 0, 0, 0, 0], backgroundColor: 'rgba(16, 185, 129, 0.7)' }
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

    let masterOptions = {};

    function loadOptions() {
        $.ajax({
            url: 'actions/allocation_actions.php?action=get_options',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    masterOptions = res;
                    populateFilters();
                }
            }
        });
    }

    function populateFilters() {
        let deptHtml = '<option value="">All Departments</option>';
        let deptModalHtml = '<option value="">Select Department</option>';
        masterOptions.departments.forEach(d => {
            deptHtml += `<option value="${d.department_id}">${d.department_name}</option>`;
            deptModalHtml += `<option value="${d.department_id}">${d.department_name}</option>`;
        });
        $('#filter_department').html(deptHtml);
        $('#modal_department_id').html(deptModalHtml);

        let courseHtml = '<option value="">All Courses</option>';
        let courseModalHtml = '<option value="">Select Course</option>';
        masterOptions.courses.forEach(c => {
            courseHtml += `<option value="${c.course_id}">${c.course_name}</option>`;
            courseModalHtml += `<option value="${c.course_id}">${c.course_name}</option>`;
        });
        $('#filter_course').html(courseHtml);
        $('#modal_course_id').html(courseModalHtml);
    }

    loadOptions();

    let subjectsTable = $('#subjectsTable').DataTable({
        responsive: true,
        ordering: true,
        pageLength: 10,
        dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-md-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        ajax: {
            url: 'actions/subject_actions.php?action=list',
            data: function(d) {
                d.department_id = $('#filter_department').val();
                d.course_id = $('#filter_course').val();
                d.semester_number = $('#filter_semester').val();
            },
            dataSrc: function(json) {
                if (json.status === 'success') {
                    const subs = json.data;
                    const totalCredits = subs.reduce((acc, s) => acc + parseInt(s.credits || 4), 0);
                    $('#statTotalSubjects').text(subs.length);
                    $('#statTotalCredits').text(totalCredits);

                    if (window.subjectChart) {
                        let theoryData = [0, 0, 0, 0, 0, 0, 0, 0];
                        let practicalData = [0, 0, 0, 0, 0, 0, 0, 0];
                        
                        subs.forEach(s => {
                            let sem = parseInt(s.semester_number);
                            if (sem >= 1 && sem <= 8) {
                                let type = (s.subject_type || 'theory').toLowerCase();
                                if (type === 'practical' || type === 'project') {
                                    practicalData[sem - 1]++;
                                } else {
                                    theoryData[sem - 1]++;
                                }
                            }
                        });
                        window.subjectChart.data.datasets[0].data = theoryData;
                        window.subjectChart.data.datasets[1].data = practicalData;
                        window.subjectChart.update();
                    }

                    return subs;
                }
                return [];
            }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'subject_name', render: d => `<span class="fw-semibold text-white">${d}</span>` },
            { data: 'subject_code', render: d => `<span class="fw-semibold text-primary">${d}</span>` },
            { data: 'department_name' },
            { data: 'course_code' },
            { data: 'semester_number', render: d => `<span class="badge bg-secondary opacity-75">Sem ${d}</span>` },
            { data: 'credits', render: d => `<span class="fw-semibold text-success">${d} Credits</span>` },
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
                        <button class="btn-action-edit btn-edit" data-id="${r.subject_id}" data-bs-toggle="modal" data-bs-target="#subjectModal" title="Edit Subject"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="btn-action-delete btn-delete" data-id="${r.subject_id}" title="Delete Subject"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                `
            }
        ],
        language: { search: "_INPUT_", searchPlaceholder: "Search subject..." }
    });

    $('#filter_department, #filter_course, #filter_semester').on('change', function() {
        subjectsTable.ajax.reload();
    });

    const subjectModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('subjectModal'));

    function loadOptions() {
        $.ajax({
            url: 'actions/allocation_actions.php?action=get_options',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    window.allDepts = res.departments;
                    window.allCourses = res.courses;

                    let deptHtml = '<option value="">Select Department</option>';
                    res.departments.forEach(function(d) {
                        deptHtml += `<option value="${d.department_id}">${d.department_name}</option>`;
                    });
                    $('#modal_department_id').html(deptHtml);

                    let filterDeptHtml = '<option value="">All Departments</option>';
                    res.departments.forEach(function(d) {
                        filterDeptHtml += `<option value="${d.department_id}">${d.department_name}</option>`;
                    });
                    $('#filter_department').html(filterDeptHtml);

                    let filterCourseHtml = '<option value="">All Courses</option>';
                    res.courses.forEach(function(c) {
                        filterCourseHtml += `<option value="${c.course_id}">${c.course_name}</option>`;
                    });
                    $('#filter_course').html(filterCourseHtml);
                }
            }
        });
    }

    loadOptions();

    $('#modal_department_id').on('change', function() {
        const deptId = $(this).val();
        let courseModalHtml = '<option value="">Select Course</option>';
        if (window.allCourses) {
            window.allCourses.forEach(function(c) {
                if (!deptId || c.department_id == deptId) {
                    courseModalHtml += `<option value="${c.course_id}">${c.course_name}</option>`;
                }
            });
        }
        $('#modal_course_id').html(courseModalHtml);
    });

    $('#btnAddSubject').on('click', function() {
        $('#subjectForm')[0].reset();
        $('#subject_id').val('');
        $('#subjectForm').removeClass('was-validated');
        $('#subjectModalLabel span').text('Add Subject');
        loadOptions();
    });

    $('#subjectModal').on('hidden.bs.modal', function() {
        $('#subjectForm')[0].reset();
        $('#subject_id').val('');
        $('#subjectForm').removeClass('was-validated');
        $('#subjectModalLabel span').text('Add Subject');
    });

    $('#subjectForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $('#btnSaveSubject')[0];
        setButtonLoading(btn, true, 'Saving...');

        const formData = $(form).serialize() + '&action=save';

        $.ajax({
            url: 'actions/subject_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                setButtonLoading(btn, false);
                if (res.status === 'success') {
                    subjectModal.hide();
                    showToast(res.message, 'success');
                    subjectsTable.ajax.reload(null, false);
                } else {
                    showAlert('Error', res.message, 'error');
                }
            },
            error: function() {
                setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred while saving subject.', 'error');
            }
        });
    });

    $('#subjectsTable').on('click', '.btn-edit', function() {
        const id = $(this).attr('data-id') || $(this).data('id');
        $.ajax({
            url: 'actions/subject_actions.php',
            type: 'GET',
            data: { action: 'get', id: id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#subject_id').val(d.subject_id);
                    $('#subject_name').val(d.subject_name);
                    $('#subject_code').val(d.subject_code);
                    $('#modal_department_id').val(d.department_id);
                    $('#modal_course_id').val(d.course_id);
                    $('#semester_number').val(d.semester_number);
                    $('#credits').val(d.credits);
                    $('#status').val(d.status);
                    $('#subjectModalLabel span').text('Edit Subject');
                }
            }
        });
    });

    $('#subjectsTable').on('click', '.btn-delete', function() {
        const id = $(this).attr('data-id') || $(this).data('id');
        showConfirm('Delete Subject?', 'This action cannot be undone.', 'Delete', 'error').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'actions/subject_actions.php',
                    type: 'POST',
                    data: { action: 'delete', id: id, csrf_token: getCsrfToken() },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast(res.message, 'success');
                            subjectsTable.ajax.reload(null, false);
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
