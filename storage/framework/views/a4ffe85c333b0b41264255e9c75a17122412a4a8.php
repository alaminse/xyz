<?php $__env->startSection('title', 'Assessments'); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('frontend/css/summernote_show.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Main Content -->
    <div>
        
        <div class="card mb-3">
            <div class="card-body">
                <div
                    class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-2">Assessment Test</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><?php echo e($course->name); ?></li>
                                <li class="breadcrumb-item"><?php echo e($assessment->chapter?->name); ?></li>
                                <?php if($assessment->lesson): ?>
                                    <li class="breadcrumb-item active text-mute" aria-current="page">
                                        <?php echo e($assessment->lesson?->name); ?>

                                    </li>
                                <?php endif; ?>
                            </ol>
                        </nav>
                    </div>
                    <div class="flex-shrink-0">
                        
                        <a href="<?php echo e(route('assessments.index', $course->slug)); ?>" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3 p-2">
            <h4><?php echo e($assessment->name); ?></h4>
        </div>
        <!-- Summary Cards -->
        <div class="summary-container">
            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-2">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-2">Assessments Result</h5>
                </div>
                <div class="flex-shrink-0">
                    <a href="<?php echo e(route('assessments.print', $data['slug'])); ?>" target="_blank"
                        class="btn btn-sm button-yellow">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>

            <!-- Exam Name -->
            <div class="summary-card">
                <div class="label">Exam Name : </div>
                <p class="value" style="font-size: 16px;"><?php echo e($data['name']); ?></p>
            </div>

            <!-- Student Name -->
            <div class="summary-card">
                <div class="label">Student Name</div>
                <div class="value" style="font-size: 18px;"><?php echo e($data['student_name']); ?></div>
            </div>

            <!-- Total Marks -->
            <div class="summary-card">
                <div class="label">Total Marks</div>
                <div class="value"><?php echo e($data['total_marks']); ?></div>
            </div>

            <!-- Achieved Marks -->
            <div class="summary-card">
                <div class="label">Achieved Marks</div>
                <div class="value" style="color: #28a745;"><?php echo e($data['achive_marks']); ?></div>
            </div>

            <!-- Result -->
            <div class="summary-card">
                <div class="label">Result</div>
                <div class="value">
                    <?php
                        $percentage = ($data['achive_marks'] / $data['total_marks']) * 100;
                        $isPassed = $percentage >= 70;
                    ?>
                    <span class="result-badge <?php echo e($isPassed ? 'pass' : 'fail'); ?>">
                        <?php echo e($isPassed ? 'PASS' : 'FAIL'); ?>

                    </span>
                </div>
            </div>
        </div>

        <!-- Questions Container -->
        <div class="questions-container">
            <?php $__currentLoopData = $data['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="question-block">
                    <div class="question-header">
                        <p class="question-text">Q<?php echo e($index + 1); ?>. <?php echo $question['question']; ?></p>
                    </div>

                    <?php if($question['question_type'] == 'sba'): ?>
                        <?php
                            $correctOption = $question['options']['correct_option'];
                            $userAnswer = $question['options']['user_option'] ?? null;
                            $optionLabels = ['A', 'B', 'C', 'D', 'E'];
                        ?>

                        <div class="row g-3 sba-options-container">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php
                                    $optionKey = 'option' . $i;
                                    $optionValue = $question['options'][$optionKey] ?? null;

                                    $class = '';
                                    $indicator = '';
                                    $statusBadge = '';

                                    if ($userAnswer === $optionKey && $userAnswer === $correctOption) {
                                        $class = 'correct';
                                        $indicator = '✓';
                                        $statusBadge = '<span class="badge bg-success ms-2">Your Answer</span>';
                                    } elseif ($userAnswer === $optionKey && $userAnswer != $correctOption) {
                                        $class = 'incorrect';
                                        $indicator = '✗';
                                        $statusBadge = '<span class="badge bg-danger ms-2">Your Answer</span>';
                                    } elseif ($correctOption === $optionKey) {
                                        $class = 'correct';
                                        $indicator = '✓';
                                        $statusBadge =
                                            '<span class="badge bg-success-subtle text-success ms-2">Correct</span>';
                                    }
                                ?>

                                <?php if($optionValue): ?>
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <div
                                            class="option-box <?php echo e($class); ?> h-100 d-flex align-items-center justify-content-between">
                                            <div class="option-content">
                                                <strong class="option-label"><?php echo e($optionLabels[$i - 1]); ?>.</strong>
                                                <span class="option-text"><?php echo e($optionValue); ?></span>
                                            </div>
                                            <div class="option-status d-flex align-items-center gap-2">
                                                <?php if($indicator): ?>
                                                    <span class="indicator" style="font-size: 20px; font-weight: bold;">
                                                        <?php echo e($indicator); ?>

                                                    </span>
                                                <?php endif; ?>
                                                <?php if($statusBadge): ?>
                                                    <?php echo $statusBadge; ?>

                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    <?php elseif($question['question_type'] == 'mcq'): ?>
                        <?php
                            $optionLabels = ['A', 'B', 'C', 'D', 'E'];
                        ?>

                        <div class="row g-3 mcq-options-container">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php
                                    $optionKey = 'option' . $i;
                                    $userOptionKey = 'user_option' . $i;
                                    $answerOption = 'answers' . $i;
                                    $optionValue = $question['options'][$optionKey] ?? null;
                                    $userOptionValue = $question['options'][$userOptionKey] ?? null;
                                    $answerOptionValue = $question['options'][$answerOption] ?? null;

                                    $class = '';
                                    $badgeClass = '';
                                    $borderClass = '';
                                    $bgClass = '';
                                    $indicator = '';
                                    $userText = '';
                                    $correctText = '';

                                    // Determine correct answer
                                    if ($answerOptionValue == 1) {
                                        $correctText = 'Correct: True';
                                    } elseif ($answerOptionValue == 0) {
                                        $correctText = 'Correct: False';
                                    }

                                    // Check user's answer
if ($userOptionValue !== null && $answerOptionValue !== null) {
    $userOptionValue = $userOptionValue == 'false' ? 0 : 1;
    $userText = $userOptionValue == 1 ? 'Your Answer: True' : 'Your Answer: False';

    if ($userOptionValue == $answerOptionValue) {
        $class = 'correct';
        $borderClass = 'border-success';
        $bgClass = 'bg-success-subtle';
        $badgeClass = 'bg-success';
        $indicator = ' ✓';
    } else {
        $class = 'incorrect';
        $borderClass = 'border-danger';
        $bgClass = 'bg-danger-subtle';
        $badgeClass = 'bg-danger';
        $indicator = ' ✗';
    }
} elseif ($answerOptionValue !== null) {
    $class = 'neutral';
    $borderClass = 'border-warning';
    $bgClass = 'bg-warning-subtle';
    $badgeClass = 'bg-secondary';
    $userText = 'Not Answered';
} else {
    $borderClass = 'border-secondary';
    $bgClass = 'bg-light';
                                    }
                                ?>

                                <?php if($optionValue): ?>
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <div class="card h-100 <?php echo e($borderClass); ?> <?php echo e($bgClass); ?>"
                                            style="border-width: 2px;">
                                            <div class="card-body d-flex flex-column p-3">
                                                
                                                <div class="mb-auto">
                                                    <div class="d-flex align-items-start gap-2">
                                                        <span class="badge bg-secondary rounded-circle"
                                                            style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                            <?php echo e($optionLabels[$i - 1]); ?>

                                                        </span>
                                                        <div class="flex-grow-1">
                                                            <p class="mb-0 fw-normal"
                                                                style="font-size: 14px; line-height: 1.6;">
                                                                <?php echo e($optionValue); ?>

                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="border-top">
                                                    <?php if($userText): ?>
                                                        <span
                                                            class="badge <?php echo e($badgeClass); ?> text-white d-inline-flex align-items-center gap-1"
                                                            style="font-size: 11px;">
                                                            <?php if($class == 'correct'): ?>
                                                                <i class="fas fa-check-circle"></i>
                                                            <?php elseif($class == 'incorrect'): ?>
                                                                <i class="fas fa-times-circle"></i>
                                                            <?php else: ?>
                                                                <i class="fas fa-minus-circle"></i>
                                                            <?php endif; ?>
                                                            <?php echo e($userText); ?><?php echo e($indicator); ?>

                                                        </span>
                                                    <?php endif; ?>

                                                    <?php if($correctText && $class == 'incorrect'): ?>
                                                        <div>
                                                            <span
                                                                class="badge bg-success-subtle text-success border border-success d-inline-flex align-items-center gap-1"
                                                                style="font-size: 11px;">
                                                                <i class="fas fa-lightbulb"></i>
                                                                <?php echo e($correctText); ?>

                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($question['explanation'])): ?>
                        <div class="explanation-section">
                            <h3><u>Explanation</u></h3>
                            <?php echo $question['explanation']; ?>

                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // সমস্ত explanation section এর ভিতরের টেবিলগুলো খুঁজে বের করা
                const explanationSections = document.querySelectorAll('.explanation-section');

                explanationSections.forEach(section => {
                    const tables = section.querySelectorAll('table');

                    tables.forEach(table => {
                        if (!table.parentElement.classList.contains('table-responsive')) {
                            // নতুন wrapper div তৈরি করা
                            const wrapper = document.createElement('div');
                            wrapper.classList.add('table-responsive');

                            // টেবিলের আগে wrapper ইনসার্ট করা
                            table.parentNode.insertBefore(wrapper, table);

                            // টেবিলকে wrapper এর ভিতরে রাখা
                            wrapper.appendChild(table);
                        }
                    });
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/assessment/show.blade.php ENDPATH**/ ?>