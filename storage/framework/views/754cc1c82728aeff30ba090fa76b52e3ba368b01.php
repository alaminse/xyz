<?php $__env->startSection('title', 'MCQ Test'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/summernote_show.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/mcq.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="test-wrapper">
        
        <div class="card mb-3">
            <div class="">
                <div
                    class="d-flex flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                    <div class="flex-grow-1">
                    <div class="nav-header d-flex justify-content-between align-items-center">
                        <button type="button" class="btn nav-btn" id="prevBtn" disabled>
                            <i class="bi bi-arrow-left me-1"></i>
                        </button>
                        <h6 class="mb-0">
                            Q <span id="currentQ">1</span> of <span id="totalQ">0</span>
                        </h6>
                        <button type="button" class="btn nav-btn" id="nextBtn">
                            <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                        
                    </div>
                    <div class="flex-shrink-0">
                        <a href="<?php echo e(route('mcqs.index', ['course' => $course->slug])); ?>" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <!-- Left Side: Questions -->
            <div class="col-lg-8 col-md-7">
                <div class="test-container">
                    <!-- Navigation Header -->

                    <!-- Questions Container -->
                    <div id="mcqContainer">
                        <?php $globalIndex = 0; ?>
                        <?php $__currentLoopData = $mcqQuestionsFiltered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mcq-question" data-index="<?php echo e($globalIndex); ?>"
                                style="<?php echo e($globalIndex == 0 ? '' : 'display:none;'); ?>">
                                <div class="question-card">
                                    <h5><?php echo e($question->question); ?></h5>

                                    <form class="mcq-form" data-mcq-id="<?php echo e($question->mcq_id); ?>"
                                        data-question-id="<?php echo e($question->id); ?>">
                                        <input type="hidden" name="quiz_id" value="<?php echo e($quiz->id); ?>">

                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($question->{"option{$i}"}): ?>
                                                <div class="option-item" data-option="<?php echo e($i); ?>"
                                                    data-correct="<?php echo e($question->{"answer{$i}"}); ?>">
                                                    <div class="row align-items-center">
                                                        <!-- Question text -->
                                                        <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                            <span class="option-text">
                                                                <?php echo e($question->{"option{$i}"}); ?>

                                                            </span>
                                                        </div>

                                                        <!-- Controls -->
                                                        <div class="col-12 col-md-6 text-md-end">
                                                            <div
                                                                class="option-controls d-flex flex-wrap justify-content-md-end gap-2">

                                                                <span class="correct-label d-none">
                                                                    <i class="bi bi-check-circle-fill"></i> Correct
                                                                </span>

                                                                <span class="chosen-label d-none"></span>

                                                                <label class="mb-0">
                                                                    <input type="radio" name="option<?php echo e($i); ?>"
                                                                        value="1">
                                                                    <span class="badge bg-success">True</span>
                                                                </label>

                                                                <label class="mb-0">
                                                                    <input type="radio" name="option<?php echo e($i); ?>"
                                                                        value="0">
                                                                    <span class="badge bg-danger">False</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endfor; ?>

                                        <button type="submit" class="btn submit-btn">
                                            <i class="bi bi-check-circle me-2"></i>Submit Answer
                                        </button>
                                    </form>

                                    <!-- Explanation Section -->
                                    <div class="explanation-section">
                                        <?php if($question->explain): ?>
                                            <div class="explanation-card">
                                                <h6><i class="bi bi-lightbulb me-2"></i>Explanation</h6>
                                                <div><?php echo $question->explain; ?></div>
                                            </div>
                                            <!-- Navigation Buttons -->
                                            <div class="explanation-nav mb-3">
                                                <button type="button" class="btn btn-outline-secondary prevExplainBtn">
                                                    <i class="bi bi-arrow-left me-1"></i>
                                                </button>
                                                <h6 class="mb-0">

                                                </h6>
                                                <button type="button" class="btn btn-outline-primary nextExplainBtn">
                                                    <i class="bi bi-arrow-right ms-1"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($question->note): ?>
                                            <div class="note-card">
                                                <h6><i class="bi bi-journal-text me-2"></i><?php echo e($question->note?->title); ?>

                                                </h6>
                                                <div><?php echo $question->note?->description; ?></div>
                                            </div>

                                            <!-- Navigation Buttons -->
                                            <div class="explanation-nav">
                                                <button type="button" class="btn btn-outline-secondary prevExplainBtn">
                                                    <i class="bi bi-arrow-left me-1"></i>
                                                </button>
                                                <h6 class="mb-0">

                                                </h6>
                                                <button type="button" class="btn btn-outline-primary nextExplainBtn">
                                                    <i class="bi bi-arrow-right ms-1"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $globalIndex++; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- Right Side: Status -->
            <div class="col-lg-4 col-md-5">
                <div class="status-card">
                    <div class="score-display">
                        <h6>Your Score</h6>
                        <h3><span id="scorePercent">0</span>%</h3>
                    </div>

                    <h6 class="mb-3">
                        <i class="bi bi-list-check me-2"></i>Question Status
                    </h6>
                    <ul class="list-group status-list" id="statusList"></ul>

                    <a href="<?php echo e(route('mcqs.index', $course->slug)); ?>" class="btn end-btn">
                        <i class="bi bi-x-circle me-2"></i>End Session
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            let total = $('.mcq-question').length;
            let current = 0;
            let correct = 0;
            let totalAnswered = 0;
            let answered = [];

            $('#totalQ').text(total);

            function updateNav() {
                $('#currentQ').text(current + 1);
                $('#prevBtn').prop('disabled', current === 0);
                $('#nextBtn').prop('disabled', current === total - 1);
            }

            function showQuestion(index) {
                if (index < 0 || index >= total) return;
                $('.mcq-question').hide();
                $(`.mcq-question[data-index="${index}"]`).show();
                current = index;
                updateNav();
            }

            function showNext() {
                if (current < total - 1) {
                    showQuestion(++current);
                } else {
                    showCompletion();
                }
            }

            function showCompletion() {
                $('#mcqContainer').html(`
            <div class="question-card completion-screen">
                <i class="bi bi-check-circle-fill"></i>
                <h4>Test Completed!</h4>
                <p>You've answered all questions. Great job!</p>
                <div class="mt-3">
                    <p><strong>Total Score:</strong> ${$('#scorePercent').text()}%</p>
                    <p><strong>Correct Answers:</strong> ${correct} / ${totalAnswered}</p>
                </div>
                <a href="<?php echo e(route('mcqs.index', $course->slug)); ?>" class="btn btn-primary mt-3">
                    <i class="bi bi-arrow-left me-2"></i>Back to Topics
                </a>
            </div>
        `);
                $('.nav-header').hide();
            }

            // Submit Form
            $('.mcq-form').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let mcqId = form.data('mcq-id');
                let questionId = form.data('question-id');
                let answerKey = mcqId + '_' + questionId;

                if (answered.includes(answerKey)) {
                    alert('You have already answered this question!');
                    return;
                }

                let options = {};
                let allSelected = true;

                // উত্তর collect করা
                form.find('.option-item').each(function() {
                    let optNum = $(this).data('option');
                    let selected = form.find(`input[name="option${optNum}"]:checked`).val();

                    // Only send if user selected
                    if (selected !== undefined) {
                        options[`option${optNum}`] = selected;
                    }
                });
                // form.find('.option-item').each(function() {
                //     let optNum = $(this).data('option');
                //     let selected = form.find(`input[name="option${optNum}"]:checked`).val();

                //     if (selected !== undefined) {
                //         options[`option${optNum}`] = selected;
                //     } else {
                //         allSelected = false;
                //     }
                // });

                // if (!allSelected) {
                //     alert('Please answer all options before submitting!');
                //     return;
                // }

                form.find('.submit-btn').prop('disabled', true).html(
                    '<i class="bi bi-hourglass-split me-2"></i> Submitting...');

                $.ajax({
                    url: '<?php echo e(route('mcqs.updateProgress')); ?>',
                    method: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        quiz_id: form.find('[name=quiz_id]').val(),
                        mcq_id: mcqId,
                        question_id: questionId,
                        answers: options // Changed from 'options' to 'answers'
                    },
                    success: function(response) {
                        console.log(response);

                        if (response.status === 'success') {
                            answered.push(answerKey);

                            let correctCount = 0;

                            // প্রতিটি option check করা
                            form.find('.option-item').each(function() {
                                let optNum = $(this).data('option');
                                let correctAns = $(this).data('correct');
                                let selected = options.hasOwnProperty(`option${optNum}`)
                                                ? options[`option${optNum}`]
                                                : null;

                                // let selected = options[`option${optNum}`];

                                // Input disable করা

                                if (selected === null) {
                                    return; // no chosen, no wrong, no correct
                                }

                                let chosenText = selected == 1 ? 'True' : 'False';
                                let correctText = correctAns == 1 ? 'True' : 'False';

                                if (parseInt(selected) === parseInt(correctAns)) {
                                    $(this).addClass('correct');
                                    $(this).find('.correct-label').removeClass('d-none');
                                    correctCount++;
                                } else {
                                    $(this).addClass('wrong');
                                    $(this).find('.chosen-label')
                                        .removeClass('d-none')
                                        .html(`
                                            <span class="text-danger">
                                                You chose: <strong>${chosenText}</strong><br>
                                                Correct: <strong>${correctText}</strong>
                                            </span>
                                        `);
                                }

                                // $(this).find('input').prop('disabled', true);

                                // let chosenText = selected == 1 ? 'True' : 'False';
                                // let correctText = correctAns == 1 ? 'True' : 'False';

                                // if (parseInt(selected) === parseInt(correctAns)) {
                                //     $(this).addClass('correct');
                                //     $(this).find('.correct-label').removeClass(
                                //     'd-none');
                                //     correctCount++;
                                // } else {
                                //     $(this).addClass('wrong');
                                //     $(this).find('.chosen-label')
                                //         .removeClass('d-none')
                                //         .html(`
                                //     <span class="text-danger">
                                //         You chose: <strong>${chosenText}</strong><br>
                                //         Correct: <strong>${correctText}</strong>
                                //     </span>
                                // `);
                                // }
                            });

                            correct += correctCount;
                            totalAnswered += Object.keys(options).length;

                            // Score আপডেট করা
                            let percent = totalAnswered > 0 ? Math.round((correct /
                                totalAnswered) * 100) : 0;
                            $('#scorePercent').text(percent);

                            // Status list এ যোগ করা
                            let statusBadge = correctCount === Object.keys(options).length ?
                                '<span class="badge bg-success">Perfect!</span>' :
                                `<span>${correctCount}/${Object.keys(options).length}</span>`;

                            let iconClass = correctCount === Object.keys(options).length ?
                                'check-circle-fill' :
                                correctCount === 0 ? 'x-circle' : 'dash-circle';

                            $('#statusList').append(`
                        <li class="list-group-item status-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-${iconClass}"></i> Q${current + 1}</span>
                            ${statusBadge}
                        </li>
                    `);

                            // Scroll to latest status
                            $('.status-list').animate({
                                scrollTop: $('.status-list')[0].scrollHeight
                            }, 300);

                            // Submit button লুকানো, explanation দেখানো
                            form.find('.submit-btn').hide();
                            form.closest('.question-card').find('.explanation-section')
                                .slideDown();
                        } else {
                            alert('Something went wrong! Please try again.');
                            form.find('.submit-btn').prop('disabled', false).html(
                                '<i class="bi bi-check-circle me-2"></i>Submit Answer');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Failed to submit! Please try again.';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            } else if (xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                        }
                        alert(errorMsg);
                        form.find('.submit-btn').prop('disabled', false).html(
                            '<i class="bi bi-check-circle me-2"></i>Submit Answer');
                    }
                });
            });

            // Navigation
            $('#nextBtn, .nextExplainBtn').on('click', showNext);

            $('#prevBtn, .prevExplainBtn').on('click', function() {
                if (current > 0) showQuestion(--current);
            });

            updateNav();
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/mcq/test.blade.php ENDPATH**/ ?>