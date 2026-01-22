<?php $__env->startSection('title', 'All Courses'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
        <div class="border-0 d-flex justify-content-between align-items-center bg-white">
            <h5 class="mb-0 fw-semibold p-3">
                <i class="fa fa-book text-primary"></i> All Courses
            </h5>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-2">
            <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col">
                    <div class="card topic-card h-100 shadow-sm border-0 course-card-hover">
                        <!-- Course Banner -->
                        <?php if($course->banner): ?>
                            <img src="<?php echo e(getImageUrl($course->banner)); ?>"
                                    class="card-img-top"
                                    alt="<?php echo e($course->name); ?>"
                                    style="height: 200px; object-fit: cover;">
                        <?php else: ?>

                            <img src="<?php echo e(getImageUrl(null)); ?>"
                                    class="card-img-top"
                                    alt="<?php echo e($course->name); ?>"
                                    style="height: 200px; object-fit: cover;">
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <!-- Course Name -->
                            <h5 class="card-title fw-bold mb-3"><?php echo e($course->name); ?></h5>

                            <!-- Course Details Preview -->
                            <?php if($course->details): ?>
                                <p class="card-text text-muted small flex-grow-1 mb-3">
                                    <?php echo e(Str::limit(strip_tags($course->details), 120)); ?>

                                </p>
                            <?php endif; ?>

                            <!-- Course Info -->
                            <?php if($course->is_pricing == 1 && $course->detail): ?>
                                <div class="mb-3 p-2 bg-light rounded">
                                    <p class="card-text text-muted mb-2 small">
                                        <i class="fa fa-clock text-primary"></i>
                                        <strong>Duration:</strong> <?php echo e($course->detail->duration); ?> <?php echo e($course->detail->type); ?>

                                    </p>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-tag text-success me-2"></i>
                                        <?php if($course->detail->sell_price): ?>
                                            <span class="text-muted text-decoration-line-through me-2 small">
                                                <?php echo e($course->detail->price); ?> ৳
                                            </span>
                                            <span class="text-success fw-bold fs-5">
                                                <?php echo e($course->detail->sell_price); ?> ৳
                                            </span>
                                        <?php else: ?>
                                            <span class="text-dark fw-bold fs-5"><?php echo e($course->detail->price); ?> ৳</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <span class="badge bg-info">Free Course</span>
                                </div>
                            <?php endif; ?>

                            <!-- View Details Button -->
                            <a href="<?php echo e(route('user.course.details', $course->slug)); ?>"
                                class="btn btn-primary w-100 mt-auto">
                                <i class="fa fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <i class="fa fa-info-circle fa-3x mb-3"></i>
                        <h5>No courses available at the moment.</h5>
                        <p class="mb-0">Please check back later for new courses.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    <style>
        .course-card-hover {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        .course-card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
        }
        .card-img-top {
            border-radius: 10px 10px 0 0;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/courses.blade.php ENDPATH**/ ?>