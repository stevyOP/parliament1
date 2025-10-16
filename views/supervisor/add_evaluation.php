<?php
$page_title = 'Add Evaluation';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-star me-2"></i>Add Weekly Evaluation
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=supervisor&action=evaluations">Evaluations</a></li>
                    <li class="breadcrumb-item active">Add Evaluation</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2"></i>Weekly Performance Evaluation
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=supervisor&action=createEvaluation">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="intern_id" class="form-label">
                                    <i class="fas fa-user me-2"></i>Select Intern *
                                </label>
                                <select class="form-select" id="intern_id" name="intern_id" required>
                                    <option value="">Choose an intern...</option>
                                    <?php foreach ($interns as $intern): ?>
                                        <option value="<?= $intern['user_id'] ?>" 
                                                <?= (isset($_GET['intern_id']) && $_GET['intern_id'] == $intern['user_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($intern['intern_name']) ?> 
                                            (<?= getDepartmentName($intern['department']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="week_no" class="form-label">
                                    <i class="fas fa-calendar-week me-2"></i>Week Number *
                                </label>
                                <input type="number" class="form-control" id="week_no" name="week_no" 
                                       min="1" max="52" required>
                                <div class="form-text">Enter the week number for this evaluation.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rating_technical" class="form-label">
                                    <i class="fas fa-code me-2"></i>Technical Skills Rating *
                                </label>
                                <select class="form-select" id="rating_technical" name="rating_technical" required>
                                    <option value="">Select rating...</option>
                                    <option value="1">1 - Needs Significant Improvement</option>
                                    <option value="2">2 - Below Expectations</option>
                                    <option value="3">3 - Meets Expectations</option>
                                    <option value="4">4 - Exceeds Expectations</option>
                                    <option value="5">5 - Outstanding Performance</option>
                                </select>
                                <div class="form-text">Rate the intern's technical skills and knowledge.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rating_softskills" class="form-label">
                                    <i class="fas fa-handshake me-2"></i>Soft Skills Rating *
                                </label>
                                <select class="form-select" id="rating_softskills" name="rating_softskills" required>
                                    <option value="">Select rating...</option>
                                    <option value="1">1 - Needs Significant Improvement</option>
                                    <option value="2">2 - Below Expectations</option>
                                    <option value="3">3 - Meets Expectations</option>
                                    <option value="4">4 - Exceeds Expectations</option>
                                    <option value="5">5 - Outstanding Performance</option>
                                </select>
                                <div class="form-text">Rate the intern's communication, teamwork, and professional skills.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comments" class="form-label">
                            <i class="fas fa-comment me-2"></i>Comments & Feedback
                        </label>
                        <textarea class="form-control" id="comments" name="comments" rows="4" 
                                  placeholder="Provide detailed feedback about the intern's performance, strengths, areas for improvement, and any recommendations..."></textarea>
                        <div class="form-text">
                            Provide constructive feedback to help the intern improve and grow.
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Evaluation Guidelines:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Be objective and fair in your assessment</li>
                            <li>Provide specific examples when possible</li>
                            <li>Focus on both strengths and areas for improvement</li>
                            <li>Consider the intern's learning curve and experience level</li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=supervisor&action=evaluations" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Evaluations
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Evaluation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-focus on intern selection
    $('#intern_id').focus();
    
    // Rating scale helper
    $('select[name^="rating_"]').on('change', function() {
        const rating = parseInt($(this).val());
        const fieldName = $(this).attr('name');
        
        if (rating <= 2) {
            $(this).addClass('border-warning');
        } else if (rating >= 4) {
            $(this).addClass('border-success');
        } else {
            $(this).removeClass('border-warning border-success');
        }
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        const technicalRating = parseInt($('#rating_technical').val());
        const softSkillsRating = parseInt($('#rating_softskills').val());
        
        if (technicalRating && softSkillsRating) {
            const avgRating = (technicalRating + softSkillsRating) / 2;
            
            if (avgRating < 2) {
                if (!confirm('This intern has received a very low rating. Are you sure you want to submit this evaluation?')) {
                    e.preventDefault();
                }
            }
        }
    });
});
</script>

<?php include 'views/layouts/footer.php'; ?>


