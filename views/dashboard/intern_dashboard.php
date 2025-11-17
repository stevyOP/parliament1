<?php
$page_title = 'Intern Dashboard';
include 'views/layouts/header.php';

// Extract variables for use in the view and set defaults
extract($data ?? []);
$logs_this_week = $logs_this_week ?? 0;
$pending_logs = $pending_logs ?? 0;
$approved_logs = $approved_logs ?? 0;
$total_logs = $total_logs ?? 0;
$recent_logs = $recent_logs ?? [];
$recent_evaluations = $recent_evaluations ?? [];
$profile = $profile ?? null;
$recent_announcements = $recent_announcements ?? [];
$skills_summary = $skills_summary ?? [];
$calendar_logs = $calendar_logs ?? [];
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
                    <h3 class="mb-0"><?= $approved_logs ?></h3>
                    <p class="mb-0">Approved Logs</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
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
</div>

<!-- Internship Progress Bar -->
<?php if ($profile): ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Internship Progress</h6>
                    <span class="badge bg-info"><?= $profile ? getDepartmentName($profile['department']) : 'N/A' ?></span>
                </div>
                <?php
                $start = new DateTime($profile['start_date']);
                $end = new DateTime($profile['end_date']);
                $now = new DateTime();
                $total_days = $start->diff($end)->days;
                $elapsed_days = $start->diff($now)->days;
                $progress = min(100, max(0, ($elapsed_days / $total_days) * 100));
                $remaining_days = max(0, $end->diff($now)->days);
                ?>
                <div class="progress mb-2" style="height: 25px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                         role="progressbar" 
                         style="width: <?= round($progress) ?>%"
                         aria-valuenow="<?= round($progress) ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        <?= round($progress) ?>%
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <small class="text-muted">
                        <i class="fas fa-calendar-check me-1"></i>Started: <?= formatDate($profile['start_date']) ?>
                    </small>
                    <small class="text-muted">
                        <i class="fas fa-hourglass-half me-1"></i><?= $remaining_days ?> days remaining
                    </small>
                    <small class="text-muted">
                        <i class="fas fa-calendar-times me-1"></i>Ends: <?= formatDate($profile['end_date']) ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <!-- Recent Logs -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Recent Daily Logs
                </h5>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <?php if (empty($recent_logs)): ?>
                    <p class="text-muted text-center py-3">No logs submitted yet.</p>
                    <div class="text-center">
                        <a href="index.php?page=intern&action=addLog" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Your First Log
                        </a>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($recent_logs, 0, 5) as $log): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= formatDate($log['date']) ?></h6>
                                        <p class="mb-1 text-muted small">
                                            <?= htmlspecialchars(substr($log['task_description'], 0, 60)) ?>...
                                        </p>
                                        <small class="text-muted">
                                            <span class="badge bg-<?= $log['status'] === 'approved' ? 'success' : ($log['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                                <?= ucfirst($log['status']) ?>
                                            </span>
                                        </small>
                                    </div>
                                    <div class="text-end ms-2">
                                        <a href="index.php?page=intern&action=viewLog&id=<?= $log['id'] ?>" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="index.php?page=intern&action=logs" class="btn btn-sm btn-outline-primary">
                            View All Logs <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Announcements -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bullhorn me-2"></i>Announcements
                </h5>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <?php if (empty($recent_announcements)): ?>
                    <p class="text-muted text-center py-3">No announcements yet.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_announcements as $announcement): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= htmlspecialchars($announcement['title']) ?></h6>
                                        <p class="mb-1 text-muted small">
                                            <?= htmlspecialchars(substr($announcement['message'], 0, 80)) ?>
                                            <?= strlen($announcement['message']) > 80 ? '...' : '' ?>
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($announcement['created_by_name']) ?> • 
                                            <i class="fas fa-clock me-1"></i><?= formatDate($announcement['date_created'], 'M j, Y') ?>
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

    <!-- Monthly Calendar View -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i><?= date('F Y') ?> Activity
                </h5>
            </div>
            <div class="card-body">
                <div class="calendar-mini">
                    <?php
                    $month = date('m');
                    $year = date('Y');
                    $first_day = mktime(0, 0, 0, $month, 1, $year);
                    $days_in_month = date('t', $first_day);
                    $day_of_week = date('w', $first_day);
                    ?>
                    <div class="row text-center mb-2">
                        <div class="col"><small class="fw-bold">Sun</small></div>
                        <div class="col"><small class="fw-bold">Mon</small></div>
                        <div class="col"><small class="fw-bold">Tue</small></div>
                        <div class="col"><small class="fw-bold">Wed</small></div>
                        <div class="col"><small class="fw-bold">Thu</small></div>
                        <div class="col"><small class="fw-bold">Fri</small></div>
                        <div class="col"><small class="fw-bold">Sat</small></div>
                    </div>
                    <?php
                    $day = 1;
                    for ($week = 0; $week < 6 && $day <= $days_in_month; $week++) {
                        echo '<div class="row text-center mb-1">';
                        for ($dow = 0; $dow < 7; $dow++) {
                            if (($week == 0 && $dow < $day_of_week) || $day > $days_in_month) {
                                echo '<div class="col"><small>&nbsp;</small></div>';
                            } else {
                                $current_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                $status = isset($calendar_logs[$current_date]) ? $calendar_logs[$current_date] : null;
                                $bg_class = $status === 'approved' ? 'bg-success' : ($status === 'pending' ? 'bg-warning' : ($status === 'rejected' ? 'bg-danger' : 'bg-light'));
                                $text_class = $status ? 'text-white' : 'text-dark';
                                $is_today = $day == date('d') ? 'border border-primary border-2' : '';
                                echo '<div class="col"><small class="d-block rounded ' . $bg_class . ' ' . $text_class . ' ' . $is_today . ' p-1" style="font-size: 11px;">' . $day . '</small></div>';
                                $day++;
                            }
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
                <hr>
                <div class="d-flex justify-content-around text-center small">
                    <div>
                        <span class="badge bg-success">&nbsp;</span>
                        <small class="d-block">Approved</small>
                    </div>
                    <div>
                        <span class="badge bg-warning">&nbsp;</span>
                        <small class="d-block">Pending</small>
                    </div>
                    <div>
                        <span class="badge bg-danger">&nbsp;</span>
                        <small class="d-block">Rejected</small>
                    </div>
                    <div>
                        <span class="badge bg-light border">&nbsp;</span>
                        <small class="d-block">No Log</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Skills Summary & Recent Evaluations -->
<div class="row mt-4">
    <!-- Skills Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Skills Summary
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($skills_summary)): ?>
                    <p class="text-muted text-center py-3">No skills recorded yet.</p>
                    <small class="text-muted d-block text-center">Add skills to your daily logs to track your learning progress.</small>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($skills_summary as $skill => $count): ?>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($skill) ?>">
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        <?= htmlspecialchars($skill) ?>
                                    </span>
                                    <span class="badge bg-primary"><?= $count ?></span>
                                </div>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar bg-primary" style="width: <?= min(100, ($count / max($skills_summary)) * 100) ?>%"></div>
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
                    <div class="text-center mt-3">
                        <a href="index.php?page=intern&action=evaluations" class="btn btn-sm btn-outline-primary">
                            View All Evaluations <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($profile): ?>
<div class="row mt-4">
    <!-- Profile Information -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>My Profile
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="40%"><strong>Name:</strong></td>
                                <td><?= htmlspecialchars($_SESSION['name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?= htmlspecialchars($_SESSION['email']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Department:</strong></td>
                                <td><span class="badge bg-info"><?= getDepartmentName($profile['department']) ?></span></td>
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

    <!-- Performance Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Performance Summary
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_evaluations)): ?>
                    <?php
                    $total_evals = count($recent_evaluations);
                    $avg_technical = array_sum(array_column($recent_evaluations, 'rating_technical')) / $total_evals;
                    $avg_softskills = array_sum(array_column($recent_evaluations, 'rating_softskills')) / $total_evals;
                    $avg_overall = ($avg_technical + $avg_softskills) / 2;
                    
                    $total_logs_count = count($recent_logs);
                    $approved_logs = count(array_filter($recent_logs, function($log) {
                        return $log['status'] === 'approved';
                    }));
                    $approval_rate = $total_logs_count > 0 ? ($approved_logs / $total_logs_count) * 100 : 0;
                    ?>
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <h4 class="text-primary mb-1"><?= number_format($avg_technical, 1) ?></h4>
                            <small class="text-muted">Technical</small>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: <?= ($avg_technical/5)*100 ?>%"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <h4 class="text-success mb-1"><?= number_format($avg_softskills, 1) ?></h4>
                            <small class="text-muted">Soft Skills</small>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: <?= ($avg_softskills/5)*100 ?>%"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info mb-1"><?= number_format($avg_overall, 1) ?></h4>
                            <small class="text-muted">Overall</small>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-info" style="width: <?= ($avg_overall/5)*100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-trophy text-warning me-2"></i>Performance Level</span>
                            <strong>
                                <span class="badge bg-<?= $avg_overall >= 4 ? 'success' : ($avg_overall >= 3 ? 'info' : 'warning') ?>">
                                    <?php
                                    if ($avg_overall >= 4.5) echo 'Excellent';
                                    elseif ($avg_overall >= 3.5) echo 'Very Good';
                                    elseif ($avg_overall >= 2.5) echo 'Good';
                                    else echo 'Needs Improvement';
                                    ?>
                                </span>
                            </strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-check-circle text-success me-2"></i>Log Approval Rate</span>
                            <strong><?= round($approval_rate) ?>%</strong>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-<?= $approval_rate >= 80 ? 'success' : ($approval_rate >= 60 ? 'warning' : 'danger') ?>" 
                                 style="width: <?= $approval_rate ?>%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-clipboard-check text-info me-2"></i>Total Evaluations</span>
                            <strong><?= $total_evals ?></strong>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No performance data available yet.</p>
                        <small class="text-muted">Your supervisor will evaluate your performance weekly.</small>
                    </div>
                <?php endif; ?>
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
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=intern&action=addLog" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-plus fa-2x d-block mb-2"></i>
                            <span>Add Daily Log</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=intern&action=logs" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-clipboard-list fa-2x d-block mb-2"></i>
                            <span>View All Logs</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=intern&action=evaluations" class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-star fa-2x d-block mb-2"></i>
                            <span>My Evaluations</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=intern&action=attendance" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-calendar-check fa-2x d-block mb-2"></i>
                            <span>Attendance</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=intern&action=statistics" class="btn btn-outline-danger w-100 py-3">
                            <i class="fas fa-chart-bar fa-2x d-block mb-2"></i>
                            <span>My Statistics</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=intern&action=weeklyReport" class="btn btn-outline-secondary w-100 py-3">
                            <i class="fas fa-file-pdf fa-2x d-block mb-2"></i>
                            <span>Weekly Report</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="index.php?page=profile" class="btn btn-outline-dark w-100 py-3">
                            <i class="fas fa-user fa-2x d-block mb-2"></i>
                            <span>My Profile</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <button onclick="window.print()" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-print fa-2x d-block mb-2"></i>
                            <span>Print Dashboard</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>


