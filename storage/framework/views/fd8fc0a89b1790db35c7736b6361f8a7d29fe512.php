<?php $__env->startSection('title', 'MCQ Review'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/summernote_show.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/mcq.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="card mb-3">
        <div class="card-body">
            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-2">Mcq Test Review</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><?php echo e($quiz->course?->name); ?></li>
                            <li class="breadcrumb-item"><?php echo e($quiz->chapter?->name); ?></li>
                            <?php if($quiz->lesson): ?>
                                <li class="breadcrumb-item active text-mute" aria-current="page"><?php echo e($quiz->lesson?->name); ?>

                                </li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>
                <div class="flex-shrink-0">
                    <a href="<?php echo e(route('mcqs.index', ['course' => $quiz->course?->slug])); ?>"
                        class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="test-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                        <i class="bi bi-arrow-left-circle"></i>
                    </button>
                    <h6 class="text-white mb-0">
                        Question <span id="current-question-number">1</span> of
                        <span id="total-question-number"><?php echo e(count($questions)); ?></span>
                    </h6>
                    <button type="button" class="btn btn-light btn-sm" id="next-button">
                        <i class="bi bi-arrow-right-circle"></i>
                    </button>
                </div>

                <div id="sba-container">
                    <?php
                        $correctAnswers = 0;
                        $totalAnsweredAnswers = 0;
                    ?>

                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mcq-question" data-index="<?php echo e($index); ?>">
                            <div class="question-card">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary">Question <?php echo e($index + 1); ?></span>
                                </div>

                                <h5 class="mb-4"><?php echo e($question->question); ?></h5>

                                <?php $__currentLoopData = $question->answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Count each MCQ answer individually
                                        if ($answer->selected !== null) {
                                            $totalAnsweredAnswers++;

                                            if ((int) $answer->selected === (int) $answer->correct) {
                                                $correctAnswers++;
                                            }
                                        }
                                    ?>

                                    <div
                                        class="option-item
                        <?php echo e($answer->selected === null ? '' : ($answer->selected == $answer->correct ? 'correct' : 'wrong')); ?>

                        option-container mb-2">

                                        <div class="row align-items-center">
                                            <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                <strong><?php echo e($answer->option_text); ?></strong>
                                            </div>

                                            <div class="col-12 col-md-6 text-md-end">
                                                <?php if($answer->selected !== null && $answer->selected == $answer->correct): ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Correct
                                                    </span>
                                                <?php elseif($answer->selected !== null): ?>
                                                    <span class="badge bg-danger">
                                                        You chose:
                                                        <strong><?php echo e($answer->selected ? 'True' : 'False'); ?></strong><br>
                                                        Correct: <strong><?php echo e($answer->correct ? 'True' : 'False'); ?></strong>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Not answered</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                
                                <?php if(!empty($question->explain)): ?>
                                    <div class="explanation-card mt-3">
                                        <h6><i class="bi bi-lightbulb text-warning"></i> Explanation</h6>
                                        <div><?php echo $question->explain; ?></div>
                                    </div>
                                <?php endif; ?>

                                
                                <?php if(!empty($question->note_description)): ?>
                                    <div class="note-preview mt-3">
                                        <h6><i class="bi bi-journal-text text-info"></i> Related Note</h6>
                                        <?php if(!empty($question->note_title)): ?>
                                            <strong><?php echo e($question->note_title); ?></strong>
                                        <?php endif; ?>
                                        <div class="mt-2"><?php echo $question->note_description; ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>


            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="status-card">
                <div class="score-display">
                    <?php
                        $percentage = $totalAnsweredAnswers > 0
                            ? ($correctAnswers / $totalAnsweredAnswers) * 100
                            : 0;

                        $progressClass =
                            $percentage >= 75 ? 'bg-success'
                            : ($percentage >= 50 ? 'bg-warning'
                            : ($percentage >= 25 ? 'bg-info'
                            : 'bg-danger'));
                    ?>

                    <h3><?php echo e(number_format($percentage, 2)); ?>%</h3>
                    <small><?php echo e($correctAnswers); ?> out of <?php echo e($totalAnsweredAnswers); ?> answered correct</small>


                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo e($progressClass); ?>"
                            role="progressbar" style="width: <?php echo e($percentage); ?>%;" aria-valuenow="<?php echo e($percentage); ?>"
                            aria-valuemin="0" aria-valuemax="100">
                            <?php echo e(number_format($percentage, 0)); ?>%
                        </div>
                    </div>

                </div>
                <h6 class="mb-3"><i class="bi bi-list-check"></i> Questions</h6>
                <div class="questions-list">

                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="question-link" data-index="<?php echo e($index); ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <small>
                                        <span class="text-warning fw-bold">Q <?php echo e($index + 1); ?>.</span>
                                        <?php echo e(Str::limit($question->question, 50)); ?>

                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            $(document).ready(function() {

                const totalQuestions = $('.mcq-question').length;
                let currentIndex = 0;

                function showQuestion(index) {
                    if (index < 0 || index >= totalQuestions) return;

                    $('.mcq-question').hide();
                    $('.mcq-question[data-index="' + index + '"]').fadeIn(200);

                    currentIndex = index;
                    updateUI();
                }

                function updateUI() {
                    $('#current-question-number').text(currentIndex + 1);

                    $('#previous-button').prop('disabled', currentIndex === 0);
                    $('#next-button').prop('disabled', currentIndex === totalQuestions - 1);

                    $('.question-link').removeClass('active');
                    $('.question-link[data-index="' + currentIndex + '"]').addClass('active');
                }

                // 👉 Sidebar question click
                $('.question-link').on('click', function() {
                    let index = $(this).data('index');
                    showQuestion(index);
                });

                // 👉 Next / Previous
                $('#next-button').on('click', function() {
                    showQuestion(currentIndex + 1);
                });

                $('#previous-button').on('click', function() {
                    showQuestion(currentIndex - 1);
                });

                // 👉 Keyboard support
                $(document).on('keydown', function(e) {
                    if (e.key === 'ArrowRight') showQuestion(currentIndex + 1);
                    if (e.key === 'ArrowLeft') showQuestion(currentIndex - 1);
                });

                // 🔥 Initial load
                $('.mcq-question').hide();
                showQuestion(0);
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/mcq/review.blade.php ENDPATH**/ ?>