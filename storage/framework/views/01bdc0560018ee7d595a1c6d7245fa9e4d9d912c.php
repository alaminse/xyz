<?php $__env->startSection('title', 'Mcq'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<style>
            .list-group {
            background-color: transparent !important;
        }

        .list-group .list-group-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="card-title text-center">
                            <i class="bi bi-bookmark-star"></i> Mcq Topics
                        </h4>
                        <h6 class="text-center mb-4"><?php echo e($course->name); ?></h6>
                    </div>
                    <hr>
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
                                                    <a href="<?php echo e(route('mcqs.test', ['course' => $course->slug, 'chapter' => $chapter->slug, 'lesson' => $lesson->slug])); ?>">
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
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
                <div class="card-body">
                    <div class="card-header bg-transparent border-0">
                        <h4 class="card-title text-white text-center mb-4">
                            <i class="bi bi-clock-history"></i> Recent Progress
                        </h4>
                    </div>
                    <?php $__empty_1 = true; $__currentLoopData = $progress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 bg-transparent">
                                            <li class="breadcrumb-item">
                                                <small class="">
                                                    <i class="bi bi-book me-1"></i><?php echo e($item->getCourseName()); ?>

                                                </small>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <small class="">
                                                    <i class="bi bi-folder me-1"></i><?php echo e($item->getChapterName()); ?>

                                                </small>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <small class="text-mute">
                                                    <i class="bi bi-file-text me-1"></i><?php echo e($item->getLessonName()); ?>

                                                </small>
                                            </li>
                                        </ol>
                                    </nav>
                                    <a href="<?php echo e(route('mcqs.review', $item->slug)); ?>" class="btn btn-sm btn-light">
                                        <i class="bi bi-eye me-1"></i>Review
                                    </a>
                                </div>

                                <?php
                                    // $progressItem = $item->total > 0 ? ($item->correct / $item->total) * 100 : 0;

                                    // ✅ CORRECT
                                    $progressItem = $item->progress_cut > 0 ? ($item->correct / ($item->total * 5)) * 100 : 0;
                                    // এটা total answered options দিয়ে ভাগ করছে
                                    $progressItemClass = $progressItem >= 75 ? 'bg-success' :
                                                        ($progressItem >= 50 ? 'bg-warning' :
                                                        ($progressItem >= 25 ? 'bg-info' : 'bg-danger'));
                                ?>

                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo e($progressItemClass); ?>"
                                         role="progressbar"
                                         style="width: <?php echo e($progressItem); ?>%;"
                                         aria-valuenow="<?php echo e($progressItem); ?>"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        <?php echo e(number_format($progressItem, 0)); ?>% (<?php echo e($item->correct); ?>/<?php echo e($item->total * 5); ?>)
                                    </div>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/mcq/index.blade.php ENDPATH**/ ?>