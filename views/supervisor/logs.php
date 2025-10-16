<?php
$page_title = 'Review Logs';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-clipboard-list me-2"></i>Review Daily Logs
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Review Logs</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <a href="index.php?page=supervisor&action=logs" 
                   class="btn btn-outline-<?= $status === 'all' ? 'primary' : 'secondary' ?>">
                    All Logs
                </a>
                <a href="index.php?page=supervisor&action=logs&status=pending" 
                   class="btn btn-outline-<?= $status === 'pending' ? 'warning' : 'secondary' ?>">
                    Pending
                </a>
                <a href="index.php?page=supervisor&action=logs&status=approved" 
                   class="btn btn-outline-<?= $status === 'approved' ? 'success' : 'secondary' ?>">
                    Approved
                </a>
                <a href="index.php?page=supervisor&action=logs&status=rejected" 
                   class="btn btn-outline-<?= $status === 'rejected' ? 'danger' : 'secondary' ?>">
                    Rejected
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-clipboard-list me-2"></i>Daily Logs to Review
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($logs)): ?>
            <div class="text-center py-5">
                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No logs found</h5>
                <p class="text-muted">
                    <?php if ($status === 'pending'): ?>
                        No pending logs to review.
                    <?php elseif ($status === 'approved'): ?>
                        No approved logs found.
                    <?php elseif ($status === 'rejected'): ?>
                        No rejected logs found.
                    <?php else: ?>
                        No logs submitted by your interns yet.
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th>Intern</th>
                            <th>Date</th>
                            <th>Task Description</th>
                            <th>Skills</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td>
                                    <div>
                                        <strong><?= htmlspecialchars($log['intern_name']) ?></strong>
                                        <br>
                                        <small class="text-muted"><?= htmlspecialchars($log['intern_email']) ?></small>
                                    </div>
                                </td>
                                <td>
                                    <strong><?= formatDate($log['date']) ?></strong>
                                </td>
                                <td>
                                    <div style="max-width: 300px;">
                                        <?= htmlspecialchars(substr($log['task_description'], 0, 100)) ?>
                                        <?= strlen($log['task_description']) > 100 ? '...' : '' ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($log['skills']): ?>
                                        <span class="badge bg-info"><?= htmlspecialchars($log['skills']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $log['status'] === 'approved' ? 'success' : ($log['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($log['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= formatDate($log['created_at'], 'M j, Y g:i A') ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?page=supervisor&action=reviewLog&id=<?= $log['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Review">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if ($log['status'] === 'pending'): ?>
                                            <a href="index.php?page=supervisor&action=reviewLog&id=<?= $log['id'] ?>" 
                                               class="btn btn-sm btn-outline-success" title="Quick Approve">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
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

<?php if (!empty($logs)): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Review Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-primary"><?= count($logs) ?></h3>
                            <p class="text-muted">Total Logs</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-warning"><?= count(array_filter($logs, function($log) { return $log['status'] === 'pending'; })) ?></h3>
                            <p class="text-muted">Pending Review</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success"><?= count(array_filter($logs, function($log) { return $log['status'] === 'approved'; })) ?></h3>
                            <p class="text-muted">Approved</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-danger"><?= count(array_filter($logs, function($log) { return $log['status'] === 'rejected'; })) ?></h3>
                            <p class="text-muted">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>


