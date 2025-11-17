<?php
/**
 * Database Configuration
 * Parliament Intern Logbook System
 */

// Load environment variables
$env_vars = [];
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $env_vars[trim($key)] = trim($value);
        }
    }
}

class Database {
    private static $instance = null;
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    private function __construct() {
        global $env_vars;
        $this->host = $env_vars['DB_HOST'] ?? 'localhost';
        $this->db_name = $env_vars['DB_NAME'] ?? 'parliament1_db';
        $this->username = $env_vars['DB_USERNAME'] ?? 'root';
        $this->password = $env_vars['DB_PASSWORD'] ?? '';
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
        } catch (Throwable $exception) {
            http_response_code(500);
            // Log error instead of exposing details
            error_log("Database connection error: " . $exception->getMessage());
            echo "Database connection failed. Please check your configuration.";
            exit;
        }
        
        return $this->conn;
    }
}

// Global database connection
$db = Database::getInstance()->getConnection();
?>


