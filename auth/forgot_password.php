<?php
/**
 * Forgot Password Workflow - Cyberpunk UI
 * Mock implementation of Email -> OTP -> Reset
 */
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['r'])) {
    unset($_SESSION['recovery_step'], $_SESSION['reset_email'], $_SESSION['mock_otp']);
}

$role_param = isset($_GET['role']) ? '?role=' . urlencode($_GET['role']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_step = $_POST['step'] ?? 1;
    
    if ($current_step == 1) {
        $email = $_POST['email'] ?? '';
        if (empty($email)) {
            $_SESSION['error'] = 'Email is required.';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                if (!$stmt->fetch()) {
                    $_SESSION['error'] = 'Email not found in our system.';
                } else {
                    $_SESSION['reset_email'] = $email;
                    $generated_otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                    $_SESSION['mock_otp'] = $generated_otp;
                    $_SESSION['success'] = "OTP sent to your email (Mock: $generated_otp)";
                    $_SESSION['recovery_step'] = 2;
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = 'Database connection error.';
            }
        }
    } elseif ($current_step == 2) {
        $otp = $_POST['otp'] ?? '';
        if ($otp !== ($_SESSION['mock_otp'] ?? '')) {
            $_SESSION['error'] = 'Invalid OTP.';
        } else {
            $_SESSION['success'] = 'OTP Verified. Proceed to reset.';
            $_SESSION['recovery_step'] = 3;
        }
    } elseif ($current_step == 3) {
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($regex, $new)) {
            $_SESSION['error'] = 'Password does not meet requirements.';
        } elseif ($new !== $confirm) {
            $_SESSION['error'] = 'Passwords do not match.';
        } else {
            try {
                $hashed = password_hash($new, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->execute([$hashed, $_SESSION['reset_email']]);
                
                $_SESSION['success'] = 'Password successfully reset!';
                $_SESSION['recovery_step'] = 4;
            } catch (PDOException $e) {
                $_SESSION['error'] = 'Database error while resetting password.';
            }
        }
    }
    
    $r_param = isset($_GET['role']) ? '&r=1' : '?r=1';
    header("Location: forgot_password.php" . $role_param . $r_param);
    exit;
}

// GET Request Handling
$step = $_SESSION['recovery_step'] ?? 1;
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);
if ($step == 4) {
    unset($_SESSION['recovery_step'], $_SESSION['reset_email'], $_SESSION['mock_otp']);
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
<body class="login-page page-transition" id="forgotBody">

    <!-- Background Elements -->
    <div class="ambient-light login-glow"></div>
    <div class="particles-layer"></div>    <div id="toastContainer" class="toast-container"></div>

    <div class="login-container container d-flex align-items-center justify-content-center min-vh-100">
        <div class="login-split-card glass-card row w-100 mx-auto g-0 animate-slide-up">
            
            <!-- Left Side: Illustration -->
            <div class="col-lg-6 d-none d-lg-flex flex-column align-items-center justify-content-center illustration-side position-relative overflow-hidden p-5">
                <div class="bg-shape"></div>
                <div id="dynamicIllustration"></div>
                <div class="text-center mt-5 text-white position-relative z-index-2">
                    <h2 class="fw-bold mb-3" id="dynamicTitleLeft">System Recovery</h2>
                    <p class="text-secondary opacity-75" id="dynamicDescLeft">Secure protocol to regain access to your account.</p>
                </div>
                
                <!-- Floating mini elements -->
                <div class="floating-icons">
                    <i class="bi bi-shield-lock pe-4 mb-4 opacity-50"></i>
                    <i class="bi bi-key ps-4 mb-4 opacity-50"></i>
                    <i class="bi bi-fingerprint mt-4 pe-3 opacity-50"></i>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="col-lg-6 form-side p-5 d-flex flex-column justify-content-center position-relative">
                
                <div class="form-header text-center mb-5 mt-4">
                    <div class="role-icon-header mx-auto mb-3">
                        <i class="bi" id="dynamicIcon"></i>
                    </div>
                    <h3 class="text-white fw-bold mb-2" id="dynamicTitleRight">System Recovery</h3>
                    <p class="text-secondary small">Follow the protocol to regain access</p>
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
                            <button type="submit" class="btn btn-primary-lux w-100 mb-3">Send OTP</button>

                        <?php elseif ($step == 2): ?>
                            <!-- Step 2: OTP -->
                            <div class="mb-4 fade-in">
                                <label for="otp" class="form-label">6-Digit OTP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-secondary"><i class="bi bi-asterisk"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 text-center fw-bold text-neon-blue" id="otp" name="otp" required maxlength="6" style="letter-spacing: 5px;">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary-lux w-100 mb-3">Verify OTP</button>

                        <?php elseif ($step == 3): ?>
                            <!-- Step 3: Reset -->
                            <div class="mb-3 fade-in">
                                <label for="new_password" class="form-label">New Password</label>
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
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 text-secondary"><i class="bi bi-check-circle"></i></span>
                                    <input type="password" class="form-control border-start-0 border-end-0 ps-0" id="confirm_password" name="confirm_password" required>
                                    <span class="input-group-text bg-transparent border-start-0 toggle-password" data-target="confirm_password" style="cursor:pointer;"><i class="bi bi-eye text-secondary"></i></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary-lux w-100 mb-3">Reset Password</button>

                        <?php elseif ($step == 4): ?>
                            <!-- Step 4: Success -->
                            <div class="text-center fade-in mb-4">
                                <i class="bi bi-check-circle-fill text-neon-green" style="font-size: 4rem;"></i>
                                <h4 class="text-white mt-3">Access Restored</h4>
                                <p class="text-secondary">Your protocol has been updated successfully.</p>
                            </div>
                            <a href="login.html" class="btn btn-primary-lux w-100 mb-3" id="returnToLoginBtn">Return to Login</a>
                        <?php endif; ?>

                        <?php if ($step != 4): ?>
                            <div class="text-center mt-3">
                                <a href="login.html" class="text-secondary text-decoration-none hover-glow transition-link" id="cancelRecoveryBtn" style="font-size: 0.9rem;">
                                    <i class="bi bi-arrow-left"></i> Cancel Recovery
                                </a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/auth-validation.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Determine role from URL
            const params = new URLSearchParams(window.location.search);
            let role = params.get('role') || 'student';
            const validRoles = ['admin', 'faculty', 'student'];
            if (!validRoles.includes(role)) role = 'student';

            // Set dynamic properties
            const roleProps = {
                'admin': { 
                    colorClass: 'theme-admin',
                    icon: 'bi-shield-fill',
                    illustration: '<div class="shield-illustration"><i class="bi bi-shield-check"></i><div class="glow-ring"></div></div>'
                },
                'faculty': { 
                    colorClass: 'theme-faculty',
                    icon: 'bi-person-workspace',
                    illustration: '<div class="faculty-illustration"><i class="bi bi-person-video3"></i><div class="glow-ring"></div></div>'
                },
                'student': { 
                    colorClass: 'theme-student',
                    icon: 'bi-mortarboard-fill',
                    illustration: '<div class="student-illustration"><i class="bi bi-mortarboard-fill"></i><div class="glow-ring"></div></div>'
                }
            };
            
            const props = roleProps[role];
            
            // Apply DOM updates
            document.getElementById('forgotBody').classList.add(props.colorClass);
            document.getElementById('dynamicIllustration').innerHTML = props.illustration;
            document.getElementById('dynamicIcon').classList.add(props.icon);

            // Update links to preserve role
            const returnBtn = document.getElementById('returnToLoginBtn');
            const cancelBtn = document.getElementById('cancelRecoveryBtn');
            const forgotForm = document.getElementById('forgotPasswordForm');

            if (returnBtn) returnBtn.href = `login.html?role=${role}`;
            if (cancelBtn) cancelBtn.href = `login.html?role=${role}`;
            if (forgotForm) {
                // Ensure form submits back to itself with the role parameter preserved
                const url = new URL(forgotForm.action);
                url.searchParams.set('role', role);
                forgotForm.action = url.toString();
            }

            // Initial Fade In
            setTimeout(() => {
                document.body.classList.add('loaded');
            }, 100);
            
            // Handle smooth page transitions for links
            document.querySelectorAll('.transition-link, a.btn-primary-lux').forEach(link => {
                if (link.getAttribute('href') && !link.getAttribute('href').startsWith('#')) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = this.href;
                        document.body.classList.remove('loaded');
                        setTimeout(() => {
                            window.location.href = target;
                        }, 500); // 500ms fade out
                    });
                }
            });
        });

        // Fix BFCache blank screen issue when using back button
        window.addEventListener('pageshow', function (event) {
            document.body.classList.add('loaded');
        });
    </script>
</body>
</html>
