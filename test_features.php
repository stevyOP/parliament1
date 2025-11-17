<?php
/**
 * Quick Test Script for Intern Dashboard Features
 * Access: http://localhost/parliament1/test_features.php
 */

session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Check if logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'intern') {
    echo "<h1>Error: Please login as an intern first</h1>";
    echo "<a href='index.php?page=login'>Go to Login</a>";
    exit;
}

echo "<!DOCTYPE html><html><head>";
echo "<title>Feature Test</title>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .section{border:1px solid #ddd;padding:15px;margin:10px 0;} h2{background:#840100;color:white;padding:10px;}</style>";
echo "</head><body>";

echo "<h1>🧪 Intern Dashboard Feature Test</h1>";
echo "<p><strong>User:</strong> " . htmlspecialchars($_SESSION['name']) . " (ID: " . $_SESSION['user_id'] . ")</p>";
echo "<hr>";

$db = Database::getInstance()->getConnection();
$user_id = $_SESSION['user_id'];

// Test 1: Dashboard Data
echo "<div class='section'>";
echo "<h2>Test 1: Dashboard Statistics</h2>";
try {
    // Logs this week
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM daily_logs WHERE intern_id = ? AND date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)");
    $stmt->execute([$user_id]);
    $logs_this_week = $stmt->fetch()['count'];
    echo "<p class='success'>✓ Logs This Week: $logs_this_week</p>";
    
    // Pending logs
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM daily_logs WHERE intern_id = ? AND status = 'pending'");
    $stmt->execute([$user_id]);
    $pending_logs = $stmt->fetch()['count'];
    echo "<p class='success'>✓ Pending Logs: $pending_logs</p>";
    
    // Approved logs
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM daily_logs WHERE intern_id = ? AND status = 'approved'");
    $stmt->execute([$user_id]);
    $approved_logs = $stmt->fetch()['count'];
    echo "<p class='success'>✓ Approved Logs: $approved_logs</p>";
    
    // Total logs
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM daily_logs WHERE intern_id = ?");
    $stmt->execute([$user_id]);
    $total_logs = $stmt->fetch()['count'];
    echo "<p class='success'>✓ Total Logs: $total_logs</p>";
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 2: Announcements
echo "<div class='section'>";
echo "<h2>Test 2: Announcements</h2>";
try {
    $stmt = $db->prepare("SELECT a.*, u.name as created_by_name FROM announcements a JOIN users u ON a.created_by = u.id ORDER BY a.date_created DESC LIMIT 5");
    $stmt->execute();
    $announcements = $stmt->fetchAll();
    echo "<p class='success'>✓ Found " . count($announcements) . " announcements</p>";
    foreach ($announcements as $ann) {
        echo "<p>• " . htmlspecialchars($ann['title']) . " (by " . htmlspecialchars($ann['created_by_name']) . ")</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 3: Skills
echo "<div class='section'>";
echo "<h2>Test 3: Skills Extraction</h2>";
try {
    $stmt = $db->prepare("SELECT skills FROM daily_logs WHERE intern_id = ? AND skills IS NOT NULL AND skills != '' ORDER BY date DESC");
    $stmt->execute([$user_id]);
    $skills_data = $stmt->fetchAll();
    $all_skills = [];
    foreach ($skills_data as $row) {
        $skills = array_map('trim', explode(',', $row['skills']));
        $all_skills = array_merge($all_skills, $skills);
    }
    $skills_summary = array_count_values($all_skills);
    arsort($skills_summary);
    $skills_summary = array_slice($skills_summary, 0, 10);
    
    echo "<p class='success'>✓ Found " . count($skills_summary) . " unique skills</p>";
    foreach ($skills_summary as $skill => $count) {
        echo "<p>• " . htmlspecialchars($skill) . ": $count times</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 4: Calendar Data
echo "<div class='section'>";
echo "<h2>Test 4: Calendar Logs</h2>";
try {
    $stmt = $db->prepare("SELECT DATE(date) as log_date, status FROM daily_logs WHERE intern_id = ? AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())");
    $stmt->execute([$user_id]);
    $calendar_logs = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    echo "<p class='success'>✓ Found " . count($calendar_logs) . " logs this month</p>";
    foreach ($calendar_logs as $date => $status) {
        echo "<p>• $date: <strong>$status</strong></p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 5: Profile
echo "<div class='section'>";
echo "<h2>Test 5: Intern Profile</h2>";
try {
    $stmt = $db->prepare("SELECT ip.*, u.name as supervisor_name FROM intern_profiles ip JOIN users u ON ip.supervisor_id = u.id WHERE ip.user_id = ?");
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();
    if ($profile) {
        echo "<p class='success'>✓ Profile found</p>";
        echo "<p>• Department: " . getDepartmentName($profile['department']) . "</p>";
        echo "<p>• Supervisor: " . htmlspecialchars($profile['supervisor_name']) . "</p>";
        echo "<p>• Start: " . $profile['start_date'] . "</p>";
        echo "<p>• End: " . $profile['end_date'] . "</p>";
        echo "<p>• Status: " . $profile['status'] . "</p>";
    } else {
        echo "<p class='error'>✗ Profile not found</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 6: Controller Methods
echo "<div class='section'>";
echo "<h2>Test 6: Controller Methods</h2>";
require_once 'controllers/InternController.php';
$controller = new InternController();
echo "<p class='success'>✓ InternController loaded</p>";
if (method_exists($controller, 'attendance')) {
    echo "<p class='success'>✓ attendance() method exists</p>";
} else {
    echo "<p class='error'>✗ attendance() method missing</p>";
}
if (method_exists($controller, 'statistics')) {
    echo "<p class='success'>✓ statistics() method exists</p>";
} else {
    echo "<p class='error'>✗ statistics() method missing</p>";
}
echo "</div>";

echo "<hr>";
echo "<h2>✅ Quick Navigation</h2>";
echo "<p><a href='index.php?page=dashboard' style='padding:10px;background:#840100;color:white;text-decoration:none;display:inline-block;margin:5px;'>Go to Dashboard</a></p>";
echo "<p><a href='index.php?page=intern&action=attendance' style='padding:10px;background:#840100;color:white;text-decoration:none;display:inline-block;margin:5px;'>Go to Attendance</a></p>";
echo "<p><a href='index.php?page=intern&action=statistics' style='padding:10px;background:#840100;color:white;text-decoration:none;display:inline-block;margin:5px;'>Go to Statistics</a></p>";

echo "</body></html>";
?>
