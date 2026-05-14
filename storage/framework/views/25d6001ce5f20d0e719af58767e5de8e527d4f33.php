<?php $__env->startSection('title', 'Study Materials'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
    <style>
        .list-group {
            background-color: transparent !important;
        }
        .list-group .list-group-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .button-yellow:hover,
        .button-white:hover {
            color: white;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        
        <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Study Materials Topic</h5>
                    </div>
                    <section id="info-utile">
                        <div class="container">
                            <div class="accordion" id="accordionExample">
                                <?php $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-<?php echo e($key); ?>">
                                            <a class="accordion-button collapsed" href="#"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse-<?php echo e($key); ?>"
                                                aria-expanded="false"
                                                aria-controls="collapse-<?php echo e($key); ?>">
                                                <?php echo e($chapter->name); ?>

                                            </a>
                                        </h2>
                                        <div id="collapse-<?php echo e($key); ?>"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="heading-<?php echo e($key); ?>"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <ul class="list-group">
                                                    <?php $__currentLoopData = $chapter->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="list-group-item">
                                                            <a href="<?php echo e(route('secure-pdfs.details', [
                                                                'course'   => $course->slug,
                                                                'chapter'  => $chapter->slug,
                                                                'lesson'   => $lesson->slug,
                                                            ])); ?>">
                                                                <?php echo e($lesson->name); ?>

                                                            </a>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        
        <div class="col-sm-12 col-md-6 col-lg-7 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Recent</h5>
                    </div>

                    <?php if($isLocked): ?>
                        <div class="alert d-flex align-items-center justify-content-between gap-3 mt-3"
                            style="background:#fff8e1; border:1px solid #f1a909;
                                   border-radius:8px; padding:14px 18px;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-lock-fill text-warning fs-5"></i>
                                <span class="fw-semibold text-dark">
                                    Premium content is locked. Showing free PDFs only.
                                </span>
                            </div>
                            <a href="<?php echo e(route('courses.checkout', ['course' => $course->slug])); ?>"
                                class="btn btn-sm btn-warning fw-bold flex-shrink-0">
                                <i class="bi bi-star-fill me-1"></i> Upgrade to Premium
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php $__currentLoopData = $latest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mt-3 p-3 topic-card">
                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2">
                                <h5 class="flex-grow-1 mb-0">
                                    <?php echo e($item->title); ?>

                                    <?php if($item->isPaid): ?>
                                        <i class="bi bi-lock-fill text-warning ms-1 small"></i>
                                    <?php else: ?>
                                        <i class="bi bi-unlock-fill text-success ms-1 small"></i>
                                    <?php endif; ?>
                                </h5>

                                <?php if($item->isPaid && $isLocked): ?>
                                    <a href="<?php echo e(route('courses.checkout', ['course' => $course->slug])); ?>"
                                        class="btn btn-sm btn-warning fw-bold flex-shrink-0">
                                        <i class="bi bi-star-fill me-1"></i> Upgrade to Premium
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('secure-pdfs.view', $item->slug)); ?>"
                                        class="btn btn-sm button-yellow flex-shrink-0">
                                        <i class="fa fa-eye"></i> Open
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/secure-pdfs/index.blade.php ENDPATH**/ ?>