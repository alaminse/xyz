<?php $__empty_1 = true; $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        <td><?php echo e($key + 1); ?></td>
        <td>
            <span class="badge badge-info"><?php echo e($note->details_count); ?> section(s)</span>
        </td>
        <td>
            <?php $__empty_2 = true; $__currentLoopData = $note->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                <span class="badge badge-primary mr-1"><?php echo e($course->name); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                <span class="text-muted">N/A</span>
            <?php endif; ?>
        </td>
        <td><?php echo e($note->chapter->name ?? '-'); ?></td>
        <td><?php echo e($note->lesson->name ?? '-'); ?></td>
        <td>
            <?php if($note->isPaid): ?>
                <span class="badge badge-success">Paid</span>
            <?php else: ?>
                <span class="badge badge-secondary">Free</span>
            <?php endif; ?>
        </td>
        <td>
            <a href="<?php echo e(route('admin.notes.status', $note->id)); ?>"
               onclick="event.preventDefault(); document.getElementById('status-form-<?php echo e($note->id); ?>').submit();">
                <?php if($note->status == 1): ?>
                    <span class="badge badge-success">Active</span>
                <?php elseif($note->status == 2): ?>
                    <span class="badge badge-warning">Inactive</span>
                <?php else: ?>
                    <span class="badge badge-danger">Deleted</span>
                <?php endif; ?>
            </a>
            <form id="status-form-<?php echo e($note->id); ?>" action="<?php echo e(route('admin.notes.status', $note->id)); ?>" method="POST" class="d-none">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
            </form>
        </td>
        <td>
            <a href="<?php echo e(route('admin.notes.show', $note->id)); ?>" class="btn btn-sm btn-info">
                <i class="fa fa-eye"></i>
            </a>
            <a href="<?php echo e(route('admin.notes.edit', $note->id)); ?>" class="btn btn-sm btn-primary">
                <i class="fa fa-edit"></i>
            </a>
            <a href="<?php echo e(route('admin.notes.destroy', $note->id)); ?>"
               class="btn btn-sm btn-danger"
               onclick="event.preventDefault();
                        if (confirm('Delete this note and all its sections?')) {
                            document.getElementById('delete-form-<?php echo e($note->id); ?>').submit();
                        }">
                <i class="fa fa-trash"></i>
            </a>
            <form id="delete-form-<?php echo e($note->id); ?>" action="<?php echo e(route('admin.notes.destroy', $note->id)); ?>" method="POST" class="d-none">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
            </form>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="8" class="text-center">No notes found for this course.</td>
    </tr>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/note_rows.blade.php ENDPATH**/ ?>