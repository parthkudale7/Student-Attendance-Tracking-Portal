<?php
/**
 * Logout Handler
 * Student Attendance Tracking Portal
 */
session_start();
session_unset();
session_destroy();

header("Location: login.php");
exit;
