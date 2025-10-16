<?php
$page_title = 'Review Log';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-eye me-2"></i>Review Daily Log
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=supervisor&action=logs">Review Logs</a></li>
                    <li class="breadcrumb-item active">Review Log</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="index.php?page=supervisor&action=logs" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Logs
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Log Details
                    </h5>
                    <span class="badge bg-<?= $log['status'] === 'approved' ? 'success' : ($log['status'] === 'pending' ? 'warning' : 'danger') ?> fs-6">
                        <?= ucfirst($log['status']) ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Intern</h6>
                        <p class="fs-5">
                            <i class="fas fa-user me-2"></i>
                            <?= htmlspecialchars($log['intern_name']) ?>
                        </p>
                        <p class="text-muted">
                            <i class="fas fa-envelope me-2"></i>
                            <?= htmlspecialchars($log['intern_email']) ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Date</h6>
                        <p class="fs-5">
                            <i class="fas fa-calendar me-2"></i>
                            <?= formatDate($log['date'], 'l, F j, Y') ?>
                        </p>
                        <p class="text-muted">
                            <i class="fas fa-clock me-2"></i>
                            Submitted: <?= formatDate($log['created_at'], 'M j, Y g:i A') ?>
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
                    <h6 class="text-muted">Your Previous Comment</h6>
                    <div class="border rounded p-3 bg-info bg-opacity-10">
                        <p class="mb-0">
                            <i class="fas fa-comment me-2"></i>
                            <?= nl2br(htmlspecialchars($log['supervisor_comment'])) ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <?php if ($log['status'] === 'pending'): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>Review Log
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=supervisor&action=processReview">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Review Decision *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select decision...</option>
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="form-label">Comments</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" 
                                  placeholder="Provide feedback or comments for the intern..."></textarea>
                        <div class="form-text">Optional: Add constructive feedback for the intern.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Review Status
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="fas fa-<?= $log['status'] === 'approved' ? 'check-circle text-success' : 'times-circle text-danger' ?> fa-3x mb-3"></i>
                    <h5 class="text-<?= $log['status'] === 'approved' ? 'success' : 'danger' ?>">
                        Log <?= ucfirst($log['status']) ?>
                    </h5>
                    <p class="text-muted">
                        This log has already been reviewed and <?= $log['status'] === 'approved' ? 'approved' : 'rejected' ?>.
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="index.php?page=supervisor&action=logs" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>All Logs
                    </a>
                    
                    <a href="index.php?page=supervisor&action=logs&intern_id=<?= $log['intern_id'] ?>" 
                       class="btn btn-outline-info">
                        <i class="fas fa-user me-2"></i>All Logs by <?= htmlspecialchars($log['intern_name']) ?>
                    </a>
                    
                    <a href="index.php?page=supervisor&action=addEvaluation&intern_id=<?= $log['intern_id'] ?>" 
                       class="btn btn-outline-success">
                        <i class="fas fa-star me-2"></i>Add Evaluation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-focus on status select
    $('#status').focus();
    
    // Show/hide comment field based on status
    $('#status').on('change', function() {
        const commentField = $('#comment');
        if ($(this).val() === 'rejected') {
            commentField.attr('required', true);
            commentField.attr('placeholder', 'Please provide reason for rejection...');
        } else {
            commentField.removeAttr('required');
            commentField.attr('placeholder', 'Provide feedback or comments for the intern...');
        }
    });
});
</script>

<?php include 'views/layouts/footer.php'; ?>


