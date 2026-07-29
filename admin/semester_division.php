<?php
$pageTitle = "Session & Division Management";
$activeNav = "semester_division";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Session & Division Management</h2>
        <p class="page-header-subtitle">Configure academic sessions, semester mappings, and division capacities</p>
    </div>
    <button class="btn-primary-action" id="btnAddSemDiv">
        <i class="fa-solid fa-plus"></i> <span id="btnAddText">Add Semester</span>
    </button>
</div>

<!-- Tab Navigation -->
<ul class="nav nav-tabs-dark mb-4" id="semDivTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="semesters-tab" data-bs-toggle="tab" data-bs-target="#semesters-pane" type="button" role="tab"><i class="fa-solid fa-layer-group me-2"></i>Semesters</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="divisions-tab" data-bs-toggle="tab" data-bs-target="#divisions-pane" type="button" role="tab"><i class="fa-solid fa-shapes me-2"></i>Divisions</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="sessions-tab" data-bs-toggle="tab" data-bs-target="#sessions-pane" type="button" role="tab"><i class="fa-solid fa-clock me-2"></i>Academic Sessions</button>
    </li>
</ul>

<!-- Tab Contents -->
<div class="tab-content" id="semDivTabContent">
    <!-- Semesters Tab -->
    <div class="tab-pane fade show active" id="semesters-pane" role="tabpanel">
        <div class="data-card">
            <div class="data-card-header">
                <h3 class="data-card-title">Semester Configurations</h3>
            </div>
            <div class="table-responsive">
                <table id="semestersTable" class="table align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Semester</th>
                            <th>Short Code</th>
                            <th>Degree Course</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody><!-- AJAX --></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Divisions Tab -->
    <div class="tab-pane fade" id="divisions-pane" role="tabpanel">
        <div class="data-card">
            <div class="data-card-header">
                <h3 class="data-card-title">Divisions & Class Capacity</h3>
            </div>
            <div class="table-responsive">
                <table id="divisionsTable" class="table align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Division Name</th>
                            <th>Semester</th>
                            <th>Course</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody><!-- AJAX --></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sessions Tab -->
    <div class="tab-pane fade" id="sessions-pane" role="tabpanel">
        <div class="data-card">
            <div class="data-card-header">
                <h3 class="data-card-title">Academic Session Schedules</h3>
            </div>
            <div class="table-responsive">
                <table id="sessionsTable" class="table align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Session Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody><!-- AJAX --></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Semester Modal -->
<div class="modal fade" id="semesterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="semesterModalLabel">
                    <i class="fa-solid fa-layer-group me-2 text-primary"></i><span>Add Semester</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="semesterForm" novalidate>
                <div class="modal-body">
                    <?php echo getCsrfTokenInput(); ?>
                    <input type="hidden" name="semester_id" id="semester_id">

                    <div class="mb-3">
                        <label class="form-label">Degree Course <span class="text-danger">*</span></label>
                        <select class="form-select form-select-dark" id="sem_course_id" name="course_id" required>
                            <option value="">Select Course</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <select class="form-select form-select-dark" id="sem_academic_year_id" name="academic_year_id" required>
                            <option value="">Select Academic Year</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Semester Number <span class="text-danger">*</span></label>
                        <select class="form-select form-select-dark" id="sem_semester_number" name="semester_number" required>
                            <option value="">Select Semester</option>
                            <?php for ($i=1; $i<=12; $i++): ?>
                                <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select form-select-dark" id="sem_status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-action btn-sm" id="btnSaveSem">Save Semester</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Division Modal -->
<div class="modal fade" id="divisionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="divisionModalLabel">
                    <i class="fa-solid fa-shapes me-2 text-primary"></i><span>Add Division</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="divisionForm" novalidate>
                <div class="modal-body">
                    <?php echo getCsrfTokenInput(); ?>
                    <input type="hidden" name="division_id" id="division_id">

                    <div class="mb-3">
                        <label class="form-label">Semester <span class="text-danger">*</span></label>
                        <select class="form-select form-select-dark" id="div_semester_id" name="semester_id" required>
                            <option value="">Select Semester</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Division Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-dark" id="division_name" name="division_name" placeholder="e.g. A or B" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Capacity (Students)</label>
                        <input type="number" class="form-control form-control-dark" id="capacity" name="capacity" value="60" min="1" max="200">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select form-select-dark" id="div_status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-action btn-sm" id="btnSaveDiv">Save Division</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Session Modal -->
<div class="modal fade" id="sessionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionModalLabel">
                    <i class="fa-solid fa-clock me-2 text-primary"></i><span>Add Academic Session</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="sessionForm" novalidate>
                <div class="modal-body">
                    <?php echo getCsrfTokenInput(); ?>
                    <input type="hidden" name="session_id" id="session_id">

                    <div class="mb-3">
                        <label class="form-label">Session Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-dark" id="session_name" name="session_name" placeholder="e.g. Morning Session" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" class="form-control form-control-dark" id="start_time" name="start_time" value="09:00">
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Time</label>
                            <input type="time" class="form-control form-control-dark" id="end_time" name="end_time" value="13:00">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select form-select-dark" id="sess_status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-action btn-sm" id="btnSaveSess">Save Session</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    let currentTab = 'semesters';

    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        if (e.target.id === 'divisions-tab') {
            currentTab = 'divisions';
            $('#btnAddText').text('Add Division');
            divisionsTable.ajax.reload();
        } else if (e.target.id === 'sessions-tab') {
            currentTab = 'sessions';
            $('#btnAddText').text('Add Session');
            sessionsTable.ajax.reload();
        } else {
            currentTab = 'semesters';
            $('#btnAddText').text('Add Semester');
            semestersTable.ajax.reload();
        }
    });

    function loadOptions() {
        $.ajax({
            url: 'actions/semester_division_actions.php?action=get_options',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let courseHtml = '<option value="">Select Course</option>';
                    res.courses.forEach(c => courseHtml += `<option value="${c.course_id}">${c.course_name} (${c.course_code})</option>`);
                    $('#sem_course_id').html(courseHtml);

                    let ayHtml = '<option value="">Select Academic Year</option>';
                    res.academic_years.forEach(ay => ayHtml += `<option value="${ay.academic_year_id}">${ay.year_label}${ay.is_current ? ' (Current)' : ''}</option>`);
                    $('#sem_academic_year_id').html(ayHtml);

                    let semHtml = '<option value="">Select Semester</option>';
                    res.semesters.forEach(s => semHtml += `<option value="${s.semester_id}">Semester ${s.semester_number} (${s.course_code})</option>`);
                    $('#div_semester_id').html(semHtml);
                }
            }
        });
    }
    loadOptions();

    let semestersTable = $('#semestersTable').DataTable({
        responsive: true,
        ordering: true,
        pageLength: 10,
        dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-md-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        ajax: {
            url: 'actions/semester_division_actions.php?action=list_semesters',
            dataSrc: function(json) { return json.status === 'success' ? json.data : []; }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'semester_number', render: d => `<span class="fw-semibold text-white">Semester ${d}</span>` },
            { data: 'semester_number', render: d => `<span class="badge bg-primary opacity-75">Sem-${d}</span>` },
            { data: 'course_code', render: d => `<span class="fw-semibold text-info">${d}</span>` },
            { 
                data: 'status',
                render: d => d === 'active' ? '<span class="badge-status badge-status-active">Active</span>' : '<span class="badge-status badge-status-inactive">Inactive</span>'
            },
            { 
                data: null,
                orderable: false,
                className: 'text-end',
                render: (d, t, r) => `<div class="d-inline-flex gap-2">
                    <button class="btn-action-edit btn-edit-sem" data-id="${r.semester_id}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="btn-action-delete btn-delete-sem" data-id="${r.semester_id}" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                </div>`
            }
        ],
        language: { search: "_INPUT_", searchPlaceholder: "Search semester..." }
    });

    let divisionsTable = $('#divisionsTable').DataTable({
        responsive: true,
        ordering: true,
        pageLength: 10,
        dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-md-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        ajax: {
            url: 'actions/semester_division_actions.php?action=list_divisions',
            dataSrc: function(json) { return json.status === 'success' ? json.data : []; }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'division_name', render: d => `<span class="fw-semibold text-white">Division ${d}</span>` },
            { data: 'semester_number', render: d => `<span class="badge bg-secondary opacity-75">Sem ${d}</span>` },
            { data: 'course_code' },
            { data: 'capacity', render: d => `${d} Students` },
            { 
                data: 'status',
                render: d => d === 'active' ? '<span class="badge-status badge-status-active">Active</span>' : '<span class="badge-status badge-status-inactive">Inactive</span>'
            },
            { 
                data: null,
                orderable: false,
                className: 'text-end',
                render: (d, t, r) => `<div class="d-inline-flex gap-2">
                    <button class="btn-action-edit btn-edit-div" data-id="${r.division_id}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="btn-action-delete btn-delete-div" data-id="${r.division_id}" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                </div>`
            }
        ],
        language: { search: "_INPUT_", searchPlaceholder: "Search division..." }
    });

    let sessionsTable = $('#sessionsTable').DataTable({
        responsive: true,
        ordering: true,
        pageLength: 10,
        dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-md-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        ajax: {
            url: 'actions/semester_division_actions.php?action=list_sessions',
            dataSrc: function(json) { return json.status === 'success' ? json.data : []; }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'session_name', render: d => `<span class="fw-semibold text-white">${d}</span>` },
            { data: 'start_time', render: d => d ? d : '09:00 AM' },
            { data: 'end_time', render: d => d ? d : '01:00 PM' },
            { 
                data: 'status',
                render: d => d === 'active' ? '<span class="badge-status badge-status-active">Active</span>' : '<span class="badge-status badge-status-inactive">Inactive</span>'
            },
            { 
                data: null,
                orderable: false,
                className: 'text-end',
                render: (d, t, r) => `<div class="d-inline-flex gap-2">
                    <button class="btn-action-edit btn-edit-sess" data-id="${r.session_id}" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="btn-action-delete btn-delete-sess" data-id="${r.session_id}" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                </div>`
            }
        ],
        language: { search: "_INPUT_", searchPlaceholder: "Search session..." }
    });

    const semModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('semesterModal'));
    const divModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('divisionModal'));
    const sessModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('sessionModal'));

    $('#btnAddSemDiv').on('click', function() {
        loadOptions();
        if (currentTab === 'semesters') {
            $('#semesterForm')[0].reset();
            $('#semester_id').val('');
            $('#semesterForm').removeClass('was-validated');
            $('#semesterModalLabel span').text('Add Semester');
            semModal.show();
        } else if (currentTab === 'divisions') {
            $('#divisionForm')[0].reset();
            $('#division_id').val('');
            $('#divisionForm').removeClass('was-validated');
            $('#divisionModalLabel span').text('Add Division');
            divModal.show();
        } else {
            $('#sessionForm')[0].reset();
            $('#session_id').val('');
            $('#sessionForm').removeClass('was-validated');
            $('#sessionModalLabel span').text('Add Academic Session');
            sessModal.show();
        }
    });

    $('#semesterModal').on('hidden.bs.modal', function() {
        $('#semesterForm')[0].reset();
        $('#semester_id').val('');
        $('#semesterForm').removeClass('was-validated');
        $('#semesterModalLabel span').text('Add Semester');
    });

    $('#divisionModal').on('hidden.bs.modal', function() {
        $('#divisionForm')[0].reset();
        $('#division_id').val('');
        $('#divisionForm').removeClass('was-validated');
        $('#divisionModalLabel span').text('Add Division');
    });

    $('#sessionModal').on('hidden.bs.modal', function() {
        $('#sessionForm')[0].reset();
        $('#session_id').val('');
        $('#sessionForm').removeClass('was-validated');
        $('#sessionModalLabel span').text('Add Academic Session');
    });

    $('#semesterForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $('#btnSaveSem')[0];
        setButtonLoading(btn, true, 'Saving...');

        const formData = $(form).serialize() + '&action=save_semester';
        $.ajax({
            url: 'actions/semester_division_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                setButtonLoading(btn, false);
                if (res.status === 'success') {
                    semModal.hide();
                    showToast(res.message, 'success');
                    semestersTable.ajax.reload(null, false);
                    loadOptions();
                } else {
                    showAlert('Error', res.message, 'error');
                }
            },
            error: function() {
                setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred while saving semester.', 'error');
            }
        });
    });

    $('#divisionForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $('#btnSaveDiv')[0];
        setButtonLoading(btn, true, 'Saving...');

        const formData = $(form).serialize() + '&action=save_division';
        $.ajax({
            url: 'actions/semester_division_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                setButtonLoading(btn, false);
                if (res.status === 'success') {
                    divModal.hide();
                    showToast(res.message, 'success');
                    divisionsTable.ajax.reload(null, false);
                    loadOptions();
                } else {
                    showAlert('Error', res.message, 'error');
                }
            },
            error: function() {
                setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred while saving division.', 'error');
            }
        });
    });

    $('#sessionForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $('#btnSaveSess')[0];
        setButtonLoading(btn, true, 'Saving...');

        const formData = $(form).serialize() + '&action=save_session';
        $.ajax({
            url: 'actions/semester_division_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                setButtonLoading(btn, false);
                if (res.status === 'success') {
                    sessModal.hide();
                    showToast(res.message, 'success');
                    sessionsTable.ajax.reload(null, false);
                    loadOptions();
                } else {
                    showAlert('Error', res.message, 'error');
                }
            },
            error: function() {
                setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred while saving session.', 'error');
            }
        });
    });

    // Edit Handlers
    $('#semestersTable').on('click', '.btn-edit-sem', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'actions/semester_division_actions.php',
            type: 'GET',
            data: { action: 'get_semester', id: id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#semester_id').val(d.semester_id);
                    $('#sem_course_id').val(d.course_id);
                    $('#sem_academic_year_id').val(d.academic_year_id);
                    $('#sem_semester_number').val(d.semester_number);
                    $('#sem_status').val(d.status);
                    $('#semesterModalLabel span').text('Edit Semester');
                    semModal.show();
                }
            }
        });
    });

    $('#divisionsTable').on('click', '.btn-edit-div', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'actions/semester_division_actions.php',
            type: 'GET',
            data: { action: 'get_division', id: id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#division_id').val(d.division_id);
                    $('#div_semester_id').val(d.semester_id);
                    $('#division_name').val(d.division_name);
                    $('#capacity').val(d.capacity);
                    $('#div_status').val(d.status);
                    $('#divisionModalLabel span').text('Edit Division');
                    divModal.show();
                }
            }
        });
    });

    $('#sessionsTable').on('click', '.btn-edit-sess', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'actions/semester_division_actions.php',
            type: 'GET',
            data: { action: 'get_session', id: id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#session_id').val(d.session_id);
                    $('#session_name').val(d.session_name);
                    $('#start_time').val(d.start_time);
                    $('#end_time').val(d.end_time);
                    $('#sess_status').val(d.status);
                    $('#sessionModalLabel span').text('Edit Academic Session');
                    sessModal.show();
                }
            }
        });
    });

    // Delete Handlers
    $('#semestersTable').on('click', '.btn-delete-sem', function() {
        const id = $(this).data('id');
        showConfirm('Delete Semester?', 'This will delete the semester mapping.', 'Delete', 'error').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'actions/semester_division_actions.php',
                    type: 'POST',
                    data: { action: 'delete_semester', id: id, csrf_token: getCsrfToken() },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast(res.message, 'success');
                            semestersTable.ajax.reload(null, false);
                            loadOptions();
                        } else {
                            showAlert('Error', res.message, 'error');
                        }
                    }
                });
            }
        });
    });

    $('#divisionsTable').on('click', '.btn-delete-div', function() {
        const id = $(this).data('id');
        showConfirm('Delete Division?', 'This will delete the division.', 'Delete', 'error').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'actions/semester_division_actions.php',
                    type: 'POST',
                    data: { action: 'delete_division', id: id, csrf_token: getCsrfToken() },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast(res.message, 'success');
                            divisionsTable.ajax.reload(null, false);
                            loadOptions();
                        } else {
                            showAlert('Error', res.message, 'error');
                        }
                    }
                });
            }
        });
    });

    $('#sessionsTable').on('click', '.btn-delete-sess', function() {
        const id = $(this).data('id');
        showConfirm('Delete Academic Session?', 'This will delete the session.', 'Delete', 'error').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'actions/semester_division_actions.php',
                    type: 'POST',
                    data: { action: 'delete_session', id: id, csrf_token: getCsrfToken() },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast(res.message, 'success');
                            sessionsTable.ajax.reload(null, false);
                            loadOptions();
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
