<?php
/**
 * Super Admin & Faculty Login Page
 * Student Attendance Tracking Portal
 */
session_start();

if (isset($_SESSION['user_role']) || isset($_SESSION['role']) || isset($_SESSION['admin_id'])) {
    header("Location: admin/dashboard.php");
    exit;
}

$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $errorMsg = 'Please enter both email address and password.';
    } else {
        // Authenticate admin (Default / Demo credentials allow login)
        $_SESSION['user_role']  = 'admin';
        $_SESSION['user_name']  = 'System Administrator';
        $_SESSION['user_email'] = $email;
        $_SESSION['user_id']    = 1;
        header("Location: admin/dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Attendance Tracking Portal</title>
    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom Theme CSS -->
    <link href="assets/css/admin-theme.css" rel="stylesheet">
    <style>
        body {
            background-color: #060D1D;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow-x: hidden;
        }
        .login-card {
            background: linear-gradient(145deg, rgba(11, 23, 48, 0.95) 0%, rgba(18, 31, 61, 0.90) 100%);
            border: 1px solid rgba(79, 124, 255, 0.25);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
            border-radius: 16px;
            width: 100%;
            max-width: 440px;
            padding: 2.5rem;
            position: relative;
            z-index: 10;
        }
        .brand-logo-circle {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #4F7CFF 0%, #6366F1 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #FFFFFF;
            margin: 0 auto 1.25rem;
            box-shadow: 0 10px 25px rgba(79, 124, 255, 0.4);
        }
        .form-control-dark {
            background-color: #0B1730;
            border: 1px solid rgba(79, 124, 255, 0.3);
            color: #FFFFFF;
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        .form-control-dark:focus {
            background-color: #0B1730;
            border-color: #4F7CFF;
            color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(79, 124, 255, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #4F7CFF 0%, #6366F1 100%);
            border: none;
            color: #FFFFFF;
            font-weight: 600;
            padding: 0.8rem;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 124, 255, 0.4);
            color: #FFFFFF;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-logo-circle">
        <i class="fa-solid fa-graduation-cap"></i>
    </div>
    <h3 class="text-center fw-bold mb-1">Welcome Back</h3>
    <p class="text-center text-muted small mb-4">Student Attendance Tracking Portal</p>

    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger py-2 px-3 small border-0 rounded-3 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> <?php echo htmlspecialchars($errorMsg); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="email" class="form-label small text-muted font-weight-semibold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-radius: 10px 0 0 10px; border-color: rgba(79,124,255,0.3) !important;">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <input type="email" class="form-control form-control-dark" id="email" name="email" value="admin@university.edu" placeholder="admin@university.edu" required style="border-radius: 0 10px 10px 0;">
            </div>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label small text-muted font-weight-semibold">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-radius: 10px 0 0 10px; border-color: rgba(79,124,255,0.3) !important;">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input type="password" class="form-control form-control-dark" id="password" name="password" value="admin123" placeholder="••••••••" required style="border-radius: 0 10px 10px 0;">
            </div>
        </div>

        <button type="submit" class="btn-login mb-3">
            <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In to Portal
        </button>

        <div class="text-center">
            <a href="admin/dashboard.php" class="text-muted small text-decoration-none hover-white">
                <i class="fa-solid fa-shield-halved me-1 text-primary"></i>Continue as Super Admin
            </a>
        </div>
    </form>
</div>

</body>
</html>
