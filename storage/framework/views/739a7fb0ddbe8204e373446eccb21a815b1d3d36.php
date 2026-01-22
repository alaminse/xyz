<?php $__env->startSection('title', 'Assessment'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/self_assessment.css')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="assessment-container">
        
        <div class="card mb-3">
            <div class="card-body p-1 p-sm-2">
                <div
                    class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                    <div class="flex-grow-1">
                        <h5 class="card-title fs-6 fs-sm-5"><?php echo e($assessment->name); ?></h5>
                        
                    </div>
                    <div class="flex-shrink-0">
                        
                        <a href="<?php echo e(route('assessments.index', $course->slug)); ?>" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Main Question Area -->
            <div class="col-lg-8 col-md-7">
                <div id="questions-container">
                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $q = json_decode($question->questions);
                        ?>

                        <?php if($question->question_type == 'sba'): ?>
                            <div class="sba-question" data-index="<?php echo e($index); ?>" data-type="sba"
                                data-questionId="<?php echo e($question->id); ?>" style="<?php echo e($index == 0 ? '' : 'display:none;'); ?>">

                                <div class="question-card">
                                    <!-- Question Header -->
                                    <div class="question-header">
                                        <div class="question-number">
                                            <i class="fas fa-question-circle"></i> Question <?php echo e($index + 1); ?>

                                        </div>
                                        <div class="question-text"><?php echo $q->question; ?></div>
                                    </div>

                                    <input type="hidden" name="question[]" value="<?php echo e($question->id); ?>">
                                    <input type="hidden" name="type[]" value="<?php echo e($question->question_type); ?>">

                                    <!-- Options -->
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if(!empty($q->{'option' . $i})): ?>
                                            <div class="option-container d-flex align-items-center"
                                                data-option="option<?php echo e($i); ?>"
                                                onclick="selectOption<?php echo e($index); ?>(<?php echo e($i); ?>)">
                                                <input type="radio" id="option<?php echo e($i); ?>_<?php echo e($index); ?>"
                                                    name="question<?php echo e($index); ?>" value="option<?php echo e($i); ?>">
                                                <label for="option<?php echo e($i); ?>_<?php echo e($index); ?>">
                                                    <?php echo e($q->{'option' . $i}); ?>

                                                </label>
                                            </div>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    <!-- Buttons -->
                                    <div class="button-group">
                                        <?php if($index > 0): ?>
                                            <button type="button" class="btn btn-assessment btn-secondary back">
                                                <i class="fas fa-arrow-left"></i> Previous
                                            </button>
                                        <?php endif; ?>

                                        <?php if($index < count($questions) - 1): ?>
                                            <button type="button" class="btn btn-assessment next">
                                                Next <i class="fas fa-arrow-right"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-assessment btn-submit submit">
                                                <i class="fas fa-check-circle"></i> Submit Assessment
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <script>
                                    function selectOption<?php echo e($index); ?>(optionNum) {
                                        const radio = document.getElementById('option' + optionNum + '_<?php echo e($index); ?>');
                                        radio.checked = true;

                                        // Trigger change event using jQuery
                                        $(radio).trigger('change');

                                        // Visual feedback
                                        const container = document.querySelector('[data-index="<?php echo e($index); ?>"]');
                                        container.querySelectorAll('.option-container').forEach(el => {
                                            el.classList.remove('selected');
                                        });
                                        event.currentTarget.classList.add('selected');
                                    }
                                </script>
                            </div>
                        <?php elseif($question->question_type == 'mcq'): ?>
                            <div class="mcq-question" data-index="<?php echo e($index); ?>"
                                data-questionId="<?php echo e($question->id); ?>" data-type="mcq"
                                style="<?php echo e($index == 0 ? '' : 'display:none;'); ?>">

                                <div class="question-card">
                                    <!-- Question Header -->
                                    <div class="question-header">
                                        <div class="question-number">
                                            <i class="fas fa-list-check"></i> Question <?php echo e($index + 1); ?>

                                        </div>
                                        <div class="question-text"><?php echo $q->question; ?></div>
                                    </div>

                                    <input type="hidden" name="question[]" value="<?php echo e($question->id); ?>">
                                    <input type="hidden" name="type[]" value="<?php echo e($question->question_type); ?>">

                                    <!-- MCQ Options -->
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if(!empty($q->{'option' . $i})): ?>
                                            <div class="mcq-option-row">
                                                <div class="mcq-option-text">
                                                    <?php echo e($q->{'option' . $i}); ?>

                                                </div>
                                                <div class="mcq-radio-group">
                                                    <div class="mcq-radio-item" data-option="option<?php echo e($i); ?>">
                                                        <input type="radio"
                                                            id="option<?php echo e($index); ?><?php echo e($i); ?>_true"
                                                            name="question<?php echo e($index); ?>[option<?php echo e($i); ?>]"
                                                            value="true">
                                                        <label for="option<?php echo e($index); ?><?php echo e($i); ?>_true">
                                                            <i class="fas fa-check"></i> True
                                                        </label>
                                                    </div>
                                                    <div class="mcq-radio-item" data-option="option<?php echo e($i); ?>">
                                                        <input type="radio"
                                                            id="option<?php echo e($index); ?><?php echo e($i); ?>_false"
                                                            name="question<?php echo e($index); ?>[option<?php echo e($i); ?>]"
                                                            value="false">
                                                        <label for="option<?php echo e($index); ?><?php echo e($i); ?>_false">
                                                            <i class="fas fa-times"></i> False
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    <!-- Buttons -->
                                    <div class="button-group">
                                        <?php if($index > 0): ?>
                                            <button type="button" class="btn btn-assessment btn-secondary back">
                                                <i class="fas fa-arrow-left"></i> Previous
                                            </button>
                                        <?php endif; ?>

                                        <?php if($index < count($questions) - 1): ?>
                                            <button type="button" class="btn btn-assessment next">
                                                Next <i class="fas fa-arrow-right"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-assessment btn-submit submit">
                                                <i class="fas fa-check-circle"></i> Submit Assessment
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 col-md-5">
                <div class="sidebar-card">
                    <!-- Progress Section -->
                    <div class="progress-section">
                        <h4 class="sidebar-title">
                            <i class="fas fa-chart-line"></i> Your Progress
                        </h4>
                        <div id="progress-count">

                        </div>
                        <ul id="selected-options-count" class="progress-list"></ul>
                        <div id="submitButton">

                        </div>
                    </div>

                    <!-- Timer Section -->
                    <div class="timer-container">
                        <div class="timer-label">
                            <i class="fas fa-clock"></i> Time Remaining
                        </div>
                        <div id="timer" class="timer-display">00:00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="submitConfirmModal" tabindex="-1" aria-labelledby="submitConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="submitConfirmModalLabel">
                        <i class="fas fa-paper-plane"></i> Submit Assessment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="text-center mb-3">Are you sure you want to submit your assessment?</h6>
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>Total Questions:</strong></span>
                            <span id="modal-total-questions"><?php echo e(count($questions)); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>Answered:</strong></span>
                            <span id="modal-answered-questions" class="text-success">0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><strong>Remaining:</strong></span>
                            <span id="modal-remaining-questions" class="text-danger">0</span>
                        </div>
                    </div>
                    <p class="text-muted text-center mb-0">
                        <small><i class="fas fa-info-circle"></i> After submitting, you will not be able to make any changes</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="confirmSubmitBtn">
                        <i class="fas fa-check-circle"></i> Yes, Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Assessment configuration
        const assessmentConfig = {
            assessmentId: '<?php echo e($assessment->id); ?>',
            courseId: '<?php echo e($course->id); ?>',
            questions: <?php echo json_encode($questions); ?>,
            time: parseInt('<?php echo e($assessment->time); ?>'),
            csrfToken: '<?php echo e(csrf_token()); ?>',
            submitRoute: '<?php echo e(route('assessments.submit')); ?>'
        };
    </script>
    <script src="<?php echo e(asset('frontend/js/assessment_test.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/assessment/test.blade.php ENDPATH**/ ?>