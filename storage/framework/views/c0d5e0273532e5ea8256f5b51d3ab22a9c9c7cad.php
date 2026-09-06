<?php $__env->startSection('title', 'Review Questions'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/summernote_show.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/sba.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/mcq.css')); ?>">
    <style>
        .option-correct { background: #d4edda !important; border-color: #28a745 !important; font-weight: 600; }
        .option-wrong   { background: #f8d7da !important; border-color: #dc3545 !important; }
        .option-item {
            border: 2px solid #e0e0e0;
            margin-bottom: 10px;
            border-radius: 8px;
            padding: 12px;
        }
        .explanation-box {
            background: #e7f3ff; border-left: 4px solid #2196f3;
            padding: 15px; border-radius: 8px; margin-top: 15px;
        }
        .note-box {
            background: #fff3cd; border-left: 4px solid #ffc107;
            padding: 15px; border-radius: 8px; margin-top: 15px;
        }
        .sidebar-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px; padding: 15px; position: sticky; top: 20px;
        }
        .question-link {
            background: rgba(255,255,255,0.1);
            padding: 10px; border-radius: 8px; margin-bottom: 8px;
            cursor: pointer; border: 2px solid transparent; transition: all .3s ease;
        }
        .question-link:hover { background: rgba(255,255,255,0.2); }
        .question-link.active { background: rgba(255,255,255,0.3); border-color: #fff; }
        .score-badge {
            background: rgba(255,255,255,0.2); padding: 15px;
            border-radius: 10px; text-align: center; margin-bottom: 20px;
        }
        .score-badge h4 { color: #fff; font-weight: bold; font-size: 2rem; margin: 0; }
        #typeTabs .nav-link { cursor: pointer; }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    
    <div class="card mb-3">
        <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
            <div class="flex-grow-1">
                <h5 class="card-title mb-2">Review Questions</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><?php echo e($course->name); ?></li>
                        <li class="breadcrumb-item"><?php echo e($chapter->name); ?></li>
                        <?php if($lesson): ?>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e($lesson->name); ?></li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
            <a href="<?php echo e(route('review_questions.index', $course->slug)); ?>" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        
        <div class="col-sm-12 col-md-8">

            
            <ul class="nav nav-pills mb-3" id="typeTabs">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-filter="all">
                        All (<?php echo e($totalCount); ?>)
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-filter="sba">
                        SBA (<?php echo e($sbaItems->count()); ?>)
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-filter="mcq">
                        MCQ (<?php echo e($mcqItems->count()); ?>)
                    </button>
                </li>
            </ul>

            <?php if($allItems->isEmpty()): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    You haven't solved any SBA or MCQ questions in this chapter yet.
                </div>
            <?php else: ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn-light btn-sm" id="previous-button" disabled>
                        <i class="bi bi-arrow-left-circle"></i>
                    </button>
                    <h6 class="mb-0">
                        Question <span id="current-question-number">1</span> of
                        <span id="total-question-number"><?php echo e($allItems->count()); ?></span>
                    </h6>
                    <button type="button" class="btn btn-light btn-sm" id="next-button">
                        <i class="bi bi-arrow-right-circle"></i>
                    </button>
                </div>

                <div id="review-container">
                    <?php $__currentLoopData = $allItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Locked only when this specific module is paid AND the user is on FREETRIAL/not enrolled
                            $itemLocked = ($item['isPaid'] ?? false) && $isLocked;
                        ?>
                        <div class="review-question" data-type="<?php echo e($item['type']); ?>" data-index="<?php echo e($index); ?>"
                             style="<?php echo e($index == 0 ? '' : 'display:none;'); ?>">
                            <div class="question-card">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary text-uppercase"><?php echo e($item['type']); ?></span>
                                    <?php if(! $itemLocked): ?>
                                        <span class="badge <?php echo e(($item['is_correct'] ?? false) ? 'bg-success' : 'bg-danger'); ?>">
                                            <?php if($item['is_correct'] ?? false): ?>
                                                <i class="bi bi-check-circle-fill me-1"></i> Correct
                                            <?php else: ?>
                                                <i class="bi bi-x-circle-fill me-1"></i> Wrong
                                            <?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <h5 class="mb-4"><?php echo $item['question'] ?? 'Question not available'; ?></h5>

                                <?php if($item['type'] === 'sba'): ?>
                                    
                                    <ul class="list-unstyled">
                                        <?php $__currentLoopData = ['option1', 'option2', 'option3', 'option4', 'option5']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(!empty($item[$opt])): ?>
                                                <?php
                                                    $isCorrect  = ($item['correct_option'] ?? null) === $opt;
                                                    $isSelected = ($item['selected_option'] ?? null) === $opt;
                                                ?>
                                                <li class="option-item <?php echo e((!$itemLocked && $isCorrect) ? 'option-correct' : ((!$itemLocked && $isSelected) ? 'option-wrong' : '')); ?>">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <?php if($isSelected): ?>
                                                                <i class="bi bi-hand-index me-2 <?php echo e((!$itemLocked && $isCorrect) ? 'text-success' : 'text-danger'); ?>"></i>
                                                            <?php endif; ?>
                                                            <?php echo e($item[$opt]); ?>

                                                        </span>
                                                        <?php if(! $itemLocked): ?>
                                                            <?php if($isCorrect): ?>
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            <?php elseif($isSelected): ?>
                                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                <?php else: ?>
                                    
                                    <?php $__currentLoopData = ($item['answers'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $selected = $ans['selected'] ?? null;
                                            $correct  = $ans['correct'] ?? null;
                                            $subCorrect = $selected !== null && (int) $selected === (int) $correct;
                                        ?>
                                        <div class="option-item <?php echo e($itemLocked ? '' : ($selected === null ? '' : ($subCorrect ? 'option-correct' : 'option-wrong'))); ?>">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                    <strong><?php echo e($ans['option_text'] ?? ''); ?></strong>
                                                </div>
                                                <div class="col-12 col-md-6 text-md-end">
                                                    <?php if(! $itemLocked): ?>
                                                        <?php if($subCorrect): ?>
                                                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Correct</span>
                                                        <?php elseif($selected !== null): ?>
                                                            <span class="badge bg-danger">
                                                                You chose: <strong><?php echo e($selected ? 'True' : 'False'); ?></strong> —
                                                                Correct: <strong><?php echo e($correct ? 'True' : 'False'); ?></strong>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Not answered</span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                                <?php if($itemLocked): ?>
                                    <div class="text-center py-4 px-3 mt-3"
                                         style="background:#fff8e1; border:2px dashed #ffc107; border-radius:12px;">
                                        <i class="bi bi-lock-fill text-warning" style="font-size:2.5rem;"></i>
                                        <h5 class="mt-3 mb-2">Answer is locked</h5>
                                        <p class="text-muted mb-3">
                                            This is Premium content. Please upgrade your plan to see the answer.
                                        </p>
                                        <a href="<?php echo e(route('courses.checkout', ['course' => $course->slug])); ?>"
                                           class="btn btn-warning fw-bold">
                                            <i class="bi bi-unlock-fill"></i> Upgrade to Premium
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <?php if(!empty($item['explain'])): ?>
                                        <div class="explanation-box">
                                            <h6><i class="bi bi-lightbulb text-warning"></i> Explanation</h6>
                                            <div><?php echo $item['explain']; ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if(!empty($item['note_description'])): ?>
                                        <div class="note-box">
                                            <h6><i class="bi bi-journal-text text-info"></i>
                                                <?php echo e($item['note_title'] ?? 'Related Note'); ?>

                                            </h6>
                                            <div><?php echo $item['note_description']; ?></div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="col-sm-12 col-md-4">
            <div class="sidebar-card">
                <div class="score-badge">
                    <h6 class="text-white mb-2">Overall Score</h6>
                    <h4><?php echo e($totalCount > 0 ? number_format(($correctCount / $totalCount) * 100, 2) : '0.00'); ?>%</h4>
                    <small class="text-white"><?php echo e($correctCount); ?> Correct | <?php echo e($totalCount); ?> Total</small>
                </div>

                <?php if($allItems->isNotEmpty()): ?>
                    <h6 class="text-white mb-3"><i class="bi bi-list-check me-2"></i>All Questions</h6>
                    <div style="max-height: 500px; overflow-y: auto;">
                        <?php $__currentLoopData = $allItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="question-link" data-index="<?php echo e($index); ?>" data-type="<?php echo e($item['type']); ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">
                                        <span class="badge bg-dark text-uppercase me-1"><?php echo e($item['type']); ?></span>
                                        <span class="d-inline-block text-truncate" style="max-width: 120px;">
                                            Q<?php echo e($index + 1); ?>. <?php echo e(strip_tags($item['question'] ?? '')); ?>

                                        </span>
                                    </span>
                                    <?php if(($item['isPaid'] ?? false) && $isLocked): ?>
                                        <i class="bi bi-lock-fill text-warning"></i>
                                    <?php elseif($item['is_correct'] ?? false): ?>
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    <?php else: ?>
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            $(document).ready(function () {
                const totalQuestions = $('.review-question').length;
                let currentIndex = 0;
                let activeFilter = 'all';

                function visibleQuestions() {
                    return $('.review-question').filter(function () {
                        return activeFilter === 'all' || $(this).data('type') === activeFilter;
                    });
                }

                function showQuestion(index) {
                    const items = visibleQuestions();
                    if (index < 0 || index >= items.length) return;

                    $('.review-question').hide();
                    items.eq(index).show();

                    currentIndex = index;
                    $('#current-question-number').text(currentIndex + 1);
                    $('#total-question-number').text(items.length);
                    $('#previous-button').prop('disabled', currentIndex === 0);
                    $('#next-button').prop('disabled', currentIndex === items.length - 1);

                    $('.question-link').removeClass('active');
                    const globalIdx = items.eq(index).data('index');
                    $(`.question-link[data-index="${globalIdx}"]`).addClass('active');
                }

                $('#next-button').on('click', () => showQuestion(currentIndex + 1));
                $('#previous-button').on('click', () => showQuestion(currentIndex - 1));

                $('.question-link').on('click', function () {
                    const globalIndex = $(this).data('index');
                    const items = visibleQuestions();

                    let idx = -1;
                    items.each(function (i) {
                        if ($(this).data('index') === globalIndex) idx = i;
                    });
                    if (idx !== -1) showQuestion(idx);
                });

                $('#typeTabs button').on('click', function () {
                    $('#typeTabs button').removeClass('active');
                    $(this).addClass('active');
                    activeFilter = $(this).data('filter');

                    $('.question-link').each(function () {
                        const show = activeFilter === 'all' || $(this).data('type') === activeFilter;
                        $(this).toggle(show);
                    });

                    showQuestion(0);
                });

                $(document).on('keydown', function (e) {
                    if (e.key === 'ArrowRight') showQuestion(currentIndex + 1);
                    if (e.key === 'ArrowLeft') showQuestion(currentIndex - 1);
                });

                if (totalQuestions > 0) showQuestion(0);
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/review/show.blade.php ENDPATH**/ ?>