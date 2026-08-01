<?php
$pageTitle = "Faculty Management";
$activeNav = "faculty";

if (isset($_GET['open_modal']) && $_GET['open_modal'] === 'add') {
    $activeNav = "faculty_registration";
} elseif (isset($_GET['view_first'])) {
    $activeNav = "faculty_profile";
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Faculty Management</h2>
        <p class="page-header-subtitle">Manage university faculty profiles, qualifications, department assignments, and contact records</p>
    </div>
    <a href="faculty_registration.php" class="btn-primary-action" id="btnAddFaculty">
        <i class="fa-solid fa-plus"></i> Add Faculty Member
    </a>
</div>

<!-- Stat Cards Grid -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-sky">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Total Faculty</div>
                <div class="stat-value" id="statTotalFaculty">3</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-green">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Active Members</div>
                <div class="stat-value" id="statActiveFaculty">3</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon-wrapper stat-icon-blue">
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
            <div class="stat-icon-wrapper stat-icon-purple">
                <i class="fa-solid fa-award"></i>
            </div>
            <div class="stat-details">
                <div class="stat-label">Professors / HODs</div>
                <div class="stat-value" id="statProfessorsCount">2</div>
            </div>
        </div>
    </div>
</div>

<!-- Data Card -->
<div class="data-card">
    <div class="data-card-header mb-2">
        <h3 class="data-card-title">Faculty Members Directory</h3>
    </div>

    <!-- Filters Bar -->
    <div class="filter-bar">
        <div class="row g-2 w-100">
            <div class="col-12 col-md-4">
                <select class="form-select form-select-dark" id="filter_faculty_dept">
                    <option value="">All Departments</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <select class="form-select form-select-dark" id="filter_faculty_status">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="facultyTable" class="table align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Faculty Member</th>
                    <th>Employee ID</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- AJAX Populated -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Faculty Modal -->
<div class="modal fade" id="facultyModal" tabindex="-1" aria-labelledby="facultyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="facultyModalLabel">
                    <i class="fa-solid fa-user-plus me-2 text-primary"></i><span>Add Faculty Member</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="facultyForm" enctype="multipart/form-data" novalidate>
                <div class="modal-body">
                    <?php echo getCsrfTokenInput(); ?>
                    <input type="hidden" name="faculty_id" id="faculty_id">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label">Employee ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-dark" id="employee_id" name="employee_id" placeholder="e.g. FAC-1004" required>
                        </div>

                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-dark" id="full_name" name="full_name" placeholder="e.g. Dr. Rajesh Kumar" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-dark" id="email" name="email" placeholder="e.g. rajesh.kumar@university.edu" required>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-dark" id="phone" name="phone" placeholder="e.g. +91 9876543210" required>
                        </div>

                        <div class="col-md-6">
                            <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select form-select-dark" id="department_id" name="department_id" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                            <select class="form-select form-select-dark" id="designation" name="designation" required>
                                <option value="Professor">Professor</option>
                                <option value="Associate Professor">Associate Professor</option>
                                <option value="Assistant Professor" selected>Assistant Professor</option>
                                <option value="Lecturer">Lecturer</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="qualification" class="form-label">Qualification</label>
                            <input type="text" class="form-control form-control-dark" id="qualification" name="qualification" placeholder="e.g. Ph.D in Computer Engineering">
                        </div>

                        <div class="col-md-6">
                            <label for="joining_date" class="form-label">Joining Date</label>
                            <input type="date" class="form-control form-control-dark" id="joining_date" name="joining_date">
                        </div>

                        <div class="col-md-6">
                            <label for="experience_years" class="form-label">Experience (Years)</label>
                            <input type="number" class="form-control form-control-dark" id="experience_years" name="experience_years" value="4" min="0" max="40">
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select form-select-dark" id="status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="photo" class="form-label">Profile Photo (Optional)</label>
                            <input type="file" class="form-control form-control-dark" id="photo" name="photo" accept="image/*">
                        </div>

                        <!-- Auto-Generated Login Credentials Section -->
                        <div class="col-12" id="facultyModalCredSection">
                            <div class="p-3 rounded-3" style="background: rgba(13, 27, 62, 0.6); border: 1px solid rgba(79, 124, 255, 0.25);">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="text-white fw-bold mb-0">
                                        <i class="fa-solid fa-key text-warning me-1"></i> Faculty Login Credentials
                                    </h6>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-primary small text-decoration-none" id="btnModalGenPass">
                                        <i class="fa-solid fa-rotate me-1"></i>Generate Password
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small mb-1">Login Email</label>
                                        <input type="text" class="form-control form-control-dark form-control-sm" id="modal_display_email" readonly placeholder="Auto-synced with Email Address">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="modal_password" class="form-label text-secondary small mb-1">Password</label>
                                        <div class="input-group input-group-sm">
                                            <input type="password" class="form-control form-control-dark" id="modal_password" name="password" placeholder="Faculty@123 or Employee ID">
                                            <button type="button" class="btn btn-outline-secondary border-opacity-50" id="btnModalTogglePass">
                                                <i class="fa-solid fa-eye" id="modalEyeIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-secondary small mt-2">
                                    <i class="fa-solid fa-circle-info text-info me-1"></i> Role: <span class="badge bg-primary">Faculty</span>. An account will be automatically registered in the login system.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-action btn-sm" id="btnSaveFaculty">Save Faculty & Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Faculty Profile Modal -->
<div class="modal fade" id="viewFacultyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-address-card me-2 text-primary"></i>Faculty Member Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="facultyProfileContent">
                <!-- Loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Faculty Credentials Success Modal -->
<div class="modal fade" id="facultyCredModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-white" style="background: #0B1730; border: 1px solid #4F7CFF; box-shadow: 0 10px 35px rgba(0,0,0,0.6);">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fa-solid fa-circle-check text-success me-2 fs-4"></i> Faculty Login Generated!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-secondary small mb-3">Faculty account has been saved and credentials are ready for login.</p>
                <div class="p-3 rounded-3 mb-3" style="background: rgba(8, 17, 31, 0.8); border: 1px solid rgba(79, 124, 255, 0.25);">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary border-opacity-25">
                        <span class="text-secondary small">Faculty Name:</span>
                        <span class="fw-bold text-white" id="modalCredName">-</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary border-opacity-25">
                        <span class="text-secondary small">Employee ID:</span>
                        <span class="badge bg-secondary" id="modalCredEmpId">-</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary border-opacity-25">
                        <span class="text-secondary small">Role:</span>
                        <span class="badge bg-primary">Faculty</span>
                    </div>
                    <div class="mb-3 pt-1">
                        <label class="text-secondary small mb-1 d-flex justify-content-between">
                            <span>Login Email:</span>
                            <span class="text-primary small cursor-pointer" onclick="copyModalCred('modalCredEmailVal', 'Email copied!')">
                                <i class="fa-solid fa-copy me-1"></i>Copy
                            </span>
                        </label>
                        <input type="text" class="form-control form-control-dark form-control-sm bg-dark text-info fw-medium" id="modalCredEmailVal" readonly>
                    </div>
                    <div class="mb-1">
                        <label class="text-secondary small mb-1 d-flex justify-content-between">
                            <span>Password:</span>
                            <span class="text-primary small cursor-pointer" onclick="copyModalCred('modalCredPassVal', 'Password copied!')">
                                <i class="fa-solid fa-copy me-1"></i>Copy
                            </span>
                        </label>
                        <input type="text" class="form-control form-control-dark form-control-sm bg-dark text-warning fw-medium" id="modalCredPassVal" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <a href="../auth/login.html?role=faculty" target="_blank" class="btn-primary-action btn-sm text-decoration-none" id="btnModalLoginFaculty">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Login as Faculty
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
function copyModalCred(elementId, toastMsg) {
    const el = document.getElementById(elementId);
    if (!el) return;
    el.select();
    navigator.clipboard.writeText(el.value).then(() => {
        if (typeof showToast === 'function') {
            showToast(toastMsg || 'Copied to clipboard!', 'success');
        } else {
            alert(toastMsg || 'Copied to clipboard!');
        }
    });
}

$(document).ready(function() {
    // Sync email input -> modal_display_email
    $('#email').on('input', function() {
        $('#modal_display_email').val($(this).val());
    });

    // Auto-generate password helper
    function genRandomPass() {
        const adjectives = ['Prof', 'Tech', 'Edu', 'Univ', 'Acad', 'Lead'];
        const symbols = ['@', '#', '$', '!'];
        const adj = adjectives[Math.floor(Math.random() * adjectives.length)];
        const sym = symbols[Math.floor(Math.random() * symbols.length)];
        const num = Math.floor(100 + Math.random() * 900);
        return `${adj}${sym}${num}`;
    }

    $('#btnModalGenPass').on('click', function() {
        const p = genRandomPass();
        $('#modal_password').val(p);
        showToast('Generated password: ' + p, 'success');
    });

    $('#btnModalTogglePass').on('click', function() {
        const pInput = document.getElementById('modal_password');
        const eye = document.getElementById('modalEyeIcon');
        if (pInput.type === 'password') {
            pInput.type = 'text';
            eye.classList.remove('fa-eye');
            eye.classList.add('fa-eye-slash');
        } else {
            pInput.type = 'password';
            eye.classList.remove('fa-eye-slash');
            eye.classList.add('fa-eye');
        }
    });

    function loadDepts() {
        $.ajax({
            url: 'actions/allocation_actions.php?action=get_options',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let html = '<option value="">All Departments</option>';
                    res.departments.forEach(function(d) {
                        html += `<option value="${d.department_id}">${d.department_name}</option>`;
                    });
                    $('#filter_faculty_dept').html(html);
                    $('#department_id').html(html.replace('All Departments', 'Select Department'));
                    $('#statDeptsCount').text(res.departments.length);
                }
            }
        });
    }
    loadDepts();

    let facultyTable = $('#facultyTable').DataTable({
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
            url: 'actions/faculty_actions.php?action=list',
            data: function(d) {
                d.department_id = $('#filter_faculty_dept').val();
                d.status = $('#filter_faculty_status').val();
            },
            dataSrc: function(json) {
                if (json.status === 'success') {
                    const faculties = json.data;
                    const activeCount = faculties.filter(f => f.status === 'active').length;
                    const profCount = faculties.filter(f => f.designation.includes('Professor')).length;

                    $('#statTotalFaculty').text(faculties.length);
                    $('#statActiveFaculty').text(activeCount);
                    $('#statProfessorsCount').text(profCount);

                    return json.data;
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
                    const initials = row.full_name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                    return `<div class="d-flex align-items-center gap-3">
                                <div class="user-avatar-circle" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                    ${initials}
                                </div>
                                <div>
                                    <div class="fw-semibold text-white">${row.full_name}</div>
                                    <small class="text-muted">${row.email}</small>
                                </div>
                            </div>`;
                }
            },
            { 
                data: 'employee_id',
                render: function(data) { return `<span class="fw-semibold text-primary">${data}</span>`; }
            },
            { data: 'department_name' },
            { data: 'designation' },
            { data: 'phone' },
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
                            <button class="btn-action-view btn-view" data-id="${row.faculty_id}" title="View Faculty Profile">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="btn-action-edit btn-edit" data-id="${row.faculty_id}" title="Edit Faculty">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn-action-delete btn-delete" data-id="${row.faculty_id}" title="Delete Faculty">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: { search: "_INPUT_", searchPlaceholder: "Search faculty member..." }
    });

    $('#filter_faculty_dept, #filter_faculty_status').on('change', function() {
        facultyTable.ajax.reload();
    });

    const facultyModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('facultyModal'));
    const viewModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('viewFacultyModal'));

    $('#btnAddFaculty').on('click', function() {
        $('#facultyForm')[0].reset();
        $('#faculty_id').val('');
        $('#facultyForm').removeClass('was-validated');
        $('#facultyModalLabel span').text('Add Faculty Member');
        loadDepts();
        facultyModal.show();
    });

    $('#facultyModal').on('hidden.bs.modal', function() {
        $('#facultyForm')[0].reset();
        $('#faculty_id').val('');
        $('#facultyForm').removeClass('was-validated');
        $('#facultyModalLabel span').text('Add Faculty Member');
    });

    // Check query params for auto triggering modals
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('open_modal') === 'add') {
        setTimeout(() => $('#btnAddFaculty').click(), 300);
    }
    if (urlParams.get('view_first') === '1') {
        setTimeout(function() {
            $('.btn-view').first().click();
        }, 600);
    }

    $('#facultyForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $(form).find('button[type="submit"]')[0];
        if (btn) setButtonLoading(btn, true, 'Saving...');

        const formData = new FormData(form);
        formData.append('action', 'save');

        $.ajax({
            url: 'actions/faculty_actions.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (btn) setButtonLoading(btn, false);
                if (res.status === 'success') {
                    facultyModal.hide();
                    showToast(res.message, 'success');
                    facultyTable.ajax.reload(null, false);

                    if (res.credentials && !$('#faculty_id').val()) {
                        $('#modalCredName').text(res.credentials.name);
                        $('#modalCredEmpId').text(res.credentials.employee_id);
                        $('#modalCredEmailVal').val(res.credentials.email);
                        $('#modalCredPassVal').val(res.credentials.password);
                        $('#btnModalLoginFaculty').attr('href', `../auth/login.html?role=faculty&email=${encodeURIComponent(res.credentials.email)}`);
                        
                        const credModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('facultyCredModal'));
                        credModal.show();
                    }
                } else {
                    showAlert('Error', res.message, 'error');
                }
            },
            error: function() {
                if (btn) setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred while saving faculty member.', 'error');
            }
        });
    });

    // View Faculty Profile Click
    $('#facultyTable').on('click', '.btn-view', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'actions/faculty_actions.php',
            type: 'GET',
            data: { action: 'get_profile', id: id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const f = res.data.faculty;
                    const allocs = res.data.allocations;
                    const stats = res.data.stats;
                    const initials = f.full_name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();

                    let allocRows = '';
                    if (allocs.length > 0) {
                        allocs.forEach((a, idx) => {
                            allocRows += `<tr>
                                <td>${idx + 1}</td>
                                <td class="fw-semibold text-white">${a.subject_name} (${a.subject_code})</td>
                                <td>${a.course_code} - Sem ${a.semester_number}</td>
                                <td>Div ${a.division_name}</td>
                                <td>${a.session_name}</td>
                                <td><span class="badge bg-primary opacity-75">${a.year_label}</span></td>
                            </tr>`;
                        });
                    } else {
                        allocRows = `<tr><td colspan="6" class="text-center text-muted py-3">No subjects allocated to this faculty member yet.</td></tr>`;
                    }

                    const html = `
                        <div class="profile-header-banner">
                            <div class="profile-avatar-placeholder">${initials}</div>
                            <div>
                                <h4 class="text-white mb-1">${f.full_name}</h4>
                                <div class="text-primary fw-semibold mb-1">${f.designation} - ${f.department_name}</div>
                                <div class="text-muted small"><i class="fa-solid fa-id-badge me-1"></i>${f.employee_id} | <i class="fa-solid fa-envelope me-1"></i>${f.email} | <i class="fa-solid fa-phone me-1"></i>${f.phone}</div>
                            </div>
                        </div>

                        <!-- Profile Stats -->
                        <div class="row g-3 mb-4">
                            <div class="col-4">
                                <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 text-center">
                                    <div class="text-muted small">Assigned Subjects</div>
                                    <div class="fs-4 fw-bold text-white">${stats.total_subjects}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 text-center">
                                    <div class="text-muted small">Total Credits</div>
                                    <div class="fs-4 fw-bold text-success">${stats.total_credits}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 text-center">
                                    <div class="text-muted small">Total Weekly Hours</div>
                                    <div class="fs-4 fw-bold text-info">${stats.total_classes} hrs</div>
                                </div>
                            </div>
                        </div>

                        <h6 class="text-white fw-bold mb-3"><i class="fa-solid fa-list-check me-2 text-primary"></i>Assigned Subjects List</h6>
                        <div class="table-responsive">
                            <table class="table align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>Course & Sem</th>
                                        <th>Division</th>
                                        <th>Session</th>
                                        <th>Academic Year</th>
                                    </tr>
                                </thead>
                                <tbody>${allocRows}</tbody>
                            </table>
                        </div>
                    `;

                    $('#facultyProfileContent').html(html);
                    viewModal.show();
                }
            }
        });
    });

    // Edit Faculty
    $('#facultyTable').on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'actions/faculty_actions.php',
            type: 'GET',
            data: { action: 'get', id: id },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#faculty_id').val(d.faculty_id);
                    $('#employee_id').val(d.employee_id);
                    $('#full_name').val(d.full_name);
                    $('#email').val(d.email);
                    $('#phone').val(d.phone);
                    $('#department_id').val(d.department_id);
                    $('#designation').val(d.designation);
                    $('#qualification').val(d.qualification);
                    $('#joining_date').val(d.joining_date);
                    $('#experience_years').val(d.experience_years);
                    $('#status').val(d.status);
                    $('#facultyModalLabel span').text('Edit Faculty Member');
                    facultyModal.show();
                }
            }
        });
    });

    // Delete Faculty
    $('#facultyTable').on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        showConfirm('Delete Faculty Member?', 'This action cannot be undone.', 'Delete', 'error').then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'actions/faculty_actions.php',
                    type: 'POST',
                    data: { action: 'delete', id: id, csrf_token: getCsrfToken() },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast(res.message, 'success');
                            facultyTable.ajax.reload(null, false);
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
