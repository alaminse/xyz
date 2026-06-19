
<?php $__env->startSection('title', 'Mock Viva Details'); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .question-group-card {
            margin-bottom: 1.5rem;
            border: 1px solid #007bff;
            border-radius: 0.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .question-header {
            font-weight: 600;
            font-size: 1.2rem;
            color: #0056b3;
            margin-bottom: 1rem;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.25rem;
        }

        .qa-item {
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.4rem;
            padding: 0.75rem;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
        }

        .question-text {
            background-color: #e2f0fb;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            font-weight: 600;
            user-select: none;
        }

        .answer-text {
            background-color: #d4edda;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            margin-top: 0.5rem;
            color: #155724;
            font-style: italic;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title d-flex justify-content-between align-items-center">
                <h2>Mock Viva Details</h2>
                <a href="<?php echo e(route('admin.mockvivas.index')); ?>" class="btn btn-sm btn-success">Back to List</a>
            </div>
            <div class="x_content pt-4">
                <div class="mockviva-details mb-4 p-3 border rounded bg-white shadow-sm">
                    <div class="row text-dark">
                        <div class="col-md-6 mb-3">
                            <h5 class="mb-1 fw-bold">Course</h5>
                            <?php $__currentLoopData = $mock->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-primary"><?php echo e($course->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5 class="mb-1 fw-bold">Chapter</h5>
                            <p class="mb-0"><?php echo e($mock->chapter->name ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5 class="mb-1 fw-bold">Lesson</h5>
                            <p class="mb-0"><?php echo e($mock->lesson->name ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h5 class="mb-1 fw-bold">Status</h5>
                            <?php
                                $statusLabels = [
                                    0 => ['Pending', 'badge bg-warning text-dark'],
                                    1 => ['Active', 'badge bg-success'],
                                    2 => ['Inactive', 'badge bg-secondary'],
                                ];
                                $status = $statusLabels[$mock->status] ?? ['Unknown', 'badge bg-dark'];
                            ?>
                            <span class="<?php echo e($status[1]); ?>"><?php echo e($status[0]); ?></span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h5 class="mb-1 fw-bold">Is Paid</h5>
                            <?php if($mock->isPaid): ?>
                                <span class="badge bg-info text-dark">Yes</span>
                            <?php else: ?>
                                <span class="badge bg-light text-muted">No</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <hr>

                <?php
                    // Decode questions JSON into array for display
                    $questionGroups = $mock->questions->map(function ($q) {
                        return ['questions' => json_decode($q->questions, true)];
                    });
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $questionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="question-group-card">
                        <div class="question-header">Question Group <?php echo e($groupIndex + 1); ?></div>
                        <?php if(isset($group['questions']) && count($group['questions']) > 0): ?>
                            <?php $__currentLoopData = $group['questions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qIndex => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="qa-item">
                                    <div class="question-text">Q<?php echo e($qIndex + 1); ?>: <?php echo e($q['question'] ?? 'N/A'); ?></div>
                                    <div class="answer-text">Answer: <?php echo strip_tags($q['answer'] ?? 'N/A'); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <p class="text-muted">No questions in this group.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted">No question groups available for this Mock Viva.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.backend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/mockviva/show.blade.php ENDPATH**/ ?>