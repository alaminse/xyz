<?php $__currentLoopData = $flashs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $flash): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($index + 1); ?></td>
        <td>
            <span class="badge badge-info">
                <i class="fa fa-list"></i> <?php echo e($flash->questions_count); ?> Questions
            </span>
        </td>
        <td>
            <?php $__empty_1 = true; $__currentLoopData = $flash->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <span class="badge badge-primary"><?php echo e($course->name); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <span class="text-muted">N/A</span>
            <?php endif; ?>
        </td>
        <td>
            <span class="badge badge-secondary">
                <?php echo e($flash->chapter?->name ?? 'N/A'); ?>

            </span>
        </td>
        <td>
            <span class="badge badge-light text-dark">
                <?php echo e($flash->lesson?->name ?? 'N/A'); ?>

            </span>
        </td>
        <td>
            <?php if($flash->isPaid): ?>
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
            <a href="<?php echo e(route('admin.flashs.status', $flash->id)); ?>"
               class="btn btn-sm btn-<?php echo e(\App\Enums\Status::from($flash->status)->message()); ?>">
                <?php echo e(\App\Enums\Status::from($flash->status)->title()); ?>

            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.flashs.show', $flash->id)); ?>"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.flashs.edit', $flash->id)); ?>"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger <?php echo e($flash->status == 3 ? 'disabled' : ''); ?> btn-sm" onclick="showDeleteConfirmation(event)" href="<?php echo e(route('admin.flashs.destroy', $flash->id)); ?>">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/flash_rows.blade.php ENDPATH**/ ?>