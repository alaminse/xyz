<?php $__env->startSection('title', 'Review Questions'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
    <style>
        .list-group { background-color: transparent !important; }
        .list-group .list-group-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="card topic-card">
        <div class="card-body">
            <div class="card-header bg-transparent border-0">
                <h4 class="card-title text-center mb-4">
                    <i class="bi bi-journal-check"></i> Review Questions
                </h4>
                <h6 class="text-center mb-4"><?php echo e($course->name); ?></h6>
            </div>
            <hr>
            <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <p class="text-center text-white-50 mb-4">Select a chapter or lesson — you will see the already solved SBA and MCQ questions together.
            </p>

            <div class="accordion" id="reviewAccordion">
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
                             data-bs-parent="#reviewAccordion">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <?php $__empty_2 = true; $__currentLoopData = $chapter->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <li class="list-group-item">
                                            <a href="<?php echo e(route('review_questions.show', ['course' => $course->slug, 'chapter' => $chapter->slug, 'lesson' => $lesson->slug])); ?>">
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
                        <i class="bi bi-info-circle me-2"></i>No chapters available for this course.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/review/index.blade.php ENDPATH**/ ?>