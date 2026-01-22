<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Assessment</h5>
                    </div>
                    <?php if($assessment): ?>
                        <div class="card mt-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <?php if(isset($assessment->assessment)): ?>
                                                <li class="breadcrumb-item">
                                                    <?php echo e($assessment->assessment->name); ?></li>
                                            <?php else: ?>
                                                <li class="breadcrumb-item"><?php echo e($assessment->name); ?>

                                                </li>
                                            <?php endif; ?>
                                        </ol>
                                    </nav>
                                    <?php if(isset($assessment->assessment)): ?>
                                        <a href="<?php echo e(route('assessments.print', $assessment->slug)); ?>"
                                            target="__blank" class="btn button-yellow btn-sm">Show</a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('assessments.show', $assessment->slug)); ?>"
                                            class="btn button-yellow btn-sm">Start</a>
                                    <?php endif; ?>
                                </div>
                                <?php if(isset($assessment->assessment)): ?>
                                    <p>
                                        <span>Total: <?php echo e((int) $assessment->total_marks); ?> Marks</span>
                                        <span style="color: #117afa; margin-left: 30px">Achive:
                                            <?php echo e($assessment->achive_marks); ?> Marks</span>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">SBA</h5>
                    </div>
                    <?php if($sba): ?>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><?php echo e($sba->getCourseName()); ?></li>
                                  <li class="breadcrumb-item"><?php echo e($sba->getChapterName()); ?></li>
                                  <li class="breadcrumb-item text-secondary" aria-current="page"><?php echo e($sba->getLessonName()); ?></li>
                                    </ol>
                                </nav>
                                <a href="<?php echo e(route('sbas.review', $sba->slug)); ?>" class="btn btn-sm button-yellow">Review</a>
                            </div>
                            <?php
                                $progressItem = $sba->total > 0 ? ($sba->current_question_index / $sba->total) * 100 : 0;
                                $progressItemClass = '';

                                if ($progressItem >= 75) {
                                    $progressItemClass = 'bg-success';
                                } elseif ($progressItem >= 50) {
                                    $progressItemClass = 'bg-warning';
                                } elseif ($progressItem >= 25) {
                                    $progressItemClass = 'bg-info';
                                } else {
                                    $progressItemClass = 'bg-danger';
                                }
                            ?>

                            <div class="progress mt-2">
                                <div class="progress-bar progress-bar-striped <?php echo e($progressItemClass); ?>" role="progressbar"
                                    style="width: <?php echo e($progressItem); ?>%;" aria-valuenow="<?php echo e($progressItem); ?>"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <?php echo e(number_format($progressItem, 0)); ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">MCQ</h5>
                    </div>
                    <?php if($mcq): ?>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><?php echo e($mcq->getCourseName()); ?></li>
                                  <li class="breadcrumb-item"><?php echo e($mcq->getChapterName()); ?></li>
                                  <li class="breadcrumb-item text-secondary" aria-current="page"><?php echo e($mcq->getLessonName()); ?></li>
                                    </ol>
                                </nav>
                                <a href="<?php echo e(route('mcqs.review', $mcq->slug)); ?>" class="btn btn-sm button-yellow">Review</a>
                            </div>
                            <?php
                                $progressItem = $mcq->progress > 0 ? ($mcq->progress_cut / $mcq->progress) * 100 : 0;
                                $progressItemClass = '';

                                if ($progressItem >= 75) {
                                    $progressItemClass = 'bg-success';
                                } elseif ($progressItem >= 50) {
                                    $progressItemClass = 'bg-warning';
                                } elseif ($progressItem >= 25) {
                                    $progressItemClass = 'bg-info';
                                } else {
                                    $progressItemClass = 'bg-danger';
                                }
                            ?>

                            <div class="progress mt-2">
                                <div class="progress-bar progress-bar-striped <?php echo e($progressItemClass); ?>" role="progressbar"
                                    style="width: <?php echo e($progressItem); ?>%;" aria-valuenow="<?php echo e($progressItem); ?>"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <?php echo e(number_format($progressItem, 0)); ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">FLASH CARD</h5>
                    </div>
                    <?php if($flash): ?>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><?php echo e($flash->getCourseName()); ?></li>
                                        <li class="breadcrumb-item"><?php echo e($flash->getChapterName()); ?></li>
                                        <li class="breadcrumb-item text-secondary" aria-current="page">
                                            <?php echo e($flash->getLessonName()); ?></li>
                                    </ol>
                                </nav>

                                <a href="<?php echo e(route('flashs.review', $flash->slug)); ?>" class="btn btn-sm button-yellow">Review</a>
                            </div>
                            <?php
                                $progress =
                                    $flash->total > 0 ? ($flash->current_question_index / $flash->total) * 100 : 0;
                                $progressClass = '';

                                if ($progress >= 75) {
                                    $progressClass = 'bg-success';
                                } elseif ($progress >= 50) {
                                    $progressClass = 'bg-warning';
                                } elseif ($progress >= 25) {
                                    $progressClass = 'bg-info';
                                } else {
                                    $progressClass = 'bg-danger';
                                }
                            ?>

                            <div class="progress mt-2">
                                <div class="progress-bar progress-bar-striped <?php echo e($progressClass); ?>" role="progressbar"
                                    style="width: <?php echo e($progress); ?>%;" aria-valuenow="<?php echo e($progress); ?>"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <?php echo e(number_format($progress, 0)); ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">My Courses</h5>
            <table class="table">
                <thead>
                    <tr> <!-- Changed th to tr -->
                        <th>ITEM</th> <!-- Changed td to th for table headers -->
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Price</th>
                        <th>Sell Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr> <!-- Closing tr -->
                </thead>
                <tbody>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <img class="image-preview img-fluid" src="<?php echo e(getImageUrl($course->course?->banner)); ?>" alt="<?php echo e($course->course?->title); ?>" style="height: 40px; width: 40px;">
                        </td>
                        <td>
                            <div><strong><?php echo e($course->course?->name); ?></strong></div>
                            <?php echo e($course->course?->detail?->duration); ?> <?php echo e($course->course?->detail?->type); ?>

                        </td>
                        <td>
                            <?php echo e($course->start_date); ?>

                        </td>
                        <td>
                            <?php echo e($course->end_date); ?>

                        </td>
                        <td>
                            <?php echo e($course->price); ?>

                        </td>
                        <td>
                            <?php echo e($course->sell_price); ?>

                        </td>
                        <td>
                            <a href="#"
                                class="btn btn-<?php echo e(\App\Enums\Status::from($course->status)->message()); ?> btn-sm  <?php echo e($course->status == 3 ? 'disabled' : ''); ?>">
                                <?php echo e(\App\Enums\Status::from($course->status)->title()); ?></a>
                        </td>
                        <td>
                            <a target="__blank" class="btn btn-info btn-sm" href="<?php echo e(route('courses.invoice', $course->slug)); ?>">Invoice</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/index.blade.php ENDPATH**/ ?>