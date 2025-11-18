<?php
$page_title = 'Settings';
include 'views/layouts/header.php';

// Mock data - replace with actual database queries
$user_activities = [
    ['action' => 'Logged in', 'timestamp' => '2025-01-18 09:15:23', 'ip' => '192.168.1.100'],
    ['action' => 'Submitted daily log', 'timestamp' => '2025-01-17 16:45:12', 'ip' => '192.168.1.100'],
    ['action' => 'Updated profile picture', 'timestamp' => '2025-01-15 10:30:45', 'ip' => '192.168.1.100'],
    ['action' => 'Changed password', 'timestamp' => '2025-01-12 14:20:33', 'ip' => '192.168.1.105'],
    ['action' => 'Logged in', 'timestamp' => '2025-01-12 09:00:15', 'ip' => '192.168.1.105'],
];

$active_sessions = [
    ['device' => 'Chrome on Windows', 'location' => 'Colombo, Sri Lanka', 'ip' => '192.168.1.100', 'last_active' => '2 minutes ago', 'current' => true],
    ['device' => 'Safari on iPhone', 'location' => 'Colombo, Sri Lanka', 'ip' => '192.168.1.105', 'last_active' => '3 days ago', 'current' => false],
];
?>

<style>
    .settings-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #d4af37;
        transition: all 0.3s ease;
    }

    .settings-section:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .settings-section h3 {
        color: #7A0000;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .settings-section h3 i {
        color: #d4af37;
        font-size: 1.5rem;
    }

    .setting-item {
        padding: 1rem 0;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .setting-item:last-child {
        border-bottom: none;
    }

    .setting-label {
        font-weight: 500;
        color: #1e293b;
        flex: 1;
    }

    .setting-description {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        width: 52px;
        height: 28px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: 0.3s;
        border-radius: 28px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .toggle-switch input:checked + .toggle-slider {
        background: linear-gradient(135deg, #7A0000, #5c0100);
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }

    /* Theme Toggle */
    .theme-option {
        flex: 1;
        padding: 1rem;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .theme-option:hover {
        border-color: #d4af37;
        background: rgba(212, 175, 55, 0.05);
    }

    .theme-option.active {
        border-color: #7A0000;
        background: rgba(122, 0, 0, 0.05);
    }

    .theme-option i {
        font-size: 2rem;
        color: #7A0000;
        margin-bottom: 0.5rem;
    }

    /* Activity Log */
    .activity-item {
        display: flex;
        align-items: start;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        background: #f8fafc;
        transition: all 0.2s ease;
    }

    .activity-item:hover {
        background: rgba(122, 0, 0, 0.05);
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d4af37, #f4d03f);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7A0000;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-action {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .activity-meta {
        font-size: 0.875rem;
        color: #64748b;
    }

    /* Session Card */
    .session-card {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .session-card:hover {
        border-color: #d4af37;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .session-card.current {
        border-color: #7A0000;
        background: rgba(122, 0, 0, 0.02);
    }

    .session-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #d4af37, #f4d03f);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7A0000;
        margin-right: 1rem;
        font-size: 1.5rem;
    }

    .session-info {
        flex: 1;
    }

    .session-device {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .session-meta {
        font-size: 0.875rem;
        color: #64748b;
    }

    /* FAQ Accordion */
    .faq-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        overflow: hidden;
    }

    .faq-question {
        padding: 1rem 1.25rem;
        background: #f8fafc;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    .faq-question:hover {
        background: rgba(122, 0, 0, 0.05);
    }

    .faq-answer {
        padding: 0 1.25rem;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        color: #64748b;
    }

    .faq-item.active .faq-answer {
        padding: 1rem 1.25rem;
        max-height: 500px;
    }

    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }

    /* Info Box */
    .info-box {
        background: linear-gradient(135deg, rgba(122, 0, 0, 0.05), rgba(212, 175, 55, 0.05));
        border-left: 4px solid #d4af37;
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .info-box-title {
        font-weight: 600;
        color: #7A0000;
        margin-bottom: 0.5rem;
    }

    .info-box-content {
        color: #64748b;
        font-size: 0.875rem;
        line-height: 1.6;
    }
</style>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-cog me-2"></i>Settings
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=profile">Profile</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Notification Settings -->
<div class="settings-section">
    <h3><i class="fas fa-bell"></i> Notification Settings</h3>
    <p class="text-muted mb-4">Manage how you receive notifications from the system</p>
    
    <div class="setting-item">
        <div>
            <div class="setting-label">Supervisor Comments</div>
            <div class="setting-description">Receive email alerts when your supervisor adds comments to your logs</div>
        </div>
        <label class="toggle-switch">
            <input type="checkbox" checked>
            <span class="toggle-slider"></span>
        </label>
    </div>
    
    <div class="setting-item">
        <div>
            <div class="setting-label">Log Approval Status</div>
            <div class="setting-description">Get notified when your daily logs are approved or rejected</div>
        </div>
        <label class="toggle-switch">
            <input type="checkbox" checked>
            <span class="toggle-slider"></span>
        </label>
    </div>
    
    <div class="setting-item">
        <div>
            <div class="setting-label">Daily Log Reminders</div>
            <div class="setting-description">Receive reminder emails if you haven't submitted your daily log</div>
        </div>
        <label class="toggle-switch">
            <input type="checkbox">
            <span class="toggle-slider"></span>
        </label>
    </div>
</div>

<!-- UI Personalization -->
<div class="settings-section">
    <h3><i class="fas fa-palette"></i> UI Personalization</h3>
    <p class="text-muted mb-4">Customize your interface appearance</p>
    
    <div class="row g-3">
        <div class="col-md-4">
            <div class="theme-option active" data-theme="light">
                <i class="fas fa-sun"></i>
                <div class="fw-bold">Light</div>
                <small class="text-muted">Classic bright theme</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="theme-option" data-theme="dark">
                <i class="fas fa-moon"></i>
                <div class="fw-bold">Dark</div>
                <small class="text-muted">Maroon & gold premium</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="theme-option" data-theme="system">
                <i class="fas fa-desktop"></i>
                <div class="fw-bold">System</div>
                <small class="text-muted">Match device settings</small>
            </div>
        </div>
    </div>
</div>

<!-- Activity Log -->
<div class="settings-section">
    <h3><i class="fas fa-history"></i> Activity Log</h3>
    <p class="text-muted mb-4">Recent account activities and security events</p>
    
    <?php foreach ($user_activities as $activity): ?>
    <div class="activity-item">
        <div class="activity-icon">
            <i class="fas fa-<?= 
                $activity['action'] === 'Logged in' ? 'sign-in-alt' : 
                ($activity['action'] === 'Submitted daily log' ? 'clipboard-check' : 
                ($activity['action'] === 'Updated profile picture' ? 'image' : 
                ($activity['action'] === 'Changed password' ? 'key' : 'sign-out-alt'))) 
            ?>"></i>
        </div>
        <div class="activity-content">
            <div class="activity-action"><?= htmlspecialchars($activity['action']) ?></div>
            <div class="activity-meta">
                <i class="fas fa-clock me-1"></i><?= $activity['timestamp'] ?>
                <span class="mx-2">•</span>
                <i class="fas fa-network-wired me-1"></i><?= $activity['ip'] ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
    <div class="text-center mt-3">
        <button class="btn btn-outline-primary btn-sm">
            <i class="fas fa-history me-2"></i>View Full Activity History
        </button>
    </div>
</div>

<!-- Device & Session Management -->
<div class="settings-section">
    <h3><i class="fas fa-laptop"></i> Active Sessions</h3>
    <p class="text-muted mb-4">Devices currently logged into your account</p>
    
    <?php foreach ($active_sessions as $session): ?>
    <div class="session-card <?= $session['current'] ? 'current' : '' ?>">
        <div class="session-icon">
            <i class="fas fa-<?= strpos($session['device'], 'Windows') !== false ? 'desktop' : 'mobile-alt' ?>"></i>
        </div>
        <div class="session-info">
            <div class="session-device">
                <?= htmlspecialchars($session['device']) ?>
                <?php if ($session['current']): ?>
                    <span class="badge bg-success ms-2">Current Session</span>
                <?php endif; ?>
            </div>
            <div class="session-meta">
                <i class="fas fa-map-marker-alt me-1"></i><?= $session['location'] ?>
                <span class="mx-2">•</span>
                <i class="fas fa-clock me-1"></i><?= $session['last_active'] ?>
            </div>
        </div>
        <?php if (!$session['current']): ?>
        <button class="btn btn-outline-danger btn-sm">
            <i class="fas fa-sign-out-alt me-1"></i>Revoke
        </button>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    
    <div class="alert alert-warning mt-3">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Security Tip:</strong> If you see an unfamiliar session, log out of all devices immediately and change your password.
    </div>
    
    <button class="btn btn-danger w-100">
        <i class="fas fa-power-off me-2"></i>Log Out of All Devices
    </button>
</div>

<!-- Help & Support -->
<div class="settings-section">
    <h3><i class="fas fa-question-circle"></i> Help & Support</h3>
    <p class="text-muted mb-4">Get assistance and answers to common questions</p>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="info-box">
                <div class="info-box-title">
                    <i class="fas fa-envelope me-2"></i>Technical Support
                </div>
                <div class="info-box-content">
                    Email: <a href="mailto:itsupport@parliament.lk">itsupport@parliament.lk</a><br>
                    Response time: Within 24 hours
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box">
                <div class="info-box-title">
                    <i class="fas fa-phone me-2"></i>HR Department
                </div>
                <div class="info-box-content">
                    Phone: +94 11 777 7777<br>
                    Hours: Mon-Fri, 9:00 AM - 5:00 PM
                </div>
            </div>
        </div>
    </div>
    
    <h5 class="mb-3">Frequently Asked Questions</h5>
    
    <div class="faq-item">
        <div class="faq-question" onclick="toggleFAQ(this)">
            How do I submit a daily log?
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            Navigate to "Daily Logs" from the sidebar, click "Add Daily Log" button, fill in your activities, tasks completed, hours worked, and any skills learned. Click "Submit" when done. You can edit your log within 24 hours of submission.
        </div>
    </div>
    
    <div class="faq-item">
        <div class="faq-question" onclick="toggleFAQ(this)">
            How do I edit a submitted log?
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            You can edit your log within 24 hours of submission by going to "Daily Logs", clicking on the log entry, and selecting "Edit". After 24 hours, logs are locked and can only be modified by your supervisor or administrator.
        </div>
    </div>
    
    <div class="faq-item">
        <div class="faq-question" onclick="toggleFAQ(this)">
            Who reviews my logs?
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            Your assigned supervisor reviews all your daily logs. They can approve, reject, or request modifications. You'll receive email notifications about the status of your logs. Your supervisor also provides weekly evaluations based on your performance.
        </div>
    </div>
    
    <div class="faq-item">
        <div class="faq-question" onclick="toggleFAQ(this)">
            How do I change my password?
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
            Go to your Profile page and click on "Change Password" button. Enter your current password, then your new password twice for confirmation. Passwords must be at least 8 characters long and contain letters and numbers.
        </div>
    </div>
</div>

<!-- About System -->
<div class="settings-section">
    <h3><i class="fas fa-info-circle"></i> About System</h3>
    <p class="text-muted mb-4">System information and credits</p>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="setting-item">
                <div>
                    <div class="setting-label">System Version</div>
                    <div class="setting-description">v1.2.0 (Stable)</div>
                </div>
                <span class="badge bg-success">Latest</span>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="setting-item">
                <div>
                    <div class="setting-label">Last Updated</div>
                    <div class="setting-description">January 15, 2025</div>
                </div>
                <i class="fas fa-calendar-check text-primary" style="font-size: 1.5rem;"></i>
            </div>
        </div>
    </div>
    
    <div class="info-box">
        <div class="info-box-title">
            <i class="fas fa-code me-2"></i>Development Team
        </div>
        <div class="info-box-content">
            <strong>Parliament Intern Logbook System</strong><br>
            Developed by IT Division, Parliament of Sri Lanka<br>
            In collaboration with the Intern Development Team<br>
            © 2025 Parliament of Sri Lanka. All rights reserved.
        </div>
    </div>
    
    <div class="info-box">
        <div class="info-box-title">
            <i class="fas fa-shield-alt me-2"></i>Data Privacy & Security
        </div>
        <div class="info-box-content">
            Your data is protected under the Sri Lankan Data Protection Act. All information submitted through this system is encrypted and stored securely. We do not share your personal information with third parties. For more details, read our <a href="#" class="text-primary">Privacy Policy</a> and <a href="#" class="text-primary">Terms of Service</a>.
        </div>
    </div>
    
    <div class="text-center mt-4">
        <button class="btn btn-outline-secondary me-2">
            <i class="fas fa-file-alt me-2"></i>View Changelog
        </button>
        <button class="btn btn-outline-secondary">
            <i class="fas fa-download me-2"></i>Export My Data
        </button>
    </div>
</div>

<script>
// Theme Toggle
document.querySelectorAll('.theme-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.theme-option').forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');
        
        const theme = this.dataset.theme;
        // Save theme preference
        localStorage.setItem('theme', theme);
        
        // Apply theme (implement your theme switching logic here)
        if (theme === 'dark') {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
        
        toastr.success(`Theme changed to ${theme} mode`, 'Settings Updated');
    });
});

// FAQ Toggle
function toggleFAQ(element) {
    const faqItem = element.parentElement;
    faqItem.classList.toggle('active');
}

// Toggle Switch Handler
document.querySelectorAll('.toggle-switch input').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const label = this.closest('.setting-item').querySelector('.setting-label').textContent;
        const status = this.checked ? 'enabled' : 'disabled';
        toastr.info(`${label} ${status}`, 'Setting Updated');
        
        // Send AJAX request to save preference
        // $.post('index.php?page=profile&action=updateSettings', { ... });
    });
});

// Log out all devices
document.querySelector('.btn-danger.w-100').addEventListener('click', function() {
    if (confirm('Are you sure you want to log out of all devices? You will need to log in again on this device.')) {
        // Implement logout logic
        toastr.warning('Logging out of all devices...', 'Security Action');
        setTimeout(() => {
            window.location.href = 'index.php?page=login&action=logout';
        }, 2000);
    }
});
</script>

<?php include 'views/layouts/footer.php'; ?>
