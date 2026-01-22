<?php $__env->startSection('title', 'Written Assessment'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
    <style>
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
                    <div class="card-header bg-transparent border-0">
                        <h4 class="card-title text-white text-center mb-4">
                            <i class="bi bi-bookmark-star"></i> <?php echo e($course->name); ?> Topics
                        </h4>
                    </div>
                    <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="accordion" id="accordionExample">
                        <?php $__empty_1 = true; $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="accordion-item bg-transparent border-0 mb-2">
                                <h2 class="accordion-header" id="heading-<?php echo e($key); ?>">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse-<?php echo e($key); ?>"
                                            aria-expanded="false"
                                            aria-controls="collapse-<?php echo e($key); ?>">
                                        <i class="bi bi-folder2-open me-2"></i> <?php echo e($chapter->name); ?>

                                    </button>
                                </h2>
                                <div id="collapse-<?php echo e($key); ?>" class="accordion-collapse collapse"
                                     aria-labelledby="heading-<?php echo e($key); ?>"
                                     data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <?php $__empty_2 = true; $__currentLoopData = $chapter->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <li class="list-group-item">
                                                    <a href="<?php echo e(route('writtens.details', ['course' => $course->slug, 'chapter' => $chapter->slug, 'lesson' => $lesson->slug])); ?>">
                                                        <i class="bi bi-play-circle me-2"></i><?php echo e($lesson->name); ?>

                                                    </a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <li class="list-group-item text-white-50">
                                                    <i class="bi bi-info-circle me-2"></i>No lessons available
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>No topics available for this course.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-7 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="card-title text-white text-center mb-4">
                            <i class="bi bi-clock-history"></i> Recent Progress
                        </h4>
                    </div>
                    <?php $__empty_1 = true; $__currentLoopData = $latest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="d-flex">
                                <h5 class="flex-grow-1"><?php echo e($item->question); ?></h5>
                                <a href="<?php echo e(route('writtens.single.details', $item->slug)); ?>"
                                    class="btn btn-sm button-yellow">Details</a>
                            </div>
                        </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>No progress yet. Start practicing to see your results here!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/written-assessment/index.blade.php ENDPATH**/ ?>