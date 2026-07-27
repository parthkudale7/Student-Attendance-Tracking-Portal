<?php
/**
 * 403 Access Denied - Cyberpunk UI
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden - Cyberpunk Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0 auth-wrapper align-items-center justify-content-center">
            <div class="col-md-6 col-lg-5 p-4 text-center">
                <div class="glass-card fade-in">
                    <i class="bi bi-lock-fill text-neon-pink" style="font-size: 5rem; text-shadow: 0 0 20px var(--neon-pink);"></i>
                    <h1 class="text-white mt-4 display-5 fw-bold text-neon-pink">403</h1>
                    <h3 class="text-white mb-3">Access Denied</h3>
                    <p class="text-secondary mb-5">You do not have the required clearance to access this sector of the portal.</p>
                    
                    <?php if(isset($_SESSION['role'])): ?>
                        <a href="<?php echo htmlspecialchars($_SESSION['role']); ?>/dashboard.php" class="btn btn-neon px-5">Return to Dashboard</a>
                    <?php else: ?>
                        <a href="auth/login.php" class="btn btn-neon px-5">Go to Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
