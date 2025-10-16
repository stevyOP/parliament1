<?php
$page_title = 'My Interns';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-users me-2"></i>My Assigned Interns
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">My Interns</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>Assigned Interns
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($interns)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No assigned interns</h5>
                <p class="text-muted">You don't have any interns assigned to you yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th>Intern Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Duration</th>
                            <th>Logs</th>
                            <th>Performance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($interns as $intern): ?>
                            <tr>
                                <td>
                                    <div>
                                        <strong><?= htmlspecialchars($intern['intern_name']) ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($intern['intern_email']) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($intern['intern_email']) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= getDepartmentName($intern['department']) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $intern['status'] === 'active' ? 'success' : ($intern['status'] === 'completed' ? 'primary' : 'danger') ?>">
                                        <?= ucfirst($intern['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= formatDate($intern['start_date']) ?> - <?= formatDate($intern['end_date']) ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-primary"><?= $intern['total_logs'] ?> Total</span>
                                        <div class="mt-1">
                                            <span class="badge bg-success"><?= $intern['approved_logs'] ?> Approved</span>
                                            <?php if ($intern['pending_logs'] > 0): ?>
                                                <span class="badge bg-warning"><?= $intern['pending_logs'] ?> Pending</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($intern['total_evaluations'] > 0): ?>
                                        <div class="d-flex flex-column">
                                            <small>Technical: <?= number_format($intern['avg_technical'], 1) ?>/5</small>
                                            <small>Soft Skills: <?= number_format($intern['avg_softskills'], 1) ?>/5</small>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No evaluations yet</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?page=supervisor&action=logs&intern_id=<?= $intern['user_id'] ?>" 
                                           class="btn btn-sm btn-outline-primary" title="View Logs">
                                            <i class="fas fa-clipboard-list"></i>
                                        </a>
                                        <a href="index.php?page=supervisor&action=addEvaluation&intern_id=<?= $intern['user_id'] ?>" 
                                           class="btn btn-sm btn-outline-success" title="Add Evaluation">
                                            <i class="fas fa-star"></i>
                                        </a>
                                        <a href="index.php?page=supervisor&action=internDetails&id=<?= $intern['user_id'] ?>" 
                                           class="btn btn-sm btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($interns)): ?>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Status Overview
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Logs Summary
                </h5>
            </div>
            <div class="card-body">
                <canvas id="logsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Status Overview Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusData = <?= json_encode($interns) ?>;

const statusCounts = {
    active: statusData.filter(i => i.status === 'active').length,
    completed: statusData.filter(i => i.status === 'completed').length,
    terminated: statusData.filter(i => i.status === 'terminated').length
};

new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Completed', 'Terminated'],
        datasets: [{
            data: [statusCounts.active, statusCounts.completed, statusCounts.terminated],
            backgroundColor: ['#28a745', '#007bff', '#dc3545'],
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

// Logs Summary Chart
const logsCtx = document.getElementById('logsChart').getContext('2d');
const internNames = statusData.map(i => i.intern_name.split(' ')[0]); // First name only
const totalLogs = statusData.map(i => parseInt(i.total_logs));
const approvedLogs = statusData.map(i => parseInt(i.approved_logs));

new Chart(logsCtx, {
    type: 'bar',
    data: {
        labels: internNames,
        datasets: [{
            label: 'Total Logs',
            data: totalLogs,
            backgroundColor: '#667eea',
            borderColor: '#667eea',
            borderWidth: 1
        }, {
            label: 'Approved Logs',
            data: approvedLogs,
            backgroundColor: '#28a745',
            borderColor: '#28a745',
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
                position: 'top'
            }
        }
    }
});
</script>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>


