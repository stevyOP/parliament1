<?php
$page_title = 'View Daily Log';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-eye me-2"></i>View Daily Log
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=intern&action=logs">Daily Logs</a></li>
                    <li class="breadcrumb-item active">View Log</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <a href="index.php?page=intern&action=logs" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Logs
                </a>
                <?php if ($log['status'] === 'pending' && strtotime($log['created_at']) > (time() - 86400)): ?>
                    <a href="index.php?page=intern&action=editLog&id=<?= $log['id'] ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Log
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Daily Log Details
                    </h5>
                    <span class="badge bg-<?= $log['status'] === 'approved' ? 'success' : ($log['status'] === 'pending' ? 'warning' : 'danger') ?> fs-6">
                        <?= ucfirst($log['status']) ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Date</h6>
                        <p class="fs-5">
                            <i class="fas fa-calendar me-2"></i>
                            <?= formatDate($log['date'], 'l, F j, Y') ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Submitted</h6>
                        <p class="fs-5">
                            <i class="fas fa-clock me-2"></i>
                            <?= formatDate($log['created_at'], 'M j, Y g:i A') ?>
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Task Description</h6>
                    <div class="border rounded p-3 bg-light">
                        <p class="mb-0"><?= nl2br(htmlspecialchars($log['task_description'])) ?></p>
                    </div>
                </div>

                <?php if ($log['skills']): ?>
                <div class="mb-4">
                    <h6 class="text-muted">Skills Learned</h6>
                    <p>
                        <i class="fas fa-graduation-cap me-2"></i>
                        <?= htmlspecialchars($log['skills']) ?>
                    </p>
                </div>
                <?php endif; ?>

                <?php if ($log['supervisor_comment']): ?>
                <div class="mb-4">
                    <h6 class="text-muted">Supervisor Comment</h6>
                    <div class="border rounded p-3 bg-info bg-opacity-10">
                        <p class="mb-0">
                            <i class="fas fa-comment me-2"></i>
                            <?= nl2br(htmlspecialchars($log['supervisor_comment'])) ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($log['updated_at'] && $log['updated_at'] !== $log['created_at']): ?>
                <div class="mb-4">
                    <h6 class="text-muted">Last Updated</h6>
                    <p class="text-muted">
                        <i class="fas fa-edit me-2"></i>
                        <?= formatDate($log['updated_at'], 'M j, Y g:i A') ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Log Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted">Status</h6>
                    <span class="badge bg-<?= $log['status'] === 'approved' ? 'success' : ($log['status'] === 'pending' ? 'warning' : 'danger') ?> fs-6">
                        <?= ucfirst($log['status']) ?>
                    </span>
                </div>

                <div class="mb-3">
                    <h6 class="text-muted">Created</h6>
                    <p class="mb-0"><?= formatDate($log['created_at'], 'M j, Y g:i A') ?></p>
                </div>

                <?php if ($log['updated_at'] && $log['updated_at'] !== $log['created_at']): ?>
                <div class="mb-3">
                    <h6 class="text-muted">Last Updated</h6>
                    <p class="mb-0"><?= formatDate($log['updated_at'], 'M j, Y g:i A') ?></p>
                </div>
                <?php endif; ?>

                <div class="mb-3">
                    <h6 class="text-muted">Can Edit</h6>
                    <p class="mb-0">
                        <?php if ($log['status'] === 'pending' && strtotime($log['created_at']) > (time() - 86400)): ?>
                            <span class="badge bg-success">Yes</span>
                            <small class="text-muted d-block">Within 24 hours</small>
                        <?php else: ?>
                            <span class="badge bg-secondary">No</span>
                            <small class="text-muted d-block">
                                <?php if ($log['status'] !== 'pending'): ?>
                                    Already reviewed
                                <?php else: ?>
                                    Time limit exceeded
                                <?php endif; ?>
                            </small>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> You can only edit logs within 24 hours of creation and if they haven't been reviewed yet.
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="index.php?page=intern&action=logs" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>All Logs
                    </a>
                    
                    <?php if ($log['status'] === 'pending' && strtotime($log['created_at']) > (time() - 86400)): ?>
                        <a href="index.php?page=intern&action=editLog&id=<?= $log['id'] ?>" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit Log
                        </a>
                    <?php endif; ?>
                    
                    <a href="index.php?page=intern&action=weeklyReport" class="btn btn-outline-info">
                        <i class="fas fa-file-pdf me-2"></i>Weekly Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>


