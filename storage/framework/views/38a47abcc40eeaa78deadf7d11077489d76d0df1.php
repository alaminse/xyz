<?php $__env->startSection('title', 'Lecture Video'); ?>
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

        .recent-video-item {
            transition: all 0.3s ease;
            background: #f8f9fa;
            border: 1px solid #e9ecef !important;
        }

        .recent-video-item:hover {
            background: #ffffff;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .recent-videos-list {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .recent-videos-list::-webkit-scrollbar {
            width: 6px;
        }

        .recent-videos-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .recent-videos-list::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .recent-videos-list::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .video-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, 0.1);
            border-radius: 50%;
        }

        .transition {
            transition: all 0.3s ease;
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
                            <i class="bi bi-layers"></i> Lecture Video Topics
                        </h4>
                    </div>

                    <div class="accordion" id="flashAccordion">
                        <?php $__empty_1 = true; $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="accordion-item bg-transparent border-0 mb-2">
                                <h2 class="accordion-header" id="heading-<?php echo e($key); ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-<?php echo e($key); ?>">
                                        <i class="bi bi-folder2-open me-2"></i>
                                        <?php echo e($chapter->name); ?>

                                    </button>
                                </h2>

                                <div id="collapse-<?php echo e($key); ?>" class="accordion-collapse collapse"
                                    data-bs-parent="#flashAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <?php $__empty_2 = true; $__currentLoopData = $chapter->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <li class="list-group-item">
                                                    <a
                                                        href="<?php echo e(route('videos.details', ['course' => $course->slug, 'chapter' => $chapter->slug, 'lesson' => $lesson->slug])); ?>">
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
            <div class="card topic-card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="card-header bg-transparent border-0 px-0 pt-0 pb-3">
                        <h5 class="card-title text-light mb-0 fw-bold">
                            <i class="bi bi-clock-history me-2 text-primary"></i>Recent Videos
                        </h5>
                    </div>

                    <div class="recent-videos-list">
                        <?php $__currentLoopData = $latest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="recent-video-item mb-3 p-3 border rounded-3 bg-light hover-shadow transition">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="video-icon">
                                        <i class="bi bi-play-circle-fill text-primary fs-4"></i>
                                    </div>
                                    <h6 class="flex-grow-1 mb-0 text-dark"><?php echo e($item->title); ?></h6>
                                    <a href="<?php echo e(route('videos.single.details', ['slug' => $item->slug, 'course' => $course->slug])); ?>"
                                        class="btn btn-sm button-yellow text-nowrap">
                                        <i class="bi bi-eye me-1"></i>Details
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/lecture-video/index.blade.php ENDPATH**/ ?>