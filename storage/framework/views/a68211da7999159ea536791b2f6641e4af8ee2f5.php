<?php $__currentLoopData = $mocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $mock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($index + 1); ?></td>
        <td><?php echo e($mock->getCourseNames()); ?></td>
        <td><?php echo e($mock->getChapterName()); ?></td>
        <td>
            <a href="#" class="btn btn-<?php echo e(\App\Enums\IsPaid::from($mock->isPaid)->message()); ?> btn-sm">
                <?php echo e(\Illuminate\Support\Str::limit(\App\Enums\IsPaid::from($mock->isPaid)->title(), 100, '....')); ?>

            </a>
        </td>
        <td>
            <a href="<?php echo e(route('admin.mockvivas.status', $mock->id)); ?>" class="btn btn-<?php echo e(\App\Enums\Status::from($mock->status)->message()); ?> btn-sm">
                <?php echo e(\App\Enums\Status::from($mock->status)->title()); ?>

            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.mockvivas.show', $mock->id)); ?>"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('admin.mockvivas.edit', $mock->id)); ?>"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger <?php echo e($mock->status == 3 ? 'disabled' : ''); ?> btn-sm" onclick="showDeleteConfirmation(event)" href="<?php echo e(route('admin.mockvivas.destroy', $mock->id)); ?>">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/backend/includes/mockviva_row.blade.php ENDPATH**/ ?>