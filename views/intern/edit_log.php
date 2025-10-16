<?php
$page_title = 'Edit Daily Log';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-edit me-2"></i>Edit Daily Log
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=intern&action=logs">Daily Logs</a></li>
                    <li class="breadcrumb-item active">Edit Log</li>
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
                    <i class="fas fa-edit me-2"></i>Edit Daily Log Entry
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=intern&action=updateLog">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date" class="form-label">
                                    <i class="fas fa-calendar me-2"></i>Date *
                                </label>
                                <input type="date" class="form-control" id="date" name="date" 
                                       value="<?= $log['date'] ?>" required>
                                <div class="form-text">Select the date for this log entry.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="skills" class="form-label">
                                    <i class="fas fa-graduation-cap me-2"></i>Skills Learned
                                </label>
                                <input type="text" class="form-control" id="skills" name="skills" 
                                       value="<?= htmlspecialchars($log['skills']) ?>"
                                       placeholder="e.g., Web Development, Database Management">
                                <div class="form-text">List the skills you learned or practiced today.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="task_description" class="form-label">
                            <i class="fas fa-tasks me-2"></i>Task Description *
                        </label>
                        <textarea class="form-control" id="task_description" name="task_description" 
                                  rows="6" placeholder="Describe the tasks you completed, projects you worked on, meetings you attended, or any other activities you performed today..." required><?= htmlspecialchars($log['task_description']) ?></textarea>
                        <div class="form-text">
                            Provide a detailed description of your daily activities and accomplishments.
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> You can only edit logs within 24 hours of creation. 
                        This log was created on <?= formatDate($log['created_at'], 'M j, Y g:i A') ?>.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=intern&action=logs" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Logs
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Log Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Set maximum date to today
    const today = new Date().toISOString().split('T')[0];
    $('#date').attr('max', today);
    
    // Character counter for task description
    $('#task_description').on('input', function() {
        const length = $(this).val().length;
        const maxLength = 1000;
        
        if (length > maxLength * 0.8) {
            $(this).addClass('border-warning');
        } else {
            $(this).removeClass('border-warning');
        }
    });
});
</script>

<?php include 'views/layouts/footer.php'; ?>


