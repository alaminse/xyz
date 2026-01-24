<?php $__env->startSection('title', 'Assessments'); ?>
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
                    <div class="card-header">
                        <h5 class="card-title">Assessment Topic</h5>
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
                                                    <a href="<?php echo e(route('assessments.by.chapter', ['course' => $course->slug, 'chapter' => $chapter->slug, 'lesson' => $lesson->slug])); ?>">
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
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title text-light">Recent</h5>
                    </div>
                    <?php $__currentLoopData = $progress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                      <li class="breadcrumb-item"><?php echo e($item->assessment->name); ?></li>
                                    </ol>
                                </nav>
                                <div style="min-width: 150px">
                                    <a href="<?php echo e(route('assessments.show', $item->slug )); ?>" target="__blank" class="btn button-yellow btn-sm">Show</a>
                                    <a href="<?php echo e(route('assessments.rank', $item->assessment->slug )); ?>" class="btn button-yellow btn-sm">Rank</a>
                                </div>
                            </div>
                            <p>
                                <span>Total: <?php echo e((int)$item->total_marks); ?> Marks</span>
                                <span style="color: #114bfa; margin-left: 30px">Achive: <?php echo e($item->achive_marks); ?> Marks</span>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if(count($progress) > 0): ?>
                        <a href="<?php echo e(route('assessments.see.all', $course->slug)); ?>" class="btn button-yellow btn-sm mt-3">See All</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->startPush('scripts'); ?>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/assessment/index.blade.php ENDPATH**/ ?>