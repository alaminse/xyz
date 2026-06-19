<?php $__env->startSection('title', 'Written Assessment Details'); ?>
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
                        href="<?php echo e(route('writtens.details', ['course' => $course_slug, 'chapter' => $assessment->chapter?->slug, 'lesson' => $assessment->lesson?->slug])); ?>">
                        <i class="bi bi-arrow-left-circle"></i> Back
                    </a>
                </div>

                <div id="question-container">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-2">
                            <button type="button" class="btn btn-sm button-yellow" disabled id="prevBtn">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                            </button>
                        </div>
                        <div class="p-2">
                            <h6>Group <span id="current-group-number">1</span> of
                                <span id="total-group-number"><?php echo e($questionGroups->count()); ?></span>
                            </h6>
                        </div>
                        <div class="p-2">
                            <button type="button" class="btn btn-sm button-yellow" id="nextBtn"
                                <?php echo e($questionGroups->count() <= 1 ? 'disabled' : ''); ?>>
                                <i class="bi bi-arrow-right-circle-fill"></i>
                            </button>
                        </div>
                    </div>

                    <?php $__empty_1 = true; $__currentLoopData = $questionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gi => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="question-card" id="group-<?php echo e($gi); ?>" style="display: none;">
                        <?php $__currentLoopData = $group->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qi => $qa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mt-3 p-3 border rounded text-dark">
                            <div class="question-text p-2 mb-2 rounded note-title"
                                style="cursor: pointer; font-weight: bold; background-color: #e2f0fb;">
                                <?php echo e($qi + 1); ?>: <?php echo e($qa['question']); ?>

                            </div>

                            <?php if($isPaid && $isLocked): ?>
                            <div class="p-2 rounded text-center"
                                style="background-color: #fff8e1; border: 2px dashed #ffc107; border-radius: 8px;">
                                <i class="bi bi-lock-fill text-warning fs-5"></i>
                                <p class="mb-2 mt-1 small">Answer is locked</p>
                                <a href="<?php echo e(route('courses.checkout', ['course' => $course_slug])); ?>"
                                    class="btn btn-warning btn-sm fw-bold">
                                    <i class="bi bi-unlock-fill me-1"></i> Upgrade to Premium
                                </a>
                            </div>
                            <?php else: ?>
                            <div class="answer-text p-2 rounded note-description"
                                style="display: none; background-color: #d4edda;">
                                <?php echo $qa['answer']; ?>

                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i> No questions available for this assessment.
                    </div>
                    <?php endif; ?>

                    <!-- Finish Button -->
                    <div class="mt-4 text-center" id="finishSection" style="display: none;">
                        <a href="<?php echo e(route('writtens.details', ['course' => $course_slug, 'chapter' => $assessment->chapter?->slug, 'lesson' => $assessment->lesson?->slug])); ?>"
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        let groups = $('.question-card');
        let currentIndex = 0;

        function showGroup(index) {
            groups.hide();
            $('#finishSection').hide();

            $(groups[index]).fadeIn();
            $('#current-group-number').text(index + 1);

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

        showGroup(currentIndex);

        // Highlight search query
        const query = <?php echo json_encode($query ?? '', 15, 512) ?>;
        if (query) {
            const regex = new RegExp(query, 'gi');
            document.querySelectorAll('.note-title, .note-description').forEach(el => {
                el.innerHTML = el.innerHTML.replace(regex,
                    match => `<span style="background: #F1A909; color: white">${match}</span>`);
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/written-assessment/details.blade.php ENDPATH**/ ?>