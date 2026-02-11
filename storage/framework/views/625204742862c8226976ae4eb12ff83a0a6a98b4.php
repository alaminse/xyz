<?php $__env->startSection('title', 'Profile'); ?>
<?php $__env->startSection('content'); ?>
    <div class="x_panel">
        <div class="x_title">
            <h2>Profile Info</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-sm btn-success text-light" href="<?php echo e(route('admin.edit.profile')); ?>">
                        <i class="fa fa-edit"></i> Edit Profile
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <!-- Profile Header -->
            <div class="profile-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <div class="profile-avatar position-relative d-inline-block">
                            <?php if($user->profile?->photo): ?>
                                <img src="<?php echo e(getImageUrl($user->profile->photo)); ?>" alt="Profile Image"
                                     class="img-fluid rounded-circle shadow-lg profile-img" style="max-width: 40% ">
                                <span class="status-indicator position-absolute bottom-0 end-0">
                                    <span class="badge bg-success rounded-circle p-2 border border-white">
                                        <i class="fa fa-check"></i>
                                    </span>
                                </span>
                            <?php else: ?>
                                <div class="avatar-placeholder rounded-circle shadow-lg d-flex align-items-center justify-content-center">
                                    <i class="fa fa-user fa-4x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3 class="mt-3 mb-1 fw-bold"><?php echo e($user->name); ?></h3>
                        <p class="text-muted mb-2"><?php echo e($user->email); ?></p>
                        <div class="profile-badges">
                            <span class="badge bg-primary rounded-pill px-3 py-1">
                                <i class="fa fa-shield-alt mr-1"></i> Admin
                            </span>
                            <span class="badge bg-success rounded-pill px-3 py-1 ms-1">
                                <i class="fa fa-check-circle mr-1"></i> Verified
                            </span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="profile-stats row g-3">
                            <div class="col-6 col-md-3">
                                <div class="stat-card bg-gradient-primary text-white rounded p-3 text-center">
                                    <i class="fa fa-calendar-check fa-2x mb-2"></i>
                                    <h5 class="mb-0">Member Since</h5>
                                    <p class="mb-0 small"><?php echo e($user->created_at->format('M Y')); ?></p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card bg-gradient-success text-white rounded p-3 text-center">
                                    <i class="fa fa-clock fa-2x mb-2"></i>
                                    <h5 class="mb-0">Last Login</h5>
                                    <p class="mb-0 small"><?php echo e($user->last_login?->diffForHumans() ?? 'Never'); ?></p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card bg-gradient-info text-white rounded p-3 text-center">
                                    <i class="fa fa-tasks fa-2x mb-2"></i>
                                    <h5 class="mb-0">Tasks</h5>
                                    <p class="mb-0 small">24 Active</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card bg-gradient-warning text-white rounded p-3 text-center">
                                    <i class="fa fa-chart-line fa-2x mb-2"></i>
                                    <h5 class="mb-0">Performance</h5>
                                    <p class="mb-0 small">92%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="profile-details">
                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 pt-4 pb-3">
                                <h5 class="mb-0">
                                    <i class="fa fa-user-circle text-primary mr-2"></i>
                                    Personal Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <label class="info-label">Full Name</label>
                                        <p class="info-value"><?php echo e($user->name); ?> <?php echo e($user->profile?->lname ?? ''); ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Email Address</label>
                                        <p class="info-value"><?php echo e($user->email); ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Phone Number</label>
                                        <p class="info-value"><?php echo e($user->profile?->phone ?? 'Not provided'); ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Date of Birth</label>
                                        <p class="info-value"><?php echo e($user->profile?->dob ?? 'Not provided'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 pt-4 pb-3">
                                <h5 class="mb-0">
                                    <i class="fa fa-info-circle text-info mr-2"></i>
                                    Additional Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <label class="info-label">Blood Group</label>
                                        <p class="info-value">
                                            <?php if($user->profile?->blood_group): ?>
                                                <span class="badge bg-danger rounded-pill px-3 py-1 text-white">
                                                    <?php echo e($user->profile->blood_group); ?>

                                                </span>
                                            <?php else: ?>
                                                Not provided
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Gender</label>
                                        <p class="info-value">
                                            <?php if($user->profile?->gender): ?>
                                                <span class="badge bg-secondary rounded-pill px-3 py-1 text-white">
                                                    <?php echo e(ucfirst($user->profile->gender)); ?>

                                                </span>
                                            <?php else: ?>
                                                Not provided
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Account Status</label>
                                        <p class="info-value">
                                            <span class="badge bg-success rounded-pill px-3 py-1 text-white">
                                                <i class="fa fa-check-circle mr-1"></i> Active
                                            </span>
                                        </p>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Role</label>
                                        <p class="info-value">
                                            <span class="badge bg-primary rounded-pill px-3 py-1 text-white">
                                                <i class="fa fa-shield-alt mr-1"></i> Administrator
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
    <style>
        .profile-img {
            width: 900px;
            height: 900px;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.05);
        }

        .avatar-placeholder {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 4px solid #fff;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .status-indicator {
            bottom: 10px !important;
            right: 10px !important;
        }

        .stat-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        .info-grid {
            display: grid;
            gap: 1rem;
        }

        .info-item {
            padding: 0.75rem;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background-color: #f8f9fa;
            border-left-color: #667eea;
            padding-left: 1rem;
        }

        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            color: #212529;
            font-weight: 500;
            margin-bottom: 0;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .action-buttons .btn {
            transition: all 0.3s ease;
            margin: 0.25rem;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .profile-badges .badge {
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }

        .profile-badges .badge:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .profile-stats .stat-card {
                margin-bottom: 1rem;
            }

            .action-buttons .btn {
                display: block;
                width: 100%;
                margin: 0.5rem 0;
            }
        }
    </style>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script>
            $(document).ready(function() {
                // Add animation to stat cards on page load
                $('.stat-card').each(function(index) {
                    $(this).css('opacity', '0');
                    $(this).css('transform', 'translateY(20px)');
                    setTimeout(() => {
                        $(this).animate({
                            opacity: 1,
                            transform: 'translateY(0)'
                        }, 500);
                    }, index * 100);
                });

                // Photo preview functionality
                $('#photo').on('change', function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#preview-image').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                });

                // Add click animation to cards
                $('.card').on('click', function() {
                    $(this).addClass('animated pulse');
                    setTimeout(() => {
                        $(this).removeClass('animated pulse');
                    }, 1000);
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/profile.blade.php ENDPATH**/ ?>