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

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
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
    <div class="col-md-3">
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
    <div class="col-md-3">
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
    <div class="col-md-3">
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
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Users by Role
                </h5>
            </div>
            <div class="card-body">
                <canvas id="usersByRoleChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Announcements -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bullhorn me-2"></i>Recent Announcements
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recent_announcements)): ?>
                    <p class="text-muted text-center py-3">No announcements yet.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_announcements as $announcement): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
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
                <?php endif; ?>
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


