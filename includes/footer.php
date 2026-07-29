<?php
/**
 * Admin Shared Footer Component
 * Student Attendance Tracking Portal
 */
?>
        <!-- Security Notice Footer Banner -->
        <div class="security-banner">
            <i class="fa-solid fa-shield-halved"></i>
            <span>All management data is secure and only accessible by authorized administrators.</span>
        </div>

    </main> <!-- /main-content -->
</div> <!-- /#content -->
</div> <!-- /#wrapper -->

<!-- Edit Super Admin Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">
                    <i class="fa-solid fa-user-gear me-2 text-primary"></i>Edit Super Admin Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSuperAdminProfileForm" novalidate>
                <div class="modal-body py-3">
                    <?php if (function_exists('getCsrfTokenInput')) echo getCsrfTokenInput(); ?>
                    <div class="text-center mb-3">
                        <div class="user-avatar-circle mx-auto mb-2" style="width: 72px; height: 72px; font-size: 1.6rem;">
                            <i class="fa-solid fa-user-shield" style="font-size: 2rem; color: #FFFFFF;"></i>
                        </div>
                        <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50 px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-shield-halved me-1"></i>SUPER ADMIN PRIVILEGES
                        </span>
                    </div>

                    <div class="mb-3">
                        <label for="admin_full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-dark" id="admin_full_name" name="admin_full_name" value="<?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control form-control-dark" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? 'admin@university.edu', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="admin_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control form-control-dark" id="admin_phone" name="admin_phone" value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? '+1 (555) 019-2834', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-6">
                            <label for="admin_designation" class="form-label">Designation</label>
                            <input type="text" class="form-control form-control-dark" id="admin_designation" name="admin_designation" value="System Administrator" required>
                        </div>
                    </div>

                    <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 mb-1">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted d-block">Access Level</small>
                                <span class="fw-semibold text-success"><i class="fa-solid fa-lock-open me-1"></i>Full System Control</span>
                            </div>
                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2 py-1">ACTIVE</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-action btn-sm" id="btnSaveAdminProfile">
                        <i class="fa-solid fa-floppy-disk me-1"></i>Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="fa-solid fa-key me-2 text-warning"></i>Change Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePasswordForm" novalidate>
                <div class="modal-body">
                    <?php if (function_exists('getCsrfTokenInput')) echo getCsrfTokenInput(); ?>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-dark" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-dark" id="new_password" name="new_password" required>
                        <div class="strength-meter-bar">
                            <div class="strength-meter-fill" id="passStrengthFill"></div>
                        </div>
                        <small class="text-muted" id="passStrengthText">Password strength meter</small>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-dark" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-action btn-sm">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal (Minimalist Theme-Adapted) -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 350px;">
        <div class="modal-content logout-card-theme p-4 text-center">
            <!-- Icon Circle -->
            <div class="logout-icon-circle mx-auto mb-3">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </div>
            
            <!-- Title & Subtitle -->
            <h4 class="text-white fw-bold mb-1" style="font-size: 1.35rem; letter-spacing: -0.3px;">Logout</h4>
            <p class="mb-4" style="color: #94A3B8; font-size: 0.88rem; font-weight: 400;">Are you sure you want to logout?</p>

            <!-- Action Buttons -->
            <div class="d-flex align-items-center justify-content-end gap-3 pt-1">
                <button type="button" class="btn btn-link text-decoration-none text-primary-cancel p-0 fw-semibold" data-bs-dismiss="modal" style="font-size: 0.9rem;">Cancel</button>
                <a href="../logout.php" class="btn btn-primary-logout px-4 py-2 fw-semibold" style="font-size: 0.9rem;">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Command Palette (CTRL + K) Modal -->
<div class="modal fade" id="commandPaletteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 620px;">
        <div class="modal-content cmd-palette-modal-content">
            <div class="cmd-palette-header p-3 border-bottom border-white border-opacity-10 d-flex align-items-center gap-3">
                <i class="fa-solid fa-magnifying-glass text-primary fs-5 ms-2"></i>
                <input type="text" id="cmdPaletteInput" class="cmd-palette-input" placeholder="Type to search departments, courses, faculty, reports..." autocomplete="off">
                <span class="badge bg-secondary bg-opacity-20 text-muted px-2 py-1" style="font-size: 0.75rem;">ESC to close</span>
            </div>
            <div class="cmd-palette-body p-2" style="max-height: 380px; overflow-y: auto;">
                <div class="cmd-group-title text-muted px-3 pt-2 pb-1" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.8px;">QUICK NAVIGATION & PORTAL MODULES</div>
                <div class="cmd-results-list" id="cmdResultsList">
                    <a href="dashboard.php" class="cmd-item">
                        <i class="fa-solid fa-gauge-high text-primary"></i>
                        <div class="cmd-item-info">
                            <div class="cmd-item-title">Dashboard Overview</div>
                            <div class="cmd-item-desc">View real-time statistics, active rate, and portal insights</div>
                        </div>
                        <span class="cmd-shortcut">Jump to</span>
                    </a>
                    <a href="department.php" class="cmd-item">
                        <i class="fa-solid fa-building-columns text-info"></i>
                        <div class="cmd-item-info">
                            <div class="cmd-item-title">Department Management</div>
                            <div class="cmd-item-desc">Manage academic departments, HOD assignments, and status</div>
                        </div>
                        <span class="cmd-shortcut">Jump to</span>
                    </a>
                    <a href="courses.php" class="cmd-item">
                        <i class="fa-solid fa-graduation-cap text-success"></i>
                        <div class="cmd-item-info">
                            <div class="cmd-item-title">Course Management</div>
                            <div class="cmd-item-desc">Configure degree programs, duration, and semesters</div>
                        </div>
                        <span class="cmd-shortcut">Jump to</span>
                    </a>
                    <a href="subjects.php" class="cmd-item">
                        <i class="fa-solid fa-book-bookmark text-warning"></i>
                        <div class="cmd-item-info">
                            <div class="cmd-item-title">Subject Management</div>
                            <div class="cmd-item-desc">Add curriculum subjects, credits, and course mapping</div>
                        </div>
                        <span class="cmd-shortcut">Jump to</span>
                    </a>
                    <a href="faculty.php" class="cmd-item">
                        <i class="fa-solid fa-user-tie text-danger"></i>
                        <div class="cmd-item-info">
                            <div class="cmd-item-title">Faculty CRUD & Records</div>
                            <div class="cmd-item-desc">View faculty directory, employee IDs, and designation</div>
                        </div>
                        <span class="cmd-shortcut">Jump to</span>
                    </a>
                    <a href="subject_allocation.php" class="cmd-item">
                        <i class="fa-solid fa-diagram-project text-primary"></i>
                        <div class="cmd-item-info">
                            <div class="cmd-item-title">Subject Allocation</div>
                            <div class="cmd-item-desc">Assign subjects and divisions to faculty members</div>
                        </div>
                        <span class="cmd-shortcut">Jump to</span>
                    </a>
                    <a href="academic_year.php" class="cmd-item">
                        <i class="fa-solid fa-calendar-days text-info"></i>
                        <div class="cmd-item-info">
                            <div class="cmd-item-title">Academic Year Configuration</div>
                            <div class="cmd-item-desc">Set active academic term, start dates, and end dates</div>
                        </div>
                        <span class="cmd-shortcut">Jump to</span>
                    </a>
                </div>
            </div>
            <div class="cmd-palette-footer p-2.5 px-3 border-top border-white border-opacity-10 d-flex align-items-center justify-content-between text-muted" style="font-size: 0.75rem;">
                <div class="d-flex align-items-center gap-3">
                    <span><kbd class="cmd-mini-kbd">↑↓</kbd> Navigate</span>
                    <span><kbd class="cmd-mini-kbd">↵</kbd> Select</span>
                    <span><kbd class="cmd-mini-kbd">esc</kbd> Dismiss</span>
                </div>
                <span>Super Admin Search</span>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JS & Bootstrap 5 Integration -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- DataTables Buttons for CSV, Excel, Print -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Flatpickr Custom Theme Calendar JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- GSAP 3 Motion Engine & ScrollTrigger -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

<!-- Custom Admin JS with Automatic Cache Busting -->
<script src="../assets/js/admin.js?v=<?php echo time(); ?>"></script>

<script>
$(document).ready(function() {
    // Save Super Admin Profile Listener
    $('#editSuperAdminProfileForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const newName = $('#admin_full_name').val().trim();
        const newEmail = $('#admin_email').val().trim();

        if (!newName || !newEmail) {
            showAlert('Validation Error', 'Name and Email are required.', 'error');
            return;
        }

        const btn = $('#btnSaveAdminProfile')[0];
        if (btn) setButtonLoading(btn, true, 'Saving...');

        const formData = $(form).serialize() + '&action=update_profile';

        $.ajax({
            url: '../admin/actions/admin_profile_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (btn) setButtonLoading(btn, false);
                if (res.status === 'success') {
                    const modalEl = document.getElementById('profileModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal.getOrCreateInstance(modalEl);
                    modalInstance.hide();

                    $('.topbar-welcome .fw-bold').text(res.full_name.toUpperCase());
                    $('.dark-dropdown-menu .fw-semibold').text(res.full_name);

                    showToast(res.message, 'success');
                } else {
                    showAlert('Profile Update Error', res.message, 'error');
                }
            },
            error: function() {
                if (btn) setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred while updating profile.', 'error');
            }
        });
    });

    // Password strength listener
    $('#new_password').on('input', function() {
        const val = $(this).val();
        let score = 0;
        if (val.length >= 6) score += 25;
        if (/[A-Z]/.test(val)) score += 25;
        if (/[0-9]/.test(val)) score += 25;
        if (/[^A-Za-z0-9]/.test(val)) score += 25;

        $('#passStrengthFill').css('width', score + '%');
        if (score <= 25) {
            $('#passStrengthFill').css('background-color', '#ef4444');
            $('#passStrengthText').text('Weak password').css('color', '#ef4444');
        } else if (score <= 50) {
            $('#passStrengthFill').css('background-color', '#f59e0b');
            $('#passStrengthText').text('Fair password').css('color', '#f59e0b');
        } else if (score <= 75) {
            $('#passStrengthFill').css('background-color', '#3b82f6');
            $('#passStrengthText').text('Good password').css('color', '#3b82f6');
        } else {
            $('#passStrengthFill').css('background-color', '#10b981');
            $('#passStrengthText').text('Strong password').css('color', '#10b981');
        }
    });

    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const p1 = $('#new_password').val();
        const p2 = $('#confirm_password').val();
        if (p1 !== p2) {
            Swal.fire({ icon: 'error', title: 'Mismatch', text: 'New passwords do not match.' });
            return;
        }

        const btn = $(form).find('button[type="submit"]')[0];
        if (btn) setButtonLoading(btn, true, 'Updating...');

        const formData = $(form).serialize() + '&action=change_password';

        $.ajax({
            url: '../admin/actions/admin_profile_actions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (btn) setButtonLoading(btn, false);
                if (res.status === 'success') {
                    const modalEl = document.getElementById('changePasswordModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal.getOrCreateInstance(modalEl);
                    modalInstance.hide();
                    form.reset();
                    Swal.fire({ icon: 'success', title: 'Password Updated', text: res.message });
                } else {
                    showAlert('Password Update Error', res.message, 'error');
                }
            },
            error: function() {
                if (btn) setButtonLoading(btn, false);
                showAlert('Error', 'An unexpected error occurred while updating password.', 'error');
            }
        });
    });
});
</script>

</body>
</html>
