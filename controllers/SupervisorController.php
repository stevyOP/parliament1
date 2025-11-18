<?php
/**
 * Supervisor Controller
 * Parliament Intern Logbook System
 */

class SupervisorController {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    /**
     * Display supervisor dashboard (redirected from main dashboard)
     */
    public function index() {
        $this->interns();
    }

    /**
     * Display assigned interns
     */
    public function interns() {
        requireRole('supervisor');
        
        $user_id = $_SESSION['user_id'];
        
        try {
            $stmt = $this->db->prepare("
                SELECT ip.*, u.name as intern_name, u.email as intern_email,
                       COUNT(DISTINCT dl.id) as total_logs,
                       COUNT(DISTINCT CASE WHEN dl.status = 'pending' THEN dl.id END) as pending_logs,
                       COUNT(DISTINCT CASE WHEN dl.status = 'approved' THEN dl.id END) as approved_logs,
                       COUNT(DISTINCT e.id) as total_evaluations,
                       AVG(e.rating_technical) as avg_technical,
                       AVG(e.rating_softskills) as avg_softskills
                FROM intern_profiles ip
                JOIN users u ON ip.user_id = u.id
                LEFT JOIN daily_logs dl ON ip.user_id = dl.intern_id
                LEFT JOIN evaluations e ON ip.user_id = e.intern_id
                WHERE ip.supervisor_id = ?
                GROUP BY ip.id, ip.user_id, ip.department, ip.supervisor_id, ip.start_date, ip.end_date, ip.status, ip.created_at, u.name, u.email
                ORDER BY ip.status, u.name
            ");
            $stmt->execute([$user_id]);
            $interns = $stmt->fetchAll();
            
            include 'views/supervisor/interns.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load interns.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Display logs to review
     */
    public function logs() {
        requireRole('supervisor');
        
        $user_id = $_SESSION['user_id'];
        $status = $_GET['status'] ?? 'all';
        $intern_id = $_GET['intern_id'] ?? null;
        
        try {
            $sql = "
                SELECT dl.*, u.name as intern_name, u.email as intern_email
                FROM daily_logs dl
                JOIN users u ON dl.intern_id = u.id
                JOIN intern_profiles ip ON dl.intern_id = ip.user_id
                WHERE ip.supervisor_id = ?
            ";
            
            $params = [$user_id];
            
            if ($status !== 'all') {
                $sql .= " AND dl.status = ?";
                $params[] = $status;
            }
            
            if ($intern_id) {
                $sql .= " AND dl.intern_id = ?";
                $params[] = $intern_id;
            }
            
            $sql .= " ORDER BY dl.date DESC, dl.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $logs = $stmt->fetchAll();
            
            include 'views/supervisor/logs.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load logs.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Review a specific log
     */
    public function reviewLog() {
        requireRole('supervisor');
        
        $log_id = $_GET['id'] ?? 0;
        $user_id = $_SESSION['user_id'];
        
        try {
            $stmt = $this->db->prepare("
                SELECT dl.*, u.name as intern_name, u.email as intern_email
                FROM daily_logs dl
                JOIN users u ON dl.intern_id = u.id
                JOIN intern_profiles ip ON dl.intern_id = ip.user_id
                WHERE dl.id = ? AND ip.supervisor_id = ?
            ");
            $stmt->execute([$log_id, $user_id]);
            $log = $stmt->fetch();

            if (!$log) {
                setFlashMessage('error', 'Log not found.');
                header('Location: index.php?page=supervisor&action=logs');
                exit;
            }

            include 'views/supervisor/review_log.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load log.');
            header('Location: index.php?page=supervisor&action=logs');
            exit;
        }
    }

    /**
     * Process log review
     */
    public function processReview() {
        requireRole('supervisor');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $log_id = $_POST['log_id'] ?? 0;
            $status = sanitize($_POST['status'] ?? '');
            $comment = sanitize($_POST['comment'] ?? '');
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=supervisor&action=logs');
                exit;
            }

            if (!in_array($status, ['approved', 'rejected'])) {
                setFlashMessage('error', 'Invalid status selected.');
                header('Location: index.php?page=supervisor&action=reviewLog&id=' . $log_id);
                exit;
            }

            try {
                // Check if log exists and belongs to supervisor
                $stmt = $this->db->prepare("
                    SELECT dl.* FROM daily_logs dl
                    JOIN intern_profiles ip ON dl.intern_id = ip.user_id
                    WHERE dl.id = ? AND ip.supervisor_id = ?
                ");
                $stmt->execute([$log_id, $_SESSION['user_id']]);
                $log = $stmt->fetch();

                if (!$log) {
                    setFlashMessage('error', 'Log not found.');
                    header('Location: index.php?page=supervisor&action=logs');
                    exit;
                }

                // Update log status and comment
                $stmt = $this->db->prepare("
                    UPDATE daily_logs 
                    SET status = ?, supervisor_comment = ?, updated_at = NOW() 
                    WHERE id = ?
                ");
                $stmt->execute([$status, $comment, $log_id]);

                logActivity($_SESSION['user_id'], 'log_reviewed', "Reviewed log for " . $log['date'] . " - Status: $status");
                setFlashMessage('success', 'Log review submitted successfully.');
                header('Location: index.php?page=supervisor&action=logs');
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to process review. Please try again.');
                header('Location: index.php?page=supervisor&action=reviewLog&id=' . $log_id);
                exit;
            }
        }

        header('Location: index.php?page=supervisor&action=logs');
        exit;
    }

    /**
     * Display evaluations
     */
    public function evaluations() {
        requireRole('supervisor');
        
        $user_id = $_SESSION['user_id'];
        
        try {
            $stmt = $this->db->prepare("
                SELECT e.*, u.name as intern_name, u.email as intern_email
                FROM evaluations e
                JOIN users u ON e.intern_id = u.id
                JOIN intern_profiles ip ON e.intern_id = ip.user_id
                WHERE ip.supervisor_id = ?
                ORDER BY e.week_no DESC, e.created_at DESC
            ");
            $stmt->execute([$user_id]);
            $evaluations = $stmt->fetchAll();
            
            include 'views/supervisor/evaluations.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load evaluations.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Display add evaluation form
     */
    public function addEvaluation() {
        requireRole('supervisor');
        
        $user_id = $_SESSION['user_id'];
        
        try {
            // Get assigned interns
            $stmt = $this->db->prepare("
                SELECT ip.*, u.name as intern_name
                FROM intern_profiles ip
                JOIN users u ON ip.user_id = u.id
                WHERE ip.supervisor_id = ? AND ip.status = 'active'
                ORDER BY u.name
            ");
            $stmt->execute([$user_id]);
            $interns = $stmt->fetchAll();
            
            include 'views/supervisor/add_evaluation.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load interns.');
            header('Location: index.php?page=supervisor&action=evaluations');
            exit;
        }
    }

    /**
     * Process add evaluation
     */
    public function createEvaluation() {
        requireRole('supervisor');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $intern_id = $_POST['intern_id'] ?? 0;
            $week_no = $_POST['week_no'] ?? 0;
            $rating_technical = $_POST['rating_technical'] ?? 0;
            $rating_softskills = $_POST['rating_softskills'] ?? 0;
            $comments = sanitize($_POST['comments'] ?? '');
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=supervisor&action=addEvaluation');
                exit;
            }

            if (empty($intern_id) || empty($week_no) || empty($rating_technical) || empty($rating_softskills)) {
                setFlashMessage('error', 'Please fill in all required fields.');
                header('Location: index.php?page=supervisor&action=addEvaluation');
                exit;
            }

            if ($rating_technical < 1 || $rating_technical > 5 || $rating_softskills < 1 || $rating_softskills > 5) {
                setFlashMessage('error', 'Ratings must be between 1 and 5.');
                header('Location: index.php?page=supervisor&action=addEvaluation');
                exit;
            }

            try {
                // Check if intern belongs to supervisor
                $stmt = $this->db->prepare("
                    SELECT id FROM intern_profiles 
                    WHERE user_id = ? AND supervisor_id = ?
                ");
                $stmt->execute([$intern_id, $_SESSION['user_id']]);
                if (!$stmt->fetch()) {
                    setFlashMessage('error', 'Invalid intern selected.');
                    header('Location: index.php?page=supervisor&action=addEvaluation');
                    exit;
                }

                // Check if evaluation already exists for this week
                $stmt = $this->db->prepare("
                    SELECT id FROM evaluations 
                    WHERE intern_id = ? AND week_no = ?
                ");
                $stmt->execute([$intern_id, $week_no]);
                if ($stmt->fetch()) {
                    setFlashMessage('error', 'Evaluation already exists for this week.');
                    header('Location: index.php?page=supervisor&action=addEvaluation');
                    exit;
                }

                // Insert evaluation
                $stmt = $this->db->prepare("
                    INSERT INTO evaluations (intern_id, week_no, rating_technical, rating_softskills, comments, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$intern_id, $week_no, $rating_technical, $rating_softskills, $comments]);

                logActivity($_SESSION['user_id'], 'evaluation_created', "Created evaluation for intern $intern_id, week $week_no");
                setFlashMessage('success', 'Evaluation added successfully.');
                header('Location: index.php?page=supervisor&action=evaluations');
                exit;
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to add evaluation. Please try again.');
                header('Location: index.php?page=supervisor&action=addEvaluation');
                exit;
            }
        }

        header('Location: index.php?page=supervisor&action=addEvaluation');
        exit;
    }

    /**
     * View specific evaluation
     */
    public function viewEvaluation() {
        requireRole('supervisor');
        
        $evaluation_id = $_GET['id'] ?? 0;
        $user_id = $_SESSION['user_id'];
        
        try {
            $stmt = $this->db->prepare("
                SELECT e.*, u.name as intern_name, u.email as intern_email
                FROM evaluations e
                JOIN users u ON e.intern_id = u.id
                JOIN intern_profiles ip ON e.intern_id = ip.user_id
                WHERE e.id = ? AND ip.supervisor_id = ?
            ");
            $stmt->execute([$evaluation_id, $user_id]);
            $evaluation = $stmt->fetch();

            if (!$evaluation) {
                setFlashMessage('error', 'Evaluation not found.');
                header('Location: index.php?page=supervisor&action=evaluations');
                exit;
            }

            include 'views/supervisor/view_evaluation.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load evaluation.');
            header('Location: index.php?page=supervisor&action=evaluations');
            exit;
        }
    }

    /**
     * Generate reports
     */
    public function reports() {
        requireRole('supervisor');
        
        $user_id = $_SESSION['user_id'];
        
        try {
            // Get supervisor's interns with their performance data
            $stmt = $this->db->prepare("
                SELECT 
                    ip.*, 
                    u.name as intern_name, 
                    u.email as intern_email,
                    COUNT(dl.id) as total_logs,
                    COUNT(CASE WHEN dl.status = 'approved' THEN 1 END) as approved_logs,
                    COUNT(CASE WHEN dl.status = 'pending' THEN 1 END) as pending_logs,
                    AVG(e.rating_technical) as avg_technical,
                    AVG(e.rating_softskills) as avg_softskills,
                    COUNT(e.id) as total_evaluations
                FROM intern_profiles ip
                JOIN users u ON ip.user_id = u.id
                LEFT JOIN daily_logs dl ON ip.user_id = dl.intern_id
                LEFT JOIN evaluations e ON ip.user_id = e.intern_id
                WHERE ip.supervisor_id = ?
                GROUP BY ip.id
                ORDER BY u.name
            ");
            $stmt->execute([$user_id]);
            $interns = $stmt->fetchAll();
            
            include 'views/supervisor/reports.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load reports.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
}
?>


