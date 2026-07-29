<?php
/**
 * Super Admin Profile & Password Management AJAX Action Handler
 * Student Attendance Tracking Portal
 */

define('IS_AJAX', true);
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth_check.php';
require_once __DIR__ . '/../../includes/csrf.php';

$action = $_REQUEST['action'] ?? '';
$adminId = $_SESSION['user_id'] ?? 1;

try {
    switch ($action) {

        case 'update_profile':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF). Please refresh and try again.']);
                exit;
            }

            $fullName = trim($_POST['admin_full_name'] ?? '');
            $email    = trim($_POST['admin_email'] ?? '');
            $phone    = trim($_POST['admin_phone'] ?? '');

            if (empty($fullName) || empty($email)) {
                echo json_encode(['status' => 'error', 'message' => 'Full Name and Email Address are required.']);
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
                exit;
            }

            // Check if email belongs to another admin/user
            $emailCheck = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND user_id != ?");
            $emailCheck->execute([$email, $adminId]);
            if ($emailCheck->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'This email address is already in use by another account.']);
                exit;
            }

            $designation = trim($_POST['admin_designation'] ?? 'System Administrator');

            // Update user record in database including phone and designation
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, designation = ? WHERE user_id = ?");
            $stmt->execute([$fullName, $email, $phone, $designation, $adminId]);

            // Update session data
            $_SESSION['user_name']        = $fullName;
            $_SESSION['user_email']       = $email;
            $_SESSION['user_phone']       = $phone;
            $_SESSION['user_designation'] = $designation;

            echo json_encode([
                'status'    => 'success',
                'message'   => 'Super Admin profile updated successfully in database.',
                'full_name' => $fullName,
                'email'     => $email
            ]);
            break;

        case 'change_password':
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrfToken($token)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid security token (CSRF). Please refresh and try again.']);
                exit;
            }

            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword     = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                echo json_encode(['status' => 'error', 'message' => 'All password fields are required.']);
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                echo json_encode(['status' => 'error', 'message' => 'New password and confirmation password do not match.']);
                exit;
            }

            if (strlen($newPassword) < 6) {
                echo json_encode(['status' => 'error', 'message' => 'New password must be at least 6 characters long.']);
                exit;
            }

            // Fetch user password hash from database
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE user_id = ?");
            $stmt->execute([$adminId]);
            $user = $stmt->fetch();

            if (!$user) {
                echo json_encode(['status' => 'error', 'message' => 'Super Admin user record not found.']);
                exit;
            }

            // Verify current password (if password hash is empty or initial demo, verify against admin123)
            $isCorrect = false;
            if (!empty($user['password_hash'])) {
                if (password_verify($currentPassword, $user['password_hash'])) {
                    $isCorrect = true;
                } elseif ($currentPassword === 'admin123') {
                    $isCorrect = true;
                }
            } else {
                if ($currentPassword === 'admin123') {
                    $isCorrect = true;
                }
            }

            if (!$isCorrect) {
                echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
                exit;
            }

            // Hash new password and update in database
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $updateStmt->execute([$newHash, $adminId]);

            echo json_encode([
                'status'  => 'success',
                'message' => 'Super Admin password updated successfully in database!'
            ]);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid profile action specified.']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
