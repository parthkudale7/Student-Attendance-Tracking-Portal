<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Attendance Tracking Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            text-align: center;
        }
        .login-logo {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 10px;
        }
        .login-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--text-primary);
        }
        .login-subtitle {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }
        .login-form {
            text-align: left;
        }
        .login-form .form-group {
            margin-bottom: 20px;
        }
        .login-form label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: var(--text-primary);
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container glass-card fade-in">
        <div class="login-logo">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <h2 class="login-title">Welcome Back</h2>
        <p class="login-subtitle">Sign in to the Faculty Portal</p>
        
        <form class="login-form" id="login-form">
            <div class="form-group">
                <label for="email">Email Address</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-envelope" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                    <input type="email" id="email" class="glass-input" style="padding-left: 40px;" placeholder="faculty@college.edu" required value="smith@college.edu">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-lock" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"></i>
                    <input type="password" id="password" class="glass-input" style="padding-left: 40px;" placeholder="Enter your password" required value="pass123">
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; font-size: 0.85rem;">
                <label style="display: flex; align-items: center; gap: 8px; margin: 0; cursor: pointer;">
                    <input type="checkbox" style="accent-color: var(--primary);" checked> Remember me
                </label>
                <a href="#" style="color: var(--primary); text-decoration: none;">Forgot Password?</a>
            </div>
            
            <button type="submit" class="btn btn-primary login-btn">Sign In</button>
        </form>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // Just simulate login by redirecting to the main app
            window.location.href = 'index.php';
        });
    </script>
</body>
</html>
