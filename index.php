<?php
/**
 * Parliament Intern Logbook System
 * Main entry point
 */

session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

// Simple routing system
$page = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? 'index';

// Check if user is logged in
if (!isset($_SESSION['user_id']) && $page !== 'login') {
    header('Location: index.php?page=login');
    exit;
}

// Route to appropriate controller
switch ($page) {
    case 'login':
        include 'controllers/AuthController.php';
        $controller = new AuthController();
        break;
    case 'dashboard':
        include 'controllers/DashboardController.php';
        $controller = new DashboardController();
        break;
    case 'intern':
        include 'controllers/InternController.php';
        $controller = new InternController();
        break;
    case 'supervisor':
        include 'controllers/SupervisorController.php';
        $controller = new SupervisorController();
        break;
    case 'admin':
        include 'controllers/AdminController.php';
        $controller = new AdminController();
        break;
    default:
        header('Location: index.php?page=login');
        exit;
}

// Execute the action
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    $controller->index();
}
?>


