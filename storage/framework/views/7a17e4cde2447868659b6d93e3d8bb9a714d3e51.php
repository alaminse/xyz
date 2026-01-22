<?php $__currentLoopData = $mcqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $mcq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($index + 1); ?></td>
        <td>
            <span class="badge badge-info">
                <i class="fa fa-list"></i> <?php echo e($mcq->questions_count); ?> Questions
            </span>
        </td>
        <td>
            <?php $__empty_1 = true; $__currentLoopData = $mcq->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <span class="badge badge-primary"><?php echo e($course->name); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <span class="text-muted">N/A</span>
            <?php endif; ?>
        </td>
        <td>
            <span class="badge badge-secondary">
                <?php echo e($mcq->chapter?->name ?? 'N/A'); ?>

            </span>
        </td>
        <td>
            <span class="badge badge-light text-dark">
                <?php echo e($mcq->lesson?->name ?? 'N/A'); ?>

            </span>
        </td>
        <td>
            <?php if($mcq->isPaid): ?>
                <span class="badge badge-warning">
                    <i class="fa fa-lock"></i> Paid
                </span>
            <?php else: ?>
                <span class="badge badge-success">
                    <i class="fa fa-unlock"></i> Free
                </span>
            <?php endif; ?>
        </td>
        <td>
            <a href="<?php echo e(route('admin.mcqs.status', $mcq->id)); ?>"
               class="btn btn-sm btn-<?php echo e(\App\Enums\Status::from($mcq->status)->message()); ?>">
                <?php echo e(\App\Enums\Status::from($mcq->status)->title()); ?>

            </a>
        </td>
        <td>
            <div class="btn-group" role="group">
                <a class="btn btn-info btn-sm"
                   href="<?php echo e(route('admin.mcqs.show', $mcq->id)); ?>"
                   title="View & Manage Questions">
                    <i class="fa fa-eye"></i>
                </a>
                <a class="btn btn-primary btn-sm"
                   href="<?php echo e(route('admin.mcqs.edit', $mcq->id)); ?>"
                   title="Edit">
                    <i class="fa fa-edit"></i>
                </a>
                <a class="btn btn-danger btn-sm"
                   onclick="showDeleteConfirmation(event)"
                   href="<?php echo e(route('admin.mcqs.destroy', $mcq->id)); ?>"
                   title="Delete">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/mcq_rows.blade.php ENDPATH**/ ?>