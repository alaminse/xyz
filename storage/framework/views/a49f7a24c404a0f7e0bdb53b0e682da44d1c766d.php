<?php $__env->startSection('title', 'Written Assessment'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="card mb-3">
    <h5 class="p-3">Written Assessment</h5>
</div>

<div class="row mt-4">
    <div class="col-12 mx-auto">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a class="btn btn-sm btn-warning"
                        href="<?php echo e(route('writtens.index', ['course' => $course_slug])); ?>">
                        <i class="bi bi-arrow-left-circle"></i> Back
                    </a>
                    <div>
                        <span class="badge bg-info text-dark fs-6">
                            Progress: <span id="completedCount"><?php echo e($completedGroups); ?></span> / <?php echo e($totalGroups); ?>

                        </span>
                        <div class="progress mt-1" style="height:6px; width:150px;">
                            <div class="progress-bar bg-success" id="progressBar"
                                style="width: <?php echo e($totalGroups > 0 ? round(($completedGroups / $totalGroups) * 100) : 0); ?>%">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="question-container">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-2">
                            <button type="button" class="btn btn-sm button-yellow" disabled id="prevBtn">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                            </button>
                        </div>
                        <div class="p-2">
                            <h6>Group <span id="currentGroupNum">1</span> of <?php echo e($totalGroups); ?></h6>
                        </div>
                        <div class="p-2">
                            <button type="button" class="btn btn-sm button-yellow" id="nextBtn"
                                <?php echo e($totalGroups <= 1 ? 'disabled' : ''); ?>>
                                <i class="bi bi-arrow-right-circle-fill"></i>
                            </button>
                        </div>
                    </div>

                    <?php $__currentLoopData = $questionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gi => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="question-card" id="group-<?php echo e($gi); ?>" style="display:none;"
                        data-group-id="<?php echo e($group->id); ?>">

                        <?php if($group->isPaid && $isLocked): ?>
                        <div class="p-3 rounded text-center"
                            style="background:#fff8e1; border:2px dashed #ffc107; border-radius:8px;">
                            <i class="bi bi-lock-fill text-warning fs-4"></i>
                            <p class="mb-2 mt-1 small">This group is locked</p>
                            <a href="<?php echo e(route('courses.checkout', ['course' => $course_slug])); ?>"
                                class="btn btn-warning btn-sm fw-bold">
                                <i class="bi bi-unlock-fill me-1"></i> Upgrade to Premium
                            </a>
                        </div>
                        <?php else: ?>
                        <?php $__currentLoopData = $group->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qi => $qa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mt-3 p-3 border rounded text-dark">
                            <div class="question-text p-2 mb-2 rounded"
                                style="cursor:pointer; font-weight:bold; background-color:#e2f0fb;">
                                <?php echo e($qi + 1); ?>: <?php echo e($qa['question']); ?>

                            </div>
                            <div class="answer-text p-2 rounded"
                                style="display:none; background-color:#d4edda;">
                                <?php echo $qa['answer']; ?>

                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php
                            $isDone = in_array($group->id, $completedGroupIds);
                        ?>
                        <div class="text-center mt-3">
                            <button type="button"
                                class="btn btn-sm <?php echo e($isDone ? 'btn-success' : 'btn-outline-success'); ?> mark-done-btn"
                                data-group-id="<?php echo e($group->id); ?>"
                                data-course-id="<?php echo e($course->id); ?>"
                                data-chapter-id="<?php echo e($chapter->id); ?>"
                                data-lesson-id="<?php echo e($lesson?->id); ?>"
                                <?php echo e($isDone ? 'disabled' : ''); ?>>
                                <i class="bi bi-check-circle<?php echo e($isDone ? '-fill' : ''); ?> me-1"></i>
                                <?php echo e($isDone ? 'Completed' : 'Mark as Done'); ?>

                            </button>
                        </div>
                        <?php endif; ?>

                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="mt-4 text-center" id="finishSection" style="display:none;">
                        <a href="<?php echo e(route('writtens.index', ['course' => $course_slug])); ?>"
                            class="btn button-yellow px-5">
                            Finish
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(function() {
        let groups      = $('.question-card');
        let currentIndex = 0;
        let completed   = <?php echo e($completedGroups); ?>;
        let total       = <?php echo e($totalGroups); ?>;

        function showGroup(index) {
            groups.hide();
            $('#finishSection').hide();
            $(groups[index]).fadeIn();
            $('#currentGroupNum').text(index + 1);
            $('#prevBtn').prop('disabled', index === 0);
            $('#nextBtn').prop('disabled', index === groups.length - 1);
            if (index === groups.length - 1) {
                $('#finishSection').fadeIn();
            }
        }

        $(document).on('click', '.question-text', function() {
            $(this).next('.answer-text').slideToggle();
        });

        $('#nextBtn').on('click', function() {
            if (currentIndex < groups.length - 1) {
                currentIndex++;
                showGroup(currentIndex);
            }
        });

        $('#prevBtn').on('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                showGroup(currentIndex);
            }
        });

        $(document).on('click', '.mark-done-btn', function() {
            const btn       = $(this);
            const groupId   = btn.data('group-id');
            const courseId  = btn.data('course-id');
            const chapterId = btn.data('chapter-id');
            const lessonId  = btn.data('lesson-id');

            $.ajax({
                url: '<?php echo e(route('writtens.progress.save')); ?>',
                method: 'POST',
                data: {
                    _token:             '<?php echo e(csrf_token()); ?>',
                    course_id:          courseId,
                    chapter_id:         chapterId,
                    lesson_id:          lessonId,
                    question_group_id:  groupId,
                },
                success: function(res) {
                    if (res.success) {
                        completed++;
                        const pct = Math.round((completed / total) * 100);
                        $('#completedCount').text(completed);
                        $('#progressBar').css('width', pct + '%');

                        btn.removeClass('btn-outline-success')
                           .addClass('btn-success')
                           .prop('disabled', true)
                           .html('<i class="bi bi-check-circle-fill me-1"></i> Completed');
                    }
                }
            });
        });

        showGroup(currentIndex);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/written-assessment/details.blade.php ENDPATH**/ ?>