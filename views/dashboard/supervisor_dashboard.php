<?php
$page_title = 'Supervisor Dashboard';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>Supervisor Dashboard
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <span class="badge bg-success fs-6">
                <i class="fas fa-user-tie me-1"></i>Supervisor
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
                    <h3 class="mb-0"><?= count($assigned_interns) ?></h3>
                    <p class="mb-0">Assigned Interns</p>
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
                    <h3 class="mb-0"><?= count($pending_reviews) ?></h3>
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
                    <h3 class="mb-0"><?= count($recent_evaluations) ?></h3>
                    <p class="mb-0">Recent Evaluations</p>
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
                    <h3 class="mb-0"><?= count($assigned_interns) ?></h3>
                    <p class="mb-0">Active Interns</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Assigned Interns -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>My Assigned Interns
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($assigned_interns)): ?>
                    <p class="text-muted text-center py-3">No assigned interns yet.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($assigned_interns as $intern): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($intern['intern_name']) ?></h6>
                                        <p class="mb-1 text-muted small">
                                            <?= htmlspecialchars($intern['intern_email']) ?>
                                        </p>
                                        <small class="text-muted">
                                            Department: <?= getDepartmentName($intern['department']) ?> • 
                                            Status: <span class="badge bg-<?= $intern['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($intern['status']) ?>
                                            </span>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">
                                            <?= formatDate($intern['start_date']) ?> - <?= formatDate($intern['end_date']) ?>
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

    <!-- Pending Reviews -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Pending Reviews
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($pending_reviews)): ?>
                    <p class="text-muted text-center py-3">No pending reviews.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($pending_reviews as $log): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($log['intern_name']) ?></h6>
                                        <p class="mb-1 text-muted small">
                                            <?= htmlspecialchars(substr($log['task_description'], 0, 80)) ?>...
                                        </p>
                                        <small class="text-muted">
                                            Date: <?= formatDate($log['date']) ?> • 
                                            Skills: <?= htmlspecialchars($log['skills'] ?? 'N/A') ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <a href="index.php?page=supervisor&action=reviewLog&id=<?= $log['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            Review
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
</div>

<div class="row mt-4">
    <!-- Recent Evaluations -->
    <div class="col-md-12">
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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Intern</th>
                                    <th>Week</th>
                                    <th>Technical Rating</th>
                                    <th>Soft Skills Rating</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_evaluations as $evaluation): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($evaluation['intern_name']) ?></td>
                                        <td>Week <?= $evaluation['week_no'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $evaluation['rating_technical'] >= 4 ? 'success' : ($evaluation['rating_technical'] >= 3 ? 'warning' : 'danger') ?>">
                                                <?= $evaluation['rating_technical'] ?>/5
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $evaluation['rating_softskills'] >= 4 ? 'success' : ($evaluation['rating_softskills'] >= 3 ? 'warning' : 'danger') ?>">
                                                <?= $evaluation['rating_softskills'] ?>/5
                                            </span>
                                        </td>
                                        <td><?= formatDate($evaluation['created_at']) ?></td>
                                        <td>
                                            <a href="index.php?page=supervisor&action=viewEvaluation&id=<?= $evaluation['id'] ?>" 
                                               class="btn btn-sm btn-outline-info">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
                        <a href="index.php?page=supervisor&action=interns" class="btn btn-outline-primary w-100 mb-3">
                            <i class="fas fa-users me-2"></i>Manage Interns
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=supervisor&action=logs" class="btn btn-outline-success w-100 mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>Review Logs
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=supervisor&action=evaluations" class="btn btn-outline-info w-100 mb-3">
                            <i class="fas fa-star me-2"></i>Weekly Evaluations
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=supervisor&action=reports" class="btn btn-outline-warning w-100 mb-3">
                            <i class="fas fa-chart-bar me-2"></i>Generate Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>


