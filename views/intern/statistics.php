<?php
$page_title = 'My Statistics';
include 'views/layouts/header.php';

// Set default values if not provided
$total_logs = $total_logs ?? 0;
$approved_logs = $approved_logs ?? 0;
$pending_logs = $pending_logs ?? 0;
$rejected_logs = $rejected_logs ?? 0;
$all_logs = $all_logs ?? [];
$all_evaluations = $all_evaluations ?? [];
$skills_count = $skills_count ?? [];
$monthly_stats = $monthly_stats ?? [];
$profile = $profile ?? null;
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar me-2"></i>My Statistics
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Statistics</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Overall Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h2 class="mb-0"><?= $total_logs ?></h2>
                <p class="mb-0">Total Logs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h2 class="mb-0"><?= $approved_logs ?></h2>
                <p class="mb-0">Approved</p>
                <small><?= $total_logs > 0 ? round(($approved_logs / $total_logs) * 100) : 0 ?>%</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body text-center">
                <h2 class="mb-0"><?= $pending_logs ?></h2>
                <p class="mb-0">Pending</p>
                <small><?= $total_logs > 0 ? round(($pending_logs / $total_logs) * 100) : 0 ?>%</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body text-center">
                <h2 class="mb-0"><?= $rejected_logs ?></h2>
                <p class="mb-0">Rejected</p>
                <small><?= $total_logs > 0 ? round(($rejected_logs / $total_logs) * 100) : 0 ?>%</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Skills Breakdown -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Top Skills Learned
                </h5>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <?php if (empty($skills_count)): ?>
                    <p class="text-muted text-center py-3">No skills recorded yet.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php 
                        $max_count = max($skills_count);
                        $rank = 1;
                        foreach (array_slice($skills_count, 0, 15) as $skill => $count): 
                        ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>
                                        <span class="badge bg-secondary me-2">#<?= $rank++ ?></span>
                                        <?= htmlspecialchars($skill) ?>
                                    </span>
                                    <strong><?= $count ?> times</strong>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" 
                                         style="width: <?= ($count / $max_count) * 100 ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Performance Trend -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Performance Trend
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($all_evaluations)): ?>
                    <p class="text-muted text-center py-5">No evaluation data available yet.</p>
                <?php else: ?>
                    <div class="mb-4">
                        <h6>Average Ratings</h6>
                        <?php
                        $avg_technical = array_sum(array_column($all_evaluations, 'rating_technical')) / count($all_evaluations);
                        $avg_softskills = array_sum(array_column($all_evaluations, 'rating_softskills')) / count($all_evaluations);
                        $avg_overall = ($avg_technical + $avg_softskills) / 2;
                        ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Technical Skills</span>
                                <strong><?= number_format($avg_technical, 2) ?> / 5.00</strong>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-primary" 
                                     style="width: <?= ($avg_technical / 5) * 100 ?>%">
                                    <?= round(($avg_technical / 5) * 100) ?>%
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Soft Skills</span>
                                <strong><?= number_format($avg_softskills, 2) ?> / 5.00</strong>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" 
                                     style="width: <?= ($avg_softskills / 5) * 100 ?>%">
                                    <?= round(($avg_softskills / 5) * 100) ?>%
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Overall Performance</span>
                                <strong><?= number_format($avg_overall, 2) ?> / 5.00</strong>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-info" 
                                     style="width: <?= ($avg_overall / 5) * 100 ?>%">
                                    <?= round(($avg_overall / 5) * 100) ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h6>Weekly Evaluations</h6>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Week</th>
                                    <th>Technical</th>
                                    <th>Soft Skills</th>
                                    <th>Average</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_evaluations as $eval): ?>
                                    <tr>
                                        <td>Week <?= $eval['week_no'] ?></td>
                                        <td><?= $eval['rating_technical'] ?>/5</td>
                                        <td><?= $eval['rating_softskills'] ?>/5</td>
                                        <td>
                                            <span class="badge bg-<?= (($eval['rating_technical'] + $eval['rating_softskills']) / 2) >= 4 ? 'success' : 'warning' ?>">
                                                <?= number_format(($eval['rating_technical'] + $eval['rating_softskills']) / 2, 1) ?>/5
                                            </span>
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

<!-- Monthly Breakdown -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Monthly Log Breakdown
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($monthly_stats)): ?>
                    <p class="text-muted text-center py-3">No monthly data available.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Total Logs</th>
                                    <th>Approved</th>
                                    <th>Pending</th>
                                    <th>Rejected</th>
                                    <th>Approval Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                krsort($monthly_stats);
                                foreach ($monthly_stats as $month_key => $stats): 
                                    $approval_rate = $stats['total'] > 0 ? round(($stats['approved'] / $stats['total']) * 100) : 0;
                                ?>
                                    <tr>
                                        <td>
                                            <strong><?= date('F Y', strtotime($month_key . '-01')) ?></strong>
                                        </td>
                                        <td><?= $stats['total'] ?></td>
                                        <td>
                                            <span class="badge bg-success"><?= $stats['approved'] ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning"><?= $stats['pending'] ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger"><?= $stats['rejected'] ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 20px; min-width: 100px;">
                                                    <div class="progress-bar bg-<?= $approval_rate >= 80 ? 'success' : ($approval_rate >= 60 ? 'warning' : 'danger') ?>" 
                                                         style="width: <?= $approval_rate ?>%">
                                                        <?= $approval_rate ?>%
                                                    </div>
                                                </div>
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
    </div>
</div>

<?php if ($profile): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Internship Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Department:</strong></td>
                                <td><?= getDepartmentName($profile['department']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Supervisor:</strong></td>
                                <td><?= htmlspecialchars($profile['supervisor_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Start Date:</strong></td>
                                <td><?= formatDate($profile['start_date']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>End Date:</strong></td>
                                <td><?= formatDate($profile['end_date']) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <?php
                        $start = new DateTime($profile['start_date']);
                        $end = new DateTime($profile['end_date']);
                        $now = new DateTime();
                        $total_days = $start->diff($end)->days;
                        $elapsed_days = min($start->diff($now)->days, $total_days);
                        $progress = min(100, max(0, ($elapsed_days / $total_days) * 100));
                        ?>
                        <div class="mb-3">
                            <h6>Internship Progress: <?= round($progress) ?>%</h6>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped bg-primary" 
                                     style="width: <?= $progress ?>%">
                                    <?= $elapsed_days ?> / <?= $total_days ?> days
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mb-0">
                            <strong>Total Logs Submitted:</strong> <?= $total_logs ?> logs<br>
                            <strong>Average Approval Rate:</strong> <?= $total_logs > 0 ? round(($approved_logs / $total_logs) * 100) : 0 ?>%<br>
                            <strong>Skills Learned:</strong> <?= count($skills_count) ?> unique skills
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
