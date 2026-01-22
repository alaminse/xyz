<?php $__env->startSection('title', 'SBA Test'); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="card mb-2 topic-card">
        <div class="card-body p-2">
            <div
                class="d-flex flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light btn-sm" id="previous-button">
                        <i class="bi bi-arrow-left-circle"></i>
                    </button>
                    <h6 class="text-white mb-0">
                        Q <span id="current-question-number">1</span> of
                        <span id="total-question-number"><?php echo e(count($sbaQuestionsFiltered)); ?></span>
                    </h6>
                    <button type="button" class="btn btn-light btn-sm" id="next-button">
                        <i class="bi bi-arrow-right-circle"></i>
                    </button>
                </div>
                    
                </div>
                <div class="flex-shrink-0">
                    <a href="<?php echo e(route('sbas.index', ['course' => $quiz->course?->slug])); ?>"
                        class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-8 mb-2">
            <div id="sba-container">
                <?php $__currentLoopData = $sbaQuestionsFiltered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="sba-question" data-index="<?php echo e($index); ?>"
                        style="<?php echo e($index == 0 ? '' : 'display:none;'); ?>">
                        <div class="question-card">
                            <h5><?php echo $question->question; ?></h5>
                            <form class="sba-form" data-question-id="<?php echo e($question->id); ?>"
                                data-sba-id="<?php echo e($question->sba_id); ?>">
                                <input type="hidden" name="quiz_id" value="<?php echo e($quiz->id); ?>" required>
                                <input type="hidden" name="sba_id" value="<?php echo e($question->sba_id); ?>" required>
                                <input type="hidden" name="question_id" value="<?php echo e($question->id); ?>" required>

                                <ul class="list-group">
                                    <?php $__currentLoopData = ['option1', 'option2', 'option3', 'option4', 'option5']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($question->{$option}): ?>
                                            <li class="list-group-item custom-list-item">
                                                <label class="w-100">
                                                    <input type="radio" name="selected_option"
                                                        value="<?php echo e($option); ?>" required class="me-2">
                                                    <?php echo e($question->{$option}); ?>

                                                </label>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-check-circle me-2"></i>Submit Answer
                                </button>

                                <div class="explanation-card" style="display:none;">
                                    <h6><i class="bi bi-lightbulb text-warning me-2"></i>Explanation</h6>
                                    <p class="mb-3"></p>

                                    <?php if($question->note): ?>
                                        <div class="note-section mt-3">
                                            <h6><i class="bi bi-journal-text text-info me-2"></i>Related Note:
                                                <?php echo e($question->note?->title); ?></h6>
                                            <div class="mb-3"><?php echo $question->note?->description; ?></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <button type="button" class="btn btn-outline-secondary btn-sm prev-btn">
                                                ←
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm next-btn">
                                                →
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="col-sm-12 col-md-4 mb-2">
            <div class="status-card">
                <a class="btn btn-light btn-sm mb-3 w-100" href="<?php echo e(route('sbas.index', $course->slug)); ?>">
                    <i class="bi bi-x-circle"></i> End Session
                </a>

                <div class="score-display">
                    <h6 class="text-white mb-2">Your Score</h6>
                    <small class="text-white">Correct: <span id="correctCount"><?php echo e($quiz->correct ?? 0); ?></span> | Wrong:
                        <span id="wrongCount"><?php echo e($quiz->wrong ?? 0); ?></span></small>
                </div>

                <h6 class="text-white mb-3"><i class="bi bi-list-check me-2"></i>Question Status</h6>
                <ul id="question-status" class="list-group">
                    <!-- Answered questions will be dynamically added here -->
                </ul>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            $(document).ready(function() {
                let totalQuestions = $('.sba-question').length;
                let currentQuestionIndex = 0;

                $('#total-question-number').text(totalQuestions);

                function updateQuestionDisplay() {
                    $('#current-question-number').text(currentQuestionIndex + 1);
                    $('#previous-button').prop('disabled', currentQuestionIndex === 0);
                    $('#next-button').prop('disabled', currentQuestionIndex === totalQuestions - 1);
                }

                function showQuestion(index) {
                    $('.sba-question').hide();
                    $(`.sba-question[data-index=${index}]`).show();
                    updateQuestionDisplay();
                }

                function showNextQuestion() {
                    if (currentQuestionIndex < totalQuestions - 1) {
                        currentQuestionIndex++;
                        showQuestion(currentQuestionIndex);
                    } else {
                        showFinishedButton();
                    }
                }

                function showFinishedButton() {
                    $('#sba-container').html(`
                        <div class="question-card text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                            <h4 class="mt-3">Quiz Completed!</h4>
                            <p class="text-muted">You've answered all questions in this set.</p>
                            <form method="POST" action="<?php echo e(route('sbas.finish')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="quiz_id" value="<?php echo e($quiz->id); ?>">
                                <button type="submit" class="btn btn-primary btn-lg mt-3">
                                    <i class="bi bi-eye me-2"></i>Review Answers
                                </button>
                            </form>
                        </div>
                    `);
                }

                function updateScoreDisplay(quizData) {
                    $('#scorePercentage').text(quizData.progress_cut);
                    $('#correctCount').text(quizData.correct);
                    $('#wrongCount').text(quizData.wrong);
                    $('#progressBar').css('width', quizData.progress + '%');
                    $('#progressBar').attr('aria-valuenow', quizData.progress);
                    $('#progressText').text(quizData.progress + '%');
                }

                // Submit Answer
                $('.sba-form').on('submit', function(e) {
                    e.preventDefault();

                    let form = $(this);
                    let selectedOption = form.find('input[name="selected_option"]:checked').val();

                    if (!selectedOption) {
                        alert('Please select an option!');
                        return;
                    }

                    // Disable submit button to prevent double submission
                    let submitBtn = form.find('button[type="submit"]');
                    submitBtn.prop('disabled', true).html(
                        '<i class="spinner-border spinner-border-sm me-2"></i>Submitting...');

                    $.ajax({
                        url: '<?php echo e(route('sbas.updateProgress')); ?>',
                        type: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            quiz_id: form.find('input[name="quiz_id"]').val(),
                            sba_id: form.find('input[name="sba_id"]').val(),
                            question_id: form.find('input[name="question_id"]').val(),
                            selected_option: selectedOption
                        },
                        success: function(response) {
                            console.log(response);

                            // Show explanation
                            let explanationCard = form.find('.explanation-card');
                            explanationCard.find('p').html(response.question.explain ||
                                'No explanation available.');
                            explanationCard.show();

                            // Disable all options and highlight correct/wrong
                            form.find('input[name="selected_option"]').each(function() {
                                let option = $(this).val();
                                let li = $(this).closest('li');

                                if (option === response.correct_option) {
                                    li.addClass('bg-success');
                                    li.find('label').prepend(
                                        '<i class="bi bi-check-circle-fill me-2"></i>');
                                } else if (option === response.selected_option) {
                                    li.addClass('bg-danger');
                                    li.find('label').prepend(
                                        '<i class="bi bi-x-circle-fill me-2"></i>');
                                }
                                $(this).prop('disabled', true);
                            });

                            // Update score display
                            updateScoreDisplay(response.quiz);

                            // Add to status panel
                            if (!$(`#question-status li[data-index="${currentQuestionIndex}"]`)
                                .length) {
                                $('#question-status').append(`
                                    <li class="list-group-item question-status-item d-flex justify-content-between"
                                        data-index="${currentQuestionIndex}">
                                        <span>Question ${currentQuestionIndex + 1}</span>
                                        <span>
                                            ${response.is_correct
                                                ? '<i class="bi bi-check-circle-fill text-success"></i>'
                                                : '<i class="bi bi-x-circle-fill text-danger"></i>'}
                                        </span>
                                    </li>
                                `);
                            }

                            // Hide submit button
                            submitBtn.hide();
                        },
                        error: function(xhr) {
                            let errorMsg = 'An error occurred. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            }
                            alert(errorMsg);

                            // Re-enable submit button on error
                            submitBtn.prop('disabled', false).html(
                                '<i class="bi bi-check-circle me-2"></i>Submit Answer');
                        }
                    });
                });

                // Global navigation buttons
                $('#next-button').on('click', showNextQuestion);

                $('#previous-button').on('click', function() {
                    if (currentQuestionIndex > 0) {
                        currentQuestionIndex--;
                        showQuestion(currentQuestionIndex);
                    }
                });

                // Explanation card navigation buttons
                $(document).on('click', '.next-btn', showNextQuestion);

                $(document).on('click', '.prev-btn', function() {
                    if (currentQuestionIndex > 0) {
                        currentQuestionIndex--;
                        showQuestion(currentQuestionIndex);
                    }
                });

                updateQuestionDisplay();
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/sba/test.blade.php ENDPATH**/ ?>