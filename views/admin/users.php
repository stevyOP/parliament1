<?php
$page_title = 'User Management';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-users me-2"></i>User Management
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="index.php?page=admin&action=addUser" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New User
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>All Users
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Supervisor</th>
                        <th>Status</th>
                        <th>Activity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?= htmlspecialchars($user['name']) ?></strong>
                                    <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                        <span class="badge bg-primary ms-2">You</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($user['email']) ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($user['email']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'supervisor' ? 'success' : 'info') ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['department']): ?>
                                    <span class="badge bg-secondary"><?= getDepartmentName($user['department']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['supervisor_name']): ?>
                                    <?= htmlspecialchars($user['supervisor_name']) ?>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="badge bg-<?= $user['is_active'] ? 'success' : 'danger' ?>">
                                        <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                    <?php if ($user['intern_status']): ?>
                                        <span class="badge bg-<?= $user['intern_status'] === 'active' ? 'success' : ($user['intern_status'] === 'completed' ? 'primary' : 'danger') ?> mt-1">
                                            <?= ucfirst($user['intern_status']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <small class="text-muted">
                                        Logs: <strong><?= $user['total_logs'] ?></strong>
                                    </small>
                                    <small class="text-muted">
                                        Evaluations: <strong><?= $user['total_evaluations'] ?></strong>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?page=admin&action=editUser&id=<?= $user['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <a href="index.php?page=admin&action=deleteUser&id=<?= $user['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($users)): ?>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Users by Role
                </h5>
            </div>
            <div class="card-body">
                <canvas id="usersByRoleChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Activity Summary
                </h5>
            </div>
            <div class="card-body">
                <canvas id="activityChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Users by Role Chart
const usersByRoleCtx = document.getElementById('usersByRoleChart').getContext('2d');
const users = <?= json_encode($users) ?>;

const roleCounts = {
    admin: users.filter(u => u.role === 'admin').length,
    supervisor: users.filter(u => u.role === 'supervisor').length,
    intern: users.filter(u => u.role === 'intern').length
};

new Chart(usersByRoleCtx, {
    type: 'doughnut',
    data: {
        labels: ['Admin', 'Supervisor', 'Intern'],
        datasets: [{
            data: [roleCounts.admin, roleCounts.supervisor, roleCounts.intern],
            backgroundColor: ['#dc3545', '#28a745', '#17a2b8'],
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

// Activity Chart
const activityCtx = document.getElementById('activityChart').getContext('2d');
const totalLogs = users.reduce((sum, user) => sum + parseInt(user.total_logs), 0);
const totalEvaluations = users.reduce((sum, user) => sum + parseInt(user.total_evaluations), 0);

new Chart(activityCtx, {
    type: 'bar',
    data: {
        labels: ['Total Logs', 'Total Evaluations'],
        datasets: [{
            data: [totalLogs, totalEvaluations],
            backgroundColor: ['#840100', '#5c0100'],
            borderColor: ['#840100', '#5c0100'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>


