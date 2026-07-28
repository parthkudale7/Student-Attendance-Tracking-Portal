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
        <p class="page-header-subtitle">Register a new university faculty member and assign department credentials</p>
    </div>
    <a href="faculty.php" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Faculty Directory
    </a>
</div>

<!-- Form Card Container -->
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="data-card">
            <div class="data-card-header mb-4">
                <h3 class="data-card-title">
                    <i class="fa-solid fa-user-plus text-primary me-2"></i>New Faculty Registration Form
                </h3>
            </div>

            <form id="regFacultyForm" enctype="multipart/form-data" novalidate>
                <?php echo getCsrfTokenInput(); ?>
                
                <div class="row g-4">
                    <!-- Personal & Employee Details -->
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

                    <!-- Academic Details -->
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
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <button type="reset" class="btn btn-outline-secondary btn-sm px-4">Reset Form</button>
                    <button type="submit" class="btn-primary-action btn-sm px-4" id="btnSubmitReg">
                        <i class="fa-solid fa-check me-1"></i> Register Faculty Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
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

    $('#regFacultyForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (!form.checkValidity()) {
            e.stopPropagation();
            $(form).addClass('was-validated');
            return;
        }

        const btn = $('#btnSubmitReg')[0];
        setButtonLoading(btn, true, 'Registering...');

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
                    showToast(res.message, 'success');
                    setTimeout(() => window.location.href = 'faculty.php', 1200);
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
});
</script>
