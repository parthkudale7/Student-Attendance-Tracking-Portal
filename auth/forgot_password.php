<?php
/**
 * Forgot Password Workflow - Cyberpunk UI
 * Mock implementation of Email -> OTP -> Reset
 */
session_start();

$step = $_POST['step'] ?? 1;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 1) {
        $email = $_POST['email'] ?? '';
        if (empty($email)) {
            $error = 'Email is required.';
            $step = 1;
        } else {
            // Mock: Send OTP
            $_SESSION['reset_email'] = $email;
            $_SESSION['mock_otp'] = '123456'; // Mock OTP
            $success = 'OTP sent to your email (Mock: 123456)';
            $step = 2;
        }
    } elseif ($step == 2) {
        $otp = $_POST['otp'] ?? '';
        if ($otp !== $_SESSION['mock_otp']) {
            $error = 'Invalid OTP.';
            $step = 2;
        } else {
            $success = 'OTP Verified. Proceed to reset.';
            $step = 3;
        }
    } elseif ($step == 3) {
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($regex, $new)) {
            $error = 'Password does not meet requirements.';
            $step = 3;
        } elseif ($new !== $confirm) {
            $error = 'Passwords do not match.';
            $step = 3;
        } else {
            $success = 'Password successfully reset!';
            session_destroy(); // Clear reset tokens
            $step = 4;
        }
    }
} else {
    $step = 1; // Default GET
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Recovery - Cyberpunk Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

    <div id="toastContainer" class="toast-container"></div>

    <div class="container-fluid p-0">
        <div class="row g-0 auth-wrapper align-items-center justify-content-center">
            
            <div class="col-md-6 col-lg-4 p-4">
                <div class="glass-card w-100 slide-up">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-exclamation fs-1 text-neon-pink"></i>
                        <h3 class="mt-2 text-white">System Recovery</h3>
                        <p class="text-secondary">Follow the protocol to regain access</p>
                    </div>

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
                            });
                        </script>
                    <?php endif; ?>

                    <form action="forgot_password.php" method="POST" class="needs-validation" novalidate id="forgotPasswordForm">
                        <input type="hidden" name="step" value="<?php echo $step; ?>">

                        <?php if ($step == 1): ?>
                            <!-- Step 1: Email -->
                            <div class="mb-4 fade-in">
                                <label for="email" class="form-label">Registered Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-secondary"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-neon w-100 mb-3">Send OTP</button>

                        <?php elseif ($step == 2): ?>
                            <!-- Step 2: OTP -->
                            <div class="mb-4 fade-in">
                                <label for="otp" class="form-label">6-Digit Protocol (OTP)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-secondary"><i class="bi bi-asterisk"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 text-center fw-bold text-neon-blue" id="otp" name="otp" required maxlength="6" style="letter-spacing: 5px;">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-neon w-100 mb-3">Verify Protocol</button>

                        <?php elseif ($step == 3): ?>
                            <!-- Step 3: Reset -->
                            <div class="mb-3 fade-in">
                                <label for="new_password" class="form-label">New Access Key</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-secondary"><i class="bi bi-shield-lock"></i></span>
                                    <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="new_password" name="new_password" required>
                                    <span class="input-group-text bg-transparent border-start-0 toggle-password" data-target="new_password" style="cursor:pointer;"><i class="bi bi-eye text-secondary"></i></span>
                                </div>
                                <div class="progress mt-2" style="height: 5px; background: rgba(255,255,255,0.1);">
                                    <div id="passwordStrengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                </div>
                            </div>

                            <div class="mb-4 fade-in">
                                <label for="confirm_password" class="form-label">Verify Access Key</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-secondary"><i class="bi bi-check-circle"></i></span>
                                    <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="confirm_password" name="confirm_password" required>
                                    <span class="input-group-text bg-transparent border-start-0 toggle-password" data-target="confirm_password" style="cursor:pointer;"><i class="bi bi-eye text-secondary"></i></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-neon w-100 mb-3">Initialize Key</button>

                        <?php elseif ($step == 4): ?>
                            <!-- Step 4: Success -->
                            <div class="text-center fade-in mb-4">
                                <i class="bi bi-check-circle-fill text-neon-green" style="font-size: 4rem;"></i>
                                <h4 class="text-white mt-3">Access Restored</h4>
                                <p class="text-secondary">Your protocol has been updated successfully.</p>
                            </div>
                            <a href="login.php" class="btn btn-neon w-100 mb-3">Return to Login</a>
                        <?php endif; ?>

                        <?php if ($step != 4): ?>
                            <div class="text-center mt-3">
                                <a href="login.php" class="text-secondary text-decoration-none hover-neon" style="font-size: 0.9rem;">
                                    <i class="bi bi-arrow-left"></i> Cancel Recovery
                                </a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/auth-validation.js"></script>
</body>
</html>
