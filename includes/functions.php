<?php
/**
 * Common Functions
 * Parliament Intern Logbook System
 */

/**
 * Sanitize input data
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check user role
 */
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

/**
 * Redirect to login if not authenticated
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
}

/**
 * Redirect if user doesn't have required role
 */
function requireRole($role) {
    requireAuth();
    if (!hasRole($role)) {
        header('Location: index.php?page=dashboard');
        exit;
    }
}

/**
 * Format date for display
 */
function formatDate($date, $format = 'Y-m-d') {
    return date($format, strtotime($date));
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Flash message system
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function getFlashMessage($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

/**
 * Log activity
 */
function logActivity($user_id, $action, $details = '') {
    global $db;
    try {
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $action, $details]);
    } catch (Exception $e) {
        // Log error silently
    }
}

/**
 * Generate random password
 */
function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

/**
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Get user's full name by ID
 */
function getUserName($user_id) {
    global $db;
    try {
        $stmt = $db->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        return $user ? $user['name'] : 'Unknown User';
    } catch (Exception $e) {
        return 'Unknown User';
    }
}

/**
 * Get department name by ID
 */
function getDepartmentName($dept_id) {
    $departments = [
        1 => 'Information Technology',
        2 => 'Human Resources',
        3 => 'Finance',
        4 => 'Legal Affairs',
        5 => 'Public Relations',
        6 => 'Research & Development'
    ];
    return $departments[$dept_id] ?? 'Unknown Department';
}
?>


