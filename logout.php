<?php
/**
 * Logout Handler
 * Student Attendance Tracking Portal
 */
session_start();
session_unset();
session_destroy();

// Clear browser sessionStorage and redirect to index.php
echo "<script>
    sessionStorage.removeItem('user_session');
    window.location.href = 'index.php';
</script>";
exit;
