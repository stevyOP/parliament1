<?php
$page_title = 'View Log';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-clipboard-list me-2"></i>Daily Log Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=admin&action=logs">Daily Logs</a></li>
                    <li class="breadcrumb-item active">View Log</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="index.php?page=admin&action=logs" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Logs
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Log Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted"><strong>Date:</strong></label>
                        <p class="mb-0"><?= formatDate($log['date'], 'F j, Y (l)') ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted"><strong>Status:</strong></label>
                        <p class="mb-0">
                            <span class="badge bg-<?= $log['status'] === 'approved' ? 'success' : ($log['status'] === 'pending' ? 'warning' : 'danger') ?> fs-6">
                                <?= ucfirst($log['status']) ?>
                            </span>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label text-muted"><strong>Task Description:</strong></label>
                    <div class="card bg-light">
                        <div class="card-body">
                            <?= nl2br(htmlspecialchars($log['task_description'])) ?>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted"><strong>Skills Learned:</strong></label>
                    <?php if ($log['skills']): ?>
                        <div>
                            <?php foreach (explode(',', $log['skills']) as $skill): ?>
                                <span class="badge bg-secondary me-1 mb-1"><?= htmlspecialchars(trim($skill)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No skills specified</p>
                    <?php endif; ?>
                </div>

                <?php if ($log['supervisor_comment']): ?>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label text-muted"><strong>Supervisor Comment:</strong></label>
                        <div class="card border-primary">
                            <div class="card-body">
                                <?= nl2br(htmlspecialchars($log['supervisor_comment'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label text-muted"><strong>Submitted:</strong></label>
                        <p class="mb-0"><?= formatDate($log['created_at'], 'M j, Y g:i A') ?></p>
                    </div>
                    <?php if ($log['updated_at']): ?>
                        <div class="col-md-6">
                            <label class="form-label text-muted"><strong>Last Updated:</strong></label>
                            <p class="mb-0"><?= formatDate($log['updated_at'], 'M j, Y g:i A') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Intern Information
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Name:</strong></p>
                <p class="mb-3"><?= htmlspecialchars($log['intern_name']) ?></p>

                <p class="mb-2"><strong>Email:</strong></p>
                <p class="mb-3"><a href="mailto:<?= htmlspecialchars($log['intern_email']) ?>"><?= htmlspecialchars($log['intern_email']) ?></a></p>

                <p class="mb-2"><strong>Department:</strong></p>
                <p class="mb-3"><span class="badge bg-info"><?= getDepartmentName($log['department']) ?></span></p>

                <?php if ($log['supervisor_name']): ?>
                    <p class="mb-2"><strong>Supervisor:</strong></p>
                    <p class="mb-0"><?= htmlspecialchars($log['supervisor_name']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
