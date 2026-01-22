<?php $__env->startSection('title', 'OSPE Station'); ?>
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
                    <div class="card-header">
                        <h5 class="card-title">OSPE Station</h5>
                    </div>
                    <section id="info-utile" class="">
                        <div class="container">
                            <h3 class="text-center text-uppercase mb-3 lead-h-text text-white">Topic</h3>

                            <div class="accordion" id="accordionExample">
                                <?php $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-<?php echo e($key); ?>">
                                            <a class="accordion-button collapsed" href="#" data-bs-toggle="collapse"
                                                data-bs-target="#collapse-<?php echo e($key); ?>" aria-expanded="false"
                                                aria-controls="collapse-<?php echo e($key); ?>">
                                                <?php echo e($topic->name); ?>

                                            </a>
                                        </h2>
                                        <div id="collapse-<?php echo e($key); ?>" class="accordion-collapse collapse"
                                            aria-labelledby="heading-<?php echo e($key); ?>"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <ul class="list-group">
                                                    <?php $__currentLoopData = $topic->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="list-group-item">
                                                            <a href="<?php echo e(route('ospes.test', ['course' => $course->slug, 'chapter' => $topic->slug, 'lesson' => $lesson->slug])); ?>"><?php echo e($lesson->name); ?></a>
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
                        <h5 class="card-title">Recently Added OSPE</h5>
                    </div>
                    <ul class="list-group">
                        <?php $__currentLoopData = $recentOspeStations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $station): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item">
                                <a href="<?php echo e(route('ospes.test', ['course' => $course->slug, 'chapter' => $station->chapter->slug, 'lesson' => $station->lesson->slug])); ?>">
                                    <?php echo e($station->chapter->name); ?> - <?php echo e($station->lesson->name); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/ospe/index.blade.php ENDPATH**/ ?>