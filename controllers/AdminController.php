<?php
/**
 * Admin Controller
 * Parliament Intern Logbook System
 */

class AdminController {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    /**
     * Display admin dashboard (redirected from main dashboard)
     */
    public function index() {
        header('Location: index.php?page=dashboard');
        exit;
    }

    /**
     * Display all users
     */
    public function users() {
        requireRole('admin');
        
        try {
            $stmt = $this->db->prepare("
                SELECT u.*, ip.department, ip.status as intern_status, 
                       s.name as supervisor_name,
                       COUNT(dl.id) as total_logs,
                       COUNT(e.id) as total_evaluations
                FROM users u
                LEFT JOIN intern_profiles ip ON u.id = ip.user_id
                LEFT JOIN users s ON ip.supervisor_id = s.id
                LEFT JOIN daily_logs dl ON u.id = dl.intern_id
                LEFT JOIN evaluations e ON u.id = e.intern_id
                GROUP BY u.id
                ORDER BY u.role, u.name
            ");
            $stmt->execute();
            $users = $stmt->fetchAll();
            
            include 'views/admin/users.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load users.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Display add user form
     */
    public function addUser() {
        requireRole('admin');
        
        try {
            // Get supervisors for intern assignment
            $stmt = $this->db->prepare("
                SELECT id, name FROM users 
                WHERE role = 'supervisor' AND is_active = 1 
                ORDER BY name
            ");
            $stmt->execute();
            $supervisors = $stmt->fetchAll();
            
            include 'views/admin/add_user.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load supervisors.');
            header('Location: index.php?page=admin&action=users');
            exit;
        }
    }

    /**
     * Process add user
     */
    public function createUser() {
        requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = sanitize($_POST['role'] ?? '');
            $department = $_POST['department'] ?? 0;
            $supervisor_id = $_POST['supervisor_id'] ?? 0;
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=admin&action=addUser');
                exit;
            }

            if (empty($name) || empty($email) || empty($password) || empty($role)) {
                setFlashMessage('error', 'Please fill in all required fields.');
                header('Location: index.php?page=admin&action=addUser');
                exit;
            }

            if (!isValidEmail($email)) {
                setFlashMessage('error', 'Please enter a valid email address.');
                header('Location: index.php?page=admin&action=addUser');
                exit;
            }

            if (strlen($password) < 6) {
                setFlashMessage('error', 'Password must be at least 6 characters long.');
                header('Location: index.php?page=admin&action=addUser');
                exit;
            }

            try {
                // Check if email already exists
                $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    setFlashMessage('error', 'Email address already exists.');
                    header('Location: index.php?page=admin&action=addUser');
                    exit;
                }

                // Insert user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("
                    INSERT INTO users (name, email, password, role, is_active, created_at) 
                    VALUES (?, ?, ?, ?, 1, NOW())
                ");
                $stmt->execute([$name, $email, $hashed_password, $role]);
                $user_id = $this->db->lastInsertId();

                // If intern, create intern profile
                if ($role === 'intern' && $supervisor_id && $start_date && $end_date) {
                    $stmt = $this->db->prepare("
                        INSERT INTO intern_profiles (user_id, department, supervisor_id, start_date, end_date, status) 
                        VALUES (?, ?, ?, ?, ?, 'active')
                    ");
                    $stmt->execute([$user_id, $department, $supervisor_id, $start_date, $end_date]);
                }

                logActivity($_SESSION['user_id'], 'user_created', "Created user: $name ($role)");
                setFlashMessage('success', 'User created successfully.');
                header('Location: index.php?page=admin&action=users');
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to create user. Please try again.');
                header('Location: index.php?page=admin&action=addUser');
                exit;
            }
        }

        header('Location: index.php?page=admin&action=addUser');
        exit;
    }

    /**
     * Edit user
     */
    public function editUser() {
        requireRole('admin');
        
        $user_id = $_GET['id'] ?? 0;
        
        try {
            $stmt = $this->db->prepare("
                SELECT u.*, ip.department, ip.supervisor_id, ip.start_date, ip.end_date, ip.status as intern_status
                FROM users u
                LEFT JOIN intern_profiles ip ON u.id = ip.user_id
                WHERE u.id = ?
            ");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            if (!$user) {
                setFlashMessage('error', 'User not found.');
                header('Location: index.php?page=admin&action=users');
                exit;
            }

            // Get supervisors for intern assignment
            $stmt = $this->db->prepare("
                SELECT id, name FROM users 
                WHERE role = 'supervisor' AND is_active = 1 
                ORDER BY name
            ");
            $stmt->execute();
            $supervisors = $stmt->fetchAll();

            include 'views/admin/edit_user.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load user.');
            header('Location: index.php?page=admin&action=users');
            exit;
        }
    }

    /**
     * Process edit user
     */
    public function updateUser() {
        requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'] ?? 0;
            $name = sanitize($_POST['name'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $role = sanitize($_POST['role'] ?? '');
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $department = $_POST['department'] ?? 0;
            $supervisor_id = $_POST['supervisor_id'] ?? 0;
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=admin&action=users');
                exit;
            }

            if (empty($name) || empty($email) || empty($role)) {
                setFlashMessage('error', 'Please fill in all required fields.');
                header('Location: index.php?page=admin&action=editUser&id=' . $user_id);
                exit;
            }

            if (!isValidEmail($email)) {
                setFlashMessage('error', 'Please enter a valid email address.');
                header('Location: index.php?page=admin&action=editUser&id=' . $user_id);
                exit;
            }

            try {
                // Check if email already exists for another user
                $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$email, $user_id]);
                if ($stmt->fetch()) {
                    setFlashMessage('error', 'Email address already exists.');
                    header('Location: index.php?page=admin&action=editUser&id=' . $user_id);
                    exit;
                }

                // Update user
                $stmt = $this->db->prepare("
                    UPDATE users 
                    SET name = ?, email = ?, role = ?, is_active = ?, updated_at = NOW() 
                    WHERE id = ?
                ");
                $stmt->execute([$name, $email, $role, $is_active, $user_id]);

                // Update intern profile if applicable
                if ($role === 'intern' && $supervisor_id && $start_date && $end_date) {
                    $stmt = $this->db->prepare("
                        INSERT INTO intern_profiles (user_id, department, supervisor_id, start_date, end_date, status) 
                        VALUES (?, ?, ?, ?, ?, 'active')
                        ON DUPLICATE KEY UPDATE 
                        department = VALUES(department),
                        supervisor_id = VALUES(supervisor_id),
                        start_date = VALUES(start_date),
                        end_date = VALUES(end_date)
                    ");
                    $stmt->execute([$user_id, $department, $supervisor_id, $start_date, $end_date]);
                }

                logActivity($_SESSION['user_id'], 'user_updated', "Updated user: $name ($role)");
                setFlashMessage('success', 'User updated successfully.');
                header('Location: index.php?page=admin&action=users');
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to update user. Please try again.');
                header('Location: index.php?page=admin&action=editUser&id=' . $user_id);
                exit;
            }
        }

        header('Location: index.php?page=admin&action=users');
        exit;
    }

    /**
     * Delete user
     */
    public function deleteUser() {
        requireRole('admin');
        
        $user_id = $_GET['id'] ?? 0;
        
        if ($user_id == $_SESSION['user_id']) {
            setFlashMessage('error', 'You cannot delete your own account.');
            header('Location: index.php?page=admin&action=users');
            exit;
        }

        try {
            // Get user info for logging
            $stmt = $this->db->prepare("SELECT name, role FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            if (!$user) {
                setFlashMessage('error', 'User not found.');
                header('Location: index.php?page=admin&action=users');
                exit;
            }

            // Delete user (cascade will handle related records)
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);

            logActivity($_SESSION['user_id'], 'user_deleted', "Deleted user: " . $user['name'] . " (" . $user['role'] . ")");
            setFlashMessage('success', 'User deleted successfully.');
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to delete user. Please try again.');
        }

        header('Location: index.php?page=admin&action=users');
        exit;
    }

    /**
     * Display announcements
     */
    public function announcements() {
        requireRole('admin');
        
        try {
            $stmt = $this->db->prepare("
                SELECT a.*, u.name as created_by_name 
                FROM announcements a 
                JOIN users u ON a.created_by = u.id 
                ORDER BY a.date_created DESC
            ");
            $stmt->execute();
            $announcements = $stmt->fetchAll();
            
            include 'views/admin/announcements.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load announcements.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Add announcement
     */
    public function addAnnouncement() {
        requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = sanitize($_POST['title'] ?? '');
            $message = sanitize($_POST['message'] ?? '');
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=admin&action=announcements');
                exit;
            }

            if (empty($title) || empty($message)) {
                setFlashMessage('error', 'Please fill in all fields.');
                header('Location: index.php?page=admin&action=announcements');
                exit;
            }

            try {
                $stmt = $this->db->prepare("
                    INSERT INTO announcements (title, message, created_by, date_created) 
                    VALUES (?, ?, ?, NOW())
                ");
                $stmt->execute([$title, $message, $_SESSION['user_id']]);

                logActivity($_SESSION['user_id'], 'announcement_created', "Created announcement: $title");
                setFlashMessage('success', 'Announcement created successfully.');
                header('Location: index.php?page=admin&action=announcements');
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to create announcement. Please try again.');
                header('Location: index.php?page=admin&action=announcements');
                exit;
            }
        }

        header('Location: index.php?page=admin&action=announcements');
        exit;
    }

    /**
     * Export data
     */
    public function export() {
        requireRole('admin');
        
        $format = $_GET['format'] ?? 'csv';
        $type = $_GET['type'] ?? 'logs';
        
        try {
            switch ($type) {
                case 'logs':
                    $this->exportLogs($format);
                    break;
                case 'users':
                    $this->exportUsers($format);
                    break;
                case 'evaluations':
                    $this->exportEvaluations($format);
                    break;
                default:
                    setFlashMessage('error', 'Invalid export type.');
                    header('Location: index.php?page=dashboard');
                    exit;
            }
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to export data.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Export logs
     */
    private function exportLogs($format) {
        $stmt = $this->db->prepare("
            SELECT dl.*, u.name as intern_name, u.email as intern_email,
                   s.name as supervisor_name
            FROM daily_logs dl
            JOIN users u ON dl.intern_id = u.id
            LEFT JOIN intern_profiles ip ON dl.intern_id = ip.user_id
            LEFT JOIN users s ON ip.supervisor_id = s.id
            ORDER BY dl.date DESC
        ");
        $stmt->execute();
        $logs = $stmt->fetchAll();

        if ($format === 'csv') {
            $this->exportCSV($logs, 'daily_logs', [
                'Date', 'Intern Name', 'Intern Email', 'Supervisor', 'Task Description', 
                'Skills', 'Status', 'Supervisor Comment', 'Created At'
            ]);
        }
    }

    /**
     * Export users
     */
    private function exportUsers($format) {
        $stmt = $this->db->prepare("
            SELECT u.*, ip.department, ip.start_date, ip.end_date, ip.status as intern_status,
                   s.name as supervisor_name
            FROM users u
            LEFT JOIN intern_profiles ip ON u.id = ip.user_id
            LEFT JOIN users s ON ip.supervisor_id = s.id
            ORDER BY u.role, u.name
        ");
        $stmt->execute();
        $users = $stmt->fetchAll();

        if ($format === 'csv') {
            $this->exportCSV($users, 'users', [
                'Name', 'Email', 'Role', 'Department', 'Supervisor', 
                'Start Date', 'End Date', 'Status', 'Active', 'Created At'
            ]);
        }
    }

    /**
     * Export evaluations
     */
    private function exportEvaluations($format) {
        $stmt = $this->db->prepare("
            SELECT e.*, u.name as intern_name, u.email as intern_email,
                   s.name as supervisor_name
            FROM evaluations e
            JOIN users u ON e.intern_id = u.id
            LEFT JOIN intern_profiles ip ON e.intern_id = ip.user_id
            LEFT JOIN users s ON ip.supervisor_id = s.id
            ORDER BY e.week_no DESC, e.created_at DESC
        ");
        $stmt->execute();
        $evaluations = $stmt->fetchAll();

        if ($format === 'csv') {
            $this->exportCSV($evaluations, 'evaluations', [
                'Week', 'Intern Name', 'Intern Email', 'Supervisor', 
                'Technical Rating', 'Soft Skills Rating', 'Comments', 'Created At'
            ]);
        }
    }

    /**
     * Export CSV
     */
    private function exportCSV($data, $filename, $headers) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        
        foreach ($data as $row) {
            fputcsv($output, array_values($row));
        }
        
        fclose($output);
        exit;
    }
}
?>


