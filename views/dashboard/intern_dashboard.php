<?php
$page_title = 'Intern Dashboard';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>Intern Dashboard
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <span class="badge bg-info fs-6">
                <i class="fas fa-user-graduate me-1"></i>Intern
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
                    <h3 class="mb-0"><?= $pending_logs ?></h3>
                    <p class="mb-0">Pending Logs</p>
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
                    <h3 class="mb-0"><?= count($recent_evaluations) ?></h3>
                    <p class="mb-0">Evaluations</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $profile ? getDepartmentName($profile['department']) : 'N/A' ?></h3>
                    <p class="mb-0">Department</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Logs -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Recent Daily Logs
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recent_logs)): ?>
                    <p class="text-muted text-center py-3">No logs submitted yet.</p>
                    <div class="text-center">
                        <a href="index.php?page=intern&action=addLog" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Your First Log
                        </a>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_logs as $log): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?= formatDate($log['date']) ?></h6>
                                        <p class="mb-1 text-muted small">
                                            <?= htmlspecialchars(substr($log['task_description'], 0, 80)) ?>...
                                        </p>
                                        <small class="text-muted">
                                            Skills: <?= htmlspecialchars($log['skills'] ?? 'N/A') ?> • 
                                            Status: <span class="badge bg-<?= $log['status'] === 'approved' ? 'success' : ($log['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                                <?= ucfirst($log['status']) ?>
                                            </span>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <?php if ($log['status'] === 'pending' && strtotime($log['created_at']) > (time() - 86400)): ?>
                                            <a href="index.php?page=intern&action=editLog&id=<?= $log['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary me-1">
                                                Edit
                                            </a>
                                        <?php endif; ?>
                                        <a href="index.php?page=intern&action=viewLog&id=<?= $log['id'] ?>" 
                                           class="btn btn-sm btn-outline-info">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Evaluations -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2"></i>Recent Evaluations
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recent_evaluations)): ?>
                    <p class="text-muted text-center py-3">No evaluations yet.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_evaluations as $evaluation): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Week <?= $evaluation['week_no'] ?> Evaluation</h6>
                                        <p class="mb-1 text-muted small">
                                            Technical: <?= $evaluation['rating_technical'] ?>/5 • 
                                            Soft Skills: <?= $evaluation['rating_softskills'] ?>/5
                                        </p>
                                        <?php if ($evaluation['comments']): ?>
                                            <small class="text-muted">
                                                "<?= htmlspecialchars(substr($evaluation['comments'], 0, 60)) ?>..."
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?= ($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2 >= 4 ? 'success' : (($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2 >= 3 ? 'warning' : 'danger') ?>">
                                            <?= number_format(($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2, 1) ?>/5
                                        </span>
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

<?php if ($profile): ?>
<div class="row mt-4">
    <!-- Profile Information -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Profile Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td><?= htmlspecialchars($_SESSION['name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?= htmlspecialchars($_SESSION['email']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Department:</strong></td>
                                <td><?= getDepartmentName($profile['department']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Supervisor:</strong></td>
                                <td><?= htmlspecialchars($profile['supervisor_name']) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Start Date:</strong></td>
                                <td><?= formatDate($profile['start_date']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>End Date:</strong></td>
                                <td><?= formatDate($profile['end_date']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-<?= $profile['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($profile['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Duration:</strong></td>
                                <td>
                                    <?php
                                    $start = new DateTime($profile['start_date']);
                                    $end = new DateTime($profile['end_date']);
                                    $interval = $start->diff($end);
                                    echo $interval->format('%m months, %d days');
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

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
                        <a href="index.php?page=intern&action=addLog" class="btn btn-outline-primary w-100 mb-3">
                            <i class="fas fa-plus me-2"></i>Add Daily Log
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=intern&action=logs" class="btn btn-outline-success w-100 mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>View All Logs
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=intern&action=weeklyReport" class="btn btn-outline-info w-100 mb-3">
                            <i class="fas fa-file-pdf me-2"></i>Weekly Report
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=intern&action=evaluations" class="btn btn-outline-warning w-100 mb-3">
                            <i class="fas fa-star me-2"></i>My Evaluations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>


