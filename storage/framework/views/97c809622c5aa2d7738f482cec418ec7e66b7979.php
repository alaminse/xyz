<?php $__env->startSection('title', 'Assessments'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <div
                            class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-2">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-2">Assessments</h5>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><?php echo e($cour->name); ?></li>
                                        <?php if(isset($chap)): ?>
                                            <li class="breadcrumb-item"><?php echo e($chap->name); ?></li>
                                        <?php endif; ?>
                                        <?php if(isset($less)): ?>
                                            <li class="breadcrumb-item active text-light" aria-current="page"><?php echo e($less->name); ?></li>
                                        <?php endif; ?>
                                    </ol>
                                </nav>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="<?php echo e(route('assessments.index', $cour->slug)); ?>" class="btn button-yellow btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Course
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php if(isset($assessments) && $assessments->isNotEmpty()): ?>
                        <?php $__currentLoopData = $assessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6><?php echo e($assessment->name); ?></h6>
                                        </div>
                                        <a href="<?php echo e(route('assessments.exam', ['assessment' => $assessment->slug, 'course' => $cour->slug])); ?>"
                                            class="btn button-yellow btn-sm py-2 px-4 align-self-start">
                                            Continue
                                        </a>
                                    </div>

                                    <ul class="list-group mt-2">
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            Start Date
                                            <span class="badge text-bg-primary rounded-pill">
                                                <?php echo e($assessment->start_date_time ? \Carbon\Carbon::parse($assessment->start_date_time)->format('F j, Y, g:i a') : 'Not Set'); ?>

                                            </span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            End Date
                                            <span class="badge text-bg-warning rounded-pill">
                                                <?php echo e($assessment->end_date_time ? \Carbon\Carbon::parse($assessment->end_date_time)->format('F j, Y, g:i a') : 'Not Set'); ?>

                                            </span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            Total Marks
                                            <span
                                                class="badge text-bg-primary rounded-pill"><?php echo e($assessment->total_marks); ?></span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            Time Duration
                                            <span class="badge text-bg-info rounded-pill"><?php echo e($assessment->time); ?>

                                                min</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            Type
                                            <span
                                                class="badge text-bg-<?php echo e($assessment->isPaid ? 'warning' : 'success'); ?> rounded-pill">
                                                <?php echo e($assessment->isPaid ? 'Paid' : 'Free'); ?>

                                            </span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
                                            Status
                                            <span
                                                class="badge text-bg-<?php echo e(\App\Enums\Status::from($assessment->status)->message()); ?> rounded-pill text-light">
                                                <?php echo e(\App\Enums\Status::from($assessment->status)->title()); ?>

                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php if(isset($progress) && $progress->isNotEmpty()): ?>
                        <?php $__currentLoopData = $progress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4><?php echo e($item->assessment->name); ?></h4>
                                        <a href="<?php echo e(route('assessments.show', $item->slug)); ?>" target="__blank"
                                            class="btn button-yellow btn-sm">Show</a>
                                    </div>

                                    <p>
                                        <span>Total: <?php echo e((int) $item->total_marks); ?> Marks</span>
                                        <span style="color: #114bfa; margin-left: 30px">Achieved: <?php echo e($item->achive_marks); ?>

                                            Marks</span>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/assessment/see_all.blade.php ENDPATH**/ ?>