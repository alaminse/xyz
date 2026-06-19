<?php $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($index + 1); ?></td>
        <td><?php echo e($note->title); ?></td>
        <td><?php echo e($note->getCourseName()); ?></td>
        <td><?php echo e($note->getChapterName()); ?></td>
        <td>
            <a href="#" class="btn btn-<?php echo e(\App\Enums\IsPaid::from($note->isPaid)->message()); ?> btn-sm">
                <?php echo e(\Illuminate\Support\Str::limit(\App\Enums\IsPaid::from($note->isPaid)->title(), 100, '....')); ?>

            </a>
        </td>

        <td>
            <a href="<?php echo e(route('admin.notes.status', $note->id)); ?>" class="btn btn-<?php echo e(\App\Enums\Status::from($note->status)->message()); ?> btn-sm">
                <?php echo e(\App\Enums\Status::from($note->status)->title()); ?>

            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.notes.show', $note->id)); ?>"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.notes.edit', $note->id)); ?>"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger <?php echo e($note->status == 3 ? 'disabled' : ''); ?> btn-sm" onclick="showDeleteConfirmation(event)" href="<?php echo e(route('admin.notes.destroy', $note->id)); ?>">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/note_rows.blade.php ENDPATH**/ ?>