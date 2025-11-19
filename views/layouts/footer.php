                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key me-2"></i>Change Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?page=login&action=changePassword">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // jQuery CDN fallback to local copy
        if (typeof jQuery === 'undefined') {
            document.write('<script src="/assets/vendor/jquery-3.6.0.min.js"><\/script>');
            console.warn('jQuery CDN failed; falling back to local jquery-3.6.0.min.js');
        }
    </script>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap CDN fallback to local copy
        if (typeof bootstrap === 'undefined') {
            document.write('<script src="/assets/vendor/bootstrap.bundle.min.js"><\/script>');
            console.warn('Bootstrap CDN failed; falling back to local bootstrap.bundle.min.js');
        }
    </script>
    
    <!-- Toastr JS for Toast Notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- Intro.js for Onboarding Tour -->
    <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // =================================================================
            // TOASTR CONFIGURATION
            // =================================================================
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Show PHP flash messages as toasts
            <?php if ($success = getFlashMessage('success')): ?>
                toastr.success('<?= addslashes($success) ?>', 'Success');
            <?php endif; ?>

            <?php if ($error = getFlashMessage('error')): ?>
                toastr.error('<?= addslashes($error) ?>', 'Error');
            <?php endif; ?>

            <?php if ($warning = getFlashMessage('warning')): ?>
                toastr.warning('<?= addslashes($warning) ?>', 'Warning');
            <?php endif; ?>

            <?php if ($info = getFlashMessage('info')): ?>
                toastr.info('<?= addslashes($info) ?>', 'Info');
            <?php endif; ?>

            // =================================================================
            // DATATABLES WITH INFINITE SCROLL OPTION
            // =================================================================
            $('.data-table').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                scrollY: '500px',
                scrollCollapse: true,
                scroller: true
            });

            // =================================================================
            // LOADING OVERLAY
            // =================================================================
            window.showLoading = function() {
                $('#loadingOverlay').addClass('active');
            };

            window.hideLoading = function() {
                $('#loadingOverlay').removeClass('active');
            };

            // Show loading on form submit
            $('form').on('submit', function(e) {
                const $form = $(this);
                
                // Don't show loading for search forms
                if ($form.hasClass('no-loading')) {
                    return true;
                }

                // Validate form first
                if ($form[0].checkValidity() === false) {
                    e.preventDefault();
                    e.stopPropagation();
                    $form.addClass('was-validated');
                    toastr.error('Please fill in all required fields correctly.', 'Validation Error');
                    return false;
                }

                // Add loading state to submit button
                const $submitBtn = $form.find('button[type="submit"]');
                $submitBtn.addClass('btn-loading').prop('disabled', true);
                
                // Show overlay
                showLoading();
            });

            // Show loading on AJAX requests
            $(document).ajaxStart(function() {
                showLoading();
            }).ajaxStop(function() {
                hideLoading();
            });

            // =================================================================
            // DELETE CONFIRMATION MODAL
            // =================================================================
            let deleteUrl = '';
            
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                deleteUrl = $(this).attr('href') || $(this).data('url');
                const itemName = $(this).data('item') || 'this item';
                
                $('#deleteConfirmMessage').text(`Are you sure you want to delete ${itemName}? This action cannot be undone.`);
                $('#deleteConfirmModal').modal('show');
            });

            $('#confirmDeleteBtn').on('click', function() {
                if (deleteUrl) {
                    $('#deleteConfirmModal').modal('hide');
                    showLoading();
                    window.location.href = deleteUrl;
                }
            });

            // =================================================================
            // ENHANCED FORM VALIDATION
            // =================================================================
            $('form').each(function() {
                const $form = $(this);
                
                $form.on('input change', 'input, textarea, select', function() {
                    const $field = $(this);
                    
                    // Remove invalid class on input
                    $field.removeClass('is-invalid');
                    
                    // Real-time validation
                    if ($form.hasClass('was-validated')) {
                        if ($field[0].checkValidity()) {
                            $field.addClass('is-valid');
                        } else {
                            $field.addClass('is-invalid');
                        }
                    }
                });
            });

            // Email validation
            $('input[type="email"]').on('blur', function() {
                const $email = $(this);
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if ($email.val() && !emailPattern.test($email.val())) {
                    $email.addClass('is-invalid');
                    $email.siblings('.invalid-feedback').text('Please enter a valid email address.');
                }
            });

            // Password match validation
            $('input[name="confirm_password"]').on('input', function() {
                const $confirm = $(this);
                const $password = $('input[name="new_password"]');
                
                if ($confirm.val() && $confirm.val() !== $password.val()) {
                    $confirm.addClass('is-invalid');
                    $confirm.siblings('.invalid-feedback').text('Passwords do not match.');
                } else {
                    $confirm.removeClass('is-invalid');
                }
            });

            // =================================================================
            // TOOLTIPS INITIALIZATION
            // =================================================================
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-initialize tooltips for icons
            $('i.fas, i.far, i.fab').each(function() {
                const $icon = $(this);
                if (!$icon.parent().attr('data-bs-toggle') && $icon.attr('title')) {
                    $icon.parent().attr('data-bs-toggle', 'tooltip');
                    $icon.parent().attr('data-bs-placement', 'top');
                    $icon.parent().attr('title', $icon.attr('title'));
                    new bootstrap.Tooltip($icon.parent()[0]);
                }
            });

            // =================================================================
            // KEYBOARD NAVIGATION
            // =================================================================
            $(document).on('keydown', function(e) {
                // Alt + D for Dashboard
                if (e.altKey && e.key === 'd') {
                    e.preventDefault();
                    window.location.href = 'index.php?page=dashboard';
                }

                // Alt + P for Profile
                if (e.altKey && e.key === 'p') {
                    e.preventDefault();
                    window.location.href = 'index.php?page=profile';
                }

                // Alt + L for Logout
                if (e.altKey && e.key === 'l') {
                    e.preventDefault();
                    window.location.href = 'index.php?page=login&action=logout';
                }

                // Escape to close modals
                if (e.key === 'Escape') {
                    $('.modal').modal('hide');
                }
            });

            // Navigate tables with arrow keys
            $('.data-table tbody tr').on('keydown', function(e) {
                const $row = $(this);
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    $row.next('tr').focus();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    $row.prev('tr').focus();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    $row.find('a:first').click();
                }
            });

            // Make table rows focusable
            $('.data-table tbody tr').attr('tabindex', '0');

            // =================================================================
            // ONBOARDING TOUR (First-time users)
            // =================================================================
            window.startOnboardingTour = function() {
                const userRole = '<?= $_SESSION['role'] ?? '' ?>';
                
                if (userRole === 'intern') {
                    introJs().setOptions({
                        steps: [
                            {
                                intro: "Welcome to the Parliament Intern Logbook System! Let's take a quick tour."
                            },
                            {
                                element: document.querySelector('.sidebar'),
                                intro: "This is your navigation menu. Use it to access different sections of the system.",
                                position: 'right'
                            },
                            {
                                element: document.querySelector('.stats-card:first-child'),
                                intro: "These cards show your key statistics at a glance.",
                                position: 'bottom'
                            },
                            {
                                element: document.querySelector('a[href*="addLog"]'),
                                intro: "Click here to add your daily activity logs. Remember, you can edit them within 24 hours!",
                                position: 'bottom'
                            },
                            {
                                element: document.querySelector('#userDropdown'),
                                intro: "Access your profile, settings, and logout from here.",
                                position: 'left'
                            }
                        ],
                        showProgress: true,
                        showBullets: true,
                        exitOnOverlayClick: false,
                        doneLabel: 'Get Started!'
                    }).start();
                } else if (userRole === 'supervisor') {
                    introJs().setOptions({
                        steps: [
                            {
                                intro: "Welcome, Supervisor! Let's explore your dashboard."
                            },
                            {
                                element: document.querySelector('.sidebar'),
                                intro: "Navigate between Interns, Logs, Evaluations, and Reports from here.",
                                position: 'right'
                            },
                            {
                                element: document.querySelector('.stats-card:first-child'),
                                intro: "Monitor your assigned interns and pending reviews at a glance.",
                                position: 'bottom'
                            }
                        ],
                        showProgress: true,
                        doneLabel: 'Got it!'
                    }).start();
                } else if (userRole === 'admin') {
                    introJs().setOptions({
                        steps: [
                            {
                                intro: "Welcome, Administrator! Here's your system overview."
                            },
                            {
                                element: document.querySelector('.sidebar'),
                                intro: "Manage users, view reports, and configure the system from the sidebar.",
                                position: 'right'
                            }
                        ],
                        showProgress: true,
                        doneLabel: 'Start Managing!'
                    }).start();
                }
            };

            // Check if user is new (you can store this in session/cookie)
            if (!localStorage.getItem('tourCompleted_<?= $_SESSION['user_id'] ?? '' ?>')) {
                // Uncomment to auto-start tour for new users
                // setTimeout(startOnboardingTour, 1000);
            }

            // Add tour button to help menu
            if ($('.navbar').length) {
                $('.navbar-nav').append(`
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="startOnboardingTour(); return false;" 
                           data-bs-toggle="tooltip" title="Start Tutorial (Alt+T)">
                            <i class="fas fa-question-circle"></i>
                        </a>
                    </li>
                `);
            }

            // Alt + T for Tour
            $(document).on('keydown', function(e) {
                if (e.altKey && e.key === 't') {
                    e.preventDefault();
                    startOnboardingTour();
                }
            });

            // Mark tour as completed
            introJs().oncomplete(function() {
                localStorage.setItem('tourCompleted_<?= $_SESSION['user_id'] ?? '' ?>', 'true');
            });

            // =================================================================
            // BETA BANNER CLOSE FUNCTIONALITY
            // =================================================================
            window.closeBetaBanner = function() {
                const banner = document.getElementById('betaBanner');
                if (banner) {
                    banner.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    banner.style.opacity = '0';
                    banner.style.transform = 'translateY(-20px)';
                    setTimeout(function() {
                        banner.style.display = 'none';
                        localStorage.setItem('betaBannerClosed_<?= $_SESSION['user_id'] ?? '' ?>', 'true');
                    }, 300);
                }
            };

            window.closeBetaFloatingBadge = function() {
                const badge = document.getElementById('betaFloatingBadge');
                if (badge) {
                    badge.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    badge.style.opacity = '0';
                    badge.style.transform = 'translateX(100%)';
                    setTimeout(function() {
                        badge.style.display = 'none';
                        localStorage.setItem('betaFloatingBadgeClosed_<?= $_SESSION['user_id'] ?? '' ?>', 'true');
                    }, 300);
                }
            };

            // Check if beta banner was previously closed
            if (localStorage.getItem('betaBannerClosed_<?= $_SESSION['user_id'] ?? '' ?>') === 'true') {
                const banner = document.getElementById('betaBanner');
                if (banner) {
                    banner.style.display = 'none';
                }
            } else {
                // Auto-close banner after 30 seconds
                setTimeout(function() {
                    closeBetaBanner();
                }, 30000);
            }

            // Check if beta floating badge was previously closed
            if (localStorage.getItem('betaFloatingBadgeClosed_<?= $_SESSION['user_id'] ?? '' ?>') === 'true') {
                const badge = document.getElementById('betaFloatingBadge');
                if (badge) {
                    badge.style.display = 'none';
                }
            } else {
                // Auto-close badge after 30 seconds
                setTimeout(function() {
                    closeBetaFloatingBadge();
                }, 30000);
            }

            // =================================================================
            // DIAGNOSTIC
            // =================================================================
            console.log('Enhanced UX features initialized');
            console.log('Keyboard shortcuts: Alt+D (Dashboard), Alt+P (Profile), Alt+L (Logout), Alt+T (Tour)');

            // =================================================================
            // DROPDOWN FIX - Ensure dropdown is never clipped
            // =================================================================
            const dropdownToggle = document.getElementById('userDropdown');
            const dropdownMenu = dropdownToggle ? dropdownToggle.nextElementSibling : null;
            
            if (dropdownToggle && dropdownMenu) {
                let isDropdownOpen = false;
                let backdrop = null;
                
                // Create backdrop
                backdrop = document.createElement('div');
                backdrop.className = 'dropdown-backdrop';
                document.body.appendChild(backdrop);
                
                // Close dropdown when clicking backdrop
                backdrop.addEventListener('click', function() {
                    $(dropdownToggle).dropdown('hide');
                });
                
                // Function to position dropdown relative to trigger
                function positionDropdown() {
                    if (!isDropdownOpen) return;
                    
                    const rect = dropdownToggle.getBoundingClientRect();
                    const dropdownHeight = dropdownMenu.offsetHeight;
                    const dropdownWidth = dropdownMenu.offsetWidth;
                    const viewportHeight = window.innerHeight;
                    const viewportWidth = window.innerWidth;
                    
                    // Calculate position
                    let top = rect.bottom + 8;
                    let left = rect.right - dropdownWidth;
                    
                    // Ensure it doesn't go off screen
                    if (left < 0) left = 8;
                    if (left + dropdownWidth > viewportWidth) {
                        left = viewportWidth - dropdownWidth - 8;
                    }
                    
                    // Check if dropdown goes below viewport
                    if (top + dropdownHeight > viewportHeight) {
                        // Position above the trigger instead
                        top = rect.top - dropdownHeight - 8;
                    }
                    
                    // Ensure it doesn't go above screen
                    if (top < 0) top = 8;
                    
                    dropdownMenu.style.position = 'fixed';
                    dropdownMenu.style.top = top + 'px';
                    dropdownMenu.style.left = left + 'px';
                    dropdownMenu.style.right = 'auto';
                    dropdownMenu.style.bottom = 'auto';
                    dropdownMenu.style.zIndex = '99999';
                    dropdownMenu.style.transform = 'none';
                    dropdownMenu.style.margin = '0';
                }
                
                // Move dropdown to body when opened
                dropdownToggle.addEventListener('show.bs.dropdown', function() {
                    isDropdownOpen = true;
                    
                    // Move to body to escape any overflow constraints
                    if (dropdownMenu.parentElement !== document.body) {
                        document.body.appendChild(dropdownMenu);
                    }
                    
                    // Show backdrop
                    if (backdrop) {
                        backdrop.classList.add('show');
                    }
                    
                    // Position it
                    setTimeout(positionDropdown, 10);
                });
                
                dropdownToggle.addEventListener('shown.bs.dropdown', function() {
                    positionDropdown();
                });
                
                dropdownToggle.addEventListener('hide.bs.dropdown', function() {
                    isDropdownOpen = false;
                    
                    // Hide backdrop
                    if (backdrop) {
                        backdrop.classList.remove('show');
                    }
                });
                
                // Reposition on scroll and resize
                window.addEventListener('scroll', positionDropdown, { passive: true });
                window.addEventListener('resize', positionDropdown, { passive: true });
                
                console.log('Dropdown portal solution initialized with backdrop');
            }

        });
    </script>
</body>
</html>


