<?php
/**
 * Dashboard Controller
 * Parliament Intern Logbook System
 */

class DashboardController {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    /**
     * Display main dashboard
     */
    public function index() {
        requireAuth();
        
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        
        $data = [
            'user_name' => $_SESSION['name'],
            'role' => $role
        ];

        // Get role-specific dashboard data
        switch ($role) {
            case 'admin':
                $data = array_merge($data, $this->getAdminDashboardData());
                include 'views/dashboard/admin_dashboard.php';
                break;
            case 'supervisor':
                $data = array_merge($data, $this->getSupervisorDashboardData($user_id));
                include 'views/dashboard/supervisor_dashboard.php';
                break;
            case 'intern':
                $data = array_merge($data, $this->getInternDashboardData($user_id));
                include 'views/dashboard/intern_dashboard.php';
                break;
            default:
                header('Location: index.php?page=login');
                exit;
        }
    }

    /**
     * Get admin dashboard data
     */
    private function getAdminDashboardData() {
        try {
            // Total users by role
            $stmt = $this->db->prepare("
                SELECT role, COUNT(*) as count 
                FROM users 
                WHERE is_active = 1 
                GROUP BY role
            ");
            $stmt->execute();
            $users_by_role = $stmt->fetchAll();

            // Active interns
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM intern_profiles 
                WHERE status = 'active'
            ");
            $stmt->execute();
            $active_interns = $stmt->fetch()['count'];

            // Logs this week
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM daily_logs 
                WHERE date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
            ");
            $stmt->execute();
            $logs_this_week = $stmt->fetch()['count'];

            // Pending reviews
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM daily_logs 
                WHERE status = 'pending'
            ");
            $stmt->execute();
            $pending_reviews = $stmt->fetch()['count'];

            // Recent announcements
            $stmt = $this->db->prepare("
                SELECT a.*, u.name as created_by_name 
                FROM announcements a 
                JOIN users u ON a.created_by = u.id 
                ORDER BY a.date_created DESC 
                LIMIT 5
            ");
            $stmt->execute();
            $recent_announcements = $stmt->fetchAll();

            return [
                'users_by_role' => $users_by_role,
                'active_interns' => $active_interns,
                'logs_this_week' => $logs_this_week,
                'pending_reviews' => $pending_reviews,
                'recent_announcements' => $recent_announcements
            ];
        } catch (Exception $e) {
            return [
                'users_by_role' => [],
                'active_interns' => 0,
                'logs_this_week' => 0,
                'pending_reviews' => 0,
                'recent_announcements' => []
            ];
        }
    }

    /**
     * Get supervisor dashboard data
     */
    private function getSupervisorDashboardData($user_id) {
        try {
            // Assigned interns
            $stmt = $this->db->prepare("
                SELECT ip.*, u.name as intern_name, u.email as intern_email
                FROM intern_profiles ip
                JOIN users u ON ip.user_id = u.id
                WHERE ip.supervisor_id = ? AND ip.status = 'active'
            ");
            $stmt->execute([$user_id]);
            $assigned_interns = $stmt->fetchAll();

            // Pending reviews
            $stmt = $this->db->prepare("
                SELECT dl.*, u.name as intern_name
                FROM daily_logs dl
                JOIN users u ON dl.intern_id = u.id
                JOIN intern_profiles ip ON dl.intern_id = ip.user_id
                WHERE ip.supervisor_id = ? AND dl.status = 'pending'
                ORDER BY dl.date DESC
                LIMIT 10
            ");
            $stmt->execute([$user_id]);
            $pending_reviews = $stmt->fetchAll();

            // Recent evaluations
            $stmt = $this->db->prepare("
                SELECT e.*, u.name as intern_name
                FROM evaluations e
                JOIN users u ON e.intern_id = u.id
                JOIN intern_profiles ip ON e.intern_id = ip.user_id
                WHERE ip.supervisor_id = ?
                ORDER BY e.created_at DESC
                LIMIT 5
            ");
            $stmt->execute([$user_id]);
            $recent_evaluations = $stmt->fetchAll();

            return [
                'assigned_interns' => $assigned_interns,
                'pending_reviews' => $pending_reviews,
                'recent_evaluations' => $recent_evaluations
            ];
        } catch (Exception $e) {
            return [
                'assigned_interns' => [],
                'pending_reviews' => [],
                'recent_evaluations' => []
            ];
        }
    }

    /**
     * Get intern dashboard data
     */
    private function getInternDashboardData($user_id) {
        try {
            // Recent logs
            $stmt = $this->db->prepare("
                SELECT * FROM daily_logs 
                WHERE intern_id = ? 
                ORDER BY date DESC 
                LIMIT 10
            ");
            $stmt->execute([$user_id]);
            $recent_logs = $stmt->fetchAll();

            // Logs this week
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM daily_logs 
                WHERE intern_id = ? 
                AND date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
            ");
            $stmt->execute([$user_id]);
            $logs_this_week = $stmt->fetch()['count'];

            // Pending logs
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM daily_logs 
                WHERE intern_id = ? AND status = 'pending'
            ");
            $stmt->execute([$user_id]);
            $pending_logs = $stmt->fetch()['count'];

            // Recent evaluations
            $stmt = $this->db->prepare("
                SELECT * FROM evaluations 
                WHERE intern_id = ? 
                ORDER BY created_at DESC 
                LIMIT 5
            ");
            $stmt->execute([$user_id]);
            $recent_evaluations = $stmt->fetchAll();

            // Get intern profile
            $stmt = $this->db->prepare("
                SELECT ip.*, u.name as supervisor_name
                FROM intern_profiles ip
                JOIN users u ON ip.supervisor_id = u.id
                WHERE ip.user_id = ?
            ");
            $stmt->execute([$user_id]);
            $profile = $stmt->fetch();

            return [
                'recent_logs' => $recent_logs,
                'logs_this_week' => $logs_this_week,
                'pending_logs' => $pending_logs,
                'recent_evaluations' => $recent_evaluations,
                'profile' => $profile
            ];
        } catch (Exception $e) {
            return [
                'recent_logs' => [],
                'logs_this_week' => 0,
                'pending_logs' => 0,
                'recent_evaluations' => [],
                'profile' => null
            ];
        }
    }
}
?>


