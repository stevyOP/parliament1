<?php
/**
 * Intern Controller
 * Parliament Intern Logbook System
 */

class InternController {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    /**
     * Display intern dashboard (redirected from main dashboard)
     */
    public function index() {
        $this->logs();
    }

    /**
     * Display all daily logs
     */
    public function logs() {
        requireRole('intern');
        
        $user_id = $_SESSION['user_id'];
        
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM daily_logs 
                WHERE intern_id = ? 
                ORDER BY date DESC, created_at DESC
            ");
            $stmt->execute([$user_id]);
            $logs = $stmt->fetchAll();
            
            include 'views/intern/logs.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load logs.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Display add log form
     */
    public function addLog() {
        requireRole('intern');
        include 'views/intern/add_log.php';
    }

    /**
     * Process add log
     */
    public function createLog() {
        requireRole('intern');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = sanitize($_POST['date'] ?? '');
            $task_description = sanitize($_POST['task_description'] ?? '');
            $skills = sanitize($_POST['skills'] ?? '');
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=intern&action=addLog');
                exit;
            }

            if (empty($date) || empty($task_description)) {
                setFlashMessage('error', 'Date and task description are required.');
                header('Location: index.php?page=intern&action=addLog');
                exit;
            }

            // Check if log already exists for this date
            try {
                $stmt = $this->db->prepare("SELECT id FROM daily_logs WHERE intern_id = ? AND date = ?");
                $stmt->execute([$_SESSION['user_id'], $date]);
                if ($stmt->fetch()) {
                    setFlashMessage('error', 'A log already exists for this date.');
                    header('Location: index.php?page=intern&action=addLog');
                    exit;
                }

                // Insert new log
                $stmt = $this->db->prepare("
                    INSERT INTO daily_logs (intern_id, date, task_description, skills, status, created_at) 
                    VALUES (?, ?, ?, ?, 'pending', NOW())
                ");
                $stmt->execute([$_SESSION['user_id'], $date, $task_description, $skills]);

                logActivity($_SESSION['user_id'], 'log_created', "Created daily log for $date");
                setFlashMessage('success', 'Daily log added successfully.');
                header('Location: index.php?page=intern&action=logs');
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to add log. Please try again.');
                header('Location: index.php?page=intern&action=addLog');
                exit;
            }
        }

        header('Location: index.php?page=intern&action=addLog');
        exit;
    }

    /**
     * Display edit log form
     */
    public function editLog() {
        requireRole('intern');
        
        $log_id = $_GET['id'] ?? 0;
        
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM daily_logs 
                WHERE id = ? AND intern_id = ? AND status = 'pending'
            ");
            $stmt->execute([$log_id, $_SESSION['user_id']]);
            $log = $stmt->fetch();

            if (!$log) {
                setFlashMessage('error', 'Log not found or cannot be edited.');
                header('Location: index.php?page=intern&action=logs');
                exit;
            }

            // Check if log is within 24 hours
            if (strtotime($log['created_at']) < (time() - 86400)) {
                setFlashMessage('error', 'Logs can only be edited within 24 hours of creation.');
                header('Location: index.php?page=intern&action=logs');
                exit;
            }

            include 'views/intern/edit_log.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load log.');
            header('Location: index.php?page=intern&action=logs');
            exit;
        }
    }

    /**
     * Process edit log
     */
    public function updateLog() {
        requireRole('intern');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $log_id = $_POST['log_id'] ?? 0;
            $date = sanitize($_POST['date'] ?? '');
            $task_description = sanitize($_POST['task_description'] ?? '');
            $skills = sanitize($_POST['skills'] ?? '');
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=intern&action=logs');
                exit;
            }

            if (empty($date) || empty($task_description)) {
                setFlashMessage('error', 'Date and task description are required.');
                header('Location: index.php?page=intern&action=editLog&id=' . $log_id);
                exit;
            }

            try {
                // Check if log exists and belongs to user
                $stmt = $this->db->prepare("
                    SELECT * FROM daily_logs 
                    WHERE id = ? AND intern_id = ? AND status = 'pending'
                ");
                $stmt->execute([$log_id, $_SESSION['user_id']]);
                $log = $stmt->fetch();

                if (!$log) {
                    setFlashMessage('error', 'Log not found or cannot be edited.');
                    header('Location: index.php?page=intern&action=logs');
                    exit;
                }

                // Check if log is within 24 hours
                if (strtotime($log['created_at']) < (time() - 86400)) {
                    setFlashMessage('error', 'Logs can only be edited within 24 hours of creation.');
                    header('Location: index.php?page=intern&action=logs');
                    exit;
                }

                // Update log
                $stmt = $this->db->prepare("
                    UPDATE daily_logs 
                    SET date = ?, task_description = ?, skills = ?, updated_at = NOW() 
                    WHERE id = ? AND intern_id = ?
                ");
                $stmt->execute([$date, $task_description, $skills, $log_id, $_SESSION['user_id']]);

                logActivity($_SESSION['user_id'], 'log_updated', "Updated daily log for $date");
                setFlashMessage('success', 'Daily log updated successfully.');
                header('Location: index.php?page=intern&action=logs');
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to update log. Please try again.');
                header('Location: index.php?page=intern&action=editLog&id=' . $log_id);
                exit;
            }
        }

        header('Location: index.php?page=intern&action=logs');
        exit;
    }

    /**
     * Delete log
     */
    public function deleteLog() {
        requireRole('intern');
        
        $log_id = $_GET['id'] ?? 0;
        
        try {
            // Check if log exists and belongs to user
            $stmt = $this->db->prepare("
                SELECT * FROM daily_logs 
                WHERE id = ? AND intern_id = ? AND status = 'pending'
            ");
            $stmt->execute([$log_id, $_SESSION['user_id']]);
            $log = $stmt->fetch();

            if (!$log) {
                setFlashMessage('error', 'Log not found or cannot be deleted.');
                header('Location: index.php?page=intern&action=logs');
                exit;
            }

            // Check if log is within 24 hours
            if (strtotime($log['created_at']) < (time() - 86400)) {
                setFlashMessage('error', 'Logs can only be deleted within 24 hours of creation.');
                header('Location: index.php?page=intern&action=logs');
                exit;
            }

            // Delete log
            $stmt = $this->db->prepare("DELETE FROM daily_logs WHERE id = ? AND intern_id = ?");
            $stmt->execute([$log_id, $_SESSION['user_id']]);

            logActivity($_SESSION['user_id'], 'log_deleted', "Deleted daily log for " . $log['date']);
            setFlashMessage('success', 'Daily log deleted successfully.');
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to delete log. Please try again.');
        }

        header('Location: index.php?page=intern&action=logs');
        exit;
    }

    /**
     * View single log
     */
    public function viewLog() {
        requireRole('intern');
        
        $log_id = $_GET['id'] ?? 0;
        
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM daily_logs 
                WHERE id = ? AND intern_id = ?
            ");
            $stmt->execute([$log_id, $_SESSION['user_id']]);
            $log = $stmt->fetch();

            if (!$log) {
                setFlashMessage('error', 'Log not found.');
                header('Location: index.php?page=intern&action=logs');
                exit;
            }

            include 'views/intern/view_log.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load log.');
            header('Location: index.php?page=intern&action=logs');
            exit;
        }
    }

    /**
     * View evaluations
     */
    public function evaluations() {
        requireRole('intern');
        
        $user_id = $_SESSION['user_id'];
        
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM evaluations 
                WHERE intern_id = ? 
                ORDER BY week_no DESC, created_at DESC
            ");
            $stmt->execute([$user_id]);
            $evaluations = $stmt->fetchAll();
            
            include 'views/intern/evaluations.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load evaluations.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Generate weekly PDF report
     */
    public function weeklyReport() {
        requireRole('intern');
        
        $user_id = $_SESSION['user_id'];
        $week_start = $_GET['week_start'] ?? date('Y-m-d', strtotime('monday this week'));
        $week_end = date('Y-m-d', strtotime($week_start . ' +6 days'));
        
        try {
            // Get logs for the week
            $stmt = $this->db->prepare("
                SELECT * FROM daily_logs 
                WHERE intern_id = ? AND date BETWEEN ? AND ?
                ORDER BY date ASC
            ");
            $stmt->execute([$user_id, $week_start, $week_end]);
            $logs = $stmt->fetchAll();

            // Get intern profile
            $stmt = $this->db->prepare("
                SELECT ip.*, u.name as intern_name, u.email as intern_email,
                       s.name as supervisor_name
                FROM intern_profiles ip
                JOIN users u ON ip.user_id = u.id
                JOIN users s ON ip.supervisor_id = s.id
                WHERE ip.user_id = ?
            ");
            $stmt->execute([$user_id]);
            $profile = $stmt->fetch();

            if (!$profile) {
                setFlashMessage('error', 'Profile not found.');
                header('Location: index.php?page=dashboard');
                exit;
            }

            // Generate PDF using simple HTML to PDF conversion
            $this->generateWeeklyPDF($profile, $logs, $week_start, $week_end);
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to generate report.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
    
    /**
     * View attendance/calendar
     */
    public function attendance() {
        requireRole('intern');
        
        $user_id = $_SESSION['user_id'];
        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');
        
        try {
            // Get all logs for the month
            $stmt = $this->db->prepare("
                SELECT * FROM daily_logs 
                WHERE intern_id = ? 
                AND MONTH(date) = ? 
                AND YEAR(date) = ?
                ORDER BY date ASC
            ");
            $stmt->execute([$user_id, $month, $year]);
            $logs = $stmt->fetchAll();
            
            // Get profile
            $stmt = $this->db->prepare("
                SELECT ip.*, u.name as supervisor_name
                FROM intern_profiles ip
                JOIN users u ON ip.supervisor_id = u.id
                WHERE ip.user_id = ?
            ");
            $stmt->execute([$user_id]);
            $profile = $stmt->fetch();
            
            include 'views/intern/attendance.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load attendance.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
    
    /**
     * View statistics
     */
    public function statistics() {
        requireRole('intern');
        
        $user_id = $_SESSION['user_id'];
        
        try {
            // Get all logs
            $stmt = $this->db->prepare("
                SELECT * FROM daily_logs 
                WHERE intern_id = ?
                ORDER BY date DESC
            ");
            $stmt->execute([$user_id]);
            $all_logs = $stmt->fetchAll();
            
            // Get all evaluations
            $stmt = $this->db->prepare("
                SELECT * FROM evaluations 
                WHERE intern_id = ?
                ORDER BY week_no ASC
            ");
            $stmt->execute([$user_id]);
            $all_evaluations = $stmt->fetchAll();
            
            // Calculate statistics
            $total_logs = count($all_logs);
            $approved_logs = count(array_filter($all_logs, function($log) { return $log['status'] === 'approved'; }));
            $pending_logs = count(array_filter($all_logs, function($log) { return $log['status'] === 'pending'; }));
            $rejected_logs = count(array_filter($all_logs, function($log) { return $log['status'] === 'rejected'; }));
            
            // Skills analysis
            $all_skills = [];
            foreach ($all_logs as $log) {
                if (!empty($log['skills'])) {
                    $skills = array_map('trim', explode(',', $log['skills']));
                    $all_skills = array_merge($all_skills, $skills);
                }
            }
            $skills_count = array_count_values($all_skills);
            arsort($skills_count);
            
            // Monthly breakdown
            $monthly_stats = [];
            foreach ($all_logs as $log) {
                $month_key = date('Y-m', strtotime($log['date']));
                if (!isset($monthly_stats[$month_key])) {
                    $monthly_stats[$month_key] = ['total' => 0, 'approved' => 0, 'pending' => 0, 'rejected' => 0];
                }
                $monthly_stats[$month_key]['total']++;
                $monthly_stats[$month_key][$log['status']]++;
            }
            
            // Get profile
            $stmt = $this->db->prepare("
                SELECT ip.*, u.name as supervisor_name
                FROM intern_profiles ip
                JOIN users u ON ip.supervisor_id = u.id
                WHERE ip.user_id = ?
            ");
            $stmt->execute([$user_id]);
            $profile = $stmt->fetch();
            
            include 'views/intern/statistics.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load statistics.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Generate PDF content
     */
    private function generateWeeklyPDF($profile, $logs, $week_start, $week_end) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Weekly Report - ' . $profile['intern_name'] . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { color: #840100; margin-bottom: 10px; }
                .header h2 { color: #840100; margin-bottom: 20px; }
                .info-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                .info-table td { padding: 8px; border: 1px solid #ddd; }
                .info-table td:first-child { background-color: #f8f9fa; font-weight: bold; width: 200px; }
                .logs-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                .logs-table th, .logs-table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
                .logs-table th { background-color: #840100; color: white; }
                .logs-table tr:nth-child(even) { background-color: #f8f9fa; }
                .summary { background-color: #e9ecef; padding: 20px; border-radius: 5px; }
                .footer { text-align: center; margin-top: 30px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Parliament of Sri Lanka</h1>
                <h2>Intern Weekly Report</h2>
                <p>Week of ' . date('F j, Y', strtotime($week_start)) . ' - ' . date('F j, Y', strtotime($week_end)) . '</p>
            </div>

            <table class="info-table">
                <tr><td>Intern Name:</td><td>' . htmlspecialchars($profile['intern_name']) . '</td></tr>
                <tr><td>Email:</td><td>' . htmlspecialchars($profile['intern_email']) . '</td></tr>
                <tr><td>Department:</td><td>' . getDepartmentName($profile['department']) . '</td></tr>
                <tr><td>Supervisor:</td><td>' . htmlspecialchars($profile['supervisor_name']) . '</td></tr>
                <tr><td>Report Period:</td><td>' . date('F j, Y', strtotime($week_start)) . ' - ' . date('F j, Y', strtotime($week_end)) . '</td></tr>
                <tr><td>Generated:</td><td>' . date('F j, Y g:i A') . '</td></tr>
            </table>

            <h3>Daily Logs</h3>
            <table class="logs-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Tasks Description</th>
                        <th>Skills Learned</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($logs as $log) {
            $html .= '
                    <tr>
                        <td>' . date('M j, Y', strtotime($log['date'])) . '</td>
                        <td>' . htmlspecialchars($log['task_description']) . '</td>
                        <td>' . htmlspecialchars($log['skills'] ?? 'N/A') . '</td>
                        <td>' . ucfirst($log['status']) . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="summary">
                <h3>Weekly Summary</h3>
                <p><strong>Total Logs Submitted:</strong> ' . count($logs) . '</p>
                <p><strong>Approved Logs:</strong> ' . count(array_filter($logs, function($log) { return $log['status'] === 'approved'; })) . '</p>
                <p><strong>Pending Logs:</strong> ' . count(array_filter($logs, function($log) { return $log['status'] === 'pending'; })) . '</p>
            </div>

            <div class="footer">
                <p>This report was generated automatically by the Parliament Intern Logbook System</p>
                <p>Parliament of Sri Lanka - ' . date('Y') . '</p>
            </div>
        </body>
        </html>';

        // For now, we'll output the HTML. In production, you'd use a proper PDF library like DomPDF
        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        exit;
    }
}
?>


