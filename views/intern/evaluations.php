<?php
$page_title = 'My Evaluations';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-star me-2"></i>My Evaluations
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Evaluations</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-star me-2"></i>Performance Evaluations
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($evaluations)): ?>
            <div class="text-center py-5">
                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No evaluations yet</h5>
                <p class="text-muted">Your supervisor will provide weekly evaluations based on your performance.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th>Week</th>
                            <th>Technical Skills</th>
                            <th>Soft Skills</th>
                            <th>Overall Rating</th>
                            <th>Comments</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $evaluation): ?>
                            <tr>
                                <td>
                                    <strong>Week <?= $evaluation['week_no'] ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?= $evaluation['rating_technical'] >= 4 ? 'success' : ($evaluation['rating_technical'] >= 3 ? 'warning' : 'danger') ?> me-2">
                                            <?= $evaluation['rating_technical'] ?>/5
                                        </span>
                                        <div class="progress" style="width: 60px; height: 8px;">
                                            <div class="progress-bar bg-<?= $evaluation['rating_technical'] >= 4 ? 'success' : ($evaluation['rating_technical'] >= 3 ? 'warning' : 'danger') ?>" 
                                                 style="width: <?= ($evaluation['rating_technical'] / 5) * 100 ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?= $evaluation['rating_softskills'] >= 4 ? 'success' : ($evaluation['rating_softskills'] >= 3 ? 'warning' : 'danger') ?> me-2">
                                            <?= $evaluation['rating_softskills'] ?>/5
                                        </span>
                                        <div class="progress" style="width: 60px; height: 8px;">
                                            <div class="progress-bar bg-<?= $evaluation['rating_softskills'] >= 4 ? 'success' : ($evaluation['rating_softskills'] >= 3 ? 'warning' : 'danger') ?>" 
                                                 style="width: <?= ($evaluation['rating_softskills'] / 5) * 100 ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $overall = ($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2;
                                    ?>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?= $overall >= 4 ? 'success' : ($overall >= 3 ? 'warning' : 'danger') ?> me-2">
                                            <?= number_format($overall, 1) ?>/5
                                        </span>
                                        <div class="progress" style="width: 60px; height: 8px;">
                                            <div class="progress-bar bg-<?= $overall >= 4 ? 'success' : ($overall >= 3 ? 'warning' : 'danger') ?>" 
                                                 style="width: <?= ($overall / 5) * 100 ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($evaluation['comments']): ?>
                                        <div style="max-width: 200px;">
                                            <?= htmlspecialchars(substr($evaluation['comments'], 0, 80)) ?>
                                            <?= strlen($evaluation['comments']) > 80 ? '...' : '' ?>
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($evaluations)): ?>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Performance Trend
                </h5>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>Performance Summary
                </h5>
            </div>
            <div class="card-body">
                <?php
                $total_evaluations = count($evaluations);
                $avg_technical = array_sum(array_column($evaluations, 'rating_technical')) / $total_evaluations;
                $avg_softskills = array_sum(array_column($evaluations, 'rating_softskills')) / $total_evaluations;
                $avg_overall = ($avg_technical + $avg_softskills) / 2;
                ?>
                <div class="row text-center">
                    <div class="col-4">
                        <h3 class="text-primary"><?= number_format($avg_technical, 1) ?></h3>
                        <small class="text-muted">Technical Skills</small>
                    </div>
                    <div class="col-4">
                        <h3 class="text-success"><?= number_format($avg_softskills, 1) ?></h3>
                        <small class="text-muted">Soft Skills</small>
                    </div>
                    <div class="col-4">
                        <h3 class="text-info"><?= number_format($avg_overall, 1) ?></h3>
                        <small class="text-muted">Overall Average</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="mt-3">
                    <h6>Performance Level</h6>
                    <?php if ($avg_overall >= 4.5): ?>
                        <span class="badge bg-success fs-6">Excellent</span>
                    <?php elseif ($avg_overall >= 3.5): ?>
                        <span class="badge bg-primary fs-6">Good</span>
                    <?php elseif ($avg_overall >= 2.5): ?>
                        <span class="badge bg-warning fs-6">Satisfactory</span>
                    <?php else: ?>
                        <span class="badge bg-danger fs-6">Needs Improvement</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Performance Trend Chart
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const evaluations = <?= json_encode($evaluations) ?>;

const weeks = evaluations.map(e => 'Week ' + e.week_no);
const technicalScores = evaluations.map(e => e.rating_technical);
const softSkillsScores = evaluations.map(e => e.rating_softskills);

new Chart(performanceCtx, {
    type: 'line',
    data: {
        labels: weeks,
        datasets: [{
            label: 'Technical Skills',
            data: technicalScores,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Soft Skills',
            data: softSkillsScores,
            borderColor: '#764ba2',
            backgroundColor: 'rgba(118, 75, 162, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 5,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});
</script>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>


