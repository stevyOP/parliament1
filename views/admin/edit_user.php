<?php
$page_title = 'Edit User';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-user-edit me-2"></i>Edit User
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=admin&action=users">Users</a></li>
                    <li class="breadcrumb-item active">Edit User</li>
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
                    <i class="fas fa-user-edit me-2"></i>Edit User Account
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=admin&action=updateUser">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name *
                                </label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($user['name']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>New Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="form-text">Leave blank to keep current password</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag me-2"></i>Role *
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select role...</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                                    <option value="supervisor" <?= $user['role'] === 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                    <option value="intern" <?= $user['role'] === 'intern' ? 'selected' : '' ?>>Intern</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="is_active" class="form-label">
                                    <i class="fas fa-toggle-on me-2"></i>Account Status *
                                </label>
                                <select class="form-select" id="is_active" name="is_active" required>
                                    <option value="1" <?= $user['is_active'] ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?= !$user['is_active'] ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Intern-specific fields -->
                    <div id="intern-fields" style="display: <?= $user['role'] === 'intern' ? 'block' : 'none' ?>;">
                        <hr>
                        <h6 class="text-primary">
                            <i class="fas fa-graduation-cap me-2"></i>Intern Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-select" id="department" name="department">
                                        <option value="">Select department...</option>
                                        <option value="1" <?= $user['department'] == 1 ? 'selected' : '' ?>>Information Technology</option>
                                        <option value="2" <?= $user['department'] == 2 ? 'selected' : '' ?>>Human Resources</option>
                                        <option value="3" <?= $user['department'] == 3 ? 'selected' : '' ?>>Finance</option>
                                        <option value="4" <?= $user['department'] == 4 ? 'selected' : '' ?>>Legal Affairs</option>
                                        <option value="5" <?= $user['department'] == 5 ? 'selected' : '' ?>>Public Relations</option>
                                        <option value="6" <?= $user['department'] == 6 ? 'selected' : '' ?>>Research & Development</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supervisor_id" class="form-label">Supervisor</label>
                                    <select class="form-select" id="supervisor_id" name="supervisor_id">
                                        <option value="">Select supervisor...</option>
                                        <?php foreach ($supervisors as $supervisor): ?>
                                            <option value="<?= $supervisor['id'] ?>" 
                                                    <?= $user['supervisor_id'] == $supervisor['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($supervisor['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="<?= $user['start_date'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="<?= $user['end_date'] ?? '' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="intern_status" class="form-label">Internship Status</label>
                                    <select class="form-select" id="intern_status" name="intern_status">
                                        <option value="active" <?= ($user['intern_status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="completed" <?= ($user['intern_status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="terminated" <?= ($user['intern_status'] ?? '') === 'terminated' ? 'selected' : '' ?>>Terminated</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Changing the role or status may affect the user's access to the system.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=admin&action=users" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Show/hide intern fields based on role selection
    $('#role').on('change', function() {
        const role = $(this).val();
        const internFields = $('#intern-fields');
        
        if (role === 'intern') {
            internFields.show();
            // Make intern fields required
            $('#department, #supervisor_id, #start_date, #end_date').attr('required', true);
        } else {
            internFields.hide();
            // Remove required attribute
            $('#department, #supervisor_id, #start_date, #end_date').removeAttr('required');
        }
    });
    
    // Set minimum date for end_date to start_date
    $('#start_date').on('change', function() {
        $('#end_date').attr('min', $(this).val());
    });

    // Password strength indicator
    $('#password').on('input', function() {
        const password = $(this).val();
        
        if (password.length > 0) {
            const strength = getPasswordStrength(password);
            
            $(this).removeClass('border-danger border-warning border-success');
            
            if (strength < 3) {
                $(this).addClass('border-danger');
            } else if (strength < 5) {
                $(this).addClass('border-warning');
            } else {
                $(this).addClass('border-success');
            }
        } else {
            $(this).removeClass('border-danger border-warning border-success');
        }
    });

    function getPasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 6) strength++;
        if (password.length >= 10) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        
        return strength;
    }
});
</script>

<?php include 'views/layouts/footer.php'; ?>
