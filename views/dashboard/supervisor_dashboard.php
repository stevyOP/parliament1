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

<!-- Beta Tester Welcome Notice -->
<div class="beta-notice-banner" id="betaNotice">
    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" aria-label="Close" onclick="document.getElementById('betaNotice').style.display='none'"></button>
    <h5>
        <i class="fas fa-star"></i>
        Thank You for Being a Beta Tester!
    </h5>
    <p>
        <strong>Welcome to the Parliament Intern Logbook System Beta Testing Program!</strong> 
        As a supervisor, your feedback on the review and evaluation workflow is crucial. Please test all features and report any issues.
        <br><br>
        <strong>What's New in Beta v0.9.5:</strong> Enhanced profile dropdown, modern settings page, improved navigation, and better UI responsiveness.
        <br>
        <strong>Expected Stable Release:</strong> February 2025
    </p>
    <button class="btn btn-feedback" onclick="window.location.href='mailto:itsupport@parliament.lk?subject=Beta Feedback - Intern Logbook (Supervisor)'">
        <i class="fas fa-comment-dots me-2"></i>Send Feedback
    </button>
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
        <div class="stats-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
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

<!-- Priority Section: Awaiting Your Review -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card" style="border-left: 4px solid #f59e0b;">
            <div class="card-header" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-circle me-2"></i>Awaiting Your Review
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($pending_reviews)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">All caught up! No pending items.</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-clipboard-list text-warning me-2"></i>
                                    <strong>You have <?= count($pending_reviews) ?> new logbook <?= count($pending_reviews) === 1 ? 'entry' : 'entries' ?> to review.</strong>
                                </div>
                                <a href="index.php?page=supervisor&action=logs&status=pending" class="btn btn-warning btn-sm">
                                    <i class="fas fa-arrow-right me-1"></i>Review Logs
                                </a>
                            </div>
                        </div>
                        <?php
                        $evaluations_due = 0;
                        $current_week = date('W');
                        foreach ($assigned_interns as $intern) {
                            $has_eval_this_week = false;
                            foreach ($recent_evaluations as $eval) {
                                if ($eval['intern_id'] == $intern['user_id'] && date('W', strtotime($eval['created_at'])) == $current_week) {
                                    $has_eval_this_week = true;
                                    break;
                                }
                            }
                            if (!$has_eval_this_week) {
                                $evaluations_due++;
                            }
                        }
                        if ($evaluations_due > 0): ?>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-star text-info me-2"></i>
                                    <strong>You have <?= $evaluations_due ?> <?= $evaluations_due === 1 ? 'evaluation' : 'evaluations' ?> due this week.</strong>
                                </div>
                                <a href="index.php?page=supervisor&action=evaluations" class="btn btn-info btn-sm">
                                    <i class="fas fa-arrow-right me-1"></i>View Evaluations
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- My Interns Overview -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>My Interns
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($assigned_interns)): ?>
                    <p class="text-muted text-center py-3">No assigned interns yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Intern Name</th>
                                    <th>Status</th>
                                    <th>Quick Links</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assigned_interns as $intern): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($intern['intern_name']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= getDepartmentName($intern['department']) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $intern['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($intern['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="index.php?page=supervisor&action=logs&intern_id=<?= $intern['user_id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="View Logs">
                                                <i class="fas fa-clipboard-list"></i>
                                            </a>
                                            <a href="index.php?page=profile&user_id=<?= $intern['user_id'] ?>" 
                                               class="btn btn-sm btn-outline-info" title="View Profile">
                                                <i class="fas fa-user"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-2">
                        <a href="index.php?page=supervisor&action=interns" class="btn btn-sm btn-outline-primary">
                            View All Interns <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Activity Feed -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-stream me-2"></i>Recent Activity Feed
                </h5>
            </div>
            <div class="card-body">
                <?php 
                $stmt = $db->prepare("
                    SELECT dl.*, u.name as intern_name,
                           TIMESTAMPDIFF(MINUTE, dl.created_at, NOW()) as minutes_ago
                    FROM daily_logs dl
                    JOIN users u ON dl.intern_id = u.id
                    JOIN intern_profiles ip ON dl.intern_id = ip.user_id
                    WHERE ip.supervisor_id = ?
                    ORDER BY dl.created_at DESC
                    LIMIT 5
                ");
                $stmt->execute([$_SESSION['user_id']]);
                $recent_activity = $stmt->fetchAll();
                ?>
                <?php if (empty($recent_activity)): ?>
                    <p class="text-muted text-center py-3">No recent activity.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_activity as $activity): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <div class="user-avatar-circle" style="width: 36px; height: 36px; background: linear-gradient(135deg, #d4af37, #f4d03f);">
                                            <?= strtoupper(substr($activity['intern_name'], 0, 1)) ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1">
                                            <strong><?= htmlspecialchars($activity['intern_name']) ?></strong> logged 
                                            <?= $activity['hours_worked'] ?> hours on 
                                            "<?= htmlspecialchars(substr($activity['task_description'], 0, 60)) ?><?= strlen($activity['task_description']) > 60 ? '...' : '' ?>"
                                        </p>
                                        <small class="text-muted">
                                            <?php 
                                            $minutes = $activity['minutes_ago'];
                                            if ($minutes < 60) {
                                                echo $minutes . ' min ago';
                                            } elseif ($minutes < 1440) {
                                                echo floor($minutes / 60) . 'h ago';
                                            } else {
                                                echo floor($minutes / 1440) . 'd ago';
                                            }
                                            ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-end mt-2">
                        <a href="index.php?page=supervisor&action=logs" class="btn btn-sm btn-outline-primary">
                            View All Logs <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Performance Snapshot -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Team Performance Snapshot
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($assigned_interns)): ?>
                    <p class="text-muted text-center py-3">No data available yet.</p>
                <?php else: ?>
                    <?php
                    $total_logs = 0;
                    $approved_logs = 0;
                    $pending_logs = 0;
                    $total_hours_this_week = 0;
                    
                    foreach ($assigned_interns as $intern) {
                        $stmt = $db->prepare("
                            SELECT COUNT(*) as count, 
                                   SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                                   SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                                   SUM(CASE WHEN date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) THEN hours_worked ELSE 0 END) as hours_week
                            FROM daily_logs 
                            WHERE intern_id = ?
                        ");
                        $stmt->execute([$intern['user_id']]);
                        $stats = $stmt->fetch();
                        
                        $total_logs += $stats['count'];
                        $approved_logs += $stats['approved'];
                        $pending_logs += $stats['pending'];
                        $total_hours_this_week += $stats['hours_week'];
                    }
                    
                    $completion_rate = $total_logs > 0 ? round(($approved_logs / $total_logs) * 100) : 0;
                    ?>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Task Completion Rate</span>
                            <strong><?= $completion_rate ?>%</strong>
                        </div>
                        <div class="progress" style="height: 24px;">
                            <div class="progress-bar" role="progressbar" style="width: <?= $completion_rate ?>%;" 
                                 aria-valuenow="<?= $completion_rate ?>" aria-valuemin="0" aria-valuemax="100">
                                <?= $completion_rate ?>%
                            </div>
                        </div>
                        <small class="text-muted"><?= $approved_logs ?> approved out of <?= $total_logs ?> total submissions</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center p-3" 
                             style="background: rgba(132, 1, 0, 0.05); border-radius: 12px;">
                            <div>
                                <i class="fas fa-clock text-primary me-2" style="font-size: 1.5rem;"></i>
                                <span class="text-muted">Hours Logged This Week</span>
                            </div>
                            <h4 class="mb-0" style="color: var(--primary-color);"><?= number_format($total_hours_this_week, 1) ?> hrs</h4>
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-2">
                                <h5 class="mb-0 text-success"><?= $approved_logs ?></h5>
                                <small class="text-muted">Approved</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2">
                                <h5 class="mb-0 text-warning"><?= $pending_logs ?></h5>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2">
                                <h5 class="mb-0 text-info"><?= $total_logs ?></h5>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Intern</th>
                                    <th>Week</th>
                                    <th>Technical</th>
                                    <th>Soft Skills</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($recent_evaluations, 0, 3) as $evaluation): ?>
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
                    <div class="text-end mt-2">
                        <a href="index.php?page=supervisor&action=evaluations" class="btn btn-sm btn-outline-primary">
                            View All Evaluations <i class="fas fa-arrow-right ms-1"></i>
                        </a>
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
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="index.php?page=supervisor&action=evaluations&new=true" class="btn btn-outline-primary w-100 py-3" style="border-width: 2px;">
                            <i class="fas fa-plus-circle d-block mb-2" style="font-size: 2rem;"></i>
                            <strong>Start New Evaluation</strong>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=supervisor&action=logs&status=pending" class="btn btn-outline-warning w-100 py-3" style="border-width: 2px;">
                            <i class="fas fa-clipboard-check d-block mb-2" style="font-size: 2rem;"></i>
                            <strong>Review Pending Logs</strong>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=supervisor&action=reports" class="btn btn-outline-info w-100 py-3" style="border-width: 2px;">
                            <i class="fas fa-chart-bar d-block mb-2" style="font-size: 2rem;"></i>
                            <strong>Generate Report</strong>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=supervisor&action=interns" class="btn btn-outline-success w-100 py-3" style="border-width: 2px;">
                            <i class="fas fa-users-cog d-block mb-2" style="font-size: 2rem;"></i>
                            <strong>Manage Interns</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>


