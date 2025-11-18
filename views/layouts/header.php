<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Parliament Intern Logbook' ?> - Parliament of Sri Lanka</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Toastr CSS for Toast Notifications -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    
    <!-- Intro.js for Onboarding Tour -->
    <link href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #840100;
            --primary-dark: #5c0100;
            --secondary-color: #a00200;
            --accent-color: #c70300;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #840100;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --card-shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            background-attachment: fixed;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--dark-color);
            overflow-x: hidden;
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(132, 1, 0, 0.05), transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(132, 1, 0, 0.08), transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(160, 2, 0, 0.03), transparent 50%);
            animation: backgroundMove 20s ease infinite;
            z-index: -1;
        }

        @keyframes backgroundMove {
            0%, 100% { transform: scale(1) translateY(0); }
            50% { transform: scale(1.1) translateY(-20px); }
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #840100 0%, #5c0100 100%);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 1rem 0;
            border-bottom: 2px solid transparent;
            border-image: linear-gradient(90deg, #d4af37 0%, #f4d03f 50%, #d4af37 100%);
            border-image-slice: 1;
            position: relative;
            z-index: 1040;
            overflow: visible !important;
        }

        .navbar .container-fluid {
            overflow: visible !important;
        }

        .navbar .navbar-collapse {
            overflow: visible !important;
        }

        .navbar .navbar-nav {
            overflow: visible !important;
        }

        .navbar .nav-item.dropdown {
            position: relative;
            overflow: visible !important;
        }

        .navbar::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #d4af37 20%, #f4d03f 50%, #d4af37 80%, transparent);
            opacity: 0.8;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar .nav-link.dropdown-toggle {
            color: white !important;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .navbar .nav-link.dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar .nav-link.dropdown-toggle::after {
            margin-left: 0;
            transition: transform 0.3s ease;
            font-size: 0.875rem;
        }

        .navbar .nav-link.dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .navbar .user-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d4af37, #f4d03f);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--primary-dark);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .navbar .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.2;
        }

        .navbar .user-name {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .navbar .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.625rem;
            letter-spacing: 0.5px;
            background: rgba(255, 255, 255, 0.9) !important;
            color: var(--primary-color) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar */
        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.08);
            min-height: calc(100vh - 76px);
            position: sticky;
            top: 76px;
            z-index: 1000;
            border-right: 1px solid rgba(132, 1, 0, 0.1);
        }

        .sidebar .nav-link {
            color: var(--dark-color);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: -1;
        }

        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            left: 0;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            transform: translateX(8px);
            box-shadow: 0 8px 20px rgba(132, 1, 0, 0.3);
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Modern Card Design */
        .card {
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: var(--card-shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-shadow-hover);
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        .card-header {
            background: linear-gradient(135deg, #840100, #5c0100);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            border: none;
            padding: 1.5rem;
        }

        /* Stats Card with Gradient */
        .stats-card {
            background: linear-gradient(135deg, #840100, #5c0100);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideInRight 0.6s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
            animation: ripple 15s infinite;
        }

        @keyframes ripple {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.2) rotate(180deg); }
        }

        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(132, 1, 0, 0.4);
        }

        .stats-card .stats-icon {
            font-size: 3.5rem;
            opacity: 0.9;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(132, 1, 0, 0.4);
        }

        /* Badge with Glow */
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.02em;
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Alert with Modern Design */
        .alert {
            border: none;
            border-radius: 16px;
            border-left: 4px solid;
            backdrop-filter: blur(10px);
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Controls */
        .form-control {
            border-radius: 12px;
            border: 2px solid rgba(99, 102, 241, 0.2);
            padding: 0.875rem 1.25rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(132, 1, 0, 0.1);
            transform: translateY(-2px);
        }

        /* Page Header */
        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            animation: fadeInDown 0.6s ease;
            border: 1px solid rgba(132, 1, 0, 0.1);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Progress Bar */
        .progress {
            height: 12px;
            border-radius: 50px;
            background: rgba(132, 1, 0, 0.1);
            overflow: visible;
        }

        .progress-bar {
            border-radius: 50px;
            background: linear-gradient(90deg, #840100, #5c0100);
            position: relative;
            animation: progressGrow 1.5s ease;
            box-shadow: 0 4px 10px rgba(132, 1, 0, 0.3);
        }

        @keyframes progressGrow {
            from { width: 0; }
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Table Modern Design */
        .table {
            border-radius: 16px;
            overflow: hidden;
            background: white;
        }

        .table thead th {
            background: linear-gradient(135deg, #840100, #5c0100);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1.25rem;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
        }

        .table tbody tr:hover {
            background: rgba(132, 1, 0, 0.05);
            transform: scale(1.01);
        }

        /* Dropdown Menu - Profile Card Style */
        .dropdown-menu {
            border: none;
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            padding: 0;
            backdrop-filter: blur(20px);
            background: white;
            animation: fadeInUp 0.3s ease;
            min-width: 280px;
            margin-top: 0.75rem !important;
            border-left: 3px solid #d4af37;
            position: fixed !important;
            z-index: 99999 !important;
            transform: none !important;
            will-change: transform;
        }

        .dropdown-menu.show {
            display: block !important;
        }
        
        /* Dropdown backdrop for better visibility */
        .dropdown-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.02);
            z-index: 99998;
            display: none;
        }
        
        .dropdown-backdrop.show {
            display: block;
        }

        /* Profile Header Section */
        .dropdown-profile-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, rgba(122, 0, 0, 0.03), rgba(122, 0, 0, 0.01));
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .dropdown-profile-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d4af37, #f4d03f);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            color: #7A0000;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
            flex-shrink: 0;
        }

        .dropdown-profile-info {
            flex: 1;
            min-width: 0;
        }

        .dropdown-profile-name {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-profile-role {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dropdown-profile-email {
            font-size: 0.75rem;
            color: #94a3b8;
            margin: 2px 0 0 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Menu Items Section */
        .dropdown-menu-items {
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
            border: none;
        }

        .dropdown-item i {
            width: 18px;
            text-align: center;
            font-size: 1rem;
            color: #64748b;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: rgba(122, 0, 0, 0.06);
            color: #7A0000;
            transform: translateX(4px);
        }

        .dropdown-item:hover i {
            color: #7A0000;
        }

        .dropdown-item.text-danger {
            color: #dc2626;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(220, 38, 38, 0.08);
            color: #b91c1c;
        }

        .dropdown-item.text-danger:hover i {
            color: #b91c1c;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-top: 1px solid #e9ecef;
        }

        /* Dropdown Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading Overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 80px;
            height: 80px;
            border: 6px solid rgba(132, 1, 0, 0.2);
            border-top: 6px solid #840100;
            border-radius: 50%;
            animation: spin 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
        }

        /* Pulse Animation */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }

        /* Calendar Styles */
        .calendar-mini {
            font-family: 'Inter', sans-serif;
        }

        .calendar-mini .row {
            margin-left: 0;
            margin-right: 0;
        }

        .calendar-mini .col {
            padding: 2px;
            flex: 0 0 14.2857%;
            max-width: 14.2857%;
        }

        .calendar-mini .fw-bold {
            color: var(--dark-color);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .calendar-mini small.d-block {
            min-height: 32px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: default;
            aspect-ratio: 1;
        }

        .calendar-mini small.d-block:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            z-index: 1;
        }

        .calendar-mini .bg-light {
            background-color: #f1f3f5 !important;
            border: 1px solid #e9ecef;
        }

        .calendar-mini .border-primary {
            box-shadow: 0 0 0 2px var(--primary-color);
            font-weight: 700 !important;
        }

        /* Scroll Animations */
        .scroll-animate {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .scroll-animate.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(132, 1, 0, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #840100, #5c0100);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #5c0100;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0.5rem 0;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: var(--secondary-color);
            transform: translateX(3px);
        }

        /* Beta Version Badge */
        .beta-badge {
            position: fixed;
            top: 76px;
            right: 0;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 0.5rem 1.5rem 0.5rem 1rem;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            z-index: 9000;
            box-shadow: -4px 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px 0 0 8px;
            animation: betaPulse 2s ease-in-out infinite;
        }

        .beta-badge i {
            margin-right: 0.5rem;
            animation: spin 3s linear infinite;
        }

        @keyframes betaPulse {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(-5px); }
        }

        /* Beta Notice Banner */
        .beta-notice-banner {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
            border-left: 4px solid #f59e0b;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            animation: slideDown 0.5s ease;
            position: relative;
        }

        .beta-notice-banner h5 {
            color: #d97706;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .beta-notice-banner p {
            color: #92400e;
            margin: 0;
            font-size: 0.875rem;
            line-height: 1.6;
        }

        .beta-notice-banner .btn-feedback {
            margin-top: 0.75rem;
            background: #f59e0b;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .beta-notice-banner .btn-feedback:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        
        .beta-notice-banner .btn-close {
            background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23d97706'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
            opacity: 0.7;
        }
        
        .beta-notice-banner .btn-close:hover {
            opacity: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-card {
                padding: 1.5rem;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }

            .beta-badge {
                top: 56px;
                font-size: 0.65rem;
                padding: 0.4rem 1rem 0.4rem 0.8rem;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }
            
            .navbar, .sidebar, .btn {
                display: none;
            }
            
            .card {
                box-shadow: none;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?page=dashboard">
                <i class="fas fa-landmark me-2"></i>
                Parliament Intern Logbook
                <span class="badge bg-warning text-dark ms-2" style="font-size: 0.65rem; vertical-align: middle;">BETA</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar-circle">
                                <?= strtoupper(substr($_SESSION['name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <span class="user-name"><?= $_SESSION['name'] ?? 'User' ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <!-- Profile Header -->
                            <li class="dropdown-profile-header">
                                <div class="dropdown-profile-avatar">
                                    <?= strtoupper(substr($_SESSION['name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <div class="dropdown-profile-info">
                                    <p class="dropdown-profile-name"><?= $_SESSION['name'] ?? 'User' ?></p>
                                    <p class="dropdown-profile-role"><?= ucfirst($_SESSION['role'] ?? 'User') ?></p>
                                    <p class="dropdown-profile-email"><?= $_SESSION['email'] ?? '' ?></p>
                                </div>
                            </li>
                            
                            <!-- Menu Items -->
                            <li class="dropdown-menu-items">
                                <a class="dropdown-item" href="index.php?page=profile">
                                    <i class="fas fa-user"></i>
                                    <span>View Profile</span>
                                </a>
                                <a class="dropdown-item" href="index.php?page=profile&action=settings">
                                    <i class="fas fa-cog"></i>
                                    <span>Settings</span>
                                </a>
                            </li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Logout -->
                            <li class="dropdown-menu-items">
                                <a class="dropdown-item text-danger" href="index.php?page=login&action=logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Beta Version Badge -->
    <div class="beta-badge">
        <i class="fas fa-flask"></i>
        BETA v0.9.5
    </div>

    <div class="container-fluid" style="overflow: visible;">
        <div class="row" style="overflow: visible;">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <nav class="nav flex-column">
                        <a class="nav-link <?= ($_GET['page'] ?? '') === 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        
                        <?php if ($_SESSION['role'] === 'intern'): ?>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'intern' && ($_GET['action'] ?? 'logs') === 'logs' ? 'active' : '' ?>" href="index.php?page=intern&action=logs">
                                <i class="fas fa-clipboard-list me-2"></i>Daily Logs
                            </a>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'intern' && ($_GET['action'] ?? '') === 'evaluations' ? 'active' : '' ?>" href="index.php?page=intern&action=evaluations">
                                <i class="fas fa-star me-2"></i>My Evaluations
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($_SESSION['role'] === 'supervisor'): ?>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'supervisor' && ($_GET['action'] ?? 'interns') === 'interns' ? 'active' : '' ?>" href="index.php?page=supervisor&action=interns">
                                <i class="fas fa-users me-2"></i>My Interns
                            </a>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'supervisor' && ($_GET['action'] ?? '') === 'logs' ? 'active' : '' ?>" href="index.php?page=supervisor&action=logs">
                                <i class="fas fa-clipboard-list me-2"></i>Review Logs
                            </a>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'supervisor' && ($_GET['action'] ?? '') === 'evaluations' ? 'active' : '' ?>" href="index.php?page=supervisor&action=evaluations">
                                <i class="fas fa-star me-2"></i>Evaluations
                            </a>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'supervisor' && ($_GET['action'] ?? '') === 'reports' ? 'active' : '' ?>" href="index.php?page=supervisor&action=reports">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'admin' ? 'active' : '' ?>" href="index.php?page=admin">
                                <i class="fas fa-cogs me-2"></i>Administration
                            </a>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'intern' && ($_GET['action'] ?? 'logs') === 'logs' ? 'active' : '' ?>" href="index.php?page=intern&action=logs">
                                <i class="fas fa-clipboard-list me-2"></i>Daily Logs
                            </a>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'intern' && ($_GET['action'] ?? '') === 'evaluations' ? 'active' : '' ?>" href="index.php?page=intern&action=evaluations">
                                <i class="fas fa-star me-2"></i>Evaluations
                            </a>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'supervisor' && ($_GET['action'] ?? 'interns') === 'interns' ? 'active' : '' ?>" href="index.php?page=supervisor&action=interns">
                                <i class="fas fa-users me-2"></i>Interns
                            </a>
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'supervisor' && ($_GET['action'] ?? '') === 'reports' ? 'active' : '' ?>" href="index.php?page=supervisor&action=reports">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        <?php endif; ?>
                    </nav>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="deleteConfirmMessage">Are you sure you want to delete this item? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <?php if ($error = getFlashMessage('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success = getFlashMessage('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= $success ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>


