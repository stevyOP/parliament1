<?php
$page_title = 'All Evaluations';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-star me-2"></i>All Evaluations
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Evaluations</li>
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
            <input type="hidden" name="action" value="evaluations">
            
            <div class="col-md-4">
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
                <label for="department_filter" class="form-label">Department</label>
                <select class="form-select" id="department_filter" name="department">
                    <option value="">All Departments</option>
                    <option value="1" <?= $filter_department == 1 ? 'selected' : '' ?>>Information Technology</option>
                    <option value="2" <?= $filter_department == 2 ? 'selected' : '' ?>>Human Resources</option>
                    <option value="3" <?= $filter_department == 3 ? 'selected' : '' ?>>Finance</option>
                    <option value="4" <?= $filter_department == 4 ? 'selected' : '' ?>>Legal Affairs</option>
                    <option value="5" <?= $filter_department == 5 ? 'selected' : '' ?>>Public Relations</option>
                    <option value="6" <?= $filter_department == 6 ? 'selected' : '' ?>>Research & Development</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="week_filter" class="form-label">Week Number</label>
                <input type="number" class="form-control" id="week_filter" name="week_no" 
                       value="<?= $filter_week_no ?>" min="1" placeholder="Enter week number">
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
                <h3 class="mb-0"><?= $total_evaluations ?></h3>
                <p class="mb-0">Total Evaluations</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h3 class="mb-0"><?= number_format($avg_technical, 1) ?>/5</h3>
                <p class="mb-0">Avg Technical Rating</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h3 class="mb-0"><?= number_format($avg_softskills, 1) ?>/5</h3>
                <p class="mb-0">Avg Soft Skills Rating</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h3 class="mb-0"><?= number_format($avg_overall, 1) ?>/5</h3>
                <p class="mb-0">Overall Average</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Evaluation Records
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($evaluations)): ?>
            <div class="text-center py-5">
                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No evaluations found</h5>
                <p class="text-muted">No evaluations match your filter criteria.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th>Week</th>
                            <th>Intern</th>
                            <th>Department</th>
                            <th>Supervisor</th>
                            <th>Technical</th>
                            <th>Soft Skills</th>
                            <th>Overall</th>
                            <th>Comments</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $evaluation): ?>
                            <?php 
                            $overall = ($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2;
                            $rating_class = $overall >= 4 ? 'success' : ($overall >= 3 ? 'info' : 'warning');
                            ?>
                            <tr>
                                <td>
                                    <strong>Week <?= $evaluation['week_no'] ?></strong>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($evaluation['intern_name']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?= getDepartmentName($evaluation['department']) ?></span>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars($evaluation['supervisor_name']) ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary me-2"><?= $evaluation['rating_technical'] ?>/5</span>
                                        <div class="progress" style="width: 60px; height: 8px;">
                                            <div class="progress-bar bg-primary" style="width: <?= ($evaluation['rating_technical']/5)*100 ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success me-2"><?= $evaluation['rating_softskills'] ?>/5</span>
                                        <div class="progress" style="width: 60px; height: 8px;">
                                            <div class="progress-bar bg-success" style="width: <?= ($evaluation['rating_softskills']/5)*100 ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $rating_class ?> px-3">
                                        <?= number_format($overall, 1) ?>/5
                                    </span>
                                </td>
                                <td>
                                    <?php if ($evaluation['comments']): ?>
                                        <div style="max-width: 200px;">
                                            <?= htmlspecialchars(substr($evaluation['comments'], 0, 50)) ?>
                                            <?= strlen($evaluation['comments']) > 50 ? '...' : '' ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No comments</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= formatDate($evaluation['created_at'], 'M j, Y') ?>
                                    </small>
                                </td>
                                <td>
                                    <a href="index.php?page=admin&action=viewEvaluation&id=<?= $evaluation['id'] ?>" 
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

<!-- Performance by Department Chart -->
<?php if (!empty($evaluations)): ?>
<div class="row mt-4">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Performance by Department
                </h5>
            </div>
            <div class="card-body">
                <canvas id="departmentPerformanceChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Performance Trend
                </h5>
            </div>
            <div class="card-body">
                <canvas id="performanceTrendChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Performance by Department Chart
<?php
$dept_stats = [];
foreach ($evaluations as $eval) {
    $dept = getDepartmentName($eval['department']);
    if (!isset($dept_stats[$dept])) {
        $dept_stats[$dept] = ['technical' => [], 'softskills' => []];
    }
    $dept_stats[$dept]['technical'][] = $eval['rating_technical'];
    $dept_stats[$dept]['softskills'][] = $eval['rating_softskills'];
}

$dept_labels = [];
$dept_technical = [];
$dept_softskills = [];

foreach ($dept_stats as $dept => $ratings) {
    $dept_labels[] = $dept;
    $dept_technical[] = count($ratings['technical']) > 0 ? array_sum($ratings['technical']) / count($ratings['technical']) : 0;
    $dept_softskills[] = count($ratings['softskills']) > 0 ? array_sum($ratings['softskills']) / count($ratings['softskills']) : 0;
}
?>

const deptCtx = document.getElementById('departmentPerformanceChart');
if (deptCtx) {
    new Chart(deptCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($dept_labels) ?>,
            datasets: [
                {
                    label: 'Technical Skills',
                    data: <?= json_encode($dept_technical) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Soft Skills',
                    data: <?= json_encode($dept_softskills) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5
                }
            }
        }
    });
}

// Performance Trend Chart
<?php
$week_stats = [];
foreach ($evaluations as $eval) {
    $week = $eval['week_no'];
    if (!isset($week_stats[$week])) {
        $week_stats[$week] = [];
    }
    $week_stats[$week][] = ($eval['rating_technical'] + $eval['rating_softskills']) / 2;
}

ksort($week_stats);
$trend_weeks = [];
$trend_avg = [];

foreach ($week_stats as $week => $ratings) {
    $trend_weeks[] = 'Week ' . $week;
    $trend_avg[] = array_sum($ratings) / count($ratings);
}
?>

const trendCtx = document.getElementById('performanceTrendChart');
if (trendCtx) {
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($trend_weeks) ?>,
            datasets: [{
                label: 'Average Rating',
                data: <?= json_encode($trend_avg) ?>,
                borderColor: 'rgba(132, 1, 0, 1)',
                backgroundColor: 'rgba(132, 1, 0, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5
                }
            }
        }
    });
}
</script>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>
