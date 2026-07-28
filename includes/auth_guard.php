<?php

function check_auth($allowed_roles = []) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        // Not logged in
        header("Location: ../auth/login.html");
        exit;
    }

    if (!empty($allowed_roles) && !in_array($_SESSION['role'], $allowed_roles)) {
        // Logged in, but wrong role (e.g. student trying to access admin dashboard)
        header("Location: ../403.php");
        exit;
    }
}
