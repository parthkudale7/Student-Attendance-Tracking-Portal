<?php
/**
 * Change Password - Cyberpunk UI
 */
session_start();
require_once '../includes/auth_guard.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($current) || empty($new) || empty($confirm)) {
        $error = 'All fields are required.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } elseif ($current === $new) {
        $error = 'New password cannot be the same as current password.';
    } else {
        // Mock success
        $success = 'Password changed successfully.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Cyberpunk Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

    <div id="toastContainer" class="toast-container"></div>

    <!-- Include Navbar (mocked) -->
    <div class="topbar w-100 ps-4">
        <div class="brand text-white fw-bold"><i class="bi bi-hexagon-fill text-neon-blue"></i> PORTAL</div>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Abort Session</a>
    </div>

    <div class="container-fluid p-0 pt-5 mt-4">
        <div class="row justify-content-center p-4">
            <div class="col-md-6 col-lg-5">
                <div class="glass-card slide-up">
                    <h3 class="text-white mb-4"><i class="bi bi-key text-neon-purple"></i> Security Update</h3>
                    
                    <?php if ($error): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                if(typeof CyberToast !== 'undefined') CyberToast.show('<?php echo htmlspecialchars($error); ?>', 'error');
                            });
                        </script>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                if(typeof CyberToast !== 'undefined') CyberToast.show('<?php echo htmlspecialchars($success); ?>', 'success');
                                setTimeout(() => { window.location.href = '../<?php echo htmlspecialchars($_SESSION['role']); ?>/dashboard.php'; }, 2000);
                            });
                        </script>
                    <?php endif; ?>

                    <form id="changePasswordForm" action="change_password.php" method="POST" class="needs-validation" novalidate>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Key</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-secondary"><i class="bi bi-unlock"></i></span>
                                <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="current_password" name="current_password" required>
                                <span class="input-group-text bg-transparent border-start-0 toggle-password" data-target="current_password" style="cursor:pointer;">
                                    <i class="bi bi-eye text-secondary"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Key</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-secondary"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="new_password" name="new_password" required minlength="8">
                                <span class="input-group-text bg-transparent border-start-0 toggle-password" data-target="new_password" style="cursor:pointer;">
                                    <i class="bi bi-eye text-secondary"></i>
                                </span>
                            </div>
                            <div class="progress mt-2" style="height: 5px; background: rgba(255,255,255,0.1);">
                                <div id="passwordStrengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <small class="text-secondary mt-1 d-block">Min 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char.</small>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Verify New Key</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-secondary"><i class="bi bi-check-circle"></i></span>
                                <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="confirm_password" name="confirm_password" required>
                                <span class="input-group-text bg-transparent border-start-0 toggle-password" data-target="confirm_password" style="cursor:pointer;">
                                    <i class="bi bi-eye text-secondary"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <a href="../<?php echo htmlspecialchars($_SESSION['role']); ?>/dashboard.php" class="btn btn-outline-light w-50">Cancel</a>
                            <button type="submit" class="btn btn-neon w-50">Update Key</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/auth-validation.js"></script>
</body>
</html>
