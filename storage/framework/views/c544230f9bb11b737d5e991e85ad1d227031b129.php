<?php $__env->startSection('title', 'SBA Review'); ?>
<?php $__env->startSection('css'); ?>

<link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
<style>
    .review-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
    }
    .review-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .option-correct {
        background: #d4edda !important;
        border-color: #28a745 !important;
        font-weight: 600;
    }
    .option-wrong {
        background: #f8d7da !important;
        border-color: #dc3545 !important;
    }
    .option-item {
        border: 2px solid #e0e0e0;
        margin-bottom: 10px;
        border-radius: 8px;
        padding: 12px;
    }
    .explanation-box {
        background: #e7f3ff;
        border-left: 4px solid #2196f3;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }
    .note-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }
    .sidebar-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 12px;
        padding: 20px;
        position: sticky;
        top: 20px;
    }
    .question-link {
        background: rgba(255,255,255,0.1);
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .question-link:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.5);
        transform: translateX(5px);
    }
    .question-link.active {
        background: rgba(255,255,255,0.3);
        border-color: white;
    }
    .score-badge {
        background: rgba(255,255,255,0.2);
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 20px;
    }
    .score-badge h4 {
        color: white;
        font-weight: bold;
        font-size: 2rem;
        margin: 0;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    
    <div class="card mb-3">
        <div class="card-body">
            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-2">Sba Test Review</h5>
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
                    <a href="<?php echo e(route('sbas.index', ['course' => $quiz->course?->slug])); ?>"
                        class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-8">
            <div class="review-container">
                <?php if(empty($answers)): ?>
                    <div class="review-card text-center py-5">
                        <i class="bi bi-exclamation-circle text-warning" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">No Answers Found</h4>
                        <p class="text-muted">You haven't answered any questions yet.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                            <i class="bi bi-arrow-left-circle"></i>
                        </button>
                        <h6 class="text-white mb-0">
                            Question <span id="current-question-number">1</span> of
                            <span id="total-question-number"><?php echo e(count($answers)); ?></span>
                        </h6>
                        <button type="button" class="btn btn-light btn-sm" id="next-button">
                            <i class="bi bi-arrow-right-circle"></i>
                        </button>
                    </div>

                    <div id="sba-container">
                        <?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="sba-question" data-index="<?php echo e($index); ?>" style="<?php echo e($index == 0 ? '' : 'display:none;'); ?>">
                                <div class="review-card">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="mb-0">
                                            <?php if(isset($answer['question'])): ?>
                                                <?php echo $answer['question']; ?>

                                            <?php else: ?>
                                                Question not available
                                            <?php endif; ?>
                                        </h5>
                                        <span class="badge <?php echo e(isset($answer['is_correct']) && $answer['is_correct'] ? 'bg-success' : 'bg-danger'); ?>">
                                            <?php if(isset($answer['is_correct']) && $answer['is_correct']): ?>
                                                <i class="bi bi-check-circle me-1"></i>Correct
                                            <?php else: ?>
                                                <i class="bi bi-x-circle me-1"></i>Wrong
                                            <?php endif; ?>
                                        </span>
                                    </div>

                                    <ul class="list-unstyled">
                                        <?php
                                            $options = ['option1', 'option2', 'option3', 'option4', 'option5'];
                                            $hasOptions = false;
                                        ?>

                                        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(isset($answer[$option]) && !empty($answer[$option])): ?>
                                                <?php
                                                    $hasOptions = true;
                                                    $isCorrect = isset($answer['correct_option']) && $answer['correct_option'] == $option;
                                                    $isSelected = isset($answer['selected_option']) && $answer['selected_option'] == $option;
                                                    $class = $isCorrect ? 'option-correct' : ($isSelected ? 'option-wrong' : '');
                                                ?>
                                                <li class="option-item <?php echo e($class); ?>">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <?php if($isSelected): ?>
                                                                <i class="bi bi-hand-index me-2 <?php echo e($isCorrect ? 'text-success' : 'text-danger'); ?>"></i>
                                                            <?php endif; ?>
                                                            <?php echo e($answer[$option]); ?>

                                                        </span>
                                                        <?php if($isCorrect): ?>
                                                            <i class="bi bi-check-circle-fill text-success"></i>
                                                        <?php elseif($isSelected && !$isCorrect): ?>
                                                            <i class="bi bi-x-circle-fill text-danger"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <?php if(!$hasOptions): ?>
                                            <li class="option-item">
                                                <span class="text-muted">Options not available</span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>

                                    <?php if(isset($answer['explain']) && !empty($answer['explain'])): ?>
                                        <div class="explanation-box">
                                            <h6><i class="bi bi-lightbulb text-primary me-2"></i>Explanation</h6>
                                            <p class="mb-0"><?php echo $answer['explain']; ?></p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                                                <i class="bi bi-arrow-left-circle"></i>
                                            </button>
                                            <h6 class="text-white mb-0">
                                                Question <span id="current-question-number">1</span> of
                                                <span id="total-question-number"><?php echo e(count($answers)); ?></span>
                                            </h6>
                                            <button type="button" class="btn btn-light btn-sm" id="next-button">
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <?php if(isset($answer['note_description']) && !empty($answer['note_description'])): ?>
                                        <div class="note-box">
                                            <h6>
                                                <i class="bi bi-journal-text text-warning me-2"></i>
                                                Related Note
                                                <?php if(isset($answer['note_title']) && !empty($answer['note_title'])): ?>
                                                    : <?php echo e($answer['note_title']); ?>

                                                <?php endif; ?>
                                            </h6>
                                            <div><?php echo $answer['note_description']; ?></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                                                <i class="bi bi-arrow-left-circle"></i>
                                            </button>
                                            <h6 class="text-white mb-0">
                                                Question <span id="current-question-number">1</span> of
                                                <span id="total-question-number"><?php echo e(count($answers)); ?></span>
                                            </h6>
                                            <button type="button" class="btn btn-light btn-sm" id="next-button">
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="sidebar-card">
                <div class="score-badge">
                    <h6 class="text-white mb-2">Final Score</h6>
                    <h4>
                        <?php echo e(count($answers) > 0
                            ? number_format(($quiz->correct / count($answers)) * 100, 2)
                            : '0.00'); ?> %
                    </h4>
                    <small class="text-white"><?php echo e($quiz->correct ?? 0); ?> Correct | <?php echo e(count($answers)); ?> Total</small>
                </div>

                <?php if(!empty($answers)): ?>
                    <h6 class="text-white mb-3">
                        <i class="bi bi-list-check me-2"></i>All Questions
                    </h6>
                    <div style="max-height: 500px; overflow-y: auto;">
                        <?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="question-link" data-index="<?php echo e($index); ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">
                                        <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                            <strong>Q<?php echo e($index + 1); ?>.</strong>
                                            <?php if(isset($answer['question'])): ?>
                                                <?php echo strip_tags($answer['question']); ?>

                                            <?php else: ?>
                                                Question <?php echo e($index + 1); ?>

                                            <?php endif; ?>
                                        </span>
                                    </span>
                                    <?php if(isset($answer['is_correct']) && $answer['is_correct']): ?>
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    <?php else: ?>
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="<?php echo e(url()->previous()); ?>" class="btn btn-light w-100">
                        <i class="bi bi-arrow-left me-2"></i>Back to Test
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            $(document).ready(function () {
                const totalQuestions = $('.sba-question').length;
                let currentQuestionIndex = 0;

                // Debug: Log answers data
                console.log('Total questions:', totalQuestions);
                console.log('Answers data:', <?php echo json_encode($answers, 15, 512) ?>);

                if (totalQuestions === 0) {
                    console.warn('No questions found to display');
                    return;
                }

                function updateQuestionDisplay() {
                    $('#current-question-number').text(currentQuestionIndex + 1);
                    $('#next-button').prop('disabled', currentQuestionIndex === totalQuestions - 1);
                    $('#previous-button').prop('disabled', currentQuestionIndex === 0);

                    // Update active question in sidebar
                    $('.question-link').removeClass('active');
                    $(`.question-link[data-index="${currentQuestionIndex}"]`).addClass('active');
                }

                function showQuestion(index) {
                    if (index < 0 || index >= totalQuestions) return;

                    $('.sba-question').hide();
                    $(`.sba-question[data-index="${index}"]`).show();
                    currentQuestionIndex = index;
                    updateQuestionDisplay();
                }

                $('#next-button').click(() => {
                    if (currentQuestionIndex < totalQuestions - 1) {
                        showQuestion(currentQuestionIndex + 1);
                    }
                });

                $('#previous-button').click(() => {
                    if (currentQuestionIndex > 0) {
                        showQuestion(currentQuestionIndex - 1);
                    }
                });

                $('.question-link').click(function () {
                    const index = $(this).data('index');
                    showQuestion(index);
                });

                // Keyboard navigation
                $(document).keydown(function(e) {
                    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (currentQuestionIndex < totalQuestions - 1) {
                            showQuestion(currentQuestionIndex + 1);
                        }
                    } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (currentQuestionIndex > 0) {
                            showQuestion(currentQuestionIndex - 1);
                        }
                    }
                });

                updateQuestionDisplay();
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/sba/review.blade.php ENDPATH**/ ?>