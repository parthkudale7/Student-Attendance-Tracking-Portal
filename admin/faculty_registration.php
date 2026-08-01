<?php
$pageTitle = "Faculty Registration";
$activeNav = "faculty_registration";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<!-- Page Header -->
<div class="page-header-container">
    <div>
        <h2 class="page-header-title">Faculty Registration</h2>
        <p class="page-header-subtitle">Register a new university faculty member and automatically generate their login credentials</p>
    </div>
    <a href="faculty.php" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Faculty Directory
    </a>
</div>

<!-- Form Card Container -->
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="data-card shadow-lg">
            <div class="data-card-header mb-4 pb-3 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="data-card-title mb-1">
                        <i class="fa-solid fa-user-plus text-primary me-2"></i>New Faculty Registration Form
                    </h3>
                    <p class="text-secondary small mb-0">Fill in the faculty information below. Login credentials will be generated automatically.</p>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnAutoFillSample">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Quick Sample
                </button>
            </div>

            <form id="regFacultyForm" enctype="multipart/form-data" novalidate>
                <?php echo getCsrfTokenInput(); ?>
                
                <div class="row g-4">
                    <!-- Personal & Employee Details -->
                    <div class="col-12">
                        <h5 class="text-white-50 small text-uppercase fw-bold letter-spacing-1 mb-0">
                            <i class="fa-solid fa-id-card me-2 text-primary"></i>1. Personal & Employee Details
                        </h5>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="employee_id" class="form-label mb-0">Employee ID <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-primary small" id="btnGenEmpId">
                                <i class="fa-solid fa-rotate me-1"></i>Suggest ID
                            </button>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary border-opacity-50 text-secondary">
                                <i class="fa-solid fa-hashtag"></i>
                            </span>
                            <input type="text" class="form-control form-control-dark" id="employee_id" name="employee_id" placeholder="e.g. FAC-1004" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary border-opacity-50 text-secondary">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" class="form-control form-control-dark" id="full_name" name="full_name" placeholder="e.g. Dr. Rajesh Kumar" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="email" class="form-label mb-0">Email Address <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-primary small" id="btnAutoEmail">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i>Auto-Generate
                            </button>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary border-opacity-50 text-secondary">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control form-control-dark" id="email" name="email" placeholder="e.g. rajesh.kumar@university.edu" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary border-opacity-50 text-secondary">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <input type="text" class="form-control form-control-dark" id="phone" name="phone" placeholder="e.g. +91 9876543210" required>
                        </div>
                    </div>

                    <!-- Academic Details -->
                    <div class="col-12 mt-4 pt-2">
                        <h5 class="text-white-50 small text-uppercase fw-bold letter-spacing-1 mb-0">
                            <i class="fa-solid fa-graduation-cap me-2 text-primary"></i>2. Academic & Department Information
                        </h5>
                    </div>

                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <select class="form-select form-select-dark" id="department_id" name="department_id" required>
                            <option value="">Loading Departments...</option>
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
                    <div class="col-12 mt-4 pt-2">
                        <div class="p-4 rounded-3" style="background: rgba(13, 27, 62, 0.5); border: 1px solid rgba(79, 124, 255, 0.3); box-shadow: 0 4px 20px rgba(0,0,0,0.25);">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <div>
                                    <h5 class="text-white fw-bold mb-1 d-flex align-items-center">
                                        <i class="fa-solid fa-key text-warning me-2"></i> Faculty Portal Login Credentials
                                        <span class="badge bg-primary ms-2 fs-xs">Auto-Generated</span>
                                    </h5>
                                    <p class="text-secondary small mb-0">These credentials will automatically be registered in the authentication system so the faculty member can log in.</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-light btn-sm" id="btnUseEmpIdPass" title="Set password as Employee ID">
                                        <i class="fa-solid fa-id-badge me-1"></i> Use Emp ID
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnGenRandomPass" title="Generate strong random password">
                                        <i class="fa-solid fa-rotate me-1"></i> Generate Password
                                    </button>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small">Login Username / Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary border-opacity-50 text-secondary">
                                            <i class="fa-solid fa-at"></i>
                                        </span>
                                        <input type="text" class="form-control form-control-dark" id="display_login_email" placeholder="Auto-synced with Email Address" readonly style="background-color: rgba(15, 23, 42, 0.6);">
                                    </div>
                                    <div class="form-text text-secondary small">Matches the primary faculty email above.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label text-secondary small">Login Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary border-opacity-50 text-secondary">
                                            <i class="fa-solid fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control form-control-dark" id="password" name="password" placeholder="e.g. Faculty@123 or FAC-1004" required>
                                        <button type="button" class="btn btn-outline-secondary border-opacity-50" id="btnTogglePassword" type="button">
                                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-info small">Default is generated from Employee ID or custom password.</div>
                                </div>
                            </div>

                            <div class="mt-3 p-2 px-3 rounded bg-primary bg-opacity-10 border border-primary border-opacity-25 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-circle-info text-primary"></i>
                                <span class="text-light small">Assigned Role: <strong class="text-primary">Faculty</strong>. Direct Login URL: <code class="text-info bg-transparent p-0">auth/login.html?role=faculty</code></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <button type="reset" class="btn btn-outline-secondary btn-sm px-4" id="btnResetForm">Reset Form</button>
                    <button type="submit" class="btn-primary-action btn-sm px-4" id="btnSubmitReg">
                        <i class="fa-solid fa-check me-1"></i> Register Faculty Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Faculty Credentials Success Modal -->
<div class="modal fade" id="credentialsModal" tabindex="-1" aria-labelledby="credentialsModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-white" style="background: #0B1730; border: 1px solid #4F7CFF; box-shadow: 0 10px 35px rgba(0,0,0,0.6);">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title d-flex align-items-center" id="credentialsModalLabel">
                    <i class="fa-solid fa-circle-check text-success me-2 fs-4"></i> Faculty Registered Successfully!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-secondary small mb-3">The faculty member profile and their login account have been successfully generated.</p>
                
                <div class="p-3 rounded-3 mb-3" style="background: rgba(8, 17, 31, 0.8); border: 1px solid rgba(79, 124, 255, 0.25);">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary border-opacity-25">
                        <span class="text-secondary small">Faculty Name:</span>
                        <span class="fw-bold text-white" id="credName">-</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary border-opacity-25">
                        <span class="text-secondary small">Employee ID:</span>
                        <span class="badge bg-secondary" id="credEmpId">-</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary border-opacity-25">
                        <span class="text-secondary small">Assigned Role:</span>
                        <span class="badge bg-primary" id="credRole">Faculty</span>
                    </div>

                    <!-- Email Row -->
                    <div class="mb-3 pt-1">
                        <label class="text-secondary small mb-1 d-flex justify-content-between">
                            <span>Login Email:</span>
                            <span class="text-primary small cursor-pointer" onclick="copyText('credEmailVal', 'Email copied!')">
                                <i class="fa-solid fa-copy me-1"></i>Copy
                            </span>
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-dark bg-dark text-info fw-medium" id="credEmailVal" readonly>
                        </div>
                    </div>

                    <!-- Password Row -->
                    <div class="mb-1">
                        <label class="text-secondary small mb-1 d-flex justify-content-between">
                            <span>Generated Password:</span>
                            <span class="text-primary small cursor-pointer" onclick="copyText('credPassVal', 'Password copied!')">
                                <i class="fa-solid fa-copy me-1"></i>Copy
                            </span>
                        </label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-dark bg-dark text-warning fw-medium" id="credPassVal" readonly>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info py-2 px-3 small mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-info-circle fs-5"></i>
                    <span>You can share these credentials with the faculty member or log in directly to verify their dashboard.</span>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" id="btnStayHere">
                    Register Another
                </button>
                <div class="d-flex gap-2">
                    <a href="faculty.php" class="btn btn-outline-primary btn-sm">
                        <i class="fa-solid fa-users me-1"></i> Faculty Directory
                    </a>
                    <a href="../auth/login.html?role=faculty" target="_blank" class="btn-primary-action btn-sm text-decoration-none" id="btnLoginFacultyNow">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Login as Faculty
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
function copyText(elementId, toastMsg) {
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
    // Load Departments
    $.ajax({
        url: 'actions/allocation_actions.php?action=get_options',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                let html = '<option value="">Select Department</option>';
                res.departments.forEach(function(d) {
                    html += `<option value="${d.department_id}">${d.department_name}</option>`;
                });
                $('#department_id').html(html);
            }
        }
    });

    // Auto-generate helper functions
    function generateEmpId() {
        const randNum = Math.floor(1000 + Math.random() * 9000);
        return 'FAC-' + randNum;
    }

    function generateRandomPassword() {
        const adjectives = ['Prof', 'Tech', 'Edu', 'Univ', 'Acad', 'Lead', 'Smart'];
        const symbols = ['@', '#', '$', '!'];
        const adj = adjectives[Math.floor(Math.random() * adjectives.length)];
        const sym = symbols[Math.floor(Math.random() * symbols.length)];
        const num = Math.floor(100 + Math.random() * 900);
        return `${adj}${sym}${num}`;
    }

    function autoGenerateEmail(name) {
        if (!name) return '';
        let clean = name.toLowerCase()
            .replace(/^(dr\.|prof\.|mr\.|mrs\.|ms\.)\s*/i, '')
            .trim()
            .replace(/[^a-z0-9\s]/g, '')
            .replace(/\s+/g, '.');
        return clean ? `${clean}@university.edu` : '';
    }

    // Set initial default password
    $('#password').val('Faculty@123');

    // Auto Suggest Employee ID
    $('#btnGenEmpId').on('click', function() {
        const newId = generateEmpId();
        $('#employee_id').val(newId).trigger('input');
        showToast('Suggested Employee ID: ' + newId, 'info');
    });

    // Sync full name -> email
    $('#full_name').on('input', function() {
        const nameVal = $(this).val().trim();
        const currentEmail = $('#email').val().trim();
        // If email is empty or looks auto-generated, keep it updated
        if (!currentEmail || currentEmail.endsWith('@university.edu')) {
            const genEmail = autoGenerateEmail(nameVal);
            if (genEmail) {
                $('#email').val(genEmail).trigger('input');
            }
        }
    });

    // Sync Email field -> display_login_email
    $('#email').on('input', function() {
        const emailVal = $(this).val().trim();
        $('#display_login_email').val(emailVal);
    });

    // Auto-generate email button
    $('#btnAutoEmail').on('click', function() {
        const nameVal = $('#full_name').val().trim();
        if (nameVal) {
            const genEmail = autoGenerateEmail(nameVal);
            $('#email').val(genEmail).trigger('input');
            showToast('Generated email: ' + genEmail, 'success');
        } else {
            const empId = $('#employee_id').val().trim();
            const genEmail = empId ? empId.toLowerCase() + '@university.edu' : 'faculty@university.edu';
            $('#email').val(genEmail).trigger('input');
            showToast('Generated email: ' + genEmail, 'info');
        }
    });

    // Use Employee ID as password
    $('#btnUseEmpIdPass').on('click', function() {
        const empId = $('#employee_id').val().trim();
        if (empId) {
            $('#password').val(empId);
            showToast('Password set to Employee ID: ' + empId, 'info');
        } else {
            const newId = generateEmpId();
            $('#employee_id').val(newId).trigger('input');
            $('#password').val(newId);
            showToast('Generated ID & Password: ' + newId, 'info');
        }
    });

    // Generate random strong password
    $('#btnGenRandomPass').on('click', function() {
        const randPass = generateRandomPassword();
        $('#password').val(randPass);
        showToast('Generated password: ' + randPass, 'success');
    });

    // Toggle password visibility
    $('#btnTogglePassword').on('click', function() {
        const passInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passInput.type === 'password') {
            passInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });

    // Quick Sample Fill
    $('#btnAutoFillSample').on('click', function() {
        const sampleNames = ['Dr. Ananya Iyer', 'Prof. Siddharth Verma', 'Dr. Vikramaditya Singh', 'Prof. Sneha Patil', 'Dr. Rajesh Deshmukh'];
        const sampleQualifications = ['Ph.D in Computer Science', 'M.Tech in Artificial Intelligence', 'Ph.D in Data Science', 'M.E. in Information Technology'];
        
        const randomName = sampleNames[Math.floor(Math.random() * sampleNames.length)];
        const randomQual = sampleQualifications[Math.floor(Math.random() * sampleQualifications.length)];
        const newEmpId = generateEmpId();
        const randPhone = '+91 98' + Math.floor(10000000 + Math.random() * 90000000);
        
        $('#employee_id').val(newEmpId);
        $('#full_name').val(randomName);
        $('#email').val(autoGenerateEmail(randomName)).trigger('input');
        $('#phone').val(randPhone);
        $('#qualification').val(randomQual);
        $('#experience_years').val(Math.floor(3 + Math.random() * 12));
        $('#joining_date').val(new Date().toISOString().split('T')[0]);
        $('#password').val(newEmpId);
        
        // Select first available dept if loaded
        if ($('#department_id option').length > 1) {
            $('#department_id').prop('selectedIndex', 1);
        }

        showToast('Sample data populated!', 'success');
    });

    // Reset Form button
    $('#btnResetForm').on('click', function() {
        setTimeout(() => {
            $('#display_login_email').val('');
            $('#password').val('Faculty@123');
        }, 50);
    });

    // Form Submission
    $('#regFacultyForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $('#btnSubmitReg')[0];
        setButtonLoading(btn, true, 'Registering & Generating Login...');

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
                setButtonLoading(btn, false);
                if (res.status === 'success') {
                    const creds = res.credentials || {
                        name: $('#full_name').val(),
                        email: $('#email').val(),
                        password: $('#password').val(),
                        employee_id: $('#employee_id').val(),
                        role: 'Faculty'
                    };

                    // Populate credentials modal
                    $('#credName').text(creds.name);
                    $('#credEmpId').text(creds.employee_id);
                    $('#credRole').text('Faculty');
                    $('#credEmailVal').val(creds.email);
                    $('#credPassVal').val(creds.password);
                    $('#btnLoginFacultyNow').attr('href', `../auth/login.html?role=faculty&email=${encodeURIComponent(creds.email)}`);

                    // Show Modal
                    const credModal = new bootstrap.Modal(document.getElementById('credentialsModal'));
                    credModal.show();

                    showToast('Faculty created and login credentials generated!', 'success');
                } else {
                    showAlert('Error', res.message, 'error');
                }
            },
            error: function(xhr) {
                setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred during faculty registration.', 'error');
            }
        });
    });

    // Reset on Stay Here / Register Another
    $('#btnStayHere').on('click', function() {
        $('#regFacultyForm')[0].reset();
        $('#regFacultyForm').removeClass('was-validated');
        $('#display_login_email').val('');
        $('#password').val('Faculty@123');
    });
});
</script>
