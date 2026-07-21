<?php
/**
 * Login Page
 * 
 * TODO: Include database connection file once confirmed by teammate:
 * // require_once '../includes/db_connect.php';
 * 
 * TODO: DB Schema is not yet finalized (separate tables for students/faculty/admins vs unified users).
 * Placeholder queries and login logic will be implemented here.
 */

// Phase 0: Scaffold only. Auth logic to be added in next phase.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Student Attendance Tracking Portal</title>
    <!-- Bootstrap CSS (as specified in tech stack) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <!-- Login Form Scaffold -->
                        <form id="loginForm" action="login.php" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username / Email</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" disabled selected>Select Role</option>
                                    <option value="admin">Super Admin</option>
                                    <option value="faculty">Faculty</option>
                                    <option value="student">Student</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/auth-validation.js"></script>
</body>
</html>
