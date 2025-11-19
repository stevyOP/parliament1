<?php
$page_title = 'View Evaluation';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-star me-2"></i>Evaluation Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=admin&action=evaluations">Evaluations</a></li>
                    <li class="breadcrumb-item active">View Evaluation</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="index.php?page=admin&action=evaluations" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Evaluations
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Week <?= $evaluation['week_no'] ?> Evaluation
                </h5>
            </div>
            <div class="card-body">
                <!-- Ratings Overview -->
                <div class="row text-center mb-4">
                    <div class="col-md-4">
                        <h3 class="text-primary mb-1"><?= $evaluation['rating_technical'] ?>/5</h3>
                        <p class="text-muted">Technical Skills</p>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-primary" style="width: <?= ($evaluation['rating_technical']/5)*100 ?>%"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h3 class="text-success mb-1"><?= $evaluation['rating_softskills'] ?>/5</h3>
                        <p class="text-muted">Soft Skills</p>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: <?= ($evaluation['rating_softskills']/5)*100 ?>%"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <?php 
                        $overall = ($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2;
                        $overall_class = $overall >= 4 ? 'success' : ($overall >= 3 ? 'info' : 'warning');
                        ?>
                        <h3 class="text-<?= $overall_class ?> mb-1"><?= number_format($overall, 1) ?>/5</h3>
                        <p class="text-muted">Overall Rating</p>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-<?= $overall_class ?>" style="width: <?= ($overall/5)*100 ?>%"></div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Performance Level -->
                <div class="mb-4">
                    <label class="form-label text-muted"><strong>Performance Level:</strong></label>
                    <p class="mb-0">
                        <span class="badge bg-<?= $overall_class ?> fs-6 px-3 py-2">
                            <?php
                            if ($overall >= 4.5) echo 'Excellent';
                            elseif ($overall >= 3.5) echo 'Very Good';
                            elseif ($overall >= 2.5) echo 'Good';
                            else echo 'Needs Improvement';
                            ?>
                        </span>
                    </p>
                </div>

                <!-- Comments -->
                <?php if ($evaluation['comments']): ?>
                    <div class="mb-3">
                        <label class="form-label text-muted"><strong>Supervisor Comments:</strong></label>
                        <div class="card border-primary">
                            <div class="card-body">
                                <?= nl2br(htmlspecialchars($evaluation['comments'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <hr>

                <!-- Timestamp -->
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label text-muted"><strong>Evaluated On:</strong></label>
                        <p class="mb-0"><?= formatDate($evaluation['created_at'], 'F j, Y g:i A') ?></p>
                    </div>
                    <?php if ($evaluation['updated_at']): ?>
                        <div class="col-md-6">
                            <label class="form-label text-muted"><strong>Last Updated:</strong></label>
                            <p class="mb-0"><?= formatDate($evaluation['updated_at'], 'F j, Y g:i A') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Intern Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate me-2"></i>Intern Information
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Name:</strong></p>
                <p class="mb-3"><?= htmlspecialchars($evaluation['intern_name']) ?></p>

                <p class="mb-2"><strong>Email:</strong></p>
                <p class="mb-3"><a href="mailto:<?= htmlspecialchars($evaluation['intern_email']) ?>"><?= htmlspecialchars($evaluation['intern_email']) ?></a></p>

                <p class="mb-2"><strong>Department:</strong></p>
                <p class="mb-0"><span class="badge bg-info"><?= getDepartmentName($evaluation['department']) ?></span></p>
            </div>
        </div>

        <!-- Supervisor Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-tie me-2"></i>Supervisor Information
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Name:</strong></p>
                <p class="mb-3"><?= htmlspecialchars($evaluation['supervisor_name']) ?></p>

                <?php if ($evaluation['supervisor_email']): ?>
                    <p class="mb-2"><strong>Email:</strong></p>
                    <p class="mb-0"><a href="mailto:<?= htmlspecialchars($evaluation['supervisor_email']) ?>"><?= htmlspecialchars($evaluation['supervisor_email']) ?></a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
