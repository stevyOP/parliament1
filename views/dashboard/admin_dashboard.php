<?php
$page_title = 'Admin Dashboard';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <span class="badge bg-primary fs-6">
                <i class="fas fa-user-shield me-1"></i>Administrator
            </span>
        </div>
    </div>
</div>

<!-- Beta Tester Welcome Notice -->
<div class="beta-notice-banner" id="betaBanner">
    <button type="button" class="btn-close" aria-label="Close" onclick="closeBetaBanner()" style="position: absolute; top: 15px; right: 15px;"></button>
    <h5>
        <i class="fas fa-star"></i>
        Thank You for Being a Beta Tester!
    </h5>
    <p>
        <strong>Welcome to the Parliament Intern Logbook System Beta Testing Program!</strong> 
        As an administrator, you have access to all system features. Please test all modules thoroughly and report any issues or suggestions.
        <br><br>
        <strong>What's New in Beta v0.9.5:</strong> Enhanced profile dropdown, modern settings page, improved navigation, and better UI responsiveness.
        <br>
        <strong>Expected Stable Release:</strong> February 2025
    </p>
    <button class="btn btn-feedback" onclick="window.location.href='mailto:itsupport@parliament.lk?subject=Beta Feedback - Intern Logbook (Admin)'">
        <i class="fas fa-comment-dots me-2"></i>Send Feedback
    </button>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=admin&action=users" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-users-cog fa-2x d-block mb-2"></i>
                            <span>Manage Users</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=supervisor&action=interns" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-users fa-2x d-block mb-2"></i>
                            <span>Interns</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=intern&action=evaluations" class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-star fa-2x d-block mb-2"></i>
                            <span>Evaluations</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=supervisor&action=reports" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-chart-bar fa-2x d-block mb-2"></i>
                            <span>Reports</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=profile" class="btn btn-outline-dark w-100 py-3">
                            <i class="fas fa-user fa-2x d-block mb-2"></i>
                            <span>My Profile</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=profile&action=settings" class="btn btn-outline-secondary w-100 py-3">
                            <i class="fas fa-cog fa-2x d-block mb-2"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                            <span>Settings</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $active_interns ?></h3>
                    <p class="mb-0">Active Interns</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $logs_this_week ?></h3>
                    <p class="mb-0">Logs This Week</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $pending_reviews ?></h3>
                    <p class="mb-0">Pending Reviews</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= count($users_by_role) ?></h3>
                    <p class="mb-0">Total Users</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Users by Role Chart -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Users by Role
                </h5>
            </div>
            <div class="card-body">
                <canvas id="usersByRoleChart" height="250"></canvas>
                <div class="mt-3">
                    <table class="table table-sm">
                        <tbody>
                            <?php foreach ($users_by_role as $role_data): ?>
                                <tr>
                                    <td><strong><?= ucfirst($role_data['role']) ?></strong></td>
                                    <td class="text-end"><span class="badge bg-primary"><?= $role_data['count'] ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Announcements -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bullhorn me-2"></i>Recent Announcements
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recent_announcements)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No announcements yet.</p>
                        <a href="index.php?page=admin&action=announcements" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Announcement
                        </a>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_announcements as $announcement): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= htmlspecialchars($announcement['title']) ?></h6>
                                        <p class="mb-1 text-muted small">
                                            <?= htmlspecialchars(substr($announcement['message'], 0, 100)) ?>...
                                        </p>
                                        <small class="text-muted">
                                            By <?= htmlspecialchars($announcement['created_by_name']) ?> • 
                                            <?= formatDate($announcement['date_created'], 'M d, Y') ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="index.php?page=admin&action=announcements" class="btn btn-sm btn-outline-primary">
                            View All Announcements
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- System Overview -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Activity Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-clipboard-list text-primary me-2"></i>Total Logs</span>
                        <strong><?= $logs_this_week * 4 ?></strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: 85%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-check-circle text-success me-2"></i>Approved</span>
                        <strong><?= $logs_this_week * 3 ?></strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 75%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-clock text-warning me-2"></i>Pending</span>
                        <strong><?= $pending_reviews ?></strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: <?= ($pending_reviews / max($logs_this_week, 1)) * 100 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users-cog me-2"></i>User Statistics
                </h5>
            </div>
            <div class="card-body">
                <?php
                $total_users = array_sum(array_column($users_by_role, 'count'));
                ?>
                <div class="text-center mb-3">
                    <h2 class="mb-0"><?= $total_users ?></h2>
                    <small class="text-muted">Total System Users</small>
                </div>
                <hr>
                <?php foreach ($users_by_role as $role_data): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-<?= $role_data['role'] === 'admin' ? 'danger' : ($role_data['role'] === 'supervisor' ? 'success' : 'info') ?>">
                            <?= ucfirst($role_data['role']) ?>
                        </span>
                        <strong><?= $role_data['count'] ?> (<?= round(($role_data['count'] / $total_users) * 100) ?>%)</strong>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tasks me-2"></i>System Health
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-check text-success me-2"></i>Active Interns</span>
                        <strong class="text-success"><?= $active_interns ?></strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-hourglass-half text-warning me-2"></i>Pending Reviews</span>
                        <strong class="text-warning"><?= $pending_reviews ?></strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-calendar-week text-info me-2"></i>Weekly Activity</span>
                        <strong class="text-info"><?= $logs_this_week ?> logs</strong>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <span class="badge bg-<?= $pending_reviews > 10 ? 'warning' : 'success' ?> fs-6">
                        <?= $pending_reviews > 10 ? 'Needs Attention' : 'System Healthy' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Quick Actions -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=users" class="btn btn-outline-primary w-100 mb-3">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=announcements" class="btn btn-outline-success w-100 mb-3">
                            <i class="fas fa-bullhorn me-2"></i>Create Announcement
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=reports" class="btn btn-outline-info w-100 mb-3">
                            <i class="fas fa-chart-bar me-2"></i>Generate Reports
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=export" class="btn btn-outline-warning w-100 mb-3">
                            <i class="fas fa-download me-2"></i>Export Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Users by Role Chart
const usersByRoleCtx = document.getElementById('usersByRoleChart').getContext('2d');
const usersByRoleData = <?= json_encode($users_by_role) ?>;

const roleLabels = usersByRoleData.map(item => item.role.charAt(0).toUpperCase() + item.role.slice(1));
const roleCounts = usersByRoleData.map(item => parseInt(item.count));

new Chart(usersByRoleCtx, {
    type: 'doughnut',
    data: {
        labels: roleLabels,
        datasets: [{
            data: roleCounts,
            backgroundColor: [
                '#840100',
                '#5c0100',
                '#a60100',
                '#3d0100'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?php include 'views/layouts/footer.php'; ?>


