<?php
/**
 * Simple Database Connection Test
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>DB Test</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;} .box{background:white;padding:20px;border-radius:5px;margin:10px 0;} .success{color:green;} .error{color:red;} h1{color:#840100;}</style>";
echo "</head><body>";

echo "<h1>🔧 Database Connection Test</h1>";

// Test 1: Check if config file exists
echo "<div class='box'>";
echo "<h2>Step 1: Configuration File</h2>";
if (file_exists('config/database.php')) {
    echo "<p class='success'>✓ config/database.php exists</p>";
    require_once 'config/database.php';
} else {
    echo "<p class='error'>✗ config/database.php NOT found</p>";
    exit;
}
echo "</div>";

// Test 2: Check if functions file exists
echo "<div class='box'>";
echo "<h2>Step 2: Functions File</h2>";
if (file_exists('includes/functions.php')) {
    echo "<p class='success'>✓ includes/functions.php exists</p>";
    require_once 'includes/functions.php';
} else {
    echo "<p class='error'>✗ includes/functions.php NOT found</p>";
}
echo "</div>";

// Test 3: Try to connect to database
echo "<div class='box'>";
echo "<h2>Step 3: Database Connection</h2>";
try {
    $db = Database::getInstance()->getConnection();
    if ($db) {
        echo "<p class='success'>✓ Database connection successful</p>";
        
        // Test query
        $stmt = $db->query("SELECT VERSION() as version");
        $version = $stmt->fetch();
        echo "<p class='success'>✓ MySQL Version: " . $version['version'] . "</p>";
        
        // Check tables
        $tables = ['users', 'intern_profiles', 'daily_logs', 'evaluations', 'announcements'];
        foreach ($tables as $table) {
            $stmt = $db->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                echo "<p class='success'>✓ Table '$table' exists</p>";
            } else {
                echo "<p class='error'>✗ Table '$table' NOT found</p>";
            }
        }
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Database Error: " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 4: Check controllers
echo "<div class='box'>";
echo "<h2>Step 4: Controllers</h2>";
$controllers = ['DashboardController.php', 'InternController.php'];
foreach ($controllers as $controller) {
    if (file_exists("controllers/$controller")) {
        echo "<p class='success'>✓ controllers/$controller exists</p>";
    } else {
        echo "<p class='error'>✗ controllers/$controller NOT found</p>";
    }
}
echo "</div>";

// Test 5: Check views
echo "<div class='box'>";
echo "<h2>Step 5: View Files</h2>";
$views = [
    'views/dashboard/intern_dashboard.php',
    'views/intern/attendance.php',
    'views/intern/statistics.php'
];
foreach ($views as $view) {
    if (file_exists($view)) {
        echo "<p class='success'>✓ $view exists</p>";
    } else {
        echo "<p class='error'>✗ $view NOT found</p>";
    }
}
echo "</div>";

echo "<hr>";
echo "<h2>✅ Next Steps</h2>";
echo "<p><a href='index.php?page=login' style='padding:10px 20px;background:#840100;color:white;text-decoration:none;border-radius:5px;display:inline-block;'>Go to Login Page</a></p>";

echo "</body></html>";
?>
