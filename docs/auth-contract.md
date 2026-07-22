# Authentication & Session Contract

This document outlines the session-variable contract for the Student Attendance Tracking Portal. All team members must adhere to this contract to ensure consistent authorization and user context across the application.

## Session Variables

Upon successful login, the following variables MUST be populated in the global `$_SESSION` array:

| Key | Type | Allowed Values | Description |
| :--- | :--- | :--- | :--- |
| `$_SESSION['user_id']` | `int` / `string` | Unique identifier (e.g., database primary key) | The unique ID of the authenticated user. |
| `$_SESSION['role']` | `string` | `'admin'`, `'faculty'`, `'student'` | The access control role of the user, determining their permissions and dashboard redirection. |
| `$_SESSION['name']` | `string` | Display name of the user (e.g., "John Doe") | The user's full name, to be displayed in the UI / navigation bar. |

## Session Lifecycle

### 1. Initialization (Login)
- These session variables are set immediately after the user's credentials are verified in `auth/login.php`.
- Before setting any variables, `session_start()` must be called, and the session ID should be regenerated using `session_regenerate_id(true)` to prevent session fixation attacks.

### 2. Verification (Auth Guard)
- The helper script `includes/auth_guard.php` will inspect these variables to restrict access to pages based on authentication status and user roles.

### 3. Destruction (Logout)
- On logout (`auth/logout.php`), all session variables must be cleared and the session fully destroyed:
  ```php
  session_start();
  $_SESSION = array(); // Clear all session variables
  if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
  }
  session_destroy(); // Destroy the session on the server
  ```
