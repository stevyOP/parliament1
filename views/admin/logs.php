<?php
$page_title = 'All Daily Logs';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-clipboard-list me-2"></i>All Daily Logs
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daily Logs</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <span class="badge bg-primary fs-6">
                <i class="fas fa-user-shield me-1"></i>Administrator View
            </span>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-3">
            <input type="hidden" name="page" value="admin">
            <input type="hidden" name="action" value="logs">
            
            <div class="col-md-3">
                <label for="intern_filter" class="form-label">Intern</label>
                <select class="form-select" id="intern_filter" name="intern_id">
                    <option value="">All Interns</option>
                    <?php foreach ($interns as $intern): ?>
                        <option value="<?= $intern['id'] ?>" <?= ($filter_intern_id == $intern['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($intern['name']) ?> - <?= getDepartmentName($intern['department']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="status_filter" class="form-label">Status</label>
                <select class="form-select" id="status_filter" name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" <?= $filter_status === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $filter_status === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= $filter_status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="<?= $filter_date_from ?>">
            </div>
            
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="<?= $filter_date_to ?>">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Summary -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h3 class="mb-0"><?= $total_logs ?></h3>
                <p class="mb-0">Total Logs</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h3 class="mb-0"><?= $pending_logs ?></h3>
                <p class="mb-0">Pending Review</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h3 class="mb-0"><?= $approved_logs ?></h3>
                <p class="mb-0">Approved</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h3 class="mb-0"><?= $rejected_logs ?></h3>
                <p class="mb-0">Rejected</p>
            </div>
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
                <h5 class="text-muted">No logs found</h5>
                <p class="text-muted">No daily logs match your filter criteria.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Intern</th>
                            <th>Department</th>
                            <th>Task Description</th>
                            <th>Skills</th>
                            <th>Status</th>
                            <th>Supervisor</th>
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
                                        <?= formatDate($log['created_at'], 'M j, g:i A') ?>
                                    </small>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($log['intern_name']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= getDepartmentName($log['department']) ?></span>
                                </td>
                                <td>
                                    <div style="max-width: 250px;">
                                        <?= htmlspecialchars(substr($log['task_description'], 0, 80)) ?>
                                        <?= strlen($log['task_description']) > 80 ? '...' : '' ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($log['skills']): ?>
                                        <?php 
                                        $skills = explode(',', $log['skills']);
                                        foreach (array_slice($skills, 0, 2) as $skill): 
                                        ?>
                                            <span class="badge bg-secondary mb-1"><?= htmlspecialchars(trim($skill)) ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($skills) > 2): ?>
                                            <span class="badge bg-light text-dark">+<?= count($skills) - 2 ?></span>
                                        <?php endif; ?>
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
                                    <?php if ($log['supervisor_name']): ?>
                                        <small><?= htmlspecialchars($log['supervisor_name']) ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="index.php?page=admin&action=viewLog&id=<?= $log['id'] ?>" 
                                       class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="fas fa-eye"></i>
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

<?php include 'views/layouts/footer.php'; ?>
