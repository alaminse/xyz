<?php $__env->startSection('title', 'Flash Card'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">

    
    <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
        <div class="card topic-card">
            <div class="card-body">
                <div class="card-header bg-transparent border-0">
                    <h4 class="card-title text-white text-center mb-4">
                        <i class="bi bi-layers"></i> Flash Card Topics
                    </h4>
                </div>

                <div class="accordion" id="flashAccordion">
                    <?php $__empty_1 = true; $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="accordion-item bg-transparent border-0 mb-2">
                            <h2 class="accordion-header" id="heading-<?php echo e($key); ?>">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse-<?php echo e($key); ?>">
                                    <i class="bi bi-folder2-open me-2"></i>
                                    <?php echo e($chapter->name); ?>

                                </button>
                            </h2>

                            <div id="collapse-<?php echo e($key); ?>"
                                 class="accordion-collapse collapse"
                                 data-bs-parent="#flashAccordion">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <?php $__empty_2 = true; $__currentLoopData = $chapter->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                            <li class="list-group-item">
                                                <a href="<?php echo e(route('flashs.test', [
                                                    'course' => $course->slug,
                                                    'chapter' => $chapter->slug,
                                                    'lesson' => $lesson->slug
                                                ])); ?>">
                                                    <i class="bi bi-play-circle me-2"></i>
                                                    <?php echo e($lesson->name); ?>

                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                            <li class="list-group-item text-white-50">
                                                <i class="bi bi-info-circle me-2"></i>
                                                No lessons available
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No flash card topics available.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-sm-12 col-md-6 col-lg-7 mb-3">
        <div class="card"
             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 15px;">
            <div class="card-body">
                <div class="card-header bg-transparent border-0">
                    <h4 class="card-title text-white text-center mb-4">
                        <i class="bi bi-clock-history"></i> Recent Flash Progress
                    </h4>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $progress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="card mt-3">
                        <div class="card-body">

                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0 bg-transparent">
                                        <li class="breadcrumb-item">
                                            <small>
                                                <i class="bi bi-book me-1"></i>
                                                <?php echo e($item->getCourseName()); ?>

                                            </small>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <small>
                                                <i class="bi bi-folder me-1"></i>
                                                <?php echo e($item->getChapterName()); ?>

                                            </small>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <small">
                                                <i class="bi bi-file-text me-1"></i>
                                                <?php echo e($item->getLessonName()); ?>

                                            </small>
                                        </li>
                                    </ol>
                                </nav>

                                <a href="<?php echo e(route('flashs.review', ['slug' => $item->slug])); ?>"
                                class="btn btn-sm btn-light" style="width: 115px">
                                    <i class="bi bi-eye me-1"></i> Review
                                </a>

                            </div>

                            
                            <?php
                                $percent = $item->total > 0
                                    ? ($item->correct / $item->total) * 100
                                    : 0;

                                $barClass = $percent >= 75 ? 'bg-success' :
                                            ($percent >= 50 ? 'bg-warning' :
                                            ($percent >= 25 ? 'bg-info' : 'bg-danger'));
                            ?>

                            
                            <div class="d-flex justify-content-between mb-2 text-white">
                                <small>
                                    <i class="bi bi-check-circle"></i>
                                    Correct: <?php echo e($item->correct); ?>/<?php echo e($item->total); ?>

                                </small>
                                <small>
                                    <i class="bi bi-x-circle"></i>
                                    Wrong: <?php echo e($item->wrong); ?>

                                </small>
                            </div>

                            
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo e($barClass); ?>"
                                     style="width: <?php echo e($percent); ?>%">
                                    <?php echo e(number_format($percent, 0)); ?>%
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        No flash card progress yet. Start learning now!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/flash/index.blade.php ENDPATH**/ ?>