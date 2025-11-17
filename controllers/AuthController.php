<?php
/**
 * Authentication Controller
 * Parliament Intern Logbook System
 */

class AuthController {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    /**
     * Display login form
     */
    public function index() {
        if (isLoggedIn()) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        include 'views/auth/login.php';
    }

    /**
     * Process login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            // Verify CSRF token
            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=login');
                exit;
            }

            if (empty($email) || empty($password)) {
                setFlashMessage('error', 'Please fill in all fields.');
                header('Location: index.php?page=login');
                exit;
            }

            try {
                $stmt = $this->db->prepare("SELECT id, name, email, password, role, is_active FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    if (!$user['is_active']) {
                        setFlashMessage('error', 'Your account has been deactivated. Please contact administrator.');
                        header('Location: index.php?page=login');
                        exit;
                    }

                    // Regenerate session ID to prevent fixation
                    session_regenerate_id(true);

                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    // Log login activity
                    logActivity($user['id'], 'login', 'User logged in');

                    // Redirect based on role
                    switch ($user['role']) {
                        case 'admin':
                            header('Location: index.php?page=dashboard');
                            break;
                        case 'supervisor':
                            header('Location: index.php?page=dashboard');
                            break;
                        case 'intern':
                            header('Location: index.php?page=dashboard');
                            break;
                        default:
                            header('Location: index.php?page=dashboard');
                    }
                    exit;
                } else {
                    setFlashMessage('error', 'Invalid email or password.');
                }
            } catch (Exception $e) {
                setFlashMessage('error', 'Login failed. Please try again.');
            }
        }

        header('Location: index.php?page=login');
        exit;
    }

    /**
     * Logout user
     */
    public function logout() {
        if (isLoggedIn()) {
            logActivity($_SESSION['user_id'], 'logout', 'User logged out');
        }

        // Clear all session variables
        $_SESSION = array();

        // Delete the session cookie if it exists
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        // Destroy the session
        session_destroy();

        // Redirect to login
        header('Location: index.php?page=login');
        exit;
    }

    /**
     * Change password
     */
    public function changePassword() {
        requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=dashboard');
                exit;
            }

            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                setFlashMessage('error', 'Please fill in all fields.');
                header('Location: index.php?page=dashboard');
                exit;
            }

            if ($new_password !== $confirm_password) {
                setFlashMessage('error', 'New passwords do not match.');
                header('Location: index.php?page=dashboard');
                exit;
            }

            if (strlen($new_password) < 6) {
                setFlashMessage('error', 'Password must be at least 6 characters long.');
                header('Location: index.php?page=dashboard');
                exit;
            }

            try {
                // Verify current password
                $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();

                if (!password_verify($current_password, $user['password'])) {
                    setFlashMessage('error', 'Current password is incorrect.');
                    header('Location: index.php?page=dashboard');
                    exit;
                }

                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $_SESSION['user_id']]);

                logActivity($_SESSION['user_id'], 'password_change', 'Password changed');
                setFlashMessage('success', 'Password changed successfully.');
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to change password. Please try again.');
            }
        }

        header('Location: index.php?page=dashboard');
        exit;
    }
}
?>
