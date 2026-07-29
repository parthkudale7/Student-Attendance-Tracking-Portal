<?php
session_start();

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logging Out...</title>
    <script>
        // Clear client-side session data
        sessionStorage.clear();
        localStorage.clear();
        
        // Redirect to the landing page
        window.location.href = '../index.php';
    </script>
</head>
<body>
    <p style="text-align: center; font-family: sans-serif; margin-top: 50px;">Securely logging you out...</p>
</body>
</html>
