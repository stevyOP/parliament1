<?php
$page_title = 'Daily Logs';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-clipboard-list me-2"></i>Daily Logs
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daily Logs</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="index.php?page=intern&action=addLog" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Log
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>All Daily Logs
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($logs)): ?>
            <div class="text-center py-5">
                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No logs submitted yet</h5>
                <p class="text-muted">Start documenting your daily activities and learning progress.</p>
                <a href="index.php?page=intern&action=addLog" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Your First Log
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Task Description</th>
                            <th>Skills Learned</th>
                            <th>Status</th>
                            <th>Supervisor Comment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td>
                                    <strong><?= formatDate($log['date']) ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= formatDate($log['created_at'], 'M j, Y g:i A') ?>
                                    </small>
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
                                    <?php if ($log['supervisor_comment']): ?>
                                        <div style="max-width: 200px;">
                                            <?= htmlspecialchars(substr($log['supervisor_comment'], 0, 50)) ?>
                                            <?= strlen($log['supervisor_comment']) > 50 ? '...' : '' ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No comment</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?page=intern&action=viewLog&id=<?= $log['id'] ?>" 
                                           class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if ($log['status'] === 'pending' && strtotime($log['created_at']) > (time() - 86400)): ?>
                                            <a href="index.php?page=intern&action=editLog&id=<?= $log['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <a href="index.php?page=intern&action=deleteLog&id=<?= $log['id'] ?>" 
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
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>


