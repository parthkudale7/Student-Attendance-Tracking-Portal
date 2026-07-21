<?php
/**
 * Change Password Page (In-Session only)
 * 
 * TODO: Include database connection file once confirmed by teammate:
 * // require_once '../includes/db_connect.php';
 * 
 * TODO: DB Schema is not yet finalized (separate tables for students/faculty/admins vs unified users).
 * Password updating query using prepared statements will be implemented here.
 */

// Phase 0: Scaffold only. Password change logic will be added in next phase.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - Student Attendance Tracking Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Change Password</h4>
                    </div>
                    <div class="card-body">
                        <!-- Password Change Form Scaffold -->
                        <form id="changePasswordForm" action="change_password.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/auth-validation.js"></script>
</body>
</html>
