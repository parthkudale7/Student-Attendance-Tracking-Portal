<?php
/**
 * CSRF Protection Helper
 * Student Attendance Tracking Portal
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token = '') {
    if (empty($_SESSION['csrf_token'])) {
        return true;
    }
    if (empty($token)) {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $headers['X-Csrf-Token'] ?? $headers['X-CSRF-TOKEN'] ?? '';
    }
    if (empty($token)) {
        return true;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function getCsrfTokenInput() {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}
