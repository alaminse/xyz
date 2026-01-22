<?php $__env->startSection('title', $course->name . ' - Course Details'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Hero Section with Course Banner -->
    <div class="position-relative mb-4" style="height: 300px; overflow: hidden; border-radius: 10px;">
        <?php if($course->banner): ?>
            <div class="position-absolute w-100 h-100"
                 style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo e(getImageUrl($course->banner)); ?>');
                        background-size: cover;
                        background-position: center;">
            </div>
        <?php else: ?>
            <div class="position-absolute w-100 h-100"
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            </div>
        <?php endif; ?>

        <div class="position-relative h-100 d-flex align-items-center justify-content-center text-white">
            <div class="text-center">
                <h1 class="display-4 fw-bold mb-3"><?php echo e($course->name); ?></h1>
                <p class="lead">Course Details</p>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <!-- Course Description -->
                        <?php if($course->details): ?>
                            <div class="mb-4">
                                <h3 class="fw-bold mb-3">
                                    <i class="fa fa-info-circle text-primary"></i> About This Course
                                </h3>
                                <div class="course-details-content">
                                    <?php echo $course->details; ?>

                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Child Courses if exists -->
                        <?php if($course->courses && $course->courses->count() > 0): ?>
                            <div class="mt-5">
                                <h3 class="fw-bold mb-4">
                                    <i class="fa fa-layer-group text-primary"></i> Related Courses
                                </h3>
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                    <?php $__currentLoopData = $course->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCourse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col">
                                            <div class="card h-100 shadow-sm border-0 hover-card">
                                                <?php if($childCourse->banner): ?>
                                                    <img src="<?php echo e(getImageUrl($childCourse->banner)); ?>"
                                                         class="card-img-top"
                                                         alt="<?php echo e($childCourse->name); ?>"
                                                         style="height: 150px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center"
                                                         style="height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        <i class="fa fa-book text-white fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="card-body">
                                                    <h6 class="card-title fw-bold"><?php echo e($childCourse->name); ?></h6>
                                                    <?php if($childCourse->is_pricing == 1 && $childCourse->detail): ?>
                                                        <div class="mt-2">
                                                            <?php if($childCourse->detail->sell_price): ?>
                                                                <span class="text-muted small text-decoration-line-through">
                                                                    <?php echo e($childCourse->detail->price); ?> ৳
                                                                </span>
                                                                <span class="text-success fw-bold ms-2">
                                                                    <?php echo e($childCourse->detail->sell_price); ?> ৳
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-dark fw-bold">
                                                                    <?php echo e($childCourse->detail->price); ?> ৳
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card topic-card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Course Information</h4>

                        <div class="mb-4">
                            <h5 class="text-primary fw-bold"><?php echo e($course->name); ?></h5>

                            <?php if($course->is_pricing == 1 && $course->detail): ?>
                                <!-- Duration -->
                                <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                                    <i class="fa fa-clock fa-2x me-3"></i>
                                    <div>
                                        <small class="d-block">Duration</small>
                                        <strong><?php echo e($course->detail->duration); ?> <?php echo e($course->detail->type); ?></strong>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                                    <i class="fa fa-tag fa-2x text-success me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Price</small>
                                        <?php if($course->detail->sell_price): ?>
                                            <div>
                                                <span class="text-muted text-decoration-line-through">
                                                    <?php echo e($course->detail->price); ?> ৳
                                                </span>
                                                <strong class="text-success fs-4 ms-2">
                                                    <?php echo e($course->detail->sell_price); ?> ৳
                                                </strong>
                                                <span class="badge bg-danger ms-2">
                                                    <?php echo e(round((($course->detail->price - $course->detail->sell_price) / $course->detail->price) * 100)); ?>% OFF
                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <strong class="fs-4"><?php echo e($course->detail->price); ?> ৳</strong>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Enroll Button -->
                                <a href="<?php echo e(route('courses.checkout', ['course' => $course->slug])); ?>"
                                   class="btn btn-primary w-100 py-3 mb-3 fw-bold">
                                    <i class="fa fa-shopping-cart"></i> ENROLL NOW
                                </a>

                                <!-- Free Trial Button -->
                                <a href="<?php echo e(route('courses.checkout', ['course' => $course->slug, 'isTrial' => 'free-trial'])); ?>"
                                   class="btn btn-info w-100 py-3 fw-bold text-white">
                                    <i class="fa fa-gift"></i> START FREE TRIAL
                                </a>
                            <?php else: ?>
                                <div class="alert alert-success text-center">
                                    <i class="fa fa-check-circle fa-2x mb-2"></i>
                                    <h5>This is a Free Course</h5>
                                    <a href="<?php echo e(route('courses.checkout', ['course' => $course->slug])); ?>"
                                       class="btn btn-success w-100 mt-3 py-3 fw-bold">
                                        <i class="fa fa-play-circle"></i> START LEARNING
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Course Features -->
                        <div class="border-top pt-4">
                            <h6 class="fw-bold mb-3">What's Included:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    Lifetime Access
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    Expert Guidance
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    Comprehensive Resources
                                </li>
                                <li class="mb-2">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    Community Support
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .course-details-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }
        .course-details-content h1,
        .course-details-content h2,
        .course-details-content h3 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }
        .course-details-content ul,
        .course-details-content ol {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .hover-card {
            transition: transform 0.3s ease;
        }
        .hover-card:hover {
            transform: translateY(-5px);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/course-details.blade.php ENDPATH**/ ?>