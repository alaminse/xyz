<?php $__env->startSection('title', 'Written Assessment'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
    <style>
        .button-yellow:hover,
        .button-white:hover {
            color: white;
        }

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
                        <h4 class="card-title text-white text-center mb-4">
                            <i class="bi bi-bookmark-star"></i> <?php echo e($course->name); ?> Topics
                        </h4>
                    </div>
                    <?php echo $__env->make('backend.includes.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="accordion" id="accordionExample">
                        <?php $__empty_1 = true; $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="accordion-item bg-transparent border-0 mb-2">
                                <h2 class="accordion-header" id="heading-<?php echo e($key); ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-<?php echo e($key); ?>" aria-expanded="false"
                                        aria-controls="collapse-<?php echo e($key); ?>">
                                        <i class="bi bi-folder2-open me-2"></i> <?php echo e($chapter->name); ?>

                                    </button>
                                </h2>
                                <div id="collapse-<?php echo e($key); ?>" class="accordion-collapse collapse"
                                    aria-labelledby="heading-<?php echo e($key); ?>" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            <?php $__empty_2 = true; $__currentLoopData = $chapter->lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                <li class="list-group-item">
                                                    <a
                                                        href="<?php echo e(route('writtens.details', ['course' => $course->slug, 'chapter' => $chapter->slug, 'lesson' => $lesson->slug])); ?>">
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
                    <div id="progressContainer">
                        <div class="text-center text-white-50 py-4">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Loading progress...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            function loadProgress() {
                $.ajax({
                    url: '<?php echo e(route('writtens.progress.index', ['course_id' => $course->id])); ?>',
                    method: 'GET',
                    success: function(res) {
                        const container = $('#progressContainer');
                        container.html('');

                        if (!res.progress || res.progress.length === 0) {
                            container.html(`
                        <div class="text-center text-white-50 py-4">
                            <i class="bi bi-info-circle me-2"></i>No progress yet. Start practicing!
                        </div>
                    `);
                            return;
                        }

                        res.progress.forEach(function(item) {
                            const isLocked = <?php echo e($isLocked ? 'true' : 'false'); ?>;
                            const checkoutUrl =
                                '<?php echo e(route('courses.checkout', ['course' => $course->slug])); ?>';
                            const detailsUrl =
                                '<?php echo e(route('writtens.details', ['course' => $course->slug, 'chapter' => '__chapter__', 'lesson' => '__lesson__'])); ?>'
                                .replace('__chapter__', item.chapter_slug)
                                .replace('__lesson__', item.lesson_slug);

                            const actionBtn = isLocked ?
                                `<a href="${checkoutUrl}" class="btn btn-warning btn-sm fw-bold">
                                    <i class="bi bi-lock-fill me-1"></i> Upgrade
                                </a>` :
                                                        `<a href="${detailsUrl}" class="btn btn-primary btn-sm">
                                    Re-Practice
                                </a>`;

                            const pct = item.percentage;

                            container.append(`
                                <div class="sba-item mb-3">
                                    <div class="sba-item-header d-flex justify-content-between align-items-center mb-1">
                                        <h5 class="mb-0 text-white">
                                            ${item.chapter_name}
                                            <small class="text-white-50">/ ${item.lesson_name}</small>
                                        </h5>
                                        ${actionBtn}
                                    </div>
                                    <small class="text-white-50">
                                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                                        ${item.completed} / ${item.total} groups completed
                                    </small>
                                    <div class="progress mt-2" style="height: 8px; border-radius: 4px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: ${pct}%"
                                            aria-valuenow="${pct}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-white-50">${pct}% complete</small>
                                        ${pct === 100
                                            ? `<small class="text-success"><i class="bi bi-trophy-fill me-1"></i>Completed!</small>`
                                            : `<small class="text-white-50">${100 - pct}% remaining</small>`
                                        }
                                    </div>
                                </div>
                                <hr style="border-color: rgba(255,255,255,0.1);">
                            `);
                        });
                    },
                    error: function() {
                        $('#progressContainer').html(`
                    <div class="text-center text-white-50 py-4">
                        <i class="bi bi-exclamation-circle me-2"></i>Failed to load progress. Please refresh.
                    </div>
                `);
                    }
                });
            }

            $(document).ready(function() {
                loadProgress();
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/written-assessment/index.blade.php ENDPATH**/ ?>