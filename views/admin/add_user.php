<?php
$page_title = 'Add User';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-user-plus me-2"></i>Add New User
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=admin&action=users">Users</a></li>
                    <li class="breadcrumb-item active">Add User</li>
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
                    <i class="fas fa-user-plus me-2"></i>Create New User Account
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=admin&action=createUser">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name *
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password *
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Minimum 6 characters</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag me-2"></i>Role *
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select role...</option>
                                    <option value="admin">Administrator</option>
                                    <option value="supervisor">Supervisor</option>
                                    <option value="intern">Intern</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Intern-specific fields -->
                    <div id="intern-fields" style="display: none;">
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
                                        <option value="1">Information Technology</option>
                                        <option value="2">Human Resources</option>
                                        <option value="3">Finance</option>
                                        <option value="4">Legal Affairs</option>
                                        <option value="5">Public Relations</option>
                                        <option value="6">Research & Development</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supervisor_id" class="form-label">Supervisor</label>
                                    <select class="form-select" id="supervisor_id" name="supervisor_id">
                                        <option value="">Select supervisor...</option>
                                        <?php foreach ($supervisors as $supervisor): ?>
                                            <option value="<?= $supervisor['id'] ?>">
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
                                    <input type="date" class="form-control" id="start_date" name="start_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> The user will be able to log in immediately after account creation. 
                        Make sure to provide them with their login credentials.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?page=admin&action=users" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create User
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

    // Set minimum date for start_date to today
    const today = new Date().toISOString().split('T')[0];
    $('#start_date').attr('min', today);
    
    // Set minimum date for end_date to start_date
    $('#start_date').on('change', function() {
        $('#end_date').attr('min', $(this).val());
    });

    // Password strength indicator
    $('#password').on('input', function() {
        const password = $(this).val();
        const strength = getPasswordStrength(password);
        
        $(this).removeClass('border-danger border-warning border-success');
        
        if (password.length > 0) {
            if (strength < 3) {
                $(this).addClass('border-danger');
            } else if (strength < 5) {
                $(this).addClass('border-warning');
            } else {
                $(this).addClass('border-success');
            }
        }
    });

    function getPasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 6) strength++;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        return strength;
    }
});
</script>

<?php include 'views/layouts/footer.php'; ?>


